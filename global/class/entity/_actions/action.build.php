<?php

$this->addproperty('id', 'field_number', array('visible'=>TRUE));
$this->addproperty('guid', 'field_gid', array('locked'=>TRUE, 'visible'=>FALSE));
$this->addproperty('type', 'field_text', array('maxlength'=>100, 'locked'=>TRUE, 'visible'=>FALSE));
$this->addproperty('system', 'field_boolean', array('value'=>FALSE, 'visible'=>FALSE));
$this->addproperty('source', 'field_text', array('maxlength'=>100, 'locked'=>TRUE, 'visible'=>FALSE));
$this->setindexproperty('id');
$this->type->value = get_class($this); 