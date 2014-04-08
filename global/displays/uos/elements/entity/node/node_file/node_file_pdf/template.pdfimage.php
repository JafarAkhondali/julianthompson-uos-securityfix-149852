<?php
/*
//ImageMagick convert command
$exec = '/usr/local/bin/convert -append -fuzz 1% -trim -colorspace rgb -background white -density 300 -quality 100 -resize 50%';

//temp file
$tmp = tempnam('','php');
rename($tmp,"$tmp.pdf");
$tmp = "$tmp.pdf";
$out = tempnam('','php');
unlink($out);
$out = "$out.png";

//copy
copy($pdf,$tmp);

//execute the command
exec(sprintf('%s %s %s',$exec,$tmp,$out),$result);
unlink($tmp);

$img=imagecreatefrompng($out);
unlink($out);
return $img;

*/
  //'private/myo2bill12.04.04.pdf.page-%04d.png'
  //ImageMagick convert command
  //$exec = UOS_BIN_IM . ' -append -fuzz 1% -trim -colorspace rgb -background white -density 300 -quality 100 -resize 50%';
  // /usr/local/bin/gs 
  //$exec = UOS_BIN_GS . ' -sDEVICE=pngalpha -o %s.page-%04d.png -r144 %s';
  //echo $exec; die(); 
  
  //temp file
  //$tmp = tempnam('','php');
  //rename($tmp,"$tmp.pdf");
  //$tmp = "$tmp.pdf";
  //$out = tempnam('','pdf');
  //unlink($out);
  //$out = "$out.png";
	$filename = UOS_DATA.$entity->filepath->value;
	$targetpath = $render->cachepath . $entity->filepath->value . '/';
	
	//echo $filename;  
  //copy
  //copy($pdf,$tmp);
  if (!file_exists($targetpath)) {
		@mkdir($targetpath,0777,TRUE);
	}
	
	$childindex = (isset($uos->request->parameters['childindex']))?$uos->request->parameters['childindex']:1;
	
  //$command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -o "%spage-%%04d.png" -r144 "%s"; sudo chmod -R 775 "%s"',$targetpath,$filename,$targetpath);	
	
  $command = sprintf(UOS_BIN_GS . ' -sDEVICE=pngalpha -dFirstPage=%d -dLastPage=%d -o	%spage-%%04d.png -r144 %s; sudo chmod -R 775 %s',$childindex,$childindex,$targetpath,$filename,$targetpath);

  //execute the command
  //echo $command;
	$commandoutput = array();
  $response = exec($command,$commandoutput);
  
  //echo $command;
  //echo basename($pdffile) . '.page-.*\.png';
	$pagefiles = find_files($targetpath, 'page-.*\.png', TRUE);
	
  //print_r($command);print_r($targetpath);print_r($commandoutput);print_r($response);print_r($entity);print_r($pagefiles);return;
	
  sort($pagefiles);
  //print_r($pagefiles);
  header("Content-type: image/png; charset=utf-8");
  header("HTTP/1.1 200 OK");
  //uos_header('Content-Disposition: attachment; filename="test.png"');
  readfile($pagefiles[0]);
  /*
  //$pagefiles = aaprojects_get_file_list(dirname($pdffile),basename($pdffile) . '.page-.*\.png',TRUE);

  //print_r($pagefiles);
  //print_r(array($pdffile.'.page-'.$page.'.png'));
  //print_r($pdffile.'.page-'.$page.'.png');
  //die();
  //return $pagefiles;
  //echo $command;die();  
  //unlink($tmp);

  //$img=imagecreatefrompng();
  //unlink($out);
  //return array($pdffile.'.page-'.$page.'.png');
  */