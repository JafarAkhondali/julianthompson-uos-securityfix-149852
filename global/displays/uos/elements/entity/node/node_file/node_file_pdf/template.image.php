<?php

	$childindex = (isset($uos->request->parameters['childindex']))?$uos->request->parameters['childindex']:1;

  $childindexpadded = sprintf('%04d', $childindex);

	$targetpath = $entity->cachepath($render->displaystring.'/');
	$targetfilename = 'page-'.$childindexpadded.'.png';

	$sourcefilename = $entity->filepath->fullpath();
	
	$sourcefilefield = $entity->filepath;
	

  if (!file_exists($targetpath)) {
		@mkdir($targetpath,0777,TRUE);
		//die('here');
	}
	

	
  //$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -o "%spage-%%04d.png" -r144 "%s"; sudo chmod -R 775 "%s"',$targetpath,$filename,$targetpath);	

	if (!file_exists($targetpath . $targetfilename)) {
  	$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -dUseCIEColor=true -dFirstPage=%d -dLastPage=%d -o "%spage-%%04d.png" -r144 "%s"',$childindex,$childindex,addslashes($targetpath),addslashes($sourcefilename));
  	
  	//$command .= sprintf(';sudo chmod -R 775 %s',addslashes($targetpath);

	  //execute the command
		$commandoutput = array();
	  $response = exec($command,$commandoutput);
	  //print_r($commandoutput);
	  //echo $command;die();
	}  

	$pagefiles = find_files($targetpath, 'page-.*\.png', TRUE);
	//print_r(find_files($targetpath, '.*',TRUE));
	//print_r($targetpath);
  sort($pagefiles);
  header("HTTP/1.1 200 OK");
  //print_r($pagefiles);die();
  //print_r($pagefiles);

  header("Content-type: image/png; charset=utf-8");
  //uos_header('Content-Disposition: attachment; filename="test.png"');
  readfile($pagefiles[0]);
  unset($render->display->wrapper);