<?php
# node_device class definition file
$uos->config->types['node_device_xbmc']->title = 'Media Center';
$uos->config->types['node_device_xbmc']->titleplural = 'Media Centers';
$uos->config->types['node_device_xbmc']->description = 'An XBMC / KODI media center';

class node_device_xbmc extends node_device {
  
  
	public function fetchchildren() {
		global $uos;
		
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
		   	$response = $rpc->Player->GetActivePlayers();
		} catch (XBMC_RPC_Exception $e) {
		    die($e->getMessage());
		}
		
		$tags = array();
		
		
		if (empty($response)) {
			$this->commands->value .= sprintf('<p>Nothing Playing</p>');	
		} else {
			$playerid = $response[0]['playerid']; // 0 Video
			try {
				$response = $rpc->Player->GetItem(array('playerid'=>$playerid, 'properties'=>array('title','thumbnail','channelnumber','description')));

				//$this->commands->value .= urldecode("http://192.168.1.200:8888/image/image%3A%2F%2F%252fhome%252fxbmc%252f.xbmc%252fuserdata%252faddon_data%252fpvr.mythtv.cmyth%252fcache%252fchannels%252ffilm4.jpg%2F");
				
				//$this->commands->value .= "<img src='http://192.168.1.200:8888/image/image%3A%2F%2F%252fhome%252fxbmc%252f.xbmc%252fuserdata%252faddon_data%252fpvr.mythtv.cmyth%252fcache%252fchannels%252ffilm4.jpg%2F'/>";
        
        $thumbnailurl = $response['item']['thumbnail'];//
        $thumbnailurl = urlencode($response['item']['thumbnail']);
        
				//$this->commands->value .= "<p><img src='http://" . $params . "/image/" . $thumbnailurl . "' width='100%'></p>";   
				
				$this->commands->value .= sprintf("<p><img src='http://%s/image/%s' width='100%%'></p>", $params, $thumbnailurl);  			 

				$this->commands->value .= sprintf('<p>%s</p><p>%s</p><p>%s</p><p>%s</p>',$uos->request->parameters['channel'],$response['item']['label'], $response['item']['title'], $response['item']['type']);
				
				
				$shorttitle = array_shift(explode(' - ',$response['item']['title'], 2));
				
				$shorttitle = preg_replace("/[^A-Za-z0-9 ]/", '', $shorttitle);
				//$tags[] = '#Escapetothecountry';
				
				switch($response['item']['type']) {
					
					case 'channel':
						// Channel
						//$tags[] = '#'.str_replace(' ', '', ucwords($response['item']['label']));
						// Programme name
						$tags[] = '#'.str_replace(' ', '', ucwords($shorttitle));
						//$tags[] = '#BBCOne';
						//$tags[] = '#ITV2';
					break;
					
					case 'movie':
						$tags[] = '#'.str_replace(' ', '', ucwords($shorttitle) . 'Movie');
					break;
				
				}
    
			} catch (XBMC_RPC_Exception $e) {
			    die($e->getMessage());
			}

			
			try {
				$response = $rpc->Player->GetProperties(array('playerid'=>$playerid, 'properties'=>array('time', 'totaltime', 'position', 'percentage', 'repeat', 'shuffled')));
				$this->commands->value .= sprintf("<p><div style='background-color:black;border-radius:20px;width:100%%;height:30px;overflow:hidden;'><div style='width:%s%%;background-color:green;height:100%%;'></div></div></p>\n<p>%02d:%02d:%02d / %02d:%02d:%02d</p>",$response['percentage'],$response['time']['hours'],$response['time']['minutes'],$response['time']['seconds'],$response['totaltime']['hours'],$response['totaltime']['minutes'],$response['totaltime']['seconds']);
				//$starttimestring = sprintf("-%d hours %d minutes %d seconds",$response['time']['hours'],$response['time']['minutes'],$response['time']['seconds']);
				//$starttime = strtotime('-1 hour');//$starttimestring);
				$starttime = time() - ( ($response['time']['hours']*60*60) + ($response['time']['minutes']*60) + ($response['time']['seconds']));
				//$this->commands->value .= sprintf('<p>$rpc->Player->GetProperties "%s"</p>',print_r($response,TRUE));
				$this->commands->value .= sprintf('<p>Time now : %s (%s)</p><p>Start time : %s (%s)</p>',time(),date('Y-m-d H:i'),$starttime,date('Y-m-d H:i',$starttime));
			} catch (XBMC_RPC_Exception $e) {
			    die($e->getMessage());
			}
		}
		



		
		/*		
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
		*/
		
		if (isset($uos->request->parameters['tweets'])) {
			// need a better way to find a twitter account
			$twitterentity = $uos->request->universe->db_select_entity(1519692434878367);
			if ($twitterentity) {
				//$this->commands->value .= 'Got twitter entity';
				
				$filter = (object) array(
					'tags'=>$tags,
					//'tags'=>array('#BBCOne'),
					'start'=>$starttime			
				);
				
			
				$tweets = $twitterentity->children($filter);
				//$this->commands->value .= $tweets;
				//$this->commands->value .= sprintf('<p>Tags : %s</p><p>%s</p>',print_r($filter,TRUE),print_r($tweets,TRUE));	
							
				foreach($tweets as $tweet) {
						//break;
						$response = $rpc->GUI->ShowNotification(array(
							'title'=> $tweet->title->value,
							'message'=> $tweet->body->value,
							'displaytime'=>10000,
							'image'=>$tweet->imageurl->value
							//$tweet->sourceid, 
							//'message'=>'XXX',//$tweet->body, 
							//'displaytime'=>10000,
							//'image'=>$tweet->user->profile_image_url_https,
						));
						//$this->commands->value .= sprintf('<p>%s</p>',print_r($tweets,TRUE));
						$this->children[] = $tweet;
					//}
					//sleep(1);
					sleep(5);
					
				}
				//$this->commands->value .= sprintf('<p>%s</p>',print_r($tweets,TRUE));
				
				
			} else {
				$this->commands->value .= 'Not got twitter entity';		
			}
		}

		//image
		if (isset($uos->request->parameters['image'])) {
			$response = $rpc->Player->Open(array('item'=>array('file'=>'http://developer:fr432ws@epsom.universeos.net/global/displays/default/elements/_resources/images/backdrop-prof.jpg')));
		
			//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://developer:fr432ws@epsom.universeos.net/global/displays/default/elements/_resources/images/uos-logo-144.png')));
			//'http://developer:fr432ws@epsom.universeos.net/6783826915427419.image')));
			//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://developer:fr432ws@epsom.universeos.net/6783826915427419.image')));
			//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://developer:fr432ws@epsom.universeos.net/cache/epsom.universeos.net/node_file/3613475726851892/image/page-0001.png')));
			//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://developer:fr432ws@epsom.universeos.net/cache/epsom.universeos.net/node_file/6783826915427419/image/page-0001.png')));
		}
		
		
		
		//camera image
		if (isset($uos->request->parameters['cameraimage'])) {
			$response = $rpc->Player->Open(array('item'=>array('file'=>'http://admin:dli1jumper@greenacres.universeos.net:800/image.jpg')));
		}
		
		
		
		//camera video feed
		if (isset($uos->request->parameters['camerafeed'])) {
		//http://IPADDRESS/mjpeg.cgi?user=[USERNAME]&password=[PASSWORD]&channel=[CHANNEL]
		//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://admin:dli1jumper@greenacres.universeos.net:800/mjpeg.cgi')));
		//$response = $rpc->Player->Open(array('item'=>array('file'=>'rtsp://admin:dli1jumper@greenacres.universeos.net:554/play1.sdp')));
		//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://admin:dli1jumper@greenacres.universeos.net:800/videostream.cgi')));
		//$response = $rpc->Player->Open(array('item'=>array('file'=>'http://admin:dli1jumper@greenacres.universeos.net:800/mjpeg.cgi?channel=0&dummy=.mjpg')));
		
		$response = $rpc->Player->Open(array('item'=>array('file'=>'http://admin:dli1jumper@greenacres.universeos.net:800/video.cgi')));
		}

		
		//youtube
		//$vid = 'dDCGL9tRDEc';		
		//$vid = 'UReaVnUODX0';
		//$vid = 'YflagIfVVec';
		//$response = $rpc->Player->Open(array('item'=>array('file'=>'plugin://plugin.video.youtube/?action=play_video&videoid='.$vid)));
		
		
		
		//channel
		//38 = Dave
		//39 = 4+1
		//40 = More 4
		//41 = Film 4
		//42 = QVC
		//43 = Really
		//44 = 4Music
		if (isset($uos->request->parameters['iplayerpid'])) {
			//		{ "jsonrpc": "2.0", "method": "XBMC.Play", "params": "plugin://plugin.video.iplayer/?pid=b00jz2t4", "id": 1 }
			$command = sprintf("plugin://plugin.video.iplayer/?resolveURL=True&pid=%s",$uos->request->parameters['iplayerpid']);
			$this->commands->value .= $command;	
			try {
				$response = $rpc->Player->Open(array('item'=>array('file'=>$command)));
				//$response = $rpc->Player->Open(array('item'=>array('channelid'=>$uos->request->parameters['channel'])));
		  } catch (XBMC_RPC_Exception $e) {
		    die($e->getMessage().':'.print_r($uos->request->parameters,TRUE));
			}		
		}
		
		if (isset($uos->request->parameters['channel']) && is_numeric($uos->request->parameters['channel'])) {
			try {
				$response = $rpc->Player->Open(array('item'=>array('channelid'=>intval($uos->request->parameters['channel']))));
				//$response = $rpc->Player->Open(array('item'=>array('channelid'=>$uos->request->parameters['channel'])));
		  } catch (XBMC_RPC_Exception $e) {
		    die($e->getMessage().':'.print_r($uos->request->parameters,TRUE));
			}
		}
		//$this->commands->value .= print_r($response,TRUE);
		//$response = $rpc->JSONRPC->GUI->ShowNotification(array('title'=>'A tweet about this programme', 'message'=>'Blah de blah'));
		//$this->fetchentitytweets();
	}


} 