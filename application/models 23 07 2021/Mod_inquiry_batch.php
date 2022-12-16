<?php
  class Mod_inquiry_batch extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getBatch($data){
      $branchId   = $this->session->userdata('branch_id');
      $createBy   = $this->session->userdata('usrId');
      $role = $this->session->userdata('role_id');
      $indc_code  = '';
      $in_role = '';

      if ($this->session->userdata('dc_type') == 'DCI') {
        $indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
      }else{
        $indc_code = "'".$this->session->userdata('dc_code')."'";
      }

      if ($role < 3) {
        $in_role = 'AND SU."USER_ID" = '.$createBy.'';
      }
      else{
        $in_role = 'AND SU."ROLE_ID" <= '.$role.'';
      }

      $query = $this->db->query('SELECT CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER", CTB."CDC_BATCH_DATE", CTB."CDC_BATCH_TYPE",
        CASE  WHEN CTB."CDC_BATCH_STATUS"=\'n\' THEN \'NEW\'
              WHEN CTB."CDC_BATCH_STATUS"=\'N\' THEN \'NEW\'
              WHEN CTB."CDC_BATCH_STATUS"=\'v\' THEN \'VALIDATE\'
              WHEN CTB."CDC_BATCH_STATUS"=\'V\' THEN \'VALIDATE\'
              WHEN CTB."CDC_BATCH_STATUS"=\'r\' THEN \'REJECT\'
              WHEN CTB."CDC_BATCH_STATUS"=\'R\' THEN \'REJECT\'
              WHEN CTB."CDC_BATCH_STATUS"=\'T\' THEN \'TRANSFER\'
        END AS "CDC_BATCH_STATUS",
        CMB."BRANCH_CODE", CMB."BRANCH_NAME", SU."USER_NAME" AS "CREATED_BY", CTB."LAST_UPDATE_DATE", TO_CHAR(CTB."LAST_UPDATE_DATE",\'HH24:MI:SS\') "INPUT_TIME",
      (
        ( SELECT COALESCE(SUM(CTR."ACTUAL_SALES_AMOUNT"),0)+COALESCE(SUM(CTR."ACTUAL_RRAK_AMOUNT"),0)+COALESCE(SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED"),0)+COALESCE(SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT"),0)+COALESCE(SUM(CTR."ACTUAL_VOUCHER_AMOUNT"),0)+COALESCE(SUM(CTR."ACTUAL_OTHERS_AMOUNT"),0) FROM CDC_TRX_RECEIPTS CTR WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID"
        )
    -
    (
      SELECT COALESCE(SUM(GTU."CDC_GTU_AMOUNT"),0) FROM CDC_TRX_GTU GTU WHERE GTU."CDC_BATCH_ID" = CTB."CDC_BATCH_ID"
    )
      )AS "TOTAL_SETOR"
         FROM CDC_TRX_BATCHES CTB, CDC_MASTER_BRANCH CMB, sys_user_2 SU
         WHERE CTB."CDC_BRANCH_ID" = CMB."BRANCH_ID" AND CTB."CREATED_BY" = SU."USER_ID"
         AND CTB."CDC_BRANCH_ID" = \''.$branchId.'\' AND CTB."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.' 
                AND CTB."CDC_BATCH_NUMBER" LIKE \'%'.$data['batchNumber'].'%\' AND SU."USER_NAME" LIKE \'%'.$data['createBy'].'%\'
                AND CTB."CDC_BATCH_STATUS" LIKE \'%'.$data['status'].'%\' AND CTB."CDC_BATCH_TYPE" LIKE \'%'.$data['type'].'%\' AND CTB."CDC_BATCH_DATE" >= \''.$data['tglBatch'].'\'
         GROUP BY CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER", CTB."CDC_BATCH_DATE", CTB."CDC_BATCH_TYPE", CTB."CDC_BATCH_STATUS", CMB."BRANCH_CODE", CMB."BRANCH_NAME", SU."USER_NAME", CTB."LAST_UPDATE_DATE"
         ORDER BY CTB."CDC_BATCH_ID" DESC
         ');

      $result['rows']=$query->result();

      return $result;
    }
    public function get_tipe_shift($branch_code,$store_code,$rec_id){

    $statement='SELECT "NO_SHIFT" from cdc_trx_receipts ctr where ctr."BRANCH_CODE"=? and ctr."STORE_ID"=(select cmt."STORE_ID" from cdc_master_toko cmt where cmt."STORE_ID"=ctr."STORE_ID" and cmt."STORE_CODE"=?)  and ctr."CDC_REC_ID"=?';
    $result=$this->db->query($statement,array($branch_code,$store_code,$rec_id))->row();
     if(trim($result->NO_SHIFT)=='1'||trim($result->NO_SHIFT)=='2'|| trim($result->NO_SHIFT)=='3'){
        return 'HS';
      }else if(trim($result->NO_SHIFT)=='S-1'){
        return 'SS1';
      }else if(trim($result->NO_SHIFT)=='S-2'){
        return 'SS2';
      }else if(trim($result->NO_SHIFT)=='S-3'){
        return 'SS3';
      }else{
        return 'H';
      }
  }
    function getBatchReject(){
      $branchId   = $this->session->userdata('branch_id');
      $createBy   = $this->session->userdata('usrId');
      $statement = 'SELECT CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER", CTB."CDC_BATCH_DATE", CTB."CDC_BATCH_TYPE", CTB."CDC_BATCH_STATUS", CMB."BRANCH_CODE", CMB."BRANCH_NAME", CTB."CREATED_BY", CTB."LAST_UPDATE_DATE", (SELECT COALESCE(SUM(CTR."ACTUAL_SALES_AMOUNT"),0) + COALESCE(SUM(CTR."ACTUAL_RRAK_AMOUNT"),0) + COALESCE(SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED"),0) + COALESCE(SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT"),0) + COALESCE(SUM(CTR."ACTUAL_VOUCHER_AMOUNT"),0) + COALESCE(SUM(CTR."ACTUAL_OTHERS_AMOUNT"),0) FROM CDC_TRX_RECEIPTS CTR WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID") AS "TOTAL_SETOR" FROM cdc_trx_batches CTB, cdc_master_branch CMB WHERE CTB."CDC_BRANCH_ID" = CMB."BRANCH_ID" AND CTB."CREATED_BY" = ? AND CTB."CDC_BATCH_STATUS" = \'R\' AND CMB."BRANCH_ID" = ? GROUP BY CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER", CTB."CDC_BATCH_DATE", CTB."CDC_BATCH_TYPE", CTB."CDC_BATCH_STATUS", CMB."BRANCH_CODE", CMB."BRANCH_NAME", CTB."CREATED_BY", CTB."LAST_UPDATE_DATE"';
      return $this->db->query($statement,array($createBy,$branchId))->result();
    }

    function getReceipt($batchId){
      $branchCode   = $this->session->userdata('branch_code');

      $data = $this->db->query(' SELECT c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", c."SALES_DATE", c."ACTUAL_SALES_AMOUNT",
        (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT") AS "TOTAL_PENAMBAHAN",
        ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT") )AS "ACTUAL_AMOUNT",
        (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION") AS "TOTAL_PENGURANGAN" FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
        WHERE c."CDC_BATCH_ID" IS NOT NULL AND c."BRANCH_CODE"= \''.$branchCode.'\' AND c."CDC_BATCH_ID"=\''.$batchId.'\' ORDER BY c."CDC_REC_ID" DESC ');

        //var_dump($data->result());
      return $data->result();
    }

    function rejectBatch($batchId){
      $result = "All Batch berhasil di REJECT";
      for($i=0; $i < count($batchId); $i++ ){
          $num = $this->db->query('SELECT * FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = \''.$batchId[$i].'\' AND "CDC_DEPOSIT_ID" IS NULL')->num_rows();
          if ($num > 0) {
            $this->db->query(' UPDATE cdc_trx_batches SET "CDC_BATCH_STATUS"= \'R\' WHERE "CDC_BATCH_ID"= \''.$batchId[$i].'\' ');
          }
          else{
            $result = "Terdapat Batch yang sudah menjadi deposit.";
          }
        }
      return $result;
    }

    function delBatch($batchId){
      //var_dump($batchId);
      for ($i=0; $i < count($batchId); $i++) { 
        $status = $this->db->query('SELECT "CDC_BATCH_STATUS" FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = '.intval($batchId[$i]).'');
        if($status->row()->CDC_BATCH_STATUS == 'V' || $status->row()->CDC_BATCH_STATUS == 'T'){
          $result = "Data yang sudah VALID, TIDAK BISA DIHAPUS!!";
        }
        else{
          $this->db->query('UPDATE cdc_trx_receipts SET "STATUS" = \'N\', "CDC_BATCH_ID" = NULL WHERE "CDC_BATCH_ID" = '.intval($batchId[$i]).'');
          $this->db->query('UPDATE cdc_trx_gtu SET "STATUS" = \'N\', "CDC_BATCH_ID" = NULL WHERE "CDC_BATCH_ID" = '.intval($batchId[$i]).'');
          if ($this->db->affected_rows() > 0) {
            $this->db->query('DELETE FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = '.intval($batchId[$i]).'');
            $result = "Batch berhasil dihapus";
          }else $result = "Batch gagal dihapus";
        }
      }
      /*foreach ($batchId as $row) {
        $status = $this->db->query(' SELECT "CDC_BATCH_STATUS" FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = \''.$row.'\' ');
        if($status->row()->CDC_BATCH_STATUS == 'V'){
          $result = "Data yang sudah VALID, TIDAK BISA DIHAPUS!!";
        }
        else{
          $result = "Batch dihapus";
          $this->db->query(' UPDATE "cdc_trx_receipts" SET "STATUS" = \'N\', "CDC_BATCH_ID" = NULL WHERE "CDC_BATCH_ID" = \''.$row.'\' ');
          $this->db->query(' UPDATE "cdc_trx_gtu" SET "STATUS" = \'N\', "CDC_BATCH_ID" = NULL WHERE "CDC_BATCH_ID" = \''.$row.'\' ');
          $this->db->query(' DELETE FROM "cdc_trx_batches" WHERE "CDC_BATCH_ID" = \''.$row.'\' ');
        }
      }*/

      return $result;
    }

    function validateBatch($batchId){
        for($i=0; $i < count($batchId['batchID']); $i++ ){
          $this->db->query(' UPDATE cdc_trx_batches SET "CDC_BATCH_STATUS"= \'V\' WHERE "CDC_BATCH_ID"= \''.$batchId['batchID'][$i].'\' ');
        }
        $result = "Validate Batch Success";
        return $result;
    }
    function get_batch_type($batchId)
    {
      $hitung=0;
      for($i=0; $i < count($batchId); $i++ ){
          $statement='select count(*) as hitung from cdc_trx_batches where "CDC_BATCH_TYPE" like \'STL%\' and "CDC_BATCH_ID"  = '.$batchId[$i].' ';
          $hitung+= $this->db->query($statement,$batchId)->row()->hitung;
      }
        return $hitung;
    }
    function get_data_transfer($batch_id)
    {
      $statement = 'SELECT btrim(CMBR."BRANCH_CODE") BRANCH_CODE, CMB."BANK_NAME" BANK_NAME, CTB."CDC_BATCH_ID" BATCH_ID, CTB."CDC_BATCH_NUMBER" BATCH_NUMBER, CTB."CDC_BATCH_TYPE" BATCH_TYPE, to_char(CTB."CDC_BATCH_DATE", \'DD-Mon-YY\') BATCH_DATE, CTB."CDC_BATCH_STATUS" BATCH_STATUS, \'SOURCE DATA WEB\' DESCRIPTION, CTB."CDC_REFF_NUM" REFF_NUM, CTR."CDC_REC_ID" REC_ID, CMT."STORE_CODE" STORE_CODE, to_char(CTR."SALES_DATE", \'DD-Mon-YY\') SALES_DATE, CTR."STATUS" STATUS, COALESCE(CTR."ACTUAL_SALES_AMOUNT",0) ACTUAL_SALES_AMOUNT, COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0) ACTUAL_RRAK_AMOUNT, COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0) ACTUAL_PAY_LESS_DEPOSITED, COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0) ACTUAL_VOUCHER_AMOUNT, COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0) ACTUAL_LOST_ITEM_PAYMENT, COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0) ACTUAL_WU_ACCOUNTABILITY, COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0) ACTUAL_OTHERS_AMOUNT, btrim(CTR."ACTUAL_OTHERS_DESC") ACTUAL_OTHERS_DESC, COALESCE(CTR."RRAK_DEDUCTION",0) RRAK_DEDUCTION, COALESCE(CTR."LESS_DEPOSIT_DEDUCTION",0) LESS_DEPOSIT_DEDUCTION, COALESCE(CTR."OTHERS_DEDUCTION",0) OTHERS_DEDUCTION, btrim(CTR."OTHERS_DESC") OTHERS_DESC, COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0) ACTUAL_VIRTUAL_PAY_LESS, CTR."ACTUAL_SALES_FLAG" ACTUAL_SALES_FLAG, COALESCE(CTR."VIRTUAL_PAY_LESS_DEDUCTION",0) VIRTUAL_PAY_LESS_DEDUCTION, CMBA."BANK_ACCOUNT_NUM", to_char(CTR."MUTATION_DATE", \'DD-Mon-YY\')"MUTATION_DATE" FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BRANCH CMBR WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CMBR."BRANCH_ID" = CTB."CDC_BRANCH_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."BANK_ACCOUNT_ID" = CMBA."BANK_ACCOUNT_ID" AND CMBA."BANK_ID" = CMB."BANK_ID" AND CTB."CDC_BATCH_STATUS" = \'V\' AND CTB."TRANSFER_FLAG" = \'N\' AND CTR."TRANSFER_FLAG" = \'N\' AND (CTB."CDC_BATCH_TYPE" LIKE \'%STN\' OR CTB."CDC_BATCH_TYPE" LIKE \'%KUN\') AND btrim(CMBR."BRANCH_CODE") = btrim(?) and CTB."CDC_BATCH_ID" IN ('.$batch_id.')';
      return $this->db->query($statement,$this->session->userdata('branch_code'))->result();
    }

    function update_status_batch_transfer($batch_id,$transfer_flag)
    {
      if($transfer_flag=='Y'){
            $statement = 'UPDATE CDC_TRX_RECEIPTS SET "TRANSFER_FLAG" = \'Y\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN ('.$batch_id.')';
            $statement_2 = 'UPDATE CDC_TRX_BATCHES SET "TRANSFER_FLAG" = \'Y\', "CDC_BATCH_STATUS" = \'T\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN ('.$batch_id.')';
            $this->db->query($statement);
            $this->db->query($statement_2);
      }else{
           $statement = 'UPDATE CDC_TRX_RECEIPTS SET "TRANSFER_FLAG" = \'N\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN ('.$batch_id.')';
           $statement_2 = 'UPDATE CDC_TRX_BATCHES SET "TRANSFER_FLAG" = \'N\', "CDC_BATCH_STATUS" = \'V\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN ('.$batch_id.')';
            $this->db->query($statement);
            $this->db->query($statement_2);
      }
     
      return $this->db->affected_rows();
    }

    function get_data_sales_rec($data)
    {
      $page = ($data['page'] - 1) * $data['rows'];
      $statement = 'SELECT CTR."CDC_REC_ID", CTB."CDC_BATCH_ID", CTR."STORE_ID", CMT."STORE_CODE"||\' - \'||CMT."STORE_NAME" "STORE", TO_CHAR(CTR."SALES_DATE", \'DD Month YYYY\') "SALES_DATE", CTR."ACTUAL_SALES_AMOUNT", CTR."ACTUAL_SALES_FLAG", CTB."CDC_BATCH_STATUS", CTR."TRANSFER_FLAG", CTR."STN_FLAG" FROM CDC_MASTER_TOKO CMT, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB WHERE CMT."STORE_ID" = CTR."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_BATCH_NUMBER" = ? AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?)';

      if ($data['store_code'] != '') {
        $statement .= ' AND CMT."STORE_ID" = '.$data['store_code'];
      }

      if ($data['sales_date'] != '') {
        $statement .= ' AND CTR."SALES_DATE" = \''.$data['sales_date'].'\'';
      }

      $result['total'] = $this->db->query($statement, array($data['batch_num'], $this->session->userdata('branch_code')))->num_rows();
      $statement .= ' ORDER BY CTR."CDC_REC_ID" LIMIT '.$data['rows'].' OFFSET '.$page;
      $result['rows'] = $this->db->query($statement, array($data['batch_num'], $this->session->userdata('branch_code')))->result();

      return $result;

    }

    public function get_batch_id($batch_num)
    {
      $statement = 'SELECT * FROM CDC_TRX_BATCHES WHERE "CDC_BATCH_NUMBER" = ?';
      return $this->db->query($statement, $batch_num)->result();
    }


     public function get_jumlah_shift($store_code)
    {
      $statement = 'SELECT "TOTAL_SHIFT" FROM cdc_master_shift WHERE "STORE_CODE" = ? AND "TIPE_SHIFT"=\'SS\'';
      return $this->db->query($statement, $store_code)->row();
    }


    public function get_combo_store($branch_code)
    {
      $statement = 'SELECT CMT."STORE_ID", CMT."STORE_CODE"||\' - \'||CMT."STORE_NAME" "STORE" FROM CDC_MASTER_TOKO CMT, CDC_MASTER_AM_AS CMA WHERE BTRIM(CMT."STORE_CODE") = BTRIM(CMA."STORE_CODE") AND BTRIM(CMA."BRANCH_CODE") = BTRIM(?) ORDER BY CMT."STORE_ID"';
      return $this->db->query($statement, $branch_code)->result();
    }

    public function cek_tanggal_sales($store_id, $sales_date, $act_flag, $stn_flag)
    {
      $statement = 'SELECT * FROM CDC_TRX_RECEIPTS WHERE "STORE_ID" = ? AND "SALES_DATE" = ? AND "ACTUAL_SALES_FLAG" = ? AND "STN_FLAG" = ?';
      return $this->db->query($statement, array($store_id, $sales_date, $act_flag, $stn_flag))->result();
    }

    public function get_batch_number($batch_id)
    {
      $statement = 'SELECT * FROM CDC_TRX_BATCHES WHERE "CDC_BATCH_ID" = ?';
      $result = $this->db->query($statement, $batch_id)->result();
      return $result[0]->CDC_BATCH_NUMBER;
    }

    public function change_sales($rec_id, $store_id, $sales_date)
    {
      $statement = 'UPDATE CDC_TRX_RECEIPTS SET "STORE_ID" = ?, "SALES_DATE" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_REC_ID" = ?';
      $this->db->query($statement, array($store_id, $sales_date, $this->session->userdata('usrId'), $rec_id));
      return $this->db->affected_rows();
    }
    
  }

?>
