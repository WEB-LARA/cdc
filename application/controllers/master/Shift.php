<?php
  class Shift extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      //$this->load->model('master/Mod_cdc_master_shift');
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

      $this->load->view("master/view_shift");
    } //END of INDEX()

    function getData(){
      $result = $this->Mod_cdc_master_shift->getData();
      echo json_encode($result);
    }

     function get_kode_toko(){
      $result = $this->Mod_cdc_master_shift->get_kode_toko();
      echo json_encode($result);
    }


     function inquiry_master_shift(){
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
     // $this->load->view("main/main_body");
      
      $this->load->view("master/view_inq_master_shift");
      $this->load->view("main/main_footer");

    } //E

     function getDataMasterShift(){
      $store_code = '';
      date_default_timezone_set('Asia/Jakarta');

      if($this->input->post('store_code')){
        $store_code =  $this->input->post('store_code');
      }
      $start_date = '';
      if($this->input->post('start_date')){
        $start_date =  date('Y-m-d' , strtotime($this->input->post('start_date')));
      
      }

      $end_date = '';
      if($this->input->post('end_date')){
          $end_date = date('Y-m-d' , strtotime($this->input->post('start_date')));
      }


       $status = '';
      if($this->input->post('status')){
          $status = $this->input->post('status');
      }
       $metode_setor = '';
      if($this->input->post('metode_setor')){
          $metode_setor = $this->input->post('metode_setor');
      }
      $jml_shift='';
      if( $this->input->post('jumlah_shift')!=''){
          $jml_shift = $this->input->post('jumlah_shift');
    
      }

      $tipe_shift = '';
       if($this->input->post('tipe_shift')){
          $tipe_shift = $this->input->post('tipe_shift');
      }
      $result['data'] = $this->Mod_cdc_master_shift->getDataMasterShift($store_code,$start_date,$end_date,$status,$metode_setor,$jml_shift,$tipe_shift);
      echo json_encode($result['data']);
    }

    function cekData(){
      $branchCode = $this->input->post('branchCode');
      $cari = $this->Mod_cdc_master_shift->cekData($branchCode);
      echo $cari;
    }

    function addData(){
      $data = $this->input->post();
      $result = $this->Mod_cdc_master_shift->addData($data);
      echo $result;
    }

    function saveEdit(){
      $data = $this->input->post();
      $result = $this->Mod_cdc_master_shift->saveEdit($data);
      echo $result;
    }

    function getShift(){
      $result = $this->Mod_cdc_master_shift->getShift();
      return $result;
    }
    function insertMasterShift(){
      $toko=$this->input->post('toko');
      $result=$this->Mod_cdc_master_shift->insertMasterShift($toko);
      echo $result;
        //echo json_encode($toko);
    }

    function deleteMasterShift(){
      $id_shift=$this->input->post('ID_SHIFT');
      $result=$this->Mod_cdc_master_shift->deleteMasterShift($id_shift);
      echo $result;
        //echo json_en
    }

    function getDataMasterShiftDetail(){
      $id_shift=$this->input->post('ID_SHIFT');
      $result=$this->Mod_cdc_master_shift->getDataMasterShiftDetail($id_shift);
      echo json_encode($result);
        //echo json_en

    }


    function updateMasterShift(){
      $store_code=$this->input->post('store_code');
      $id_shift=$this->input->post('id_shift');
      $start_date=$this->input->post('start_date');
      $end_date=$this->input->post('end_date');
      $tipe_shift=$this->input->post('tipe_shift');
      $jml_shift=$this->input->post('jml_shift');
      $metode_setor=$this->input->post('metode_setor');
      $result=$this->Mod_cdc_master_shift->updateMasterShift($id_shift,$store_code,$start_date,$end_date,$tipe_shift,$jml_shift,$metode_setor);
      echo json_encode($result);
    }

  }
?>
