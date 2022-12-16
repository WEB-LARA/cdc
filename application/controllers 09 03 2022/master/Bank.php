<?php
  class Bank extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('master/Mod_cdc_master_bank');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    function index(){
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
	    $data['subMenu'] = $this->Mod_sys_menu->getSub();
      $data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      $this->load->view("main/main_body");

      $this->load->view("master/view_bank");

      $this->load->view("main/main_footer");
    } //END of INDEX()

    function getData(){
      $name = '';
      if($this->input->post('name')){
        $name = $this->input->post('name');
      }

      $type = '';
      if($this->input->post('type')){
        $type = $this->input->post('type');
      }

	    $num = '';
      if($this->input->post('num')){
          $num = $this->input->post('num');
      }

      $result['data'] = $this->Mod_cdc_master_bank->getData($name,$type,$num);
      echo json_encode($result['data']);
    }

    function addData(){
      $tbl  = "cdc_master_bank";
      $id   = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      $this->Mod_cdc_master_bank->addData($id,$data);
      echo "Data berhasil ditambahkan !";
    }

    function editData(){
      $data = $this->input->post();
      $this->Mod_cdc_master_bank->editData($data);
      echo "Data berhasil diupdate !";
    }

    function deleteData(){
      $id = $this->input->post('bankId');
      $this->Mod_cdc_master_bank->deleteData($id);
      echo json_encode(array('success'=>true));
    }

  }
?>
