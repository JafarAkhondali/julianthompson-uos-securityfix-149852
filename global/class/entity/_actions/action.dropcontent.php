<?php

$contenttypes = (array) $uos->request->parameters['content'];
if (isset($contenttypes['text/html'])) {
	$content = strip_html_attributes($contenttypes['text/html']);
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
		if ($tags[0]=='img') addoutput('content/','IMAGE');
		if ($tags[0]=='a') addoutput('content/','LINK');		
	} else {
	// its a mix or two - treat as html
		addoutput('content/', $content);
	}
} elseif (isset($contenttypes['text/plain'])) {
	addoutput('content/', $contenttypes['text/plain']);
}