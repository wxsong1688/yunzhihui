<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLists($data = array(),$offset = 0)
    {
        $where = $this->getWhere($data);
        $order = " order by id desc";
        $limit = " limit ".$offset.",".$this->pagesize;
    	$sql = "SELECT * FROM yzh_project WHERE ".$where.$order.$limit;
    	$res = $this->yzh_conn->query($sql)->result_array();
    	return $res;
    }
    
    public function getListsCount($data = array())
    {
        $where = $this->getWhere($data);
        $sql = "select count(1) as count FROM yzh_project WHERE ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getWhere($data)
    {
    	$where = " 1=1 ";
    	if(!empty($data['pro_id']))
    	{
    		$where .= " and id=".$this->yzh_conn->escape($data['pro_id']);
    	}
        if(!empty($data['id']))
        {
            $where .= " and id=".$this->yzh_conn->escape($data['id']);
        }
        if(!empty($data['tenderee_uid']))
        {
            $where .= " and tenderee_uid=".$this->yzh_conn->escape_str($data['tenderee_uid']);
        }
        if(!empty($data['pro_name']))
        {
            $where .= " and pro_name=".$this->yzh_conn->escape($data['pro_name']);
        }
        if(!empty($data['type']))
        {
            $where .= " and type=".$this->yzh_conn->escape($data['type']);
        }
        if(!empty($data['amount']))
        {
            $where .= " and amount=".$this->yzh_conn->escape($data['amount']);
        }
        if(!empty($data['cycle']))
        {
            $where .= " and cycle=".$this->yzh_conn->escape($data['cycle']);
        }
        if(!empty($data['status']))
        {
            if($data['status']==99){
                $where .= " and status > 1";
            }else{
                $where .= " and status=".$this->yzh_conn->escape($data['status']); 
            }            
        }
        if(!empty($data['time_start']))
        {
            $where .= " and create_time>".$this->yzh_conn->escape($data['time_start']." 00:00:00");
        }
        if(!empty($data['time_end']))
        {
            $where .= " and create_time<=".$this->yzh_conn->escape($data['time_end']." 23:59:59");
        }
        if(!empty($data['check_uid']))
        {
            $where .= " and ( audio_uid=".$this->yzh_conn->escape($data['check_uid'])." or raudio_uid= ".$this->yzh_conn->escape($data['check_uid']).") ";
        }
        if(!empty($data['pro_num']))
        {
            $where .= " and pro_num like '%".$data['pro_num']."%' ";
        }
        if(!empty($data['audio_uid']))
        {
            $where .= " and audio_uid=".$this->yzh_conn->escape($data['audio_uid']);
        }


    	return $where;
    }

    public function addProject($data)
    {
    	$sqlk = $sqlv = '';
	    foreach($data as $k=>$v)
        {
            $sqlk .= ','.$k;
            $sqlv .= ",".$this->yzh_conn->escape($v);
        }
         
        $sqlk = substr($sqlk,1);
        $sqlv = substr($sqlv,1);

        $sql = "INSERT INTO yzh_project (".$sqlk.") VALUES (".$sqlv.")";
        //echo $sql;exit;
        $res = $this->yzh_conn->query($sql);
        $res = $this->yzh_conn->affected_rows();
        return $res;
    }

    public function editProject($data)
    {
    	$id = intval($data['pid']);
    	if(empty($id)){
    		return false;
    	}

    	unset($data['pid']);
    	$sqlv = "";
	    foreach($data as $k=>$v)
        {
            $sqlv .= ",". $k ."=".$this->yzh_conn->escape($v);
        }
        $sqlv = substr($sqlv,1);
    	$sql = "update yzh_project set ".$sqlv." where id=".$id;
        $res = $this->yzh_conn->query($sql);
        $res = $this->yzh_conn->affected_rows();
        return $res;
    }

    public function getUserProjects($data,$offset = 0)
    {
        $where = $this->getUserWhere($data);
        //$filed = "a.*,b.username,b.phone,c.pro_name";
        $limit = " limit ".$offset.",".$this->pagesize;

        $sql = "select a.*,b.pro_name from  yzh_project_user a left join yzh_project b on a.pro_id=b.id where ".$where.$limit;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }


    public function getUserProjectsCount($data = array())
    {
        $where = $this->getUserWhere($data);
        $sql = "select count(1) as count from yzh_project_user a left join yzh_project b on a.pro_id=b.id where ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getUserWhere($data)
    {
        $where = " 1=1 ";
        
        if(!empty($data['id']))
        {
            $where .= " and a.pro_id=".$this->yzh_conn->escape($data['id']);
        }
        if(!empty($data['uid']))
        {
            $where .= " and a.uid=".$this->yzh_conn->escape($data['uid']);
        }
        /*if(!empty($data['pro_name']))
        {
            $where .= " and c.pro_name=".$this->yzh_conn->escape($data['pro_name']);
        }
        if(!empty($data['username']))
        {
            $where .= " and b.username=".$this->yzh_conn->escape($data['username']);
        }
        if(!empty($data['phone']))
        {
            $where .= " and b.phone=".$this->yzh_conn->escape($data['phone']);
        }
        if(!empty($data['status']))
        {
            $where .= " and a.status=".$this->yzh_conn->escape($data['status']);
        }*/
        return $where;
    }

    public function getProjectCycle()
    {
        $sql = "select * from yzh_project_cycle order by cycle asc";
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function editProjectCycle($data)
    {
        if(empty($data)){
            return false;
        }
        foreach($data as $k=>$v){
            $sql = "update yzh_project_cycle set ifshow = ".$this->yzh_conn->escape_str($v)." where cycle =".$this->yzh_conn->escape_str($k);
            $res = $this->yzh_conn->query($sql);
        }
        return true;
    }

    public function getZqzrLists($data = array(),$offset = 0)
    {
        $where = $this->getZqzrWhere($data);
        $limit = " limit ".$offset.",".$this->pagesize;
        $sql = "SELECT * FROM yzh_credit WHERE ".$where.$limit;
        //echo $sql;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }
    
    public function getZqzrCount($data = array())
    {
        $where = $this->getZqzrWhere($data);
        $sql = "SELECT count(1) as count FROM yzh_credit WHERE ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getZqzrWhere($data)
    {
        $where = " 1=1 ";

        if(!empty($data['pro_id'])){
            $where .= " and pro_id=".$this->yzh_conn->escape($data['pro_id']);
        }
        if(!empty($data['creditor_id'])){
            $where .= " and creditor_id=".$this->yzh_conn->escape($data['creditor_id']);
        }
        if(!empty($data['time_start']))
        {
            $where .= " and deal_time>".$this->yzh_conn->escape($data['time_start']." 00:00:00");
        }
        if(!empty($data['time_end']))
        {
            $where .= " and deal_time<=".$this->yzh_conn->escape($data['time_end']." 23:59:59");
        }

        return $where;
    }
}

?>
