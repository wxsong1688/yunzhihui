<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function swhere($where=null){
        if(is_array($where) && !empty($where))
        {
            $nwhere = " where";
            foreach ($where as $key => $value) 
            {
                $nwhere .= " $key=$value and ";
            }
            $nwhere .= '1=1';
        }
        return $nwhere;
    }

    public function getProjects($where=null,$limit=null)
    {
        if( !empty($where) && is_array($where) ){ $where = $this->swhere($where); }else{ $where = "where 1=1"; }
        if( !empty($limit) && is_int($limit) ){ $limit = "limit 0,$limit"; }else{ $limit = ""; }        
        $sql = "select * from yzh_project $where order by status ASC,full_time DESC,create_time DESC,year_rate_out DESC $limit";
        $result = $this->yzh_conn->query($sql)->result_array();        
        return $result;
    }

    public function getProject($data)
    {
        $query = $this->yzh_conn->get_where('yzh_project', $data);
        $result = $query->result_array();
        return $result;
    }

    

    public function getProjectById($id)
    {
        $sql = 'select * from yzh_project where id='.$id;
        $result = $this->yzh_conn->query($sql)->result_array();
        return $result;
    }

    public function getSysconfig($data)
    {
        $sql = 'select value from yzh_sys_config where name="'.$data.'"';
        $result = $this->yzh_conn->query($sql)->result_array();
        return $result;
    }

	public function getMyProject($data)
    {
        $query = $this->yzh_conn->get_where('yzh_project_user', $data);
        $result = $query->result_array();
        return $result;
    }

    public function getUserProjectById($data)
    {
        $query = $this->yzh_conn->order_by("id","DESC")->get_where('yzh_project_user', $data);
        $result = $query->result_array();
        return $result;
    }

    public function countMypro($data){
        $query = $this->yzh_conn->get_where('yzh_project_user', $data);
        $result = $query->num_rows();
        return $result;        
    }
    

    public function countProject($data){
        $query = $this->yzh_conn->get_where('yzh_project', $data);
        $result = $query->num_rows();
        return $result;        
    }

    public function countMyCredit($data){
        $query = $this->yzh_conn->get_where('yzh_credit', $data);
        $result = $query->num_rows();
        return $result;        
    }

    public function countCredit(){
        $query = $this->yzh_conn->where("status != 10")->get('yzh_credit');
        $result = $query->num_rows();
        return $result;        
    }

    public function getProjectCredit($data)
    {
        $query = $this->yzh_conn->get_where('yzh_credit', $data);
        $result = $query->result_array();
        return $result;
    }

    //联合查询-根据投资类型获取【债权转让】相关信息
    public function getProjectCreditByTyep($type)
    {
        $result = $this->yzh_conn->select('c.id, c.pro_id, c.status, u.realname, c.credit_amount, c.discount, c.real_amount, p.year_rate_out, p.cycle, p.full_time')
        ->from('yzh_credit c')
        ->join('yzh_project p', 'c.pro_id = p.id')
        // ->join('yzh_project_user as i', 'c.pro_id = i.pro_id')
        ->join('yzh_user u', 'u.uid = c.creditor_id')
        ->where("p.type in ($type) ")
        ->order_by("id","DESC")
        ->limit(10)
        ->get()->result_array();
        return $result;
    }

    

    public function updateProject($id,$data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_project', $data, array('id'=>$id));
        return $result;
    }

    public function updateProjectUser($id,$data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_project_user', $data, array('id'=>$id));
        return $result;
    }

    public function addUserProject($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_project_user', $data);
        return $result;
    }

    public function addProjectCredit($data)
    {
        $creditInfo = $this->yzh_conn->from("yzh_credit")->where("item_id",$data['item_id'])->get()->result_array();
        if(!isset($creditInfo[0])){
            $result = $this->yzh_conn->insert('yzh_credit', $data);
        }else{
            $data['buyer_uid'] = 0;
            $result = $this->yzh_conn->update('yzh_credit', $data, array('item_id'=>$data['item_id']));
        }
        return $result;
    }

    //资金流水
    public function getFlows($where=null,$limit=null)
    {
        $tableName = "yzh_user_flow_".date("Y",time());
        if( !empty($where) && is_array($where) ){ $where = $this->swhere($where); }else{ $where = "where 1=1"; }
        if( !empty($limit) && is_int($limit) ){ $limit = "limit 0,$limit"; }else{ $limit = ""; }
        $sql = "select * from $tableName $where order by create_time desc $limit";
        $result = $this->yzh_conn->query($sql)->result_array();
        return $result;
    }

}

?>