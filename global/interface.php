<?php 

include_once "./core/core.php";

if (isset($uos->request->parameters['debugrequest'])) {
	print_r($uos->request);
	die();
}

//$uos->universe = new node_universe($uos->config->data->universe);

//print_r($uos->request->targetstring);die();

// Find target
//$target = false;
if (empty($uos->request->targetstring)) {

	$target = $universe;
	$target->id = 1;
} else {
	//$uos->universe
	//$target = fetchentity($uos->request->targetstring);
	//$target = $uos->universe->db_select_entity($uos->request->targetstring);
	$target = $universe->db_select_entity($uos->request->targetstring);
	
}


