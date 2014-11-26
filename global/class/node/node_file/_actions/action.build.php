<?php
$this->addproperty('mime', 'field_text', array(
	'maxlength'=>255,
	'required'=>TRUE,
	'usereditable'=>FALSE,
	'stored'=>TRUE
));

$this->addproperty('size', 'field_number', array(
	'usereditable'=>FALSE,
	'stored'=>TRUE
));

$this->addproperty('checksum', 'field_text', array(
	'maxlength'=>32,
	'required'=>TRUE,
	'usereditable'=>FALSE,
	'stored'=>TRUE
));

$this->addproperty('filename', 'field_text', array(
	'stored'=>TRUE
));


$this->addproperty('filepath', 'field_file', array(
	'stored'=>TRUE
));

