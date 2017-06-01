<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends App_Controller
{
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('System_model');
	}

	# 常见问题管理
	public function help()
	{
		$res = $this->System_model->getHelpLists();
		$list['userinfo'] = $this->userinfo;
		$list['lists']    = $res;
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('system/help',$list);
		$this->load->view('footer');
	}

	# 日志管理
	public function log()
	{
		
		$this->load->view('header');
		$this->load->view('adminmenu',$this->userinfo);
		$this->load->view('asset/platform');
		$this->load->view('footer');
	}

	public function editHelpInfo()
	{
		$data = $_GET;
		if(!empty($data)){
			$newdata = array();
			$count = count($data['asks']);
			for ($i=0;$i<$count;$i++)
			{
				$newdata[$i]['ask']    = $data['asks'][$i];
				$newdata[$i]['answer'] = $data['answers'][$i];
				$newdata[$i]['time']   = date("Y-m-d H:i:s");
			}
			$this->System_model->del_sysHelp();
			$this->System_model->insert_batch_sysHelp($newdata);
		}
		header("Location:/System/help");
		exit();
	}

	
}
