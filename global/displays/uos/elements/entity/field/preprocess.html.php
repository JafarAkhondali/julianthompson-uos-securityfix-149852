<?php
// field preprocess - /entity/field/preprocess.html.php

if ($entity->visible!==TRUE) {
	//$render->output = 
	//$render->display->template = FALSE;	
	//$render->display->wrapper = FALSE;
	//return;
	//$render->finish = TRUE;
	//return;
}

if (!isset($render->attributes)) $render->attributes = array();
$render->attributes['id'] = $render->instanceid;
$render->attributes['class'] = $render->classtreestring;

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.html.php';

//$render->elementdata->displaykey = 'field';

//$render->attributes['draggable'] = "false";
addoutputunique('resources/style/', $render->rendererurl."elements/entity/field/_resources/style/display.field.css");
addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/display.field.js");
