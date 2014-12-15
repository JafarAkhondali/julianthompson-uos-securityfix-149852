<?php
// field preprocess - /entity/field/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.html.php';

$render->attributes['id'] = $render->instanceid;
//$render->attributes['class'] = $render->classtreestring;

$render->attributes['class'][] = ($entity->visible==TRUE) ? 'uos-visible':'uos-invisible';
$render->attributes['class'][] = ($entity->isvalid()) ? 'uos-valid':'uos-invalid';
$render->attributes['class'][] = ' display-'.$render->displayformat;
$render->attributes['class'][] = ' field-key-'.$entity->key;


$render->elementdata->fieldinfo = 'display key set in file';
$render->elementdata->displaykey = 'field';
$render->elementdata->fieldkey = $entity->key;

if ($entity->masked) {
	$entity->value = '[MASKED]';
}



//$render->elementdata->displaykey = 'field';

//$render->attributes['draggable'] = "false";
addoutputunique('resources/style/', $render->rendererurl."elements/entity/field/_resources/style/display.field.css");
addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/display.field.js");
