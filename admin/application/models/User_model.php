<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLists($data = array(),$offset = 0)
    {
    	$where = $this->getWhere($data);
        $limit = " limit ".$offset.",".$this->pagesize;
    	$sql = "SELECT * FROM yzh_user WHERE ".$where." order by create_time desc ".$limit;
        //echo $sql;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function getListsCount($data = array())
    {
        $where = $this->getWhere($data);
        $sql = "select count(1) as count FROM yzh_user WHERE ".$where;
        $res = $this->yzh_conn->query($sql)->row_array();
        return $res['count'] ? $res['count'] : 0;
    }

    public function getWhere($data)
    {
    	$where = " 1=1 ";
    	if(!empty($data['uid']))
    	{
    		$where .= " and uid=".$this->yzh_conn->escape($data['uid']);
    	}

        if(!empty($data['type']))
        {
            $where .= " and type IN (".$this->yzh_conn->escape_str($data['type']).")";
        }

        if(!empty($data['username']))
        {
            $where .= " and username like '%".$this->yzh_conn->escape_str($data['username'])."%'";
        }

        if(!empty($data['realname']))
        {
            $where .= " and realname=".$this->yzh_conn->escape($data['realname']);
        }

        if(!empty($data['phone']))
        {
            $where .= " and phone=".$this->yzh_conn->escape($data['phone']);
        }

        if(!empty($data['identify']))
        {
            $where .= " and identify=".$this->yzh_conn->escape($data['identify']);
        }

        if(!empty($data['level']))
        {
            $where .= " and level=".$this->yzh_conn->escape($data['level']);
        }

        if(!empty($data['email']))
        {
            $where .= " and email=".$this->yzh_conn->escape($data['email']);
        }

        if(!empty($data['time_start']))
        {
            $where .= " and create_time>".$this->yzh_conn->escape($data['time_start']." 00:00:00");
        }
        if(!empty($data['time_end']))
        {
            $where .= " and create_time<=".$this->yzh_conn->escape($data['time_end']." 23:59:59");
        }
    	return $where;
    }

    public function getUser($array)
    {
        $query = $this->yzh_conn->get_where('yzh_user', $array);
        $result = $query->result();
        return $result;
    }

    public function getUserLevel()
    {
        $sql = "SELECT * FROM yzh_user_level order by level asc";
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function addUser($data)
    {
    	$data = (object)$data;
        $res  = $this->yzh_conn->insert('yzh_user',$data);
        return $res;
    }

    public function editUser($data,$id)
    {
        $this->yzh_conn->where('uid', $id);
        $res = $this->yzh_conn->update('yzh_user', $data); 
        return $res;
    }

    # 是否有进行中的项目的普通投资者，返回0则没有
    public function checkProjByUid($uid)
    {
        $where = "uid = ".$this->yzh_conn->escape($uid)." and status != 20 and status !=50";
        $sql   = "select 1 from yzh_project_user where  ".$where." limit 1";
        $res = $this->yzh_conn->query($sql)->num_rows();
        return $res;
    }

    # 是否有进行中项目的内部融资者，返回0则没有
    public function checkRziProjByUid($uid)
    {
        $where = "tenderee_uid = ".$this->yzh_conn->escape($uid)." and status != 80 and status !=20";
        $sql   = "select 1 from yzh_project where  ".$where." limit 1";
        $res = $this->yzh_conn->query($sql)->num_rows();
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

    # 验证用户的唯一性
    public function getUserInfoByUnique($data,$uid='')
    {
        $where = $this->getWhere($data);
        if(!empty($uid)){
            $where .= " and uid != ".$this->yzh_conn->escape($uid);
        }
        $sql = "SELECT 1 FROM yzh_user WHERE ".$where." limit 1";
        $res = $this->yzh_conn->query($sql)->num_rows();
        return $res;
    }

    //修改用户信息
    public function updateUser($data,$where)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->update('yzh_user', $data, $where);
        return $result;
    }

    public function updateUserLevel($data)
    {
        if(empty($data)){
            return false;
        }
        foreach($data as $k=>$v){
            $sql = "update yzh_user_level set min_money = ".$this->yzh_conn->escape_str($v['min']).",max_money= ".$this->yzh_conn->escape_str($v['max']).",level_name=".$this->yzh_conn->escape($v['name'])." where level=".$this->yzh_conn->escape_str($k);
            $res = $this->yzh_conn->query($sql);
        }
        return true;
    }

    public function getAllcheckUsers($uid)
    {
        $uid   = intval($uid);
        $where = "";
        if(!empty($uid))
        {
            $where = " and uid != ".$this->yzh_conn->escape_str($uid);
        }
        $sql = "select * from yzh_user where type=2 ".$where;
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;  
    }

    public function getUserNamesByUids($uids)
    {
        if(empty($uids)){
            return array();
        }
        $sql = "select username,uid,phone,pwd1 from yzh_user where uid in (".$uids.")";
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;  
    }

    //创建用户绑定银行卡信息
    public function addUserBank($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_user_bank', $data);
        return $result;
    }

    //获取用户绑定银行卡信息
    public function getUserBank($data)
    {
        $query  = $this->yzh_conn->get_where('yzh_user_bank', $data);
        $result = $query->result_array();
        return $result;
    }

    //删除绑定银行卡
    public function delUserBank($data)
    {
        $result = $this->yzh_conn->delete('yzh_user_bank', $data);
        return $result;
    }

    //创建融资人账户
    public function addTendereeAccount($data)
    {
        $data   = (object)$data;
        $result = $this->yzh_conn->insert('yzh_tenderee_account', $data);
        return $result;
    }
}

?>
