<?php
require dirname(dirname(__FILE__))."/init.php";
if(!model_user::isSuper()){
	header("Location: ../404.php");
	exit();
}

$a = $_REQUEST['a'];
$tpl = Template::get();
firelog($_POST, "Post Data", __FILE__, __LINE__);

if($a == 'statis'){
	$stage = (int)$_REQUEST['stage'];
	$stage_info = model_stage::getItem($stage);
	$res = model_statistics::statistics($stage_info['pid'], $stage_info['stage'], $stage_info['info_param']);
	if($res) $tpl->assign('message', 'statistics success');
	else $tpl->assign('message', 'statistics fail');
}elseif($a == 'upfile'){
	exit('doing');
	$stage = (int)$_REQUEST['stage'];
	$stage_info = model_stage::getItem($stage);
	if(!$stage_info) exit('error stage');
	
	$uploda_path = ROOT_PATH."/template_c/";
	$time = time();
	$file_name = $uploda_path."{$stage}-{$time}.cvs";
	$type=$_FILES["file"]["type"];
	if($type != 'text/csv')exit('error: only csv file valide');
	
	$size=$_FILES["file"]["size"];
	if($_FILES["file"]["error"]>0) exit("error:{$_FILES["file"]["error"]}");
	$tmp_name=$_FILES["file"]["tmp_name"];
	$data = file($tmp_name);var_dump($data);die;

	if(move_uploaded_file($tmp_name, $file_name)){
		$data = file($file_name);
		$tpl->assign('message', 'ok');
	}else{
		$tpl->assign('message', 'fail');
	}
}

stage_show($tpl);

function stage_show($tpl){
	$stage_list = model_stage::stages();
	firelog($stage_list, 'stage_list',__FILE__, __LINE__);
	$tpl->assign('stage_list', $stage_list);
	$tpl->display('admin/stage_list.tpl');
}