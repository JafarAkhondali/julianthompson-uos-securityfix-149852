<?php
# node_url class definition file
class node_url extends node {
  
  
	function fetchtitle() {
	
		$this->title->value = 'Untitled web page';
    $str = file_get_contents($this->url->value);
    if(strlen($str)>0){
			$this->title->value = 'Untitled web page - found';  
			//return $str;  
      if (preg_match("/\<title\>\s*(.*)\<\/title\>/",$str,$title) > 0) {
      	$this->title->value = $title[1];
      	//return trim(print_r($title,TRUE));
      } 
    }
	}
	
	
	
	
} 