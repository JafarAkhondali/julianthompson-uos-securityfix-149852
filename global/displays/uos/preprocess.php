<?php
//print_r($uos->output);die();

// jQuery CDN
//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js");

// jQuery Local
addoutput('resources/script/', "/global/libraries/jquery/1.9.1/jquery.min.js");
addoutput('resources/script/', "/global/libraries/jqueryui/1.10.2/jquery-ui.min.js");

// jQuery Hotkeys 
addoutput('resources/script/', "/global/libraries/jquery.hotkeys/default.jquery.hotkeys.js");

// Growl
addoutput('resources/script/', "/global/libraries/jquery.growl/javascripts/jquery.growl.js");
addoutput('resources/style/', "/global/libraries/jquery.growl/stylesheets/jquery.growl.css");

// For Three.js
addoutput('resources/script/', "/global/libraries/mrdoob-three.js/build/three.min.js");
addoutput('resources/script/', "/global/libraries/mrdoob-three.js/examples/js/libs/tween.min.js");
addoutput('resources/script/', "/global/libraries/mrdoob-three.js/examples/js/controls/TrackballControls.js");
addoutput('resources/script/', "/global/libraries/mrdoob-three.js/examples/js/renderers/CSS3DRenderer.js");


// Font Awesome CDN
//addoutput('resources/style/', "http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");

// Font Awesome Local
addoutput('resources/style/', "/global/libraries/font-awesome/css/font-awesome.css");


// Core UOS
//addoutput('resources/script/', $render->activerendererurl."resources/script/classextend.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.uos.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.base.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.uos.three.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/entity.resources/script/jquery.entity.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/field/field.resources/script/jquery.field.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/node/node.resources/script/jquery.node.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/node/node_device/node_device.resources/script/jquery.node_device.js");

addoutput('resources/style/', $render->rendererurl."resources/style/style.css");

//print_r($uos->universe->getactions());