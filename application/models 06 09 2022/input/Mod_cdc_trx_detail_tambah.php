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
      $statement = 'SELECT "TRX_PLUS_ID", BTRIM("TRX_PLUS_DESC") "TRX_PLUS_DESC" FROM cdc_master_detail_penambah WHERE "ACTIVE_FLAG" = \'Y\' AND "TRX_PLUS_NUM" IS NULL';
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

    function get_rec_id($id){
      $stmt = 'SELECT "CDC_REC_ID" FROM cdc_trx_receipts_shift WHERE "CDC_SHIFT_REC_ID" = ?';
      return $this->db->query($stmt,array($id))->row();
    }

    function cek_rec_plus($id,$plus_id){
      $stmt = 'SELECT * FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      return $this->db->query($stmt,array($id,$plus_id))->row();
    }

    function get_amount_shift($id,$plus_id){
      $stmt = 'SELECT SUM("TRX_DET_AMOUNT") as "AMOUNT" FROM cdc_trx_detail_tambah_shift WHERE "CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      return $this->db->query($stmt,array($id,$plus_id))->row();
    }

    //function penambah shift
    /*function save_data_penambah_shift( $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount, $no_shift,$batch,$real_id)
    {
      if ($det_id == 'X') {
        $desc = $plus_desc.' Shift '.$no_shift;

        $statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
         VALUES(?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
        $this->db->query($statement, array($rec_id, $plus_id, $plus_date, $desc, $plus_amount, $no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
        return $this->db->affected_rows();
      } else {
        $statement = 'UPDATE cdc_trx_detail_tambah_shift SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_SHIFT_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));
        return $this->db->affected_rows();
      }
    }*/

    function cek_peng_plus($id_shift,$no_shift){
      $stmt = 'SELECT COUNT(*) AS "COUNT" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($stmt,array($id_shift,$no_shift))->row();   
    }

    function cek_data_plus($plus_id,$real_id,$rec_id,$no_shift){
      $stmt = 'SELECT * FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ? AND "CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($stmt,array($rec_id,$plus_id,$real_id,$no_shift))->row();
    }

     //function penambah shift



      function save_data_penambah_shift_denom( $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount, $no_shift,$batch,$real_id)
    {

      $statement_tipe_shift='SELECT "SHIFT_CODE" FROM cdc_shift_desc WHERE "SHIFT_DESC"=?';
      $res_tipe_shift=$this->db->query($statement_tipe_shift,$no_shift)->row();
      $no_shift=$res_tipe_shift->SHIFT_CODE;

      if ($det_id == 'X') {
        $desc = $plus_desc.' Shift '.$no_shift;

        if($batch != ''){
          $rec = $this->get_rec_id($rec_id);
          $cek_rec = $this->cek_rec_plus($rec->CDC_REC_ID,$plus_id);
          $plus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

            if(!$cek_rec){
              if($no_shift == 'H'){
                $id_tmb = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_tambah');

                $statement = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($statement,array($id_tmb,$rec->CDC_REC_ID,$plus_id,$plus_date,$plus_desc,$plus_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
              }
              else{
                  $id_tmb = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_tambah');

                 $statement = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                 $this->db->query($statement,array($id_tmb,$rec->CDC_REC_ID,$plus_id,$plus_date,$plus_desc,$plus_amount_shift->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
              }
            }
            else{
              if($no_shift != 'H'){
                 $plus_amount_shift2 = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

                 $stmt = 'UPDATE cdc_trx_detail_tambah SET "TRX_DET_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';

                 $this->db->query($stmt,array($plus_amount_shift2->AMOUNT,$rec->CDC_REC_ID,$plus_id));
              }
            }
            
          }

        $cek_peng_plus = $this->cek_peng_plus($rec_id,$no_shift);
      //  echo " check point =".$rec_id."  ".$no_shift."  ".$cek_peng_plus;
        if(($cek_peng_plus->COUNT > 0 && $no_shift != 'H') || $no_shift == 'H'){

          $cek_row = $this->cek_data_plus($plus_id,$real_id,$rec_id,$no_shift);
            if(!$cek_row){
              $statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
               VALUES(?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
              $this->db->query($statement, array($rec_id, $plus_id, $plus_date, $desc, $plus_amount, $no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
              return $this->db->affected_rows();
            }
        }
      } else {

        $statement = 'UPDATE cdc_trx_detail_tambah_shift SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_SHIFT_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));

        $result = $this->db->affected_rows();

        $rec = $this->get_rec_id($rec_id);
        $cek_rec = $this->cek_rec_plus($rec->CDC_REC_ID,$plus_id);
        $plus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

        if($cek_rec){
          if($no_shift == 'H'){
               $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $rec->CDC_REC_ID,$plus_id));
          }
          else{
              $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount_shift->AMOUNT, $this->session->userdata('usrId'), $rec->CDC_REC_ID,$plus_id));
          }
        }else{
            $statement = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($statement,array($id_tmb,$rec->CDC_REC_ID,$plus_id,$plus_date,$plus_desc,$plus_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
                        $result = $this->db->affected_rows();

        }
        return $result;

      }
    }

    function save_data_penambah_shift( $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount, $no_shift,$batch,$real_id)
    {
      if ($det_id == 'X') {
        $desc = $plus_desc.' Shift '.$no_shift;

        if($batch != ''){
          $rec = $this->get_rec_id($rec_id);
          $cek_rec = $this->cek_rec_plus($rec->CDC_REC_ID,$plus_id);
          $plus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

            if(!$cek_rec){
              if($no_shift == 'H'){
                $id_tmb = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_tambah');

                $statement = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($statement,array($id_tmb,$rec->CDC_REC_ID,$plus_id,$plus_date,$plus_desc,$plus_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
              }
              else{
                  $id_tmb = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_tambah');

                 $statement = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                 $this->db->query($statement,array($id_tmb,$rec->CDC_REC_ID,$plus_id,$plus_date,$plus_desc,$plus_amount_shift->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
              }
            }
            else{
              if($no_shift != 'H'){
                 $plus_amount_shift2 = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

                 $stmt = 'UPDATE cdc_trx_detail_tambah SET "TRX_DET_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';

                 $this->db->query($stmt,array($plus_amount_shift2->AMOUNT,$rec->CDC_REC_ID,$plus_id));
              }
            }
            
          }

        $cek_peng_plus = $this->cek_peng_plus($rec_id,$no_shift);

        if(($cek_peng_plus->COUNT > 0 && $no_shift != 'H') || $no_shift == 'H'){

          $cek_row = $this->cek_data_plus($plus_id,$real_id,$rec_id,$no_shift);
            if(!$cek_row){
              $statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
               VALUES(?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
              $this->db->query($statement, array($rec_id, $plus_id, $plus_date, $desc, $plus_amount, $no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
              return $this->db->affected_rows();
            }
        }
      } else {

        $statement = 'UPDATE cdc_trx_detail_tambah_shift SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL_SHIFT_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $det_id));

        $result = $this->db->affected_rows();

        $rec = $this->get_rec_id($rec_id);
        $cek_rec = $this->cek_rec_plus($rec->CDC_REC_ID,$plus_id);
        $plus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$plus_id);

        if($cek_rec){
          if($no_shift == 'H'){
               $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $this->session->userdata('usrId'), $rec->CDC_REC_ID,$plus_id));
          }
          else{
              $statement = 'UPDATE cdc_trx_detail_tambah SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount_shift->AMOUNT, $this->session->userdata('usrId'), $rec->CDC_REC_ID,$plus_id));
          }
        }
        return $result;

      }
    }

    function save_data_penambah_shift2( $det_id, $rec_id, $plus_id, $plus_date, $plus_desc, $plus_amount, $plus_amount2, $plus_amount3, $no_shift)
    {
      if ($det_id == 'X') {
        $desc = $plus_desc.' Shift '.$no_shift;

        $statement = 'INSERT INTO cdc_trx_detail_tambah_shift2("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","TRX_DET_AMOUNT2","TRX_DET_AMOUNT3","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE")
         VALUES(?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
        $this->db->query($statement, array($rec_id, $plus_id, $plus_date, $desc, $plus_amount, $plus_amount2, $plus_amount3, $no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
        return $this->db->affected_rows();
      } else {
        $statement = 'UPDATE cdc_trx_detail_tambah_shift2 SET "TRX_DETAIL_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_DETAIL_DESC" = ?, "TRX_DET_AMOUNT" = ?, "TRX_DET_AMOUNT2" = ?, "TRX_DET_AMOUNT3" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_DATE WHERE "TRX_DETAIL2_SHIFT_ID" = ?';
        $this->db->query($statement, array($plus_date, $plus_desc, $plus_amount, $plus_amount2, $plus_amount3, $this->session->userdata('usrId'), $det_id));
        return $this->db->affected_rows();
      }
    }

    /* function delete_data_penambah_shift($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_tambah_shift WHERE "TRX_DETAIL_SHIFT_ID" = ?';
      $this->db->query($statement, array($det_id));
      return $this->db->affected_rows();
    }*/

     function delete_data_penambah_shift($det_id,$rec_id,$batch,$plus_id,$no_shift)
    {

      $statement = 'DELETE FROM cdc_trx_detail_tambah_shift WHERE "TRX_DETAIL_SHIFT_ID" = ?';
      $this->db->query($statement, array($det_id));
      $result = $this->db->affected_rows();

      if($batch != ''){
         $cek_rec = $this->cek_rec_plus($rec_id,$plus_id);

         if($cek_rec){
           if($no_shift == 'H'){
               $stmt = 'DELETE FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
               $this->db->query($stmt,array($rec_id,$plus_id));
           }
           else{
              $amount = $this->get_amount_shift($rec_id,$plus_id);

              if($amount->AMOUNT > 0){
                $stmt = 'UPDATE cdc_trx_detail_tambah SET "TRX_DET_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                $this->db->query($stmt,array($amount->AMOUNT,$rec_id,$plus_id));
              }
              else{
                  $stmt = 'DELETE FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
                  $this->db->query($stmt,array($rec_id,$plus_id));
              }

           }
         }

      }
      return $result;
    }

     function delete_data_penambah_shift2($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_tambah_shift2 WHERE "TRX_DETAIL2_SHIFT_ID" = ?';
      $this->db->query($statement, array($det_id));
      return $this->db->affected_rows();
    }

    function get_rec_penambah_shift($rec_id, $plus_id)
    {
      $statement = 'SELECT "TRX_DETAIL_SHIFT_ID", "TRX_CDC_REC_ID", "TRX_PLUS_ID", TO_CHAR("TRX_DETAIL_DATE", \'DD-MM-YYYY\') "TRX_DETAIL_DATE", "TRX_DETAIL_DESC", "TRX_DET_AMOUNT","NO_SHIFT" FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $plus_id))->result();
    }

    function get_rec_penambah_shift2($rec_id, $plus_id)
    {
      $statement = 'SELECT "TRX_DETAIL2_SHIFT_ID", "TRX_CDC_REC_ID", "TRX_PLUS_ID", TO_CHAR("TRX_DETAIL_DATE", \'DD-MM-YYYY\') "TRX_DETAIL_DATE", "TRX_DETAIL_DESC", "TRX_DET_AMOUNT", "TRX_DET_AMOUNT2", "TRX_DET_AMOUNT3","NO_SHIFT" FROM cdc_trx_detail_tambah_shift2 WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $plus_id))->result();
    }

  }

?>
