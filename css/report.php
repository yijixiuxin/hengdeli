<?php
/**
 * 高层阅读报告
 */
class report extends base{
	public function __construct(){
		parent::__construct();
		$user_info = model_user::cuser();
		if($user_info['level'] < 10){
			header("location:404.php");
			exit();
		}

		//if(isset($_REQUEST['a']) && $_REQUEST['a']!='index'){
			self::$location[] = array('title'=>'高层阅读报告', 'url'=>"c=report");
			$navArrays = $this->navArrays(false);
			$navArrays[] = array('title'=>"9.排行榜", 'datas'=>array(array('title'=>"9.1全国表行排行榜", 'url'=>"index.php?c=report&a=rank")));
			self::$tpl->assign('nav_arrays', $navArrays);
		//}else{
		//	self::$location[] = array('title'=>'高层阅读报告');
		//}
		if(strpos($_GET['a'],'report1')!==false){
			self::$location[] = array('title'=>'1.全国及各渠道MS诊断报告');
		}else{
			$gcode = $_GET['gcode'];
			$project_info = model_project::projectInfo(self::$context['cstage']['pid']);
			$qids = $project_info['qids_array'];
			foreach ($qids as $ginfo) {
				if($ginfo['gcode']==$gcode){
					$gname = preg_replace('|（.*?）|', '', $ginfo['gname']);
					$gname = preg_replace('|\(.*?\)|', '', $gname);
					self::$location[] = array('title'=>"{$gcode}.{$gname}");
					break;
				}
			}
		}

		$creportInfo = model_mendian::calcTypes(self::$context['cstage']['stage'], $acode='all');
		self::$tpl->assign('creportInfo', $creportInfo);
	}

	public function nodata($msg = ''){
		$table_datas = empty($msg) ? array(array('暂无数据')) : array(array($msg));
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', array(' '));
		self::$tpl->assign('table_datas', $table_datas);
		$this->display('report.tpl');
	}

	public function index(){
		
		$table_datas = $this->navArrays();
		$table_datas[] = array('title'=>"排行榜", 'datas'=>array(array('title'=>"全国表行排行榜", 'url'=>"index.php?c=report&a=rank")));
		//self::$tpl->assign('nav_arrays', $table_datas);
		self::$tpl->assign( 'table_type', 'dir' );
		self::$tpl->assign('tables', $table_datas);
		$this->display('report.tpl');
	}

	public function rank(){
		self::$location[] = array('title'=>'全国表行排行榜');
		$cstage = self::$context['cstage'];
		$data = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
		$data = $data['total'];
		$finalData = array();
		foreach($data as $acode=>$ascores){
			if($acode=='0') continue;
			foreach($ascores as $mcode=>$mscore){
				$info = model_mendian::Get($mcode);
				$finalData[$mcode] = array('defenlv'=>$mscore, 'info'=>$info);
			}
		}
		unset($data);
		$table_titles = array('表行', '所在渠道', '排名', '本期得分', '表行详情'); //th中的标题
		$table_datas = array();
		$finalData = util_sort::bubbleSort($finalData, 'defenlv', $mode='D');
		foreach($finalData as $minfo){
			$ainfo = model_area::getItem("{$minfo['info']['acode']}:name");
			$table_datas[] = array(
				'mendian'=>"<a href='index.php?c=content&from=report&mcode={$minfo['info']['code']}'>{$minfo['info']['name']}</a>", 
				'ainfo'=>$ainfo,'rank'=>$minfo['rank'], 'defenlv'=>$minfo['defenlv'], 
				"<a href='index.php?c=content&from=report&mcode={$minfo['info']['code']}'>查看</a>");
		}
		self::$tpl->assign('table_type', 'three_line');
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		
		$this->display('report.tpl');
	}

	private function navArrays($long=true){
		$table_datas = array();
		$level1 = model_nav::Gets(array('c'=>'report', 'status'=>2), array('order'=>array('order'=>1)));
		if($level1){
			$temp = array('title'=>'1.全国及各渠道MS诊断报告', 'datas'=>array());
			foreach ($level1 as $nav) {
				$tempTitle = (!$long && $nav['short_title']) ? $nav['short_title'] :$nav['title'];
				$temp['datas'][] = array(
					'title'=>$tempTitle, 
					'url'=>"index.php?c=report&a={$nav['a']}");
			}
			$table_datas[] = $temp;
		}

		$cstage = self::$context['cstage'];
		$groups = model_project::calcbleGroup($cstage['pid']); $tc = 2;
		if($groups){
			foreach ($groups as $gcode => $ginfo) {
				$tempTitle = empty($long) ? "{$tc}.{$ginfo['gname']}" : "{$tc}.服务流程诊断报告-{$ginfo['gname']}";
				if($long==false){ $tempTitle = preg_replace('|（.*?）|', '', $tempTitle);}
				
				$table_datas[] = array(
					'title' => $tempTitle,
					'datas' => array(
						array('title'=>"{$tc}.1三级指标环比上期得分率", 'url'=>"index.php?c=report&a=report2_1&gcode={$gcode}"),
						array('title'=>"{$tc}.2表行得分率、众数和中位数环比", 'url'=>"index.php?c=report&a=report2_2&gcode={$gcode}"),
						array('title'=>"{$tc}.3高层需重点关注的渠道", 'url'=>"index.php?c=report&a=report2_3&gcode={$gcode}"),
					)
				);
				$tc ++;
			}
		}
		return $table_datas;
	}

	/**
	 * 1-1各渠道环比上期成绩
	 * 三线表输出
	 */
	public function report1_1(){
		self::$location[] = array('title'=>'1.1各渠道环比上期成绩');
		self::$tpl->assign('table_name', '1.1各渠道环比上期成绩');
		$cstage = self::$context['cstage'];
		$cfull = model_calculate::getFull($cstage['pid'], 'total');//本期满分

		$areas = model_area::getItem();

		$table_titles = array('渠道', '本期排名', '本期得分率', '上期得分率', '变化情况'); //th中的标题
		$table_datas = array();

		$c_average = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
		
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$p_average = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all'); //本期全国各区总分平均分
			$pfull = model_calculate::getFull($pstage['pid'], 'total'); //上期满分
			foreach ($areas as $acode=>$info) {
				$c = util_report::score_of_acode($cstage['stage'], 'total', $info['code']); $c = ($c==-1) ? '-' :percent($c, $cfull);
				$p = util_report::score_of_acode($pstage['stage'], 'total', $info['code']); $p = ($p==-1) ? '-' :percent($p, $pfull);
				$i = ($c=='-' || $p=='-') ? '-' : $c - $p;
				$table_datas[$acode] = array(
					'name' => $info['name'],
					'rank' => 1,
					'c' => $c,
					'p' => $p,
					'i' => number_format($i, 1));
			}
			
		}else{
			$p_average = '-';
			foreach ($areas as $info) {
				$acode = $info['code'];
				$c = util_report::score_of_acode($cstage['stage'], 'total', $info['code']); $c = ($c==-1) ? '-' :percent($c, $cfull);
				$table_datas[$acode] = array(
					'name' => $info['name'],
					'rank' => 1,
					'c' => $c,
					'p' => '-',
					'i' => '-');
			}
		}
		$table_datas = util_sort::bubbleSort($table_datas, 'c');

		//显示图表
		if($_REQUEST['ajax']=='chart'){
			$info = array('chart_name' => '各渠道环比上期成绩', 'xname'=>'渠道', 'yname'=>'得分率', 'cstage'=>$cstage['stage'], 'data'=>$table_datas);
			$obj = new util_multiChart();
			if(isset($pstage) && $pstage){
				$chartData = util_chart::buildNormal($info, $param=array('catgry_k'=>'name','c_k'=>'c','p_k'=>'p'), true);
				$width = count($table_datas) * 80;
			}else{
				$chartData = util_chart::buildNormal($info, $param=array('catgry_k'=>'name','c_k'=>'c','p_k'=>'p'), false);
				$width = count($table_datas) * 40;
			}//var_dump($chartData);die;
			$chartData = $obj->buildChart($chartData);
			$chartData = preg_replace('/\s+/', ' ', $chartData);
			$flash = ($_REQUEST['type']=='zhu') ? 'MSColumn3D.swf' : 'MSLine.swf';
			self::$tpl->assign('chart', array('width'=>$width, 'height'=>350, 'xml'=>$chartData, 'flash'=>$flash));
			$this->display('chart.tpl');
		}
		
		$table_datas = array_merge(array(array('全国平均', '-', $c_average, $p_average,'-')), $table_datas);

		self::$tpl->assign('table_type', 'three_line');//一级环比用三线表
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('chart_id', array('c'=>'report', 'a'=>'report1_1')); //分布、平均分等数据分析
		
		$this->display('report.tpl');
	}

	public function report1_2(){
		self::$location[] = array('title'=>'1.2全国门店成绩分布、众数和中位数环比');
		self::$tpl->assign('table_name', '1.2全国门店成绩分布、众数和中位数环比');
		$cstage = self::$context['cstage'];
		//$cscore = util_report::score_of_acodes($cstage['stage'], 'total', 'all');

		$cscores = util_report::scoresArray($cstage['stage'], 'total', 'all');
		$cfull = model_calculate::getFull($cstage['pid'], 'total');
		$cAnalyse = model_statistics::analyseAll($cscores, $cfull);
		self::$tpl->assign('c_analyse', $cAnalyse);
		
		//上期数据分析
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$pscores = util_report::scoresArray($pstage['stage'], 'total', 'all');
			$pfull = model_calculate::getFull($pstage['pid'], 'total');
			$pAnalyse = model_statistics::analyseAll($pscores, $pfull);
			self::$tpl->assign('p_analyse', $pAnalyse);

			$compare = array();
			foreach ($cAnalyse as $k => $v) {
				$compare[$k] = $v - $pAnalyse[$k];
			}
			self::$tpl->assign('compare', $compare);
		}

		//显示图表
		if($_REQUEST['ajax']=='chart'){
			$info = array('chart_name' => '全国门店成绩分布、众数和中位数环比', 'cstage'=>$cstage['stage']);
			$obj = new util_multiChart();
			if(isset($pAnalyse) && $pAnalyse){
				$chartData = util_chart::buildAnalyse($info, $cAnalyse, $pAnalyse);
			}else{
				$chartData = util_chart::buildAnalyse($info, $cAnalyse);
			}
			$chartData = $obj->buildChart($chartData); //var_dump($chartData);die;
			$chartData = preg_replace('/\s+/', ' ', $chartData);
			$flash = ($_REQUEST['type']=='zhu') ? 'MSColumn3D.swf' : 'MSLine.swf';
			self::$tpl->assign('chart', array('width'=>750, 'height'=>350, 'xml'=>$chartData, 'flash'=>$flash));
			$this->display('chart.tpl');
		}
		
		self::$tpl->assign('table_type', 'analyse'); //分布、平均分等数据分析
		self::$tpl->assign('chart_id', array('c'=>'report', 'a'=>'report1_2')); //分布、平均分等数据分析
		self::$tpl->assign('introInfos', util_report::introInfo(array('zs','zws','pingjuqb')));
		$this->display('report.tpl');
	}

	//各渠道二级指标的得分率
	public function report1_3(){
		self::$location[] = array('title'=>'1.3各渠道二级指标的得分率');
		self::$tpl->assign('table_name', '1.3各渠道二级指标的得分率');
		$cstage = self::$context['cstage'];
		
		$table_titles = array('渠道名称'); //th中的标题
		$table_datas = array();

		/*for chart*/
		$categories = array();//x轴
		$datasets = array();
		$obj = new util_multiChart();
		
		$project_info = model_project::projectInfo($cstage['pid']);
		$qids = $project_info['qids_array'];
		$areas = model_area::getItem();
		
		foreach ($qids as $gk => $gv) {
			if(!$gv['calcble']) continue;//不计分的题组跳过

			$gcode = $gv['gcode'];
			$table_titles[] = $gv['gname'];
			$datasets[$gcode] = array('attributes' => array('name' => $gv['gname']),'datas'=>array());
			$full = model_calculate::getFull($cstage['pid'], $gcode);//本题组满分
			foreach ($areas as $acode=>$info) {
				$temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
				$table_datas[$acode][$gk] = ($temp==-1) ? '-' : percent($temp, $full);
				$datasets[$gcode]['datas'][] = array('value'=>($temp==-1) ? 0 : percent($temp, $full));
			}
		}

		//处理acode
		foreach ($table_datas as $acode => $data) {
			$aname = model_area::getItem("{$acode}:name"); //根据地区编码获取地区名称
			$categories[] = $aname;
			$table_datas[$acode] = array_merge(array('name'=>$aname), $data);// array('name'=>$aname, 'data'=>$data);
		}

		//显示图表
		if($_REQUEST['ajax']=='chart'){
			$chartData = array(
				'chart'=>array('chart_name' => '各渠道二级指标的得分率', 'xname'=>'渠道名称', 'yname'=>'得分率'),
				'categories' => $categories, 'datasets'=>$datasets);
			$chartData = $obj->buildChart($chartData);
			$chartData = preg_replace('/\s+/', ' ', $chartData);
			$flash = ($_REQUEST['type']=='zhu') ? 'MSColumn3D.swf' : 'MSLine.swf';
			$width = count($categories) * 40 * count($datasets); $width = ($width>4000) ? 4000:$width;
			self::$tpl->assign('chart', array('width'=>$width, 'height'=>350, 'xml'=>$chartData, 'flash'=>$flash));
			$this->display('chart.tpl');
		}

		self::$page_style .= '<style type="text/css">thead #dth_0{width:5em;}</style>';
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		//self::$tpl->assign('chart_id', array('c'=>'report', 'a'=>'report1_3')); //分布、平均分等数据分析
		$this->display('report.tpl');
	}

	public function report1_4(){
		self::$location[] = array('title'=>'1.4本期服务细节短板');
		self::$tpl->assign('table_name', '1.4本期服务细节短板');
		$cstage = self::$context['cstage'];
		$table_datas = util_report::character($cstage['stage']);
		
		
		if(!$table_datas){
			$table_datas = array(array('暂无数据', '-'));
		}
		
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', array('服务细节', '本期得分率'));
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('introInfos', util_report::introInfo(array('cduanban')));
		self::$page_style .= '<style type="text/css">tbody td.dtd_0{text-align:left; padding-left:4px;} thead #dth_1{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_5(){
		self::$location[] = array('title'=>'1.5上期服务细节短板跟踪');
		self::$tpl->assign('table_name', '1.5上期服务细节短板跟踪');
		$cstage = self::$context['cstage'];
		$table_datas = util_report::character($cstage['stage']-1, 'last', 10, true);//var_dump($table_datas);die;

		if(!$table_datas){
			$table_datas = array(array('暂无数据', '-', '-', '-'));
		}else{
			foreach ($table_datas as $qcode => $data) {
				$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
				$c = util_report::score_of_acodes($cstage['stage'], $qcode, $acodes='all'); $c = ($c==-1)?'-':percent($c, $full);
				$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
				$table_datas[$qcode] = array(
					'question' => $data['q'],
					'p_defenlv' => $data['v'],
					'c_defenlv' => $c,
					'i' => $i
					);
			}
			$table_datas = util_sort::bubbleSortOnly($table_datas, 'p_defenlv', 'A'); //按上期得分率升序
		}

		self::$tpl->assign('table_type', 'three_line');
		self::$tpl->assign('table_titles', array('服务细节',  '上期得分率', '本期得分率','变化情况'));
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('introInfos', util_report::introInfo(array('pduanban')));
		self::$page_style .= '<style type="text/css">tbody td.dtd_0{text-align:left; padding-left:4px;} #dth_1,#dth_2,#dth_3{width:6em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_6(){
		self::$location[] = array('title'=>'1.6本期表现不佳的渠道点评');
		$cstage = self::$context['cstage'];
		$cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
		$cscore = ($cscore==-1) ? '-' : percent($cscore, 100);

		$areas = model_area::getItem(); $acount = count($areas);
		$data1 = $data2 = $data3 = array(); $certainAcode = array();
		foreach ($areas as $acode => $ainfo) {
			$ascore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
			$ascore = percent($ascore, 100);
			if($ascore < $cscore){
				$data1[$acode] = array(
					'aname'=>$ainfo['name'],
					'rank' => 1,
					'score' => $ascore
					);
				$certainAcode[] = $acode;
			}
		}
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $acount-count($data1));
		/*$data1 = util_sort::bubbleSort($data1, 'score', 'A');
		foreach ($data1 as $k => $v) $data1[$k]['rank'] = $acount - $v['rank']+1;
		$data1 = array_reverse($data1, true);*/
		
		//$data1 = array_merge(array(array('全国平均', '-', $cscore)), $data1);
		$tables = array(
			array('titles'=>array( '渠道', '排名', '本期得分率'), 'table_name'=>'本期表现不佳的渠道成绩', 'datas'=>array_merge(array(array('全国平均', '-', $cscore)), $data1))
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['titles'] =array('渠道');
		$data2['table_name'] = '二级指标表现';
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			$full = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($certainAcode as $acode) {
				if(!isset($data2['datas'][$acode]['aname']) || !$data2['datas'][$acode]['aname']){
					$data2['datas'][$acode] = array('aname'=> model_area::getItem("{$acode}:name"));
				}
				$temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$data2['datas'][$acode][$gcode] = $temp;
			}
		}
		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = $tempData2;
		$tables[] = $data2;

		$data3['table_name'] = '导致表现不佳的具体服务细节短板';
		$data3['titles'] = array( '服务细节','本期得分率');
		$data3['datas'] = util_report::character($cstage['stage'], $style="last",$top=10, $detail=false, $certainAcode);
		$tables[] = $data3;

		self::$tpl->assign('table_type', 'muilty_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('cbadqd', 'badreason')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_7(){
		self::$location[] = array('title'=>'1.7上期表现不佳的渠道');
		self::$tpl->assign('table_name', '1.7上期表现不佳的渠道');
		$cstage = self::$context['cstage'];
		$cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
		$cscore = ($cscore==-1) ? '-' : percent($cscore, 100);
		
		$pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');//上期全国各区总分平均分
		if(-1 == $pscore){ $this->nodata();}
		$pscore = ($pscore==-1) ? '-' : percent($pscore, 100);

		$areas = model_area::getItem();  $acount = count($areas);
		$data1 = $data2 = $data3 = array(); $certainAcode = array();
		foreach ($areas as $acode => $ainfo) {
			$apscore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //该渠道上期得分
			$apscore = ($apscore==-1) ? '-' : percent($apscore, 100);
			if($apscore < $pscore){
				$acscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);//该渠道本期得分
				$acscore = ($acscore==-1) ? '-' : percent($acscore, 100);
				$data1[$acode] = array(
					'aname'=>$ainfo['name'],
					'prank' => 1,
					'pscore' => $apscore,
					'rank' => 1,
					'score' => $acscore,
					'i' => ($acscore=='-' || $apscore=='-') ? '-' : number_format($acscore-$apscore, 1),
					);
				$certainAcode[] = $acode;
			}
		}
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $acount-count($data1));
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank', $acount-count($data1));
		/*$data1 = util_sort::bubbleSort($data1, 'score', 'A', 'rank');
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'A', 'prank');
		foreach ($data1 as $k => $v) $data1[$k]['rank'] = $acount - $v['rank']+1;
		foreach ($data1 as $k => $v) $data1[$k]['prank'] = $acount - $v['prank']+1;
		$data1 = array_reverse($data1, true);*/

		//$data1 = array_merge(array(array('全国平均', '-', $pscore, '-', $cscore)), $data1);
		$tables = array(
			array('titles'=>array( '渠道', '上期排名', '上期得分率', '本期排名', '本期得分率', '变化情况'), 'table_name'=>'跟踪该类渠道在本期的一级指标表现','datas'=>array_merge(array(array('全国平均', '-', $pscore, '-', $cscore, '-')), $data1))
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['type'] = 'ext';
		$data2['table_name'] = '跟踪该类渠道在本期的二级指标表现';
		$data2['titles'] =array('渠道'); 
		$temp_title2 = array(''); //二级title
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			array_push($temp_title2, '上期', '本期');
			$full = model_calculate::getFull($cstage['pid'], $gcode); //该题组满分，用于计算得分率
			foreach ($certainAcode as $acode) {
				if(!isset($data2['datas'][$acode]['aname']) || !$data2['datas'][$acode]['aname']){
					$data2['datas'][$acode] = array('aname'=> model_area::getItem("{$acode}:name")); //第一列为渠道名称
				}
				$temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode); //本期该渠道本题得分
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$ptemp = util_report::score_of_acode($cstage['stage']-1, $gcode, $acode);
				$ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
				$data2['datas'][$acode][] = $ptemp;
				$data2['datas'][$acode][] = $temp;
			}
		}

		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = array_merge(array($temp_title2), $tempData2);
		$tables[] = $data2;

		$data3['table_name'] = '跟踪上期导致不佳的具体服务细节短板在本期改善情况';
		$data3['titles'] = array( '服务细节','上期得分率','本期得分率');
		$data3['datas'] = util_report::character($cstage['stage']-1, $style="last",$top=10, $detail=true, $certainAcode);
		foreach ($data3['datas'] as $qcode => $data) {
			$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
			$c = util_report::score_of_acodes($cstage['stage'], $qcode, $certainAcode); $c = ($c==-1)?'-':percent($c, $full);
			//$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
			$data3['datas'][$qcode] = array(
				'question' => $data['q'],
				'p_defenlv' => $data['v'],
				'c_defenlv' => $c,
				//'i' => $i
				);
		}
		$tables[] = $data3;

		self::$tpl->assign('table_type', 'compare_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('pbadqd', 'badreason')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1, #dth_2_2{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_8(){
		self::$location[] = array('title'=>'1.8上期达标渠道本期表现');
		$cstage = self::$context['cstage'];
		
		$pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');
		if(-1 == $pscore){ $this->nodata();}

		$areas = model_area::getItem(); $acount = count($areas);
		$data1 = $data2 = $data3 = array(); $certainAcode = array();
		foreach ($areas as $acode => $ainfo) {
			$ascore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //pre
			$ascore = percent($ascore, 100);
			if($ascore > 85){
				$temppscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
				$temppscore = percent($temppscore, 100); //this
				$data1[$acode] = array(
					'aname'=>$ainfo['name'],
					'prank' => 1,
					'pscore' => $ascore,
					'rank' => 1,
					'score' => $temppscore,
					'i' => ($ascore=='-' || $temppscore=='-') ? '-' : number_format($temppscore-$ascore, 1)
					);
				$certainAcode[] = $acode;
			}
		}
		var_dump($data1);die;
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank');
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank');
		//foreach ($data1 as $k => $v) $data1[$k]['rank'] = $acount - $v['rank']+1;
		//foreach ($data1 as $k => $v) $data1[$k]['prank'] = $acount - $v['prank']+1;
		//$data1 = array_reverse($data1, true);

		//$data1 = util_sort::bubbleSort($data1, 'score');
		$tables = array(
			array('titles'=>array( '渠道', '上期排名', '上期得分率', '本期排名', '本期得分率', '变化情况'), 'table_name'=>'跟踪该类渠道在本期的一级指标表现','datas'=>$data1)
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['type'] = 'ext';
		$data2['table_name'] = '跟踪该类渠道在本期的二级指标表现';
		$data2['titles'] =array('渠道');
		$temp_title2 = array(''); //二级title
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			array_push($temp_title2, '上期', '本期');
			$full = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($certainAcode as $acode) {
				if(!isset($data2['datas'][$acode]['aname']) || !$data2['datas'][$acode]['aname']){
					$data2['datas'][$acode] = array('aname'=> model_area::getItem("{$acode}:name"));
				}
				$temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$ptemp = util_report::score_of_acode($cstage['stage']-1, $gcode, $acode);
				$ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
				
				$data2['datas'][$acode][] = $ptemp;
				$data2['datas'][$acode][] = $temp;
			}
		}
		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = array_merge(array($temp_title2), $tempData2);
		$tables[] = $data2;
		//$data2['datas'] = array_merge(array($temp_title2), $tempData2);
		//$tables[] = $data2;

		$data3['table_name'] = '跟踪上期支撑达标的服务细节亮点在本期的保持情况';
		$data3['titles'] = array( '服务细节','上期得分率','本期得分率');
		$data3['datas'] = util_report::character($cstage['stage']-1, $style="top",$top=10, $detail=true, $certainAcode);
		foreach ($data3['datas'] as $qcode => $data) {
			$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
			$c = util_report::score_of_acodes($cstage['stage'], $qcode, $certainAcode); $c = ($c==-1)?'-':percent($c, $full);
			$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
			$data3['datas'][$qcode] = array(
				'question' => $data['q'],
				'p_defenlv' => $data['v'],
				'c_defenlv' => $c,
				//'i' => $i
				);
		}
		$tables[] = $data3;

		self::$tpl->assign('table_type', 'compare_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('pgoodqd', 'pliangdian')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1, #dth_2_2, #dth_2_3{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report2_1(){
		$gcode = $_GET['gcode'];
		
		self::$location[] = array('title'=>"{$gcode}.1三级指标环比上期得分率");
		self::$tpl->assign('table_name', "{$gcode}.1三级指标环比上期得分率");
		$cstage = self::$context['cstage'];
		
		$questions = model_project::questions($cstage['pid'], $gcode);
		if(!$questions){
			firelog('no questions', "error gcode-{$gcode}", __FILE__, __LINE__, 'error');
			exit('error data');
		}
		
		$table_datas = array();
		foreach ($questions as $qid => $qinfo) {
			$full = model_calculate::getFull($cstage['pid'], "{$gcode}:{$qinfo['qid']}");
			$c = util_report::score_of_acodes($cstage['stage'], "{$qinfo['sid']}X{$qinfo['qid']}", 'all');
			$c = ($c==-1)?'-':percent($c, $full);
			$p = util_report::score_of_acodes($cstage['stage']-1, "{$qinfo['sid']}X{$qinfo['qid']}", 'all');
			$p = ($p==-1)?'-':percent($p, $full);
			$i = ($c=='-'||$p=='-') ? '-' : number_format($c-$p, 1);
			$table_datas[] = array($qinfo['question'], $c, $p, $i);
		}
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', array("三级指标", '本期得分率', '上期得分率', '变化情况'));
		self::$page_style .= '<style type="text/css">tbody td.dtd_0{text-align:left; padding-left:4px;} #dth_1, #dth_2, #dth_3{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report2_2(){
		$gcode = $_GET['gcode'];
		
		self::$location[] = array('title'=>"{$gcode}.2全国门店成绩分布、众数和中位数环比");
		self::$tpl->assign('table_name', "{$gcode}.2全国门店成绩分布、众数和中位数环比");
		$cstage = self::$context['cstage'];

		$cscores = util_report::scoresArray($cstage['stage'], $gcode, 'all');
		if(!$cscores) exit('gcode error');
		$cfull = model_calculate::getFull($cstage['pid'], $gcode);
		$cAnalyse = model_statistics::analyseAll($cscores, $cfull);
		self::$tpl->assign('c_analyse', $cAnalyse);
		
		//上期数据分析
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$pscores = util_report::scoresArray($pstage['stage'], $gcode, 'all');
			$pfull = model_calculate::getFull($pstage['pid'], $gcode);
			$pAnalyse = model_statistics::analyseAll($pscores, $pfull);
			self::$tpl->assign('p_analyse', $pAnalyse);

			$compare = array();
			foreach ($cAnalyse as $k => $v) {
				$compare[$k] = $v - $pAnalyse[$k];
			}
			self::$tpl->assign('compare', $compare);
		}
		
		self::$tpl->assign('introInfos', util_report::introInfo(array('zws', 'zs', 'pingjuqb')));
		self::$tpl->assign('table_type', 'analyse'); //分布、平均分等数据分析
		$this->display('report.tpl');
	}

	public function report2_3(){
		$gcode = $_GET['gcode'];
		
		self::$location[] = array('title'=>"{$gcode}.3高层需重点关注的渠道");
		
		$cstage = self::$context['cstage'];
		$cscore = util_report::score_of_acodes($cstage['stage'], $gcode, $acodes='all'); if(!$cscore) exit('gcode error');
		$cscore = ($cscore==-1) ? '-' : percent($cscore, 100);

		$areas = model_area::getItem(); $acount = count($areas);
		$data1 = $data2 = $data3 = $data4 = array(); $certainAcode = array();
		foreach ($areas as $acode => $ainfo) {
			$ascore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
			$ascore = percent($ascore, 100);
			if($ascore < $cscore){
				$data1[$acode] = array(
					'aname'=>$ainfo['name'],
					'score' => $ascore
					);
				$certainAcode[] = $acode;
			}
		}
		$data1 = util_sort::bubbleSortOnly($data1, 'score');
		$data1 = array_merge(array(array('该指标全国平均', $cscore)), $data1);
		$tables = array(
			array('titles'=>array( '渠道', '本期得分'), 'table_name'=>'本期表现不佳的渠道成绩', 'datas'=>$data1)
		);

		//导致表现不佳的具体服务细节短板
		$tempdatas = array();
		$shortslab = util_report::character($cstage['stage'], 'last', 999, true, $certainAcode);
		if($shortslab){
			foreach ($shortslab as $qcode => $info) {
				if($info['i']['gcode'] == $gcode){
					$tempdatas[] = array('q'=>$info['q'], 'value'=>$info['v']);
				}
				if(count($tempdatas)==3) break;
			}
		}
		$tempdatas = util_sort::bubbleSortOnly($tempdatas, 'value', 'A');
		$tables[] = array(
			'titles' => array( '服务细节','本期得分率' ),
			'table_name' => '导致表现不佳的具体服务细节短板',
			'datas' => $tempdatas);
		
		//上期表现不佳的渠道
		$pstage = model_stage::getItem($cstage['stage']-1);
		if( $pstage ){
			$pscore = util_report::score_of_acodes($pstage['stage'], $gcode, $acodes='all'); //上期平均
			$pfull = model_calculate::getFull($pstage['stage'], $gcode);
			$pscore = ($pscore==-1) ? '-' : percent($pscore, $pfull);

			$areas = model_area::getItem();
			$cfull = model_calculate::getFull($cstage['stage'], $gcode);
			foreach ($areas as $acode => $ainfo) {
				$apscore = util_report::score_of_acode($pstage['stage'], $gcode, $acode);
				$apscore = ($apscore==-1) ? '-' : percent($apscore, $pfull);
				if($apscore < $pscore){
					$acscore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
					$acscore = ($acscore==-1) ? '-' : percent($acscore, $pfull);
					$data2[$acode] = array(
						'aname'=>$ainfo['name'],
						'pscore' => $apscore,
						'score' => $acscore,
						'i' => ($acscore=='-'||$apscore=='-') ? '-' :number_format($acscore - $apscore, 1), );
				}elseif($apscore > 85){
					$acscore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
					$acscore = ($acscore==-1) ? '-' : percent($acscore, $pfull);
					$data3[$acode] = array(
						'aname'=>$ainfo['name'],
						'pscore' => $apscore,
						'score' => $acscore,
						'i' => ($acscore=='-'||$apscore=='-') ? '-' :number_format($acscore - $apscore, 1), );
				}
			}
			$data2 = util_sort::bubbleSortOnly($data2, 'pscore'); //上期表现不佳 D
			$data2 = array_merge(array('all'=>array('aname'=>'该指标全国平均','pscore'=>$pscore, 'score'=>'-','i'=>'-')), $data2);
			$data3 = util_sort::bubbleSortOnly($data3, 'pscore'); //上期达标渠道
			$data3 = array_reverse($data3, true);
			$tables[] = array(
				'titles' => array('渠道', '上期得分率', '本期得分率', '变化情况'),
				'table_name' => '上期表现不佳的渠道',
				'datas' => $data2);
			$tables[] = array(
				'titles' => array('渠道', '上期得分率', '本期得分率', '变化情况'),
				'table_name' => '上期达标渠道本期表现',
				'datas' => $data3);
		}
		self::$tpl->assign('table_type', 'muilty_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('cbadqd', 'badreason_part', 'pbadqd')));
		self::$page_style .= '<style type="text/css">#tb_1 tbody td.dtd_1_0{text-align:left; padding-left:4px;} #dth_1_1{width:5em;}</style>';
		$this->display('report.tpl');
	}
}