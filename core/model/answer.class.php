<?php
class model_answer extends model_base{
	private static $tname = 'lime_answers';
	private static $answers = array();
	
	/**
	 * array('19766'=>array('1'=>array('a'=>array('answer'=>'ziliang', 'value'=>'1'),'b'=>array('answer'=>'ziliang1', 'value'=>2))))
	 */
	/*public static function Gets($sid){
		if(isset(self::$answers[$sid])) return self::$answers[$sid];

		$tname = self::$tname;
		$sid = (int)$sid;
		$option = array('order'=>array('sortorder'=>1));
		$answers = self::DB()->select(self::$dbname, $tname, array('sid'=>$sid), $option);
		//firelog($answers, "Get all answers of sid:{$sid}", basename(__FILE__), __LINE__);
		
		self::$answers[$sid] = array();
		if($answers){
			foreach ($answers as $a) {
				$k = $a['qid'];
				if(isset(self::$answers[$sid][$k])){
					$k2 = $a['code'];
					self::$answers[$sid][$k][$k2] = array('answer'=>$a['answer'], 'value'=>$a['assessment_value']);
				}else{
					self::$answers[$sid][$k] = array($a['code'] => array('answer'=>$a['answer'], 'value'=>$a['assessment_value']));
				}
			}
		}
		firelog(self::$answers[$sid], "Processed all answers of sid:{$sid}", basename(__FILE__), __LINE__);
		return self::$answers[$sid];
	}*/

	/**
	 * array(
	 *		'1'=>array( 'a'=>array('answer'=>'ziliang', 'value'=>'1'),'b'=>array('answer'=>'ziliang1', 'value'=>2) ) 
	 * )
	 */
	public static function getAll(){
		if( !empty(self::$answers) ){
			return self::$answers;
		}
		$answers = self::DB()->select(self::$dbname, self::$tname);
		if($answers){
			foreach ($answers as $a) {
				$k = $a['qid'];
				if(isset(self::$answers[$k])){
					$k2 = $a['code'];
					self::$answers[$k][$k2] = array('answer'=>$a['answer'], 'value'=>$a['assessment_value']);
				}else{
					self::$answers[$k] = array($a['code'] => array('answer'=>$a['answer'], 'value'=>$a['assessment_value']));
				}
			}
		}
		firelog(self::$answers, "Processed all answers of sid:{$sid}", basename(__FILE__), __LINE__);
		return self::$answers;
		
	}
	
	/**
	 * getValue : getItem("{$qid}:{$code}:value");
	 * getAnswer : getItem("{$qid}:{$code}:answer");
	 * getAnswers : getItem("{$qid}");
	 */
	public static function getItem($key){
		$answers = self::getAll();
		$keys = explode(':', $key);
		$c = $answers;
		foreach($keys as $k) {
			if(isset($c[$k])) $c = $c[$k];
			else return NULL;
		}
		return $c;
	}
}