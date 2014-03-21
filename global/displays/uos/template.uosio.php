<?php print json_encode( (object) array(
	'content'=>render($entity,array('format'=>'html','display'=>$uos->request->outputformat->display)),
	'elementdata'=>$uos->output['elementdata'],
//	'resources'=>$uos->output['resources']
));?>