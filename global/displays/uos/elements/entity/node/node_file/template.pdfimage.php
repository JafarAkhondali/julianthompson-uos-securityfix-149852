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
  //$exec = '/usr/local/bin/convert -append -fuzz 1% -trim -colorspace rgb -background white -density 300 -quality 100 -resize 50%';

  //$exec = '/usr/local/bin/gs -sDEVICE=pngalpha -o %s.page-%04d.png -r144 %s';
  //echo $exec; die(); 
  
  //temp file
  //$tmp = tempnam('','php');
  //rename($tmp,"$tmp.pdf");
  //$tmp = "$tmp.pdf";
  //$out = tempnam('','pdf');
  //unlink($out);
  //$out = "$out.png";
	$filename = UOS_DATA.$entity->filepath->value;
	//echo $filename;  
  //copy
  //copy($pdf,$tmp);

  $command = sprintf('/usr/local/bin/gs -sDEVICE=pngalpha -o %s.page-%%04d.png -r144 %s',$filename,$filename);

  //execute the command
  echo $command;
    /*
  exec($command);
  
  //echo $command;
  //echo basename($pdffile) . '.page-.*\.png';
	$pagefiles = find_files(dirname($filename), '.page-.*\.png', TRUE);
  print_r($pagefiles);
  //$pagefiles = aaprojects_get_file_list(dirname($pdffile),basename($pdffile) . '.page-.*\.png',TRUE);
  //sort($pagefiles);
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