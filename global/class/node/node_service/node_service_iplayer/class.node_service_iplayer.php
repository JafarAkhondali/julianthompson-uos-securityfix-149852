<?php
# node_url class definition file
class node_service_iplayer extends node_service {
	
	public function fetchchildren() {

		$this->addproperty('output','field_text',array('value'=>''));

	  $command = sprintf('/usr/bin/get_iplayer --listformat="child-<pid>||<name>||<episode>||<channel>||<categories>"');

	  //execute the command
	  //echo $command;
		$commandoutput = array();
	  $response = exec($command,$commandoutput);
	  
	  $programmes = array();
	  
	  foreach($commandoutput as $lineno=>$line) {
			//$this->output->value .= debuginfo($line);	  	
	  	if (preg_match("/^child-(.*)$/",$line,$matches)>0) {
	  	//if (preg_match("/(\:+):\s*([^,]*),\s*([^,]*),\s*(.*)/",$line,$matches)>0) {
	  		//$this->output->value .= debuginfo($matches);
	  		$matches = explode('||',$matches[1]);
	  		/*
	  		$programmes[($matches[0])] = new node(array(
	  			'guid'=>$this->guid->value.'['.$matches[0].']',
	  			'title'=>$matches[1],
	  			'channel'=>$matches[2],
	  			'episode'=>$matches[3],
	  			'tags'=>explode(',',$matches[4])
	  		));*/
				$this->addchild(new node(array(
		  			'guid'=>$this->guid->value.'['.$matches[0].']',
		  			'title'=>$matches[1],
		  			'channel'=>$matches[2],
		  			'episode'=>$matches[3],
		  			'tags'=>explode(',',$matches[4])
		  	)), $matches[0]);
			}
			if ($lineno>50) break;
		}
		
		//$this->output->value .= debuginfo($commandoutput);
		//$this->addproperty('programmes','field_text',array('value'=>debuginfo($programmes,TRUE)));
	  return $commandoutput;
	}
} 