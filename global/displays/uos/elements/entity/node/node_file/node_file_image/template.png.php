<?php


$filename = UOS_DATA.$entity->filepath->value;


$percent = 0.5;

// Get new dimensions
list($width, $height) = getimagesize($filename);
$new_width = $width * $percent;
$new_height = $height * $percent;

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


imagestring($image, 5, 40, 20, 'UniverseOS (Source URL) QR - PNG', 0xFFFFFF);
// Output
//imagejpeg($image_p, null, 100);
imagepng($image, null, 0);

//readfile($filename);