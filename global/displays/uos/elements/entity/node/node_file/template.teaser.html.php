
<?php 
$imagepath = FALSE;
switch ($entity->mime->value) {
	case "image/gif" :
	case "image/png" :
	case "image/jpeg" :
	case "image/svg+xml" :
		$imagepath = $entity->dataurl() . $entity->filepath->value;//->value;
	break;
	
	case "application/pdf" :
		$imagepath = '/'.$entity->guid->value . '.image';
	break;
	
}
?>

<div class="overlay">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
	<p><?php print (isset($entity->properties['mime']))?$entity->mime->value:'No mime';?></p>
</div>
<?php if ($imagepath) : ?>
<div class="visual">
	<img src="<?php print $imagepath;?>" width="100%">
</div>
<?php endif; ?>

<?php //print render($entity->getactions()); ?>
<!--
<pre>
<?php print $render->display->preprocess;?>

<?php print $render->display->template;?>

<?php print $render->display->wrapper;?>

<?php print_r($render);?>
</pre>
-->
<div class="clearboth"></div>	