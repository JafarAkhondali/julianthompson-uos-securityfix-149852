<?php
$this->addproperty('mime', 'field_text', array('maxlength'=>255,'required'=>TRUE));
$this->addproperty('size', 'field_number');
$this->addproperty('checksum', 'field_text', array('maxlength'=>16,'required'=>TRUE));
$this->addproperty('filename', 'field_text');
$this->addproperty('filepath', 'field_file');