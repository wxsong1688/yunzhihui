<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    ##超级管理员，普通管理员，客服，内部融资人可以登录后台
    public function getUserInfo($username,$password)
    {
    	$role   = "1,2,3,4,7";
    	$where  = " username= '".addslashes($username)."' and pwd1 = '".addslashes($password)."' and type in (".$role.")"; 
        $result = $this->yzh_conn->query('select * from yzh_user where '.$where)->result_array();
        return $result;
    }

}

?>
