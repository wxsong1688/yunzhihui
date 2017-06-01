<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgotpwd extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		$data = $_GET;
		$data['uid'] 	= $this->getCookie('uid');
		$data['uname'] 	= $this->getCookie('uname');
		if(empty($userInfo)){
			$data['user'] = ''; 
		}else{
			$data['user'] = $userInfo['uname'];
		}
		$this->load->view('forgotpwd',$data);
	}

	public function submitInfo()
	{
		//数据过滤
		$phone		= $this->filter_input($_GET['phone']);
		$phonecode	= $this->filter_input($_GET['phonecode']);

		//短信验证
		$this->load->library('session');
		$phoneNum  	= $this->session->userdata('phoneNum_'.$phone);
		$phone_code = $this->session->userdata('phoneCode_'.$phone);

		if($phone!=$phoneNum or $phonecode!=$phone_code or empty($phone) or empty($phonecode)){
			echo "手机验证码输入错误。";exit;
		}else{
			$this->session->unset_userdata('phoneCode_'.$phone);
			$this->session->unset_userdata('phoneNum_'.$phone);
		}		
		
		//手机号验证
		if (!preg_match("/1[3458]{1}\d{9}/",$phone)){
		  	echo "手机号不能为空或格式不正确！";exit;
		}
		//手机号是否存在
		$result = $this->User_model->getUser(1, array('phone' => $phone));
		$result = !empty($result[0])?(array)$result[0]:array();
		if ( !isset($result['phone']) ){
		  	echo "手机号".$phone."不存在！";exit;
		}else{
			echo "success";exit;
		}		
	}

	function changePwd(){
		$this->load->view('changePwd',$data);	
	}

	//发送短信验证码
	function sendmsg()
	{
		$mobile = !empty($_POST['phone'])?$_POST['phone']:'';
		$code 	= $this->get_random_str(4,'num');
		$this->config->set_item('sess_expiration', 60*10);# 设置session的有效期，单位秒
        $this->load->library('session');
		$session_info = array(
      		'phoneCode_'.$mobile => $code,
      		'phoneNum_'.$mobile  => $mobile,
     	);
		$this->session->set_userdata($session_info);

		$send_title = $this->config->config['send_msg_title'];
		$send_kftel = $this->config->config['send_msg_kftel'];
		$send_text = $send_title."尊敬的用户，您正在通过该绑定手机号找回密码，验证码为".$code."，请勿将此验证码泄露给他人。如有疑问，请致电".$send_kftel."。";
		echo $this->sed_tpl_msg($mobile,$send_text);
	}

	//网页验证码
	function checkcode()
	{		
		$this->vCode(4, 30, 100); //4个数字，显示大小为15
	}

}