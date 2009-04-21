<?php
$smarty = render::getInstance();

if($_POST['Validate']){
	//gen object
	phpClassGenerator::$userZendLoader = ($_POST['useZendLoader'] ? true : false);
	phpClassGenerator::factory();
	phpClassGenerator::listTable();
	phpClassGenerator::makeAllObjects();
	phpClassGenerator::makeAll();
	
	//make report
	$nb = count(phpClassGenerator::$objects);
	$objectsList = array();	
	for ($a = 0 ; $a < $nb ; $a++) {		
		if(phpClassGenerator::$objects[$a]['make']){
			$currentObject = phpClassGenerator::$objects[$a]['object'];
			$objectsList[] = array('name' => $currentObject->getName(), 'properties' => array() );
			$key = count($objectsList) - 1;
			$properties = $currentObject->getProperties();			
			foreach( $properties as $propertyName => $propertyInfos){
				$objectsList[$key]['properties'][] = $propertyName;
			}			
		}
	}
	$smarty->assign('objectsList', $objectsList);	
	$smarty->assign('message', 'These objects have been created in the '.configObjectAbstract::OUTPUT_FOLDER.' folder.');
}
else{
	//page directly called, send an error message
	$smarty->assign('message', 'This page must be called by index. Please go back and click on generate button.');	
}
?>