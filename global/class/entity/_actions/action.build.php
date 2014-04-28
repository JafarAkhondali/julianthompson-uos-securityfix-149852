<?php

$this->addproperty('id', 'field_number', array('visible'=>FALSE));
$this->addproperty('guid', 'field_gid', array('locked'=>TRUE));
$this->addproperty('type', 'field_text', array('maxlength'=>100, 'locked'=>TRUE));
$this->setindexproperty('id');
$this->type->value = get_class($this); 