<?php
class model_calculate extends model_base{

	public static function tname($pid){
		if(!$pid) return NULL;
		return getConf('dbinfo:prefix')."calc_{$pid}";
	}

	public static function Gets($pid, $where=array(), $option=array()){
		$tname = self::tname($pid);
		return self::DB()->select(self::$dbname, $tname, $where, $option);
	}

	/*
	 * 获取某题组或者某道题满分
		$full[$pid] = array(
				'gid1'=>array('qid1'=>1, 'qid2'=>2), 
				'gid2'=>array('qid3'=>1, 'qid3'=>2), 
		);
	*/
	public static function getFull($pid, $key=null){
		static $full = array();
		if(!isset($full[$pid]) || !$full[$pid]){
			 $temp = model_project::projectInfo($pid);
			 $full[$pid] = $temp['fullscore_array'];
		}
		/*if($key==null){ //返回问卷满分
			$total = 0;
			foreach ($full[$pid] as $gcode => $qids) {
				$total += array_sum($qids);
			}
			return $total;
		}*/
		$data = getItem($full[$pid], $key);
		return is_array($data) ? array_sum($data) : $data;
	}

	//统计某一项目中各期，各个门店的答卷得分，插入到统计得分表中
	//表中每一行对应一个门店在某一期的得分：总分，分组得分，每道题得分
	public static function calculate($pid){
		$project_info = model_project::projectInfo((int)$pid);
		if(!$project_info) return false;
		
		/**
		 *  - 项目一旦启用，题组中的问题(qid)以及组编码(gcode)便不能更改
		 * 		只能改动题组的顺序和题组title，否则报错
		 *  - 若是第一次统计则建立统计表
		 */
		$tname = self::tname($pid);
		$dbname = self::$dbname;
		if(!self::DB()->query("SHOW TABLES FROM `{$dbname}` LIKE '{$tname}'")){
			$res = self::createCalcTable($pid);
			if($res === false) return false;
		}
		
		$datas = model_unit::Gets($pid);
		if(!$datas){
			firelog('empty data', "Pid:{$pid}", __FILE__, __LINE__, 'error');
			return false;
		}
		foreach ($datas as $k => $item) {
			$itemScore = self::calcItem($pid, $item);
			$itemScore['id'] = $item['id'];
			$itemScore['stage'] = $item['stage'];
			
			self::DB()->replace(self::$dbname, $tname, $itemScore, (int)$item['id']);
		}
		return true;
	}

	/**
	 * 创建统计表
	 */
	private static function createCalcTable($pid){
		$project_info = model_project::projectInfo((int)$pid);
		if(!$project_info || $project_info['status']<1) return false;
		
		$dbname = self::$dbname;
		$sid = $project_info['sid'];
		$tname = self::tname($pid);
		self::DB()->query("DROP TABLE IF EXISTS `{$dbname}`.`{$tname}`");

		$sql = "CREATE TABLE `{$dbname}`.`{$tname}` (
					`id` INT(11) UNSIGNED NOT NULL, 
					`stage` INT(2) NOT NULL DEFAULT '1', 
					`total` INT(4) NOT NULL DEFAULT '0', ";
		$sqlg = "";
		$sqlq = "";
		foreach ($project_info['qids_array'] as $gk => $group) {
			if(!$group['calcble']) continue; //不用计算得分
			$sqlg .= "`{$group['gcode']}` INT(4) NOT NULL DEFAULT -1, ";
			foreach ($group['qids'] as $k => $qid) {
				$sqlq .= "`{$sid}X{$qid}` INT(4) NOT NULL DEFAULT -1, ";
			}
		}
		$sql .= $sqlg . $sqlq ." PRIMARY KEY(`id`)) CHARSET=utf8";
		firelog($sql, 'Create calculate Table', __FILE__, __LINE__);
		return self::DB()->query($sql);
	}

	/**
	 * 计算某一条记录的得分：总分，各组得分，每道题得分
	 */
	private static function calcItem($pid, $itemData){
		$project_info = model_project::projectInfo($pid);
		if(!$project_info || empty($project_info['qids_array']) || !$itemData) return false;

		$itemScore = array('total'=>0);
		foreach ($project_info['qids_array'] as $gk => $group) {
			if(!$group['calcble']) continue; //不用计算得分
			$itemScore[$group['gcode']] = 0;
			foreach ($group['qids'] as $k => $qid) {
				$k = "{$project_info['sid']}X{$qid}";
				$tacode = $itemData[$k];
				$tScore = model_answer::getItem("{$qid}:{$tacode}:value");
				if($tScore == null){
					$itemScore[$k] = -1;
				}else{
					$itemScore[$k] = (int)$tScore;
					$itemScore[$group['gcode']] += (int)$tScore;
					$itemScore['total'] += (int)$tScore;
				}
			}
		}
		return $itemScore;
	}

	//统计满分
	// $full = array( 'total'=>100, 'group1'=>array('1'=>2, '2'=>2), 'group2'=>array('3'=>2, '4'=>5) );
	public static function calcFullScore($pid){
		$project_info = model_project::projectInfo($pid);
		if(!$project_info || empty($project_info['qids_array']) ){
			firelog('fail to get project qids info', 'calc full scores', __FILE__, __LINE__, 'error');
			return false;
		}

		$fulls = array('total'=>0);
		foreach ($project_info['qids_array'] as $gk => $group) {
			if(!$group['calcble']) continue;
			$k = $group['gcode'];
			$fulls[$k] = array();
			$group_full = 0; //组满分
			foreach ($group['qids'] as $qk => $qid) {
				$answers = model_answer::getItem($qid);
				$full = 0;
				foreach ($answers as $ak => $answer) {
					if($answer['value']>$full) $full = $answer['value'];
				}
				$fulls[$k][$qid] = $full;
				$fulls['total'] += $full;
			}
		}
		return $fulls;
	}
}