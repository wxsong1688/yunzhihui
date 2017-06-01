<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SafetysAssurance extends Base_Controller
{

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Project_model');
	}

	public function index()
	{
		$this->load->helper('cookie');
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


		$this->load->helper('cookie');
		$this->setCookie('projectNavId_cookie', 'safeBz', time()+3600*24);
		$data['projectNavId'] = 'safeBz';


		//系统通知
		$data['systemNotice'] = array();
 //print_R($data);exit;
		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();

		$this->load->view('/public/header',$data);
		$this->load->view('/safetysAssurance/safetysAssurance',$data);
	}

}