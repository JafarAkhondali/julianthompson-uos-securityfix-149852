<?php

include_once "core/globals.php";

register_shutdown_function('universe_shutdown');
set_error_handler('handleError');


function __autoload($classname='') {
  $classpath = getclassfile($classname);
  if (file_exists($classpath)) {
    require_once $classpath;     
  } else {
    print_r(debug_backtrace());
    die('__autoload : missing class file - ' . $classname . '(' .$classpath . ')');
  }
}

function fetch($target) {
	global $uos;

	fetchentitychildren($target);
	//print_r($target);die();
	addoutput('body/', $target);
}

function fetchentity($guid) {
	global $uos;

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
	return $uos->instances[$guid];
}

function fetchentitychildren(&$entity) {
	global $uos;
	
	if ($entity->type->value == 'node_universe') {
		foreach($uos->config->data->entities as $guid=>$propertyobject) {
			$entity->children[] = fetchentity($guid);
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


// Input : Path - Output : $path + '/' + $pathext + '/'
function addtopath($path,$pathext) {
	return $path . $pathext . '/';
}


function render($entity, object $renderoverride=NULL) {
	global $uos;
	
	$content = '';
	
	//throw new Exception('Division by zero.');

	$render = new stdClass();//$uos->render;

	//$classtree = class_tree($entity);
  
  if (empty($entity)) return '';
  
  //if ($uos->render->depth>4) return 'Too Deep';
	
	 
	$render->activerenderer = UOS_DEFAULT_DISPLAY;
	
	$render->format = $uos->request->outputformat;
  	
	$render->activerendererpath = addtopath(UOS_DISPLAYS,$render->activerenderer);

	$render->filesearchpaths = find_element_paths($render->activerendererpath, $entity, TRUE);
	
	$format = $render->format;
		
	$render->preprocessfile = find_element_file($render->filesearchpaths, "preprocess.${format}.php");
		
	$render->templatefile = find_element_file($render->filesearchpaths, "template.${format}.php");
		
	$render->wrapperfile = find_element_file($render->filesearchpaths, "wrapper.${format}.php");  
	
	$render->classes = class_tree($entity);
		
	$render->classtree = $render->classes;
		
	$render->classtreestring = 'uos-element '.implode(' ', array_reverse($render->classes));
	
	$render->inheritancestring = implode(',', $render->classes);
		
	$render->entitytype = $render->classes[0];
		
	//$render->callerinfo = getcallerinfo();
		
	$render->typeinfo = get_type_data($render->classes[0]);
		
	$render->instanceid = get_instance_id($render->classes[0]);
	
	$render->elementid = $render->instanceid. '__' . round((time() + microtime(true)) * 1000);
		
	$render->childcount = 0;
	
	//if we use this autoload is called which crashes the system for string types
	//$render->isuosentity = is_universe_entity($entity);
		
	$render->displaymode = 'default';
		
	$render->renderindex = $uos->render->index;
		
	$render->renderdepth = $uos->render->depth;
	
	$render->wrapperelement = 'div';
	
	$render->preprocessed = array();
	  
  if (is_subclass_of($render,'node')) { 
  	$render->title = $entity->title->value;
  } else {
  	$render->title = ucfirst($render->classes[0]);
  }
  
	$render->attributes = array(
		'id' => $render->elementid,
		'class' => $render->classtreestring,
		'data-uostypetree' => $render->inheritancestring,
		'data-uostype' => $render->entitytype,
		'data-accept' => '',
		'draggable' => 'false',
		'title' => $render->title,
		'data-display' => $render->displaymode
	);
  
  $uos->render->index++;
  
  $uos->render->depth++;
  
  //$render->elementtitle = is_set($entity->title)?$entity->title->value:$render->classes[0];
  
  //array_push($uos->render->renderpath,$classes[0]);

  //extract($rendersettings,EXTR_OVERWRITE);
  
  //trace($preprocessfile);
  $preprocessed = array();

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
  
  //print_r($render);die();
  
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
		  	$content = "<!-- start '.$render->wrapperfile.' -->\n".ob_get_contents()."<!-- end '.$render->wrapperfile.' -->\n";
		  } catch (Exception $e) {
		    trace('Caught exception: '.  $e->getMessage());
		  }
		  ob_end_clean();  	
	  }	  

  } else {
  	$content = '<div class="error">entity render error</div>';
  }
  unset($render->renderpath);
  unset($render->filesearchpaths);
  addoutput('resources/json/'.$render->elementid, (object) $render);
  //$content .= print_r($rendersettings,TRUE);
  $uos->render->depth--; 
  return $content;
}


function startrender() {
	global $uos;
	
	// create a new render object
	$uos->render = new StdClass();
	
	// assign to render for convenience 
	$render = $uos->render;

	$render->activerenderer = UOS_DEFAULT_DISPLAY;	
	$render->rendererurl = addtopath(UOS_DISPLAYS_URL, $render->activerenderer); 
	$render->rendererpath = addtopath(UOS_DISPLAYS, $render->activerenderer);
	$render->index = 0;
	$render->depth = 0;
	$render->renderpath = array();
	$render->format = $uos->request->outputformat;
	$format = $render->format;
	
	include $render->rendererpath . "display.uos.php";
	include $render->rendererpath . "preprocess.".$format.".php";
	include $render->rendererpath . "template.page.".$format.".php";

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
  $logitem->source = $source['file'];
  $logitem->line = $source['line'];  
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
	
	$files = find_files(UOS_LIBRARIES. $librarystr . '/','.*\.uos.php', FALSE);
	if (!empty($files)) {
		foreach($files as $file) {
			include $file;
		}	
		return TRUE;
	}
	return FALSE;
}





