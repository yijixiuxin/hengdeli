<?php
class user extends base{
	public function __construct(){
		parent::__construct();
		if(self::$context['cuser']['level']>=10){
			self::$tpl->assign('manage_user', true);
		}elseif($_GET['a']!='index'){
			header('location:404.php');
			exit();
		}
	}

	public function index(){
		if(isset($_POST['oldpwd']) && $_POST['oldpwd']){
			if($_POST['pwd'] != $_POST['pwd1']){
				$message = '两次密码不一致';
			}elseif( model_user::login(self::$context['cuser']['user'], $_POST['oldpwd'])==false ){
				$message = '旧密码错误';
			}else{
				$uid = (int)self::$context['cuser']['id'];
				$res = model_user::update(array('pwd'=>$_POST['pwd']), $uid);
				$message = empty($res)?'密码修改失败！':'密码修改成功！';
			}
			self::$tpl->assign( 'message', $message );
		}
		$this->display('user.tpl');
	}
	
	public function manage(){
		$user_list = model_user::Gets();
		$gaoceng = $qudao = array();
		if($user_list){
			foreach($user_list as $k=>$user){
				$user['group'] = model_user::levelToGroup($user['level']);
				if($user['level'] < 10){ //渠道用户
					$user['ainfo'] = model_uanda::getArea($user['id']);
					$qudao[] = $user;
				}elseif($user['level'] == 10){
					$gaoceng[] = $user;
				}
			}
		}
		self::$tpl->assign('qudao', $qudao);
		self::$tpl->assign('gaoceng', $gaoceng);
		
		$able_areas = model_area::getItem();
		self::$tpl->assign('able_areas', $able_areas);
		$this->display('quanxian.tpl');
	}
	
	public function edit(){
		$data = model_user::Gets((int)$_REQUEST['id']);
		$temp = model_uanda::getArea($data['id']);
		$data['acode'] = $temp['code'];
		json_out(1, $data);
	}
	
	public function delete(){
		$data = model_user::Gets((int)$_REQUEST['id']);
		if($data['level'] > 10) json_out(0, ' 超级管理员不能删除');
		$res = model_user::delete($data['id']);
		if($res) json_out(1);
		json_out(0, '未知错误');
	}
	
	public function update(){
		if(empty($_POST['name'])) json_out(0, ' 用户名不能为空');
		if(!$_POST['id'] && empty($_POST['pwd'])) json_out(0, ' 添加用户，密码不能为空');
		if($_POST['chgpwd'] && empty($_POST['pwd'])) json_out(0, ' 修改密码，密码不能为空');
		
		$userData = array(
			'user' => $_POST['name'],
			'name' => $_POST['name'],
			'level' => $_POST['level'],
		);
		if(($_POST['id'] && $_POST['chgpwd']) || !$_POST['id']){
			if(strlen($_POST['pwd'])<5) json_out(0, ' 密码长度不能小于5');
			$userData['pwd'] = $_POST['pwd'];
		}
		
		$user = model_user::Gets(array('name'=>$userData['name']));
		
		if($_POST['id']){ //modify
			$id = (int)$_POST['id'];
			if($user && $user[0]['id']!= $id) json_out(0, ' 该用户已经存在');
			model_user::update($userData, $id);
		}else{
			if($user) json_out(0, ' 该用户已经存在');
			$id = model_user::insert($userData);
		}
		if($_POST['level'] < 10) model_uanda::update($id, $_POST['acode']);
		else model_uanda::update($id, 0);
		
		json_out(1);
	}
}