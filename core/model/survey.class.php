<?php
class model_survey extends model_base{
	private static $tname = 'lime_surveys';

	public static function Get($id){
		$data = self::Gets(array('id'=>(int)$id));
		return empty($data) ? false : $data[0];
	}

	public static function Gets( $where=array('status'=>1), $option = array() ){
		return self::DB()->select(self::$dbname, self::$tname, $where, $option);
	}

	public static function title($sid){
		$dbname = self::$dbname;
		$sql = "SELECT `surveyls_title` FROM `{$dbname}`.`lime_surveys_languagesettings` WHERE `surveyls_survey_id`='{$sid}' LIMIT 1";
		$data = self::DB()->query($sql);
		return empty($data) ? null : $data[0]['surveyls_title'];
	}

	public static function currentSid(){
		return isset($_REQUEST['sid']) ? (int)$_REQUEST['sid'] : 0;
		//return model_session::Get('sid');
	}

	public static function qanda($sid){
		$sid = (int)$sid;
		if(!$sid) {
			firelog($sid, 'empty sid', basename(__FILE__), __LINE__, 'error');
			return NULL;
		}
		$questions = model_question::Gets($sid);
		if(!$questions) return NULL;

		//$answers = model_answer::Gets($sid);
		$answers = model_answer::getAll();
		foreach ($questions as $qid => $q) {
			if(in_array($q['type'], array('O', 'M', 'P', 'L'))) {
				$questions[$qid]['answers'] = $answers[$qid];
			}
		}
		return $questions;
	}
	
	public static function export($sid){
		$questions = self::qanda($sid);
		$survey = self::Get($sid);
		if(!$questions || !$survey) return false;
		
		$data = array('info'=>$survey, 'qanda'=>$questions);
		$data = json_encode($data);
		$filename = ROOT_PATH."/survey_{$sid}.txt";
		return file_put_contents($filename, $data);
	}
	
	public static function import($data){
		$data = json_decode($data, true);
		firelog($data, "Import survey data", basename(__FILE__), __LINE__);
		if(!$data) return false;
		$sid = rand(10000, 99999);
		$used = self::Get($sid);
		while($used){
			$sid = rand(10000, 99999);
			$used = self::Get($sid);
		}
		
		$prefix = 'lime_';
		$dbname = self::$dbname;
		$info = $data['info']; $qanda = $data['qanda'];
		$sql = "INSERT INTO `{$dbname}`.`{$prefix}surveys`(`id`, `title`, `status`, `info`) VALUES ('{$sid}', '{$info['title']}', '0', '{$info['info']}');";
		firelog($sql, "Add survey sql", basename(__FILE__), __LINE__);
		$res = self::DB()->query($sql);
		
		$qorder = count($data['qanda']);
		foreach($data['qanda'] as $qid => $q){
			$sql = "INSERT INTO `{$dbname}`.`{$prefix}questions`(`id`, `sid`, `title`, `type`, `order`, `must`)
					VALUES (NULL, '{$sid}', '{$q['title']}', '{$q['type']}', '{$qorder}', '{$q['must']}')";
			firelog($sql, "Add Question sql", basename(__FILE__), __LINE__);
			$qid = self::DB()->query($sql);
			if(!$qid) return false;
			if($q['answers']) {
				$sql = "INSERT INTO `{$dbname}`.`{$prefix}answers`(`sid`, `qid`, `code`, `answer`, `order`) VALUES";
				$aorder = count($q['answers']);
				foreach($q['answers'] as $acode=>$a){
					$sql .= " ('{$sid}', '{$qid}', '{$acode}', '{$a}', '{$aorder}'),";
					$aorder--;
				}
				$sql = rtrim($sql, ',');
				firelog($sql, "Add Answers sql", basename(__FILE__), __LINE__);
				self::DB()->query($sql);
			}
			$qorder--;
		}
		return true;
	}
}