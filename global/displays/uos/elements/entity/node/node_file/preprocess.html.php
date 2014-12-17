<?php
// node preprocess - /entity/node/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/node/preprocess.html.php';

$render->attributes['class'][] = 'uos-subtype-' . display_uos_hypenate_none_alphanumeric($entity->mime->value);

//addoutputunique('resources/script/', $render->rendererurl."elements/entity/node/_resources/script/display.node.js");