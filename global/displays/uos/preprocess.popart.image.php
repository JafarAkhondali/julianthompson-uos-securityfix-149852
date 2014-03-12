<?php
$entity = $uos->output['body'];
useLibrary('ImageWorkshop');
//die();
header("Content-type: ".$entity->mimetype->value."; charset=utf-8");
