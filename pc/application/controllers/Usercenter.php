<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usercenter extends Base_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Project_model');
    }

    public function index()
    {   
        $e = !empty($_GET['e'])?$_GET['e']:'';
        if($e){
            $this->load->library('session');
            $mailKey = $this->session->userdata('mailKey_'.base64_decode($_GET['e']));
            //var_dump($mailKey);exit;
            if($mailKey!=$_GET['mailKey']){
                $data['checkRes'] = 'checkFalse';
            }else{
                # 更新用户邮箱验证状态
                $result = $this->yzh_conn->update('yzh_user', array("email_succ"=>1), array("email"=>base64_decode($_GET['e'])));
                $chk = $this->yzh_conn->affected_rows();
                if ($chk>0) {
                    $data['checkRes'] = 'checkSuccess';
                }else{
                    $data['checkRes'] = 'checkFalse';
                }
            }
        }

        $this->load->helper('cookie');
        $data['navId'] = $this->getCookie('navId_cookie');
        if( empty($data['navId']) )
        {
            $this->setCookie('navId_cookie', 'accBrowse', time()+3600*24);
            $data['navId'] = 'accBrowse';
        }

        $url = urlencode("/Usercenter");
        $this->islogin($url);
        $userInfo['uid']    = $this->getCookie('uid_cookie');
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

        /*$result = $this->Project_model->getProjects();
        foreach ($result as $key => $value) {
            $result[$key]['companyinfo']    = unserialize($value['companyinfo']);
            $result[$key]['projectinfo']    = unserialize($value['projectinfo']);
            $result[$key]['financierinfo']  = unserialize($value['financierinfo']);//发起人
        }
        $data['project'] = $result;
*/
        //系统通知
        $data['systemNotice'] = array();

        //获取headerNavClass
        $data['headerNavClass'] = $this->getHeaderNavClass();

        $this->load->view('/public/header',$data);
        $this->load->view('/userCenter/usercenter',$data);
        
    }

    //个人中心左侧导航Cookie
    public function setcookie_nav()
    {
        $navId = $_GET['navId'];
        if( !empty($navId) )
        {
            $this->setCookie('navId_cookie', $navId, time()+3600*24);
            echo "success";exit;
        }
        
    }

    //Header导航Cookie
    public function setcookie_headNav()
    {
        $headNav = $_GET['headNav'];
        if( !empty($headNav) )
        {
            $this->setCookie('headNav_cookie', $headNav, time()+3600*24);
            echo "success";exit;
        }
        
    }

    //Fin 导航Cookie
    public function setcookie_finNav()
    {
        $projectNavId = !empty($_GET['projectNavId'])?$_GET['projectNavId']:'';
        if( !empty($projectNavId) )
        {
            $this->setCookie('projectNavId_cookie', $projectNavId, time()+3600*24);
            echo "success";exit;
        }
        
    }

    //加载个人中心Header
    public function userHeader(){
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
        $this->load->view('/userCenter/userHeader',$data);
    }

    //加载个人中心Footer
    public function userFooter(){
        $data['project'] = array();
        $this->load->view('/userCenter/userFooter',$data);
    }

    //加载个人中心主框架
    public function userAccount(){
        $data['project'] = array();
        $this->load->view('/userCenter/userAccount',$data);
    }

    //加载个人中心工具栏
    public function userNav(){
        $uid = $this->getCookie('uid_cookie');
        $query = $this->yzh_conn->query('select id from yzh_sysmsg_record where uid="'.$uid.'" and status=0'); 
        $msgcount = $query->num_rows();
        if($msgcount>0){
            $data['msgcount'] = $msgcount;
        }else{
            $data['msgcount'] = "0";
        }       
        $data['navId'] = !empty($_GET['navId'])?$_GET['navId']:'';
        $this->load->view('/userCenter/userNav',$data);
    }

    //个人中心-基本资料
    public function userInformation(){
        $data['checkRes'] = !empty($_GET['certific'])?$_GET['certific']:'';
        $url = urlencode("/usercenter");
        $this->islogin($url);
        //var_dump($this->islogin($url));exit;
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $userBank = $this->User_model->getUserBank( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        switch ($data['userInfo']['level']) {
            case '1':
                $data['userInfo']['level'] = "普通用户";
                break;
            case '2':
                $data['userInfo']['level'] = "精英用户";
                break;
            case '3':
                $data['userInfo']['level'] = "高端用户";
                break;
            default:
                $data['userInfo']['level'] = "";
                break;
        }

        $this->load->library('Public/ApiMobile', null, 'm');
        $data['code'] = $this->m->getrandomstr(9);
        $this->load->library('session');
        $this->session->set_userdata('createAccount_'.$data['code'], $data['userInfo']['phone'], 1200);

        //获取绑定银行卡信息
        if($userBank)
        {
            $data['userInfo']['bank'] = (array)$userBank[0];            
            $data['userInfo']['bank']['type'] = !empty($data['userInfo']['bank']['type'])?$data['userInfo']['bank']['type']:"1";
        }else{
            $data['userInfo']['bank'] = array();
        }
        if( !empty($data['userInfo']['bank']['deposit_bank']) )
        {
            $data['userInfo']['bank']['bank_name'] = $this->bankinfo[$data['userInfo']['bank']['deposit_bank']]['name'];
        }
        $this->load->view('/userCenter/userInformation',$data);
    }

    public function changePwd()
    {
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $userBank = $this->User_model->getUserBank( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        $this->load->view('/userCenter/changePwd',$data);
    }

    //个人中心-修改密码
    public function dochangePwd()
    {
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $old_pwd = !empty($_POST['old_pwd'])?$_POST['old_pwd']:"";
        $new_pwd = !empty($_POST['new_pwd'])?$_POST['new_pwd']:"";
        $renew_pwd = !empty($_POST['renew_pwd'])?$_POST['renew_pwd']:"";

        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $userInfo = (array)$userInfo[0];
        if( md5($old_pwd)==$userInfo['pwd1'] )
        {
            if( empty($new_pwd) || $new_pwd!=$renew_pwd ){
                echo json_encode(array("code" => 0,"msg" => "新密码不能为空或输入不一致！"));exit;
            }
            $result = $this->User_model->updateUser( array( "pwd1" => md5($new_pwd) ),array("uid" => $uid) );
            if($result)
            {
                echo json_encode(array("code" => 1,"msg" => "修改成功！"));exit;
            }else{
                echo json_encode(array("code" => 0,"msg" => "密码修改失败！"));exit;
            }

        }
        else
        {
            echo json_encode(array("code" => 0,"msg" => "原始密码输入有误！"));exit;
        }
    }

    
    public function autotender()
    {
        $url = urlencode("/Usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $where = array("uid"=>$uid);
        $data = array(
            "auto_tender"=>$_POST['tender'],
            "auto_amount"=>$_POST['amount'],
            "auto_circle"=>$_POST['circle'],
            "auto_type"=>$_POST['type'],
            );
        $result = $this->yzh_conn->update('yzh_user_account', $data, $where);
        if($result)
        {
            //echo "设置成功！";exit;
            $this->autoInvestmentPlan($uid);
            echo json_encode(array("code" => 1,"msg" => "设置成功！"));exit;
        }else{
            //echo "设置失败！";exit;
            echo json_encode(array("code" => 0,"msg" => "设置失败！"));exit;
        }

    }

    public function editNiname()
    {
        $data = $_POST;
        //验证参数
        if(!$data['username'] || !$data['uid']){
            echo '参数错误！';exit;
        }
        //验证昵称是否已存在        
        $query = $this->yzh_conn->get_where('yzh_user', array("username"=>trim($data['username'])));
        $userRes = $query->num_rows();
        if($userRes>0){
            echo '该昵称已存在！';exit;
        }
        //修改昵称
        $result = $this->User_model->updateUser( array( "username" => $data['username'] ),array("uid" => $data['uid']) );
        $chk = $this->yzh_conn->affected_rows();
        if ($chk) {            
            echo "success";exit;
        }else{
            echo '编辑昵称失败！';exit;
        }
        
    }

    public function editEmail()
    {
        $data = $_POST;
        //验证参数
        if(!$data['email'] || !$data['uid']){
            echo '参数错误！';exit;
        }
        //验证昵称是否已存在        
        $query = $this->yzh_conn->get_where('yzh_user', array("email"=>trim($data['email'])));
        $userRes = $query->num_rows();
        if($userRes>0){
            echo '该邮箱已存在！';exit;
        }
        //更新邮箱
        $result = $this->User_model->updateUser( array( "email" => $data['email'],"email_succ"=>0 ),array("uid" => $data['uid']) );
        $chk = $this->yzh_conn->affected_rows();
        if($chk){
            $res = $this->sendsmail($data['email']);
            if($res>0){
                echo "success";exit;
            }else{
                echo '邮箱验证发送失败，请稍后重试！';exit;
            }            
        }else{
            echo '邮箱修改失败！';exit;
        }

    }

    public function isBindMail(){
        $email = $_POST['email'];
        //验证昵称是否已认证        
        $query = $this->yzh_conn->get_where('yzh_user', array("email"=>trim($email)))->result_array();
        $userRes = $query[0];
        if($userRes['email_succ']==1){
            echo 'isbinded';exit;
        }
    }

    public function sendsmail($email=''){
        if($email==''){
            $query = $this->yzh_conn->get_where('yzh_user', array("uid"=>$_POST['uid']))->result_array();
            $email = $query[0]['email'];
        }
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.163.com';
        $config['smtp_user'] = 'ambtion@163.com';
        $config['smtp_pass'] = 'aa003746900';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        # 生成随机数
        $this->load->library('Public/ApiMobile', null, 'm');
        $mailKey = $this->m->getrandomstr(38);

        # 写入session
        $this->load->library('session');
        $this->session->set_userdata('mailKey_'.$email, $mailKey);
        $linkUrl = $this->config->config['base_url'].'/Usercenter?mailKey='.$mailKey.'&e='.base64_encode($email);

        # 编写邮件内容        
        $mailContent  = '【云智慧金融】您正在进行邮箱验证，验证邮箱请点击以下链接，若不能点击，则请复制到浏览器的地址栏上：';
        $mailContent .= "</br>";
        $mailContent .= '<a href="'.$linkUrl.'" target="_blank ">'.$linkUrl.'</a>';
        $mailContent .= "</br>";
        $mailContent .= '感谢您的使用！';
        $mailContent .= "</br>";
        $mailContent .= '如果您未在云智慧金融申请邮箱验证，请自动忽略该邮件，抱歉打扰，请原谅。';

        $this -> load -> library('email');
        $this->email->initialize($config);
        $this->email->from('ambtion@163.com');
        $this->email->to($email);
        $this->email->subject('【云智慧金融】邮箱绑定验证');
        $this->email->message($mailContent);
        // $this->email->attach('public/doc/test.docx');                           # 附件

        $isBindmail = !empty($_POST['isBindmail'])?$_POST['isBindmail']:'';
        if( ! $this->email->send()){
            if($isBindmail==1){
                echo "发送失败！";exit;
            }else{
                return '0'; # 发送失败
            }            
        }else{
            if($isBindmail==1){
                echo "success";exit;
            }else{
                return '1'; # 发送成功
            }            
        }
    }

    public function userConfirm(){        
        $this->load->library('session');
        $mailKey = $this->session->userdata('mailKey_'.base64_decode($_GET['e']));
        //var_dump($mailKey);exit;
        if($mailKey!=$_GET['mailKey']){
            $data['checkRes'] = 'checkFalse';
        }else{
            # 更新用户邮箱验证状态
            $result = $this->yzh_conn->update('yzh_user', array("email_succ"=>1), array("email"=>base64_decode($_GET['e'])));
            $chk = $this->yzh_conn->affected_rows();
            if ($chk>0) {            
                $data['checkRes'] = 'checkSuccess';
            }else{
                $data['checkRes'] = 'checkFalse';
            }
        }

        //获取headerNavClass
        $data['headerNavClass'] = $this->getHeaderNavClass();
        $data['u'] = !empty($_GET['u'])?$_GET['u']:'';
        
        //系统通知
        $data['systemNotice'] = array();

        $this->load->view('/public/header',$data);
        $this->load->view('/userCenter/userLogin',$data);
    }

    public function systemNews(){
        $uid = $this->getCookie('uid_cookie');

        $where = "uid = '".$uid."'";
        $params = $this->input->get();

        $page_config['perpage']     = 6;                                     # 每页条数
        $page_config['part']        = 2;                                     # 当前页前后链接数量
        $page_config['url']         = '/Usercenter/systemNews?';             # url
        $page_config['nowindex']    = isset($params['pg'])?$params['pg']:1;  # 当前页
        $limits                     = ($page_config['nowindex']-1)*$page_config['perpage'];

        $this->load->library('Public/MypageClass', null, 'pageclass');
        $countnum = $this->yzh_conn->from("yzh_sysmsg_record")->where($where)->count_all_results();
        $page_config['total']       = $countnum;
        $this->pageclass->initialize($page_config);
        
        # 获取列表数据
        $data['msginfo'] = $this->yzh_conn->select("*")->from("yzh_sysmsg_record")->where($where)->order_by("status ASC,id DESC")->limit($page_config['perpage'],$limits)->get()
                    ->result_array();
        # 获取未读总个数
        $where .= " and status = 0";
        $msgcount = $this->yzh_conn->from("yzh_sysmsg_record")->where($where)->count_all_results();

        $data['msgcount'] = intval($msgcount) > 0 ? intval($msgcount) : 0;
        $data['searchData'] = $params;
        $this->load->view('/userCenter/systemNews',$data);
    }

    public function systemNewsup(){
        $id = $_POST['id'];
        $data = array("status"=>"1","read_time"=>date("Y-m-d H:i:s"));
        $result = $this->yzh_conn->update('yzh_sysmsg_record', $data, array('id'=>$id));
        if($result){
            echo 1;exit;
        }else{
            echo 0;exit;
        }
    }
    

    public function accountBrowse(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $uid) );
        $userInfo = $this->yzh_conn->from("yzh_user")->where("uid", $uid)->get()->result_array();
        if( $userAccountInfo )
        {
            $userAccountInfo = (array)$userAccountInfo[0];
            $userAccountInfo['money']                   = $userAccountInfo['money'];
            $userAccountInfo['gain_total']              = $userAccountInfo['gain_total'];
            $userAccountInfo['invest_total']            = $userAccountInfo['invest_total'];
            $userAccountInfo['recharge_total']          = $userAccountInfo['recharge_total'];
            $userAccountInfo['withdrawal_cash']         = $userAccountInfo['withdrawal_cash'];
            $userAccountInfo['withdrawal_cash_total']   = $userAccountInfo['withdrawal_cash_total'];
            $userAccountInfo['gain_curr_day']           = (float)$this->readyEverydayGain($uid)+(float)$this->creditEverydayReady($uid);
        }else{
            $userAccountInfo['money']                   = (float)0;
            $userAccountInfo['gain_total']              = (float)0;
            $userAccountInfo['invest_total']            = (float)0;
            $userAccountInfo['recharge_total']          = (float)0;
            $userAccountInfo['withdrawal_cash']         = (float)0;
            $userAccountInfo['withdrawal_cash_total']   = (float)0;
            $userAccountInfo['gain_curr_day']           = (float)0;
        }
        $data['user'] = $userInfo[0];
        $data['user']['username'] = !empty($userInfo[0]['username'])?$userInfo[0]['username']:$userInfo[0]['phone'];
        $data['userAccount'] = $userAccountInfo;
        $data['userAccount']['used_money'] = (float)$this->_used_money($uid) + (float)$this->_credit_in_money($uid);//投资中本金
        $data['userAccount']['expected'] = (float)$this->readyGain($uid) + (float)$this->creditReady($uid);//待收本息
        $data['userAccount']['total'] = $data['userAccount']['used_money'] + $userAccountInfo['money'];
        $this->load->view('/userCenter/accountBrowse',$data);
    }

    /**
    * 充值页面
    */
    public function recharge(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $uid) );
        if( $userAccountInfo )
        {
            $userAccountInfo = (array)$userAccountInfo[0];
            $userAccountInfo['money']                   = number_format($userAccountInfo['money'], 2);
            $userAccountInfo['gain_total']              = number_format($userAccountInfo['gain_total'], 2);
            $userAccountInfo['invest_total']            = number_format($userAccountInfo['invest_total'], 2);
            $userAccountInfo['recharge_total']          = number_format($userAccountInfo['recharge_total'], 2);
            $userAccountInfo['withdrawal_cash_total']   = number_format($userAccountInfo['withdrawal_cash_total'], 2);
        }else{
            $userAccountInfo['money']                   = "0.00";
            $userAccountInfo['gain_total']              = "0.00";
            $userAccountInfo['invest_total']            = "0.00";
            $userAccountInfo['recharge_total']          = "0.00";
            $userAccountInfo['withdrawal_cash_total']   = "0.00";
        }
        $data['userAccount'] = $userAccountInfo;
        /*获取实名认证信息*/
        $user_info = $this->yzh_conn->from("yzh_user")->where("uid",$uid)->get()->row_array();
        $data['id_succ'] = isset($user_info['id_succ']) ? $user_info['id_succ'] : 0 ;

        /*获取绑定银行卡信息*/
        $card_info = $this->yzh_conn->where("uid",$uid)->get("yzh_user_bank")->result_array();
        if(!empty($card_info)){
            $data['card_status'] = 1;
        }else{
            $data['card_status'] = 0;
        }

        $this->load->helper('cookie');
        $this->setCookie('navId_cookie', 'irecharge', time()+3600*24);
        $data['navId'] = 'irecharge';       
        $this->load->view('/userCenter/recharge',$data);
    }

    /*
     * 提现页面
     */
    public function withdrawals(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $uid) );
        if( $userAccountInfo )
        {
            $userAccountInfo = (array)$userAccountInfo[0];
            $userAccountInfo['money']                   = number_format($userAccountInfo['money'], 2);
            $userAccountInfo['gain_total']              = number_format($userAccountInfo['gain_total'], 2);
            $userAccountInfo['invest_total']            = number_format($userAccountInfo['invest_total'], 2);
            $userAccountInfo['recharge_total']          = number_format($userAccountInfo['recharge_total'], 2);
            $userAccountInfo['withdrawal_cash_total']   = number_format($userAccountInfo['withdrawal_cash_total'], 2);
        }else{
            $userAccountInfo['money']                   = "0.00";
            $userAccountInfo['gain_total']              = "0.00";
            $userAccountInfo['invest_total']            = "0.00";
            $userAccountInfo['recharge_total']          = "0.00";
            $userAccountInfo['withdrawal_cash_total']   = "0.00";
        }
        $data['userAccount'] = $userAccountInfo;
        
        /*获取实名认证信息*/
        $user_info = $this->yzh_conn->from("yzh_user")->where("uid",$uid)->get()->row_array();
        $data['id_succ'] = isset($user_info['id_succ']) ? $user_info['id_succ'] : 0 ;

        /*获取绑定银行卡信息*/
        $card_info = $this->yzh_conn->where("uid",$uid)->get("yzh_user_bank")->result_array();
        if(!empty($card_info)){
            $data['card_status'] = 1;
            $data['card_type'] = 1;
            foreach($card_info as $k => $v){
                if($v['type']==2){
                    $data['card_type'] = 2;
                }
            }
        }else{
            $data['card_status'] = 0;
            $data['card_type'] = 1;
        }
        
        $this->load->helper('cookie');
        $this->setCookie('navId_cookie', 'withdrawals', time()+3600*24);
        $data['navId'] = 'withdrawals';

        $cardInfo = $this->User_model->getUserBank( array("uid" => $uid) );
        if( is_array($cardInfo) && !empty($cardInfo)){
            foreach ($cardInfo as $key => $value) {
                $data['cardInfo'][] = (array)$value;
            }
        }else{
            $data['cardInfo'] = array();
        }
        $data['bankInfo'] = $this->config->config['bank'];
        $this->load->view('/userCenter/withdrawals',$data);
    }

    public function fundRunning(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');

        $where = "uid = '".$uid."'";
        
        # 查询条件开始
        $params = $this->input->get();
        $type = $this->getRunningType(isset($params['runningType'])?$params['runningType']:'recharge,invest,reback,withdraw');
        if(is_array($type)){
            if(empty($type)){
                $where .= " and type in (-1)";
            }else{
                $where .= " and type in (".implode(",",$type).")";
            }
        }
        $time_start = isset($params['time_start'])?trim($params['time_start']):'';
        $time_end   = isset($params['time_end'])?trim($params['time_end']):'';
        if(!empty($time_start)){
            $where .= " and create_time >= '".$time_start." 00:00:00'";
        }
        if(!empty($time_end)){
            $where .= " and create_time <= '".$time_end." 23:59:59'";
        }
        if(isset($params['date']) && !empty($params['date']))
        {
            $date       = date("Y-m-d",time());
            $time_end   = $date;
            switch (intval($params['date'])) {
                case 1:
                    $time_start   = $date;
                    break;
                case 2:
                    $time_start   = date("Y-m-d",strtotime("-30 day"));
                    break;
                default:
                    $time_start   = date("Y-m-d",strtotime("-90 day"));
                    break;
            }
            $where .= " and create_time >= '".$time_start." 00:00:00'";
            $where .= " and create_time <= '".$time_end." 23:59:59'";
            $params['time_start']   = $time_start;
            $params['time_end']     = $time_end;
        }
        # 查询条件结束

        $page_config['perpage']     = 6;                                     # 每页条数
        $page_config['part']        = 2;                                     # 当前页前后链接数量
        $page_config['url']         = '/Usercenter/fundRunning?';            # url
        $page_config['nowindex']    = isset($params['pg'])?$params['pg']:1;  # 当前页
        $limits                     = ($page_config['nowindex']-1)*$page_config['perpage'];

        $this->load->library('Public/MypageClass', null, 'pageclass');
        $countnum = $this->yzh_conn->from("yzh_user_flow_".date("Y"))->where($where)->count_all_results();
        $page_config['total']       = $countnum;
        $this->pageclass->initialize($page_config);
        
        $flowInfo = $this->yzh_conn
                    ->from("yzh_user_flow_".date("Y"))
                    ->where($where)
                    ->order_by("create_time DESC,id DESC")
                    ->limit($page_config['perpage'],$limits)
                    ->get()
                    ->result_array();
                    
        foreach ($flowInfo as $key => $value) {
            $flowInfo[$key]['create_time']  = $value['create_time'];
            $flowInfo[$key]['amount']       = number_format($value['amount'], 2);
            $flowInfo[$key]['remaining_amount'] = number_format($value['remaining_amount'],2);
            //1：充值，2：提现，3：充值手续费 4：提现手续费 5：投标冻结 6：投标 7：回款利息 8：回款本息 9：债权出让 10：购买债券
            switch ($value['type']) {
                case 1: $flowInfo[$key]['type']     = '充值';
                        $flowInfo[$key]['ispay']    = 0;        //收入
                break;
                case 2: $flowInfo[$key]['type']     = '提现';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                case 3: $flowInfo[$key]['type']     = '充值手续费';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                case 4: $flowInfo[$key]['type']     = '提现手续费';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                case 5: $flowInfo[$key]['type']     = '投标冻结';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                case 6: $flowInfo[$key]['type']     = '投标成功';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                case 7: $flowInfo[$key]['type']     = '回款利息';
                        $flowInfo[$key]['ispay']    = 0;        //收入
                break;
                case 8: $flowInfo[$key]['type']     = '回款本息';
                        $flowInfo[$key]['ispay']    = 0;        //收入
                break;
                case 9: $flowInfo[$key]['type']     = '债权转让';
                        $flowInfo[$key]['ispay']    = 0;        //收入
                break;
                case 10: $flowInfo[$key]['type']    = '购买债权';
                        $flowInfo[$key]['ispay']    = 1;        //支出
                break;
                default: # code...  
                break;
            }
        }
        $data['data']       = $flowInfo;
        $data['searchData'] = $params;
        $this->load->view('/userCenter/fundRunning',$data);
    }

    private function getRunningType($runningType)
    {
        $type   = array();
        $config = array(
            'recharge' => '1,3',
            'withdraw' => '2,4',
            'invest' => '5,6,9,10',
            'reback' => '7,8',
            );
        if(!empty($runningType)){
            foreach(explode(",",trim($runningType,",")) as $k => $v){
                $type[] = $config[$v];
            }
            $type = explode(",",implode(',', $type));
            foreach ($type as $key => $value) {
                $type[$key] = (int)$value;
            }
        }
        return empty($type)?array():$type;
        $type_en = array(
            1 =>'recharge',
            2 =>'withdraw',
            3 =>'recharge_poundage',
            4 =>'withdraw_poundage',
            5 =>'invest_freeze',
            6 =>'invest',
            7 =>'reback_interest',
            8 =>'reback',
            9 =>'credit_sell',
            10 =>'credit_buy',
            );
    }

    public function userAutobid(){
        $url = urlencode("/userAutobid");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $data['project'] = array();
        $autobidinfo = $this->yzh_conn->from("yzh_user_account")->where("uid",$uid)->get()->result_array();
        if( is_array($autobidinfo) && !empty($autobidinfo) ){
            $data['autobid'] = $autobidinfo[0];
        }else{
            $data['autobid'] = array();
        }
        
        $this->load->view('/userCenter/userAutobid',$data);
    }

    public function userAssignCreditor(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');

        $data['creditp'] = array();
        $data['credit'] = array();
        $data['crediting'] = array();
        $data['credited'] = array();
        //可转让-项目
        $creditp = $this->yzh_conn->select("*,I.id as item_id,P.status as p_status")
            ->from("yzh_project as P")->join("yzh_project_user as I","P.id = I.pro_id")
            ->where(array("I.uid"=>$uid, "P.status"=>10, "I.credit_status"=>0, "I.status"=>1))
            ->get()->result_array();
        foreach($creditp as $k => $v){
            $data['creditp'][$k] = $v;
            $data['creditp'][$k]['year_rate_out'] = $v['year_rate_out'];
            $data['creditp'][$k]['pro_status'] = $v['p_status'];
            $data['creditp'][$k]['cycle'] = $v['cycle'];
            $data['creditp'][$k]['pro_num'] = $v['pro_num'];
            $data['creditp'][$k]['remain_date'] = ($v['cycle']*30) - ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($v['full_time']))))/3600/24);
            $data['creditp'][$k]['discountRatio'] = $this->discountRatio($v['cycle'],$v['full_time']);
        }

        //可转让-债权
        $credit = $this->yzh_conn->select("*,C.id as credit_id,P.status as p_status,C.status as c_status")
            ->from("yzh_credit as C")->join("yzh_project as P","C.pro_id=P.id")
            ->where(array("C.buyer_uid"=>$uid,"C.status"=>10,"P.status"=>10))
            ->get()->result_array();
        foreach($credit as $k => $v){
            $pro = $this->yzh_conn->from("yzh_project")->where("id",$v['pro_id'])->get()->result_array();
            $data['credit'][$k] = $v;
            $data['credit'][$k]['year_rate_out'] = $v['year_rate_out'];
            $data['credit'][$k]['pro_status'] = $v['status'];
            $data['credit'][$k]['cycle'] = $v['cycle'];
            $data['credit'][$k]['pro_num'] = $v['pro_num'];
            $data['credit'][$k]['remain_date'] = ($v['cycle']*30) - ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($v['full_time']))))/3600/24);
            $data['credit'][$k]['discountRatio'] = $this->discountRatio($v['cycle'],$v['full_time']);
        }

        //转让中
        $crediting = $this->yzh_conn->select("*,C.id as credit_id,P.status as p_status")
            ->from("yzh_credit as C")->join("yzh_project as P","C.pro_id=P.id")
            ->where(array("C.creditor_id"=>$uid, "C.status"=>1,"P.status"=>10))
            ->get()->result_array();
        foreach($crediting as $k => $v){
            $pro = $this->yzh_conn->from("yzh_project")->where("id",$v['pro_id'])->get()->result_array();
            $item = $this->yzh_conn->from("yzh_project_user")->where("id",$v['item_id'])->get()->result_array();
            $data['crediting'][$k] = $v;
            $data['crediting'][$k]['year_rate_out'] = $v['year_rate_out'];
            $data['crediting'][$k]['pro_status'] = $v['p_status'];
            $data['crediting'][$k]['cycle'] = $v['cycle'];
            $data['crediting'][$k]['pro_num'] = $v['pro_num'];
            $data['crediting'][$k]['invest_sum'] = $item[0]['invest_sum'];
            $data['crediting'][$k]['remain_date'] = ($v['cycle']*30) - ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($v['full_time']))))/3600/24);
            $data['crediting'][$k]['discountRatio'] = $this->discountRatio($v['cycle'],$v['full_time']);
        }

        //已转让
        $credited = $this->yzh_conn->from("yzh_credit_record")
            ->where(array("from_uid"=>$uid))
            ->get()->result_array();
        foreach($credited as $k => $v){
            $pro = $this->yzh_conn->from("yzh_project")->where("id",$v['pro_id'])->get()->result_array();
            $credit = $this->yzh_conn->from("yzh_credit")->where("id",$v['credit_id'])->get()->result_array();
            $item = $this->yzh_conn->from("yzh_project_user")->where("id",$v['item_id'])->get()->result_array();
            $data['credited'][$k] = $v;
            $data['credited'][$k]['year_rate_out'] = $pro[0]['year_rate_out'];
            $data['credited'][$k]['pro_status'] = $pro[0]['status'];
            $data['credited'][$k]['cycle'] = $pro[0]['cycle'];
            $data['credited'][$k]['pro_num'] = $pro[0]['pro_num'];
            $data['credited'][$k]['deal_time'] = isset($credit[0]) ? $credit[0]['deal_time'] : "--";
            $data['credited'][$k]['buyer_uid'] = isset($credit[0]) ? $credit[0]['buyer_uid'] : 0;
            $data['credited'][$k]['invest_sum'] = $item[0]['invest_sum'];
            if($data['credited'][$k]['buyer_uid'] != 0){
                $userinfo = $this->yzh_conn->from("yzh_user")->where("uid",$credit[0]['buyer_uid'])->get()->result_array();
                $data['credited'][$k]['buyer_uname'] = !empty($userinfo[0]['username']) ? $userinfo[0]['username'] : $userinfo[0]['phone'];
            }else{
                $data['credited'][$k]['buyer_uname'] = "";
            }
            if(strtotime($pro[0]['full_time']) <= 0){
                $data['credited'][$k]['remain_date'] = 0;
            }else{
                $data['credited'][$k]['remain_date'] = ($pro[0]['cycle']*30) - ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($pro[0]['full_time']))))/3600/24);
            }
            $data['credited'][$k]['discountRatio'] = $v['discount'];//$this->discountRatio($pro[0]['cycle'],$pro[0]['full_time']);
        }
// print_R(json_encode($data));exit;
        $this->load->view('/userCenter/userAssignCreditor',$data);
    }

    function projectStatus($status){
        $statusArr = array(
            "1" => "待审核",
            "2" => "初审通过",
            "5" => "已上线",
            "6" => "审核驳回",
            "10" => "已满标",
            "20" => "未满标",
            "25" => "清算完成",
            "30" => "还款延时",
            "80" => "还款完成"
        );
        return $statusArr[$status];
    }

    public function financiaTransactions(){

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
        
        //债权转让-普惠金融
        $result_cp = $this->Project_model->getProjectCreditByTyep(2);
        $data['projectCredit_p'] = $result_cp;
        //债权转让-普惠金融
        $result_cj = $this->Project_model->getProjectCreditByTyep(3);
        $data['projectCredit_j'] = $result_cj;
        //债权转让-普惠金融
        $result_cg = $this->Project_model->getProjectCreditByTyep(4);
        $data['projectCredit_g'] = $result_cg;

        //普惠金融
        $result_p = $this->getProjectByType(array("type" => 2, "status" => 5));
        $data['project_p'] = $result_p;
        //经营理财
        $result_j = $this->getProjectByType(array("type" => 3, "status" => 5));
        $data['project_j'] = $result_j;
        //高端定制
        $result_g = $this->getProjectByType(array("type" => 4, "status" => 5));
        $data['project_g'] = $result_g;

        //系统通知
        $data['systemNotice'] = array();

        //获取headerNavClass
        $data['headerNavClass'] = $this->getHeaderNavClass();

        $this->load->view('/public/header',$data);
        $this->load->view('/financiaTransactions/financiaTransactions',$data);
    }

    function baseinfo()
    {
        $url = urlencode("financiaTransactions/baseinfo");
        $this->islogin($url);
        $userInfo['uid']    = $this->getCookie('uid');
        $userInfo['uname']  = $this->getCookie('uname');
        if(empty($userInfo)){
            $data['user']   = ''; 
        }else{
            $data['user']   = $userInfo['uname'];
        }
        //查询用户信息
        $result = $this->User_model->getUser(1, array('uid' => $userInfo['uid']));
        $result = (array)$result[0];
        $result['user'] = $data['user'];
        //var_dump($result);exit;
        $this->load->view('/userCenter/baseinfo',$result); 
    }

    public function investment(){
        $input = $_GET;
        $view = (isset($input['type']) && $input['type']=="credit") ? "/project/investment_credit" : "/project/investment_project";
        $url = urlencode("/Project/investment");
        $this->islogin($url);
        $userInfo['uid']    = $this->getCookie('uid_cookie');
        $userInfo['uname']  = $this->getCookie('uname_cookie');
        if(empty($userInfo)){
            $data['user']   = ''; 
        }else{
            $data['user']   = $userInfo['uname'];
        }
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $userInfo['uid']) );
        $userInfo = $this->yzh_conn->where("uid",$userInfo['uid'])->get("yzh_user")->result_array();
        if(empty($userInfo)){
            echo "user info error!";exit;
        }
        $userInfo = $userInfo[0];

        $page_config['perpage']     = 5;                                     # 每页条数
        $page_config['part']        = 2;                                     # 当前页前后链接数量
        $page_config['url']         = '/Usercenter/investment?';             # url
        $page_config['nowindex']    = isset($_GET['pg'])?$_GET['pg']:1;      # 当前页
        $limits                     = ($page_config['nowindex']-1)*$page_config['perpage'];

        $this->load->library('Public/MypageClass', null, 'pageclass');
        $countnum = $this->yzh_conn
                    ->from('yzh_project_user')
                    ->where(array("uid"=>$userInfo['uid'],"status !="=>0))
                    ->count_all_results();
        $page_config['total']       = $countnum;
        $this->pageclass->initialize($page_config);

        $mypro = $this->yzh_conn->select('*')
                    ->from('yzh_project_user')
                    ->where(array("uid"=>$userInfo['uid'],"status !="=>0))
                    ->order_by("id","DESC")
                    ->limit($page_config['perpage'],$limits)
                    ->get()->result_array();
        
        if(!empty($mypro)){
            foreach($mypro as $k=>$v){
                $proInfo = $this->Project_model->getProjectById($v['pro_id']);
                if( !empty($v) && !empty($proInfo) ){
                    $mypro[$k]['proInfo'] = $proInfo[0];
                    $mypro[$k]['readyGainPro'] = $this->readyGainPro($v['id'],$v['pro_id']);
                    switch ($proInfo[0]['status']) {
                        case '5':
                            $mypro[$k]['proInfo']['stat'] = '投标中';
                            break;
                        case '10':
                            $mypro[$k]['proInfo']['stat'] = '回款中';
                            break;
                        case '20':
                            $mypro[$k]['proInfo']['stat'] = '未满标，投资失败';
                            break;
                        case '25':
                            $mypro[$k]['proInfo']['stat'] = '已完成';
                            break;
                        case '80':
                            $mypro[$k]['proInfo']['stat'] = '已结束';
                            break;
                        default:
                            $mypro[$k]['proInfo']['stat'] = '<font color="red">状态异常</font>';
                            break;
                    }
                    
                    if($v['credit_status']==1){
                        $mypro[$k]['proInfo']['credit_stat'] = '债权转让中';
                    }elseif($v['credit_status']==10){
                        $mypro[$k]['proInfo']['credit_stat'] = '债权转让已完成';
                    }else{
                        $mypro[$k]['proInfo']['credit_stat'] = '';
                    }

                }else{
                    $mypro = array();
                }
            }
        }

        $page_config_c['perpage_c']     = 4;                                     # 每页条数
        $page_config_c['part_c']        = 2;                                     # 当前页前后链接数量
        $page_config_c['url_c']         = '/Usercenter/investment?';             # url
        $page_config_c['nowindex_c']    = !empty($_GET['pg_c'])?$_GET['pg_c']:1; # 当前页
        $limits_c                     = ($page_config_c['nowindex_c']-1)*$page_config_c['perpage_c'];

        $this->load->library('Public/MypageClassc', null, 'pageclassc');
        $countnum_c = $this->yzh_conn
            ->from('yzh_credit')
            ->where("(`buyer_uid` = ".$userInfo['uid']." and status in (10,15)) or (`creditor_id` = ".$userInfo['uid']." and status = 1)")
            ->count_all_results();           # 得到记录总数
        
        $page_config_c['total_c']       = $countnum_c;
        $this->pageclassc->initialize($page_config_c);

        $myCredit = $this->yzh_conn
            ->from('yzh_credit')
            ->where("(`buyer_uid` = ".$userInfo['uid']." and status in (10,15)) or (`creditor_id` = ".$userInfo['uid']." and status = 1)")
            ->order_by("deal_time","DESC")
            ->limit($page_config_c['perpage_c'],$limits_c)
            ->get()->result_array();

        if(!empty($myCredit)){
            foreach($myCredit as $k => $v){
                $record = $this->yzh_conn->where("credit_id",$v['id'])->get("yzh_credit_record")->result_array();
                if(empty($record)){
                    unset($myCredit[$k]);
                    $countnum_c--;
                    continue;
                }
                $pro_info = $this->yzh_conn->from('yzh_project')->where("id",$v['pro_id'])->get()->result_array();
                $myCredit[$k]['pro_info'] = $pro_info[0];
                $myCredit[$k]['finish_time'] = date("Y-m-d",(strtotime($pro_info[0]['full_time']) + ($pro_info[0]['cycle']*30-1)*24*3600));
                $myCredit[$k]['readyGain'] = $this->readyGainCre($v['id']);
            }
        }
        $data['expected'] = (float)$this->readyGain($userInfo['uid']) + (float)$this->creditReady($userInfo['uid']);
        $data['mypro'] = $mypro;
        $data['myProjectCount'] = $countnum;
        $data['myCredit'] = $myCredit;
        $data['myCreditCount'] = $countnum_c;
        $data['myaccount'] = isset($userAccountInfo[0]) ? $userAccountInfo[0] : array();
        $data['myaccount']['used_money'] = (float)$this->_used_money($userInfo['uid']) + (float)$this->_credit_in_money($userInfo['uid']);
        $data['userInfo'] = $userInfo;
        // print_R(json_encode($data));exit;
        $this->load->view($view,$data);
    }


    //忘记密码 - VIEW
    function findPwd(){
        $data = array();
        $data['p'] = !empty($_GET['p'])?$_GET['p']:'';
        //系统通知
        $data['systemNotice'] = array();

        //获取headerNavClass
        $data['headerNavClass'] = $this->getHeaderNavClass();

        $this->load->view('/public/header',$data);
        $this->load->view('/userCenter/findPwd',$data);
        $this->load->view('/public/footer');
    }

    function findPwdSubmit(){
        //数据过滤
        $phone      = $this->filter_input($_GET['phone']);
        $phonecode  = $this->filter_input($_GET['phonecode']);

        //短信验证
        $this->load->library('session');
        $phoneNum   = $this->session->userdata('phoneNum_'.$phone);
        $phone_code = $this->session->userdata('phoneCode_'.$phone);

        if($phone!=$phoneNum or $phonecode!=$phone_code or empty($phone) or empty($phonecode)){
            echo "手机验证码输入错误。";exit;
        }else{
            $this->session->unset_userdata('phoneCode_'.$phone);
            $this->session->unset_userdata('phoneNum_'.$phone);
        }       
        
        //手机号验证
        if (!preg_match("/1[3458]{1}\d{9}/",$phone)){
            echo "手机号不能为空或格式不正确！";exit;
        }
        //手机号是否存在
        $result = $this->yzh_conn->where("phone",$phone)->get("yzh_user")->result_array();
        if (empty($result)){
            echo "手机号".$phone."不存在！";exit;
        }else{
            echo "success";exit;
        }
    }

    //忘记密码 - 重置密码VIEW
    function pwdRestart(){
        $data = array();
        $data['p'] = !empty($_GET['p'])?$_GET['p']:'';
        //系统通知
        $data['systemNotice'] = array();

        //获取headerNavClass
        $data['headerNavClass'] = $this->getHeaderNavClass();   

        $this->load->view('/public/header',$data);
        $this->load->view('/userCenter/pwdRestart',$data);
        $this->load->view('/public/footer');
    }

    //忘记密码-更新密码
    public function pwdRestartSubmit($phone='')
    {
        $input = $this->input->post();
        $phone = !empty($input['phone'])?$input['phone']:"";
        $new_pwd = !empty($input['newpwd'])?$input['newpwd']:"";
        $renew_pwd = !empty($input['renewpwd'])?$input['renewpwd']:"";

        $userInfo = $this->User_model->getUser( array("phone" => $phone) );
        $userInfo = (array)$userInfo[0];

        if( empty($new_pwd) || $new_pwd!=$renew_pwd ){
            echo json_encode(array("code" => 0,"msg" => "新密码不能为空或输入不一致！"));exit;
        }
        $result = $this->yzh_conn->update("yzh_user",array("pwd1" => md5($new_pwd)),array("phone" => $phone));
        if($result)
        {
            echo json_encode(array("errCode"=>0,"message"=>"success"));exit;
        }else{
            echo json_encode(array("errCode"=>1,"message"=>"failure"));exit;
        }


    }

    function myprojectdetail(){
        $proInfo = $this->Project_model->getProjectById($_GET['pro_id']);
        $data['mypro'] = $proInfo[0];
        $data['mypro']['companyinfo'] = unserialize($proInfo[0]['companyinfo']);
        $data['mypro']['projectinfo'] = unserialize($proInfo[0]['projectinfo']);
        $data['mypro']['financierinfo'] = unserialize($proInfo[0]['financierinfo']);
        $this->load->view('/project/myprojectdetail',$data); 
    }

    public function redrectRecharge()
    {
        $this->load->helper('cookie');
        $this->setCookie('navId_cookie', 'irecharge', time()+3600*24);
        $data['navId'] = 'irecharge';
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $userInfo['uid']    = $this->getCookie('uid_cookie');
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
        $this->load->view('/userCenter/usercenter',$data);
        
    }

    function uploadImg(){
        $url = urlencode("/usercenter");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        if(!empty($_POST['sub'])){
            //打印变量的函数
            
            $file = $_FILES['upfile'];
            //var_dump($file);exit;
            if($file['size'] >= 2097152){
                $this->alert("图片大小不得>2M");exit;
            }
            else{
                switch ($file['type']) {
                    case 'image/jpeg':
                        $type = '.jpg';
                        break;
                    case 'image/pjpeg':
                        $type = '.jpg';
                        break;
                    case 'image/png':
                        $type = '.png';
                        break;
                    case 'image/gif':
                        $type = '.gif';
                        break;
                    default:
                        $type = false;
                        break;
                }
                //上传路径
                $img_path = dirname(dirname(dirname(__DIR__)))."/files/img/";
                $catalog = "head/";
                $path = $img_path.$catalog;
                if(!file_exists($path)) 
                { 
                    mkdir($path, 0777); 
                }
                if($type){
                    $filename = time().rand(1000,9999);
                    $res = move_uploaded_file($file['tmp_name'], $path.$filename.$type);
                    if($res){
                        // 更新用户信息表
                        $img_url = $this->config->config['img_url'].$catalog.$filename.$type;
                        $result = $this->User_model->updateUser( array( "headpic" => $img_url ),array("uid" => $uid) );
                        if($result){
                            $message = "database success";
                        }else{
                            $message = "database failure";
                        }
                    }else{
                        $message = "upload failure";
                    }
                }
                else{
                    echo "不支持该图片类型！";exit;
                }
            }
        }
        $this->load->view("/tools/uploadImg");
    }

    public function get_repayment_list()
    {
        $input = $this->input->get();
        if(!isset($input['item_id'])){
            echo json_encode(array("code"=>1,"msg"=>"params error!"));exit;
        }
        $repayment = $this->getRepaymentList($input['item_id']);
        if(!$repayment){
            echo json_encode(array("code"=>1,"msg"=>"未满标，暂无每期金额！"));exit;
        }
        foreach ($repayment as $key => $value) {
            $settle_params = array(
                "item_id" => $input['item_id'],
                "from_unixtime(unix_timestamp(create_time),'%Y-%m-%d')" => date("Y-m-d"),
                );
            $settle = $this->yzh_conn->where($settle_params)->get("yzh_item_settlement_".date("Ym"))->result_array();
            if(!empty($settle)){
                $repayment[$key]['is_finish'] = $settle[0]['is_finish'];
            }
            $repayment[$key]['repay_principal'] = number_format($value['repay_principal'],2);
            $repayment[$key]['repay_interest'] = number_format($value['repay_interest'],2);
            $repayment[$key]['repay_amount'] = number_format($value['repay_amount'],2);
        }
        $res = array("code"=>0,"msg"=>"success","res"=>$repayment);
        echo json_encode($res);exit;
    }

}
