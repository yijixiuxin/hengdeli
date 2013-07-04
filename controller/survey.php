<?php
/**
 * 显示问卷内容
 * 必须参数：答案编号：id, 问卷编号：sid
 */
class survey extends base{
	public function index(){
		$sid = model_survey::currentSid();
		$id = (int)$_REQUEST['id'];
		//$survey_info = model_survey::title($sid);
		
	}
}
include ROOT_PATH."/part/top.php";

include ROOT_PATH."/part/left.php";


$answer = $db->queryLine("SELECT * FROM `lime_survey_{$_sid}` WHERE `id`=$id");

$paction = $_GET['paction'];
$title = $db->queryVar("SELECT `title` FROM `sto_nav` WHERE `url`='{$paction}'");
//当前位置
$location = array(
					array('url'=>$paction,'title' => $title),
					array('url'=>$paction . '&method=detail','title' => '记录列表'),
					array('title' => '记录详情')
);
$tpl->assign('location',$location);

$partname = $title;

$gids = array();
$sql = "SELECT * FROM `sto_surveys_info` WHERE `sid`='$_sid'";
$res = $db->queryRows($sql);
foreach($res as $k=>$v)
{
	$temp = explode(',', $v['gids']);
	$gids = array_merge($gids,$temp);
}

$sql = "SELECT * FROM `lime_questions` WHERE `sid`='$_sid'";
$questions = $db->queryRows( $sql );
//echo "<pre>";print_r($questions);die;

$_answer_to_word = array('1'=>'Y','0'=>'N','99'=>'不确定');

$qanda = array();
$i = 0;
$g = 0;
foreach( $questions as $key=>$question ){
		
		if('X' == $question['type']){		//样板文件,或作为调查的设计分隔
				$_gid = $question['gid'];
				$ginfo = q_group::getInfo( $question['gid'], $_sid );
				$qanda[$i]['biggid'] = $ginfo['group_name'];
				$i++;
			continue;
		}
		
		$q_name = $_sid."X".$question['gid']."X".$question['qid'];
		$q_comment = $q_name."comment";
				
		switch( $question['type'] ){
		
				case 'X':
					continue;
				break;
				
				case 'L':
					$temp_q = $question['question'];
					//$temp_a = answer::getQA($question['qid'],$answer[$q_name]);
					$temp_a = answer::getAnswer($question['qid'],$answer[$q_name]);
					//$a = $temp_a['assessment_value']."分";
					$a = $_answer_to_word [$temp_a];
				break;
				
				default:
					$temp_q = $question['question'];
					$a = $answer[$q_name];
				break;
		}
		
		if( !isset($_gid) || $_gid != $question['gid']){
				
			$_gid = $question['gid'];
			$ginfo = q_group::getInfo( $question['gid'], $_sid );
			
			if(!isset($partname)){	//partname - 大板块标题（A，B...）
				$partname = $ginfo['group_name'];
			}else{
				$g++;
				$qanda[$i]['gid'] = $ginfo['group_name'];//gid - 小版块标题（A1，A2...）
			}
		}
		$qanda[$i]['q'] = $temp_q;
		$qanda[$i]['a'] = $a;
		$i++;
		
}

$tpl->assign( 'partname', $partname );
$tpl->assign( 'qanda', $qanda );
$tpl->assign( 'list', $list );
$tpl->assign( 'nomethod', true );

$tpl->display("neirong.tpl");
exit();