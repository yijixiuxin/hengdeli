<?php
/**
 * index.php - 入口文件
 */
require (dirname(__FILE__)."/init.php");
require ROOT_PATH."/controller/base.php";
require ROOT_PATH."/controller/ajax.php";

//用户信息
$cuser = model_user::cuser();
//权限验证
$page_info = model_nav::Gets(array('c'=>$c, 'a'=>$a));
if($page_info && $cuser['level'] <= $page_info['level']){
	json_out(0, 'undefined method');
}

$c = new ajax();
$a = $_GET['a'];
if(!method_exists($c, $a)){
	json_out(0, 'undefined method');
}
$c->$a();