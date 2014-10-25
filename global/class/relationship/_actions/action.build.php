<?php
$this->addproperty('relation', 'field_text',array('maxlength'=>1));
$this->addproperty('parent', 'field_number');
$this->addproperty('child', 'field_number');
$this->addproperty('weight', 'field_number');
$this->setuniqueproperty('parent','child');