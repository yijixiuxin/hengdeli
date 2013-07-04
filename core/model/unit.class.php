<?php
class model_unit extends model_base{
	public static function tname($pid){
		if(!$pid) return NULL;
		return getConf('dbinfo:prefix')."project_{$pid}";
	}

	public static function Gets($pid, $where=array(), $option=array()){
		$tname = self::tname($pid);
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}

	/*public static function questionValue($sid, $qid, $data){
		$tempk = "{$sid}X{$qid}";
		return $data[$tempk];
	}*/

	/*public static function Gets($sid){
		$tname = self::tname($sid);
		return self::DB()->select(self::$dbname, $tname);
	}*/

	public static function update($sid, $data, $id=0){
		$tname = getConf('dbinfo:prefix')."survey_{$sid}";
		$id = (int)$id;
		if($id){
			return self::DB()->update(self::$dbname, $tname, $data, array('id'=>$id));
		}else{
			return self::DB()->insert(self::$dbname, $tname, $data);
		}
	}

	public static function checkIP($sid, $ip){
		$tname = self::tname($sid);
		$res = self::DB()->select(self::$dbname, $tname, array('ip'=>$ip), array('order'=>array('ctime'=>-1), 'limit'=>10));
		if(!$res) return true;
		$now = time()-180; if($res[0]['ctime'] > $now) return false;

		if(count($res)>=10){
			$temp = array_pop($res);
			if(($res[0]['ctime']-$temp['ctime']) < 43200) return false; //12小时内最多10条
		}
		return true;
	}

	public static function getByEmail($sid, $email){
		$tname = self::tname($sid);
		$res = self::DB()->select(self::$dbname, $tname, array('email'=>$email));
		return empty($res) ? NULL : $res[0];
	}

	public static function getById($sid, $id){
		$tname = self::tname($sid);
		return self::DB()->select(self::$dbname, $tname, (int)$id);
	}
}