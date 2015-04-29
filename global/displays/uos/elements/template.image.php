<?php 
if (is_array($entity) && isset($entity['content'])) {
	$content = $entity['content'];
	print render($content,'image');
} else {
	$content = null;//$entity;
	// Create the image
	// Create a 100*30 image
	$im = imagecreatetruecolor(500, 500);
	imagealphablending($im, true);
	//echo debuginfo($entity);
	// White background and blue text
	$transparentbackground = imagecolorallocatealpha($im, 0,0,0);
	$backgroundcolor = imagecolorallocatealpha($im, 255, 255, 255, 60);
	$textcolor = imagecolorallocatealpha($im, 255,255,255, 30);
	
	imagecolortransparent($im, $transparentbackground);
	
	imagefilledrectangle ( $im , 0, 0 , imagesx($im) , imagesy($im), $backgroundcolor);
	
	$font = $render->rendererpath . '/elements/_resources/fonts/GillSans.ttf';
	/*
	$bbox = imagettfbbox(40, 0, $font, $render->entityconfig->title);
	// This is our cordinates for X and Y
	$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2);
	$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2)-25;
	// Write it
	imagettftext($im, 40, 0, $x, $y, $textcolor, $font, $render->entityconfig->title);
	*/
	//function display_uos_imagecenteredstringttf ( &$im, $font, $fontsize, $xoffset, $yoffset, $str, $textcolor ) {
	display_uos_imagecenteredstringttf($im, $font, 15, 0, -20, $render->entityconfig->title, $textcolor);	
	display_uos_imagecenteredstringttf($im, $font, 20, 0, 0, $entity->title->value, $textcolor);	
	display_uos_imagecenteredstringttf($im, $font, 12, 0, 30, '('.$entity->guid->value.')' , $textcolor);	
	
	// Write the string at the top left
	//display_uos_imagecenteredstring($im, 5, 0, 500, 100, $render->entityconfig->title, $textcolor);
	//display_uos_imagecenteredstring($im, 5, 0, 500, 120, '('.$entity->guid->value.')' , $textcolor);
	
	//display_uos_imagecenteredstring($im, 5, 0, 500, 140, '('.$content->guid.')', $textcolor);
	header("Content-type: image.pdf; charset=utf-8");
	//imagealphablending($im, true);
	imagepng($im);
}