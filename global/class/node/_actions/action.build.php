<?php
$this->addproperty('title', 'field_text', array('maxlength'=>255,'required'=>TRUE));
$this->addproperty('created', 'field_datetime', array('locked'=>FALSE));
$this->addproperty('modified', 'field_datetime', array('locked'=>FALSE));
//trace($this);

//$outarray[] = $this->newproperty('id',$);
//$this->setindexproperty('id');  