<i class="fa fa-<?php print $render->entityconfig->icon;?> fa-stack-1x"></i> 
<?php print render( ( isset($entity->properties['title']) ? $entity->properties['title'] : $entity->type ) ,'html'); ?>

<?php 
$imagepath = FALSE;
switch ($entity->mime) {
	case "image/gif" :
	case "image/png" :
	case "image/jpeg" :
	case "image/svg+xml" :
		$imagepath = $entity->dataurl() . $entity->filepath->value;//->value;
	break;
	
	case "application/pdf" :
		$imagepath = '/'.$entity->guid->value . '.image';
	break;
	
	default :
		if (isset($entity->properties['mime'])) print_r($entity->mime->value);
	break;
}
?>
<?php if ($imagepath) : ?>
<img src="<?php print $imagepath;?>" width="100%">
<?php endif; ?>
<?php if (isset($entity->properties['body'])) : ?>
<?php print render($entity->properties['body'],'html'); ?>
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