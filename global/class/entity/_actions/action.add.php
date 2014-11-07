<?php

if (isset($parameters['uploadedfiles'][0])) {
	$output = $parameters['uploadedfiles'][0];
	$guid = $universe->add($output);
} else if (isset($parameters['type'])) {

	$type = $parameters['type'];
	$typeconfig = $uos->config->types[$type];
	$output = new $type($parameters);
	$output->addproperty('target', 'field_text', array('value'=>$this->guid));
	$output->addproperty('action', 'field_text', array('value'=>'add'));
	$output->addproperty('display', 'field_text', array('value'=>'uosio'));
	//$output->removeproperty('type');
	$output->type->locked=FALSE;
	//$output->addproperty('type', 'field_text', array('value'=>$parameters['type']));
	$output->addproperty('sourceid', 'field_text', array('value'=>$parameters['sourceid']));
	
	$output->addproperty('info','field_text', array('value'=>implode(',',array_keys($output->invalidproperties()))));
	
	if (!$output->isvalid())	{
		$output->displaystring = 'edit.html';
	} else {
		$output->removeproperty('target');
		$output->removeproperty('action');
		$output->removeproperty('display');
		$output->removeproperty('sourceid');
		$output->removeproperty('info');
	  $guid = $universe->add($output);
		//$universe->tagcontent($this, array($file->id->value));		
	}

} else {
	$typelist = array();
	foreach($uos->config->types as $key => $type) {
	
		//if (is_subclass_of($key, UOS_BASE_CLASS)) {
		//if (is_creatable_entity($key)) {
		if (in_array($key,array(
			'unknown',
			'double',
			'integer',
			'object',
			//'stdclass',
			//'entity',
			'array',
			'string',
			'relationship',
			'node_universe'))) continue;
		if (is_subclass_of($key, 'node')) {
			$class = new ReflectionClass($key);
			if (!$class->isAbstract()) {
				//$typelist[$key] = '<option value="'.$key.'"><i class="fa fa-'.$type->icon.'"></i> <span class="title">' . $type->title . '</span></option>';
				$typelist[$key] = '<option value="'.$key.'">' . $type->title . '</option>';
			}
		}
		//}
		//$type->icon;
	}

	$output = new node_form();

	$output->title = "Add to " . $this->title . '?';
	
	$output->description = '<p>What do you want to add to '.$this->title.'</p><select name="type">' . implode("\n",$typelist) . '</select>';
	
	$output->action = 'add';
	$output->target = $this->guid->value;
	$output->sourceid = $parameters['sourceid'];
	
	$output->addproperty('file', 'field_file', array());
	$output->addproperty('uploadedurl', 'field_text', array('value'=>'unset'));
	$output->addproperty('confirm', 'field_boolean', array('value'=>0));
	$output->addproperty('parameters', 'field_text', array('value'=>print_r($parameters,TRUE), 'usereditable'=>FALSE));
	$output->displaystring = 'edit.html';

	//$output->created->setvalue(now);
}


addoutput('content/', $output);
