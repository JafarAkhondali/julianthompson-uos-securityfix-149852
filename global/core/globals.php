<?php

/* Paths */

// Universe sub path
// Set a subpath if your global folder not in the document root
//define( 'UOS_SUBPATH',					'global/admin/dragdrop/');
define( 'UOS_SUBPATH',					'');

// Command line - Move command line stuff to one place
if (isset($argv)) {
 	//print_r($argv[1]); 	
 	//print "-1-";
 	//print_r(json_decode($argv[1]));
 	//die();
	parse_str(trim($argv[1]),$cliargs);
	//print_r($argv[1]);
	//print_r($cliargs);die();
	define( 'UOS_ROOT',            ( $cliargs['DOCUMENT_ROOT'] . '/' . UOS_SUBPATH ));
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


// Classes folder
define( 'UOS_CLASSES',         UOS_GLOBAL . 'class/');


// Displays folder
define( 'UOS_DISPLAYS',        UOS_GLOBAL . 'displays/');

// Displays folder
define( 'UOS_DISPLAYS_URL',    UOS_GLOBAL_URL . 'displays/');


// Default display 
define( 'UOS_DEFAULT_DISPLAY', 'default');


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

$uos->title = 'UniverseOS';

// To test Browser Capabilities
//useLibrary('browscap-php');
//namespace uos\library
//require UOS_LIBRARIES.'browscap-php/src/phpbrowscap/Browscap.php';
//use phpbrowscap\Browscap;
//$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);
//$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);



// Build Input parameters

// Command Line
if (isset($argv)) {

	parse_str(trim($argv[1]),$cliargs);
  $uos->request->commandtype = 'CLI';
  $uos->request->sessionid = isset($argv[2])?session_id($argv[2]):session_id();
  //$uos->request->urlpath = (count($argv)>1)?trim($argv[1],'\''):"";
  $uos->request->url = trim($cliargs['CLI_REQUEST']);
	$parsedurl = parse_url($uos->request->url);
	//$uos->request->urlparsed = $parsedurl;
  $uos->request->argv = $argv;
  session_save_path('/tmp');
	$uos->request->outputformat = 'cli';
  
// Apache
} elseif (isset($_SERVER['REQUEST_URI'])) {
	//Only enable for debug
	if ($uos->config->debugmode) $uos->request->servervars = $_SERVER;  
	
	$parsedurl = parse_url($_SERVER['REQUEST_URI']);
	$uos->request->parsedurl = $parsedurl;
  $uos->request->commandtype = 'GET';
  $uos->request->port = $_SERVER['SERVER_PORT'];
  $uos->request->ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false; 
  $uos->request->urlprotocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http"; 
	$uos->request->urlhostname = $_SERVER['SERVER_NAME'];

	$uos->request->url = trim($parsedurl['path'],'/');
	$uos->request->urlfull = $uos->request->urlprotocol .'://'. $uos->request->urlhostname . '/' . $uos->request->url;
	
	if(!empty($parsedurl['query'])) {
		$uos->request->parameters = UrlToQueryArray($parsedurl['query']);
	}
	if (isset($parsedurl['path'])) {
		$uos->request->request = trim($parsedurl['path'],'/');
	}
	//Overwrite get variables with posted
	if (!empty($_POST)) {
	  $uos->request->commandtype = 'POST';
		$uos->request->parameters = $uos->request->parameters + $_POST;
	}
	$uos->request->outputformat = 'html';
	
	//$uos->request->browser = $browsercapabilities->getBrowser(null, true);
  //$uos->request->urlpath = trim($_SERVER['REQUEST_URI'],'/');
	//$uos->request->urlparsed = $parsedurl;
  //$uos->request->urlexploded = explode('/',$uos->request->urlpath);  
  //$uos->request->serverrequest = $_REQUEST;
	//$uos->request->urlparsed = $parsedurl;
}



if(!empty($_FILES)) {
	$uos->request->files = $_FILES;
}

$explodedurl = pathinfo($uos->request->request);


$uos->request->outputdisplay = null;
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

//$uos->request->username = $_SERVER['PHP_AUTH_USER'];

session_start();
$uos->request->sessionid = session_id();
$uos->request->session = &$_SESSION;

//print_r($cliargs);
//print_r($uos->request);
//die();  
 










