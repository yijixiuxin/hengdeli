<?php
/**
 * 报告下载数据源模型
 * Created by JetBrains PhpStorm.
 * User: Xavier
 * Date: 13-7-4
 * Time: 上午10:14
 * To change this template use File | Settings | File Templates.
 */

class model_question extends model_base{
    public function __construct() {
        parent::__construct();
    }

    /**
     * 高层数据报告
     * 1-1各区域环比上期成绩
     * 三线表输出
     */
    public function g_report1_1() {
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

        //显示图表
        if($_REQUEST['ajax']=='chart'){
            $info = array('chart_name' => '各区域环比上期成绩', 'xname'=>'区域', 'yname'=>'得分', 'cstage'=>$cstage['stage'], 'data'=>$table_datas);
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

    }
}
