<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectcredit extends Base_Controller
{

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Project_model');
		$url = urlencode("/usercenter");
		$this->islogin($url);
		$this->load->helper('cookie');
		$userInfo['uid'] 	= $this->getCookie('uid_cookie');
		$userInfo['uname'] 	= $this->getCookie('uname_cookie');
		if(empty($userInfo)){
			$data['user'] 	= ''; 
		}else{
			$data['user'] 	= $userInfo['uname'];
		}
	}

	public function index()
	{
		$result 			= $this->Project_model->getProjectCredit();
		$data['project'] 	= $result;
		$this->load->view('creditlist',$data);
	}

	public function mycredit()
	{
		$result = $this->Project_model->getProjectCredit($userInfo['uid']);
		$data['project'] = $result;
		$this->load->view('mycredit',$data);
	}

    /*
     * 发布债权转让按钮触发
     * pid pro_id 项目id
     * mpid item_id project_user中的id
     */
    function docredit()
    {
        $input = $this->input->post();
        //判断用户余额是否充足
        //冻结投资金额
        //项目信息
        $proinfo = $this->yzh_conn->where("id",$input['pid'])->get("yzh_project")->row_array();
        //我的投资信息
        $myproinfo = $this->yzh_conn->where("id",$input['mpid'])->get("yzh_project_user")->row_array();
        $createtime = date("Y-m-d H:i:s", time());
        //(strtotime(date("Y-m-d",strtotime($time2))) - strtotime(date("Y-m-d",strtotime($time1))))/3600/24;
        //$realtime	= (strtotime(date("Y-m-d",strtotime($createtime))) - strtotime(date("Y-m-d",strtotime($proinfo['full_time']))))/3600/24;
        $realtime = ( strtotime(date("Y-m-d",strtotime($createtime))) - strtotime(date("Y-m-d",strtotime($proinfo['full_time']))) )/3600/24;
        $time_scale = $realtime/($proinfo['cycle']*30);
        $ratio = $this->Project_model->getSysconfig('ratio');
        $ratio = $ratio[0]['value'];
        //折价率=a%+持有时间/项目周期 *(100-a)%  90<a<100
        $discount = $ratio/100+$time_scale*(100-$ratio)/100;
        $realamount = $myproinfo['invest_sum']*$discount;
        $endtime = date("Y-m-d",strtotime(date("Y-m-d",strtotime($proinfo['full_time']))) + $proinfo['cycle']*30*24*3600);
        //date("Y-m-d H:i:s",strtotime("2015-05-08 10:00:00 +4 days"));

        $this->load->helper('cookie');
        $uid = $this->getCookie('uid_cookie');

        $creditInfo = $this->yzh_conn->from("yzh_credit")->where("item_id",$input['mpid'])->get()->result_array();
        $this->yzh_conn->trans_begin();
        if(empty($creditInfo)){
            $data = array(
                'pro_id' => $input['pid'],
                'item_id' => $input['mpid'],
                'creditor_id' => $uid,
                'credit_amount' => $myproinfo['invest_sum'],
                'discount' => $discount,
                'real_amount' => $realamount,
                'status' => 1,
                'create_time' => $createtime,
                'end_time' => $endtime,
            );
            $credit_res = $this->yzh_conn->insert('yzh_credit', $data);
            $item_res = $this->yzh_conn->update('yzh_project_user', array("credit_status"=>1), array('id'=>$input['mpid']));
        }else{
            $data = array(
                'creditor_id' => $uid,
                'credit_amount' => $myproinfo['invest_sum'],
                'discount' => $discount,
                'real_amount' => $realamount,
                'status' => 1,
            );
            $credit_res = $this->yzh_conn->update('yzh_credit', $data, array('item_id'=>$input['mpid']));
            $item_res = true;
        }
        if($credit_res && $item_res){
            $this->yzh_conn->trans_commit();
            echo "success";exit;
        }else{
            $this->yzh_conn->trans_rollback();
            echo "failure";exit;
        }
        
    }

	/*
	 * 债权转让发布页面
	 */
	function assignCreditorCon(){

		$input = $this->input->get();
		//project_user
		$myproinfo = $this->yzh_conn->where(array("id"=>$input['mpid']))->get("yzh_project_user")->result_array();
		if(empty($myproinfo)){
			echo "project_user error!";exit;
		}
		$myproinfo 	= $myproinfo[0];
		
		//项目信息
		$proinfo 	= $this->Project_model->getProjectById( $myproinfo['pro_id'] );
        $proinfo 	= $proinfo[0];

		//过期时间（天数）
		$createtime = date("Y-m-d H:i:s", time());
		$realtime	= ( strtotime(date("Y-m-d",strtotime($createtime))) - strtotime(date("Y-m-d",strtotime($proinfo['full_time']))) )/3600/24;
		$expire_date = ($proinfo['cycle']*30)-$realtime;

		//折价率
		$discount	= $this->discountRatio($proinfo['cycle'],$proinfo['full_time']);

		$real_amount= $myproinfo['invest_sum']*$discount;

		$this->load->helper('cookie');
		$uid 	= $this->getCookie('uid_cookie');
		
		//校验当前登陆用户是否存在该债权
		$credit = $this->yzh_conn
			->where(array("item_id"=>$input['mpid'],"buyer_uid"=>$uid,"status"=>10))
			->get("yzh_credit")->result_array();
// print_R($this->yzh_conn->last_query());exit;
		if(isset($input['credit_id']) && empty($credit)){
			echo "credit not exists!";exit;
		}

		$data 	= array(
			'creditor_id'   => $uid,						# 转让人ID
			'pro_id' 		=> $proinfo['id'],				# 项目ID
			'mpid' 			=> $myproinfo['pro_id'],		# 项目ID
			'pro_num' 		=> $proinfo['pro_num'],			# 项目编号
			'item_id' 		=> $myproinfo['id'],
			'credit_amount' => $myproinfo['invest_sum'],	# 投资金额
			'expire_date' 	=> $expire_date,				# 到期天数
			'discount'		=> $discount*100,				# 折价率
			'real_amount' 	=> $real_amount					# 转让价格
		);

		$data['type'] = isset($input['type'])?$input['type']:'';
		

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

		$data['navId'] = $this->getCookie('navId_cookie');
		if( empty($data['navId']) )
		{
			$this->setCookie('navId_cookie', 'basicInfor', time()+3600*24);
			$data['navId'] = 'basicInfor';
		}

		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();

		//系统通知
		$data['systemNotice'] = array();

		$this->load->view('/public/header',$data);
		$this->load->view('/projectCredit/assignCreditorCon',$data);
	}

    /*
     * 债权转让撤销操作api
     */
    public function revokeCredit()
    {
        $input = $this->input->post();
        $uid = $this->getCookie('uid_cookie');
        if(!isset($input['credit_id'])){
            echo json_encode(array("code"=>1,"msg"=>"params error!"));exit;
        }
        $credit = $this->yzh_conn->where("id",$input['credit_id'])->get("yzh_credit")->result_array();
        if(empty($credit)){
            echo json_encode(array("code"=>1,"msg"=>"credit not exists!"));exit;
        }
        if($credit[0]['status']!=1){
            echo json_encode(array("code"=>1,"msg"=>"credit status error!"));exit;
        }
        if($uid != $credit[0]['creditor_id']){
            echo json_encode(array("code"=>1,"msg"=>"user info error!"));exit;
        }
        $item = $this->yzh_conn->where("id",$credit[0]['item_id'])->get("yzh_project_user")->result_array();
        if(empty($item)){
            echo json_encode(array("code"=>1,"msg"=>"item not exists!"));exit;
        }

        $record = $this->yzh_conn->where("credit_id",$input['credit_id'])->order_by("deal_time","DESC")->get("yzh_credit_record")->result_array();
        if(empty($record)){
            //第一次转让（从项目中进行的转让）
            //删除credit表数据
            $credit_res = $this->yzh_conn->delete("yzh_credit",array("id"=>$input['credit_id']));
            $item_data = array(
                'credit_status' => 0,
                'credit_to_uid' => 0,
                );
            //更新item数据中的credit部分
            $item_res = $this->yzh_conn->update("yzh_project_user",$item_data,array("id"=>$item[0]['id']));
        }else{
            //之后的转让（从债权中转让的）
            $credit_data = array(
                'creditor_id' => $record[0]['from_uid'],
                'buyer_uid' => $record[0]['to_uid'],
                'discount' => $record[0]['discount'],
                'real_amount' => $record[0]['to_amount'],
                'status' => 10,
                'deal_time' => $record[0]['deal_time'],
                'hf_bid_ordid' => $record[0]['hf_order_id'],
                );
            $credit_res = $this->yzh_conn->update("yzh_credit",$credit_data,array("id"=>$input['credit_id']));
        }
        //发送短信和站内信
        $userInfo = $this->yzh_conn->where("uid",$uid)->get("yzh_user")->result_array();
        $mobile     = $userInfo['phone'];
        $send_title = $this->config->config['send_msg_title'];
        $send_kftel = $this->config->config['send_msg_kftel'];
        $sys_msg    = "尊敬的用户,您在云智慧金融发布的债权转让已成功撤销，详情请登录云智慧官网查询。如有疑问，请致电".$send_kftel."。";
        $send_text  = $send_title.$sys_msg;
        @$this->sed_tpl_msg($mobile,$send_text);
        @$this->sed_sys_msg($uid,"债权转让撤销",$sys_msg);
        //发送短信和站内信
        echo json_encode(array("code"=>0,"msg"=>"success"));exit;
    }
}
