<?php
// field preprocess - /entity/field/preprocess.html.php

if ($entity->visible!==TRUE) {
	//$render->output = 
	//$render->display->template = FALSE;	
	//$render->display->wrapper = FALSE;
	//return;
	$render->finish = TRUE;
}

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.html.php';

$render->elementdata->displaykey = 'field';

//$render->attributes['draggable'] = "false";

addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/display.field.js");