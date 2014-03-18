<?php print json_encode( (object) array(
	'content'=>render($entity),
	'elementdata'=>$uos->output['elementdata'],
//	'resources'=>$uos->output['resources']
));?>