<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */

class phpGenObjectManager extends configObjectAbstract {
	
	private $object;
	private $name;
	
	/**
	 * set object to manipulate
	 *
	 * @param phpGenObject $object
	 * @return phpGenObjectManager
	 */
	public function setObject(phpGenObject $object){
		$this->object = $object;
		$this->name = $this->object->getName().'_manager';
		return $this;		
	}
	
	public function generate(){
		$this->_header();
		$this->_headerFunction();
		$this->_build();
		$this->_save();
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
		$this->_append('private static $'.$this->object->getName().'; //USED OBJECT');
		$this->_append();
	}
	
	private function _headerFunction(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->name.' builder.');
		$this->_append(' * Initialize internal database connector and work with '.$this->object->getName().' object.');
		$this->_append(' *');
		$this->_append(' * @param '.$this->object->getName().' $'.$this->object->getName().'');
		$this->_append(' */');
		$this->_append('public static function factory('.$this->object->getName().' $'.$this->object->getName().'=null){');
		$this->level = 2;
		$this->_append('if(!self::$db){');
		$this->level = 3;
		$this->_append('self::$db = '.self::DB_CALLER.';');
		$this->level = 2;
		$this->_append('}');
		$this->_append('if($'.$this->object->getName().'){');
		$this->level = 3;
		$this->_append('self::using($'.$this->object->getName().');');
		$this->level = 2;
		$this->_append('}');
		$this->level = 1;
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * Set '.$this->object->getName().' object to work.');
		$this->_append(' *');
		$this->_append(' * @param '.$this->object->getName().' $'.$this->object->getName().'');
		$this->_append(' * @return '.$this->object->getName().'');
		$this->_append(' */');
		$this->_append('static function using('.$this->object->getName().' $'.$this->object->getName().'){');
		$this->level = 2;
		$this->_append('if(self::$'.$this->object->getName().'){');
		$this->level = 3;
		$this->_append('self::$'.$this->object->getName().' = NULL;');
		$this->level = 2;
		$this->_append('}');
		$this->_append('self::$'.$this->object->getName().' = $'.$this->object->getName().';');
		$this->_append('return self::$'.$this->object->getName().';');
		$this->level = 1;
		$this->_append('}');
		
	}
	
	private function _build(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->object->getName().' builder.');
		$this->_append(' * ');
		$this->_append(' * @return '.$this->object->getName());
		$this->_append(' */');
		$this->_append('public static function build('.$this->object->getName().' $'.$this->object->getName().'=null){');
		$this->level = 2;
		$this->_append('if(!$'.$this->object->getName().'){');
		$this->level = 3;
		$this->_append('$'.$this->object->getName().' = self::$'.$this->object->getName().';');
		$this->level = 2;
		$this->_append('}');
		$primary = $this->object->getPrimaryKeyName();
		$_tmp = $this->object->getProperty($primary);
		$fieldName = $_tmp["fieldName"];
		$fields = $this->object->getProperties();
		$getPrimaryKeyFunction = phpClassGenerator::formatPropertyName('get_'.$primary); 
		$this->_append('self::$db->query("SELECT * FROM '.$this->object->getTableName().' WHERE '.$fieldName.' = ".$'.$this->object->getName().'->'.$getPrimaryKeyFunction.'());');
		$this->_append('$results = self::$db->fetchArray();');

		$i=0;
		foreach ($fields as $propertyName => $params){					
			if(!$params['primary']){
				$this->_append(($i === 0 ? '$'.$this->object->getName() : '').'->'.phpClassGenerator::formatPropertyName('set_'.$propertyName).'($results["'.$params['fieldName'].'"])');
				$i++;
			}
		}
		$this->_append('->check();');
		$this->_append('return $'.$this->object->getName().';');
		$this->level = 1;
		$this->_append('}');
	}
	
	private function _save(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->object->getName().' saver.');
		$this->_append(' * ');
		$this->_append(' * @return '.$this->object->getName());
		$this->_append(' */');
		$this->_append('public static function save('.$this->object->getName().' $'.$this->object->getName().'=null){');
		$this->level = 2;
		$this->_append('if(!$'.$this->object->getName().'){');
		$this->level = 3;
		$this->_append('$'.$this->object->getName().' = self::$'.$this->object->getName().';');
		$this->level = 2;
		$this->_append('}');
		$fields = $this->object->getProperties();
		$primary = $this->object->getPrimaryKeyName();
		$getPrimaryKeyFunction = phpClassGenerator::formatPropertyName('get_'.$primary); 
		$setPrimaryKeyFunction = phpClassGenerator::formatPropertyName('set_'.$primary); 
		#CASE UPDATE ROW
		$this->_append('if($'.$this->object->getName().'->'.$getPrimaryKeyFunction.'()){');
		$this->level = 3;
		$this->_append('$update = "UPDATE '.$this->object->getTableName().' SET ";');
		$this->_append('$_update = array();');
		$i=0;
		foreach ($fields as $propertyName => $params){					
			if(!$params['primary']){
				$this->level = 3;
				$this->_append('if($'.$this->object->getName().'->getModifier(\''.$propertyName.'\')){');
				$this->level = 4;
				$this->_append('$_update[] = "'.$params['fieldName'].' = \'".$'.$this->object->getName().'->'. phpClassGenerator::formatPropertyName('get_'.$propertyName).'()."\'";');
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
		$this->_append('$update .= " WHERE '.$primaryKeyField.' = ".$'.$this->object->getName().'->'.$getPrimaryKeyFunction.'();');
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
				$listFieldsValue .= "'\".$".$this->object->getName()."->".phpClassGenerator::formatPropertyName('get_'.$propertyName)."().\"'";
				$i++;
			}
		}
		$this->_append('self::$db->query("INSERT INTO '.$this->object->getTableName().' (');
		$this->_append($listFields);
		$this->_append(') VALUES (');
		$this->_append($listFieldsValue);
		$this->_append(')");');
		$this->_append('$'.$this->object->getName().'->'.$setPrimaryKeyFunction.'(self::$db->getInsertId());');
		$this->level = 2;
		$this->_append('}');		
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