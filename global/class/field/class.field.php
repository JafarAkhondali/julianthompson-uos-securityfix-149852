<?php
# field class definition file
class field extends entity {
	public $value = null;  
	public $parent = null;
	public $key = null;
	public $guid = null;
	public $title = '';
	public $locked = FALSE;
	public $required = FALSE;
	public $indexfield = FALSE;
	public $visible = TRUE;
	public $usereditable = TRUE;
	public $displayname = null;
	public $defaultvalue = null;
	public $masked = FALSE;
	public $modified = FALSE;
	public $unique = FALSE;
	
	
	function __construct($initializer=null) {

	//	$this->callaction('build');
		$this->initialize($initializer);
  }
  
  function initialize($initializer = null) {
		$initializer = format_initializer($initializer);
		
		foreach($initializer as $fieldname=>$fieldvalue) {
			//if (isset($this->fieldname)) {
			if (property_exists($this,$fieldname)) {
				$this->{$fieldname} = $fieldvalue;
			}			
			//if ($this->propertyexists($fieldname)) {
			//	$this->properties[$fieldname]->value = $fieldvalue;
			//}
		}  
		
		if (!isset($initializer['value'])) {
			$this->setvalue($this->defaultvalue,FALSE);
		} 
  }
  
  function isvalid() {
  	if ($this->required && !$this->isvalueset()) return FALSE;
  	return TRUE;
  }
  
  function reset() {
  	$this->value = $this->defaultvalue;
  	$this->modified = FALSE;
  }
  
  function isvalueset() {
  	return !is_null($this->value);
  }
  
  function ismodified() {
  	return $this->modified;
  }
  
  function isstored() {
  	return $this->stored;
  }
  
  function getdbfieldtype() {
  	return "text";
  }
  
  function getdbfieldvalue() {
  	return $this->value;
  }
  
  public function __toString() {
    return is_object($this->value) ? print_r($this->value,TRUE) : (string) $this->value;
  }
  
  public function cachepath() {
  	return parent::cachepath() . $key . '/';
  }
  

  protected function addproperty($propertyname,$fieldtypename,$parameters=array()) {

    if (!$this->propertyexists($propertyname)) {

      trace('addproperty : '. $propertyname . '(' . $fieldtypename . ') - ' . $this->scope);
      try {
        $field = new stdClass();
        if (is_subclass_of($fieldtypename,'field')) {
          $field->parent = $this;
          $field->scope = $this->scope;
          $field->type = $fieldtypename;
          $field->parameters = $parameters;
          //$this->trace('Locked : '.$this->islocked());
          //$field->setlock($this->islocked());
          $field->key = $propertyname;
          //trace($field);
          //trace('addproperty');
          $this->properties[$propertyname] = $field;
          //return $field;
        }      
      } catch(Exception $e) {
        trace('Cannot addproperty '.$fieldtypename . print_r($e,TRUE));
      }
    } 
    return FALSE;
  }
  
  public function __gettabledefinition() {
    $tables = array();
    $properties = $this->getproperties();
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
  
  /*
  protected function __getproperty($propertyname) {
    if ($this->propertyexists($propertyname)) {
    	// if just placeholder temporary object - make it into an entity 
    	if (!is_universe_entity($this->properties[$propertyname])) {
    		$settings = $this->properties[$propertyname];
    		$entitytype = $settings->type;
    		$entity = new $entitytype($settings->parameters);
    		return $entity;
    	}
    }
    return NULL;
  }
  */

  protected function __instanceproperty($propertyname) {
    if ($this->propertyexists($propertyname)) {
    	// if just placeholder temporary object - make it into an entity 
    	if (!is_universe_entity($this->properties[$propertyname])) {
    		$settings = $this->properties[$propertyname];
    		$entitytype = $settings->type;
    		$entity = new $entitytype($settings->parameters);
    		$entity->scope = $settings->scope;
    		$entity->parent = $settings->parent;
     		$entity->key = $settings->key;
     		$entity->value = &$settings->value;
    		return $entity;
    	}
    }
    return NULL;
  }
  

  
  public function getproperties() {
  	$properties = array();
    foreach ($this->properties as $key=>$property) {
    	//$properties[$key] = $this->$key;
    	$fieldtypename = $property->type;
      $field = new $fieldtypename($property->parameters);
      $field->scope = $property->scope;
    	$properties[$key] = $field;
    	
    }
    return $properties;
  }
  
  public function __set($propertyname,$value) {
    return $this->value = $value;
  }
  
  public function setvalue($value, $setmodified = TRUE) {
  	$this->value = $value;
  	if ($setmodified) {
  		$this->modified = TRUE;
  		if ($this->parent) $this->parent->event_propertymodified($this);
  	}
  }
  
	function getindexproperty() {
		//print_r($this->__instanceproperty($this->indexproperty->key));die();
		return (isset($this->properties[$this->indexproperty])) ? $this->__instanceproperty($this->indexproperty->key) : NULL;
		//return $this->indexproperty;
	}
	
	public function __clone() {
		if ($this->unique) {
			$this->value = 'cloned value';
		}
	}
  
} 