<?php
# node_location class definition file
class node_location extends node {
  
	function getgeolocation() {
		if ($this->address->isvalueset()) {
		  $Address = urlencode($this->address->value);
		  $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$Address."&sensor=true";
		  $xml = simplexml_load_file($request_url) or die("url not loading");
		  $status = $xml->status;
		  if ($status=="OK") {
		      $Lat = $xml->result->geometry->location->lat;
		      $Lon = $xml->result->geometry->location->lng;
		      $LatLng = "$Lat,$Lon";
		      $this->latitude->value = (string) $Lat[0];
		      $this->longitude->value = (string) $Lon[0];
		  }
	  }
  }
  
	function afterupdate() {
		$this->getgeolocation();
	}
	
	public function fetchchildren() {
		$this->getgeolocation();	
	}
} 