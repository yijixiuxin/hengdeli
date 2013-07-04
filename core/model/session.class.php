<?php
class model_session extends model_base{
	public $session = array();
	public static $instance = null;
	public static function Instance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function __construct(){
		if(!isset($_SESSION)){ session_start(); }
		$this->session = &$_SESSION['hengdeli'];
	}
	
	public function set($k, $v){
		$this->session[$k] = $v;
	}
	
	public function get($key=null){
		if($key == NULL) return $this->session;
		// 通过冒号:获取配置层级
		$keys = explode(':', $key);
		$c = $this->session;
		foreach($keys as $k) {
			if(isset($c[$k])) $c = $c[$k];
			else return NULL;
		}
		return $c;
	}

	public function destroy(){
		unset($this->session);
		session_destroy();
	}
}