<?php
include_once UOS_LIBRARIES . "adodb/adodb5/adodb.inc.php";
# node_universe class definition file
class node_universe extends node {

	public $dbconnection = NULL;
	public $tags = array();
  

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
	
	function add($entity) {
		$entity->guid = $this->createguid();
		return $this->db_entity_insert($entity);
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
			//echo $sql;
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
			//echo $sql;
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
	
	function db_query() {
		$args = func_get_args();
		$sql = call_user_func_array('sprintf',$args);
		$connection = $this->db_connect();
		return $connection->Execute($sql);
	}
	
	
	function db_unique_guid() {
		do {
			$guid = $this->createguid();
			$result = $this->db_query('SELECT id FROM `entity` WHERE guid="%d" LIMIT 1',$guid);
		} while ($result->fields['id']);
		return $guid;
	}
	
	
	function db_clear_universe() {
	
		$connection = $this->db_connect();
		$tables = $connection->MetaTables(); 
		//print_r($tables)
		foreach ($tables as $table) {
			$sql = sprintf('DROP TABLE IF EXISTS %s', $table);
			$result = $connection->Execute($sql);
		}
	}
	
	
	function db_select_entity($guid) {
	
		$connection = $this->db_connect();
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);

		$sql = 'SELECT * FROM `entity` WHERE guid="'.$guid.'" LIMIT 1';
		$result = $connection->Execute($sql);	
		
		if ($result) {
			
			//return $result->fields;
			
			$type = $result->fields['type'];
			
			// this new type to just get table definition is wasteful
			$entity = new $type();
			$tables = ($entity->__gettabledefinition());	
			unset($tables['entity']);
			$joins = array();
			foreach ($tables as $table=>$fields) {
				$joins[] = "JOIN `$table` ON $table.entity_id = entity.id";
			}
			$sql = 'SELECT * FROM `entity` '.implode(' ',$joins).' WHERE id='.$result->fields['id'].' LIMIT 1';
			$result = $connection->Execute($sql);
			if ($result) {			
				$entity = new $type($result->fields);			
				//return $result;	
				//return $sql;
				return $entity;
				//return $tables;
			}

		}
	}
	
	
	function db_select_children() {
	
		$entities = array();
		$connection = $this->db_connect();
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);
		
  	$ids = array_unique(func_get_args());
		
		// remove universe id from array
		if(($key = array_search(1, $ids)) !== false) {
    	unset($ids[$key]);
		}
		
		$ids = array_values($ids);
		
		//$idcount = count($ids);		
		
				
		$joins = array();
		foreach($ids as $key=>$id) {
			$joins[] = sprintf("INNER JOIN `relationship` r%d ON r%d.to = entity.id AND r%d.from = %d",$key,$key,$key,$id);
		}

		$sql = sprintf("SELECT DISTINCT id,type FROM `entity` %s WHERE entity.type !='relationship' AND entity.id != 1",implode(' ',$joins));

		//print_r($ids);
		//print_r($sql);die();
		
		$result = $connection->Execute($sql);		
		
		while (!$result->EOF) {
				$type = $result->fields['type'];
				// this new type to just get table definition is wasteful
				$entity = new $type();
				$tables = ($entity->__gettabledefinition());	
				unset($tables['entity']);
				$joins = array();
				//print_r($tables);
				foreach ($tables as $table=>$fields) {
					$joins[] = "JOIN `$table` ON $table.entity_id = entity.id";
				}
				
				$sql = 'SELECT * FROM `entity` '.implode(' ',$joins).' WHERE id='.$result->fields['id'].' LIMIT 1';
				$eresult = $connection->Execute($sql);
				if ($eresult) {	
					unset($eresult->fields['entity_id']);		
					$entity->initialize($eresult->fields);		
					//print_r($eresult->fields);
					$entities[] = $entity;	
				}
				$result->MoveNext();
		}	
		//die();
		//print count($entities);die();
		return $entities;	
	}


} 