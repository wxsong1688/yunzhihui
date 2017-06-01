<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Helpcenter extends Base_Controller
{

	public function __construct() {
		parent::__construct();
		//$this->load->model('Help_model');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->load->helper('cookie');
		$data['navId'] = $this->getCookie('navId_cookie');
		if( empty($data['navId']) )
		{
			$this->setCookie('navId_cookie', 'basicInfor', time()+3600*24);
			$data['navId'] = 'basicInfor';
		}

		/*$url = urlencode("/usercenter");
		$this->islogin($url);*/
		$userInfo['uid'] 	= $this->getCookie('uid_cookie');
		if( $userInfo['uid'] )
		{
			$userInfo = $this->User_model->getUser( array("uid" => $userInfo['uid']) );
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

		$this->load->view('/public/header',$data);
		$this->load->view('/helpCenter/helpcenter',$data);		
	}
}
