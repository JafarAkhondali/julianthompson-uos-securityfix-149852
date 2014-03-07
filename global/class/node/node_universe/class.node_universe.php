<?php
include_once UOS_LIBRARIES . "adodb/adodb5/adodb.inc.php";
# node_universe class definition file
class node_universe extends node {

	public $dbconnection = NULL;
  

  public function connect() {
  	if (!$this->dbconnection) {
			$this->dbconnection = NewADOConnection($this->dbconnector->value);
		}
  }  

	public function getentities($filter) {
		$filter = format_initializer($filter);
		return $filter;
		$this->dbconnection->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->dbconnection->debug=TRUE;
		return $this->dbconnection->GetAssoc("SELECT * FROM node WHERE type LIKE 'node_%'");
	}


} 