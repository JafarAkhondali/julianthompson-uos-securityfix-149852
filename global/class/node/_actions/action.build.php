<?php
$this->addproperty('title', 'field_text', array(
	'maxlength'=>255,
	'required'=>TRUE, 
	'stored'=>TRUE
));

$this->addproperty('created', 'field_datetime', array(
	'locked'=>FALSE,
	'usereditable'=>FALSE, 
	'defaultvalue'=>'now', 
	'stored'=>TRUE
));

$this->addproperty('modified', 'field_datetime', array(
	'locked'=>FALSE,'usereditable'=>FALSE, 
	'defaultvalue'=>'now', 
	'stored'=>TRUE
));