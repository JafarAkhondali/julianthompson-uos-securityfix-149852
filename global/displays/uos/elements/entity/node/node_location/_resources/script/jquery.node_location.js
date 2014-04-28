uos.displays['node_location'] = {};

uos.displays['node_location'].extends = uos.displays['node'];

uos.displays['node_location'].actions = {

	directionsfrom : {
		title : 'Directions from',
		icon : 'fa-chevron-circle-left',
		handler : uostype_node_location_directionsfrom			
	},

	directionsto : {
		title : 'Directions to',
		icon : 'fa-chevron-circle-right',
		handler : uostype_node_location_directionsto	
	},
	
	init : {
		title : 'Initialize',
		icon : 'fa-wrench',
		handler : uostype_node_location_initialize					
	},
};


function uostype_node_location_initialize($element) {

	uostype_entity_initialize($element);
  var elementdata = uos.getelementdata($element);
  
  console.log(elementdata); 
  
  
  switch (elementdata.activedisplay) {
  
  	case 'map.html' :
		  //var domelement = $element.get(0);
			var $mapelement = $element.find('.map-canvas');
			var mapelementdom = $mapelement.get(0);
		  var latitude = $mapelement.data('latitude');
		  var longitude = $mapelement.data('longitude');
			var directionsDisplay;
			var directionsService = new google.maps.DirectionsService();
	
			//function initialize() {
			directionsDisplay = new google.maps.DirectionsRenderer();
			var mapOptions = {
			    zoom: 8,
			    scrollwheel: false,
			    center: new google.maps.LatLng(latitude, longitude)
			};
			  
			var map = new google.maps.Map(mapelementdom, mapOptions);
			
			directionsDisplay.setMap(map);

			$element.data('directionsDisplay',directionsDisplay);
			$element.data('directionsService',directionsService);
			//uostype_node_location_calcRoute($element);
	  break;
  }
	uos.log('uostype_node_location_initialize',elementdata);
}

function uostype_node_location_directionsfrom($element) {
  var elementdata = uos.getelementdata($element); 
	uos.log('uostype_node_location_directionsfrom',elementdata);
}

function uostype_node_location_directionsto($element) {
  var elementdata = uos.getelementdata($element); 
	uos.log('uostype_node_location_directionsto',elementdata);
}

	
function uostype_node_location_calcRoute($element) {
	var $mapelement = $element.find('.map-canvas');
	var mapelementdom = $mapelement.get(0);
  console.log($element.find('.map-canvas'));
  var latitude = $mapelement.data('latitude');
  var longitude = $mapelement.data('longitude');	
	var directionsDisplay = $element.data('directionsDisplay');
	var directionsService = $element.data('directionsService');
  //'de6 1ph'
  uos.log(uos.session.coordinates);
  var start = new google.maps.LatLng(uos.session.coordinates.latitude, uos.session.coordinates.longitude);
  //var start = document.getElementById('start').value;
  
  //'kt19 8sl'
  var end = new google.maps.LatLng(latitude,longitude);
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
	
