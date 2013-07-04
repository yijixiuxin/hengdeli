<?php
require_once dirname(__FILE__).'/init.php';
if(model_user::login($_POST['user'], $_POST['pwd'])){
	header('location:index.php');
}else{
	header("Content-type:text/html;charset=utf-8");
	echo '<script>alert("用户名或密码错误");</script>';
	echo "<script>window.location.href='login.html';</script>";
}
exit();