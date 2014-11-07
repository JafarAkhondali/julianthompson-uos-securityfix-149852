<?php
# field_datetime class definition file
class field_datetime extends field {
  function getdbfieldtype() {
  	return "datetime null";
 	}
 	
 	
  public function setvalue($timestring) {
  	$time = strtotime($timestring);
  	$this->value = date('Y-m-d H:i:s',$time);
  }
} 