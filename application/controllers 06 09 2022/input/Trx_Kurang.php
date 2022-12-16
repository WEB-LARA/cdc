<?php
  class Trx_Kurang extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('input/Mod_cdc_trx_detail_kurang');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    function addData(){
      $tbl = "cdc_trx_detail_minus";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      if ($this->Mod_cdc_trx_detail_kurang->addData($id,$data) > 0) {
        echo "Data berhasil ditambahkan !";
      }else{
        echo "Data gagal ditambahkan, mohon untuk dicoba kembali.";
      }
    }

    function updateData(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_kurang->updateData($data);
      echo "Data berhasil diupdate !";
    }

    function getData($dataId){
      $result['data'] = $this->Mod_cdc_trx_detail_kurang->getData($dataId);
      echo json_encode($result['data']);
    }

    function getDataDetail($dataId){
      $result= $this->Mod_cdc_trx_detail_kurang->getDataDetail($dataId);
      echo json_encode($result);
    }

    function getTotal($dataId){
      $total = $this->Mod_cdc_trx_detail_kurang->getTotal($dataId);
      echo $total;
    }

    function deleteData(){
      $data = $this->input->post();
      $this->Mod_cdc_trx_detail_kurang->deleteData($data);
      echo "Delete berhasil !";
    }

    function get_master_pengurang()
    {
      $result = $this->Mod_cdc_trx_detail_kurang->get_master_pengurang();
      echo json_encode($result);
    }

    function save_data_pengurang()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id = $this->input->post('rec_id');
        $min_id = $this->input->post('min_id');
        $min_date = $this->input->post('min_date');
        $min_desc = $this->input->post('min_desc');
        $min_amount = $this->input->post('min_amount');
        $tbl = "cdc_trx_detail_minus";
        $id  = $this->Mod_cdc_seq_table->getID($tbl);
        if ($min_desc == '') {
          $mas_min_desc = $this->Mod_cdc_trx_detail_kurang->get_desc_master_min($min_id);
          $store_code = $this->Mod_cdc_trx_detail_kurang->get_store_by_rec_id($rec_id);
          $min_desc = $mas_min_desc->TRX_MINUS_DESC.' '.$store_code->STORE_CODE.' '.str_replace('-', '/', $min_date);
        }
        $result = $this->Mod_cdc_trx_detail_kurang->save_data_pengurang($id, $rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id);
        echo $result;
      }
    }

    function get_rec_pengurang()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
        $min_id = $this->input->post('min_id');
        $result = $this->Mod_cdc_trx_detail_kurang->get_rec_pengurang($rec_id, $min_id);
        echo json_encode($result);
      }
    }

    function delete_data_pengurang()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('min_det_id');
        $result = $this->Mod_cdc_trx_detail_kurang->delete_data_pengurang($det_id);
        echo $result;
      }
    }

    //function pengurang shift
    function save_data_pengurang_shift()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id = $this->input->post('rec_id');
        $real_id = $this->input->post('real_id');
        $min_id = $this->input->post('min_id');
        $no_shift = $this->input->post('no_shift');
        //$m_date = $this->input->post('min_date');
        //$min_date = date("Y-m-d", strtotime($m_date));
        $min_date = $this->input->post('min_date');
        $min_desc = $this->input->post('min_desc');
        $min_amount = $this->input->post('min_amount');
        $batch = $this->input->post('batch_id');
        //$tbl = "cdc_trx_detail_minus";
        //$id  = $this->Mod_cdc_seq_table->getID($tbl);
        if ($min_desc == '') {
          $mas_min_desc = $this->Mod_cdc_trx_detail_kurang->get_desc_master_min($min_id);
          $store_code = $this->Mod_cdc_trx_detail_kurang->get_store_by_rec_id($rec_id);
          $min_desc = $mas_min_desc->TRX_MINUS_DESC.' '.$store_code->STORE_CODE.' '.str_replace('-', '/', $min_date);
        }
        $result = $this->Mod_cdc_trx_detail_kurang->save_data_pengurang_shift($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id,$no_shift,$batch,$real_id);
        echo $result;
      }
    }


      function save_data_pengurang_shift_denom()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id = $this->input->post('rec_id');
        $real_id = $this->input->post('real_id');
        $min_id = $this->input->post('min_id');
        $no_shift = $this->input->post('no_shift');
        //$m_date = $this->input->post('min_date');
        //$min_date = date("Y-m-d", strtotime($m_date));
        $min_date = $this->input->post('min_date');
        $min_desc = $this->input->post('min_desc');
        $min_amount = $this->input->post('min_amount');
        $batch = $this->input->post('batch_id');
        //$tbl = "cdc_trx_detail_minus";
        //$id  = $this->Mod_cdc_seq_table->getID($tbl);
        if ($min_desc == '') {
          $mas_min_desc = $this->Mod_cdc_trx_detail_kurang->get_desc_master_min($min_id);
          $store_code = $this->Mod_cdc_trx_detail_kurang->get_store_by_rec_id($rec_id);
          $min_desc = $mas_min_desc->TRX_MINUS_DESC.' '.$store_code->STORE_CODE.' '.str_replace('-', '/', $min_date);
        }
        $result = $this->Mod_cdc_trx_detail_kurang->save_data_pengurang_shift_denom($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id,$no_shift,$batch,$real_id);
        echo $result;
      }
    }

    function get_rec_pengurang_shift()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
        $min_id = $this->input->post('min_id');

        if($rec_id){
           $result = $this->Mod_cdc_trx_detail_kurang->get_rec_pengurang_shift($rec_id, $min_id);
           echo json_encode($result);
        }
       
      }
    }

    function get_rec_pengurang_shift2()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
        $min_id = $this->input->post('min_id');
        $result = $this->Mod_cdc_trx_detail_kurang->get_rec_pengurang_shift2($rec_id, $min_id);
        echo json_encode($result);
      }
    }

    /*function delete_data_pengurang_shift()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('min_det_id');
        $result = $this->Mod_cdc_trx_detail_kurang->delete_data_pengurang_shift($det_id);
        echo $result;
      }
    }*/

     function delete_data_pengurang_shift()
    {
      if ($this->input->post()) {
        $det_id = $this->input->post('min_det_id');
        $rec_id = $this->input->post('rec_id');
        $batch = $this->input->post('batch_id');
        $min_id = $this->input->post('min_id');
        $no_shift = $this->input->post('no_shift');
        $result = $this->Mod_cdc_trx_detail_kurang->delete_data_pengurang_shift($det_id,$rec_id,$batch,$min_id,$no_shift);
        echo $result;
      }
    }

  }
?>
