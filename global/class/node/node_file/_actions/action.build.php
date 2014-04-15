<?php
$this->addproperty('mime', 'field_text', array('maxlength'=>255,'required'=>TRUE));
$this->addproperty('size', 'field_number');
$this->addproperty('checksum', 'field_text', array('maxlength'=>16,'required'=>TRUE));
$this->addproperty('path', 'field_text', array('maxlength'=>255,'required'=>TRUE));