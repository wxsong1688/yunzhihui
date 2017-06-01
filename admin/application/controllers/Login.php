<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller
{
	/**
	 * Index Page for this controller.
	 * 后台用户权限判断
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
	}

	public function index()
	{
		$this->load->view('login');
	}

	# 验证用户名和密码
	public function authzUser()
	{
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$res = $this->login_model->getUserInfo($username , md5($password));

		if(empty($res)){
			# 登录失败提示错误
			echo json_encode(array('code'=>0,'msg'=>'用户名或密码错误！'));exit;
		}else{
			# 登录成功直接跳转到主页
			$last_access = time() + 24*3600;
			setcookie('yzh_admin_uid', $res[0]['uid'], $last_access, '/');
			setcookie('yzh_admin_username', $res[0]['username'], $last_access, '/');
			setcookie('yzh_admin_realname', $res[0]['realname'], $last_access, '/');
			setcookie('yzh_admin_roletype', md5($res[0]['type'].'_role_type'), $last_access, '/');
			
			echo json_encode(array('code'=>1,'msg'=>'登录成功！','id_succ'=>$res[0]['id_succ']));exit;
		}
	}

	function logout()
	{
		$username = $this->username;
		setcookie('yzh_admin_uid', '',time()-3600, '/');
		setcookie('yzh_admin_username', '',time()-3600, '/');
		setcookie('yzh_admin_realname', '',time()-3600, '/');
		setcookie('yzh_admin_roletype', '',time()-3600, '/');
		// setcookie('showUserMenu','',time()-3600, '/');
		// setcookie('showAssetMenu','',time()-3600, '/');
		// setcookie('showProMenu','',time()-3600, '/');
		header("Location:/Login");
	}
}
