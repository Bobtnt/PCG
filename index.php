<?php
/**
 * This file is a part of php class generator (PCG) apps.
 * 
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html 
 * author: Cyril Janssens
 * $Id$
 */
#ini_set('include_path', '.;C:\opt\lib');
ini_set('include_path', '.;./lib');

function __autoload($className){
	if(is_file('lib/class.'.$className.'.php')){
		require_once 'lib/class.'.$className.'.php';
	}
	elseif(is_file('out/class.'.$className.'.php')){
		require_once 'out/class.'.$className.'.php';
	}
	elseif($className == 'Zend_Loader'){
		require_once 'Zend/Loader.php';
	}
	else{
		Zend_Loader::loadClass($className);
	}
}

phpClassGenerator::$userZendLoader = true;

phpClassGenerator::factory();
phpClassGenerator::listTable();
phpClassGenerator::makeAllObjects();
phpClassGenerator::makeAll();





?>