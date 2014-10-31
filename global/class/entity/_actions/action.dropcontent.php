<?php

$contenttypes = (array) $uos->request->parameters['content'];

		

if (isset($contenttypes['text/html'])) {
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
					'title'=> 'Untitled dropped image content',
					'mime'=>'image/'
				));
			break;
			
			case 'a' :
				$newcontent = new node_url(array(
					'title'=> 'Untitled dropped link content',
				));
			break;
			
		}
		
	} else {
		// its a mix or two - treat as html
		$newcontent = new node_note(array(
			'title'=> 'Untitled dropped mixed content',
		));
	}
	
} elseif (isset($contenttypes['text/plain'])) {
	//addoutput('content/', $contenttypes['text/plain']);
	$newcontent = new node_note(array(
		'title'=> 'Untitled dropped text content',
	));
}

if ($newcontent) {
  $guid = $universe->add($newcontent);
	$universe->tagcontent($this, array($newcontent->id->value));
	addoutput('content/', $newcontent);
} else {
	$newcontent = new node_message(array(
		'title'=> 'Unknown dropped content',
		'body'=> print_r($contenttypes,TRUE)
	));
	$message = new node_message(array(
		'title' => "Dropped content",
		'body' => "Dropped content (" . ")  onto " . $this->title . ' (' . $this->id . ':' . $this->guid . ')'
	));
	addoutput('content/', $message);
}

