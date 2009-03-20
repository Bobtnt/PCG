<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */
class phpGenObject extends configObjectAbstract {
	
	private $name;
	private $tableName;
	private $properties = array();
	
	
	public function addProperty($property, $defaultValue=null){
		$this->properties[$property] = $defaultValue;
		return $this;
	}
	
	public function getProperty($propertyName){
		return $this->properties[$propertyName];
	}
	
	public function getProperties(){
		return $this->properties;
	}
	
	public function getPrimaryKeyName(){
		foreach ($this->properties as $name => $params) {
			if($params['primary']){
				$primary = $name;
				break;
			}
		}
		return $primary;
	}
	
	public function getCode(){
		return $this->code;
	}	
	
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
		return $this;
	}
	public function getTableName(){
		return $this->tableName;
	}
	public function setTableName($name){
		$this->tableName = $name;
		return $this;
	}
	
	
	public function generate(){
		$this->code = '';
		$this->_header();
		$this->_properties();
		$this->_constructor();
		$this->_call();
		$this->_save();
		$this->_modifier();
		$this->_getterAndSetter();
		$this->_footer();
		return $this->code;
	}
	
	
	private function _header(){
		$this->_append('<?php');
		$this->_append('/**');
		$this->_append(' * '.$this->name.' object');
		$this->_append(' **/');
		$this->_append('class '.$this->name.' {');
		$this->_append();
	}
	
	private function _properties(){
		$i=0;
		$modified = 'private $modified = array(';
		foreach ($this->properties as $name => $params) {
			$code = 'private $'.$name;
			if($params['default']){
				if($params['type'] == 'int'){
					$code .= ' = '.$params['default'].';';
				}
				elseif($params['type'] == 'date' || $params['type'] == 'timestamp'){
					$code .= ';';
				}
				else{
					$code .= ' = \''.$params['default'].'\';';
				}
			}
			else{
				$code .= ';';
			}
			if($params['primary']){
				$code .= ' //this is the primary key';
			}
			$this->_append($code);
			$modified .= ($i === 0 ? '' : ',')."'".$name."' => false";
			$i++;
		}
		$this->_append($modified.');');
		$this->_append();
		$this->_append('private $context; //context object, generaly collection object');
		$this->_append();
	}
	
	private function _getterAndSetter(){
		$this->_append('/**');
		$this->_append(' * Check function. Unused for the moment.');
		$this->_append(' */');
		$this->_append('public function check(){');
		$this->_append('return true;');
		$this->_append('}');
		$this->_append();
		$this->_append('/******************************');
		$this->_append(' * GETTER AND SETTER');
		$this->_append(' *******************************/');
		$this->_append();
		foreach ($this->properties as $name => $params) {
			#GETTER
			$this->_append('/**');
			$this->_append(' * @return '.$this->name.'->'.$name);
			$this->_append(' **/');
			$this->_append('public function '.phpClassGenerator::formatPropertyName('get_'.$name).'(){');
			if($params['type'] != 'int' &&  $params['type'] != 'timestamp' && $params['type'] != 'date'){
				$this->_append('return stripslashes($this->'.$name.');');
			}
			else{
				$this->_append('return '.(($params['type'] == 'int') ? '(int)' : '').'$this->'.$name.';');
			}
			$this->_append('}');
			#SETTER
			$this->_append('/**');
			$this->_append(' * @param $'.$name);
			$this->_append(' * @return '.$this->name);
			$this->_append(' **/');
			$this->_append('public function '.phpClassGenerator::formatPropertyName('set_'.$name).'($'.$name.'){');
			if($params['type'] != 'int' &&  $params['type'] != 'timestamp' && $params['type'] != 'date'){
				$this->_append('$this->'.$name.' = addslashes($'.$name.');');
			}
			else{
				$this->_append('$this->'.$name.' = '.(($params['type'] == 'int') ? '(int)' : '').' $'.$name.';');
			}
			$this->_append('$this->setModifier(\''.$name.'\');');
			$this->_append('return $this;');
			$this->_append('}');
			
		}
	}
	
	private function _constructor(){
		#get primary key
		$primary = $this->getPrimaryKeyName();
		$inputVar = '$'.$this->name.'_'.$primary.'';
		$this->_append('/**');
		$this->_append(' * '.$this->name.' object constructor');
		$this->_append(' * Build '.$this->name.' with '.$inputVar.' or create new '.$this->name.' without '.$inputVar);
		$this->_append(' *');
		$this->_append(' * @param [integer] '.$inputVar);
		$this->_append(' * @param [object] $context');
		$this->_append(' */');
		$this->_append('function __construct('.$inputVar.'=null, $context=null){');
		$this->_append($this->name.'_manager::factory($this);');
		$this->_append('if('.$inputVar.'){');
		$this->_append('$this->'.phpClassGenerator::formatPropertyName('set_'.$primary).'('.$inputVar.');');
		$this->_append($this->name.'_manager::build($this);');
		$this->_append('}');
		$this->_append('if($context){');
		$this->_append('$this->context = $context;');
		$this->_append('}');
		$this->_append('}');
	}
	
	private function _modifier(){
		$this->_append('/**');
		$this->_append(' * Reset all modifier');
		$this->_append(' * You may not use this function');
		$this->_append(' **/');
		$this->_append('private function resetModifier(){');
		foreach ($this->properties as $name => $params) {
			$params; //Just for ZCA
			$this->_append('$this->modified[\''.$name.'\'] = false;');
		}
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * set modifier');
		$this->_append(' *');
		$this->_append(' * @return '.$this->getName());
		$this->_append(' **/');
		$this->_append('private function setModifier($propertyName, $modified=true){');
		$this->_append('$this->modified[$propertyName] = $modified;');
		$this->_append('return $this;');
		$this->_append('}');
		$this->_append('/**');
		$this->_append(' * get modifier');
		$this->_append(' *');
		$this->_append(' * @return bool');
		$this->_append(' **/');
		$this->_append('public function getModifier($propertyName){');
		$this->_append('return $this->modified[$propertyName];');
		$this->_append('}');
	}
	
	private function _save(){
		$this->_append('/**');
		$this->_append(' * Save '.$this->name);
		$this->_append(' *');
		$this->_append(' * @return '.$this->name);
		$this->_append(' **/');
		$this->_append('public function save(){');
		$this->_append($this->name.'_manager::using($this);');
		$this->_append($this->name.'_manager::save();');
		$this->_append('$this->resetModifier();');
		$this->_append('return $this;');
		$this->_append('}');
	}
	
	private function _call(){
		$this->_append('/**');
		$this->_append(' * magic call method');
		$this->_append(' * call context method if reconized');
		$this->_append(' *');
		$this->_append(' * @param string $method');
		$this->_append(' * @param array $arguments');
		$this->_append(' */');
		$this->_append('public function __call($method, $arguments){');
		$this->_append('$arguments; //Just for code analyzer');
		$this->_append('switch($method){');
		$this->_append('case "remove":');
		$this->_append('if(is_object($this->context)){');
		$this->_append('$this->context->remove($this);');
		$this->_append('return $this->context;');
		$this->_append('}');
		$this->_append('break;');
		$this->_append('}');
		$this->_append('throw new Exception("try to access to an unknown method");');
		$this->_append('}');
	}
	
	private function _footer(){
		$this->_append('/**');
		$this->_append(' * setter for $context');
		$this->_append(' */');
		$this->_append('public function setContextObject($context){');
		$this->_append('$this->context = $context;');
		$this->_append('}');		
		$this->_append('}');
		$this->_append('?>');
	}
	
	private function _relationshipProperties(){
		#1,n or 1,1 relation
		$this->_append('/**');
		$this->_append(' * relationship with category');
		$this->_append(' * @var category');
		$this->_append(' */');
		$this->_append('private $_category; ');
	}
	
	private function _relationshipGetter(){
		#1,n or 1,1 relation
		$this->_append('/**');
		$this->_append('* magic get method');
		$this->_append('* used to construct relationship between objects');
		$this->_append('*');
		$this->_append('* @param string $name');
		$this->_append('*/');
		$this->_append('public function __get($name){');
		$this->_append('if($name == \'category\'){');
		$this->_append('if(!$this->_category){');
		$this->_append('#n, m mode');
		$this->_append('//$name == \'category_collection\'');
		$this->_append('//$this->_category = new category_collection();');
		$this->_append('//$this->_category->select(\'select category_id from category where\')');
		$this->_append('#1, n mode');
		$this->_append('$categoryId = $this->getCategoryId();');
		$this->_append('$this->_category = new category($categoryId);');
		$this->_append('}');
		$this->_append('elseif(is_object($this->_category)){');
		$this->_append('if($this->_category->getId() != $this->getCategoryId()){');
		$this->_append('$categoryId = $this->getCategoryId();');
		$this->_append('$this->_category = new category($categoryId);');
		$this->_append('}');
		$this->_append('}');
		$this->_append('return $this->_category;');
		$this->_append('}');
		$this->_append('throw new Exception("try to access to an unknown property");');
		$this->_append('}');
	}
	
	
}

?>