<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 5){
			redirect(base_url('login'));
		}
		$this->load->model('Mod_sys_user');
	}

	public function index()
	{
		$data['user'] = $this->Mod_login->getData();
		$data['menu'] = $this->Mod_sys_menu->getMenu();
		$data['subMenu'] = $this->Mod_sys_menu->getSub();
		/*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
		$data['role'] = $this->session->userdata('role_id');

		$this->load->view("main/main_header");
		$this->load->view("main/main_menu",$data);
		/*$this->load->view("main/main_body");*/
		$this->load->view('master/view_user',$data);
	}

	public function get_data_user()
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      	$nik = isset($_POST['nik']) ? intval($_POST['nik']) : 'X';
		$result = $this->Mod_sys_user->get_data_user($page, $rows, $nik);
		echo json_encode($result);
	}

	public function add_user()
	{
		if ($this->input->post()) {
			$cek_user = $this->Mod_sys_user->cek_nik_user($this->input->post('nik'));
			if ($cek_user == 0) {
				$count = $this->Mod_sys_user->add_user($this->input->post());
				if ($count > 0) {
					echo 'S';
				} else echo 'E';
			} else echo 'A';
		}
	}

}

/* End of file User.php */
/* Location: ./application/controllers/master/User.php */