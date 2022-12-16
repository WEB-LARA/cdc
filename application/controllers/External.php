<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class External extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_external');
	}

	public function index()
	{
		
	}

	public function get_data_amas($branch_code)
	{
		$result = $this->Mod_external->get_data_amas($branch_code);	
		echo json_encode($result);
	}

	public function get_data_toko($branch_code)
	{
		$result = $this->Mod_external->get_data_toko($branch_code);
		echo json_encode($result);
	}

}

/* End of file External.php */
/* Location: ./application/controllers/External.php */