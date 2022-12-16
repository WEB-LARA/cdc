<?php
  class Mod_cdc_trx_GTU extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData(){
      $userId   = $this->session->userdata('usrId');
      $result = $this->db->query(' SELECT a."CDC_GTU_ID", a."CDC_BANK_ID", b."BANK_NAME" ,/*b."BANK_ACCOUNT_NUM",*/ a."CDC_GTU_NUMBER" ,a."CDC_GTU_AMOUNT"
                    FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                    WHERE a."CREATED_BY" = \''.$userId.'\' AND a."CDC_BATCH_ID" IS NULL
                    ORDER BY a."CDC_GTU_ID" DESC
                ');
      return $result->result();
    }

    function get_total_gtu($user_id)
    {
      $statement = 'SELECT SUM(a."CDC_GTU_AMOUNT") "TOTAL"
                    FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                    WHERE a."CREATED_BY" = ? AND (a."CDC_BATCH_ID" IS NULL OR a."CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = ?))';
      $result = $this->db->query($statement,array(intval($user_id),intval($user_id)))->result();
      return $result[0]->TOTAL;
    }

    function getDataGTUReject($batch_id){
      $userId   = $this->session->userdata('usrId');
      $result = $this->db->query(' SELECT a."CDC_GTU_ID", a."CDC_BANK_ID", b."BANK_NAME" ,/*b."BANK_ACCOUNT_NUM",*/ a."CDC_GTU_NUMBER" ,a."CDC_GTU_AMOUNT"
                    FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                    WHERE a."CDC_BATCH_ID" = '.$batch_id.'
                    ORDER BY a."CDC_GTU_ID" DESC
                ');
      return $result->result();
    }

    function getBank(){
      $option = $this->db->query(' SELECT "BANK_ID", "BANK_NAME" FROM cdc_master_bank WHERE "ACTIVE_FLAG" = \'Y\' ');
      return $option->result();
    }

    function getBankNum($id){
      $bankNum = $this->db->query(' SELECT "BANK_ACCOUNT_NUM" FROM cdc_master_bank_account WHERE "BANK_ACCOUNT_ID" = \''.$id.'\' ');
      return $bankNum->row()->BANK_ACCOUNT_NUM;
    }

    function addData($id,$data){
      date_default_timezone_set("Asia/Bangkok");
      $info = getdate();
      $date = $info['mday'];
      $month = $info['mon'];
      $year = $info['year'];
      $current_date = "$year-$month-$date";

      $userId   = $this->session->userdata('usrId');
      $branchId = $this->session->userdata('branch_id');

      $statement_2 = 'SELECT * FROM CDC_TRX_GTU WHERE "CDC_GTU_NUMBER" = ?';
      $count = $this->db->query($statement_2,$data['check_num'])->num_rows();
      if ($count == 0) {
        if (intval($branchId) != 0 && intval($data['bank_id']) != 0 && intval($userId) != 0) {
          $statement = 'INSERT INTO CDC_TRX_GTU("CDC_GTU_ID","CDC_BRANCH_ID","CDC_BANK_ID","CDC_GTU_NUMBER","CDC_GTU_AMOUNT","CDC_GTU_DATE","CREATED_BY","CREATION_DATE","LAST_UPDATED_BY","LAST_UPDATE_DATE") VALUES(?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?,CURRENT_DATE)';
          $this->db->query($statement,array(intval($id),intval($branchId),intval($data['bank_id']),$data['check_num'],intval($data['check_amount']),intval($userId),intval($userId)));

          if ($this->db->affected_rows() > 0) {
            return "GTU berhasil ditambahkan.";
          }else{
            return "GTU gagal ditambahkan, mohon untuk dicoba kembali.";
          }
        }else return "GTU gagal ditambahkan, mohon untuk dicoba kembali.";
      }else{
        return "GTU Number sudah terpakai.";
      }

      /*$data = array('CDC_GTU_ID'=>$id, 'CDC_BRANCH_ID'=>$branchId, 'CDC_BANK_ID'=>$data['bank_id'], 'CDC_GTU_NUMBER'=>$data['check_num'],
              'CDC_GTU_AMOUNT'=>$data['check_amount'], 'CDC_GTU_DATE'=>$current_date, 'CREATED_BY'=>$userId, 'CREATION_DATE'=>$current_date
            );
      $this->db->insert('cdc_trx_gtu',$data);*/

    }

    function addDataGTUReject($id,$data){
      date_default_timezone_set("Asia/Bangkok");
      $info = getdate();
      $date = $info['mday'];
      $month = $info['mon'];
      $year = $info['year'];
      $current_date = "$year-$month-$date";

      $userId   = $this->session->userdata('usrId');
      $branchId = $this->session->userdata('branch_id');

      $data = array('CDC_GTU_ID'=>$id, 'CDC_BATCH_ID'=>$data['batch_id'], 'CDC_BRANCH_ID'=>$branchId, 'CDC_BANK_ID'=>$data['bank_id'], 'CDC_GTU_NUMBER'=>$data['check_num'],
              'CDC_GTU_AMOUNT'=>$data['check_amount'], 'CDC_GTU_DATE'=>$current_date, 'CREATED_BY'=>$userId, 'CREATION_DATE'=>$current_date
            );
      $this->db->insert('cdc_trx_gtu',$data);

      return "GTU Berhasil Ditambahkan";
    }

    function deleteData($id){
      $this->db->where('CDC_GTU_ID',$id);
      $this->db->delete('cdc_trx_gtu');
      return "GTU Berhasil Dihapus";
    }

    function getGTU_detail($id){
      $result = $this->db->query(' SELECT a."CDC_GTU_ID", a."CDC_GTU_NUMBER", b."BANK_ID", b."BANK_NAME", a."CDC_GTU_AMOUNT"
                      FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                      WHERE a."CDC_GTU_ID" = \''.$id.'\' ');
      return $result->row();
    }

    function updateData($data){
      $updateBy = $this->session->userdata('usrId');
      date_default_timezone_set("Asia/Bangkok");
      $info = getdate();
      $date = $info['mday']; $month = $info['mon']; $year = $info['year'];
      $current_date = "$year-$month-$date";

      $this->db->query(' UPDATE cdc_trx_gtu SET "CDC_GTU_NUMBER" = \''.$data['check_num'].'\', "CDC_BANK_ID" = \''.$data['bank_id'].'\', "CDC_GTU_AMOUNT"= \''.$data['check_amount'].'\', "LAST_UPDATED_BY"=\''.$updateBy.'\', "LAST_UPDATE_DATE"=\''.$current_date.'\'  WHERE "CDC_GTU_ID"=\''.$data['gtu_id'].'\'   ');
      return "GTU Berhasil Diupdate";
    }


  }
?>
