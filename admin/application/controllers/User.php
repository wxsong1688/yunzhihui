<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends App_Controller
{
	/*用户管理权限：
	 1.超级管理员是我们直接添加在数据库的。只有用户名和密码；
	 2.超级管理员可以添加普通管理员，客服，内部融资人。也可以修改。（身份证号和真实姓名不可以修改）
	 3.普通管理员可以添加客服，内部融资人。也可以修改。（身份证号和真实姓名不可以修改）
	 用户项：
	 1.超级管理员添加普通管理员和客服的时候，必填项包括：用户名，密码
	 2.管理员添加内部融资人的时候，必填项包括：用户名，密码，电话号码，真实姓名(需要实名认证)，身份证号；选填项包括：电子邮箱
	 3.用户名，手机号，邮箱需要验证唯一性
	*/

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Asset_model');
	}

	# 管理员管理列表
	public function manager_index()
	{
		# 根据用户角色判断可以查看的用户组
		$data        = $_GET;
		$search_data = $this->_getSearchData($data);
		$search_data['type'] = "1,2,3,4";

		$count              	  = $this->User_model->getListsCount($search_data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;
		
		$res = $this->User_model->getLists($search_data,$offset);
		$list['list'] = $res;
		$list['search_data'] = $search_data;
		$page['pageinfo']    = $pageinfo;
		$list['userinfo']	 = $this->userinfo;
		$list['total_count'] = $count;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_manager_list',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	# 内部融资人管理列表
	public function finance_index()
	{
		# 根据用户角色判断可以查看的用户组
		$data        = $_GET;
		$search_data = $this->_getSearchData($data);
		$search_data['type'] = "7";

		$count              	  = $this->User_model->getListsCount($search_data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;
		
		$res = $this->User_model->getLists($search_data,$offset);
		$list['list'] = $res;
		$list['search_data'] = $search_data;
		$page['pageinfo']    = $pageinfo;
		$list['userinfo']	 = $this->userinfo;
		$list['total_count'] = $count;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_finance_index',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	# 投资者管理列表
	public function invest_index()
	{
		# 根据用户角色判断可以查看的用户组
		$data        = $_GET;
		$search_data = $this->_getSearchData($data);
		$search_data['type'] = "5";

		$count              	  = $this->User_model->getListsCount($search_data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;
		
		$res = $this->User_model->getLists($search_data,$offset);
		$list['list'] = $res;
		$list['search_data'] = $search_data;
		$page['pageinfo']    = $pageinfo;
		$list['userinfo']	 = $this->userinfo;
		$list['total_count'] = $count;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_invest_index',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	public function level()
	{
		$res = $this->User_model->getUserLevel();
		$lists['lists']    = $res;
		$lists['userinfo'] = $this->userinfo;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_level',$lists);
		$this->load->view('footer');
	}

	public function editLevel()
	{
		$data = $_GET;
		$updateData[1]['min']  = $data['level1_min'];
		$updateData[1]['max']  = $data['level1_max'];
		$updateData[1]['name'] = $data['level1_name'];
		$updateData[2]['min']  = $data['level2_min'];
		$updateData[2]['max']  = $data['level2_max'];
		$updateData[2]['name'] = $data['level2_name'];
		$updateData[3]['min']  = $data['level3_min'];
		$updateData[3]['max']  = $data['level3_max'];
		$updateData[3]['name'] = $data['level3_name'];
		$updateData[4]['min']  = $data['level4_min'];
		$updateData[4]['max']  = $data['level4_max'];
		$updateData[4]['name'] = $data['level4_name'];
		$updateData[5]['min']  = $data['level5_min'];
		$updateData[5]['max']  = $data['level5_max'];
		$updateData[5]['name'] = $data['level5_name'];

		$this->User_model->updateUserLevel($updateData);
		header("Location:/User/level");
	}

	public function addUser()
	{
		$from = $_GET['from'];

		$info['from']     = $from?$from:1;
		$info['userinfo'] = $this->userinfo;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_add',$info);
		$this->load->view('footer');
	}

	public function addUserData()
	{
		$data = $_POST;

		$addData['username']   = trim($data['username']);
		$addData['pwd1'] 	   = md5(trim($data['pwd1']));
		$addData['create_time'] = date("Y-m-d H:i:s");
		$addData['type']        = intval($data['type']);
		if($addData['type'] == 7)
		{
			$addData['phone']    = trim($data['phone']);
			$addData['email']    = trim($data['email']);
		}
		
		$res = $this->User_model->addUser($addData);
		if($res){
			$action_from = $data['action_from'];
			if($action_from == 1){
				header('Location:/User/manager_index');
			}else{
				header('Location:/User/finance_index');
			}
		}else{
			echo '<script language="JavaScript">;alert("添加失败");history.back(-1);</script>;';
		}
		exit();
	}

	function editUser()
	{
		$uid = $_GET['uid'];
		$data['uid'] = $uid;

		$res = $this->User_model->getLists($data);
		$list['item'] 		  = $res;

		$list['uinfo'] = $this->userinfo;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_edit',$list);
		$this->load->view('footer');

	}

	public function editUserData()
	{
		$data     = $_POST;
		$editData = array();

		if(isset($data['username'])){
			$editData['username']   = trim($data['username']); 
		}
		if(isset($data['pwd1']) && !empty($data['pwd1'])){
			$editData['pwd1'] 	   = md5(trim($data['pwd1'])); 
		}
		if(isset($data['type'])){
			$editData['type'] 	   = intval($data['type']); 
		}
		if(isset($data['phone'])){
			$editData['phone'] 	   = trim($data['phone']); 
		}
		if(isset($data['email'])){
			$editData['email'] 	   = trim($data['email']); 
		}
		
		$res = $this->User_model->editUser($editData,intval($data['uid']));
		if($res){
			header('Location:/User/manager_index');
		}else{
			echo '<script language="JavaScript">;alert("修改失败");history.back(-1);</script>;';
		}
		exit();
	}

	private function _getSearchData($data)
	{
		$searchData = array();
		if(!empty($data['username']))
		{
			$searchData['username'] = urldecode($data['username']); 
		}
		if(!empty($data['time_start'])){
			$searchData['time_start'] = trim($data['time_start']);
		}
		if(!empty($data['time_end'])){
			$searchData['time_end'] = trim($data['time_end']);
		}
		if(!empty($data['realname']))
		{
			$searchData['realname'] = urldecode($data['realname']); 
		}
		if(!empty($data['phone']))
		{
			$searchData['phone'] = $data['phone']; 
		}
		if(!empty($data['identify']))
		{
			$searchData['identify'] = $data['identify']; 
		}
		if(!empty($data['level']))
		{
			$searchData['level'] = $data['level']; 
		}
		return $searchData;
	}

	# 验证用户信息的唯一性(包括用户名，手机号，邮箱)
	public function checkUserInfo()
	{
		$uid 		= isset($_POST['uid']) ? intval($_POST['uid']) : '';
		$username 	= isset($_POST['username']) ? trim(urldecode($_POST['username'])) : '';
		$phone      = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$email      = isset($_POST['email']) ? trim($_POST['email']) : '';

		$res = array();
		if(!empty($username)){
			$user_res = $this->_checkUserUnique('username',$username,$uid);
			$res['username'] = $user_res;
		}
		if(!empty($phone)){
			$phone_res = $this->_checkUserUnique('phone',$phone,$uid);
			$res['phone'] = $phone_res;
		}
		if(!empty($email)){
			$email_res = $this->_checkUserUnique('email',$email,$uid);
			$res['email'] = $email_res;
		}
		exit(json_encode($res));
	}

	# 验证用户名,邮箱，手机号是否重复
	private function _checkUserUnique($type,$value,$uid='')
	{
		$data = array($type=>$value);
		$res  = $this->User_model->getUserInfoByUnique($data,$uid);
		if(empty($res)){
			return false; # 不存在
		}
		return true; # 已存在
	}

	public function editUserPwd()
	{
		$uid = $this->userinfo['uid'];
		$data['uid'] = $uid;

		$res = $this->User_model->getLists($data);
		$list['item'] 		  = $res;

		$list['uinfo'] = $this->userinfo;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_editpwd',$list);
		$this->load->view('footer');
	}

	public function editUserPwdData()
	{
		$data     = $_POST;
		$uid      = intval($data['uid']);

		if(empty($uid)){
			echo '<script language="JavaScript">;alert("修改失败");history.back(-1);</script>;';
			exit();
		}
		# 校验原密码是否正确
		$userinfo = $this->User_model->getUserNamesByUids($uid);
		if(md5($data['pwd']) != $userinfo[0]['pwd1']){
			echo '<script language="JavaScript">;alert("原密码错误");history.back(-1);</script>;';
			exit();
		}

		$editData = array();
		if(isset($data['pwd1']) && !empty($data['pwd1'])){
			$editData['pwd1'] 	   = md5(trim($data['pwd1'])); 
		}
		
		$res = $this->User_model->editUser($editData,$uid);
		if($res){
			header('Location:/Asset/account');
		}else{
			echo '<script language="JavaScript">;alert("修改失败");history.back(-1);</script>;';
		}
		exit();
	}

	public function recharge(){
		$uid = $this->userinfo['uid'];
		$data['uid'] = $uid;

		$userInfo 		= $this->User_model->getLists($data);
		$userAccount 	= $this->Asset_model->getTendereeAccount($data);
		$userBank	 	= $this->User_model->getUserBank($data);

		$list['userInfo'] 		= $userInfo[0];
		$list['userAccount'] 	= !empty($userAccount) ? $userAccount[0] : array() ;
		$list['userBank'] 		= !empty($userBank) ? $userBank : array() ;

		$list['uinfo'] = $this->userinfo;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_recharge',$list);
		$this->load->view('footer');
	}

	public function withdrawals(){
		$uid = $this->userinfo['uid'];
		$data['uid'] = $uid;

		$userInfo 		= $this->User_model->getLists($data);
		$userAccount 	= $this->Asset_model->getTendereeAccount($data);
		$userBank	 	= $this->User_model->getUserBank($data);

		$list['userInfo'] 		= $userInfo[0];
		$list['userAccount'] 	= $userAccount[0];
		$list['userBank'] 		= $userBank;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('user/user_withdrawals',$list);
		$this->load->view('footer');
	}


}
