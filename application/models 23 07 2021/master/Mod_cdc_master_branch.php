<?php
  class Mod_cdc_master_branch extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData($code,$name){
      $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
      $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }

      //HITUNG TOTAL DATA
      $this->db->like('BRANCH_CODE',$code);
      $this->db->like('BRANCH_NAME',$name);
      $totalCount = $this->db->count_all_results('cdc_master_branch');
      $result['total']= $totalCount;

      //AMBIL DATA
      $this->db->order_by('BRANCH_ID','asc');
      $this->db->like('BRANCH_CODE',$code);
      $this->db->like('BRANCH_NAME',$name);
      $data=$this->db->get('cdc_master_branch',$rows,$offset);
      $result['rows']=$data->result();

      return $result;
    }

    function addData($id,$data){
      $aDate  = date("Y-m-d");
      $create = date("Y-m-d");
      $last   = date("Y-m-d");

      $data = array('BRANCH_ID'=>$id,'BRANCH_CODE'=>$data['branchCode'],'BRANCH_NAME'=>$data['branchName'],'REG_ORG_ID'=>$data['regOrg'],
      'FRC_ORG_ID'=>$data['frcOrg'],'ACTIVE_FLAG'=>$data['activeFlag'],'ACTIVE_DATE'=>$aDate,'CREATION_DATE'=>$create,'LAST_UPDATE_DATE'=>$last);
      $this->db->insert('cdc_master_branch',$data);
    }

    function editData($data){
      $last   = date("Y-m-d");
      $id     = $data['branchId'];

      //active flag ganti
      if($data['activeFlag'] == "Y"){
        $inactive = null;
      }else{
        $inactive = date("Y-m-d");
      }

      $data = array('BRANCH_CODE'=>$data['branchCode'],'BRANCH_NAME'=>$data['branchName'],'REG_ORG_ID'=>$data['regOrg'],
      'FRC_ORG_ID'=>$data['frcOrg'],'ACTIVE_FLAG'=>$data['activeFlag'],'INACTIVE_DATE'=>$inactive,'LAST_UPDATE_DATE'=>$last);
      $this->db->where('BRANCH_ID',$id);
      $this->db->update('cdc_master_branch',$data);
    }

    function deleteData($id){
      $this->db->where('BRANCH_ID',$id);
      $this->db->delete('cdc_master_branch');
    }

	function getOption(){
    $option = $this->db->query(' SELECT "BRANCH_ID","BRANCH_CODE" || \' - \' || "BRANCH_NAME" AS "BRANCH" FROM cdc_master_branch ');
    	$result=$option->result();
		return $result;
	}

  function getBranchId($code){
    $branchId = $this->db->query(' SELECT "BRANCH_ID" FROM cdc_master_branch WHERE "BRANCH_CODE" = \''.$code.'\'  ');
    $data = $branchId->row()->BRANCH_ID;  //ambil 1 field
    return $data;
  }

  function getBranchCode($id){
    $branchCode = $this->db->query(' SELECT "BRANCH_CODE" FROM cdc_master_branch WHERE "BRANCH_ID" = \''.$id.'\' ');
    $data = $branchCode->row()->BRANCH_CODE;
    return $data;
  }

  function getBranchName($code){
    $branchName = $this->db->query(' SELECT "BRANCH_NAME" FROM cdc_master_branch WHERE "BRANCH_CODE" = \''.$code.'\' ');
    $data = $branchName->row()->BRANCH_NAME;
    return $data;
  }
}
?>
