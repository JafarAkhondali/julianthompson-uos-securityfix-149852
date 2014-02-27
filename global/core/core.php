<?php

include "core/const.php";

register_shutdown_function('universe_shutdown');
//set_error_handler('handleError');


function __autoload($classname='') {
  $classpath = getclassfile($classname);
  if (file_exists($classpath)) {
    require_once $classpath;     
  } else {
    die('__autoload : missing class file - ' . $classname . '(' .$classpath . ')');
  }
}

function newentity($constructor) {
	// To do (Do we want this)
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
	return $uos->config->types['node'];
}


function get_instance_id($entity) {
	static $instances = array();
		

	$id = gettype($entity);	
	
	if (!isset($instances[$id])) {
		$instances[$id] = 0;
	}
	$instances[$id]++;
	return $id . '_' . $instances[$id];
}


function render($entity, $format='html') {
  
  $classes = entity_class_tree($entity,TRUE);
  
  //print_r($classes);
  
  $entitytype = $classes[0];
  
  $callerinfo = getcallerinfo();
  
  $instanceid = get_instance_id($entity);
  
  //trace($preprocessfile);

  $preprocessfile = find_display_file($entity, 'process', 'php');
  //die($preprocessfiles);
  $preprocessed = array();
  $templatefile = find_display_file($entity, 'template', $format.'.php');

  //need to cater for multiple preprocess from node down
  if ($preprocessfile) {
	  try {
	    //trace('Including preprocess file - '.$preprocessfile);
	    include $preprocessfile;
	  } catch (Exception $e) {
	    trace('Caught exception: '.  $e->getMessage());
	  }  
	  $preprocessed[] = $preprocessfile;
  }
  
  


  if ($templatefile) {
	  //trace('Including template file - '.$templatefile);
	  ob_start();
	  try {
	    include $templatefile;
	  	$content = ob_get_contents();
	  } catch (Exception $e) {
	    trace('Caught exception: '.  $e->getMessage());
	  }
	  ob_end_clean();

  } else {
  	$content = '<div class="error">entity render error</div>';
  }

  return $content;
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
    $path = UOS_DISPLAYS . implode('/',$classtree) . '/';
    $class = array_pop($classtree);
    $file = $path . $filetype . '.' . $class . $extension; 
    //$paths[] = $file;
    //trace($file);
    if (file_exists($file)) {
      return $file;
    }
  } 
  
  // default template
  return UOS_DISPLAYS . $filetype . $extension;
}

/*old functions */


function addoutput($path,$content=FALSE,$attributes=array()) {
  global $uos;
  
  if (empty($content)) return;
  
  $output = &$uos->output->content;
  $outputpath = outputarraypath($path);
   
  @eval ( $outputpath . '= $content;' );
}


function outputarraypath($path) {
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


function trace($message, $tags=NULL) {
  static $starttime = NULL;
  global $uos;

  if (is_null($starttime)) {
    $starttime = microtime();
    $time = 0;
  } else {
    $time = microtime()-$starttime;
  }
  
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
  if ($uos->logtoscreen) {
  	print 'xxx';
  	print_r($logitem);
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

  global $__universe_input, $__universe_output;

  $error = error_get_last();
  if ($error) {
  	addoutput('content/',$error);
  } 
  
  //print render($__universe_output->);
  
}

function handleError($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
		trace($errstr);
    //throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
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



