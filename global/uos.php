<?php 

include_once "./core/core.php";

if ($uos->request->debugmode==UOS_DEBUGMODE_REQUEST) {
	echo "DEBUG REQUEST\n";
	print_r($uos->request);
	die();
}


//$universe->dbconnector->masked=FALSE;

if (!$uos->request->configfound) {
	//trace('No universe found :'.$universe->dbconnector);
	//redirect('/global/uos.create.php');
	//print('universe not created : '.$uos->request->hostname);
	$form = new node_form();	
	$form->title->value = "universe not created : ".$uos->request->hostname;
	$form->action = 'create';
	$form->target = 0;
	$form->displaystring = 'edit.html';
	addoutput('content/',$form);
	//$universe = new node_universe();
	$universe->dbconnector->value  = 'mysql://' . $uos->config->globaldatabaseuser . ':' . $uos->config->globaldatabasepassword . '@' . $uos->config->globaldatabasehost;
	$universe->db_create($uos->request->universename);
	
} else if (!$universe->test()) {
	$universe->dbconnector->masked=FALSE;
	//addoutput('content/',$universe);
	$form = new node_form();	
	//$form->addproperty('info','field_text', array('value'=>''));
	$form->title->value = "universe not work : ".$uos->request->hostname;
	addoutput('content/',$form);
} else {

	trace('Found universe :'.$universe->dbconnector);
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
	
		//trace('Found target :'.$universe->dbconnector);
		/*
		$children = ($universe->db_select_children((string) $target->id));
		  foreach($children as $child) {
			$target->children[] = $child;
		}
		*/
		//$universe->db_select_children($target->id->value);
		//fetchentitychildren($target);
		$target->trigger($uos->request->action,$uos->request->parameters);
		//addoutput('content', $target);
		$uos->response->code = 200;
	}
}


if ($uos->request->debugmode==UOS_DEBUGMODE_RESPONSE) {
	echo "DEBUG RESPONSE\n";
	//print_r($uos);
	//print_r($uos->request);
	print_r($uos->output);
	die();
}


// shutdown function called here

//print_r($uos->output);die();

// we found something do something otherwise be as silent as a ninja
if (isset($uos->output)) {
	try {
		$uos->response->content = render($uos->output,array(
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
