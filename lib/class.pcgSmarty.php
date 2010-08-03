<?php

require_once 'smarty/libs/Smarty.class.php';
class pcgSmarty extends smarty {
	
	public function __construct(){
		$this->template_dir = 'smartyWorkDir/templates';
		$this->compile_dir = 'smartyWorkDir/templates_c';
		$this->cache_dir = 'smartyWorkDir/cache';
		$this->debugging = false;
		
		return parent::smarty();		 
	}
	
	
}
?>