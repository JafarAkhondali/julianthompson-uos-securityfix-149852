<?php
$entity = $uos->output['body'];
header("Content-type: ".$entity->mimetype->value."; charset=utf-8");
