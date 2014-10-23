<?php
// field preprocess - /entity/field/preprocess.html.php

include $render->rendererpath . '/elements/entity/preprocess.html.php';

if (empty($entity)) {
	//$render->display->template = FALSE;	
	//$render->display->wrapper = FALSE;
	//return;
}

// include parent behaviour
// equivalent of calling parent preprocess method


//$render->attributes['draggable'] = "false";