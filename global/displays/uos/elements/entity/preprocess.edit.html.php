<?php

include $render->rendererpath . '/elements/entity/preprocess.html.php';

addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/display.edit.html.js");

//$entity->id->visible = FALSE;

//$render->wrapperelement = 'form';

foreach($entity->properties as $key=>$field) {
	if ($field->locked || (!$field->usereditable)) {
	//if (in_array($key,array('guid','type'))) {
		$field->displaystring = 'html';
	} else {
		$field->displaystring = 'edit.html';
	}
}