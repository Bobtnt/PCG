<?php


require_once 'class.bugs.php';
require_once 'class.bugs_manager.php';

ini_set('include_path', '.;C:\opt\lib');
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

$bug = new bugs();
$bug->setSubject("test")->setDescription("desc test")->save();

$id = $bug->getId();

unset($bug);

$bug2 = new bugs($id);
$bug2->setSubject("mod sub")->save();

unset($bug2);



?>