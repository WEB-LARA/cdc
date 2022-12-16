<?php
  class Toko extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('master/Mod_cdc_master_toko');
	    $this->load->model('master/Mod_cdc_master_branch');
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

	    $option['branch'] = $this->Mod_cdc_master_branch->getOption();
      $this->load->view("master/view_toko",$option);
    } //END of INDEX()

    function getData(){
      $branchId = null;
      if($this->input->post('branchId')){
        $branchId = $this->input->post('branchId');
      }

      $storeName = '';
      if($this->input->post('code')){
        $storeName = $this->input->post('code');
      }

	    $active = null;
      if($this->input->post('activeDate')){
          $active = $this->input->post('activeDate');
      }

      $result['data'] = $this->Mod_cdc_master_toko->getData($branchId,$storeName,$active);
      echo json_encode($result['data']);
    }

    function addData(){
      $tbl = "cdc_master_toko";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);



      $data = $this->input->post();
      //var_dump($data['branchId']);
      $this->Mod_cdc_master_toko->addData($id,$data);
      echo "Data berhasil ditambahkan !";
    }

    function editData(){
      $data = $this->input->post();
      //echo $data['branchId'];
      //$this->Mod_cdc_master_toko->editData($data);
      echo "Data berhasil diupdate !";
    }

    function deleteData(){
      $id = $this->input->post('storeId');
      $this->Mod_cdc_master_toko->deleteData($id);
      echo json_encode(array('success'=>true));
    }

    function getStore(){
      if( $this->input->post('storeCode') == null && $this->input->post('shift')==null && $this->input->post('salesDate')==null ){
        $storeName = null;
      }
      else{
        $code = $this->input->post('storeCode');
        $shift=$this->input->post('shift');
        $salesDate=$this->input->post('salesDate');
        if ($this->Mod_cdc_master_toko->checkStore($code) == 0) {
          $storeName = 'FALSE';
        }
        else{
          $storeName  = $this->Mod_cdc_master_toko->getStore($code);
          
        }
      }
      echo "".$storeName;
    }


    function getStoreDenom(){
      if( $this->input->post('storeCode') == null && $this->input->post('shift')==null && $this->input->post('salesDate')==null ){
        $storeName = null;
      }
      else{
        $code = $this->input->post('storeCode');
        $shift=$this->input->post('shift');
        $salesDate=$this->input->post('salesDate');
        if ($this->Mod_cdc_master_toko->checkStore($code) == 0) {
          $storeName = 'FALSE';
        }
        else{
          $storeName  = $this->Mod_cdc_master_toko->getStore($code);
          $shift_valid= $this->Mod_cdc_master_toko->get_shift_valid($code,$shift,$salesDate);
          if($shift_valid=='VALID')
          {
              
          }else{
            $storeName='SHIFTINVALID';
          }
        }
      }
      echo "".$storeName;
    }


  }
?>
