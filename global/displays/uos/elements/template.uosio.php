<?php 
if (isset($entity['content'])) {
	$content = $entity['content'];
} 
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
print render($content,$display);