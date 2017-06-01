<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends App_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	//登录
	public function login()
	{
		$input = $this->input->post();
		$input = array(
			'userName' => '15822260051',
			'pwd' => '123123',
			'platform' => 3,
			);
		if(!isset($input['userName']) || !isset($input['pwd']) || !isset($input['platform'])){
			echo json_encode(array("code" => 201,"massage" => "params error!"));exit;
		}
		$this->yzh_conn->where("username",$input['userName']);
		$this->yzh_conn->or_where("phone",$input['userName']);
		$username_info = $this->yzh_conn->get("yzh_user")->result_array();
		//验证用户是否存在
		if(!isset($username_info[0])){
			echo json_encode(array("code" => 201,"massage" => "user not exist!"));exit;
		}
		//验证密码是否正确
		$pwd_info = $this->yzh_conn->where("uid",$username_info[0]['uid'])->get("yzh_user")->row_array();
		if($pwd_info['pwd1'] !== md5($input['pwd'])){
			echo json_encode(array("code" => 201,"massage" => "wrong password!"));exit;
		}
		echo json_encode(array("code" => 200,"massage" => "login success!", "userId" => $username_info[0]['uid']));
	}

	//注册
	public function register()
	{
		$input = $this->input->post();
		$input = array(
			'phone' => '13652171410',
			'pwd' => '123123',
			'code' => '6666',
			);
		if(!isset($input['phone']) || !isset($input['pwd']) || !isset($input['code'])){
			echo json_encode(array("code" => 201,"massage" => "params error!"));exit;
		}
	}

	public function test($a,$b)
	{
		$input = array(
			'userName' => '15822260051',
			'pwd' => '123123',
			'platform' => 3,
			);
		if(!isset($input['userName']) || !isset($input['pwd']) || !isset($input['platform'])){
			echo json_encode(array("code" => 201,"massage" => "params error!"));exit;
		}
		$this->yzh_conn->where("username",$input['userName']);
		$this->yzh_conn->or_where("phone",$input['userName']);
		$username_info = $this->yzh_conn->get("yzh_user")->result_array();
		print_R($username_info);
	}

}