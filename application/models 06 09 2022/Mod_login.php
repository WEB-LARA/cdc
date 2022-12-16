<?php
  class Mod_login extends CI_model{

    function getLogin($user, $pass){
      $result_data['status'] = 'OK';
  		$result_data['msg'] = 'OK';
      $pass1=md5($pass);

      $this->db->where('NIK',$user);
      $this->db->where('PASSWORD',$pass1);
      $result_query = $this->db->get('sys_user_2');

      $flag="Y";
      $this->db->where('NIK',$user);
      $this->db->where('PASSWORD',$pass1);
      $this->db->where('ACTIVE_FLAG',$flag);
      $result_query2 = $this->db->get('sys_user_2');

  		if($result_query->num_rows() == 1){
  			if($result_query2->num_rows() == 1){
  				$result_data['status'] = 'OK';
  				$result_data['msg'] = 'OK';
  			}else{
  				$result_data['status'] = 'INACTIVE';
  				$result_data['msg'] = 'Akun tidak aktif, mohon untuk menghubungi IT Support SD 6.';
  			}
  		}else{
  			$result_data['status'] = 'FAILED';
  			$result_data['msg'] = 'Invalid Username or Password.';
  		}
  		return $result_data;
    }

    /*function userid($username){
      $this->db->where('NIK',$username);
      $result_query = $this->db->get('sys_user_2');

      $row = $result_query->row();
      $array_result['idUsr'] = $row->USER_ID;
      $array_result['usrName'] = $row->USER_NAME;
      $array_result['roleId'] = $row->ROLE_ID;
      $array_result['resetFlag'] = $row->RESET_FLAG;
      $array_result['password'] = $row->PASSWORD;
      $array_result['branchId'] = $row->BRANCH_ID;
      $array_result['dcCode'] = $row->DC_CODE;

      return $array_result;
    }*/

    function userid($username){
      $statement = 'SELECT * FROM sys_user_2 WHERE "NIK" = ?';
      $result_query = $this->db->query($statement,array($username))->result();

      /*$row = $result_query->row();*/
      $array_result['idUsr'] = $result_query[0]->USER_ID;
      $array_result['usrName'] = $result_query[0]->USER_NAME;
      $array_result['roleId'] = $result_query[0]->ROLE_ID;
      $array_result['resetFlag'] = $result_query[0]->RESET_FLAG;
      $array_result['password'] = $result_query[0]->PASSWORD;
      $array_result['branchId'] = $result_query[0]->BRANCH_ID;
      $array_result['dcCode'] = $result_query[0]->DC_CODE;

      return $array_result;
    }

    function getData(){
      $data=$this->db->get('sys_user_2');
      return $data->result();
    }

    function userInput(){
      $id = $this->input->post('id');
      $username = $this->input->post('username');
      $p = $this->input->post('password');
      $password = md5($p);
      //$roleid = $this->input->post('roleid');

      $data = array('USER_ID'=>$id,'USER_NAME'=>$username,'PASSWORD'=>$password,'ACTIVE_FLAG'=>"Y");
      $this->db->insert('sys_user_2',$data);
    }

    function check_shift($user_id)
    {
      $statement = 'SELECT * FROM cdc_tmp_shift WHERE "USER_ID" = ? AND "SHIFT_STATUS" = \'Y\' AND "SHIFT_CLOSE" IS NULL';
      return $this->db->query($statement,array($user_id))->result();
    }

    function set_shift($data)
    {
      $stmt_cek = 'SELECT * FROM cdc_tmp_shift WHERE "USER_ID" = ?';
      $cek = $this->db->query($stmt_cek, array($data['user_id']))->num_rows();
      if ($cek == 0) {
        $statement = 'INSERT INTO cdc_tmp_shift("SHIFT_NUMBER","SHIFT_OPEN","SHIFT_DATE","USER_ID","NO_REF","DC_CODE") VALUES(?,localtime,current_date,?,?,?)';
        if ($data['dc_shift'] == 'N') {
          $this->db->query($statement,array($data['no_shift'],$data['user_id'],$data['no_ref'],$this->session->userdata('dc_code')));
        }else{
          $this->db->query($statement,array($data['no_shift'],$data['user_id'],$data['no_ref'],$data['dc_shift']));
        }
      } else {
        $stmt_upd = 'UPDATE cdc_tmp_shift SET "SHIFT_NUMBER" = ?, "NO_REF" = ?, "DC_CODE" = ? WHERE "USER_ID" = ?';
        if ($data['dc_shift'] == 'N') {
          $this->db->query($stmt_upd,array($data['no_shift'],$data['no_ref'],$this->session->userdata('dc_code'),$data['user_id']));
        }else{
          $this->db->query($stmt_upd,array($data['no_shift'],$data['no_ref'],$data['dc_shift'],$data['user_id']));
        }
      }
    }

    function del_shift($data)
    {
      $statement = 'DELETE FROM cdc_tmp_shift WHERE "USER_ID" = ?';
      $this->db->query($statement,$data['user_id']);
    }

    function check_dc_type($dc_code)
    {
      $statement = 'SELECT "DC_TYPE" FROM sys_map_dc WHERE "DC_CODE" = ?';
      return $this->db->query($statement,array($dc_code))->result();
    }

    function get_dc($dc_code)
    {
      $statement = 'SELECT * FROM sys_map_dc WHERE "DC_INDUK" = ? AND "DC_CODE" NOT IN (?)';
      return $this->db->query($statement,array($dc_code,$dc_code))->result();
    }

    function cek_dc($dc_code)
    {
      $statement = 'SELECT * FROM sys_map_dc WHERE "DC_CODE" = ? AND BTRIM("BRANCH_CODE") = BTRIM(?)';
      return $this->db->query($statement,array($dc_code,$this->session->userdata('branch_code')))->result();
    }

    public function update_date_shift($user_id)
    {
    	$statement = 'UPDATE cdc_tmp_shift SET "SHIFT_DATE" = current_date WHERE "USER_ID" = ?';
    	if ($this->db->query($statement,$user_id)) {
    		return true;
    	}else{
    		return false;
    	}
    }

    public function admin_choose_branch()
    {
      $statement = 'SELECT "BRANCH_ID", "BRANCH_CODE", BTRIM("BRANCH_NAME") "BRANCH_NAME", BTRIM("BRANCH_CODE")||\'-\'||BTRIM("BRANCH_NAME") "BRANCH_VALUE" FROM CDC_MASTER_BRANCH WHERE BTRIM("BRANCH_CODE") NOT IN (\'001\') ORDER BY "BRANCH_CODE"';
      return $this->db->query($statement)->result();
    }

    public function admin_choose_dc($branch_id)
    {
      $statement = 'SELECT DC."DC_CODE", DC."DC_NAME", DC."DC_CODE"||\'-\'||DC."DC_NAME" "DC_VALUE" FROM SYS_MAP_DC DC, CDC_MASTER_BRANCH CMB WHERE DC."BRANCH_CODE" = BTRIM(CMB."BRANCH_CODE") AND CMB."BRANCH_ID" = ? ORDER BY DC."DC_CODE"';
      return $this->db->query($statement,$branch_id)->result();
    }

  }

 ?>
