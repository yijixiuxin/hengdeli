<?php
class model_user extends model_base{
	const USER_LEADER = 15;
	const USER_QUDAO = 5;
	const USER_ADMIN = 100;
	
	private static $tname = 'users';
	
	public static function login($user, $pwd){
		$tname = getConf('dbinfo:prefix').self::$tname;
		$user_info = self::DB()->select(self::$dbname, $tname, array('user'=>$user, 'pwd'=>$pwd));
		if(!$user_info[0]) return false;
		
		$user_info[0]['group'] = self::levelToGroup($user_info[0]['level']);
		if($user_info[0]['level'] > self::USER_QUDAO){
			$user_info[0]['acode'] = 'all';
			self::session()->set('acode', 'all'); //非渠道用户默认01
		}else{
			$acode = model_uanda::Get($user_info[0]['id']);
			if($acode && model_area::getItem($acode)){
				$user_info[0]['acode'] = $acode;
				self::session()->set('acode', $acode);
			}else{
				exit('user acode error');
			}
		}
		self::session()->set('user_info', $user_info[0]);
		return true;
	}
	
	public static function cuser(){
		return self::session()->get('user_info');
	}

	public static function logout(){
		self::session()->destroy();
	}
	
	public static function Gets($where=array(), $options=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->select(self::$dbname, $tname, $where, $options);
	}

	public static function isSuper(){
		$cuser = self::cuser();
		return ($cuser['level']==self::USER_ADMIN) ? true : false;
	}

	public static function update($data, $where){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->update(self::$dbname, $tname, $data, $where);
	}
	
	public static function insert($data){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->insert(self::$dbname, $tname, $data);
	}

	public static function delete($where){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->delete(self::$dbname, $tname, $where);
	}

	public static function levelToGroup($level){
		if($level > self::USER_LEADER) return '管理员';
		if($level > self::USER_QUDAO) return '高层';
		return '渠道';
	}
}