<?php
# field_text class definition file
class field_boolean extends field {

	function isvalid() {
		return (is_bool($this->value));
	}
	
  function getdbfieldtype() {
  	return "tinyint(1)";
  }
} 