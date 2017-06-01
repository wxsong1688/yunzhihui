<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends App_Controller
{
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 超级管理员：默认跳转到用户管理
	 * 普通管理员：默认跳转到用户管理
	 * 客服：默认跳转到平台资产管理
	 * 内部融资者: 
	 *    已认证：默认跳转到项目列表(只能看到自己发布的项目列表)
	 *    未认证：跳转到认证页面
	 */
	public function index()
	{
		#权限判断之后跳转到不同的菜单
		if(in_array($this->role_type,array(1,2,3,4)))
		{
			header('Location:/User/manager_index');exit;
		}	

		if($this->role_type == 7)
		{
			# 内部融资人，登录后判断是否实名认证
			$id_succ = $_GET['id_succ'];
			if($id_succ == 1){
				header('Location:/Project');exit;
			}else{
				header('Location:/Project/renzheng');exit;
			}
			
		}	

		
	}
}
