<?php
class model_uanda extends model_base{
	
	private static $tname = 'uanda';
	private static $uandas = null;

	public static function Get($uid){
		$tname = getConf('dbinfo:prefix').self::$tname;
		$acodes = self::DB()->select(self::$dbname, $tname, array('uid'=>$uid));
		return $acodes[0]['acode'];
	}
	
	private static function uandas(){
		if(self::$uandas == null){
			$tname = getConf('dbinfo:prefix').self::$tname;
			$datas = self::DB()->select(self::$dbname, $tname);
			if(!$datas) return array();
			foreach($datas as $data){
				$k = (int)$data['uid'];
				self::$uandas[$k] = $data;
			}
		}
		return self::$uandas;
	}
	
	public static function update($uid, $acode){
		$tname = getConf('dbinfo:prefix').self::$tname;
		$res1 = self::DB()->delete(self::$dbname, $tname, array('uid' => $uid));
		if($acode){
			$res2 = self::DB()->insert(self::$dbname, $tname, array('uid' => $uid, 'acode'=>$acode));
		}
		return ($res1 && $res2);
	}
	
	public static function getArea($uid){
		$uandas = self::uandas();
		if(!isset($uandas[$uid])) return null;
		return model_area::getItem($uandas[$uid]['acode']);
	}
}