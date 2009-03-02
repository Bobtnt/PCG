<?php

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
		return $this->code;
	}
	
	private function _header(){
		$this->level = 0;
		$this->_append('/**');
		$this->_append(' * '.$this->name.' manager object');
		$this->_append(' **/');
		$this->_append('class '.$this->name.' {');
		$this->_append();
		$this->level = 1;
		$this->_append('private $db; //DATABASE CONNECTOR');
		$this->_append('private $'.$this->object->getName().'; //USED OBJECT');
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
		$this->_append('	if(self::$'.$this->object->getName().'){');
		$this->_append('		self::$'.$this->object->getName().' = NULL;');
		$this->_append('	}');
		$this->_append('	self::$'.$this->object->getName().' = $'.$this->object->getName().';');
		$this->_append('	return self::$'.$this->object->getName().';');
		$this->_append('}');
	}
	
	
	
	
}

?>