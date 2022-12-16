<?php
  class Mod_cdc_trx_detail_pengganti extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function get_rec_pengganti($rec_id, $shift)
    {
      $statement_tipe_shift='SELECT "SHIFT_CODE" FROM cdc_shift_desc WHERE "SHIFT_DESC"=?';
      $res_tipe_shift=$this->db->query($statement_tipe_shift,$shift)->row();
      
      $statement = 'SELECT ctp."TRX_DETAIL_PENG_ID",
                           ctp."TRX_CDC_REC_ID", 
                           ctp."NO_SHIFT",
                           ctp."TRX_PENG_AMOUNT" ,
                           ctd."ID",
                           ctd."25" "QTY_25",
                           ctd."50"  "QTY_50",
                           ctd."100" "QTY_100",
                           ctd."200" "QTY_200",
                           ctd."500" "QTY_500",
                           ctd."1000" "QTY_1000",
                           ctd."2000" "QTY_2000",
                           ctd."5000" "QTY_5000",
                           ctd."10000" "QTY_10000",
                           ctd."20000" "QTY_20000",
                           ctd."50000" "QTY_50000",
                           ctd."75000" "QTY_75000",
                           ctd."100000" "QTY_100000"
                    FROM cdc_trx_detail_pengganti ctp,
                         cdc_trx_receipts_denom ctd 
                    WHERE ctp."TRX_CDC_REC_ID"=ctd."CDC_SHIFT_REC_ID"
                          AND  ctp."TRX_CDC_REC_ID" = ? 
                          AND ctp."NO_SHIFT" = ?';
      return $this->db->query($statement, array($rec_id, $res_tipe_shift->SHIFT_CODE))->result();




    }

    function get_rec_pengganti2($rec_id, $shift)
    {
      $statement = 'SELECT "TRX_DETAIL_PENG_ID", "TRX_CDC_REC_ID", "NO_SHIFT", "TRX_PENG_AMOUNT", "TRX_PENG_AMOUNT2", "TRX_PENG_AMOUNT3" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      return $this->db->query($statement, array($rec_id, $shift))->result();
    }


    function get_data_denom_sd7($store,$salesDate,$tipe_shift)
    {
      $timestamp = strtotime($salesDate);
      $salesDate = date("Y-m-d", $timestamp);
      if($tipe_shift=='SHIFT-1')
      {
        $tipe_shift='1';
      }else if($tipe_shift=='SHIFT-2')
      {
        $tipe_shift='2';
      }else if($tipe_shift=='SHIFT-3')
      {
        $tipe_shift='3';
      }else if($tipe_shift=='HARIAN'){
        $tipe_shift='H';
      }

      $statement='SELECT "25" as "25_RP","50" as "50_RP","100" as "100_RP","200" as "200_RP","500" as "500_RP","1000" as "1000_RP","2000" as "2000_RP","5000" as "5000_RP","10000" as "10000_RP","20000" as "20000_RP","50000" as "50000_RP","75000" AS "75000_RP","100000" as "100000_RP"
                  FROM cdc_data_sales_per_denom WHERE "STORE_CODE"=? AND "SALES_DATE"=? AND "SHIFT"=? ';
      $result=$this->db->query($statement, array($store,$salesDate,$tipe_shift))->row();
      return $result;

    }

    function save_data_pengganti($det_id,$rec_id,$no_shift, $peng_amount)
    {

        $statement_tipe_shift='SELECT "SHIFT_CODE" FROM cdc_shift_desc WHERE "SHIFT_DESC"=?';
        $res_tipe_shift=$this->db->query($statement_tipe_shift,$no_shift)->row();
      
        if($det_id == 'X'){

           $statement_cek='select count(*) as "HITUNG" from cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID"=?';
           $cek=$this->db->query($statement_cek,$rec_id)->row();
           if($cek->HITUNG==0)
           {

              $statement = 'INSERT INTO cdc_trx_detail_pengganti ("TRX_CDC_REC_ID","NO_SHIFT","TRX_PENG_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES(?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
              $this->db->query($statement, array($rec_id, $res_tipe_shift->SHIFT_CODE, $peng_amount, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
              return $this->db->affected_rows();
           }else{
            return 0;
           }

         
        }else{

           $statement_cek='select count(*) as "HITUNG" from cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID"=?';
           $cek=$this->db->query($statement_cek,$rec_id)->row();
           if($cek->HITUNG==0)
           {

              $statement = 'INSERT INTO cdc_trx_detail_pengganti ("TRX_CDC_REC_ID","NO_SHIFT","TRX_PENG_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES(?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
              $this->db->query($statement, array($rec_id, $res_tipe_shift->SHIFT_CODE, $peng_amount, $this->session->userdata('usrId'), $this->session->userdata('usrId')));
              return $this->db->affected_rows();
           }else{
             $statement = 'UPDATE cdc_trx_detail_pengganti SET "TRX_PENG_AMOUNT" = ? WHERE "TRX_DETAIL_PENG_ID" = ?';

            $this->db->query($statement,array($peng_amount,$det_id));
             return $this->db->affected_rows();
           }

         
        }
       
    
    }


    function save_data_input_denom($det_id,$rec_id,$no_shift,$qty_100000,$qty_75000,$qty_50000,$qty_20000,$qty_10000,$qty_5000,$qty_2000,$qty_1000,$qty_500,$qty_200,$qty_100,$qty_50,$qty_25)
    {

        $statement_tipe_shift='SELECT "SHIFT_CODE" FROM cdc_shift_desc WHERE "SHIFT_DESC"=?';
        $res_tipe_shift=$this->db->query($statement_tipe_shift,$no_shift)->row();
      
        if($det_id == 'X'){

            $statement='INSERT INTO cdc_trx_receipts_denom(
                                "CDC_SHIFT_REC_ID", "25", "50", "100", "200", "500", "1000", 
                                "2000", "5000", "10000", "20000", "50000", "75000","100000", "CREATION_DATE", 
                                "LAST_UPDATE_DATE")
                        VALUES (?, ?, ?, ?, ?, ?, ?, 
                                ?, ?, ?, ?, ?,?, ?, CURRENT_DATE, 
                                CURRENT_DATE)';
            $this->db->query($statement, array($rec_id,$qty_25,$qty_50,$qty_100,$qty_200,$qty_500,$qty_1000,$qty_2000,$qty_5000,$qty_10000,$qty_20000,$qty_50000,$qty_75000,$qty_100000));
            return $this->db->affected_rows();
        }
        else{

          $statement_cek='SELECT COUNT(*) AS "HITUNG" FROM cdc_trx_receipts_denom where "CDC_SHIFT_REC_ID"=?';
          $res_cek=$this->db->query($statement_cek,$rec_id)->row();
          if($res_cek->HITUNG=='0')
          {
            $statement='INSERT INTO cdc_trx_receipts_denom(
                                "CDC_SHIFT_REC_ID", "25", "50", "100", "200", "500", "1000", 
                                "2000", "5000", "10000", "20000", "50000","75000", "100000", "CREATION_DATE", 
                                "LAST_UPDATE_DATE")
                        VALUES (?, ?, ?, ?, ?, ?, ?, 
                                ?, ?, ?, ?, ?, ?,?, CURRENT_DATE, 
                                CURRENT_DATE)';
            $this->db->query($statement, array($rec_id,$qty_25,$qty_50,$qty_100,$qty_200,$qty_500,$qty_1000,$qty_2000,$qty_5000,$qty_10000,$qty_20000,$qty_50000,$qty_75000,$qty_100000));

          }else{
            $statement = 'UPDATE public.cdc_trx_receipts_denom
                         SET  "25"=?, "50"=?, "100"=?, "200"=?, 
                             "500"=?, "1000"=?, "2000"=?, "5000"=?, "10000"=?, "20000"=?, 
                             "50000"=?,"75000"=?, "100000"=?
                       WHERE "ID"=? and "CDC_SHIFT_REC_ID"=?';

            $this->db->query($statement,array($qty_25,$qty_50,$qty_100,$qty_200,$qty_500,$qty_1000,$qty_2000,$qty_5000,$qty_10000,$qty_20000,$qty_50000,$qty_75000,$qty_100000,$det_id,$rec_id));
          }
          
          return $this->db->affected_rows();
        }
       
    
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

    function delete_data_pengganti_denom($del_id,$receipt_denom_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_pengganti WHERE "TRX_DETAIL_PENG_ID" = ?';
      $this->db->query($statement, array($del_id));


      $statement2='DELETE FROM cdc_trx_receipts_denom WHERE "ID" = ?';
      $this->db->query($statement2, array($receipt_denom_id));
      return $this->db->affected_rows();


    }

    function delete_data_pengganti($del_id,$receipt_denom_id)
    {
      $statement = 'DELETE FROM cdc_trx_detail_pengganti WHERE "TRX_DETAIL_PENG_ID" = ?';
      $this->db->query($statement, array($del_id));


      $statement2='DELETE FROM cdc_trx_receipts_denom WHERE "ID" = ?';
      $this->db->query($statement2, array($receipt_denom_id));
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