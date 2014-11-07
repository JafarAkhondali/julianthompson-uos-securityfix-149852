<?php
# field_file class definition file
class field_file extends field {

  function getdbfieldtype() {
  	return "varchar(255)";
  }
  
  public function fullpath() {
  	global $universe;
  	if ($this->parent!=NULL) {
  		return $this->parent->datapath($this->value);
  	}
  	return $universe->datapath.$this->value;
  }
} 