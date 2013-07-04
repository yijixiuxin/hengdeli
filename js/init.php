<?php
//线上环境模式 设为false为本地调试模式
define('ONLINE_MODE', false);
define('ROOT_PATH',dirname(__FILE__) );

require(ROOT_PATH."/core/functions.php");
require(ROOT_PATH."/core/template.class.php");
require ROOT_PATH."/core/db.lib.php";

session_start();

if( !ONLINE_MODE ){
	error_reporting(E_ALL^ E_NOTICE);
	Ini_set('display_errors', 1);
	model_session::Instance()->set('user_info', array('user'=>'test', 'name'=>'testuser', 'level'=>100, 'group'=>'开发人员模式'));
	model_session::Instance()->set('acode', '01');
	DB::$Mode = DB_MODE_ECHO_ERROR; //DB::$Mode = DB_MODE_ECHO_SQL;

	require (ROOT_PATH ."/core/fb.php");
	function firelog($value, $key='', $file='', $line='', $type='log'){
		$time = microtime(true);
		$key = "{$file} - {$line} - {$time} - {$key}";
		switch($type){
			case 'log': FB::log($value, $key); break;
			case 'info': FB::info($value, $key); break;
			case 'warn': FB::warn($value, $key); break;
			case 'error': FB::error($value, $key); break;
			case 'dump': FB::dump($key, $value); break;
		}
	}
}else{
	function firelog($value, $key='', $file='', $line='', $type=''){return true;}
}