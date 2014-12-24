<?php 

$childindex = (isset($uos->request->parameters['childindex']))?$uos->request->parameters['childindex']:1;
$targetpath = $entity->cachepath($render->displaystring.'/');
$childindexpadded = sprintf('%04d', $childindex);
$sourcefilename = $entity->filepath->fullpath();

switch($entity->mime) {

	case  'application/pdf' :
	
		//imagepng(display_uos_make_visual($entity));

	
	  
	
		$targetfilename = 'page-'.$childindexpadded.'.png';
	

		
		$sourcefilefield = $entity->filepath;
	
	  if (!file_exists($targetpath)) {
	  	umask(0);
			@mkdir($targetpath,0777,TRUE);
		}
	  //$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -o "%spage-%%04d.png" -r144 "%s"; sudo chmod -R 775 "%s"',$targetpath,$filename,$targetpath);	
	
		if (!file_exists($targetpath . $targetfilename)) {

			if (!file_exists($sourcefilename)) die('No source');
			if (!file_exists($targetpath)) die('No target');	

			//$im = new imagick('file.pdf[0]');	
			
			$command = sprintf('/usr/bin/convert -colorspace sRGB -density 144 -channel rgba -alpha on "%s[%d]" "PNG32:%spage-%04d.png"',addslashes($sourcefilename),$childindex-1,addslashes($targetpath),$childindex);
				
	  	//$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -dUseCIEColor=true -dFirstPage=%d -dLastPage=%d -o "%spage-%%04d.png" -r144 "%s"',$childindex,$childindex,addslashes($targetpath),addslashes($sourcefilename));
	  	
	  	//$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -dUseCIEColor=true -dFirstPage=%d -dLastPage=%d -o "%spage-%%04d.png" -r144 "%s"',$childindex,$childindex,addslashes($targetpath),addslashes($sourcefilename));

	  	//$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -dUseCIEColor=true -dFirstPage=%d -dLastPage=%d -o "%spage-%%04d.png" -r144 "%s"',$childindex,$childindex,$targetpath,$sourcefilename);


	  	
	  	//$command .= sprintf('chmod -R 777 "%s"',$targetpath);
	
		  //execute the command
			$commandoutput = array();
		  $response = exec($command,$commandoutput,$response);
		  //$response = exec(escapeshellcmd($command),$commandoutput);
		  //print_r($commandoutput);
		  //echo $command;print_r($commandoutput);die();
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
  
 break;
 
 case 'image/vnd.adobe.photoshop' :
		$childindex = (isset($uos->request->parameters['childindex']))?$uos->request->parameters['childindex']:1;
		//$targetfilename = $targetpath.'image.png';	
		$targetfilename = $targetpath.'page-'.$childindexpadded.'image.png';	
	  if (!file_exists($targetpath)) {
			@mkdir($targetpath,0777,TRUE);
			//die('here');
		} 
		//die('photoshop image');
		if (!file_exists($targetfilename)) {
			$sourcefilename = $entity->filepath->fullpath();
			//convert test.psd[0] -colorspace RGB -resize 200x200 -strip test.jpg
	  	$command = sprintf(UOS_BIN_IM . ' "%s[%d]" -colorspace RGB -resize 200x200 -strip "%s"', $sourcefilename, $childindex-1,  $targetfilename);		
			//die($command);
			$commandoutput = array();
		  $response = exec($command,$commandoutput);
		  print_r($commandoutput);
		  echo $command."\n".print_r($response,TRUE);die();
		}
	  header("Content-type: image/png; charset=utf-8");
	  //uos_header('Content-Disposition: attachment; filename="test.png"');
	  readfile($targetfilename);
		
 break;
 
 default : 
		$filename = $entity->filepath->fullpath();
		//imagepng(display_uos_make_visual($entity));
		readfile($filename);
	break;
}

