<?php 
//print_r($entity);
$filename = UOS_DATA.$entity->filepath->value;

//$image = new Imagick($filename);

// If 0 is provided as a width or height parameter,
// aspect ratio is maintained
//$image->thumbnailImage(100, 0);

//echo $image;
//echo $path;

$percent = 0.5;

// Get new dimensions
list($width, $height) = getimagesize($filename);
$new_width = $width * $percent;
$new_height = $height * $percent;

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Output
//imagejpeg($image_p, null, 100);
imagejpeg($image, null, 100);

//readfile($filename);
