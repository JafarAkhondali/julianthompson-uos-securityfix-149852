<?php
$contentguids = $universe->normalize_guid_list($uos->request->parameters['content']);
//$contentguids = $universe->guid_to_id($uos->request->parameters['content']);
//addoutput('content/', "Dropped entities");
$message = new node_message(array(
	'body' => "Dropped ".implode(',',$contentguids)  . " entities onto " . $this->title
));

addoutput('content/', $message);
//addoutput('content/', "Onto : ".$this->guid);

