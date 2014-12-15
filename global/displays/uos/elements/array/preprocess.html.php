<?php


//include $render->rendererpath . '/elements/entity/preprocess.html.php';

$render->childcount = count($entity);

$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->displaystring;
$render->attributes['title'] = 'List (Array)';


$render->elementdata->typetree = $render->entityconfig->classtree;
$render->elementdata->type = $render->entityconfig->class;
$render->elementdata->typeinfo = $render->entityconfig;
$render->elementdata->activedisplay = $render->displaystring;
$render->elementdata->displays = $render->formatdisplaynames;
$render->elementdata->displaykey = 'entity';
$render->elementdata->guid = (string) $entity->guid;
//$render->elementdata->typedisplay = $render->entityconfig->class.'.'.$render->displaystring;


if (isset($entity->title->value)) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}

$render->wrapperelement = 'div';



//addoutput('elementdata/'.$render->instanceid, $render->elementdata);
//addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/classextend.js");
addoutputunique('resources/script/', $render->rendererurl."elements/array/_resources/script/display.js");
addoutputunique('resources/script/', $render->rendererurl."elements/array/_resources/script/display.html.js");
//addoutputunique('resources/style/', $render->rendererurl."elements/array/_resources/style/display.html.css");