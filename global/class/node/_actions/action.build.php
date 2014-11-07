<?php
$this->addproperty('title', 'field_text', array('maxlength'=>255,'required'=>TRUE));
$this->addproperty('created', 'field_datetime', array('locked'=>FALSE,'usereditable'=>FALSE, 'initialvalue'=>'now'));
$this->addproperty('modified', 'field_datetime', array('locked'=>FALSE,'usereditable'=>FALSE, 'initialvalue'=>'now'));
//trace($this);

//$outarray[] = $this->newproperty('id',$);
//$this->setindexproperty('id');  