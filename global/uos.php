<?php 

include_once "./core/core.php";

if (!$universe) {
	//redirect('/global/uos.create.php');
	print('universe not created : '.$uos->request->hostname);
	$universe = new node_universe();
	$universe->dbconnector->value  = 'mysql://' . $uos->config->globaldatabaseuser . ':' . $uos->config->globaldatabasepassword . '@' . $uos->config->globaldatabasehost;
	$universe->db_create($uos->request->universename);
} else {
	//$entity = new field_boolean(array('id'=>4));
	//print_r($entity);
	//print_r($entity);
	//print_r($entity->___gettabledefinition());
	//print_r($entity->___getdata());
	//$universe->db_create();
	//die();
	
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
	
	//print_r($target);die();
	
	if ($target) {
		$children = ($universe->db_select_children((string) $target->id));
		foreach($children as $child) {
			$target->children[] = $child;
		}
		//$universe->db_select_children($target->id->value);
		//fetchentitychildren($target);
		$target->trigger($uos->request->action);
		//addoutput('content', $target);
		$uos->response->code = 200;
	}
}
//print_r($target);die();
//print_r($uos->request->displaystring);
//print_r($uos->request);
//print_r($uos->output);die();
//echo rendernew($uos->output, $uos->request->displaystring);
if (isset($uos->request->parameters['debugresponse'])) {
	print_r($uos->request);
	print_r($_POST);
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