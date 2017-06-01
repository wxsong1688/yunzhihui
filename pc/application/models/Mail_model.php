<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_model extends MY_Model
{
    /**
     * 生成邮件内容
     * 
     * @param string $templ
     * @param array $data
     * @return string
     * @author http://blog.iwshop.com/
     */
    public function makeBody($tmpl, $data)
    {
        $content = $this->load->view($tmpl, $data, true);
        return $content;
    }
      
  /**
   * 发送邮件
   * 
   * @param array $param
   * @return boolean
   */
  public function sendMail($param)
  {
      // 发件人配置
      if ( empty($param['frommail']))   $param['frommail'] = 'huojinlei@btte.net';
      if ( empty($param['fromname'])) $param['fromname'] = 'huojinlei';
      //$param['frommail'] = $this->_domain($param['frommail']);
    
      // CI mail 配置
      $this->load->library('email');     
     $config = array();
     $config['charset']  = "UTF-8";  // 编码
     $config['wordwrap'] = TRUE;     // 自动换行
     $config['mailtype'] = 'html';   // 格式 (text/html)
     $config['protocol'] = 'SMTP';   // 邮件协议
     $this->email->initialize($config);
     //$param['cc'] = 'barly.li,qingbin.wu';
      $this->email->from($param['frommail'], $param['fromname']);
      //if ( $param['to'])  $this->email->to($this->_domain($param['to']));
      //if ( $param['cc'])  $this->email->cc($this->_domain($param['cc']));
      //if ( $param['bcc']) $this->email->bcc($this->_domain($param['bcc']));

	  if ( $param['to'])  $this->email->to($param['to']);
      if ( $param['cc'])  $this->email->cc($param['cc']);
      if ( $param['bcc']) $this->email->bcc($param['bcc']);

      //如果存在邮件模板使用模板作为邮件内容 2015-01-08
      $param['content'] = $param['tmpl']?$this->makeBody($param['tmpl'], $param['data']):$param['content'];
      $this->email->subject($param['subject']);
      $this->email->message($param['content']);
      $this->email->send();
      return true;
  }
}

?>
