<?php
# field_number class definition file
class field_number extends field {
	public $minvalue = NULL;
	public $maxvalue = NULL;
	
  function getdbfieldtype() {	
  	return "bigint(20)"; //NOT NULL;
  }
} 