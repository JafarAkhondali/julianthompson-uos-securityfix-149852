<?php
$this->title->usereditable = FALSE;
$this->addproperty('description', 'field_text', array('maxlength'=>255, 
	'usereditable'=>FALSE, 
));
$this->addproperty('action', 'field_text', array(
	'maxlength'=>50,
	'visible'=>FALSE
));

$this->addproperty('target', 'field_gid', array(
	'visible'=>FALSE
));

$this->addproperty('sourceid', 'field_text', array(
	'maxlength'=>255,
	'visible'=>FALSE
));

//trace($this);

//$outarray[] = $this->newproperty('id',$);
//$this->setindexproperty('id');  