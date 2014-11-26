<?php 

if (is_array($entity) && isset($entity['content'])) {
	$content = $entity['content'];
	print render($content,'image');
} else {
	$content = null;//$entity;
	// Create the image
	// Create a 100*30 image
	$im = imagecreatetruecolor(500, 200);
	
	// White background and blue text
	$transparentbackground = imagecolorallocatealpha($im, 0,0,0);
	$backgroundcolor = imagecolorallocatealpha($im, 255, 255, 255, 60);
	$textcolor = imagecolorallocatealpha($im, 0, 0, 255, 60);
	
	imagecolortransparent($im, $transparentbackground);
	
	imagefilledrectangle ( $im , 0, 0 , 500 , 200, $backgroundcolor);
	
	// Write the string at the top left
	display_uos_imagecenteredstring($im, 5, 0, 500, 100, $render->entityconfig->title, $textcolor);
	display_uos_imagecenteredstring($im, 5, 0, 500, 120, '('.$content->guid.')' , $textcolor);
	//display_uos_imagecenteredstring($im, 5, 0, 500, 140, '('.$content->guid.')', $textcolor);
	header("Content-type: image.pdf; charset=utf-8");
	//imagealphablending($im, true);
	imagepng($im);
}