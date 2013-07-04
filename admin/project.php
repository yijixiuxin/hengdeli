<?php
require dirname(dirname(__FILE__))."/init.php";
if(!model_user::isSuper()){
	header("Location: ../404.php");
	exit();
}

$a = $_REQUEST['a'];
$tpl = Template::get();
firelog($_POST, "Post Data", __FILE__, __LINE__);

if($a == 'edit'){
	$id = (int)$_REQUEST['id'];
	if($id){
		$project_info = model_project::projectInfo($id);
		if($project_info) $tpl->assign('project_info', $project_info);
	}
	$tpl->display('admin/pedit.tpl');
	exit();

}elseif($a == 'qedit' || $a == 'gedit'){
	$id = (int)$_REQUEST['id'];
	$project_info = model_project::projectInfo($id);
	firelog($project_info, 'project_info', __FILE__, __LINE__);
	if(!$project_info) exit('project error');

	if($project_info['qids_array']){
		foreach ($project_info['qids_array'] as $k => $group) {
			$project_info['qids_array'][$k]['qids_str'] = is_array($group['qids']) ? implode(',', $group['qids']) : '';
		}
	}

	$tpl->assign('qids', $project_info['qids_array']);
	if($a == 'qedit'){
		$question_list = model_question::Gets($project_info['sid']);
		/* //@todo可视化问题分组
		$q_of_p = $project_info['qids_array'];
		foreach ($q_of_p as $k => $group) {
			foreach ($group['qids'] as $k=>$qid) {
				$group['qids'][$k] = $question_list[$qid];
				unset(question_list[$qid]);
			}
		}*/
		$tpl->assign('question_list', $question_list);
		//$tpl->assign('q_of_p', $q_of_p);
		//firelog($group, 'questions in project', __FILE__, __LINE__);
	}
	$tpl->assign('pid', $project_info['id']);
	if($project_info['status']==0) $tpl->assign('editable', 'editable');
	$tpl->assign('action', $a);
	$tpl->display('admin/qedit.tpl');
	exit();

}elseif($a == 'update'){
	$data = array_extract($_POST, array('year', 'sid', 'title', 'info'));
	if(empty($data['title'])){
		$data['title'] = model_survey::title($data['sid']);
	}
	if($id = (int)$_POST['id']){
		$res = model_project::update($data, $id);
	}else{
		$res = model_project::insert($data);
	}
	if($res === false){
		$tpl->assign('message', 'fail');
	}else{
		$tpl->assign('message', 'success');
	}

}elseif($a == 'active' || $a == 'die'){
	$id = (int)$_REQUEST['id'];
	$project_info = model_project::projectInfo($id);
	if($a == 'active' && $project_info['status']==0){
		$res = model_project::active($id);
		$full = model_calculate::calcFullScore($id);//统计满分
		firelog($full, 'project full scores', __FILE__, __LINE__);
		model_project::update( array('fullscore'=>serialize($full)), $pid );
	}elseif($a=='die' && $project_info['status']==1){
		$res = model_project::update(array('status'=>2), $id);
	}
	if($res === true || $res=='1') $tpl->assign('message', 'ok');
	else $tpl->assign('message', $res);

}elseif($a == 'qupdate'){
	$pid = (int)$_POST['pid'];
	$project_info = model_project::projectInfo($id);
	if(!$project_info) exit('pid error');
	$qids_array = array();
	if($_POST['gcode']){
		foreach ($_POST['gcode'] as $k => $gcode) {
			$qids = explode(',', $_POST['qids'][$k]);
			$gname = $_POST['gname'][$k];
			$calcble = empty($_POST['calcble'][$k]) ? 0 :1;
			if(empty($gname) || empty($gcode)){ exit('gname or gcode error'); }
			$qids_array[] = array('gcode'=>$gcode, 'gname'=>$gname, 'calcble'=>$calcble, 'qids'=>$qids);
		}
		firelog($qids_array, 'post qids data', __FILE__, __LINE__);
		$res = model_project::update(array('qids'=>serialize($qids_array)), $pid);
		if($res) $tpl->assign('message', 'ok');
	}
	
}elseif($a == 'calc'){
	$pid = (int)$_REQUEST['id']; 

	//统计该项目每个门店在各期调查中的得分
	$project_info = model_project::projectInfo($pid);
	if($project_info && $project_info['status'] > 0){
		$res = model_calculate::calculate($pid);
		if($res) $tpl->assign('message','calculate successfull');
		else $tpl->assign('message','calculate fail');
	}else{
		$tpl->assign('message','project error');
	}
}

pshow($tpl);

function pshow($tpl){
	$project_list = model_project::Gets(array(), array('order'=>array('status'=>-1)));
	firelog($project_list, 'project_list',__FILE__, __LINE__);
	$tpl->assign('project_list', $project_list);
	$tpl->display('admin/project_list.tpl');
}