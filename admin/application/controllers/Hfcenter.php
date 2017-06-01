<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hfcenter extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Asset_model');
        $this->load->model('Project_model');
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        $this->load->library('Public/ApiMobile', null, 'm');
        $this->fileLog = $this->chinapnr->logFile;
    }

    private function writeLog($fileLog, $content)
    {
        $fp = fopen($fileLog,"a");
        fwrite($fp, $content);
        fclose($fp);
    }
    
    //汇付接口：用户注册实名认证--已调通
    function createAccount(){
        $input = $this->input->get();
        if(!isset($input['c']) || !isset($input['phone']) || empty($input['c']) || empty($input['phone'])){
            $this->alert("参数错误！",-1);exit;
        }

        $usrMp      = $input['phone'];
        $merCustId  = $this->config->config['merCustId'];
        $bgRetUrl   = $this->config->config['admin_domain']."/Hfcenter/apiBackCreate";
        $res = $this->chinapnr->userRegister($merCustId, $bgRetUrl, $usrMp, $merPriv='');
    }

    //开户成功后将用户信息存至本地库
    function apiBackCreate(){
        $data       = $_POST;
        $content    = '';
        $upStatus   = '';
        $addStatus  = '';
        
        if(empty($data)){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCreate] param null !";
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCreate] error_code : ".$data['RespCode']." ";      
            $this->chinapnr->writeLogs($content );
            exit;
        }

        //实名数据入库
        $userInfo   = array(
            "hf_usrId"      => $data['UsrId'],      # 用户号
            "hf_usrCustId"  => $data['UsrCustId'],  # 用户客户号
            "realname"      => $data['UsrName'],    # 用户真是姓名
            "identify"      => $data['IdNo'],       # 用户身份证号
            "id_succ"       => 1
        );
        $resupdate  = $this->User_model->updateUser( $userInfo,array("phone" => $data['UsrMp']) );
        $reginfo    = $this->User_model->getUser( array('phone' => $data['UsrMp']) );
        $reginfo    = (array)$reginfo[0];

        $accdata    = array(
            'uid'               => $reginfo['uid'], 
            'money'             => 0, 
            'freeze_money'      => 0, 
            'income'            => 0, 
            'expend'            => 0, 
            'withdrawal_cash'   => 0, 
            'iflock'            => 0,
            'tenderee_money'    => 0
        );
        $resultadd  = $this->User_model->addTendereeAccount($accdata);
        
        if(!$resupdate || !$resultadd){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCreate] update&add failed! ";      
            $this->chinapnr->writeLogs($content );
        }else{
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackCreate] ";
            $content .= json_encode($data);     
            $this->chinapnr->writeLogs($content );
        }
        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

#######################################################################################################
    //绑定银行卡接口 -- 已调通（测试环境 使用银行卡开头 622580 非18位的模拟招行卡）
    public function userBindCard(){
        $uid                = $this->userinfo['uid'];
        $userInfo           = $this->User_model->getLists(array("uid" => $uid));
        $data['userInfo']   = (array)$userInfo[0];
        $merCustId          = $this->config->config['merCustId'];                 # 商户客户号
        if($data['userInfo']['hf_usrCustId'])
        {
            $usrCustId      = $data['userInfo']['hf_usrCustId'];
        }else{
            $this->alert("未获取汇付帐号，请确认已做申请实名认证！",-1);exit;
        }
        $bgRetUrl           = $this->config->config['admin_domain']."/Hfcenter/apiBackBid";
        $merPriv            = $uid;
        $res = $this->chinapnr->userBindCard($merCustId,$usrCustId,$bgRetUrl,$merPriv);
    }

    //帮卡成功后将用户信息存至本地库
    function apiBackBid(){
        $data       = $_POST;
        $content    = '';
        
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackBid] param null !";        
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackBid] error_code : ".$data['RespCode']." ";
            $this->chinapnr->writeLogs($content );
            exit;
        }
         
        //银行卡数据入库
        $cardInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "card_num"      => $data['OpenAcctId'],         # 银行卡号
            "deposit_bank"  => $data['OpenBankId'],         # 银行代号
            "create_time"   => date("Y-m-d H:i:s", time())  # 绑卡时间
        );
        $resultadd  = $this->User_model->addUserBank($cardInfo);
        if(!$resultadd){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackBid] add error !";     
            $this->chinapnr->writeLogs($content );
        }else{
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackBid] ";
            $content .= json_encode($data);     
            $this->chinapnr->writeLogs($content );
        }
        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

######################################################################################################

    //汇付账户充值--已调通（测试接口支持兴业银行卡 622908493458092716 且要求商户余额大于手续费）
    public function Recharge()
    {   
        $uid        = $this->userinfo['uid'];
        $userInfo   = $this->User_model->getLists(array("uid" => $uid));
        $data['userInfo'] = (array)$userInfo[0];

        $this->load->library('Public/ApiMobile', null, 'm');
        $rand       = $this->m->getrandomstr(5,'num');
        $ordernum   = time().$rand;

        $merPriv    = $uid;
        $merCustId  = $this->config->config['merCustId'];                                     # 商户客户号
        //$usrCustId = $this->config->config['merCustId'];                                    # 商户充值（商户余额不足时，进行充值）
        $usrCustId  = $data['userInfo']['hf_usrCustId'];                # 用户客户号
        $ordId      = $ordernum;                                        # 订单ID唯一纯数字自行生成
        $ordDate    = date("Ymd");                                      # 订单日期（20150303）
        $transAmt   = sprintf("%.2f",$_POST['rechargeMoney']);          # 交易金额
        $gateBusiId = $_POST['GateBusiId'];                             # 交易类型
        $openBankId = $_POST['OpenBankId'];                             # 银行代号
        $dcFlag     = 'D';                                              # D--借记，储蓄卡 C--贷记 ，信用卡
        $bgRetUrl   = $this->config->config['admin_domain']."/Hfcenter/apiBackRecharge";                # 回调地址
        $res = $this->chinapnr->netSave($merCustId,$usrCustId,$ordId,$ordDate,$gateBusiId,$openBankId,$dcFlag,$transAmt,$retUrl = '',$bgRetUrl,$merPriv);
    }

    public function apiBackRecharge()
    {
        $data       = $_POST;
        $content    = '';
        
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackRecharge-admin] param null !";       
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackRecharge-admin] error_code : ".$data['RespCode']." ";        
            $this->chinapnr->writeLogs($content );
            exit;
        }

        //防止重复callback
        $userFlow = $this->yzh_conn->where(array("order_id" => $data['OrdId']))->get("yzh_tenderee_flow_".date("Y"))->result_array();
        if(!empty($userFlow)) {
            $content_session .= "\r\n";
            $content_session .= date("Y-m-d H:i:s",time())."\r\n";
            $content_session .= "[apiBackRecharge-admin] error : Repeat Request! ";       
            $this->chinapnr->writeLogs($content_session );
            exit;
        }
        //如果该充值使用快捷充值，则删除其他绑定卡
        if( $data['GateBusiId']=='QP' ){
            $this->User_model->delUserBank(array("uid"=>$data['MerPriv']));
        }
        
        //更新个人账户 user_account表
        $this->getUserBalance($data['MerPriv']);

        //添加充值记录个人流水 user_flow表 
        $cardInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 2,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => '账户充值',                  # 流水说明
            "order_id"      => $data['OrdId'],              # 订单ID
        );
        $resultadd  = $this->yzh_conn->insert('yzh_tenderee_flow_'.date("Y"), $cardInfo);

        //添加充值手续费个人流水 user_flow表 
        $rechargepoundage = "0.00";
        $cardInfo2   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 4,                           # 交易类型
            "amount"        => $rechargepoundage,           # 交易金额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "充值手续费{$rechargepoundage}元",  # 流水说明
            "order_id"      => $data['OrdId'],              # 订单ID
        );
        $resultadd2  = $this->yzh_conn->insert('yzh_tenderee_flow_'.date("Y"), $cardInfo2);

        if( !$resultadd || !$resultadd2){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackRecharge-admin] addFlow error !";     
            $this->chinapnr->writeLogs($content );
        }else{          
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackRecharge-admin] json:".json_encode();
            $content .= json_encode($data);     
            $this->chinapnr->writeLogs($content );
        }

        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

######################################################################################################

    //汇付账户提现--已调通（测试接口支持兴业银行卡 622908493458092716 且要求商户余额大于手续费）
    public function getCash()
    {   
        $uid        = $this->userinfo['uid'];
        $userInfo   = $this->User_model->getLists(array("uid" => $uid));
        $data['userInfo'] = (array)$userInfo[0];

        $this->load->library('Public/ApiMobile', null, 'm');
        $rand      = $this->m->getrandomstr(5,'num');
        $ordernum  = time().$rand;

        $merPriv   = $uid;
        $merCustId = $this->config->config['merCustId'];                                      # 商户客户号
        //$usrCustId = $this->config->config['merCustId'];                                    # 商户充值（商户余额不足时，进行充值）
        $usrCustId = $data['userInfo']['hf_usrCustId'];                 # 用户客户号
        $ordId     = $ordernum;                                         # 订单ID唯一纯数字自行生成
        $ordDate   = date("Ymd");                                       # 订单日期（20150303）
        $transAmt  = sprintf("%.2f",$_POST['withdrawalsMoney']);        # 交易金额
        $bgRetUrl  = $this->config->config['admin_domain']."/Hfcenter/apiBackGetCash";                  # 回调地址
        $reqExt    = '[{"CashChl":"GENERAL"}]';                         # 取现渠道 FAST 快速 | GENERAL 一般 | IMMEDIATE 及时
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        $res = $this->chinapnr->cash($merCustId,$ordId,$usrCustId,$transAmt,$servFee = '',$servFeeAcctId = '',$openAcctId = '',$retUrl = '',$bgRetUrl,$remark = '',$charSet = '',$merPriv,$reqExt);
    }

    public function apiBackGetCash()
    {
        $data       = $_POST;
        $content    = '';

        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackGetCash-admin] param null !";        
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackGetCash-admin] error_code : ".$data['RespCode']." ";     
            $this->chinapnr->writeLogs($content );
            exit;
        }
        //防止重复callback
        $userFlow = $this->yzh_conn->where(array("order_id" => $data['OrdId']))->get("yzh_tenderee_flow_".date("Y"))->result_array();
        if(!empty($userFlow)) {
            $content_session  = '';
            $content_session .= "\r\n";
            $content_session .= date("Y-m-d H:i:s",time())."\r\n";
            $content_session .= "[apiBackGetCash-admin] error : Repeat Request! ";       
            $this->chinapnr->writeLogs($content_session );
            exit;
        }

        echo "RECV_ORD_ID_".$data['OrdId'];

        //调用取现复核接口
        $merCustId = $this->config->config['merCustId'];                                      # 商户客户号        
        $ordId     = $data['OrdId'];                                    # 订单ID唯一纯数字自行生成
        $usrCustId = $data['UsrCustId'];                                # 用户客户号
        $transAmt  = sprintf("%.2f",$data['TransAmt']);                 # 交易金额
        $auditFlag = 'S';                                               # R--拒绝 S--复核通过
        $retUrl    = '';
        $bgRetUrl  = $this->config->config['admin_domain']."/Hfcenter/apiBackCashAudit";                # 回调地址
        $merPriv   = $data['MerPriv'];
        $res = $this->chinapnr->cashAudit($merCustId,$ordId,$usrCustId,$transAmt,$auditFlag,$retUrl = '',$bgRetUrl,$merPriv);
    }

######################################################################################################
    public function apiBackCashAudit(){
        $data       = $_POST;
        $content    = '';
        $content .= "\r\n";
        $content .= date("Y-m-d H:i:s",time())."\r\n";
        $content .= "[apiBackCashAudit-admin] ".json_encode($data);
        $this->chinapnr->writeLogs($content );
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCashAudit-admin] param null !";
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCashAudit-admin] error_code : ".$data['RespCode']." ";
            $this->chinapnr->writeLogs($content );
            exit;
        }

        //防止重复callback
        $userFlow = $this->User_model->getUserFlow(array("order_id" => $data['OrdId']));
        if(is_array($userFlow[0]) && !empty($userFlow[0])) {
            $content_session = "";
            $content_session .= "\r\n";
            $content_session .= date("Y-m-d H:i:s",time())."\r\n";
            $content_session .= "[apiBackCashAudit-admin] error : Repeat Request! ";       
            $this->chinapnr->writeLogs($content_session );
            exit;
        }

        //更新个人账户 user_account表
        $this->getUserBalance($data['MerPriv']);

        //添加提现记录个人流水 user_flow表 
        $cardInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 2,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => '账户提现',                  # 流水说明
            "order_id"      => $data['OrdId'],              # 订单ID
        );
        $resultadd  = $this->yzh_conn->insert('yzh_tenderee_flow_'.date("Y"), $cardInfo);

        //添加提现手续费个人流水 user_flow表 
        $cashpoundage = "0.00";
        $cardInfo2   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 4,                           # 交易类型
            "amount"        => $cashpoundage,               # 交易金额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "提现手续费{$cashpoundage}元",  # 流水说明
            "order_id"      => $data['OrdId'],              # 订单ID
        );
        $resultadd2  = $this->yzh_conn->insert('yzh_tenderee_flow_'.date("Y"), $cardInfo2);

        if(!$resultadd || !$resultadd2){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackCashAudit-admin] addFlow error !";
            $this->chinapnr->writeLogs($content );
        }else{
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackCashAudit-admin] ";
            $content .= json_encode($data);
            $this->chinapnr->writeLogs($content );
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }
    //汇付主动投标--已调通
    public function doInvestment()
    {   
        //投资项目
        $UId            = $this->getCookie('uid_cookie');
        $pid            = !empty($_POST['pid'])?$_POST['pid']:'';
        $buid           = !empty($_POST['buid'])?$_POST['buid']:'';
        $invest_sum     = !empty($_POST['invest_sum'])?$_POST['invest_sum']:'';
        $remain_amount  = !empty($_POST['remain_amount'])?$_POST['remain_amount']:'';
        $projectInfo = $this->Project_model->getProjectById($pid); 
        $projectInfo = $projectInfo[0];

        if($invest_sum > $remain_amount){
            $this->alert( "投资金额超出该项目剩余融资金额！" );exit;
        }

        //冻结投资金额
        if($projectInfo['remain_amount']==0 && $projectInfo['status']==10){
            $this->alert( "该项目已满,请选择其他项目！" );exit;
        }

        //判断用户余额是否充足
        $userAccount = $this->User_model->getUserAccount(1,array('uid' => $UId));
        $userAccount = (array)$userAccount[0];
        if($userAccount['withdrawal_cash'] < $invest_sum){
            $this->alert( "账户可用余额不足！" );exit;
        }

        //生成汇付订单号
        $this->load->library('Public/ApiMobile', null, 'm');
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $uproject = array(
            'uid'           => $UId,
            'tenderee_id'   => $projectInfo['tenderee_uid'],
            'pro_id'        => $pid,
            'invest_sum'    => $invest_sum,
            'create_time'   => date("Y-m-d H:i:s", time()),
            'hforder_id'    => $ordernum
        );

        $result = $this->Project_model->addUserProject($uproject);
        if($result){

            $borrowUinfo = $this->User_model->getUser( array("uid" => $buid) );
            $borrowUinfo = (array)$borrowUinfo[0];
            $userInfo = $this->User_model->getUser( array("uid" => $UId) );
            $userInfo = (array)$userInfo[0];

            $BorrowerCustId     = $borrowUinfo['hf_usrCustId'];                         # 融资人汇付ID
            $merPriv            = $UId;                                                 # 用户ID
            $merCustId          = $this->config->config['merCustId'];                                         # 商户客户号
            $usrCustId          = $userInfo['hf_usrCustId'];                            # 用户客户号
            $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
            $ordDate            = date("Ymd");                                          # 订单日期（20150303）
            $transAmt           = sprintf("%.2f",$invest_sum);                          # 交易金额
            $maxTenderRate      = $this->config->config['hf_max_tender_rate'];          # 数最大投资手续费率 
            $borrowerDetails    = '[{"BorrowerCustId":"'.$BorrowerCustId.'","BorrowerAmt":"'.$transAmt.'","BorrowerRate":"'.$this->config->config['hf_borrower_rate'].'"}]';
            $BorrowerAmt        = $transAmt;                                            # 投资金额
            $BorrowerRate       = $this->config->config['hf_borrower_rate'];            # 借款手续费率 0.00<= BorrowerRate <=1.00
            $ProId              = $pid;                                                 # 项目ID
            $IsFreeze           = 'N';                                                  # 是否冻结 Y--冻结 N--不冻结 
            $bgRetUrl           = $this->config->config['admin_domain']."/Hfcenter/apiBackDoInvestment";                # 回调地址
            $Hfres = $this->chinapnr->initiativeTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$BorrowerCustId,$BorrowerAmt,$BorrowerRate,$ProId,$IsFreeze,$freezeOrdId = '',$retUrl = '',$bgRetUrl,$merPriv,$RespExt='');      
        }else{
            $this->alert( "投资失败！" );exit;
        }
    }

    public function apiBackDoInvestment()
    {
        $data       = $_POST;
        $content    = '';
        
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackDoInvestment] param null !";       
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackDoInvestment] error_code : ".$data['RespCode']." ";        
            $this->chinapnr->writeLogs($content );
            exit;
        }
        
        //事物回滚开启
        $this->yzh_conn->trans_begin();

        //投资成功，修改项目表信息
        $projectUser = $this->Project_model->getMyProject( array("hforder_id"=>$data['OrdId']) );
        $projectUser = $projectUser[0];
        $projectInfo = $this->Project_model->getProjectById($projectUser['pro_id']); 
        $projectInfo = $projectInfo[0];
        if($data['TransAmt'] == $projectInfo['remain_amount']){
            $project = array(
                    'gained_amount' => $projectInfo['gained_amount']+$data['TransAmt'],
                    'remain_amount' => $projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt'],
                    'full_time'     => date("Y-m-d H:i:s",time()),
                    'status'        => 10
                );
            //满标调用汇付标的信息输入接口
            // $res_add_bid = $this->addBidInfo($projectUser['pro_id']);
        }else{
            $project = array(
                    'gained_amount' => $projectInfo['gained_amount']+$data['TransAmt'],
                    'remain_amount' => $projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt'],
                    'status'        => 5
                );
        }
        $res_project     = $this->Project_model->updateProject($projectInfo['id'],$project);
        $projectup       = isset($res_project)?1:'project update failed!';

        //投资成功，修改投资信息状态为已完成
        $res_projectUser = $this->Project_model->updateProjectUser( $projectUser['id'],array( "status"=>1 ) );
        $projectUserup   = isset($res_projectUser)?1:'projectUser update failed!';

        //更新个人账户 user_account表
        $this->getUserBalance($data['MerPriv']);

        //添加提现记录个人流水 user_flow表
        $cardInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 6,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "remaining_amount"  => $userAccountInfo['money']-$data['TransAmt'],     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "成功投标项目".$projectInfo['pro_num']       # 交易说明
        );
        $resultadd  = $this->User_model->addUserFlow($cardInfo);
        if( $resultadd ){
            $userFlowadd = 1;
        }else{
            $userFlowadd = 'userAccount update failed!';
        }

        if( ($projectup==1)&&($projectUserup ==1)&&($userAccountup==1)&&($userFlowadd==1) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackDoInvestment] ";
            $content .= json_encode($data);     
            $this->chinapnr->writeLogs($content );
            $this->yzh_conn->trans_commit();                # 完全执行成功，提交数据更新
        }else{
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackDoInvestment] error ! projectup:".$projectup."|projectUserup:".$projectUserup."|userAccountup:".$userAccountup."|userFlowadd:".$userFlowadd;       
            $this->chinapnr->writeLogs($content );
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    /*
     * 自动投标计划接口-返回null
     */
    public function autoInvestmentPlan()
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
            $userInfo = $this->yzh_conn->from("yzh_user")->where("uid",$uid)->get()->result_array();
            $merCustId = $this->config->config['merCustId'];
            $usrCustId = $userInfo[0]['hf_usrCustId'];
            $tenderPlanType = 'W';                  # P--部分授权 W--完全授权 
            $transAmt = '';                 # 投资金额(部分授权)
            $merPriv = '';
            $retUrl = $this->config->config['admin_domain']."/Hfcenter/apiBackautoInvestmentPlan"; 
            $res = $this->chinapnr->autoTenderPlan($merCustId,$usrCustId,$tenderPlanType,$transAmt,$retUrl = '',$merPriv = '');
            echo json_encode(array("code" => 1,"msg" => json_encode($res)));exit;
            //echo json_encode(array("code" => 1,"msg" => "设置成功！"));exit;
        }else{
            //echo "设置失败！";exit;
            echo json_encode(array("code" => 0,"msg" => "设置失败！"));exit;
        }

    }
    
    /*
     * 自动投标计划关闭接口-返回null
     */
    public function autoInvestmentPlanClose()
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
            $userInfo = $this->yzh_conn->from("yzh_user")->where("uid",$uid)->get()->result_array();
            $merCustId = $this->config->config['merCustId'];
            $usrCustId = $userInfo[0]['hf_usrCustId'];
            $merPriv = '';
            $retUrl = $this->config->config['admin_domain']."/Hfcenter/apiBackautoInvestmentPlanClose";
            $res = $this->chinapnr->autoTenderPlanClose($merCustId,$usrCustId,$retUrl = '',$merPriv = '');
            echo json_encode(array("code" => 1,"msg" => json_encode($res)));exit;
            //echo json_encode(array("code" => 1,"msg" => "设置成功！"));exit;
        }else{
            //echo "设置失败！";exit;
            echo json_encode(array("code" => 0,"msg" => "设置失败！"));exit;
        }

    }

    function apiBackautoInvestmentPlan(){
        $data = $_POST;
        $content = "\r\n".date('Y-m-d H:i:s', time())."\r\n[apiBackautoInvestmentPlan]:".json_encode($data);
        $this->chinapnr->writeLogs($content);
    }

    function apiBackautoInvestmentPlanClose(){
        $data = $_POST;
        $content = "\r\n".date('Y-m-d H:i:s', time())."\r\n[apiBackautoInvestmentPlanClose]:".json_encode($data);
        $this->chinapnr->writeLogs($content);
    }

######################################################################################################

    //汇付自动投标--正在调试
    //自动投标规则：1、能一次吸收投资金额的标的优先；2，在1规则的基础上利率高的优先
    public function autoInvestment()
    {   
        //投资项目
        $UId            = $this->getCookie('uid_cookie');
        $pid            = !empty($_POST['pid'])?$_POST['pid']:'';
        $buid           = !empty($_POST['buid'])?$_POST['buid']:'';
        $invest_sum     = !empty($_POST['invest_sum'])?$_POST['invest_sum']:'';
        $remain_amount  = !empty($_POST['remain_amount'])?$_POST['remain_amount']:'';
        $projectInfo = $this->Project_model->getProjectById($pid); 
        $projectInfo = $projectInfo[0];

        if($invest_sum > $remain_amount){
            $this->alert( "投资金额超出该项目剩余融资金额！" );exit;
        }

        //冻结投资金额
        if($projectInfo['remain_amount']==0 && $projectInfo['status']==10){
            $this->alert( "该项目已满,请选择其他项目！" );exit;
        }

        //判断用户余额是否充足
        $userAccount = $this->User_model->getUserAccount(1,array('uid' => $UId));
        $userAccount = (array)$userAccount[0];
        if($userAccount['withdrawal_cash'] < $invest_sum){
            $this->alert( "账户可用余额不足！" );exit;
        }

        //生成汇付订单号
        $this->load->library('Public/ApiMobile', null, 'm');
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $uproject = array(
            'uid'           => $UId,
            'tenderee_id'   => $projectInfo['tenderee_uid'],
            'pro_id'        => $pid,
            'invest_sum'    => $invest_sum,
            'create_time'   => date("Y-m-d H:i:s", time()),
            'hforder_id'    => $ordernum
        );

        $result = $this->Project_model->addUserProject($uproject);
        if($result){

            $borrowUinfo = $this->User_model->getUser( array("uid" => $buid) );
            $borrowUinfo = (array)$borrowUinfo[0];
            $userInfo = $this->User_model->getUser( array("uid" => $UId) );
            $userInfo = (array)$userInfo[0];

            $merPriv            = $UId;                                                 # 用户ID
            $merCustId          = $this->config->config['merCustId'];                                         # 商户客户号
            $usrCustId          = $userInfo['hf_usrCustId'];                            # 用户客户号
            $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
            $ordDate            = date("Ymd");                                          # 订单日期（20150303）
            $transAmt           = sprintf("%.2f",$invest_sum);                          # 交易金额
            $maxTenderRate      = $this->config->config['hf_max_tender_rate'];          # 数最大投资手续费率 
            $BorrowerCustId     = $borrowUinfo['hf_usrCustId'];                         # 融资人汇付ID
            $borrowerDetails    = '[{"BorrowerCustId":"'.$BorrowerCustId.'","BorrowerAmt":"'.$transAmt.'","BorrowerRate":"'.$this->config->config['hf_borrower_rate'].'"}]';
            // $BorrowerAmt        = $transAmt;                                            # 投资金额
            
            // $BorrowerRate       = $this->config->config['hf_borrower_rate'];            # 借款手续费率 0.00<= BorrowerRate <=1.00
            // $ProId              = $pid;                                                 # 项目ID
            // $IsFreeze           = 'N';                                                  # 是否冻结 Y--冻结 N--不冻结 
            $bgRetUrl           = $this->config->config['admin_domain']."/Hfcenter/apiBackautoInvestment";              # 回调地址
            $Hfres = $this->chinapnr->autoTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$retUrl = '',$bgRetUrl,$merPriv);      
        }else{
            $this->alert( "投资失败！" );exit;
        }
    }

    public function apiBackautoInvestment()
    {
        $data       = $_POST;
        $content    = '';
        
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackautoInvestment] param null !";       
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackautoInvestment] error_code : ".$data['RespCode']." ";        
            $this->chinapnr->writeLogs($content );
            exit;
        }
        
        //事物回滚开启
        $this->yzh_conn->trans_begin();

        //投资成功，修改项目表信息
        $projectUser = $this->Project_model->getMyProject( array("hforder_id"=>$data['OrdId']) );
        $projectUser = $projectUser[0];
        $projectInfo = $this->Project_model->getProjectById($projectUser['pro_id']); 
        $projectInfo = $projectInfo[0];
        if($data['TransAmt'] == $projectInfo['remain_amount']){
            $project = array(
                    'gained_amount' => $projectInfo['gained_amount']+$data['TransAmt'],
                    'remain_amount' => $projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt'],
                    'full_time'     => date("Y-m-d H:i:s",time()),
                    'status'        => 10
                );
            //满标调用汇付标的信息输入接口
            // $res_add_bid = $this->addBidInfo($projectUser['pro_id']);
        }else{
            $project = array(
                    'gained_amount' => $projectInfo['gained_amount']+$data['TransAmt'],
                    'remain_amount' => $projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt'],
                    'status'        => 5
                );
        }
        $res_project     = $this->Project_model->updateProject($projectInfo['id'],$project);
        $projectup       = isset($res_project)?1:'project update failed!';

        //投资成功，修改投资信息状态为已完成
        $res_projectUser = $this->Project_model->updateProjectUser( $projectUser['id'],array( "status"=>1 ) );
        $projectUserup   = isset($res_projectUser)?1:'projectUser update failed!';

        //更新个人账户 user_account表
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $data['MerPriv']) );
        $userAccountInfo = (array)$userAccountInfo[0];
        $accountInfo     = array(
            "money"             => $userAccountInfo['money']-$data['TransAmt'],             # 账户总资产--
            "expend"            => $userAccountInfo['expend']+$data['TransAmt'],            # 支出++
            "used_money"        => $userAccountInfo['used_money']+$data['TransAmt'],        # 已投金额++
            "withdrawal_cash"   => $userAccountInfo['withdrawal_cash']-$data['TransAmt']    # 可提现金额--
        );
        $resultup        = $this->User_model->updateUserAccount($data['MerPriv'],$accountInfo);
        $userAccountup   = isset($resultup)?1:'userAccount update failed!';

        //添加提现记录个人流水 user_flow表
        $cardInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 5,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "remaining_amount"  => $userAccountInfo['money']+$data['TransAmt'],     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => '项目投资'                   # 交易说明
        );
        $resultadd  = $this->User_model->addUserFlow($cardInfo);
        if( $resultadd ){
            $userFlowadd = 1;
        }else{
            $userFlowadd = 'userAccount update failed!';
        }

        if( ($projectup==1)&&($projectUserup ==1)&&($userAccountup==1)&&($userFlowadd==1) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackDoInvestment] ";
            $content .= json_encode($data);     
            $this->chinapnr->writeLogs($content );
            $this->yzh_conn->trans_commit();                # 完全执行成功，提交数据更新
        }else{
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackDoInvestment] error ! projectup:".$projectup."|projectUserup:".$projectUserup."|userAccountup:".$userAccountup."|userFlowadd:".$userFlowadd;       
            $this->chinapnr->writeLogs($content );
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    /*
     * 投资额满标之后调用汇付标的信息录入接口
     */
    public function addBidInfo($proid)
    {
        $proInfo = $this->yzh_conn->from("yzh_project")->where("id",$proid)->get()->result_array();
        $borrowerInfo = $this->yzh_conn->from("yzh_user")->where("uid",$proInfo[0]['tenderee_uid'])->get()->result_array();
        $merCustId = $this->config->config['merCustId'];
        $proId = $proid;
        $borrCustId = $borrowerInfo[0]['hf_usrCustId'];
        $borrTotAmt = sprintf("%.2f",$proInfo[0]['amount']);
        $yearRate = sprintf("%.2f",$proInfo[0]['year_rate_out']/100);
        $retType = '03';
        $bidStartDate = date("YmdHis",strtotime($proInfo[0]['create_time']));
        $bidEndDate = date("YmdHis");
        $retAmt = sprintf("%.2f",$proInfo[0]['amount']*(1+$proInfo[0]['year_rate_out']/100*$proInfo[0]['cycle']/12));
        $retDate = date("Ymd",strtotime("+".(string)($proInfo[0]['cycle']*30)." days"));
        $proArea = '1200';
        $guarCompId = '';
        $guarAmt = '';
        $bgRetUrl = $this->config->config['admin_domain']."/Hfcenter/apiBackAddBidInfo";
        $merPriv = '';
        $reqExt = '';
        $res = $this->chinapnr->objectTypein($merCustId, $proId, $borrCustId, $borrTotAmt, $yearRate, $retType, $bidStartDate, $bidEndDate, $retAmt, $retDate, $proArea, $guarCompId='', $guarAmt='', $bgRetUrl, $merPriv, $reqExt="");
        return $res;
    }


    /*
     * 标的信息录入回调函数
     */
    public function apiBackAddBidInfo()
    {
        $input = $this->input->post();
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAddBidInfo]:".json_encode($input)."\r\n";
        $this->chinapnr->writeLogs($content);
        echo "RECV_ORD_ID_".$_POST['ProId'];exit;
    }

######################################################################################################

    //债权转让
    public function hfCredit()
    {
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $merCustId      = $this->config->config['merCustId'];                     # 商户客户号
        $sellCustId     = $_POST['hf_usrCustId'];           # 转让人客户号
        $creditAmt      = sprintf("%.2f",$_POST['credit_amount']);                              # 转让金额
        $creditDealAmt  = sprintf("%.2f",$_POST['real_amount']);                                # 承接金额

        $bidDetails = '{"BidDetails":[{"BidOrdId":"'.$_POST['bidOrdId'].'","BidOrdDate":"'.date("Ymd").'","BidCreditAmt":"'.sprintf("%.2f",$_POST['credit_amount']).'","BorrowerDetails":[{"BorrowerCustId":"'.$_POST['jk_hf_usrCustId'].'","BorrowerCreditAmt":"'.sprintf("%.2f",$_POST['credit_amount']).'","PrinAmt":"0.00","ProId":"'.$_POST['pro_id'].'"}]}]}'; 
        
        $fee            = "0.00";                           # 放款或扣款的手续费
        $divDetails     = '';
        $buyCustId      = $data['userInfo']['hf_usrCustId'];# 承接人客户号
        $ordId          = $ordernum;                        # 订单ID唯一纯数字自行生成
        $ordDate        = date("Ymd");                      # 订单日期（20150303）
        $bgRetUrl       = $this->config->config['admin_domain']."/Hfcenter/apiBackPCredit";     # 回调地址
        $merPrivData = array(
            'pro_id' => $_POST['pro_id'],
            'hforder_id' => $_POST['bidOrdId'],
            'credit_id'=>$_POST['credit_id'],
            'credit_to_uid' => $uid,
            );
        $merPriv        = json_encode($merPrivData);
        $res = $this->chinapnr->creditAssign($merCustId,$sellCustId,$creditAmt,$creditDealAmt,$bidDetails,$fee,$divDetails,$buyCustId,$ordId,$ordDate,$retUrl = '',$bgRetUrl,$merPriv,$reqExt = '');
    }

    public function apiBackPCredit()
    {
        $data       = $_POST;
        $content    = '';
        
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n".json_encode($data)."\r\n" );
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackPCredit] param null !";
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackPCredit] error_code : ".$data['RespCode']." ";
            $this->chinapnr->writeLogs($content );
            exit;
        }

        $merPriv = json_decode($data['MerPriv'],true);
        //更新project_user表数据
        $upd_data = array('credit_status' => 20, 'credit_to_uid' => $merPriv['credit_to_uid']);
        $res_item = $this->yzh_conn->where("hforder_id",$merPriv['hforder_id'])->update('yzh_project_user',$upd_data);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n更新project_user表数据:".$res_item."\r\n" );

        //更新credit表数据
        $item = $this->yzh_conn->from("yzh_project_user")->where("hforder_id",$merPriv['hforder_id'])->get()->result_array();
        $credit_data = array(
                'buyer_uid' => $merPriv['credit_to_uid'],
                'status' => 10,
                'deal_time' => date("Y-m-d H:i:s"),
            );
        $res_credit = $this->yzh_conn->where("id",$merPriv['credit_id'])->update("yzh_credit",$credit_data);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n更新credit表数据:".$res_credit."\r\n" );

        //新增credit_record表数据
        $credit = $this->yzh_conn->from("yzh_credit")->where("id",$merPriv['credit_id'])->get()->result_array();
        $record_data = array(
                'pro_id' => $merPriv['pro_id'],
                'item_id' => $credit[0]['item_id'],
                'credit_id' => $merPriv['credit_id'],
                'from_uid' => $credit[0]['creditor_id'],
                'to_uid' => $merPriv['credit_to_uid'],
                'from_amount' => $credit[0]['credit_amount'],
                'to_amount' => $credit[0]['real_amount'],
                'discount' => $credit[0]['discount'],
                'deal_time' => date("Y-m-d H:i:s"),
            );
        $res_record = $this->yzh_conn->insert("yzh_credit_record",$record_data);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增credit_record表数据:".$res_record."\r\n" );
############################################ 出让人
        //更新个人账户 user_account表
        $userAccountInfoout = $this->User_model->getUserAccount( 1, array("uid" => $data['MerPriv']) );
        $userAccountInfoout = (array)$userAccountInfoout[0];
        $accountInfoout     = array(
            "money"             => $userAccountInfoout['money']-$data['CreditAmt']+$data['CreditDealAmt'],  # 账户总资产--
            "expend"            => $userAccountInfoout['income']+$data['CreditDealAmt'],                     # 收入++
            "used_money"        => $userAccountInfoout['used_money']-$data['CreditAmt'],                 # 已投金额++
            "withdrawal_cash"   => $userAccountInfoout['withdrawal_cash']+$data['CreditDealAmt']    # 可提现金额--
        );
        $resultupout        = $this->User_model->updateUserAccount($data['MerPriv'],$accountInfoout);
        $userAccountupout   = isset($resultupout)?1:'userAccountout update failed!';
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n更新债权出让人user_account表数据:".$userAccountupout."\r\n" );

        //添加债权出让人记录 user_flow表
        $cardInfoout   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 10,                          # 交易类型
            "amount"        => $data['CreditDealAmt'],      # 交易金额
            "remaining_amount"  => $userAccountInfoout['money']+$data['CreditDealAmt'],     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "成功购买债权(原始项目)"//"成功购买债权(原始项目".$projectInfo['pro_num'].")"       # 交易说明
        );
        $resultaddout  = $this->User_model->addUserFlow($cardInfoout);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增债权出让人user_flow表数据:".$resultaddout."\r\n" );

############################################ 购买人
        //更新购买人账户 user_account表
        $userInfoin = $this->User_model->getUser( array("hf_usrCustId" => $data['BuyCustId']) );
        $userInfoin = (array)$userInfoin[0];
        $userAccountInfoin = $this->User_model->getUserAccount( 1, array("uid" => $userInfoin['uid']) );
        $userAccountInfoin = (array)$userAccountInfoin[0];
        $accountInfoin     = array(
            "money"             => $userAccountInfoin['money']+$data['CreditAmt']-$data['CreditDealAmt'],   # 账户总资产--
            "expend"            => $userAccountInfoin['expend']+$data['CreditDealAmt'],                     # 支出++
            "used_money"        => $userAccountInfoin['used_money']+$data['CreditAmt'],                     # 已投金额++
            "withdrawal_cash"   => $userAccountInfoin['withdrawal_cash']-$data['CreditDealAmt']             # 可提现金额--
        );
        $resultupin        = $this->User_model->updateUserAccount($userInfoin['uid'],$accountInfoin);
        $userAccountupin   = isset($resultupin)?1:'userAccount update failed!';
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n更新债权购买人user_account表数据:".$userAccountupin."\r\n" );

        //添加债权购买记录 user_flow表
        $cardInfoin   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 9,                           # 交易类型
            "amount"        => $data['CreditDealAmt'],      # 交易金额
            "remaining_amount"  => $userAccountInfoin['money']+$data['CreditDealAmt'],  # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "成功出让债权(原始项目)"//"成功出让债权(原始项目".$projectInfo['pro_num'].")"          # 交易说明
        );
        $resultaddin  = $this->User_model->addUserFlow($cardInfoin);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增债权购买人user_flow表数据:".$resultaddin."\r\n" );

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    //商户无卡代扣充值(需申请开通，暂时不用)
    public function bizRecharge()
    {
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $merCustId = $this->config->config['merCustId'];                                      # 商户客户号
        $usrCustId = $this->config->config['merCustId'];                                      # 用户客户号
        $ordId     = $ordernum;                                         # 订单ID唯一纯数字自行生成
        $ordDate   = date("Ymd");                                       # 订单日期（20150303）
        $transAmt  = "10000.00";//$amount;                              # 交易金额
        $bgRetUrl  = $this->config->config['admin_domain']."/Hfcenter/apiBackBizRecharge";              # 回调地址
        $res = $this->chinapnr->posWhSave($merCustId,$usrCustId,$openAcctId,$transAmt,$ordId,$ordDate,$checkDate = '',$retUrl = '',$bgRetUrl,$merPriv = '');
        //posWhSave
    }

    public function apiBackBizRecharge()
    {
        $data       = $_POST;
        $content    = '';
        
        if( empty($data) ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())." : ";
            $content .= "[apiBackBizRecharge] param null !";
            $this->chinapnr->writeLogs($content );
            exit;
        }
        if( $data['RespCode'] != '000' ){
            $content .= "\r\n";
            $content .= date("Y-m-d H:i:s",time())."\r\n";
            $content .= "[apiBackBizRecharge] error_code : ".$data['RespCode']." ";     
            $this->chinapnr->writeLogs($content );
            exit;
        }
        
        $content .= "\r\n";
        $content .= date("Y-m-d H:i:s",time())."\r\n[apiBackBizRecharge] ";
        $content .= json_encode($data);     
        $this->chinapnr->writeLogs($content );

        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

######################################################################################################

    //自动扣款（还款）
    public function autoRepayment()
    {
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $merCustId = $this->config->config['merCustId'];                                      # 商户客户号
        $ordId     = $ordernum;                                         # 订单ID唯一纯数字自行生成
        $ordDate   = date("Ymd");                                       # 订单日期（20150303）
        $outCustId = '';
        $subOrdId  = '';
        $subOrdDate = '';
        $outAcctId = '';
        $transAmt  = "10000.00";//$amount;                              # 交易金额
        $fee       = '';
        $inCustId  = '';
        $feeObjFlag = '';
        $bgRetUrl  = $this->config->config['admin_domain']."/Hfcenter/apiBackAutoRepayment";            # 回调地址
        $res = $this->chinapnr->repayment($merCustId,$ordId,$ordDate,$outCustId,$subOrdId,$subOrdDate,$outAcctId = '',$transAmt,$fee,$inCustId,$inAcctId = '',$divDetails = '',$feeObjFlag,$bgRetUrl,$merPriv = '',$reqExt = '');
        //posWhSave
    }

    //自动还款给用户
    public function apiBackAutoRepayment()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //防止重复callback
        $userFlow = $this->yzh_conn->where("hf_order_id", $data['OrdId'])->get("yzh_item_settlement_".date("Ym"))->result_array();
        if(!empty($userFlow)) {
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]: error Repeat Request!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        $merPriv = json_decode($data['MerPriv'],true);
        $settle_id = $merPriv['settle_id'];
        $in_user_balance = $merPriv['in_user_balance'];
        $settle_info = $this->yzh_conn->where("id",$settle_id)->get("yzh_item_settlement_".date("Ym"))->result_array();
        if(empty($settle_info)){
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]no settle info" );
            $this->yzh_conn->trans_rollback();exit;
        }
        $item_info = $this->yzh_conn->where("id",$settle_info[0]['item_id'])->get("yzh_project_user")->result_array();
        if(empty($item_info)){
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]no item info" );
            $this->yzh_conn->trans_rollback();exit;
        }
        $pro_info = $this->yzh_conn->where("id",$settle_info[0]['pro_id'])->get("yzh_project")->result_array();
        if(empty($pro_info)){
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]no pro info" );
            $this->yzh_conn->trans_rollback();exit;
        }

        //开启事务处理
        $this->yzh_conn->trans_begin();

        $hf_tenderee = $this->getUserBalance($settle_info[0]['tenderee_id']);
        $hf_user = $this->getUserBalance($settle_info[0]['uid']);

        //修改settlement表数据
        $settlement_info = $this->yzh_conn
            ->where(array("id"=>$settle_id))
            ->get("yzh_item_settlement_".date("Ym"))->result_array();

        $settle_upd_data = array(
                'is_finish' => 1,
                'pay_time' => date("Y-m-d H:i:s"),
                'hf_order_id' => $data['OrdId'],
            );
        $res_settle = $this->yzh_conn->update("yzh_item_settlement_".date("Ym"),$settle_upd_data,array("id"=>$settle_id));

        //update project_user
        $item_upd_data = array(
                'last_settle_time' => date("Y-m-d H:i:s"),
            );
        $res_item = $this->yzh_conn->update("yzh_project_user",$item_upd_data,array("id",$settle_info[0]['item_id']));

        //update project如果回款金额大于投资金额，置为还款完成
        if($settle_info[0]['settlement_gain'] >= $item_info[0]['invest_sum']){
            $pro_upd_data = array(
                    'status' => 80,// 还款完成
                );
            $res_project = $this->yzh_conn->update("yzh_project",$pro_upd_data,array("id"=>$settle_info[0]['pro_id']));
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]update_project:".$this->yzh_conn->last_query()."\r\n");
        }else{
            $res_project = true;
        }

        /**
        * 添加user资金流水
        */
        $user_flow = array(
            'uid' => $settle_info[0]['uid'],
            'pro_id' => $settle_info[0]['pro_id'],
            'pro_name' => $pro_info[0]['pro_name'],
            'amount' => $settle_info[0]['settlement_gain'],
            'remaining_amount' => (float)$in_user_balance+(float)$settle_info[0]['settlement_gain'],
            'status' => 5,
            'create_time' => date("Y-m-d H:i:s"),
            'order_id' => $data['OrdId'],
            );
        if($settle_info[0]['settlement_gain'] >= $item_info[0]['invest_sum']){
            $user_flow['type'] = 8;//回款本息
            $user_flow['comment'] = "回款本息到账";
        }else{
            $user_flow['type'] = 7;//回款利息
            $user_flow['comment'] = "回款利息到账";
        }
        $res_user_flow = $this->yzh_conn->insert("yzh_user_flow_".date("Y"),$user_flow);

        /**
        * 添加tenderee资金流水
        */
        $tenderee_flow = array(
            'uid' => $settle_info[0]['tenderee_id'],
            'amount' => $settle_info[0]['settlement_pay'],
            'status' => 5,
            'create_time' => date("Y-m-d H:i:s"),
            'order_id' => $data['OrdId'],
            );
        if($settle_info[0]['settlement_gain'] >= $item_info[0]['invest_sum']){
            $tenderee_flow['type'] = 6;//回款本息
            $tenderee_flow['comment'] = "项目：[".$pro_info[0]['pro_name']."]已回款本息，金额：".number_format($settle_info[0]['settlement_pay'],2);
        }else{
            $tenderee_flow['type'] = 7;//回款利息
            $tenderee_flow['comment'] = "项目：[".$pro_info[0]['pro_name']."]已回款利息，金额：".number_format($settle_info[0]['settlement_pay'],2);
        }
        $res_tenderee_flow = $this->yzh_conn->insert("yzh_tenderee_flow_".date("Y"),$tenderee_flow);

        /**
        *修改user_account表
        *已得总收益
        */
        $gain_interest = 0;
        if($settle_info[0]['settlement_gain'] >= $item_info[0]['invest_sum']){
            $gain_interest = $settle_info[0]['settlement_gain'] - $item_info[0]['invest_sum'];
        }else{
            $gain_interest = $settle_info[0]['settlement_gain'];
        }
        $res_user_account = $this->yzh_conn->set("gain_total","gain_total+".$gain_interest,false)->where("uid",$settle_info[0]['uid'])->update("yzh_user_account");
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]user_account:".$this->yzh_conn->last_query()."\r\n");

        if(($res_settle == 1)&&($res_item == 1)&&($res_project == 1)&&($res_user_flow==1)&&($res_tenderee_flow==1)&&($res_user_account==1)){
            $this->yzh_conn->trans_commit();
            # 投资成功 发送短信息开始
            $user_info = $this->yzh_conn->where("uid",$settle_info[0]['uid'])->get("yzh_user")->row_array();
            $mobile = isset($user_info['phone']) ? $user_info['phone'] : "" ;
            $send_title = $this->config->config['send_msg_title'];
            $send_kftel = $this->config->config['send_msg_kftel'];
            $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
            if($settle_info[0]['settlement_gain'] >= $item_info[0]['invest_sum']){
                $msg_type = "本息";//回款本息
            }else{
                $msg_type = "利息";//回款利息
            }
            $send_text  = $send_title."尊敬的用户，您在云智慧金融平台上投资的项目于".$cur_date."收到".$msg_type.$settle_info[0]['settlement_gain']."元，详情请登录云智慧官网查询。";
            @$this->m->tpl_send_sms($mobile,$send_text);
            # 发送短信息结束
        }else{
            $this->yzh_conn->trans_rollback();
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackDoInvestment] error ! res_settle:".$res_settle."|res_item:".$res_item."|res_project:".$res_project."|res_user_flow:".$res_user_flow."|res_tenderee_flow:".$res_tenderee_flow."|res_user_account:".$res_user_account."\r\n";       
            $this->chinapnr->writeLogs($content);
            exit;
        }
        
        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

    /**
    *验证商户还款信息(暂时不需要)
    */
    public function validateUsrAcctPay()
    {
        $input = $this->input->post();
        if(!isset($input['pro_id']) || $input['pro_id']==0){
            echo json_encode(array("code"=>0,"msg"=>"param pro_id error!"));exit;
        }
        if($this->role_type != 7 || empty($this->uid)){
            echo json_encode(array("code"=>0,"msg"=>"param user error!"));exit;
        }
        //search settlement list
        $where = array(
            "pro_id" => $input['pro_id'],
            "tenderee_id" => $this->uid,
            // "is_finish" => 2,
            // "from_unixtime(unix_timestamp(create_time),'%Y-%m-%d')" => date("Y-m-d"),
        );
        $res_settlement = $this->yzh_conn
            ->where($where)
            ->where_in("is_finish",array(2,5))//1：全部返款完成，2：否，5：已还给用户，10：已还给平台
            ->get("yzh_item_settlement_".date("Ym"))->result_array();
        
        if(empty($res_settlement)){//没有需还款记录
            echo json_encode(array("code"=>0,"msg"=>"项目ID：".$input['pro_id']." 没有需要还款的纪录！"));exit;
        }
        echo json_encode(array("code"=>200,"msg"=>"success!"));exit;
    }

    /**
     * 用户手动给商户还款(暂时不需要)
     */
    public function usrAcctPay()
    {
        $input = $this->input->post();

        //search settlement list
        $where = array(
            "pro_id" => $input['pro_id'],
            "tenderee_id" => $this->uid,
            // "is_finish" => 2,
            // "from_unixtime(unix_timestamp(create_time),'%Y-%m-%d')" => date("Y-m-d"),
        );
        $res_settlement = $this->yzh_conn
            ->where($where)
            ->where_in("is_finish",array(2,5))//1：全部返款完成，2：否，5：已还给用户，10：已还给平台
            ->get("yzh_item_settlement_".date("Ym"))->result_array();
        
        //获取要还款的金额
        $amount = 0;
        foreach($res_settlement as $k => $v){
            $amount += ($v['settlement_pay'] - $v['settlement_gain']);
        }
        $amount = number_format($amount,2,".","");

        $tenderee_info = $this->yzh_conn->where("uid",$this->uid)->get("yzh_user")->row_array();

        $ordId = time().rand(10000,99999);
        $usrCustId = $tenderee_info['hf_usrCustId'];
        $merCustId = $this->config->config['merCustId'];
        $transAmt = $amount;
        $inAcctId = "MDT000001";
        $inAcctType = "MERDT";
        $retUrl = $this->config->config['base_url']."/welcome";
        $bgRetUrl = $this->config->config['base_url']."/Hfcenter/apibackUsrAcctPay";
        $merPriv = base64_encode(serialize(array("pro_id"=>$input['pro_id'],"tenderee_uid"=>$this->uid)));

        $this->chinapnr->usrAcctPay($ordId,$usrCustId,$merCustId,$transAmt,$inAcctId,$inAcctType,$retUrl,$bgRetUrl,$merPriv);
        
    }

    //用户手动给商户还款 callback(暂时不需要)
    public function apibackUsrAcctPay()
    {
        $data = $this->input->post();
        $this->chinapnr->writeLogs(date("Y-m-d H:i:s")."\r\n[apibackUsrAcctPay]: ".json_encode($data)."\r\n");

        //开启事务处理
        $this->yzh_conn->trans_begin();

        //添加借款人流水记录
        $tenderee_flow_data = array(
            'uid' => $this->uid,
            'type' => 6,
            'amount' => number_format($data['TransAmt'],2,".",""),
            'status' => 5,
            'create_time' => date("Y-m-d H:i:s"),
            'comment' => "借款人还款给平台",
            'order_id' => $data['OrdId'],
            );
        $tenderee_flow_res = $this->yzh_conn->insert("yzh_tenderee_flow_".date("Y"),$tenderee_flow_data);

        $merPriv = unserialize(base64_decode($data['MerPriv']));

        //修改settlement表数据
        $settlement_info = $this->yzh_conn
            ->where(array("pro_id"=>$merPriv['pro_id'],"tenderee_uid"=>$merPriv['tenderee_uid']))
            ->get("yzh_item_settlement_".date("Ym"))->result_array();
        //1：全部返款完成，2：否，5：已还给用户，10：已还给平台
        if(!empty($settlement_info)){
            if($settlement_info[0]['is_finish']==2){
                $is_finish = 10;
            }else if($settlement_info[0]['is_finish']==5){
                $is_finish = 1;
            }
        }
        $settlement_upd_data = array(
            'is_finish' => $is_finish,
            'pay_time' => date("Y-m-d H:i:s"),
            );
        $settlement_upd_where = array(
            'pro_id' => $merPriv['pro_id'],
            'tenderee_id' => $merPriv['tenderee_uid'],
            );
        $settlement_res = $this->yzh_conn->update("yzh_item_settlement_".date("Ym"),$settlement_upd_data,$settlement_upd_where);
        
        //修改project表
        $pro_info = $this->yzh_conn->where("id",$merPriv['pro_id'])->get("yzh_project")->row_array();
        if($pro_info['status'] < 70){//未还款状态
            $status = 70;//给用户还款完成
        }else if($pro_info['status'] == 75){//给平台还款完成
            $status = 80;//全部还款完成
        }
        $project_res = $this->yzh_conn->update("yzh_project",array("status"=>$status),array("id"=>$pro_info["id"]));

        if($tenderee_flow_res && $settlement_res && $project_res){
            $this->yzh_conn->trans_commit();
        }else{
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apibackUsrAcctPay] error ! tenderee_flow_res:".$tenderee_flow_res."|settlement_res:".$settlement_res."|project_res:".$project_res."\r\n";       
            $this->chinapnr->writeLogs($content);
            $this->yzh_conn->trans_rollback();
            exit;
        }
        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    //汇付接口：用户登录--已调通(测试环境密码yzh_123456/正是环境用户密码在注册成功后会短信发送给用户)
    public function hflogin()
    {
        $merCustId  = $this->config->config['merCustId'];         # 商户客户号
        $usrCustId  = $_GET['usrCustId'];   # 保存在用户表中的hf_usrCustId字段，用户客户号
        $res = $this->chinapnr->userLogin($merCustId, $usrCustId);
    }

    //余额查询
    public function getUserBalance($uid){
        $user_info = $this->yzh_conn->where("uid",$uid)->get("yzh_user")->result_array();
        $merCustId = $this->config->config['merCustId'];
        $usrCustId = !empty($user_info[0]['hf_usrCustId']) ? $user_info[0]['hf_usrCustId'] : "" ;
        $res = $this->chinapnr->queryBalanceBg($merCustId,$usrCustId);
        $user_account_upd_data = array(
                'money' => str_replace(",","",$res['AcctBal']),//总资产
                'withdrawal_cash' => str_replace(",","",$res['AvlBal']),//可用余额
                );
        //user_account
        $this->yzh_conn->update("yzh_user_account",$user_account_upd_data,array("uid"=>$uid));
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[update user account] : ".$this->yzh_conn->last_query()."\r\n";
        $this->chinapnr->writeLogs($content);
        //tenderee_account
        $this->yzh_conn->update("yzh_tenderee_account",$user_account_upd_data,array("uid"=>$uid));
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[update user account] : ".$this->yzh_conn->last_query()."\r\n";
        $this->chinapnr->writeLogs($content);
        return $res;
    }

    public function test()
    {
        $uid = $this->input->get("uid");
        $res = $this->getUserBalance($uid);
        print_R(json_encode($res));
    }

    //自动扣款转账（商户用）｜测试环境已调通
    public function transfer_test()
    {
        $ordId = time().rand(10000,99999);
        $outCustId = $this->config->config['merCustId'];
        $outAcctId = "MDT000001";
        $transAmt = "10000.00";
        $inCustId = "6000060002723557";
        $inAcctId = "";
        $retUrl = "";
        $bgRetUrl = $this->config->config['base_url']."/Hfcenter/apibackTransfer";
        $merPriv = "";

        $res = $this->chinapnr->transfer($ordId,$outCustId,$outAcctId,$transAmt,$inCustId,$inAcctId,$retUrl,$bgRetUrl,$merPriv);
        print_R(json_encode($res));
    }

    public function apibackTransfer()
    {
        $data = $this->input->post();
        $this->chinapnr->writeLogs(date("Y-m-d H:i:s")."\r\n[apibackTransfer]: ".json_encode($data)."\r\n");
        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

}
