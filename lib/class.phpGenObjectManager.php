<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */

class phpGenObjectManager extends configObjectAbstract {
	
	private $object; 		//base object
	private $name;	 		//manager name (this)
	private $baseName; 		//base object name
	private $tableName; 		//table object name
	private $primary;		//primary key name
	private $primaryGetter; //getter method for primary value
	private $primarySetter; //setter method for primary value
	
	public function __construct($object=null){
		if(is_object($object)){
			$this->setObject($object);
		}
	}
	
	
	/**
	 * set object to manipulate
	 *
	 * @param phpGenObject $object
	 * @return phpGenObjectManager
	 */
	public function setObject(phpGenObject $object){
		$this->object = $object;
		$this->name = $this->object->getName().'_manager';
		$this->baseName = $this->object->getName();
		$this->primary = $this->object->getPrimaryKeyName();
		$this->primaryGetter = phpClassGenerator::formatPropertyName('get_'.$this->primary);
		$this->primarySetter = phpClassGenerator::formatPropertyName('set_'.$this->primary);
		$this->tableName = $this->object->getTableName();
		return $this;
	}
	
	public function generate(){
		$this->_header();
		$this->_headerFunction();
		$this->_build();
		$this->_save();
		$this->_select();
		$this->_footer();
		return $this->code;
	}
	
	private function _header(){
		$this->level = 0;
		$this->_append('<?php');
		$this->_append('/**');
		$this->_append(' * '.$this->name.' manager object');
		$this->_append(' **/');
		$this->_append('class '.$this->name.' {');
		$this->_append();
		$this->level = 1;
		$this->_append('private static $db; //DATABASE CONNECTOR');
		$this->_append('private static $'.$this->baseName.'; //USED OBJECT');
		$this->_append('private static $context; //context of bugs object');
		$this->_append();
	}
	
	private function _headerFunction(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->name.' builder.');
		$this->_append(' * Initialize internal database connector and work with '.$this->baseName.' object.');
		$this->_append(' *');
		$this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
		$this->_append(' */');
		$this->_append('public static function factory('.$this->baseName.' $'.$this->baseName.'=null){');
		$this->level = 2;
		$this->_append('if(!self::$db){');
		$this->level = 3;
		$this->_append('self::$db = '.self::DB_CALLER.';');
		$this->level = 2;
		$this->_append('}');
		$this->_append('if($'.$this->baseName.'){');
		$this->level = 3;
		$this->_append('self::using($'.$this->baseName.');');
		$this->level = 2;
		$this->_append('}');
		$this->level = 1;
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * Set '.$this->baseName.' object to work.');
		$this->_append(' *');
		$this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
		$this->_append(' * @return '.$this->baseName.'');
		$this->_append(' */');
		$this->_append('static function using('.$this->baseName.' $'.$this->baseName.'){');
		$this->level = 2;
		$this->_append('if(self::$'.$this->baseName.'){');
		$this->level = 3;
		$this->_append('self::$'.$this->baseName.' = NULL;');
		$this->level = 2;
		$this->_append('}');
		$this->_append('self::$'.$this->baseName.' = $'.$this->baseName.';');
		$this->_append('return self::$'.$this->baseName.';');
		$this->level = 1;
		$this->_append('}');
		
	}
	
	private function _build(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->baseName.' builder.');
		$this->_append(' * ');
		$this->_append(' * @return '.$this->baseName);
		$this->_append(' */');
		$this->_append('public static function build('.$this->baseName.' $'.$this->baseName.'=null){');
		$this->level = 2;
		$this->_append('self::factory();');
		$this->_append('if(!$'.$this->baseName.'){');
		$this->level = 3;
		$this->_append('$'.$this->baseName.' = self::$'.$this->baseName.';');
		$this->level = 2;
		$this->_append('}');
		$primary = $this->primary;
		$_tmp = $this->object->getProperty($primary);
		$fieldName = $_tmp["fieldName"];
		$fields = $this->object->getProperties();
		$this->_append('$ressource = self::$db->query("SELECT * FROM '.$this->tableName.' WHERE '.$fieldName.' = ".$'.$this->baseName.'->'.$this->primaryGetter.'());');
		//$this->_append('$results = self::$db->fetchArray();');
		$this->_append('$results = $ressource->fetchAll();');
		$this->_append('$results = $results[0];');
		$i=0;
		foreach ($fields as $propertyName => $params){					
			if(!$params['primary']){
				$this->_append(($i === 0 ? '$'.$this->baseName : '').'->'.phpClassGenerator::formatPropertyName('set_'.$propertyName).'($results["'.$params['fieldName'].'"])');
				$i++;
			}
		}
		$this->_append('->check();');
		$this->_append('return $'.$this->baseName.';');
		$this->level = 1;
		$this->_append('}');
	}
	
	private function _save(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->baseName.' saver.');
		$this->_append(' * ');
		$this->_append(' * @return '.$this->baseName);
		$this->_append(' */');
		$this->_append('public static function save('.$this->baseName.' $'.$this->baseName.'=null){');
		$this->level = 2;
		$this->_append('self::factory();');
		$this->_append('if(!$'.$this->baseName.'){');
		$this->level = 3;
		$this->_append('$'.$this->baseName.' = self::$'.$this->baseName.';');
		$this->level = 2;
		$this->_append('}');
		$fields = $this->object->getProperties();
		$primary = $this->primary;
		$getPrimaryKeyFunction = $this->primaryGetter;
		$setPrimaryKeyFunction = $this->primarySetter; 
		#CASE UPDATE ROW
		$this->_append('if($'.$this->baseName.'->'.$getPrimaryKeyFunction.'()){');
		$this->level = 3;
		$this->_append('$update = "UPDATE '.$this->tableName.' SET ";');
		$this->_append('$_update = array();');
		$i=0;
		foreach ($fields as $propertyName => $params){					
			if(!$params['primary']){
				$this->level = 3;
				$this->_append('if($'.$this->baseName.'->getModifier(\''.$propertyName.'\')){');
				$this->level = 4;
				$this->_append('$_update[] = "'.$params['fieldName'].' = \'".$'.$this->baseName.'->'. phpClassGenerator::formatPropertyName('get_'.$propertyName).'()."\'";');
				$this->level = 3;
				$this->_append('}');
				$i++;	
			}else{
				$primaryKeyField = $params['fieldName'];
			}
		}
		$this->_append('if(count($_update) > 0){');
		$this->level = 4;
		$this->_append('for($a=0; $a < count($_update);$a++){');
		$this->level = 5;
		$this->_append('$update .= ($a === 0 ? "" : ",").$_update[$a];');
		$this->level = 4;
		$this->_append('}');
		$this->_append('$update .= " WHERE '.$primaryKeyField.' = ".$'.$this->baseName.'->'.$getPrimaryKeyFunction.'();');
		$this->_append('self::$db->query($update);');
		$this->level = 3;
		$this->_append('}');
		$this->level = 2;
		$this->_append('}');
		#CASE NEW ROW
		$listFields = '';
		$listFieldsValue = '';
		$this->_append('else{');
		$this->level = 3;
		$i=0;
		foreach ($fields as $propertyName => $params){					
			if(!$params['primary']){
				$listFields .= ($i === 0 ? '' : ',').$params['fieldName'];
				$listFieldsValue .= ($i === 0 ? '' : ',');
				$listFieldsValue .= "'\".$".$this->baseName."->".phpClassGenerator::formatPropertyName('get_'.$propertyName)."().\"'";
				$i++;
			}
		}
		$this->_append('self::$db->query("INSERT INTO '.$this->tableName.' (');
		$this->_append($listFields);
		$this->_append(') VALUES (');
		$this->_append($listFieldsValue);
		$this->_append(')");');
		$this->_append('self::$'.$this->baseName.'->'.$setPrimaryKeyFunction.'(self::$db->lastInsertId());');
		$this->level = 2;
		$this->_append('}');		
		$this->level = 1;
		$this->_append('}');
	}
	
	private function _select(){
		
		$_tmp = $this->object->getProperty($this->primary);
		$fieldName = $_tmp["fieldName"];
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * Select '.$fieldName.' $sql');
		$this->_append(' * '.$fieldName.' field must be in selected fields');
		$this->_append(' *');
		$this->_append(' * @param string $sql');
		$this->_append(' * @return array');
		$this->_append(' */');
		$this->_append('public static function select($sql){');
		$this->level = 2;
		$this->_append('self::factory();');
		$this->_append('$ressource = self::$db->query($sql);');
		$this->_append('$primarys = array();');
		$this->_append('$_tmp = array();');
		$this->_append('$_tmp = $ressource->fetchAll();');
		$this->_append('for ($a = 0 ; $a < count($_tmp) ; $a++) {');
		$this->level = 3;
		$this->_append('$primarys[] = $_tmp[$a][\''.$fieldName.'\'];');
		$this->level = 2;
		$this->_append('}');
		$this->_append('return $primarys;');
		$this->level = 1;
		$this->_append('}');
	}
	
	
	
	
	
	private function _footer(){
		$this->level = 0;
		$this->_append('}');
		$this->_append('?>');
	}
	
}

?>