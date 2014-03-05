<?php 

include_once "./core/core.php";

foreach($uos->config->data->entities as $guid=>$propertyobject) {

	$entity = fetchentity($guid);
	//$type = isset($propertyobject->type)?$propertyobject->type:'StdClass';
	//$entity = new $type($propertyobject);
	//foreach($childlist as $key) {
	//	$entity->children[] = ;
	//}
	if ($entity) addoutput('body/', $entity);
}


// compress output
ob_start("ob_gzhandler");

?>
<!-- UniverseOS-->
<!-- 
<?php print_r($uos->request);?>
-->
<?php
//die();
include "./relocate/templates/template.page.uos.html.php";