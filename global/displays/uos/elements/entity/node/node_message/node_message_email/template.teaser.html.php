<div class="overlay">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
	<p>Email</p>
</div>
<div class="visual" style="overflow:hidden;">
	<iframe width="100%" src="data:text/html;base64, <?php print base64_encode(render($entity->bodyhtml->value,'html'));?>" />
	<?php //print render($entity->body,'html');?>
</div>