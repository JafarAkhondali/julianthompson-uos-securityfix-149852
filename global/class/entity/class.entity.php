<?php
//abstract 
class entity {
	
	public	 	$attributes = array();
	public		$properties = array();
	
	//public	$__log = array();
	public		$displaymode = '';
	private		$parent = null;
	public		$children = array();
	public		$actions = null;
	public 		$scope = null;
	
	function __construct($initializer=null) {

		$this->callaction('build');
		// call build object here - create fields
		
		$initializer = format_initializer($initializer);
		//print_r($initializer);
		//populate fields 
		
		//$this->properties['type'] = new field();
		//$this->properties['type']->value = get_class($this);
		
		foreach($initializer as $fieldname=>$fieldvalue) {
			//$field_initializer = array(
				//'parent' => $this,
				//'value' => $fieldvalue,
				//'key' => $fieldname
			//);
			try {
		    if ($this->propertyexists($fieldname)) {
		      //$this->trace('__setpropertyvalue ('.$propertyname.') : '.$value);
		      $this->properties[$fieldname]->value = $fieldvalue;
		    } else {
		    	$this->addproperty($fieldname,'field',array(
		    		'value' => $fieldvalue
		    	));
		    }
			} catch (Exception $e) {
				trace('exception!');
			}
			//$this->properties[$fieldname]->value = $fieldvalue;
		}
  }
  
  function propertyexists($propertyname) {
     return isset($this->properties[$propertyname]);
  }
  
  public function __set($propertyname,$value) {
    return $this->__setpropertyvalue($propertyname, $value);
  }
  
  protected function __getproperty($propertyname) {
    return ($this->propertyexists($propertyname))?$this->properties[$propertyname]:false;
  }
  

  protected function __setpropertyvalue($propertyname,$value) {
    if ($this->propertyexists($propertyname)) {
      //$this->trace('__setpropertyvalue ('.$propertyname.') : '.$value);
      return $this->properties[$propertyname]->value = $value;
    }
    throw new Exception('Unknown property type : '.$propertyname);
    return false;
  }


  public function __get($propertyname) {
    return $this->__getproperty($propertyname); 
  }
  
  function trace($message,$tags='') {
    /*
  	$this->__log[] = (object) array(
  		'message' => $message,
  		'tags' => explode(',',$tags)
  	);
  	*/
  	//print_r($message);
  }
/*
  
  final protected function addaction($methodname) {
    $parentclasses = $this->getparentclasses();
    //$this->trace($parentclasses);
    $args=func_get_args();
    array_shift($args);
    $output = array();
    //trace('----------','jmt');
    //trace(get_class($this),'jmt');
    //trace($parentclasses,'jmt');
    foreach($parentclasses as $scope) {
      $scopefunction = $scope.'_'.$methodname;
      //trace('looking for : '.$scopefunction,'jmt');
      //$this->trace('looking for : '.$scopefunction);
      if (method_exists($scope,$scopefunction)) {
        //$this->trace('calling : '.$scopefunction);
        $output[$scope] = call_user_func_array(array($this,$scopefunction),$args);
        //$output[$scope] = $this->$scopefunction($args);
      }
    }
    //trace(array_keys($this->__properties),'jmt');
    //$this->trace($this);
    return $output;
  }

  public function actiontopath($action) {
    trace('actiontopath','dev');
    //trace('class'.get_class($this),'dev');
    $paths = ($this->classpath('.actions'));
    trace($paths,'dev');
    foreach ($paths as $path) {
      $actionfiles = file_list($path, 'action\.'.$action.'\.php');
      if (!empty($actionfiles)) return $path.$actionfiles[0];
    }
    return NULL;
    //return getfirstfile($paths);
  }  
  
*/  
  
  final public function classpathtree($pathsuffix) {
  	$paths = array();
  	$classes = entity_class_tree($this,TRUE);
  	foreach($classes as $class) {
  		$paths[$class] = classtopath($class) . 'actions.' . $class . '/';
  	}
  	return $paths;
  }
  

  
  public function getactions() {
  
  	global $uos;
	$entityclass = get_class($this);
  	trace('looking for actions for : '.$entityclass,'jmt');
  	if (is_null($this->actions)) {		
		trace('finding actions for : '.$entityclass,'jmt');
  		if (!isset($uos->config->types[$entityclass])) {
  			$uos->config->types[$entityclass] = new StdClass();  
  		}
		
		if (!isset($uos->config->types[$entityclass]->actions)) {
		
			$actions = Array();
			$classes = entity_class_tree($this,TRUE);
			
			//trace($classes);
			foreach($classes as $class) {
				$path = classtopath($class) . '_actions/';
				trace('found action : '.$path,'jmt');
				$actionfiles = file_list($path, 'action\..*\.php');
				foreach ($actionfiles as $actionfile) {
					$actionname = substr($actionfile,7,-4);
					$actions[$actionname][$class] = $path.$actionfile;
				}
			}
			$uos->config->types[$entityclass]->actions = $actions;
		}
	    $this->actions = &$uos->config->types[$entityclass]->actions;
	}
    return $this->actions;
  }
  
  
  public function getproperties() {
    return $this->properties;  
  }
  
  
  protected function addproperty($propertyname,$fieldtypename,$parameters=array()) {

    if (!$this->propertyexists($propertyname)) {

      trace('addproperty : '. $propertyname . '(' . $fieldtypename . ') - ' . $this->scope);
      try {
        $field = new $fieldtypename($parameters);
        //if (is_subclass_of($field,'field')) {
          $field->parent = $this;
          $field->scope = $this->scope;
          //$this->trace('Locked : '.$this->islocked());
          //$field->setlock($this->islocked());
          $field->key = $propertyname;
          //trace($field);
          //trace('addproperty');
          $this->properties[$propertyname] = $field;
          //return $field;
        //}      
      } catch(Exception $e) {
        trace('Cannot addproperty '.$fieldtypename . print_r($e,TRUE));
      }
    } 
    return FALSE;
  }
  
  public function callaction($action,$parameters=NULL) {
  	global $uos;
  	$result = NULL;
		//if (get_class($this)=='node_person') $uos->config->logging = TRUE;
    $this->getactions();
    //print_r($this->actions[$action]);
    //print_r(gettype($this));
		trace(get_class($this),'jmt');
		//trace($this->title,'jmt');
		trace(empty($this->actions)?'EMPTYACTONS':'','jmt');
    if (isset($this->actions[$action])) {
    
      //$response->found = TRUE;
      //trace("this->fireevent(".$action.','.print_r($parameters,TRUE). "," . UNIVERSE_EVENT_POST . ")");
      //$this->fireevent(UNIVERSE_EVENT_POST,$action,$parameters);
      //extract($parameters);
      //return;//
      

    	$response = new StdClass();
    	$classtree = entity_class_tree($this,TRUE);
    	//trace($classtree);
    	//trace($this->actions[$action]);
    	//die();
    	
      //ob_start();    	
    	foreach($this->actions[$action] as $classname => $actionfile) {
    		$this->scope = $classname;
      	$response->actionfiles[] = $actionfile; 
      	trace('callaction  : '.$actionfile.' ('.$this->scope.')');  
      	if (file_exists($actionfile)) {
		      try {
		        //$result = @include $__actionfile;
		        @include $actionfile;
		      } catch (Exception $e) {
		      	die('failed includes');
		        //$result = 'error';//$e;
		      }
	      }
      }

      //addoutput('output',$result);
      //addoutput('dump/',  ob_get_contents() );      
      //$response->output = ob_get_contents();
      //trace($harry);
      //ob_end_clean();
      trace('processed action file');      
      //$parameters['actionresult'] = $result;
      //$this->fireevent($action,$parameters,UNIVERSE_EVENT_POST);

    } else {
      //$response->error = TRUE;
      return UOS_ERROR_NOT_FOUND;
    }
	//$uos->config->logging = FALSE;
    
    return $result;
  }
  
  public function trigger($action,$parameters=NULL) {
		return $this->callaction($action,$parameters);
	}

 
  protected function removeproperty($propertyname) {
    if ($this->propertyexists($propertyname)) {
    	unset($this->__properties[$propertyname]);
    }  	
  }

  public function filterproperties($propertylist) {
  	$propertylist = is_array($propertylist)?$propertylist:func_get_args();
  	foreach($this->__properties as $key=>$property) {
  	  if (!in_array($key,$propertylist)) {
  	    $this->removeproperty($key);
  	  }
  	}
  }
  
  public function __gettabledefinition() {
    $tables = array();
    foreach($this->properties as $key=>$property) {
      //if (!isset($tables[($property->scope)]) && ($property->parentclass!=='node')) {
      //  $tables[($property->scope)]['node_id'] = $this->__properties['id']->getdbfieldcreate('node'); 
      //}
      $data = new StdClass();
      $data->value = $property->value;
      $data->index = $property->indexfield;
      $data->dbfieldtype = $property->getdbfieldtype();
      $tables[($property->scope)][$key] = $data;//$property->getdbfieldcreate();//getdbtype();
    }
    return ($tables);
  }
  
  public function __toString() {
    return (string) $this->type . '(' . (string) $this->guid . ')';
  }
  
}		
