<?php
  class Mod_cdc_master_detail_penambah extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData($name,$account){
      $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
      $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }

      //HITUNG TOTAL DATA
      $this->db->like('TRX_PLUS_NAME',$name);
      $this->db->like('TRX_DETAIL_ACCOUNT',$account);
      $totalCount = $this->db->count_all_results('cdc_master_detail_penambah');
      $result['total']= $totalCount;

      //AMBIL DATA
      $this->db->order_by('TRX_PLUS_ID','asc');
      $this->db->like('TRX_PLUS_NAME',$name);
      $this->db->like('TRX_DETAIL_ACCOUNT',$account);
      $data=$this->db->get('cdc_master_detail_penambah',$rows,$offset);
      $result['rows']=$data->result();

      return $result;
    }

    function addData($id,$data){
      $aDate  = date("Y-m-d");
      $create = date("Y-m-d");
      $last   = date("Y-m-d");

      $data = array('TRX_PLUS_ID'=>$id,'TRX_PLUS_NAME'=>$data['plusName'],'TRX_PLUS_DESC'=>$data['plusDesc'],'TRX_DETAIL_ACCOUNT'=>$data['plusAccount'],
      'ACTIVE_FLAG'=>$data['activeFlag'],'ACTIVE_DATE'=>$aDate,'CREATION_DATE'=>$create,'LAST_UPDATE_DATE'=>$last);
      $this->db->insert('cdc_master_detail_penambah',$data);
    }

    function editData($data){
      $last   = date("Y-m-d");
      $id     = $data['plusId'];

      //active flag ganti
      if($data['activeFlag'] == "Y"){
        $inactive = null;
      }else{
        $inactive = date("Y-m-d");
      }

      $data = array('TRX_PLUS_NAME'=>$data['plusName'],'TRX_PLUS_DESC'=>$data['plusDesc'],'TRX_DETAIL_ACCOUNT'=>$data['plusAccount'],
      'ACTIVE_FLAG'=>$data['activeFlag'],'INACTIVE_DATE'=>$inactive,'LAST_UPDATE_DATE'=>$last);
      $this->db->where('TRX_PLUS_ID',$id);
      $this->db->update('cdc_master_detail_penambah',$data);
    }

    function deleteData($id){
      $this->db->where('TRX_PLUS_ID',$id);
      $this->db->delete('cdc_master_detail_penambah');
    }

	function getOption(){
    $option = $this->db->query(' SELECT "TRX_PLUS_ID", trim("TRX_PLUS_NAME") AS "TRX_PLUS_NAME" FROM cdc_master_detail_penambah WHERE "ACTIVE_FLAG" = \'Y\' ');
    $result=$option->result();
    //var_dump($result);
		return $result;
	}

  function getName($id){
    //var_dump($id);
    $data = $this->db->query(' SELECT "TRX_PLUS_NAME" FROM cdc_master_detail_penambah WHERE "TRX_PLUS_ID" = \' '.$id.' \' ');
    $result = $data->row()->TRX_PLUS_NAME;
    return $result;
  }
}
?>
