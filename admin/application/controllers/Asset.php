<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends App_Controller
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
		$this->load->model('Asset_model');
	}

	# 平台资产管理
	public function platform()
	{
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/platform');
		$this->load->view('footer');
	}

	# 融资人资产管理
	public function finance()
	{
		$data = $_GET;
		$search_data = $this->_getSearchData($data);

		$count              	  = $this->Asset_model->getListsCount($search_data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;

		$res 			     = $this->Asset_model->getLists($search_data,$offset);
		$list['list'] 	     = $res;
		$list['search_data'] = $search_data;
		$list['userinfo']    = $this->userinfo;
		$page['pageinfo']    = $pageinfo;
		$list['total_count'] = $count;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/finance',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	# 投资人资产管理
	public function invest()
	{
		$data = $_GET;
		$search_data = $this->_getSearchData($data);

		$count              	  = $this->Asset_model->getInvestListsCount($search_data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;

		$res 			     = $this->Asset_model->getInvestLists($search_data,$offset);
		$list['list'] 	     = $res;
		$list['search_data'] = $search_data;
		$list['userinfo']    = $this->userinfo;
		$page['pageinfo']    = $pageinfo;
		$list['total_count'] = $count;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/invest',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	private function _getSearchData($data)
	{
		$searchData = array();
		# 融资者只能查看自己的资产信息
		if($this->role_type == 7)
		{
			$searchData['uid'] = $this->uid;
		}else{
			if(!empty($data['uid'])){
				$searchData['uid'] = $data['uid']; 
			}
		}
		if(!empty($data['username']))
		{
			$searchData['username'] = urldecode($data['username']); 
		}
		if(!empty($data['phone']))
		{
			$searchData['phone'] = $data['phone']; 
		}
		return $searchData;
	}

	# 冻结用户/解冻用户
	public function lockUser()
	{
		$uid  = intval(trim($_GET['uid']));
		$type = $_GET['type'] == 1 ? 1 : 0;
		$from = $_GET['from'] == 1 ? 1 : 0;
		if(!empty($uid)){
			$this->Asset_model->lockUser($uid,$type,$from);
		}
		$url  = $_GET['from'] == 1 ? '/Asset/invest' : '/Asset/finance'; 
		header("Location:".$url);
	}

	public function getPayforMoney()
	{
		$uid    = $_POST['uid'];
		$pro_id = isset($_POST['pro_id'])?$_POST['pro_id'] : '';
		# 根据用户获取下期应付利息
		$money['money'] = array();
		echo json_encode(array('money'=>$money['money']));exit;
	}
	
	public function myindex()
    {
    	$uid = $this->userinfo['uid'];

    	#已融资总金额  账户余额  
    	$userAccount = $this->Asset_model->getUserTendereeAccount($uid);
    	$res['accountInfo'] =  !empty($userAccount)?$userAccount[0]:array() ;
    	$payinfo = array();
		$res['payinfo'] = $payinfo;

    	$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/myindex',$res);
		$this->load->view('footer');
    }

	public function account()
	{
		$uid = $this->userinfo['uid'];
		#账户余额  
		$userAccount = $this->Asset_model->getTendereeAccount(array("uid"=>$uid));
		$res['accountInfo'] =  !empty($userAccount)?$userAccount[0]:array() ;
		$res['accountInfo']['withdrawal_cash'] = !empty($res['accountInfo']['withdrawal_cash'])?$res['accountInfo']['withdrawal_cash']:"0";
		# 下次还款日期　 下期应还金额
		$payinfo = array();
		$res['payinfo'] = $payinfo;

		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/account',$res);
		$this->load->view('footer');
	}

	public function paylist()
	{
		$input = $this->input->get();
		if(!isset($input['pro_id']) || empty($input['pro_id'])){
			echo "params error!";exit;
		}
		$pro_id = $input['pro_id'];
		$uid = $this->userinfo['uid'];
		$stype = isset($input['s'])?1:0;

		$pro_info = $this->yzh_conn->where("id",$pro_id)->get("yzh_project")->row_array();
		$res = $this->repaymentList($pro_id);

		$list['pro_info'] = $pro_info;
		$list['list'] = $res;

		// print_r($list);exit;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/paylist',$list);
		$this->load->view('footer');
	}

    # 投资人流水管理
    public function investFlow()
	{
		$data = $_GET;
		// /print_r($data);exit;
		$count              	  = $this->Asset_model->getInvestFlowCount($data);
		$pageinfo['total_page']   = ceil($count/$this->pagesize);
		$pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset                   = ($pageinfo['page']-1) * $this->pagesize;

		$res = $this->Asset_model->getInvestFlowList($data,$offset);
		$list['list'] = $res;
		$list['search_data'] = $data;
		$page['pageinfo']    = $pageinfo;
		$page['total_count'] = $count;
	//print_R($res);exit;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/invest_flow',$list);
		$this->load->view('page',$page);
		$this->load->view('footer');
	}

	/**
    * 借款人还款列表
    */
    public function repaymentList($pro_id)
    {
        // $input = $this->input->get();
        // $pro_id = $input['pro_id'];
        $pro_info = $this->yzh_conn->where("id",$pro_id)->get("yzh_project")->result_array();
        if(empty($pro_info)){
            echo "project not exists!";exit;
        }
        if((strtotime($pro_info[0]['full_time']) < strtotime("2000-01-01 00:00:00")) || !in_array($pro_info[0]['status'],array(10,25,30,80))) {
            echo "project status error!";exit;
        }
        $repay_date = array(
            'start_time' => $pro_info[0]['full_time'],
            'cycle' => $pro_info[0]['cycle'],
            'settle_day' => 15,
            );
        $this->load->helper("pro_date");
        $res = repayListDate($repay_date);
        foreach($res as $k => $v){
            $res[$k]['interval'] = (strtotime($v['calcu_end'])-strtotime($v['calcu_start'])) / 3600 / 24;
            $res[$k]['repay_interest'] = $pro_info[0]['amount'] * $pro_info[0]['year_rate_in']/100 * $res[$k]['interval']/360;
            if($k == (sizeof($res)-1))//最后一期
            {
                $res[$k]['repay_amount'] = $pro_info[0]['amount'] + $res[$k]['repay_interest'];
            }else{
                $res[$k]['repay_amount'] = $res[$k]['repay_interest'];
            }
        }
        return $res;
    }

}
