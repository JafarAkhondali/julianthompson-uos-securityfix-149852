<?php 

include_once "./core/core.php";


$uos->universe = new node_universe($uos->config->data->universe);

//print_r($uos->universe);die();


// Find target
if (empty($uos->request->url)) {
	// find universe start point
	fetch($uos->universe);
} else {
	$requestexploded = explode(':',$uos->request->request);
	//if (isset($requestexploded))
	//print_r($requestexploded);
	$entity = fetchentity($uos->request->target);
	addoutput('body', $entity);
	//die();
	//fetchentity($uos->request);
}





// Shutdown function called here