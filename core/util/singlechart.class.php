<?php
/**
 * 单图表
 */
class util_singleChart
{	
	public function builtChart($data){
		$baseinfo = $data['chart'];
		$string = "<chart caption='{$baseinfo['chart_name']}' xAxisName='{$baseinfo['xname']}' yAxisName='{$baseinfo['yname']}' showValues='1' decimals='1'>";
		$string .= $this->builtDatas($data['datas']);
		return $string;
	}
	
	public function builtDatas($datas){
		if(!$datas) return '';
		$string = '';
		foreach($datas as $data){
			$string .= "<set label='{$data['label']}' value='{$data['value']}' />";
		}
		return $string;
	}
}
/*****************
$tmpData = array(
	'chart' => array(
		'tname' => '图表名称', //caption
		'xname' => 'x轴名称', //xAxisName
		'yname' => 'y轴名称', //yAxisName
		'numberPrefix' => 'numberPrefix', //numberPrefix
		'showValues' => 1,	//showValues值为boolen型0为在柱子或者曲线上不显示数据
		'decimals' => 1, //小数点后尾数
		'outCnvBaseFontColor' => '', //图表外字体颜色
		'outCnvBaseFontSize' => '', //图表外字体大小
		'outCnvBaseFont' => '', //图表外字体
	),
	'datas' => array(
		array('label'=>'x1', 'value'=>'1'),
		array('label'=>'x2', 'value'=>'2'),
		array('label'=>'x3', 'value'=>'3'),
	),
);
******************/