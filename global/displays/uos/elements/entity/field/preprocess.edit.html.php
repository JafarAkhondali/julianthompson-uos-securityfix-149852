<?php
// field preprocess - /entity/field/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/field/preprocess.html.php';

if (!$entity->usereditable) {
	$render->finish = TRUE;
}

//$render->attributes['draggable'] = "false";

if (!isset($render->attributes)) $render->attributes = array();
$render->attributes['id'] = $render->instanceid;
$render->attributes['class'] = $render->classtreestring;

//$render->attributes['valid'] = ($entity->isvalid()) ? "uos-valid":"uos-invalid";
$render->attributes['class'] = $render->attributes['class'] . (($entity->isvalid()) ? " uos-valid":" uos-invalid");

addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/jquery.edit.html.js");