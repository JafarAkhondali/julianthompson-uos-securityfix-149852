<?php 

include_once "./core/core.php";


if (!$universe) {

	//redirect('/global/uos.create.php');
	print('universe not created : '.$uos->request->hostname);
	$universe = new node_universe();
	$universe->dbconnector->value  = 'mysql://' . $uos->config->globaldatabaseuser . ':' . $uos->config->globaldatabasepassword . '@' . $uos->config->globaldatabasehost;
	$universe->db_create($uos->request->universename);
	
} else {

	if ( (empty($uos->request->targetstring)) || ($uos->request->targetstring=='0000000000000000') ) {
		$target = $universe;
		//$target->id = 1;
	} else {
		//$uos->universe
		//$target = fetchentity($uos->request->targetstring);
		//$target = $uos->universe->db_select_entity($uos->request->targetstring);
		$target = $universe->db_select_entity($uos->request->targetstring);
		
	}
	
	if ($target) {
		/*
		$children = ($universe->db_select_children((string) $target->id));
		  foreach($children as $child) {
			$target->children[] = $child;
		}
		*/
		//$universe->db_select_children($target->id->value);
		//fetchentitychildren($target);
		$target->trigger($uos->request->action);
		//addoutput('content', $target);
		$uos->response->code = 200;
	}
}
	//print $uos->request->action;print_r($target);die();

if (isset($uos->request->parameters['debugresponse'])) {
	echo "DEBUG RESPONSE\n";
	//print_r($uos);
	print_r($uos->request);
	print_r($uos->output);
	die();
}

//print_r($uos->output);die();

// we found something do something otherwise be as silent as a ninja
if (isset($uos->output)) {
	try {
		$uos->response->content = rendernew($uos->output,array(
		//$uos->response->content = rendernew($uos->output['content'],array(
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