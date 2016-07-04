<?php
// field preprocess - /entity/field/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/field/preprocess.html.php';

if (!$entity->usereditable) {
	$render->finish = TRUE;
}

addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/display.field.edit.html.js?3");