<?php
$render->childcount = count($entity->children);
$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['draggable'] = 'false';
$render->attributes['title'] = $render->title;
$render->attributes['data-display'] = $render->displaymode;

$render->data->clicktarget = $uos->request->siteurl . $entity->guid->value;

$draglinkfile = $uos->request->siteurl . $entity->guid->value . '.webloc';
$render->data->draglink = "application/octet-stream:" . $entity->title->value . ".webloc:" . $draglinkfile;

if ($entity->filename) {
	//$dragfilefile = $uos->request->siteurl . $entity->filepath->value;
	$dragfilefile = $uos->request->siteurl . $entity->guid->value . '.file';
	$render->data->dragfile = $entity->mimetype->value . ":" . $entity->filename->value . ":" . $dragfilefile;
}

addoutput('resources/json/'.$render->elementid, $render->data);