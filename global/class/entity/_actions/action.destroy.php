<?php

if (isset($parameters['confirm']) && ($parameters['confirm']==1)) {
	$message = new node_message();
	$message->title = 'Destroyed '.$this->title;

	// do destroy
	/*
	if ($this->type->value=='node_universe') {
		$response->destroy_universe();
	} else {
		$response->body = $universe->destroy($this);
	}
	*/
	$message->body = ('<pre>'.$response.'</pre>');
	addoutput('content/', $message);
} else {
	$form = new node_form();
	$form->title = 'Destroy '.$this->title.'?';
	$form->title->usereditable = FALSE;
	$form->description->usereditable = FALSE;
	$form->description = '<p>Are you sure about this?</p>';
	$form->action = 'destroy';
	$form->target = $this->guid->value;
	$form->sourceid = $parameters['sourceid'];
	$form->addproperty('confirm', 'field_boolean', array('value'=>FALSE));
	$form->addproperty('parameters', 'field_text', array('value'=>print_r($parameters,TRUE), 'usereditable'=>FALSE));
	$form->displaystring = 'edit.html';
	addoutput('content/', $form);
}