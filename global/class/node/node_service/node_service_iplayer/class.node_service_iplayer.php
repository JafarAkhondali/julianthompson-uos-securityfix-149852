<?php
# node_url class definition file
class node_service_iplayer extends node_service {
	
	public function fetchchildren() {
	  $command = sprintf('/usr/bin/get_iplayer');

	  //execute the command
	  //echo $command;
		$commandoutput = array();
	  $response = exec($command,$commandoutput);
	  
	  $programmes = array();
	  
	  foreach($commandoutput as $line) {
	  	if (preg_match("/(\d+):\s*([^,]*),\s*([^,]*),\s*(.*)/",$line,$matches)>0) {
	  		$programmes[($matches[1])] = array(
	  			'title'=>$matches[2],
	  			'channel'=>$matches[3],
	  			'tags'=>explode(',',$matches[4])
	  		);
			}
		}
		
		$this->addproperty('response','field_text',array('value'=>$response));
		$this->addproperty('programmes','field_text',array('value'=>print_r($programmes,TRUE)));
	  return $commandoutput;
	}
} 