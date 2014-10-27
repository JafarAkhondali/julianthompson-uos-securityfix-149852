<?php


$tagentityids = $universe->guid_to_id(array($uos->request->parameters['target']));
//$this->children[] = print_r($uos->request->filesd,TRUE);

//foreach($uos->request->files as $file) {

//	$this->children[] = fetchentity('5645342341');//'XXXXX'.print_r($file,TRUE);	
//	$this->children[] = (string) get_class($file);	
//}
//$this->children = $uos->request->files;

trace($tagentityids,'ANALW');
$guid = 'unset';

foreach($uos->request->files as $file) {
	$foutput[] = $file->title->value;
	$guid = $universe->add($file);
	//throw new Exception('Division by zero.');
	//addoutput('content', $guid);
	$universe->tagcontent($file, $tagentityids);
	addoutput('content/', $file);
	//print_r($file);
}


//$testthis = fetchentity(5645342341);

//$this->title->value = "test".count($uos->request->files);

//addoutput('content', $testthis);
//addoutput('content', $foutput);

addoutput('contento', 'dropfiles'.$guid.'--');
//addoutput('universe', $uos->universe);