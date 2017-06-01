<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUser()
    {
        $result = $this->yzh_conn->query('select * from yzh_user')->result_array();
        return $result;
    }

}

?>
