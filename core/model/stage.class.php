<?php
class model_stage extends model_base{
	private static $stages = array(
		1 => array(
			'stage'=>1, 'pid'=>1, 'stage_str'=>'01','start_date'=>'2013年5月11日', 'end_date'=>'2013年5月16日',
			'info_param'=>'19766X1', 'mcode_param'=>'19766X4', 'acode_param'=>'19766X2'), //'mendian_param'=>'19766X2', 
		2 => array(
			'stage'=>2, 'pid'=>1, 'stage_str'=>'02','start_date'=>'2013年6月18日', 'end_date'=>'2013年7月23日',
			'info_param'=>'19766X1', 'mcode_param'=>'19766X4', 'acode_param'=>'19766X2'), //'mendian_param'=>'19766X2',
	);
	public static function cstage(){
		//$cstage = isset($_REQUEST['stage']) ? (int)$_REQUEST['stage'] : 1;
		$cstage = model_session::Instance()->get('stage');
		$stages = self::$stages;
		if(isset($stages[$cstage]) && $stages[$cstage]){
			return $stages[$cstage];
		}else{
			return array_shift($stages);
		}
	}
	
	public static function stages(){
		$cstage = self::cstage();
		$stages = self::$stages;
		$stages[$cstage['stage']]['c'] = 1;
		return $stages;
	}

	public static function getItem($key){
		$stages = self::$stages;
		$keys = explode(':', $key);
		$c = $stages;
		foreach($keys as $k) {
			if(isset($c[$k])) $c = $c[$k];
			else return NULL;
		}
		return $c;
	}
}