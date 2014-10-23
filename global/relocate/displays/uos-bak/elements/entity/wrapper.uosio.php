<?php header("HTTP/1.1 200 OK"); ?>
<?php
$elementdata = isset($uos->output['elementdata'])?$uos->output['elementdata']:array();
print json_encode( (object) array(
	'content'=>$render->templateoutput,
	'xx' => 'yy',
	'elementdata'=>$elementdata,
	'elementcount'=>count($elementdata),
	'resources'=>(isset($uos->output['resources']))?$uos->output['resources']:Array()
));