<?php

include_once "core/globals.php";

//register_shutdown_function('universe_shutdown');
//set_error_handler('handleError');


function __autoload($classname='') {
  $classpath = getclassfile($classname);
  if (file_exists($classpath)) {
    require_once $classpath;     
  } else {
    print_r(debug_backtrace());
    die('__autoload : missing class file - ' . $classname . '(' .$classpath . ')');
  }
}
/*
function fetch($target) {
	global $uos;

	//fetchentitychildren($target);
	//print_r($target);die();
	return $target;
}
*/
function fetchentity($guid) {
	global $uos;

	$field = null;
	$guidexploded = explode(':',$guid);
	if (isset($guidexploded[1])) @list($guid,$field) = $guidexploded;

	if (!isset($uos->instances[$guid])) {
		//pretend to load from db
		if (isset($uos->config->data->entities[$guid])) {
			$propertyobject = $uos->config->data->entities[$guid];
			$type = isset($propertyobject->type)?$propertyobject->type:'StdClass';
			$entity = new $type($propertyobject);	
			//fetchentitychildren($entity);
			$uos->instances[$guid] = $entity;
		}
	} else {
		$entity = $uos->instances[$guid];
	}
	if ($entity) {
		if ($field) {
			return $uos->instances[$guid]->properties[$field];
		} else {
			return $uos->instances[$guid];
		}
	}
	return NULL;
}

function fetchentitychildren(&$entity) {
	global $uos;
	
	if ($entity->type->value == 'node_universe') {
		foreach($uos->config->data->entities as $guid=>$propertyobject) {
			if ($guid != $entity->guid->value) {
				$entity->children[] = fetchentity($guid);
				//print_r($guid);
			}
		}	
	} else {
		$entityguid = $entity->guid->value;
		if (isset($uos->config->data->children[$entityguid])) {
			$childguids = $uos->config->data->children[$entityguid];
			foreach($childguids as $childguid) {
				$entity->children[] = fetchentity($childguid);
			}
		}
	}
}


function getclassfile($class='',$prefix = 'class') {
  if ($class == UOS_BASE_CLASS) {
    return UOS_CLASSES . '/' . UOS_BASE_CLASS . '/' . $prefix . '.' . UOS_BASE_CLASS . '.php';
  } else {
  	return classtopath($class) . $prefix . '.' . trim($class) . '.php';
  }
}


function classtopath($class) {
    $explodedpath = explode('_',$class);
	  $path = array();
	  $pathbuilt = '';
	  foreach($explodedpath as $pathelement) {  
	     array_push($path, $pathbuilt . $pathelement) ; 
	     $pathbuilt .= $pathelement . '_';
	  }
	  $class = trim(implode('_',$explodedpath));
	  return UOS_CLASSES . trim(implode('/',$path)). '/';	
}


function entity_class_tree($entity,$reverse=FALSE) {

  if ((gettype($entity)=='object') && is_universe_entity($entity)) {
  
  	$currentclass = get_class($entity);
  	$classarray = array();    
    do {
      $classarray[] = $currentclass;
    } while($currentclass = get_parent_class($currentclass));
    return ($reverse) ? array_reverse($classarray) : $classarray;
	}
	
	return array(gettype($entity));
}

function is_universe_entity($entity) {
	return is_subclass_of($entity, UOS_BASE_CLASS);
}

/*
    $explodedpath = explode('_',$class);
*/

function format_initializer($initializer) {	

	if (is_null($initializer)) return array();

	if (!is_array($initializer)) {
	
		if (is_object($initializer)) return (array) $initializer;
	
		if (is_numeric($initializer)) {
			return array('id'=>$initializer);
						
		} elseif ($matches = format_match('^G[1-9][0-9]{9}$',$initializer)) {
			return array('guid'=>$matches[0]);
			
		//} elseif ($matches = format_match('John.*',$initializer)) {
		//	$intializer = array('johnid'=>$matches[1]);
	  }
	}
	return $initializer;
}


function format_match($search,$text) {
	preg_match('/'.$search.'/', $text, $matches);
	return $matches;
}


function get_type_data($machinename) {
	global $uos;

	if (isset($uos->config->types[$machinename])) {
		return $uos->config->types[$machinename];
	}
	return $uos->config->types['unknown'];
}


function get_instance_id($id) {
	static $instances = array();
		
	if (!isset($instances[$id])) {
		$instances[$id] = 0;
	}
	$instances[$id]++;
	return 'uos_' . $id . '__' . $instances[$id];
}

//function render_as($entity, )

function class_tree($entity,$reverse=FALSE) {
	$tree = array();
	if (is_object($entity)) {
  	$currentclass = get_class($entity); 
    do {
      $tree[] = strtolower($currentclass);
    } while($currentclass = get_parent_class($currentclass));		
    //$tree[] = 'object';
	} else {
		$tree[] = gettype($entity);	
	}
	return ($reverse) ? array_reverse($tree) : $tree;
}

function class_tree_new($entity,$reverse=FALSE) {
	$tree = array();
	if (is_object($entity)) {
  	$currentclass = get_class($entity); 
    do {
      $tree[] = strtolower($currentclass);
    } while($currentclass = get_parent_class($currentclass));		
    //$tree[] = 'object';
	} else {
		$tree[] = gettype($entity);	
	}
	return ($reverse) ? array_reverse($tree) : $tree;
}


// Input : Path - Output : $path + '/' + $pathext + '/'
function addtopath($path,$pathext) {
	return $path . $pathext . '/';
}


function render($entity, object $rendermask=NULL) {
	global $uos;

	$content = '';
	
	//throw new Exception('Division by zero.');

	//$render = new stdClass();//$uos->render;
	if (isset($uos->activerenderbranch)) {
		$parentrenderbranch = $uos->activerenderbranch;
  	$render = clone $parentrenderbranch;
  	$render->children = array(); 
  	$render->elementdepth++;
  	$render->elementindex = count($parentrenderbranch->children);
  	$uos->activerender->children[] = $render;
  	$uos->activerender = $render;
		$render->filesearchpaths = find_element_paths($render->rendererpath, $entity, TRUE); 
  } else {
  	// we are starting a new render tree
  	$parentrenderbranch = NULL;
  	$render = new StdClass();
		$render->activerenderer = UOS_DEFAULT_DISPLAY;	
		$render->rendererurl = addtopath(UOS_DISPLAYS_URL, $render->activerenderer); 
		$render->rendererpath = addtopath(UOS_DISPLAYS, $render->activerenderer);
		$render->elementindex = 0;
		$render->depth = 0;
		$render->renderpath = array();
		$render->format = $uos->request->outputformat->format;
		$render->display = $uos->request->outputformat->display;
	  $render->children = array(); 
	  $render->maxdepth = 4;
		$render->filesearchpaths = array($render->rendererpath); 
		$render->stop = FALSE;
		//$uos->rendertrees[] = new StdClass();
		$uos->activerenderbranch= $render;
		include_once $render->rendererpath . "include.uos.php";
  }
  
  //$render->buffering = FALSE;
  
	if ($render->display=='default') {
		$render->formatdisplay = $render->format;
	} else {
		$render->formatdisplay = implode('.',array((string) $render->display, (string)$render->format));
	}

  // this should wrap the whole thing
  if (empty($entity) || ($render->maxdepth && ($render->depth>=$render->maxdepth)) ) {
  	$uos->activerender = $parentrenderbranch;
  	return '';
  }
	
	$render->classtree = class_tree($entity);
	$render->entitytype = $render->classtree[0]; 
	$render->instanceid = get_instance_id($render->entitytype). '__' . round((time() + microtime(true)) * 1000);		

  if (is_subclass_of($entity,'entity') && (isset($entity->title->value))) { 
  	$render->title = $entity->title->value;
  } else {
  	$render->title = ucfirst($render->entitytype);
  }

  
  // apply rendermask here?
  if ($rendermask) {
  	// object merge / step through array?
  	$render->masked = TRUE;
  }  

	//$render->callerinfo = getcallerinfo();	
	//if we use this autoload is called which crashes the system for string types
	//$render->isuosentity = is_universe_entity($entity);
	
	$render->preprocessed = array();

	$render->preprocessall = find_element_file($render->filesearchpaths, "preprocess.php");
	
	$render->preprocessfile = find_element_file($render->filesearchpaths, "preprocess.$render->formatdisplay.php");
		
	$render->templatefile = find_element_file($render->filesearchpaths, "template.$render->formatdisplay.php");
		
	$render->wrapperfile = find_element_file($render->filesearchpaths, "wrapper.$render->formatdisplay.php");  

  
  // START - should only be set in preprocess functions that need it
  
	$render->childcount = NULL;

	$render->wrapperelement = 'div';

	$render->typeinfo = get_type_data($render->entitytype);

	$render->classtreestring = 'uos-element uos-uninitialized '.implode(' ', array_reverse($render->classtree));
	
	$render->inheritancestring = implode(',', $render->classtree);
 
	$render->attributes = array(
		'id' => $render->instanceid,
		'class' => $render->classtreestring
	);
	
	$render->elementdata = new stdClass();	
	$render->elementdata->typetree = $render->classtree;
	$render->elementdata->type = $render->entitytype;
	$render->elementdata->typeinfo = $render->typeinfo;
	$render->elementdata->display = $render->formatdisplay;
	

  //extract($rendersettings,EXTR_OVERWRITE);

  //need to cater for multiple preprocess from node down
  if ($render->preprocessfile) {
	  try {
	    //trace('Including preprocess file - '.$preprocessfile);
	    include $render->preprocessfile;
	  } catch (Exception $e) {
	    trace('Caught exception: '.  $e->getMessage());
	  }  
	  $render->preprocessed[] = $render->preprocessfile;
  }
    
  addoutput('elementdata/'.$render->instanceid, $render->elementdata);
  
  if ($render->stop) {
		return print_r($render,TRUE);  	  	
  }
    
  if ($render->templatefile) {
	  //trace('Including template file - '.$templatefile);
	  ob_start();
	  try {
	    include $render->templatefile;
	  	$content = ob_get_contents();
	  } catch (Exception $e) {
	    trace('Caught exception: '.  $e->getMessage());
	  }
	  ob_end_clean();
	  //print_r($content);die();
	  if ($render->wrapperfile) {
		  ob_start();
		  try {
		    include $render->wrapperfile;
		  	$content = ob_get_contents();
		  	//$content = "<!-- start '.$render->wrapperfile.' -->\n".ob_get_contents()."<!-- end '.$render->wrapperfile.' -->\n";
		  } catch (Exception $e) {
		    trace('Caught exception: '.  $e->getMessage());
		  }
		  ob_end_clean();  	
	  }	  

  } else {
  	$content = '<div class="error">';
  	$content .= implode(PHP_EOL,find_all_element_paths($render->filesearchpaths, "template.$render->formatdisplay.php"));
  	$content .= '</div>';
  }
  //unset($render->renderpath);
  //unset($render->filesearchpaths);
  //$content .= print_r($rendersettings,TRUE);
  //$uos->render->depth--; 
  if ($parentrenderbranch) {
  	$uos->activerender = $parentrenderbranch;
  }
  return $content;
}


function startrender() {
	global $uos;
	$entity = $uos->output['content'];		
	return render($entity);
}

function find_display_file($entity, $filetype='template', $extension='html.php') {
	
	$classtree = entity_class_tree($entity,TRUE);
	
	$displaymode = '';//-feature';//'.test.';$displaymode = $entity->displaymode;
	
	$extension = $displaymode . '.' .$extension;
	
  $paths = array();

  // add class templates based on class tree
  // node_device_light
  // node_device
  // node
  
  while(!empty($classtree)) {
    $path = UOS_DEFAULT_DISPLAY . implode('/',$classtree) . '/';
    $class = array_pop($classtree);
    $file = $path . $filetype . '.' . $class . $extension; 
    //$paths[] = $file;
    //trace($file);
    if (file_exists($file)) {
      return $file;
    }
  } 
  
  // default template
  return UOS_DEFAULT_DISPLAY . $filetype . $extension;
}

function find_element_paths($rootpath,$entity,$reverse=FALSE) {
	$paths = array();
	$currentpath = $rootpath.'elements/';
	array_push($paths, $currentpath);
	$classtree = class_tree($entity,TRUE);
	foreach($classtree as $leaf) { 
		$currentpath .= $leaf . '/'; 
	  array_push($paths, $currentpath) ; 
	}		
	return ($reverse) ? array_reverse($paths) : $paths;
}

function find_element_file($patharray,$filename) {
  foreach($patharray as $path) {
    $file = $path . $filename;
    if (file_exists($file)) {
      return $file;
    }
  }
  return NULL;
}


// Nearly identical but separate to find_element_paths for speed
function find_all_element_paths($patharray,$filename) {
$paths = array();
  foreach($patharray as $path) {
     $paths[] = $path . $filename;
  }
  return $paths;
}


/*old functions */


function addoutputold($path,$content=FALSE,$attributes=array()) {
  global $uos;
  
  if (empty($content)) return;
  
  $output = &$uos->output->content;
  $outputpath = outputarraypath($path);

  @eval ( $outputpath . '= $content;' );
}

function addoutput($path,$content=FALSE,$attributes=array()) {
  global $uos;
  
  if (empty($content)) return;
  
  $output = &$uos->output;
  $outputpath = outputarraypath($path);
  //print_r($outputpath);die();
  @eval ( $outputpath . '= $content;' );
}


function outputarraypathold($path) {
  if (empty($path)) return '$uos->output->content';
  $path = trim($path);
  $pathexploded = explode('/',$path);
  foreach($pathexploded as &$pathelement) {
    if (!empty($pathelement)) $pathelement = '\''.$pathelement.'\'';
  }
  //$append = (substr($path,-1)=='/')?'[]':'';
  //$path = trim($path,'/');  
  $arraypath = '$uos->output->content[' . implode('][',$pathexploded) . ']';
  return $arraypath;
}

function outputarraypath($path) {
  if (empty($path)) return '$uos->output';
  //if (empty($path)) return '$uos->output';
  $path = trim($path);
  $pathexploded = explode('/',$path);
  foreach($pathexploded as &$pathelement) {
    if (!empty($pathelement)) $pathelement = '\''.$pathelement.'\'';
  }
  //$append = (substr($path,-1)=='/')?'[]':'';
  //$path = trim($path,'/');  
  $arraypath = '$uos->output[' . implode('][',$pathexploded) . ']';
  return $arraypath;
}


function trace($message, $tags=NULL) {
  static $starttime = NULL;
  global $uos;

  if (is_null($starttime)) {
    $starttime = microtime();
    $time = 0;
  } else {
    $time = microtime()-$starttime;
  }
  if (!$uos->config->logging) return;
  
  $logitem = new StdClass();
  
  $logitem->time = round($time,4);
  $logitem->title = 'Log entry';
  $logitem->message = print_r($message,TRUE);
  $logitem->guid = 'unsaved';
  
  $source = getcallerinfo();
  $logitem->function = $source['function'];
  $logitem->source = (isset($source['file']))?$source['file']:'Unknown';
  $logitem->line = (isset($source['line']))?$source['line']:'Unknown';  
  $logitem->tags = ($tags)?array($tags):array();


	//if (class_exists('node_message')) {
	//	$logitem = new node_message((array) $logitem);
	//}
  //$log[] = $logitem;
  //$message = new node((array) $logitem);
  //$index = count($log)-1;
  //print_r($logitem);
  if ($uos->config->logtostdout) {
  	print render($logitem);
  }
  addoutput('log/', $logitem);
}

function getcallerinfo() {
  $backtrace = debug_backtrace();
  $position = 2;
  if (isset($backtrace[$position])) { 
    return $backtrace[$position];
  }
  return FALSE;
}


function universe_shutdown() { 

  global $uos;
  
	$error = error_get_last();
	if (($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)|| ($error['type'] === E_USER_NOTICE)) {
	  //addoutput('content/',$error);
	  echo "ERRORnr : " . $error['type']. " |Msg : ".$error['message']." |File : ".$error['file']. " |Line : " . $error['line'];
	  die();
	} 
  
	if (!isset($uos->shutdown)) {
		$uos->shutdown = TRUE;	
		// compress output if supported
		ob_start();
		try {
			startrender();
		} catch (Exception $e) {
			ob_end_clean();
	    echo('Caught exception: ' .  $e->getMessage() . "\n");
	    die();
		}
		$content = ob_get_contents();
		ob_end_clean();
		ob_start("ob_gzhandler");
		echo $content;
	} else {
		die('Something went wrong');
	}
}

function handleError($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator

    $errormap = Array(
    	E_USER_ERROR => 'user',
    	E_USER_WARNING => 'warning',
    	E_USER_NOTICE => 'notice'
    );
    $tags = array('error');
    $tags[] = isset($errormap[$errno])?$errormap[$errno]:'unknown';
		trace($errstr,$tags);
}


function file_list($path, $expression='.*', $dotfolders=FALSE) {
  $filearray = array();
  $path = rtrim($path,"/");
  if (is_dir($path)) {
    if ($handle = @opendir($path)) {
      while (false !== ($filename = readdir($handle))) {
        if ($dotfolders || ($filename != "." && $filename != "..")) {
          if (preg_match('/'.$expression.'/', $filename)>0) {
            $filearray[] = $filename;
          }
        }
      }
      closedir($handle);
    }
    trace('found path :'.$path);
  } else {
    //trace('Cannot find path :'.$path,'file_list');
  }
  return $filearray;
}


function find_files($path, $regexp, $casesensitive=TRUE) {
	$matchedfiles = Array();
	if (is_dir($path)) {
		$handler = opendir($path);
		$regexp = "/^{$regexp}$/" . ($casesensitive?'':'i');
		while ($file = readdir($handler)) {
		  if ($file !== "." && $file !== "..") {
		    if (preg_match($regexp, $file, $name) > 0) {
		    	$matchedfiles[] = $path.$name[0];
		    	//echo "${regexp} : " . (isset($name[0]) ? $name[0] : 'No match') . "\n\n";
		    }
		  }
		}
		closedir($handler);
	}
	return empty($matchedfiles)?UOS_ERROR_NOT_FOUND:$matchedfiles;
}


function UrlToQueryArray($query) {
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = isset($item[1])?$item[1]:'';
    }
   
    return $params;
}


function useLibrary($librarystr) {
	//how do we deal with sublibraries
	//$explodedstr = explode('/', trim($librarystr,'/'));
	$librarypath = UOS_LIBRARIES. $librarystr .'/';
	$files = find_files($librarypath,'.*\.uos.php', FALSE);
	if (!empty($files)) {
		foreach($files as $file) {
			include $file;
		}	
		return TRUE;
	}
	return FALSE;
}





