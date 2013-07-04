<?php
function __autoload($className){
	$className = trim($className, '_');
	$className = strtolower($className);
	$className = str_replace('_', '/', $className);
	$path = ROOT_PATH . '/core/' . $className . '.class.php';
	if ( file_exists($path) ){
		include $path;
	}
}

function getConf($key=NULL){
	static $config = NULL;
	if(!$config) {
		$config = include ROOT_PATH.'/conf/config.php';
	}
	if($key == NULL) return $config;
	// 通过冒号:获取配置层级
	$keys = explode(':', $key);
	$c = $config;
	foreach($keys as $k) {
		if(isset($c[$k])) $c = $c[$k];
		else return NULL;
	}
	return $c;
}

function array_extract ($arr, $keys) {
    $_arr = array();
    foreach( $keys as $key ) { if(isset($arr[$key])) $_arr[$key] = $arr[$key]; }
    return $_arr ;
}

function json_out($status, $msg=''){
	if($status) echo json_encode(array('status'=>$status, 'data'=>$msg));
	else echo json_encode(array('status'=>0, 'msg'=>$msg));
	exit();
}

function getItem($data, $key){
	$keys = explode(':', $key);
	foreach ($keys as $k) {
		if(!isset($data[$k])) return null;
		$data = $data[$k];
	}
	return $data;
}

function percent($p,$t){
	if(empty($t)){
		firelog($t, "percent error - p:{$p} / t:{$t}", __FILE__, __LINE__, 'error');
		return 0;
	}
	$data = round( $p/$t*100 , 1 );
	$data = number_format($data, 1);
	return strpos($data, '.') ? "{$data}%" : "{$data}.0%";
}

function calcIncrease($a, $b){
	if($a=='-'||$b=='-') return '-';
	$fix = strpos($a, '%') ? '%' : '';
	$i = trim($a,'%') - trim($b,'%');
	return number_format($i, 1).$fix;
}

/**
 * 获取题组中的问题编号
 */
function getList($sid,$groups,$type=2)
{
	
	static $qanda = array();
	
	if(empty($qanda)){
		$db = db::getInstance();
		//查出所有有评估值的问题及其答案的对应分值
		$sql = "SELECT DISTINCT `q`.`gid`, `q`.`type`, `a`.`qid` "
				." FROM `lime_answers` AS `a` "
				." LEFT JOIN `lime_questions` AS `q` ON `a`.`qid`=`q`.`qid` "
				." WHERE `q`.`sid`=$sid";
		$qanda = $db->queryRows($sql);
	}
	
	$list = array();
	
	switch($type)
	{
		case '1':
			foreach($qanda as $v){
					
					if('L' != $v['type'])
					{
						continue;
					}
					
					if(in_array($v['gid'],$groups)){
					
						$q_name = $sid."X".$v['gid']."X".$v['qid'];
						$list[] = $q_name;
						
					}else{
						continue;
					}
			}
		break;
		
		case '2':
			foreach($qanda as $v)
			{
					if('L' != $v['type'])
					{
						continue;
					}
					
					if(in_array($v['gid'],$groups)){
					
						$q_name = $sid."X".$v['gid']."X".$v['qid'];
						$list[$v['gid']][] = $q_name;
						
					}else{
						continue;
					}
			}
		break;
		
		case '3';
			foreach($qanda as $v){
			
					if('L' != $v['type'])
					{
						continue;
					}
										
					if(in_array($v['gid'],$groups)){
					
						$q_name = $sid."X".$v['gid']."X".$v['qid'];
						$list[$v['qid']] = $q_name;	
					}else{
						continue;
					}
			}
		break;
		
	}
	
	return $list;
}

//求该题组得分的最大值/个数，最小值/个数，平均值，众数,是否为对公
//		只有在第一板块才需要众数
//		对公问卷的第四部分只计算33家网点的
function getAllResult($sid,$q,$q_list,$action='yingjian')
{
	static $result = array();
	
	if(empty($result))
	
	{
		$db = db::getInstance();
		if(empty($q)){
			$sql = "SELECT * FROM `lime_survey_{$sid}`";
		}else{
			$sql = "SELECT * FROM `lime_survey_{$sid}` WHERE `qishu`='{$q}'";
		}
		$result = $db->queryRows($sql);
	}
	
	$total = 0;//test($q_list);
	$count = 0;
	$num = array(); //相同楼层统计次数
	$id = 0;//数据分组的依据：id or floor
	$floor = array();
	
	foreach($result as $one){
						
			$fen = 0;
			$full = 0;
			foreach($q_list as $v){
				
				if(99 != $one[$v]){$fen += $one[$v];$count++;}
				$full++;
			}
			$total += $fen;
			
			//统计单行元组数据
			switch($action)
			{
				case 'yingjian':
					$id = $one['floor'];
				break;
				
				case 'zhuangui':
					$id = $one['11342X32X116'];
				break;
				
				case 'canyin':
					$id = $one['24861X75X298'];
				break;
				
				case 'floorguanli':
					$id = $one['51963X101X412'];
				break;
				
				case 'shouyin':
					$id = $one['34771X107X441'];	
				break;
				
				default :                    //此处进行了更改
					$id = $one['id'];
				break;
			}
			
			if(isset($line[$id])){
				$line[$id]['total'] += $fen;
				$num[$id] += 1;
			}else{
				$line[$id]['total'] = $fen;
				$num[$id] = 1;
			}
			if(!in_array($id,$floor)){
				$floor[] = $id;
			}
			
			if(!isset($max)){
				$max = $min = $fen;
				$max_num = $min_num = 0;
			}
			
			if($max < $fen){
				$max = $fen;
				$max_num = 1;
			}elseif($max == $fen)
			{
				$max_num += 1;
			}
			
			if($min > $fen){
				$min = $fen;
				$min_num = 1;
			}elseif($min == $fen)
			{
				$min_num += 1;
			}
			
			$i++;
			
	}
	foreach($floor as $fid){
	
		$line[$fid]['total'] /= $num[$fid];
	}
	
	if($count != 0)$avg = round($total/$count,3);	//保留小数
	
	$res = array(
			'max'		=> $max,
			'max_num'	=> $max_num,
			'min'		=> $min,
			'min_num'	=> $min_num,
			'avg'		=> $avg,
			'line'		=> $line,
			'full'		=> $full
	);

	return $res;
}
/**
 * 中文截取，支持gb2312,gbk,utf-8,big5
 * Enter description here ...
 * @param string $str 要截取的字串
 * @param int $start 截取起始位置
 * @param int $length 截取长度
 * @param string $charset utf-8|gb2312|gbk|big5 编码
 * @param $suffix 是否加尾缀
 */
function getThrstr($str, $length, $start=0, $charset="utf-8", $suffix=false)
{
	if(function_exists("mb_substr"))
	return mb_substr($str, $start, $length, $charset);

	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

/**
 * 分页函数
 * Enter description here ...
 * @param unknown_type $page
 * @param unknown_type $pages
 * @param unknown_type $url_get
 */
function pages($pages,$page,$url_get,$class="current")
{
	$first = 1;
	$prev = $page-1;
	$next = $page+1;
	$last = $pages;
	
	$depages='<p>&nbsp;</p><div style="text-align:center">';
	
	if($page>1)
	{
		$depages .= "&nbsp;<a href='?$url_get&page=$first'>首页</a>&nbsp;";
		$depages .= "&nbsp;<a href='?$url_get&page=$prev'>上一页</a>&nbsp;";
	}
	//本页前相邻页码
	if($page>=5)
	{
		$depages .="&nbsp;<a href='?$url_get&page=1'>1</a>&nbsp;...";
	}
	if($page<5)
	{
		for($i=1;$i<$page;$i++)
			$depages .="&nbsp;<a href='?$url_get&page=$i'>$i</a>&nbsp;";
		$depages .= "&nbsp;<span class='$class'>$i</span>&nbsp;";
	}else{
		for($i=$page-2;$i<$page;$i++)
			$depages .="&nbsp;<a href='?$url_get&page=$i'>$i</a>&nbsp;";
		$depages .= "&nbsp;<span class='$class'>$i</span>&nbsp;";
	}
	//本页后相邻页码
	if($page>=$pages-3 && $page<$pages)
	{
		for($i=$page+1;$i<$pages;$i++)
			$depages .= "&nbsp;<a href='?$url_get&page=$i'>$i</a>&nbsp;";
	}else{
		for($i=$page+1;$i<$page+2 && $page<$pages;$i++)
			$depages .="&nbsp;<a href='?$url_get&page=$i'>$i</a>&nbsp;";
		if($page<$pages)
			$depages .= "&nbsp;<a href='?$url_get&page=$i'>$i</a>&nbsp;...";
	}
	
	if($page<$pages) $depages .="&nbsp;<a href='?$url_get&page=$pages'>$pages</a>&nbsp;";
	if($page<$pages)
	{
		$depages .= "&nbsp;<a href='?$url_get&page=$next'>下一页</a>&nbsp;";
		$depages .= "&nbsp;<a href='?$url_get&page=$last'>尾页</a>&nbsp;";
	}
	$depages .= "</div><p>&nbsp;</p><div style=\"float:none;text-align:center\"><p>($page/$pages)&nbsp;&nbsp;页记录</p></div>";
	return $depages;
}