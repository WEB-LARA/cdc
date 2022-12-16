<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_deposit extends CI_Model {

	public function getBank()
	{
		$statement = 'select "BANK_ID" as ID, "BANK_NAME" as NAME from cdc_master_bank';
		return $this->db->query($statement)->result();
	}

	public function get_single_bank($id_bank)
	{
		$statement = 'select "BANK_ID" as ID, "BANK_NAME" as NAME from cdc_master_bank where "BANK_ID" = ?';
		return $this->db->query($statement,$id_bank)->result();
	}


	public function get_batch_type($depid)
	{
		$statement='select count(*) as hitung from cdc_trx_batches where "CDC_BATCH_TYPE" like \'STL%\' and "CDC_DEPOSIT_ID"=?';
		return $this->db->query($statement,$depid)->row()->hitung;
	}
	public function get_data_batch($id_bank,$id_deposit)
	{
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}

		$statement = 'SELECT CTB."CDC_DEPOSIT_ID" DEPOSIT_ID, CTB."CDC_BATCH_ID" BATCH_ID, CTB."CDC_BATCH_NUMBER" BATCH_NUMBER, CTB."CDC_REFF_NUM" REFERENCE_NUM, CTB."CDC_BATCH_TYPE" BATCH_TYPE, SU."USER_NAME" USER_NAME, CTB."CDC_BATCH_DATE" BATCH_DATE, (select SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_receipts where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID") as ACTUAL_TOTAL_AMOUNT, coalesce((select sum(COALESCE("CDC_GTU_AMOUNT",0)) from cdc_trx_gtu where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID"),0) CHECK_EXCHANGES_TOTAL_AMOUNT, (select SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_receipts where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID") - coalesce((select sum(COALESCE("CDC_GTU_AMOUNT",0)) from cdc_trx_gtu where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID"),0) as DEPOSIT FROM cdc_trx_batches CTB, sys_user_2 SU WHERE CTB."CREATED_BY" = SU."USER_ID" AND CTB."CDC_BANK_ID" = ? AND CTB."CDC_BRANCH_ID" = ? AND CTB."CDC_BATCH_STATUS" = \'V\' AND (CTB."CDC_BATCH_TYPE" NOT LIKE \'%STN\' AND CTB."CDC_BATCH_TYPE" NOT LIKE \'%KUN\') AND (CTB."CDC_DEPOSIT_ID" IS NULL OR CTB."CDC_DEPOSIT_ID" = ?) AND CTB."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.' ORDER BY CTB."CDC_DEPOSIT_ID", CTB."CDC_BATCH_DATE" DESC';

		if ($id_deposit == 0) {
			return $this->db->query($statement,array($id_bank,$this->session->userdata('branch_id'),NULL))->result();
		}else{
			return $this->db->query($statement,array($id_bank,$this->session->userdata('branch_id'),$id_deposit))->result();
		}
	}

	public function get_tipe_shift($branch_code,$store_code,$rec_id){

		$statement='SELECT "NO_SHIFT" from cdc_trx_receipts ctr where ctr."BRANCH_CODE"=? and ctr."STORE_ID"=(select cmt."STORE_ID" from cdc_master_toko cmt where cmt."STORE_ID"=ctr."STORE_ID" and cmt."STORE_CODE"=?) and ctr."CDC_REC_ID"=?';
		$result=$this->db->query($statement,array($branch_code,$store_code,$rec_id))->row();
		if($result){
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
		}else{
			return 'H';
		}
		
	}
	  public function get_jumlah_shift($store_code)
    {
      $statement = 'SELECT "TOTAL_SHIFT" FROM cdc_master_shift WHERE "STORE_CODE" = ? AND "TIPE_SHIFT"=\'SS\'';
      return $this->db->query($statement, $store_code)->row();
    }

	public function get_flag_final($store_code,$sales_date){
		$tgl= date("Y-m-d");
		$sales_date = DateTime::createFromFormat("d-M-Y", $sales_date)->format("Y-m-d");
		$statement='SELECT "TIPE_SHIFT" from cdc_master_shift cms where "STORE_CODE"=? AND "STATUS"=\'A\' and "TGL_ACTIVE"<=? AND ("TGL_INACTIVE" IS NULL OR "TGL_INACTIVE">=?)';
		$rs=$this->db->query($statement,array($store_code,$sales_date,$sales_date))->row();
		
		if($rs){
			$tipe_shift=$rs->TIPE_SHIFT;
			if($tipe_shift=='SS'){
			$statement='SELECT "TOTAL_SHIFT" from cdc_master_shift cms where "STORE_CODE"=?  AND "STATUS"=\'A\' and "TGL_ACTIVE"<=? AND ("TGL_INACTIVE" IS NULL OR "TGL_INACTIVE">=?)';
			$rs=$this->db->query($statement,array($store_code,$sales_date,$sales_date))->row();
			$total_shift=$rs->TOTAL_SHIFT;

			
			
			$statement_receipt='SELECT COUNT(*)  as "CURRENT" FROM cdc_trx_receipts ctr where ctr."STORE_ID"=(SELECT "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctr."ACTUAL_SALES_FLAG"=\'Y\' AND ctr."SALES_DATE"=? AND ctr."TRANSFER_FLAG"=\'Y\'';
			$rs2=$this->db->query($statement_receipt,array($store_code,$sales_date))->row();
			
			if((trim($rs->TOTAL_SHIFT)==trim($rs2->CURRENT))){
				$statement='SELECT ? as "STORE_CODE", ?  AS "SALES_DATE" ';
				$rs=$this->db->query($statement,array($store_code,$sales_date))->result();
				return $rs;

			}

			}
			/*else{
				$statement='SELECT ? as "STORE_CODE", ?  AS "SALES_DATE" ';
				$rs=$this->db->query($statement,array($store_code,$sales_date))->result();
				return $rs;
			}*/
		}

		/*else{
			$statement='SELECT ? as "STORE_CODE", ?  AS "SALES_DATE" ';
			$rs=$this->db->query($statement,array($store_code,$sales_date))->result();
			return $rs;
		}*/
		
	}

	public function get_data_batch_val($id_bank,$id_deposit)
	{
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}

		$statement = 'SELECT CTB."CDC_DEPOSIT_ID" DEPOSIT_ID, CTB."CDC_BATCH_ID" BATCH_ID, CTB."CDC_BATCH_NUMBER" BATCH_NUMBER, CTB."CDC_REFF_NUM" REFERENCE_NUM, CTB."CDC_BATCH_TYPE" BATCH_TYPE, SU."USER_NAME" USER_NAME, TO_CHAR(CTB."CDC_BATCH_DATE", \'DD Month YYYY\') BATCH_DATE, (select SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_receipts where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID") as ACTUAL_TOTAL_AMOUNT, coalesce((select sum(COALESCE("CDC_GTU_AMOUNT",0)) from cdc_trx_gtu where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID"),0) CHECK_EXCHANGES_TOTAL_AMOUNT, (select SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_receipts where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID") - coalesce((select sum("CDC_GTU_AMOUNT") from cdc_trx_gtu where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID"),0) as DEPOSIT FROM cdc_trx_batches CTB, sys_user_2 SU WHERE CTB."CREATED_BY" = SU."USER_ID" AND CTB."CDC_BANK_ID" = ? AND CTB."CDC_BRANCH_ID" = ? AND CTB."CDC_DEPOSIT_ID" = ? AND CTB."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.' ORDER BY CTB."CDC_DEPOSIT_ID", CTB."CDC_BATCH_DATE" DESC';
		return $this->db->query($statement,array($id_bank,$this->session->userdata('branch_id'),$id_deposit))->result();
	}

	public function save_data_deposit($data,$dep_id)
	{
		$vir_status = 'N';
		$cek_deposit=0;
		if ($dep_id == 0) {
			$rows = $data['rows'];
	      	$statement = 'insert into cdc_trx_deposit("CDC_BANK_ID","CDC_DEPOSIT_NUM","DEPOSIT_DATE","MUTATION_DATE","DEPOSIT_STATUS","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","ACTUAL_TOTAL_SELECTED","CHECK_TOTAL_SELECTED","DEPOSIT_TOTAL_SELECTED","CDC_DC_CODE") values(?,?,?,?,?,?,?,current_date,?,CURRENT_TIMESTAMP,?,?,?,?)';
	     	 $this->db->query($statement,array($data['id_bank'],$data['dep_num'],$data['dep_date'].' '.$data['dep_jam'].':'.$data['dep_min'].':00',$data['mut_date'],'N',$this->session->userdata('branch_code'),$this->session->userdata('usrId'),$this->session->userdata('usrId'),$data['ats'],$data['cts'],$data['dts'],$this->session->userdata('dc_code')));
	      	$deposit_id = $this->db->insert_id();
	      	$statement_cek_deposit='select count(*) as hitung from cdc_trx_deposit where "CDC_DEPOSIT_ID"=?';
					$cek_deposit=$this->db->query($statement_cek_deposit,$deposit_id)->row()->hitung;
					if($cek_deposit>0)
					{
							foreach ($rows as $key) {
								$statement2 = 'update cdc_trx_batches set "CDC_DEPOSIT_ID" = ? where "CDC_BATCH_ID" = ?';
								$this->db->query($statement2,array($deposit_id,$key['batch_id']));
								$statement3 = 'SELECT CDM."TRX_DETAIL_MINUS_ID" FROM CDC_TRX_DETAIL_MINUS CDM, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB WHERE CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CDM."TRX_MINUS_ID" = 23 AND CTB."CDC_BATCH_ID" = ?';
								if ($this->db->query($statement3, $key['batch_id'])->num_rows() > 0) {
									$vir_status = 'Y';
								}
							}
							$statement4 = 'UPDATE cdc_trx_deposit SET "VIR_STATUS" = ? WHERE "CDC_DEPOSIT_ID" = ?';
							$this->db->query($statement4, array(strval($vir_status), intval($deposit_id)));
							return $deposit_id;
					}else{
						return 0;
					}
			
		}else{
			$statement_cek_deposit='select count(*) as hitung from cdc_trx_deposit where "CDC_DEPOSIT_ID"=?';
			$cek_deposit=$this->db->query($statement_cek_deposit,$dep_id)->row()->hitung;
			if($cek_deposit>0)
			{
					$rows = $data['rows'];
	      	$statement = 'update cdc_trx_deposit set "CDC_BANK_ID" = ?, "CDC_DEPOSIT_NUM" = ?, "DEPOSIT_DATE" = ?, "MUTATION_DATE" = ?, "DEPOSIT_STATUS" = \'N\', "BRANCH_CODE" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP, "ACTUAL_TOTAL_SELECTED" = ?, "CHECK_TOTAL_SELECTED" = ?, "DEPOSIT_TOTAL_SELECTED" = ? where "CDC_DEPOSIT_ID" = ?';
	     	 	$this->db->query($statement,array($data['id_bank'],$data['dep_num'],$data['dep_date'].' '.$data['dep_jam'].':'.$data['dep_min'].':00',$data['mut_date'],$this->session->userdata('branch_code'),$this->session->userdata('usrId'),$data['ats'],$data['cts'],$data['dts'],$dep_id));
	     	 $statement3 = 'update cdc_trx_batches set "CDC_DEPOSIT_ID" = NULL where "CDC_DEPOSIT_ID" = ?';
	     	 $this->db->query($statement3,$dep_id);
				foreach ($rows as $key) {
					$statement2 = 'update cdc_trx_batches set "CDC_DEPOSIT_ID" = ? where "CDC_BATCH_ID" = ?';
					$this->db->query($statement2,array($dep_id,$key['batch_id']));
					$statement4 = 'SELECT CDM."TRX_DETAIL_MINUS_ID" FROM CDC_TRX_DETAIL_MINUS CDM, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB WHERE CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CDM."TRX_MINUS_ID" = 23 AND CTB."CDC_BATCH_ID" = ?';
					if ($this->db->query($statement4, $key['batch_id'])->num_rows() > 0) {
						$vir_status = 'Y';
					}
				}
				$statement5 = 'UPDATE cdc_trx_deposit SET "VIR_STATUS" = ? WHERE "CDC_DEPOSIT_ID" = ?';
				$this->db->query($statement5, array(strval($vir_status), intval($dep_id)));
				return $dep_id;

			}else{
				return 0;
			}
			
		}
	}

	public function get_data_deposit($page,$rows,$deposit_num,$deposit_date,$mutation_date,$bank_id,$username)
	{
		$page = ($page - 1) * $rows;
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}

		$statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CMB."BANK_NAME" BANK_NAME, CMB."BANK_ID" BANK_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, CTD."DEPOSIT_DATE" DEPOSIT_DATE, CTD."MUTATION_DATE" MUTATION_DATE, CTD."DEPOSIT_STATUS" STATUS, SU."USER_NAME" CREATED_BY, CTD."ACTUAL_TOTAL_SELECTED" ATS, CTD."CHECK_TOTAL_SELECTED" CTS, CTD."DEPOSIT_TOTAL_SELECTED" DTS, CTD."PRINT_COUNT" CETAKAN_KE FROM cdc_trx_deposit CTD, sys_user_2 SU, cdc_master_bank CMB WHERE CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTD."CREATED_BY" = SU."USER_ID" AND CTD."LAST_UPDATE_BY" = SU."USER_ID" AND CTD."DEPOSIT_STATUS" = \'N\' AND CTD."BRANCH_CODE" = ? AND CTD."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.'';
		if ($bank_id != '') {
			$statement .= ' AND CMB."BANK_ID" = '.$bank_id.'';
		}
		if ($deposit_num != '') {
			$statement .= ' AND CTD."CDC_DEPOSIT_NUM" like \'%'.$deposit_num.'%\'';
		}
		if ($deposit_date != '') {
			$statement .= ' AND date_part(\'year\', CTD."DEPOSIT_DATE")||\'-\'||lpad(cast(date_part(\'month\', CTD."DEPOSIT_DATE") as varchar),2,\'0\')||\'-\'||lpad(cast(date_part(\'day\', CTD."DEPOSIT_DATE") as varchar),2,\'0\') = \''.$deposit_date.'\'';
		}
		if ($mutation_date != '') {
			$statement .= ' AND CTD."MUTATION_DATE" = \''.$mutation_date.'\'';
		}
		if ($username != '') {
			$statement .= ' AND SU."USER_NAME" like \'%'.$username.'%\'';
		}
		$statement .= ' order by CTD."CREATION_DATE" desc limit '.$rows.' offset '.$page.'';

		return $this->db->query($statement,$this->session->userdata('branch_code'))->result();
	}

	public function get_data_deposit_rows()
	{
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}

		$statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CMB."BANK_NAME" BANK_NAME, CMB."BANK_ID" BANK_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, CTD."DEPOSIT_DATE" DEPOSIT_DATE, CTD."MUTATION_DATE" MUTATION_DATE, CTD."DEPOSIT_STATUS" STATUS, SU."USER_NAME" CREATED_BY, CTD."ACTUAL_TOTAL_SELECTED" ATS, CTD."CHECK_TOTAL_SELECTED" CTS, CTD."DEPOSIT_TOTAL_SELECTED" DTS FROM cdc_trx_deposit CTD, sys_user_2 SU, cdc_master_bank CMB WHERE CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTD."CREATED_BY" = SU."USER_ID" AND CTD."LAST_UPDATE_BY" = SU."USER_ID" AND CTD."DEPOSIT_STATUS" = \'N\' AND CTD."BRANCH_CODE" = ? AND CTD."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.'';
		return $this->db->query($statement,$this->session->userdata('branch_code'))->num_rows();
	}

	public function validate_deposit($dep_id)
	{
		$statement = 'update cdc_trx_deposit set "DEPOSIT_STATUS" = \'V\' where "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement,$dep_id);
		return $this->db->affected_rows();
	}

	public function delete_deposit($dep_id)
	{
		$statement = 'delete from cdc_trx_deposit where "CDC_DEPOSIT_ID" = ?';
		$statement2 = 'update cdc_trx_batches set "CDC_DEPOSIT_ID" = NULL where "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement,$dep_id);
		$this->db->query($statement2,$dep_id);
		return $this->db->affected_rows();
	}

	public function reject_deposit($dep_id)
	{
		$statement = 'UPDATE cdc_trx_deposit SET "DEPOSIT_STATUS" = \'N\', "PRINT_COUNT" = 0 WHERE "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement,$dep_id);
		return $this->db->affected_rows();
	}

	public function get_data_deposit_validate($page,$rows,$deposit_num,$deposit_date,$mutation_date,$bank_id,$status,$username)
	{
		$page = ($page - 1) * $rows;
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}

		$statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CMB."BANK_NAME" BANK_NAME, CMB."BANK_ID" BANK_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, TO_CHAR(CTD."DEPOSIT_DATE", \'DD Month YYYY\') DEPOSIT_DATE, TO_CHAR(CTD."MUTATION_DATE", \'DD Month YYYY\') MUTATION_DATE, CTD."DEPOSIT_STATUS" STATUS, SU."USER_NAME" CREATED_BY, CTD."ACTUAL_TOTAL_SELECTED" ATS, CTD."CHECK_TOTAL_SELECTED" CTS, CTD."DEPOSIT_TOTAL_SELECTED" DTS, CTD."PRINT_COUNT" CETAKAN_KE FROM cdc_trx_deposit CTD, sys_user_2 SU, cdc_master_bank CMB WHERE CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTD."CREATED_BY" = SU."USER_ID" AND CTD."LAST_UPDATE_BY" = SU."USER_ID" /*AND CTD."DEPOSIT_STATUS" = \'V\'*/ AND CTD."BRANCH_CODE" = ? AND CTD."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.'';
		if ($bank_id != '') {
			$statement .= ' AND CMB."BANK_ID" = '.$bank_id.'';
		}
		if ($deposit_num != '') {
			$statement .= ' AND CTD."CDC_DEPOSIT_NUM" like \'%'.$deposit_num.'%\'';
		}
		if ($deposit_date != '') {
			$statement .= ' AND date_part(\'year\', CTD."DEPOSIT_DATE")||\'-\'||lpad(cast(date_part(\'month\', CTD."DEPOSIT_DATE") as varchar),2,\'0\')||\'-\'||lpad(cast(date_part(\'day\', CTD."DEPOSIT_DATE") as varchar),2,\'0\') = \''.$deposit_date.'\'';
		}
		if ($mutation_date != '') {
			$statement .= ' AND CTD."MUTATION_DATE" = \''.$mutation_date.'\'';
		}
		if ($status != '') {
			$statement .= ' AND CTD."DEPOSIT_STATUS" = \''.$status.'\'';
		}
		if ($username != '') {
			$statement .= ' AND SU."USER_NAME" like \'%'.$username.'%\'';
		}
		$statement .= ' ORDER BY CTD."CREATION_DATE" DESC';

		$result['total'] = $this->db->query($statement,$this->session->userdata('branch_code'))->num_rows();
		
		$statement .= ' limit '.$rows.' offset '.$page.'';

		$result['rows'] = $this->db->query($statement,$this->session->userdata('branch_code'))->result();
		return $result;
	}

	public function get_data_deposit_validate_rows()
	{
		$indc_code  = '';
		$in_role = '';

		if ($this->session->userdata('dc_type') == 'DCI') {
			$indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
		}else{
			$indc_code = "'".$this->session->userdata('dc_code')."'";
		}

		if ($this->session->userdata('role_id') < 3) {
			$in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
		}
		else{
			$in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
		}
		
		$statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CMB."BANK_NAME" BANK_NAME, CMB."BANK_ID" BANK_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, CTD."DEPOSIT_DATE" DEPOSIT_DATE, CTD."MUTATION_DATE" MUTATION_DATE, CTD."DEPOSIT_STATUS" STATUS, SU."USER_NAME" CREATED_BY, CTD."ACTUAL_TOTAL_SELECTED" ATS, CTD."CHECK_TOTAL_SELECTED" CTS, CTD."DEPOSIT_TOTAL_SELECTED" DTS FROM cdc_trx_deposit CTD, sys_user_2 SU, cdc_master_bank CMB WHERE CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTD."CREATED_BY" = SU."USER_ID" AND CTD."LAST_UPDATE_BY" = SU."USER_ID"/* AND CTD."DEPOSIT_STATUS" = \'V\'*/ AND CTD."BRANCH_CODE" = ? AND CTD."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.'';
		return $this->db->query($statement,$this->session->userdata('branch_code'))->num_rows();
	}

	public function get_branch_name($branch_id)
	{
		$statement = 'select "BRANCH_NAME" from cdc_master_branch where "BRANCH_ID" = ?';
		return $this->db->query($statement,$branch_id)->result();
	}

	public function get_report_deposit($deposit_id,$bank_id)
	{
		$statement = 'SELECT CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME",
					(SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0))+ SUM(COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0))+ SUM(COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0))+ SUM(COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0))+
					sum(COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0))+sum(COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0))) NOMINAL
					FROM CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD
					WHERE CMBA."BANK_ID" = CMB."BANK_ID" AND CMBA."BANK_ACCOUNT_ID" = CMT."BANK_ACCOUNT_ID" AND CMT."STORE_ID" = CTR."STORE_ID"
					AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_DEPOSIT_ID" = ? AND CMBA."BANK_ID" = ?
					AND CMT."STORE_TYPE" = \'F\'
					GROUP BY CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME" ORDER BY CMBA."BANK_ACCOUNT_ID" DESC';
		$statement2 = 'SELECT CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME",
					(SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0))+ SUM(COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0))+ SUM(COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0))+ SUM(COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0))+
					sum(COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0))+sum(COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)))
					- COALESCE((SELECT SUM(GU."CDC_GTU_AMOUNT") FROM CDC_TRX_GTU GU, CDC_TRX_BATCHES CB, CDC_TRX_DEPOSIT CD WHERE GU."CDC_BATCH_ID" = CB."CDC_BATCH_ID" AND CB."CDC_DEPOSIT_ID" = CD."CDC_DEPOSIT_ID" AND CD."CDC_DEPOSIT_ID" = ?),0) NOMINAL
					FROM CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD
					WHERE CMBA."BANK_ID" = CMB."BANK_ID" AND CMBA."BANK_ACCOUNT_ID" = CMT."BANK_ACCOUNT_ID" AND CMT."STORE_ID" = CTR."STORE_ID"
					AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_DEPOSIT_ID" = ? AND CMBA."BANK_ID" = ?
					AND CMT."STORE_TYPE" = \'R\'
					GROUP BY CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME"';
					
		$res['frc'] = $this->db->query($statement,array($deposit_id,$bank_id))->result();
		$res['reg'] = $this->db->query($statement2,array($deposit_id,$deposit_id,$bank_id))->result();

		return $res;
	}

	public function get_report_other_format_deposit($deposit_id,$bank_id)
	{
		$statement = 'SELECT CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME", CMB."BANK_NAME", \'SALES FRC - \'||CTD."CDC_DEPOSIT_NUM" "REMARK",
					(SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0))+ SUM(COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0))+ SUM(COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0))+ SUM(COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0))+
					sum(COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0))+sum(COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0))) "NOMINAL"
					FROM CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD
					WHERE CMBA."BANK_ID" = CMB."BANK_ID" AND CMBA."BANK_ACCOUNT_ID" = CMT."BANK_ACCOUNT_ID" AND CMT."STORE_ID" = CTR."STORE_ID"
					AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_DEPOSIT_ID" = ? AND CMBA."BANK_ID" = ?
					AND CMT."STORE_TYPE" = \'F\'
					GROUP BY CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME", CMB."BANK_NAME", CTD."CDC_DEPOSIT_NUM" ORDER BY CMBA."BANK_ACCOUNT_ID" DESC';
		$statement2 = 'SELECT CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME", CMB."BANK_NAME", \'SALES REG - \'||CTD."CDC_DEPOSIT_NUM" "REMARK",
					(SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0))+ SUM(COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0))+ SUM(COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0))+
					SUM(COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0))+ SUM(COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0))+
					sum(COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0))+sum(COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)))
					- COALESCE((SELECT SUM(GU."CDC_GTU_AMOUNT") FROM CDC_TRX_GTU GU, CDC_TRX_BATCHES CB, CDC_TRX_DEPOSIT CD WHERE GU."CDC_BATCH_ID" = CB."CDC_BATCH_ID" AND CB."CDC_DEPOSIT_ID" = CD."CDC_DEPOSIT_ID" AND CD."CDC_DEPOSIT_ID" = ?),0) "NOMINAL"
					FROM CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD
					WHERE CMBA."BANK_ID" = CMB."BANK_ID" AND CMBA."BANK_ACCOUNT_ID" = CMT."BANK_ACCOUNT_ID" AND CMT."STORE_ID" = CTR."STORE_ID"
					AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_DEPOSIT_ID" = ? AND CMBA."BANK_ID" = ?
					AND CMT."STORE_TYPE" = \'R\'
					GROUP BY CMBA."BANK_ACCOUNT_ID", CMBA."BANK_ACCOUNT_NUM", CMBA."BANK_ACCOUNT_NAME", CMB."BANK_NAME", CTD."CDC_DEPOSIT_NUM"';
					
		$res['frc'] = $this->db->query($statement,array($deposit_id,$bank_id))->result();
		$res['reg'] = $this->db->query($statement2,array($deposit_id,$deposit_id,$bank_id))->result();

		return $res;
	}

	public function get_deposit_num($deposit_id)
	{
		$statement = 'SELECT "CDC_DEPOSIT_NUM","DEPOSIT_DATE" FROM cdc_trx_deposit WHERE "CDC_DEPOSIT_ID" = ?';
		return $this->db->query($statement,$deposit_id)->result();
	}

	public function up_cetakan_deposit($deposit_id,$cetakan)
	{
		$statement = 'update cdc_trx_deposit set "PRINT_COUNT" = ? where "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement,array($cetakan,$deposit_id));
		return $this->db->affected_rows();
	}

	public function get_print_count($deposit_id)
	{
		$statement = 'select "PRINT_COUNT" CNT from cdc_trx_deposit where "CDC_DEPOSIT_ID" = ?';
		$count = $this->db->query($statement,$deposit_id)->result();
		return $count[0]->cnt;
	}

	public function get_status_deposit($deposit_id)
	{
		$statement = 'select "DEPOSIT_STATUS" STAT from cdc_trx_deposit where "CDC_DEPOSIT_ID" = ?';
		$status = $this->db->query($statement,$deposit_id)->result();
		return $status[0]->stat;
	}

	public function cek_deposit_num($dep_num)
	{
		$statement = 'SELECT * FROM CDC_TRX_DEPOSIT WHERE TRIM("CDC_DEPOSIT_NUM") = TRIM(?)';
		return $this->db->query($statement,str_replace('-', '', str_replace('_', '', $dep_num)))->num_rows();
	}

	public function get_all_branch()
	{
		$statement = 'SELECT BTRIM("BRANCH_CODE") "BRANCH_CODE" FROM CDC_MASTER_BRANCH';
		return $this->db->query($statement)->result();
	}

	public function get_data_transfer($deposit_id)
	{
		$statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, to_char(CTD."DEPOSIT_DATE", \'DD-Mon-YY\') DEPOSIT_DATE, to_char(CTD."MUTATION_DATE", \'DD-Mon-YY\') MUTATION_DATE, CTD."DEPOSIT_STATUS" DEPOSIT_STATUS, btrim(CTD."BRANCH_CODE") BRANCH_CODE, CMB."BANK_NAME" BANK_NAME, CTB."CDC_BATCH_ID" BATCH_ID, CTB."CDC_BATCH_NUMBER" BATCH_NUMBER, CTB."CDC_BATCH_TYPE" BATCH_TYPE, to_char(CTB."CDC_BATCH_DATE", \'DD-Mon-YY\') BATCH_DATE, CTB."CDC_BATCH_STATUS" BATCH_STATUS, \'SOURCE DATA WEB\' DESCRIPTION, CTB."CDC_REFF_NUM" REFF_NUM, CTR."CDC_REC_ID" REC_ID, CMT."STORE_CODE" STORE_CODE, to_char(CTR."SALES_DATE", \'DD-Mon-YY\') SALES_DATE, CTR."STATUS" STATUS, COALESCE(CTR."ACTUAL_SALES_AMOUNT",0) ACTUAL_SALES_AMOUNT, COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0) ACTUAL_RRAK_AMOUNT, COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0) ACTUAL_PAY_LESS_DEPOSITED, COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0) ACTUAL_VOUCHER_AMOUNT, COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0) ACTUAL_LOST_ITEM_PAYMENT, COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0) ACTUAL_WU_ACCOUNTABILITY, COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0) ACTUAL_OTHERS_AMOUNT, btrim(CTR."ACTUAL_OTHERS_DESC") ACTUAL_OTHERS_DESC, COALESCE(CTR."RRAK_DEDUCTION",0) RRAK_DEDUCTION, COALESCE(CTR."LESS_DEPOSIT_DEDUCTION",0) LESS_DEPOSIT_DEDUCTION, COALESCE(CTR."OTHERS_DEDUCTION",0) OTHERS_DEDUCTION, btrim(CTR."OTHERS_DESC") OTHERS_DESC, COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0) ACTUAL_VIRTUAL_PAY_LESS, CTR."ACTUAL_SALES_FLAG" ACTUAL_SALES_FLAG, COALESCE(CTR."VIRTUAL_PAY_LESS_DEDUCTION",0) VIRTUAL_PAY_LESS_DEDUCTION, CMBA."BANK_ACCOUNT_NUM", CTB."CDC_BATCH_TYPE" BATCH_TYPE FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT, CDC_MASTER_BANK_ACCOUNT CMBA WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CMT."BANK_ACCOUNT_ID" = CMBA."BANK_ACCOUNT_ID" AND CTD."DEPOSIT_STATUS" = \'V\' AND CTB."CDC_BATCH_STATUS" = \'V\' AND CTD."TRANSFER_FLAG" = \'N\' AND CTB."TRANSFER_FLAG" = \'N\' AND CTR."TRANSFER_FLAG" = \'N\' AND CTD."CDC_DEPOSIT_ID" = ? AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?)';
		return $this->db->query($statement,array(intval($deposit_id),strval($this->session->userdata('branch_code'))))->result();
	}

	public function get_data_gtu_transfer($batch_id)
	{
		$statement = 'SELECT GTU."CDC_GTU_ID", GTU."CDC_BATCH_ID", MB."BRANCH_CODE", CMB."BANK_NAME", GTU."CDC_GTU_NUMBER", GTU."CDC_GTU_AMOUNT", GTU."CDC_GTU_DATE" FROM CDC_TRX_GTU GTU, CDC_MASTER_BRANCH MB, CDC_MASTER_BANK CMB WHERE GTU."CDC_BRANCH_ID" = MB."BRANCH_ID" AND GTU."CDC_BANK_ID" = CMB."BANK_ID" AND GTU."CDC_BATCH_ID" = ?';
		return $this->db->query($statement,intval($batch_id))->result();
	}

	public function get_data_pnb_transfer($rec_id)
	{
		$statement = 'SELECT CDT."TRX_DETAIL_ID", CDT."TRX_CDC_REC_ID", BTRIM(CDP."TRX_PLUS_NAME") TRX_PLUS_NAME, TO_CHAR(CDT."TRX_DETAIL_DATE",\'DD-Mon-YY\') TRX_DETAIL_DATE, BTRIM(CDT."TRX_DETAIL_DESC") TRX_DETAIL_DESC, CDT."TRX_DET_AMOUNT", CDT."TRX_PLUS_ID" FROM CDC_TRX_DETAIL_TAMBAH CDT, CDC_MASTER_DETAIL_PENAMBAH CDP WHERE CDT."TRX_PLUS_ID" = CDP."TRX_PLUS_ID" AND CDT."TRX_CDC_REC_ID" = ?';
		return $this->db->query($statement,intval($rec_id))->result();
	}

	public function get_data_pgr_transfer($rec_id)
	{
		$statement = 'SELECT CDT."TRX_DETAIL_MINUS_ID", CDT."TRX_CDC_REC_ID", BTRIM(CDP."TRX_MINUS_NAME") TRX_MINUS_NAME, TO_CHAR(CDT."TRX_MINUS_DATE",\'DD-Mon-YY\') TRX_MINUS_DATE, BTRIM(CDT."TRX_MINUS_DESC") TRX_MINUS_DESC, CDT."TRX_MINUS_AMOUNT", CDT."TRX_MINUS_ID" FROM CDC_TRX_DETAIL_MINUS CDT, CDC_MASTER_DETAIL_PENGURANG CDP WHERE CDT."TRX_MINUS_ID" = CDP."TRX_MINUS_ID" AND CDT."TRX_CDC_REC_ID" = ?';
		return $this->db->query($statement,intval($rec_id))->result();
	}

	public function get_data_vcr_transfer($rec_id)
	{
		$statement = 'SELECT "TRX_VOUCHER_ID", "TRX_CDC_REC_ID", BTRIM("TRX_VOUCHER_CODE") TRX_VOUCHER_CODE, "TRX_VOUCHER_NUMBER", "TRX_VOUCHER_CODE"||\' \'||"TRX_VOUCHER_NUMBER" VOUCHER_NUM, TO_CHAR("TRX_VOUCHER_DATE",\'DD-Mon-YY\') TRX_VOUCHER_DATE, BTRIM("TRX_VOUCHER_DESC") TRX_VOUCHER_DESC, "TRX_VOUCHER_AMOUNT" FROM CDC_TRX_VOUCHER WHERE "TRX_CDC_REC_ID" = ?';
		return $this->db->query($statement,intval($rec_id))->result();
	}

	public function update_status_deposit_transfer($deposit_id,$deposit_status)
	{
		if($deposit_status=='T'){
			$statement_deposit = 'UPDATE CDC_TRX_DEPOSIT SET "TRANSFER_FLAG" = \'Y\', "DEPOSIT_STATUS" = \'T\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_DEPOSIT_ID" = ?';
			$statement_batch = 'UPDATE CDC_TRX_BATCHES SET "TRANSFER_FLAG" = \'Y\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_DEPOSIT_ID" = ?';
			$statement_receipt = 'UPDATE CDC_TRX_RECEIPTS SET "TRANSFER_FLAG" = \'Y\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM CDC_TRX_BATCHES WHERE "CDC_DEPOSIT_ID" = ?)';
			$this->db->query($statement_receipt,intval($deposit_id));
			$this->db->query($statement_batch,intval($deposit_id));
			$this->db->query($statement_deposit,intval($deposit_id));
		}else if($deposit_status=='V'){
			$statement_deposit = 'UPDATE CDC_TRX_DEPOSIT SET "TRANSFER_FLAG" = \'N\', "DEPOSIT_STATUS" = \'V\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_DEPOSIT_ID" = ?';
			$statement_batch = 'UPDATE CDC_TRX_BATCHES SET "TRANSFER_FLAG" = \'N\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_DEPOSIT_ID" = ?';
			$statement_receipt = 'UPDATE CDC_TRX_RECEIPTS SET "TRANSFER_FLAG" = \'N\', "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM CDC_TRX_BATCHES WHERE "CDC_DEPOSIT_ID" = ?)';
			$this->db->query($statement_receipt,intval($deposit_id));
			$this->db->query($statement_batch,intval($deposit_id));
			$this->db->query($statement_deposit,intval($deposit_id));
		}
		
		return $this->db->affected_rows();
	}

	public function get_bank_stn()
	{
		$statement = 'SELECT * FROM cdc_master_bank';
		return $this->db->query($statement)->result();
	}

	public function get_bank_account_stn($bank_id)
	{
		$statement = 'SELECT * FROM CDC_MASTER_BANK_ACCOUNT WHERE "BANK_ID" = ? AND "BRANCH_ID" = ?';
		return $this->db->query($statement, array($bank_id, $this->session->userdata('branch_id')))->result();
	}

	public function get_data_kurset_virtual($deposit_num)
	{
		$statement = 'SELECT CDM.*, SU."USER_NAME", CTB."CDC_BATCH_ID", CTD."CDC_DEPOSIT_ID", CTB."CDC_BATCH_NUMBER", COALESCE((SELECT "STATUS" FROM CDC_TRX_DETAIL_INTERFACE WHERE "CDC_DET_ID" = CDM."TRX_DETAIL_MINUS_ID" AND "CDC_REC_ID" = CTR."CDC_REC_ID" AND "CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND "CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID"), \'N\') "CEK" FROM CDC_TRX_DETAIL_MINUS CDM, sys_user_2 SU, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD WHERE CDM."CREATED_BY" = SU."USER_ID" AND CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CDM."TRX_MINUS_ID" = 23 AND BTRIM(CTD."CDC_DEPOSIT_NUM") = BTRIM(?) AND CTD."DEPOSIT_STATUS" = \'V\' AND CTD."VIR_STATUS" <> \'N\' AND CTD."TRANSFER_FLAG" = \'N\' AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?) ORDER BY CDM."TRX_DETAIL_MINUS_ID"';

		return $this->db->query($statement,array($deposit_num, $this->session->userdata('branch_code')))->result();
	}

	public function get_data_kurset_virtual_by_id($deposit_id)
	{
		$statement = 'SELECT CDM.*, SU."USER_NAME", CTB."CDC_BATCH_ID", CTD."CDC_DEPOSIT_ID", CTB."CDC_BATCH_NUMBER", COALESCE((SELECT "STATUS" FROM CDC_TRX_DETAIL_INTERFACE WHERE "CDC_DET_ID" = CDM."TRX_DETAIL_MINUS_ID"), \'N\') "CEK" FROM CDC_TRX_DETAIL_MINUS CDM, sys_user_2 SU, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD WHERE CDM."CREATED_BY" = SU."USER_ID" AND CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CDM."TRX_MINUS_ID" = 23 AND CTD."CDC_DEPOSIT_ID" = ? AND CTD."DEPOSIT_STATUS" = \'V\' AND CTD."VIR_STATUS" <> \'N\' AND CTD."TRANSFER_FLAG" = \'N\' AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?) ORDER BY CDM."TRX_DETAIL_MINUS_ID"';

		return $this->db->query($statement,array($deposit_id, $this->session->userdata('branch_code')))->result();
	}

	public function cek_vir_status_deposit($dep_num)
	{
		$statement = 'SELECT "CDC_DEPOSIT_ID", "DEPOSIT_STATUS", "TRANSFER_FLAG", "VIR_STATUS" FROM CDC_TRX_DEPOSIT WHERE BTRIM("CDC_DEPOSIT_NUM") = BTRIM(?) AND BTRIM("BRANCH_CODE") = BTRIM(?)';
		return $this->db->query($statement, array($dep_num, $this->session->userdata('branch_code')))->result();
	}

	public function validate_vir($det_id, $rec_id, $batch_id, $deposit_id, $status)
	{
		$statement = 'SELECT * FROM CDC_TRX_DETAIL_INTERFACE WHERE "CDC_DET_ID" = ? AND "CDC_REC_ID" = ? AND "CDC_BATCH_ID" = ? AND "CDC_DEPOSIT_ID" = ?';
		if ($this->db->query($statement, array($det_id, $rec_id, $batch_id, $deposit_id))->num_rows() > 0) {
			$statement_2 = 'UPDATE CDC_TRX_DETAIL_INTERFACE SET "STATUS" = ? WHERE "CDC_DET_ID" = ? AND "CDC_REC_ID" = ? AND "CDC_BATCH_ID" = ? AND "CDC_DEPOSIT_ID" = ?';
			$this->db->query($statement_2, array($status, $det_id, $rec_id, $batch_id, $deposit_id));
		}else {
			$statement_2 = 'INSERT INTO CDC_TRX_DETAIL_INTERFACE VALUES(?,?,?,?,?)';
			$this->db->query($statement_2, array($deposit_id, $batch_id, $rec_id, $det_id, $status));
		}
		return $this->db->affected_rows();
	}

	public function update_deposit_vir($dep_id)
	{
		$statement = 'UPDATE cdc_trx_deposit SET "VIR_STATUS" = \'S\' WHERE "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement, $dep_id);
		return $this->db->affected_rows();
	}

	public function reset_status_vir($dep_id='')
	{
		$statement = 'UPDATE CDC_TRX_DETAIL_INTERFACE SET "STATUS" = \'N\' WHERE "CDC_DEPOSIT_ID" = ?';
		$this->db->query($statement, $dep_id);
		return $this->db->affected_rows();
	}

	public function get_deskripsi_kur_virtual($det_id)
	{
		$statement = 'SELECT *, BTRIM("TRX_MINUS_DESC") "DESK" FROM CDC_TRX_DETAIL_MINUS WHERE "TRX_DETAIL_MINUS_ID" = ?';
		return $this->db->query($statement, $det_id)->result();
	}

	public function update_deskripsi_virtual($det_id, $deskripsi)
	{
		$statement = 'UPDATE cdc_trx_detail_minus SET "TRX_MINUS_DESC" = ? WHERE "TRX_DETAIL_MINUS_ID" = ?';
		$this->db->query($statement, array($deskripsi, $det_id));
		return $this->db->affected_rows();
	}

	public function get_cabang_session($branch_id)
	{
		$statement = 'select * from cdc_master_branch where "BRANCH_ID" = ?';
		return $this->db->query($statement,$branch_id)->result();
	}

	public function get_data_listing_val_vir($dep_num)
	{
		$statement = 'SELECT TO_CHAR(CDM."TRX_MINUS_DATE", \'DD-Mon-YY\') "MIN_DATE", BTRIM(CDM."TRX_MINUS_DESC") "TRX_MINUS_DESC", CDM."TRX_MINUS_AMOUNT", SU."USER_NAME", CTB."CDC_BATCH_ID", CTD."CDC_DEPOSIT_ID", CTB."CDC_BATCH_NUMBER", COALESCE((SELECT "STATUS" FROM CDC_TRX_DETAIL_INTERFACE WHERE "CDC_DET_ID" = CDM."TRX_DETAIL_MINUS_ID"), \'N\') "CEK", CMT."STORE_CODE"||\' - \'||CMT."STORE_NAME" "STORE" FROM CDC_TRX_DETAIL_MINUS CDM, sys_user_2 SU, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_TOKO CMT WHERE CDM."CREATED_BY" = SU."USER_ID" AND CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CDM."TRX_MINUS_ID" = 23 AND BTRIM(CTD."CDC_DEPOSIT_NUM") = BTRIM(?) AND CTD."DEPOSIT_STATUS" = \'V\' AND CTD."VIR_STATUS" <> \'N\' AND CTD."TRANSFER_FLAG" = \'N\' AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?) ORDER BY CDM."TRX_DETAIL_MINUS_ID"';

		return $this->db->query($statement,array($dep_num, $this->session->userdata('branch_code')))->result();
	}

	public function cek_validasi_virtual($dep_id)
	{
		$statement = 'SELECT "VIR_STATUS" FROM CDC_TRX_DEPOSIT WHERE "CDC_DEPOSIT_ID" = ?';
		return $this->db->query($statement, $dep_id)->result();
	}

	public function get_data_change_vir($dep_id)
	{
		$statement = 'SELECT CDM."TRX_DETAIL_MINUS_ID", CTR."CDC_REC_ID", CTB."CDC_BATCH_ID", CTD."CDC_DEPOSIT_ID", CTB."CDC_BATCH_NUMBER", COALESCE((SELECT "STATUS" FROM CDC_TRX_DETAIL_INTERFACE WHERE "CDC_DET_ID" = CDM."TRX_DETAIL_MINUS_ID" AND "CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND "CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND "CDC_REC_ID" = CTR."CDC_REC_ID"), \'N\') "CEK" FROM CDC_TRX_DETAIL_MINUS CDM, sys_user_2 SU, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_TOKO CMT WHERE CDM."CREATED_BY" = SU."USER_ID" AND CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CDM."TRX_MINUS_ID" = 23 AND CTD."CDC_DEPOSIT_ID" = ? AND CTD."DEPOSIT_STATUS" = \'V\' AND CTD."VIR_STATUS" <> \'N\' AND CTD."TRANSFER_FLAG" = \'N\' AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?) ORDER BY CDM."TRX_DETAIL_MINUS_ID"';

		$statement_2 = 'SELECT DISTINCT CDM."TRX_CDC_REC_ID" FROM CDC_TRX_DETAIL_MINUS CDM, sys_user_2 SU, CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_TOKO CMT WHERE CDM."CREATED_BY" = SU."USER_ID" AND CDM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CDM."TRX_MINUS_ID" = 23 AND CTD."CDC_DEPOSIT_ID" = ? AND CTD."DEPOSIT_STATUS" = \'V\' AND CTD."VIR_STATUS" <> \'N\' AND CTD."TRANSFER_FLAG" = \'N\' AND BTRIM(CTD."BRANCH_CODE") = BTRIM(?)';

		$result['detail'] = $this->db->query($statement,array($dep_id, $this->session->userdata('branch_code')))->result();

		$result['receipt'] = $this->db->query($statement_2,array($dep_id, $this->session->userdata('branch_code')))->result();

		return $result;
	}

	public function update_master_min_virtual($det_id)
	{
		$statement = 'UPDATE CDC_TRX_DETAIL_MINUS SET "TRX_MINUS_ID" = 22 WHERE "TRX_DETAIL_MINUS_ID" = ?';
		$this->db->query($statement, $det_id);
		return $this->db->affected_rows();
	}

	public function update_sum_receipt_min_vir($rec_id)
	{
		$statement = 'UPDATE CDC_TRX_RECEIPTS SET "LESS_DEPOSIT_DEDUCTION" = (SELECT SUM("TRX_MINUS_AMOUNT") FROM CDC_TRX_DETAIL_MINUS WHERE "TRX_MINUS_ID" = 22 AND "TRX_CDC_REC_ID" = ?), "VIRTUAL_PAY_LESS_DEDUCTION" = (SELECT SUM("TRX_MINUS_AMOUNT") FROM CDC_TRX_DETAIL_MINUS WHERE "TRX_MINUS_ID" = 23 AND "TRX_CDC_REC_ID" = ?) WHERE "CDC_REC_ID" = ?';
		$this->db->query($statement, array($rec_id,$rec_id,$rec_id));
		return $this->db->affected_rows();
	}

	public function get_data_kur_transfer($rec_id)
	{
		$statement = 'SELECT "CDC_REC_ID", "CDC_INV_AR_NUM", "STORE_CODE", TO_CHAR("CDC_TRX_DATE", \'DD-Mon-YY\') "TRX_DATE", "CDC_DESC", "CDC_AMOUNT", "CDC_ACTUAL_AMOUNT", "TEMPLATE_FLAG" FROM CDC_TRX_KURSET_LINES WHERE "CDC_REC_ID" = ? AND "CDC_ACTUAL_AMOUNT" <> 0 group by "CDC_REC_ID", "CDC_INV_AR_NUM", "STORE_CODE", TO_CHAR("CDC_TRX_DATE", \'DD-Mon-YY\'), "CDC_DESC", "CDC_AMOUNT", "CDC_ACTUAL_AMOUNT", "TEMPLATE_FLAG" ';
		return $this->db->query($statement, $rec_id)->result();
	}

	public function get_batch_sum($deposit_id)
	{
		/*$statement = 'SELECT cmt."STORE_CODE", cmt."STORE_TYPE" ,count(cmt.*) FROM cdc_master_toko cmt, cdc_trx_receipts ctr, cdc_trx_batches ctb, cdc_trx_deposit ctd WHERE cmt."STORE_ID" = ctr."STORE_ID" AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID" AND ctd."CDC_DEPOSIT_ID" = ? AND cmt."STORE_TYPE" = \'F\' GROUP BY cmt."STORE_CODE", cmt."STORE_TYPE" ORDER BY cmt."STORE_CODE"';*/
		
		$statement = '
						SELECT cmt."STORE_CODE", cmt."STORE_TYPE", COUNT (cmt.*)
					    FROM cdc_master_toko cmt,
					         cdc_trx_receipts ctr,
					         cdc_trx_batches ctb,
					         cdc_trx_deposit ctd
					   WHERE     cmt."STORE_ID" = ctr."STORE_ID"
					         AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
					         AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID"
					         AND ctd."CDC_DEPOSIT_ID" = ?
					         AND cmt."STORE_TYPE" = \'F\'
					         AND (   ctr."ACTUAL_SALES_FLAG" = \'Y\'
					              OR (    ctr."ACTUAL_SALES_FLAG" = \'N\'
					                  AND (   COALESCE (CTR."ACTUAL_RRAK_AMOUNT", 0) > 0
					                       OR COALESCE (CTR."ACTUAL_PAY_LESS_DEPOSITED", 0) > 0
					                       OR COALESCE (CTR."ACTUAL_VOUCHER_AMOUNT", 0) > 0
					                       OR COALESCE (CTR."ACTUAL_LOST_ITEM_PAYMENT", 0) > 0
					                       OR COALESCE (CTR."ACTUAL_WU_ACCOUNTABILITY", 0) > 0
					                       OR COALESCE (CTR."ACTUAL_OTHERS_AMOUNT", 0) > 0
										   OR COALESCE (CTR."ACTUAL_VIRTUAL_PAY_LESS", 0) > 0
										   OR COALESCE (CTR."ACTUAL_SALES_AMOUNT", 0) > 0)))
					GROUP BY cmt."STORE_CODE", cmt."STORE_TYPE"
					ORDER BY cmt."STORE_CODE"
		';
		return $this->db->query($statement, $deposit_id)->num_rows();
	}

	public function get_batch_sum_reg($deposit_id)
	{
		$statement = 'SELECT cmt."STORE_CODE", cmt."STORE_TYPE" ,count(cmt.*) FROM cdc_master_toko cmt, cdc_trx_receipts ctr, cdc_trx_batches ctb, cdc_trx_deposit ctd WHERE cmt."STORE_ID" = ctr."STORE_ID" AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID" AND ctd."CDC_DEPOSIT_ID" = ? AND cmt."STORE_CODE" <> \'KTR\' AND cmt."STORE_TYPE" = \'R\' GROUP BY cmt."STORE_CODE", cmt."STORE_TYPE" ORDER BY cmt."STORE_CODE"';
		return $this->db->query($statement, $deposit_id)->num_rows();
	}

	public function get_batch_sum_ktr($deposit_id)
	{
		$statement = 'SELECT cmt."STORE_CODE", cmt."STORE_TYPE" ,count(cmt.*) FROM cdc_master_toko cmt, cdc_trx_receipts ctr, cdc_trx_batches ctb, cdc_trx_deposit ctd WHERE cmt."STORE_ID" = ctr."STORE_ID" AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID" AND ctd."CDC_DEPOSIT_ID" = ? AND cmt."STORE_CODE" = \'KTR\' AND cmt."STORE_TYPE" = \'R\' GROUP BY cmt."STORE_CODE", cmt."STORE_TYPE" ORDER BY cmt."STORE_CODE"';
		return $this->db->query($statement, $deposit_id)->num_rows();
	}

	public function get_batch_sum_stl($deposit_id)
	{
		$statement = 'SELECT cmt."STORE_CODE", cmt."STORE_TYPE" ,count(cmt.*) FROM cdc_master_toko cmt, cdc_trx_receipts ctr, cdc_trx_batches ctb, cdc_trx_deposit ctd WHERE cmt."STORE_ID" = ctr."STORE_ID" AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID" AND ctd."CDC_DEPOSIT_ID" = ? AND cmt."STORE_CODE" = \'KTR\' GROUP BY cmt."STORE_CODE", cmt."STORE_TYPE" ORDER BY cmt."STORE_CODE"';
		return $this->db->query($statement, $deposit_id)->num_rows();
	}

	public function get_data_stl_transfer($rec_id)
	{
		$statement = 'SELECT STL.*, CMS."DESCRIPTION" "CATEGORY" FROM CDC_TRX_STL STL, CDC_MASTER_STL CMS WHERE STL."CDC_MASTER_STL_ID" = CMS."CDC_MASTER_STL_ID" AND STL."CDC_REC_ID" = ?';
		return $this->db->query($statement, array($rec_id))->result();
	}

}

/* End of file mod_deposit.php */
/* Location: ./application/models/mod_deposit.php */