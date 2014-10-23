<?php

include $render->rendererpath . '/elements/entity/preprocess.html.php';

addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/display.edit.html.js");

$entity->id->visible = FALSE;

foreach($entity->properties as $key=>$child) {
	if ($child->locked) {
	//if (in_array($key,array('guid','type'))) {
		$child->displaystring = 'html';
	} else {
		$child->displaystring = 'edit.html';
	}
}