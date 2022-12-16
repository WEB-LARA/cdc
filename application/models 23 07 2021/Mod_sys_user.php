<?php
  class Mod_sys_user extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function update_user_password($user_id,$password){
      //$query_update = "UPDATE sys_user_2 SET PASSWORD='".md5($password)."', LAST_UPDATED_DATE = sysdate(),  RESET_FLAG='Y' WHERE ID_USER=".$user_id;
      //$this->db->query($query_update);
      $id = $user_id;
      $pswrd = md5(trim($password));
      $reset = "N";
      $last = date("Y/m/d");
      $data = array('PASSWORD'=>$pswrd,'LAST_UPDATE_DATE'=>$last,'RESET_FLAG'=>$reset);
      $this->db->where('USER_ID',$id);
      $this->db->update('sys_user_2',$data);
    }

    function getData(){
      $this->db->order_by('USER_ID','asc');
      $data=$this->db->get('sys_user_2');
      return $data->result();
    }

    public function get_data_user($page, $rows, $nik)
    {
      $page = ($page - 1) * $rows;
      $statement = 'SELECT SU.*, SR."ROLE_NAME", CMB."BRANCH_CODE"||\' - \'||CMB."BRANCH_NAME" "BRANCH", SMD."DC_CODE"||\' - \'||SMD."DC_NAME" "DC" FROM sys_user_2 SU, SYS_ROLE SR, CDC_MASTER_BRANCH CMB, SYS_MAP_DC SMD WHERE SU."ROLE_ID" = SR."ROLE_ID" AND SU."BRANCH_ID" = CMB."BRANCH_ID" AND BTRIM(SU."DC_CODE") = BTRIM(SMD."DC_CODE")';
      if ($nik != 'X') {
        $statement .= ' AND SU."NIK" = \''.$nik.'\'';
      }
      $result['total'] = $this->db->query($statement)->num_rows();
      $statement .= ' ORDER BY SU."USER_ID" LIMIT '.$rows.' OFFSET '.$page.'';
      $result['rows'] = $this->db->query($statement)->result();
      return $result;
    }

    public function cek_nik_user($nik)
    {
      $statement = 'SELECT * FROM sys_user_2 WHERE "NIK" = ?';
      return $this->db->query($statement, $nik)->num_rows();
    }

    public function add_user($data)
    {
      $statement = 'INSERT INTO sys_user_2 VALUES ((SELECT MAX("USER_ID")+1 FROM sys_user_2),?,MD5(?),?,?,CURRENT_DATE,\'Y\',\'N\',CURRENT_DATE,CURRENT_DATE,?,?)';
      $this->db->query($statement, array($data['username'], $data['pass'], $data['role'], $data['branch'], $data['dc'], $data['nik']));
      return $this->db->affected_rows();
    }
  }
 ?>
