<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hfcenter extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Project_model');
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        $this->load->library('Public/ApiMobile', null, 'm');
        $this->fileLog = $this->chinapnr->logFile;
    }

    function writeLog($fileLog,$content)
    {
        $fp = fopen($fileLog,"a+");
        @chmod($fileLog,0777);
        fwrite($fp, $content);
        fclose($fp);
    }
    
    //汇付接口：用户注册实名认证--已调通
    function createAccount(){
        if(isset($_GET['c'])){
            $this->load->library('session');
            $usrMp      = $this->session->userdata('createAccount_'.$_GET['c']);
            if( empty($usrMp) ){
                $usrMp  = $_GET['phone'];
            }
            if(isset($_GET['isback'])){
                $merPriv  = $_GET['isback'];
            }else{
                $merPriv  = "2";
            }
            $merCustId  = $this->config->config['hf_merCustId'];
            $bgRetUrl   = $this->config->config['pc_domain']."/Hfcenter/apiBackCreate";
            $res = $this->chinapnr->userRegister($merCustId, $bgRetUrl, $usrMp, $merPriv);
        }else{
            $this->alert("参数错误！",-1);exit;
        }
    }

    //开户成功后将用户信息存至本地库
    function apiBackCreate(){
        $data       = $_POST;

        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackCreate]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
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
        if($data['MerPriv']==1){
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
        }else{
            $accdata    = array(
                'uid'               => $reginfo['uid'], 
                'money'             => 0, 
                'freeze_money'      => 0, 
                'income'            => 0, 
                'expend'            => 0, 
                'used_money'        => 0, 
                'withdrawal_cash'   => 0, 
                'gain_total'        => 0,
                'gain_curr_day'     => 0
            );
            $resultadd  = $this->User_model->addUserAccount($accdata);
        }
        
        if(!$resupdate || !$resultadd){
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackCreate] update&add failed!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }
        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

#######################################################################################################
    //绑定银行卡接口 -- 已调通（测试环境 使用银行卡开头 622580 非18位的模拟招行卡）
    public function userBindCard(){

        $url                = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid                = $this->getCookie('uid_cookie');
        $userInfo           = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo']   = (array)$userInfo[0];
        $merCustId          = $this->config->config['hf_merCustId'];                 # 商户客户号
        if($data['userInfo']['hf_usrCustId'])
        {
            $usrCustId      = $data['userInfo']['hf_usrCustId'];
        }else{
            $this->alert("未获取汇付帐号，请确认已做申请实名认证！",-1);exit;
        }
        $bgRetUrl           = $this->config->config['pc_domain']."/Hfcenter/apiBackBid";
        $merPriv            = $uid;
        $res = $this->chinapnr->userBindCard($merCustId,$usrCustId,$bgRetUrl,$merPriv);
    }

    //帮卡成功后将用户信息存至本地库
    function apiBackBid(){
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackBid]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
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
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackBid]: add error !\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }
        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

######################################################################################################

    //汇付账户充值--已调通（测试接口支持兴业银行卡 622908493458092716 且要求商户余额大于手续费）
    public function Recharge()
    {
        $url        = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid        = $this->getCookie('uid_cookie');
        $userInfo   = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];
        switch ($_POST['GateBusiId']) {
            case 'B2C':
                $bankID = $_POST['OpenBankId'];
                break;
            case 'QP':
                $bankID = $_POST['kbank'];
                break;
            default:
                $bankID = '';
                break;
        }
        $rand       = $this->m->getrandomstr(5,'num');
        $ordernum   = time().$rand;

        $merPriv    = $uid;
        $merCustId  = $this->config->config['hf_merCustId'];                                     # 商户客户号
        //$usrCustId = $this->config->config['hf_merCustId'];                                    # 商户充值（商户余额不足时，进行充值）
        $usrCustId  = $data['userInfo']['hf_usrCustId'];                # 用户客户号
        $ordId      = $ordernum;                                        # 订单ID唯一纯数字自行生成
        $ordDate    = date("Ymd");                                      # 订单日期（20150303）
        $transAmt   = sprintf("%.2f",$_POST['rechargeMoney']);          # 交易金额
        $gateBusiId = $_POST['GateBusiId'];                             # 交易类型
        $openBankId = $bankID;                                          # 银行代号
        $dcFlag     = 'D';                                              # D--借记，储蓄卡 C--贷记 ，信用卡
        $bgRetUrl   = $this->config->config['pc_domain']."/Hfcenter/apiBackRecharge";                # 回调地址
        $res = $this->chinapnr->netSave($merCustId,$usrCustId,$ordId,$ordDate,$gateBusiId,$openBankId,$dcFlag,$transAmt,$retUrl = '',$bgRetUrl,$merPriv);
    }

    public function apiBackRecharge()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackRecharge]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //防止重复callback
        $userFlow = $this->yzh_conn->where(array("order_id"=>$data['OrdId'],"status"=>5))->get("yzh_user_flow_".date("Y"))->result_array();
        $this->chinapnr->writeLogs(json_encode($userFlow));
        if(!empty($userFlow)) {
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackRecharge]: error Repeat Request!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        //如果该充值使用快捷充值，则删除其他绑定卡
        if( $data['GateBusiId']=='QP' ){
            $this->User_model->delUserBank( array("uid"=>$data['MerPriv']) );
            //判断快捷充值的卡是否存在，如不存在则添加到用户银行卡表
            $isExist = $this->yzh_conn->where(array("card_num"=>$data['CardId'],"uid"=>$data['MerPriv']))->get("yzh_user_bank")->result_array();
            if(empty($isExist)) {
                //银行卡数据入库
                $cardInfo   = array(
                    "uid"           => $data['MerPriv'],            # 用户ID
                    "card_num"      => $data['CardId'],             # 银行卡号
                    "deposit_bank"  => $data['GateBankId'],         # 银行代号
                    "type"          => 2,                           # 银行卡类型：快捷卡
                    "create_time"   => date("Y-m-d H:i:s", time())  # 绑卡时间
                );

                $resultadd  = $this->User_model->addUserBank($cardInfo);
                if(!$resultadd){
                    $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackRecharge]: QP ：add cardInfo error !\r\n";
                    $this->chinapnr->writeLogs($content);exit;
                }
            }
        }
        
        //更新个人账户 user_account表
        $user_avl_bal = $this->getUserBalance($data['MerPriv']);

        //更新个人账户 user_account表 # 充值总额++
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $data['MerPriv']) );
        $userAccountInfo = $userAccountInfo[0];
        $accountInfo = array("recharge_total" => $userAccountInfo['recharge_total']+$data['TransAmt']);
        $resultup   = $this->User_model->updateUserAccount($data['MerPriv'],$accountInfo);

        //更新用户等级
        $userLevelInfo = $this->User_model->getLevelConfig(array("1"=>"1"));
        $userLevelInfo = $userLevelInfo[0];
        $recharge = $userAccountInfo['recharge_total']+$data['TransAmt'];

        //先获取用户当前等级
        $userCurLevelInfo = $this->User_model->getUser(array("uid" => $data['MerPriv']));
        $userCurLevelInfo = intval($userCurLevelInfo[0]);

        if(is_array($userLevelInfo) && !empty($userLevelInfo)){
            foreach ($userLevelInfo as $k => $v) {
                if($recharge>=$v['min_money'] && $recharge<=$v['max_money']){
                    if($userCurLevelInfo['level'] >= $v['level']){
                        continue;
                    }
                    $array      = array("level"=>$v['level']);
                    $this->User_model->updateUser($array,array("uid" => $data['MerPriv'])); 
                    if($v['level'] == 3){
                        //变为高级会员发送短信和站内信
                        $mobile     = $userCurLevelInfo['phone'];
                        $send_title = $this->config->config['send_msg_title'];
                        $send_kftel = $this->config->config['send_msg_kftel'];
                        $sys_msg    = "尊敬的用户,恭喜您已成为云智慧高端会员。您可以登录云智慧官网投资专为高端用户准备的高端定制项目。如需了解更多详情，请致电".$send_kftel."。";
                        $send_text  = $send_title.$sys_msg;
                        @$this->sed_tpl_msg($mobile,$send_text);
                        @$this->sed_sys_msg($data['MerPriv'],"成为高端会员通知",$sys_msg);
                    }
                }
            }
        }

        //添加充值记录个人流水 user_flow表 
        $userFlowInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 1,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$user_avl_bal['AvlBal']),     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => '账户充值',                  # 流水说明
            "order_id"      => $data['OrdId']               # 交易号
        );
        $flowadd    = $this->User_model->addUserFlow($userFlowInfo);

        //添加充值手续费记录 user_flow表 
        $poundage = "0.00";
        $userFlowInfop  = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 3,                           # 交易类型
            "amount"        => $poundage,                   # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$user_avl_bal['AvlBal']),     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "充值手续费".$poundage."元",     # 流水说明
            "order_id"      => $data['OrdId']               # 交易号
        );
        $flowaddp    = $this->User_model->addUserFlow($userFlowInfop);

        # 充值成功后发送短信开始
        $userInfoin = $this->User_model->getUser( array("hf_usrCustId" => $data['UsrCustId']) );
        $userInfoin = (array)$userInfoin[0];
        $mobile     = $userInfoin['phone'];
        $send_title = $this->config->config['send_msg_title'];
        $send_kftel = $this->config->config['send_msg_kftel'];
        $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
        $sys_msg    = "尊敬的用户，您已于".$cur_date."成功充值".$data['TransAmt']."元。如有疑问，请致电".$send_kftel."。";
        $send_text  = $send_title.$sys_msg;
        @$this->sed_tpl_msg($mobile,$send_text);
        @$this->sed_sys_msg($data['MerPriv'],"充值成功",$sys_msg);

        # 充值成功后发送短信结束

        if( !$flowadd || !$flowaddp){
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackRecharge]: add&update error!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        echo "RECV_ORD_ID_".$data['TrxId'];exit;
    }

######################################################################################################

    //汇付账户提现--已调通（测试接口支持兴业银行卡 622908493458092716 且要求商户余额大于手续费）
    public function getCash()
    {   
        $cashType = !empty($_POST['cashtype'])?$_POST['cashtype']:'FAST';
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];

        $rand      = $this->m->getrandomstr(5,'num');
        $ordernum  = time().$rand;

        $merPriv   = $uid;
        $merCustId = $this->config->config['hf_merCustId'];                                      # 商户客户号
        //$usrCustId = $this->config->config['hf_merCustId'];                                    # 商户充值（商户余额不足时，进行充值）
        $usrCustId = $data['userInfo']['hf_usrCustId'];                 # 用户客户号
        $ordId     = $ordernum;                                         # 订单ID唯一纯数字自行生成
        $ordDate   = date("Ymd");                                       # 订单日期（20150303）
        $transAmt  = sprintf("%.2f",$_POST['withdrawalsMoney']);        # 交易金额
        $bgRetUrl  = $this->config->config['pc_domain']."/Hfcenter/apiBackGetCash";                  # 回调地址
        $reqExt    = '[{"CashChl":"'.$cashType.'"}]';                         # 取现渠道 FAST 快速 | GENERAL 一般 | IMMEDIATE 及时
        $res = $this->chinapnr->cash($merCustId,$ordId,$usrCustId,$transAmt,$servFee = '',$servFeeAcctId = '',$openAcctId = '',$retUrl = '',$bgRetUrl,$remark = '',$charSet = '',$merPriv,$reqExt);
    }

    public function apiBackGetCash()
    {
        $data     = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackGetCash]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //防止重复callback
        $userFlow = $this->User_model->getUserFlow(array("order_id" => $data['OrdId']));
        if(!empty($userFlow)) {
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackGetCash]: error Repeat Request!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }
        echo "RECV_ORD_ID_".$data['OrdId'];

        //调用取现复核接口
        $merCustId = $this->config->config['hf_merCustId'];                                      # 商户客户号        
        $ordId     = $data['OrdId'];                                    # 订单ID唯一纯数字自行生成
        $usrCustId = $data['UsrCustId'];                                # 用户客户号
        $transAmt  = sprintf("%.2f",$data['TransAmt']);                 # 交易金额
        $auditFlag = 'S';                                               # R--拒绝 S--复核通过
        $retUrl    = '';
        $bgRetUrl  = $this->config->config['pc_domain']."/Hfcenter/apiBackCashAudit";                # 回调地址
        $merPriv   = $data['MerPriv'];
        $res = $this->chinapnr->cashAudit($merCustId,$ordId,$usrCustId,$transAmt,$auditFlag,$retUrl = '',$bgRetUrl,$merPriv);
        
    }

    //取现复核接口
    public function apiBackCashAudit(){
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackCashAudit]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //防止重复callback
        $userFlow = $this->User_model->getUserFlow(array("order_id" => $data['OrdId']));
        if(is_array($userFlow[0]) && !empty($userFlow[0])) {
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackCashAudit]: error Repeat Request!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        //更新个人账户 user_account表
        $resBalance = $this->getUserBalance($data['MerPriv']);

        //更新个人账户 user_account表 # 可提现金额++
        $userAccountInfo = $this->User_model->getUserAccount( 1, array("uid" => $data['MerPriv']) );
        $userAccountInfo = (array)$userAccountInfo[0];
        $accountInfo = array("withdrawal_cash_total" => $userAccountInfo['withdrawal_cash_total']+$data['TransAmt']);
        $resultup    = $this->User_model->updateUserAccount($data['MerPriv'],$accountInfo);

        //添加提现记录个人流水 user_flow表 
        $userFlowInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 2,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$resBalance['AvlBal']),   # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => '账户提现',                  # 流水说明
            "order_id"      => $data['OrdId']               # 交易号
        );
        $resultadd  = $this->User_model->addUserFlow($userFlowInfo);

        //添加提现手续费个人流水 user_flow表 
        $cashpoundage = "0.00";
        $userFlowInfop   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "type"          => 4,                           # 交易类型
            "amount"        => $cashpoundage,               # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$resBalance['AvlBal']),   # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "提现手续费$cashpoundage元", # 流水说明
            "order_id"      => $data['OrdId']               # 交易号
        );
        $resultadd2  = $this->User_model->addUserFlow($userFlowInfop);

        # 提现成功后发送短信开始
        $userInfoin = $this->User_model->getUser( array("hf_usrCustId" => $data['UsrCustId']) );
        $userInfoin = (array)$userInfoin[0];
        $mobile     = $userInfoin['phone'];
        $send_title = $this->config->config['send_msg_title'];
        $send_kftel = $this->config->config['send_msg_kftel'];
        $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
        $sys_msg    = "尊敬的用户，您已于".$cur_date."申请提现".$data['TransAmt']."元。预计取款到账需1~3个工作日。如有疑问，请致电".$send_kftel."。";
        $send_text  = $send_title.$sys_msg;
        @$this->sed_tpl_msg($mobile,$send_text);
        @$this->sed_sys_msg($data['MerPriv'],"提现申请",$sys_msg);
        # 提现成功后发送短信结束

        if( !$resultadd || !$resultadd2 || !$resultup ){
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackCashAudit]: add & update error!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    //汇付主动投标--已调通
    public function doInvestment()
    {
        //投资项目
        $UId            = $this->getCookie('uid_cookie');
        $pid            = !empty($_POST['pid'])?$_POST['pid']:'';
        $ptype          = !empty($_POST['ptype'])?$_POST['ptype']:'';
        $buid           = !empty($_POST['buid'])?$_POST['buid']:'';
        $invest_sum     = !empty($_POST['invest_sum'])?$_POST['invest_sum']:'';
        $remain_amount  = !empty($_POST['remain_amount'])?$_POST['remain_amount']:'';
        $projectInfo = $this->Project_model->getProjectById($pid); 
        $projectInfo = $projectInfo[0];

        //生成汇付订单号
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
            $merCustId          = $this->config->config['hf_merCustId'];                # 商户客户号
            $usrCustId          = $userInfo['hf_usrCustId'];                            # 用户客户号
            $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
            $ordDate            = date("Ymd");                                          # 订单日期（20150303）
            $transAmt           = sprintf("%.2f",$invest_sum);                          # 交易金额
            $maxTenderRate      = $this->config->config['hf_max_tender_rate'];          # 数最大投资手续费率 
            $borrowerDetails    = '[{"BorrowerCustId":"'.$BorrowerCustId.'","BorrowerAmt":"'.$transAmt.'","BorrowerRate":"'.$this->config->config['hf_borrower_rate'].'"}]';
            $BorrowerAmt        = $transAmt;                                            # 投资金额
            $BorrowerRate       = $this->config->config['hf_borrower_rate'];                                     # 借款手续费率 0.00<= BorrowerRate <=1.00
            $ProId              = $pid;                                                 # 项目ID
            $IsFreeze           = 'Y';                                                  # 是否冻结 Y--冻结 N--不冻结 
            $freezeOrdId        = time().rand(1000,9999);                       # 冻结单号
            $bgRetUrl           = $this->config->config['pc_domain']."/Hfcenter/apiBackDoInvestment";                # 回调地址
            $Hfres = $this->chinapnr->initiativeTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$BorrowerCustId,$BorrowerAmt,$BorrowerRate,$ProId,$IsFreeze,$freezeOrdId,$retUrl = '',$bgRetUrl,$merPriv,$RespExt='');      
        }else{
            $this->alert( "投资失败！" );exit;
        }
    }

    public function apiBackDoInvestment()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackDoInvestment]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }
        
        //事物回滚开启
        $this->yzh_conn->trans_begin();

        //获取信息
        $projectUser = $this->Project_model->getMyProject( array("hforder_id"=>$data['OrdId']) );
        $projectUser = $projectUser[0];
        $projectInfo = $this->Project_model->getProjectById($projectUser['pro_id']); 
        $projectInfo = $projectInfo[0];

        //投资成功，修改投资信息状态为已完成
        $params = array("status"=>1,"freeze_trx_id"=>$data['FreezeTrxId']);  
        $res_projectUser = $this->Project_model->updateProjectUser($projectUser['id'], $params);
        $projectUserup   = isset($res_projectUser)?1:'projectUser update failed!';

        //更新个人账户 user_account表
        $user_avl_bal = $this->getUserBalance($data['MerPriv']);

        //添加投标冻结记录个人流水 user_flow表
        $userFlowInfo   = array(
            "uid"           => $data['MerPriv'],            # 用户ID
            "pro_id"        => $projectInfo['id'],          # 项目ID
            "pro_name"      => $projectInfo['pro_name'],    # 项目名称
            "type"          => 5,                           # 交易类型
            "amount"        => $data['TransAmt'],           # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$user_avl_bal['AvlBal']),     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s", time()), # 交易时间
            "comment"       => "投标[".$projectInfo['pro_num']."]，资金冻结",       # 交易说明
        );
        $resultadd  = $this->User_model->addUserFlow($userFlowInfo);
        if( $resultadd ){
            $userFlowadd = 1;
        }else{
            $userFlowadd = 'userAccount update failed!';
        }

        //更新project表 置投资完成
        $project = array(
                    'gained_amount' => $projectInfo['gained_amount']+$data['TransAmt'],
                    'remain_amount' => $projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt'],
                    'status'        => 5
                );
        $res_project     = $this->Project_model->updateProject($projectInfo['id'],$project);
        $projectup       = isset($res_project)?1:'project update failed!';

        if(($projectup == 1)&&($projectUserup == 1)&&($userFlowadd== 1) ){
            # 投资成功 发送短信息开始
            $userInfoin = $this->User_model->getUser( array("hf_usrCustId" => $data['UsrCustId']) );
            $userInfoin = (array)$userInfoin[0];
            $mobile     = $userInfoin['phone'];
            $send_title = $this->config->config['send_msg_title'];
            $send_kftel = $this->config->config['send_msg_kftel'];
            $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
            $sys_msg    = "您已于".$cur_date."成功投资云智慧金融平台".$data['TransAmt']."元。如有疑问，请致电".$send_kftel."。";
            $send_text  = $send_title.$sys_msg;
            @$this->sed_tpl_msg($mobile,$send_text);
            @$this->sed_sys_msg($data['MerPriv'],"投资成功",$sys_msg);
            # 发送短信息结束

            $this->yzh_conn->trans_commit();                # 完全执行成功，提交数据更新
        }else{
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackDoInvestment] update data error! projectUserup:".$projectUserup."|userAccountup:".$userAccountup."|userFlowadd:".$userFlowadd;
            $this->chinapnr->writeLogs($content);
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            exit;
        }

        echo "RECV_ORD_ID_".$data['OrdId'];

        //判断是否满标，满标调 放款 接口，修改project表状态
        if($data['TransAmt'] == $projectInfo['remain_amount']){
            //验证是否已经满标，金额是否正确
            if(($projectInfo['amount']-$projectInfo['gained_amount']-$data['TransAmt']) != 0){
                $content .= "\r\n";
                $content .= date("Y-m-d H:i:s",time())."\r\n";
                $content .= "[loans] amount error! investment not full !";
                $this->chinapnr->writeLogs($content);
            }

            $item_info = $this->yzh_conn
                ->where(array("pro_id"=>$projectUser['pro_id'],"status"=>1))
                ->get("yzh_project_user")->result_array();
            foreach($item_info as $k => $v){
                $out_user_info = $this->yzh_conn->where(array("uid"=>$v['uid']))->get("yzh_user")->result_array();
                $in_user_info = $this->yzh_conn->where(array("uid"=>$v['tenderee_id']))->get("yzh_user")->result_array();
                $ordernum = time().rand(10000,99999);
                
                $merPriv            = $v['id'];                                             # 用户ID
                $merCustId          = $this->config->config['hf_merCustId'];                                         # 商户客户号
                $proId              = substr($projectInfo['pro_num'],2);
                $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
                $ordDate            = date("Ymd");                                          # 订单日期（20150303）
                $outCustId          = $out_user_info[0]['hf_usrCustId'];                    # 出账人客户号
                $transAmt           = sprintf("%.2f",$v['invest_sum']);                          # 交易金额
                $fee                = "0.00";
                $subOrdId           = $v['hforder_id'];
                $subOrdDate         = date("Ymd",strtotime($v['create_time']));
                $inCustId           = $in_user_info[0]['hf_usrCustId'];
                $divDetails         = '';//'[{"DivCustId":"6000060002609324","DivAcctId":"'.$v['tenderee_id'].'","DivAmt":"1.00"}]';
                $feeObjFlag         = '';
                $isDefault          = 'N';
                $isUnFreeze         = 'Y';
                $unFreezeOrdId      = time().rand(1000,9999);
                $freezeTrxId        = $v['freeze_trx_id'];
                $bgRetUrl           = $this->config->config['pc_domain']."/Hfcenter/apiBackLoans"; # 回调地址
                $reqExt             = '';
                // 满标调用汇付放款借口
                $Hfres = $this->chinapnr->loans($merCustId,$ordId,$ordDate,$outCustId,$transAmt,$fee,$subOrdId,$subOrdDate,$inCustId,$divDetails,$feeObjFlag,$isDefault,$isUnFreeze,$unFreezeOrdId,$freezeTrxId,$bgRetUrl,$merPriv,$reqExt);
            }
        }
    }

    /*
     * 投资满标，放款回调函数
     */
    public function apiBackLoans()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackLoans]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //获取信息
        $item = $this->Project_model->getMyProject( array("id"=>$data['MerPriv']) );
        $item = $item[0];
        $projectInfo = $this->Project_model->getProjectById($item['pro_id']); 
        $projectInfo = $projectInfo[0];
        $project = array(
                    'gained_amount' => $projectInfo['amount'],
                    'remain_amount' => 0,
                    'full_time'     => date("Y-m-d H:i:s",time()),
                );
        if($data['RespCode'] == '000')
        {
            $project['status'] = 10;//放款成功
        }else{
            $project['status'] = 15;//放款失败
        }

        //更新项目信息，状态及金额
        $res_project     = $this->Project_model->updateProject($projectInfo['id'],$project);
        $projectup       = isset($res_project)?1:'project update failed!';

        //更新融资人账户信息
        $hf_tenderee = $this->getUserBalance($item['tenderee_id']);
        $hf_user = $this->getUserBalance($item['uid']);

        //添加投标成功解冻金额记录个人流水 user_flow表
        $userFlowInfo   = array(
            "uid"           => $item['uid'],            # 用户ID
            "pro_id"        => $projectInfo['id'],          # 项目ID
            "pro_name"      => $projectInfo['pro_name'],    # 项目名称
            "type"          => 6,                           # 交易类型
            "amount"        => 0,           # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$hf_user['AvlBal']),     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s"), # 交易时间
            "comment"       => "投标[".$projectInfo['pro_num']."]，放款成功",       # 交易说明
        );
        $resultadd  = $this->User_model->addUserFlow($userFlowInfo);

        // //update user account
        // //tenderee
        // $tenderee_upd_data = array(
        //         'money' => $hf_tenderee['AcctBal'],//总资产
        //         'withdrawal_cash' => $hf_tenderee['AvlBal'],//可用余额
        //         'income' => 'income + '.$item[0]['invest_sum'],
        //     );
        // $tenderee_account_upd_res = $this->yzh_conn->update("yzh_tenderee_account",$tenderee_upd_data,array("uid",$settle_info[0]['tenderee_id']));

        // //user
        // $user_upd_data = array(
        //         'money' => $hf_user['AcctBal'],//总资产
        //         'withdrawal_cash' => $hf_user['AvlBal'],//可用余额
        //         'expend' => 'expend + '.$item[0]['invest_sum'],
        //         'used_money' => 'used_money + '.$item[0]['invest_sum'],//投资中金额
        //     );
        // $user_account_upd_res = $this->yzh_conn->update("yzh_user_account",$user_upd_data,array("uid",$settle_info[0]['uid']));

        if($projectup != 1){
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackLoans] update table project error!\r\n";
            $this->chinapnr->writeLogs($content);
        }
        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

    /**
    *手动触发放款接口(测试用，平时关闭)
    */
    public function loans()
    {exit;
        $input = $this->input->get();
        $pro_id = $input['pro_id'];

        $pro_info = $this->yzh_conn->where(array("id"=>$pro_id))->get("yzh_project")->row_array();
        $item_info = $this->yzh_conn
            ->where(array("pro_id"=>$pro_id,"status"=>1))
            ->get("yzh_project_user")->result_array();
        foreach($item_info as $k => $v){
            $out_user_info = $this->yzh_conn->where(array("uid"=>$v['uid']))->get("yzh_user")->result_array();
            $in_user_info = $this->yzh_conn->where(array("uid"=>$v['tenderee_id']))->get("yzh_user")->result_array();
            $ordernum = time().rand(10000,99999);
            
            $merPriv            = $v['id'];                                             # 用户ID
            $merCustId          = $this->config->config['hf_merCustId'];                                         # 商户客户号
            $proId              = substr($pro_info['pro_num'],2);
            $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
            $ordDate            = date("Ymd");                                          # 订单日期（20150303）
            $outCustId          = $out_user_info[0]['hf_usrCustId'];                    # 出账人客户号
            $transAmt           = sprintf("%.2f",$v['invest_sum']);                          # 交易金额
            $fee                = "0.00";
            $subOrdId           = $v['hforder_id'];
            $subOrdDate         = date("Ymd",strtotime($v['create_time']));
            $inCustId           = $in_user_info[0]['hf_usrCustId'];
            $divDetails         = '';//'[{"DivCustId":"6000060002609324","DivAcctId":"'.$v['tenderee_id'].'","DivAmt":"1.00"}]';
            $feeObjFlag         = '';
            $isDefault          = 'N';
            $isUnFreeze         = 'Y';
            $unFreezeOrdId      = time().rand(1000,9999);
            $freezeTrxId        = $v['freeze_trx_id'];
            $bgRetUrl           = $this->config->config['pc_domain']."/Hfcenter/apiBackLoans"; # 回调地址
            $reqExt             = '';
            // 满标调用汇付放款借口
            $Hfres = $this->chinapnr->loans($merCustId,$ordId,$ordDate,$outCustId,$transAmt,$fee,$subOrdId,$subOrdDate,$inCustId,$divDetails,$feeObjFlag,$isDefault,$isUnFreeze,$unFreezeOrdId,$freezeTrxId,$bgRetUrl,$merPriv,$reqExt);
        }
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
            $merCustId = $this->config->config['hf_merCustId'];
            $usrCustId = $userInfo[0]['hf_usrCustId'];
            $tenderPlanType = 'W';                  # P--部分授权 W--完全授权 
            $transAmt = '';                 # 投资金额(部分授权)
            $merPriv = '';
            $retUrl = $this->config->config['pc_domain']."/Hfcenter/apiBackautoInvestmentPlan";
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
            $merCustId = $this->config->config['hf_merCustId'];
            $usrCustId = $userInfo[0]['hf_usrCustId'];
            $merPriv = '';
            $retUrl = $this->config->config['pc_domain']."/Hfcenter/apiBackautoInvestmentPlanClose"; 
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
        $content = "\r\n".date('Y-m-d H:i:s')."\r\n[apiBackautoInvestmentPlan]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);
    }

    function apiBackautoInvestmentPlanClose(){
        $data = $_POST;
        $content = "\r\n".date('Y-m-d H:i:s')."\r\n[apiBackautoInvestmentPlanClose]:".json_encode($data)."\r\n";
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
            $merCustId          = $this->config->config['hf_merCustId'];                # 商户客户号
            $usrCustId          = $userInfo['hf_usrCustId'];                            # 用户客户号
            $ordId              = $ordernum;                                            # 订单ID唯一纯数字自行生成
            $ordDate            = date("Ymd");                                          # 订单日期（20150303）
            $transAmt           = sprintf("%.2f",$invest_sum);                          # 交易金额
            $maxTenderRate      = $this->config->config['hf_max_tender_rate'];          # 数最大投资手续费率 
            $BorrowerCustId     = $borrowUinfo['hf_usrCustId'];                         # 融资人汇付ID
            $borrowerDetails    = '[{"BorrowerCustId":"'.$BorrowerCustId.'","BorrowerAmt":"'.$transAmt.'","BorrowerRate":"'.$this->config->config['hf_borrower_rate'].'"}]';
            /*$BorrowerAmt        = $transAmt;                                            # 投资金额
            
            $BorrowerRate       = $this->config->config['hf_borrower_rate'];                                     # 借款手续费率 0.00<= BorrowerRate <=1.00
            $ProId              = $pid;                                                 # 项目ID
            $IsFreeze           = 'N';                                                  # 是否冻结 Y--冻结 N--不冻结 */
            $bgRetUrl           = $this->config->config['pc_domain']."/Hfcenter/apiBackautoInvestment";                # 回调地址

            $Hfres = $this->chinapnr->autoTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$retUrl = '',$bgRetUrl,$merPriv);      
        }else{
            $this->alert( "投资失败！" );exit;
        }
    }

    public function apiBackautoInvestment()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackautoInvestment]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
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

        if( ($projectup==1)&&($projectUserup==1)&&($userAccountup==1)&&($userFlowadd==1) ){
            $this->yzh_conn->trans_commit();                # 完全执行成功，提交数据更新
        }else{
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackDoInvestment] error ! projectup:".$projectup."|projectUserup:".$projectUserup."|userAccountup:".$userAccountup."|userFlowadd:".$userFlowadd."\r\n";
            $this->chinapnr->writeLogs($content);
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            exit;
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
        $merCustId = $this->config->config['hf_merCustId'];
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
        $bgRetUrl = $this->config->config['pc_domain']."/Hfcenter/apiBackAddBidInfo";
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
        echo "RECV_ORD_ID_".$_POST['ProId'];exit;
    }

######################################################################################################

    //债权转让
    public function hfCredit()
    {
        $input = $this->input->post();
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $userInfo = (array)$userInfo[0];
        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        /**
        credit_record表中没有记录，说明是由投标项目转让的债权成交，bidOrdId为item中的hforderid
        有记录，则表示是由债权转让的纪录再次转让的债权成交，bidOrdId为当前credit的hforderid
        */
        $item_info = $this->yzh_conn->where("hforder_id",$input['bidOrdId'])->get("yzh_project_user")->row_array();
        $pro_info = $this->yzh_conn->where("id",$item_info['pro_id'])->get("yzh_project")->row_array();
        $credit_record = $this->yzh_conn->where(array("item_id"=>$item_info['id'],"credit_id"=>$input['credit_id']))->order_by("deal_time","DESC")->get("yzh_credit_record")->result_array();
        if(empty($credit_record)){
            $bidOrdId = $item_info['hforder_id'];
        }else{
            $bidOrdId = $credit_record[0]['hf_order_id'];
        }
        $bidOrdDate = date("Ymd",substr($bidOrdId, 0, 10));

        /**
        *计算成交时候的折价转让率，和成交金额
        */
        $discount = $this->discountRatio($pro_info['cycle'],$pro_info['full_time']);
        $real_amount = $item_info['invest_sum'] * round($discount,4);

        $merCustId      = $this->config->config['hf_merCustId'];                     # 商户客户号
        $sellCustId     = $input['hf_usrCustId'];           # 转让人客户号
        $creditAmt      = sprintf("%.2f",$item_info['invest_sum']);                              # 转让金额
        $creditDealAmt  = sprintf("%.2f",$real_amount);                                # 承接金额

        $bidDetails = '{"BidDetails":[{"BidOrdId":"'.$bidOrdId.'","BidOrdDate":"'.$bidOrdDate.'","BidCreditAmt":"'.sprintf("%.2f",$input['credit_amount']).'","BorrowerDetails":[{"BorrowerCustId":"'.$input['jk_hf_usrCustId'].'","BorrowerCreditAmt":"'.sprintf("%.2f",$input['credit_amount']).'","PrinAmt":"0.00","ProId":"'.substr($input['pro_num'],2).'"}]}]}'; 
        
        $fee            = "0.00";                           # 放款或扣款的手续费
        $divDetails     = '';
        $buyCustId      = $userInfo['hf_usrCustId'];# 承接人客户号
        $ordId          = $ordernum;                        # 订单ID唯一纯数字自行生成
        $ordDate        = date("Ymd");                      # 订单日期（20150303）
        $bgRetUrl       = $this->config->config['pc_domain']."/Hfcenter/apiBackPCredit";     # 回调地址
        $merPrivData = array(
            'pro_id' => $input['pro_id'],
            'hforder_id' => $input['bidOrdId'],//item表中的orderid
            'credit_id'=>$input['credit_id'],
            'credit_to_uid' => $uid,
            'discount' => $discount,
            );
        $merPriv        = json_encode($merPrivData);

        $res = $this->chinapnr->creditAssign($merCustId,$sellCustId,$creditAmt,$creditDealAmt,$bidDetails,$fee,$divDetails,$buyCustId,$ordId,$ordDate,$retUrl = '',$bgRetUrl,$merPriv,$reqExt = '');
    }

    public function apiBackPCredit()
    {
        $data       = $this->input->post();
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackPCredit]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //防止重复callback
        $credit_record = $this->yzh_conn->where("hf_order_id",$data['OrdId'])->get("yzh_credit_record")->result_array();
        if(!empty($credit_record)){
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackPCredit]: error Repeat Request!\r\n";
            $this->chinapnr->writeLogs($content);exit;
        }

        //事物回滚开启
        $this->yzh_conn->trans_begin();

        $merPriv = json_decode($data['MerPriv'],true);
        //更新project_user表数据
        $upd_data = array('credit_status' => 10, 'credit_to_uid' => $merPriv['credit_to_uid']);
        $res_item = $this->yzh_conn->where("hforder_id",$merPriv['hforder_id'])->update('yzh_project_user',$upd_data);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n更新project_user表数据:".$this->yzh_conn->last_query()."\r\n");

        //更新credit表数据
        $item = $this->yzh_conn->from("yzh_project_user")->where("hforder_id",$merPriv['hforder_id'])->get()->result_array();
        $pro_info = $this->yzh_conn->where("id",$item[0]['pro_id'])->get("yzh_project")->result_array();
        $credit_data = array(
                'buyer_uid' => $merPriv['credit_to_uid'],
                'discount' => $merPriv['discount'],
                'real_amount' => $data['CreditDealAmt'],
                'status' => 10,
                'deal_time' => date("Y-m-d H:i:s"),
                'hf_bid_ordid' => $data['OrdId'],
            );
        $res_credit = $this->yzh_conn->where("id",$merPriv['credit_id'])->update("yzh_credit",$credit_data);

        //新增credit_record表数据
        $credit = $this->yzh_conn->from("yzh_credit")->where("id",$merPriv['credit_id'])->get()->result_array();
        $record_data = array(
                'pro_id' => $merPriv['pro_id'],
                'item_id' => $credit[0]['item_id'],
                'credit_id' => $merPriv['credit_id'],
                'from_uid' => $credit[0]['creditor_id'],
                'to_uid' => $merPriv['credit_to_uid'],
                'from_amount' => $credit[0]['credit_amount'],
                'to_amount' => $data['CreditDealAmt'],
                'discount' => $merPriv['discount'],
                'deal_time' => date("Y-m-d H:i:s"),
                'hf_order_id' => $data['OrdId'],
            );
        $res_record = $this->yzh_conn->insert("yzh_credit_record",$record_data);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增credit_record表数据:".$this->yzh_conn->last_query()."\r\n");

############################################ 出让人
        //更新个人账户 user_account表
        $seller_avlbal = $this->getUserBalance($credit[0]['creditor_id']);
        $userInfoFrom = $this->yzh_conn->where("uid",$credit[0]['creditor_id'])->get("yzh_user")->row_array();
        
        //添加债权出让人记录 user_flow表
        $cardInfoout   = array(
            "uid"           => $credit[0]['creditor_id'],            # 用户ID
            "type"          => 9,                          # 交易类型（债权转让）
            "amount"        => $data['CreditDealAmt'],      # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$seller_avlbal['AvlBal']),     # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s"), # 交易时间
            "comment"       => "成功出让债权(原始项目:".$pro_info[0]['pro_num'].")",       # 交易说明
        );
        $resultaddout  = $this->User_model->addUserFlow($cardInfoout);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增债权出让人user_flow表数据:".$this->yzh_conn->last_query()."\r\n");

############################################ 购买人
        //更新个人账户 user_account表
        $buyer_avlbal = $this->getUserBalance($merPriv['credit_to_uid']);
        $userInfoTo = $this->yzh_conn->where("uid",$merPriv['credit_to_uid'])->get("yzh_user")->row_array();

        //添加债权购买记录 user_flow表
        $cardInfoin   = array(
            "uid"           => $merPriv['credit_to_uid'],            # 用户ID
            "type"          => 10,                           # 交易类型（购买债权）
            "amount"        => $data['CreditDealAmt'],      # 交易金额
            "remaining_amount"  => (float)str_replace(",","",$buyer_avlbal['AvlBal']),  # 账户余额
            "status"        => 5,                           # 交易状态
            "create_time"   => date("Y-m-d H:i:s"), # 交易时间
            "comment"       => "成功购买债权(原始项目:".$pro_info[0]['pro_num'].")",        # 交易说明
        );
        $resultaddin  = $this->User_model->addUserFlow($cardInfoin);
        $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n新增债权购买人user_flow表数据:".$this->yzh_conn->last_query()."\r\n");

        if($res_item && $res_credit && $res_record && $resultaddout && $resultaddin){
            $this->yzh_conn->trans_commit();
            # 债权转让成功 发送短信息给出让人开始
            $mobile     = $userInfoFrom['phone'];
            $send_title = $this->config->config['send_msg_title'];
            $send_kftel = $this->config->config['send_msg_kftel'];
            $sys_msg    = "尊敬的用户,您在云智慧金融的投资".$data['CreditDealAmt']."元已成功进行债权转让，详情请登录云智慧官网查询。如有疑问，请致电".$send_kftel."。";
            $send_text  = $send_title.$sys_msg;
            @$this->sed_tpl_msg($mobile,$send_text);
            @$this->sed_sys_msg($credit[0]['creditor_id'],"债权转让成功",$sys_msg);
            # 债权转让成功 发送短信息给出让人结束

            # 债权转让成功 发送短信息给购买人开始
            $mobile     = $userInfoTo['phone'];
            $send_title = $this->config->config['send_msg_title'];
            $send_kftel = $this->config->config['send_msg_kftel'];
            $sys_msg    = "尊敬的用户,您在云智慧金融投资的债权转让：".$data['CreditDealAmt']."元已成交，详情请登录云智慧官网查询。如有疑问，请致电".$send_kftel."。";
            $send_text  = $send_title.$sys_msg;
            @$this->sed_tpl_msg($mobile,$send_text);
            @$this->sed_sys_msg($merPriv['credit_to_uid'],"债权转让成功",$sys_msg);
            # 债权转让成功 发送短信息给购买人结束
        }else{
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackPCredit] error ! res_item:".$res_item."|res_credit:".$res_credit."|res_record:".$res_record."|resultaddout:".$resultaddout."|resultaddin".$resultaddin."\r\n";
            $this->chinapnr->writeLogs($content);
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            exit;
        }
        
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

        $merCustId = $this->config->config['hf_merCustId'];                                      # 商户客户号
        $usrCustId = $this->config->config['hf_merCustId'];                                      # 用户客户号
        $ordId     = $ordernum;                                         # 订单ID唯一纯数字自行生成
        $ordDate   = date("Ymd");                                       # 订单日期（20150303）
        $transAmt  = "10000.00";//$amount;                                          # 交易金额
        $bgRetUrl  = $this->config->config['pc_domain']."/Hfcenter/apiBackBizRecharge";              # 回调地址

        $res = $this->chinapnr->posWhSave($merCustId,$usrCustId,$openAcctId,$transAmt,$ordId,$ordDate,$checkDate = '',$retUrl = '',$bgRetUrl,$merPriv = '');
        //posWhSave
    }

    public function apiBackBizRecharge()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackPCredit]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    //自动扣款
    public function autoRepayment()
    {
        $url = urlencode("/Hfcenter/userInformation");
        $this->islogin($url);
        $uid = $this->getCookie('uid_cookie');
        $userInfo = $this->User_model->getUser( array("uid" => $uid) );
        $data['userInfo'] = (array)$userInfo[0];

        $rand = $this->m->getrandomstr(5,'num');
        $ordernum = time().$rand;

        $merCustId = $this->config->config['hf_merCustId'];                                      # 商户客户号
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
        $bgRetUrl  = $this->config->config['pc_domain']."/Hfcenter/apiBackAutoRepayment";              # 回调地址

        $res = $this->chinapnr->repayment($merCustId,$ordId,$ordDate,$outCustId,$subOrdId,$subOrdDate,$outAcctId = '',$transAmt,$fee,$inCustId,$inAcctId = '',$divDetails = '',$feeObjFlag,$bgRetUrl,$merPriv = '',$reqExt = '');
        //posWhSave
    }

    public function apiBackAutoRepayment()
    {
        $data       = $_POST;
        $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]:".json_encode($data)."\r\n";
        $this->chinapnr->writeLogs($content);

        if(empty($data) || $data['RespCode']!='000'){
            exit;
        }

        //开启事务处理
        $this->yzh_conn->trans_begin();

        $settle_id = $data['MerPriv'];
        $settle_info = $this->yzh_conn->where("id",$settle_id)->get("yzh_item_settlement_".date("Ym"))->result_array();
        if(empty($settle_info)){
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]no settle info");
            $this->yzh_conn->trans_rollback();exit;
        }
        $item_info = $this->yzh_conn->where("id",$settle_info[0]['item_id'])->get("yzh_project_user")->result_array();
        if(empty($item_info)){
            $this->chinapnr->writeLogs("\r\n".date("Y-m-d H:i:s")."\r\n[apiBackAutoRepayment]no item info");
            $this->yzh_conn->trans_rollback();exit;
        }
        $hf_tenderee = $this->getUserBalance($settle_info[0]['tenderee_id']);
        $hf_user = $this->getUserBalance($settle_info[0]['uid']);

        //update table settlement
        $settle_upd_data = array(
                'is_finish' => 1,
                'pay_time' => date("Y-m-d H:i:s"),
            );
        $res_settle = $this->yzh_conn->update("yzh_item_settlement_".date("Ym"),$settle_upd_data,array("id"=>$settle_id));

        //update project_user
        $item_upd_data = array(
                'last_settle_time' => date("Y-m-d H:i:s"),
            );
        $res_item = $this->yzh_conn->update("yzh_project_user",$item_upd_data,array("id",$settle_info[0]['item_id']));

        //update project
        $pro_upd_data = array(
                'status' => 80,// 还款完成
            );
        $res_project = $this->yzh_conn->update("yzh_project",$pro_upd_data,array("id",$settle_info[0]['pro_id']));

        // //update user account
        // //tenderee
        // $tenderee_upd_data = array(
        //         'money' => $hf_tenderee['AcctBal'],//总资产
        //         'withdrawal_cash' => $hf_tenderee['AvlBal'],//可用余额
        //         'expend' => 'expend + '.$settle_info[0]['settlement_pay'],
        //     );
        // $tenderee_account_upd_res = $this->yzh_conn->update("yzh_tenderee_account",$tenderee_upd_data,array("uid",$settle_info[0]['tenderee_id']));

        // //user
        // $user_upd_data = array(
        //         'money' => $hf_user['AcctBal'],//总资产
        //         'withdrawal_cash' => $hf_user['AvlBal'],//可用余额
        //         'income' => 'income + '.$settle_info[0]['settlement_gain'],
        //     );
        // if($settle_info[0]['settlement_gain'] > $item_info[0]['invest_sum']){//项目最后一天
        //     $user_upd_data['used_money'] = 'used_money - '.$item_info[0]['invest_sum'];//投资中金额
        //     $user_upd_data['gain_total'] = $settle_info[0]['settlement_gain']-$item_info[0]['invest_sum'];
        // }else{
        //     $user_upd_data['gain_total'] = $settle_info[0]['settlement_gain'];
        // }
        // $user_account_upd_res = $this->yzh_conn->update("yzh_user_account",$user_upd_data,array("uid",$settle_info[0]['uid']));

        if(($res_settle == 1)&&($res_item == 1)&&($res_project== 1)){
            # 投资成功 发送短信息开始
            // $userInfoin = $this->User_model->getUser( array("hf_usrCustId" => $data['UsrCustId']) );
            // $userInfoin = (array)$userInfoin[0];
            // $mobile     = $userInfoin['phone'];
            // $send_title = $this->config->config['send_msg_title'];
            // $send_kftel = $this->config->config['send_msg_kftel'];
            // $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
            // $send_text  = $send_title."您已于".$cur_date."成功投资云智慧金融平台".$data['TransAmt']."元。如有疑问，请致电".$send_kftel."。";
            // @$this->sed_tpl_msg($mobile,$send_text);
            # 发送短信息结束
            $this->yzh_conn->trans_commit();                # 完全执行成功，提交数据更新
        }else{
            $content = "\r\n".date("Y-m-d H:i:s")."\r\n[apiBackDoInvestment] error ! projectUserup:".$projectUserup."|userAccountup:".$userAccountup."|userFlowadd:".$userFlowadd."\r\n";       
            $this->chinapnr->writeLogs($content);
            $this->yzh_conn->trans_rollback();              # 更新数据存在失败，回滚事物
            exit;
        }

        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

######################################################################################################

    //用户登录(测试环境密码yzh_123456/正是环境用户密码在注册成功后会短信发送给用户)
    public function userLogin_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];         # 商户客户号
        $usrCustId = "6000060002609413";   # 保存在用户表中的hf_usrCustId字段，用户客户号
        $res = $this->chinapnr->userLogin($merCustId, $usrCustId);
        print_R($res);
    }

    //余额查询
    public function getUserBalance($uid){
        $user_info = $this->yzh_conn->where("uid",$uid)->get("yzh_user")->result_array();
        $merCustId = $this->config->config['hf_merCustId'];
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

    //商户子账户查询
    public function queryAccts_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $res = $this->chinapnr->queryAccts($merCustId);
        print_R(json_encode($res));
    }

    //交易状态查询
    public function queryTransStat_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $ordId = "1460092698";
        $ordDate = "20160408";
        $queryTransType = strtoupper("loans");#必须要大写
        $res = $this->chinapnr->queryTransStat($merCustId,$ordId,$ordDate,$queryTransType);
        print_R(json_encode($res));
    }

    //商户扣款对账
    public function trfReconciliation_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $beginDate = "20151212";
        $endDate = "20160223";
        $pageNum = "1";
        $pageSize = "1000";
        $res = $this->chinapnr->trfReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize);
        print_R(json_encode($res));
    }

    //放还款对账
    public function reconciliation_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $beginDate = "20151212";
        $endDate = "20160223";
        $pageNum = "1";
        $pageSize = "1000";
        $queryTransType = strtoupper("loans");#必须要大写
        $res = $this->chinapnr->reconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize,$queryTransType);
        print_R(json_encode($res));
    }

    //取现对账
    public function cashReconciliation_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $beginDate = "20151212";
        $endDate = "20160223";
        $pageNum = "1";
        $pageSize = "1000";
        $res = $this->chinapnr->cashReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize);
        print_R(json_encode($res));
    }

    //充值对账
    public function saveReconciliation_test()
    {
        $merCustId = $this->config->config['hf_merCustId'];
        $beginDate = "20151212";
        $endDate = "20160223";
        $pageNum = "1";
        $pageSize = "1000";
        $res = $this->chinapnr->saveReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize);
        print_R(json_encode($res));
    }

    public function usrAcctPay_test()
    {
        $ordId = time().rand(10000,99999);
        $usrCustId = "6000060002703506";
        $merCustId = $this->config->config['hf_merCustId'];
        $transAmt = "1.00";
        $inAcctId = "MDT000001";
        $inAcctType = "MERDT";
        $retUrl = '';
        $bgRetUrl = $this->config->config['pc_domain']."/Hfcenter/apibackUsrAcctPay";
        $merPriv = '';
        $this->chinapnr->usrAcctPay($ordId,$usrCustId,$merCustId,$transAmt,$inAcctId,$inAcctType,$retUrl,$bgRetUrl,$merPriv);
    }

    public function apibackUsrAcctPay()
    {
        $data = $this->input->post();
        $this->chinapnr->writeLogs(date("Y-m-d H:i:s")."\r\n[usrAcctPay]: ".json_encode($data)."\r\n");
    }

    public function test()
    {
        $uid = $this->input->get("uid");
        $res = $this->getUserBalance($uid);
        print_R(json_encode($res));
    }

}
