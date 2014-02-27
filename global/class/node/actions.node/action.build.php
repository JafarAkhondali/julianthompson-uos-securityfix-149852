<?php
$this->addproperty('title', 'field_text', array('maxlength'=>255,'required'=>TRUE));
$this->addproperty('created', 'field_datetime');
$this->addproperty('modified', 'field_datetime');
//trace($this);

//$outarray[] = $this->newproperty('id',$);
//$this->setindexproperty('id');  