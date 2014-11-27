<div class="overlay">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
	<?php print render($entity->address,'html'); ?>
</div>

<!--
<iframe height="400" src="<?php print (string) $entity->url;?>" frameborder="0" allowfullscreen style="width:100%; height:500px;"></iframe>
<img src="/<?php print (string) $entity->guid->value;?>.map.html" width="100%"/> 
-->

<div class="visual">
	<div class="map-panel">
	</div>
	<div class="map-canvas" data-latitude="<?php print (string) $entity->latitude;?>" data-longitude="<?php print (string) $entity->longitude;?>"></div>
</div>
<div class="clearboth"></div>	