<?php
//ini_set('memory_limit','64M');
class util_sort{
	
	//关联数组，指定字段快速排序
	public static function quickSort($array, $param, $mode = 'b'){
		if(count($array) <=1 ) return $array;
		$left = array();
		$right = array();
		foreach($array as $k=>$v){
			$midk = $k;
			$midv = $v;
			$mv = getItem($midv, $param);
			break;
		}
		
		if($mode == 'b'){
			foreach($array as $k=>$v){
				$cv = getItem($v, $param);
				if($cv <= $mv) $right[$k] = $v;
				else $left[$k] = $v;
			}
		}else{
			foreach($array as $k=>$v){
				$cv = getItem($v, $param);
				if($cv <= $mv) $left[$k] = $v;
				else $right[$k] = $v;
			}
		}
		$left = self::quickSort($left, $param, $mode);
		$right = self::quickSort($right, $param, $mode);
		return array_merge($left, array($midk=>$midv), $right);
	}
	
	//冒泡排序 DESC-降序 ASC-升序
	public static function bubbleSort($arr, $param, $mode='D', $rank_param='rank', $start=0){
		$len = count($arr);
		$final = array();
		$rank = 1; $tempv = null;
		if($mode == 'D'){
			for($i=1; $i<=$len; $i++){
				$pk = $pv = null;
				foreach($arr as $k=>$v){
					if(!$pk || !$pv){
						$pk = $k; $pv = $v;
						continue;
					}
					if(trim($v[$param],'%') > trim($pv[$param],'%')){
						$pk = $k; $pv = $v;
					}
				}
				unset($arr[$pk]);
				if( $tempv!=$pv[$param] ){
					$rank = $i;
					$tempv = $pv[$param];
				}
				
				$pv[$rank_param] = $rank+$start;
				$final[$pk] = $pv;
				$pk = $pv = null;
			}
		}else{
			for($i=1; $i<=$len; $i++){
				$pk = $pv = null;
				foreach($arr as $k=>$v){
					if(!$pk || !$pv){
						$pk = $k; $pv = $v;
						continue;
					}
					if(trim($v[$param],'%') < trim($pv[$param],'%')){
						$pk = $k; $pv = $v;
					}
				}
				unset($arr[$pk]);
				if( $tempv!=$pv[$param] ){
					$rank = $i;
					$tempv = $pv[$param];
				}
				
				$pv[$rank_param] = $rank+$start;
				$final[$pk] = $pv;
				$pk = $pv = null;
			}
		}
		return $final;
	}

	//冒泡排序 DESC-降序 ASC-升序
	public static function bubbleSortExt($arr, $param, $mode='D'){
		$len = count($arr);
		$final = array();
		$rank = 1; $tempv = null;
		
		for($i=1; $i<=$len; $i++){
			$pk = $pv = null;
			foreach($arr as $k=>$v){
				if(!$pk || !$pv){
					$pk = $k; $pv = $v;
					continue;
				}
				if(trim($v[$param],'%') > trim($pv[$param],'%')){
					$pk = $k; $pv = $v;
				}
			}
			unset($arr[$pk]);
			if( $tempv!=$pv[$param] ){
				$rank = $i;
				$tempv = $pv[$param];
			}
			
			$pv['rank'] = $rank;
			$final[$pk] = $pv;
			$pk = $pv = null;
		}
		if($mode != 'D'){
			$final = array_reverse($final, true);
		}
		return $final;
	}

	//冒泡排序 DESC-降序 ASC-升序 - 不要rank字段
	public static function bubbleSortOnly($arr, $param, $mode='D'){
		$len = count($arr);
		$final = array();
		$rank = 1; $tempv = null;
		
		for($i=1; $i<=$len; $i++){
			$pk = $pv = null;
			foreach($arr as $k=>$v){
				if(!$pk || !$pv){
					$pk = $k; $pv = $v;
					continue;
				}
				if(trim($v[$param],'%') > trim($pv[$param],'%')){
					$pk = $k; $pv = $v;
				}
			}
			unset($arr[$pk]);
			if( $tempv!=$pv[$param] ){
				$rank = $i;
				$tempv = $pv[$param];
			}
			
			
			$final[$pk] = $pv;
			$pk = $pv = null;
		}
		if($mode != 'D'){
			$final = array_reverse($final, true);
		}
		return $final;
	}
}