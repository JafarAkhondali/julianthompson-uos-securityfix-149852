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

//print_r($uos->output);die();



//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js");
addoutput('resources/script/', "/global/library/jquery/1.9.1/jquery.min.js");
addoutput('resources/script/', "/global/library/jqueryui/1.10.2/jquery-ui.min.js");

addoutput('resources/script/', "/global/library/jquery.hotkeys/default.jquery.hotkeys.js");
addoutput('resources/script/', "/global/library/jquery.growl/javascripts/jquery.growl.js");
addoutput('resources/script/', "/global/library/jquery.growl/javascripts/jquery.growl.js");
addoutput('resources/script/', "/global/displays/entity/class.entity.js");
addoutput('resources/script/', "/global/relocate/script/jquery.uos.js");
addoutput('resources/script/', "/global/relocate/script/jquery.base.js");
//addoutput('resources/script/', "http://mrdoob.github.io/three.js/build/three.min.js");
//addoutput('resources/script/', "http://mrdoob.github.io/three.js/examples/js/libs/tween.min.js");
//addoutput('resources/script/', "http://mrdoob.github.io/three.js/examples/js/controls/TrackballControls.js");
//addoutput('resources/script/', "http://mrdoob.github.io/three.js/examples/js/renderers/CSS3DRenderer.js");
addoutput('resources/script/', "/global/library/mrdoob-three.js/build/three.min.js");
addoutput('resources/script/', "/global/library/mrdoob-three.js/examples/js/libs/tween.min.js");
addoutput('resources/script/', "/global/library/mrdoob-three.js/examples/js/controls/TrackballControls.js");
addoutput('resources/script/', "/global/library/mrdoob-three.js/examples/js/renderers/CSS3DRenderer.js");
//addoutput('resources/style/', "http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
addoutput('resources/style/', "/global/library/font-awesome/css/font-awesome.css");
addoutput('resources/style/', "/global/library/jquery.growl/stylesheets/jquery.growl.css");
addoutput('resources/style/', "/global/relocate/style/uos.css");

//print_r($uos->universe->getactions());

?>
<html>

	<head>
		<title><?php print $uos->title;?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php foreach($uos->output['resources']['script'] as $script) : ?>
		<script src="<?php print $script;?>"></script>		
		<?php endforeach; ?>
		
		<?php foreach($uos->output['resources']['style'] as $stylesheet) : ?>
		<link rel="stylesheet" href="<?php print $stylesheet;?>"></link>		
		<?php endforeach; ?>		

	</head>
	
	<body>
	
	
		<div id="universetoolbar">
			<ul id="universe-status">
				<li class="field-icon-container" id="universe-status-icon">
					<div class="field-icon">
						<span class="fa-stack fa-lg">
						<i class="fa fa-stack-2x" id="universe-selected-count" class="universe-selected-count">0</i>
						<!--<i class="fa fa-asterisk fa-stack-1x"></i>-->
						</span>
					</div>
				</li>
				<li id="universe-details">
					<h1><?php print $uos->title;?></h1>
					<div class="field field-tags"><ul><li><i class="fa fa-circle"></i> Work</li><li><i class="fa fa-circle"></i> Policy Connect</li></ul></div>
				</li>
				<li id="universe-user">
					<i class="fa fa-user fa-stack-1x"></i>
				</li>
			</ul>
			<ul id="universe-actions">

			</ul>
		</div>

		<div id="input">
			<h2><i class="fa fa-sign-in"></i> Input</h2>
			<?php print render($uos->input);?>
			<h2><i class="fa fa-sign-in"></i> Log</h2>
			<div id="inputmessages">
			<?php print render($uos->output['log']); ?>
			</div>
			<h2><i class="fa fa-sign-in"></i> Universe Config ($uos->config)</h2>
			<div id="uosobject">
			<?php print render($uos->config);?>
			</div>
		</div>		
		
		<div id="container">
		<?php print render($uos->output['body']);?>
		</div>
		
	</body>

</html>

<pre>

<?php
//$relationship = new relationship();
//print_r($relationship->__gettabledefinition());
//$db = $uos->universe->connect();
//$uos->logtoscreen = TRUE;
//print_r($uos->universe);
//print_r($uos->universe->__gettabledefinition());
//$data = ($nodes[6453127562]);
//$person = new node_person($data);
//print_r($person);
//print_r($person->__gettabledefinition());
//print_r($uos->universe->getentities(array(
//	type => 'node%'
//)));
//$type = 'node_device';

//$fieldtypename = 'field_text';
//$field = new $fieldtypename();
//$field = new field_gid();
//$field = new field_number();
//print_r($field);



//$uos->user->title = 'Julian Thompson';
//addoutput('input/', $uos_input);
//print_r($uos->config->data);die();
//$entity->content['body'][1]->children[] = $entity->content['body'][0];
