<?php

		$targetpath = $render->cachepath . $entity->guid->value . '/url/';
		
		$targetprefix = '1000x1000';

		$filename = $targetpath.$targetprefix.'-full.png';
		
		//$filename = $targetpath.$targetprefix.'-clipped.png';

		$url = (string) $entity->url;
		//$url = "http://stackoverflow.com/questions/7878784/php-mkdir-permissions";
		//$url = 'http://www.google.com';// (string) $entity->url



	  if (!file_exists($targetpath)) {
			@mkdir($targetpath,0777,TRUE);
		}		
		
		if (!file_exists($filename)) {

  		$command = sprintf('/usr/local/bin/webkit2png -F -W 1000 -H 1000 --dir=%s -o %s %s; chmod -R 755 %s',$targetpath,$targetprefix,$url,$targetpath);
  		
  		//clipped
   		//$command = sprintf('/usr/local/bin/webkit2png -C -W 1000 -H 1000 --clipwidth=1000 --clipheight=1000 --dir=%s -o %s %s; chmod -R 755 %s',$targetpath,$targetprefix,$url,$targetpath);
  		
	  	//print_r($command);
		  //execute the command
		  //echo $command;
			$commandoutput = array();
		  $response = exec($command,$commandoutput);
		  
		  //print_r($commandoutput);
  	}

		if (file_exists($filename)) {
	  //print_r($filename);
  		header("Content-type: image/png; charset=utf-8");
  		header("HTTP/1.1 200 OK");
  //uos_header('Content-Disposition: attachment; filename="test.png"');
  		readfile($filename);
  	}
   	unset($render->display->wrapper);