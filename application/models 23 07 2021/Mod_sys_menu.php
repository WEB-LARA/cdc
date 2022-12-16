<?php
  class Mod_sys_menu extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getMenu(){
      $role = $this->session->userdata('role_id');

      if($role != 6 && $role !=7){
        $this->db->where('ROLE_ID <=',$role);
      }elseif($role==7){
        $this->db->where('ROLE_ID =',$role);
      }elseif($role==8){
        $this->db->where('ROLE_ID <=',$role);
   
      }else{
         $this->db->where('ROLE_ID =',$role);
         $this->db->or_where('MENU_ID = 1');
      }
      $this->db->where('ROLE_ID <=',$role);
      $this->db->order_by('MENU_ID','asc');
      $data=$this->db->get('sys_menu');
      return $data->result();
    }

    function getSub(){
      $statement = 'SELECT * FROM sys_menu_detail';
      if ($this->session->userdata('role_id') == 4) {
        $statement .= ' WHERE "DETAIL_ID" NOT IN (118)';
      }
      elseif ($this->session->userdata('role_id') > 4  && $this->session->userdata('role_id') !=7  && $this->session->userdata('role_id') !=8) {
        $statement .= ' WHERE "DETAIL_ID" NOT IN (118,120,1,2,3,4,5)';
      }
      elseif ($this->session->userdata('role_id') < 4 ) {
        $statement .= ' WHERE "DETAIL_ID" NOT IN (138)';
      }elseif ($this->session->userdata('role_id')==7){
        $statement .= ' WHERE "DETAIL_ID" IN (1,2,3,4,5)';
      }elseif ($this->session->userdata('role_id') == 8 ) {
        $statement .= ' WHERE "DETAIL_ID" NOT IN (118,120) ';
      }
      
      $statement .= '  ORDER BY "DETAIL_ID"';
      $data=$this->db->query($statement);
      return $data->result();
    }

  }
 ?>
