<?php
# node_url class definition file
class node_service_iplayer extends node_service {
	
	public function fetchProgrammes() {
	  $command = sprintf('/usr/bin/get_iplayer');

	  //execute the command
	  //echo $command;
		$commandoutput = array();
	  $response = exec($command,$commandoutput);
	  return $commandoutput;
	}
} 