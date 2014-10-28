<?php

$message = new node_message(array(
	'title' => "Add content to " . $this->title,
	'body' => '<p>Choose from / Drop file</p><p><form><input type="file" id="file" name="file"><input type="submit"></form>'
));

addoutput('content/', $message);