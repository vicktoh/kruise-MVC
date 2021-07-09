<?php 
class App{
	protected $controller = "Home";
	protected $method = "index";
	protected $params = [];

	public function __construct(){
		$url = $this->parse_url();
		if(file_exists(CONTROLLERS.ucfirst($url[0]).".php")){
			$this->controller = ucfirst($url[0]);
			unset($url[0]);
		}
		
		require_once(CONTROLLERS.$this->controller.".php");
		//die(CONTROLLERS.$this->controller.".php");
		$this->controller = new $this->controller;
		if(isset($url[1]))
		{
			if(method_exists($this->controller,$url[1])){
				$this->method = $url[1];
				unset($url[1]);
				
			}

		}
		$this->params = $url ? array_values($url): [];
		call_user_func_array([$this->controller, $this->method], $this->params);



		
	}
	
	private function parse_url(){
		if(isset($_GET["url"])){
			//die($_GET['url']);
			$url = filter_var(rtrim($_GET["url"], "/"),FILTER_SANITIZE_URL);
			$url = explode("/",$url);
			return $url;
		}
		
	}
	
	
}