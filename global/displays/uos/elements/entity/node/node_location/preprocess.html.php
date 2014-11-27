<?php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/node/preprocess.html.php';

addoutputunique('resources/script/', $render->rendererurl."elements/entity/node/node_location/_resources/script/jquery.js");