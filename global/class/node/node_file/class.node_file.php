<?php
# node_file class definition file
class node_file extends node {
  
  
	function relocatefiles() {
		foreach($this->properties as $key=>$property) {
		 	trace('RELOCATE FILES:'.get_class($property));
			if ( (get_class($property)=='field_file') || (is_subclass_of($property,'field_file')) ) {
					$cachepath = $this->cachepath() . $key . '/';
					trace($cachepath);
					umask(0);
					if (!file_exists($cachepath)) {
						mkdir($cachepath,0777,TRUE);
					}
					$newpath = $cachepath.$this->filename;
					move_uploaded_file($property->value,$newpath);
					$property->value = $newpath;
			}
		}
	}
} 