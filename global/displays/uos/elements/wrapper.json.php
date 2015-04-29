<?php header("HTTP/1.1 200 OK"); ?>
<?php
global $uos;
$elementdata = isset($uos->output['elementdata'])?$uos->output['elementdata']:array();
print json_encode( (object) array(
	'content'=>$render->templateoutput,
//	'notifications'=>render($notifications),
	'elementdata'=>$elementdata
));