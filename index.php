<?php
/**
 * index.php - 入口文件
 */
require (dirname(__FILE__)."/init.php");
require ROOT_PATH."/controller/base.php";

//用户信息
$cuser = model_user::cuser();
if( !$cuser ){
	header("Location:login.html"); exit();
}else{
	//base::$tpl->assign('user_info', $cuser);
	base::$context['cuser'] = $cuser;
}

if(isset($_REQUEST['stage'])){
	model_session::Instance()->set('stage', $_REQUEST['stage']);
}

$c = empty($_REQUEST['c']) ? 'index' : $_REQUEST['c'];
$file = ROOT_PATH."/controller/{$c}.php";
if(!file_exists($file)){
	exit("Controller ERROR!");	
}
base::$tpl->assign('c', $c);

require $file;
$c = new $c();
$a = $_GET['a'];
if(!method_exists($c, $a)) $a = 'index';
base::$tpl->assign('a', $a);

//权限验证
$page_info = model_nav::Gets(array('c'=>$c, 'a'=>$a));
if($page_info && $cuser['level'] <= $page_info['level']){
	header("Location:404.php");
	exit();
}

base::$page_title .= isset($page_info['page_title']) ? $page_info['page_title'] : '';
$c->$a();