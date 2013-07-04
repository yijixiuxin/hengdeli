<?php
class content extends base{
	
	public $cstage = null;
	public $mendian = null;
	
	public function __construct(){
		parent::__construct();
		
		$this->cstage = self::$context['cstage'];
		$this->mendian = model_mendian::Get($_GET['mcode']);
		if( self::$context['cuser']['acode']!='all' && self::$context['cuser']['acode']!=$this->mendian['acode']){
			header("location:404.php");
			exit();
		}
	}
	
	public function index(){
		if($_GET['from']=='report'){
			self::$location[] = array('title'=>'高层阅读报告', 'url'=>'c=report&a=index');
			self::$location[] = array('title'=>'全国门店排行榜', 'url'=>'c=report&a=rank');
			self::$location[] = array('title'=>'查看详情');
		}else{
			$area_name = model_area::getItem("{$this->mendian['acode']}:name");
			self::$location[] = array('title'=>'区域阅读报告', 'url'=>'c=junior&a=index');
			self::$location[] = array('title'=>$area_name, 'url'=>"c=junior&a=index&acode={$this->mendian['acode']}");
			self::$location[] = array('title'=>'排行榜', 'url'=>"c=junior&a=rank&acode={$this->mendian['acode']}");
			self::$location[] = array('title'=>'查看详情');
		}
		$data = model_unit::Gets($this->cstage['pid'], array($this->cstage['info_param']=>$this->cstage['stage_str'].$this->mendian['code']));
		$data = $data[0];
		$project_info = model_project::projectInfo($this->cstage['pid']);
		$qids_array = $project_info['qids_array']; //var_dump($qids_array);die;
		if(!$qids_array || !$data) exit('error');

		$tables = array();
		foreach ($qids_array as $qgroup) {
			$tables[] = $this->buildPart($project_info['sid'], $qgroup, $data);
		}
		
		self::$tpl->assign('table_type', 'muilty_three_line');
		self::$tpl->assign('tables', $tables);
		self::$page_style .= '
			<style type="text/css">
				/*table.three-line th{min-width:5em;}*/
				table.three-line td.dtd_0{text-align:left; padding-left:7px;}
				table.three-line td.dtd_3{width:12em;}
				th#dth_1_1, th#dth_2_1, th#dth_3_1, th#dth_4_1, th#dth_5_1, th#dth_6_1, th#dth_7_1{width:3em;}
				th#dth_1_2, th#dth_2_2, th#dth_3_2, th#dth_4_2, th#dth_5_2, th#dth_6_2, th#dth_7_2{width:3em;}
			</style>';
		
		$this->display('report.tpl');
	}


	public function buildPart($sid, $show_question, $data){
		$questions = model_question::Gets($sid);
		$table_datas = array();
		foreach ($show_question['qids'] as $qid) {
			$qk = "{$sid}X{$qid}";
			$tq = $questions[$qid];
			switch($tq['type']){
				case 'S'://短自由文本
				case 'T'://长自由文本
					$tanswer = $data[$qk];
					$tscore = $treason ='-';
				break;
				case 'L'://单选
				case 'O'://带评论的单选
					$ansInfo = model_answer::getItem("{$qid}:{$data[$qk]}");
					$tanswer = $ansInfo['answer'];
					if($show_question['calcble']){
						$tscore = $ansInfo['value'];
						$treason = empty($data["{$qk}_add"]) ? '-' : $data["{$qk}_add"];
					}
				break;
				case 'M': //多选题不能计分
					$tanswer = '';
					if(strpos($data[$qk], ',')!==false) $options = explode(',', $data[$qk]);
					elseif(strpos($data[$qk], '/')!==false) $options = explode('/', $data[$qk]);
					else $options = array($data[$qk]);
					//var_dump(strpos($options, '/'));die;
					if(!empty($options)){
						foreach ($options as $option) {
							$tanswer .= model_answer::getItem("{$qid}:{$option}:answer")."<br/>";
						}
					}
					$tscore = $treason ='-';
				break;
				case 'H': //自定义
					if($this->cstage['mcode_param'] == $qk){
						$tk = $this->cstage['acode_param'];
						$tacode = $data[$tk];
						$t_m_info = model_mendian::getItem($tacode, "{$tacode}{$data[$qk]}");
						$t_m_type = getConf("type:{$t_m_info['type']}");
						$tanswer = $t_m_info['name']."($t_m_type)";// var_dump($tanswer);die();
						$tq['question'] = '门店';
						
					}elseif($this->cstage['acode_param'] == $qk){
						$tanswer = model_area::getItem("{$data[$qk]}:name");
					}
					
				break;
			}
			if($show_question['calcble']){
				$tfull = model_calculate::getFull($this->cstage['pid'], "{$show_question['gcode']}:{$qid}");
				$table_datas[] = array($tq['question'], $tfull, $tscore, $treason);
			}else{
				$table_datas[] = array($tq['question'], $tanswer);
			}
		}
		if($show_question['calcble']){
			$tfull = model_calculate::getFull($this->cstage['pid'], $show_question['gcode']); //某题组满分
			$tscore = util_report::score_of_mendians($this->cstage['stage'], $show_question['gcode'], $this->mendian['acode'], $this->mendian['code']);
			$table_datas[] = array('模块总评分', $tfull, $tscore, '-');
			return array('table_name'=>$show_question['gname'], 'titles'=>array('检测项目', '满分', '评分', '失分说明'), 'datas'=>$table_datas);
		}else{
			return array('table_name'=>$show_question['gname'], 'titles'=>array('检测项目', '结果'), 'datas'=>$table_datas);
		}
		
	}
}