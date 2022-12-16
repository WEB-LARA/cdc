<?php
  class Trx_Voucher extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('input/Mod_cdc_trx_detail_voucher');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    function getData($dataId){
      $result['data'] = $this->Mod_cdc_trx_detail_voucher->getData($dataId);
      echo json_encode($result['data']);
    }

    function addData(){
      $tbl = "cdc_trx_detail_voucher";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data   = $this->input->post();
      $result = $this->Mod_cdc_trx_detail_voucher->addData($id,$data);
      echo $result;
    }

    function getDataDetail($dataId){
      $result= $this->Mod_cdc_trx_detail_voucher->getDataDetail($dataId);
      echo json_encode($result);
    }

    function updateData(){
      $data = $this->input->post();
      $result = $this->Mod_cdc_trx_detail_voucher->updateData($data);
      echo "Data berhasil diupdate !";
    }

    function cekVoucher(){
      if ($this->input->post()) {
        $num    = $this->input->post('voucherNum');
        $result = $this->Mod_cdc_trx_detail_voucher->cekVoucher($num);
        echo json_encode($result);
      }
    }

    function getTotal($dataId){
      $total = $this->Mod_cdc_trx_detail_voucher->getTotal($dataId);
      echo $total;
    }

    function deleteData(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_voucher->deleteData($data);
      echo "Delete berhasil !";
    }

    //function voucher shift
     function getDataVoucherShift($dataId){
      $result['data'] = $this->Mod_cdc_trx_detail_voucher->getDataVoucherShift($dataId);
      echo json_encode($result['data']);
    }

    function addDataShift(){
      /*$tbl = "cdc_trx_detail_voucher";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);*/

      $data   = $this->input->post();
      $result = $this->Mod_cdc_trx_detail_voucher->addDataShift($data);
      echo $result;
    }

     function updateDataShift(){
      $data = $this->input->post();
      $result = $this->Mod_cdc_trx_detail_voucher->updateDataShift($data);
      echo "Data berhasil diupdate !";
    }

    function getTotalShift($dataId){
      $total = $this->Mod_cdc_trx_detail_voucher->getTotalShift($dataId);
      echo $total;
    }

    function deleteDataShift(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_voucher->deleteDataShift($data);
      echo "Delete berhasil !";
    }

  }
?>
