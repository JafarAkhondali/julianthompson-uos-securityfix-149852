<?php

$contenttypes = (array) $uos->request->parameters['content'];
print_r($contenttypes);

$typenames = array_keys($contenttypes);

// if link
if ( isset($contenttypes['text/plain']) && isset($contenttypes['text/html']) ) {
	$content = strip_html_attributes($contenttypes['text/html'],'');
	$domd = new DOMDocument();
	libxml_use_internal_errors(true);
	$domd->loadHTML($content);
	$tags = array();

	$domx = new DOMXPath($domd);

	$body = $domd->getElementsByTagName('body')->item(0);
	
	$items = $domx->query(".//*", $body);	
	foreach($items as $item) {
		$tags[] = $item->tagName;
	}
	
	// if just one tag
	if (count($tags)==1) {
	
		switch($tags[0]) {
		
			case 'img' :
				$newcontent = new node_file_image(array(
					'title'=> 'Untitled dropped image content ' .implode(':',$typenames) . $items->item(0)->getAttribute('src'),
					'mime'=>'image/'
				));
			break;
			
			case 'a' :
				$newcontent = new node_url(array(
					'title'=> 'Untitled link content ' .implode(':',$typenames) . $items->item(0)->getAttribute('href'),
					'url'=> $items->item(0)->getAttribute('src')
				));
			break;
			
		}
		
	} else {
		// its a mix or two - treat as html
		$title = trim($contenttypes['text/plain']);
		if (strlen($title)>100) {
			$title = trim(substr($title,0,97)," \t\n\r\0\x0B,") . '...';
		}
		$newcontent = new node_note(array(
			'title'=> $title,
			'body'=> $contenttypes['text/plain']
		));
	}

} elseif ( isset($contenttypes['text/plain']) && isset($contenttypes['text/uri-list']) ) {
  //[text/plain] => http://www.wickes.co.uk/Wickes-General-Purpose-OSB3-Board-18x1220x2440mm/p/110517
  //[text/uri-list] => http://www.wickes.co.uk/Wickes-General-Purpose-OSB3-Board-18x1220x2440mm/p/110517
	$newcontent = new node_url(array(
		'title'=> 'Untitled link content ' .implode(':',$typenames), // get webpage title
		'url' => $contenttypes['text/plain']
	));
	$newcontent->fetchtitle();
	//die('here');
	
} elseif (isset($contenttypes['urlx'])) {

} elseif (isset($contenttypes['text/plain'])) {
	//addoutput('content/', $contenttypes['text/plain']);
	//$newcontent = new node_note(array(
	//	'title'=> 'Untitled dropped text content - ',
	//));
}
print debuginfo($newcontent);
die();
if ($newcontent) {
  $guid = $universe->add($newcontent);
	$universe->tagcontent($this, array($newcontent->id->value));
	addoutput('content/', $newcontent);
	//addoutput('content/', $s);
} else {
	$newcontent = new node_message(array(
		'title'=> 'Unknown dropped content',
		'body'=> print_r($contenttypes,TRUE)
	));
	$message = new node_message(array(
		'title' => "Unknown dropped content",
		'body' => "Dropped mysterious content onto " . $this->title . ' (' . $this->id . ':' . $this->guid . ')'  . implode(':',$typenames) . print_r($contenttypes,TRUE)
	));
	addoutput('content/', $message);
}

