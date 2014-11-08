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
	'node_service_google' => (object) array(
		'title'=>'Google account',
		'icon'=>'bullseye'
	),
	'node_service_twitter' => (object) array(
		'title'=>'Twitter account',
		'icon'=>'bullseye'
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
	),
	'node_service_x10' => (object) array(
		'title'=>'X10 Controller',
		'icon'=>'bolt'
	),
);
