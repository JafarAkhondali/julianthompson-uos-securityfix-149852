<?php
# field class definition file
class field extends entity {
	public $value = null;  
	private $parent = null;
	public $key = null;
	public $guid = null;
	public $title = '';
	public $locked = FALSE;
	public $required = FALSE;
	public $indexfield = FALSE;
	
	
	function __construct($initializer=null) {
	
		$initializer = format_initializer($initializer);
		
		foreach($initializer as $fieldname=>$fieldvalue) {
			//if (isset($this->fieldname)) {
				$this->$fieldname = $fieldvalue;
			//}
		}
  }
  
  function isvalid() {
  	return TRUE;
  }
  
  function getdbfieldtype() {
  	return "text";
  }
  
  public function __toString() {
    return (string) $this->value;
  }
} 