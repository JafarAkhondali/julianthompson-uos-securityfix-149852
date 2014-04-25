<?php 
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
//$content = rendernew($entity,array('displaystring'=>'html'));
//$entity->displaystring = $display;
print rendernew($entity,$display);
?>