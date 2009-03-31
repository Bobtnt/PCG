<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */
/**
 * phpClassGenerator class
 * <code>
 * 
 * </code>
 */
class phpClassGenerator extends configObjectAbstract {
	/**
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	static $db;
	static $tables = array();
	static $objects = array();
	static $relatedField = array();
	
	static $userZendLoader = false;
	
	/**
	 * initialize database connection
	 *
	 * @param [string] $databaseName
	 */
	static function factory($databaseName=null){
		 if(!self::$db){
		 	self::$db = database_binder::factory($databaseName);
		 }	 
	}
	/**
	 * list all database table's
	 *
	 * @return array
	 */
	static function listTable(){
		$results = self::$db->fetchAll("SHOW TABLES");
		$nb = count($results);
		for ($a = 0 ; $a < $nb ; $a++) {
			$_tables[]['name'] = $results[$a][key($results[$a])];
		}
		self::$tables = $_tables;
		return $_tables;
	}
	
	/**
	 * Make all object form database
	 * make sur have load listTable before
	 *
	 */
	static function makeAllObjects(){
		
		$nb = count(self::$tables);
		# Need to prefetch object before create this. this cause by relationship
		for ($a = 0 ; $a < $nb ; $a++) {
			$objectName = self::$tables[$a]['name'];
			$tableName = self::$tables[$a]['name'];
			$objectKey = $a;
			self::$objects[$objectKey] = array('objectName' => $objectName,
								 'object' => new phpGenObject(),
								 'objectManager' => new phpGenObjectManager(),
								 'objectCollection' => new phpGenObjectCollection(),
								 'make' => true);
			self::$objects[$objectKey]['object']->setName($tableName);
			self::$objects[$objectKey]['object']->setTableName($tableName);
		}
		# now fill all objects
		for ($a = 0 ; $a < $nb ; $a++) {
			self::createObjects(self::$tables[$a]['name']);
		}		
	}
	
	/**
	 * Create objects form table in out folder 
	 *
	 * @param string $tableName
	 * @param [string] $objectName
	 * @return bool
	 */
	static function createObjects($tableName, $objectName=null){
		$table = new blankTable(array('name' => $tableName, 'db' => self::$db));
		$infos = $table->info();
		$primary = $infos['primary'][1];
		$flag['nm'] = false;
		$flag['11'] = false;
		$flag['make'] = true;
		$matches = array();
		//check for relation table
		if(preg_match('#(.+)_has_(.+)#', $tableName, $matches)){
			$flag['make'] = false;
			//its relation table. Now determine what relation type is it.
			//if all columns are primary, its a n:m table
			if(count($infos['cols']) == count($infos['primary'])){
				$flag['nm'] = true;
			}
			//else it's 1:1 relation
			else{
				$flag['11'] = true;
				$srcTable = $matches[1];
				$linkedTable = $matches[2];
			}
		}
		
		if(!$primary){
			//CANNOT Create Object without Primary Key
			return false;
		}
		
		if(!$objectName){
			$objectName = $tableName;
		}
		
		$objectKey=false;
		$_object = self::getObjectByTableName($tableName, $objectKey);
		
		self::$objects[$objectKey]['make'] = $flag['make'];
		self::$objects[$objectKey]['object']->setName($objectName);
		
		$nb = count($infos['cols']);
		for ($a = 0 ; $a < $nb ; $a++) {
			$propertyName = null;
			$column = $infos['cols'][$a];
			//check for local field or foreign field
			$localField = false;
			$localField = preg_match('#^'.$objectName.'_#', $column);
			if($localField){
				//$oneLocalfieldAtLeast = true;
				$propertyName = ereg_replace('^('.$objectName.'_)', '', $column);
				$propertyName = self::formatPropertyName($propertyName);			
			}
			else{
				$propertyName = self::formatPropertyName($column);
				if($flag['nm']){
					$relation = 'n:m';
				}
				elseif($flag['11']){
					$relation = '1:1';
					//if not pk
					if($column != $primary){
						// catch the foreign object and add this 1:1 relationship property
						$_o = self::getObjectByTableName($linkedTable);
						
						$_o->addProperty($propertyName, 
											array(
											'default' => $infos['metadata'][$column]['DEFAULT'],
											'type' => $infos['metadata'][$column]['DATA_TYPE'],
											'fieldName' => $column,
											'primary' => ($primary == $column ? true:false),
											'foreignField' => true 
											));	
					}
				}
				else{
					$relation = '1:n';
				}
				self::$relatedField[] = array('fromTable' => $tableName, 'toField' => $column, 'object' => $objectName, 'relationType' => $relation);
			}
			self::$objects[$objectKey]['object']->addProperty($propertyName, 
															array(
															'default' => $infos['metadata'][$column]['DEFAULT'],
															'type' => $infos['metadata'][$column]['DATA_TYPE'],
															'fieldName' => $column,
															'primary' => ($primary == $column ? true:false),
															'foreignField' => false 
															));	
		}
		
		//Set object wich will be manipulate by the manager
		self::$objects[$objectKey]['objectManager']->setObject(self::$objects[$objectKey]['object']);
		//set object wich will be manipulate by the colection
		self::$objects[$objectKey]['objectCollection']->setObject(self::$objects[$objectKey]['object']);
		return true;
	}

	static function getObjectByName($name){
		$findKey = false;
		foreach (self::$objects as $key => $subArray) {
			if($subArray['objectName'] == $name){
				$findKey = $key;
				break;
			}
		}
		return $findKey !== 0 ? self::$objects[$findKey]['object'] : false;
	}
	
	static function getObjectByTableName($tableName, &$findKey=false){
		foreach (self::$objects as $key => $subArray) {
			if($subArray['object']->getTableName() == $tableName){
				$findKey = $key;
				break;
			}
		}
		return $findKey !== false ? self::$objects[$findKey]['object'] : false;
	}
	
	
	static function makeAll(){
		foreach (self::$objects as $object) {
			$objectName = $object['object']->getName();
			$strObject = $object['object']->generate();
			$strObjectManager = $object['objectManager']->generate();
			$strObjectCollection = $object['objectCollection']->generate();
			if($object['make']){
				self::make($strObject, $strObjectManager, $strObjectCollection, $objectName, true);
			}
		}
	}
	
	static function make($strObject, $strObjectManager, $strObjectCollection, $objectName){
		$userZendLoader = self::$userZendLoader;
		if(!$userZendLoader){
			$f = fopen(self::OUTPUT_FOLDER.'/class.'.$objectName.'.php', "w+");
			fwrite($f, $strObject);
			fclose($f);
			$f = fopen(self::OUTPUT_FOLDER.'/class.'.$objectName.'_manager.php', "w+");
			fwrite($f, $strObjectManager);
			fclose($f);
			$f = fopen(self::OUTPUT_FOLDER.'/class.'.$objectName.'_collection.php', "w+");
			fwrite($f, $strObjectCollection);
			fclose($f);
		}
		else{
			@mkdir(self::OUTPUT_FOLDER.'/'.$objectName);
			$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'.php', "w+");
			fwrite($f, $strObject);
			fclose($f);
			$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'/manager.php', "w+");
			fwrite($f, $strObjectManager);
			fclose($f);
			$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'/collection.php', "w+");
			fwrite($f, $strObjectCollection);
			fclose($f);
			self::genCustomFiles($objectName);
		}
	}
	
	
	static function genCustomFiles($objectName){
		@mkdir(self::OUTPUT_FOLDER.'/'.$objectName.'/manager');
		@mkdir(self::OUTPUT_FOLDER.'/'.$objectName.'/collection');
		$str = '<?php 
		abstract class '.$objectName.'_custom {
			//add your own code here
		}
		?>';
		$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'/custom.php', "w+");
		fwrite($f, $str);
		fclose($f);
		
		$str = '<?php
		abstract class '.$objectName.'_manager_custom {
			//add your own code here
		}
		?>';
		$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'/manager/custom.php', "w+");
		fwrite($f, $str);
		fclose($f);
		
		$str = '<?php
		abstract class '.$objectName.'_collection_custom {
			//add your own code here
		}
		?>';
		$f = fopen(self::OUTPUT_FOLDER.'/'.$objectName.'/collection/custom.php', "w+");
		fwrite($f, $str);
		fclose($f);
		
	}
	
	/**
	 * Format property name with field name
	 * remove _ sign and upcase the first char of each word
	 *
	 * @param string $propertyName
	 * @return string
	 */
	static function formatPropertyName($propertyName){
		return preg_replace("#(_(.))#e" , "strtoupper('\\2')" , $propertyName);		
	}
	
	
	
}

?>