<?php


// Set up the default UOS structure
$uos = new StdClass();


// Setup config object default 
$uos->config = new StdClass();
$uos->config->debugmode = FALSE;
$uos->config->showerrors = FALSE;
$uos->config->logging = TRUE;
$uos->config->logtostdout = FALSE;
$uos->config->types = Array();

$uos->libraries = Array();

//Include the configuration file
include_once UOS_CONFIG_FILE;

//overwrite configuration settings
$uos->config->logging = TRUE;


// Turn on error reporting
if ($uos->config->showerrors) {
  error_reporting(E_ALL);
  ini_set('display_errors','On');
}


// Messy I know
$uos->responsecodes = array(
	100 => 'Continue',
	200 => 'OK',
	201 => 'Created',
	202	=> 'Accepted',
	205 => 'Reset Content',
	206 => 'Partial Content',
	300 => 'Multiple Choices',
	307 => 'Temporary Redirect',
	402 => 'Payment Required',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
	406 => 'Not Acceptable',
	500 => 'Internal Server',
	501 => 'Not Implemented',
	502 => 'Bad Gateway',
	503 => 'Service Unavailable',
	505 => 'HTTP Version Not Supported'
);



$uos->actions = array();

$uos->request = new StdClass(); 

//$uos->request->outputformat = new StdClass(); 

$uos->response = new StdClass(); 

$uos->response->code = 404;  // Default to Not Found

$uos->output = new StdClass();
$uos->output = array();

$uos->output['log'] = array();

$uos->title = 'UniverseOS';


$universe = new node_universe($uos->config->data->universe);

// To test Browser Capabilities
//useLibrary('browscap-php');
//namespace uos\library
//require UOS_LIBRARIES.'browscap-php/src/phpbrowscap/Browscap.php';
//use phpbrowscap\Browscap;
//$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);
//$browsercapabilities = new phpbrowscap\Browscap(UOS_TEMPORARY);

$uos->request->action = UOS_DEFAULT_ACTION;

// Build Input parameters

// Command Line
if (isset($argv)) {

 	$cliargs = (json_decode($argv[1]));
  $uos->request->source = UOS_REQUEST_TYPE_CLI;
  $uos->request->cliargs = $cliargs;
  $uos->request->sessionid = $cliargs->sessionid;//isset($cliargs->sessionid)?session_id($cliargs->sessionid):session_id();
	$parsedurl = parse_url(trim($cliargs->url,'/'));
  $uos->request->urlpath = $parsedurl['path'];
  
	$uos->request->parameters = array();
	if(!empty($parsedurl['query'])) {
		$uos->request->parameters = UrlToQueryArray($parsedurl['query']);
	}
  session_save_path('/tmp');
	$uos->request->displaystring = 'cli';
  
// Webserver
} elseif (isset($_SERVER['REQUEST_URI'])) {

  $uos->request->source = 'browser';//UOS_REQUEST_TYPE_GET;
	$uos->request->displaystring = UOS_DEFAULT_DISPLAY_STRING;
  
	//Only enable for debug  
	if ($uos->config->debugmode) $uos->request->servervars = $_SERVER;  
	
	$parsedurl = parse_url($_SERVER['REQUEST_URI']);
	$uos->request->parsedurl = $parsedurl;
  $uos->request->port = $_SERVER['SERVER_PORT'];
  $uos->request->ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false; 
  $uos->request->protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http"; 
	$uos->request->hostname = $_SERVER['SERVER_NAME'];
	$uos->request->hosturl = $uos->request->protocol .'://'. $uos->request->hostname . '/';

	$uos->request->urlpath = trim($parsedurl['path'],'/');
	$uos->request->url = $uos->request->hosturl . $uos->request->urlpath;

	$uos->request->parameters = array();
	
	if(!empty($parsedurl['query'])) {
		$uos->request->parameters = UrlToQueryArray($parsedurl['query']);
	}
	
	//Overwrite get variables with posted
	if (!empty($_POST)) {
		//$uos->request->action = 'drop';
	  //$uos->request->commandtype = $uos->request->parameters['target'];
		$uos->request->parameters = $uos->request->parameters + $_POST;
	}

	if(!empty($_FILES)) {
		$uploadedfiles = diverse_array($_FILES["files"]);
		//$uos->request->filesd = $uploadedfiles;
		//$uos->request->files = $_FILES;
		foreach($uploadedfiles as $uploadedfile) {
			//$uploadedfile['type'] = 'node_file';
			//$uos->request->files[] = print_r($uploadedfile,TRUE);
			//$uos->request->files[] = $uploadedfile;
			$uos->request->files[] = new node_file(array(
				'title'=>$uploadedfile['name'],
				'mime'=>$uploadedfile['type'],
				'size'=>$uploadedfile['size'],
				'checksum'=>md5_file($uploadedfile['tmp_name']),
				'path'=>$uploadedfile['tmp_name'],
			));//$uploadedfile);		
		}
	}	
	//$uos->request->browser = $browsercapabilities->getBrowser(null, true);
  //$uos->request->urlpath = trim($_SERVER['REQUEST_URI'],'/');
	//$uos->request->urlparsed = $parsedurl;
  //$uos->request->urlexploded = explode('/',$uos->request->urlpath);  
  //$uos->request->serverrequest = $_REQUEST;
	//$uos->request->urlparsed = $parsedurl;
	
	// GET overrides
	
	if(isset($uos->request->parameters['target'])) {
		$uos->request->target = $uos->request->parameters['target'];
	}
	
	if(isset($uos->request->parameters['action'])) {
		$uos->request->action = $uos->request->parameters['action'];
	}
	
	if(isset($uos->request->parameters['display'])) {	
		$uos->request->displaystring = $uos->request->parameters['display'];
	}
	
	if($uos->request->urlpath=='global/uos.php') {
		$uos->request->urlpath = implode('.',array($uos->request->target,$uos->request->action,$uos->request->displaystring));
	}
}

$uos->request->bindir = PHP_BINDIR;


// look in paths first
// all to move to database
if (isset($uos->config->data->aliases[$uos->request->urlpath])) {
	$aliasdata = $uos->config->data->aliases[$uos->request->urlpath];
	$uos->request->targetstring = $aliasdata->targetstring;
	$uos->request->displaystring = $aliasdata->displaystring;
	$uos->request->action = $aliasdata->action;
	//$uos->request->outputformat->display = $aliasdata->display;
	//$uos->request->outputformat->format = $aliasdata->format;
} else {


  // for format 
  // http://julian.universeos/GUID/view/png
  // http://julian.universeos/GUID:field/view/png
  /*
	$explodedurl = explode('/', trim($uos->request->urlpath, '/') );
	//$targetaction = explode(':',$explodedurl[0]);
	//print_r($explodedurl);
	// first part of url is always targetstring
	$uos->request->targetstring = array_shift($explodedurl);
	// if we have a display string and/or action string
	if (!empty($explodedurl)) {
		// if we only have one string it must be a display string
		if (count($explodedurl)==1) {
			$uos->request->action = array_pop($explodedurl);
		} else {
			@list($uos->request->action,$uos->request->displaystring,$uos->request->extra) = $explodedurl;
		}
	}
	*/
	
  // for format 
  // http://julian.universeos/GUID.view.png
  // http://julian.universeos/GUID-field.view.png

	$explodedurl = explode('.', trim($uos->request->urlpath, '/'),3);
	//$targetaction = explode(':',$explodedurl[0]);
	//print_r($explodedurl);
	// first part of url is always targetstring
	$uos->request->targetstring = array_shift($explodedurl);
	// if we have a display string and/or action string
	if (!empty($explodedurl)) {
		// if we only have one string it must be a display string
		if (count($explodedurl)==1) {
			$uos->request->action = 'view';
			$uos->request->displaystring = array_pop($explodedurl);
		} else {
			@list($uos->request->action,$uos->request->displaystring,$uos->request->extra) = $explodedurl;
		}
	}
	

	//$uos->request->action = array_pop($targetaction);
	//$uos->request->target = implode(':',$targetaction);
	//$uos->request->displaystring = isset($explodedurl[1])?$explodedurl[1]:UOS_DEFAULT_DISPLAY_STRING; 
	//$displaystringexploded = explode('.',$uos->request->displaystring);
	//$uos->request->transport = array_pop($displaystringexploded);
	//$uos->request->formatdisplay = implode('.',$displaystringexploded);
	
	
	//@list($requeststring, $outputstring) = $uos->request->explodedurl;
	//@list($uos->request->target, $uos->request->action) = array_reverse(explode('-',$requeststring));
	//if (empty($uos->request->action)) $uos->request->action = 'view';
	//$displayformat = explode('.',$outputstring);
	//if (count($displayformat)<2) array_unshift($displayformat,UOS_DEFAULT_DISPLAY);
	//@list($uos->request->outputformat->display, $uos->request->outputformat->format) = $displayformat;
	//if (empty($uos->request->outputformat->display)) $uos->request->outputformat->display = 'default';
	//if (empty($uos->request->outputformat->format)) $uos->request->outputformat->format='html';

}

//$viewdisplay = explode('.',$outputstring,2);
//$uos->request->action2 = array_unshift($viewdisplay);
//$uos->request->displaystring = array_unshift($viewdisplay);

//$explodeddisplay = explode('.',)
/*





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
*/
//$uos->request->eu = $explodedurl;
//$splitbasename = explode('.',$explodedurl['filename']);



//$universe = new node_universe(array('connection'=>$_SERVER['DATABASE1'],'name'=>$_SERVER['DATABASE1NAME']));

//render($universe);die();

//$uos->request->username = $_SERVER['PHP_AUTH_USER'];
if (isset($uos->request->sessionid)) {
	//session_id($uos->request->sessionid);
}
//session_start();
//$uos->request->sessionid = session_id();
//$uos->request->session = &$_SESSION;

if (!isset($uos->request->session['history'])) {
	//$uos->request->session['history'] = array();
}
//$uos->request->session['history'][] = $uos->request;

//$_SESSION['history'][] = $uos->request;

//print_r($uos->request);
//die();

//$universe = fetchentity($uos->config->universeguid);









