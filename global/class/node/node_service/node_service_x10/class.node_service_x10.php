<?php
# node_note class definition file
class node_service_x10 extends node_service {

	private $socket = NULL;
	public $status = array();
	private $selected = array();
	public $dummyoutput = FALSE;
	public $log = array();


	public function fetchchildren() {
		$this->addproperty('info','field_text',array('value'=>print_r($response,TRUE)));
		$response = $this->sendcommand(FALSE);
		$this->info->value .= $response;
		if ($response) {
			foreach($response->status as $code => $status) {
			
				$this->children[] = new node_device(array(
					'title'=>'Untitled Device ('.$code.')',
					'x10key'=>substr($code,1),
					'x10housecode'=> substr($code,0,1),
					'source'=>$this->guid->value.'/'.$code
				));
			}
		}
	}
	
	function connect() {
		if (!$this->dummyoutput) {
			if (gettype($this->socket)!='resource') {
				$url = "tcp://{$this->host->value}:{$this->port->value}";
				$this->socket = stream_socket_client($url, $errno, $errstr, 0); 
				$this->info->value = $url . print_r($this->socket,TRUE);
			}  
		}	else {
			$this->info->value = 'Dummy connection';
			$this->socket = FALSE;
		}
		return $this->socket;
	}
	
	function close() {
		if (!$this->dummyoutput) fclose($this->socket); 
		$this->socket = NULL;
	} 
	
	
	function readresponse($length, $end) {
		
		if ($this->dummyoutput) {
			$str = "01/01 20:43:33 Device status\n01/01 20:43:33 House A: 1=1,2=0,3=1\n01/01 20:43:33 House B: 1=0,3=0\n01/01 20:43:33 Security sensor status\n01/01 20:43:33 End status";			
		} else {
			// get file pointer
			$current = ftell($this->socket);
			$str = fread($this->socket, $length);
		} 
		
		$responses = preg_split('/\n|\r/', $str, -1, PREG_SPLIT_NO_EMPTY);

		foreach($responses as $key=>$response) {
		  $pattern = '@^([0-9]{2}\\/[0-9]{2}\\s[0-9]{2}:[0-9]{2}:[0-9]{2})\\s+(.*)$@i';
			$result = preg_match( $pattern, $response , $matches );
			$responseobj = new StdClass;
			$responseobj->timestamp = strtotime($matches[1]);
			$responseobj->data = trim($matches[2]);
			$responses[$key] = $responseobj;
		}
		return $responses;
	}


	function sendcommand($command) {
		if ($this->connect()) {
	  		if (!$command) $command=array();
	  		if (!is_array($command)) $command = array($command);
	  		$command[] = 'st';
	  		$commandimploded = implode(UOS_NEWLINE, $command) . UOS_NEWLINE;
			if (!$this->dummyoutput) {
				fwrite($this->socket, $commandimploded);
	    			stream_set_timeout($this->socket, 1);
	    			usleep(800);
    			}
    			$responses = $this->readresponse(1500000, "End status");
    			//$this->close(); 
			$this->process_responses($responses);
			$responseobj = new StdClass();
			$responseobj->command = $command;
			$responseobj->response = $responses;
			$responseobj->status = $this->status;
			$responseobj->log = $this->log;
			return $responseobj;
		}
		return FALSE;
	
	}
	
	function logentry($logentry) {
		$this->log[] = $logentry;
	}


	function getstatus() {
		return $this->sendcommand(FALSE);
	}

	
	function setstate($targets,$state,$level=FALSE) {
	  if (!is_array($targets)) {
	  	$targets = array($targets);
	  }
	  $commands = array();
	  $zones = array();
	  foreach($targets as $devicecode) {
	  	if (preg_match('@^([A-Za-z])(\d+)$@',$devicecode,$matches)) {
	  		if ($level!==FALSE) {
	  			//$status[$housecode][($keyvalue[1])]->level
	  			if (!$this->status[$devicecode]) $this->status[$devicecode] = new StdClass;
	  			$targetlevel = (30/100)*$level;
	  			$currentlevel = (30/100)*$this->status[$devicecode]->level;
	  			$delta = intval($targetlevel-$currentlevel);
	  			if ($delta>0) {
	  		  	$commands[] = "pl {$devicecode} bright " . abs($delta);
	  		  } elseif ($delta<0) {
	  		  	$commands[] = "pl {$devicecode} dim " . abs($delta);
	  		  }
	  		  $this->status[$devicecode]->level = intval($level);
	  		} else {
	  			$commands[] = "pl {$devicecode} ".($state?'on':'off');
	  			$this->status[$devicecode]->level = ($state?100:0);
	  		}
	  		$zones[($matches[1])]=TRUE;
	  	}
	  }
	  
	  return $this->sendcommand($commands);
	}

	private function process_responses($responses) {
		$responselen = count($responses);

		for($index=0; $index < $responselen; $index++) {

		  $response = $responses[$index];
		  
			switch($response->data) {
				case 'Device selected':
					//echo "Device selected data :\n";
					$index++;
					$this->selected = array();
					if (isset($responses[$index])) {
						while (preg_match('@^House\s([A-Z]):\s(\d+)$@', $responses[$index]->data, $matches)) {
							//$data = $responses[$index]->data;
							$this->selected[] = strtolower($matches[1]) . $matches[2];  
							//echo print_r($matches,TRUE);
							$index++;
						}
					}
					$index--;
				break;
				
				case 'Device status':
					//echo "Device status data :\n";
					//is last line?
					if (!isset($responses[($index+1)])) break;
					$index++;
					while (preg_match('@^House\s([A-Z]):\s(.*)$@', $responses[$index]->data, $matches)) {
					  $housecode = $matches[1];
						$status = explode(',',$matches[2]);
						foreach($status as $value) {
							if (preg_match('@(\d+)=(\d+)@',$value, $keyvalue)) {
								$devicecode = strtolower($housecode) . $keyvalue[1];
								if (!$this->status[$devicecode] instanceof StdClass) {
									$this->status[$devicecode] = new StdClass;
								}
							  $device = $this->status[$devicecode];
								$device->status = intval($keyvalue[2]);
								if (!isset($device->level)) $device->level = ($device->status==1)?100:0;
								//print_r('y');
							}
						}
						//echo print_r($matches,TRUE);
						$index++;
					}
					$index--;
				break;
				
				case 'Security sensor status':
					//echo "Security sensor status :\n";
					//is last line?
					if (!isset($responses[($index+1)])) break;
					$index++;
					while (preg_match('@^House\s([A-Z]):\s(.*)$@', $responses[$index]->data, $matches)) {
					  $housecode = $matches[1];
						$status = explode(',',$matches[2]);
						foreach($status as $value) {
							if (preg_match('@(\d+)=(\d+)@',$value, $keyvalue)) {
								$devicecode = strtolower($housecode) . $keyvalue[1];
								if (!$this->status[$devicecode] instanceof StdClass) {
									$this->status[$devicecode] = new StdClass;
								}
							  $device = $this->status[$devicecode];
							  
								$device->status = intval($keyvalue[2]);
							}
						}
						$index++;
					}
					$index--;
				break;
				
				case 'End status':
					//echo "End status : OK\n";
				break;
				
				default :
					//echo ($response->data).':';
			}
		}
		return $this->status;
	}


} 
