<?php

class phpGenObject extends configObjectAbstract {
	
	private $name;
	private $properties = array();
	
	
	public function addProperty($property, $defaultValue=null){
		$this->properties[$property] = $defaultValue;
		return $this;
	}
	
	public function generate(){
		$this->code = '';
		$this->_header();
		$this->_properties();
		$this->_constructor();
		$this->_save();
		$this->_resetModifier();
		$this->_getterAndSetter();
		$this->_footer();
		return $this->code;
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
	
	private function _header(){
		$this->_append('/**');
		$this->_append(' * '.$this->name.' object');
		$this->_append(' **/');
		$this->_append('class '.$this->name.' {');
		$this->_append();
	}
	
	private function _properties(){
		$this->level = 1; //set Indentation level to 1
		foreach ($this->properties as $name => $params) {
			$code = 'private '.$name;
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
			$this->_append('private '.$name.'Modified = false;');
		}
		$this->_append();
	}
	
	private function _getterAndSetter(){
		$this->level = 1; //set Indentation level to 1
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
			$this->level = 2;
			if($params['type'] != 'int' &&  $params['type'] != 'timestamp' && $params['type'] != 'date'){
				$this->_append('return stripslashes($this->'.$name.');');
			}
			else{
				$this->_append('return $this->'.$name.';');
			}
			$this->level = 1;
			$this->_append('}');
			#SETTER
			$this->_append('/**');
			$this->_append(' * @param $'.$name);
			$this->_append(' * @return '.$this->name);
			$this->_append(' **/');
			$this->_append('public function '.phpClassGenerator::formatPropertyName('set_'.$name).'($'.$name.'){');
			$this->level = 2;
			if($params['type'] != 'int' &&  $params['type'] != 'timestamp' && $params['type'] != 'date'){
				$this->_append('$this->'.$name.' = addslashes($'.$name.');');
			}
			else{
				$this->_append('$this->'.$name.' = $'.$name.';');
			}
			$this->_append('$this->'.$name.'Modified = true;');
			$this->_append('return $this;');
			$this->level = 1;
			$this->_append('}');
			
		}
	}
	
	private function _constructor(){
		#get primary key
		foreach ($this->properties as $name => $params) {
			if($params['primary']){
				$primary = $name;
				break;
			}
		}
		$inputVar = '$'.$this->name.'_'.$primary.'';
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * '.$this->name.' object constructor');
		$this->_append(' * Build '.$this->name.' with '.$inputVar.' or create new '.$this->name.' without '.$inputVar);
		$this->_append(' *');
		$this->_append(' * @param integer '.$inputVar);
		$this->_append(' */');
		$this->_append('function __construct('.$inputVar.'=null){');
		$this->level = 2;
		$this->_append($this->name.'_manager::factory($this);');
		$this->_append('if('.$inputVar.'){');
		$this->level = 3;
		$this->_append('$this->'.phpClassGenerator::formatPropertyName('set_'.$primary).'('.$inputVar.');');
		$this->_append($this->name.'_manager::build($this);');
		$this->level = 2;
		$this->_append('}');
		$this->level = 1;
		$this->_append('}');
	}
	
	private function _resetModifier(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * Reset all modifier');
		$this->_append(' * You may not use this function');
		$this->_append(' **/');
		$this->_append('private function resetModifier(){');
		foreach ($this->properties as $name => $params) {
			$params; //Just for ZCA
			$this->level = 2;
			$this->_append('$this->'.$name.'Modified = false;');
		}
		$this->_append('}');
	}
	
	private function _save(){
		$this->level = 1;
		$this->_append('/**');
		$this->_append(' * Save '.$this->name);
		$this->_append(' *');
		$this->_append(' * @return '.$this->name);
		$this->_append(' **/');
		$this->_append('public function save(){');
		$this->level = 2;
		$this->_append($this->name.'_manager::using($this);');
		$this->_append($this->name.'_manager::save();');
		$this->_append('$this->resetModifier();');
		$this->_append('return $this;');
		$this->level = 1;
		$this->_append('}');
	}
	
	private function _footer(){
		$this->level = 0;
		$this->_append('}');
	}
	
	
}

?>