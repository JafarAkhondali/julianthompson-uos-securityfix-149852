<?php

$filename = (string) $entity->title . '.webloc';
//uos_header("HTTP/1.1 " . $uos->response->code . " " . $uos->responsecodes[$uos->response->code]);

//open/save dialog box
uos_header('Content-Disposition: attachment; filename="'.$filename.'"');

//content type
uos_header('Content-type: application/octet-stream; charset=utf-8');

uos_header('HTTP/1.1 200 OK');

//how do we set the size?
//we can skip the template
//and just write the content variable
//$render->templatefile = NULL

//header('Content-Length: ' . filesize($filename));
uos_header('Content-Length: ' . strlen ($render->templateoutput));
?>
<!-- UniverseOS-->
<?php print $render->templateoutput;?>