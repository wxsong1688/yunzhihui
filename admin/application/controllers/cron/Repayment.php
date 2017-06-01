<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repayment extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        if(!$this->input->is_cli_request()){
            echo "please use terminal request this API!\r\n";exit;
        }
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        // $cli_get_time = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : '' ;
        // $this->now = !empty($cli_get_time) ? date("Y-m-d H:i:s",strtotime($cli_get_time)) : date("Y-m-d H:i:s") ;
        $this->now = date("Y-m-d H:i:s");
    }

    public function getAllSettlement()
    {
        $res_tenderee = $this->yzh_conn->where(array("type"=>7,"id_succ"=>1))->get("yzh_user")->result_array();
        if(empty($res_tenderee)){
            echo "no tenderee exists!";exit;
        }
        foreach($res_tenderee as $kt => $vt){
            var_dump($vt['uid']);
            //search settlement list
            $where = array(
                "tenderee_id" => $vt['uid'],
                "is_finish" => 2,
                "from_unixtime(unix_timestamp(create_time),'%Y-%m-%d')" => date("Y-m-d",strtotime($this->now)),
            );
            $type = 1;//1:以每条还款记录为单位,2:以每个项目及用户为单位
            if($type == 1){
                $res_settlement = $this->yzh_conn
                    ->where($where)
                    ->get("yzh_item_settlement_".date("Ym",strtotime($this->now)))->result_array();
            }else{
                $res_settlement = $this->yzh_conn
                    ->where($where)
                    ->group_by("pro_id,uid")
                    ->get("yzh_item_settlement_".date("Ym",strtotime($this->now)))->result_array();
            }
            if(empty($res_settlement)){//没有需还款记录
                echo "no settlement for ".$vt['uid']."!\r\n";continue;
            }
            //get total amount
            $res_total = $this->yzh_conn
                ->where($where)->select_sum("settlement_pay")
                ->get("yzh_item_settlement_".date("Ym",strtotime($this->now)))->result_array();
            
            //余额查询
            $merCustId = $this->config->config['merCustId'];
            $usrCustId = $vt['hf_usrCustId'];
            $res = $this->chinapnr->queryBalanceBg($merCustId,$usrCustId);

            /**
            查询余额是否充足
            不足需要短信邮件提醒
            */
            if((float)str_replace(",","",$res['AvlBal']) <= $res_total[0]['settlement_pay']){
                //余额不足，短信邮件提醒
                $notice_data = array(
                    "tenderee_id" => $vt['uid'],
                    "repay_amount" => $res_total[0]['settlement_pay'],
                    "avlbal" => (float)str_replace(",","",$res['AvlBal'])
                    );
                $this->notice($notice_data);
            }else{
                //余额充足，处理返款逻辑
                $this->repayment($res_settlement);
            }
        }
        
    }

    //自动还款（手续费用来还商户）
    public function repayment($data)
    {
        foreach($data as $k => $v){
            $out_user_info = $this->yzh_conn->where("uid",$v['tenderee_id'])->get("yzh_user")->row_array();
            $in_user_info = $this->yzh_conn->where("uid",$v['uid'])->get("yzh_user")->row_array();
            $pro_info = $this->yzh_conn->where("id",$v['pro_id'])->get("yzh_project")->row_array();
            $item_info = $this->yzh_conn->where("id",$v['item_id'])->get("yzh_project_user")->row_array();
            //查询收款用户的余额
            $merCustId = $this->chinapnr->merCustId;
            $usrCustId = !empty($in_user_info['hf_usrCustId']) ? $in_user_info['hf_usrCustId'] : "" ;
            $res = $this->chinapnr->queryBalanceBg($merCustId,$usrCustId);
            $in_user_balance = (float)str_replace(",","",$res['AvlBal']);

            $credit_record_info = $this->yzh_conn
                ->where(array("pro_id"=>$item_info['pro_id'],"item_id"=>$item_info['id'],"to_uid"=>$v['uid']))
                ->get("yzh_credit_record")->result_array();
            if(empty($credit_record_info)){
                $subOrdId = $item_info['hforder_id'];
            }else{
                $subOrdId = $credit_record_info[0]['hf_order_id'];
            }
            $subOrdDate = date("Ymd",substr($subOrdId, 0, 10));

            $ordId = time().rand(10000,99999);
            $ordDate = date("Ymd");
            $outCustId = $out_user_info['hf_usrCustId'];
            $subOrdId = $subOrdId;
            $subOrdDate = $subOrdDate;
            $outAcctId = '';
            $transAmt = number_format($v['settlement_gain'],2,".","");# 入帐金额是投资人应收金额
            $fee = number_format(($v['settlement_pay']-$v['settlement_gain']),2,".","");
            $inCustId = $in_user_info['hf_usrCustId'];# 入账人是客户
            $inAcctId = '';
            if($fee == 0){
                $divDetails = '';
                $feeObjFlag = '';
            }else{
                $divDetails = '[{"DivCustId":"'.$this->chinapnr->merCustId.'","DivAcctId":"MDT000001","DivAmt":"'.$fee.'"}]';
                $feeObjFlag = 'O';
            }
            $bgRetUrl = $this->config->config['base_url']."/Hfcenter/apiBackAutoRepayment";
            $merPrivData = array(
                'settle_id' => $v['id'],
                'in_user_balance' => $in_user_balance,
                );
            $merPriv = json_encode($merPrivData);
            $reqExt = '{"ProId":"'.substr($pro_info['pro_num'],2).'"}';

            if($transAmt == 0 && $fee == 0){
                continue;
            }
            $res = $this->chinapnr->repayment($merCustId,$ordId,$ordDate,$outCustId,$subOrdId,$subOrdDate,$outAcctId,$transAmt,$fee,$inCustId,$inAcctId,$divDetails,$feeObjFlag,$bgRetUrl,$merPriv,$reqExt);

        }

    }

    //暂时不需要
    public function apiBackAutoRepayment()
    {
        $data = $_POST;
        echo "RECV_ORD_ID_".$data['OrdId'];exit;
    }

    public function notice($data)
    {
        echo "no enough money!";
        if(date("H",strtotime($this->now)) > 22 || date("H",strtotime($this->now)) < 6){
            echo "time is too early or late!";
        }else{
            # 还款给用户 借款人余额不足 发送短信息提示
            $tenderee_info = $this->yzh_conn->where(array("uid" => $data['tenderee_id']))->get("yzh_user")->result_array();
            $tenderee_info = (array)$tenderee_info[0];
            $mobile     = $tenderee_info['phone'];
            $send_title = $this->config->config['send_msg_title'];
            $send_kftel = $this->config->config['send_msg_kftel'];
            $cur_date   = date("Y")."年".date("m")."月".date("d")."日 ".date("H")."：".date("i");
            $send_text  = "{$send_title}尊敬的借款用户，您在云智慧平台发布的借款于{$cur_date}（今天）需要归还利息和本金共计".round($data['repay_amount'],2).",现账户余额为".round($data['avlbal'],2)."，资金不足无法完成还款,请及时充值以免逾期对您造成经济和信用损失。如有疑问，请致电{$send_kftel}。";
            @$this->mobileSMS->tpl_send_sms($mobile,$send_text);
            # 还款给用户 借款人余额不足 发送短信息提示
            /**********************************************/
            # 提醒admin
            $send_dph_text = "请注意，今天有借款用户{$mobile}项目到期，需归还本金和利息共计".round($data['repay_amount'],2)."，账户余额为".round($data['avlbal'],2).",请提醒用户注意";
            @$this->mobileSMS->tpl_send_sms("15822549636",$send_dph_text);
            # 提醒admin
        }
    }


}
