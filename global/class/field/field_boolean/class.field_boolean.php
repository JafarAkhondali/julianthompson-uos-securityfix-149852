<?php
# field_text class definition file
class field_boolean extends field {

	function isvalid() {
		return TRUE;//(is_bool($this->value));
	}
	
  function getdbfieldtype() {
  	return "tinyint(1)";
  }
  
  public function setvalue($value) {
  	$this->value = (boolean) $value;
  }
} 