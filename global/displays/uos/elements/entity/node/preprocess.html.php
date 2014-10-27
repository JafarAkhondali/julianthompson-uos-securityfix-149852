<?php
// node preprocess - /entity/node/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.html.php';

//if (!isset($render->attributes)) $render->attributes = array();
//$render->title = (string) $entity->title;
$render->attributes['draggable'] = 'true';
$render->attributes['title'] = (string) $entity->title;

//print_r($render->elementdata);
//print_r($uos->request->siteurl);
$render->elementdata->clicktarget = $uos->request->hosturl . $entity->guid->value;


$draglinkurl = $entity->guid->value . '.view.webloc';
$draglinkfilename =  (string) $entity->title . ".webloc";
$draglinkfilename = "http://universeos/2868744583.view.webloc";
//$render->elementdata->draglink = "application/octet-stream:/" . $draglinkurl . ":" . $draglinkfilename;
$render->elementdata->draglink = "application/octet-stream:" . $render->attributes['title']. '.webloc' . ":" . $render->elementdata->clicktarget.'.view.webloc';
if ($entity->filename) {
	//$dragfilefile = $uos->request->siteurl . $entity->filepath->value;
	$dragfilefile = $uos->request->hosturl . '/'. $entity->guid->value . '.file';
	//$render->elementdata->dragfile = $entity->mimetype->value . ":" . $entity->filename->value . ":" . $dragfilefile;
}

addoutputunique('resources/script/', $render->rendererurl."elements/entity/node/_resources/script/display.node.js");