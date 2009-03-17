<?php
/**
 * bugs_manager manager object
 **/
class bugs_manager {

	private static $db; //DATABASE CONNECTOR
	private static $bugs; //USED OBJECT
	
	private static $context; //context of bugs object
	
	/**
	 * bugs_manager builder.
	 * Initialize internal database connector and work with bugs object.
	 *
	 * @param bugs $bugs
	 */
	public static function factory(bugs $bugs=null){
		if(!self::$db){
			self::$db = database_binder::factory();
		}
		if($bugs){
			self::using($bugs);
		}
	}
	/**
	 * Set bugs object to work.
	 *
	 * @param bugs $bugs
	 * @return bugs
	 */
	static function using(bugs $bugs){
		if(self::$bugs){
			self::$bugs = NULL;
		}
		self::$bugs = $bugs;
		return self::$bugs;
	}
	/**
	 * bugs builder.
	 * 
	 * @return bugs
	 */
	public static function build(bugs $bugs=null){
		self::factory();
		if(!$bugs){
			$bugs = self::$bugs;
		}
		$ressource = self::$db->query("SELECT * FROM bugs WHERE bugs_id = ".$bugs->getId());
		$results = $ressource->fetchAll();
		$results = $results[0];
		//$results = self::$db->fetchArray();
		$bugs->setSubject($results["bugs_subject"])
		->setDescription($results["bugs_description"])
		->setCreateDate($results["bugs_create_date"])
		->setUpdateDate($results["bugs_update_date"])
		->setPriority($results["bugs_priority"])
		->check();
		return $bugs;
	}
	/**
	 * bugs saver.
	 * 
	 * @return bugs
	 */
	public static function save(bugs $bugs=null){
		self::factory();
		if(!$bugs){
			$bugs = self::$bugs;
		}
		if($bugs->getId()){
			$update = "UPDATE bugs SET ";
			$_update = array();
			if($bugs->getModifier('subject')){
				$_update[] = "bugs_subject = '".$bugs->getSubject()."'";
			}
			if($bugs->getModifier('description')){
				$_update[] = "bugs_description = '".$bugs->getDescription()."'";
			}
			if($bugs->getModifier('createDate')){
				$_update[] = "bugs_create_date = '".$bugs->getCreateDate()."'";
			}
			if($bugs->getModifier('updateDate')){
				$_update[] = "bugs_update_date = '".$bugs->getUpdateDate()."'";
			}
			if($bugs->getModifier('priority')){
				$_update[] = "bugs_priority = '".$bugs->getPriority()."'";
			}
			if(count($_update) > 0){
				for($a=0; $a < count($_update);$a++){
					$update .= ($a === 0 ? "" : ",").$_update[$a];
				}
				$update .= " WHERE bugs_id = ".$bugs->getId();
				self::$db->query($update);
			}			
		}
		else{
			self::$db->query("INSERT INTO bugs (
			bugs_subject,bugs_description,bugs_create_date,bugs_update_date,bugs_priority
			) VALUES (
			'".$bugs->getSubject()."','".$bugs->getDescription()."','".$bugs->getCreateDate()."','".$bugs->getUpdateDate()."','".$bugs->getPriority()."'
			)");
			self::$bugs->setId(self::$db->lastInsertId());
		}
	}
	/**
	 * Select bugs_id $sql
	 * bugs_id field must be in selected fields
	 *
	 * @param string $sql
	 * @return array
	 */
	public static function select($sql){
		self::factory();
		$ressource = self::$db->query($sql);
		$bugsIds = array();
//		while($results = self::$db->fetchArray()){
//			$bugsIds[] = $results['bugs_id'];
//		}
		$bugsIds = $ressource->fetchAll();
		return $bugsIds;
	}
	
}
?>
