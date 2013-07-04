<?php
/**
 * controller 基类
 */
class base{
	public static $page_title = '';
	public static $page_style = '';
	public static $location = array();
	public static $tpl = null;
	public static $context = array(
					'cuser' => null,
					'cstage' => null,
					'cyear' => 2013,
					'carea' => null,
					'cmendian' => null,
			);

	public function __construct(){
		self::$context['cstage'] = model_stage::cstage();
		if(!self::$context['cuser']) self::$context['cuser'] = model_user::cuser();
		/*if($_REQUEST['mendian']){
			self::$context['cmendian'] = model_mendian::Get( $_REQUEST['mendian'] );
			$acode = self::$context['cmendian']['acode'];
			self::$context['carea'] = model_area::Gets(array('code'=>$acode));
		}elseif($_REQUEST['area']){
			self::$context['carea'] = model_area::getItem($_REQUEST['area']);
		}*/

		$stages = model_stage::stages();
		self::$tpl->assign('stages', $stages);
	}


	public function nav(){
		$user_info = model_user::cuser();
		//$navs = array(3, 4);$navs = model_nav::Gets(array('id'=>$navs));
		$navs = model_nav::Gets(array('status'=>1)); //status1是一级菜单 status2是隐藏菜单
		firelog($navs, 'All Naves In DB', __FILE__, __LINE__);
		foreach ($navs as $k => $nav) {
			if($user_info['level'] > $nav['level']) continue;
			unset($navs[$k]);
		}
		self::$tpl->assign('navs', $navs);
	}
	
	
	public function display($page){
		$this->nav();
		self::$tpl->assign('context', self::$context);
		self::$tpl->assign('page_title', self::$page_title);
		self::$tpl->assign('location', self::$location);
		self::$tpl->assign('page_style', self::$page_style);
		self::$tpl->display($page);
		exit();
	}
}
base::$page_title = getConf('siteinfo:name');
base::$tpl = Template::get();