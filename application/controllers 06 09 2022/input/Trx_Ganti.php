<?php
  class Trx_Ganti extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('input/Mod_cdc_trx_detail_pengganti');
      $this->load->model('master/Mod_cdc_seq_table');
    }

    /*function addData(){
      $tbl = "cdc_trx_detail_pengganti";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      if ($this->Mod_cdc_trx_detail_tambah->addData($id,$data) > 0) {
        echo "Data berhasil ditambahkan !";
      }else{
        echo "Data gagal ditambahkan, mohon untuk dicoba kembali.";
      }
    }*/

    /*function updateData(){
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
    }*/

    function get_master_pengganti($value = null)
    {
      $result = $this->Mod_cdc_trx_detail_pengganti->get_master_pengganti($value);
      echo json_encode($result);
    }

    function get_data_denom_sd7()
    {
      if ($this->input->post()) {
          $store = $this->input->post('store');
          $salesDate = $this->input->post('salesDate');
          $tipe_shift = $this->input->post('tipe_shift');

          $result = $this->Mod_cdc_trx_detail_pengganti->get_data_denom_sd7($store,$salesDate,$tipe_shift);
          echo json_encode($result);
         
      }
    }

    function get_rec_pengganti()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
      //  $peng_id = $this->input->post('peng_id');
        $shift=$this->input->post('shift');

        if($rec_id){
           $result = $this->Mod_cdc_trx_detail_pengganti->get_rec_pengganti($rec_id,$shift);
          echo json_encode($result);
        } 
      }
    }

    function get_rec_pengganti2()
    {
      if ($this->input->post()) {
        $rec_id = $this->input->post('rec_id');
        $peng_id = $this->input->post('peng_id');
        $result = $this->Mod_cdc_trx_detail_pengganti->get_rec_pengganti2($rec_id, $peng_id);
        echo json_encode($result);
      }
    }

    function save_data_input_denom()
    {
      if ($this->input->post()) {
        $det_id =  $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id =  $this->input->post('rec_id');
        $no_shift =  $this->input->post('no_shift');
        $qty_100000 =  $this->input->post('qty_100000') != '' ? $this->input->post('qty_100000') : 0;
        $qty_50000 =  $this->input->post('qty_50000') != '' ? $this->input->post('qty_50000') : 0;
        $qty_20000 =  $this->input->post('qty_20000') != '' ? $this->input->post('qty_20000') : 0;
        $qty_10000 =  $this->input->post('qty_10000') != '' ? $this->input->post('qty_10000') : 0;
        $qty_5000 =  $this->input->post('qty_5000') != '' ? $this->input->post('qty_5000') : 0;
        $qty_2000 =  $this->input->post('qty_2000') != '' ? $this->input->post('qty_2000') : 0;
        $qty_1000 =  $this->input->post('qty_1000') != '' ? $this->input->post('qty_1000') : 0;
        $qty_500 =  $this->input->post('qty_500') != '' ? $this->input->post('qty_500') : 0;
        $qty_200 =  $this->input->post('qty_200') != '' ? $this->input->post('qty_200') : 0;
        $qty_100 =  $this->input->post('qty_100') != '' ? $this->input->post('qty_100') : 0;
        $qty_50 =  $this->input->post('qty_50') != '' ? $this->input->post('qty_50') : 0;
        $qty_25 =  $this->input->post('qty_25') != '' ? $this->input->post('qty_25') : 0;

        $result = $this->Mod_cdc_trx_detail_pengganti->save_data_input_denom($det_id,$rec_id,$no_shift,$qty_100000,$qty_50000,$qty_20000,$qty_10000,$qty_5000,$qty_2000,$qty_1000,$qty_500,$qty_200,$qty_100,$qty_50,$qty_25);
        echo $result;
      }
    }

     function save_data_pengganti()
    {
      if ($this->input->post()) {
        $det_id =  $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id =  $this->input->post('rec_id');
        $no_shift =  $this->input->post('no_shift');
        $peng_amount = $this->input->post('peng_amount');
        $result = $this->Mod_cdc_trx_detail_pengganti->save_data_pengganti($det_id,$rec_id,$no_shift, $peng_amount);
        echo $result;
      }
    }

     function save_data_pengganti2()
    {
      if ($this->input->post()) {
        //$tbl = "cdc_trx_detail_tambah";
        //$id  = $this->Mod_cdc_seq_table->getID($tbl);
        $det_id =  $this->input->post('id') != '' ? $this->input->post('id') : 'X';
        $rec_id =  $this->input->post('rec_id');
        $no_shift =  $this->input->post('no_shift');
        $peng_amount = $this->input->post('peng_amount');
        $peng_amount2 = $this->input->post('peng_amount2');
        $peng_amount3 = $this->input->post('peng_amount3');
        $result = $this->Mod_cdc_trx_detail_pengganti->save_data_pengganti2($det_id,$rec_id,$no_shift, $peng_amount,$peng_amount2,$peng_amount3);
        echo $result;
      }
    }

    function delete_data_pengganti_denom()
    {
      if ($this->input->post()) {
        $del_id = $this->input->post('peng_del_id');
        $receipt_denom_id=$this->input->post('receipt_denom_id');
        $result = $this->Mod_cdc_trx_detail_pengganti->delete_data_pengganti_denom($del_id,$receipt_denom_id);
        echo $result;
      }
    }



    function delete_data_pengganti()
    {
      if ($this->input->post()) {
        $del_id = $this->input->post('peng_del_id');
        $result = $this->Mod_cdc_trx_detail_pengganti->delete_data_pengganti($del_id);
        echo $result;
      }
    }

  }
?>
