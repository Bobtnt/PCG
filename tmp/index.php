<?php


require_once 'class.bugs.php';
require_once 'class.bugs_manager.php';
require_once 'class.bugs_collection.php';
require_once 'class.localIterator.php';

//ini_set('include_path', '.;..;C:\opt\lib');
ini_set('include_path', '.;..;C:\Program Files\Zend\Zend Studio for Eclipse - 6.1.0\plugins\org.zend.php.framework.resource_6.1.1.v20081231-1100\resources\ZendFramework_1.7\FrameworkLib');
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

//$bugs_collection = new bugs_collection();
//$bugs_collection->select("select * from bugs where bugs_id < 90 and bugs_id > 0");
//
//$bugs[] = $bugs_collection->get('subject NOT LIKE Création ');
//$bugs[] = $bugs_collection->get('subject LIKE Création ');
//$bugs[] = $bugs_collection->get('id == 88 ');
//$bugs[] = $bugs_collection->get('id != 88 ');
//$bugs[] = $bugs_collection->get('id < 88 ');
//$bugs[] = $bugs_collection->get('id > 88 ');
//$bugs[] = $bugs_collection->get('id <= 88 ');
//$bugs[] = $bugs_collection->get('id >= 88 ');
//$nb = count($bugs);
//for ($a = 0 ; $a < $nb ; $a++) {
//	if(is_object($bugs[$a])){
//		Zend_Debug::dump($bugs[$a]->getId());
//	}else{
//		Zend_Debug::dump(false);
//	}
//}
//foreach ($bugs_collection as $bug){
//	Zend_Debug::dump($bug);
//}

$bugs_collection = new bugs_collection();
$bugs_collection->
select("select * from bugs where bugs_id >= 5  and bugs_id <= 7")->
select("select * from bugs where bugs_id >= 10  and bugs_id <= 12");
//foreach ($bugs_collection as $bug){
//	Zend_Debug::dump($bug->getId());
//}
/*
collection bug id content:
int(5)
int(6)
int(7)
int(10)
int(11)
int(12)
*/
$bugs_collection->get("id == 7")->remove();
//foreach ($bugs_collection as $bug){
//	Zend_Debug::dump($bug->getId());
//}
/*
collection bug id content:
int(5)
int(6)
int(10)
int(11)
int(12)
*/
$otherBug = new bugs(100);
$bugs_collection->add($otherBug);
//foreach ($bugs_collection as $bug){
//	Zend_Debug::dump($bug->getId());
//}
/*
collection bug id content:
int(5)
int(6)
int(10)
int(11)
int(12)
int(100)
*/

/**
 * $bugs_collection->get('id == 100') is memory pointer to $otherBug
 * $bugs_collection->get('id == 100')->getSubject() return 'my test'
 */
$otherBug->setSubject('my test');
echo $bugs_collection->get('id == 100')->getSubject();
$bugs_collection->save();


unset($otherBug);
unset($bugs_collection);

$mybug = new bugs(100);
echo $mybug->getSubject();






?>