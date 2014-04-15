<?php
# field_text class definition file
class field_text extends field {
	public $minlength = 0;
	public $maxlength = 0;
	
  function getdbfieldtype() {
  	if ($this->maxlength>0) {
  		return "varchar(".$this->maxlength.')';
  	} else {
  		return 'text';
  	}
  }
} 