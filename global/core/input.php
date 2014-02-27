<?php

// Command Line
if (isset($argv)) {

  $uos->input->commandtype = 'CLI';
  $uos->input->sessionid = isset($argv[2])?session_id($argv[2]):session_id();
  $uos->input->url = (count($argv)>1)?trim($argv[1],'\''):"";
	$parsedurl = parse_url($uos->input->url);
	//$uos->input->urlparsed = $parsedurl;
  $uos->input->argv = $argv;
  session_save_path('/tmp');

} elseif (isset($_SERVER['REQUEST_URI'])) {
	$uos->input->server = $_SERVER;
	$uos->input->servername = $_SERVER['SERVER_NAME'];
  $uos->input->commandtype = 'GET';
  $uos->input->url = trim($_SERVER['REQUEST_URI'],'/');
  $uos->input->request = $_REQUEST;
	$parsedurl = parse_url($uos->input->url);
	//$uos->input->urlparsed = $parsedurl;
	$uos->input->request = $parsedurl['path'];
	if(!empty($parsedurl['query'])) {
		$uos->input->parameters = convertUrlQuery($parsedurl['query']);
	}
}

if (!empty($_POST)) {
  $uos->input->commandtype = 'POST';
  //if posted get command from url
  $uos->input->url = trim($_SERVER['REQUEST_URI'],'/');
  //print_r($_SERVER);
	$uos->input->parameters = $uos->input->parameters + $_POST;
}

$explodedurl = pathinfo($uos->input->request);

$uos->input->outputformat = 'html';
$uos->input->outputclass = null;
$uos->input->outputtransport = null;

if (!isset($explodedurl['dirname'])) {
	$uos->input->universename = 'global';
	$uos->input->action = 'view';
} else {

	if ($explodedurl['dirname']=='.') {
		$uos->input->universename = $explodedurl['basename'];
	} else {
		$explodedurl['dirparts'] = explode('/',$explodedurl['dirname']);
		$uos->input->universename = $explodedurl['dirparts'][0];
		array_shift($explodedurl['dirparts']);
		$uos->input->target = $explodedurl['dirparts'];
	}
	$uos->input->actionurl = $explodedurl['basename'];
	$actionurlex = explode('.',$uos->input->actionurl);
	$uos->input->action = array_shift($actionurlex);
	$uos->input->outputformat = array_shift($actionurlex);
	$uos->input->outputtransport = empty($actionurlex) ? null : array_shift($actionurlex);
}

//$uos->input->eu = $explodedurl;
//$splitbasename = explode('.',$explodedurl['filename']);



//$universe = new node_universe(array('connection'=>$_SERVER['DATABASE1'],'name'=>$_SERVER['DATABASE1NAME']));

//render($universe);die();

if (empty($uos->input->target)) {
	//$uos->input->target = $universe;
}

//$uos->input->username = $_SERVER['PHP_AUTH_USER'];

session_start();
$uos->input->sessionid = session_id();
$uos->input->session = &$_SESSION;


$uos->universe = new node_universe(array(
	'dbconnector' => UOS_DATABASE,
	'title' => 'UniverseOS'
));

