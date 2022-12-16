<?php
  class Pengurang extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('master/Mod_cdc_master_detail_pengurang');
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
      $this->load->view("main/main_footer");

      $this->load->view("master/view_pengurang");


    } //END of INDEX()

    function getData(){
      $name = '';
      if($this->input->post('name')){
        $name = $this->input->post('name');
      }
      $account = '';
      if($this->input->post('account')){
          $code = $this->input->post('account');
      }

      $result['data'] = $this->Mod_cdc_master_detail_pengurang->getData($name,$account);
      echo json_encode($result['data']);
    }

    function addData(){
      $tbl = "cdc_master_detail_pengurang";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      $this->Mod_cdc_master_detail_pengurang->addData($id,$data);
      echo "Data berhasil ditambahkan !";
    }

    function editData(){
      $data = $this->input->post();
      $this->Mod_cdc_master_detail_pengurang->editData($data);
      echo "Data berhasil diupdate !";
    }

    function deleteData(){
      $id = $this->input->post('plusId');
      $this->Mod_cdc_master_detail_pengurang->deleteData($id);
      echo json_encode(array('success'=>true));
    }

    function getOption(){
      $result= $this->Mod_cdc_master_detail_pengurang->getOption();
      echo json_encode($result);
    }

    function getName(){
      $id = $this->input->post('pengurangId');
      $result= $this->Mod_cdc_master_detail_pengurang->getName($id);
      echo json_encode($result);
    }

  }
?>
