<div class="overlay">
	<i class="uos-entity-icon"></i> 
	<?php print render($entity->title,'html'); ?>
	<?php print render($entity->address,'html'); ?>
</div>
<div class="visual">
<!--<iframe height="400" src="<?php print (string) $entity->url;?>" frameborder="0" allowfullscreen style="width:100%; height:500px;"></iframe>-->
<!--<img src="/<?php print (string) $entity->guid->value;?>.map.html" width="100%"/>-->
<style>
.map-canvas {
  height: 500px;
  width: 100%;
  margin: 0px;
  padding: 0px;
  border:15px solid white;
}
.map-panel {
  position: absolute;
  left: 30px;
  bottom: 50px;
  xmargin-left: -180px;
  z-index: 5;
  background-color: #fff;
  background-color: rgba(255,255,255,0.8);
  padding: 5px;
}
</style>
<div class="map-panel">
    <b>Start: </b>
    <select id="start" onchange="calcRoute();">
      <option value="de6 1ph, uk">Chicago</option>
      <option value="st louis, mo">St Louis</option>
      <option value="joplin, mo">Joplin, MO</option>
      <option value="oklahoma city, ok">Oklahoma City</option>
      <option value="amarillo, tx">Amarillo</option>
      <option value="gallup, nm">Gallup, NM</option>
      <option value="flagstaff, az">Flagstaff, AZ</option>
      <option value="winona, az">Winona</option>
      <option value="kingman, az">Kingman</option>
      <option value="barstow, ca">Barstow</option>
      <option value="san bernardino, ca">San Bernardino</option>
      <option value="los angeles, ca">Los Angeles</option>
    </select>
    <b>End: </b>
    <select id="end" onchange="calcRoute();">
      <option value="kt19 8sl, uk">Chicago</option>
      <option value="st louis, mo">St Louis</option>
      <option value="joplin, mo">Joplin, MO</option>
      <option value="oklahoma city, ok">Oklahoma City</option>
      <option value="amarillo, tx">Amarillo</option>
      <option value="gallup, nm">Gallup, NM</option>
      <option value="flagstaff, az">Flagstaff, AZ</option>
      <option value="winona, az">Winona</option>
      <option value="kingman, az">Kingman</option>
      <option value="barstow, ca">Barstow</option>
      <option value="san bernardino, ca">San Bernardino</option>
      <option value="los angeles, ca">Los Angeles</option>
    </select>
</div>
</div>
<div class="map-canvas" data-latitude="<?php print (string) $entity->latitude;?>" data-longitude="<?php print (string) $entity->longitude;?>"></div>
<div class="clearboth"></div>	