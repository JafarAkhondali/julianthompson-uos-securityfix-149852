<?php
# node_device class definition file
class node_device_xbmc extends node {
  
  
	public function fetchchildren() {
		
		includeLibrary('xbmc-php-rpc');
		
		$params = 'xbmc:xbmc@'.$this->ipaddress.':'.$this->port;
		//require_once 'rpc/HTTPClient.php';
		
		$this->addproperty('connected', 'field_boolean');
		$this->addproperty('commands', 'field_text');
		
		try {
		    $rpc = new XBMC_RPC_HTTPClient($params);
		    $this->connected->setvalue(TRUE);
		} catch (XBMC_RPC_ConnectionException $e) {
		    die($e->getMessage());
				$this->connected->setvalue(FALSE);
		}
		
		$this->connected->setvalue(TRUE);
		
		try {
		    if ($rpc->isLegacy()) {
		        $response = $rpc->System->GetInfoLabels(array('System.Time'));
		    } else {
		        $response = $rpc->XBMC->GetInfoLabels(array('labels' => array('System.Time')));
		    }
		} catch (XBMC_RPC_Exception $e) {
		    die($e->getMessage());
		}
		
		$this->commands->value .= sprintf('<p>The current time according to XBMC is %s</p>', $response['System.Time']);
		
		try {
		    $response = $rpc->JSONRPC->Introspect();
		} catch (XBMC_RPC_Exception $e) {
		    die($e->getMessage());
		}
		$this->commands->value .= '<p>The following commands are available according to XBMC:</p>';
		if ($rpc->isLegacy()) {
		    foreach ($response['commands'] as $command) {
		        $this->commands->value .= sprintf('<p><strong>%s</strong><br />%s</p>', $command['command'], $command['description']);
		    }
		} else {
		    foreach ($response['methods'] as $command => $commandData) {
		        $this->commands->value .=  sprintf(
		            '<p><strong>%s</strong><br />%s</p>',
		            $command,
		            isset($commandData['description']) ? $commandData['description'] : ''
		        );
		    }
		}
		//$this->fetchentitytweets();
	}


} 