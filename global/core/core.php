<?php
error_reporting(0);
ini_set('display_errors', 0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
register_shutdown_function('universe_shutdown');
//set_error_handler('handleError');

include_once "core/const.php";
include_once "core/globals.php";
include_once "displays/default/display.php";

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);





function __autoload($classname='') {
  $classpath = getclassfile($classname);
  if (file_exists($classpath)) {
    require_once $classpath;     
  } else {
    //print_r(debug_backtrace());
    trigger_error('__autoload : missing class file - ' . $classname . '(' .$classpath . ')');
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
	$guidexploded = explode(UOS_GUID_FIELD_SEPARATOR,$guid);
	if (isset($guidexploded[1])) @list($guid,$field) = $guidexploded;

	// if entity not in the instances try to add it
	if (!isset($uos->instances[$guid])) {
		//pretend to load from db
		if (isset($uos->config->data->entities[$guid])) {
			$propertyobject = $uos->config->data->entities[$guid];
			$propertyobject->created = time();
			$type = isset($propertyobject->type)?$propertyobject->type:'StdClass';
			$entity = new $type($propertyobject);	
			//fetchentitychildren($entity);
			$uos->instances[$guid] = $entity;
		}
	} 
	// if now set return entity
	if (isset($uos->instances[$guid])) {
		if ($field) {
			return $uos->instances[$guid]->properties[$field];
		} else {
			return $uos->instances[$guid];
		}
	}
	return null;
}

function fetchentitychildren(&$entity) {
	global $uos;
	if (is_universe_entity($entity)) {	
		if (get_class($entity) == 'node_universe') {
			foreach($uos->config->data->entities as $guid=>$propertyobject) {
				if ($guid != $entity->guid->value) {
					$entity->children[] = fetchentity($guid);
					//print_r($guid);
				}
			}	
		} elseif (is_subclass_of($entity,'field')) {
		
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
	$classname = (is_object($entity))?get_class($entity):(string) $entity;
	return ($classname==UOS_BASE_CLASS) || (is_subclass_of($classname, UOS_BASE_CLASS));
}

function is_creatable_entity($entity) {
	$classname = (is_object($entity))?get_class($entity):(string) $entity;
	return ($classname==UOS_BASE_CLASS) || (is_subclass_of($classname, UOS_BASE_CLASS));
	return TRUE;
}

function is_uos_field($entity) {
	return get_class($entity)=='field' || is_subclass_of($entity, 'field');
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

function find_displays(&$render) {

	$displays = array();
  foreach($render->filesearchpaths as $path) {
  	if (is_dir($path)) {
	  	$files = scandir($path);
	  	foreach($files as $file) {
	  		if (preg_match('/(?:template|preprocess|wrapper)\.(.*)\.php/', $file, $matches)>0) {
	  			if (endsWith($matches[1],'html')) {
	  				$displays[($matches[1])] = TRUE;
	  			}
	  			//$displays[] = implode(':',$matches);
	  		}
	  	}
  	}
  }
  return array_keys($displays);
  //return ($displays);
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
	if (!is_array($patharray)) {
		throw new Exception('find_element_file bad parameter');
	}
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
  
  if (!isset($content)) return;
  
  $output = &$uos->output;
  $outputpath = outputarraypath($path);
  //print_r($outputpath);die();
  @eval ( $outputpath . '= $content;' );
}

function addoutputunique($path,$content=FALSE,$attributes=array()) {
  global $uos;
  
  if (!isset($content)) return;
  
  $output = &$uos->output;
  $outputpath = outputarraypath($path);
  $searcharray = NULL;
  
  if (substr($path, -1) == '/') {
  	$outputpathtrimmed = substr($outputpath,0,-2);
  	//print_r("if (isset($outputpathtrimmed)) \$searcharray = $outputpathtrimmed;");
  	eval ( "if (isset($outputpathtrimmed)) \$searcharray = $outputpathtrimmed;" );
	  //print_r($searcharray);die();
	  //if ($searcharray!=NULL) {
	  //	print_r($outputpath);print_r(gettype($searcharray));print_r($searcharray);
	  //	die();
	  //}
  }
  if (!$searcharray || ($searcharray && (array_search($content, $searcharray)===FALSE)) ) {
  	@eval ( $outputpath . '= $content;' );
  }
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
  if ($uos->config->logtarget == UOS_LOGTARGET_FILE) {
  	writetrace($logitem);
  } 
  if ($uos->config->logtarget == UOS_LOGTARGET_CONTENT) {  
  	addoutput('log/', $logitem);
  }
}

function writetrace($logitem) {
	global $uos;
	static $file;
	if (isset($uos->request->universe)) {
	  $universecachepath = $uos->request->universe->getcachepath();
	  
	  if (!$file) {

	  	if (!file_exists($universecachepath)) {
				mkdir($universecachepath,0777,TRUE);
	  	}
		  $cachetracefile = $universecachepath . 'trace.log'; 
		   
	  	if (file_exists($cachetracefile)) {	
	  		unlink($cachetracefile);
	  	}
	  	
	  	$file = fopen($cachetracefile, 'a');  
  	}
  	fwrite($file, print_r($logitem,TRUE) . "\n");	
  	//fclose($file);
		//print_r($universecachepath);die();
	}
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
	if ( in_array($error['type'], array(E_ERROR, E_USER_ERROR, E_PARSE, E_CORE_ERROR)) ) {
	  //addoutput('content/',$error);
	  echo "ERROR type : " . $error['type']. " | Msg : ".$error['message']." | File : ".$error['file']. " | Line : " . $error['line'];
	  die();
	} 
  /*
	if (isset($uos->output)) {
		try {
			$uos->response->content = rendernew($uos->output,array(
			//$uos->response->content = rendernew($uos->output['content'],array(
				//'debug'	=> TRUE,
				'displaystring' => $uos->request->displaystring
			));
		} catch (Exception $e) {
			//$uos->response->code = 500;
		  $uos->response->content = ('Caught exception: ' .  $e->getMessage() . "\n");
		}
		echo $uos->response->content;
	}
	*/
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
		die('handleError');
		return true;
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
    //trace('found path :'.$path);
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


// simular structure to fetch entity
// Return Values
// TRUE - Library (already) loaded successfully
// FALSE - Library not found / no config 
// Add better error handling - status codes
function includeLibrary($librarykey) {

	global $uos;
	
	if (!isset($uos->libraries[$librarykey])) {
		$config = array();
		$librarykeyexploded = explode('.',$librarykey,2);
		$library = $librarykeyexploded[0];
		$librarypath = UOS_LIBRARIES. $library .'/';	
		$libraryconfigfile = $librarypath . 'library.uos.php';
		
		if (file_exists($libraryconfigfile)) {
		
			include_once $libraryconfigfile;
			
			if (isset($config[$librarykey])) {
				//print_r($config[$librarykey]);
				foreach($config[$librarykey] as $libraryfile) {
					include_once $libraryfile;
				}
				$uos->libraries[$librarykey] = TRUE;
			} else {
				$uos->libraries[$librarykey] = FALSE;
			}
		} else {
		  $uos->libraries[$librarykey] = FALSE;
		}
	}
	//die();
	return $uos->libraries[$librarykey];
}

// in case we later want to move render time of headers
function uos_header($string, $replace = true) {
	header($string, $replace);
}

// in case we later want to move render time of headers
function uos_add_headers($string, $replace = true) {
	header($string, $replace);
}


function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}



function getFileOutput($file,$variables) {

	$output = '';
	extract($variables, EXTR_REFS || EXTR_OVERWRITE);

	if (empty($file)) throw new Exception('getFileOutput : bad filename : '.$file);
	
  try {
  	ob_start();
    include $file;
  	$output = ob_get_clean();
  } catch (Exception $e) {
  	$output = ob_get_clean();
  	// throw an exception here?
    throw new Exception('Caught exception: '.  $e->getMessage() . $file);
  } 	
  return $output;	  
}



function get_type_info_old($entity) {
	global $uos;

	$classtree = class_tree($entity);
	$typeconfig = NULL;
	$class = $classtree[0];
	if (!isset($uos->config->types[$class])) {
		$typeconfig = $uos->config->types[$class] = new StdClass();
	} else {
		$typeconfig = $uos->config->types[$class];
	}
	$typeconfig->class = $class;
	$typeconfig->classtree = $classtree;
	if (!isset($typeconfig->title)) $typeconfig->title = 'Unknown';
	if (!isset($typeconfig->icon)) $typeconfig->icon = 'asterisk';
	
	return $typeconfig;		
}




function get_type_display_files($entity, $rendererpath, $displaystring=NULL) {
	global $uos;

	$typeconfig = get_type_info($entity);
	$display = array();
	
	if (!isset($typeconfig->displays[$displaystring])) {
	
		if (!isset($typeconfig->displays)) $typeconfig->displays = Array();
		
		$display = $typeconfig->displays[$displaystring] = new StdClass();		
		$displaystringexploded = explode('.',$displaystring);

		while(count($displaystringexploded)>0) {
			$displaystring = implode('.',$displaystringexploded);
			//echo $displaystring.":";
			foreach ($typeconfig->displaypaths as $displaypath) {
				if (!isset($display->preprocessfile)) $display->preprocessfile = find_element_file(array($displaypath), "preprocess.$displaystring.php");
				if (!isset($display->templatefile)) $display->templatefile = find_element_file(array($displaypath), "template.$displaystring.php");
				if (!isset($display->wrapperfile)) $display->wrapperfile = find_element_file(array($displaypath), "wrapper.$displaystring.php");
				if (!isset($display->transportfile)) $display->transportfile = find_element_file(array($displaypath),  "transport.$displaystring.php");
			}
			array_shift($displaystringexploded);
		}
		
		
	}
	
	return $display;
}
			
function get_type_displays($entity, $rendererpath) {
	global $uos;

	$typeconfig = &get_type_info($entity);
	
	if (!$typeconfig) {
		print_r($entity);
		die('no config found');
	}
	
	$displays = array();

	if (!property_exists($typeconfig,'displaypaths')) {	
		$typeconfig->displaypaths = find_element_paths($rendererpath, $entity, TRUE);
	}
	
	if (!isset($typeconfig->displays)) {
		foreach ($typeconfig->displaypaths as $displaypath) {
			$filelist = file_list($displaypath);
			foreach ($filelist as $file) {
				// we have found a template|preprocess|wrapper... file
				if (preg_match('/^([a-z]*)\.(.*)\.php$/', $file, $matches)>0) {
					$filetype = $matches[1];
					$displaystring = $matches[2];
					if (!isset($displays[$displaystring])) {
						$displays[$displaystring] = new StdClass();
					}
					$display = $displays[$displaystring];
					if (!property_exists($display,$filetype)) {
					  $display->$filetype = $displaypath.$file;
					}
				}
			}
		}
		foreach($displays as $displaystring=>$display) {
			$displaystringexploded = explode('.',$displaystring);
			//echo "For display string $displaystring :\n";
			while(count($displaystringexploded)>0) {
				$subdisplaystring = implode('.',$displaystringexploded);
				//print_r($subdisplaystring);echo "\n";
				$displays[$displaystring] = (object) array_merge((array) $displays[$subdisplaystring], (array) $displays[$displaystring]);
				array_shift($displaystringexploded);				
			}
		}
		$typeconfig->displays = $displays;
	}
}


function get_type_displays_old($entity, $rendererpath) {
	global $uos;

	$typeconfig = get_type_info($entity);
	$displays = array();

	if (!isset($typeconfig->displays)) {
		$typeconfig->displaypaths = find_element_paths($rendererpath, $entity, TRUE);
		$display = $typeconfig->displays[$displaystring] = new StdClass();
		$displaystringexploded = explode('.',$displaystring);
		//foreach($display)
		while(count($displaystringexploded)>0) {
			$displaystring = implode('.',$displaystringexploded);
			//echo $displaystring.":";
			foreach ($typeconfig->displaypaths as $displaypath) {
			
				$filelist = file_list($displaypath);
				foreach ($filelist as $file) {
					// we have found a template|preprocess|wrapper... file
					if (preg_match('([a-z]*)\.(.*)\.php', $file, $matches)>0) {
						//$typeconfig->displays[($matches[2])] =
					}
				}
			}
			array_shift($displaystringexploded);
		}
	}
}


function &get_type_info($entity) {
	global $uos;

	$classtree = class_tree($entity);
	
	$class = $classtree[0];

	if (!isset($uos->config->types[$class])) {
		$uos->config->types[$class] = new StdClass();	
	}
	$typeconfig = &$uos->config->types[$class];
	$typeconfig->class = $class;
	$typeconfig->classtree = $classtree;
	if (!isset($typeconfig->title)) $typeconfig->title = 'Unknown';
	if (!isset($typeconfig->icon)) $typeconfig->icon = 'asterisk';

	return $uos->config->types[$class];		
}

function &get_unknown_type_info() {
	return $uos->config->types['unknown'];
}



function setIfUnset(&$variable,$value) {
	return (isset($variable))?$variable=$variable:$variable=$value;
}


//http://www.php.net/manual/en/reserved.variables.files.php
function diverse_array($vector) { 
    $result = array(); 
    foreach($vector as $key1 => $value1) 
        foreach($value1 as $key2 => $value2) 
            $result[$key2][$key1] = $value2; 
    return $result; 
} 

function redirect($url='/') {
	header("Location: " . $url);
	exit;
}

function strip_html_attributes2($html,$attribs) {
    $dom = new simple_html_dom();
    $dom->load($html);
    foreach($attribs as $attrib)
        foreach($dom->find("*[$attrib]") as $e)
            $e->$attrib = null; 
    $dom->load($dom->save());
    return $dom->save();
}

function strip_html_attributes($html,$attribs) {

	//return $html;
	$domd = new DOMDocument();
	$domd->preserveWhiteSpace = false;
	libxml_use_internal_errors(true);
	$domd->loadHTML(strip_tags($html,'<p><h1><h2><h3><h4><img><div><uL><ol><li><em><a><span><a>'));
	//$domd->loadHTML($html);

	libxml_use_internal_errors(false);
	
	$domx = new DOMXPath($domd);
	$items = $domx->query("//*[@style]");	
	foreach($items as $item) {
	  $item->removeAttribute("style");
	}
	
	$domx = new DOMXPath($domd);
	$items = $domx->query("//*[@class]");	
	foreach($items as $item) {
	  $item->removeAttribute("class");
	}
	
	# remove <!DOCTYPE 
	$domd->removeChild($domd->firstChild);            

	//return print_r($domd->firstChild->firstChild->firstChild,TRUE);
	/*
	$xpath = new DOMXPath($domd);
	$body = $xpath->query('//*');
	$tags = array();
	foreach($items as $item) {
		$tags[] = $item->tagName;
	}
	return print_r($tags,TRUE);
	*/
	# remove <html><body></body></html> 
	//$domd->replaceChild($domd->firstChild->firstChild, $domd->firstChild);
	//$domd->replaceChild($domd->firstChild->firstChild, $domd->firstChild);
	
	//return $domd->saveHTML();
	return preg_replace("/\s+/", " ", strip_tags($domd->saveHTML(),'<p><h1><h2><h3><h4><img><div><uL><ol><li><a>') );
}


//http://stackoverflow.com/questions/3243900/convert-cast-an-stdclass-object-to-another-class
/**
 * recast stdClass object to an object with type
 *
 * @param string $className
 * @param stdClass $object
 * @throws InvalidArgumentException
 * @return mixed new, typed object
 */
function recast($className, stdClass &$object) {
    if (!class_exists($className))
        throw new InvalidArgumentException(sprintf('Inexistant class %s.', $className));

    $new = new $className();

    foreach($object as $property => &$value)
    {
        $new->$property = &$value;
        unset($object->$property);
    }
    unset($value);
    $object = (unset) $object;
    return $new;
}

function recursiveDelete($str){
    if(is_file($str)){
        return @unlink($str);
    }
    elseif(is_dir($str)){
        $scan = glob(rtrim($str,'/').'/*');
        foreach($scan as $index=>$path){
            recursiveDelete($path);
        }
        return @rmdir($str);
    }
}

function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}



function getremotefile($remotepath,$targetpath=FALSE) {

	$filename = '/tmp/uos_'.basename($remotepath);
	
	if(!@copy($remotepath, $filename)) {
    $errors= error_get_last();
    return FALSE; //"COPY ERROR: ".$errors['type'] . $errors['message'] . ':' . $remotepath . ':' . $filename;
	} else {
	  return $filename;
	}
	
}

function identifyfile($filepath) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $filepath);
	finfo_close($finfo);
	return array(
		'mime'=>$mime, 
		'size'=>filesize($filepath), 
		'title'=>basename($filepath),
		'filename'=>basename($filepath),
		'filepath'=>$filepath,
		'checksum'=>md5_file($filepath)
	);	
}


