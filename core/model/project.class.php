<?php
class model_project extends model_base{
	private static $tname = 'projects';
	
	public static function Gets($where=array(), $option=array()){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}

	public static function projectInfo($pid){
		static $pinfos = array();
		if(!isset($pinfos[$pid])){
			$temp = self::Gets($pid);
			if(!$temp) return null;
			$temp['qids_array'] = unserialize($temp['qids']);
			$temp['fullscore_array'] = unserialize($temp['fullscore']);
			//$temp['qids_array'] = json_decode($temp['qids'], true);
			$pinfos[$pid] = $temp;
			firelog($temp, 'project_info', __FILE__, __LINE__);
		}
		return $pinfos[$pid];
	}
	
	/*public static function qanda($pid){
		$project_info = self::Gets($pid);
	}*/

	public static function questions($pid, $gcode=null){
		$project_info = self::projectInfo($pid);
		$qids_array = $project_info['qids_array'];
		$questions = model_question::Gets($project_info['sid']);
		if(!$qids_array || !$questions) return null;
		$q_of_p = array();
		if($gcode == null){
			foreach ($qids_array as $gk => $pgroup) {
				if(!$pgroup['qids'] ) continue;
				foreach ($pgroup['qids'] as $qk=>$qid) {
					$q_of_p[$qid] = $questions[$qid];
				}
			}
		}else{
			foreach ($qids_array as $gk => $pgroup) {
				if(!$pgroup['qids'] || $pgroup['gcode']!=$gcode) continue;
				foreach ($pgroup['qids'] as $qk=>$qid) {
					$q_of_p[$qid] = $questions[$qid];
				}
				break;
			}
		}
		//firelog($q_of_p, 'questions of project', __FILE__, __LINE__);
		return $q_of_p;
	}

	public static function calcableQuestions($pid){
		$data = self::questions($pid);
		if(!$data) return array();
		$final = array();
		$project_info = self::projectInfo($pid);
		foreach ($project_info['qids_array'] as $gk => $pgroup) {
			if(!$pgroup['calcble'] ) continue;
			foreach ($pgroup['qids'] as $qk=>$qid) {
				$final[$qid] = $data[$qid];
				$final[$qid]['gcode'] = $pgroup['gcode'];
			}
		}
		return $final;
	}

	public static function calcbleGroup($pid){
		$data = self::questions($pid);
		if(!$data) return array();
		$final = array();
		$project_info = self::projectInfo($pid);
		foreach ($project_info['qids_array'] as $gk => $pgroup) {
			if(!$pgroup['calcble'] ) continue;
			$gcode = $pgroup['gcode'];
			$final[$gcode] = $pgroup;
		}
		return $final;
	}
	
	public static function active($pid){
		$q_of_p = self::questions($pid);
		$project_info = self::projectInfo($pid);
		if(!$q_of_p || $project_info['status']!=0) return 'empty questions or error status';

		$dbname = self::$dbname;
		$tname = model_unit::tname($project_info['id']);
		$sid = $project_info['sid'];

		$sql = "CREATE TABLE `{$dbname}`.`{$tname}` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
					`stage` INT(2) NOT NULL DEFAULT '1', ";
		foreach ($q_of_p as $tq){
			if($tq['type'] == 'T' && $tq['parent_qid']) continue; //多选题的答案
			$sql .= "`{$sid}X{$tq['qid']}` ";
			if ($tq['type'] == 'S'||$tq['type'] == 'H'){$sql .= " varchar(300) NOT NULL DEFAULT '', ";} //短自由文本
			elseif ($tq['type'] == 'T'){$sql .= " text NOT NULL DEFAULT '', ";} //长自由文本
			elseif ($tq['type'] == 'L'){$sql .= " varchar(10) NOT NULL DEFAULT '', ";} //单选
			elseif ($tq['type'] == 'O'){$sql .= " varchar(10) NOT NULL DEFAULT '', `{$sid}X{$tq['qid']}_add` varchar(255) NOT NULL DEFAULT '', ";} //带评论的单选
			elseif ($tq['type'] == 'M'){$sql .= " varchar(200) NOT NULL DEFAULT '', ";} //多选
			elseif ($tq['type'] == 'P'){$sql .= " varchar(200) NOT NULL DEFAULT '', `{$sid}X{$tq['qid']}_add` varchar(255) NOT NULL DEFAULT '', ";} //带评论的多选题
			else {$sql .= " varchar(100) NOT NULL DEFAULT '',";}
		}
		$sql .= " PRIMARY KEY (`id`) );";
		$res = self::DB()->query($sql);
		firelog($sql, "Create Table:{$res}",__FILE__, __LINE__);
		$tname = getConf('dbinfo:prefix').self::$tname;
		self::DB()->update(self::$dbname, $tname, array('status'=>1), $pid);
		firelog($sql, "Active project create table sql", basename(__FILE__), __LINE__);
		return true;
	}

	public static function update($data, $where){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->update(self::$dbname, $tname, $data, $where);
	}

	public static function insert($data){
		$tname = getConf('dbinfo:prefix').self::$tname;
		return self::DB()->insert(self::$dbname, $tname, $data);
	}
}