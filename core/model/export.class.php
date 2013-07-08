<?php
/**
 * 导出数据报表数据
 * Created by JetBrains PhpStorm.
 * User: WEIWEI
 * Date: 13-7-4
 * Time: 下午11:00
 * To change this template use File | Settings | File Templates.
 */

class model_export extends model_base {

    public static $acode = null;

    public function __construct() {
        parent::__construct();
    }

    /**
     * 高层报告
     */
    public static function g_report_1_1() {
        $tTitle = '1.整体表现—集团与区域';
        $tableName = '1.1集团总体评分、各区域评分，及环比情况';
        $cstage = base::$context['cstage'];
        $cfull = model_calculate::getFull($cstage['pid'], 'total');//本期满分
        $areas = model_area::getItem();
        $table_titles = array('区域', '本期排名', '本期得分', '上期得分', '变化情况'); //th中的标题
        $table_datas = array();

        $c_average = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
        $c_average = number_format($c_average, 1);

        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $p_average = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all'); //上期全国各区总分平均分
            $p_average = number_format($p_average, 1);
            $pfull = model_calculate::getFull($pstage['pid'], 'total'); //上期满分
            foreach ($areas as $acode=>$info) {
                $c = util_report::score_of_acode($cstage['stage'], 'total', $info['code']); $c = ($c==-1) ? '-' :number_format($c, 1);
                $p = util_report::score_of_acode($pstage['stage'], 'total', $info['code']); $p = ($p==-1) ? '-' :number_format($p, 1);
                //$i = ($c=='-' || $p=='-') ? '-' : $c - $p;
                $table_datas[$acode] = array(
                    'name' => $info['name'],
                    'rank' => 1,
                    'c' => $c,
                    'p' => $p,
                    'i' => calcIncrease($c, $p), //number_format($i, 1)
                );
            }

        }else{
            $p_average = '-';
            foreach ($areas as $info) {
                $acode = $info['code'];
                $c = util_report::score_of_acode($cstage['stage'], 'total', $info['code']); $c = ($c==-1) ? '-' :number_format($c, 1);
                $table_datas[$acode] = array(
                    'name' => $info['name'],
                    'rank' => 1,
                    'c' => $c,
                    'p' => '-',
                    'i' => '-');
            }
        }
        $table_datas = util_sort::bubbleSort($table_datas, 'c');

        $table_datas = array_merge(array(array('全国平均', '-', $c_average, $p_average,'-')), $table_datas);
        $tInfo = array();
        $tInfo['tTitle'] = $tTitle;
        $tInfo['name'] = $tableName;
        $tInfo['title'] = $table_titles;
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_1_2_1() {
        $titleName = '1.2集团所有门店评分一览';
        $cstage = base::$context['cstage'];

        $cscores = util_report::scoresArray($cstage['stage'], 'total', 'all');
        $cfull = model_calculate::getFull($cstage['pid'], 'total');
        $cAnalyse = model_statistics::analyseAll($cscores, $cfull);
        $tInfo['name'] = $titleName;
        $tInfo['title'] = array(
                array(0 => ' ', 'l' => 2),
                array(0 => '频次分布', 'l' => 2, 'c' => 4, 'cs' => array('[0,65分)', '[65分,75分)', '[75分,85分)', '[85分,100分)')),
                array(0 => '集团平均分', 'l' => 2),
                array(0 => '众数', 'l' => 2),
                array(0 => '中数', 'l' => 2)
        );
        @array_unshift($cAnalyse, '本期');
        $tInfo['data'] = array($cAnalyse);
        return $tInfo;


    }

    public static function g_report_1_2_2() {
        $tName = '集团所有门店评分一览';
        $cstage = base::$context['cstage'];

        //上期相关信息
        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $p_data = model_statistics::get($pstage['pid'], $pstage['stage'], 'byqids');
            $p_data = $p_data['total'];
        }
        $data = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
        $data = $data['total'];
        $finalData = array();
        foreach($data as $acode=>$ascores){
            if($acode=='0') continue;
            foreach($ascores as $mcode=>$mscore){
                if($pstage){$pdefen = $p_data[$acode][$mcode];}
                else $pdefen = '-';
                $info = model_mendian::Get($mcode);
                $finalData[$mcode] = array('defenlv'=>$mscore, 'prank'=>'-','pdefen'=>$pdefen, 'info'=>$info);
            }
        }
        unset($data);unset($p_data);
        $table_datas = array();
        if($pstage) $finalData = util_sort::bubbleSort($finalData, 'pdefen', $mode='D', 'prank');
        $finalData = util_sort::bubbleSort($finalData, 'defenlv', $mode='D');
        foreach($finalData as $minfo){
            $ainfo = model_area::getItem("{$minfo['info']['acode']}:name");
            $table_datas[] = array(
                'mendian'=> $minfo['info']['name'], 'ainfo'=>$ainfo,  'rank'=>$minfo['rank'],  'prank'=>$minfo['prank'], 'defenlv'=>$minfo['defenlv'],  'pdefen'=>$minfo['pdefen'],
            );
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('门店', '所在区域', '本期排名', '上期排名', '本期评分', '上期评分');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_1_3() {
        $tName = '1.3各模块评分情况';
        $cstage = base::$context['cstage'];

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

            $all_average = util_report::score_of_acodes($cstage['stage'], $gcode, $acodes='all');
            $table_datas['all'][$gk] = percent($all_average, $full); //全国平均
            foreach ($areas as $acode=>$info) {
                $temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
                $table_datas[$acode][$gk] = ($temp==-1) ? '-' : percent($temp, $full);
                $datasets[$gcode]['datas'][] = array('value'=>($temp==-1) ? 0 : percent($temp, $full));
            }
        }

        //处理acode
        foreach ($table_datas as $acode => $data) {
            $aname = ($acode=='all') ? '全国平均' : model_area::getItem("{$acode}:name"); //根据地区编码获取地区名称
            $categories[] = $aname;
            $table_datas[$acode] = array_merge(array('name'=>$aname), $data);// array('name'=>$aname, 'data'=>$data);
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('区域名称', '门店环境（14分）', '员工形象(针对门店所有销售顾问)（16分）', '欢迎和接近顾客（11分）', '了解需求与产品介绍（17分）', '产品展示与试戴（21分）', '回应异议（12分）', '礼貌道别（9分）');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_1_4() {
        $tName = '1.4本期评分最低的10个检测指标';
        $cstage = base::$context['cstage'];
        $table_datas = util_report::character($cstage['stage']);

        $tInfo['name'] = $tName;
        $tInfo['title'] = array('服务细节', '本期得分率');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_1_5() {
        $tName = '1.5上期评分最低的10个检测指标追踪';
        $cstage = base::$context['cstage'];
        $table_datas = util_report::character($cstage['stage']-1, 'last', 10, true);//var_dump($table_datas);die;

        if(!$table_datas){
            $table_datas = array();
        }else{
            foreach ($table_datas as $qcode => $data) {
                $full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
                $c = util_report::score_of_acodes($cstage['stage'], $qcode, $acodes='all'); $c = ($c==-1)?'-':percent($c, $full);
                //$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
                $table_datas[$qcode] = array(
                    'question' => $data['q'],
                    'p_defenlv' => $data['v'],
                    'c_defenlv' => $c,
                    'i' => calcIncrease($c, $data['v']),
                );
            }
            $table_datas = util_sort::bubbleSortOnly($table_datas, 'p_defenlv', 'A'); //按上期得分率升序
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('服务细节',  '上期得分率', '本期得分率','变化情况');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_1_6_1() {
        $tTitle = '1.6本期表现不佳的区域解析(低于集团平均分)';
        $tName = '本期表现不佳的区域成绩';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);

        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
            $ascore = percent($ascore, 100);
            if($ascore < $cscore){
                $data1[$acode] = array(  'aname'=>$ainfo['name'],'rank' => 1,'score' => $ascore);
                $certainAcode[] = $acode;
            }
        }

        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $acount-count($data1));
        $tables = array(
            array('titles'=>array( '区域', '排名', '本期得分率'), 'table_name'=>'本期表现不佳的区域成绩', 'datas'=>array_merge(array(array('全国平均', '-', $cscore)), $data1))
        );
        $tInfo['tTitle'] = $tTitle;
        $tInfo['name'] = $tName;
        $tInfo['title'] = $tables[0]['titles'];
        $tInfo['data'] = $tables[0]['datas'];
        return $tInfo;
    }

    public static function g_report_1_6_2() {
        $tName = '模块表现';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);
        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
            $ascore = percent($ascore, 100);
            if($ascore < $cscore){
                $data1[$acode] = array( 'aname'=>$ainfo['name'],'rank' => 1, 'score' => $ascore );
                $certainAcode[] = $acode;
            }
        }
        $groups = model_project::calcbleGroup($cstage['pid']);
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
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('区域', '门店环境（14分）', '员工形象(针对门店所有销售顾问)（16分）', '欢迎和接近顾客（11分）', '了解需求与产品介绍（17分）', '产品展示与试戴（21分）', '回应异议（12分）', '礼貌道别（9分）');
        $tInfo['data'] = $tempData2;
        return $tInfo;
    }

    public static function g_report_1_6_3() {
        $tName = '导致表现不佳的具体服务细节短板';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);
        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
            $ascore = percent($ascore, 100);
            if($ascore < $cscore){
                $data1[$acode] = array( 'aname'=>$ainfo['name'],'rank' => 1, 'score' => $ascore );
                $certainAcode[] = $acode;
            }
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array( '服务细节','本期得分率');
        $tInfo['data'] = util_report::character($cstage['stage'], $style="last",$top=10, $detail=false, $certainAcode);
        return $tInfo;
    }

    public static function g_report_1_7_1() {
        $tTitle = '1.7上期表现不佳的区域追踪(低于集团平均分) ';
        $tName = '跟踪该类区域在本期的总分表现';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);

        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');//上期全国各区总分平均分
        if(-1 == $pscore){
            return array('name' => $tName, 'title' => array(), 'data' => array());
        }
        $pscore = ($pscore==-1) ? '-' : percent($pscore, 100);

        $areas = model_area::getItem();  $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $apscore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //该区域上期得分
            $apscore = ($apscore==-1) ? '-' : percent($apscore, 100);
            if($apscore < $pscore){
                $acscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);//该区域本期得分
                $acscore = ($acscore==-1) ? '-' : percent($acscore, 100);
                $data1[$acode] = array(  'aname'=>$ainfo['name'], 'prank' => 1,  'pscore' => $apscore,  'rank' => 1,  'score' => $acscore, 'i' => calcIncrease($acscore,$apscore));
                $certainAcode[] = $acode;
            }
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $acount-count($data1));
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank', $acount-count($data1));

        $tables = array(
            array('titles'=>array( '区域', '上期排名', '上期得分率', '本期排名', '本期得分率', '变化情况'), 'table_name'=>'跟踪该类区域在本期的总分表现','datas'=>array_merge(array(array('全国平均', '-', $pscore, '-', $cscore, '-')), $data1))
        );
        $tInfo['tTitle'] = $tTitle;
        $tInfo['name'] = $tName;
        $tInfo['title'] = $tables[0]['titles'];
        $tInfo['data'] = $tables[0]['datas'];
        return $tInfo;
    }

    public static function g_report_1_7_2() {
        $tName = '跟踪该类区域在本期的模块表现';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);
        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');//上期全国各区总分平均分
        if(-1 == $pscore){  return array('name' => $tName, 'title' => array(), 'data' => array()); }
        $pscore = ($pscore==-1) ? '-' : percent($pscore, 100);
        $areas = model_area::getItem();  $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $apscore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //该区域上期得分
            $apscore = ($apscore==-1) ? '-' : percent($apscore, 100);
            if($apscore < $pscore){
                $acscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);//该区域本期得分
                $acscore = ($acscore==-1) ? '-' : percent($acscore, 100);
                $data1[$acode] = array(  'aname'=>$ainfo['name'], 'prank' => 1,  'pscore' => $apscore,  'rank' => 1,  'score' => $acscore, 'i' => calcIncrease($acscore,$apscore));
                $certainAcode[] = $acode;
            }
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $acount-count($data1));
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank', $acount-count($data1));
        $tables = array(array('titles'=>array( '区域', '上期排名', '上期得分率', '本期排名', '本期得分率', '变化情况'), 'table_name'=>'跟踪该类区域在本期的总分表现','datas'=>array_merge(array(array('全国平均', '-', $pscore, '-', $cscore, '-')), $data1)));
        $groups = model_project::calcbleGroup($cstage['pid']);
        $data2['type'] = 'ext';
        $data2['table_name'] = '跟踪该类区域在本期的模块表现';
        $data2['titles'] =array('区域');
        $temp_title2 = array(''); //二级title
        $data2['datas'] = array();
        foreach ($groups as $gcode => $ginfo) {
            $data2['titles'][] = $ginfo['gname'];
            array_push($temp_title2, '上期', '本期');
            $full = model_calculate::getFull($cstage['pid'], $gcode); //该题组满分，用于计算得分率
            foreach ($certainAcode as $acode) {
                if(!isset($data2['datas'][$acode]['aname']) || !$data2['datas'][$acode]['aname']){
                    $data2['datas'][$acode] = array('aname'=> model_area::getItem("{$acode}:name")); //第一列为区域名称
                }
                $temp = util_report::score_of_acode($cstage['stage'], $gcode, $acode); //本期该区域本题得分
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
        $tInfo['name'] = $tName;
        $tInfo['title'] = $data2['titles'];
        $tInfo['data'] = $data2['datas'];
        return $tInfo;
    }

    public static function g_report_1_7_3() {
        $tName = '跟踪上期导致不佳的具体服务细节短板在本期改善情况';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all'); //本期全国各区总分平均分
        $cscore = ($cscore==-1) ? '-' : percent($cscore, 100);
        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');//上期全国各区总分平均分
        if(-1 == $pscore){  return array('name' => $tName, 'title' => array(), 'data' => array()); }
        $pscore = ($pscore==-1) ? '-' : percent($pscore, 100);
        $areas = model_area::getItem();  $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $apscore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //该区域上期得分
            $apscore = ($apscore==-1) ? '-' : percent($apscore, 100);
            if($apscore < $pscore){
                $acscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);//该区域本期得分
                $acscore = ($acscore==-1) ? '-' : percent($acscore, 100);
                $data1[$acode] = array(  'aname'=>$ainfo['name'], 'prank' => 1,  'pscore' => $apscore,  'rank' => 1,  'score' => $acscore, 'i' => calcIncrease($acscore,$apscore));
                $certainAcode[] = $acode;
            }
        }
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
        $tInfo['name'] = $tName;
        $tInfo['title'] = $data3['titles'];
        $tInfo['datas'] = $data3['datas'];
        return $tInfo;
    }

    public static function g_report_1_8_1() {
        $tTitle = '1.8上期达标区域追踪 ';
        $tName = '跟踪该类区域在本期的总分表现';
        $cstage = base::$context['cstage'];

        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');
        if(-1 == $pscore){ return array('name' => $tName, 'title' => array(), 'data' => array());}

        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //pre
            $ascore = percent($ascore, 100);
            if($ascore > 85){
                $temppscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
                $temppscore = percent($temppscore, 100); //this
                $data1[$acode] = array('aname'=>$ainfo['name'],'prank' => 1,'pscore' => $ascore,'rank' => 1,'score' => $temppscore,'i' => calcIncrease($temppscore, $ascore) );
                $certainAcode[] = $acode;
            }
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank');
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank');
        $tables = array(
            array('titles'=>array( '区域', '上期排名', '上期得分率', '本期排名', '本期得分率', '变化情况'), 'table_name'=>'跟踪该类区域在本期的总分表现','datas'=>$data1)
        );
        $tInfo['tTitle'] = $tTitle;
        $tInfo['name'] = $tName;
        $tInfo['title'] = $tables[0]['titles'];
        $tInfo['data'] = $tables[0]['datas'];
        return $tInfo;
    }

    public static function g_report_1_8_2() {
        $tName = '跟踪该类区域在本期的模块表现';
        $cstage = base::$context['cstage'];
        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');
        if(-1 == $pscore){ return array();}
        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //pre
            $ascore = percent($ascore, 100);
            if($ascore > 85){
                $temppscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
                $temppscore = percent($temppscore, 100); //this
                $data1[$acode] = array('aname'=>$ainfo['name'],'prank' => 1,'pscore' => $ascore,'rank' => 1,'score' => $temppscore,'i' => calcIncrease($temppscore, $ascore) );
                $certainAcode[] = $acode;
            }
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank');
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank');
        $groups = model_project::calcbleGroup($cstage['pid']);
        $data2['table_name'] = '跟踪该类区域在本期的模块表现';
        $data2['titles'] =array('区域');
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
        $tInfo['name'] = $tName;
        $tInfo['title'] = $data2['titles'];
        $tInfo['data'] = $data2['datas'];
        return $tInfo;
        $tables[] = $data2;
    }

    public static function g_report_1_8_3() {
        $tName = '跟踪上期支撑达标的服务细节亮点在本期的保持情况';
        $cstage = base::$context['cstage'];
        $pscore = util_report::score_of_acodes($cstage['stage']-1, 'total', $acodes='all');
        if(-1 == $pscore){ return array();}
        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage']-1, 'total', $acode); //pre
            $ascore = percent($ascore, 100);
            if($ascore > 85){
                $temppscore = util_report::score_of_acode($cstage['stage'], 'total', $acode);
                $temppscore = percent($temppscore, 100); //this
                $data1[$acode] = array('aname'=>$ainfo['name'],'prank' => 1,'pscore' => $ascore,'rank' => 1,'score' => $temppscore,'i' => calcIncrease($temppscore, $ascore) );
                $certainAcode[] = $acode;
            }
        }
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
        $tInfo['name'] = $tName;
        $tInfo['title'] = $data3['titles'];
        $tInfo['data'] = $data3['datas'];
        return $tInfo;
    }

    public static function g_report_2_1($gcode = '2') {
        switch ($gcode) {
            case '2':
                $tTitle = '2.模块表现-门店环境（14分）';
                break;
            case '3':
                $tTitle = '3.模块表现-员工形象(针对门店所有销售顾问)（16分）';
                break;
            case '4':
                $tTitle = '4.模块表现-欢迎和接近顾客（11分）';
                break;
            case '5':
                $tTitle = '5.模块表现-了解需求与产品介绍（17分）';
                break;
            case '6':
                $tTitle = '6.模块表现-产品展示与试戴（21分）';
                break;
            case '7':
                $tTitle = '7.模块表现-回应异议（12分）';
                break;
            case '8':
                $tTitle = '8.模块表现-礼貌道别（9分）';
                break;
        }
        $tName = $gcode.'.1检测指标评分，及环比情况';
        $cstage = base::$context['cstage'];
        $questions = model_project::questions($cstage['pid'], $gcode);
        if(!$questions){
            return array('name' => $tName, 'tTitle' => $tTitle, 'title' => array(), 'data' => array());
        }
        $table_datas = array();
        foreach ($questions as $qid => $qinfo) {
            $full = model_calculate::getFull($cstage['pid'], "{$gcode}:{$qinfo['qid']}");
            $c = util_report::score_of_acodes($cstage['stage'], "{$qinfo['sid']}X{$qinfo['qid']}", 'all'); //获取该题所有区域平均分
            $c = ($c==-1)?'-':percent($c, $full);
            $p = util_report::score_of_acodes($cstage['stage']-1, "{$qinfo['sid']}X{$qinfo['qid']}", 'all');
            $p = ($p==-1)?'-':percent($p, $full);
            $i = calcIncrease($c,$p); //($c=='-'||$p=='-') ? '-' : number_format($c-$p, 1);
            $table_datas[] = array($qinfo['question'], $c, $p, $i);
        }
        $tInfo['name'] = $tName;
        $tInfo['tTitle'] = $tTitle;
        $tInfo['title'] = array("检测指标", '本期得分率', '上期得分率', '变化情况');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function g_report_2_2($gcode = '2') {
        $tName = $gcode.'.2所有门店的评分分布';
        $cstage = base::$context['cstage'];
        $cscores = util_report::scoresArray($cstage['stage'], $gcode, 'all');
        if(!$cscores) return array('name' => $tName, 'title' => array(), 'data' => array());
        $cfull = model_calculate::getFull($cstage['pid'], $gcode);
        $cAnalyse = model_statistics::analyseAll($cscores, $cfull);
        //上期数据分析
        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $pscores = util_report::scoresArray($pstage['stage'], $gcode, 'all');
            $pfull = model_calculate::getFull($pstage['pid'], $gcode);
            $pAnalyse = model_statistics::analyseAll($pscores, $pfull);
            foreach ($cAnalyse as $k => $v) {
                $compare[$k] = calcIncrease($v, $pAnalyse[$k]);//$v - $pAnalyse[$k];
            }
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array(
            array(0 => '', 'l' => 2),
            array(0 => '频次分布', 'l' => 2, 'c' => 4, 'cs' => array('[0,65分)', '[65分,75分)', '[75分,85分)', '[85分,100分)')),
            array(0 => '集团平均成绩', 'l' => 2),
            array(0 => '众数', 'l' => 2),
            array(0 => '中数', 'l' => 2)
        );
        @array_unshift($cAnalyse, '本期');
        @array_unshift($pAnalyse, '上期');
        @array_unshift($compare, '变化情况');
        $tInfo['data'] = array($cAnalyse, $pAnalyse, $compare);
        return $tInfo;
    }

    public static function g_report_2_3($gcode = 2) {
        $tName = $gcode.'.3高层需重点关注的区域';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acodes($cstage['stage'], $gcode, $acodes='all'); if(!$cscore) exit('gcode error');
        $cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

        $areas = model_area::getItem(); $acount = count($areas);
        $data1 = $data2 = $data3 = $data4 = array(); $certainAcode = array();
        foreach ($areas as $acode => $ainfo) {
            $ascore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
            $ascore = number_format($ascore, 1);
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
            array('title'=>array( '区域', '本期得分'), 'name'=>'本期表现不佳的区域成绩', 'data'=>$data1)
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
            'title' => array( '服务细节','本期得分率' ),
            'name' => '导致表现不佳的具体服务细节短板',
            'data' => $tempdatas);

        //上期表现不佳的区域
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
                        'i' => calcIncrease($acscore, $apscore),);//($acscore=='-'||$apscore=='-') ? '-' :number_format($acscore - $apscore, 1), );
                }elseif($apscore > 85){
                    $acscore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
                    $acscore = ($acscore==-1) ? '-' : percent($acscore, $pfull);
                    $data3[$acode] = array(
                        'aname'=>$ainfo['name'],
                        'pscore' => $apscore,
                        'score' => $acscore,
                        'i' => calcIncrease($acscore, $apscore),);//($acscore=='-'||$apscore=='-') ? '-' :number_format($acscore - $apscore, 1), );
                }
            }
            $data2 = util_sort::bubbleSortOnly($data2, 'pscore'); //上期表现不佳 D
            $data2 = array_merge(array('all'=>array('aname'=>'该指标全国平均','pscore'=>$pscore, 'score'=>'-','i'=>'-')), $data2);
            $data3 = util_sort::bubbleSortOnly($data3, 'pscore'); //上期达标区域
            $data3 = array_reverse($data3, true);
            $tables[] = array(
                'title' => array('区域', '上期得分率', '本期得分率', '变化情况'),
                'name' => '上期表现不佳的区域',
                'data' => $data2);
            $tables[] = array(
                'title' => array('区域', '上期得分率', '本期得分率', '变化情况'),
                'name' => '上期达标区域本期表现',
                'data' => $data3);
        }
        return $tables;
    }

    public static function set_acode($acode) {
        self::$acode = $acode;
    }

    //区域报表下载
    public static function j_report_1_1() {
        $tName = '1.1区域总体评分、各表行评分，及环比情况';
        $cstage = base::$context['cstage'];
        $cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');// var_dump($cdata);die;
        $cdata = $cdata[self::$acode]['total']; //该区域下，所有门店总分的得分数组
        //var_dump($cdata);die;
        if(!$cdata){
            firelog($cdata, 'cdata byarea', __FILE__, __LINE__);
            return array('name' => $tName, 'title' => '', 'data' => '');
        }

        $table_titles = array('门店', '排名', '本期得分', '上期得分', '变化情况'); //th中的标题
        $table_datas = array();

        $cfull = model_calculate::getFull($cstage['pid'], 'total');//本期满分
        //本期全国平均
        $c_all_avg = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
        $c_all_avg = number_format($c_all_avg, 1);
        //本期区域平均
        $c_area_avg = util_report::score_of_acode($cstage['stage'], 'total', self::$acode);
        $c_area_avg = ($c_area_avg==-1) ? '-' :number_format($c_area_avg, 1);

        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $pdata = model_statistics::get($pstage['pid'], $pstage['stage'], 'byarea'); //上期数据
            $pdata = $pdata[self::$acode]['total'];
            $pfull = model_calculate::getFull($pstage['pid'], 'total'); //上期满分
            foreach($cdata as $mcode => $value) {
                $table_datas[$mcode]['name'] = model_mendian::getItem(self::$acode, "{$mcode}:name");
                $table_datas[$mcode]['rank'] = 1;//占位：第二位是排名
                $table_datas[$mcode]['c_defenlv'] = ($value==-1) ? '-' : round($value, 0);
                $table_datas[$mcode]['p_defenlv'] = ($pdata[$mcode]==-1) ? '-' : round($pdata[$mcode], 0);
                $table_datas[$mcode]['change'] = $table_datas[$mcode]['c_defenlv'] - $table_datas[$mcode]['p_defenlv'];
            }
            //上期全国平均
            $p_all_avg = util_report::score_of_acodes($cstage['stage'], 'total', $acodes='all');
            $p_all_avg = number_format($p_all_avg, 1);
            //上期区域平均
            $p_area_avg = util_report::score_of_acode($cstage['stage'], 'total', self::$acode);
            $p_area_avg = ($p_area_avg==-1) ? '-' :number_format($p_area_avg, 1);
        }else{
            foreach($cdata as $mcode => $value) {
                //$cScores = model_statistics::doArray($value['total'], $cfull);//求得该区域总分的平均分
                $table_datas[$mcode]['name'] = model_mendian::getItem(self::$acode, "{$mcode}:name");
                $table_datas[$mcode]['rank'] = 1;//占位：第二位是排名
                $table_datas[$mcode]['c_defenlv'] = ($value==-1) ? '-' : round($value, 0);
                $table_datas[$mcode]['p_defenlv'] = '-';
                $table_datas[$mcode]['change'] = '-';
            }
            $p_all_avg = $p_area_avg = '-';
        }
        $table_datas = util_sort::bubbleSort($table_datas, 'c_defenlv');
        @array_unshift($table_datas,
            array('name'=>'全国平均', 'rank'=>'-', 'c_defenlv'=>$c_all_avg, 'p_defenlv'=>$p_all_avg, 'change'=>'-'),
            array('name'=>'区域平均', 'rank'=>'-', 'c_defenlv'=>$c_area_avg, 'p_defenlv'=>$p_area_avg, 'change'=>'-')
        );
        $tInfo['name'] = $tName;
        $tInfo['title'] = $table_titles;
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function j_report_1_2() {
        $tName = '1.2区域所有门店评分一览';
        $cstage = base::$context['cstage'];
        $cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
        //firelog($cdata, 'cdata byqids', __FILE__, __LINE__);
        //var_dump($cdata);die;

        //计算本期分布、众数、中位数
        $cfull = model_calculate::getFull($cstage['pid'], 'total');
        $ctotals = $cdata['total'][self::$acode];
        $cAnalyse = model_statistics::analyseAll($ctotals, $cfull);

        //上期数据分析
        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $pdata = model_statistics::get($pstage['pid'], $pstage['stage'], 'byqids');
            $pfull = model_calculate::getFull($cstage['pid'], 'total'); //上期满分
            $ptotals = $pdata['total'][self::$acode];
            $pAnalyse = model_statistics::analyseAll($ptotals, $pfull);
            $compare = array();
            foreach ($cAnalyse as $k => $v) {
                $compare[$k] = calcIncrease($v, $pAnalyse[$k]);
            }
        }
        @array_unshift($cAnalyse, '本期');
        @array_unshift($pAnalyse, '上期');
        @array_unshift($compare, '变化情况');
        $tables = array();
        $tInfo['name'] = $tName;
        $tInfo['title'] = array(
            array(0 => '', 'l' => 2),
            array(0 => '频次分布', 'l' => 2, 'c' => 4, 'cs' => array('[0,65分)', '[65分,75分)', '[75分,85分)', '[85分,100分)')),
            array(0 => '区域平均成绩', 'l' => 2),
            array(0 => '众数', 'l' => 2),
            array(0 => '中数', 'l' => 2)
        );
        $tInfo['data'] = array($cAnalyse, $pAnalyse, $compare);
        $rank = self::j_rank(true);
        $tables[] = $tInfo;
        $tables[] = $rank;
        return $tables;
    }

    public static function j_rank() {
        $tName = '区域所有门店评分一览';
        $cstage = base::$context['cstage'];
        $datas = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');
        $datas = $datas[self::$acode]['total'];
        arsort($datas);//var_dump($datas);die;
        $table_datas = array();
        $rank = 1; $tempv = null; $t_rank=1;
        foreach ($datas as $mcode=>$mvalue) {
            $m_name = model_mendian::getItem(self::$acode, "{$mcode}:name");
            if($mvalue!=$tempv) {$rank = $t_rank; $tempv = $mvalue;}
            $table_datas[] = array($m_name,$rank, $mvalue);
            $t_rank ++;
        }
        $table_titles = array('门店', '排名', '本期得分'); //th中的标题
        return array('name'=>$tName, 'title' => $table_titles, 'data' => $table_datas);
    }

    public static function j_report_1_3() {
        $tName = '1.3各模块评分情况';
        $cstage = base::$context['cstage'];
        $cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byarea');
        $cdata = $cdata[self::$acode];
        if(!$cdata) return array('name' => $tName, 'title' => array(), 'data' => array());

        $table_titles = array('门店'); //th中的标题
        $table_datas = array();

        $project_info = model_project::projectInfo($cstage['pid']);
        $qids = $project_info['qids_array'];
        $mendians = model_mendian::getItem(self::$acode);
        foreach ($qids as $gk => $gv) {
            if(!$gv['calcble']) continue;//不计分的题组跳过
            $gcode = $gv['gcode'];
            $table_titles[] = $gv['gname'];
            $full = model_calculate::getFull($cstage['pid'], $gcode);//本题组满分
            $all_avg = util_report::score_of_acodes($cstage['stage'], $gcode, 'all');
            $table_datas['all'][$gk] = percent($all_avg, $full); //全国平均
            $area_avg = util_report::score_of_acode($cstage['stage'], $gcode, self::$acode);
            $table_datas['area'][$gk] = percent($area_avg, $full); //区域平均
            foreach($mendians as $mcode => $value) {
                $table_datas[$mcode][$gk] = percent($cdata[$gcode][$mcode], $full);
            }
        }
        //处理acode
        foreach ($table_datas as $mcode => $data) {
            if($mcode=='area') $mname = '区域平均';
            elseif($mcode=='all') $mname = '全国平均';
            else $mname = model_mendian::getItem(self::$acode, "{$mcode}:name"); //根据编码获取名称
            $table_datas[$mcode] = array_merge(array('name'=>$mname), $data);// array('name'=>$aname, 'data'=>$data);
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = $table_titles;
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function j_report_1_4() {
        $tName = '1.4本期评分最低的10个检测指标';
        $cstage = base::$context['cstage'];
        $table_datas = util_report::second_character($cstage['stage']);
        if(!$table_datas){
            $table_datas = array();
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('服务细节', '本期得分率');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function j_report_1_5() {
        $tName = '1.5上期评分最低的10个检测指标追踪';
        $cstage = base::$context['cstage'];
        $table_datas = util_report::second_character($cstage['stage']-1, 'last', 10, true);//var_dump($table_datas);die;
        if(!$table_datas){
            $table_datas = array();
        }else{
            foreach ($table_datas as $qcode => $data) {
                $full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
                $c = util_report::score_of_mendians($cstage['stage'], $qcode, self::$acode); $c = ($c==-1)?'-':percent($c, $full);
                $table_datas[$qcode] = array(
                    'question' => $data['q'],
                    'p_defenlv' => $data['v'],
                    'c_defenlv' => $c,
                    'i' => calcIncrease($c, $data['v']));//number_format($i, 1));
            }
        }
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('服务细节', '上期得分率', '本期得分率', '变化情况');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function j_report_1_6() {
        $tTitle = '1.6本期表现不佳的门店解析(低于区域平均分)';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acode($cstage['stage'], 'total', self::$acode);
        $cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

        $mendians = model_mendian::getItem(self::$acode); $mcount = count($mendians);
        $data1 = $data2 = $data3 = array(); $certainMcode = array();
        foreach ($mendians as $mcode => $minfo) {
            $mscore = util_report::score_of_mendians($cstage['stage'], 'total', self::$acode, $mcode);
            $mscore = ($mscore=='-1') ? '-' : $mscore;
            if($mscore < $cscore){
                $data1[$mcode] = array('mname'=>$minfo['name'],'rank' => 1,'score' => $mscore);
                $certainMcode[] = $mcode;
            }
        }
        if(empty($certainMcode)){
            return array();
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $mcount-count($data1));
        $tables = array(
            array('tTitle' => $tTitle, 'title'=>array( '门店', '排名', '本期得分'), 'name'=>'本期表现不佳的门店成绩', 'data'=>array_merge(array(array('该区域平均', '-', $cscore)), $data1))
        );
        $groups = model_project::calcbleGroup($cstage['pid']);
        $data2['title'] =array('门店');
        $data2['name'] = '模块表现';
        $data2['data'] = array();
        foreach ($groups as $gcode => $ginfo) {
            $data2['title'][] = $ginfo['gname'];
            $full = model_calculate::getFull($cstage['pid'], $gcode);
            foreach ($certainMcode as $mcode) {
                if(!isset($data2['data'][$mcode]['mname']) || !$data2['data'][$mcode]['mname']){
                    $data2['data'][$mcode] = array('mname'=> model_mendian::getItem(self::$acode, "{$mcode}:name"));
                }
                $temp = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);
                $temp = ($temp==-1) ? '-' : percent($temp, $full);
                $data2['data'][$mcode][$gcode] = $temp;
            }
        }
        $tempData2 = array();
        foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['data'][$acode];
        $data2['data'] = $tempData2;
        $tables[] = $data2;

        $data3['name'] = '导致表现不佳的具体服务细节短板';
        $data3['title'] = array( '服务细节','本期得分率');
        $data3['data'] = util_report::second_character($cstage['stage'], $style="last",$top=10, $detail=false, $certainMcode);
        $tables[] = $data3;
        return $tables;
    }

    public static function j_report_1_7() {
        $tTitle = '1.7上期表现不佳的门店解析(低于区域平均分)';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acode($cstage['stage'], 'total', self::$acode);
        $cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

        $pscore = util_report::score_of_acode($cstage['stage']-1, 'total', self::$acode);
        if(-1 == $pscore){ return array('tTitle' => $tTitle, 'name' => $tTitle, 'title' => array(), 'data' => array());}
        $pscore = ($pscore==-1) ? '-' : number_format($pscore, 1);

        $mendians = model_mendian::getItem(self::$acode); $mcount = count($mendians);
        $data1 = $data2 = $data3 = array(); $certainMcode = array();
        foreach ($mendians as $mcode => $minfo) {
            $mpscore = util_report::score_of_mendians($cstage['stage']-1, 'total', self::$acode, $mcode); //该门店上期得分
            $mpscore = ($mpscore=='-1') ? '-' : $mpscore;
            if($mpscore < $pscore){
                $mcscore = util_report::score_of_mendians($cstage['stage'], 'total', self::$acode, $mcode);//该门店本期得分
                $mcscore = ($mcscore=='-1') ? '-' : $mcscore;
                $data1[$mcode] = array(  'mname'=>$minfo['name'],  'prank' => 1,  'pscore' => $mpscore, 'rank' => 1,  'score' => $mcscore, );
                $certainMcode[] = $mcode;
            }
        }
        if(empty($certainMcode)){
            return array('tTitle' => $tTitle, 'name' => $tTitle, 'title' => array(), 'data' => array());
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank', $mcount-count($data1));
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank', $mcount-count($data1));

        $tables = array(
            array('tTitle' => $tTitle, 'title'=>array( '门店', '上期排名', '上期得分', '本期排名', '本期得分'), 'name'=>'跟踪该类门店在本期的总分表现','data'=>array_merge(array(array('该区域平均', '-', $pscore, '-', $cscore)), $data1))
        );

        $groups = model_project::calcbleGroup($cstage['pid']);
        $data2['type'] = 'ext';
        $data2['name'] = '跟踪该类门店在本期的模块表现';
        $data2['title'] =array('门店');
        $temp_title2 = array(''); //二级title
        $data2['data'] = array();
        foreach ($groups as $gcode => $ginfo) {
            $data2['title'][] = $ginfo['gname'];
            array_push($temp_title2, '上期', '本期');
            $full = model_calculate::getFull($cstage['pid'], $gcode);
            foreach ($certainMcode as $mcode) {
                if(!isset($data2['data'][$mcode]['aname']) || !$data2['data'][$mcode]['aname']){
                    $data2['data'][$mcode] = array('aname'=> model_mendian::getItem(self::$acode, "{$mcode}:name"));
                }
                $temp = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);//门店本期得分
                $temp = ($temp==-1) ? '-' : percent($temp, $full);
                $ptemp = util_report::score_of_mendians($cstage['stage']-1, $gcode, self::$acode, $mcode);
                $ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
                $data2['data'][$mcode][] = $temp;
                $data2['data'][$mcode][] = $ptemp;
            }
        }
        $tempData2 = array();//var_dump($certainAcode);die;
        foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['data'][$acode];
        $data2['data'] = array_merge(array($temp_title2), $tempData2);
        $tables[] = $data2;

        $data3['name'] = '跟踪上期导致不佳的具体服务细节短板在本期改善情况';
        $data3['title'] = array( '服务细节','上期得分率','本期得分率');
        $data3['data'] = util_report::second_character($cstage['stage']-1, $style="last",$top=10, $detail=true, $certainMcode);
        foreach ($data3['data'] as $qcode => $data) {
            $full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
            $c = util_report::score_of_mendians($cstage['stage'], $qcode, self::$acode, $certainMcode); $c = ($c==-1)?'-':percent($c, $full); //该题门店本期得分
            $data3['data'][$qcode] = array( 'question' => $data['q'],  'p_defenlv' => $data['v'],  'c_defenlv' => $c );
        }
        $tables[] = $data3;
        return $tables;
    }

    public static function j_report_1_8() {
        $tTitle = '1.8上期达标门店追踪 ';
        $cstage = base::$context['cstage'];

        $mendians = model_mendian::getItem(self::$acode); $mcount = count($mendians);
        $data1 = $data2 = $data3 = array(); $certainMcode = array();
        foreach ($mendians as $mcode => $info) {
            $mpscore = util_report::score_of_mendians($cstage['stage']-1, 'total', self::$acode, $mcode);  //该门店上期得分
            $mpscore = ($mpscore=='-1') ? '-' : $mpscore;
            if($mpscore > 85){
                $mcscore = util_report::score_of_mendians($cstage['stage'], 'total', self::$acode, $mcode);//该门店本期得分
                $mcscore = ($mcscore=='-1') ? '-' : $mcscore;
                $data1[$mcode] = array( 'aname'=>$info['name'], 'prank' => 1, 'pscore' => $mpscore,  'rank' => 1,  'score' => $mcscore,
                    'i' => calcIncrease($mcscore,$mpscore) );
                $certainMcode[] = $mcode;
            }
        }
        if(empty($certainMcode)){
            return array('tTitle' => $tTitle, 'name' => $tTitle, 'title' => array(), 'data' => array());
        }
        $data1 = util_sort::bubbleSort($data1, 'score', 'D', 'rank');
        $data1 = util_sort::bubbleSort($data1, 'pscore', 'D', 'prank');
        $tables = array(
            array('tTitle' => $tTitle, 'title'=>array( '门店', '上期排名', '上期得分', '本期排名', '本期得分', '变化情况'), 'name'=>'跟踪该类门店在本期的总分表现','data'=>$data1)
        );

        $groups = model_project::calcbleGroup($cstage['pid']);
        $data2['type'] = 'ext';
        $data2['name'] = '跟踪该类门店在本期的模块表现';
        $data2['title'] =array('门店');
        $temp_title2 = array(''); //二级title
        $data2['data'] = array();
        foreach ($groups as $gcode => $ginfo) {
            $data2['title'][] = $ginfo['gname'];
            array_push($temp_title2, '上期', '本期');
            $full = model_calculate::getFull($cstage['pid'], $gcode);
            foreach ($certainMcode as $mcode) {
                if(!isset($data2['data'][$mcode]['aname']) || !$data2['data'][$mcode]['aname']){
                    $data2['data'][$mcode] = array('aname'=> model_mendian::getItem(self::$acode, "{$mcode}:name"));
                }
                $temp = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);//门店本期得分
                $temp = ($temp==-1) ? '-' : percent($temp, $full);
                $ptemp = util_report::score_of_mendians($cstage['stage']-1, $gcode, self::$acode, $mcode);
                $ptemp = ($ptemp==-1) ? '-' : percent($ptemp, $full);
                $data2['data'][$mcode][] = $ptemp;
                $data2['data'][$mcode][] = $temp;
            }
        }
        $tempData2 = array();//var_dump($certainAcode);die;
        foreach ($data1 as $acode=>$ainfo) $tempData2[$acode] = $data2['data'][$acode];
        $data2['data'] = array_merge(array($temp_title2), $tempData2);
        $tables[] = $data2;

        $data3['name'] = '跟踪上期支撑达标的服务细节亮点在本期的保持情况';
        $data3['title'] = array( '服务细节', '上期得分率', '本期得分率', '变化情况');
        $data3['data'] = util_report::second_character($cstage['stage']-1, $style="top",$top=10, $detail=true, $certainMcode);
        foreach ($data3['data'] as $qcode => $data) {
            $full = model_calculate::getFull($cstage['pid'], "{$data['i']['gcode']}:{$data['i']['qid']}");
            $c = util_report::score_of_mendians($cstage['stage'], $qcode, self::$acode, $certainMcode); $c = ($c==-1)?'-':percent($c, $full); //该题门店本期得分
            //$i = ($c=='-' || $data['q']=='-') ? '-' : ($c - $data['v']);
            $data3['data'][$qcode] = array( 'question' => $data['q'], 'p_defenlv' => $data['v'],  'c_defenlv' => $c,
                'i' => calcIncrease($c,$data['v']));
        }
        $tables[] = $data3;
        return $tables;
    }

    public static function j_report_2_1($gcode = '2') {
        switch($gcode) {
            case '2' :
                $tTitle = '2.模块表现-门店环境（14分）';
                break;
            case '3' :
                $tTitle = '3.模块表现-员工形象(针对门店所有销售顾问)（16分）';
                break;
            case '4' :
                $tTitle = '4.模块表现-欢迎和接近顾客（11分）';
                break;
            case '5' :
                $tTitle = '5.模块表现-了解需求与产品介绍（17分）';
                break;
            case '6' :
                $tTitle = '6.模块表现-产品展示与试戴（21分）';
                break;
            case '7' :
                $tTitle = '7.模块表现-回应异议（12分）';
                break;
            case '8' :
                $tTitle = '8.模块表现-礼貌道别（9分）';
                break;
        }
        $tName = $gcode.'.1检测指标评分，及环比情况';
        $cstage = base::$context['cstage'];
        $questions = model_project::questions($cstage['pid'], $gcode);
        if(!$questions){
            return array('tTitle' => $tTitle, 'name' => $tName, 'title' => array(), 'data' => array());
        }

        $table_datas = array();
        foreach ($questions as $qid => $qinfo) {
            $full = model_calculate::getFull($cstage['pid'], "{$gcode}:{$qinfo['qid']}");
            $c = util_report::score_of_acode($cstage['stage'], "{$qinfo['sid']}X{$qinfo['qid']}", self::$acode); //区域本期得分
            $c = ($c==-1)?'-':percent($c, $full);
            $p = util_report::score_of_acode($cstage['stage']-1, "{$qinfo['sid']}X{$qinfo['qid']}", self::$acode);
            $p = ($p==-1)?'-':percent($p, $full);
            $i = calcIncrease($c,$p);//($c=='-'||$p=='-') ? '-' : ($c-$p);
            $table_datas[] = array($qinfo['question'], $c, $p, $i);
        }
        $tInfo['tTitle'] = $tTitle;
        $tInfo['name'] = $tName;
        $tInfo['title'] = array('检测指标', '本期得分率', '上期得分率', '变化情况');
        $tInfo['data'] = $table_datas;
        return $tInfo;
    }

    public static function j_report_2_2($gcode = '2') {
        $tName = $gcode.'.2区域内所有门店的评分分布';
        $cstage = base::$context['cstage'];

        $cdata = model_statistics::get($cstage['pid'], $cstage['stage'], 'byqids');
        $cscores = $cdata[$gcode][self::$acode];
        if(!$cscores){
            return array('name' => $tName, 'title' => array(), 'data' => array());
        }
        $cfull = model_calculate::getFull($cstage['pid'], $gcode);
        $cAnalyse = model_statistics::analyseAll($cscores, $cfull);
        //上期数据分析
        $pstage = model_stage::getItem($cstage['stage']-1);
        if($pstage){
            $pdata = model_statistics::get($cstage['pid'], $cstage['stage']-1, 'byqids');
            //$pscores = util_report::scoresArray($pstage['stage'], $gcode, 'all');
            $pscores = $pdata[$gcode][self::$acode];
            $pfull = model_calculate::getFull($pstage['pid'], $gcode);
            $pAnalyse = model_statistics::analyseAll($pscores, $pfull);
            $compare = array();
            foreach ($cAnalyse as $k => $v) {
                $compare[$k] = calcIncrease($v, $pAnalyse[$k]);
            }
        }
        @array_unshift($cAnalyse, '本期');
        @array_unshift($pAnalyse, '上期');
        @array_unshift($compare, '变化情况');
        $tInfo['name'] = $tName;
        $tInfo['title'] = array(
            array(0 => '', 'l' => 2),
            array(0 => '频次分布', 'l' => 2, 'c' => 4, 'cs' => array('[0,65分)', '[65分,75分)', '[75分,85分)', '[85分,100分)')),
            array(0 => '区域平均成绩', 'l' => 2),
            array(0 => '众数', 'l' => 2),
            array(0 => '中数', 'l' => 2)
        );
        $tInfo['data'] = array($cAnalyse, $pAnalyse, $compare);
        return $tInfo;
    }

    public static function j_report_2_3($gcode = '2') {
        $tName = '本期表现不佳的门店成绩';
        $cstage = base::$context['cstage'];
        $cscore = util_report::score_of_acode($cstage['stage'], $gcode, self::$acode); //区域本期得分
        if(!$cscore) array('name' => $tName, 'title' => array(), 'data' => array());
        $cscore = ($cscore==-1) ? '-' : number_format($cscore, 1);

        $mendians = model_mendian::getItem(self::$acode); $mcount = count($mendians);
        $data1 = $data2 = $data3 = $data4 = array(); $certainMcode = array();
        foreach ($mendians as $mcode => $info) {
            $mscore = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);//门店本期得分
            $mscore = ($mscore==-1) ? '-' : $mscore;
            if($mscore < $cscore){
                $data1[$mcode] = array( 'mname'=>$info['name'],  'score' => $mscore);
                $certainMcode[] = $mcode;
            }
        }
        $data1 = array_merge(array(array('该区域平均', $cscore)), $data1);
        $tables = array(
            array('title' => array( '门店', '本期得分'), 'name' => '本期表现不佳的门店成绩', 'data' => $data1)
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
            'title' => array( '服务细节','本期得分率' ),
            'name' => '导致表现不佳的具体服务细节短板',
            'data' => $tempdatas);

        //上期表现不佳的门店
        $pstage = model_stage::getItem($cstage['stage']-1);
        if( $pstage ){
            $pscore = util_report::score_of_acode($pstage['stage'], $gcode, self::$acode); //区域上期得分
            $pfull = model_calculate::getFull($pstage['pid'], $gcode);
            $pscore = ($pscore==-1) ? '-' : percent($pscore, $pfull);

            $mendians = model_mendian::getItem(self::$acode);
            $cfull = model_calculate::getFull($cstage['pid'], $gcode);
            foreach ($mendians as $mcode => $ainfo) {
                $mpscore = util_report::score_of_mendians($cstage['stage']-1, $gcode, self::$acode, $mcode);//门店上期得分
                $mpscore = ($mpscore==-1) ? '-' : percent($mpscore, $pfull);
                if(trim($mpscore,'%') < trim($pscore,'%')){
                    //				$temppscore = util_report::score_of_acode($cstage['stage'], $gcode, $acode);
                    $mcscore = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);//门店本期得分
                    $mcscore = ($mcscore==-1) ? '-' : percent($mcscore, $cfull);
                    $data2[$mcode] = array(  'aname'=>$ainfo['name'], 'pscore' => $mpscore, 'score' => $mcscore,
                        'i' => calcIncrease($mcscore, $mpscore));//($mcscore=='-'||$mpscore=='-') ? '-' :($mcscore - $mpscore) );
                }elseif(trim($mpscore,'%') > 85){
                    $mcscore = util_report::score_of_mendians($cstage['stage'], $gcode, self::$acode, $mcode);//门店本期得分
                    $mcscore = ($mcscore==-1) ? '-' : percent($mcscore, $cfull);
                    $data3[$mcode] = array( 'aname'=>$ainfo['name'], 'pscore' => $mpscore,
                        'score' => $mcscore,'i' => calcIncrease($mcscore, $mpscore));//($mcscore=='-'||$mpscore=='-') ? '-' : number_format($mcscore - $mpscore, 1), );
                }
            }
            $data2 = util_sort::bubbleSortOnly($data2, 'pscore'); //上期表现不佳 D
            $data2 = array_merge(array('all'=>array('aname'=>'该区域平均','pscore'=>$pscore, 'score'=>'-','i'=>'-')), $data2);
            $data3 = util_sort::bubbleSortOnly($data3, 'pscore'); //上期达标区域
            $data3 = array_reverse($data3, true);

            $tables[] = array(
                'title' => array('门店', '上期得分率', '本期得分率', '变化情况'),
                'name' => '上期表现不佳的门店本期表现',
                'data' => $data2);
            $tables[] = array(
                'title' => array('门店', '上期得分率', '本期得分率', '变化情况'),
                'name' => '上期达标门店本期表现',
                'data' => $data3);
        }
        return $tables;
    }
}