<?php
  class Branch extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
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

      $this->load->view("master/view_branch");
    } //END of INDEX()

    function addData(){
      $tbl = "cdc_master_branch";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      $this->Mod_cdc_master_branch->addData($id,$data);
      echo "Data berhasil ditambahkan !";
    }

    function getData(){
	    $code = '';
      if($this->input->post('code')){
          $code = $this->input->post('code');
      }

      $name = '';
      if($this->input->post('name')){
        $name = $this->input->post('name');
      }

      $result['data'] = $this->Mod_cdc_master_branch->getData($code,$name);
      echo json_encode($result['data']);
    }

    function editData(){
      $data = $this->input->post();
      $this->Mod_cdc_master_branch->editData($data);
      echo "Data berhasil diupdate !";
    }

    function deleteData(){
      $id = $this->input->post('branchId');
      $this->Mod_cdc_master_branch->deleteData($id);
      echo json_encode(array('success'=>true));
      //echo json_encode(array("Msg"=>"Delete Success", "errorMsg"=>NULL));
    }

    function getOption(){
      $result= $this->Mod_cdc_master_branch->getOption();
      //var_dump($result['option']);
      echo json_encode($result);
    }

    function getBranchId(){
      $a = $this->input->post();
      if( strlen($a['xxx']) >2 ){
        $b = substr($a['xxx'],0,3);
        $branch_id = $this->Mod_cdc_master_branch->getBranchId($b);
      }
      else{
        $branch_id = $a['xxx'];
      }
      echo $branch_id;
    }


  }
?>
