<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Base_Controller
 * 本站核心基类
 * 不需做权限及登陆验证的继承此类，如对外接口
 */
class Base_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header("Content-type:text/html;charset=utf-8");
        $this->yzh_conn = $this->load->database('yzh',true);
    }

}

/**
 * Class App_Controller
 * 本站基类
 * 需要做权限及登陆验证的模块要继承此类
 */
class App_Controller extends Base_Controller {

    function __construct()
    {
        parent::__construct();
    }

}

?>
