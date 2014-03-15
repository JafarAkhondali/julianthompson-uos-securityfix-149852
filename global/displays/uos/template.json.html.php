<?php print json_encode( (object) array(
	'content'=>render($entity),
	'resources'=>$uos->output['resources']
));?>