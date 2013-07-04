<?php
/**
 * multi-chart
 * 
 */
class util_multiChart
{
	public $chartName = '';//图表名称
	public $type = array(
		'1' =>'MSColumn3D.swf',
		'2' =>'MSLine.swf',
	);
	
	function buildChart($data){
		$baseinfo = $data['chart'];
		$string = "<chart caption='{$baseinfo['chart_name']}' xAxisName='{$baseinfo['xname']}' yAxisName='{$baseinfo['yname']}' showValues='1' decimals='1' numberSuffix ='%'>";
		$string .= $this->buildCategorys($data['categories']);
		$string .= $this->buildDatasets($data['datasets']);
		return $string."</chart>";
	}
	/**
	 * Y轴上的元素
	 */
	function buildDatasets($datasets){
		if(!$datasets) return '';
		$string = '';
		foreach($datasets as $k => $dataset){
			$attr = array(
				'name' => $dataset['attributes']['name'],
				'yaxis' => ('S'==$dataset['attributes']['yaxis'])?'S':'P'
			);
			$datas = $dataset['datas'];
			$string .= "<dataset seriesName='{$attr['name']}' parentYAxis='{$attr['yaxis']}'>";
			if($datas){
				foreach($datas as $data) $string .= "<set value='{$data['value']}' />";
			}
			$string .= "</dataset>";
		}
		return $string;
	}
	/**
	 * X轴上的元素
	 */
	function buildCategorys($categories){
		if(!$categories) return '';
		$string = '<categories>';
		foreach($categories as $label){
			if(is_array($label)) $string .= "<vLine color='FF5904' thickness='2'/>"; //中间分割线 thickness宽度
			else $string .= "<category label='{$label}' />";
		}
		return $string."</categories>";
	}
	/**
	 * 水平分割线
	 */
	function buildTendlines($trendlines){
		if(!$trendlines) return '';
		$string = '<trendlines>';
		foreach($trendlines as $line){
			$color = empty($line['color']) ? '91C728' : $line['color'];
			$string .= "<line startvalue='{$line['start']}' color='{$color}' displayValue='{$line['name']}' />";
		}
		return $string."</trendlines>";
	}	
}
/*****************
$temp = array(
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
	'categories' => array( //x轴上的值
		'Jan', 'Feb', 'mar', 'Apr'
	),
	
	'datasets' => array(
		array(
			'attributes' => array(
				'name' => '2005'
				'renderAs'=>'Line'
			),
			'datas' => array(
				array('value'=>'1', 'link'=>'http://t.cn'),
				array('value'=>'1', 'link'=>'http://t.cn'),
				array('value'=>'1', 'link'=>'http://t.cn'),
				array('value'=>'1', 'link'=>'http://t.cn'),
			),
		),
		array(
			'attributes' => array(
				'name' => '2006'
				//'renderAs'=>'Line'
				'renderAs' => 'Area',
				'ParentYAxis' => 's', //y轴 [S|P]
			),
			'datas' => array(
				array('value'=>'2', 'link'=>'http://t.cn'),
				array('value'=>'2', 'link'=>'http://t.cn'),
				array('value'=>'2', 'link'=>'http://t.cn'),
				array('value'=>'2', 'link'=>'http://t.cn'),
			),
		),
	),
	
	'trendlines' => array(
		array(
			'startValue' => '1.5',
			'color' => ,
			'displayValue' => '平均分',
			'showOnTop' => ,
		),
	),
);
******************/