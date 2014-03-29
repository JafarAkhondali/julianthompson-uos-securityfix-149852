<?php

$this->addproperty('id', 'field_number');
$this->addproperty('guid', 'field_gid');
$this->addproperty('type', 'field_text', array('maxlength'=>100));
$this->type->value = get_class($this); 