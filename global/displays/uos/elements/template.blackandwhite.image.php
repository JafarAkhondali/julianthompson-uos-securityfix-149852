<?php 
$filename = $entity->filepath->fullpath();
$image = imagecreatefromjpeg($filename);
imagefilter($image, IMG_FILTER_GRAYSCALE);
imagejpeg($image, null, 100);
