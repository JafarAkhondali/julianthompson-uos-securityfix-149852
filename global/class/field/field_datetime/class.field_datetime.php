<?php
# field_datetime class definition file
class field_datetime extends field {
  function getdbfieldtype() {
  	return "datetime null";
 	}
 	
 	
  public function setvalue($timestring, $setmodified = TRUE) {
  	//$this->value = 'xxxx';
    //throw new Exception('setvalue');
  	$time = (is_numeric($timestring)) ? $timestring : strtotime($timestring);
  	$value = date('Y-m-d H:i:s',$time);
  	parent::setvalue($value, $setmodified);
  }
} 