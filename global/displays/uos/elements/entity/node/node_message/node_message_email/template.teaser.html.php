<iframe src="data:text/html;base64, <?php print base64_encode(render($entity->bodyhtml,'html'));?>" />
<?php print render($entity->body,'html');?>
<div class="wrapper-info">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
</div>