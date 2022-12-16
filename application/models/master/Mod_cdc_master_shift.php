<?php
  class Mod_cdc_master_shift extends CI_Model{
    function __construct(){
      parent ::__construct();
    }

    function getData(){
      $branchCode = $this->session->userdata('branch_code');

      $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
      $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }

      $this->db->where('SHIFT_BRANCH_CODE',$branchCode);
      $totalCount = $this->db->count_all_results('cdc_master_shift');
      $result['total']= $totalCount;

      //AMBIL DATA
      $this->db->order_by('BRANCH_ID','asc');
      $data = $this->db->query('
            SELECT a."SHIFT_ID",b."BRANCH_CODE" || \' - \' || b."BRANCH_NAME" AS "BRANCH", a."SHIFT_NUMBER",
                    a."SHIFT_TIME_FROM", a."SHIFT_TIME_TO", a."ACTIVE_FLAG", a."INACTIVE_DATE"
            FROM cdc_master_branch AS b INNER JOIN cdc_master_shift AS a ON (a."SHIFT_BRANCH_CODE" = b."BRANCH_CODE")
            WHERE a."SHIFT_BRANCH_CODE" = \''.$branchCode.'\'   ORDER BY a."SHIFT_NUMBER"
       ');
      $result['rows']=$data->result();
      //var_dump($result['rows']);

      return $result;
    }

    function cekData($branchCode){
      $this->db->where('SHIFT_BRANCH_CODE',$branchCode);
      $this->db->where('ACTIVE_FLAG','Y');
      $this->db->from('cdc_master_shift');
      $cari = $this->db->count_all_results();
      return $cari;
    }

    function addData($data){
      $aDate  = date("Y-m-d");
      $create = date("Y-m-d");
      $last   = date("Y-m-d");
      $branchCode = $this->session->userdata('branch_code');

      $tbl = "cdc_master_shift";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);
      $shift1 = array('SHIFT_ID'=>$id, 'SHIFT_NUMBER'=>1, 'SHIFT_BRANCH_CODE'=>$branchCode,
              'SHIFT_TIME_FROM'=>$data['start1'], 'SHIFT_TIME_TO'=>$data['end1'], 'ACTIVE_FLAG'=>$data['activeFlag'],
              'ACTIVE_DATE'=>$aDate, 'CREATION_DATE'=>$create, 'LAST_UPDATE_DATE'=>$last,'LAST_UPDATE_BY'=>$this->session->userdata('usrId')  );
      $this->db->insert('cdc_master_shift',$shift1);

      $id  = $this->Mod_cdc_seq_table->getID($tbl);
      $shift2 = array('SHIFT_ID'=>$id, 'SHIFT_NUMBER'=>2, 'SHIFT_BRANCH_CODE'=>$branchCode,
              'SHIFT_TIME_FROM'=>$data['start2'], 'SHIFT_TIME_TO'=>$data['end2'], 'ACTIVE_FLAG'=>$data['activeFlag'],
              'ACTIVE_DATE'=>$aDate, 'CREATION_DATE'=>$create, 'LAST_UPDATE_DATE'=>$last,'LAST_UPDATE_BY'=>$this->session->userdata('usrId')  );
      $this->db->insert('cdc_master_shift',$shift2);

      $id  = $this->Mod_cdc_seq_table->getID($tbl);
      $shift3 = array('SHIFT_ID'=>$id, 'SHIFT_NUMBER'=>3, 'SHIFT_BRANCH_CODE'=>$branchCode,
              'SHIFT_TIME_FROM'=>$data['start3'], 'SHIFT_TIME_TO'=>$data['end3'], 'ACTIVE_FLAG'=>$data['activeFlag'],
              'ACTIVE_DATE'=>$aDate, 'CREATION_DATE'=>$create, 'LAST_UPDATE_DATE'=>$last ,'LAST_UPDATE_BY'=>$this->session->userdata('usrId') );
      $this->db->insert('cdc_master_shift',$shift3);

      return "Add Berhasil";
    }


    function saveEdit($data){
      $last   = date("Y-m-d");
      $id = $data['shiftId'];

      //active flag ganti
      if($data['activeFlag'] == "Y"){
        $inactive = null;
      }else{
        $inactive = date("Y-m-d");
      }

      $dataUpdate = array('SHIFT_TIME_FROM'=>$data['start'], 'SHIFT_TIME_TO'=>$data['end'],
            'ACTIVE_FLAG'=>$data['activeFlag'],'INACTIVE_DATE'=>$inactive,'LAST_UPDATE_DATE'=>$last);
      $this->db->where('SHIFT_ID',$id);
      $this->db->update('cdc_master_shift',$dataUpdate);
      return "Edit Berhasil";
    }


    function getShift(){
      $branch = $this->session->userdata('branch_code');
      //$this->load->model('Mod_cdc_master_shift');
      $cek = $this->Mod_cdc_master_shift->cekData($branch);

      if($cek == 3){
        $data = $this->db->query(' SELECT "SHIFT_ID", "SHIFT_NUMBER", "SHIFT_TIME_FROM", "SHIFT_TIME_TO" FROM cdc_master_shift WHERE "SHIFT_BRANCH_CODE"=\''.$branch.'\' ORDER BY "SHIFT_NUMBER" ');
      } else{
        $data = $this->db->query(' SELECT "SHIFT_ID", "SHIFT_NUMBER", "SHIFT_TIME_FROM", "SHIFT_TIME_TO" FROM cdc_master_shift WHERE "SHIFT_BRANCH_CODE" IS NULL ORDER BY "SHIFT_NUMBER" ');
      }
            return $data->result();
    }

    function get_kode_toko(){
      $statement='SELECT concat(cst."STORE_CODE",\'-\',trim(cst."STORE_NAME")) as "STORE",cst."STORE_CODE" as "STORE_CODE" from cdc_master_toko cmt,cdc_stores cst where cmt."STORE_CODE"=cst."STORE_CODE" and cst."TGL_INACTIVE_CABANG" is null and cmt."BRANCH_ID"=? order by cst."STORE_CODE" ASC';
       return $this->db->query($statement,$this->session->userdata('branch_id'))->result();
    } 
  


    function getDataMasterShift($store_code,$start_date,$end_date,$status,$metode_setor,$jml_shift,$tipe_shift){
      $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
      $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
      $offset = ($page-1)*$rows;
      if($offset < 0){
        $offset = 0;
      }
      $where='';
      if($store_code!=''){
        $where.='  AND cms."STORE_CODE"=TRIM(\''.$store_code.'\')';
      }
      if($start_date!=''){
        $where.='  AND cms."TGL_ACTIVE">=\''.$start_date.'\'';
      }
      if($end_date!=''){
        $where.='  AND cms."TGL_ACTIVE">=\''.$end_date.'\'';
      }
      if($status!=''){
         $where.='  AND cms."STATUS"=\''.$status.'\'';
      }
      if($metode_setor!=''){
         $where.='  AND cms."TIPE_SETORAN"=\''.$metode_setor.'\'';
      }
      if($jml_shift!=''){
         $where.='  AND (cms."TOTAL_SHIFT")=\''.$jml_shift.'\'';
      }
      if($tipe_shift!=''){
         $where.='  AND cms."TIPE_SHIFT"=\''.$tipe_shift.'\'';
      }
     // echo "total_shift :".$jml_shift;
      //HITUNG TOTAL DATA

      $statement1='SELECT count(*) AS "TOTAL"  from cdc_master_shift cms where cms."BRANCH_CODE"=(select "BRANCH_CODE" from cdc_master_branch cmb where cmb."BRANCH_ID"=?)  '.$where;
      $result['total']= $this->db->query($statement1,array($this->session->userdata('branch_id')))->row()->TOTAL;

      //AMBIL DATA

      $statement='SELECT cms."ID_SHIFT", (select concat(cmt."STORE_CODE",\'-\',cmt."STORE_NAME") from cdc_master_toko cmt where cmt."STORE_CODE"=cms."STORE_CODE") as "TOKO",cms."TGL_ACTIVE",cms."TGL_INACTIVE",cms."STATUS",(select cmb."BRANCH_NAME" from cdc_master_branch cmb where cmb."BRANCH_CODE"=cms."BRANCH_CODE"),cms."TOTAL_SHIFT" as "JML_SHIFT",(case when (cms."TIPE_SHIFT"=\'SS\')
     then \'SALES SHIFT\'
     when (cms."TIPE_SHIFT"=\'H\')
     then \'HARIAN\'
     when (cms."TIPE_SHIFT"=\'H-1\')
     then \'HARIAN SHIFT\'
end) AS "TIPE_SHIFT",cms."TIPE_SETORAN" as "METODE_SETORAN"  from cdc_master_shift cms where cms."BRANCH_CODE"=(select "BRANCH_CODE" from cdc_master_branch cmb where cmb."BRANCH_ID"=?) '.$where.' ORDER BY cms."CREATION_DATE" DESC,cms."LAST_UPDATE_DATE" DESC limit ? OFFSET ?';
      $data=$this->db->query($statement,array($this->session->userdata('branch_id'),$rows,$offset))->result();
 
      $result['rows']=$data;
      //$data=$this->db->get('cdc_master_branch',5,0); //brp row , mulai dari

       return $result;
    }

    function insertMastershift($toko){


      $row_affected=0;
      $decoded   = $toko;


      for($i=0;$i<count($toko);$i++){
        $kd_toko=$toko[$i]['KD_TOKO'];
        $tgl_active=$toko[$i]['ACTIVE_DATE'];
        $tgl_inactive=$toko[$i]['END_DATE'];
        $total_shift=$toko[$i]['JML_SHIFT'];
        $tipe_setoran=$toko[$i]['METODE_SETOR'];
        $tipe_shift='';
        if($toko[$i]['TIPE_SHIFT']=='HARIAN'){
          $tipe_shift='H';
        }else if($toko[$i]['TIPE_SHIFT']=='HARIAN_SHIFT'){
          $tipe_shift='HS';
        }else{
          $tipe_shift='SS';
        }
          $statement_kd_cabang='SELECT cmb."BRANCH_CODE" as "BRANCH_CODE" FROM cdc_master_toko cmt,cdc_master_branch cmb WHERE cmt."BRANCH_ID"=cmb."BRANCH_ID" AND cmt."STORE_CODE"=? ';
          $rs=$this->db->query($statement_kd_cabang,$kd_toko)->row();
          $kd_cbg=$rs->BRANCH_CODE;
          $statement_cek='SELECT count(*) AS "HITUNG" from cdc_master_shift where "STORE_CODE"=?';
          $cek=$this->db->query($statement_cek,$kd_toko)->row();
          if($cek->HITUNG=='0'){
            if($tgl_inactive!=''&& $tgl_inactive!='--' && $tgl_inactive!=' ' ){

             $statement =  'INSERT into cdc_master_shift ("BRANCH_CODE","STORE_CODE","TGL_ACTIVE","TGL_INACTIVE","STATUS","TOTAL_SHIFT","TIPE_SETORAN","TIPE_SHIFT","CREATION_DATE","LAST_UPDATE_DATE","LAST_UPDATE_BY") VALUES (?,?,?,?,?,?,?,?,CURRENT_DATE,CURRENT_DATE,?)';
              $result=$this->db->query($statement,array($kd_cbg,$kd_toko,$tgl_active,$tgl_inactive,'A',$total_shift,$tipe_setoran,$tipe_shift,$this->session->userdata('usrId')));
           
            }else{
               $statement =  'INSERT into cdc_master_shift ("BRANCH_CODE","STORE_CODE","TGL_ACTIVE","STATUS","TOTAL_SHIFT","TIPE_SETORAN","TIPE_SHIFT","CREATION_DATE","LAST_UPDATE_DATE","LAST_UPDATE_BY") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,CURRENT_DATE,?)';
              $result=$this->db->query($statement,array($kd_cbg,$kd_toko,$tgl_active,'A',$total_shift,$tipe_setoran,$tipe_shift,$this->session->userdata('usrId')));
           
            }
            $row_affected++;
          }else{
              $statement_cek_aktif='SELECT count(*) AS "HITUNG" from cdc_master_shift where "STORE_CODE" = ? 
              and ("TGL_INACTIVE" IS NULL or "TGL_INACTIVE" > ? )';
              $cek_aktif=$this->db->query($statement_cek_aktif,array($kd_toko,$tgl_active))->row();
              //kalau udah ada datanya 
              //cek dulu data yg sudah ada ini masih aktif atau ga

              // kalau masih aktif
              if($cek_aktif->HITUNG=='1')
              {
                //TOLAK

             
              }else{
                $statement_cek_aktif='SELECT "TGL_ACTIVE","TGL_INACTIVE" from cdc_master_shift where "STORE_CODE"=? and ("TGL_INACTIVE" >= ? or "TGL_INACTIVE" IS NULL)';
                $cek_aktif=$this->db->query($statement_cek_aktif,array($kd_toko,$tgl_active))->row();
                 // data existing sudah ada namun sudah tidak aktif
                  //insert baru aja dengan catatan tanggal aktif nya tidak diantara tanggal aktif dan inaktif yg existing
                if($cek_aktif->TGL_ACTIVE<=$tgl_active && ($cek_aktif->TGL_INACTIVE<=$tgl_active||$cek_aktif->TGL_INACTIVE == NULL))
                {
                    $statement =  'INSERT into cdc_master_shift ("BRANCH_CODE","STORE_CODE","TGL_ACTIVE","STATUS","TOTAL_SHIFT","TIPE_SETORAN","TIPE_SHIFT","CREATION_DATE","LAST_UPDATE_DATE","LAST_UPDATE_BY") VALUES (?,?,?,?,?,?,?,CURRENT_DATE,CURRENT_DATE,?)';
                    $result=$this->db->query($statement,array($kd_cbg,$kd_toko,$tgl_active,'A',$total_shift,$tipe_setoran,$tipe_shift,$this->session->userdata('usrId')));
                    $row_affected++;
                }
               
                 

              }
            
            //   if($tgl_inactive!=''&& $tgl_inactive!='--' && $tgl_inactive!=' ' ){

            //   $statement =  'UPDATE cdc_master_shift set "BRANCH_CODE"=?,"TGL_ACTIVE"=?,"TGL_INACTIVE"=?,"STATUS"=?,"TOTAL_SHIFT"=?,"TIPE_SETORAN"=?,"TIPE_SHIFT"=?,"LAST_UPDATE_DATE"=CURRENT_DATE WHERE "STORE_CODE"=?';
            //   $result=$this->db->query($statement,array($kd_cbg,$tgl_active,$tgl_inactive,'A',$total_shift,$tipe_setoran,$tipe_shift,$kd_toko));
            //   $row_affected++;
           
            // }else{
            //    $statement =  'UPDATE cdc_master_shift set "BRANCH_CODE"=?,"TGL_ACTIVE"=?,"STATUS"=?,"TOTAL_SHIFT"=?,"TIPE_SETORAN"=?,"TIPE_SHIFT"=?,"LAST_UPDATE_DATE"=CURRENT_DATE WHERE "STORE_CODE"=?';
            //   $result=$this->db->query($statement,array($kd_cbg,$tgl_active,'A',$total_shift,$tipe_setoran,$tipe_shift,$kd_toko));
            //   $row_affected++;
            // }
             

          }
         
          
      }

      return $row_affected;
      

    }


    function deleteMasterShift($id_shift){

      $statement='DELETE FROM cdc_master_shift where "ID_SHIFT"=?';
      $rs=$this->db->query($statement,$id_shift);
      return $this->db->affected_rows();
    }


   function updateMasterShift($id_shift,$store_code,$start_date,$end_date,$tipe_shift,$jml_shift,$metode_setor){
        date_default_timezone_set('Asia/Jakarta');
        $date = substr($start_date, 6).'-'.substr($start_date, 3,2).'-'.substr($start_date, 0,2);


        $date2 = substr($end_date, 6).'-'.substr($end_date, 3,2).'-'.substr($end_date, 0,2);
        if($date2=='--'){
          $date2=null;
        }
        //cek dulu sebelumnya ini udah pernah ada data nya atau belum




        if($date2==''){
            $statement =  'UPDATE cdc_master_shift set "TGL_ACTIVE"=?,"TOTAL_SHIFT"=?,"TGL_INACTIVE"=NULL,"TIPE_SETORAN"=?,"TIPE_SHIFT"=?,"STATUS"=?,"LAST_UPDATE_DATE"=CURRENT_DATE WHERE "STORE_CODE"=? and "ID_SHIFT"=?';
            $result=$this->db->query($statement,array($date,$jml_shift,$metode_setor,$tipe_shift,'A',$store_code,$id_shift));
             return $this->db->affected_rows();
        }else{
            if( date('Y-m-d' , strtotime($date2))<=date('Y-m-d')){

              $activeFlag='I';
            }else{
              $activeFlag='A';
            }
            $statement =  'UPDATE cdc_master_shift set "TGL_ACTIVE"=?,"TGL_INACTIVE"=?,"TOTAL_SHIFT"=?,"TIPE_SETORAN"=?,"TIPE_SHIFT"=?,"STATUS"=?,"LAST_UPDATE_DATE"=CURRENT_DATE WHERE "STORE_CODE"=? and "ID_SHIFT"=?';
            $result=$this->db->query($statement,array($date,$date2,$jml_shift,$metode_setor,$tipe_shift,$activeFlag,$store_code,$id_shift));
             return $this->db->affected_rows();
        }
        
        
            

   }

    function getDataMasterShiftDetail($id_shift){
      $statement='SELECT "STORE_CODE","TGL_ACTIVE","TGL_INACTIVE","TOTAL_SHIFT","TIPE_SETORAN","TIPE_SHIFT" FROM cdc_master_shift where "ID_SHIFT"=?';
      $rs=$this->db->query($statement,$id_shift)->row();
      return $rs;
    }

    function shiftLogin(){
    ////////////////////  JAM INPUT   //////////////////////////////
      date_default_timezone_set("Asia/Bangkok");
      $info = getdate();
      $hour = $info['hours'];
      $min = $info['minutes'];
      $sec = $info['seconds'];
      $current_time = "$hour:$min:$sec";
      //$current_time = "22:50:01";
    ///////////////////////////////////////////////////////////////
        $shift = $this->Mod_cdc_master_shift->getShift();
        $shift_number = 0; $loop = 0;
        foreach ($shift as $number) {
          $loop++;
          //var_dump('FROM : '.$number->SHIFT_TIME_FROM.'  TO : '.$number->SHIFT_TIME_TO.'<br>');
          if(strtotime($current_time) > strtotime($number->SHIFT_TIME_FROM) && strtotime($current_time) < strtotime($number->SHIFT_TIME_TO) ){
            $shift_number = $loop;
            $shift_id     = $number->SHIFT_ID;
            break;
          }
          if($loop == 3 ){
            $shift_number = 3;
            $shift_id     = $number->SHIFT_ID;
            break;
          }
        }
        return $loop;

    }



  }
?>
