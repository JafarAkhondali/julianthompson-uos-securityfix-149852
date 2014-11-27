<?php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/node/node_location/preprocess.html.php';
$entity->getgeolocation();
//addoutputunique('resources/script/', "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false");
//addoutputunique('resources/script/', $render->rendererurl."elements/entity/node/node_location/_resources/script/jquery.node_location.js");
addoutputunique('resources/style/', $render->rendererurl."elements/entity/node/node_location/_resources/style/display.teaser.html.css");