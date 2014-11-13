<?php

$render->childcount = count($entity->children);

//if (!isset($render->attributes)) $render->attributes = array();

$render->attributes['id'] = $render->instanceid;
$render->attributes['class'] = $render->classtreestring;
$render->attributes['childcount'] = $render->childcount;
$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->displaystring;
$render->attributes['arse'] = array('item1','item2','item3');
$render->attributes['bool'] = TRUE;

if (!isset($render->elementdata)) $render->elementdata = new stdClass();
$render->elementdata->typetree = $render->entityconfig->classtree;
$render->elementdata->type = $render->entityconfig->class;
$render->elementdata->typeinfo = $render->entityconfig;
$render->elementdata->activedisplay = $render->displaystring;
$render->elementdata->displays = $render->formatdisplaynames;
$render->elementdata->displaykey = 'entity';
//$render->elementdata->typedisplay = $render->entityconfig->class.'.'.$render->displaystring;




if (isset($entity->title->value)) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}

$render->wrapperelement = 'div';

$render->elementdata->guid = (string) $entity->guid;

//addoutput('elementdata/'.$render->instanceid, $render->elementdata);
addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/display.entity.js");
addoutputunique('resources/style/', $render->rendererurl."elements/entity/_resources/style/style.html.css");