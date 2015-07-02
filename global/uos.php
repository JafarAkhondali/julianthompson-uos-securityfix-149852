<?php 

include_once "./core/core.php";

//$headers = get_headers('http://trac.kodi.tv/ticket/10380', 1);
//print debuginfo($headers);
//print debuginfo(identifyfile('http://trac.kodi.tv/ticket/10380'));
//die();

if ($uos->request->debugmode==UOS_DEBUGMODE_REQUEST) {
	echo "DEBUG REQUEST\n";
	echo "(\$uos->request)\n";
	//print_r($uos->request);
	print debuginfo($uos->request);
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
		//die('got
		//$target->id = 1;
	} else {
	
		//look to see if target is child
		if (preg_match("/^[0-9]{16}$/", $uos->request->targetstring, $matches) > 0) {
			$target = $universe->db_select_entity($uos->request->targetstring);
			$uos->request->targetchildid=null;
		} else if (preg_match("/^([0-9]{16})\[(.*)\]$/", $uos->request->targetstring, $matches) > 0) {
			array_shift($matches);
			list($uos->request->targetstring,$uos->request->targetchildid) = $matches;
			//print_r('<br>target:'.$uos->request->targetstring);
			//print_r('<br>child:'.$childid);
			$targetparent = $universe->db_select_entity($uos->request->targetstring);
			$target = $targetparent->getchild($uos->request->targetchildid);
			//print_r($matches);
			//die('xxxxx');
		}
		
		//$uos->universe
		//$target = fetchentity($uos->request->targetstring);
		//$target = $uos->universe->db_select_entity($uos->request->targetstring);
		
		
	}
	
	if ($uos->request->debugmode==UOS_DEBUGMODE_TARGET) {
		echo "DEBUG TARGET<br/>\n";
		echo "(\$uos->request)<br/>\n";
		//print_r($uos->request);
		print debuginfo($uos->request);
		echo "(\$target)<br/>\n";
		print debuginfo($target);
		die();
	}
		
	
	if ($target) {
	
		// we should save everything to a process now and run that
		// write vars to file 
		//get_defined_vars ( void )
		//$serializedvars = serialize($uos);
		//file_put_contents($file, serialize($uos));
		//print debuginfo($serializedvars);
		//$content = unserialize(file_get_contents($file));
		//die();
	
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
	echo "(\$uos->output)\n";
	//print_r($uos);
	//print_r($uos->request);
	//print_r($uos->output);
	print debuginfo($uos->output);
	die();
}

// shutdown function called here
// or this till now
return renderoutputnew();
