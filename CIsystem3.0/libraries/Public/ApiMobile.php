<?php
define("APIKEY", "e3654fc90f2916baf5807a90868ce648");
define("NAMETMP", "#company#=云片网&#code#=");

class CI_ApiMobile{

	/**
	* 模板接口发短信
	* apikey 为云片分配的apikey
	* tpl_id 为模板id
	* tpl_value 为模板值
	* mobile 为接受短信的手机号
	*/
	/*function tpl_send_sms($tpl_id, $code, $mobile){
		$apikey = APIKEY;
		$tpl_value = NAMETMP.$code;
		$url="http://yunpian.com/v1/sms/tpl_send.json";
		$encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
		return $this->sock_post($url, $post_string);
	}*/

    public function tpl_send_sms($mobile, $text){
        $apikey = APIKEY;
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$text");
        $mobile = urlencode("$mobile");
        $post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
        return $this->sock_post($url, $post_string);

    }

    /**
    * url 为服务的url地址
    * query 为请求串
    */
    function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

     //获取随机字符串
    public function getrandomstr($len,$type=null) 
    { 
        switch ($type) {
            case 'num':
                $chars_array = array( 
                "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
                );
                break;

            case 'word':
                $chars_array = array( 
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", 
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", 
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", 
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", 
                "S", "T", "U", "V", "W", "X", "Y", "Z"
                ); 
                break;
            case 'randauto':    # 生成项目编号 不含4
                $chars_array = array( 
                "0", "1", "2", "3", "5", "6", "7", "8", "9"
                );
                break;
            
            default:
                $chars_array = array( 
                "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", 
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", 
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", 
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", 
                "S", "T", "U", "V", "W", "X", "Y", "Z"
                ); 
                break;
        }       
        
        $charsLen = count($chars_array) - 1;
        $outputstr = ""; 
        for ($i=0; $i<$len; $i++) 
        { 
            $outputstr .= $chars_array[mt_rand(0, $charsLen)]; 
        } 
        return $outputstr; 
    }
}
?>
