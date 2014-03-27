<?php
# node class definition file
class node extends entity {
  
  public function __toString() {
    return (string) $this->title;
  }
} 