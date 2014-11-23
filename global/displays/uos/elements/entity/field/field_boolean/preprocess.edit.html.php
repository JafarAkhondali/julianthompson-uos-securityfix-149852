<?php
// field_boolean preprocess - /entity/field/field_boolean/preprocess.html.php

// include parent behaviour
include $render->rendererpath . '/elements/entity/field/preprocess.html.php';


//$render->attributes['disabled'] = TRUE;

// Add bootstrap switch libraries
addoutputunique('resources/script/', $render->rendererurl . "elements/_resources/libraries/bootstrap-switch-3.0/dist/js/bootstrap-switch.min.js");

addoutputunique('resources/style/', $render->rendererurl. "elements/_resources/libraries/bootstrap-switch-3.0/dist/css/bootstrap3/bootstrap-switch.min.css");


addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/field_boolean/_resources/script/display.field_boolean.js");