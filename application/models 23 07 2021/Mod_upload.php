<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_upload extends CI_Model {

	public function insert_am_as($nik_am,$nama_am,$shortname_am,$nik_as,$nama_as,$shortname_as,$store_code,$kode_gudang)
	{
		$statement = 'INSERT INTO CDC_MASTER_AM_AS("AMAS_ID","AM_NUMBER","AM_NAME","AM_SHORT","AS_NUMBER","AS_NAME","AS_SHORT","STORE_CODE","BRANCH_CODE","ACTIVE_FLAG") VALUES(?,?,?,?,?,?,?,trim(?),?,\'Y\')';
		
		$statement_2 = 'SELECT COALESCE(MAX("AMAS_ID")+1,1) "AMS_ID" FROM CDC_MASTER_AM_AS';
		$statement_3 = 'SELECT * FROM CDC_MASTER_TOKO WHERE "STORE_CODE" = \''.substr($store_code, 0, 4).'\'';
		
		if ($this->db->query($statement_3)->num_rows() > 0) {
			$ams_id = $this->db->query($statement_2)->result();
			$this->db->query($statement,array($ams_id[0]->AMS_ID,$nik_am,$nama_am,$shortname_am,$nik_as,$nama_as,$shortname_as,trim($store_code),$this->session->userdata('branch_code')));
			return $this->db->affected_rows();
		}else{
			return 0;
		}
	}

	public function cek_am_as_ins($shortname_am,$shortname_as,$store_code)
	{
		$statement = 'SELECT * FROM CDC_MASTER_AM_AS WHERE "AM_SHORT" = ? AND "AS_SHORT" = ? AND "STORE_CODE" = ?';
		return $this->db->query($statement,array($shortname_am,$shortname_as,$store_code))->num_rows(); 
	}

	public function cek_am_as_up($store_code)
	{
		$statement = 'SELECT "AMAS_ID" FROM CDC_MASTER_AM_AS WHERE "STORE_CODE" = ?';
		if ($ret = $this->db->query($statement,array($store_code))->result()) {
			return $ret[0]->AMAS_ID;
		}else{
			return 0;
		}
	}

	public function cek_store($store_code)
	{
		$statement='SELECT COUNT(*)AS cek FROM cdc_stores where "STORE_CODE"=?  AND "BRANCH_CODE"=?';
		$ret = $this->db->query($statement,array($store_code,$this->session->userdata('branch_code')))->row();
		return $ret->cek;

	}

	public function update_am_as($nik_am,$nama_am,$shortname_am,$nik_as,$nama_as,$shortname_as,$store_code,$amas_id)
	{
		$statement = 'UPDATE CDC_MASTER_AM_AS SET "AM_NUMBER" = ?, "AM_NAME" = ?, "AM_SHORT" = ?, "AS_NUMBER" = ?, "AS_NAME" = ?, "AS_SHORT" = ?, "STORE_CODE" = ?, "BRANCH_CODE" = (SELECT CMB."BRANCH_CODE" FROM CDC_MASTER_TOKO CMT, CDC_MASTER_BRANCH CMB WHERE CMT."BRANCH_ID" = CMB."BRANCH_ID" AND CMT."STORE_CODE" = ?) WHERE "AMAS_ID" = ?';
		$this->db->query($statement,array($nik_am,$nama_am,$shortname_am,$nik_as,$nama_as,$shortname_as,$store_code,$store_code,$amas_id));
		return $this->db->affected_rows();
	}

	public function delete_current_amas($brach_code)
	{
		$statement = 'DELETE FROM CDC_MASTER_AM_AS WHERE "BRANCH_CODE" = ?';
		$this->db->query($statement,$brach_code);
	}

	public function cek_data_go($kode_toko,$tgl)
	{
		$statement = 'SELECT * FROM CDC_GO_TABLE WHERE "STORE_CODE" = ? AND "TGL_KIRIM" = TO_DATE(?,\'DD/MM/YYYY\')';
		return $this->db->query($statement,array($kode_toko,$tgl))->num_rows();
	}

	public function ins_data_go($store_code,$tgl,$jam,$no_polisi,$no_lambung,$nama_supir)
	{
		$statement = 'INSERT INTO CDC_GO_TABLE("STORE_CODE","TGL_KIRIM","JAM_KIRIM","NO_POLISI","NO_LAMBUNG","NAMA_SUPIR","BRANCH_CODE") VALUES(?,TO_DATE(?,\'DD/MM/YYYY\'),?,?,?,?,?)';
		$this->db->query($statement,array($store_code,$tgl,$jam,$no_polisi,$no_lambung,$nama_supir,str_replace(" ", "", $this->session->userdata('branch_code'))));
		return $this->db->affected_rows();
	}

	public function cek_data_voucher($code,$num)
	{
		$statement = 'SELECT * FROM CDC_MASTER_DETAIL_VOUCHER WHERE "VOUCHER_CODE" = ? AND "VOUCHER_NUMBER" = ?';
		return $this->db->query($statement,array($code,$num))->num_rows();
	}

	public function ins_data_voucher($code,$num,$amount)
	{
		/*var_dump($code.$num.$amount);*/
		$statement = 'INSERT INTO CDC_MASTER_DETAIL_VOUCHER("VOUCHER_ID","VOUCHER_CODE","VOUCHER_NUMBER","VOUCHER_NOMINAL","USED_FLAG","CREATION_DATE","LAST_UPDATE_DATE") VALUES(?,?,?,?,\'N\',current_date,current_date)';
		$statement_2 = 'SELECT MAX("VOUCHER_ID")+1 "ID_VOC" FROM CDC_MASTER_DETAIL_VOUCHER';
		$id_voc = $this->db->query($statement_2)->result();
		$this->db->query($statement,array($id_voc[0]->ID_VOC,$code,$num,$amount));
		return $this->db->affected_rows();
	}

	
	//UPLOAD STN
	public function insert_stn_to_tmp($store_code,$sales_date,$sales_amount,$branch_code,$bank,$bank_account,$mutation_date,$sess_id,$user_id){
		$statement = 'INSERT INTO cdc_receipts_tmp("SESSION_ID","USER_ID","STORE_CODE","SALES_DATE","SALES_AMOUNT","BRANCH_CODE","BANK","BANK_ACCOUNT","MUTATION_DATE","CREATED_DATE") VALUES(?,?,?,?,?,?,?,?,?,CURRENT_DATE)';

		$this->db->query($statement,array($sess_id,$user_id,$store_code,$sales_date,$sales_amount,$branch_code,$bank,$bank_account,$mutation_date));

		return $this->db->affected_rows();	
	}
	/*public function insert_rec_stn_to_tmp($user_id, $tipe, $toko, $tgl_sales, $kolom1, $kolom2,$kolom3,$kolom4,$tipe_shift){
		if(trim($tipe_shift)=='SS1')
		{
			$tipe_shift='S-1';
		}else if(trim($tipe_shift)=='SS2'){
			$tipe_shift=='S-2';
		}else if(trim($tipe_shift)=='SS3'){
			$tipe_shift=='S-3';
		}
		$statement = 'INSERT INTO cdc_upload_rec_stn_tmp("USER_ID", "TIPE", "TOKO", "TGL_SALES", "KOLOM1", "KOLOM2", "KOLOM3", "KOLOM4","TIPE_SHIFT") VALUES(?,?,?,?,?,?,?,?,?)';

		$this->db->query($statement,array($user_id, $tipe, $toko, $tgl_sales, $kolom1, $kolom2,$kolom3,$kolom4,$tipe_shift));

		return $this->db->affected_rows();	
	}*/

	public function insert_rec_stn_to_tmp($user_id, $tipe, $toko, $tgl_sales, $kolom1, $kolom2,$kolom3,$kolom4,$tipe_shift){
		if(trim($tipe_shift)=='SS1')
		{
			$tipe_shift='S-1';
		}else if(trim($tipe_shift)=='SS2'){
			$tipe_shift=='S-2';
		}else if(trim($tipe_shift)=='SS3'){
			$tipe_shift=='S-3';
		}

		if($tipe=='HEADER')
		{
			$statement_cek='SELECT COUNT(*) "HITUNG" FROM cdc_upload_rec_stn_tmp where "TOKO"=? AND "TGL_SALES"=? AND "TIPE" =? AND "TIPE_SHIFT"=?';

			$cek=$this->db->query($statement_cek,array($toko,$tgl_sales,$tipe,$tipe_shift))->row()->HITUNG;

			if($cek=='0')
			{
				$statement = 'INSERT INTO cdc_upload_rec_stn_tmp("USER_ID", "TIPE", "TOKO", "TGL_SALES", "KOLOM1", "KOLOM2", "KOLOM3", "KOLOM4","TIPE_SHIFT") VALUES(?,?,?,?,?,?,?,?,?)';

				$this->db->query($statement,array($user_id, $tipe, $toko, $tgl_sales, $kolom1, $kolom2,$kolom3,$kolom4,$tipe_shift));

				return $this->db->affected_rows();
			}else{
				
				return 0;

			}
		}else{
			$statement = 'INSERT INTO cdc_upload_rec_stn_tmp("USER_ID", "TIPE", "TOKO", "TGL_SALES", "KOLOM1", "KOLOM2", "KOLOM3", "KOLOM4","TIPE_SHIFT") VALUES(?,?,?,?,?,?,?,?,?)';

			$this->db->query($statement,array($user_id, $tipe, $toko, $tgl_sales, $kolom1, $kolom2,$kolom3,$kolom4,$tipe_shift));
			return $this->db->affected_rows();

		}
		





			
	}

	public function check_rec_stn_tmp_header_duplicate($user_id){
		

		$statement = 'SELECT "TOKO", "TGL_SALES" ,"TIPE_SHIFT", count(*) "COUNT" from cdc_upload_rec_stn_tmp
			where "TIPE" = \'HEADER\' and "USER_ID" = ? 
			group by "TOKO", "TGL_SALES","TIPE_SHIFT" ';
		return $this->db->query($statement,array($user_id))->row()->COUNT;

	}

	public function check_line_yang_tanpa_header($user_id){
		$statement = 'SELECT "TIPE", "TOKO", "TGL_SALES"
			from cdc_upload_rec_stn_tmp a
			where ("TIPE" = \'MINUS\' or "TIPE" = \'PLUS\') and "USER_ID" = ?
			and (select count(*) from cdc_upload_rec_stn_tmp b 
			where b."TIPE" = \'HEADER\' and b."TOKO" = a."TOKO" and b."TGL_SALES" = a."TGL_SALES") < 1;';

		return $this->db->query($statement,array($user_id))->num_rows();
	}

	public function get_shift_valid($store_code,$tgl_sales,$tipe_shift_inputan)
	{
		$valid=true;
		$statement1='SELECT count(*) as "CEK" FROM cdc_master_shift where "STORE_CODE"=? and "STATUS"=\'A\' and "TGL_ACTIVE"<=? and ("TGL_INACTIVE" IS NULL OR "TGL_INACTIVE">=?) and "TIPE_SHIFT"=\'SS\'';
    	$rs_statement1=$this->db->query($statement1,array($store_code,$tgl_sales,$tgl_sales))->row();
    	if($rs_statement1->CEK=='1')
    	{
    		$statement2='SELECT "TOTAL_SHIFT" AS "TOTAL_SHIFT" FROM cdc_master_shift where "STORE_CODE"=? and "STATUS"=\'A\' and "TGL_ACTIVE"<=? and ("TGL_INACTIVE" IS NULL OR "TGL_INACTIVE">=?)';
       		$rs_statement2 = $this->db->query($statement2,array($store_code,$tgl_sales,$tgl_sales))->row();
       		if($rs_statement2->TOTAL_SHIFT=='2')
       		{
       			if(trim($tipe_shift_inputan)=='SS2'||trim($tipe_shift_inputan)=='SS1')
       			{
       				$valid= true;
       			}else{
       				$valid= false;
       			}

       		}else if($rs_statement2->TOTAL_SHIFT=='3')
       		{
       			if(trim($tipe_shift_inputan)=='SS2'||trim($tipe_shift_inputan)=='SS1' || trim($tipe_shift_inputan)=='SS3')
       			{
       				$valid= true;
       			}else{
       				$valid= false;
       			}
       		}
    		
    	}
    	return $valid;
	}

	public function check_toko_valid($user_id, $branch_id, $tipe_retur){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp r
			where r."USER_ID" = ?
			and not exists 
			(select 1 from cdc_master_toko t 
				where r."TOKO" = t."STORE_CODE"
				and t."BRANCH_ID" = ?
			)';

		if($tipe_retur == 'num_rows'){
			return $this->db->query($statement,array($user_id, $branch_id))->num_rows();
		} else{
			return $this->db->query($statement,array($user_id, $branch_id))->result();
		}
	}

	public function check_stn_tmp_trx_duplicate($user_id, $tipe_retur){
		
			$statement = 'SELECT rt."TGL_SALES", rt."TOKO", rs."STORE_ID", t."STORE_ID", t."STORE_CODE"
			from cdc_upload_rec_stn_tmp rt, cdc_trx_receipts_shift rs, cdc_master_toko t
			where rt."TOKO" = t."STORE_CODE" and rs."STORE_ID" = t."STORE_ID"
			and rs."ACTUAL_SALES_FLAG" = \'Y\'
			and( rt."TIPE_SHIFT"=rs."NO_SHIFT" or
			substr(rt."TIPE_SHIFT",1,1)!= substr(rs."SHIFT_FLAG",1,1))
			and rt."TIPE" = \'HEADER\' 
			and TO_DATE(rt."TGL_SALES",\'YYYY-MM-DD\') = rs."SALES_DATE"
			and rt."USER_ID" = ?';
		
		

		// return $this->db->query($statement,array($user_id))->num_rows();
		if($tipe_retur == 'num_rows'){
			return $this->db->query($statement,array($user_id))->num_rows();
		} else{
			return $this->db->query($statement,array($user_id))->result();
		}
	}

	public function cek_bank_tmp($user_id){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp rt
			where rt."USER_ID" = ?
			and rt."TIPE" = \'HEADER\'
			and not exists 
			(select 1 from cdc_master_bank b
				where rt."KOLOM2" = b."BANK_NAME" AND b."ACTIVE_FLAG" = \'Y\'
			);';
		return $this->db->query($statement,array($user_id))->num_rows();

	}

	public function cek_bank_account_tmp($user_id, $branch_id){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp rt, cdc_master_bank b
			where replace(UPPER(rt."KOLOM2"),\' \',\'\') = replace(UPPER(b."BANK_NAME"),\' \',\'\')
			and rt."USER_ID" = ?
			and rt."TIPE" = \'HEADER\'
			and not exists 
			(select 1 from cdc_master_bank_account ba
				where b."BANK_ID" = ba."BANK_ID"
				and replace(replace(rt."KOLOM3",\'-\',\'\'),\' \',\'\') = replace(replace(ba."BANK_ACCOUNT_NUM",\'-\',\'\'),\' \',\'\')
				and ba."BRANCH_ID" = ?
				and ba."ACTIVE_FLAG" = \'Y\'
			);';
		return $this->db->query($statement,array($user_id,$branch_id))->num_rows();
	}

	public function cek_detail_pengurang($user_id, $tipe_retur){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp t
			where t."TIPE" = \'MINUS\' and t."USER_ID" = ?
			and not exists 
			(select 1 from cdc_master_detail_pengurang dp
				where REPLACE(UPPER(dp."TRX_MINUS_NAME"),\' \',\'\') = REPLACE(UPPER(t."KOLOM1"),\' \',\'\')
				and dp."ACTIVE_FLAG" = \'Y\'
			);';
		if($tipe_retur == 'num_rows'){
			return $this->db->query($statement,array($user_id))->num_rows();
		} else{
			return $this->db->query($statement,array($user_id))->result();
		}
	}

	public function cek_detail_penambah($user_id, $tipe_retur){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp t
			where t."TIPE" = \'PLUS\' and t."USER_ID" = ?
			and not exists 
			(select 1 from cdc_master_detail_penambah dp
				where REPLACE(UPPER(dp."TRX_PLUS_NAME"), \' \',\'\') = REPLACE(UPPER(t."KOLOM1"),\' \',\'\')
				and dp."ACTIVE_FLAG" = \'Y\'
			);';
		if($tipe_retur == 'num_rows'){
			return $this->db->query($statement,array($user_id))->num_rows();
		} else{
			return $this->db->query($statement,array($user_id))->result();
		}
	}

	public function get_minus_id($pot_type){
		$statement = 'SELECT * from cdc_master_detail_pengurang dp
			where REPLACE(UPPER(dp."TRX_MINUS_NAME"),\' \',\'\') = REPLACE(UPPER(?),\' \',\'\') and dp."ACTIVE_FLAG" = \'Y\';';
		$query = $this->db->query($statement,$pot_type)->row();
		if($query){
			return $query;
		} else{
			return null;
		}
	}

	public function get_plus_id($pot_type){
		$statement = 'SELECT * from cdc_master_detail_penambah dp
			where REPLACE(UPPER(dp."TRX_PLUS_NAME"),\' \',\'\') = REPLACE(UPPER(?),\' \',\'\') and dp."ACTIVE_FLAG" = \'Y\';';
		$query = $this->db->query($statement,$pot_type)->row();
		if($query){
			return $query;
		} else{
			return null;
		}
	}

	public function delete_rec_stn_tmp($user_id){

		$statement = 'DELETE FROM cdc_upload_rec_stn_tmp WHERE "USER_ID" = ?';

		$this->db->query($statement,array($user_id));
		
	}

	public function data_stn_tmp($sess_id,$user_id){
		$statement = 'SELECT * FROM cdc_receipts_tmp WHERE "SESSION_ID" = ? AND "USER_ID" = ?';

		return $this->db->query($statement,array($sess_id,$user_id))->result();
	}

	public function data_rec_stn_tmp($user_id){
		$statement = 'SELECT * from cdc_upload_rec_stn_tmp t
			where t."USER_ID" = ?
			order by t."TOKO", t."TGL_SALES",t."TIPE_SHIFT", t."TIPE"';

		return $this->db->query($statement,array($user_id))->result();
	}

	public function getBankID($bank){
		
		$statement = 'SELECT "BANK_ID" FROM cdc_master_bank WHERE UPPER(replace("BANK_NAME",\' \',\'\')) = ?';
		$query = $this->db->query($statement,str_replace(' ','',strtoupper($bank)))->row();

		return $query->BANK_ID;
	}

	public function getBankAccountID($bank_id,$branch_id,$bank_account){
		$statement = 'SELECT "BANK_ACCOUNT_ID" FROM cdc_master_bank_account WHERE "BANK_ID" = ? AND "BRANCH_ID" = ? AND BTRIM(BTRIM(REPLACE(REPLACE(REPLACE("BANK_ACCOUNT_NUM",\'-\',\'\'),\'.\',\'\'),\' \',\'\'))) = ?';
		$query = $this->db->query($statement,array($bank_id,$branch_id,str_replace(' ','',str_replace('-','',str_replace('/','',str_replace('.','',$bank_account))))))->row();

		return $query->BANK_ACCOUNT_ID;

	}

	public function getBranchID($branch_code){
		$statement = 'SELECT "BRANCH_ID" FROM cdc_master_branch WHERE BTRIM("BRANCH_CODE") = ?';
		$query = $this->db->query($statement,$branch_code)->row();

		if($query){
			return $query->BRANCH_ID;
		}else{
			return 0;
		}
	}


	public function getStoreID($store_code,$branch_id){
		$statement = 'SELECT "STORE_ID" FROM cdc_master_toko WHERE BTRIM("STORE_CODE") = ? AND "BRANCH_ID" = ?';
		$query = $this->db->query($statement,array($store_code,$branch_id))->row();

		if($query){
			return $query->STORE_ID;
		}else{
			return 0;
		}
	}

	public function cek_cabang($branch_code){
		$statement = 'SELECT "BRANCH_ID" FROM cdc_master_branch WHERE BTRIM("BRANCH_CODE") = ?';
		$query = $this->db->query($statement,array($branch_code))->row();

		if($query){
			return $query->BRANCH_ID;
		}else{
			return 0;
		}
	}

	public function cek_toko($store_code,$branch_id){
		$statement = 'SELECT COUNT(*) AS "COUNT" FROM cdc_master_toko WHERE BTRIM("STORE_CODE") = ? AND "BRANCH_ID" = ?';
		$query = $this->db->query($statement,array($store_code,$branch_id))->row();

		return $query->COUNT;
	}

	public function cek_aktif_toko($store_code,$branch_id){
		$statement = 'SELECT COUNT(*) AS "COUNT" FROM cdc_master_toko WHERE BTRIM("STORE_CODE") = ? AND "BRANCH_ID" = ? AND "ACTIVE_FLAG" = ?';
		$query = $this->db->query($statement,array($store_code,$branch_id,'Y'))->row();

		return $query->COUNT;
	}

	public function cek_bank($bank){
		$statement = 'SELECT "BANK_ID" FROM cdc_master_bank WHERE BTRIM("BANK_NAME") = ? AND "ACTIVE_FLAG" = ?';
		$query = $this->db->query($statement,array($bank,'Y'))->row();

		if($query){
			return $query->BANK_ID;
		}else{
			return 0;
		}
	}

	public function cek_bank_account($bank_id,$bank_account,$branchID){
		$statement = 'SELECT COUNT(*) AS "COUNT" FROM cdc_master_bank_account WHERE "BANK_ID" = ? AND "BRANCH_ID" = ? AND BTRIM(REPLACE(REPLACE(REPLACE("BANK_ACCOUNT_NUM",\'-\',\'\'),\'.\',\'\'),\' \',\'\')) = ? AND "ACTIVE_FLAG" = ?';
		$query = $this->db->query($statement,array($bank_id,$branchID,$bank_account,'Y'))->row();

		return $query->COUNT;
	}

	public function cek_receipt($store_id,$sales_date){
		$statement = 'SELECT COUNT(*) AS "COUNT" FROM cdc_trx_receipts_shift WHERE "STORE_ID" = ? AND "SALES_DATE" = ? AND "ACTUAL_SALES_FLAG" =\'Y\'';
		$query = $this->db->query($statement,array($store_id,$sales_date))->row();

		return $query->COUNT;
	}

	public function validateDate($date)
	{
		date_default_timezone_set('Asia/Jakarta');

	    $d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') == $date;
	}

	public function validate_stn_tmp($sess_id,$user_id){

		$error = [];

		$dataSTN_tmp = $this->data_stn_tmp($sess_id,$user_id);

		foreach ($dataSTN_tmp as $stn) {
			$BranchID = '';
			$store_id = '';

			$BranchID = $this->getBranchID(trim($stn->BRANCH_CODE));
			$cek_cabang = $this->cek_cabang(trim($stn->BRANCH_CODE));
			$cek_toko = $this->cek_toko(trim($stn->STORE_CODE),$cek_cabang);
			$store_id = $this->getStoreID(trim($stn->STORE_CODE),$BranchID);
			$cek_aktif_toko = $this->cek_aktif_toko(trim($stn->STORE_CODE),$cek_cabang);
			$cek_bank = $this->cek_bank(trim($stn->BANK));
			$cek_bank_account = $this->cek_bank_account($cek_bank,trim($stn->BANK_ACCOUNT),$cek_cabang);
			$cek_receipt = $this->cek_receipt($store_id,$stn->SALES_DATE);
			$cek_format_tgl_sales = $this->validateDate($stn->SALES_DATE);
			$cek_format_tgl_mutasi = $this->validateDate($stn->MUTATION_DATE);

			if($cek_cabang == 0){
				array_push($error,'Cabang '.$stn->BRANCH_CODE.' tidak ditemukan!');
			}

			if($cek_toko == 0){
				array_push($error,trim($stn->STORE_CODE).' tidak ditemukan!');
			}else{
				if($cek_aktif_toko == 0){
					array_push($error,trim($stn->STORE_CODE).' sudah tidak aktif!');
				}
			}

			/*if($cek_format_tgl_sales == FALSE){
				array_push($error,trim($stn->STORE_CODE).'tgl sales'.$stn->SALES_DATE.' format tanggal tidak sesuai!');
			}

			if($cek_format_tgl_mutasi == FALSE){
				array_push($error,trim($stn->STORE_CODE).'tgl mutasi'.$stn->MUTATION_DATE.' format tanggal tidak sesuai!');
			}*/
			
			if($cek_bank == 0){
				array_push($error,'Tidak ditemukan '.trim($stn->BANK).'!');
			}

			if($cek_bank_account == 0){
				array_push($error,'Tidak ditemukan '.trim($stn->BANK).' dengan no account '.trim($stn->BANK_ACCOUNT).'!');
			}

			if($cek_receipt > 0){
				array_push($error,'Sales '.date('d-m-Y',strtotime($stn->SALES_DATE)).' untuk toko '.trim($stn->STORE_CODE).' sudah ada!');
			}

			
			/*if($cek_cabang > 0){
				$cek_toko = $this->cek_toko(trim($stn->STORE_CODE),$cek_cabang);
				if($cek_toko > 0){
					$store_id = $this->getStoreID(trim($stn->STORE_CODE),trim($stn->BRANCH_CODE));
					$cek_aktif_toko = $this->cek_aktif_toko(trim($stn->STORE_CODE),$cek_cabang);

					if($cek_aktif_toko > 0){//start if aktif toko
						$cek_bank = $this->cek_bank(trim($stn->BANK));

						if($cek_bank > 0){//start if cek bank
							$cek_bank_account = $this->cek_bank_account($cek_bank,trim($stn->BANK_ACCOUNT),$cek_cabang);

							if($cek_bank_account > 0){
								$cek_receipt = $this->cek_receipt($store_id,$stn->SALES_DATE);

								if($cek_receipt > 0){
									array_push($error,'Sales '.$stn->SALES_DATE.' untuk toko '.trim($stn->STORE_CODE).'sudah ada!');
								}
							}else{
								array_push($error,'Tidak ditemukan '.trim($stn->BANK).' dengan no account '.trim($stn->BANK_ACCOUNT).'!');
							}
						}//end  if cek bank
						else{
							array_push($error,'Tidak ditemukan '.trim($stn->BANK).'!');
						}
					}//end if aktif toko
					else{
						array_push($error,trim($stn->STORE_CODE).' sudah tidak aktif!');
					}
				}//end if cek toko
				else{
					array_push($error,trim($stn->STORE_CODE).' tidak ditemukan!');
				}
			}//end if cabang*/
		}//end loop data stn

		return $error;
	}

	public function delete_stn_tmp($sess_id,$user_id){
		$statement = 'DELETE FROM cdc_receipts_tmp WHERE "SESSION_ID" = ? AND "USER_ID" = ?';

		$this->db->query($statement,array($sess_id,$user_id));
	}

	public function insert_stn($cdc_rec_id,$store_id,$store_code,$sales_date,$sales_amount,$branch_code,$bank,$bank_account,$bank_account_id,$mutation_date,$user_id,$tipe_shift){
		if($tipe_shift=='H' || $tipe_shift=='HARIAN')
		{
			$statement_cek='SELECT COUNT(*) "HITUNG" FROM cdc_trx_receipts_shift where "STORE_ID"=? AND "SALES_DATE"=? AND ("SHIFT_FLAG" LIKE \'SS%\' OR "SHIFT_FLAG" LIKE \'H\')';
			$cek=$this->db->query($statement_cek,array($store_id,$sales_date))->row();
			if($cek->HITUNG=='0')
			{
				$statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE","SHIFT_FLAG","NO_SHIFT","BANK_ACCOUNT_ID") VALUES(?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?,?,?,?,?,?)';

				$this->db->query($statement,array($cdc_rec_id,$store_id,$sales_date,'N','Y',$sales_amount,$branch_code,intval($user_id),intval($user_id),'N','Y',$mutation_date,'N','H',$bank_account_id));

				return $this->db->insert_id();
			}			

			
		}else{
			if($tipe_shift=='SS1'|| $tipe_shift=='S-1')
			{	$statement_cek='SELECT COUNT(*) "HITUNG" FROM cdc_trx_receipts_shift where "STORE_ID"=? AND "SALES_DATE"=? AND ("NO_SHIFT" = \'H\' OR "NO_SHIFT"  LIKE \'S-1\') AND "ACTUAL_SALES_FLAG"=\'Y\'';
				$cek=$this->db->query($statement_cek,array($store_id,$sales_date))->row();
				if($cek->HITUNG=='0')
				{
						$statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE","SHIFT_FLAG","NO_SHIFT","BANK_ACCOUNT_ID") VALUES(?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?,?,?,?,?,?)';

						$this->db->query($statement,array($cdc_rec_id,$store_id,$sales_date,'N','Y',$sales_amount,$branch_code,intval($user_id),intval($user_id),'N','Y',$mutation_date,'SS','S-1',$bank_account_id));

						return $this->db->insert_id();
				}	
			
			}
			if($tipe_shift=='SS2'|| $tipe_shift=='S-2')
			{
				$statement_cek='SELECT COUNT(*) "HITUNG" FROM cdc_trx_receipts_shift where "STORE_ID"=? AND "SALES_DATE"=? AND ("NO_SHIFT" = \'H\'  OR "NO_SHIFT"  LIKE \'S-2\') AND "ACTUAL_SALES_FLAG"=\'Y\' ';
				$cek=$this->db->query($statement_cek,array($store_id,$sales_date))->row();
				if($cek->HITUNG=='0')
				{
					$statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE","SHIFT_FLAG","NO_SHIFT","BANK_ACCOUNT_ID") VALUES(?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?,?,?,?,?,?)';

					$this->db->query($statement,array($cdc_rec_id,$store_id,$sales_date,'N','Y',$sales_amount,$branch_code,intval($user_id),intval($user_id),'N','Y',$mutation_date,'SS','S-2',$bank_account_id));

					return $this->db->insert_id();
				}
				
			}
			if($tipe_shift=='SS3'|| $tipe_shift=='S-3')
			{
				$statement_cek='SELECT COUNT(*) "HITUNG" FROM cdc_trx_receipts_shift where "STORE_ID"=? AND "SALES_DATE"=? AND ("NO_SHIFT" = \'H\'  OR "NO_SHIFT"  LIKE \'S-3\') AND "ACTUAL_SALES_FLAG"=\'Y\'';
				$cek=$this->db->query($statement_cek,array($store_id,$sales_date))->row();
				if($cek->HITUNG=='0')
				{
					$statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE","SHIFT_FLAG","NO_SHIFT","BANK_ACCOUNT_ID") VALUES(?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?,?,?,?,?,?)';

					$this->db->query($statement,array($cdc_rec_id,$store_id,$sales_date,'N','Y',$sales_amount,$branch_code,intval($user_id),intval($user_id),'N','Y',$mutation_date,'SS','S-3',$bank_account_id));

					return $this->db->insert_id();
				}
			
			}
		}
		
	}

	public function insert_stn_plus($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, $cdc_rec_id,$tipe_shift){

		if($tipe_shift=='H' || $tipe_shift=='HARIAN')
		{
			
			$statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
             VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
	        $this->db->query($statement, array($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, 'H', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
			$returan = $this->db->insert_id();
		}else{
			if($tipe_shift=='SS1' || $tipe_shift=='S-1')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
	             VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
		        $this->db->query($statement, array($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, '1', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();

			}
			if($tipe_shift=='SS2' || $tipe_shift=='S-2')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
	             VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
		        $this->db->query($statement, array($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, '2', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();

			}
			if($tipe_shift=='SS3' || $tipe_shift=='S-3')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_tambah_shift("TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
	             VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
		        $this->db->query($statement, array($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, '3', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();

			}	
		}
		
		if($returan){
			//PENAMBAHAN
            // $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
            // //var_dump($rrak);
            // $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
            // $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
            // //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
            // $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
            // $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
            // $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN

            // 'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
            //     'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
            //     'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
			$statement2 = 'UPDATE cdc_trx_receipts_shift set ';
			if($plus_id == 9 || $plus_id == 10){
				$statement2.='"ACTUAL_RRAK_AMOUNT" = "ACTUAL_RRAK_AMOUNT" + '.$plus_amount;
			}
			if($plus_id == 11){
				$statement2.='"ACTUAL_PAY_LESS_DEPOSITED" = "ACTUAL_PAY_LESS_DEPOSITED" + '.$plus_amount;
			}
			if($plus_id == 12){
				$statement2.='"ACTUAL_VIRTUAL_PAY_LESS" = "ACTUAL_VIRTUAL_PAY_LESS" + '.$plus_amount;
			}
			// if($plus_id == 32){
			// 	$statement2.='ACTUAL_LOST_ITEM_PAYMENT = ACTUAL_LOST_ITEM_PAYMENT + '$minus_amount;
			// }
			// if($plus_id == 32){
			// 	$statement2.='ACTUAL_WU_ACCOUNTABILITY = ACTUAL_WU_ACCOUNTABILITY + '$minus_amount;
			// }
			if($plus_id == 13){
				$statement2.='"ACTUAL_OTHERS_AMOUNT" = "ACTUAL_OTHERS_AMOUNT" + '.$plus_amount;
			}
			if($tipe_shift=='HARIAN' || $tipe_shift=='H')
			{
				$shift='H';
			}else if($tipe_shift=='SS1' || $tipe_shift=='S-1')
			{
				$shift='S-1';
			}else if($tipe_shift=='SS2' || $tipe_shift=='S-2')
			{
				$shift='S-2';
			}else if($tipe_shift=='SS3' || $tipe_shift=='S-3')
			{
				$shift='S-3';
			}
			$statement2.=' WHERE "CDC_SHIFT_REC_ID" = '.$trx_cdc_rec_id.' AND "CDC_REC_ID" ='.$cdc_rec_id.'  AND "NO_SHIFT"=\''.$shift.'\'';
			$this->db->query($statement2, array($trx_cdc_rec_id, $plus_id, $plus_date, $desc, $plus_amount, 'H', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
		}
		return $returan;
	}

	public function insert_stn_minus($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, $cdc_rec_id,$tipe_shift){
		if($tipe_shift=='H' || $tipe_shift=='H')
		{
			$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
                 VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
			$this->db->query($statement, array($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, 'H', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
			$returan = $this->db->insert_id();
		}else{
			if($tipe_shift=='SS1' || $tipe_shift=='S-1')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
                 VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
				$this->db->query($statement, array($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, '1', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();

			}
			if($tipe_shift=='SS2' || $tipe_shift=='S-2')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
                 VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
				$this->db->query($statement, array($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, '2', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();


			}
			if($tipe_shift=='SS3' || $tipe_shift=='S-3')
			{	
				$statement = 'INSERT INTO cdc_trx_detail_minus_shift("TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","NO_SHIFT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_REC_ID")
                 VALUES (?,?,?,?,?,?,?,CURRENT_DATE,?,CURRENT_DATE,?)';
				$this->db->query($statement, array($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, '3', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
				$returan = $this->db->insert_id();
			}	
		}
		
		if($returan){
	

			$statement2 = 'UPDATE cdc_trx_receipts_shift set ';
			if($minus_id == 35){
				$statement2.='"RRAK_DEDUCTION" = "RRAK_DEDUCTION" + '.$minus_amount;
			}
			if($minus_id == 27 || $minus_id == 28 || $minus_id == 29 || $minus_id == 30 || $minus_id == 31){
				$statement2.='"LESS_DEPOSIT_DEDUCTION" = "LESS_DEPOSIT_DEDUCTION" + '.$minus_amount;
			}
			if($minus_id == 34){
				$statement2.='"OTHERS_DEDUCTION" = "OTHERS_DEDUCTION" + '.$minus_amount;
			}
			if($minus_id == 32 || $minus_id == 33){
				$statement2.='"VIRTUAL_PAY_LESS_DEDUCTION" = "VIRTUAL_PAY_LESS_DEDUCTION" + '.$minus_amount;
			}
			if($tipe_shift=='H'  || $tipe_shift=='H')
			{
				$shift='H';
			}else if($tipe_shift=='SS1' || $tipe_shift=='S-1')
			{
				$shift='S-1';
			}else if($tipe_shift=='SS2' || $tipe_shift=='S-2')
			{
				$shift='S-2';
			}else if($tipe_shift=='SS3' || $tipe_shift=='S-3')
			{
				$shift='S-3';
			}

			$statement2.=' WHERE "CDC_SHIFT_REC_ID" = '.$trx_cdc_rec_id.' AND "CDC_REC_ID" ='.$cdc_rec_id.'  AND "NO_SHIFT"=\''.$shift.'\'';
			$this->db->query($statement2, array($trx_cdc_rec_id, $minus_id, $minus_date, $desc, $minus_amount, 'H', $this->session->userdata('usrId'), $this->session->userdata('usrId'),$cdc_rec_id));
		}
		return $returan;
	}

	//END UPLOAD STN

}

/* End of file Mod_upload.php */
/* Location: ./application/models/Mod_upload.php */