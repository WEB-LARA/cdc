<?php
  class Mod_cdc_trx_detail_tambah extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function addData($id,$data){
      $createBy   = $this->session->userdata('usrId');
      $create = date("Y-m-d");
      $plusId = $this->getPlusID($data['name']);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $statement = 'INSERT INTO CDC_TRX_DETAIL_TAMBAH VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
      $this->db->query($statement,array(intval($id),intval($data['receiptID']),intval($plusId),$trx_date,$data['desc'],intval($data['amount']),intval($createBy),intval($createBy)));
      return $this->db->affected_rows();
      /*$data = array('TRX_DETAIL_ID'=>$id, 'TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_PLUS_ID'=>$plusId, 'TRX_DETAIL_DATE'=>$trx_date,
              'TRX_DETAIL_DESC'=>$data['desc'], 'TRX_DET_AMOUNT'=>$data['amount'], 'CREATED_BY'=>$createBy, 'CREATION_DATE'=>$create );
      //var_dump($data);
      $this->db->insert('cdc_trx_detail_tambah',$data);*/
    }

    function updateData($data){
      $updateBy   = $this->session->userdata('usrId');
      $updateDate = date("Y-m-d");
      $plusId = $this->getPlusID($data['name']);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $update = array('TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_PLUS_ID'=>$plusId, 'TRX_DETAIL_DATE'=>$trx_date,
              'TRX_DETAIL_DESC'=>$data['desc'], 'TRX_DET_AMOUNT'=>$data['amount'], 'LAST_UPDATE_BY'=>$updateBy, 'LAST_UPDATE_DATE'=>$updateDate );
      $this->db->where('TRX_DETAIL_ID',$data['penambahID']);
      $this->db->update('cdc_trx_detail_tambah',$update);
    }

    function getPlusID($name){
      $data=$this->db->query(' SELECT "TRX_PLUS_ID" FROM cdc_master_detail_penambah WHERE "TRX_PLUS_NAME"= \''.$name.'\' ');
      return $data->row()->TRX_PLUS_ID;
    }

    function getData($dataId){
      $data = $this->db->query(' SELECT b."TRX_DETAIL_ID", a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", b."TRX_DETAIL_DESC" AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ORDER BY b."TRX_DETAIL_ID" DESC ');
      //$data = $this->db->query(' SELECT a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", trim(b."TRX_DETAIL_DESC") AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$id.'\' ');
      $result['rows']=$data->result();
      return($result);
    }

    function getDataDetail($dataId){
      $data = $this->db->query(' SELECT b."TRX_DETAIL_ID", a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", b."TRX_DETAIL_DESC" AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE b."TRX_DETAIL_ID" = \''.$dataId.'\' ');
      //$data = $this->db->query(' SELECT a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", trim(b."TRX_DETAIL_DESC") AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$id.'\' ');
      $result=$data->row();
      return $result;
    }

    function getTotal($dataId){
      $total = $this->db->query(' SELECT SUM("TRX_DET_AMOUNT") AS "TOTAL" FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ');
      $data  = $total->row()->TOTAL;
      return $data;
    }

    function deleteData($data){
      $this->db->where('TRX_DETAIL_ID',$data['dataId']);
      $this->db->delete('cdc_trx_detail_tambah');
    }

    function get_master_penambah($value = null)
    {
      $statement = 'SELECT "TRX_PLUS_ID", BTRIM("TRX_PLUS_DESC") "TRX_PLUS_DESC" FROM cdc_master_detail_penambah WHERE "ACTIVE_FLAG" = \'N\' AND "TRX_PLUS_NUM" IS NULL';
      if ($value) {
        $statement .= ' AND "TRX_PLUS_ID" = '.$value;
      }
      return $this->db->query($statement)->result();
    }

    function get_desc_master_plus($plus_id)
    {
      $statement = 'SELECT BTRIM("TRX_PLUS_DESC") "TRX_PLUS_DESC" FROM cdc_master_detail_penambah WHERE "TRX_PLUS_ID" = ?';
      return $this->db->query($statement, array($plus_id))->row();
    }

    function get_rec_penambah($rec_id, $plus_id)
    {
      $statement = 'SELECT "TRX_DETAIL_ID", "TRX_CDC_REC_ID", "TRX_PLUS_ID", TO_CHAR("TRX_DETAIL_DATE", \'DD-MM-YYYY\') "TRX_DETAIL_DATE", "TRX_DETAIL_DESC", "TRX_DET_AMOUNT" FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $plus_id))->result();
    }

    function get_store_by_rec_id($rec_id)
    {
      $statement = 'SELECT * FROM cdc_master_toko WHERE "STORE_ID" IN (SELECT "STORE_ID" FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?)';
      return $this->db->query($statement, array($rec_id))->row();
    }

    function save_data_penambah($id, $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount)
    {
      if ($det_id == 'X') {
        $statement = 'INSERT INTO cdc_trx_detail_tambah VALUES(?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
        $this->db->query($statement, array($id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
        return $this->db->affected_rows();
      } else {
        $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));
        return $this->db->affected_rows();
      }
    }

    function delete_data_penambah($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_tambah WHERE "TRX_DETAIL_ID" = ?';
      $this->db->query($statement, array($det_id));
      return $this->db->affected_rows();
    }

  }

?>
