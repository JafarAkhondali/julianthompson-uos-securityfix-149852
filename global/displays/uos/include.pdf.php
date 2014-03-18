<?php


// For PHP without Anonymous functions

function display_uos_pdf_file_to_image($pdffile,$page=1) {

  $command = sprintf('/usr/local/bin/gs -sDEVICE=pngalpha -o %s.page-%%04d.png -r144 %s',$pdffile,$pdffile);
  
  exec($command);
  
  $pagefiles = aaprojects_get_file_list(dirname($pdffile),basename($pdffile) . '.page-.*\.png',TRUE);
  sort($pagefiles);

  return $pagefiles;
}

