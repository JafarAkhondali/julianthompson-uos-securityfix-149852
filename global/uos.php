<?php 

include_once "./core/core.php";

//print_r($uos->request);
//print_r($uos->output['content']);
//die();

$uos->universe = new node_universe($uos->config->data->universe);

// Find target
//$target = false;
if (!empty($uos->request->targetstring)) {
	$target = fetchentity($uos->request->targetstring);
	fetchentitychildren($target);
  $target->callaction($uos->request->action);
	//addoutput('content', $target);
	$uos->response->code = 200;
}

//print_r($uos->request->displaystring);
//print_r($uos->request);
//print_r($uos->output);die();
//echo rendernew($uos->output, $uos->request->displaystring);



// we found something
try {
	$uos->response->content = rendernew($uos->output,$uos->request->displaystring);
} catch (Exception $e) {
	//$uos->response->code = 500;
  $uos->response->content = ('Caught exception: ' .  $e->getMessage() . "\n");
}

echo $uos->response->content;
//echo '<pre>'.print_r($uos->activerender,TRUE).'</pre>';