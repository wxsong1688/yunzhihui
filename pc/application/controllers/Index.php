<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Base_Controller
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
		$this->load->model('User_model');
		
		$this->load->model('Project_model');
	}	

	function projectStatus($status){
		$statusArr = array(
			"1" => "待审核",
			"2" => "初审通过",
			"5" => "我要投标",//已上线
			"6" => "审核驳回",
			"10" => "已满标",
			"15" => "已满标 放款失败",
			"20" => "未满标",
			"25" => "已结算",
			"30" => "还款延时",
			"80" => "已结束"
		);
		return $statusArr[$status];
	}

	public function index()
	{
		//用户信息
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
		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();
		
		//产品种类
		$data['categoryProject'] = array();

		//项目精选-OK
		//$result = $this->Project_model->getProjects(array("status" => "5"),3);
		$result = $this->yzh_conn->where_in("status",array(5,10,25,80))->order_by("status ASC, create_time DESC, full_time DESC")->limit(3,0)->get("yzh_project")->result_array();
		foreach ($result as $key => $value) {
			$result[$key]['year_rate_out'] 	= number_format($result[$key]['year_rate_out'], 2).'%';
			$result[$key]['amount'] 		= number_format($result[$key]['amount']);
			$result[$key]['remain_amount'] 	= number_format($result[$key]['remain_amount']);
			$result[$key]['percentage'] 	= sprintf("%.2f",$value['gained_amount']/$value['amount']*100).'%';
			$result[$key]['status_zh'] 		= $this->projectStatus($result[$key]['status']);
			$result[$key]['companyinfo'] 	= unserialize($value['companyinfo']);
			$result[$key]['projectinfo'] 	= unserialize($value['projectinfo']);
			$result[$key]['financierinfo']  = unserialize($value['financierinfo']);		//发起人
		}
		$data['handpickProject'] = $result;

		//系统通知
		$data['systemNotice'] = array();
// print_R($data);exit;
		$data['model'] = "index";
		$this->load->view('/public/header', $data);
		$this->load->view('/index/index', $data);
		//$this->load->view('footer', $data);
	}
}
