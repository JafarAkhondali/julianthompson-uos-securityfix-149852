<?php 

include_once "./core/core.php";


$uos->universe = new node_universe($uos->config->data->universe);

// Find target
//$target = false;
if (!empty($uos->request->target)) {
	$target = fetchentity($uos->request->target);
	fetchentitychildren($target);
  //$target->callaction('view');
	addoutput('content', $target);
	$uos->response->code = 200;
}

//print_r($target);
//print_r($uos->output['content']);die();
//die('died'); 

//print_r($target);die();
// we found something
try {
	$uos->response->content = startrender();
} catch (Exception $e) {
	//$uos->response->code = 500;
  $uos->response->content = ('Caught exception: ' .  $e->getMessage() . "\n");
}

echo $uos->response->content;
//echo '<pre>'.print_r($uos->activerender,TRUE).'</pre>';