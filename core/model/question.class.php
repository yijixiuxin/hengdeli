<?php
class model_question extends model_base{
	private static $tname = 'lime_questions';
	private static $question = array();
	
	public static function Gets($sid){
		if(isset(self::$question[$sid])) return self::$question[$sid];

		$tname = self::$tname;
		$sid = (int)$sid;
		$option = array('order'=>array('gid'=>1, 'question_order'=>1));
		$question = self::DB()->select(self::$dbname, $tname, array('sid'=>$sid), $option);
		
		self::$question[$sid] = array();
		if($question){
			foreach ($question as $q) {
				$k = $q['qid'];
				self::$question[$sid][$k] = $q;
			}
		}
		//firelog(self::$question[$sid], "Processed all question of sid:{$sid}", basename(__FILE__), __LINE__);
		return self::$question[$sid];
	}
	
}