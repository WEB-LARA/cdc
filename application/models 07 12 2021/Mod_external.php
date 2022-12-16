<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_external extends CI_Model {

	public function get_data_amas($branch_code)
	{
		if($branch_code == '001')
		{
			$statement = 'SELECT * FROM CDC_MASTER_AM_AS';
			return $this->db->query($statement)->result();
		}
		else
		{
			$statement = 'SELECT * FROM CDC_MASTER_AM_AS WHERE BTRIM("BRANCH_CODE") = BTRIM(?)';
			return $this->db->query($statement, $branch_code)->result();
		}
	}

	public function get_data_toko($branch_code)
	{
		$statement = 'SELECT CMT."STORE_CODE", CMT."STORE_TYPE", CMT."STORE_NAME", CMT."STORE_ADDRESS", CMT."ACTIVE_FLAG", CMT."ACTIVE_DATE", BTRIM(CMB."BRANCH_CODE") "BRANCH_CODE" FROM CDC_MASTER_TOKO CMT, CDC_MASTER_BRANCH CMB WHERE CMT."BRANCH_ID" = CMB."BRANCH_ID" AND BTRIM(CMB."BRANCH_CODE") = BTRIM(?)';
		return $this->db->query($statement, $branch_code)->result();	
	}

}

/* End of file Mod_external.php */
/* Location: ./application/models/Mod_external.php */