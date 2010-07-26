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
	public $primaryColumn;
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
		$this->_relationshipProperties();
		$this->_constructor();
		$this->_call();
		$this->_relationshipGetter();
		$this->_relationshipSetter();
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
		$this->_append('abstract class '.$this->name.'_base '.(phpClassGenerator::$userZendLoader ? 'extends '.$this->name.'_custom' : '').' {');
		$this->_append();
	}

	private function _properties(){
		$i=0;
		$PCGPrimaryKeyProperties = 'array(';
		$modified = 'protected $modified = array(';
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
				$PCGPrimaryKeyProperties .= 'array(\''. phpClassGenerator::formatPropertyName('get_'.$name).'\', \''.$params['fieldName'].'\', \''.$name.'\'),';//protected $PCGPrimaryKeyProperties = array(array('getId', 'cms_cache_id', 'id'),);
			}
			$this->_append($code);
			$modified .= ($i === 0 ? '' : ',')."'".$name."' => false";
			$i++;
		}
		$this->_append($modified.');');
		$this->_append();
		$this->_append('protected $context; //context object, generaly collection object');
		$this->_append('protected $PCGPrimaryKeyProperties = ' .$PCGPrimaryKeyProperties.'); //primary keys list');
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
		$this->_append('public function getPCGPrimaryKeyProperties(){');
		$this->_append('return $this->PCGPrimaryKeyProperties;');
		$this->_append('}');
		$this->_append();
		foreach ($this->properties as $name => $params) {
//			if($this->name == 'cms_gabarit'){
//				Zend_Debug::dump($params);
//			}
//			Zend_Debug::dump($params);
			$useStripSlashes = false;
			$defaultNull = false;
			if(is_null($params['default']) && $params['nullable']){
				$defaultNull = true;
			}
			if($params['type'] != 'int' && $params['type'] != 'tinyint' &&  $params['type'] != 'timestamp' && $params['type'] != 'date'){
				$useStripSlashes = true;
			}
			$getterName = phpClassGenerator::formatPropertyName('get_'.$name);
			$setterName = phpClassGenerator::formatPropertyName('set_'.$name);

			#GETTER
			$this->_append('/**');
			if($useStripSlashes){$this->_append(' * @param bool optional $sqlMode');}
			$this->_append(' * @return '.$this->name.'->'.$name);
			$this->_append(' **/');
			$this->_append('public function '.$getterName.'($sqlMode=false){');
			if($useStripSlashes){
				$this->_append('$_return = $sqlMode ? "\'".$this->'.$name.'."\'" : stripslashes($this->'.$name.');');
			}
			else{
				if(preg_match('/int/', $params['type']) && $defaultNull){
					$this->_append('$_return = is_null($this->'.$name.') ? null : (int)$this->'.$name.';');
				}
				elseif(preg_match('/int/', $params['type']) && !$defaultNull){
					$this->_append('$_return = (int)$this->'.$name.';');
				}
				else{
					$this->_append('$_return = $sqlMode ? "\'".$this->'.$name.'."\'" : $this->'.$name.';');
				}
			}
			if($defaultNull){
				$this->_append('if(is_null($this->'.$name.')){');
				$this->_append('$_return = $sqlMode ? \'NULL\' : null;');
				$this->_append('}');
			}
			$this->_append('return $_return;');
			$this->_append('}');

			#SETTER
			$this->_append('/**');
			$this->_append(' * @param $'.$name);
			$this->_append(' * @return '.$this->name);
			$this->_append(' **/');
			$this->_append('public function '.$setterName.'($'.$name.'){');
			if($useStripSlashes){
				if($defaultNull){
					$this->_append('$this->'.$name.' = is_null($'.$name.') ? null : addslashes($'.$name.');');
				}
				else{
					$this->_append('$this->'.$name.' = addslashes($'.$name.');');
				}
			}
			else{
				$this->_append('$this->'.$name.' = '.((preg_match('/int/', $params['type']) && !$defaultNull) ? '(int)' : '').' $'.$name.';');
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
		$this->_append('$this->resetModifier();');
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
		$this->_append('protected function resetModifier(){');
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
		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("try to access to an unknown method");');
		}
		else{
			$this->_append('return $this;');
		}
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
		$this->_append('');
	}


	private function _relationshipProperties(){
		$relation = phpClassGenerator::$relatedField;
		$nb = count($relation);
		for ($a = 0 ; $a < $nb ; $a++){
			$relationFound = false;
			# 1:N relation
			if(array_key_exists('relationType', phpClassGenerator::$relatedField[$a]) && phpClassGenerator::$relatedField[$a]['relationType'] == '1:n'){
				//check if the relation regards current object
				if($relation[$a]['object'] == $this->getName()){
					//search related object:
					//in relation we have src table, field and object
					//now we searching in all other object->table the primary field matching the src.table.field
					$objectList = phpClassGenerator::$objects;
					$nb2 = count($objectList);
					//Zend_Debug::Dump($relation[$a]);
					for ($b = 0 ; $b < $nb2 ; $b++){
						//we remowe the current object from search area because the self relationship are not yet supported
						if($objectList[$b]['object']->getName() != $this->getName()){
							foreach ($objectList[$b]['object']->properties as $propertyName => $infos){
								//if object->properties->infos->fieldname == relation->fieldname &&
								// object->properties->infos->fieldname->primary == true
								if($infos['fieldName'] == $relation[$a]['toField']  && $infos['primary']){
									//THIS FIELD MATCH !!!
//									Zend_Debug::Dump($infos);
//									Zend_Debug::Dump($objectList[$b]['object']->getName());
									$relatedObjectName = $objectList[$b]['object']->getName();
									phpClassGenerator::$relatedField[$a]['relatedObject'] = $relatedObjectName;
									phpClassGenerator::$relatedField[$a]['relatedPropertyName'] = $propertyName;
									phpClassGenerator::$relatedField[$a]['relationType'] = '1:n';
									$relationFound = true;
									break;
								}
							}
							if($relationFound){
								break;
							}
						}
						if($relationFound){
							break;
						}
					}
				}
				if($relationFound){
					$this->_append('/**');
					$this->_append(' * relationship with '.$relatedObjectName);
					$this->_append(' * @var '.$relatedObjectName);
					$this->_append(' */');
					$this->_append('private $_'.$relatedObjectName.';');
				}
			}
			# 1:1 RELATION
			if(array_key_exists('relationType', phpClassGenerator::$relatedField[$a]) && phpClassGenerator::$relatedField[$a]['relationType'] == '1:1'){
				//in this mode the're one direct column linked and all other int column are object of linked table (srctable)_has_(linkedtable)
				$matches = array();
				preg_match("#(.+)_has_(.+)#",$relation[$a]['fromTable'], $matches);
				$srcTable = $matches[1];
				$linkedTable = $matches[2];
				//search which objects match with table name
				foreach (phpClassGenerator::$objects as $objects) {
					if($objects['object']->getTableName() == $srcTable){
						$srcObject =  $objects['object'];
					}
					if($objects['object']->getTableName() == $linkedTable){
						$linkedObject =  $objects['object'];
					}
				}
				//check if we are in scr object else do nothing
				if($srcObject->getName() == $this->getName()){
					//now match if the field is the foreign key
					if(preg_match('#^'.$srcTable.'#',$relation[$a]['toField'])){
						$linkedObjectName = $srcObject->getName();
						phpClassGenerator::$relatedField[$a]['srcObject'] = $srcObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedObject'] = $linkedObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedPropertyName'] = null;
						phpClassGenerator::$relatedField[$a]['relationType'] = '1:1';
					}
					//else is the linked object
					else{
						$linkedObjectName = $linkedObject->getName();
						$propertyName = '_'.phpClassGenerator::formatPropertyName($relation[$a]['toField']);
						$this->_append('/**');
						$this->_append(' * relationship with '.$linkedObjectName);
						$this->_append(' * @var '.$linkedObjectName);
						$this->_append(' */');
						$this->_append('private $'.$propertyName.';');
						phpClassGenerator::$relatedField[$a]['srcObject'] = $srcObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedObject'] = $linkedObjectName;
						phpClassGenerator::$relatedField[$a]['relatedPropertyName'] = $propertyName;
						phpClassGenerator::$relatedField[$a]['relationType'] = '1:1';
					}
				}
			}
			# N:M RELATION
			if(array_key_exists('relationType', phpClassGenerator::$relatedField[$a]) && phpClassGenerator::$relatedField[$a]['relationType'] == 'n:m'){
				//in this mode all column are foreign. we must build a collection of linked object
				$matches = array();
				preg_match("#(.+)_has_(.+)#",$relation[$a]['fromTable'], $matches);
				$srcTable = $matches[1];
				$linkedTable = $matches[2];

				//search which objects match with table name
				$srcObject = phpClassGenerator::getObjectByTableName($srcTable);
				$linkedObject = phpClassGenerator::getObjectByTableName($linkedTable);

				//check if we are in SRC object else do nothing
				if($srcObject->getName() == $this->getName()){
					if(preg_match('#^'.$srcTable.'#', phpClassGenerator::$relatedField[$a]['toField'])){

						$linkedObjectName = $linkedObject->getName();
						$propertyName = '_'.$linkedObjectName.'_collection';

						phpClassGenerator::$relatedField[$a]['srcObject'] = $srcObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedObject'] = $linkedObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedPropertyName'] = $propertyName;
						phpClassGenerator::$relatedField[$a]['relationType'] = 'n:m';

						$this->_append('/**');
						$this->_append(' * relationship with '.$linkedObjectName);
						$this->_append(' * @var '.$linkedObjectName.'_collection');
						$this->_append(' */');
						$this->_append('private $'.$propertyName.';');

//						Zend_Debug::Dump($a);
//						Zend_Debug::Dump(phpClassGenerator::$relatedField[$a]);
					}
				}
				if($linkedObject->getName() == $this->getName()){
					if(preg_match('#^'.$linkedTable.'#', phpClassGenerator::$relatedField[$a]['toField'])){
						$linkedObjectName = $srcObject->getName();
						$propertyName = '_'.$linkedObjectName.'_collection';

						phpClassGenerator::$relatedField[$a]['srcObject'] = $linkedObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedObject'] = $srcObject->getName();
						phpClassGenerator::$relatedField[$a]['relatedPropertyName'] = $propertyName;
						phpClassGenerator::$relatedField[$a]['relationType'] = 'n:m';

						$this->_append('/**');
						$this->_append(' * relationship with '.$linkedObjectName);
						$this->_append(' * @var '.$linkedObjectName.'_collection');
						$this->_append(' */');
						$this->_append('private $'.$propertyName.';');

//						Zend_Debug::Dump($a);
//						Zend_Debug::Dump(phpClassGenerator::$relatedField[$a]);
					}
				}
			}
		}
	}

	private function _relationshipGetter(){

		$this->_append('/**');
		$this->_append('* magic get method');
		$this->_append('* used to construct relationship between objects');
		$this->_append('*');
		$this->_append('* @param string $name');
		$this->_append('*/');
		$this->_append('public function __get($name){');

		$relation = phpClassGenerator::$relatedField;
		$nb = count($relation);
		for ($a = 0 ; $a < $nb ; $a++){
			if($relation[$a]['relationType'] == '1:n'){
				if($relation[$a]['object'] == $this->getName()){
					$relatedObjectName = $relation[$a]['relatedObject'];
					$primaryFieldName = $relation[$a]['toField'];
					$primaryVarName = phpClassGenerator::formatPropertyName($primaryFieldName);
					$primaryGetterName = phpClassGenerator::formatPropertyName('get_'.$primaryFieldName);
					$relatedPropertyGetterName = phpClassGenerator::formatPropertyName('get_'.$relation[$a]['relatedPropertyName']);
					$this->_append('if($name == \''.$relatedObjectName.'\'){');
					$this->_append('if(!$this->_'.$relatedObjectName.'){');
					$this->_append('#1, n mode');
					$this->_append('$'.$primaryVarName.' = $this->'.$primaryGetterName.'();');
					$this->_append('$this->_'.$relatedObjectName.' = new '.$relatedObjectName.'($'.$primaryVarName.');');
					$this->_append('}');
					$this->_append('elseif(is_object($this->_'.$relatedObjectName.')){');
					$this->_append('if($this->_'.$relatedObjectName.'->'.$relatedPropertyGetterName.'() != $this->'.$primaryGetterName.'()){');
					$this->_append('$'.$primaryVarName.' = $this->'.$primaryGetterName.'();');
					$this->_append('$this->_'.$relatedObjectName.' = new '.$relatedObjectName.'($'.$primaryVarName.');');
					$this->_append('}');
					$this->_append('}');
					$this->_append('return $this->_'.$relatedObjectName.';');
					$this->_append('}');
				}
			}
			elseif ($relation[$a]['relationType'] == '1:1'){
				if($relation[$a]['srcObject'] == $this->getName()){
					//check for real property
					if($relation[$a]['relatedPropertyName']){

						$relatedObjectName = $relation[$a]['relatedObject'];
						$propertyName = $relation[$a]['relatedPropertyName']; 					// _property
						$calledPropertyName = substr($relation[$a]['relatedPropertyName'],1); 	// property
						$getterCalledPropertyName = phpClassGenerator::formatPropertyName('get_'.$calledPropertyName);
						$relatedObject = phpClassGenerator::getObjectByName($relatedObjectName);
						$relatedPropertyGetterName = phpClassGenerator::formatPropertyName('get_'.$relatedObject->getPrimaryKeyName());

						$this->_append('if($name == \''.$calledPropertyName.'\'){');
						$this->_append('if(!$this->'.$propertyName.'){');
						$this->_append('#1:1 mode');
						$this->_append('$'.$calledPropertyName.' = $this->'.$getterCalledPropertyName.'();');
						$this->_append('$this->'.$propertyName.' = new '.$relatedObjectName.'($'.$calledPropertyName.');');
						$this->_append('}');
						$this->_append('elseif(is_object($this->'.$propertyName.')){');
						$this->_append('if($this->'.$propertyName.'->'.$relatedPropertyGetterName.'() != $this->'.$getterCalledPropertyName.'()){');
						$this->_append('$'.$calledPropertyName.' = $this->'.$getterCalledPropertyName.'();');
						$this->_append('$this->'.$propertyName.' = new '.$relatedObjectName.'($'.$calledPropertyName.');');
						$this->_append('}');
						$this->_append('}');
						$this->_append('return $this->'.$propertyName.';');
						$this->_append('}');

					}
				}
			}
			elseif ($relation[$a]['relationType'] == 'n:m'){
				if($relation[$a]['srcObject'] == $this->getName()){
					//check for real property
					if($relation[$a]['relatedPropertyName']){

						$relatedObjectName = $relation[$a]['relatedObject'];
						$propertyName = $relation[$a]['relatedPropertyName']; 					// _property
						$calledPropertyName = substr($relation[$a]['relatedPropertyName'],1); 	// property
						$relatedObject = phpClassGenerator::getObjectByName($relatedObjectName);
						$relatedPropertyGetterName = phpClassGenerator::formatPropertyName('get_'.$relatedObject->getPrimaryKeyName());

						$this->_append('if($name == \''.$calledPropertyName.'\' || $name == \''.$relatedObjectName.'s\'){');
						$this->_append('if(!$this->'.$propertyName.'){');
						$this->_append('#n:m mode');
						$this->_append('$'.$relatedObject->getPrimaryKeyName().' = $this->'.$relatedPropertyGetterName.'();');
						$this->_append('$this->'.$propertyName.' = new '.$calledPropertyName.'();');
						$this->_append('if($'.$relatedObject->getPrimaryKeyName().'){');
						$this->_append('$this->'.$propertyName.'->select("SELECT * FROM '.$relation[$a]['fromTable'].' WHERE '.$relation[$a]['srcObject'].'_'.$relatedObject->getPrimaryKeyName().' = ".$'.$relatedObject->getPrimaryKeyName().');');
						$this->_append('}');
						$this->_append('}');
						$this->_append('return $this->'.$propertyName.';');
						$this->_append('}');

					}
				}
			}
		}
		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("Try to access to an unknown property");');
		}else{
			$this->_append('return $this;');
		}
		$this->_append('}');
	}

	private function _relationshipSetter(){

		$this->_append('/**');
		$this->_append('* magic set method');
		$this->_append('* used to construct relationship between objects');
		$this->_append('*');
		$this->_append('* @param string $name');
		$this->_append('* @param mixed $value');
		$this->_append('* @return '.$this->getName());
		$this->_append('*/');
		$this->_append('public function __set($name, $value){');
		$relation = phpClassGenerator::$relatedField;
		$nb = count($relation);
		for ($a = 0 ; $a < $nb ; $a++){
			if(phpClassGenerator::$relatedField[$a]['relationType'] == 'n:m'){
				if($relation[$a]['srcObject'] == $this->getName()){
//					Zend_Debug::Dump(phpClassGenerator::$relatedField[$a]);
					$propertyName = substr(phpClassGenerator::$relatedField[$a]["relatedPropertyName"], 1);
					$shortPropertyName = phpClassGenerator::$relatedField[$a]["relatedObject"].'s';
					$this->_append('if($name == \''.$propertyName.'\' || $name == \''.$shortPropertyName.'\'){');
					$this->_append('if(is_object($value)){');
					$this->_append('$this->'.phpClassGenerator::$relatedField[$a]["relatedPropertyName"].' = $value;');
					$this->_append('}');
					$this->_append('return $this;');
					$this->_append('}');
				}
			}
//			if(phpClassGenerator::$relatedField[$a]['relationType'] == '1:1'){
//					if($relation[$a]['srcObject'] == $this->getName()){
//	//					Zend_Debug::Dump(phpClassGenerator::$relatedField[$a]);
//						$propertyName = substr(phpClassGenerator::$relatedField[$a]["relatedPropertyName"], 1);
//						$relatedObject = phpClassGenerator::$relatedField[$a]["relatedObject"];
//						$this->_append('if($name == \''.$relatedObject.'\'){');
//						$this->_append('if(is_object($value)){');
//						$this->_append('$'.$relatedObject.' = $value;');
//
//						$this->_append('$this->'.phpClassGenerator::$relatedField[$a]["relatedPropertyName"].' = $value;');
//						$this->_append('}');
//						$this->_append('return $this;');
//						$this->_append('}');
//
//
//						if($name == 'commentcategory'){
//							/**
//							 * @var commentcategory
//							 */
//							$commentcategory = $value;
//							$this->commentcategoryId = $commentcategory->getId();
//							$this->_commentcategory = $commentcategory;
//							return $this;
//						}
//					}
//				}
		}

		if(phpClassGenerator::$throwExceptionOnUnkownMagicCall){
			$this->_append('throw new Exception("Try to access to an unknown property");');
		}else{
			$this->_append('return $this;');
		}
		$this->_append('}');
	}
}

?>