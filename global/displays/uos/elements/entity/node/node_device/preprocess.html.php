<?php
// node_device preprocess - /entity/node/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/node/preprocess.html.php';

//if (!isset($render->attributes)) $render->attributes = array();
//$render->title = (string) $entity->title;
$render->attributes['draggable'] = 'true';
$render->attributes['title'] = (string) $entity->title;

//print_r($render->elementdata);
//print_r($uos->request->siteurl);
$render->elementdata->clicktarget = $uos->request->hosturl . $entity->guid->value;

$draglinkurl = $entity->guid->value . '.view.webloc';
$draglinkfilename =  (string) $entity->title . ".webloc";
$render->elementdata->draglink = "application/octet-stream:" . $draglinkurl . ":" . $draglinkfilename;

if ($entity->filename) {
	//$dragfilefile = $uos->request->siteurl . $entity->filepath->value;
	$dragfilefile = $uos->request->siteurl . $entity->guid->value . '.file';
	$render->elementdata->dragfile = $entity->mimetype->value . ":" . $entity->filename->value . ":" . $dragfilefile;
}

addoutputunique('resources/script/', $render->rendererurl."elements/entity/node/node_device/_resources/script/jquery.node_device.js");