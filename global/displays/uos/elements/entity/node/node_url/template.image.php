<?php

		$targetpath = $entity->cachepath($render->displaystring.'/');

		//$targetpath = $render->cachepath . $entity->guid->value . '/url/';
		
		$targetprefix = '1024x1000';

		$targetfilename = $targetpath.$targetprefix.'.png';
		
		//$filename = $targetpath.$targetprefix.'-clipped.png';

		$url = (string) $entity->url;
		//$url = "http://stackoverflow.com/questions/7878784/php-mkdir-permissions";
		//$url = 'http://www.google.com';// (string) $entity->url



	  if (!file_exists($targetpath)) {
	  	umask(0);
			@mkdir($targetpath,0777,TRUE);
			if (!file_exists($targetpath)) {
				 //echo "FUCK";
			}
			//die($targetpath);
		}	
		
			
		
		if (!file_exists($targetfilename)) {
			//macbook
  		//$command = sprintf('/usr/local/bin/webkit2png -F -W 1000 -H 1000 --dir=%s -o %s %s; chmod -R 755 %s',$targetpath,$targetfilename,$url,$targetpath);
  		//ubuntu version
  		///usr/local/bin/webkit2png -x 1000 1000 -o /uos/universes/uos1-001/data/cache/epsom.universeos.net/node_url/2336475498186257/image/1000x1000.png http://www.atomicant.co.uk/services
  		//$command = sprintf('/usr/local/bin/webkit2png -x 1024 1024 -g 1024 1024 -o "%s" "%s"; chmod -R 777 "%s"',$targetfilename,$url,$targetpath,$targetpath);
  		$command = sprintf('cd "%s"; /usr/local/bin/webkit2png -x 1024 1024 -g 1024 1024 -o "%s" "%s"; chmod -R 777 "%s"',$targetpath,$targetfilename,$url,$targetpath,$targetpath);
  		//clipped
   		//$command = sprintf('/usr/local/bin/webkit2png -C -W 1000 -H 1000 --clipwidth=1000 --clipheight=1000 --dir=%s -o %s %s; chmod -R 755 %s',$targetpath,$targetprefix,$url,$targetpath);
		  //execute the command
		  //echo $command;
		  //echo $filename;
			$commandoutput = array();
		  $response = exec($command,$commandoutput,$returnvar);
			//$response = exec('whoami',$commandoutput,$returnvar);
  		//print_r($targetpath);
  		//print "<hr/>";
	  	//print_r($command);		
	  	//print_r($returnvar);		  
		  //print_r($commandoutput);
  	}

		if (file_exists($targetfilename)) {
	  	//print_r($targetfilename);
  		header("Content-type: image/png; charset=utf-8");
  		header("HTTP/1.1 200 OK");
  //uos_header('Content-Disposition: attachment; filename="test.png"');
  		readfile($targetfilename);
  		//unlink($targetfilename);
  		//echo "ARSE";
  	}
   	unset($render->display->wrapper);