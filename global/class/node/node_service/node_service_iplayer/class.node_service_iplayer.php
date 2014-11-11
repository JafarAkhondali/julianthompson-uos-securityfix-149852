<?php
# node_url class definition file
class node_service_iplayer extends node_service {
	
	public function fetchchildren() {
	  $command = sprintf('/usr/bin/get_iplayer');

	  //execute the command
	  //echo $command;
		$commandoutput = array();
	  $response = exec($command,$commandoutput);
	  
	  /*
	  foreach($tweets as &$tweet) {
			$this->children[] = new node_message_tweet(array(
				'body'=> $tweet->text,
				'messageid'=> $tweet->id,
				'title'=> $tweet->text,
				'created'=> $tweet->created_at,
				'modified'=> $tweet->created_at 
			));
		}*/
		
		$this->addproperty('imap','field_text',array('value'=>$response));
	  return $commandoutput;
	}
} 