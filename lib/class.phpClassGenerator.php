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
		for ($a = 0 ; $a < $nb ; $a++) {
			self::createObjects(self::$tables[$a]['name']);
		}		
	}
	
	/**
	 * Create object and object manager in out folder 
	 *
	 * @param string $tableName
	 * @param [string] $objectName
	 * @return bool
	 */
	static function createObjects($tableName, $objectName=null){
		$table = new blankTable(array('name' => $tableName, 'db' => self::$db));
		$infos = $table->info();
		$primary = $infos['primary'][1];
		$mnFlag = false;
		//check for relation table
		if(preg_match('#(.+)_has_(.+)#', $tableName)){
			$mnFlag = true;
		}
		
		if(!$primary){
			//CANNOT Create Object without Primary Key
			return false;
		}
		
		if(!$objectName){
			$objectName = $tableName;
		}
		
		self::$objects[] = array('objectName' => $objectName,
								 'object' => new phpGenObject(),
								 'objectManager' => new phpGenObjectManager(),
								 'objectCollection' => new phpGenObjectCollection());
		
		$objectKey = (count(self::$objects) - 1);
		self::$objects[$objectKey]['object']->setName($objectName);
		self::$objects[$objectKey]['object']->setTableName($tableName);
		$oneLocalfieldAtLeast = false;
		$nb = count($infos['cols']);
		
		
		
		
		
		for ($a = 0 ; $a < $nb ; $a++) {
			$propertyName = null;
			$column = $infos['cols'][$a];
			//check for local field or foreign field
			$localField = false;
			$localField = preg_match('#^'.$objectName.'_#', $column);
			if($localField){
				$oneLocalfieldAtLeast = true;
				$propertyName = ereg_replace('^('.$objectName.'_)', '', $column);
				$propertyName = self::formatPropertyName($propertyName);			
			}
			else{
				$propertyName = self::formatPropertyName($column);
				self::$relatedField[] = array('fromTable' => $tableName, 'toField' => $column, 'object' => $objectName, 'relationType' => ($mnFlag ? 'n:m': '1:n'));
			}
			Zend_Debug::Dump($propertyName);
			self::$objects[$objectKey]['object']->addProperty($propertyName, 
															array(
															'default' => $infos['metadata'][$column]['DEFAULT'],
															'type' => $infos['metadata'][$column]['DATA_TYPE'],
															'fieldName' => $column,
															'primary' => ($primary == $column ? true:false)
															));	
		}
		//if(!$oneLocalfieldAtLeast){
			//no local field on table. do not create object
			//may be a n,m link table
			//return false;	 
		//}
		//else{
		//Set object wich will be manipulate by the manager
		self::$objects[$objectKey]['objectManager']->setObject(self::$objects[$objectKey]['object']);
		//set object wich will be manipulate by the colection
		self::$objects[$objectKey]['objectCollection']->setObject(self::$objects[$objectKey]['object']);
		//}
		return true;
	}
	
	static function makeAll(){
		foreach (self::$objects as $object) {
			$objectName = $object['object']->getName();
			$strObject = $object['object']->generate();
			$strObjectManager = $object['objectManager']->generate();
			$strObjectCollection = $object['objectCollection']->generate();
			self::make($strObject, $strObjectManager, $strObjectCollection, $objectName);
		}
	}
	
	static function make($strObject, $strObjectManager, $strObjectCollection, $objectName){
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