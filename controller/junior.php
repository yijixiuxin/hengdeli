<?php
/**
 * 区域阅读报告
 */
class junior extends base{
	public $acode = null;
	public function __construct(){
		parent::__construct();
		if(isset($_REQUEST['a']) && $_REQUEST['a']!='index'){
			self::$location[] = array('title'=>'区域阅读报告', 'url'=>"c=junior");
			$navArrays = $this->navArrays(false);
			self::$tpl->assign('nav_arrays', $navArrays);
		}else{
			self::$location[] = array('title'=>'区域阅读报告', 'url'=>"c=junior");
			
			//高层回到首页的时候区域置为all
			if(self::$context['cuser']['acode']=='all'){
				model_session::Instance()->set('acode', 'all');
			}
		}
		
		if( self::$context['cuser']['acode']=='all' ){
			if($_REQUEST['acode'] ){
				model_session::Instance()->set('acode', $_REQUEST['acode']);
			}

			//左侧下拉
			$areas = model_area::Gets(); $carea = model_area::cacode();
			foreach ($areas as $k => $area) {
				if($carea == $area['code']) $areas[$k]['c'] = 1;
			}
			self::$tpl->assign('areas', $areas);
		}
		
		$this->acode = model_area::cacode();
		if($this->acode!='all') self::$location[] = array('title'=>model_area::getItem($this->acode.":name"), 'url'=>"c=junior&a=index&acode={$this->acode}");
		//$creportInfo = model_mendian::calcTypes(self::$context['cstage']['stage'], $this->acode);
		//self::$tpl->assign('creportInfo', $creportInfo);
		if($this->acode=='all' && $_GET['a']!='index'){
			header("location:index.php?c=junior&a=index");die;
		}
		
		if(strpos($_GET['a'],'report1')!==false){
			self::$location[] = array('title'=>'1.整体表现—区域与门店');
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
        self::$tpl->assign('x_acode', isset($_REQUEST['acode']) ? $_REQUEST['acode'] : '');
		self::$tpl->assign('page_type', 'junior');
	}

	public function nodata($msg = ''){
		$table_datas = empty($msg) ? array(array('暂无数据')) : array(array($msg));
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', array(' '));
		self::$tpl->assign('table_datas', $table_datas);
		$this->display('report.tpl');
	}
	
	public function index(){
		if($this->acode != 'all'){
			$table_datas = $this->navArrays();
			$areaname = model_area::getItem($this->acode.":name");
			//$table_datas[] = array('title'=>"9.排行榜", 'datas'=>array(array('title'=>"9.1{$areaname}区域门店排行榜", 'url'=>"index.php?c=junior&a=rank&acode={$this->acode}")));
			self::$tpl->assign('nav_arrays', $table_datas);
		}else{
			$table_datas = array();
			$temp = array('title'=>'区域阅读报告', 'datas'=>array());
			//$rank = array('title'=>"排行榜", 'datas'=>array());
			$areas = model_area::Gets();
			foreach ($areas as $ainfo) {
				$temp['datas'][] = array(
					'title'=>$ainfo['name'], 
					'url'=>"index.php?c=junior&a=index&acode={$ainfo['code']}");
				//$rank['datas'][] = array('title'=>"{$ainfo['name']}区域门店排行榜",'url'=>"index.php?c=junior&a=rank&acode={$ainfo['code']}");
			}
			//$table_datas = array($temp, $rank);
			$table_datas = array($temp);
		}
		self::$tpl->assign( 'table_type', 'dir' );
		self::$tpl->assign('tables', $table_datas);
		$this->display('report.tpl');
	}

	public function rank($dataonly=false){
		self::$location[] = array('title'=>'区域所有门店评分一览');
		$cstage = self::$context['cstage'];
		$datas = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');
		$datas = $datas[$this->acode]['total'];
		arsort($datas);//var_dump($datas);die;
		
		$table_datas = array();
		$rank = 1; $tempv = null; $t_rank=1;
		foreach ($datas as $mcode=>$mvalue) {
			$m_name = model_mendian::getItem($this->acode, "{$mcode}:name");
			if($mvalue!=$tempv) {$rank = $t_rank; $tempv = $mvalue;}
			$table_datas[] = array(
				"<a href='index.php?c=content&from=junior&mcode={$mcode}'>{$m_name}</a>",
				$rank, $mvalue, 
				"<a href='index.php?c=content&from=junior&mcode={$mcode}'>查看</a>");
			$t_rank ++;
		}
		$table_titles = array('门店', '排名', '本期得分', '查看详情'); //th中的标题
		if($dataonly){
			return array('name'=>'区域所有门店评分一览', 'titles'=>$table_titles, 'datas'=>$table_datas);
		}
		self::$tpl->assign('table_type', 'three_line');
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		
		$this->display('report.tpl');
	}

	private function navArrays($long=true){
		$table_datas = array();
		$level1 = model_nav::Gets(array('c'=>'junior', 'status'=>2), array('order'=>array('order'=>1)));
		if($level1){
			$temp = array('title'=>'1.整体表现—区域与门店', 'datas'=>array());
			foreach ($level1 as $nav) {
				$temp['datas'][] = array(
					'title'=>$nav['title'], 
					'url'=>"index.php?c=junior&a={$nav['a']}");
			}
			$table_datas[] = $temp;
		}

		$cstage = self::$context['cstage'];
		$groups = model_project::calcbleGroup($cstage['pid']); $tc = 2;
		if($groups){
			foreach ($groups as $gcode => $ginfo) {
				$tempTitle = empty($long) ? "{$tc}.{$ginfo['gname']}" : "{$tc}.模块表现-{$ginfo['gname']}";
				if($long==false){ $tempTitle = preg_replace('|（.*?）|', '', $tempTitle);}
				
				$table_datas[] = array(
					'title' => $tempTitle,
					'datas' => array(
						array('title'=>"{$tc}.1检测指标评分，及环比情况", 'url'=>"index.php?c=junior&a=report2_1&gcode={$gcode}"),
						array('title'=>"{$tc}.2区域内所有门店的评分分布", 'url'=>"index.php?c=junior&a=report2_2&gcode={$gcode}"),
						array('title'=>"{$tc}.3本期不佳门店解析、上期不佳及达标门店追踪", 'url'=>"index.php?c=junior&a=report2_3&gcode={$gcode}"),
					)
				);
				$tc ++;
			}
		}
		return $table_datas;
	}

	/**
	 * 1-各门店环比上期成绩
	 * 三线表输出
	 */
	public function report1_1(){
		self::$location[] = array('title'=>'1.1区域总体评分、各表行评分，及环比情况');
		self::$tpl->assign('table_name', '1.1区域总体评分、各表行评分，及环比情况');
		$cstage = self::$context['cstage'];
		$cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');// var_dump($cdata);die;
		$cdata = $cdata[$this->acode]['total']; //该区域下，所有门店总分的得分数组
		//var_dump($cdata);die;
		if(!$cdata){
			firelog($cdata, 'cdata byarea', __FILE__, __LINE__);
			exit('data error');
		}

		$table_titles = array('门店', '排名', '本期得分', '上期得分', '变化情况'); //th中的标题
		$table_datas = array();
		
		$cfull = model_calculate::getFull($cstage['pid'], 'total');//本期满分
		//本期全国平均
		$c_all_avg = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
		$c_all_avg = number_format($c_all_avg, 1);
		//本期区域平均
		$c_area_avg = util_report::score_of_acode($cstage['stage'], 'total', $this->acode);
		$c_area_avg = ($c_area_avg==-1) ? '-' :number_format($c_area_avg, 1);
		
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$pdata = model_statistics::get($pstage['pid'], $pstage['stage'], 'byarea'); //上期数据
			$pdata = $pdata[$this->acode]['total'];
			$pfull = model_calculate::getFull($pstage['pid'], 'total'); //上期满分
			foreach($cdata as $mcode => $value) {
				$table_datas[$mcode]['name'] = model_mendian::getItem($this->acode, "{$mcode}:name");
				$table_datas[$mcode]['rank'] = 1;//占位：第二位是排名
				$table_datas[$mcode]['c_defenlv'] = ($value==-1) ? '-' : round($value, 0);
				$table_datas[$mcode]['p_defenlv'] = ($pdata[$mcode]==-1) ? '-' : round($pdata[$mcode], 0);
				$table_datas[$mcode]['change'] = $table_datas[$mcode]['c_defenlv'] - $table_datas[$mcode]['p_defenlv'];
			}
			//上期全国平均
			$p_all_avg = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
			$p_all_avg = number_format($p_all_avg, 1);
			//上期区域平均
			$p_area_avg = util_report::score_of_acode($cstage['stage'], 'total', $this->acode);
			$p_area_avg = ($p_area_avg==-1) ? '-' :number_format($p_area_avg, 1);
		}else{
			foreach($cdata as $mcode => $value) {
				//$cScores = model_statistics::doArray($value['total'], $cfull);//求得该区域总分的平均分
				$table_datas[$mcode]['name'] = model_mendian::getItem($this->acode, "{$mcode}:name");
				$table_datas[$mcode]['rank'] = 1;//占位：第二位是排名
				$table_datas[$mcode]['c_defenlv'] = ($value==-1) ? '-' : round($value, 0);
				$table_datas[$mcode]['p_defenlv'] = '-';
				$table_datas[$mcode]['change'] = '-';
			}
			$p_all_avg = $p_area_avg = '-';
		}
		$table_datas = util_sort::bubbleSort($table_datas, 'c_defenlv');
		array_unshift($table_datas, 
						array('name'=>'全国平均', 'rank'=>'-', 'c_defenlv'=>$c_all_avg, 'p_defenlv'=>$p_all_avg, 'change'=>'-'),
						array('name'=>'区域平均', 'rank'=>'-', 'c_defenlv'=>$c_area_avg, 'p_defenlv'=>$p_area_avg, 'change'=>'-')
					);
		
		self::$tpl->assign('table_type', 'three_line');//一级环比用三线表
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		
		$this->display('report.tpl');
	}

	public function report1_2(){
		self::$location[] = array('title'=>'1.2区域所有门店的评分分布');
		self::$tpl->assign('table_name', '1.2区域所有门店评分一览');
		$cstage = self::$context['cstage'];
		$cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
		//firelog($cdata, 'cdata byqids', __FILE__, __LINE__);
		//var_dump($cdata);die;

		//计算本期分布、众数、中位数
		$cfull = model_calculate::getFull($cstage['pid'], 'total');
		$ctotals = $cdata['total'][$this->acode];
		$cAnalyse = model_statistics::analyseAll($ctotals, $cfull);
		self::$tpl->assign('c_analyse', $cAnalyse);
		
		//上期数据分析
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$pdata = model_statistics::get($pstage['pid'], $pstage['stage'], 'byqids');
			$pfull = model_calculate::getFull($cstage['pid'], 'total'); //上期满分
			$ptotals = $pdata['total'][$this->acode];
			$pAnalyse = model_statistics::analyseAll($ptotals, $pfull);
			$compare = array();
			foreach ($cAnalyse as $k => $v) {
				$compare[$k] = calcIncrease($v, $pAnalyse[$k]);
			}
			self::$tpl->assign('p_analyse', $pAnalyse);
			self::$tpl->assign('compare', $compare);
		}
		$rank = $this->rank(true);
		self::$tpl->assign('rank', $rank); 
		self::$tpl->assign('introInfos', util_report::introInfo(array('zs','zws','pingjuqb')));
		self::$tpl->assign('table_type', 'analyse'); //分布、平均分等数据分析
		$this->display('report.tpl');
	}

	//各门店模块的得分率
	public function report1_3(){
		self::$location[] = array('title'=>'1.3各模块评分情况');
		self::$tpl->assign('table_name', '1.3各模块评分情况');
		$cstage = self::$context['cstage'];
		$cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');
		$cdata = $cdata[$this->acode];
		if(!$cdata) return false;

		$table_titles = array('门店'); //th中的标题
		$table_datas = array();
		
		$project_info = model_project::projectInfo($cstage['pid']);
		$qids = $project_info['qids_array'];
		$mendians = model_mendian::getItem($this->acode);
		foreach ($qids as $gk => $gv) {
			if(!$gv['calcble']) continue;//不计分的题组跳过

			$gcode = $gv['gcode'];
			$table_titles[] = $gv['gname'];
			
			$full = model_calculate::getFull($cstage['pid'], $gcode);//本题组满分
			$all_avg = util_report::score_of_acodes($cstage['stage'], $gcode, 'all');
			$table_datas['all'][$gk] = percent($all_avg, $full); //全国平均
			
			$area_avg = util_report::score_of_acode($cstage['stage'], $gcode, $this->acode);
			$table_datas['area'][$gk] = percent($area_avg, $full); //区域平均
			foreach($mendians as $mcode => $value) {
				$table_datas[$mcode][$gk] = percent($cdata[$gcode][$mcode], $full);
			}
		}

		//处理acode
		foreach ($table_datas as $mcode => $data) {
			if($mcode=='area') $mname = '区域平均';
			elseif($mcode=='all') $mname = '全国平均';
			else $mname = model_mendian::getItem($this->acode, "{$mcode}:name"); //根据编码获取名称
			$table_datas[$mcode] = array_merge(array('name'=>$mname), $data);// array('name'=>$aname, 'data'=>$data);
		}
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', $table_titles);
		self::$tpl->assign('table_datas', $table_datas);
		$this->display('report.tpl');
	}

	public function report1_4(){
		self::$location[] = array('title'=>'1.4本期评分最低的10个检测指标');
		self::$tpl->assign('table_name', '1.4本期评分最低的10个检测指标');
		$cstage = self::$context['cstage'];
		
		$table_datas = util_report::second_character($cstage['stage']);
		
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
		self::$location[] = array('title'=>'1.5上期评分最低的10个检测指标追踪');
		self::$tpl->assign('table_name', '1.5上期评分最低的10个检测指标追踪');
		$cstage = self::$context['cstage'];
		$table_datas = util_report::second_character($cstage['stage']-1, 'last', 10, true);//var_dump($table_datas);die;

		if(!$table_datas){
			$table_datas = array(array('暂无数据', '-', '-', '-'));
		}else{
			foreach ($table_datas as $qcode => $data) {
				$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
				$c = util_report::score_of_mendians($cstage['stage'], $qcode, $this->acode); $c = ($c==-1)?'-':percent($c, $full);
				//$c = util_report::score_of_acodes($cstage['stage'], $qcode, $acodes='all'); $c = ($c==-1)?'-':percent($c, $full);
				//$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
				$table_datas[$qcode] = array(
					'question' => $data['q'],
					'p_defenlv' => $data['v'],
					'c_defenlv' => $c,
					'i' => calcIncrease($c, $data['v']));//number_format($i, 1));
			}
		}

		self::$tpl->assign('table_type', 'three_line');
		self::$tpl->assign('table_titles', array('服务细节', '上期得分率', '本期得分率', '变化情况'));
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('introInfos', util_report::introInfo(array('pduanban')));
		self::$page_style .= '<style type="text/css">tbody td.dtd_0{text-align:left; padding-left:4px;} #dth_1,#dth_2,#dth_3{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_6(){
		self::$location[] = array('title'=>'1.6本期表现不佳的门店解析(低于区域平均分)');
		$cstage = self::$context['cstage'];
		$cscore = util_report::score_of_acode($cstage['stage'], 'total', $this->acode);
		$cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

		$mendians = model_mendian::getItem($this->acode); $mcount = count($mendians);
		$data1 = $data2 = $data3 = array(); $certainMcode = array();
		foreach ($mendians as $mcode => $minfo) {
			$mscore = util_report::score_of_mendians($cstage['stage'], 'total', $this->acode, $mcode);
			$mscore = ($mscore=='-1') ? '-' : $mscore;
			if($mscore < $cscore){
				$data1[$mcode] = array(
					'mname'=>$minfo['name'],
					'rank' => 1,
					'score' => $mscore
					);
				$certainMcode[] = $mcode;
			}
		}
		if(empty($certainMcode)){
			$this->nodata('本期没有成绩低于平均分的门店');
		}

		/*$data1 = util_sort::bubbleSort($data1, 'score', 'A');
		foreach ($data1 as $k => $v) $data1[$k]['rank'] = $mcount - $v['rank']+1;
		$data1 = array_reverse($data1, true);*/
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $mcount-count($data1));
		
		//$data1 = array_merge(array(array('该区域平均', '-', $cscore)), $data1);
		$tables = array(
			array('titles'=>array( '门店', '排名', '本期得分'), 'table_name'=>'本期表现不佳的门店成绩', 'datas'=>array_merge(array(array('该区域平均', '-', $cscore)), $data1))
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['titles'] =array('门店');
		$data2['table_name'] = '模块表现';
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			$full = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($certainMcode as $mcode) {
				if(!isset($data2['datas'][$mcode]['mname']) || !$data2['datas'][$mcode]['mname']){
					$data2['datas'][$mcode] = array('mname'=> model_mendian::getItem($this->acode, "{$mcode}:name"));
				}
				$temp = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$data2['datas'][$mcode][$gcode] = $temp;
			}
		}
		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = $tempData2;
		$tables[] = $data2;

		$data3['table_name'] = '导致表现不佳的具体服务细节短板';
		$data3['titles'] = array( '服务细节','本期得分率');
		$data3['datas'] = util_report::second_character($cstage['stage'], $style="last",$top=10, $detail=false, $certainMcode);
		$tables[] = $data3;

		self::$tpl->assign('table_type', 'muilty_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('cbadbh', 'badreason_bh')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1{width:8em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_7(){
		self::$location[] = array('title'=>'1.7上期表现不佳的门店解析(低于区域平均分)');
		self::$tpl->assign('table_name', '1.7上期表现不佳的门店解析(低于区域平均分)');
		$cstage = self::$context['cstage'];
		$cscore = util_report::score_of_acode($cstage['stage'], 'total', $this->acode);
		$cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);
		
		$pscore = util_report::score_of_acode($cstage['stage']-1, 'total', $this->acode);
		if(-1 == $pscore){ $this->nodata('没有上期数据');}
		$pscore = ($pscore==-1) ? '-' : number_format($pscore, 1);

		$mendians = model_mendian::getItem($this->acode); $mcount = count($mendians);
		$data1 = $data2 = $data3 = array(); $certainMcode = array();
		foreach ($mendians as $mcode => $minfo) {
			$mpscore = util_report::score_of_mendians($cstage['stage']-1, 'total', $this->acode, $mcode); //该门店上期得分
			$mpscore = ($mpscore=='-1') ? '-' : $mpscore;
			if($mpscore < $pscore){
				$mcscore = util_report::score_of_mendians($cstage['stage'], 'total', $this->acode, $mcode);//该门店本期得分
				$mcscore = ($mcscore=='-1') ? '-' : $mcscore;
				$data1[$mcode] = array(
					'mname'=>$minfo['name'],
					'prank' => 1,
					'pscore' => $mpscore,
					'rank' => 1,
					'score' => $mcscore,
					);
				$certainMcode[] = $mcode;
			}
		}
		if(empty($certainMcode)){
			$this->nodata('上期没有成绩低于平均分的门店');
		}
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $mcount-count($data1));
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank', $mcount-count($data1));
		/*$data1 = util_sort::bubbleSort($data1, 'score', 'A', 'rank');
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'A', 'prank');
		foreach ($data1 as $k => $v) $data1[$k]['rank'] = $mcount - $v['rank']+1;
		foreach ($data1 as $k => $v) $data1[$k]['prank'] = $mcount - $v['prank']+1;
		$data1 = array_reverse($data1, true);*/

		//$data1 = array_merge(array(array('该区域平均', '-', $cscore, $pscore)), $data1);
		$tables = array(
			array('titles'=>array( '门店', '上期排名', '上期得分', '本期排名', '本期得分'), 'table_name'=>'跟踪该类门店在本期的总分表现','datas'=>array_merge(array(array('该区域平均', '-', $pscore, '-', $cscore)), $data1))
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['type'] = 'ext';
		$data2['table_name'] = '跟踪该类门店在本期的模块表现';
		$data2['titles'] =array('门店');
		$temp_title2 = array(''); //二级title
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			array_push($temp_title2, '上期', '本期');
			$full = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($certainMcode as $mcode) {
				if(!isset($data2['datas'][$mcode]['aname']) || !$data2['datas'][$mcode]['aname']){
					$data2['datas'][$mcode] = array('aname'=> model_mendian::getItem($this->acode, "{$mcode}:name"));
				}
				$temp = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);//门店本期得分
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$ptemp = util_report::score_of_mendians($cstage['stage']-1, $gcode, $this->acode, $mcode);
				$ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
				$data2['datas'][$mcode][] = $temp;
				$data2['datas'][$mcode][] = $ptemp;
			}
		}
		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = array_merge(array($temp_title2), $tempData2);
		$tables[] = $data2;

		$data3['table_name'] = '跟踪上期导致不佳的具体服务细节短板在本期改善情况';
		$data3['titles'] = array( '服务细节','上期得分率','本期得分率');
		$data3['datas'] = util_report::second_character($cstage['stage']-1, $style="last",$top=10, $detail=true, $certainMcode);
		foreach ($data3['datas'] as $qcode => $data) {
			$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
			$c = util_report::score_of_mendians($cstage['stage'], $qcode, $this->acode, $certainMcode); $c = ($c==-1)?'-':percent($c, $full); //该题门店本期得分
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
		self::$tpl->assign('introInfos', util_report::introInfo(array('pbadbh', 'badreason_bh_p')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1, #dth_2_2{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report1_8(){
		self::$location[] = array('title'=>'1.8上期达标门店追踪');
		$cstage = self::$context['cstage'];

		$mendians = model_mendian::getItem($this->acode); $mcount = count($mendians);
		$data1 = $data2 = $data3 = array(); $certainMcode = array();
		foreach ($mendians as $mcode => $info) {
			$mpscore = util_report::score_of_mendians($cstage['stage']-1, 'total', $this->acode, $mcode);  //该门店上期得分
			$mpscore = ($mpscore=='-1') ? '-' : $mpscore;
			if($mpscore > 85){
				$mcscore = util_report::score_of_mendians($cstage['stage'], 'total', $this->acode, $mcode);//该门店本期得分
				$mcscore = ($mcscore=='-1') ? '-' : $mcscore;
				$data1[$mcode] = array(
					'aname'=>$info['name'],
					'prank' => 1,
					'pscore' => $mpscore,
					'rank' => 1,
					'score' => $mcscore,
					'i' => calcIncrease($mcscore,$mpscore)//($mcscore=='-' || $mpscore=='-') ? '-' :($mcscore-$mpscore)
					);
				$certainMcode[] = $mcode;
			}
		}
		if(empty($certainMcode)){
			$this->nodata('上期没有得分高于85的门店');
		}
		$data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank');
		$data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank');
		$tables = array(
			array('titles'=>array( '门店', '上期排名', '上期得分', '本期排名', '本期得分', '变化情况'), 'table_name'=>'跟踪该类门店在本期的总分表现','datas'=>$data1)
		);

		$groups = model_project::calcbleGroup($cstage['pid']);
		$data2['type'] = 'ext';
		$data2['table_name'] = '跟踪该类门店在本期的模块表现';
		$data2['titles'] =array('门店');
		$temp_title2 = array(''); //二级title
		$data2['datas'] = array();
		foreach ($groups as $gcode => $ginfo) {
			$data2['titles'][] = $ginfo['gname'];
			array_push($temp_title2, '上期', '本期');
			$full = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($certainMcode as $mcode) {
				if(!isset($data2['datas'][$mcode]['aname']) || !$data2['datas'][$mcode]['aname']){
					$data2['datas'][$mcode] = array('aname'=> model_mendian::getItem($this->acode, "{$mcode}:name"));
				}
				$temp = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);//门店本期得分
				$temp = ($temp==-1) ? '-' : percent($temp, $full);
				$ptemp = util_report::score_of_mendians($cstage['stage']-1, $gcode, $this->acode, $mcode);
				$ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
				$data2['datas'][$mcode][] = $ptemp;
				$data2['datas'][$mcode][] = $temp;
			}
		}
		$tempData2 = array();//var_dump($certainAcode);die;
		foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['datas'][$acode];
		$data2['datas'] = array_merge(array($temp_title2), $tempData2);
		$tables[] = $data2;

		$data3['table_name'] = '跟踪上期支撑达标的服务细节亮点在本期的保持情况';
		$data3['titles'] = array( '服务细节', '上期得分率', '本期得分率', '变化情况');
		$data3['datas'] = util_report::second_character($cstage['stage']-1, $style="top",$top=10, $detail=true, $certainMcode);
		foreach ($data3['datas'] as $qcode => $data) {
			$full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
			$c = util_report::score_of_mendians($cstage['stage'], $qcode, $this->acode, $certainMcode); $c = ($c==-1)?'-':percent($c, $full); //该题门店本期得分
			//$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
			$data3['datas'][$qcode] = array(
				'question' => $data['q'],
				'p_defenlv' => $data['v'],
				'c_defenlv' => $c,
				'i' => calcIncrease($c,$data['v'])//$i
				);
		}
		$tables[] = $data3;

		self::$tpl->assign('table_type', 'compare_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('pgoodqd_part', 'pliangdian_bh')));
		self::$page_style .= '<style type="text/css">#tb_2 tbody td.dtd_2_0{text-align:left; padding-left:4px;} #dth_1_0, #dth_2_1, #dth_2_2, #dth_2_3{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report2_1(){
		$gcode = $_GET['gcode'];
		self::$location[] = array('title'=>"{$gcode}.1检测指标评分，及环比情况");
		self::$tpl->assign('table_name', "{$gcode}.1检测指标评分，及环比情况");

		$cstage = self::$context['cstage'];
		
		$questions = model_project::questions($cstage['pid'], $gcode);
		if(!$questions){
			firelog('no questions', "error gcode-{$gcode}", __FILE__, __LINE__, 'error');
			exit('error data');
		}
		
		$table_datas = array();
		foreach ($questions as $qid => $qinfo) {
			$full = model_calculate::getFull($cstage['pid'], "{$gcode}:{$qinfo['qid']}");
			$c = util_report::score_of_acode($cstage['stage'], "{$qinfo['sid']}X{$qinfo['qid']}", $this->acode); //区域本期得分
			$c = ($c==-1)?'-':percent($c, $full);
			$p = util_report::score_of_acode($cstage['stage']-1, "{$qinfo['sid']}X{$qinfo['qid']}", $this->acode);
			$p = ($p==-1)?'-':percent($p, $full);
			$i = calcIncrease($c,$p);//($c=='-'||$p=='-') ? '-' : ($c-$p);
			$table_datas[] = array($qinfo['question'], $c, $p, $i);
		}
		self::$tpl->assign('table_datas', $table_datas);
		self::$tpl->assign('table_type', 'three_line');//二级环比用三线表
		self::$tpl->assign('table_titles', array("检测指标", '本期得分率', '上期得分率', '变化情况'));
		self::$page_style .= '<style type="text/css">tbody td.dtd_0{text-align:left; padding-left:4px;} #dth_1, #dth_2, #dth_3{width:5em;}</style>';
		$this->display('report.tpl');
	}

	public function report2_2(){
		$gcode = $_GET['gcode'];
		self::$location[] = array('title'=>"{$gcode}.2区域内所有门店的评分分布");
		self::$tpl->assign('table_name', "{$gcode}.2区域内所有门店该模块的评分分布");
		$cstage = self::$context['cstage'];

		//$cscores = util_report::scoresArray($cstage['stage'], $gcode, 'all');
		$cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
		$cscores = $cdata[$gcode][$this->acode];
		if(!$cscores){
			firelog($cdata, "gcode:{$gcode} show all data", __FILE__, __LINE__);
			exit('gcode error');
		}
		$cfull = model_calculate::getFull($cstage['pid'], $gcode);
		$cAnalyse = model_statistics::analyseAll($cscores, $cfull);
		self::$tpl->assign('c_analyse', $cAnalyse);
		
		//上期数据分析
		$pstage = model_stage::getItem($cstage['stage']-1);
		if($pstage){
			$pdata = model_statistics::get($cstage['pid'], $cstage['stage']-1, 'byqids');
			//$pscores = util_report::scoresArray($pstage['stage'], $gcode, 'all');
			$pscores = $pdata[$gcode][$this->acode];
			$pfull = model_calculate::getFull($pstage['pid'], $gcode);
			$pAnalyse = model_statistics::analyseAll($pscores, $pfull);
			self::$tpl->assign('p_analyse', $pAnalyse);

			$compare = array();
			foreach ($cAnalyse as $k => $v) {
				$compare[$k] = calcIncrease($v, $pAnalyse[$k]);
			}
			self::$tpl->assign('compare', $compare);
		}
		
		self::$tpl->assign('introInfos', util_report::introInfo(array('zws', 'zs', 'pingjuqb')));
		self::$tpl->assign('table_type', 'analyse_percent'); //分布、平均分等数据分析
		$this->display('report.tpl');
	}

	public function report2_3(){
		$gcode = $_GET['gcode'];
		self::$location[] = array('title'=>"{$gcode}.3本期不佳门店解析、上期不佳及达标门店追踪");
		$cstage = self::$context['cstage'];
		
		$cscore = util_report::score_of_acode($cstage['stage'], $gcode, $this->acode); //区域本期得分
		if(!$cscore) exit('gcode error');
		$cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

		$mendians = model_mendian::getItem($this->acode); $mcount = count($mendians);
		$data1 = $data2 = $data3 = $data4 = array(); $certainMcode = array();
		foreach ($mendians as $mcode => $info) {
			$mscore = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);//门店本期得分
			$mscore = ($mscore==-1) ? '-' : $mscore;
			if($mscore < $cscore){
				$data1[$mcode] = array(
					'mname'=>$info['name'],
					'score' => $mscore
					);
				$certainMcode[] = $mcode;
			}
		}
		$data1 = array_merge(array(array('该区域平均', $cscore)), $data1);
		$tables = array(
			array('titles'=>array( '门店', '本期得分'), 'table_name'=>'本期表现不佳的门店成绩', 'datas'=>$data1)
		);

		//导致表现不佳的具体服务细节短板
		$tempdatas = array();
		$shortslab = util_report::second_character($cstage['stage'], 'last',999, true, $certainMcode);
		if($shortslab){
			foreach ($shortslab as $qcode => $info) {
				if($info['i']['gcode'] == $gcode){
					$tempdatas[] = array('q'=>$info['q'], 'v'=>$info['v']);
				}
				if(count($tempdatas)==3) break;
			}
		}
		$tables[] = array(
			'titles' => array( '服务细节','本期得分率' ),
			'table_name' => '导致表现不佳的具体服务细节短板',
			'datas' => $tempdatas);
		
		//上期表现不佳的门店
		$pstage = model_stage::getItem($cstage['stage']-1);
		if( $pstage ){
			$pscore = util_report::score_of_acode($pstage['stage'], $gcode, $this->acode); //区域上期得分
			$pfull = model_calculate::getFull($pstage['pid'], $gcode);
			$pscore = ($pscore==-1) ? '-' : percent($pscore, $pfull);

			$mendians = model_mendian::getItem($this->acode);
			$cfull = model_calculate::getFull($cstage['pid'], $gcode);
			foreach ($mendians as $mcode => $ainfo) {
				$mpscore = util_report::score_of_mendians($cstage['stage']-1, $gcode, $this->acode, $mcode);//门店上期得分
				$mpscore = ($mpscore==-1) ? '-' : percent($mpscore, $pfull);
				if(trim($mpscore,'%') < trim($pscore,'%')){
	//				$temppscore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
					$mcscore = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);//门店本期得分
					$mcscore = ($mcscore==-1) ? '-' : percent($mcscore, $cfull);
					$data2[$mcode] = array(
						'aname'=>$ainfo['name'],
						'pscore' => $mpscore,
						'score' => $mcscore,
						'i' => calcIncrease($mcscore, $mpscore));//($mcscore=='-'||$mpscore=='-') ? '-' :($mcscore - $mpscore) );
				}elseif(trim($mpscore,'%') > 85){
					$mcscore = util_report::score_of_mendians($cstage['stage'], $gcode, $this->acode, $mcode);//门店本期得分
					$mcscore = ($mcscore==-1) ? '-' : percent($mcscore, $cfull);
					$data3[$mcode] = array(
						'aname'=>$ainfo['name'],
						'pscore' => $mpscore,
						'score' => $mcscore,
						'i' => calcIncrease($mcscore, $mpscore));//($mcscore=='-'||$mpscore=='-') ? '-' : number_format($mcscore - $mpscore, 1), );
				}
			}
			$data2 = util_sort::bubbleSortOnly($data2, 'pscore'); //上期表现不佳 D
			$data2 = array_merge(array('all'=>array('aname'=>'该区域平均','pscore'=>$pscore, 'score'=>'-','i'=>'-')), $data2);
			$data3 = util_sort::bubbleSortOnly($data3, 'pscore'); //上期达标区域
			$data3 = array_reverse($data3, true);
			
			$tables[] = array(
				'titles' => array('门店', '上期得分率', '本期得分率', '变化情况'),
				'table_name' => '上期表现不佳的门店本期表现',
				'datas' => $data2);
			$tables[] = array(
				'titles' => array('门店', '上期得分率', '本期得分率', '变化情况'),
				'table_name' => '上期达标门店本期表现',
				'datas' => $data3);
		}
		self::$tpl->assign('table_type', 'muilty_three_line');
		self::$tpl->assign('tables', $tables);
		self::$tpl->assign('introInfos', util_report::introInfo(array('cbadbh', 'badreason_bh_part', 'pbadbh')));
		self::$page_style .= '<style type="text/css">#tb_1 tbody td.dtd_1_0{text-align:left; padding-left:4px;} #dth_1_1{width:5em;}</style>';
		$this->display('report.tpl');
	}
}