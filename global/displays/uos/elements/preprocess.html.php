<?php
$render->elementdata->typetree = $render->entityconfig->classtree;
$render->elementdata->type = $render->entityconfig->class;
$render->elementdata->typeinfo = $render->entityconfig;
$render->elementdata->activedisplay = $render->displaystring;
$render->elementdata->displays = $render->formatdisplaynames;

$render->wrapperelement = 'div';


foreach($render->entityconfig->classtree as $type) {
	$entityclassurl = display_build_type_info($entity,$type,TRUE);
	if ($entityclassurl) {
		addoutputunique('resources/script/', $entityclassurl);
	}
	//die($entityclassurl);
}