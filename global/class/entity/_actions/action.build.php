<?php

$this->addproperty('id', 'field_number', array('visible'=>TRUE, 'usereditable'=>FALSE));
$this->addproperty('guid', 'field_gid', array('locked'=>TRUE, 'visible'=>FALSE, 'usereditable'=>FALSE));
$this->addproperty('type', 'field_text', array('maxlength'=>100, 'locked'=>TRUE, 'required'=>FALSE, 'visible'=>FALSE));
$this->addproperty('system', 'field_boolean', array('value'=>FALSE, 'visible'=>FALSE));
$this->addproperty('source', 'field_text', array('maxlength'=>100,'required'=>FALSE));//, 'locked'=>TRUE, 'visible'=>FALSE));
$this->addproperty('weight', 'field_number', array('required'=>TRUE, 'value'=>0));//, 'locked'=>TRUE, 'visible'=>FALSE));
$this->setindexproperty('id');
$this->type->value = get_class($this); 