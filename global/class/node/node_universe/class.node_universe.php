<?php
include_once UOS_LIBRARIES . "adodb/adodb5/adodb.inc.php";
# node_universe class definition file
class node_universe extends node {

	public $dbconnection = NULL;
	public $tags = array();
  

  public function test() {
  	return(file_exists($this->datapath) && $this->db_connect());
  }

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
		$entity->guid = $this->db_unique_guid();	
		$entity->relocatefiles();
		return ($this->db_entity_insert($entity))?$entity->guid->value:null;
	}

/*
	//moved to entity	
	function db_entity_structure($entity) {
		$tables = array();
		
		$properties = $entity->getproperties();
		foreach($properties as $property) {
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
*/
	function db_entity_insert($entity) {
		trace('ENTITY_INSERT');
		$this->db_create_tables($entity);
		//$tables = $this->db_entity_data($entity);
		$tables = $entity->___getdata();
		foreach($tables as $scope=>$values) {
			$sql = "INSERT INTO `".$scope."` (`".implode('`,`',array_keys($values))."`) VALUES ('".implode('\',\'',$values)."');\n";
			//echo $sql;
			trace($sql);
			$connection = $this->db_connect();
			$result = $connection->Execute($sql);
			if ($result) {
				$insertid = $connection->Insert_ID();
				$entity->id->value = $insertid;
				//echo "RESULT ".$insertid;
			} elseif ($result===false) {
				//print 'error inserting: '.$connection->ErrorMsg();
				throw new Exception('error inserting: '.$connection->ErrorMsg());
			}
			//print_r($result);
		}
		return true;
	}
	
	function db_create_tables($entity) {
		//$tables = $this->db_entity_structure($entity);
		$tables = $entity->___gettabledefinition();
		//print_r($tables);
		//$primarykey = $this->db_entity_primary_key($entity);
		$primarykey = $entity->getindexproperty()->key;
		//print_r($primarykey);
		//die('create tables');
		$primarykeystr = ($primarykey) ? ', PRIMARY KEY (`'.$primarykey.'`)' : '';
		foreach($tables as $scope=>$values) {
			$fielddata = array();
			foreach($values as $key=>$value) {
				$fielddata[] = '`' . $key . '` ' . $value;
			}
			$sql = "CREATE TABLE IF NOT EXISTS `".$scope."` (".implode(', ',$fielddata)."$primarykeystr);";
			trace($sql);
			$connection = $this->db_connect();
			$result = $connection->Execute($sql);
			//if ($result) echo "Created $scope Table\n";
			//print_r($result);
			$primarykeystr = '';
		}	
	}
	
	function db_create($universename) {
		//CREATE DATABASE testDB;
			print_r($this->dbconnector->value);
		$databasename = 'uos_'.str_replace(".","_",$universename);
		$result = $this->db_query("CREATE DATABASE IF NOT EXISTS %s", $databasename);
		if ($result) {
			$this->dbconnection->close();
			$this->dbconnection = NULL;
			$this->dbconnector->value = $this->dbconnector->value .'/'.$databasename;
			$this->title->value = $universename;
			//print_r($this->dbconnector->value);
			$this->db_connect();	
			//print_r(new entity);
			$this->db_create_tables(new entity);
			//$this->db_create_tables(new field);
			$this->db_create_tables(new relationship);
		}
		
		$universedatapath = UOS_GLOBAL_DATA . $universename . '/'; 
		$universeconfigpath = $universedatapath . 'config.universe.php';
		
		umask(0);
		
		// create file store
		if (!file_exists($universedatapath)) {
			mkdir($universedatapath,0777,TRUE);
		}
		if (!file_exists($universeconfigpath)) {
			$config = sprintf("<?php\n\n\$uos->config->universe = (object) array(\n\t'dbconnector' => '%s',\n\t'datapath' => UOS_GLOBAL_DATA . '%s/',\n\t'title' => '%s'\n);\n", $this->dbconnector->value, $universename, $universename);
			file_put_contents($universedatapath . 'config.universe.php', $config);
			chmod($universeconfigpath, 0777);
		}
		
		// create cache
		$universecachepath = UOS_GLOBAL_CACHE . $universename . '/';
		if (!file_exists($universedatapath)) {
			mkdir($universecachepath,0777,TRUE);
		} 
	}
	
	function getcachepath() {
		return UOS_GLOBAL_CACHE . $this->title->value . '/';
	}
	
	function createguid() {
		$seed = str_split('123456789123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$rand = '';
		foreach (array_rand($seed, 16) as $k) $rand .= $seed[$k];
		return $rand;
	}
	
	function db_unique_guid($table='entity') {
		do {
			$guid = $this->createguid();
			$result = $this->db_query('SELECT id FROM `%s` WHERE guid="%d" LIMIT 1',$table,$guid);
		} while ($result->fields['id']);
		return $guid;
	}
	
	function is_guid($testvalue) {
		return (preg_match('/^[0-9]{16}$/', $testvalue)>0);
	}
	
	function db_query() {
		$args = func_get_args();
		$sql = call_user_func_array('sprintf',$args);
		$connection = $this->db_connect();
		return $connection->Execute($sql);
	}
	
	function guid_to_id($guidmixed) {
		$guids = $this->normalize_guid_list($guidmixed);
		$result = $this->db_query('SELECT id FROM `entity` WHERE guid IN (%d)',$table,implode(',',$guids));
		while (!$result->EOF) {
			$ids[]= $result->fields['id'];
		}
		return $ids;
	}
	
	function normalize_guid_list($guids) {
		if (is_string($guidmixed)) {
				$guids = explode(',',$guidmixed);
		}
		
		if (is_array($guids)) {			
			return array_filter($guids, array($this, 'is_guid'));
		}
		
		return null;
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
		
		$indextype = $this->is_guid($guid)?'guid':'id';
		$indextype = 'guid';

		$sql = 'SELECT * FROM `entity` WHERE '.$indextype.'="'.$guid.'" LIMIT 1';
		$result = $connection->Execute($sql);	
		
		if ($result && !$result->EOF) {
			
			//return $result->fields;
			//print_r($result);
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

		//$sql = sprintf("SELECT DISTINCT id,type FROM `entity` %s WHERE entity.type !='relationship' AND entity.id != 1",implode(' ',$joins));
		// universe no longer entity 1
		$sql = sprintf("SELECT DISTINCT id,type FROM `entity` %s WHERE entity.type !='relationship'",implode(' ',$joins));

		trace($sql);
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
				trace($sql);
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