<?php


    public function transfer_deposit()
    {
      date_default_timezone_set("Asia/Jakarta");
      $result = 0;

      if ($this->input->post()) {
        $this->load->library('ftp');
        
        $config['hostname'] = 'ftpfadidm.indomaret.lan';
        $config['username'] = 'ftpdevba';
        $config['password'] = 'ftpdevba';
        // $config['username'] = 'ftpdevbu';
       // $config['password'] = 'ftpdevbu2';
        // $config['username'] = 'ftpfinbu';
        // $config['password'] = 'New!dom4r@1dm';
        $config['port']     = 21;
        $config['debug']    = TRUE;
        $config['passive']  = TRUE;
         $path_lokal = 'D:/xampp/CDC_LOCAL/';
       // $path_lokal = '/opt/CDC_LOCAL/';
        $deposit_id = $this->input->post('depid');

        if ($this->input->post('vir_status') == 'S') {
          $data_vir = $this->Mod_deposit->get_data_change_vir($deposit_id);

          foreach ($data_vir['detail'] as $det) {
            if ($det->CEK == 'N') {
              $this->Mod_deposit->update_master_min_virtual($det->TRX_DETAIL_MINUS_ID);
            }
          }

          foreach ($data_vir['receipt'] as $rec) {
            $this->Mod_deposit->update_sum_receipt_min_vir($rec->TRX_CDC_REC_ID);
          }
        }

        if ($this->ftp->connect($config)) {
          // $list_dir = $this->ftp->list_files('/u01/bu/interface_data/');
          $list_dir = $this->ftp->list_files('/u01/budev/interface_ba/');

 
          foreach ($list_dir as $dir) {

            if (trim($this->session->userdata('branch_code')) == substr($dir, 24, 3)) {

              //substr($dir, 23, 3)
              $count = 0;
              $data_deposit = $this->Mod_deposit->get_data_transfer($deposit_id);

              if ($data_deposit) {


                  $file_name = 'IDMCDCSTJ'.$data_deposit[0]->deposit_id.date('dmY').'.'.trim($this->session->userdata('branch_code'));
                  $fp = fopen($path_lokal.$file_name, 'a+');
                  $coll = array();
                  $success = 0;
                  $header_type = '';

                  $batch_cek = $this->Mod_deposit->get_batch_sum($deposit_id);
                  $batch_reg = $this->Mod_deposit->get_batch_sum_reg($deposit_id);
                  $batch_ktr = $this->Mod_deposit->get_batch_sum_ktr($deposit_id);

                  if ($batch_reg > 0) {
                    $batch_cek++;
                  }

                  if ($batch_ktr > 0) {
                    $batch_cek++;
                  }

                  fwrite($fp, 'BATCH_CHECK|'.$deposit_id.'|'.$batch_cek."\n");
                  $flag_final='';
                foreach ($data_deposit as $deposit) {
                  if (substr($deposit->batch_type, -3) == 'STJ') {
                    $header_type = 'HDRSTJ';
                  } elseif ($deposit->batch_type == 'STL-TN') {
                    $header_type = 'HDRSTL';
                  } else $header_type = 'HDRKUR';
                  $tipe_shift='';
                 
                  $tipe_shift=$this->get_tipe_shift($deposit->branch_code,$deposit->store_code,$deposit->rec_id);
                 // $flag_final=$this->get_flag_final($tipe_shift,$deposit->branch_code,$deposit->store_code,$deposit->sales_date,$deposit->rec_id);
                  if (fwrite($fp, $header_type.'|'.$deposit->deposit_id.'|'.$deposit->deposit_num.'|'.$deposit->deposit_date.'|'.$deposit->mutation_date.'|'.$deposit->deposit_status.'|'.$deposit->branch_code.'|'.$deposit->bank_name.'|'.$deposit->batch_id.'|'.$deposit->batch_number.'|'.$deposit->batch_type.'|'.$deposit->batch_date.'|'.$deposit->batch_status.'|'.$deposit->description.'|'.$deposit->reff_num.'|'.$deposit->rec_id.'|'.$deposit->store_code.'|'.$deposit->sales_date.'|'.$deposit->status.'|'.$deposit->actual_sales_amount.'|'.$deposit->actual_rrak_amount.'|'.$deposit->actual_pay_less_deposited.'|'.$deposit->actual_voucher_amount.'|'.$deposit->actual_lost_item_payment.'|'.$deposit->actual_wu_accountability.'|'.$deposit->actual_others_amount.'|'.$deposit->actual_others_desc.'|'.$deposit->rrak_deduction.'|'.$deposit->less_deposit_deduction.'|'.$deposit->others_deduction.'|'.$deposit->others_desc.'|'.$deposit->actual_virtual_pay_less.'|'.$deposit->actual_sales_flag.'|'.$deposit->virtual_pay_less_deduction.'|'.str_replace('.', '', str_replace('-', '', $deposit->BANK_ACCOUNT_NUM)).'|'.$tipe_shift."\n")) {
                    $success++;
                    $coll['batch_id'][] = $deposit->batch_id;
                    $coll['rec_id'][] = $deposit->rec_id;
                    if(($tipe_shift=='SS1' ||$tipe_shift=='SS2' ||$tipe_shift=='SS3') && ($deposit->actual_sales_flag =='Y')){
                          $coll['final'][] = $deposit->store_code.'|'.$deposit->sales_date.'|'.$deposit->batch_id;
                    }
              
                  
                  }
                }

                if ($success > 0) {

                  $unik = array_unique($coll['batch_id']);
                  $x = 0;
                  $tampung;
                                
                  foreach( $unik as $key => $value ){
                      //echo $key."\t=>\t".$value."\n";
                      $tampung[$x] = $value;
                      $x = $x + 1;
                  }

                  for ($i=0; $i < count($tampung); $i++) {
                    $data_gtu = $this->Mod_deposit->get_data_gtu_transfer($tampung[$i]); 
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
                  $result += $this->Mod_deposit->update_status_deposit_transfer($deposit_id,'T');
                  if(isset($coll['final'])){
                      $unik2 = array_unique($coll['final']);
                      $unik3=array();

                      foreach( $unik2 as $key ){

                           $pecah=explode("|",$key); 
                           $store_code=$pecah[0];
                           $temp=$pecah[1];
                           $temp=explode("-",$temp);
                           $tgl_sales=$temp[0].'-'.$temp[1].'-20'.$temp[2];
                           $temp_unik3='';
                           $temp_unik3=$store_code.'|'.$tgl_sales; 
                           array_push($unik3,$temp_unik3);

                          // $batch_id=$pecah[2];
                         //  $jumlah_shift=$this->Mod_deposit->get_jumlah_shift($store_code)->TOTAL_SHIFT;
                          // $data_final = $this->Mod_deposit->get_flag_final($store_code,$tgl_sales,$batch_id);
                          // if ($data_final) {
                          //   foreach ($data_final as $fnl) {
                          //       fwrite($fp, 'FNL|'.$fnl->STORE_CODE.'|'.$fnl->SALES_DATE.'|'.$jumlah_shift."\n");
                          //   }
                          // }
                      }
                       foreach( array_unique($unik3) as $key ){

                           $pecah=explode("|",$key); 
                           $store_code=$pecah[0];
                           $temp=$pecah[1];
                           $temp=explode("-",$temp);
                           $tgl_sales=$temp[0].'-'.$temp[1].'-'.$temp[2];
                          
                          //$batch_id=$pecah[2];
                           $jumlah_shift=0;
                          
                          $data_final = $this->Mod_deposit->get_flag_final($store_code,$tgl_sales);
                          if ($data_final) {
                            foreach ($data_final as $fnl) {
                              $jumlah_shift=$this->Mod_deposit->get_jumlah_shift($store_code,$tgl_sales);
                                fwrite($fp, 'FNL|'.$fnl->STORE_CODE.'|'.$fnl->SALES_DATE.'|'.$jumlah_shift."\n");
                            }
                          }
                      }

                  
                  }
                  


                  fclose($fp);
                  $path_lokal2='D:\xampp\CDC_LOCAL\\';
                //  $path_lokal2 = '/opt/CDC_LOCAL/';
                  if ($this->ftp->upload($path_lokal.$file_name, $dir."/cdc/DATA_CDC/".$file_name, 'binary', 0777)) {
                   
                     rename($path_lokal.$file_name, $path_lokal.'/tmp/'.trim($this->session->userdata('branch_code')).'/'.$file_name);

                     // rename($path_lokal.$file_name, $path_lokal.'tmp/'.trim($this->session->userdata('branch_code')).'/'.$file_name);
                    $list_cek = $this->ftp->list_files($dir.'/cdc/DATA_CDC/');
                    $cek_list = 0;
                    for ($i=0; $i < count($list_cek); $i++) {

                      if (substr($list_cek[$i], strrpos($list_cek[$i], '/', 36)+1) == $file_name) {
                        $cek_list = 1;
                      }
                    }
                    if ($cek_list <=0) {
                      $result += $this->Mod_deposit->update_status_deposit_transfer($deposit_id,'V');
                    }
                  }
                }
              }
            }
          }
          $this->ftp->close();
        }
        }
        echo $result;
    }


?>