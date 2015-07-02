<?php header("HTTP/1.1 200 OK"); ?>
<?php

$sourceid = (isset($uos->request->parameters['sourceid']))?($uos->request->parameters['sourceid']):NULL;
$content = render($uos->output['content']);

$elementdata = isset($uos->output['elementdata'])?$uos->output['elementdata']:array();
print json_encode( (object) array(
	'sourceid'=> $sourceid,
	'content'=>$content,
	'targetregion'=> (isset($uos->request->parameters['targetregion']))?($uos->request->parameters['targetregion']):$sourceid,
//	'notifications'=>render($notifications),
	'elementdata'=>$elementdata,
	'elementcount'=>count($elementdata),
	'resources'=>(isset($uos->output['resources']))?$uos->output['resources']:Array()
));