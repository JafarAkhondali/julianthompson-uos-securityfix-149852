<?php 

include_once "./core/core.php";


$uos->universe = new node_universe($uos->config->data->universe);

//print_r($uos->universe);die();

/*
$uos->request->target = new entity(array(
	'universename'=>'julian',
	'guid'=>'1234567'
));
*/

fetch($uos->universe, $uos->request->action);


// Shutdown function called here