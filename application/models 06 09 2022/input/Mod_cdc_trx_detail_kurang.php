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
      $statement = 'SELECT "TRX_MINUS_ID", BTRIM("TRX_MINUS_DESC") "TRX_MINUS_DESC" FROM cdc_master_detail_pengurang WHERE "ACTIVE_FLAG" = \'Y\' AND "TRX_MINUS_NUM" IS NULL';
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

    function save_data_pengurang($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id)
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
      $statement = 'SELECT "TRX_DETAIL_MINUS_SHIFT_ID", "TRX_CDC_REC_ID", "TRX_MINUS_ID", "TRX_MINUS_DATE", "TRX_MINUS_DESC", "TRX_MINUS_AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $min_id))->result();
    }

    function delete_data_pengurang($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_minus WHERE "TRX_DETAIL_MINUS_ID" = ?';
      $this->db->query($statement, $det_id);
      return $this->db->affected_rows();
    }

    function get_rec_id($id){
      $stmt = 'SELECT "CDC_REC_ID" FROM cdc_trx_receipts_shift WHERE "CDC_SHIFT_REC_ID" = ?';
      return $this->db->query($stmt,array($id))->row();
    }

    function cek_rec_minus($id,$minus_id){
      $stmt = 'SELECT * FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($stmt,array($id,$minus_id))->row();
    }

    function get_amount_shift($id,$minus_id){
      $stmt = 'SELECT SUM("TRX_MINUS_AMOUNT") as "AMOUNT" FROM cdc_trx_detail_minus_shift WHERE "CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($stmt,array($id,$minus_id))->row();
    }

    //function pengurang shift
    /* function save_data_pengurang_shift($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id,$no_shift,$batch,$real_id)
    {
      if ($det_id == 'X') {
        $statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
         VALUES (?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
        $this->db->query($statement, array($rec_id, $min_id, $min_date, $min_desc, $min_amount,$no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
      } else {
        $statement = 'UPDATE cdc_trx_detail_minus_shift SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_DETAIL_MINUS_SHIFT_ID" = ?';
        $this->db->query($statement, array($min_date, $min_desc, $min_amount, $det_id));
      }
      return $this->db->affected_rows();
    }*/

    function cek_peng_min($id_shift,$no_shift){
      $stmt = 'SELECT COUNT(*) AS "COUNT" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($stmt,array($id_shift,$no_shift))->row();   
    }

    function cek_data_minus($min_id,$real_id,$rec_id,$no_shift){
      $stmt = 'SELECT * FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ? AND "CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($stmt,array($rec_id,$min_id,$real_id,$no_shift))->row();
    }

     //function pengurang shift
     function save_data_pengurang_shift_denom($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id,$no_shift,$batch,$real_id)
    {

      $statement_tipe_shift='SELECT "SHIFT_CODE" FROM cdc_shift_desc WHERE "SHIFT_DESC"=?';
      $res_tipe_shift=$this->db->query($statement_tipe_shift,$no_shift)->row();
      $no_shift=$res_tipe_shift->SHIFT_CODE;
      if ($det_id == 'X') {


        /*$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
         VALUES (?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
        $this->db->query($statement, array($rec_id, $min_id, $min_date, $min_desc, $min_amount,$no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));*/

        $cek_peng_min = $this->cek_peng_min($rec_id,$no_shift);
        if(($cek_peng_min->COUNT > 0 && $no_shift != 'H') || $no_shift == 'H'){

         $cek_row = $this->cek_data_minus($min_id,$real_id,$rec_id,$no_shift);

           if(!$cek_row){
             $statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
             VALUES (?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
             $this->db->query($statement, array($rec_id, $min_id, $min_date, $min_desc, $min_amount,$no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
           }
        }

        $result = $this->db->affected_rows();

        if($batch != ''){
         $rec = $this->get_rec_id($rec_id);
         $cek_rec = $this->cek_rec_minus($rec->CDC_REC_ID,$min_id);
         $minus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

         if(!$cek_rec){
             if($no_shift == 'H'){
              $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

              $statement = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

              $this->db->query($statement,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
            else{
                $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

               $statement = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($statement,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
         }
          else{
              if($no_shift != 'H'){
                 $minus_amount_shift2 = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

                 $stmt = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';

                 $this->db->query($stmt,array($minus_amount_shift2->AMOUNT,$rec->CDC_REC_ID,$min_id));
              }
            }
        }

        
         return $result;
      } 
      else {
        $statement = 'UPDATE cdc_trx_detail_minus_shift SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_DETAIL_MINUS_SHIFT_ID" = ?';
        $this->db->query($statement, array($min_date, $min_desc, $min_amount, $det_id));
         $result = $this->db->affected_rows();


        $rec = $this->get_rec_id($rec_id);
        $cek_rec = $this->cek_rec_minus($rec->CDC_REC_ID,$min_id);
        $minus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

        if($cek_rec){
          if($no_shift == 'H'){
               $statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                $this->db->query($statement, array($min_date, $min_desc, $min_amount, $rec->CDC_REC_ID,$min_id));
          }
          else{
               $statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                $this->db->query($statement, array($min_date, $min_desc, $minus_amount_shift->AMOUNT, $rec->CDC_REC_ID,$min_id));
          }
        }
        else{
          if($batch != ''){
              if($no_shift == 'H'){
              $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

              $stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($stmt_minus,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
            else{
                $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

               $stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                 $this->db->query($stmt_minus,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$minus_amount_shift->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
          }
        }
        return $result;   
      }
     
    }


   

    function save_data_pengurang_shift($rec_id, $min_id, $min_date, $min_desc, $min_amount, $det_id,$no_shift,$batch,$real_id)
    {
      if ($det_id == 'X') {


        /*$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
         VALUES (?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
        $this->db->query($statement, array($rec_id, $min_id, $min_date, $min_desc, $min_amount,$no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));*/

        $cek_peng_min = $this->cek_peng_min($rec_id,$no_shift);
        if(($cek_peng_min->COUNT > 0 && $no_shift != 'H') || $no_shift == 'H'){

         $cek_row = $this->cek_data_minus($min_id,$real_id,$rec_id,$no_shift);

           if(!$cek_row){
             $statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
             VALUES (?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
             $this->db->query($statement, array($rec_id, $min_id, $min_date, $min_desc, $min_amount,$no_shift, $this->session->userdata('usrId'), $this->session->userdata('usrId'),$real_id));
           }
        }

        $result = $this->db->affected_rows();

        if($batch != ''){
         $rec = $this->get_rec_id($rec_id);
         $cek_rec = $this->cek_rec_minus($rec->CDC_REC_ID,$min_id);
         $minus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

         if(!$cek_rec){
             if($no_shift == 'H'){
              $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

              $statement = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

              $this->db->query($statement,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
            else{
                $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

               $statement = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,TO_DATE(?, \'DD-MM-YYYY\'),?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($statement,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
         }
          else{
              if($no_shift != 'H'){
                 $minus_amount_shift2 = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

                 $stmt = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';

                 $this->db->query($stmt,array($minus_amount_shift2->AMOUNT,$rec->CDC_REC_ID,$min_id));
              }
            }
        }

        
         return $result;
      } 
      else {
        $statement = 'UPDATE cdc_trx_detail_minus_shift SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_DETAIL_MINUS_SHIFT_ID" = ?';
        $this->db->query($statement, array($min_date, $min_desc, $min_amount, $det_id));
         $result = $this->db->affected_rows();


        $rec = $this->get_rec_id($rec_id);
        $cek_rec = $this->cek_rec_minus($rec->CDC_REC_ID,$min_id);
        $minus_amount_shift = $this->get_amount_shift($rec->CDC_REC_ID,$min_id);

        if($cek_rec){
          if($no_shift == 'H'){
               $statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                $this->db->query($statement, array($min_date, $min_desc, $min_amount, $rec->CDC_REC_ID,$min_id));
          }
          else{
               $statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DATE" = TO_DATE(?, \'DD-MM-YYYY\'), "TRX_MINUS_DESC" = ?, "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                $this->db->query($statement, array($min_date, $min_desc, $minus_amount_shift->AMOUNT, $rec->CDC_REC_ID,$min_id));
          }
        }
        else{
          if($batch != ''){
              if($no_shift == 'H'){
              $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

              $stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                $this->db->query($stmt_minus,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$min_amount->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
            else{
                $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

               $stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_TIMESTAMP)';

                 $this->db->query($stmt_minus,array($id_minus,$rec->CDC_REC_ID,$min_id,$min_date,$min_desc,$minus_amount_shift->AMOUNT,$this->session->userdata('usrId'),$this->session->userdata('usrId')));
            }
          }
        }
        return $result;   
      }
     
    }

    function get_rec_pengurang_shift($rec_id, $min_id)
    {
      $statement = 'SELECT "TRX_DETAIL_MINUS_SHIFT_ID", "TRX_CDC_REC_ID", "TRX_MINUS_ID", "TRX_MINUS_DATE", "TRX_MINUS_DESC", "TRX_MINUS_AMOUNT","NO_SHIFT" FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $min_id))->result();
    }

    function get_rec_pengurang_shift2($rec_id, $min_id)
    {
      $statement = 'SELECT "TRX_DETAIL_MINUS_SHIFT_ID", "TRX_CDC_REC_ID", "TRX_MINUS_ID", "TRX_MINUS_DATE", "TRX_MINUS_DESC", "TRX_MINUS_AMOUNT", "TRX_MINUS_AMOUNT2", "TRX_MINUS_AMOUNT3","NO_SHIFT" FROM cdc_trx_detail_minus_shift2 WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      return $this->db->query($statement, array($rec_id, $min_id))->result();
    }

   /* function delete_data_pengurang_shift($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_minus_shift WHERE "TRX_DETAIL_MINUS_SHIFT_ID" = ?';
      $this->db->query($statement, $det_id);
      return $this->db->affected_rows();
    }*/

     function delete_data_pengurang_shift($det_id,$rec_id,$batch,$minus_id,$no_shift)
    {
      $statement = 'DELETE FROM cdc_trx_detail_minus_shift WHERE "TRX_DETAIL_MINUS_SHIFT_ID" = ?';
      $this->db->query($statement, $det_id);
      $result = $this->db->affected_rows();

      if($batch != ''){
         $cek_rec = $this->cek_rec_minus($rec_id,$minus_id);

         if($cek_rec){
           if($no_shift == 'H'){
               $stmt = 'DELETE FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
               $this->db->query($stmt,array($rec_id,$minus_id));
           }
           else{
              $amount = $this->get_amount_shift($rec_id,$minus_id);

              if($amount->AMOUNT > 0){
                $stmt = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_AMOUNT" = ? WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                $this->db->query($stmt,array($amount->AMOUNT,$rec_id,$minus_id));
              }
              else{
                  $stmt = 'DELETE FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
                  $this->db->query($stmt,array($rec_id,$minus_id));
              }

           }
         }

      }
      return $result;
    }

     function delete_data_pengurang_shift2($det_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_minus_shift2 WHERE "TRX_DETAIL2_MINUS_SHIFT_ID" = ?';
      $this->db->query($statement, $det_id);
      return $this->db->affected_rows();
    }

  }

?>
