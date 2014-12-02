<?php
$this->addproperty('action', 'field_text', array('value'=>'test'));
$mimetypes = print_r($this->mimeoutputtypes(),TRUE);
$this->addproperty('information', 'field_text', array('value'=>$mimetypes));
$mimeout = $this->getasmime('image/jpeg');
$this->addproperty('mimeoutput', 'field_text', array('value'=>$mimeout));
$this->addproperty('entitytable', 'field_text', array('value'=>print_r($universe->db_describe_table('entity'),TRUE) ));
$this->addproperty('entitystruct', 'field_text', array('value'=>print_r($universe->___gettabledefinition(),TRUE) ));


addoutput('content', $this);
//$testcode = '<?php new '; // will cause a syntax error