<!-- UniverseOS-->
<!-- 
<?php print_r($uos->request);?>
-->
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
			</ul>
			<ul id="universe-actions">
			</ul>
		</div>
		
		<div id="container">
		<?php print render($uos->output['body']);?>
		</div>

		<div id="input">
			<h2><i class="fa fa-sign-in"></i> Request</h2>
			<?php print render($uos->request);?>
			<h2><i class="fa fa-sign-in"></i> Log</h2>
			<div id="inputmessages">
			<?php print render($uos->output['log']); ?>
			</div>
			<h2><i class="fa fa-sign-in"></i> Universe Config ($uos->config)</h2>
			<div id="uosobject">
			<?php print render($uos->config);?>
			</div>
		</div>	
		
	</body>

</html>

<script>
uos.elements = <?php print json_encode($uos->output['resources']['json']);?>;
</script>

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
//print_r($uos->output);
//$entity->content['body'][1]->children[] = $entity->content['body'][0];
