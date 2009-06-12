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
			$page = $matches[1];
			if(substr($page, -1, 1) == '/'){
				$page = substr($page, 0, -1);
			}
			return $page.'.tpl';
		}
	}
	
	private function getControllerFromPage(){
		$matches = array();
		preg_match('#(.*)\.tpl$#', $this->page, $matches);
		return $matches[1].'.php';
	}
	
	/**
	 * Run controller
	 *
	 * @return render
	 */
	public function controller(){
		if(is_file(self::CONTROLLERS_PATH.$this->controller)){
			require_once self::CONTROLLERS_PATH.$this->controller;
		}
		return $this;
	}
	
	public function display(){
		
		if(!is_file($this->template_dir.'/'.$this->page)){
			$this->page = 'error/404.tpl';
		}
		return parent::display($this->page);
	}
	
}
?>