<?php
# node class definition file
class node extends entity {
  
  public function __toString() {
    return (string) $this->title;
  }
  
	function event_propertymodified($property) {
		parent::event_propertymodified($property);
		if ($property->key != 'modified') {
			$this->modified->setvalue(time());
		}
	}
} 