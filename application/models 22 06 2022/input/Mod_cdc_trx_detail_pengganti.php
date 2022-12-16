<?php
  class Mod_cdc_trx_detail_pengganti extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function get_rec_pengganti($rec_id, $shift)
    {
      $statement = 'SELECT "TRX_DETAIL_PENG_ID", "TRX_CDC_REC_ID", "NO_SHIFT", "TRX_PENG_AMOUNT" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($statement, array($rec_id, $shift))->result();
    }

    function get_rec_pengganti2($rec_id, $shift)
    {
      $statement = 'SELECT "TRX_DETAIL_PENG_ID", "TRX_CDC_REC_ID", "NO_SHIFT", "TRX_PENG_AMOUNT", "TRX_PENG_AMOUNT2", "TRX_PENG_AMOUNT3" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($statement, array($rec_id, $shift))->result();
    }

    function save_data_pengganti($det_id,$rec_id,$no_shift, $peng_amount)
    {
      
        if($det_id == 'X'){
           $statement = 'INSERT INTO cdc_trx_detail_pengganti ("TRX_CDC_REC_ID","NO_SHIFT","TRX_PENG_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES(?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
            $this->db->query($statement, array($rec_id, $no_shift, $peng_amount, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
            return $this->db->affected_rows();
        }
        else{
          $statement = 'UPDATE cdc_trx_detail_pengganti SET "TRX_PENG_AMOUNT" = ? WHERE "TRX_DETAIL_PENG_ID" = ?';

          $this->db->query($statement,array($peng_amount,$det_id));
          return $this->db->affected_rows();
        }
       
      /*} else {
        $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));
        return $this->db->affected_rows();
      }*/
    }

    function save_data_pengganti2($det_id,$rec_id,$no_shift, $peng_amount,$peng_amount2,$peng_amount3)
    {
      
        if($det_id == 'X'){
           $statement = 'INSERT INTO cdc_trx_detail_pengganti2 ("TRX_CDC_REC_ID","NO_SHIFT","TRX_PENG_AMOUNT","TRX_PENG_AMOUNT2","TRX_PENG_AMOUNT3","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES(?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
            $this->db->query($statement, array($rec_id, $no_shift, $peng_amount, $peng_amount2, $peng_amount3, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
            return $this->db->affected_rows();
        }
        else{
          $statement = 'UPDATE cdc_trx_detail_pengganti2 SET "TRX_PENG_AMOUNT" = ?,"TRX_PENG_AMOUNT2" = ?,"TRX_PENG_AMOUNT3" = ? WHERE "TRX_DETAIL2_PENG_ID" = ?';

          $this->db->query($statement,array($peng_amount,$peng_amount2,$peng_amount3,$det_id));
          return $this->db->affected_rows();
        }
       
      /*} else {
        $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));
        return $this->db->affected_rows();
      }*/
    }

    function delete_data_pengganti($del_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_pengganti WHERE "TRX_DETAIL_PENG_ID" = ?';
      $this->db->query($statement, array($det_id));
      return $this->db->affected_rows();
    }

     function delete_data_pengganti2($del_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_pengganti2 WHERE "TRX_DETAIL2_PENG_ID" = ?';
      $this->db->query($statement, array($det_id));
      return $this->db->affected_rows();
    }


}
?>