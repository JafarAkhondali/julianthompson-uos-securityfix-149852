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
define( 'UOS_DISPLAYS',        UOS_GLOBAL . 'display/');


// Data folder
define( 'UOS_DATA',            UOS_ROOT . 'data/');


// Data cache folder
define( 'UOS_CACHE',      			UOS_ROOT . 'cache/');


// Universe config folder
define( 'UOS_DATA_CONFIG',			UOS_DATA . 'config/');


// Universe config file
define( 'UOS_CONFIG_FILE',			UOS_DATA_CONFIG . 'config.uos.php');

// Universe config folder
define( 'UOS_TEMPORARY',				'/tmp/');

// Universe Base Class
define( 'UOS_BASE_CLASS',      'entity');


// Get Database from Virtual host / htaccess file
define( 'UOS_DATABASE',					getenv('UOS_DATABASE'));

define(	'UOS_ERROR_NOT_FOUND',	NULL);


// Set up the default UOS structure
$uos = new StdClass();


// Setup config object default 
$uos->config = new StdClass();
$uos->config->debugmode = FALSE;
$uos->config->showerrors = FALSE;
$uos->config->logging = TRUE;
$uos->config->logtostdout = FALSE;
$uos->config->types = Array();

//Include the configuration file
include_once UOS_CONFIG_FILE;

//overwrite configuration settings
$uos->config->logging = TRUE;


// Turn on error reporting
if ($uos->config->showerrors) {
  error_reporting(E_ALL);
  ini_set('display_errors','On');
}




$uos->actions = array();

$uos->request = new StdClass(); 

$uos->request->parameters = array();

$uos->output = new StdClass();
$uos->output = array();

$uos->output['log'] = array();

$uos->render = new StdClass();
$uos->render->renderindex = 0;
$uos->render->renderpath = array();

$uos->title = 'UniverseOS';

// To test Browser Capabilities
useLibrary('browscap-php');
//namespace uos\library
//require UOS_LIBRARY.'browscap-php/src/phpbrowscap/Browscap.php';
//use phpbrowscap\Browscap;
//$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);
$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);



// Build Input parameters

// Command Line
if (isset($argv)) {

  $uos->request->commandtype = 'CLI';
  $uos->request->sessionid = isset($argv[2])?session_id($argv[2]):session_id();
  $uos->request->url = (count($argv)>1)?trim($argv[1],'\''):"";
	$parsedurl = parse_url($uos->request->url);
	//$uos->request->urlparsed = $parsedurl;
  $uos->request->argv = $argv;
  session_save_path('/tmp');

} elseif (isset($_SERVER['REQUEST_URI'])) {
	//Only enable for debug
	if ($uos->config->debugmode) $uos->request->server = $_SERVER;  
	$uos->request->servername = $_SERVER['SERVER_NAME'];
  $uos->request->commandtype = 'GET';
  $uos->request->url = trim($_SERVER['REQUEST_URI'],'/');
  $uos->request->serverrequest = $_REQUEST;
	$parsedurl = parse_url($_SERVER['REQUEST_URI']);
	//$uos->request->urlparsed = $parsedurl;
	if (isset($parsedurl['path'])) {
		$uos->request->request = trim($parsedurl['path'],'/');
	}
	if(!empty($parsedurl['query'])) {
		$uos->request->parameters = UrlToQueryArray($parsedurl['query']);
	}
	$uos->request->browser = $browsercapabilities->getBrowser(null, true);
}

if (!empty($_POST)) {
  $uos->request->commandtype = 'POST';
  //if posted get command from url
  $uos->request->url = trim($_SERVER['REQUEST_URI'],'/');
  //print_r($_SERVER);
	$uos->request->parameters = $uos->request->parameters + $_POST;
}

if(!empty($_FILES)) {
	$uos->request->files = $_FILES;
}

$explodedurl = pathinfo($uos->request->request);

$uos->request->outputformat = 'html';
$uos->request->outputclass = null;
$uos->request->outputtransport = null;

if (!isset($explodedurl['dirname'])) {
	$uos->request->universename = 'global';
	$uos->request->action = 'view';
} else {

	if ($explodedurl['dirname']=='.') {
		$uos->request->universename = $explodedurl['basename'];
	} else {
		$explodedurl['dirparts'] = explode('/',$explodedurl['dirname']);
		$uos->request->universename = $explodedurl['dirparts'][0];
		array_shift($explodedurl['dirparts']);
		$uos->request->target = $explodedurl['dirparts'];
	}
	$uos->request->actionurl = $explodedurl['basename'];
	$actionurlex = explode('.',$uos->request->actionurl);
	$uos->request->action = array_shift($actionurlex);
	$uos->request->outputformat = empty($actionurlex) ? 'html' : array_shift($actionurlex);
	
	$uos->request->outputtransport = empty($actionurlex) ? null : array_shift($actionurlex);
}

//$uos->request->eu = $explodedurl;
//$splitbasename = explode('.',$explodedurl['filename']);



//$universe = new node_universe(array('connection'=>$_SERVER['DATABASE1'],'name'=>$_SERVER['DATABASE1NAME']));

//render($universe);die();

if (empty($uos->request->target)) {
	//$uos->request->target = $universe;
}

//$uos->request->username = $_SERVER['PHP_AUTH_USER'];

session_start();
$uos->request->sessionid = session_id();
$uos->request->session = &$_SESSION;


$uos->universe = new node_universe(array(
	'dbconnector' => UOS_DATABASE,
	'title' => 'UniverseOS'
));


$uos->request->target = new entity(array(
	'universename'=>'julian',
	'guid'=>'1234567'
));
 










