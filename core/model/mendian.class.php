<?php
class model_mendian extends model_base{
	private static $tname = "mendian";

	public static function Gets($where=array(), $option=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}

	public static function Get($code){
		$data = self::Gets(array('code'=>$code));
		return empty($data[0]) ? null : $data[0];
	}

	//根据地区编码获取地区名称 model_area::getItem("{$acode}:name");
	//根据地区编码获取地区编码 model_area::getItem("{$acode}:code");
	public static function getItem($acode, $key=null){
		static $data = array();
		if(!isset($data[$acode]) || !$data[$acode]){
			$temp = self::Gets(array('acode'=>$acode));
			$data[$acode] = array();
			foreach ($temp as $v) {
				$k = $v['code'];
				$data[$acode][$k] = $v;
			}
		}

		if($key == null) return $data[$acode];

		$keys = explode(':', $key);
		$c = $data[$acode];
		foreach($keys as $k) {
			if(isset($c[$k])) $c = $c[$k];
			else return NULL;
		}
		return $c;
	}

	public static function calcTypes($stage, $acode='all'){
		$stageInfo = model_stage::getItem($stage);
		if($acode == all){
			$data = model_unit::Gets($stageInfo['pid'], array('stage'=>$stage));
		}else{
			$k = $stageInfo['acode_param'];
			$data = model_unit::Gets($stageInfo['pid'], array('stage'=>$stage, $k=>$acode));
		}
		if(!$data) exit('error'); //var_dump($data);die;

		$calcInfo = array('ctype1'=>0, 'ctype2'=>0, 'cadd'=>'-', 'cdelete'=>'-');
		$tempRecord = array();
		foreach ($data as $item) {
			$mcode = substr($item[$stageInfo['info_param']], 2, 4);
			$tacode = substr($mcode, 0, 2); //var_dump($item);die;
			$tempRecord[$mcode] = 1;
			$m_type = self::getItem($tacode, "{$mcode}:type");
			if($m_type==1) $calcInfo['ctype1']++;
			elseif($m_type==2) $calcInfo['ctype2']++;
		}

		$p_stageInfo = model_stage::getItem($stage-1);
		if($p_stageInfo){
			$calcInfo['cadd'] = $calcInfo['cdelete'] = 0;
			if($acode == all){
				$data = model_unit::Gets($p_stageInfo['pid'], array('stage'=>$p_stageInfo['stage']));
			}else{
				$k = $p_stageInfo['acode_param'];
				$data = model_unit::Gets($p_stageInfo['pid'], array('stage'=>$p_stageInfo['stage'], $k=>$acode));
			}
			if(!$data) exit('error');
			foreach ($data as $item) {
				$mcode = substr($item[$p_stageInfo['info_param']], 2, 4);
				if(isset($tempRecord[$mcode]) && $tempRecord[$mcode]){
					unset($tempRecord[$mcode]);//var_dump($tempRecord);die;
				}else{
					$calcInfo['cadd']++;
				}
			}

			$calcInfo['cdelete'] = count($tempRecord);
		}
		return $calcInfo;
	}
}