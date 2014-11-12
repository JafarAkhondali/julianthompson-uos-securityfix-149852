<?php
$this->addproperty('mime', 'field_text', array(
	'maxlength'=>255,
	'required'=>TRUE,
	'usereditable'=>FALSE
));

$this->addproperty('size', 'field_number', array(
	'usereditable'=>FALSE
));

$this->addproperty('checksum', 'field_text', array(
	'maxlength'=>32,
	'required'=>TRUE,
	'usereditable'=>FALSE
));

$this->addproperty('filename', 'field_text');

$this->addproperty('filepath', 'field_file');