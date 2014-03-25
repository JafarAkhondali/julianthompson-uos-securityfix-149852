<?php 
$filename = UOS_DATA.$entity->filepath->value;
$image = imagecreatefromjpeg($filename);
imagefilter($image, IMG_FILTER_GRAYSCALE);
imagejpeg($image, null, 100);
