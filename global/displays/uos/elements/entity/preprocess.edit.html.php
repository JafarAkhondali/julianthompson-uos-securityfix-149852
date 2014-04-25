<?php

$render->childcount = count($entity->children);

if (!isset($render->attributes)) $render->attributes = array();

$render->attributes['id'] = $render->instanceid;
$render->attributes['class'] = $render->classtreestring;
$render->attributes['childcount'] = $render->childcount;
$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->displaystring;

if (!isset($render->elementdata)) $render->elementdata = new stdClass();
$render->elementdata->typetree = $render->entityconfig->classtree;
$render->elementdata->type = $render->entityconfig->class;
$render->elementdata->typeinfo = $render->entityconfig;
$render->elementdata->activedisplay = $render->displaystring;
$render->elementdata->displays = $render->formatdisplaynames;


if (isset($entity->title->value)) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}

$render->wrapperelement = 'div';

$render->elementdata->guid = (string) $entity->guid;

foreach($entity->properties as $child) {
	$child->displaystring = 'edit.html';
}

//foreach($entity->children as $child) {
//	$child->displaystring = 'edit.html';
//}

addoutput('elementdata/'.$render->instanceid, $render->elementdata);
addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/jquery.entity.js");
addoutputunique('resources/style/', $render->rendererurl."elements/entity/_resources/style/style.html.css");