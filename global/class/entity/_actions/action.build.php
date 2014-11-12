<?php

$this->addproperty('id', 'field_number', array(
	'visible'=>FALSE, 
	'usereditable'=>FALSE, 
	'stored'=>TRUE
));

$this->addproperty('guid', 'field_gid', array(
	'locked'=>TRUE, 
	'visible'=>FALSE,
	'usereditable'=>FALSE, 
	'stored'=>TRUE	
));

$this->addproperty('type', 'field_text', array(
	'maxlength'=>100, 
	'locked'=>TRUE, 
	'usereditable'=>FALSE, 
	'required'=>FALSE, 
	'visible'=>FALSE,
	'stored'=>TRUE
));

$this->addproperty('system', 'field_boolean', array(
	'value'=>FALSE, 
	'visible'=>FALSE, 
	'usereditable'=>FALSE, 
	'stored'=>TRUE
));

$this->addproperty('source', 'field_text', array(
	'maxlength'=>100,
	'required'=>FALSE,
	'stored'=>TRUE
	//, 'locked'=>TRUE, 'visible'=>FALSE
));

$this->addproperty('weight', 'field_number', array(
	'required'=>TRUE, 
	'value'=>0, 
	'usereditable'=>FALSE, 
	'stored'=>TRUE
	//, 'locked'=>TRUE, 'visible'=>FALSE));
));
	
	
$this->setindexproperty('id');
$this->type->value = get_class($this); 