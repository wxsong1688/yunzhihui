<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }
   

    //用户注册
    public function addUser($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_user', $data);
        return $result;
    }

    public function getUser($array)
    {
        $query = $this->yzh_conn->get_where('yzh_user', $array);
        $result = $query->result();
        return $result;
    }

    //修改用户信息
    public function updateUser($data,$where)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_user', $data, $where);
        return $result;
    }

    //创建用户账户
    public function addUserAccount($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_user_account', $data);
        return $result;
    }

    //创建融资人账户
    public function addTendereeAccount($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_tenderee_account', $data);
        return $result;
    }

    public function getUserInfo($uid)
    {
        $result = $this->yzh_conn->select('u.username, a.withdrawal_cash')
        ->from('yzh_user as u')
        ->join('yzh_user_account as a', 'u.uid = a.uid')
        ->where("u.uid = ".$uid)
        ->get()->result_array();
        return $result;
    }

    //创建用户绑定银行卡信息
    function addUserBank($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_user_bank', $data);
        return $result;
    }

    //获取用户绑定银行卡信息
    public function getUserBank($data)
    {
        $query  = $this->yzh_conn->get_where('yzh_user_bank', $data);
        $result = $query->result();
        return $result;
    }

    //获取用户等级配置
    public function getLevelConfig($data){
        $query  = $this->yzh_conn->get_where('yzh_user_level', $data);
        $result = $query->result();
        return $result;
    }

    //删除绑定银行卡
    public function delUserBank($data)
    {
        $result = $this->yzh_conn->delete('yzh_user_bank', $data);
        return $result;
    }

    //创建流水记录
    public function addUserFlow($data)
    {
        $data   = (object)$data;
        $year   = date("Y");
        $result = $this->yzh_conn->insert('yzh_user_flow_'.$year, $data);
        return $result;
    }

    public function getUserFlow($array)
    {
        $year   = date("Y");
        $query = $this->yzh_conn->get_where('yzh_user_flow_'.$year, $array);
        $result = $query->result_array();
        return $result;
    }

    public function getUserAccount($limit,$array)
    {
        $query = $this->yzh_conn->get_where('yzh_user_account', $array);
        $result = $query->result_array();
        return $result;
    }

    public function updateUserAccount($uid,$data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_user_account', $data, array('uid'=>$uid));
        return $result;
    }

    /*
     * 计算投资人单个项目待收本息
     */
    public function readyGainPro($uid,$pro_id)
    {
        $pro_info = $this->yzh_conn->from("yzh_project")->where("id",$pro_id)->get()->result_array();
        $item = $this->yzh_conn->from("yzh_project_user")->where("pro_id",$pro_id)->where("uid",$uid)->get()->result_array();
        if($pro_info[0]['status'] == 5){//未满标
            $readyGainInterest = 0;//$pro_info[0]['cycle'] * 30 * ($pro_info[0]['year_rate_out']/360) * $v['invest_sum'];
        }elseif($pro_info[0]['status'] == 10){//满标，项目进行中
            $readyGainInterest = 
                ($pro_info[0]['year_rate_out']/360/100) * $item[0]['invest_sum'] *
                (($pro_info[0]['cycle'] * 30)
                -
                (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($pro_info[0]['full_time']))))/3600/24)
                ;
        }
        $readyGain = (float)$item[0]['invest_sum']+$readyGainInterest;
        return $readyGain;
    }

    public function investing_sum($uid)
    {
        $pro_sum = $this->yzh_conn->where(array("uid"=>$uid,"status"=>1))->select_sum("invest_sum")->get("yzh_project_user")->result_array();
        $credit_sum = $this->yzh_conn->where(array("buyer_uid"=>$uid,"status"=>10))->select_sum("real_amount")->get("yzh_credit")->result_array();
        (float)$total_sum = (float)$pro_sum + (float)$credit_sum;
        return $total_sum;
    }

}

?>
