<!--<iframe height="400" src="<?php print (string) $entity->url;?>" frameborder="0" allowfullscreen style="width:100%; height:500px;"></iframe>-->
<div class="overlay">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
	<p>Bookmark</p>
</div>
<div class="visual">
	<img src="/<?php print (string) $entity->guid->value;?>.image" width="100%"/>
</div>
<div class="clearboth"></div>	