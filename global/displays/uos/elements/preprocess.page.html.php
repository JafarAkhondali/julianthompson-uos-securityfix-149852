<?php
$render->title = ucfirst($render->entityconfig->title);
$render->wrapperelement = 'div';
if (!isset($render->attributes)) $render->attributes = array();
if (!isset($render->elementdata)) $render->elementdata = new stdClass();

addoutput('elementdata/'.$render->instanceid, $render->elementdata);

//print_r($uos->output);die();

// jQuery CDN
//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
//addoutput('resources/script/', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js");

// jQuery Local
addoutput('resources/script/', UOS_LIBRARIES_URL . "jquery/1.9.1/jquery.min.js");
addoutput('resources/script/', UOS_LIBRARIES_URL . "jqueryui/1.10.2/jquery-ui.min.js");

// jQuery Hotkeys 
addoutput('resources/script/', UOS_LIBRARIES_URL . "jquery.hotkeys/default.jquery.hotkeys.js");

// Growl
addoutput('resources/script/', UOS_LIBRARIES_URL . "jquery.growl/javascripts/jquery.growl.js");
addoutput('resources/style/',  UOS_LIBRARIES_URL . "jquery.growl/stylesheets/jquery.growl.css");

// For Three.js
addoutput('resources/script/', UOS_LIBRARIES_URL . "mrdoob-three.js/build/three.min.js");
addoutput('resources/script/', UOS_LIBRARIES_URL . "mrdoob-three.js/examples/js/libs/tween.min.js");
addoutput('resources/script/', UOS_LIBRARIES_URL . "mrdoob-three.js/examples/js/controls/TrackballControls.js");
addoutput('resources/script/', UOS_LIBRARIES_URL . "mrdoob-three.js/examples/js/renderers/CSS3DRenderer.js");


// Font Awesome CDN
//addoutput('resources/style/', "http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");

// Font Awesome Local
addoutput('resources/style/', UOS_LIBRARIES_URL . "font-awesome/css/font-awesome.css");


// Core UOS
//addoutput('resources/script/', $render->activerendererurl."resources/script/classextend.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.uos.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.base.js");
addoutput('resources/script/', $render->rendererurl."resources/script/jquery.uos.three.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/entity.resources/script/jquery.entity.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/field/field.resources/script/jquery.field.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/node/node.resources/script/jquery.node.js");
addoutput('resources/script/', $render->rendererurl."elements/entity/node/node_device/node_device.resources/script/jquery.node_device.js");

addoutput('resources/style/', $render->rendererurl."resources/style/style.uos.css");
