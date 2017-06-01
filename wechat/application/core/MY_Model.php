<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        header("Content-type:text/html;charset=utf-8");
    }

}

?>
