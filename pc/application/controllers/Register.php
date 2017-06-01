<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends Base_Controller
{
	
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
	}
	
	public function index() 
	{
		$uid = $this->getCookie('uid_cookie');
		if( $uid )
		{
			$userInfo = $this->User_model->getUser( array("uid" => $uid) );
			if( $userInfo )
			{
				$userInfo = (array)$userInfo[0];
				if( $userInfo['username'] )
				{
					$data['user'] = $userInfo['username'];
				}else{
					$data['user'] = $userInfo['phone'];
				}
			}
		}else{
			$data['user'] = ''; 
		}

		//系统通知
		$data['systemNotice'] = array();

		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();
		
		$this->load->view('/public/header', $data);
		$this->load->view('/userCenter/userRegister');
	}

	function checkPiccode(){
		$this->load->library('session');
		$piccode= $this->filter_input($_POST['piccode']);
		$s_code = $this->session->userdata('VerifyCode');
		if(empty($piccode) or strtoupper($piccode)!=strtoupper($s_code)){
			//防用户恶意请求
			echo json_encode(array("code" => 1,"msg" => "请正确输入图片验证码！"));exit;
		}else{
			echo json_encode(array("code" => 0,"msg" => "sucess"));exit;
		}
	}

	//注册信息
	function register()
	{
		//数据过滤
		$phone		= $this->filter_input($_GET['phone']);
		$username	= $this->filter_input($_GET['username']);
		$pwd1 		= $this->filter_input($_GET['pwd1']);
		$repwd		= $this->filter_input($_GET['repwd']);
		$phonecode	= $this->filter_input($_GET['phonecode']);
		//$sendcode	= $this->filter_input($_GET['sendcode']);

		//短信验证
		$this->load->library('session');
		$phoneNum  	= $this->session->userdata('phoneNum_'.$phone);
		$phone_code = $this->session->userdata('phoneCode_'.$phone);
		/*$send_code 	= $this->session->userdata('sendCode_'.$phone);
		if(empty($send_code) or $sendcode!=$send_code){
			//防用户恶意请求
			echo "请求超时，请刷新页面后重试！";exit;
		}*/

		/*if($phone!=$phoneNum or $phonecode!=$phone_code or empty($phone) or empty($phonecode)){
			echo "手机验证码输入错误。";exit;
		}else{
			$this->session->unset_userdata('phoneCode_'.$phone);
			//$this->session->unset_userdata('sendCode_'.$phone);
			$this->session->unset_userdata('phoneNum_'.$phone);
		}*/		
		
		/*if( $checkcode != $phonecode ){
			echo "短信验证码不能为空或格式不正确！";exit;
		}*/
		//密码验证
		if ( !preg_match("/[^\s]{6,20}/",$pwd1) ){
		  	echo "密码不能为空或格式不正确！";exit;
		}
		//确认密码验证
		if ( $pwd1 != $repwd ){
		  	echo "密码与确认密码不匹配！";exit;
		}
		//手机号验证
		if (!preg_match("/1[34578]{1}\d{9}/",$phone)){
		  	echo "手机号不能为空或格式不正确！";exit;
		}
		//手机号是否存在
		$result = $this->User_model->getUser(array('phone' => $phone));
		$result = !empty($result[0])?(array)$result[0]:array();
		if ( isset($result['phone']) ){
		  	echo "手机号".$phone."已经存在！";exit;
		}

		$data = array(
			'phone' 	=> $phone,
			'username' 	=> $username,
			'pwd1' 		=> md5($pwd1),
			'type'		=> 5,
			'create_time' => date("Y-m-d H:i:s",time())				
		);

		//数据入库
		$result = $this->User_model->addUser($data);
		$userinfo = $this->User_model->getUser(array('phone' => $phone));
		//记录cookie
		$this->setCookie('uid_cookie', $userinfo[0]->uid, 60000);
		$this->setCookie('uname_cookie', $userinfo[0]->phone, 60000);
		if($result){
			$send_title = $this->config->config['send_msg_title'];
			$sys_msg    = "尊敬的用户，恭喜您已成功注册为云智慧金融平台的用户。";
			$send_text  = $send_title.$sys_msg;
			$this->sed_tpl_msg($phone,$send_text);
			$this->sed_sys_msg($userinfo[0]->uid,"注册成功",$sys_msg);
			echo 'success';exit;
		}else{
			echo "注册失败";exit;
		}
		exit;
	}

	//用户注册成功提示页面
	function regsuc(){
		$data = $_GET;
		if(isset($data['p'])){
			$this->load->library('Public/ApiMobile', null, 'm');
	        $data['code'] = $this->m->getrandomstr(9);
	        $this->load->library('session');
	        $this->session->set_userdata('createAccount_'.$data['code'], $data['p'], 1200);
			$this->load->view('/userCenter/regsuc',$data);
		}else{
			$this->alert("参数错误！",-1);exit;
		}
	}

	//发送短信验证码
	function sendmsg()
	{
		$this->load->library('session');
		
		$piccode= $this->filter_input($_POST['piccode']);
		$s_code = $this->session->userdata('VerifyCode');
		if(empty($piccode) or strtoupper($piccode)!=strtoupper($s_code)){
			//防用户恶意请求
			echo json_encode(array("code" => 1,"msg" => "请正确输入图片验证码！"));exit;
		}

		$mobile 		= !empty($_POST['phone'])?$_POST['phone']:'';
		$code 			= $this->get_random_str(4,'num');
		# 设置验证码session开始
        $this->config->set_item('sess_expiration', 60*10);# 设置session的有效期，单位秒
		$session_info = array(
      		'phoneCode_'.$mobile => $code,
      		'phoneNum_'.$mobile  => $mobile,
     	);
		$this->session->set_userdata($session_info);
		# 设置验证码session结束

		# 发送短信
		$send_title = $this->config->config['send_msg_title'];
		$send_text = $send_title."您好，您正在申请为注册成为云智慧金融平台用户，验证码为".$code.",十分钟内输入有效。";
		echo $this->sed_tpl_msg($mobile,$send_text);
	}

	//网页验证码 
	function checkcode()
	{
		$this->vCode(4, 20, 85); //4个数字，显示大小为15
	}

	function test(){

		//echo date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");exit;
		//$tmp_id = 1141039;//尊敬的用户，您已于#date#申请提现#money#元。预计取款到账需1~3个工作日。如有疑问，请致电#tel#。 
		$text = "【云智慧金融】尊敬的用户，您已于#date#申请提现#money#元。预计取款到账需1~3个工作日。如有疑问，请致电#tel#。";
		$mobile = 13652171413;
		$ss = $this->sed_tpl_msg($mobile,$text);
		print_r($ss);
		exit;
	}
}