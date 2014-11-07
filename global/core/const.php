<?php
/* Paths */

// Universe sub path
// Set a subpath if your global folder not in the document root
//define( 'UOS_SUBPATH',					'global/admin/dragdrop/');
define( 'UOS_SUBPATH',					'');

//$x= new StdClass();
//$x->URL = '/werewr/sdfsdfsd/sdfsdf';
//$x->DOCUMENT_ROOT = '/sdfsdf/sdf/sdfs';
//$x->SESSION= 344567897654345;
//print_r(json_encode($x));
//die();

// Command line - Move command line stuff to one place
if (isset($argv)) {
 	$cliargs = (json_decode($argv[1]));
 	//print_r($argv[1]);die();
 	//print_r($cliargs);die();
 	//print_r($cliargs);
 	//die();
	define( 'UOS_ROOT',            ( $cliargs->documentroot . '/' . UOS_SUBPATH ));
// Universe root
} else {
	define( 'UOS_ROOT',            ( $_SERVER['DOCUMENT_ROOT'] . '/' . UOS_SUBPATH ));
}

//echo (UOS_ROOT);die();


define( 'UOS_GLOBAL_URL',			'/global/');


// Global folder
define( 'UOS_GLOBAL',					UOS_ROOT . 'global/');


// Library folder
define( 'UOS_LIBRARIES',      UOS_GLOBAL . 'libraries/');


// Library folder URL
define( 'UOS_LIBRARIES_URL',    UOS_GLOBAL_URL . 'libraries/');


// Classes folder - update convention to match UOS_PATH_XXXX
define( 'UOS_PATH_CORE',        UOS_GLOBAL . 'core/');

// Classes folder
define( 'UOS_CLASSES',         UOS_GLOBAL . 'class/');


// Displays folder Path
define( 'UOS_DISPLAYS',        UOS_GLOBAL . 'displays/');


// Displays folder URL
define( 'UOS_DISPLAYS_URL',    UOS_GLOBAL_URL . 'displays/');


// Default display 
define( 'UOS_DEFAULT_DISPLAY', 'default');


// Default display string (Will replace default display)
define('UOS_DEFAULT_DISPLAY_STRING',	'page.html');


define('UOS_DEFAULT_ACTION',	'view');


// Data folder
define( 'UOS_GLOBAL_DATA',      UOS_ROOT . 'data/');


// Data cache folder
define( 'UOS_GLOBAL_CACHE',      UOS_ROOT . 'cache/');


// Universe config file
define( 'UOS_GLOBAL_CONFIG',	UOS_PATH_CORE . 'config.uos.php');

define( 'UOS_LOCAL_CONFIG',	UOS_GLOBAL_DATA . 'config.uos.php');

// Universe config folder
define( 'UOS_TEMPORARY',				'/tmp/');

// Universe Base Class
define( 'UOS_BASE_CLASS',      'entity');


define( 'UOS_GUID_FIELD_SEPARATOR','-');


define( 'UOS_LOGTARGET_FILE',0);
define( 'UOS_LOGTARGET_CONTENT',1);

// Get Database from Virtual host / htaccess file
define( 'UOS_DATABASE',				getenv('UOS_DATABASE'));
define( 'UOS_BIN_GS',					getenv('UOS_BIN_GS'));
define( 'UOS_BIN_IM',					getenv('UOS_BIN_IM'));

define(	'UOS_REQUEST_TYPE_CLI',		'cli');
define(	'UOS_REQUEST_TYPE_GET',		'get');
define(	'UOS_REQUEST_TYPE_POST',	'post');

define(	'UOS_ERROR_NOT_FOUND',	NULL);

define( 'UOS_DEBUGMODE_REQUEST', 'request');
define( 'UOS_DEBUGMODE_RESPONSE', 'response');
define( 'UOS_DEBUGMODE_RENDER',  'render');
