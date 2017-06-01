<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PreThreedays extends Base_Controller
{

    public function __construct() {
        parent::__construct();
        if(!$this->input->is_cli_request()){
            echo "please use terminal request this API!\r\n";exit;
        }
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        $this->load->helper("pro_date");
        // $cli_get_time = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : '' ;
        // $this->now = !empty($cli_get_time) ? date("Y-m-d H:i:s",strtotime($cli_get_time)) : date("Y-m-d H:i:s") ;
        $this->now = date("Y-m-d H:i:s");
    }

    public function getAllTenderee()
    {
        $res_tenderee = $this->yzh_conn->where(array("type"=>7,"id_succ"=>1))->get("yzh_user")->result_array();
        if(empty($res_tenderee)){
            echo "no tenderee exists!";exit;
        }
        foreach($res_tenderee as $kt => $vt){
            var_dump($vt['uid']);

            $where = array(
                'tenderee_uid' => $vt['uid'],
                'status' => 10,
                );
            $hisPro = $this->yzh_conn->where($where)->get("yzh_project")->result_array();
            //定义tenderee应还款金额
            $repayAmount = 0;
            if(!empty($hisPro)){
                foreach ($hisPro as $key => $value) {
                    //今天是项目结束倒数第三天
                    if(date("Y-m-d",(strtotime($value['full_time']) + ($value['cycle']*30-4)*24*3600)) == date("Y-m-d",strtotime($this->now))){
                        $pro_date = repayListDate(array('start_time'=>$value['full_time'],'cycle'=>$value['cycle'],'settle_day'=>15));
                        $interval = $pro_date[sizeof($pro_date)-1]['calcu_end_timestamp'] - $pro_date[sizeof($pro_date)-1]['calcu_start_timestamp'];
                        //最后一期的利息
                        $interest = $value['amount'] * $value['year_rate_in']/100 * $interval/3600/24/360;
                        $repayAmount += $interest + $value['amount'];
                    }
                }
                //余额查询
                $merCustId = $this->config->config['merCustId'];
                $usrCustId = $vt['hf_usrCustId'];
                $res = $this->chinapnr->queryBalanceBg($merCustId,$usrCustId);
                $avlBal = (float)str_replace(",","",$res['AvlBal']);

                //发短信提示
                $tenderee_info = $this->yzh_conn->where(array("uid" => $vt['uid']))->get("yzh_user")->result_array();
                $tenderee_info = (array)$tenderee_info[0];
                $mobile     = $tenderee_info['phone'];
                $send_title = $this->config->config['send_msg_title'];
                $send_kftel = $this->config->config['send_msg_kftel'];
                $end_date = date("Y-m-d",(strtotime($value['full_time']) + ($value['cycle']*30-1)*24*3600));
                echo $send_text  = "{$send_title}尊敬的借款用户，您在云智慧平台发布的借款于{$end_date}（三日后）需要归还利息和本金共计".round($repayAmount,2).",现账户余额为".round($avlBal,2)."，请及时充值以免逾期对您造成经济和信用损失。如有疑问，请致电{$send_kftel}。";
                @$this->mobileSMS->tpl_send_sms($mobile,$send_text);
            }
        }
    }

}
