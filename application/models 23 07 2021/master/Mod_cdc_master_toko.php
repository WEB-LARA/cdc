<?php
  class Mod_cdc_master_toko extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData($branchId,$storeName,$active){
      $page = $this->input->post('page');
      $rows = $this->input->post('rows');
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }

      //HITUNG TOTAL DATA
      if(!empty($branchId)){
        //$this->db->where('BRANCH_ID',$branchId);
      }
      //$this->db->like('STORE_NAME',$storeName);
      //$this->db->like('ACTIVE_DATE',$active);
      $totalCount = $this->db->count_all_results('cdc_master_toko');
      $result['total']= $totalCount;

      //AMBIL DATA
      if(!empty($branchId)){
        //$this->db->where('BRANCH_ID',$branchId);
      }
      //$this->db->like('STORE_NAME',$storeName);
      //$this->db->like('ACTIVE_DATE',$active);

      $data = $this->db->query(' SELECT a."STORE_ID",a."STORE_CODE",a."STORE_NAME",a."STORE_TYPE",a."STORE_ADDRESS",a."ACTIVE_FLAG",a."INACTIVE_DATE",b."BRANCH_CODE" || \' - \' || b."BRANCH_NAME" AS "BRANCH" FROM cdc_master_toko AS a INNER JOIN cdc_master_branch AS b USING("BRANCH_ID")  ');
      $result['rows']=$data->result();

      return $result;
    }


    function addData($id,$data){
      $aDate  = date("Y-m-d");
      $create = date("Y-m-d");
      $last   = date("Y-m-d");

      $data = array('STORE_ID'=>$id,'STORE_CODE'=>$data['storeCode'],'STORE_NAME'=>$data['storeName'],'STORE_TYPE'=>$data['storeType'],
      'STORE_ADDRESS'=>$data['storeAddress'],'BRANCH_ID'=>$data['branchId'],'ACTIVE_FLAG'=>$data['activeFlag'],
      'ACTIVE_DATE'=>$aDate,'CREATION_DATE'=>$create,'LAST_UPDATE_DATE'=>$last);
      $this->db->insert('cdc_master_toko',$data);
    }


    function editData($data){
      $last   = date("Y-m-d");
      $id     = $data['storeId'];

      //active flag ganti
      if($data['activeFlag'] == "Y"){
        $inactive = null;
      }else{
        $inactive = date("Y-m-d");
      }

      $data = array('STORE_CODE'=>$data['storeCode'],'STORE_NAME'=>$data['storeName'],'STORE_TYPE'=>$data['storeType'],'STORE_ADDRESS'=>$data['storeAddress'],
      'BRANCH_ID'=>$data['branchId'],'ACTIVE_FLAG'=>$data['activeFlag'],'INACTIVE_DATE'=>$inactive,'LAST_UPDATE_DATE'=>$last);
      $this->db->where('STORE_ID',$id);
      $this->db->update('cdc_master_toko',$data);
    }

    function deleteData($id){
      $this->db->where('STORE_ID',$id);
      $this->db->delete('cdc_master_toko');
    }

    function getStore($code){

       $storeName  = $this->db->query(' SELECT "STORE_NAME" FROM cdc_master_toko WHERE "STORE_CODE" = \''.$code.'\'  ');
        $data       = $storeName->row()->STORE_NAME;
        return $data;
     
    }

    function checkStore($store_code)
    {
      $num = $this->db->query('SELECT "STORE_NAME" FROM cdc_master_toko WHERE BTRIM("STORE_CODE") = BTRIM(\''.str_replace(" ","",$store_code).'\')')->num_rows();
      if ($num > 0) {
        $statement = 'SELECT * FROM CDC_MASTER_AM_AS WHERE BTRIM("BRANCH_CODE") = BTRIM(?) AND BTRIM("STORE_CODE") = BTRIM(?)';
        if ($this->db->query($statement,array($this->session->userdata('branch_code'),str_replace(" ","",$store_code)))->num_rows() > 0) {
          return $num;
        }else{
          return 0;
        }
      }
      else{
        return $num;
      }
    }

  }
 ?>
