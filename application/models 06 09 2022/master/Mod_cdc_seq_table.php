<?php
  class Mod_cdc_seq_table extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getID($tbl){
      date_default_timezone_set("Asia/Bangkok");

      $tgl = date("Y-m-d");
      $this->db->SELECT('SEQ_COUNTER');
      $this->db->WHERE('SEQ_TABLE',$tbl);
      $query= $this->db->get('cdc_seq_table');
      if($query->num_rows() > 0){
        $row = $query->row_array();
        //echo $row["SEQ_COUNTER"];
        $counter = $row["SEQ_COUNTER"] + 1;
        $data = array('SEQ_COUNTER'=>$counter,'SEQ_YEAR'=>date("Y"),'LAST_UPDATE_DATE'=>$tgl);
        $this->db->where('SEQ_TABLE',$tbl);
        $this->db->update('cdc_seq_table',$data);
      }
      /*else{
        //echo "kosong";
        $counter = 1;
        $data = array('SEQ_TABLE'=>$tbl,'SEQ_COUNTER'=>$counter,'SEQ_YEAR'=>date("Y"),'ACTIVE_DATE'=>$tgl,'CREATION_DATE'=>$tgl,'LAST_UPDATE_DATE'=>$tgl);
        $this->db->insert('cdc_seq_table',$data);
      }*/
      return $counter;
    }

    //function shift
    function getIDNShift(){
      $stmt = 'SELECT nextval(\'"cdc_trx_receipts_shift_CDC_SHIFT_REC_ID_seq"\') as "ID"';
      return $this->db->query($stmt)->row()->ID;
    }

    function getIDN(){
      $stmt = 'SELECT nextval(\'"cdc_trx_receipts_id_seq"\') as "ID"';
      return $this->db->query($stmt)->row()->ID;
    }

  }
 ?>
