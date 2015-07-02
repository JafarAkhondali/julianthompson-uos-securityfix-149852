<?php 
if (isset($entity['content'])) {
	$content = $entity['content'];
	//print_r($content);
} 
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
//print render($content,$display);
print render($content,'html');//$display);