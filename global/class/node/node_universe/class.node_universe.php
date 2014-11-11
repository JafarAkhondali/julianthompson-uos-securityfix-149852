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

	public function add($entity) {
		$entity->guid = $this->db_unique_guid();	
		$entity->relocatefiles();
		return ($this->db_entity_insert($entity))?$entity->guid->value:null;
	}
	
	public function destroy($entity) {	
		$entity->relocatefiles();
		recursiveDelete($entity->cachepath());
		recursiveDelete($entity->datapath());	
		$this->removerelationships($entity);	
		return $this->db_entity_destroy($entity);
		//return ($this->db_entity_delete($entity))?$entity->guid->value:null;
	}
	
	public function destroy_universe() {	
		//$entity->relocatefiles();
		recursiveDelete(UOS_GLOBAL_DATA . $this->title->value);
		$this->db_clear_universe();
		$this->writeconfig($this->title->value);
		$this->db_initialize();
		//$this->removerelationships($entity);	
		//return $this->db_entity_destroy($entity);
		//return ($this->db_entity_delete($entity))?$entity->guid->value:null;
		return "destroyed :" . $this->title->value;
	}
	/*
	public function addtag($entity) {
		$this->tags[$entity->guid->value] = $entity;
	}
	*/
	// put tagentities in $entity
	public function tagcontent($entity, $tagentitiesid, $required=FALSE) {		
		foreach($tagentitiesid as $tagid) {
			$relationship = new relationship(array(
				'required'=> $required,
				'fieldname'=> '',
				'parent' => $entity->id,
				'child' => $tagid
			));
			
			trace('trying to insert relationship : '.$tagid,'tagcontent');
			//$this->db_entity_insert($relationship);
			$this->add($relationship);
			$this->trace('inserted relationship');
		}
		incrementweight($entity,count($tagentitiesid));
	}
	
	public function removerelationships($entity, $type='ALL') {
		$entityid = $entity->id->value;
		$sql = sprintf('SELECT id,type FROM `relationship` JOIN `entity` ON `relationship`.`entity_id` = `entity`.`id`  WHERE `relationship`.`parent`=%d OR `relationship`.`child`=%d;',$entityid,$entityid);
		$connection = $this->db_connect();
		$result = $connection->Execute($sql);
		while($result && !$result->EOF) {
			//$sql .= "<br>\n" . $result->fields['id'];
			$tempentity = new relationship($result->fields);
			$sql .= '<br>ed:'.$this->db_entity_destroy($tempentity);
			$result->MoveNext();
		}
		return $sql;
	}
	
	public function incrementweight($entity,$increment=1) {
		$sql = sprintf('UPDATE `entity` SET `entity`.`weight` = `entity`.`weight` + %d WHERE `entity`.`id` = %d',$increment,$entity->id);
		$connection = $this->db_connect();
		$result = $connection->Execute($sql);
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
		trace('ENTITY_INSERT','db_entity_insert');
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
	
	
	
	function db_entity_destroy($entity) {
		$tables = $entity->___getdata();	
		$primarykey = $entity->getindexproperty();
		$tables = array_reverse($tables);
		$connection = $this->db_connect();
		foreach($tables as $scope=>$value) {
			$keyfield = ($primarykey->scope==$scope)?$primarykey->key : $primarykey->scope.'_'.$primarykey->key;
			$sql = sprintf("DELETE FROM `%s` WHERE `%s`.`%s` = %d;",$scope,$scope,$keyfield,$entity->id->value);
			$result = $connection->Execute($sql);
			$allsql .= ($sql . "\n" . print_r($result,TRUE));
		}

		//return $sql;
		return $allsql;
		return TRUE;
	}
	
	function db_create_tables($entity) {
		//$tables = $this->db_entity_structure($entity);
		$tables = $entity->___gettabledefinition();
		//print_r($tables);
		//$primarykey = $this->db_entity_primary_key($entity);
		$primarykey = $entity->getindexproperty()->key;
		//$uniquekey = $entity->uniqueproperties;		
		//print_r($primarykey);
		//die('create tables');
		$primarykeystr = ($primarykey) ? ', PRIMARY KEY (`'.$primarykey.'`)' : '';
		foreach($tables as $scope=>$values) {
			$uniquestr = ', UNIQUE KEY (pid, aid)'; // <- to be completed
			$fielddata = array();
			foreach($values as $key=>$value) {
				$fielddata[] = '`' . $key . '` ' . $value;
			}
			$sql = "CREATE TABLE IF NOT EXISTS `".$scope."` ( " . implode(', ',$fielddata) . $primarykeystr . ");";
			trace($sql);
			$connection = $this->db_connect();
			$result = $connection->Execute($sql);
			//if ($result) echo "Created $scope Table\n";
			//print_r($result);
			$primarykeystr = '';
		}	
	}
	
	function db_initialize() {
		$this->db_create_tables(new entity);
		//$this->db_create_tables(new field);
		$this->db_create_tables(new relationship);	
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
			$this->db_initialize();
		}
		
		$this->writeconfig($universename);

	}
	
	function writeconfig($universename) {
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
		trace($sql,'Database query');
		$connection = $this->db_connect();
		return $connection->Execute($sql);
	}


	
	function guid_to_id($guidmixed) {
		$guids = $this->normalize_guid_list($guidmixed);
		$result = $this->db_query('SELECT id FROM `entity` WHERE guid IN (%s)',implode(',',$guids));
		while ($result && !$result->EOF)  {
			$ids[]= $result->fields['id'];
			$result->MoveNext();
		}
		$this->trace($ids);
		return $ids;
	}
	
	function normalize_guid_list($guids) {
		if (is_string($guids)) {
				$guids = explode(',',$guids);
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
	
	function db_search($searchobj, $count=FALSE) {

		$operator = '=';

		$wheres = array();

		$joins = array();
		
		$order = array();

		foreach($searchobj['where'] as $value) {
			//list($tablename,$fieldname) = explode('.',$tablefield);
			$tablename = $value['table']; 
			if (!isset($joins[$tablename])) {
				$joins[$tablename] = sprintf("INNER JOIN `%s` ON %s.entity_id = entity.id",$tablename,$tablename);
			}
			$wheres[] = sprintf("%s.%s %s '%s'",$value['table'],$value['field'],$value['operator'],$value['value']);	
		}	
		$selects = ($count)?'COUNT(*)':'DISTINCT entity.id,entity.type';
		$joins = (empty($joins))? '' : implode(' ', $joins);
		$wheres = (empty($wheres))? '' : ' WHERE ' . implode(' AND ', $wheres);
		$order = (empty($order))? '' : ' ORDER BY ' . implode(',', $order);
		
		$sql = sprintf("SELECT %s FROM `entity` %s %s %s", $selects, $joins, $wheres, $order);
		return $this->db_get_entities($sql);	
	}
	
	
	function db_select_children() {
		
  	$ids = array_unique(func_get_args());
		trace($ids,'db_select_children');
		// remove universe id from array
		if(($key = array_search(0, $ids)) !== false) {
    	unset($ids[$key]);
		}
		
		$ids = array_values($ids);
		
		//$idcount = count($ids);		
		
				
		$joins = array();
		foreach($ids as $key=>$id) {
			$joins[] = sprintf("INNER JOIN `relationship` r%d ON r%d.child = entity.id AND r%d.parent = %d",$key,$key,$key,$id);
		}

		//$sql = sprintf("SELECT DISTINCT id,type FROM `entity` %s WHERE entity.type !='relationship' AND entity.id != 1",implode(' ',$joins));
		// universe no longer entity 1
		$sql = sprintf("SELECT DISTINCT id,type FROM `entity` %s WHERE entity.type !='relationship'",implode(' ',$joins));

		trace($sql);
		
		return $this->db_get_entities($sql);

	}

	public function db_get_entities($sql) {
		$entities = array();
		$connection = $this->db_connect();
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);
		
		$result = $connection->Execute($sql);	
		//return $sql;
		while ($result && !$result->EOF) {	
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
		return $entities;	
	}
	

} 
