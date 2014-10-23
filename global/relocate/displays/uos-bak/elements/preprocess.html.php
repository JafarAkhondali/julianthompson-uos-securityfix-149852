<?php
$render->childcount = count($entity);
//$render->title = ucfirst($render->entityconfig->title);
$render->wrapperelement = 'div';
if (!isset($render->attributes)) $render->attributes = array();