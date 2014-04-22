<script>

	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	
	//function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer();
	  var mapOptions = {
	    zoom: 8,
	    scrollwheel: false,
	    center: new google.maps.LatLng(<?php print (string) $entity->latitude;?>,<?php print (string) $entity->longitude;?>)
	  };
	  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	  directionsDisplay.setMap(map);
	//}
	
	function calcRoute() {
	
	  //'de6 1ph'
	  uos.log(uos.session.coordinates);
	  var start = new google.maps.LatLng(uos.session.coordinates.latitude, uos.session.coordinates.longitude);
	  //var start = document.getElementById('start').value;
	  
	  //'kt19 8sl'
	  var end = new google.maps.LatLng(<?php print (string) $entity->latitude;?>,<?php print (string) $entity->longitude;?>);
	  //var end = document.getElementById('end').value;
	  var request = {
	      origin:start,
	      destination:end,
	      travelMode: google.maps.TravelMode.DRIVING
	  };
	  directionsService.route(request, function(response, status) {
	    if (status == google.maps.DirectionsStatus.OK) {
	      directionsDisplay.setDirections(response);
	    }
	  });
	}
	
	calcRoute();
	//google.maps.event.addDomListener(window, 'load', initialize);

</script>
<style>
#map-canvas {
  height: 500px;
  width: 100%;
  margin: 0px;
  padding: 0px;
  border:15px solid white;
}
#panel {
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
<div id="panel">
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
<div id="map-canvas"></div>
<!--
<h1><?php print (string) $entity->title;?>(html)</h1>

<div class="clearboth"></div>	
-->