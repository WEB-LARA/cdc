<?php
  class Mod_cdc_trx_detail_kurang extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function addData($id,$data){
      $createBy   = $this->session->userdata('usrId');
      $create = date("Y-m-d");
      $minId = $this->getMinID($data['name']);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $statement = 'INSERT INTO CDC_TRX_DETAIL_MINUS VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
      $this->db->query($statement,array(intval($id),intval($data['receiptID']),intval($minId),$trx_date,$data['desc'],intval($data['amount']),intval($createBy),intval($createBy)));
      return $this->db->affected_rows();
      /*$data = array('TRX_DETAIL_MINUS_ID'=>$id, 'TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_MINUS_ID'=>$minId, 'TRX_MINUS_DATE'=>$trx_date,
              'TRX_MINUS_DESC'=>$data['desc'], 'TRX_MINUS_AMOUNT'=>$data['amount'], 'CREATED_BY'=>$createBy, 'CREATION_DATE'=>$create );
      //var_dump($data);
      $this->db->insert('cdc_trx_detail_minus',$data);*/
    }

    function getMinID($name){
      $data=$this->db->query(' SELECT "TRX_MINUS_ID" FROM cdc_master_detail_pengurang WHERE "TRX_MINUS_NAME"= \''.trim($name).'\' ');
      return $data->row()->TRX_MINUS_ID;
    }

    function getData($dataId){
      $data = $this->db->query(' SELECT b."TRX_DETAIL_MINUS_ID", a."TRX_MINUS_NAME" AS "TRX_MINUS_NAME", b."TRX_MINUS_DATE" AS "TRX_MINUS_DATE", b."TRX_MINUS_DESC" AS "TRX_MINUS_DESC", b."TRX_MINUS_AMOUNT" AS "TRX_MINUS_AMOUNT" FROM cdc_master_detail_pengurang AS a INNER JOIN cdc_trx_detail_minus AS b USING ("TRX_MINUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ORDER BY b."TRX_DETAIL_MINUS_ID" DESC ');
      $result['rows']=$data->result();
      return($result);
    }

    function getTotal($dataId){
      $total = $this->db->query(' SELECT SUM("TRX_MINUS_AMOUNT") AS "TOTAL" FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ');
      $data  = $total->row()->TOTAL;
      return $data;
    }

    function getDataDetail($dataId){
      $data = $this->db->query(' SELECT b."TRX_DETAIL_MINUS_ID", a."TRX_MINUS_NAME" AS "TRX_MINUS_NAME", b."TRX_MINUS_DATE" AS "TRX_MINUS_DATE", b."TRX_MINUS_DESC" AS "TRX_MINUS_DESC", b."TRX_MINUS_AMOUNT" AS "TRX_MINUS_AMOUNT" FROM cdc_master_detail_pengurang AS a INNER JOIN cdc_trx_detail_minus AS b USING ("TRX_MINUS_ID") WHERE b."TRX_DETAIL_MINUS_ID" = \''.$dataId.'\' ');
      $result=$data->row();
      return $result;
    }

    function updateData($data){
      $updateBy   = $this->session->userdata('usrId');
      $updateDate = date("Y-m-d");
      $minId = $this->getMinID($data['name']);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $update = array('TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_MINUS_ID'=>$minId, 'TRX_MINUS_DATE'=>$trx_date,
              'TRX_MINUS_DESC'=>$data['desc'], 'TRX_MINUS_AMOUNT'=>$data['amount'], 'LAST_UPDATE_BY'=>$updateBy, 'LAST_UPDATE_DATE'=>$updateDate );
      $this->db->where('TRX_DETAIL_MINUS_ID',$data['pengurangID']);
      $this->db->update('cdc_trx_detail_minus',$update);
    }

    function deleteData($data){
      $this->db->where('TRX_DETAIL_MINUS_ID',$data['dataId']);
      $this->db->delete('cdc_trx_detail_minus');
    }

    function get_master_pengurang()
    {
      $statement = 'SELECT "TRX_MINUS_ID", BTRIM("TRX_MINUS_DESC") "TRX_MINUS_DESC" FROM cdc_master_detail_pengurang WHERE "ACTIVE_FLAG" = \'N\' AND "TRX_MINUS_NUM" IS NULL';
      return $this->db->query($statement)->result();
    }

    function get_desc_master_min($min_id)
    {
      $statement = 'SELECT BTRIM("TRX_MINUS_DESC") "TRX_MINUS_DESC" FROM cdc_master_detail_pengurang WHERE "TRX_MINUS_ID" = ?';
      return $this->db->query($statement, array($min_id))->row();
    }

    function get_store_by_rec_id($rec_id)
    {
      $statement = 'SELECT * FROM cdc_master_toko WHERE "STORE_ID" IN (SELECT "STORE_ID" FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?)';
      return $this->db->query($statement, array($rec_id))->row();
    }

    function save_data_pengurang($id, $rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id)
    {
      if ($det_id == 'X') {
        $statement = 'INSERT INTO cdc_trx_detail_minus VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
        $this->db->query($statement, array($id, $rec_id, $min_id, $min_date, $min_desc, $min_amount, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
      } else {
        $statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_DETAIL_MINUS_ID" = ?';
        $this->db->query($statement, array($min_date, $min_desc, $min_amount, $det_id));
      }
      return $this->db->affected_rows();
    }

    function get_rec_pengurang($rec_id, $min_id)
    {
      $statement = 'SELECT "TRX_DETAIL_MINUS_ID", "TRX_CDC_REC_ID", "TRX_MINUS_ID", "TRX_MINUS_DATE", "TRX_MINUS_DESC", "TRX_MINUS_AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $min_id))->result();
    }

    function delete_data_pengurang($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_minus WHERE "TRX_DETAIL_MINUS_ID" = ?';
      $this->db->query($statement, $det_id);
      return $this->db->affected_rows();
    }

  }

?>
