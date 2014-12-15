<?php
$this->addproperty('messageid', 'field_text', array(
	'usereditable'=>FALSE,
	'stored'=>TRUE
));
$this->addproperty('read', 'field_boolean', array(
	'required'=>TRUE,
	'usereditable'=>FALSE,
	'stored'=>TRUE
));
$this->addproperty('body', 'field_text', array(
	'required'=>TRUE,
	'usereditable'=>TRUE,
	'stored'=>TRUE
));