<?php 
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
print render($entity,'html');