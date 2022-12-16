<?php
  class InquiryBatch extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('Mod_input_batch');
      $this->load->model('master/Mod_cdc_seq_table');
      $this->load->model('Mod_inquiry_batch');
      $this->load->model('Mod_deposit');
    }

    function index(){
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
	    $data['subMenu'] = $this->Mod_sys_menu->getSub();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
      $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      //$this->load->view("main/main_body");

      $this->load->view("view_inquiryBatch",$data);
      $this->load->view("main/main_footer");

    }
    function get_tipe_shift($branch_code,$store_code,$rec_id){
      return $this->Mod_inquiry_batch->get_tipe_shift($branch_code,$store_code,$rec_id);


    }
    function get_batch_type(){
      $batch_id=$this->input->post('batch_id');
      $result = $this->Mod_inquiry_batch->get_batch_type($batch_id);
      echo $result;
    }


    function getBatch(){
      $data['batchNumber'] = '';
      if($this->input->post('batchNumber')){
          $data['batchNumber'] = $this->input->post('batchNumber');
      }

      $data['tglBatch'] = '1000-01-01';
      if($this->input->post('tglBatch')){
        $data['tglBatch'] = $this->input->post('tglBatch');
      }

      $data['status']= 'N';
      if($this->input->post('status')){
          $data['status'] = $this->input->post('status');
      }

      $data['createBy'] = '';
      if($this->input->post('createBy')){
        $data['createBy'] = $this->input->post('createBy');
      }

      $data['type'] = '';
      if($this->input->post('type')){
        $data['type'] = $this->input->post('type');
      }

      //var_dump($data);

      $result = $this->Mod_inquiry_batch->getBatch($data);
      echo json_encode($result);
    }

    function getBatchReject(){
      $result = $this->Mod_inquiry_batch->getBatchReject();
      echo json_encode($result);
    }

    function getReceipt($batchId){
      $result = $this->Mod_inquiry_batch->getReceipt($batchId);
      echo json_encode($result);
    }

    function rejectBatch(){
      $result = $this->Mod_inquiry_batch->rejectBatch($this->input->post('batchID'));
      echo $result;
    }

    function delBatch(){
      $batchId = $this->input->post('batchID');
      $result = $this->Mod_inquiry_batch->delBatch($batchId);
      echo $result;
    }

    function validateBatch(){
      $batchId = $this->input->post();
      $result = $this->Mod_inquiry_batch->validateBatch($batchId);
      echo $result;
    }


    function transfer_stn()
    {

      date_default_timezone_set("Asia/Jakarta");

      $result = 0;
      if ($this->input->post()) {
        $this->load->library('ftp');

        // $config['hostname'] = 'ftpfadidm.indomaret.lan';
      
        // $config['hostname'] = 'ftpfadidm.indomaret.lan';
        // $config['username'] = 'ftpfinbu';
        // $config['password'] = 'New!dom4r@1dm';
        $config['hostname'] = 'ftpfadidm.indomaret.lan';
        $config['username'] = 'ftpfinbu';
        $config['password'] = 'New!dom4r@1dm';
        $config['port']     = 21;
        $config['debug']    = TRUE;
        $config['passive']  = TRUE;
        $path_lokal = '/opt/CDC_LOCAL/';
        $batch_id = str_replace('-', ',', $this->input->post('batch_id'));

        if ($this->ftp->connect($config)) {
          $list_dir = $this->ftp->list_files('/u01/bu/interface_data/');
          foreach ($list_dir as $dir) {
                    
            if (trim($this->session->userdata('branch_code')) == substr($dir, 23, 3)) {
               $count = 0;
              $data_batch = $this->Mod_inquiry_batch->get_data_transfer($batch_id);

              if ($data_batch) {
                  $file_name = 'IDMCDCSTN'.date('dmYHis').'.'.trim($this->session->userdata('branch_code'));
                  $fp = fopen($path_lokal.$file_name, 'a+');
                  $coll = array();
                  $success = 0;
                  $tipe_shift='';
                 
                foreach ($data_batch as $batch) {

                  $tipe_shift=$this->get_tipe_shift($batch->branch_code,$batch->store_code,$batch->rec_id);
                  if (fwrite($fp, 'HDRSTN|-9|||'.$batch->MUTATION_DATE.'||'.$batch->branch_code.'|'.$batch->bank_name.'|'.$batch->batch_id.'|'.$batch->batch_number.'|'.$batch->batch_type.'|'.$batch->batch_date.'|'.$batch->batch_status.'|'.$batch->description.'|'.$batch->reff_num.'|'.$batch->rec_id.'|'.$batch->store_code.'|'.$batch->sales_date.'|'.$batch->status.'|'.$batch->actual_sales_amount.'|'.$batch->actual_rrak_amount.'|'.$batch->actual_pay_less_deposited.'|'.$batch->actual_voucher_amount.'|'.$batch->actual_lost_item_payment.'|'.$batch->actual_wu_accountability.'|'.$batch->actual_others_amount.'|'.$batch->actual_others_desc.'|'.$batch->rrak_deduction.'|'.$batch->less_deposit_deduction.'|'.$batch->others_deduction.'|'.$batch->others_desc.'|'.$batch->actual_virtual_pay_less.'|'.$batch->actual_sales_flag.'|'.$batch->virtual_pay_less_deduction.'|'.str_replace('.', '', str_replace('-', '', $batch->BANK_ACCOUNT_NUM)).'|'.$tipe_shift."\n")) {
                    $success++;
                    $coll['batch_id'][] = $batch->batch_id;
                    $coll['rec_id'][] = $batch->rec_id;
                    if(($tipe_shift=='SS1' ||$tipe_shift=='SS2' ||$tipe_shift=='SS3') && ($batch->actual_sales_flag=='Y')){
                           $coll['final'][] = $batch->store_code.'|'.$batch->sales_date.'|'.$batch->batch_id;
                    }
               
                  }
                }

                if ($success > 0) {

                  for ($i=0; $i < count($coll['batch_id']); $i++) {
                    $data_gtu = $this->Mod_deposit->get_data_gtu_transfer($coll['batch_id'][$i]); 
                    if ($data_gtu) {
                      foreach ($data_gtu as $gtu) {
                        fwrite($fp, 'GTU|'.$gtu->CDC_GTU_ID.'|'.$gtu->CDC_BATCH_ID.'|'.str_replace(' ', '', $gtu->BRANCH_CODE).'|'.$gtu->BANK_NAME.'|'.$gtu->CDC_GTU_NUMBER.'|'.$gtu->CDC_GTU_AMOUNT."\n");
                      }
                    }
                  }

                  for ($i=0; $i < count($coll['rec_id']); $i++) { 
                    $data_pnb = $this->Mod_deposit->get_data_pnb_transfer($coll['rec_id'][$i]);
                    if ($data_pnb) {
                      foreach ($data_pnb as $pnb) {
                        fwrite($fp, 'PLS|'.$pnb->TRX_DETAIL_ID.'|'.$pnb->TRX_CDC_REC_ID.'|'.$pnb->trx_plus_name.'|'.$pnb->trx_detail_date.'|'.str_replace(",", "/", $pnb->trx_detail_desc).'|'.$pnb->TRX_DET_AMOUNT.'|'.$pnb->TRX_PLUS_ID."\n");
                      }
                    }
                  }

                  for ($i=0; $i < count($coll['rec_id']); $i++) { 
                    $data_pgr = $this->Mod_deposit->get_data_pgr_transfer($coll['rec_id'][$i]);
                    if ($data_pgr) {
                      foreach ($data_pgr as $pgr) {
                        fwrite($fp, 'MNS|'.$pgr->TRX_DETAIL_MINUS_ID.'|'.$pgr->TRX_CDC_REC_ID.'|'.$pgr->trx_minus_name.'|'.$pgr->trx_minus_date.'|'.str_replace(",", "/", $pgr->trx_minus_desc).'|'.$pgr->TRX_MINUS_AMOUNT.'|'.$pgr->TRX_MINUS_ID."\n");
                      }
                    }
                  }
                  for ($i=0; $i < count($coll['rec_id']); $i++) { 
                    $data_vcr = $this->Mod_deposit->get_data_vcr_transfer($coll['rec_id'][$i]);
                    if ($data_vcr) {
                      foreach ($data_vcr as $vcr) {
                        fwrite($fp, 'VCH|'.$vcr->TRX_VOUCHER_ID.'|'.$vcr->TRX_CDC_REC_ID.'|'.$vcr->trx_voucher_code.'|'.$vcr->TRX_VOUCHER_NUMBER.'|'.$vcr->voucher_num.'|'.$vcr->trx_voucher_date.'|'.str_replace(",", "/", $vcr->trx_voucher_desc).'|'.$vcr->TRX_VOUCHER_AMOUNT."\n");
                      }
                    }
                  }

                  for ($i=0; $i < count($coll['rec_id']); $i++) { 
                    $data_kur = $this->Mod_deposit->get_data_kur_transfer($coll['rec_id'][$i]);
                    if ($data_kur) {
                      foreach ($data_kur as $kur) {
                        fwrite($fp, 'KUR|'.$kur->CDC_REC_ID.'|'.$kur->CDC_INV_AR_NUM.'|'.$kur->STORE_CODE.'|'.$kur->TRX_DATE.'|'.$kur->CDC_DESC.'|'.$kur->CDC_ACTUAL_AMOUNT.'|'.$kur->TEMPLATE_FLAG."\n");
                      }
                    }
                  }

                  for ($i=0; $i < count($coll['rec_id']); $i++) { 
                    $data_stl = $this->Mod_deposit->get_data_stl_transfer($coll['rec_id'][$i]);
                    if ($data_stl) {
                      foreach ($data_stl as $stl) {
                        fwrite($fp, 'STL|'.$stl->CDC_STL_ID.'|'.$stl->CDC_REC_ID.'|'.$stl->DESCRIPTION.'|'.$stl->TRX_DATE.'|'.$stl->AMOUNT.'|'.$stl->CATEGORY."\n");
                      }
                    }
                  }
                    $result += $this->Mod_inquiry_batch->update_status_batch_transfer($batch_id,'Y');

                  if(isset($coll['final']))
                  {
                       $unik2 = array_unique($coll['final']);
                    $unik3=array();
                  foreach( $unik2 as $key ){
                      $pecah=explode("|",$key); 
                      $store_code=$pecah[0];
                  
                      	$jumlah_shift=$this->Mod_deposit->get_jumlah_shift($store_code)->TOTAL_SHIFT;
                     

                        $temp=$pecah[1];
                        $temp=explode("-",$temp);
                        $tgl_sales=$temp[0].'-'.$temp[1].'-20'.$temp[2];
                        $temp_unik3='';
                        $temp_unik3=$store_code.'|'.$tgl_sales; 
                        array_push($unik3,$temp_unik3);

                  }

                   foreach( array_unique($unik3) as $key ){

                           $pecah=explode("|",$key); 
                           $store_code=$pecah[0];
                           $temp=$pecah[1];
                           $temp=explode("-",$temp);
                           $tgl_sales=$temp[0].'-'.$temp[1].'-'.$temp[2];
                          

                        //  $batch_id=$pecah[2];
                          $data_final = $this->Mod_deposit->get_flag_final($store_code,$tgl_sales);
                      if ($data_final) {
                        foreach ($data_final as $fnl) {
                            fwrite($fp, 'FNL|'.$fnl->STORE_CODE.'|'.$fnl->SALES_DATE.'|'.$jumlah_shift."\n");
                        }
                      }
                  }
                 
                      }
                  fclose($fp);
                  $path_lokal2='/opt/CDC_LOCAL/';

                 // $path_lokal2='C:\xampp\CDC_LOCAL\\';
                  if ($this->ftp->upload($path_lokal.$file_name, $dir."/cdc/DATA_CDC/".$file_name, 'binary', 0777)) {
                    rename($path_lokal.$file_name, $path_lokal.'tmp/'.trim($this->session->userdata('branch_code')).'/'.$file_name);
                   
                  }else{
                     $result += $this->Mod_inquiry_batch->update_status_batch_transfer($batch_id,'N');
                  }
                }
              }
            }
          }
          $this->ftp->close();
        }
      }
     // echo "aneh";
      echo $result;
    }

    function change_sales_date()
    {
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      $data['bank'] = $this->Mod_deposit->getBank();
      $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      $this->load->view('view_change_sales',$data);
    }

    function get_data_sales_rec()
    {
      if ($this->input->post('batch_num')) {
        $data = $this->input->post();
        $result = $this->Mod_inquiry_batch->get_data_sales_rec($data);
        echo json_encode($result);
      }
    }

    function get_batch_id($batch_num)
    {
      $result = $this->Mod_inquiry_batch->get_batch_id($batch_num);
      echo $result[0]->CDC_BATCH_ID;
    }

    function get_combo_store()
    {
      $result = $this->Mod_inquiry_batch->get_combo_store($this->session->userdata('branch_code'));
      echo json_encode($result);
    }

    function cek_tanggal_sales()
    {
      if ($this->input->post()) {
        $data = $this->input->post();
        if (date_format(date_create($data['sales_date']),'Y-m-d') > date('Y-m-d')) {
          echo 'O';
        } else {
          $result = $this->Mod_inquiry_batch->cek_tanggal_sales($data['store_id'], $data['sales_date'], $data['act_sales_flag'], $data['stn_flag']);
          if ($result) {
            if ($result[0]->CDC_BATCH_ID) {
              $batch_num = $this->Mod_inquiry_batch->get_batch_number($result[0]->CDC_BATCH_ID);
              echo 'F'.$batch_num;
            } else echo 'FN';
          } else echo 'S';
        }
      }
    }

    function change_sales()
    {
      if ($this->input->post()) {
        $data = $this->input->post();
        $result = $this->Mod_inquiry_batch->change_sales($data['rec_id'], $data['store_id'], $data['sales_date']);
        echo $result;
      }
    }

  }

?>
