<?php
class model_nav extends model_base{
	private static $tname = 'candas';
	
	/*public static function accessed($c = null){
		if($c) $where = array('c'=>$c, 'status'=>1);
		else $where = array('a'=>'index', 'status'=>1);
		$allnavs = self::DB()->select(self::$dbname, self::$tname, array('status'=>1), array('order'=>1));
		if($allnavs){
			$user_info = model_user::cuser();
			foreach($allnavs as $k=>$nav){
				if($nav['level'] > $user_info['level']) unset($allnavs[$k]);
			}
			return $allnavs;
		}
		return array();
	}*/
	
	public static function add($c, $a='index', $info=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		$data = array_extract($info, array('titile', 'status'));
		return self::DB()->insert(self::$dbname, $tname, $data);
	}

	public static function Gets($where, $option=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}
}