<?php
$render->childcount = count($entity->children);
$render->attributes['data-childcount'] = $render->childcount;
$draglinkfile = $uos->request->siteurl . $entity->guid->value . '.webloc';

$render->draglink = "application/octet-stream:" . $entity->title->value . ".webloc:" . $draglinkfile;

if ($entity->filename) {
	//$dragfilefile = $uos->request->siteurl . $entity->filepath->value;
	$dragfilefile = $uos->request->siteurl . $entity->guid->value . '.file';
	$render->dragfile = $entity->mimetype->value . ":" . $entity->filename->value . ":" . $dragfilefile;
}

addoutput('resources/json/'.$render->elementid, $render);