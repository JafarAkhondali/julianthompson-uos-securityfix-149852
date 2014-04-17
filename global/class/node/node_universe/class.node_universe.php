<?php
include_once UOS_LIBRARIES . "adodb/adodb5/adodb.inc.php";
# node_universe class definition file
class node_universe extends node {

	public $dbconnection = NULL;
  

  public function db_connect() {
  	if (!$this->dbconnection) {
			$this->dbconnection = NewADOConnection($this->dbconnector->value);
		}
		return $this->dbconnection;
  }  

	public function getentities($filter) {
		$filter = format_initializer($filter);
		return $filter;
		$this->dbconnection->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->dbconnection->debug=TRUE;
		return $this->dbconnection->GetAssoc("SELECT * FROM node WHERE type LIKE 'node_%'");
	}

	public function add($entity) {
		
	}
	
	function db_entity_structure($entity) {
		$tables = array();
		foreach($entity->properties as $property) {
			if (is_subclass_of($property,'field')) {
				$auto = ($entity->indexproperty->key==$property->key) ? ' NOT NULL AUTO_INCREMENT':'';
				$tables[($property->scope)][$property->key] = $property->getdbfieldtype() . $auto;
			}
		}
		$indexscope = $entity->indexproperty->scope;
		$indexelements = array($indexscope.'_id'=> $entity->indexproperty->getdbfieldtype());
		
		foreach($tables as $scope=>$property) {
			if ($scope!==$indexscope) {
				$tables[$scope] = array_merge($indexelements, $tables[$scope]);
			}
		}
		return $tables;
	}
	
	function db_entity_primary_key($entity) {
		return $entity->indexproperty->key;
	}
	
	function db_entity_data($entity) {
		$tables = array();
		foreach($entity->properties as $property) {
			if (is_subclass_of($property,'field')) {
				if ($entity->indexproperty->key!==$property->key) {
					$tables[($property->scope)][$property->key] = $property->getdbfieldvalue();
				}
			}
		}
		$indexscope = $entity->indexproperty->scope;
		$indexelements = array($indexscope.'_id'=> &$entity->properties[$entity->indexproperty->key]->value);
				
		foreach($tables as $scope=>$property) {
			if ($scope!==$indexscope) {
				$tables[$scope] = array_merge($indexelements, $tables[$scope]);
			}
		}
		return $tables;
	}
	
	function db_entity_insert($entity) {
		$this->db_create_tables($entity);
		$tables = $this->db_entity_data($entity);
		foreach($tables as $scope=>$values) {
			$sql = "INSERT INTO `".$scope."` (`".implode('`,`',array_keys($values))."`) VALUES ('".implode('\',\'',$values)."');\n";
			echo $sql;
			$connection = $this->db_connect();
			$result = $connection->Execute($sql);
			if ($result) {
				$insertid = $connection->Insert_ID();
				$entity->id->value = $insertid;
				//echo "RESULT ".$insertid;
			} elseif ($result===false) {
				print 'error inserting: '.$connection->ErrorMsg();
			}

			//print_r($result);
		}
	}
	
	function db_create_tables($entity) {
		$tables = $this->db_entity_structure($entity);
		$primarykey = $this->db_entity_primary_key($entity);
		$primarykeystr = ($primarykey) ? ', PRIMARY KEY (`'.$primarykey.'`)' : '';
		foreach($tables as $scope=>$values) {
			$fielddata = array();
			foreach($values as $key=>$value) {
				$fielddata[] = '`' . $key . '` ' . $value;
			}
			$sql = "CREATE TABLE IF NOT EXISTS `".$scope."` (".implode(', ',$fielddata)."$primarykeystr);";
			echo $sql;
			$connection = $this->db_connect();
			$result = $connection->Execute($sql);
			//print_r($result);
			$primarykeystr = '';
		}	
	}
	
	function createguid() {
		$seed = str_split('123456789123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$rand = '';
		foreach (array_rand($seed, 16) as $k) $rand .= $seed[$k];
		return $rand;
	}
	
	function db_select_entity($guid) {
		$sql = 'SELECT * FROM `entity` WHERE guid="'.$guid.'" LIMIT 1';
		$connection = $this->db_connect();
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);
		$result = $connection->Execute($sql);	
		
		if ($result) {
			return $result->fields;
		}
	}

} 