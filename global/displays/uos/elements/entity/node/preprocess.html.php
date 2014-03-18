<?php
include $render->rendererpath . '/elements/entity/preprocess.html.php';

//$render->title = (string) $entity->title;
$render->attributes['draggable'] = 'true';
$render->attributes['title'] = (string) $entity->title;

//print_r($render->elementdata);
//print_r($uos->request->siteurl);
$render->elementdata->clicktarget = $uos->request->siteurl . $entity->guid->value;

$draglinkfile = $uos->request->siteurl . $entity->guid->value . '.webloc';
$render->elementdata->draglink = "application/octet-stream:" . (string) $entity->title . ".webloc:" . $draglinkfile;

if ($entity->filename) {
	//$dragfilefile = $uos->request->siteurl . $entity->filepath->value;
	$dragfilefile = $uos->request->siteurl . $entity->guid->value . '.file';
	$render->elementdata->dragfile = $entity->mimetype->value . ":" . $entity->filename->value . ":" . $dragfilefile;
}