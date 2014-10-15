<?php 
//namespace PHPImageWorkshop;
$filename = $entity->filepath->fullpath();

$layer = PHPImageWorkshop\ImageWorkshop::initFromPath($filename);
//$layer->applyFilter(IMG_FILTER_NEGATE);
//$layer->applyFilter(IMG_FILTER_CONTRAST, -15, null, null, null, true); // constrast
//$layer->applyFilter(IMG_FILTER_BRIGHTNESS, 8, null, null, null, true); // brightness

$layer->applyFilter(IMG_FILTER_GRAYSCALE);
$layer->applyFilter(IMG_FILTER_CONTRAST, -100);
//$pinguLayer = ImageWorkshop::initFromPath($filename);

//$phpThumb = new phpThumb(); 
//$thumbnail_width = 100; 
//$phpThumb->setSourceFilename($filename);
//$phpThumb->setParameter('w', $thumbnail_width); 
//$phpThumb->OutputThumbnail();
//$image = imagecreatefromjpeg($filename);
//imagefilter($image, IMG_FILTER_GRAYSCALE);
$image = $layer->getResult();
imagejpeg($image, null, 100);
