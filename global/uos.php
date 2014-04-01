<?php 

include_once "./core/core.php";

if (isset($uos->request->parameters['debugrequest'])) {
	print_r($uos->request);
	die();
}

$uos->universe = new node_universe($uos->config->data->universe);

// Find target
//$target = false;
if (!empty($uos->request->targetstring)) {
	$target = fetchentity($uos->request->targetstring);
	fetchentitychildren($target);
  $target->trigger($uos->request->action);
	//addoutput('content', $target);
	$uos->response->code = 200;
}

//print_r($uos->request->displaystring);
//print_r($uos->request);
//print_r($uos->output);die();
//echo rendernew($uos->output, $uos->request->displaystring);
if (isset($uos->request->parameters['debugresponse'])) {
	print_r($uos->request);
	print_r($uos->output);
	print_r($_FILES);
	die();
}

// we found something do something otherwise be as silent as a ninja
if (isset($uos->output['content'])) {
	try {
		$uos->response->content = rendernew($uos->output['content'],array(
			//'debug'	=> TRUE,
			'displaystring' => $uos->request->displaystring
		));
	} catch (Exception $e) {
		//$uos->response->code = 500;
	  $uos->response->content = ('Caught exception: ' .  $e->getMessage() . "\n");
	}
	echo $uos->response->content;
}
//echo '<pre>'.print_r($uos->activerender,TRUE).'</pre>';