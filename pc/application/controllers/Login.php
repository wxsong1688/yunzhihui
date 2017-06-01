<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();
		$data['u'] = !empty($_GET['u'])?$_GET['u']:'';
		
		//系统通知
		$data['systemNotice'] = array();

		$this->load->view('/public/header',$data);
		$this->load->view('/userCenter/userLogin',$data);
	}

	public function exitLogin(){
		//清除cookie
		$this->load->helper('cookie');
		if(!empty($_COOKIE)){
			foreach($_COOKIE as $k => $v){
				delete_cookie($k);
			}
		}
		header('Location:/Index');
	}

	public function doLogin()
	{
		$this->load->library('session');

		//数据过滤
		$url  		= isset($_GET['u'])?$_GET['u']:'';
		$phone		= isset($_POST['phone'])?$_POST['phone']:'';
		$pwd1 		= isset($_POST['pwd1'])?$_POST['pwd1']:'';
		$checkcode	= isset($_POST['checkcode'])?$_POST['checkcode']:'';
		$VerifyCode = $this->session->userdata('VerifyCode');

		//var_dump(strtoupper($checkcode), strtoupper($VerifyCode));exit;
		//验证码是否正确
		if( strtoupper($checkcode) !== strtoupper($VerifyCode) )
		{
			$this->alert("验证码输入有误！",-1);exit;
		}
		//查询用户信息
		$result = $this->yzh_conn->where("type=5 and (phone='{$phone}' or username='{$phone}')")->get("yzh_user")->result_array();
		if( empty($result) )
		{
			$this->alert("帐号不存在！",-1);exit;
		}
		$result = $result[0];
		//账号密码是否正确
		if($result['pwd1'] != md5($pwd1))
		{
			$this->alert("用户名或密码错误！",-1);exit;
		}else{
			//记录最后登录时间
			$data = array( 'last_login' => date("Y-m-d H:i:s", time()) );
			$this->User_model->updateUser($data,array('phone' => $result['phone']));

			//记录cookie
			$this->setCookie('uid_cookie', $result['uid'], 60000);
			$this->setCookie('uname_cookie', $result['phone'], 60000);

			# 获取用户总资产
	        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $result['uid']) );
	        $userAccountInfo = $userAccountInfo[0];
	        $used_money = (float)$this->_used_money($result['uid']) + (float)$this->_credit_in_money($result['uid']);//投资中本金
	        $total = $used_money+(float)$userAccountInfo['withdrawal_cash'];

	        # 获取用户level对应表
	        $userLevel = $this->yzh_conn->from("yzh_user_level")->get()->result_array();
	        foreach ($userLevel as $key => $value) {
	        	if((float)$value['max_money'] >= $total && (float)$value['min_money'] <= $total && $result['level']<$value['level']){
	        		# 更新用户等级
	        		$this->User_model->updateUser( array( "level" => $value['level'] ),array("uid" => $result['uid']) );
	        	}
	        }

			if($url){
				header('Location:'.$url);exit;
			}else{
				header('Location:/Usercenter');exit;
			}
			
		}
			
	}

	//invested_money
    public function _used_money($uid)
    {
        $res = $this->yzh_conn->from("yzh_project_user")->join("yzh_project","yzh_project.id = yzh_project_user.pro_id")
            ->where(array("yzh_project_user.status"=>1,"yzh_project_user.credit_status !="=>10,"yzh_project_user.uid"=>$uid,"yzh_project.status"=>10))
            ->select_sum("yzh_project_user.invest_sum")
            ->get()->row_array();
        return $res['invest_sum'];
    }

    //invested_credit_money
    public function _credit_in_money($uid)
    {
        $res = $this->yzh_conn->from("yzh_credit")->join("yzh_project","yzh_project.id = yzh_credit.pro_id")
            ->where(array("yzh_credit.status"=>10,"yzh_credit.buyer_uid"=>$uid,"yzh_project.status"=>10))
            ->select_sum("credit_amount")
            ->get()->row_array();
        return $res['credit_amount'];
    }

	//网页验证码
	function checkcode()
	{	
		$this->vCode(4, 30, 100); //4个数字，显示大小为15
	}

}
