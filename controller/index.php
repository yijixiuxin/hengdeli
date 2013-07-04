<?php
class index extends base{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		self::$tpl->assign( 'action', 'index' );
		$this->display('index.tpl');
	}

	public function about(){
		self::$location[] = array('title'=>'关于我们');
		self::$tpl->assign( 'action', 'about' );
		$this->display('index.tpl');
	}
}