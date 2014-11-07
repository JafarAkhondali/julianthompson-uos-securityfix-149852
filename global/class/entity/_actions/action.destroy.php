<?php

if (isset($parameters['confirm']) && ($parameters['confirm']==1)) {
	$form = new node_form();
	$form->title->value = 'D E S T R O Y E D '.$this->title;
	$response = 'empty';
	$form->target->setvalue($this->guid->value);
	if ($this->type->value=='node_universe') {
		$response = $universe->destroy_universe();
	} else {
		$resposne = $universe->destroy($this);
	}
	$form->description->usereditable = FALSE;
	$form->description->setvalue('<pre>'.$response.'</pre>');
} else {
	$form = new node_form();
	$form->title = 'D E S T R O Y '.$this->title.'?';
	$form->title->usereditable = FALSE;
	$form->description->usereditable = FALSE;
	$form->description = '<p>Are you sure about this?</p>';
	$form->action = 'destroy';
	$form->target = $this->guid->value;
	$form->sourceid = $parameters['sourceid'];
	$form->addproperty('confirm', 'field_number', array('value'=>0));
	$form->addproperty('parameters', 'field_text', array('value'=>print_r($parameters,TRUE), 'usereditable'=>FALSE));
	$form->displaystring = 'edit.html';
}

addoutput('content/', $form);