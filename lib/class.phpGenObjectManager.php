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

  public function getName(){
    return $this->name;
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
    $this->_append('<?php');
    $this->_append('/**');
    $this->_append(' * '.$this->name.' manager object');
    $this->_append(' **/');
    $this->_append('abstract class '.$this->name.'_base '.(phpClassGenerator::$userZendLoader ? 'extends '.$this->name.'_custom' : '').' {');
    $this->_append();
    $this->_append('protected static $db; //DATABASE CONNECTOR');
    $this->_append('protected static $'.$this->baseName.'; //USED OBJECT');
    $this->_append('protected static $context; //context of object');
    $this->_append();
  }

  private function _headerFunction(){
    $this->_append('/**');
    $this->_append(' * '.$this->name.' builder.');
    $this->_append(' * Initialize internal database connector and work with '.$this->baseName.' object.');
    $this->_append(' *');
    $this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
    $this->_append(' */');
    $this->_append('public static function factory('.$this->baseName.' $'.$this->baseName.'=null){');
    $this->_append('if(!self::$db){');
    $this->_append('self::$db = '.self::DB_CALLER.';');
    $this->_append('}');
    $this->_append('if($'.$this->baseName.'){');
    $this->_append($this->baseName.'_manager::using($'.$this->baseName.');');
    $this->_append('}');
    $this->_append('}');
    $this->_append('/**');
    $this->_append(' * Set '.$this->baseName.' object to work.');
    $this->_append(' *');
    $this->_append(' * @param '.$this->baseName.' $'.$this->baseName.'');
    $this->_append(' * @return '.$this->baseName.'');
    $this->_append(' */');
    $this->_append('static function using('.$this->baseName.' $'.$this->baseName.'){');
    $this->_append('if(self::$'.$this->baseName.'){');
    $this->_append('self::$'.$this->baseName.' = NULL;');
    $this->_append('}');
    $this->_append('self::$'.$this->baseName.' = $'.$this->baseName.';');
    $this->_append('return self::$'.$this->baseName.';');
    $this->_append('}');

  }

  private function _build(){
    $this->_append('/**');
    $this->_append(' * '.$this->baseName.' builder.');
    $this->_append(' * ');
    $this->_append(' * @return '.$this->baseName);
    $this->_append(' */');
    $this->_append('public static function build('.$this->baseName.' $'.$this->baseName.'=null){');
    $this->_append($this->baseName.'_manager::factory();');
    $this->_append('if(!$'.$this->baseName.'){');
    $this->_append('$'.$this->baseName.' = self::$'.$this->baseName.';');
    $this->_append('}');
    $primary = $this->primary;
    $_tmp = $this->object->getProperty($primary);
    $fieldName = $_tmp["fieldName"];
    $fields = $this->object->getProperties();
    $this->_append('$ressource = self::$db->query("SELECT * FROM '.$this->tableName.' ');

    //add relation in build fonction
    $relation = phpClassGenerator::$relatedField;
    $nb = count($relation);
    for ($a = 0 ; $a < $nb ; $a++){
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
            $linkedObject = $objects['object'];
          }
        }
        //check if we are in scr object else do nothing
        if($srcObject->getName() == $this->object->getName()){
          //now match if the field is the foreign key
          if(preg_match('#^'.$srcTable.'#',$relation[$a]['toField'])){
            $this->_append('NATURAL LEFT OUTER JOIN '.$relation[$a]['fromTable'].' ');
          }
        }
      }
    }
    $this->_append('WHERE '.$fieldName.' = ".$'.$this->baseName.'->'.$this->primaryGetter.'());');
    //$this->_append('$results = self::$db->fetchArray();');
    $this->_append('$results = $ressource->fetchAll();');
    $this->_append('if(count($results) <= 0){');
    $this->_append('$'.$this->baseName.'->'.$this->primarySetter.'(NULL);');
    $this->_append('return $'.$this->baseName.';');
    $this->_append('}');
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
    $this->_append('}');
  }

  private function _save(){
    $this->_append('/**');
    $this->_append(' * '.$this->baseName.' saver.');
    $this->_append(' * ');
    $this->_append(' * @return '.$this->baseName);
    $this->_append(' */');
    $this->_append('public static function save('.$this->baseName.' $'.$this->baseName.'=null){');
    $this->_append($this->baseName.'_manager::factory();');
    $this->_append('if(!$'.$this->baseName.'){');
    $this->_append('$'.$this->baseName.' = self::$'.$this->baseName.';');
    $this->_append('}');
    $fields = $this->object->getProperties();
    //$primary = $this->primary;
    $getPrimaryKeyFunction = $this->primaryGetter;
    $setPrimaryKeyFunction = $this->primarySetter;
    #CASE UPDATE ROW
    $this->_append('if($'.$this->baseName.'->'.$getPrimaryKeyFunction.'()){');
    //add relation in save fonction
    $relation = phpClassGenerator::$relatedField;

    $nb = count($relation);
    $related11tables = array();
    $relatedNMtables = array();
    for ($a = 0 ; $a < $nb ; $a++){
      # 1:1 relation
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
        if($srcObject->getName() == $this->object->getName()){
          //now match if the field is the foreign key
          //if(preg_match('#^'.$srcTable.'#',$relation[$a]['toField'])){
          // add table name in key, for unique naming

          $related11tables[$relation[$a]['fromTable']][] = $relation[$a];
          //}
        }
      }
      # N:M RELATION
      if(array_key_exists('relationType', phpClassGenerator::$relatedField[$a]) && phpClassGenerator::$relatedField[$a]['relationType'] == 'n:m'){

        //			if($this->name == 'cms_gabarit_manager'){
        //				echo '<span style="color:blue">';
        //				Zend_Debug::dump(phpClassGenerator::$relatedField[$a]);
        //				echo '</span>';
        //			}
        //in this mode we must empty the lines in the relation table and refill with the new collection value
        $matches = array();
        preg_match("#(.+)_has_(.+)#",$relation[$a]['fromTable'], $matches);
        $srcTable = $matches[1];
        $linkedTable = $matches[2];

        //search which objects match with table name
        $srcObject = phpClassGenerator::getObjectByTableName($srcTable);
        $linkedObject = phpClassGenerator::getObjectByTableName($linkedTable);
        //check if we are in scr object else do nothing

        if($srcObject->getName() == $this->object->getName() || $linkedObject->getName() == $this->object->getName() ){
          //					if($this->name == 'cms_gabarit_manager'){
          //						echo '<span style="color:green">';
          //						Zend_Debug::dump($srcObject->getName().' '.$this->object->getName());
          //						echo '</span>';
          //					}
          # CASE REVERT N:M RELATION
          if($linkedObject->getName() == $this->object->getName()){
            $tempSrc = $srcObject;
            $tempLnk = $linkedObject;
            $linkedObject = $tempSrc;
            $srcObject = $tempLnk;
          }

          //unifying request
          //if(array_key_exists("relatedObject", phpClassGenerator::$relatedField[$a]) && !array_key_exists($srcObject->getName().' '.phpClassGenerator::$relatedField[$a]["relatedObject"], $relatedNMtables)){
          if(array_key_exists("relatedObject", phpClassGenerator::$relatedField[$a]) && !array_key_exists(phpClassGenerator::$relatedField[$a]["object"], $relatedNMtables)){
//            if($srcObject->getName() == 'ebtv_video' || $linkedObject->getName() == 'ebtv_video' ){
//              Zend_Debug::dump($srcObject->getName().' '.$linkedObject->getName());
//              Zend_Debug::dump(phpClassGenerator::$relatedField[$a]);
//            }

            //						if($this->name == 'cms_gabarit_manager'){
            //							echo '<span style="color:red">';
            //							Zend_Debug::dump($relatedNMtables);
            //							echo '</span>';
            //						}
            $primarygetterName = phpClassGenerator::formatPropertyName('get_'.$srcObject->getPrimaryKeyName());
            $primaryLinkedGetterName = phpClassGenerator::formatPropertyName('get_'.$linkedObject->getPrimaryKeyName());
            $SQLemptyingTable = 'DELETE FROM '.$relation[$a]['fromTable'].' WHERE '.$srcObject->getTableName().'_'.$srcObject->getPrimaryKeyName().' = \'.$'.$this->baseName.'->'.$primarygetterName.'(true)';
            $NMinsert[] = $this->_append('$collection = self::$'.$this->baseName.'->'.$linkedObject->getName().'_collection;');
            $this->_append('self::$db->query(\''.$SQLemptyingTable.');');
            $relatedNMtables[phpClassGenerator::$relatedField[$a]["object"]] = true;

            $NMinsert[] = $this->_append('$i=0;');
            $NMinsert[] = $this->_append('$insert = \'INSERT INTO '.$relation[$a]['fromTable'].' ('.$srcObject->getTableName().'_'.$srcObject->getPrimaryKeyName().', '.$linkedObject->getTableName().'_'.$linkedObject->getPrimaryKeyName().') VALUES \';');

            $NMinsert[] = $this->_append('foreach ($collection as $'.$linkedObject->getName().'){');
            $NMinsert[] = $this->_append('$insert .= $i !== 0 ? "," : "";');
            $NMinsert[] = $this->_append('$insert .= \'(\'.$'.$this->baseName.'->'.$primarygetterName.'(true).\',\'.$'.$linkedObject->getName().'->'.$primaryLinkedGetterName.'(true).\')\';');
            $NMinsert[] = $this->_append('$i++;');
            $NMinsert[] = $this->_append('}');
            $NMinsert[] = $this->_append('if($i!==0){');
            $NMinsert[] = $this->_append('self::$db->query($insert);');
            $NMinsert[] = $this->_append('}');
          }
        }
      }
    }

    $this->_append('$update = "UPDATE '.$this->tableName.' ');
    foreach ($related11tables as $tableName => $foreignFields) {
      $this->_append(','.$tableName);;
    }

    $this->_append(' SET ";');
    $this->_append('$_update = array();');
    $i=0;
    foreach ($fields as $propertyName => $params){
      if(!$params['primary']){
        $this->_append('if($'.$this->baseName.'->getModifier(\''.$propertyName.'\')){');
        $this->_append('$_update[] = "'.$params['fieldName'].' = ".$'.$this->baseName.'->'. phpClassGenerator::formatPropertyName('get_'.$propertyName).'(true);');
        $this->_append('}');
        $i++;
      }
      else{
        $primaryKeyField = $params['fieldName'];
      }
    }
    $this->_append('if(count($_update) > 0){');
    $this->_append('for($a=0; $a < count($_update);$a++){');
    $this->_append('$update .= ($a === 0 ? "" : ",").$_update[$a];');
    $this->_append('}');
    $this->_append('$update .= " WHERE '.$this->tableName.'.'.$primaryKeyField.' = ".$'.$this->baseName.'->'.$getPrimaryKeyFunction.'(true);');

    foreach ($related11tables as $tableName => $foreignFields) {
      $this->_append('$update .= " AND '.$tableName.'.'.$primaryKeyField.' = ".$'.$this->baseName.'->'.$getPrimaryKeyFunction.'(true);');
    }

    $this->_append('self::$db->query($update);');
    $this->_append('}');
    $this->_append('}');
    #CASE NEW ROW
    $listFields = '';
    $listFieldsValue = '';
    $this->_append('else{');
    $i=0;
    foreach ($fields as $propertyName => $params){
      if(!$params['primary'] && !$params['foreignField']){
        $listFields .= ($i === 0 ? '' : ',').$params['fieldName'];
        $listFieldsValue .= ($i === 0 ? '' : ',');
        $listFieldsValue .= "\".$".$this->baseName."->".phpClassGenerator::formatPropertyName('get_'.$propertyName)."(true).\"";
        $i++;
      }
    }
    $this->_append('self::$db->query("INSERT INTO '.$this->tableName.' (');
    $this->_append($listFields);
    $this->_append(') VALUES (');
    $this->_append($listFieldsValue);
    $this->_append(')");');

    $this->_append('self::$'.$this->baseName.'->'.$setPrimaryKeyFunction.'(self::$db->lastInsertId());');
    //now INSERT for 1:1 relationship
    $i=0;
    foreach ($related11tables as $tableName => $foreignFields) {
      if($i === 0){
        $listForeignFields = '';
        $listForeignFieldsValue = '';
      }
      $this->_append('self::$db->query("INSERT INTO '.$tableName.' (');
      $nb = count($foreignFields);
      for ($a = 0 ; $a < $nb ; $a++) {
        $listForeignFields .= ($a === 0 ? '' : ',').$foreignFields[$a]['toField'];
        if($foreignFields[$a]['relatedPropertyName']){
          $listForeignFieldsValue .= ($a === 0 ? '' : ',')."\".$".$this->baseName."->".phpClassGenerator::formatPropertyName('get_'.$foreignFields[$a]['toField'])."(true).\"";
        }
        else{
          $listForeignFieldsValue .= ($a === 0 ? '' : ',')."\".$".$this->baseName."->".$getPrimaryKeyFunction."(true).\"";
        }
      }
      $this->_append($listForeignFields.') VALUES ('.$listForeignFieldsValue.')');
      $this->_append('");');
      $i++;
      if(count($tableName) == $i){
        $i=0;
      }
    }
    //N:M insert
    $nb = count($NMinsert);
    for ($a = 0 ; $a < $nb ; $a++) {
      $this->_append($NMinsert[$a]);
    }


    $this->_append('}');
    $this->_append('}');
  }

  private function _select(){

    $_tmp = $this->object->getProperty($this->primary);
    $fieldName = $_tmp["fieldName"];
    $this->_append('/**');
    $this->_append(' * Select '.$fieldName.' $sql');
    $this->_append(' * '.$fieldName.' field must be in selected fields');
    $this->_append(' *');
    $this->_append(' * @param string $sql');
    $this->_append(' * @param array $moreField');
    $this->_append(' * @return array');
    $this->_append(' */');
    $this->_append('public static function select($sql, $moreField=false){');
    $this->_append($this->baseName.'_manager::factory();');
    $this->_append('$ressource = self::$db->query($sql);');
    $this->_append('$primarys = array();');
    $this->_append('$_tmp = array();');
    $this->_append('$_tmp = $ressource->fetchAll();');
    $this->_append('for ($a = 0 ; $a < count($_tmp) ; $a++) {');
 	$this->_append('$primarys[$a][\'FORPCGUID\'] = $_tmp[$a][\''.$fieldName.'\'];');
	$this->_append('if(is_array($moreField)){');
	$this->_append('foreach ($moreField as $field){');
	$this->_append('$primarys[$a][$field] = $_tmp[$a][$field];');
	$this->_append('}');
	$this->_append('}');
    $this->_append('}');
    $this->_append('return $primarys;');
    $this->_append('}');
  }

  private function _footer(){
    $this->_append('}');
    $this->_append('?>');
  }

}

?>