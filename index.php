<?php
ini_set('include_path', '.;C:\opt\lib');
function __autoload($className){
	if(is_file('lib/class.'.$className.'.php')){
		require_once 'lib/class.'.$className.'.php';
	}
	elseif($className == 'Zend_Loader'){
		require_once 'Zend/Loader.php';
	}
	else{
		Zend_Loader::loadClass($className);
	}
}

phpClassGenerator::factory();

$tables = phpClassGenerator::listTable();

phpClassGenerator::createObjects($tables[0]['name']);

?>