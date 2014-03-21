<?php
global $uos;

$render->childcount = count($entity->children);
$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->formatdisplay;

if (isset($entity->title->value)) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}

$render->elementdata->guid = (string) $entity->guid;