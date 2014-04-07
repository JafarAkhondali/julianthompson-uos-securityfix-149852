<?php 
//echo "xxx";
$display = isset($uos->request->parameters['display'])?$uos->request->parameters['display']:'html';
$content = rendernew($entity,array('displaystring'=>'html'));
$elementdata = isset($uos->output['elementdata'])?$uos->output['elementdata']:array();
print json_encode( (object) array(
	'content'=>$content,
	'elementdata'=>$elementdata,
	'elementcount'=>count($elementdata),
	'resources'=>(isset($uos->output['resources']))?$uos->output['resources']:Array()
));
?>