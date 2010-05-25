<?php
/**
 * This file is a part of php class generator (PCG) apps.
 *
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html
 * author: Cyril Janssens
 * $Id: class.phpGenObjectCollection.php 72 2009-08-17 15:53:05Z cyriljanssens $
 */

class phpGenObjectCollection extends configObjectAbstract {

	private $object; 		//base object
	private $name;	 		//manager name (this)
	private $baseName; 		//base object name
	private $tableName; 	//table object name
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
		$this->name = $this->object->getName().'_collection';
		$this->baseName = $this->object->getName();
		$this->primary = $this->object->getPrimaryKeyName();
		$this->primaryGetter = phpClassGenerator::formatPropertyName('get_'.$this->primary);
		$this->primarySetter = phpClassGenerator::formatPropertyName('set_'.$this->primary);
		$this->tableName = $this->object->getTableName();
		return $this;
	}

	public function getName(){
		return $this->name;
	}

	public function generate(){
		$this->_header();
		$this->_call();
		$this->_magicSetGet();
		$this->_addRemoveCount();
		$this->_getSelectFilter();
		$this->_save();
		$this->_privates();
		$this->_footer();
		return $this->code;
	}

	private function _header(){
		$this->_append('<?php');
		$this->_append('/**');
		$this->_append(' * collection object for '.$this->baseName);
		$this->_append(' *');
		$this->_append(' * <pattern syntax>');
		$this->_append(' * $pattern = "proprety operator value"');
		$this->_append(' * operator can be [== | != | < | <= | > | >= | LIKE | NOT LIKE]');
		$this->_append(' * Examples: ');
		$this->_append(' * $pattern = "subject LIKE some words";');
		$this->_append(' * $pattern = "description NOT LIKE some words";');
		$this->_append(' * $pattern = "id LIKE 123" ;');
		$this->_append(' */');
		$this->_append('abstract class '.$this->name.'_base '.(phpClassGenerator::$userZendLoader ? "extends ".$this->name."_custom" : "").' implements IteratorAggregate {');
		$this->_append('');
		$this->_append('const MODE_APPEND = 1;');
		$this->_append('const MODE_REPLACE = 2;	');
		$this->_append('	');
		$this->_append('private $items = array();');
		$this->_append('private $fields = array();');
		$this->_append('');
	}

	private function _call(){
		$this->_append('public function __call($name, $arguments){');
		$this->_append('switch ($name) {');
		$this->_append('case \'fill\':');
		$this->_append('$query = (array_key_exists(0, $arguments) ? $arguments[0] : NULL);');
		$this->_append('$mode = (array_key_exists(1, $arguments) ? $arguments[1] : 1);');
		$this->_append('return $this->select($query, $mode);');
		$this->_append('break;');
		$this->_append('}');
		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("try to access to an unknown method");');
		}
		else{
			$this->_append('return $this;');
		}
		$this->_append('}');
	}

	private function _magicSetGet(){
		$this->_append('public function __get($name){');
		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("try to access to an unknown method");');
		}
		else{
			$this->_append('return $this;');
		}
		$this->_append('}');
		$this->_append('public function __set($name, $param){');
		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("try to access to an unknown method");');
		}
		else{
			$this->_append('return $this;');
		}
		$this->_append('}');
	}


	private function _addRemoveCount(){
		$this->_append('/**');
		$this->_append(' * add '.$this->baseName.' to collection');
		$this->_append(' *');
		$this->_append(' * @param '.$this->baseName.' $'.$this->baseName);
		$this->_append(' * @param [integer] $mode @see '.$this->name.'::select');
		$this->_append(' * @return '.$this->name);
		$this->_append(' */	');
		$this->_append('public function add('.$this->baseName.' $'.$this->baseName.', $mode=1){');
		$this->_append('$existFlag = false;');
		$this->_append('//set context object');
		$this->_append('$'.$this->baseName.'->setContextObject($this);');
		$innerVar = phpClassGenerator::formatPropertyName('$inner_'.$this->baseName);
		$this->_append('foreach ($this as $key => '.$innerVar.'){');
		$this->_append('//check for existing '.$this->baseName);
		$this->_append('if(!is_null('.$innerVar.'->'.$this->primaryGetter.'()) && '.$innerVar.'->'.$this->primaryGetter.'() == $'.$this->baseName.'->'.$this->primaryGetter.'()){');
		$this->_append('$existFlag = true;');
		$this->_append('$existKey = $key;');
		$this->_append('break;');
		$this->_append('}');
		$this->_append('}');
		$this->_append('if($existFlag && $mode == self::MODE_APPEND){');
		$this->_append('// do nothing');
		$this->_append('}');
		$this->_append('else if($existFlag && $mode == self::MODE_REPLACE){');
		$this->_append('//replace');
		$this->_append('$this->_set($existKey, $'.$this->baseName.');');
		$this->_append('}');
		$this->_append('else if(!$existFlag && ($mode == self::MODE_APPEND || $mode == self::MODE_REPLACE)){');
		$this->_append('//simply add');
		$this->_append('$this->_add($'.$this->baseName.');');
		$this->_append('}');
		$this->_append('else{');
		$this->_append('//what ??!');
		$this->_append('return false;');
		$this->_append('}');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append('* count collection elements');
		$this->_append('*');
		$this->_append('* @param [mixed] $count by ref');
		$this->_append('* @return '.$this->name.'|integer');
		$this->_append('*/');
		$this->_append('public function count(&$count=null){');
		$this->_append('if(!is_null($count)){');
		$this->_append('$count = count($this->items);');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('else{');
		$this->_append('return count($this->items);	');
		$this->_append('}');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * remove current item from collection');
		$this->_append(' *');
		$this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
		$this->_append(' * @return boolean');
		$this->_append(' */');
		$this->_append('public function remove('.$this->baseName.' $'.$this->baseName.'){');
		$this->_append('$existFlag = false;');
		$this->_append('foreach ($this as $key => '.$innerVar.'){');
		$this->_append('//check for existing bugs');
		$this->_append('if('.$innerVar.'->'.$this->primaryGetter.'() == $'.$this->baseName.'->'.$this->primaryGetter.'()){');
		$this->_append('$existFlag = true;');
		$this->_append('$existKey = $key;');
		$this->_append('break;');
		$this->_append('}');
		$this->_append('}');
		$this->_append('if($existFlag){');
		$this->_append('unset($this->items[$existKey]);');
		$this->_append('return true;');
		$this->_append('}');
		$this->_append('else{');
		$this->_append('return false;');
		$this->_append('}');
		$this->_append('}');
	}

	private function _getSelectFilter(){
		$this->_append('/**');
		$this->_append(' * get first object match $pattern selector from collection');
		$this->_append(' *');
		$this->_append(' * Pattern is a selector from object properties:');
		$this->_append(' * $pattern = \'id == 88\'');
		$this->_append(' * ');
		$this->_append(' * @param string $pattern');
		$this->_append(' * @return '.$this->baseName.' ');
		$this->_append(' */');
		$this->_append('public function get($pattern){');
		$this->_append('$pattern = trim($pattern);');
		$this->_append('if(empty($pattern)){');
		$this->_append('return false;');
		$this->_append('}');
		$this->_append('$keyList = $this->_filter($pattern);');
		$this->_append('return (count($keyList)) != 0 ?  $this->item($keyList[0]) : false;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * call collection item\'s by $index');
		$this->_append(' *');
		$this->_append(' * @param integer $index');
		$this->_append(' * @return '.$this->baseName.'');
		$this->_append(' */');
		$this->_append('public function item($index){');
		$this->_append('return $this->items[$index];');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * Fill collection with sql query within $mode method');
		$this->_append(' * ');
		$this->_append(' * '.$this->name.'::MODE_APPEND');
		$this->_append(' * This mode add bugs in collection without modify existing bugs if already in colection');
		$this->_append(' * ');
		$this->_append(' * '.$this->name.'::MODE_REPLACE');
		$this->_append(' * This mode add '.$this->baseName.' in collection replace existing '.$this->baseName.' if already in colection');
		$this->_append(' *');
		$this->_append(' * @param string $query');
		$this->_append(' * @param [integer] $mode '.$this->name.'::MODE_APPEND [default], '.$this->name.'::MODE_REPLACE');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('public function select($query, $mode=1){');
		$this->_append('$fields = array();');
		$this->_append('foreach ($this->fields as $field){');
		$this->_append('$fields[] = key($field);');
		$this->_append('}');
		$this->_append('$ids = '.$this->baseName.'_manager::select($query, $fields);');
		$this->_append('$nb = count($ids);');
		$this->_append('for($a = 0 ; $a < $nb; $a++){');
		$this->_append('$'.$this->baseName.' = new '.$this->baseName.'($ids[$a][\'FORPCGUID\']);');
		$this->_append('foreach ($this->fields as $key => $array){');
		$this->_append('foreach($array as $field => $property){');
		$this->_append('$'.$this->baseName.'->$property = $ids[$a][$field];');
		$this->_append('}');
		$this->_append('}');
		$this->_append('$this->add($'.$this->baseName.', $mode);');
		$this->_append('}');
		$this->_append('$this->fields = array();');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * remove items in collection matching $pattern');
		$this->_append(' *');
		$this->_append(' * @param string $pattern');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('public function filter($pattern){');
		$this->_append('$pattern = trim($pattern);');
		$this->_append('if(empty($pattern)){');
		$this->_append('return false;');
		$this->_append('}');
		$this->_append('$keyList = $this->_filter($pattern);');
		$this->_append('if(is_array($keyList)){');
		$this->_append('foreach ($keyList as $key){');
		$this->_append('unset($this->items[$key]);');
		$this->_append('}');
		$this->_append('}');
		$this->_append('return $this;');
		$this->_append('}');
	}

	private function _save(){
		$this->_append('/**');
		$this->_append(' * save all elements in collection');
		$this->_append(' *');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('public function save(){');
		$this->_append('foreach($this as $item){');
		$this->_append('$item->save();');
		$this->_append('}');
		$this->_append('}');
	}

	private function _privates(){
		$this->_append('/**');
		$this->_append(' * Define key pair field/property to add in objects for the next select query.');
		$this->_append(' * Field and prperty must be exists.');
		$this->_append(' *');
		$this->_append(' * @param string $field');
		$this->_append(' * @param string $property');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('public function defineField($field, $property){');
		$this->_append('$this->fields[] = array($field => $property);');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * Set '.$this->baseName.' for local collection');
		$this->_append(' *');
		$this->_append(' * @access private');
		$this->_append(' * @param integer $key');
		$this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('private function _set($key, '.$this->baseName.' $'.$this->baseName.'){');
		$this->_append('$this->items[$key] = $'.$this->baseName.';');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * add '.$this->baseName.' for local collection ');
		$this->_append(' *');
		$this->_append(' * @access private');
		$this->_append(' * @param bugs $'.$this->baseName.'');
		$this->_append(' * @return '.$this->name.'');
		$this->_append(' */');
		$this->_append('private function _add('.$this->baseName.' $'.$this->baseName.') {');
		$this->_append('$this->items[] = $'.$this->baseName.';');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * return list of key matching $pattern');
		$this->_append(' * ');
		$this->_append(' * @access private');
		$this->_append(' * @see collection');
		$this->_append(' * @param string $pattern');
		$this->_append(' * @return array');
		$this->_append(' */');
		$this->_append('private function _filter($pattern){');
		$this->_append('//parse pattern and generate condition');
		$this->_append('$patternizerPattern = \'#(.+)(==|!=|<=|<|>=|>|(?<=[^T] )LIKE | NOT LIKE )(.+)#\';');
		$this->_append('$matches = array();');
		$this->_append('preg_match($patternizerPattern, $pattern, $matches);');
		$this->_append('$localTestedProperty = trim($matches[1]);');
		$this->_append('$operator = trim($matches[2]);');
		$this->_append('$testedValue = trim($matches[3]);');
		$this->_append('$testedValue = (is_numeric($testedValue) ? intval($testedValue) : $testedValue);');
		$this->_append('$results = array();');
		$this->_append('$getterName = \'get\'.ucfirst($localTestedProperty);');
		$this->_append('foreach ($this as $key => $item){');
		$this->_append('switch($operator){');
		$this->_append('case "==":');
		$this->_append('if($item->$getterName() == $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case "!=":');
		$this->_append('if($item->$getterName() != $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case "<=":');
		$this->_append('if($item->$getterName() <= $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case "<":');
		$this->_append('if($item->$getterName() < $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case ">=":');
		$this->_append('if($item->$getterName() >= $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case ">":');
		$this->_append('if($item->$getterName() > $testedValue){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case "LIKE":');
		$this->_append('if(strpos($item->$getterName(),$testedValue) !== false){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('case "NOT LIKE":');
		$this->_append('if(strpos($item->$getterName(),$testedValue) === false){');
		$this->_append('$results[] = $key;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('default:');
		$this->_append('//what ??!');
		$this->_append('return false;');
		$this->_append('}			');
		$this->_append('}');
		$this->_append('return $results;');
		$this->_append('}');

	}


	private function _footer(){
		$this->_append('/**');
		$this->_append(' * called by iterator (foreach syntax)');
		$this->_append(' *');
		$this->_append(' * @return localIterator');
		$this->_append(' */');
		$this->_append('public function getIterator() {');
		$this->_append('return new localIterator($this->items);');
		$this->_append('}');
		$this->_append('}');
		$this->_append('?>');
	}
}
?>