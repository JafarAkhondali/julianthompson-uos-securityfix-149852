<?php
trace( print_r($this, TRUE), 'action.view.php');
trace( (string) $this->id, 'action.view.php');
//$children = $universe->db_select_children((string) $this->id);
//foreach($children as $child) {
//	$this->children[] = $child;
//}
$this->fetchchildren();
addoutput('content', $this);