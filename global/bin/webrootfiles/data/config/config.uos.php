<?php

// Universe OS Configuration file
// Ammend as required

// Takes the global universe database from Virtual host file in format :
// SetEnv UOS_DATABASE    mysql://root:xxxx@localhost/uos
// But the value can be coded here 

$uos->config->globaldbstring = UOS_DATABASE;
$uos->config->debugmode = FALSE;
$uos->config->showerrors = TRUE;


// These items are only temporary dummy items to get us started

$uos->config->types = array(
	'node' => (object) array(
		'title'=>'Category',
		'icon'=>'asterisk'
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
	'node_note' => (object) array(
		'title'=>'Note',
		'icon'=>'file-text-o'
	),	
	'node_service' => (object) array(
		'title'=>'Service',
		'icon'=>'bullseye'
	),
	'node_service_google' => (object) array(
		'title'=>'Google',
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
);

$uos->config->data = array(

	4567898765 => (object) array(
		'guid' => 4567898765,  
		'type' => 'node_device_light', //attribute
		'title' => 'Standard Lamp',
		//'description' => "",
		//'parents' => array('downstairs','lounge'),
		'x10housecode' => 1,
		'x10key' => 'a',
		'dimmable' => TRUE,
		'level' => 100,
		//'lastupdate' => 0
	),
	
	8834323145 => (object) array(
		'guid' => 8834323145,
		'type' => 'node',
		'title' => 'Greenacres',
		'zones' => array('downstairs','lounge'),
	),

	1313534234 => (object) array(
		'guid' => 1313534234,
		'type' => 'node',
		'title' => "Atomic Ant",
		'zones' => array('downstairs','lounge'),
		'downloadurl' => "text/vcard:Atomic Ant.vcf:/data/1313534234/atomicant.vcf"
	),
	5645342341 => (object) array(
		'guid' => 5645342341,
		'type' => 'node_file_image',
		'title' => 'Waterfall',
		'description' => "You can almost hear the water.",
		'checksum' => 0,
		'downloadurl' => "image/jpeg:Waterfall.jpg:http://universeos.localhost/global/admin/dragdrop/data/5645342341/test-image.jpg"
	), 
	
	3643827492 => (object) array(
		'guid' => 3643827492,
		'type' => 'node_person',
		'title' => "Julian Thompson",
//		'zones' => array('downstairs','lounge'),
//		'downloadurl' => "text/vcard:Julian Thompson.vcf:http://universeos.localhost/global/admin/dragdrop/data/1313534234/atomicant.vcf"
	),

	6453127562 => (object) array(
		'guid' => 6453127562,
		'type' => 'node_person',
		'title' => "Peter Barret",
//		'zones' => array('downstairs','lounge'),
//		'downloadurl' => "text/vcard:Peter Barret.vcf:http://universeos.localhost/global/admin/dragdrop/data/1313534234/atomicant.vcf"
	),
	
	5647646464 => (object) array(
		'guid' => 5647646464,
		'type' => 'node_person',
		'title' => "Marek Sotak",
//		'zones' => array('downstairs','lounge'),
//		'downloadurl' => "text/vcard:Marek Sotak.vcf:http://universeos.localhost/global/admin/dragdrop/data/1313534234/atomicant.vcf"
	),
	
	3344221134 => (object) array(
		'guid' => 3344221134,
		'type' => 'node_event',
		'title' => "Julian Thompson Birthday",
		'zones' => array('downstairs','lounge'),
		'downloadurl' => "text/calendar:Julian Thompson Birthday.vcal:http://universeos.localhost/global/admin/dragdrop/data/3344221134/3344221134.vcal"
	),
	
	2345725347 => (object) array(
		'guid' => 2345725347,
		'type' => 'node_url',
		'title' => "8 Cool Google Labs Projects Spared the Axe",
		'url' => 'http://www.cio.com/article/692467/8_Cool_Google_Labs_Projects_Spared_the_Axe',
		'zones' => array('downstairs','lounge'),
		//'downloadurl' => "text/calendar:Julian Thompson Birthday.vcf:http://universeos.localhost/global/admin/dragdrop/data/3344221134/3344221134.ics"
	),
	5644564645 => (object) array(
		'guid' => 5644564645,
		'type' => 'node_location',
		'title' => "XX Court Farm Gardens",
		//'url' => 'http://www.cio.com/article/692467/8_Cool_Google_Labs_Projects_Spared_the_Axe',
		'zones' => array('downstairs','lounge'),
		//'downloadurl' => "text/calendar:Julian Thompson Birthday.vcf:http://universeos.localhost/global/admin/dragdrop/data/3344221134/3344221134.ics"
	),
	2113564674 => (object) array(
		'guid' => 2113564674,
		'type' => 'node_message',
		'title' => "Accounts to 31 december 2013",
		'zones' => array('downstairs','lounge'),
	),
	5645625342 => (object) array(
		'guid' => 5645625342,
		'type' => 'node_file_video',
		'title' => "Alan Watts",
		'zones' => array('downstairs','lounge'),
	),
	3434652345 => (object) array(
		'guid' => 3434652345,
		'type' => 'node_device',
		'title' => "Media Center",
		'zones' => array('downstairs','lounge')	
	),
	7665563464 => (object) array(
		'guid' => 7665563464,
		'type' => 'node_file',
		'title' => "The Voynich Manuscript complete",
		'zones' => array('downstairs','lounge')	
	),
	2346828276 => (object) array(
		'guid' => 2346828276,
		'type' => 'node_note',
		'title' => "The truth",
		'zones' => array('downstairs','lounge')	
	),
	5345428273 => (object) array(
		'guid' => 5345428273,
		'type' => 'node_service_google',
		'title' => "mistergroove@atomicant.co.uk",
		'zones' => array('downstairs','lounge')	
	),
	2827623468 => (object) array(
		'guid' => 2827623468,
		'type' => 'node_service_google',
		'title' => "xxx@atomicant.co.uk",
		'zones' => array('downstairs','lounge')	
	),
	3246824231 => (object) array(
		'guid' => 3246824231,
		'type' => 'node_emailaddress',
		'title' => "xxx@atomicant.co.uk",
		'zones' => array('downstairs','lounge')	
	),
	4674646465 => (object) array(
		'guid' => 4674646465,
		'type' => 'node_phonenumber',
		'title' => "+44 7958 903924",
		'country' => 'uk',
		'phonenumber' => '07958903924',
		'zones' => array('downstairs','lounge')	
	)
);



/* 
		<a href="Eadui.ttf" id="dragout" draggable="true" data-downloadurl="
    application/octet-stream
    :Eadui.ttf
    :http://universeos.localhost/global/admin/dragdrop/Eadui.ttf">Font file</a>
*/