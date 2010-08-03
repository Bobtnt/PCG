<?php


class database_optimizer {

	static public $objects = array();


	static public function register($object){
		if(method_exists($object, 'getPCGPrimaryKeyProperties')){
			$className = get_class($object);
			$uid = $className;
			$props = $object->getPCGPrimaryKeyProperties();
			foreach ($props as $tmp){
				$getter = $tmp[0];
				$field 	= $tmp[1];
				$prop 	= $tmp[2];
				$uid .= $object->$getter();
			}
			self::$objects[$uid] = $object;
			return true;
		}
		return false;
	}

	static public function unregister($object){
		if(method_exists($object, 'getPCGPrimaryKeyProperties')){
			$className = get_class($object);
			$uid = $className;
			$props = $object->getPCGPrimaryKeyProperties();
			foreach ($props as $tmp){
				$getter = $tmp[0];
				$field 	= $tmp[1];
				$prop 	= $tmp[2];
				$uid .= $object->$getter();
			}
			if(array_key_exists($uid, self::$objects)){
				unset(self::$objects[$uid]);
				return true;
			}
		}
		return false;
	}

	static public function get($object){
		if(method_exists($object, 'getPCGPrimaryKeyProperties')){
			$className = get_class($object);
			$uid = $className;
			$props = $object->getPCGPrimaryKeyProperties();
			foreach ($props as $tmp){
				$getter = $tmp[0];
				$field 	= $tmp[1];
				$prop 	= $tmp[2];
				$uid .= $object->$getter();
			}

			if(array_key_exists($uid, self::$objects)){
				return self::$objects[$uid];
			}
		}
		return false;
	}

}