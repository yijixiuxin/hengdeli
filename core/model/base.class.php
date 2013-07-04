<?php
class model_base{
	public static $dbname = NULL;
	
	public static function DB(){
		$dbconfig = getConf('dbinfo:config');
		return DB::Instance($dbconfig);
	}
	
	public static function session(){
		return model_session::Instance();
	}
}
model_base::$dbname = getConf('dbinfo:dbname');