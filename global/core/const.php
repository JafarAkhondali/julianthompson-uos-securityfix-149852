<?php

/* Paths */

// Universe sub path
// Set a subpath if your global folder not in the document root
//define( 'UOS_SUBPATH',					'global/admin/dragdrop/');
define( 'UOS_SUBPATH',					'');

// Universe root
define( 'UOS_ROOT',            ( $_SERVER['DOCUMENT_ROOT'] . '/' . UOS_SUBPATH ));


// Global folder
define( 'UOS_GLOBAL',					UOS_ROOT. 'global/');


// Library folder
define( 'UOS_LIBRARY',         UOS_GLOBAL . 'library/');


// Classes folder
define( 'UOS_CLASSES',         UOS_GLOBAL . 'class/');


// Displays folder
define( 'UOS_DISPLAYS',        UOS_GLOBAL . 'displays/');


// Data folder
define( 'UOS_DATA',            UOS_ROOT . 'data/');


// Data cache folder
define( 'UOS_CACHE',      			UOS_ROOT . 'cache/');


// Universe config folder
define( 'UOS_DATA_CONFIG',			UOS_DATA . 'config/');


// Universe config file
define( 'UOS_CONFIG_FILE',			UOS_DATA_CONFIG . 'config.php');


// Universe Base Class
define( 'UOS_BASE_CLASS',      'entity');

define( 'UOS_DATABASE',					getenv('UOS_DATABASE'));

define( 'LOGTOSTD',						TRUE);


$uos = new StdClass();

$uos->logtoscreen = FALSE;

$uos->actions = array();

$uos->input = new StdClass(); 
//$uos->input->settings = $settings;

//$uos->input = new stdClass();
$uos->input->parameters = array();




