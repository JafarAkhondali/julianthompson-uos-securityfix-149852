<?php
$this->addproperty('node', 'id', 'field_number');
$this->addproperty('node', 'gid', 'field_gid');
$this->addproperty('node', 'type', 'field_text', array('typelimit'=>''));
$this->type = get_class($this); 
$this->addproperty('node', 'title', 'field_text', array('maxsize'=>255,'required'=>TRUE));
$this->addproperty('node', 'created', 'field_time');
$this->addproperty('node', 'modified', 'field_time');
//$outarray[] = $this->newproperty('id',$);
//$this->setindexproperty('id');  