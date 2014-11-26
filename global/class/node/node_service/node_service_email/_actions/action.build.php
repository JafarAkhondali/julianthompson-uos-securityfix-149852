<?php
$this->addproperty('hostname', 'field_text', array(
	'required'=>TRUE, 
	'stored'=>TRUE
));

/*$this->addproperty('port', 'field_number', array(
	'required'=>TRUE, 
	'stored'=>TRUE
));*/

$this->addproperty('username', 'field_text', array(
	'required'=>TRUE, 
	'stored'=>TRUE
));

$this->addproperty('password', 'field_text', array(
	'visible'=>FALSE,
	'masked'=>TRUE,
	'stored'=>TRUE
));
