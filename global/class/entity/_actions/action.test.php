<?php
$this->addproperty('action', 'field_text', array('value'=>'test'));
$mimetypes = print_r($this->mimeoutputtypes(),TRUE);
$this->addproperty('information', 'field_text', array('value'=>$mimetypes));
$mimeout = $this->getasmime('image/jpeg');
$this->addproperty('mimeoutput', 'field_text', array('value'=>$mimeout));
addoutput('content', $this);
$testcode = '<?php new '; // will cause a syntax error