<?php
  class Mod_cdc_trx_detail_voucher extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData($dataId){
      $data = $this->db->query(' SELECT "TRX_VOUCHER_ID", "TRX_VOUCHER_CODE" ||\' \'|| "TRX_VOUCHER_NUMBER" AS "TRX_VOUCHER_NUM", "TRX_VOUCHER_DATE", "TRX_VOUCHER_DESC", "TRX_VOUCHER_AMOUNT"
        FROM cdc_trx_voucher WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ORDER BY "TRX_VOUCHER_ID" DESC ');
      //$data = $this->db->query(' SELECT a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", trim(b."TRX_DETAIL_DESC") AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$id.'\' ');
      $result['rows']=$data->result();
      return($result);
    }

    function cekVoucher($num){
      $code = substr(str_replace(' ', '', $num),0,2);
      $number = substr(str_replace(' ', '', $num),2);
      $statement = 'SELECT * FROM cdc_master_detail_voucher WHERE BTRIM("VOUCHER_CODE") = BTRIM(?) AND BTRIM("VOUCHER_NUMBER") = BTRIM(?)';
      $query = $this->db->query($statement,array($code,$number))->result();
      return $query;
    }

    function addData($id,$data){
      $createBy   = $this->session->userdata('usrId');
      $create     = date("Y-m-d");
      $code = substr($data['num'],0,2);
      $number = substr($data['num'],3,9);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);

      $statement = 'INSERT INTO CDC_TRX_VOUCHER VALUES(?,?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
      $this->db->query($statement,array(intval($id),intval($data['receiptID']),$code,$number,$trx_date,$data['desc'],intval($data['amount']),intval($createBy),intval($createBy)));
/*
      $input = array('TRX_VOUCHER_ID'=>$id, 'TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_VOUCHER_CODE'=>$code, 'TRX_VOUCHER_NUMBER'=>$number,
                    'TRX_VOUCHER_DATE'=>$trx_date, 'TRX_VOUCHER_DESC'=>$data['desc'], 'TRX_VOUCHER_AMOUNT'=>$data['amount'], 'CREATED_BY'=>$createBy, 'CREATION_DATE'=>$create );
      $this->db->insert('cdc_trx_voucher',$input);*/

      if ($this->db->affected_rows() > 0) {
      	//UPDATE STATUS VOUCHER FLAG
		$this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\', "USED_DATE" = current_date, "BRANCH_CODE" = \''.str_replace(" ", "",$this->session->userdata('branch_code')).'\', "USED_REC_ID" = '.$data['receiptID'].' WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');
		if ($this->db->affected_rows() > 0) {
			return "Data berhasil ditambahkan !";
		}
      }else{
      	return "Data gagal ditambahkan, mohon untuk dicoba kembali.";
      }
    }

    function getDataDetail($dataId){
      $data = $this->db->query(' SELECT "TRX_VOUCHER_ID", "TRX_VOUCHER_CODE" ||\' \'|| "TRX_VOUCHER_NUMBER" AS "TRX_VOUCHER_NUM", "TRX_VOUCHER_DATE", "TRX_VOUCHER_DESC", "TRX_VOUCHER_AMOUNT"
            FROM cdc_trx_voucher WHERE "TRX_VOUCHER_ID" = \''.$dataId.'\' ');
      //$data = $this->db->query(' SELECT a."TRX_PLUS_NAME" AS "TRX_PLUS_NAME", b."TRX_DETAIL_DATE" AS "TRX_DETAIL_DATE", trim(b."TRX_DETAIL_DESC") AS "TRX_DETAIL_DESC", b."TRX_DET_AMOUNT" AS "TRX_DET_AMOUNT" FROM cdc_master_detail_penambah AS a INNER JOIN cdc_trx_detail_tambah AS b USING ("TRX_PLUS_ID") WHERE "TRX_CDC_REC_ID" = \''.$id.'\' ');
      $result=$data->row();
      return $result;
    }

    function updateData($data){
      $updateBy   = $this->session->userdata('usrId');
      $updateDate = date("Y-m-d");
      $code = substr($data['num'],0,2);
      $number = substr($data['num'],3,9);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $update = array('TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_VOUCHER_CODE'=>$code, 'TRX_VOUCHER_NUMBER'=>$number,
                'TRX_VOUCHER_DATE'=>$trx_date, 'TRX_VOUCHER_DESC'=>$data['desc'], 'TRX_VOUCHER_AMOUNT'=>$data['amount'], 'LAST_UPDATE_BY'=>$updateBy, 'LAST_UPDATE_DATE'=>$updateDate );
      $this->db->where('TRX_VOUCHER_ID',$data['voucherID']);
      $this->db->update('cdc_trx_voucher',$update);/*
       $this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\' WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');*/
    }

    function getTotal($dataId){
      $total = $this->db->query(' SELECT SUM("TRX_VOUCHER_AMOUNT") AS "TOTAL" FROM cdc_trx_voucher WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ');
      $data  = $total->row()->TOTAL;
      return $data;
    }

    function deleteData($data){
      //UPDATE STATUS VOUCHER FLAG
      $master_voucher = $this->db->query(' SELECT "TRX_VOUCHER_CODE", "TRX_VOUCHER_NUMBER" FROM cdc_trx_voucher WHERE "TRX_VOUCHER_ID"=\''.$data['id'].'\' ');
      $code   = $master_voucher->row()->TRX_VOUCHER_CODE;
      $number = $master_voucher->row()->TRX_VOUCHER_NUMBER;
      $this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'N\', "BRANCH_CODE" = NULL, "USED_REC_ID" = NULL, "USED_DATE" = NULL WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');

      $this->db->where('TRX_VOUCHER_ID',$data['id']);
      $this->db->delete('cdc_trx_voucher');
    }

    //function voucher shift
    function getDataVoucherShift($dataId){
      $data = $this->db->query(' SELECT "TRX_VOUCHER_SHIFT_ID", "TRX_VOUCHER_CODE" ||\' \'|| "TRX_VOUCHER_NUMBER" AS "TRX_VOUCHER_NUM", "TRX_VOUCHER_DATE", "TRX_VOUCHER_DESC", "TRX_VOUCHER_AMOUNT","NO_SHIFT"
        FROM cdc_trx_voucher_shift WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ORDER BY "TRX_VOUCHER_SHIFT_ID" DESC ');
      $result['rows']=$data->result();
      return($result);
    }

     function updateDataShift($data){
      $updateBy   = $this->session->userdata('usrId');
      $updateDate = date("Y-m-d");
      $code = substr($data['num'],0,2);
      $number = substr($data['num'],3,9);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);
      $update = array('TRX_CDC_REC_ID'=>$data['receiptID'], 'TRX_VOUCHER_CODE'=>$code, 'TRX_VOUCHER_NUMBER'=>$number,
                'TRX_VOUCHER_DATE'=>$trx_date, 'TRX_VOUCHER_DESC'=>$data['desc'], 'TRX_VOUCHER_AMOUNT'=>$data['amount'],'NO_SHIFT'=>$data['no_shift'], 'LAST_UPDATE_BY'=>$updateBy, 'LAST_UPDATE_DATE'=>$updateDate );
      $this->db->where('TRX_VOUCHER_SHIFT_ID',$data['voucherID']);
      $this->db->update('cdc_trx_voucher_shift',$update);/*
       $this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\' WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');*/
    }

    function addDataShift($data){
      $createBy   = $this->session->userdata('usrId');
      $create     = date("Y-m-d");
      $code = substr($data['num'],0,2);
      $number = substr($data['num'],3,9);
      $trx_date = substr($data['date'], 6).'-'.substr($data['date'], 3,2).'-'.substr($data['date'], 0,2);

      $statement = 'INSERT INTO CDC_TRX_VOUCHER_SHIFT ("TRX_CDC_REC_ID","TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER","TRX_VOUCHER_DATE","TRX_VOUCHER_DESC","TRX_VOUCHER_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE")
       VALUES(?,?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE)';
      $this->db->query($statement,array(intval($data['receiptID']),$code,$number,$trx_date,$data['desc'],intval($data['amount']),$data['no_shift'],intval($createBy),intval($createBy)));

      if ($this->db->affected_rows() > 0) {
        //UPDATE STATUS VOUCHER FLAG
    $this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\', "USED_DATE" = current_date, "BRANCH_CODE" = \''.str_replace(" ", "",$this->session->userdata('branch_code')).'\', "USED_REC_ID" = '.$data['receiptID'].' WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');
    if ($this->db->affected_rows() > 0) {
      return "Data berhasil ditambahkan !";
    }
      }else{
        return "Data gagal ditambahkan, mohon untuk dicoba kembali.";
      }
    }

    function getTotalShift($dataId){
      $total = $this->db->query(' SELECT SUM("TRX_VOUCHER_AMOUNT") AS "TOTAL" FROM cdc_trx_voucher_shift WHERE "TRX_CDC_REC_ID" = \''.$dataId.'\' ');
      $data  = $total->row()->TOTAL;
      return $data;
    }

    function deleteDataShift($data){
      //UPDATE STATUS VOUCHER FLAG
      $master_voucher = $this->db->query(' SELECT "TRX_VOUCHER_CODE", "TRX_VOUCHER_NUMBER" FROM cdc_trx_voucher WHERE "TRX_VOUCHER_ID"=\''.$data['id'].'\' ');
      $code   = $master_voucher->row()->TRX_VOUCHER_CODE;
      $number = $master_voucher->row()->TRX_VOUCHER_NUMBER;
      $this->db->query('UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'N\', "BRANCH_CODE" = NULL, "USED_REC_ID" = NULL, "USED_DATE" = NULL WHERE "VOUCHER_CODE"=\''.$code.'\' AND "VOUCHER_NUMBER"=\''.$number.'\' ');

      $this->db->where('TRX_VOUCHER_SHIFT_ID',$data['id']);
      $this->db->delete('cdc_trx_voucher_shift');
    }




  }
?>
