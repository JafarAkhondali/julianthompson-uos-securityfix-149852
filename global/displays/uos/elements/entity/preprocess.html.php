<?php

include $render->rendererpath . '/elements/preprocess.html.php';

$render->childcount = count($entity->children);

$render->attributes['data-childcount'] = $render->childcount;
$render->attributes['data-uostypetree'] = $render->inheritancestring;
$render->attributes['data-uostype'] = $render->entitytype;
$render->attributes['data-accept'] = '';
$render->attributes['data-display'] = $render->displaystring;
$render->attributes['data-guid'] = (string) $entity->guid;


foreach ($render->displaynames as $displayname) {
	$mimetype = system_extension_mime_type('test.'.$displayname); 
	$render->elementdata->filetypes[] = (object) array(
		'mimetype' => $mimetype,
		'url' => $uos->request->hosturl . (string) $entity->guid . '.' . $displayname
	);
}


//$render->elementdata->typetree = $render->entityconfig->classtree;
//$render->elementdata->type = $render->entityconfig->class;
//$render->elementdata->typeinfo = $render->entityconfig;
//$render->elementdata->activedisplay = $render->displaystring;
//$render->elementdata->displays = $render->formatdisplaynames;
$render->elementdata->displaykey = 'entity';
$render->elementdata->guid = (string) $entity->guid;
//$render->elementdata->typedisplay = $render->entityconfig->class.'.'.$render->displaystring;

$render->attributes['class'][] = ($entity->isvalid()) ? 'uos-valid':'uos-invalid';

if (isset($entity->title->value)) { 
	$render->title = $entity->title->value;
} else {
	$render->title = ucfirst($render->entitytype);
}

//addoutput('elementdata/'.$render->instanceid, $render->elementdata);
//addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/classextend.js");
addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/display.entity.js");
//addoutputunique('resources/script/', $render->rendererurl."elements/entity/_resources/script/display.entity.html.js");
addoutputunique('resources/style/', $render->rendererurl."elements/entity/_resources/style/style.html.css");