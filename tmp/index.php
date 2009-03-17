<?php


require_once 'class.bugs.php';
require_once 'class.bugs_manager.php';
require_once 'class.bugs_collection.php';
require_once 'class.localIterator.php';

ini_set('include_path', '.;..;C:\opt\lib');
//ini_set('include_path', '.;..;C:\Program Files\Zend\Zend Studio for Eclipse - 6.1.0\plugins\org.zend.php.framework.resource_6.1.1.v20081231-1100\resources\ZendFramework_1.7\FrameworkLib');
function __autoload($className){
	if(is_file('../lib/class.'.$className.'.php')){
		require_once '../lib/class.'.$className.'.php';
	}	
	elseif($className == 'Zend_Loader'){
		require_once 'Zend/Loader.php';
	}
	else{
		Zend_Loader::loadClass($className);
	}
}

//$bug = new bugs();
//$bug->setSubject("test")->setDescription("desc test ".rand(200,2000))->save();
//
//$id = $bug->getId();
//Zend_Debug::dump($id);
//unset($bug);
//
//
//$bug2 = new bugs($id);
//$bug2->setSubject(genRandSubject())->save();
//
//unset($bug2);


$bugs_collection = new bugs_collection();
$bugs_collection->select("select * from bugs where bugs_id < 90 and bugs_id > 87");

foreach ($bugs_collection as $bug){
	Zend_Debug::dump($bug);
	break;
}

function genRandSubject(){
	$words = array("Flux ",
				"provenant ",
				"Technique ",
				"Mettre à jour ",
				"Modification ",
				"Création " ,
				"rapport ",
				"Accès ",
				"statut ",
				"sites ",
				"entreprise ",
				"Migrer ",
				"nom de domaine ",
				"Gestion ",
				"table ",
				"bug ");
	$subjet = $words[rand(0,15)].$words[rand(0,15)].$words[rand(0,15)];
	return $subjet;	
}

?>