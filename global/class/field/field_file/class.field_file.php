<?php
# field_file class definition file
class field_file extends field {
  function getdbfieldtype() {
  	return "varchar(255)";
  }
  
  function fullpath() {
  	global $universe;
  	return $universe->datapath.$this->value;
  }
} 