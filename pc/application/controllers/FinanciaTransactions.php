<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FinanciaTransactions extends Base_Controller
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
					$Hdata['user'] = $userInfo['username'];
				}else{
					$Hdata['user'] = $userInfo['phone'];
				}
			}
		}else{
			$Hdata['user'] = ''; 
		}

		//系统通知
		$Hdata['systemNotice'] = array();

		//获取headerNavClass
		$Hdata['headerNavClass'] = $this->getHeaderNavClass();
		$this->load->view('/public/header',$Hdata);

		$p = !empty($_GET['p'])?$_GET['p']:'fin_zq';
		//$page_config['nowindex']= !empty($_GET['pg'])?$_GET['pg']:'';
		switch ($p) {
			default: //债权转让-全部

				$page_config['perpage']		= 5;										# 每页条数
				$page_config['part']		= 3;										# 当前页前后链接数量
				$page_config['url']			='/FinanciaTransactions?p=fin_zq';		# url
				$page_config['nowindex']	= !empty($_GET['pg'])?$_GET['pg']:'';
				$page_config['nowindex']	=!empty($page_config['nowindex']) ? $page_config['nowindex']:1;  # 当前页
				$limits 					= ($page_config['nowindex']-1)*$page_config['perpage'];

				$this->load->library('Public/MypageClass', null, 'pageclass');
				$countnum = $this->yzh_conn
					        ->from('yzh_credit c')
					        ->join('yzh_project p', 'c.pro_id = p.id')
					        ->join('yzh_user u', 'u.uid = c.creditor_id')
					        ->where("p.type in (2,3,4) and c.status !=10 ")
					        ->count_all_results();
				$page_config['total'] 		= $countnum;
				$this->pageclass->initialize($page_config);

				$result_all = $this->yzh_conn->select('*,c.id as c_id, c.creditor_id, c.pro_id, c.status as c_status, u.realname, c.credit_amount, c.discount, c.real_amount, p.year_rate_out, p.cycle, p.full_time,p.status as p_status')
					        ->from('yzh_credit c')
					        ->join('yzh_project p', 'c.pro_id = p.id')
					        ->join('yzh_user u', 'u.uid = c.creditor_id')
					        ->where("p.type in (2,3,4) and p.status in (10,25,80)")
					        ->order_by("c.status ASC, c.update_time DESC")
					        ->limit($page_config['perpage'],$limits)
					        ->get()->result_array();
				foreach ($result_all as $key => $value) {
					$item_info = $this->yzh_conn->where("id",$value['item_id'])->get("yzh_project_user")->result_array();
					if($value['c_status'] == 1){//转让中
						$discount_ratio = $this->discountRatio($value['cycle'],$value['full_time']);
					}else{
						$discount_ratio = $value['discount'];
					}
					$result_all[$key]['discount'] = $discount_ratio;
					$result_all[$key]['real_amount'] = $item_info[0]['invest_sum']*$discount_ratio;
					$result_all[$key]['year_rate_out'] = $value['year_rate_out'];
					$result_all[$key]['credit_amount'] = $value['credit_amount'];
					if((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($value['full_time']))))/3600/24 > $value['cycle'] * 30){
						$result_all[$key]['remain_date'] = 0;
					}else{
						$result_all[$key]['remain_date'] = ($value['cycle'] * 30) - (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($value['full_time']))))/3600/24;
					}
					
					if($value['discount'] >= 0.997){
						$result_all[$key]['star'] = 3;
					}elseif($value['discount'] >= 0.993 && $value['discount'] <= 0.997){
						$result_all[$key]['star'] = 4;
					}else{
						$result_all[$key]['star'] = 5;
					}
				}
				$data['projectCredit_all'] = $result_all;
				$this->load->view('/financiaTransactions/ft_zqzr_view',$data);
				break;

			case 'fin_ph': //普惠金融
				$perpage = 8;
				$page_config['nowindex']= !empty($_GET['pg'])?$_GET['pg']:'';
				$page_config['nowindex']=!empty($page_config['nowindex']) ? $page_config['nowindex']:1;  # 当前页
				$result_p = $this->getProjectByType(2,$perpage,'fin_ph',$page_config['nowindex']);
				$data['project_p'] = $result_p;
				$this->load->view('/financiaTransactions/ft_phjr_view',$data);

				break;

			case 'fin_jy': //精英理财
				$perpage = 8;
				$page_config['nowindex']= !empty($_GET['pg'])?$_GET['pg']:'';
				$page_config['nowindex']=!empty($page_config['nowindex']) ? $page_config['nowindex']:1;  # 当前页
				$result_p = $this->getProjectByType(3,$perpage,'fin_jy',$page_config['nowindex']);
				$data['project_j'] = $result_p;
				$this->load->view('/financiaTransactions/ft_jylc_view',$data);

				break;

			case 'fin_gd': //高端定制
				$perpage = 8;
				$page_config['nowindex']= !empty($_GET['pg'])?$_GET['pg']:'';
				$page_config['nowindex']=!empty($page_config['nowindex']) ? $page_config['nowindex']:1;  # 当前页
				$result_p = $this->getProjectByType(4,$perpage,'fin_gd',$page_config['nowindex']);
				$data['project_g'] = $result_p;
				$this->load->view('/financiaTransactions/ft_gddz_view',$data);

				break;
		}
	}

	//根据类型获取融资列表
	function getProjectByType( $type, $perpage=null, $url=null ,$nowindex=null ){
		if($url!=''){
			$page_config['perpage']		= $perpage;									# 每页条数
			$page_config['part']		= 2;									# 当前页前后链接数量
			$page_config['url']			= '/FinanciaTransactions?p='.$url;		# url
			$page_config['nowindex']	= $nowindex;  # 当前页
			$limits 					= ($nowindex-1)*$page_config['perpage'];

			$this->load->library('Public/MypageClass', null, 'pageclass');
			$countnum = $this->yzh_conn
				        ->from('yzh_project')
				        ->where(array("type"=>$type))
				        ->where_in("status",array(5,10,25,80))
				        ->count_all_results();
			$page_config['total'] 		= $countnum;
			$this->pageclass->initialize($page_config);

			$result = $this->yzh_conn->select('*')
				        ->from('yzh_project')
				        ->where(array("type"=>$type))
				        ->where_in("status",array(5,10,25,80))
				        ->order_by("online_time","DESC")
				        ->limit($page_config['perpage'],$limits)
				        ->get()->result_array();
	    }else{
	    	$result = $this->Project_model->getProjects(array("id"=>$type));
	    }		

		if( is_array($result)&&!empty($result) ) {
			foreach ($result as $key => $value) {
				if( $result[$key]['gained_amount'] == 0 ) {
					$result[$key]['percentage'] = "0.00%";
				}else{
					$result[$key]['percentage'] = sprintf("%.2f",$value['gained_amount']/$value['amount']*100).'%';
				}
				if($result[$key]['status']>5){
					$result[$key]['status_zh'] 		= "已满标";
				}else{
					$result[$key]['status_zh'] 		= "我要投资";
				}

				$result[$key]['companyinfo'] 	= unserialize($value['companyinfo']);
				$result[$key]['projectinfo'] 	= unserialize($value['projectinfo']);
				$result[$key]['financierinfo']  = unserialize($value['financierinfo']);		//发起人
			}
		}else{
			$result = array();
		}
		return $result;
	}

	/*
	 * 债权转让购买页面
	 */
	function assignCreditor(){
		$input = $this->input->get();
		if(!isset($input['id'])||empty($input['id'])){
			echo "param error!";exit;
		}
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

		//系统通知
		$data['systemNotice'] = array();

		//获取headerNavClass
		$data['headerNavClass'] = $this->getHeaderNavClass();

		$cid = $input['id'];
		$creditInfo 		= $this->Project_model->getProjectCredit( array("id"=>$cid) );

		# 转让人客户号获取
		$creditUserInfo = $this->User_model->getUser( array("uid" => $creditInfo[0]['creditor_id']) );
		$creditInfo[0]['hf_usrCustId'] = $creditUserInfo[0]->hf_usrCustId;

		$data['creditInfo']	= $creditInfo[0];
		$projectInfo 		= $this->Project_model->getProject( array("id"=>$data['creditInfo']['pro_id']) );
		$data['projectInfo']= $projectInfo[0];
		$item_info = $this->yzh_conn->from("yzh_project_user")->where('id',$data['creditInfo']['item_id'])->get()->result_array();

		# 借款人客户号获取
		$jkUserInfo = $this->User_model->getUser( array("uid" => $item_info[0]['tenderee_id']) );
		$item_info[0]['jk_hf_usrCustId'] = $jkUserInfo[0]->hf_usrCustId;

		$data['itemInfo'] = $item_info[0];

		# 实际年化收益= (债权价值/实际支付价格) * 原始项目年化收益
		$real_earnings = ($data['creditInfo']['credit_amount']/$data['creditInfo']['real_amount'])*$data['projectInfo']['year_rate_out'];
		$data['projectInfo']['real_earnings'] = number_format($real_earnings, 2);

		$discount = $this->discountRatio($projectInfo[0]['cycle'],$projectInfo[0]['full_time']);
		$data['creditInfo']['discount'] = $discount*100;
		$data['creditInfo']['real_amount'] = $item_info[0]['invest_sum']*$discount;
		$data['creditInfo']['credit_amount'] = $data['creditInfo']['credit_amount'];
		$data['creditInfo']['ready_gain'] = $this->readyGain4view($creditInfo[0]['item_id'],$creditInfo[0]['pro_id']);
		$data['creditInfo']['privilege']   = number_format($data['creditInfo']['credit_amount']-$data['creditInfo']['real_amount'], 2);
		
		$data['projectInfo']['buid'] 			= $data['projectInfo']['tenderee_uid'];
		$data['projectInfo']['amount'] 			= number_format($data['projectInfo']['amount'], 2);
		$data['projectInfo']['year_rate_out'] 	= number_format($data['projectInfo']['year_rate_out'], 2);
		$data['projectInfo']['remain_amount'] 	= number_format($data['projectInfo']['remain_amount'], 2);
		$data['projectInfo']['companyinfo'] 	= unserialize($data['projectInfo']['companyinfo']);
		$data['projectInfo']['projectinfo'] 	= unserialize($data['projectInfo']['projectinfo']);
		$data['projectInfo']['financierinfo'] 	= unserialize($data['projectInfo']['financierinfo']);

		# 项目投资剩余时间
		$createtime = date("Y-m-d H:i:s", time());
		$realtime	= ( strtotime(date("Y-m-d",strtotime($createtime))) - strtotime(date("Y-m-d",strtotime($data['projectInfo']['full_time']))) )/3600/24;
		$data['projectInfo']['residue_time'] = ($data['projectInfo']['cycle']*30)-$realtime;


		switch ($data['projectInfo']['financierinfo']['financier_sex']) {
			case '1':
				$data['projectInfo']['financierinfo']['financier_sex_zh'] = "女";
				break;			
			default:
				$data['projectInfo']['financierinfo']['financier_sex_zh'] = "男";
				break;
		}
		switch ($data['projectInfo']['financierinfo']['financier_mar']) {
			case '1':
				$data['projectInfo']['financierinfo']['financier_mar_zh'] = "未婚";
				break;			
			default:
				$data['projectInfo']['financierinfo']['financier_mar_zh'] = "已婚";
				break;
		}

		$this->load->view('/public/header',$data);
		$this->load->view('/projectCredit/assignCreditor',$data);
	}

	/*
     * 检测债权购买人是否为债权发布者
     */
	function checkAssignCreditor(){
		$creditor_id = !empty($_POST['creditor_id'])?$_POST['creditor_id']:'';
		$this->load->helper('cookie');
		$uid = $this->getCookie('uid_cookie');
		if($creditor_id==$uid){
			echo "stop";exit;
		}else{
			echo "goon";exit;
		}

	}

	/*
     * 计算投资人单个项目投资的待收本息
     */
    public function readyGain4view($item_id,$pro_id)
    {
        $pro_info = $this->yzh_conn->where("id",$pro_id)->get("yzh_project")->row_array();
        $item = $this->yzh_conn->where("id",$item_id)->get("yzh_project_user")->row_array();
        
        $interval = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($pro_info['full_time']))))/3600/24;
        $readyGainInterest = ($pro_info['year_rate_out']/100 * ($pro_info['cycle']*30 - $interval)/360) * $item['invest_sum'];

        $readyGain = (float)$item['invest_sum']+$readyGainInterest;
        return $readyGain;
    }

	function redrect(){
		$projectNavId = $_GET['p'];
		$this->load->helper('cookie');
		$this->setCookie('projectNavId_cookie', $projectNavId, time()+3600*24);
		$data['projectNavId'] = $projectNavId;
		$this->load->view('/public/header',$data);
		$this->load->view('/financiaTransactions/financiaTransactions',$data);
	}

}