<?php

function render($entity, $rendersettings = NULL) {

	global $uos,$universe;
	static $renderdepth = 0;
	$renderdepth++;

	/*
	if (is_array($rendersettings) || is_object($rendersettings)) {
		$render = (object) $rendersettings;
	} else {
		$render = new StdClass();	
		if (is_object($entity) && is_subclass_of($entity,'entity') && !empty($entity->displaymode)) {
			$render->displaystring = $entity->displaymode;
			$render->displaymode = $entity->displaymode;
	//if ($render->displaystring=='slider.html') {
	//	return print_r($render,TRUE);
	//}
		} elseif (is_string($rendersettings) && (!empty($rendersettings))) {
			$render->displaystring = $rendersettings;
		}
	}	
	*/
	
	$render = new StdClass();	
	
	if (is_object($entity) && !empty($entity->displaystring)) {
			$render->displaystring = $entity->displaystring;
	} 
	
	if (is_array($rendersettings) || is_object($rendersettings)) {
			$render = (object) ( (array) $render + (array) $rendersettings);
	}	elseif (is_string($rendersettings) && (!empty($rendersettings))) {
			//$render->displaystring = $rendersettings;
			setIfUnset($render->displaystring, $rendersettings);
	}

	setIfUnset($render->activerenderer, UOS_DEFAULT_DISPLAY);
	
	setIfUnset($render->displaystring, $uos->request->displaystring);

	$render->output = '';
	$render->finishprocessing = FALSE;
	$render->finish = FALSE;
	
	$render->rendererurl = addtopath(UOS_DISPLAYS_URL, $render->activerenderer, '/'); 
	$render->rendererpath = addtopath(UOS_DISPLAYS, $render->activerenderer);
	$render->renderdepth = $renderdepth;

	$render->entityconfig = &get_type_info($entity);	
	
	$render->elementtype = $render->entitytype = $render->entityconfig->class;
	

	if (in_array($render->elementtype, array('string','integer'))) return (string) $entity;
	
	get_type_displays($entity,$render->rendererpath);
	
	if (!isset($render->entityconfig->displays[$render->displaystring])) {
		return "I can't show you that. (".$render->displaystring.")\n Type '".$render->elementtype."' Supports : ".print_r($render->entityconfig->displays,TRUE);
	}
	
	$render->display = $render->entityconfig->displays[$render->displaystring]; 
	
	
	$render->instanceid = get_instance_id( $render->entityconfig->class ). '__' . round((time() + microtime(true)) * 1000);		

	$render->classtreestring = 'uos-element uos-uninitialized '.implode(' ', array_reverse($render->entityconfig->classtree)) . ' display-'.str_replace('.','-',$render->displaystring);
	
	$render->inheritancestring = implode(',', $render->entityconfig->classtree);
	
	$render->wrapperelement = 'div';
	
	$rendervariables = array('render'=>$render,'entity'=>$entity, 'uos'=>$uos);
	
	//require_once $render->rendererpath . 'elements/include.uos.php';
	
	$explodeddisplaystring = explode('.',$render->displaystring);

	$render->displayformat = array_pop($explodeddisplaystring);

	$render->displaynames = array_keys($render->entityconfig->displays);

	//remove some items for now
	$render->displaynames = array_diff($render->displaynames, array('page.html'));
	
	$render->formatdisplaynames = preg_grep("/($render->displayformat|.*\.$render->displayformat)/i", $render->displaynames);

	if (is_subclass_of($entity,'entity')) {
		$render->cachepath = $universe->cachepath();// . '32745275472/'. $displaystring . '/'; 
	}

	//if ($render->elementtype=='field_number' && $entity->key=='intensity') {
		//return print_r($entity,TRUE);
		//return $render->display->preprocess.':'.$render->display->template.':'.$render->display->wrapper;
		//return print_r($render);
	//}
	
	if ($renderdepth>10) return "RENDER DEPTH REACHED\n".print_r($render,TRUE)."\n".print_r($uos->output['content'],TRUE);
	
	if (	(isset($uos->request->parameters['debugrender']))  ||
				(isset($render->debug) && $render->debug) ) {
		//print_r($uos->request);
		//print_r(get_class($entity));
		print_r($render);
		die();
	}
	
	if (!isset($render->attributes)) $render->attributes = array();
	if (!isset($render->elementdata)) $render->elementdata = new stdClass();
	
	
  // maybe this is for html only but keep here for now
	$render->attributes['id'] = $render->instanceid;

	$render->attributes['class'] = array(
			'uos-element',
			'uos-uninitialized'		
	);

	
	$render->attributes['class'] = array_merge($render->attributes['class'], array_reverse($render->entityconfig->classtree));

	//$render->attributes['classnew'] = $render->attributes['class'] + array_reverse($render->entityconfig->classtree);
	
	$render->attributes['classnew'] = array_reverse(array_map( 
        function($item) { return "uos-type-".$item; }, 
        $render->entityconfig->classtree 
	)); 	 
	
	$render->attributes['class'] = array_merge($render->attributes['class'], $render->attributes['classnew']);
	
	unset($render->attributes['classnew']);
	
	array();//array_map($render->entityconfig->classtree, function(&$item) { return 'uose-'.$item; });
	
	// PHP 5.3 and beyond!
		 // or $item = '-'.$item;

	trace('About to prerender:'. print_r(array(
		'elementtype'=>$render->elementtype,
		'display'=>$render->display,
		'displaystring'=>$render->displaystring,
		'renderdepth'=>$renderdepth
	),TRUE) ,array('display'));	
	//die();
		
	try {
	
		//echo $render->display->preprocess;

		if (property_exists($render->display,'preprocess')) {
			trace('Preprocess Start : '.$render->display->preprocess);
			//trace('including preprocess file :'.$render->display->preprocess . print_r($render,TRUE) ,array('display'));
			$render->workingpath = dirname($render->display->preprocess);
			$render->preprocessoutput = getFileOutput($render->display->preprocess,$rendervariables);
			$render->output = &$render->preprocessoutput;
			trace('Preprocess Complete');
		}	
		
		if ($render->finish) return $render->output;
	

	
		if (property_exists($render->display,'template') && ($render->display->template!==FALSE)) {
			trace('Template Start : '.$render->display->template);
			$render->workingpath = dirname($render->display->template);
			$render->templateoutput = getFileOutput($render->display->template,$rendervariables);
			$render->output = $render->templateoutput;
		
			trace('Template Complete');
		}
		
		
		if (isset($render->elementdata)) {
			addoutput('elementdata/'.$render->instanceid, $render->elementdata);
		}
		
		if ($render->finish) return $render->output;

		//echo $render->display->template;
		//echo "here";
		//print_r($render->output);
		//die();
	
		if (property_exists($render->display,'wrapper') && ($render->display->wrapper!==FALSE)) {
			trace('Wrapper Start: '.$render->display->wrapper);
			$render->workingpath = dirname($render->display->wrapper);
			$render->wrapperoutput = getFileOutput($render->display->wrapper,$rendervariables);
			$render->output = $render->wrapperoutput;
			trace('Wrapper Complete');
		}

		if (property_exists($render->display,'transport')) {
			trace('Transport Start: '.$render->display->transport);
			$render->workingpath = dirname($render->display->transport);
			$render->transportoutput = getFileOutput($render->display->transport,$rendervariables);
			$render->output = $render->transportoutput;
			trace('Transport Complete');			
		}
	} catch (Exception $e) {
		$render->templateoutput = 'Unset';
		$render->output = 'Error : '.$e->getMessage().print_r($uos->request,TRUE).print_r($render,TRUE).':'.$render->displaystring;
	}
	
	trace('Finished render:'. print_r(array(
		'elementtype'=>$render->elementtype,
		'display'=>$render->display,
		'displaystring'=>$render->displaystring,
		'renderdepth'=>$renderdepth
	),TRUE) ,array('display'));	
	
	$renderdepth--;
	return $render->output;
	//$file = $render->rendererelements.'/uos/template.
	//return getFileOutput($file,array('entity'=>&$entity,'render'=>&$render, 'uos'=>&$uos));
}


// some sort of scope on mime type - objects? not sure yet
// For PHP without Anonymous functions
// $attributes['foo'] = array('item1','item2','item3');
// $attributes['goo'] = TRUE;
// 
// Renders as :
//
// foo="item1 item2 item3" goo=""
function display_uos_attributestostring($attributes) {
	$keys = array_keys($attributes);
	return join(' ', array_map('display_uos_attributestostring_callback',$keys,$attributes));
}

function display_uos_attributestostring_callback($key,$value) {
   if (is_bool($value)) {
      return $value?$key:'';
   } elseif (is_array($value)) {
   	$value = implode(' ',$value);
   } // else if entity etc?
   return $key.'="'.$value.'"';	
}

function display_uos_make_visual($entity) {
	// Create the image
	// Create a 100*30 image
	$im = imagecreate(500, 200);
	
	// White background and blue text
	$bg = imagecolorallocate($im, 255, 255, 255);
	$textcolor = imagecolorallocate($im, 0, 0, 255);
	
	// Write the string at the top left
	display_uos_imagecenteredstring($im, 5, 0, 500, 100, 'display_uos_make_visual', $textcolor);
	display_uos_imagecenteredstring($im, 5, 0, 500, 120, $entity->type, $textcolor);
	display_uos_imagecenteredstring($im, 5, 0, 500, 140, '('.$entity->guid.')', $textcolor);
	return $im;
}

function display_uos_imagecenteredstring ( &$img, $font, $xMin, $xMax, $y, $str, $col ) {
    $textWidth = imagefontwidth( $font ) * strlen( $str );
    $xLoc = ( $xMax - $xMin - $textWidth ) / 2 + $xMin + $font;
    imagestring( $img, $font, $xLoc, $y, $str, $col );
}

/*
function DISPLAY_attributestostring($attributes) {
	return join(' ', array_map(function($sKey) use ($attributes) {
	
	   if (is_bool($attributes[$sKey])) {
	      return $attributes[$sKey]?$sKey:'';
	   }

	   return $sKey.'="'.$attributes[$sKey].'"';

	}, array_keys($attributes)));
}
*/

function renderdisabled($entity, $rendermask=NULL) {
	global $uos, $universe;

	$content = '';
	
	//throw new Exception('Division by zero.');

	//$render = new stdClass();//$uos->render;
	if (isset($uos->activerenderbranch)) {
		$parentrenderbranch = $uos->activerenderbranch;
  	$render = clone $parentrenderbranch;
  	$render->children = array();
		$render->headers = array(); 
		$render->startpoint = FALSE;
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
		$render->displaystring = $uos->request->displaystring;
		$displaystringexploded = explode('.',$uos->request->displaystring);
		$render->format = $uos->request->outputformat->format;
		$render->display = $uos->request->outputformat->display;
	  $render->children = array(); 
	  $render->maxdepth = 4;
		$render->filesearchpaths = array($render->rendererpath); 
		$render->stop = FALSE;
		$render->headers = array();
		//$uos->rendertrees[] = new StdClass();
		$uos->activerenderbranch= $render;
		include_once $render->rendererpath . "include.uos.php";
		$render->startpoint = FALSE;
  }
  
	if ($render->display=='default') {
		$render->formatdisplay = $render->format;
	} else {
		$render->formatdisplay = implode('.',array((string) $render->display, (string)$render->format));
	}
  
  //$render->buffering = FALSE;

  // this should wrap the whole thing
  if (empty($entity) || ($render->maxdepth && ($render->depth>=$render->maxdepth)) ) {
  	$uos->activerender = $parentrenderbranch;
  	return '';
  }
	
	$render->classtree = class_tree($entity);
	$render->entitytype = $render->classtree[0]; 
	$render->typeinfo = get_type_data($render->entitytype);
	$render->instanceid = get_instance_id($render->entitytype). '__' . round((time() + microtime(true)) * 1000);		

	if (is_universe_entity($entity) && (isset($entity->title->value))) {
  //if (is_subclass_of($entity,'entity') && (isset($entity->title->value))) { 
  	$render->title = $entity->title->value;
  } else {
  	$render->title = ucfirst($render->entitytype);
  }

  
  // apply rendermask here?
  if ($rendermask) {
  	// object merge / step through array?
  	$render = (object) array_merge((array) $render, (array) $rendermask);
  	$render->masked = TRUE;
  }  

	//$render->callerinfo = getcallerinfo();	
	//if we use this autoload is called which crashes the system for string types
	$render->isuosentity = is_universe_entity($entity);
	
	//$render->preprocessed = array();

	//$render->preprocessall = find_element_file($render->filesearchpaths, "preprocess.php");
	
	$render->preprocessfile = find_element_file($render->filesearchpaths, "preprocess.$render->formatdisplay.php"); 
	
	if (!$render->preprocessfile) {
		$render->preprocessfile = find_element_file($render->filesearchpaths, "preprocess.$render->format.php");
	} 
		
	$render->templatefile = find_element_file($render->filesearchpaths, "template.$render->formatdisplay.php");
	
	if (!$render->templatefile) {
		$render->templatefile = find_element_file($render->filesearchpaths, "template.$render->format.php");
	} 	
		
	$render->wrapperfile = find_element_file($render->filesearchpaths, "wrapper.$render->formatdisplay.php"); 
	
	if (!$render->wrapperfile) {
		$render->wrapperfile = find_element_file($render->filesearchpaths, "wrapper.$render->format.php");
	}   

  
  // START - should only be set in preprocess functions that need it
  
	$render->childcount = NULL;

	$render->wrapperelement = 'div';

	$render->classtreestring = 'uos-element uos-uninitialized '.implode(' ', array_reverse($render->classtree));
	
	$render->inheritancestring = implode(',', $render->classtree);
	
	$render->elementdata = new stdClass();	
	$render->elementdata->typetree = $render->classtree;
	$render->elementdata->type = $render->entitytype;
	$render->elementdata->typeinfo = $render->typeinfo;
	$render->elementdata->display = $render->display;
	$render->elementdata->format = $render->format;
	$render->elementdata->activedisplay = $render->formatdisplay;
	
 
  // maybe this is for html only but keep here for now
	$render->attributes = array(
		'id' => $render->instanceid,
		'class' => $render->classtreestring,
		'classadd' => array(
			'uos-element',
			'uos-uninitialized'		
		)
	);
	
	//$render->attributes['class2'] += implode(' ', array_reverse($render->classtree));
  //extract($rendersettings,EXTR_OVERWRITE);

  //need to cater for multiple preprocess from node down
  if ($render->preprocessfile) {
	  try {
	    //trace('Including preprocess file - '.$preprocessfile);
	    include $render->preprocessfile;
	  } catch (Exception $e) {
	    trace('Caught exception: '.  $e->getMessage());
	  }  
	  //$render->preprocessed[] = $render->preprocessfile;
  }
    
  addoutput('elementdata/'.$render->instanceid, $render->elementdata);
  
  if ($render->stop) {
		return print_r($render,TRUE);  	  	
  }
    
  if ($render->templatefile) {
  
		// find display modes 
		//$render->typeinfo->displays = 		//$render->typeinfo->displays = $render->typeinfo->displays + find_element_displays($render->filesearchpaths, "preprocess.$render->format.php");
		
		$render->elementdata->displays = find_displays($render);

		
		
		
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

function startrenderdisabled($content,$displaystring=FALSE) {
	global $uos;
	
	if (isset($uos->output['content'])) {
		$entity = $uos->output['content'];		
		return render($entity);
	}
}