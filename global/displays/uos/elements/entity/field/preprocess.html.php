<?php
// field preprocess - /entity/field/preprocess.html.php

// include parent behaviour
// equivalent of calling parent preprocess method
include $render->rendererpath . '/elements/entity/preprocess.html.php';

//$render->attributes['draggable'] = "false";

addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/_resources/script/jquery.field.js");