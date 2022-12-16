<?php
  class Admin extends CI_controller{

  	function __construct(){
    	parent::__construct();
      	$this->load->model('Mod_admin');
    }

  	function index(){
        if($this->session->userdata('logged_in')){
          redirect(base_url());
        }else{
          $this->load->view("main/main_header");
          $this->load->view("view_login");
        }
    }

    public function entry_user()
    {
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      //$data['bank'] = $this->Mod_deposit->getBank();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
     // $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      /*$this->load->view("main/main_body");*/
      $this->load->view('view_entry_user',$data);
    }

    public function entry_stn()
    {
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      //$data['bank'] = $this->Mod_deposit->getBank();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
     // $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      /*$this->load->view("main/main_body");*/
      $this->load->view('view_entry_stn',$data);
    }

    public function getBankAcc(){
    	$result = $this->Mod_admin->getBankAcc();
    	echo json_encode($result);
    }

    public function getMasterBank(){
    	$result = $this->Mod_admin->getMasterBank();
    	echo json_encode($result);
    }

    public function getBranch(){
    	$result = $this->Mod_admin->getBranch();
    	echo json_encode($result);
    }

     public function getBranchWithID(){
    	$result = $this->Mod_admin->getBranchWithID();
    	echo json_encode($result);
    }

    public function getRole(){
    	$result = $this->Mod_admin->getRole();
    	echo json_encode($result);
    }

     public function getDCode($branch){
    	$result = $this->Mod_admin->getDCode($branch);
    	echo json_encode($result);
    }

    public function cek_nik(){
    	$data = $this->input->post();
    	$result = $this->Mod_admin->cek_nik($data);
    	echo $result;
    }

    public function cek_bank(){
    	$data = $this->input->post();
    	$result = $this->Mod_admin->cek_bank($data);
    	echo $result;
    }

    public function getUserNIK(){
    	$result = $this->Mod_admin->getUserNIK();
    	echo json_encode($result);
    }

    public function getOldNIK(){
    	$data = $this->input->post();
    	$result = $this->Mod_admin->getOldNIK($data);
    	echo $result;
    }

    public function getOldBank(){
    	$data = $this->input->post();
    	$result = $this->Mod_admin->getOldBank($data);
    	echo json_encode($result);
    }

    public function edit_user(){
    	$data = $this->input->post();

    	$branch = $this->Mod_admin->getBranchID($data['branch']);
    	$result = $this->Mod_admin->edit_user($data,$branch);

    	echo $result;
    }

    public function edit_bank(){
    	$data = $this->input->post();
    	$tgl = '';
    	if($data['active'] == 'N'){
    		$tgl = date('Y-m-d');
    	}

    	$result = $this->Mod_admin->edit_bank($data,$tgl);

    	echo $result;
    }

    public function resetPassword(){
    	$data = $this->input->post();
    	$result = $this->Mod_admin->resetPassword($data);

    	echo $result;
    }

    public function insert_user(){
    	$data = $this->input->post();

    	$branch = $this->Mod_admin->getBranchID($data['branch']);
    	$maxID = $this->Mod_admin->getMaxID();

    	if($maxID != ''){
    		$maxID = intval($maxID) + 1;
    		$result = $this->Mod_admin->insert_user($data,$maxID,$branch);
    	}else{
    		$result = 0;
    	}
   
    	echo $result;
    }

    public function insert_bank(){
    	$data = $this->input->post();

    	$result = $this->Mod_admin->insert_bank($data);
    	
    	echo $result;
    }

    public function getDataUser(){
    	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$nik = isset($_POST['nik']) ? $_POST['nik'] : '';
		$offset = ($page-1)*$rows;

    	$where = ' WHERE "ROLE_ID" in (1,3,4)';

    	$where2 = '';
		if($nik != '' || $nik != null){
			if($nik != 0){
				$where2 = 'AND BTRIM("NIK") = \''.$nik.'\'';
				$where .= 'AND BTRIM("NIK") = \''.$nik.'\'';
			}
		}

    	$rs = $this->Mod_admin->count_row('sys_user_2',$where);
		$result["total"] = $rs->COUNT;

		$rs = $this->Mod_admin->getDataUser($rows,$offset,$where2);

		$items = array();
		foreach ($rs as $row) {
			array_push($items, $row);
		}
			    
		$result["rows"] = $items;

    	echo json_encode($result);
    }

    public function getDataBank(){
    	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$bank_acc = isset($_POST['bank_acc']) ? $_POST['bank_acc'] : '';
		$offset = ($page-1)*$rows;

    	//$where = ' WHERE "ROLE_ID" in (1,3,4)';

    	$where2 = '';
		if($bank_acc != '' || $bank_acc != null){
			if($bank_acc != 0){
				$where2 = 'WHERE "BANK_ACCOUNT_ID" = \''.$bank_acc.'\'';
			}
		}

    	$rs = $this->Mod_admin->count_row('cdc_master_bank_account','');
		$result["total"] = $rs->COUNT;

		$rs = $this->Mod_admin->getDataBank($rows,$offset,$where2);

		$items = array();
		foreach ($rs as $row) {
			array_push($items, $row);
		}
			    
		$result["rows"] = $items;

    	echo json_encode($result);
    }

  }

 ?>