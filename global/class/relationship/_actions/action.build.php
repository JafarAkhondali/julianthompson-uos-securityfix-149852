<?php
$this->addproperty('required', 'field_text', array(
	'maxlength'=>1,
	'stored'=>TRUE
))
;
$this->addproperty('fieldname', 'field_text', array(
	'maxlength'=>100,
	'stored'=>TRUE
));

$this->addproperty('parent', 'field_number', array(
	'stored'=>TRUE
));

$this->addproperty('child', 'field_number', array(
	'stored'=>TRUE
));
//$this->addproperty('weight', 'field_number');
$this->setuniqueproperty('parent','child');