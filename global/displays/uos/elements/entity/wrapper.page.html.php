<?php
//$scripts = array_unique($uos->output['resources']['script']);
//$styles = array_unique($uos->output['resources']['style']);
$scripts = $uos->output['resources']['script'];
$styles = $uos->output['resources']['style'];
?>
<!-- UniverseOS-->
<!-- 
<?php print_r($uos->request);?>
-->
<<?php print $render->wrapperelement;?> <?php print display_uos_attributestostring($render->attributes);?>>

<head>
	<title><?php print $render->title;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="icon" type="image/x-icon" href="<?php print $render->rendererpath . '/elements/_resources/images/favicon.ico';?>">
<?php foreach($scripts as $script) : ?>
	<script src="<?php print $script;?>"></script>		
<?php endforeach; ?>
	
<?php foreach($styles as $stylesheet) : ?>
	<link rel="stylesheet" href="<?php print $stylesheet;?>"></link>		
<?php endforeach; ?>		
</head>
	
<body>
<?php print $render->templateoutput;?>
</body>

<script>
uos.elements = <?php print json_encode($uos->output['elementdata']);?>;
</script>

</<?php print $render->wrapperelement;?>>
