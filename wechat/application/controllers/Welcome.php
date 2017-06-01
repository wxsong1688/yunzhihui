<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Base_Controller
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
		$this->load->model('Demo_model');
		$this->load->model('welcome_model');
	}

	public function index()
	{
		echo "wechat";
		$result = $this->welcome_model->getInfo();
		$result2 = $this->Demo_model->getUser();
		print_R($result2);
		$this->load->view('welcome_message');
	}
}
