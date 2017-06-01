<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getHelpLists()
    {
        $sql = "select * from yzh_help";
        $res = $this->yzh_conn->query($sql)->result_array();
        return $res;
    }

    public function insert_batch_sysHelp($data)
    {
        if(empty($data)){
            return false;
        }

        $this->yzh_conn->insert_batch('yzh_help',$data);
    }
    
    public function del_sysHelp(){
        $sql = "TRUNCATE TABLE yzh_help";
        $res = $this->yzh_conn->query($sql);
        return true;
    }
}

?>
