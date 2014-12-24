<?php
# node_file class definition file
class node_file extends node {
  
  
	function relocatefiles() {
		foreach($this->properties as $key=>$property) {
		 	trace('RELOCATE FILES:'.get_class($property));
			if ( (get_class($property)=='field_file') || (is_subclass_of($property,'field_file')) ) {
					$datapath = $this->datapath() . $key . '/';
					trace($cachepath);
					umask(0);
					if (!file_exists($datapath)) {
						mkdir($datapath,0777,TRUE);
					}
					$newpath = $datapath.$this->filename;
					
					if (is_uploaded_file($property->value)) {
						move_uploaded_file($property->value,$newpath);
					} else {
						rename($property->value,$newpath);
					}
					
					$property->value = $key . '/' . $this->filename;
			}
		}
	}
	
	public function getchild($childid) {
		$this->fetchchildren();
		return $this->children[$childid];
	}
	
	
	function getasmime($mimetype, $force=FALSE) {
		if ($this->mime->value==$mimetype) {
			//return $this->filepath->fullpath();
			//return file_exists($this->filepath->fullpath())?"Exists":"Doesn't";		
			return $this->filepath->fullpath(); 
		}
		return parent::getasmime($mimetype, $force);
	}
	
} 