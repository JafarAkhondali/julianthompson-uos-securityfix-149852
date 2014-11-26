<?php 
$filename = $entity->filepath->fullpath();
//imagepng(display_uos_make_visual($entity));
readfile($filename);

