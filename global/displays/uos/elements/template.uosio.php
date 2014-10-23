<?php 
//echo "xxx";
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
//$content = rendernew($entity,array('displaystring'=>'html'));
//print rendernew($entity,$display);
print rendernew($entity,'html');