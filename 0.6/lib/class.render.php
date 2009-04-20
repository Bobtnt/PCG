<?php

class render extends pcgSmarty {
	
	static $instance = array();
	public $page = 'index.tpl';
	public $controller = 'index.php';
	const CONTROLLERS_PATH = 'controllers/';
	
	public function __construct($page=false, $controller=false){
		parent::__construct();
		if($page){
			$this->page = $page;
		}
		else{
			$this->page = $this->getPageFromServer();
		}
		if($controller){
			$this->controller = $controller;
		}
		else{
			$this->controller = $this->getControllerFromPage();
		}
		render::$instance[] = $this;
	}
	
	/**
	 * return only the first instance 
	 *
	 * @return render
	 */
	static function getInstance(){
		//return only the first instance
		return self::$instance[0];
	}
	
	private function getPageFromServer(){
		if(!array_key_exists('REDIRECT_URL',$_SERVER)){
			return $this->page;
		}
		else{
			$matches = array();
			preg_match('#^/(.*)[/]?[\?]?#', $_SERVER['REDIRECT_URL'], $matches);
			return $matches[1].'.tpl';
		}
	}
	
	private function getControllerFromPage(){
		$matches = array();
		preg_match('#(.*)\.tpl$#', $this->page, $matches);
		return $matches[1].'.php';
	}
	
	public function controller(){
		require_once self::CONTROLLERS_PATH.$this->controller;
	}
	
	public function display(){
		return parent::display($this->page);
	}
	
}
?>