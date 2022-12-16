<?php
  class Trx_GTU extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('input/Mod_cdc_trx_GTU');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    function getData(){
      $result = $this->Mod_cdc_trx_GTU->getData();
      echo json_encode($result);
    }

    function getDataGTUReject($batch_id){
      $result = $this->Mod_cdc_trx_GTU->getDataGTUReject($batch_id);
      echo json_encode($result);
    }

    function getBank(){
      $result = $this->Mod_cdc_trx_GTU->getBank();
      echo json_encode($result);
    }

    function getBankNum(){
      $id = $this->input->post('bank_account_id');
      $result = $this->Mod_cdc_trx_GTU->getBankNum($id);
      echo $result;
    }

    function addData(){
      $tbl = "cdc_trx_gtu";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data   = $this->input->post();
      $result = $this->Mod_cdc_trx_GTU->addData($id,$data);
      echo $result;
    }

    function addDataGTUReject(){
      $tbl = "cdc_trx_gtu";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data   = $this->input->post();
      $result = $this->Mod_cdc_trx_GTU->addDataGTUReject($id,$data);
      echo $result;
    }

    function deleteData(){
      $id = $this->input->post('dataId');
      $result = $this->Mod_cdc_trx_GTU->deleteData($id);
      echo $result;
    }

    function getGTU_detail(){
      $id = $this->input->post('GTU_id');
      $result = $this->Mod_cdc_trx_GTU->getGTU_detail($id);
      echo json_encode($result);
    }

    function updateData(){
      $data = $this->input->post();
      $result = $this->Mod_cdc_trx_GTU->updateData($data);
      echo $result;
    }

    function getTotalAmount(){
      $total = 0;
      $data = $this->input->post('data');
      foreach ($data as $row) {
        $total = $total + $row['CDC_GTU_AMOUNT'];
      }
      echo $total;
    }

    function get_total_gtu()
    {
      $result = $this->Mod_cdc_trx_GTU->get_total_gtu($this->session->userdata('usrId'));
      echo $result;
    }

  }

?>
