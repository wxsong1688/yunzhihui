<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLists($data = array(),$offset = 0)
    {
        //echo $this->pagesize;exit;
    	# a表是融资者资产表 b表是用户表
        /*$this->yzh_conn->select("a.*,b.username,b.phone");
        $this->yzh_conn->from('yzh_tenderee_account as a');
        $this->yzh_conn->join('yzh_user as b','b.uid = a.uid','join');
        $this->yzh_conn->where(array('b.uid'=>64,'a.id'=>3));*/

        $filed = " a.*,b.username,b.phone";
        $where = $this->getWhere($data);
        $limit = " limit ".$offset.",".$this->pagesize;

        $sql = "select ".$filed." from yzh_tenderee_account a left join yzh_user b on b.uid=a.uid where ".$where.$limit;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function getListsCount($data = array())
    {
        $where = $this->getWhere($data);
        $sql = "select count(1) as count from yzh_tenderee_account a left join yzh_user b on b.uid=a.uid where ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getInvestLists($data = array(),$offset = 0)
    {
        $filed = " a.*,b.username,b.phone";
        $where = $this->getWhere($data);
        $limit = " limit ".$offset.",".$this->pagesize;

        $sql = "select ".$filed." from yzh_user_account a left join yzh_user b on b.uid=a.uid where ".$where.$limit;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function getInvestListsCount($data = array())
    {
        $where = $this->getWhere($data);
        $sql = "select count(1) as count from yzh_user_account a left join yzh_user b on b.uid=a.uid where ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getWhere($data)
    {
    	$where = " 1=1 ";
    	if(!empty($data['uid']))
    	{
    		$where .= " and a.uid=".$this->yzh_conn->escape($data['uid']);
    	}
        if(!empty($data['username']))
        {
            $where .= " and b.username=".$this->yzh_conn->escape($data['username']);
        }
        if(!empty($data['phone']))
        {
            $where .= " and b.phone=".$this->yzh_conn->escape($data['phone']);
        }
        
    	return $where;
    }

    public function lockUser($uid,$type,$from)
    {
        $table = $from == 1 ? "yzh_user_account" : "yzh_tenderee_account";
        $set   = $type == 1 ? "iflock = 1" : "iflock = 0" ;
        $sql = "update ".$table ." set ".$set." where uid=".$this->yzh_conn->escape($uid);
        $res = $this->yzh_conn->query($sql);
        $res = $this->yzh_conn->affected_rows();
        return $res;
    }

    public function getUserTendereeAccount($uid)
    {
        if(empty($uid)){
            return array();
        }
        $sql = "select * from yzh_tenderee_account where uid = ".$this->yzh_conn->escape($uid);
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function getInvestFlowCount($data)
    {
        $where = $this->getInvestFlowWhere($data);

        $sql = "select count(1) as count from yzh_user_flow_".$where['table']." where ".$where['where'];
        $res = $this->yzh_conn->query($sql)->result_array();
        return !empty($res)?$res[0]['count']:0;
    }

    public function getInvestFlowList($data,$offset)
    {
        $where = $this->getInvestFlowWhere($data);
        $limit = " limit ".$offset.",".$this->pagesize;

        $sql = "select * from yzh_user_flow_".$where['table']." where ".$where['where'].$limit;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function getInvestFlowWhere($data)
    {
        $where = " 1=1 ";
        $table = date("Y");
        if(!empty($data['uid']))
        {
            $where .= " and uid=".$this->yzh_conn->escape($data['uid']);
        }
        if(!empty($data['time_start']))
        {
            $where .= " and create_time >= ".$this->yzh_conn->escape($data['time_start']);
            $table = date("Y",strtotime($data['time_start']));
        }
        if(!empty($data['phone']))
        {
            $where .= " and create_time <= ".$this->yzh_conn->escape($data['time_end']);
            $table = date("Y",strtotime($data['time_end']));
        }
        
        return array('where'=>$where,'table'=>$table);
    }

    //获取融资人账户信息
    public function getTendereeAccount($data)
    {
        $query = $this->yzh_conn->get_where('yzh_tenderee_account', $data);
        $result = $query->result_array();
        return $result;
    }

    public function updateTendereeAccount($uid,$data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_tenderee_account', $data, array('uid'=>$uid));
        return $result;
    }

    //创建流水记录
    public function addTendereeFlow($data)
    {
        $data = (object)$data;
        $res  = $this->yzh_conn->insert('yzh_tenderee_flow_2015',$data);
        return $res;
    }


}

?>
