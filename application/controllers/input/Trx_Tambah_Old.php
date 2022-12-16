<?php
  class Trx_Tambah extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('input/Mod_cdc_trx_detail_tambah');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    function addData(){
      $tbl = "cdc_trx_detail_tambah";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      if ($this->Mod_cdc_trx_detail_tambah->addData($id,$data) > 0) {
        echo "Data berhasil ditambahkan !";
      }else{
        echo "Data gagal ditambahkan, mohon untuk dicoba kembali.";
      }
    }

    function updateData(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_tambah->updateData($data);
      echo "Data berhasil diupdate !";
    }

    function getData($dataId){
      $result['data'] = $this->Mod_cdc_trx_detail_tambah->getData($dataId);
      echo json_encode($result['data']);
    }

    function getDataDetail($dataId){
      $result= $this->Mod_cdc_trx_detail_tambah->getDataDetail($dataId);
      //var_dump(json_encode($result));
      echo json_encode($result);
    }

    function getTotal($dataId){
      $total = $this->Mod_cdc_trx_detail_tambah->getTotal($dataId);
      echo $total;
    }

    function deleteData(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_tambah->deleteData($data);
      echo "Delete berhasil !";
    }

    function get_master_penambah($value = null)
    {
      $result = $this->Mod_cdc_trx_detail_tambah->get_master_penambah($value);
      echo json_encode($result);
    }

    function get_rec_penambah()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
        $plus_id = $this->input->post('plus_id');
        $result = $this->Mod_cdc_trx_detail_tambah->get_rec_penambah($rec_id, $plus_id);
        echo json_encode($result);
      }
    }

    function save_data_penambah()
    {
      if ($this->input->post()) {
        $tbl = "cdc_trx_detail_tambah";
        $id  = $this->Mod_cdc_seq_table->getID($tbl);
        $det_id =  $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id =  $this->input->post('rec_id');
        $plus_id =  $this->input->post('plus_id');
        $plus_date =  $this->input->post('plus_date');
        $plus_desc =  $this->input->post('plus_desc');
        $plus_amount =  $this->input->post('plus_amount');
        if ($plus_desc == '') {
          $mas_plus_desc = $this->Mod_cdc_trx_detail_tambah->get_desc_master_plus($plus_id);
          $store_code = $this->Mod_cdc_trx_detail_tambah->get_store_by_rec_id($rec_id);
          $plus_desc = $mas_plus_desc->TRX_PLUS_DESC.' '.$store_code->STORE_CODE.' '.str_replace('-', '/', $plus_date);
        }
        $result = $this->Mod_cdc_trx_detail_tambah->save_data_penambah($id, $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount);
        echo $result;
      }
    }

    function delete_data_penambah()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('plus_det_id');
        $result = $this->Mod_cdc_trx_detail_tambah->delete_data_penambah($det_id);
        echo $result;
      }
    }

  }
?>
