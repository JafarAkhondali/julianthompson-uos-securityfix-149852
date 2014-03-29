<?php
// field_boolean preprocess - /entity/field/field_boolean/preprocess.html.php

// include parent behaviour
include $render->rendererpath . '/elements/entity/field/preprocess.html.php';

// Add bootstrap switch libraries
addoutputunique('resources/script/', $render->rendererurl . "elements/_resources/libraries/bootstrap-slider/dist/bootstrap-slider.min.js");

addoutputunique('resources/style/', $render->rendererurl. "elements/_resources/libraries/bootstrap-slider/dist/css/bootstrap-slider.min.css");


addoutputunique('resources/script/', $render->rendererurl."elements/entity/field/field_number/_resources/script/jquery.field_number.js");