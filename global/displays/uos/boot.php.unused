<?php
//include core display functions
//include addpaths($render->rendererpath,"include.uos.php");
include "include.uos.php";

//$render->activerenderer = UOS_DEFAULT_DISPLAY;	
//$render->rendererurl = addpaths(UOS_DISPLAYS_URL, $render->activerenderer, '/'); 
//$render->rendererpath = addpaths(UOS_DISPLAYS, $render->activerenderer, '/');

//$format = $render->format;

if ($render->display=='default') {
	$render->formatdisplay = $render->format;
} else {
	$render->formatdisplay = implode('.',array($render->display,$render->format));
}
$render->preprocessfile = find_element_file($render->filesearchpaths, "preprocess.".$render->formatdisplay.".php");

$render->templatefile = find_element_file($render->filesearchpaths, "template.".$render->formatdisplay.".php");

if ($render->preprocessfile) {
	include $render->preprocessfile;
} else {
	throw new Exception('No preprocess file found');
}



if ($render->templatefile) {
	include $render->templatefile;
} else {
	throw new Exception('No template file found');
}

//print_r($render);
//return;