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
        $this->load->helper("identify");
        $this->load->library('Public/ChinapnrClass', null, 'chinapnr');
        $this->load->library('Public/ApiMobile',null,'mobileSMS');
        $this->uid      = !empty($_COOKIE['yzh_admin_uid']) ? $_COOKIE['yzh_admin_uid'] : '';
        $this->realname = !empty($_COOKIE['yzh_admin_realname']) ? $_COOKIE['yzh_admin_realname'] : $this->getUserInfo($this->uid)['realname'];
        $this->username = !empty($_COOKIE['yzh_admin_username']) ? $_COOKIE['yzh_admin_username'] : $this->getUserInfo($this->uid)['username'];
        $role_type = !empty($_COOKIE['yzh_admin_roletype']) ? $_COOKIE['yzh_admin_roletype'] : '';
        # 权限判断：解密后存入变量:1,2,3,7
        if(md5('1_role_type') == $role_type){
            $this->role_type  = 1; # 超级管理员
        }else if(md5('2_role_type') == $role_type){
            $this->role_type  = 2; # 普通管理员
        }else if(md5('3_role_type') == $role_type){
            $this->role_type  = 3; # 客服
        }else if(md5('4_role_type') == $role_type){
            $this->role_type  = 4; # 风险控制管理员
        }else if(md5('5_role_type') == $role_type){
            $this->role_type  = 5; # 普通融资人
        }elseif(md5('7_role_type') == $role_type){
            $this->role_type  = 7; # 内部融资人
        }else{
            $this->role_type = "";
        }
        $this->userinfo = array(
            'role'      =>$this->role_type,
            'username'  =>$this->username,
            'uid'       =>$this->uid,
            'realname'  =>$this->realname,
            'route'     =>array(
                'controller'=>$this->router->class,
                'action'    =>$this->router->method
                )
        );
        $this->pagesize = 20;
    }

    public function getUserInfo($uid = 0)
    {
        $userinfo = $this->yzh_conn->where("uid",$uid)->get("yzh_user")->result_array();
        if(isset($userinfo[0])){
            $userinfo['realname'] = $userinfo[0]['realname'];
            $userinfo['username'] = $userinfo[0]['username'];
        }else{
            $userinfo['realname'] = '';
            $userinfo['username'] = '';
        }
        return $userinfo;
    }

    //alert方法
    function alert($msg,$p=null)
    {
        if( $p == -1 ){
            echo "<script language='JavaScript'>;alert('".$msg."');history.back(-1);</script>";
        }else{
            echo "<script language='JavaScript'>;alert('".$msg."');</script>";
        }
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

        $user_info = $this->yzh_conn->where("uid",$this->uid)->get("yzh_user")->result_array();
        if(empty($user_info)){
            header("Location:/Login/logout");exit;
        }

        if(empty($this->username) && $this->router->class!="Login" )
        {
            header("Location:/Login/logout");exit;
        }
        # 添加用户的权限验证,每个功能需要配置用户权限
        if(!empty($this->role_type) && $this->router->class!="Login"){
            $configRole = $this->config->config['userRoles'];
            if(!in_array($this->role_type,$configRole[$this->router->class][$this->router->method]))
            {
                header("Location:/Login/logout");exit;
            }
        }

    }

    public function setCookie($key,$info,$time=null){
        $this->load->helper('cookie');
        set_cookie($key,$info,$time);//userInfo：cookie名称。$info:要保存的cookie 。$time 设置保存期，即过期时间获取cookie:
    }

    public  function getCookie($info){
        //$info实际就是形成，调用这个方法的时候，需要获取哪个cookie名称就在调用的时候输入cookie名称
        $this->load->helper("cookie");
        return get_cookie($info);
    }
    
    //判断用户是否登录
    public function islogin($url=null)
    {
        $userInfo['uid'] = $this->getCookie('uid_cookie');
        $userInfo['uname'] = $this->getCookie('uname_cookie');
        //var_dump($userInfo);exit;
        if(empty($userInfo['uid']) || empty($userInfo['uname'])){
            if($url){
                header('Location:/login?u='.$url);exit;
            }else{
                header('Location:/login');exit;
            }
        }else{
            $userInfo = $this->User_model->getUser( array("uid" => $userInfo['uid']) );
            $data['userInfo'] = (array)$userInfo[0];
                if(empty($data['userInfo'])){
                    if($url){
                    header('Location:/login?u='.$url);exit;
                }else{
                    header('Location:/login');exit;
                }
            }
        }
    }

    //字符串过滤
    function filter_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //alert方法
    function alert($msg,$p=null)
    {
        if( $p == -1 ){
            echo '<script language="JavaScript">;alert("'.$msg.'");history.back(-1);</script>';
        }else{
            echo '<script language="JavaScript">;alert("'.$msg.'");</script>';
        }
    }

    //writeLog
    function writeLog( $fileLog, $content )
    {
        $fp = fopen($fileLog,"a");
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * vCode(m,n,x,y) m个数字  显示大小为n   边宽x   边高y
     * http://blog.qita.in
     * 自己改写记录session $code
     */

    function vCode($num = 4, $size = 20, $width = 0, $height = 0) {
        !$width && $width = $num * $size * 4 / 5 + 5;
        !$height && $height = $size + 10; 
        // 去掉了 0 1 O l 等
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        $code = '';
        for ($i = 0; $i < $num; $i++) {
            $code .= $str[mt_rand(0, strlen($str)-1)];
        } 
        //var_dump($code);exit;
        // 画图像
        $im = imagecreatetruecolor($width, $height); 
        // 定义要用到的颜色
        $back_color = imagecolorallocate($im, 235, 236, 237);
        $boer_color = imagecolorallocate($im, 118, 151, 199);
        $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120)); 
        // 画背景
        imagefilledrectangle($im, 0, 0, $width, $height, $back_color); 
        // 画边框
        imagerectangle($im, 0, 0, $width-1, $height-1, $boer_color); 
        // 画干扰线
        for($i = 0;$i < 25;$i++) {
            $font_color = imagecolorallocate($im, mt_rand(10, 255), mt_rand(20, 255), mt_rand(30, 255));
            imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
        } 
        // 画干扰点
        for($i = 0;$i < 150;$i++) {
            $font_color = imagecolorallocate($im, mt_rand(10, 255), mt_rand(20, 255), mt_rand(30, 255));
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
        } 
        // 画验证码
        $fontsize = 20;
        @imagefttext($im, $fontsize, 0, 5, $size + 3, $text_color, './public/verdanai1.ttf', $code);
        //var_dump(@imagefttext($im, $size , 0, 5, $size + 3, $text_color, '../verdanai.ttf', $code));exit;
        //@imagefttext($im, $size , 0, 5, $size + 3, $text_color, '/public/verdanai.ttf', $code);
        $this->load->library('session');
        $this->session->set_userdata('VerifyCode', $code);
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png;charset=utf-8");
        imagepng($im);
        imagedestroy($im);
    }


    public function curl_request($method,$url,$params=array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(strtolower($method) == "post"){
            curl_setopt($ch, CURLOPT_POST,count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

?>
