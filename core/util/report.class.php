<?php
class util_report{
	//获取某期，某个区域，某题（题组）的得分
	public static function score_of_acode($stage, $qcode, $acode){
		$stage_info = model_stage::getItem($stage);
		if(!$stage_info){
			firelog($stage, 'stage error', __FILE__, __LINE__, 'error');
			return -1;
		}
		$data = model_statistics::get($stage_info['pid'], $stage_info['stage'], 'areaaverage');
		return (is_array($data[$acode]) && array_key_exists($qcode, $data[$acode])) ? $data[$acode][$qcode] :-1;
	}

	//获取某期，指定某些|某个门店，某题（题组）的得分|平均分
	public static function score_of_mendians($stage, $qcode, $acode, $mendians='all'){
		$stage_info = model_stage::getItem($stage);
		if(!$stage_info){
			firelog($stage, 'stage error', __FILE__, __LINE__, 'error');
			return -1;
		}
		if(is_array($mendians)){
			$data = model_statistics::get($stage_info['pid'], $stage_info['stage'], 'byqids');// var_dump($data);die;
			$data = $data[$qcode][$acode]; $total = array();
			foreach ($data as $mcode => $value) {
				if(!in_array($mcode, $mendians)) continue;
				$total[] = $value;
			}
			$c = count($total); $s = array_sum($total);
			if($c==0) return -1;
			return $s/$c;
		}elseif($mendians=='all'){
			return self::score_of_acode($stage, $qcode, $acode);
		}else{
			$data = model_statistics::get($stage_info['pid'], $stage_info['stage'], 'byqids');
			return $data[$qcode][$acode][$mendians];
		}
	}
	
	//计算指定某些区域 某道题的得分
	public static function score_of_acodes($stage, $qcode, $acodes='all'){
		$stage_info = model_stage::getItem($stage);
		if(!$stage_info){
			firelog($stage, 'stage error', __FILE__, __LINE__, 'error');
			return -1;
		}
		if(is_array($acodes)){
			$data = model_statistics::get($stage_info['pid'], $stage, 'byqids');
			$data = $data[$qcode];
			$total = array();
			foreach($data as $acode=>$ascores){
				if(!in_array($acode, $acodes)) continue;
				$total = array_merge($total, $ascores);
			}
			$c = count($total); $s = array_sum($total);
			if($c==0) return -1;
			return number_format($s/$c, 3);
		}else{
			$data = model_statistics::get($stage_info['pid'], $stage, 'allaverage');
			return number_format($data[$qcode], 3);
		}
	}
	
	//获取指定某些区域中 某道题的得分数组，用于分析
	public static function scoresArray($stage, $qcode, $acodes='all'){
		$stage_info = model_stage::getItem($stage);
		if(!$stage_info){
			firelog($stage, 'stage error', __FILE__, __LINE__, 'error');
			return array();
		}
		$data = model_statistics::get($stage_info['pid'], $stage_info['stage'], 'byqids');
		$data = $data[$qcode]; $final = array();
		if(!$data){
			firelog($qcode, 'qcode error:', __FILE__, __LINE__, 'error');
			return array();
		}

		foreach ($data as $acode => $ascores) {
			if(is_array($acodes) && !in_array($acode, $acodes)) continue;
			$final = array_merge($final, $ascores);
		}
		return $final;
	}
	//计算某期短板 或者 优势
	public static function character($stage, $style="last",$top=10, $detail=false, $areas='all'){
		$cstage = model_stage::getItem($stage);
		if(!$cstage) return false;

		$questions = model_project::calcableQuestions($cstage['pid']);
		$data = array();
		foreach ($questions as $qinfo) {
			$qcode = "{$qinfo['sid']}X{$qinfo['qid']}";
			$full = model_calculate::getFull($cstage['pid'], "{$qinfo['gcode']}:{$qinfo['qid']}");
			$temp = util_report::score_of_acodes($cstage['stage'], $qcode, $areas);
			$temp = ($temp==-1) ? '-' : percent($temp, $full);
			$data[$qcode] = array(
				'question' => "{$qinfo['title']}.{$qinfo['question']}",
				'defenlv' => $temp,
				'qinfo' => $qinfo);
		}
		$data = ($style=="last") ? util_sort::bubbleSort($data, 'defenlv', 'A') : util_sort::bubbleSort($data, 'defenlv', 'D');
		$final = array();
		if($detail){
			 //$data[$qcode]['qinfo'] = $qinfo;
			 foreach ($data as $qcode => $info) {
				$final[$qcode] = array('q'=>$info['question'], 'v'=>$info['defenlv'], 'i'=>$info['qinfo']);
				if(count($final)==$top) break;
			}
		}else{
			foreach ($data as $qcode => $info) {
				$final[$qcode] = array('q'=>$info['question'], 'v'=>$info['defenlv']);
				if(count($final)==$top) break;
			}
		}
		return $final;
	}
	
	//计算门店的短板优势
	public static function second_character($stage, $style="last", $top=10, $detail=false, $mendians='all'){
		$cstage = model_stage::getItem($stage);
		if(!$cstage) return false;

		$questions = model_project::calcableQuestions($cstage['pid']);
		$data = array(); $acode = model_area::cacode();
		foreach ($questions as $qinfo) {
			$qcode = "{$qinfo['sid']}X{$qinfo['qid']}";
			$full = model_calculate::getFull($cstage['pid'], "{$qinfo['gcode']}:{$qinfo['qid']}");
			$temp = util_report::score_of_mendians($cstage['stage'], $qcode, $acode, $mendians);
			$temp = ($temp==-1) ? '-' : percent($temp, $full);
			$data[$qcode] = array(
				'question' => "{$qinfo['title']}.{$qinfo['question']}",
				'defenlv' => $temp,
				'qinfo' => $qinfo);
		}
		$data = ($style=="last") ? util_sort::bubbleSort($data, 'defenlv', 'A') : util_sort::bubbleSort($data, 'defenlv', 'D');
		$final = array();
		if($detail){
			 //$data[$qcode]['qinfo'] = $qinfo;
			 foreach ($data as $qcode => $info) {
				$final[$qcode] = array('q'=>$info['question'], 'v'=>$info['defenlv'], 'i'=>$info['qinfo']);
				if(count($final)==$top) break;
			}
		}else{
			foreach ($data as $qcode => $info) {
				$final[$qcode] = array('q'=>$info['question'], 'v'=>$info['defenlv']);
				if(count($final)==$top) break;
			}
		}
		return $final;
	}

	//字段说明
	public function introInfo($keys){
		$data = array();
		foreach ($keys as $key) {
			$temp = getConf("intro_infos:{$key}");
			$temp = explode('#', $temp);
			if($temp) $data[] = "<strong>{$temp[0]}</strong><p>{$temp[1]}</p>";
		}
		return $data;
	}
}