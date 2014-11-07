<?php


//$sql = $universe->destroy($this);
return;
$output = new node_message(array(
	'title' => "Destroy universe " . $this->title . "?",
	'body' => 'Are you sure about this?' //. print_r($uos->request, TRUE)
));
$output->addproperty('confirm', 'field_boolean', array('value'=>0));
$output->created->setvalue(now);

addoutput('content/', $output);