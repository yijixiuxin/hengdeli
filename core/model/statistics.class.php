<?php
class model_statistics extends model_base{

	//统计一个纯数字数组中的中位数，众数，平均数
	//一般用于求该区域总分的中位数，众数
	public static function doArray($array, $full){
		if(!$array){
			firelog($array, 'error array to analyse', __FILE__, __LINE__, 'error');
			return false;
		}
		
		$res = array('mode'=>array());
		//得分率众数
		$counts = array_count_values($array);
		asort($counts); $counts = array_reverse($counts, true);
		$mode_c = null;
		foreach($counts as $mode=>$c){
			if($mode_c == null) $mode_c = $c;
			if($mode_c == $c) $res['mode'][] = $mode;
			else break;
		}
		sort($res['mode']);
		$res['mode'] = implode(',', $res['mode']);
		
		//得分率平均数
		$sum = array_sum($array);
		$count = count($array);
		$res['average'] = number_format($sum/$count, 1); //平均数

		//得分率中位数
		sort($array);
		if($count%2==0){
			$k1 = $count/2; $k2 = $k1-1;
			$res['mid'] = ($array[$k1]+$array[$k2])/2;
		}else{
			$k = (int)($count/2);
			$res['mid'] = $array[$k];
		}
		//$res['mid'] = percent($res['mid'], $full);
		return $res;
	}

	//统计各个等级的分布
	//$l = array('1'=>65, '2'=>75, '3'=>85);
	public static function doAnalyse($array, $full, $l=array('1'=>65,'2'=>75,'3'=>85)){
		$res = array(1=>0,2=>0,3=>0,4=>0);
		if($array){
			foreach ($array as $k => $v) {
				$v = percent($v, $full); //得分率
				if($v < $l[1]) $res[1]+=1;
				elseif($v < $l[2]) $res[2]+=1;
				elseif($v < $l[3]) $res[3]+=1;
				else $res[4]+=1;
			}
		}
		return $res;
	}

	//分析得分率分布，中位数，众数
	public static function analyseAll($array, $full, $l=array('1'=>65,'2'=>75,'3'=>85)){
		$data = self::doArray($array, $full);
		$counts = count($array);
		$analyse = self::doAnalyse($array, $full, $l);firelog($data);
		return array(
			'd' => percent($analyse[1], $counts),
			'c' => percent($analyse[2], $counts),
			'b' => percent($analyse[3], $counts),
			'a' => percent($analyse[4], $counts),
			'average' => $data['average'],
			'mode' => $data['mode'],
			'mid' => $data['mid'],
		);
	}

	public static function statistics($pid, $stage, $info_param, $mode='all'){
		$data = array();
		
		$data['byqids'] = self::doByQids($pid, $stage, $info_param);
		$data['byarea'] = self::doByArea($pid, $stage, $info_param);
		self::put($pid, $stage, $data);
		sleep(1);
		$data['allaverage'] = self::doAllAvrage($pid, $stage);
		$data['areaaverage'] = self::doAreaAvrage($pid, $stage);
		
		return self::put($pid, $stage, $data);
	}

	//根据qid统计，最终格式如下
	//array( 'total'=>array('01'=>array(20,21,21)), 'group1'=>array('01'=>array(1,2,2,1)) )
	public static function doByQids($pid, $stage, $info_param){
		$data = model_calculate::Gets($pid, array('stage'=>$stage));
		if(!$data) exit('no data');
		//firelog($data, 'All calculate data', __FILE__, __LINE__);

		$qids = array();
		foreach ($data as $item) {
			$info = model_unit::Gets($pid, $item['id']);
			$acode = substr($info[$info_param], 2, 2);
			$mcode = substr($info[$info_param], 2, 4);
			foreach ($item as $k => $v) {
				if($k=='id' || $k=='stage') continue;
				if(!is_array($qids[$k])) $qids[$k] = array();
				if(!is_array($qids[$k][$acode])) $qids[$k][$acode] = array();
				if($v == -1) continue;
				$qids[$k][$acode][$mcode] = $v;
			}
		}
		firelog($qids, 'statistics by qids', __FILE__, __LINE__);
		//self::put($pid, $stage, $qids);
		return $qids;
	}

	//$info_param 19766X1
	//根据area统计（得分），最终格式如下
	//array('0101'=>array('total'=>array(20,21,21), 'group1'=>array(1,2,3)), '0102'=>array('total'=>array(20,21,21), 'group1'=>array(1,2,3)))
	public static function doByArea($pid, $stage, $info_param){
		$data = model_calculate::Gets($pid, array('stage'=>$stage));
		if(!$data) exit('no data');//return flase;
		firelog($data, 'All calculate data', __FILE__, __LINE__);
		
		$area = array();
		foreach ($data as $item) {
			$info = model_unit::Gets($pid, $item['id']);
			$acode = substr($info[$info_param], 2, 2);
			$mcode = substr($info[$info_param], 2, 4);
			
			if(!is_array($area[$acode])) $area[$acode] = array();
			
			foreach ($item as $k => $v) {
				if($k=='id' || $k=='stage') continue;
				if(!is_array($area[$acode][$k])){
					$area[$acode][$k] = array();
				}
				if($v == -1) continue;
				
				$area[$acode][$k][$mcode] = $v;
			}
		}
		firelog($area, 'statistics by area', __FILE__, __LINE__);
		//self::put($pid, $stage, $area);
		return $area;
	}
	
	//array('total'=>95, '1'=>2, '19755X3'=>2)
	public static function doAllAvrage($pid, $stage){
		$datas = self::get($pid, $stage, 'byqids');
		if(!$datas) exit('no data');
		$final = array();
		foreach($datas as $qk => $ainfos){
			$totals = array();
			if(!$ainfos){ exit('error');$final[$qk] = 0; continue;}
			
			foreach($ainfos as $acode => $ascores){
				$totals = array_merge($totals, $ascores);
			}
			$c = count($totals); $s = array_sum($totals);
			if(!$c) $final[$qk] = 0;
			else $final[$qk] = $s/$c;
		}
		return $final;
	}
	
	//array('01'=>array('total'=>90, '1'=>15, '19755X3'=>2), '02'=>array('total'=>90, '1'=>15, '19755X3'=>2))
	public static function doAreaAvrage($pid, $stage){
		$datas = self::get($pid, $stage, 'byarea');
		if(!$datas) exit('no data');
		$final = array();
		foreach($datas as $acode => $qinfos){
			foreach($qinfos as $qk => $qscores){
				if($qscores && is_array($qscores)) $final[$acode][$qk] = array_sum($qscores)/count($qscores);
				else $final[$acode][$qk] = 0;
			}
		}
		return $final;
	}

	public static function get($pid, $stage, $key=null){
		$fname = self::fname($pid, $stage);
		if(!file_exists($fname)){
			$info_param = model_stage::getItem("{$stage}:info_param");
			self::statistics($pid, $stage, $info_param, 'score');
		}
		$data = require $fname;
		if($key) return $data[$key];
		return $data;
	}

	public static function put($pid, $stage, $data){
		$name = self::fname($pid, $stage);
		$var = var_export($data, true);
		$var = preg_replace('/\s/', '', $var);
		$str = "<?php\r\nreturn {$var};";

		return file_put_contents($name, $str);
	}

	private static function fname($pid, $stage){
		return ROOT_PATH."/template_c/{$pid}-{$stage}.php";
	}
	
}