<?php

//$this->children[] = print_r($uos->request->filesd,TRUE);

//foreach($uos->request->files as $file) {

//	$this->children[] = fetchentity('5645342341');//'XXXXX'.print_r($file,TRUE);	
//	$this->children[] = (string) get_class($file);	
//}

$this->children = $uos->request->files;

//$testthis = fetchentity(5645342341);

//$this->title->value = "test".count($uos->request->files);

//addoutput('content', $testthis);

addoutput('content', $this);