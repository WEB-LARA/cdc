<?php
  class Penambah extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('master/Mod_cdc_master_detail_penambah');
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

      $this->load->view("master/view_penambah");


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

      $result['data'] = $this->Mod_cdc_master_detail_penambah->getData($name,$account);
      echo json_encode($result['data']);
    }

    function addData(){
      $tbl = "cdc_master_detail_penambah";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      $this->Mod_cdc_master_detail_penambah->addData($id,$data);
      echo "Data berhasil ditambahkan !";
    }

    function editData(){
      $data = $this->input->post();
      $this->Mod_cdc_master_detail_penambah->editData($data);
      echo "Data berhasil diupdate !";
    }

    function deleteData(){
      $id = $this->input->post('plusId');
      $this->Mod_cdc_master_detail_penambah->deleteData($id);
      echo json_encode(array('success'=>true));
    }

    function getOption(){
      $result= $this->Mod_cdc_master_detail_penambah->getOption();
      //var_dump($result);
      echo json_encode($result);
    }

    function getName(){
      $id = $this->input->post('penambahId');
      $result= $this->Mod_cdc_master_detail_penambah->getName($id);
      echo json_encode($result);
    }

  }
?>
