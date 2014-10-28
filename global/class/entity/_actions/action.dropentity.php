<?php
//$contentguids = $universe->normalize_guid_list($uos->request->parameters['content']);
$tagentityids = $universe->guid_to_id($uos->request->parameters['content']);
//$tagentityids = array('one','two');
//$contentguids = $universe->guid_to_id($uos->request->parameters['content']);
//addoutput('content/', "Dropped entities");
//throw new Exception('Division by zero.');
$message = new node_message(array(
	'title' => "Dropped entities",
	'body' => "Dropped " . implode(',',$tagentityids)  . " entities onto " . $this->title . ' (' . $this->id . ')'
));
//trigger_error("(SQL)", E_USER_ERROR);
//trace('xxxx','poo');
$universe->tagcontent($this, $tagentityids);
addoutput('content/', $message);
//addoutput('content/', "Onto : ".$this->guid);

