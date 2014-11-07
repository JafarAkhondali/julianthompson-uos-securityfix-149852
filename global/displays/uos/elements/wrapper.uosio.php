<?php header("HTTP/1.1 200 OK"); ?>
<?php
global $uos;
$elementdata = isset($uos->output['elementdata'])?$uos->output['elementdata']:array();
print json_encode( (object) array(
	'content'=>$render->templateoutput,
//	'notifications'=>render($notifications),
	'elementdata'=>$elementdata,
	'elementcount'=>count($elementdata),
	'sourceid'=> (isset($uos->request->parameters['sourceid']))?($uos->request->parameters['sourceid']):NULL,
	'resources'=>(isset($uos->output['resources']))?$uos->output['resources']:Array()
));