<?php

// Global Universe OS Configuration file


// Setup config object default 
$uos->config = new StdClass();
$uos->config->debugmode = FALSE;
$uos->config->showerrors = FALSE;
$uos->config->logging = TRUE;
$uos->config->logtostdout = FALSE;
$uos->config->types = Array();

$uos->config->globaldbstring = UOS_DATABASE;
$uos->config->debugmode = FALSE;
$uos->config->debugrequest = FALSE;
//$uos->request->parameters['debugrequest'] = TRUE;
$uos->config->showerrors = FALSE;
$uos->config->logging = FALSE;
$uos->config->logtarget = UOS_LOGTARGET_FILE;
$uos->config->globaldatabasehost = '';
$uos->config->globaldatabaseuser = '';
$uos->config->globaldatabasepassword = '';
$uos->config->globaldatabasename = ''; 
$uos->config->bindir = PHP_BINDIR;


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


// These items are only temporary dummy items to get us started

$uos->config->types = array(
	'unknown' => (object) array(
		'title'=>'Unknown',
		'icon'=>'asterisk'
	),
	'array' => (object) array(
		'title'=>'Array',
		'icon'=>'list'
	),
	'string' => (object) array(
		'title'=>'String',
		'icon'=>'asterisk'
	),
	'double' => (object) array(
		'title'=>'Double',
		'icon'=>'asterisk'
	),
	'integer' => (object) array(
		'title'=>'Integer',
		'icon'=>'asterisk'
	),
	'object' => (object) array(
		'title'=>'Object',
		'icon'=>'asterisk'
	),
	'stdclass' => (object) array(
		'title'=>'Object (stdclass)',
		'icon'=>'asterisk'
	),
	'entity' => (object) array(
		'title'=>'Entity',
		'icon'=>'asterisk'
	),
	'node' => (object) array(
		'title'=>'Category',
		'icon'=>'asterisk'
	),
	'field' => (object) array(
		'title'=>'Field',
		'icon'=>'asterisk'
	),
	'node_universe' => (object) array(
		'title'=>'Universe',
		'icon'=>'circle-o'
	),
	'node_device' => (object) array(
		'title'=>'Device',
		'icon'=>'bolt'
	),
	'node_device_light' => (object) array(
		'title'=>'Light',
		'icon'=>'lightbulb-o'
	),
	'node_device_xbmc' => (object) array(
		'title'=>'Media Centre',
		'icon'=>'play-circle'
	),
	'node_file' => (object) array(
		'title'=>'File',
		'icon'=>'file-o'
	),
	'node_file_image' => (object) array(
		'title'=>'Image',
		'icon'=>'picture-o'
	),
	'node_file_video' => (object) array(
		'title'=>'Video',
		'icon'=>'video-camera'
	),
	'node_file_pdf' => (object) array(
		'title'=>'PDF Document',
		'icon'=>'file-o'
	),
	'node_person' => (object) array(
		'title'=>'Person',
		'icon'=>'user'
	),
	'node_event' => (object) array(
		'title'=>'Event',
		'icon'=>'calendar'
	),
	'node_location' => (object) array(
		'title'=>'Location',
		'icon'=>'map-marker'
	),
	'node_url' => (object) array(
		'title'=>'Webpage',
		'icon'=>'cloud'
	),
	'node_message' => (object) array(
		'title'=>'Message',
		'icon'=>'envelope'
	),
	'node_message_email' => (object) array(
		'title'=>'Email',
		'icon'=>'envelope'
	),
	'node_message_tweet' => (object) array(
		'title'=>'Tweet',
		'icon'=>'envelope'
	),
	'node_note' => (object) array(
		'title'=>'Note',
		'icon'=>'file-text-o'
	),	
	'node_service' => (object) array(
		'title'=>'Service',
		'icon'=>'bullseye'
	),
	'node_service_email' => (object) array(
		'title'=>'Email account',
		'icon'=>'bullseye'
	),
	'node_service_google' => (object) array(
		'title'=>'Google account',
		'icon'=>'bullseye'
	),
	'node_service_iplayer' => (object) array(
		'title'=>'BBC iPlayer',
		'icon'=>'video-camera'
	),
	'node_service_twitter' => (object) array(
		'title'=>'Twitter account',
		'icon'=>'bullseye'
	),
	'node_service_x10' => (object) array(
		'title'=>'X10 Controller',
		'icon'=>'bolt'
	),
	'node_emailaddress' => (object) array(
		'title'=>'Email address',
		'icon'=>'inbox'
	),
	'node_phonenumber' => (object) array(
		'title'=>'Phone number',
		'icon'=>'phone'
	),
	'node_invoice' => (object) array(
		'title'=>'Invoice',
		'icon'=>'gbp'
	),
	'relationship' => (object) array(
		'title'=>'Relationship',
		'icon'=>'expand'
	)
);
