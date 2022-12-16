<?php
  class Mod_admin extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    public function count_row($table,$where){
			$stmt = 'SELECT COUNT(*) as "COUNT" FROM '.$table.$where.'';
			$result_query = $this->db->query($stmt)->row();
			return $result_query;
	}

    public function getDataUser($rows,$offset,$where){
    	$statement = 'SELECT "USER_ID","USER_NAME","NIK","ROLE_ID","BRANCH_ID","DC_CODE","ACTIVE_DATE","ACTIVE_FLAG","RESET_FLAG",
    	(SELECT "BRANCH_NAME" FROM cdc_master_branch cmb WHERE cmb."BRANCH_ID" = su2."BRANCH_ID") AS "BRANCH",
    	(SELECT "BRANCH_CODE" FROM cdc_master_branch cmb WHERE cmb."BRANCH_ID" = su2."BRANCH_ID") AS "BRANCH_CODE",
    	(SELECT "ROLE_DESCRIPTION" FROM sys_role sr WHERE sr."ROLE_ID" = su2."ROLE_ID") AS "ROLE",
    	CASE 
    		WHEN "ACTIVE_FLAG" = \'Y\' THEN \'YES\'
    		ELSE \'NO\'
    	END AS "ACTIVE"
    	 FROM sys_user_2 su2 WHERE "ROLE_ID" in (1,3,4) '.$where.' ORDER BY "USER_ID" DESC OFFSET ? LIMIT ?';

    	return $this->db->query($statement,array($offset,$rows))->result();
    }

    public function cek_nik($data){
    	$statement = 'SELECT COUNT(*) AS "COUNT" FROM sys_user_2 WHERE BTRIM("NIK") = BTRIM(?)';
    	$query = $this->db->query($statement,$data['nik'])->row();

    	return $query->COUNT;
    }

    public function cek_bank($data){
    	$statement = 'SELECT COUNT(*) AS "COUNT" FROM cdc_master_bank_account WHERE "BANK_ID" = ? AND BTRIM("BANK_ACCOUNT_NUM") = BTRIM(?)';
    	$query = $this->db->query($statement,array($data['bank'],$data['no_bank']))->row();

    	return $query->COUNT;
    }

    public function getOldNIK($data){
    	$statement = 'SELECT "NIK" FROM sys_user_2 WHERE "USER_ID" = ?';
    	$query = $this->db->query($statement,$data['user_id'])->row();

    	return $query->NIK;
    }

    public function resetPassword($data){
    	$statement = 'UPDATE sys_user_2 SET "PASSWORD" = MD5(?) WHERE "USER_ID" = ?';
    	$this->db->query($statement,array($data['password'],$data['user_id']));

    	return $this->db->affected_rows();
    }

    public function insert_user($data,$user_id,$branch){
    	$statement = 'INSERT INTO sys_user_2("USER_ID","USER_NAME","PASSWORD","ROLE_ID","BRANCH_ID","ACTIVE_DATE","ACTIVE_FLAG","RESET_FLAG","CREATE_DATE","LAST_UPDATE_DATE","DC_CODE","NIK") VALUES(?,?,MD5(?),?,?,CURRENT_DATE,?,?,CURRENT_DATE,CURRENT_DATE,?,?)';

    	 $this->db->query($statement,array($user_id,$data['username'],$data['password'],$data['role'],$branch,'Y','Y',$data['dc'],$data['nik']));

    	 return $this->db->affected_rows();
    }

    public function edit_user($data,$branch){
    	$statement = 'UPDATE sys_user_2 SET "USER_NAME" = ?,"ROLE_ID" = ?,"BRANCH_ID" = ?,"ACTIVE_FLAG" = ?,"LAST_UPDATE_DATE" = CURRENT_DATE,"DC_CODE" = ?, "NIK" = ? WHERE "USER_ID" = ?';

    	$this->db->query($statement,array($data['username'],$data['role'],$branch,$data['active'],$data['dc'],$data['nik'],$data['user_id']));

    	return $this->db->affected_rows();
    }

    public function getUserNIK(){
    	$statement = 'SELECT "NIK","NIK" || \'-\'||"USER_NAME" AS "USER" FROM sys_user_2 WHERE "ROLE_ID" in (1,3,4)  ORDER BY "USER_ID" DESC';

    	return $this->db->query($statement)->result();
    }

    public function getBranchID($branch){
    	$statement = 'SELECT "BRANCH_ID" FROM cdc_master_branch WHERE BTRIM("BRANCH_CODE") = BTRIM(?)';
    	$query = $this->db->query($statement,$branch)->row();

    	return $query->BRANCH_ID;
    }

    public function getMaxID(){
    	$statement = 'SELECT MAX("USER_ID") as "ID" FROM sys_user_2';

    	$query = $this->db->query($statement)->row();

    	return $query->ID;
    }

    public function getBranch(){
    	$statement = 'SELECT "BRANCH_CODE","BRANCH_CODE" || \'-\' || "BRANCH_NAME" AS "BRANCH" FROM cdc_master_branch WHERE "BRANCH_CODE" != ? ORDER BY "BRANCH_CODE"';
    	return $this->db->query($statement,'001')->result();
    }

    public function getBranchWithID(){
    	$statement = 'SELECT "BRANCH_ID","BRANCH_CODE" || \'-\' || "BRANCH_NAME" AS "BRANCH" FROM cdc_master_branch WHERE "BRANCH_CODE" != ? ORDER BY "BRANCH_CODE"';
    	return $this->db->query($statement,'001')->result();
    }

    public function getRole(){
    	$statement = 'SELECT "ROLE_ID","ROLE_DESCRIPTION" AS "ROLE" FROM sys_role WHERE "ROLE_ID" in (1,3,4) ORDER BY "ROLE_ID"';
    	return $this->db->query($statement)->result();
    }

    public function getDCode($branch){
    	$statement = 'SELECT "DC_CODE","DC_CODE" || \'-\' || "DC_NAME" AS "DC" FROM sys_map_dc WHERE BTRIM("BRANCH_CODE") = BTRIM(?) ORDER BY "DC_CODE"';
    	return $this->db->query($statement,$branch)->result();
    }

    public function getMasterBank(){
    	$statement = 'SELECT "BANK_ID","BANK_NAME" FROM cdc_master_bank ORDER BY "BANK_NAME"';

    	return $this->db->query($statement)->result();
    }

    public function getOldBank($data){
    	$statement = 'SELECT "BANK_ID","BANK_ACCOUNT_NUM" FROM cdc_master_bank_account WHERE "BANK_ACCOUNT_ID" = ?';

    	return $this->db->query($statement,$data['bank_id'])->row();
    }

    public function getBankAcc(){
    	$statement = 'SELECT "BANK_ACCOUNT_ID","BANK_ACCOUNT_NUM" || \'-\'|| "BANK_ACCOUNT_NAME" AS "BANK" FROM cdc_master_bank_account ORDER BY "BANK_ACCOUNT_ID" DESC';

    	return $this->db->query($statement)->result();
    }

    public function getDataBank($rows,$offset,$where2){
    	$statement = 'SELECT "BANK_ACCOUNT_ID","BANK_ID","BRANCH_ID","BANK_ACCOUNT_NAME","BANK_ACCOUNT_NUM","ACTIVE_FLAG","CREATION_DATE","ACTIVE_DATE",
    	(SELECT "BANK_NAME" FROM cdc_master_bank cmb WHERE cmb."BANK_ID" = cmba."BANK_ID") AS "BANK",
    	(SELECT "BRANCH_CODE" || \'-\' || "BRANCH_NAME" FROM cdc_master_branch cmb WHERE cmb."BRANCH_ID" = cmba."BRANCH_ID") AS "BRANCH",
    	CASE 
    		WHEN "ACTIVE_FLAG" = \'Y\' THEN \'YES\'
    		ELSE \'NO\'
    	END AS "ACTIVE"
    	 FROM cdc_master_bank_account cmba '.$where2.' ORDER BY "BANK_ACCOUNT_ID" DESC OFFSET ? LIMIT ?';

    	return $this->db->query($statement,array($offset,$rows))->result();
    }

    public function insert_bank($data){
    	$statement = 'INSERT INTO cdc_master_bank_account("BANK_ID","BRANCH_ID","BANK_ACCOUNT_NAME","BANK_ACCOUNT_NUM","ACCOUNT_TYPE","ACTIVE_FLAG","ACTIVE_DATE","CREATION_DATE","LAST_UPDATE_DATE") VALUES(?,?,?,?,?,?,CURRENT_DATE,CURRENT_DATE,CURRENT_DATE)';

    	 $this->db->query($statement,array($data['bank'],$data['branch'],$data['nama_bank'],$data['no_bank'],'INTERNAL','Y'));

    	 return $this->db->affected_rows();
    }

    public function edit_bank($data,$tgl){
    	if($data['active'] == 'N'){
    		$statement = 'UPDATE cdc_master_bank_account SET "BANK_ID" = ?,"BRANCH_ID" = ?,"BANK_ACCOUNT_NAME" = ?,"BANK_ACCOUNT_NUM" = ?,"INACTIVE_DATE" = ?,"LAST_UPDATE_DATE" = CURRENT_DATE WHERE "BANK_ACCOUNT_ID" = ?';

    		$this->db->query($statement,array($data['bank'],$data['branch'],$data['nama_bank'],$data['no_bank'],$tgl,$data['bank_id']));

    		return $this->db->affected_rows();
    	}else{
    		$statement = 'UPDATE cdc_master_bank_account SET "BANK_ID" = ?,"BRANCH_ID" = ?,"BANK_ACCOUNT_NAME" = ?,"BANK_ACCOUNT_NUM" = ?,"LAST_UPDATE_DATE" = CURRENT_DATE WHERE "BANK_ACCOUNT_ID" = ?';

    		$this->db->query($statement,array($data['bank'],$data['branch'],$data['nama_bank'],$data['no_bank'],$data['bank_id']));

    		return $this->db->affected_rows();
    	}

    	
    }
  }
 ?>