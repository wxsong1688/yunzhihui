<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SecureRechargeWithdrawal extends Base_Controller
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

    

}
