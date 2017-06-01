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
        $this->bankinfo = $this->config->config['bank'];
        $this->yzh_conn = $this->load->database('yzh',true);
        $this->load->helper("identify");
    }

    public function setCookie($key,$info,$time=null){
        $this->load->helper('cookie');
        set_cookie($key,$info,$time);//userInfo：cookie名称。$info:要保存的cookie 。$time 设置保存期，即过期时间获取cookie:
    }

    public function getCookie($info){
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
            echo "<script language='JavaScript'>;alert('".$msg."');history.back(-1);</script>";
        }else{
            echo "<script language='JavaScript'>;alert('".$msg."');</script>";
        }
    }

    //遮罩层封装
    function alertlog($title, $content, $button){
        $html = '<div class="imsec2-ul-out"></div>';
        $html .= '<div class="show-center modal">';
        $html .= '<div class="cloud-zz show-center">';
        $html .= '<div class="show-center imsec2-ul-inner">';
        $html .= '<div class="modal-section1 text-center">';
        $html .= '<div class="text-right"><button class="modal-close" aria-hidden="true" data-dismiss="modal">×</button></div>';
        switch ($button) {
            case 'login':
                $html .= '<p class="register-success">'.$title.'</p>';
                $html .= '<p class="register-suc-text">'.$content.'</p>';
                $html .= '<button class="loginNow" type="button" onClick="login()">现在登录</button>';
                break;

            case 'realname':
                $html .= '<p class="register-success">'.$title.'</p>';
                $html .= '<p class="register-suc-text">'.$content.'</p>';
                $html .= '<button class="loginNow" type="button" onClick="login()">现在进行实名认证</button>';
                break;
            case 'success':
                $html .= '<p class="register-success">'.$title.'</p>';
                $html .= '<p class="register-suc-text">'.$content.'</p>';
                break;
            case 'error':
                $html .= '<p class="register-success">'.$title.'</p>';
                $html .= '<p class="register-suc-text">'.$content.'</p>';
                break;
            
            default:
                $html .= '<p class="register-success">'.$title.'</p>';
                $html .= '<p class="register-suc-text">'.$content.'</p>';
                break;
        }
        $html .= '</div></div></div></div>';
        echo $html;
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

    //当前菜单栏光标
    function getHeaderNavClass()
    {
        $data = array();
        $url = $_SERVER["REQUEST_URI"];
        if( strstr($url, '/Index') ){
            $data['HNav_home']       = 'nav-a-current';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/FinanciaTransactions') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a-current';
            $data['HNav_myAccount']  = 'nav-a';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/Usercenter') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a-current';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/Register') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a-current';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/Login') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a-current';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/SafetysAssurance') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a';
            $data['HNav_safeBz']     = 'nav-a-current';
            $data['HNav_ask']        = 'nav-a';
        }elseif( strstr($url, '/Helpcenter') ){
            $data['HNav_home']       = 'nav-a';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a-current';
        }else{
            $data['HNav_home']       = 'nav-a-current';
            $data['HNav_finan']      = 'nav-a';
            $data['HNav_myAccount']  = 'nav-a';
            $data['HNav_safeBz']     = 'nav-a';
            $data['HNav_ask']        = 'nav-a';
        }
        return $data;
    }

    /*
     * 计算债权转让折价率
     */
    public function discountRatio($cycle, $full_time)
    {
        $ratio  = $this->yzh_conn->where("name","ratio")->get("yzh_sys_config")->row_array();
        $discount = 
            (float)$ratio['value']/100
             + 
            ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($full_time))))/3600/24)
             / 
            ($cycle*30)
             * 
            (100-(float)$ratio['value'])/100;
        return $discount;
    }

    /**
     * 发送短信息
     * @param $tmp_id 模板id
     * @param $tmp_value 模板参数
     * @param $mobile 电话号码
     */
    public function sed_tpl_msg($mobile,$text)
    {
        $this->load->library('Public/ApiMobile', null, 'm');
        return $this->m->tpl_send_sms($mobile,$text);
    }

     /**
     * 发送站内信 
     * @param $tmp_value 模板参数
     * @param $mobile 电话号码
     */
    public function sed_sys_msg($uid,$title,$text)
    {
        $uid    = intval($uid);
        $title  = trim($title);
        $text   = trim($text);
        if(empty($uid) || empty($title) || empty($text)){
            return false;
        }

        $data = array(
            'uid'       => $uid,
            'title'     => $title,
            'content'   => $text,
            'status'    => 0,
            'send_time' => date("Y-m-d H:i:s")
        );
        $this->yzh_conn->insert('yzh_sysmsg_record', $data);
        return true;
    }



    /*
     * 获取
     */
    public function get_random_str($len,$type)
    {
        $this->load->library('Public/ApiMobile', null, 'm');
        return $this->m->getrandomstr($len,$type);
    }

/************************************************************************************************/

    //invested_money
    public function _used_money($uid)
    {
        $res = $this->yzh_conn
            ->from("yzh_project_user as I")->join("yzh_project as P","P.id = I.pro_id")
            ->where(array("I.uid"=>$uid,"I.status"=>1,"I.credit_status !="=>10,"P.status"=>10))
            ->select_sum("I.invest_sum")
            ->get()->row_array();
        return $res['invest_sum'];
    }

    //invested_credit_money
    public function _credit_in_money($uid)
    {
        $res = $this->yzh_conn
            ->from("yzh_credit as C")->join("yzh_project as P","P.id = C.pro_id")->join("yzh_project_user as I","I.id = C.item_id")
            ->where("P.status=10 and I.status=1 and I.credit_status=10 and (C.buyer_uid=".$uid." and C.status=10 or C.creditor_id=".$uid." and C.status=1)")
            ->select_sum("credit_amount")
            ->get()->row_array();
        return $res['credit_amount'];
    }


    /*
     * 计算投资人所有投资项目今日收益
     */
    public function readyEverydayGain($uid)
    {
        $pro_user = $this->yzh_conn
            ->from("yzh_project_user as I")->join("yzh_project as P","P.id = I.pro_id")
            ->where(array("I.uid"=>$uid,"I.status"=>1,"I.credit_status !="=>10,"P.status"=>10))
            ->get()->result_array();
        if(empty($pro_user)){
            return 0;
        }
        $readyEverydayGainTotal = (float)0;
        foreach($pro_user as $k => $v){
            $readyGainInterest = $v['year_rate_out'] / 100 * $v['invest_sum'] / 360;
            $readyEverydayGainTotal += $readyGainInterest;
        }
        return $readyEverydayGainTotal;
    }

    /* 
     * 购买的所有债权转让的今日收益
     */
    public function creditEverydayReady($uid)
    {
        $credit = $this->yzh_conn
            ->from("yzh_credit as C")->join("yzh_project as P","P.id = C.pro_id")->join("yzh_project_user as I","I.id = C.item_id")
            ->where("P.status=10 and I.status=1 and I.credit_status=10 and (C.buyer_uid=".$uid." and C.status=10 or C.creditor_id=".$uid." and C.status=1)")
            ->get()->result_array();
        if(empty($credit)){
            return 0;
        }
        $readyEverydayGainTotal = (float)0;
        foreach($credit as $k => $v){
            $item = $this->yzh_conn->where("id",$v['item_id'])->get("yzh_project_user")->row_array();
            $readyGainInterest = ($v['year_rate_out']/100/360) * $item['invest_sum'];
            $readyEverydayGainTotal += $readyGainInterest;
        }
        return $readyEverydayGainTotal;
    }

    /*
     * 计算投资人所有投资项目待收本息
     */
    public function readyGain($uid)
    {
        $pro_user = $this->yzh_conn
            ->from("yzh_project_user as I")->join("yzh_project as P","P.id = I.pro_id")
            ->where(array("I.uid"=>$uid,"I.status"=>1,"I.credit_status !="=>10,"P.status"=>10))
            ->get()->result_array();
        if(empty($pro_user)){
            return 0;
        }
        $readyGainTotal = (float)0;
        foreach($pro_user as $k => $v){
            $readyGainInterest = $v['year_rate_out']/100 * $v['invest_sum'] * ($v['cycle'] * 30 / 360);
            $readyGain = (float)$v['invest_sum']+$readyGainInterest;
            $readyGainTotal += $readyGain;
        }
        return $readyGainTotal;
    }

    /*
     * 购买的所有债权转让的应收本息
     */
    public function creditReady($uid)
    {
        $credit = $this->yzh_conn
            ->from("yzh_credit as C")->join("yzh_project as P","P.id = C.pro_id")->join("yzh_project_user as I","I.id = C.item_id")
            ->where("P.status=10 and I.status=1 and I.credit_status=10 and (C.buyer_uid=".$uid." and C.status=10 or C.creditor_id=".$uid." and C.status=1)")
            ->get()->result_array();
        if(empty($credit)){
            return 0;
        }
        $readyGainTotal = (float)0;
        foreach($credit as $k => $v){
            $item = $this->yzh_conn->where("id",$v['item_id'])->get("yzh_project_user")->row_array();
            $readyGainInterest = 
                ($v['year_rate_out']/100/360) * $item['invest_sum'] *
                (($v['cycle'] * 30)
                -
                (strtotime(date("Y-m-d",strtotime($v['deal_time']))) - strtotime(date("Y-m-d",strtotime($v['full_time']))))
                /3600/24)
                ;
            $readyGain = (float)$item['invest_sum']+$readyGainInterest;
            $readyGainTotal += $readyGain;
        }
        return $readyGainTotal;
    }

    /*
     * 计算投资人单个项目投资的待收本息
     */
    public function readyGainPro($item_id,$pro_id)
    {
        $pro_info = $this->yzh_conn->where("id",$pro_id)->get("yzh_project")->result_array();
        $item = $this->yzh_conn->where("id",$item_id)->get("yzh_project_user")->result_array();
        
        $readyGainInterest = $pro_info[0]['year_rate_out']/100 * ($pro_info[0]['cycle'] * 30)/360 * $item[0]['invest_sum'];

        $readyGain = (float)$item[0]['invest_sum']+$readyGainInterest;
        return $readyGain;
    }

    /*
     * 计算投资人单条债权转让待收本息
     */
    public function readyGainCre($credit_id)
    {
        $credit_info = $this->yzh_conn->where(array("id"=>$credit_id))->get("yzh_credit")->result_array();
        $record_info = $this->yzh_conn->where(array("credit_id"=>$credit_id))->order_by("id","DESC")->get("yzh_credit_record")->result_array();
        if(empty($record_info)){
            return 0;
        }
        $pro_info = $this->yzh_conn->where("id",$credit_info[0]["pro_id"])->get("yzh_project")->result_array();
        $item = $this->yzh_conn->where("id",$credit_info[0]["item_id"])->get("yzh_project_user")->result_array();
        
        $readyGainInterest = 
        $item[0]['invest_sum'] * $pro_info[0]['year_rate_out']/100
        *
        ( strtotime(date("Y-m-d",strtotime($pro_info[0]['full_time']))) + ($pro_info[0]['cycle'] * 30 * 24 * 3600) - strtotime(date("Y-m-d",strtotime($record_info[0]['deal_time']))) )
        / 3600 / 24 //天数
        / 360 ;

        $readyGain = (float)$item[0]['invest_sum']+$readyGainInterest;
        return $readyGain;
    }

    /**
    * 投资人每期应收金额
    */
    public function getRepaymentList($item_id)
    {
        $this->load->helper("pro_date");
        $item_info = $this->yzh_conn->where("id",$item_id)->get("yzh_project_user")->result_array();
        $pro_info = $this->yzh_conn->where("id",$item_info[0]['pro_id'])->get("yzh_project")->result_array();

        if($pro_info[0]['status']!=10){
            return false;
        }
        $repay_date = array(
            'start_time' => $pro_info[0]['full_time'],
            'cycle' => $pro_info[0]['cycle'],
            'settle_day' => 15,
            );
        $res = repayListDate($repay_date);
        foreach($res as $k => $v){
            $res[$k]['interval'] = (strtotime($v['calcu_end'])-strtotime($v['calcu_start'])) / 3600 / 24;
            $res[$k]['repay_interest'] = $item_info[0]['invest_sum'] * $pro_info[0]['year_rate_out']/100 * $res[$k]['interval']/360;
            if($k == (sizeof($res)-1))//最后一期
            {
                $res[$k]['repay_principal'] = (float)$item_info[0]['invest_sum'];
                $res[$k]['repay_amount'] = (float)$item_info[0]['invest_sum'] + $res[$k]['repay_interest'];
            }else{
                $res[$k]['repay_principal'] = (float)0;
                $res[$k]['repay_amount'] = (float)$res[$k]['repay_interest'];
            }
        }
        return $res;
    }

/************************************************************************************************/

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
