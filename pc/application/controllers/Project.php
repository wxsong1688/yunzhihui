<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends Base_Controller
{

	public function __construct() {
		parent::__construct();		
		$this->load->model('User_model');
		$this->load->model('Project_model');
	}

	public function index()
	{
		$userInfo['uid'] 	= $this->getCookie('uid_cookie');
		$userInfo['uname'] 	= $this->getCookie('uname_cookie');
		if(empty($userInfo))
		{
			$data['user'] = ''; 
		}else{
			$data['user'] = $userInfo['uname'];
		}
		$result = $this->Project_model->getProjects();
		foreach ($result as $key => $value) {
			$result[$key]['companyinfo'] 	= unserialize($value['companyinfo']);
			$result[$key]['projectinfo'] 	= unserialize($value['projectinfo']);
			$result[$key]['financierinfo']  = unserialize($value['financierinfo']);		//发起人
		}
		$data['project'] = $result;
		$this->load->view('project',$data);
	}

	function checkInvest(){
		//投资项目
        $UId            = $this->getCookie('uid_cookie');
        $pid            = !empty($_POST['pid'])?$_POST['pid']:'';
        $ptype          = !empty($_POST['ptype'])?$_POST['ptype']:'';
        $buid           = !empty($_POST['buid'])?$_POST['buid']:'';
        $invest_sum     = !empty($_POST['invest_sum'])?$_POST['invest_sum']:'';
        $remain_amount  = !empty($_POST['remain_amount'])?$_POST['remain_amount']:'';
        $projectInfo = $this->Project_model->getProjectById($pid); 
        $projectInfo = $projectInfo[0];
        $limit = $this->yzh_conn->where(array("id"=>$ptype))->get("yzh_project_type")->result_array();
        $limit = $limit[0];
        if($invest_sum<$limit['per_min'] || $invest_sum>$limit['per_max']){
        	$msg = "该项目投资金额应在".number_format($limit['per_min'])."~".number_format($limit['per_max'])."之间，且为".number_format($limit['per_min'])."整数倍！";
        	echo json_encode(array("code" => 0,"msg" => $msg));exit;
        }

        if($invest_sum%$limit['per_min']!==0){
        	$msg = "投资数额必须为".$limit['per_min']."的整数倍";
        	echo json_encode(array("code" => 0,"msg" => $msg));exit;
        }

        if($invest_sum > $remain_amount){
            $msg = "投资金额超出该项目剩余融资金额！";
        	echo json_encode(array("code" => 0,"msg" => $msg));exit;
        }

        //冻结投资金额
        if($projectInfo['remain_amount']==0 && $projectInfo['status']==10){
            $msg = "该项目已满,请选择其他项目！";
        	echo json_encode(array("code" => 0,"msg" => $msg));exit;
        }

        //判断用户余额是否充足
        $userAccount = $this->User_model->getUserAccount(1,array('uid' => $UId));
        $userAccount = (array)$userAccount[0];
        if($userAccount['withdrawal_cash'] < $invest_sum){
            $msg = "账户可用余额不足！";
        	echo json_encode(array("code" => 0,"msg" => $msg));exit;
        }
        echo json_encode(array("code"=>1,"msg"=>"success"));exit;
	}

	function checkBid(){
		$pro_id = !empty($_POST['pro_id'])?$_POST['pro_id']:"";
		if( empty($pro_id) )
		{
			echo '项目ID参数错误';exit;
		}

		$uid 		= $this->getCookie('uid_cookie');
		$userInfo 	= $this->User_model->getUser( array("uid" => $uid) );
		$userInfo 	= (array)$userInfo[0];
		$userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $uid) );
		if( empty($userAccountInfo) )
		{
			echo '请进入“我的账户->基本信息”中进行实名认证后投标！';exit;
		}else{
			$userAccountInfo = (array)$userAccountInfo[0];
		}		
		
		if( empty($userInfo) ){
			echo '参数错误';exit;
		}else{
			$data['user'] = $userInfo['username'];
			$data['withdrawal_cash'] = $userAccountInfo['withdrawal_cash'];
		}
		$projectInfo 		= $this->Project_model->getProjectById($pro_id);
		if(!is_array($projectInfo[0]) && empty($projectInfo[0])){
			echo '项目不存在！';exit;
		}
		echo "success";

	}

	function bid()
	{
		//系统通知
		$data['systemNotice'] = array();
		$pro_id = !empty($_GET['pro_id'])?$_GET['pro_id']:"";
		$url = urlencode("/Project/bid?pro_id=".$pro_id);
		$this->islogin($url);

		$uid 		= $this->getCookie('uid_cookie');
		$userInfo 	= $this->User_model->getUser( array("uid" => $uid) );
		$userInfo 	= (array)$userInfo[0];
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
		$userAccountInfo 	= $this->User_model->getUserAccount( 1, array("uid" => $uid) );
		$userAccountInfo 	= (array)$userAccountInfo[0];
		$data['withdrawal_cash'] = $userAccountInfo['withdrawal_cash'];
		$projectInfo 		= $this->Project_model->getProjectById($pro_id);
		$data['pid'] 		= $pro_id;
		$data['investment'] = $projectInfo[0];
		$data['investment']['buid'] 			= $data['investment']['tenderee_uid'];
		$data['investment']['amount'] 			= $data['investment']['amount'];
		$data['investment']['year_rate_out'] 	= $data['investment']['year_rate_out'];
		$data['investment']['remain_amount'] 	= $data['investment']['remain_amount'];
		$data['investment']['companyinfo'] 		= unserialize($projectInfo[0]['companyinfo']);
		$data['investment']['projectinfo'] 		= unserialize($projectInfo[0]['projectinfo']);
		$data['investment']['financierinfo'] 	= unserialize($projectInfo[0]['financierinfo']);
		$data['investment']['proj_rzpic'] 	    = $data['investment']['projectinfo']['proj_rzpic'];
		//var_dump($data['investment']);exit;
		switch ($data['investment']['financierinfo']['financier_sex']) {
			case '1':
				$data['investment']['financierinfo']['financier_sex_zh'] = "女";
				break;			
			default:
				$data['investment']['financierinfo']['financier_sex_zh'] = "男";
				break;
		}
		switch ($data['investment']['financierinfo']['financier_mar']) {
			case '1':
				$data['investment']['financierinfo']['financier_mar_zh'] = "未婚";
				break;			
			default:
				$data['investment']['financierinfo']['financier_mar_zh'] = "已婚";
				break;
		}

		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();

		//该项目投资记录
		$pro_user = $this->yzh_conn->from("yzh_project_user")->where(array("pro_id"=>$pro_id,"status !="=>0))->get()->result_array();
		foreach($pro_user as $k => $v){
			$user_info = $this->yzh_conn->from("yzh_user")->where("uid",$v['uid'])->get()->result_array();
			$pro_user[$k]['username'] = isset($user_info[0]) ? $user_info[0]['username'] : "";
			$pro_user[$k]['phone'] = isset($user_info[0]) ? $user_info[0]['phone'] : "";
		}
		$data['pro_user'] = $pro_user;
		$this->load->view('/public/header',$data);
		$this->load->view('/project/bid',$data);
	}

	function myproject(){
		$url = urlencode("/project/myproject");
		$this->islogin($url);
		$userInfo['uid'] 	= $this->getCookie('uid_cookie');
		$userInfo['uname'] 	= $this->getCookie('uname_cookie');
		if(empty($userInfo)){
			$data['user'] = ''; 
		}else{
			$data['user'] = $userInfo['uname'];
		}
		$mypro = $this->Project_model->getMyProjectById($userInfo['uid']);
		foreach($mypro as $k=>$v){
			$proInfo = $this->Project_model->getProjectById($v['pro_id']);
			$mypro[$k]['myProInfo'] = $proInfo[0];
			switch ($proInfo[0]['status']) {
				case '5':
					$mypro[$k]['myProInfo']['stat'] = '平台计息';
					break;
				case '10':
					$mypro[$k]['myProInfo']['stat'] = '融资方计息';
					break;
				case '20':
					$mypro[$k]['myProInfo']['stat'] = '未满标，投资失败';
					break;
				case '25':
					$mypro[$k]['myProInfo']['stat'] = '清算完成';
					break;
				default:
					$mypro[$k]['myProInfo']['stat'] = '<font color="red">状态异常</font>';
					break;
			}
			
			$mypro[$k]['invest_sum'] = number_format($mypro[$k]['invest_sum'],2);
			$mypro[$k]['myProInfo']['year_rate_out'] = round($mypro[$k]['myProInfo']['year_rate_out'], 2);
			if($v['credit_status']==1){
				$mypro[$k]['myProInfo']['credit_stat'] = '债权转让中';
			}elseif($v['credit_status']==10){
				$mypro[$k]['myProInfo']['credit_stat'] = '债权转让已完成';
			}else{
				$mypro[$k]['myProInfo']['credit_stat'] = 'what?';
			}
		}
		//var_dump($mypro);exit;
		$data['mypro'] = $mypro;
		$this->load->view('myproject',$data); 
	}

	function myprojectdetail(){
		$proInfo = $this->Project_model->getProjectById($_GET['pro_id']);
		$data['mypro'] = $proInfo[0];
		$data['mypro']['companyinfo'] = unserialize($proInfo[0]['companyinfo']);
		$data['mypro']['projectinfo'] = unserialize($proInfo[0]['projectinfo']);
		$data['mypro']['financierinfo'] = unserialize($proInfo[0]['financierinfo']);
		$this->load->view('myprojectdetail',$data); 
	}

}