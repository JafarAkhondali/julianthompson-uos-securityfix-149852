<?php
$entity = $uos->output['content'];
header("Content-type: ".$entity->mimetype->value."; charset=utf-8");
