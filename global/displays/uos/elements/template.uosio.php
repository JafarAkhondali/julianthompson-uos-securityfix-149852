<?php 
if (isset($entity['content'])) {
	$content = $entity['content'];
	//print_R($entity->con)
} 
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
print render($content,'html');//$display);