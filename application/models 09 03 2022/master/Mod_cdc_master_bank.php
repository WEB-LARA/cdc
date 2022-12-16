<?php
  class Mod_cdc_master_bank extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData($name,$type,$num){
      $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
      $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }


      //HITUNG TOTAL DATA
      $this->db->like('BANK_NAME',$name);
      $this->db->like('BANK_ACCOUNT_TYPE',$type);
      $this->db->like('BANK_ACCOUNT_NUM',$num);
      $totalCount = $this->db->count_all_results('cdc_master_bank');
      $result['total']= $totalCount;

      //AMBIL DATA
      $this->db->order_by('BANK_ID','asc');
      $this->db->like('BANK_NAME',$name);
      $this->db->like('BANK_ACCOUNT_TYPE',$type);
      $this->db->like('BANK_ACCOUNT_NUM',$num);
      $data=$this->db->get('cdc_master_bank',$rows,$offset);
      $result['rows']=$data->result();
      //$data=$this->db->get('cdc_master_branch',5,0); //brp row , mulai dari

       return $result;
    }


    function addData($id,$data){
      $aDate  = date("Y-m-d");
      $create = date("Y-m-d");
      $last   = date("Y-m-d");

      $data = array('BANK_ID'=>$id,'BANK_NAME'=>$data['bankName'],'BANK_ACCOUNT_TYPE'=>$data['bankAccountType'],'BANK_ACCOUNT_NUM'=>$data['bankAccountNum'],
      'ACTIVE_FLAG'=>$data['activeFlag'],'ACTIVE_DATE'=>$aDate,'CREATION_DATE'=>$create,'LAST_UPDATE_DATE'=>$last);
      $this->db->insert('cdc_master_bank',$data);
    }

    function editData($data){
      $last   = date("Y-m-d");
      $id     = $data['bankId'];

      //active flag ganti
      if($data['activeFlag'] == "Y"){
        $inactive = null;
      }else{
        $inactive = date("Y-m-d");
      }

      $data = array('BANK_NAME'=>$data['bankName'],'BANK_ACCOUNT_TYPE'=>$data['bankAccountType'],'BANK_ACCOUNT_NUM'=>$data['bankAccountNum'],
      'ACTIVE_FLAG'=>$data['activeFlag'],'INACTIVE_DATE'=>$inactive,'LAST_UPDATE_DATE'=>$last);
      //var_dump($data);
      $this->db->where('BANK_ID',$id);
      $this->db->update('cdc_master_bank',$data);
    }

    function deleteData($id){
      $this->db->where('BANK_ID',$id);
      $this->db->delete('cdc_master_bank');
    }

  }
 ?>
