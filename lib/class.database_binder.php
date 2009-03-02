<?php
/**
 * $LastChangedDate: 2009-02-10 15:49:57 +0100 (mar., 10 févr. 2009) $
 * $LastChangedRevision: 13 $
 * $LastChangedBy: cjanssens $
 * $Id: class.database_binder.phtml 13 2009-02-10 14:49:57Z cjanssens $
 */
  
/**
 * Database connector for database opl lib
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
	 * @param string $databaseName @see opl database object 
	 * @return object database
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