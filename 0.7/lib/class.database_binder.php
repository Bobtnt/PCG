<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */
/**
 * Database connector
 * maintain connection for all database connector 
 *
 * @static $db array of db connector 
 */
class database_binder {
	
	private static $db;
	const DATABASE_NAME = 'DEFAULT_DATABASE';
	
	/**
	 * object builder, use singleton
	 * 
	 * @param string database name
	 * @return object
	 */
	public static function factory($databaseName=null){
		try{
			return self::singleton($databaseName);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
		return false;
	}
	/**
	 * Unique connection to databases
	 *
	 * @param string $databaseName
	 * @return object Zend_Db_Adapter_Pdo_Mysql
	 */
	public static function singleton($databaseName){
		//Check for $db initialized into array
		if(!is_array(self::$db)){
			self::$db = array();
		}
		//Default database connector:
		if(!$databaseName){
			$databaseName = self::DATABASE_NAME;
		}
		
		$config = new Zend_Config_Ini('etc/config.ini', $databaseName);
		
		//Check for existing connction for selected database
		if(!array_key_exists($databaseName, self::$db)){
			self::$db[$databaseName] = Zend_Db::factory($config->database);
		}
		//If we cannot create connection to database, throw an error
		if(!self::$db[$databaseName]){
			throw new Exception('Unable to connect on:'. $databaseName);
		}
		return self::$db[$databaseName];
	}
	
	/**
	 * return connexion information
	 *
	 * @return string
	 */
	public static function __toString(){
		if(!is_array(self::$db)){
			self::$db = array();
		}
		$_return = '';
		foreach (self::$db as $connexionName => $link) {
			$_return .= $connexionName.'=>'.$link->__toString();
		}
		return $_return;
	}
	
	
}
?>