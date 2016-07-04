<?php
# node class definition file
$uos->config->types['node']->title = 'Category';
$uos->config->types['node']->titleplural = 'Categories';
$uos->config->types['node']->description = 'A simple category.';


class node extends entity {
  
  public function __toString() {
    return (string) $this->title;
  }
  
	function event_propertymodified($property) {
		parent::event_propertymodified($property);
		if ($property->key != 'modified') {
			//$this->modified->setvalue(time());
		}
	}
} 