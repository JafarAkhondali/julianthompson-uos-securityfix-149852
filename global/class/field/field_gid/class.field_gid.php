<?php
# field_gid class definition file
class field_gid extends field {
	public $indexfield = TRUE;
	public $locked = TRUE;
	
	
  function getdbfieldtype() {
  	return "bigint(20)";
  }
} 