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

    public function __construct() {
        parent::__construct();
    }

    /**
     * 高层报告
     * @param string $gcode
     */
    public static function g_report_1_1($gcode = '') {
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
                array(0 => '', 'l' => 2),
                array(0 => '频次分布', 'l' => 2, 'c' => 4, 'cs' => array('[0,65分)', '[65分,75分)', '[75分,85分)', '[75分,85分)')),
                array(0 => '集团平均分', 'l' => 2),
                array(0 => '众数', 'l' => 2),
                array(0 => '中数', 'l' => 2)
        );
        $tInfo['data'] = $cAnalyse;
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
}