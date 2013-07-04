<?php
class model_area extends model_base{
	private static $tname = "areas";

	public static function Gets($where=array(), $option=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}
	
	//获取当前地区code
	public static function cacode(){
		$acode = self::session()->get('acode');
		if('all' == $acode) return 'all';
		if(!$acode || null == self::getItem($acode)) exit('model_area:acode error');
		return $acode;
	}

	//根据地区编码获取地区名称 model_area::getItem("{$acode}:name");
	//根据地区编码获取地区编码 model_area::getItem("{$acode}:code");
	public static function getItem($key=null){
		static $data = null;
		if($data == null){
			$temp = self::Gets();
			foreach ($temp as $v) {
				$k = $v['code'];
				$data[$k] = $v;
			}
		}

		if($key == null) return $data;

		$keys = explode(':', $key);
		$c = $data;
		foreach($keys as $k) {
			if(isset($c[$k])) $c = $c[$k];
			else return NULL;
		}
		return $c;
	}
}