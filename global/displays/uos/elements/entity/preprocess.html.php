<?php
global $uos;

$render->childcount = count($entity->children);
$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->formatdisplay;

$render->elementdata->displaypaths = array(
	'withfooter' => '/4567898765.withfooter.html'
);

if (is_subclass_of($entity,'entity') && (isset($entity->title->value))) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}