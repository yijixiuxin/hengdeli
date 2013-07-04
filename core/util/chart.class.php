<?php
/**
 * build chart data
 * 
 */
class util_chart
{
	public static function buildAnalyse1($data, $cAnalyse, $pAnalyse=null){
		$chartData = array( 'chart' => array('chart_name' => $data['chart_name'],
											 'xname' => '期数', 'yname' => '百分比' )
		);
		if(isset($pAnalyse) && $pAnalyse){
			$chartData['categories'] = array( ($data['cstage']-1)."期", $data['cstage']."期");
			$chartData['datasets'] = array(
				array(
						'attributes' => array('name' => '频次分布[0,65%)'),
						'datas' => array( array('value'=>$cAnalyse['d']),array('value'=>$pAnalyse['d'])) ),
				array(
						'attributes' => array('name' => '频次分布[65%,75%)'),
						'datas' => array( array('value'=>$cAnalyse['c']),array('value'=>$pAnalyse['c'])) ),
				array(
						'attributes' => array('name' => '频次分布[75%,85%)'),
						'datas' => array( array('value'=>$cAnalyse['b']),array('value'=>$pAnalyse['b'])) ),
				array(
						'attributes' => array('name' => '频次分布[85%,100%]'),
						'datas' => array( array('value'=>$cAnalyse['a']),array('value'=>$pAnalyse['a'])) ),
				array(
						'attributes' => array('name' => '众数', 'yaxis'=>'S'),
						'datas' => array( array('value'=>$cAnalyse['mode']),array('value'=>$pAnalyse['mode'])) ),
				array(
						'attributes' => array('name' => '中位数', 'yaxis'=>'S'),
						'datas' => array( array('value'=>$cAnalyse['mid']),array('value'=>$pAnalyse['mid'])) ),
			);
		}else{
			$chartData['categories'] = array($data['cstage']."期");
			$chartData['datasets'] = array(
				array(
						'attributes' => array('name' => '频次分布[0,65%)'),
						'datas' => array( array('value'=>$cAnalyse['d']),array('value'=>$pAnalyse['d'])) ),
				array(
						'attributes' => array('name' => '频次分布[65%,75%)'),
						'datas' => array( array('value'=>$cAnalyse['c']),array('value'=>$pAnalyse['c'])) ),
				array(
						'attributes' => array('name' => '频次分布[75%,85%)'),
						'datas' => array( array('value'=>$cAnalyse['b']),array('value'=>$pAnalyse['b'])) ),
				array(
						'attributes' => array('name' => '频次分布[85%,100%]'),
						'datas' => array( array('value'=>$cAnalyse['a']),array('value'=>$pAnalyse['a'])) ),
				array(
						'attributes' => array('name' => '众数', 'yaxis'=>'S'),
						'datas' => array( array('value'=>$cAnalyse['mode']),array('value'=>$pAnalyse['mode'])) ),
				array(
						'attributes' => array('name' => '中位数', 'yaxis'=>'S'),
						'datas' => array( array('value'=>$cAnalyse['mid']),array('value'=>$pAnalyse['mid'])) ),
			);
		}
		return $chartData;
	}

	public static function buildAnalyse($data, $cAnalyse, $pAnalyse=null){
		$chartData = array( 'chart' => array('chart_name' => $data['chart_name'],
											 'xname' => '期数', 'yname' => '百分比' )
		);
		$chartData['categories'] = array( '频次分布[0,65%)','频次分布[65%,75%)','频次分布[75%,85%)','频次分布[85%,100%]','众数','中位数');
		$chartData['datasets'] = array( array(
					'attributes' => array('name' => $data['cstage']."期"), 
					'datas' => array(
							array('value'=>$cAnalyse['d']),
							array('value'=>$cAnalyse['c']),
							array('value'=>$cAnalyse['b']),
							array('value'=>$cAnalyse['a']),
							array('value'=>$cAnalyse['mode']),
							array('value'=>$cAnalyse['mid']))
		));
		if(isset($pAnalyse) && $pAnalyse){
			$temp = array( array(
					'attributes' => array('name' => ($data['cstage']-1)."期"), 
					'datas' => array(
							array('value'=>$pAnalyse['d']),
							array('value'=>$pAnalyse['c']),
							array('value'=>$pAnalyse['b']),
							array('value'=>$pAnalyse['a']),
							array('value'=>$pAnalyse['mode']),
							array('value'=>$pAnalyse['mid']))
			));
			$chartData['datasets'] = array_merge($temp, $chartData['datasets']);
		}
		return $chartData;
	}
	
	//处理合并的数组
	public static function buildNormal($data, $param=array('catgry_k'=>'name','c_k'=>'c','p_k'=>'p'), $is_pre=true){
		$chartData = array( 'chart' => array('chart_name' => $data['chart_name'],
											 'xname' => $data['xname'], 'yname' => $data['yname'])
		);
		$p = array(
					'attributes' => array('name' => ($data['cstage']-1)."期"), 
					'datas' => array());
		$c = array(
					'attributes' => array('name' => $data['cstage']."期"), 
					'datas' => array());
		$catgry_k = $param['catgry_k']; $c_k = $param['c_k']; $p_k = $param['p_k'];
		$data = $data['data']; $chartData['categories'] = array();
		foreach($data as $acode=>$info){
			$chartData['categories'][] = $info[$catgry_k];
			$p['datas'][] = array('value'=>$info[$p_k]);
			$c['datas'][] = array('value'=>$info[$c_k]);
		}
		$chartData['datasets'] = ($is_pre==true) ? array($p, $c) : array($c);
		return $chartData;
	}
}