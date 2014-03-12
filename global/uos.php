<?php 

include_once "./core/core.php";


$uos->universe = new node_universe($uos->config->data->universe);

// Find target
$target = false;
if (empty($uos->request->url)) {
	// find universe start point
	$target = fetch($uos->universe);
} else {
	$requestexploded = explode(':',$uos->request->request);
	$target = fetchentity($uos->request->target);
}

if ($target) {
  $target->callaction('view');
	//addoutput('body', $target);
	header("HTTP/1.1 200 OK");
} else {
	echo 'Not found';
	die();
}

// Shutdown function called here