<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id: $
 */
/**
 * phpClassGenerator class
 * <code>
 * 
 * </code>
 */
class phpClassGenerator {
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
	 * @return phpClassGenerator
	 */
	static function listTable(){
		$results = self::$db->fetchAll("SHOW TABLES");
		$nb = count($results);
		for ($a = 0 ; $a < $nb ; $a++) {
			$_tables[]['name'] = $results[$a][key($results[$a])];
		}
		self::$tables = $_tables;
		return $_tables;
		//return $this;
	}
	/**
	 * Create object and object manager 
	 *
	 * @param string $tableName
	 * @param [string] $objectName
	 */
	static function createObjects($tableName, $objectName=null){
		$table = new blankTable(array('name' => $tableName, 'db' => self::$db));
		$infos = $table->info();
		$primary = $infos['primary'][1];
		
		if(!$objectName){
			$objectName = $tableName;
		}
		
		self::$objects[] = array('objectName' => $objectName,
								 'object' => new phpGenObject(),
								 'objectManager' => new phpGenObjectManager());
		
//		Zend_Debug::dump($infos);
		$objectKey = key(self::$objects);
		self::$objects[$objectKey]['object']->setName($objectName);
		
		
		$nb = count($infos['cols']);
		for ($a = 0 ; $a < $nb ; $a++) {
			$propertyName = null;
			$column = $infos['cols'][$a];
			//check for local field or foreign field
			$localField = false;
			$localField = preg_match('#^'.$objectName.'_#', $column);
			if($localField){
				$propertyName = ereg_replace('^('.$objectName.'_)', '', $column);
				$propertyName = self::formatPropertyName($propertyName);
				self::$objects[$objectKey]['object']->addProperty($propertyName, 
																array(
																'default' => $infos['metadata'][$column]['DEFAULT'],
																'type' => $infos['metadata'][$column]['DATA_TYPE'],
																'primary' => ($primary == $column ? true:false)
																));				
			}
			else{
				self::$relatedField[] = array('fromTable' => $tableName, 'toField' => $column);
			}
			//Set object wich will be manipulate by the manager
			self::$objects[$objectKey]['objectManager']->setObject(self::$objects[$objectKey]['object']);
		}
		
		Zend_Debug::dump(self::$objects[$objectKey]['objectManager']->generate());
		
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