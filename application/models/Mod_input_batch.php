<?php
class Mod_input_batch extends CI_Model{
  function __construct(){
    parent ::__construct();
  }

  function getPraInput(){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $this->load->model('master/Mod_cdc_seq_table');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $data = $this->db->query(' SELECT c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD Month YYYY\') "SALES_DATE", e."BANK_NAME", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG",
      (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
      ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
      (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
      ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER"

      FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
      INNER JOIN cdc_master_bank_account AS d ON(
        CASE
          WHEN c."STN_FLAG" = \'N\' THEN a."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
          ELSE c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
        END
      )
      INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
      WHERE "CDC_BATCH_ID" IS NULL AND "CREATED_BY"=\''.$createBy.'\' AND "BRANCH_CODE"= \''.$branchCode.'\' AND "STATUS"=\'N\' AND "OTHERS_DESC" IS NULL ORDER BY "CDC_REC_ID" DESC ');

    $result['rows']=$data->result();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }
  function get_tipe_shift($store_code,$sales_date,$sales_flag)
  {
    date_default_timezone_set("Asia/Bangkok");
    $a = explode('-',$sales_date);
    $my_new_date = $a[2].'-'.$a[1].'-'.$a[0];
    $tgl       = date("Y-m-d");
    $statement1='SELECT count(*) as "CEK" FROM cdc_master_shift where "STORE_CODE"=? AND "BRANCH_CODE"=(select "BRANCH_CODE" FROM  cdc_master_branch where "BRANCH_ID"=?)  and "TGL_ACTIVE"<=? and ("TGL_INACTIVE" IS NULL or "TGL_INACTIVE" > ?)';
    $rs_statement1=$this->db->query($statement1,array($store_code,$this->session->userdata('branch_id'),$my_new_date,$my_new_date))->row();
    if($rs_statement1->CEK=='1'){

      $statement2='SELECT "TIPE_SHIFT" as "TIPE_SHIFT" FROM cdc_master_shift where "STORE_CODE"=? AND "BRANCH_CODE"=(select "BRANCH_CODE" FROM  cdc_master_branch where "BRANCH_ID"=?)  and "TGL_ACTIVE"<=? and ("TGL_INACTIVE" IS NULL or "TGL_INACTIVE" > ?)';
      $rs_statement2=$this->db->query($statement2,array($store_code,$this->session->userdata('branch_id'),$my_new_date,$my_new_date))->row();
      if($rs_statement2->TIPE_SHIFT=='H' || $rs_statement2->TIPE_SHIFT=='H-1' || $rs_statement2->TIPE_SHIFT=='HS'){
        $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE"  IN (\'H\',\'H-1\',\'HS\')';
        return $this->db->query($statement)->result();
      }else{

    
      $statement2='SELECT "TOTAL_SHIFT" AS "TOTAL_SHIFT" FROM cdc_master_shift where "STORE_CODE"=? and  "TGL_ACTIVE"<=? and ("TGL_INACTIVE" IS NULL OR "TGL_INACTIVE">?)';
       $rs_statement2 = $this->db->query($statement2,array($store_code,$my_new_date,$my_new_date))->row();

      
       if($rs_statement2->TOTAL_SHIFT==2 ){
           $statement_receipts_shift1='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_shift1=$this->db->query($statement_receipts_shift1,array($store_code,$my_new_date,'S-1'))->row();
           $statement_receipts_shift2='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\' ';
           $rs_receipts_shift2=$this->db->query($statement_receipts_shift2,array($store_code,$my_new_date,'S-2'))->row();
           $statement_receipts_harian='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\' ';
           $rs_receipts_shiftharian=$this->db->query($statement_receipts_harian,array($store_code,$my_new_date,'H'))->row();
           $statement_receipts_harianshift='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."SHIFT_FLAG"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_harianshift=$this->db->query($statement_receipts_harianshift,array($store_code,$my_new_date,'HS'))->row();
            
           if($sales_flag=='Y'){
              if($rs_receipts_harianshift->HITUNG=='0'&& $rs_receipts_shiftharian->HITUNG=='0' && $rs_receipts_shift2->HITUNG=='0'   &&  $rs_receipts_shift1->HITUNG=='0' )
              {
                 $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                 return $this->db->query($statement)->result();
              }else{
                if($rs_receipts_shift2->HITUNG>0 &&  $rs_receipts_shift1->HITUNG>0 ){

               
                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                return $this->db->query($statement)->result();

                }else if($rs_receipts_shift2->HITUNG==0 &&  $rs_receipts_shift1->HITUNG>0 ){
                   $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                   return $this->db->query($statement)->result();

                }else if($rs_receipts_shiftharian->HITUNG>0 ){
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                    return $this->db->query($statement)->result();
               }else if($rs_receipts_harianshift->HITUNG>0){
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                    return $this->db->query($statement)->result();
               }else if($rs_receipts_shift2->HITUNG>0 &&  $rs_receipts_shift1->HITUNG==0 ){
                   $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-2\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                   return $this->db->query($statement)->result();

                }else{
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                    return $this->db->query($statement)->result();
                  
                }
              }
              
           }else{
               if($rs_receipts_harianshift->HITUNG=='0'&& $rs_receipts_shiftharian->HITUNG=='0' && $rs_receipts_shift2->HITUNG=='0'   &&  $rs_receipts_shift1->HITUNG=='0' )
              {
                 $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                 return $this->db->query($statement)->result();
              }else{
                   if($rs_receipts_shift2->HITUNG>0 && $rs_receipts_shift1->HITUNG>0 ){
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                    return $this->db->query($statement)->result();

                  }else  if($rs_receipts_shift2->HITUNG>0 || $rs_receipts_shift1->HITUNG>0 ){
                        $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                        return $this->db->query($statement)->result();

                  }else  if($rs_receipts_shiftharian->HITUNG>0){
                        $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
                        return $this->db->query($statement)->result();

                  }else  if($rs_receipts_harianshift->HITUNG!='0'){
                        $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
                        return $this->db->query($statement)->result();

                  }else{
                   $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                   return $this->db->query($statement)->result();
                }


              }
              
           }

           

       }else if ($rs_statement2->TOTAL_SHIFT==3){
           $statement_receipts_shift1='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_shift1=$this->db->query($statement_receipts_shift1,array($store_code,$my_new_date,'S-1'))->row();
           $statement_receipts_shift2='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_shift2=$this->db->query($statement_receipts_shift2,array($store_code,$my_new_date,'S-2'))->row();
           $statement_receipts_shift3='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_shift3=$this->db->query($statement_receipts_shift3,array($store_code,$my_new_date,'S-3'))->row();
                
           $statement_receipts_harian='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_shiftharian=$this->db->query($statement_receipts_harian,array($store_code,$my_new_date,'H'))->row();
           $statement_receipts_harianshift='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."SHIFT_FLAG"=? AND ctrs."ACTUAL_SALES_FLAG"=\'Y\'';
           $rs_receipts_harianshift=$this->db->query($statement_receipts_harianshift,array($store_code,$my_new_date,'HS'))->row();
         
           if($sales_flag=='Y'){
              if($rs_receipts_shiftharian->HITUNG=='0')
              {//kalau shift harian H kosong
                 if($rs_receipts_harianshift->HITUNG=='0')
                 { //kalau  h-1 dan h kosong
                      if(($rs_receipts_shift1->HITUNG=='0')){
                         //kalau h,h-1 dan 1 kosong
                         if(($rs_receipts_shift2->HITUNG=='0')){
                          //kalau h,h-1,1,2 kosong
                             if(($rs_receipts_shift3->HITUNG=='0')){
                                //kalau h,h-1,2,3 kosong

                                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc  where "SHIFT_CODE" NOT IN (\'H-1\',\'H\',\'HS\') ';
                                return $this->db->query($statement)->result();

                             }else{
                              //kalau h,h-1,1,2 kosong tapi 3 ga kosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                             }


                         }else{
                          //kalau h,h-1 ,1 kosong tapi 2 ga ksoong
                            $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-2\',\'H-1\',\'H\',\'HS\')';
                            return $this->db->query($statement)->result();
                         }

                      }else{
                          //kalau h,h-1 kosong tapi 1 ga kosong
                         if(($rs_receipts_shift2->HITUNG=='0')){
                            // kalau h,h-1 kosong,2 kosong tapi 1 ga kosong
                            if(($rs_receipts_shift3->HITUNG=='0')){
                            // kalau h,h-1 kosong,2 kosong,3 tapi 1 ga kosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'H-1\',\'H\',\'HS\')';
                               return $this->db->query($statement)->result();
                            }else{
                              //h,h-1 kosong,2 kosong tapi 1 sama 3 ga ksosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                            }
                         }else{
                            //kalau h,h-1 kosong,1 ga ksosong dan 2 ga kosong

                              if(($rs_receipts_shift3->HITUNG=='0')){
                                  //h,h-1 ksosog, 1 ga kosong,2 ga ksosong tapi 3 kosong
                                  $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'H-1\',\'H\',\'HS\')';
                                  return $this->db->query($statement)->result();

                              }else{
                                //h , h-1 kosong, 1 ga kosong,2 ga ksosong dan 3 pun ga ksoong
                                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                              }
                         }
                      }
                  }else{
                    //kalau shift h-1 sudah diisi
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H\',\'HS\')';
                    return $this->db->query($statement)->result();
                  }

              }else{
                //kalau shift Harian H sudah diisi
                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\',\'H\',\'HS\')';
                return $this->db->query($statement)->result();

              }
              
           }else if( $sales_flag=='N'){
             
             
              if($rs_receipts_shiftharian->HITUNG=='0')
              {//kalau shift harian H kosong
                 if($rs_receipts_harianshift->HITUNG=='0')
                 { //kalau  h-1 dan h kosong
                      if(($rs_receipts_shift1->HITUNG=='0')){
                         //kalau h,h-1 dan 1 kosong
                         if(($rs_receipts_shift2->HITUNG=='0')){
                          //kalau h,h-1,1,2 kosong
                             if(($rs_receipts_shift3->HITUNG=='0')){
                                //kalau h,h-1,2,3 kosong

                                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc ';
                                return $this->db->query($statement)->result();

                             }else{
                              //kalau h,h-1,1,2 kosong tapi 3 ga kosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                             }


                         }else{
                          //kalau h,h-1 ,1 kosong tapi 2 ga ksoong
                            $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'S-1\',\'H-1\',\'H\',\'HS\')';
                            return $this->db->query($statement)->result();
                         }

                      }else{
                          //kalau h,h-1 kosong tapi 1 ga kosong
                         if(($rs_receipts_shift2->HITUNG=='0')){
                            // kalau h,h-1 kosong,2 kosong tapi 1 ga kosong
                            if(($rs_receipts_shift3->HITUNG=='0')){
                            // kalau h,h-1 kosong,2 kosong,3 tapi 1 ga kosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-3\',\'S-2\',\'H-1\',\'H\',\'HS\')';
                               return $this->db->query($statement)->result();
                            }else{
                              //h,h-1 kosong,2 kosong tapi 1 sama 3 ga ksosong
                               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                            }
                         }else{
                            //kalau h,h-1 kosong,1 ga ksosong dan 2 ga kosong

                              if(($rs_receipts_shift3->HITUNG=='0')){
                                  //h,h-1 ksosog, 1 ga kosong,2 ga ksosong tapi 3 kosong
                                  $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-3\',\'H-1\',\'H\',\'HS\')';
                                  return $this->db->query($statement)->result();

                              }else{
                                //h , h-1 kosong, 1 ga kosong,2 ga ksosong dan 3 pun ga ksoong
                                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'H-1\',\'H\',\'HS\')';
                                return $this->db->query($statement)->result();

                              }
                         }
                      }
                  }else{
                    //kalau shift h-1 sudah diisi
                    $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
                    return $this->db->query($statement)->result();
                  }

              }else{
                //kalau shift Harian H sudah diisi
                $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
                return $this->db->query($statement)->result();

              }
              
           }else{
               $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'H-1\',\'H\',\'HS\')';
                return $this->db->query($statement)->result();
           }
       }
      }

      
      
    }else{
       $statement_receipts_harian='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."NO_SHIFT"=?';
        $rs_receipts_shiftharian=$this->db->query($statement_receipts_harian,array($store_code,$my_new_date,'H'))->row()->HITUNG;
        $statement_receipts_harianshift='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts_shift ctrs where ctrs."STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) AND ctrs."SALES_DATE"=? and ctrs."SHIFT_FLAG"=?';
        $rs_receipts_harianshift=$this->db->query($statement_receipts_harianshift,array($store_code,$my_new_date,'HS'))->row()->HITUNG;

        if($rs_receipts_shiftharian=='1')
        {
          $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
          return $this->db->query($statement)->result();
        }else  if($rs_receipts_harianshift!='0'){
          $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
          return $this->db->query($statement)->result();
        }else  {
          //if($rs_receipts_shiftharian=='0' && $rs_receipts_harianshift=='0')
          $statement = 'SELECT "SHIFT_CODE" AS "SHIFT", "SHIFT_DESC" AS "SHIFT_DESC" FROM cdc_shift_desc where "SHIFT_CODE" NOT IN (\'S-1\',\'S-2\',\'S-3\')';
          return $this->db->query($statement)->result();
        }
    }
    
  }


  function getPraInputShift(){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $data = $this->db->query(' SELECT c."CDC_SHIFT_REC_ID",c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD Month YYYY\') "SALES_DATE", e."BANK_NAME", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG",
      (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
      ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
      (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN","NO_SHIFT",
      ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher_shift where "TRX_CDC_REC_ID" = c."CDC_SHIFT_REC_ID" ) AS "TOTAL_VOUCHER"
      FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts_shift AS c USING ("STORE_ID")
      INNER JOIN cdc_master_bank_account AS d ON(
        CASE
          WHEN c."STN_FLAG" = \'N\' THEN a."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
          ELSE c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
        END
      )
      INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
      WHERE "CDC_BATCH_ID" IS NULL AND "CREATED_BY"=\''.$createBy.'\' AND "BRANCH_CODE"= \''.$branchCode.'\' AND "STATUS"=\'N\' AND "OTHERS_DESC" IS NULL ORDER BY c."CREATION_DATE" DESC ');

    $result['rows']=$data->result();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }

  function getPraInputRejectShift($batch_id){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $data = $this->db->query(' SELECT c."CDC_SHIFT_REC_ID",c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD Month YYYY\') "SALES_DATE", e."BANK_NAME", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG",
      (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
      ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
      (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
      ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER","SHIFT_FLAG","NO_SHIFT"

      FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts_shift AS c USING ("STORE_ID")
      INNER JOIN cdc_master_bank_account AS d ON(
        CASE
          WHEN c."STN_FLAG" = \'N\' THEN a."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
          ELSE c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
        END
      )
      INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
      WHERE "CDC_BATCH_ID"  = '.$batch_id.' AND "BRANCH_CODE"= \''.$branchCode.'\' ORDER BY "CDC_REC_ID" DESC ');

    $result['rows']=$data->result();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }


  function getPraInputReject($batch_id){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $data = $this->db->query(' SELECT c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD Month YYYY\') "SALES_DATE", e."BANK_NAME", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG",
      (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
      ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
      (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
      ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER"

      FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
      INNER JOIN cdc_master_bank_account AS d ON(
        CASE
          WHEN c."STN_FLAG" = \'N\' THEN a."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
          ELSE c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID"
        END
      )
      INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
      WHERE "CDC_BATCH_ID"  = '.$batch_id.' AND "BRANCH_CODE"= \''.$branchCode.'\' ORDER BY "CDC_REC_ID" DESC ');

    $result['rows']=$data->result();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }

  function getPraInputKurRec(){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);
    $statement = 'SELECT CTR."CDC_REC_ID", CTR."STORE_ID", CMT."STORE_CODE", CMT."STORE_NAME", CTR."SALES_DATE", CTR."ACTUAL_SALES_AMOUNT", CTR."ACTUAL_SALES_FLAG", CTR."STN_FLAG",CTR."NO_SHIFT"
      FROM CDC_TRX_RECEIPTS CTR, CDC_MASTER_TOKO CMT
      WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CTR."CREATED_BY" = ? AND CTR."BRANCH_CODE" = ? AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" = \'KURSET\' ORDER BY CTR."CDC_REC_ID" DESC';
    $data = $this->db->query($statement,array(intval($createBy),$branchCode));

    $result['rows'] = $data->result();
    $result['total'] = $data->num_rows();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }

  function getPraInputKurRecShift(){
    $createBy   = $this->session->userdata('usrId');
    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);
    $statement = 'SELECT CTR."CDC_SHIFT_REC_ID",CTR."CDC_REC_ID", CTR."STORE_ID", CMT."STORE_CODE", CMT."STORE_NAME", CTR."SALES_DATE", CTR."ACTUAL_SALES_AMOUNT", CTR."ACTUAL_SALES_FLAG", CTR."STN_FLAG"
      FROM CDC_TRX_RECEIPTS_SHIFT CTR, CDC_MASTER_TOKO CMT
      WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CTR."CREATED_BY" = ? AND CTR."BRANCH_CODE" = ? AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" = \'KURSET\' ORDER BY CTR."CDC_REC_ID" DESC';
    $data = $this->db->query($statement,array(intval($createBy),$branchCode));

    $result['rows'] = $data->result();
    $result['total'] = $data->num_rows();
      //echo "BRANCH_ID : ".$this->session->userdata('branch_id');
    return $result;
  }

  /*function praInputShift($data){
    date_default_timezone_set("Asia/Bangkok");
    $salesDate  = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);
    $createBy   = $this->session->userdata('usrId');
    $createDate = date("Y-m-d H:i:s");
    $lastBy     = $this->session->userdata('usrId');
    $last       = date("Y-m-d H:i:s");
    $store = $this->getStoreID($data['store_code']);

    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $mutation_date = NULL;
    $bank_acc = NULL;
    if ($data['mutation_date']) {
      $mutation_date = $data['mutation_date'];
      $bank_acc = $data['bank_acc'];
    }

   

    //$exp = explode('-',$data['receiptID']);

    if($data['statusxx'] == 'n' && $data['flag_shift'] == 'Y'){
       $exp = [$data['receiptID'],$data['receiptID2'],$data['receiptID3']];
      if($data['receiptID'] != '--'){
        for($i = 0; $i < 3; $i++){
          if($exp[$i] != ''){

            if($data['flag_shift'] == 'Y'){
               $sales_amt = 0 + $this->getTotalSalesShift($exp[$i],$i+1);
            }
            else{
               $sales_amt = 0 + $data['sales_amount'];
            }
           

            //PENAMBAHAN
            $rrak       = 0 + $this->getTotalPenambahShift($exp[$i],9) + $this->getTotalPenambahShift($exp[$i],10);  //RRAK
            //var_dump($rrak);
            $kurset_t    = 0 + $this->getTotalPenambahShift($exp[$i],11);  //KURANG SETOR
            $virtual    = 0 + $this->getTotalPenambahShift($exp[$i],12);  //KURANG SETOR VIRTUAL
            //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
            $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
            $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
            $other_t     = 0 + $this->getTotalPenambahShift($exp[$i],13); //LAIN-LAIN
            //$other_t    = 0 + $other6 + $other7;

        //PENGURANGAN
            $d_rrak    = 0 + $this->getTotalPengurangShift($exp[$i],35);
            $d_kurset  = 0 + $this->getTotalPengurangShift($exp[$i],27) + $this->getTotalPengurangShift($exp[$i],28) + $this->getTotalPengurangShift($exp[$i],29) + $this->getTotalPengurangShift($exp[$i],30) + $this->getTotalPengurangShift($exp[$i],31);
            $d_virtual  = 0 + $this->getTotalPengurangShift($exp[$i],32) + $this->getTotalPengurangShift($exp[$i],33);
            $d_other_t  = 0 + $this->getTotalPengurangShift($exp[$i],34);

            if($sales_amt != 0)
            {
              $dataInput = array('CDC_SHIFT_REC_ID'=>intval($exp[$i]),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
                'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
                'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
                'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=>$data['flag_shift'],'NO_SHIFT'=>$i+1);

               $this->db->insert('cdc_trx_receipts_shift',$dataInput);
             }
          }
        }
      }
    }
    else if($data['statusxx'] == 'n' && $data['flag_shift'] == 'N'){

            $sales_amt = 0 + $data['sales_amount'];
            $shift = 'H';
            //PENAMBAHAN
            $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
            //var_dump($rrak);
            $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
            $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
            //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
            $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
            $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
            $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
            //$other_t    = 0 + $other6 + $other7;

        //PENGURANGAN
            $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
            $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
            $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
            $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34);

            if($sales_amt != 0 && $data['flag'] != 'N')
            {
              $insert_pengganti = array('TRX_CDC_REC_ID'=>intval($data['receiptID']),'NO_SHIFT'=>$shift,'TRX_PENG_AMOUNT'=>$sales_amt,'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last);

              $this->db->insert('cdc_trx_detail_pengganti',$insert_pengganti);
            }

              $dataInput = array('CDC_SHIFT_REC_ID'=>intval($data['receiptID']),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
                'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
                'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
                'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=>$data['flag_shift'],'NO_SHIFT'=>$shift,'BANK_ACCOUNT_ID'=>$bank_acc);

               $this->db->insert('cdc_trx_receipts_shift',$dataInput);
             
    }
    else if($data['statusxx'] == 'e'){
         if ($data['receiptID'] != 0 || $data['receiptID'] != '') {

          //PENAMBAHAN
            $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
            //var_dump($rrak);
            $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
            $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
            //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
            $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
            $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
            $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
            //$other_t    = 0 + $other6 + $other7;

        //PENGURANGAN
            $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
            $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
            $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
            $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34);


          $dataInput = array(
            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL, 'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'SALES_DATE'=>$salesDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
          $this->db->where('CDC_SHIFT_REC_ID',$data['receiptID']);
          $this->db->update('cdc_trx_receipts_shift',$dataInput);
        }
    }

//PENAMBAHAN
     /* $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambah($data['receiptID'],10);  //RRAK
      //var_dump($rrak);
      $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
      $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
      //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
      $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
      $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
      $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
      //$other_t    = 0 + $other6 + $other7;*/

//PENGURANGAN
      /*$d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
      $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurang($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurang($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
      $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurang($data['receiptID'],33);
      $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34);*/

     /* if($data['statusxx'] == 'n'){
        if ($data['receiptID'] != 0 || $data['receiptID'] != '') {
          $dataInput = array('CDC_SHIFT_REC_ID'=>intval($data['receiptID']),'CDC_REC_ID'=>intval($data['realrecid']), 'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'NO_SHIFT'=>$data['flag_shift']);
          $this->db->insert('cdc_trx_receipts_shift',$dataInput);
        }
      }
      else if($data['statusxx'] == 'e'){
        if ($data['receiptID'] != 0 || $data['receiptID'] != '') {
          $dataInput = array(
            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL, 'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'SALES_DATE'=>$salesDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
          $this->db->where('CDC_REC_ID',$data['receiptID']);
          $this->db->update('cdc_trx_receipts_shift',$dataInput);
        }
      }*/

    //}
       function praInputShift($data){
        date_default_timezone_set("Asia/Bangkok");
        try {
            $salesDate  = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);
            if($data['start_input_time'])
            {
                $start_input_time = str_replace("+"," ",$data['start_input_time']);

            }else{
                $start_input_time =  date("Y-m-d H:i:s");

            }
            $createBy   = $this->session->userdata('usrId');
            $createDate = date("Y-m-d H:i:s");
            $lastBy     = $this->session->userdata('usrId');
            $last       = date("Y-m-d H:i:s");
            $store = $this->getStoreID($data['store_code']);

            $this->load->model('master/Mod_cdc_master_branch');
            $branchId     = $this->session->userdata('branch_id');
            $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

            $mutation_date = NULL;
            $bank_acc = NULL;
            if ($data['mutation_date']) {
              $mutation_date = $data['mutation_date'];
              $bank_acc = $data['bank_acc'];
            }
            $flag_shift='';
            if($data['flag_shift'] == 'H-1'){
              $flag_shift='HS';
            }else if($data['flag_shift'] == 'N' || $data['flag_shift'] == 'H' ){
              $flag_shift='N';
            }else{
              $flag_shift='SS';
            }
            if($data['statusxx'] == 'n' && $data['flag_shift'] == 'H-1'){
                $exp = [$data['receiptID'],$data['receiptID2'],$data['receiptID3']];
                if($data['receiptID'] != '--'){
                    for($i = 0; $i < 3; $i++){
                      if($exp[$i] != ''){

                        if($data['flag_shift'] == 'H-1'){
                           $sales_amt = 0 + $this->getTotalSalesShift($exp[$i],$i+1);
                        }
                        else{
                           $sales_amt = 0 + $data['sales_amount'];
                        }
                       

                        //PENAMBAHAN
                        $rrak       = 0 + $this->getTotalPenambahShift($exp[$i],9) + $this->getTotalPenambahShift($exp[$i],10);  //RRAK
                        //var_dump($rrak);
                        $kurset_t    = 0 + $this->getTotalPenambahShift($exp[$i],11);  //KURANG SETOR
                        $virtual    = 0 + $this->getTotalPenambahShift($exp[$i],12);  //KURANG SETOR VIRTUAL
                        //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
                        $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
                        $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
                        $other_t     = 0 + $this->getTotalPenambahShift($exp[$i],13); //LAIN-LAIN
                        //$other_t    = 0 + $other6 + $other7;

                    //PENGURANGAN
                        $d_rrak    = 0 + $this->getTotalPengurangShift($exp[$i],35);
                        $d_kurset  = 0 + $this->getTotalPengurangShift($exp[$i],27) + $this->getTotalPengurangShift($exp[$i],28) + $this->getTotalPengurangShift($exp[$i],29) + $this->getTotalPengurangShift($exp[$i],30) + $this->getTotalPengurangShift($exp[$i],31);
                        $d_virtual  = 0 + $this->getTotalPengurangShift($exp[$i],32) + $this->getTotalPengurangShift($exp[$i],33);
                        $d_other_t  = 0 + $this->getTotalPengurangShift($exp[$i],34)+$this->getTotalPengurangShift($exp[$i],36);

                        if($sales_amt != 0)
                        {
                          $dataInput = array('CDC_SHIFT_REC_ID'=>intval($exp[$i]),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
                            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,'START_INPUT_TIME'=>$start_input_time,
                            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
                            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=> $flag_shift,'NO_SHIFT'=>$i+1);

                           $this->db->insert('cdc_trx_receipts_shift',$dataInput);
                         }
                      }
                    }
                  }
            }else if($data['statusxx'] == 'n' && $data['flag_shift'] != 'H-1' ){

                $sales_amt = 0 + $data['sales_amount'];
                $shift =$data['flag_shift']  ;

                if(($flag_shift=='SS' && $data['flag_shift'])||($flag_shift!='SS'))
                {
                     //PENAMBAHAN
                    $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
                    //var_dump($rrak);
                    $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
                    $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
                    //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
                    $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
                    $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
                    $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
                    //$other_t    = 0 + $other6 + $other7;

                    //PENGURANGAN
                    $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
                    $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
                    $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
                    $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34)+$this->getTotalPengurangShift($data['receiptID'],36);

                    if($sales_amt != 0 && $data['flag'] != 'N')
                    {
                      $insert_pengganti = array('TRX_CDC_REC_ID'=>intval($data['receiptID']),'NO_SHIFT'=>$shift,'TRX_PENG_AMOUNT'=>$sales_amt,'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last);
                       $statement_cek='select count(*) as "HITUNG" from cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ?';
                       $cek=$this->db->query($statement_cek,$data['receiptID'])->row();
                       if($cek->HITUNG==0)
                       {

                          $this->db->insert('cdc_trx_detail_pengganti',$insert_pengganti);

                       }else{
                        
                          $this->db->where('TRX_CDC_REC_ID',$data['receiptID']);
                          $this->db->update('cdc_trx_detail_pengganti',$insert_pengganti);
                       }
                    }
//ini

                     $statement_cek='select count(*) as "HITUNG" from cdc_trx_receipts_shift where "STORE_ID"=? and "SALES_DATE"=? and "ACTUAL_SALES_FLAG"=\'Y\' AND "NO_SHIFT"=?';
                      $result=$this->db->query($statement_cek,array($store,$salesDate,$shift))->row();
                      if($result->HITUNG=='0' && $data['flag']=='Y'){
                            $dataInput = array('CDC_SHIFT_REC_ID'=>intval($data['receiptID']),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
                            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
                            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,'START_INPUT_TIME'=>$start_input_time,
                            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=> $flag_shift,'NO_SHIFT'=>$shift,'BANK_ACCOUNT_ID'=>$bank_acc);

                           $this->db->insert('cdc_trx_receipts_shift',$dataInput);
                      }else if($data['flag']=='N'){
                            $dataInput = array('CDC_SHIFT_REC_ID'=>intval($data['receiptID']),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
                            'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
                            'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                            'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                            'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,'START_INPUT_TIME'=>$start_input_time,
                            'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=> $flag_shift,'NO_SHIFT'=>$shift,'BANK_ACCOUNT_ID'=>$bank_acc);

                           $this->db->insert('cdc_trx_receipts_shift',$dataInput);
                      }

                 

                }
           
            }
            else if($data['statusxx'] == 'e'){
                 if ($data['receiptID'] != 0 || $data['receiptID'] != '') {

                  //PENAMBAHAN
                    $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
                    //var_dump($rrak);
                    $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
                    $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
                    //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
                    $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
                    $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
                    $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
                    //$other_t    = 0 + $other6 + $other7;

                //PENGURANGAN
                    $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
                    $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
                    $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
                    $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34)+$this->getTotalPengurangShift($data['receiptID'],36);


                    $dataInput = array(
                    'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
                    'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
                    'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL, 'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
                    'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
                    'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'SALES_DATE'=>$salesDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
                    $this->db->where('CDC_SHIFT_REC_ID',$data['receiptID']);
                    $this->db->update('cdc_trx_receipts_shift',$dataInput);

                  if($data['batch_id'] != ''){
                    $cek = $this->cek_rec($data['realrecid']);

                    if($cek){
                      if($data['no_shift'] != 'H-1' ){
                        $stmt = 'UPDATE cdc_trx_receipts SET "ACTUAL_SALES_FLAG" = ?,"ACTUAL_SALES_AMOUNT" = ?, "ACTUAL_RRAK_AMOUNT" = ?, "ACTUAL_PAY_LESS_DEPOSITED" = ?, "ACTUAL_VOUCHER_AMOUNT" = ?, "ACTUAL_LOST_ITEM_PAYMENT" = ?, "ACTUAL_OTHERS_AMOUNT" = ?,"ACTUAL_WU_ACCOUNTABILITY" = ?,"ACTUAL_VIRTUAL_PAY_LESS" = ?,"RRAK_DEDUCTION" = ?,"LESS_DEPOSIT_DEDUCTION" = ?,"OTHERS_DEDUCTION" = ?,"SALES_DATE" = ?,"LAST_UPDATE_DATE" = ?, "STN_FLAG" = ?,"VIRTUAL_PAY_LESS_DEDUCTION" = ?, "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?';

                        $this->db->query($stmt,array($data['flag'],0+$data['sales_amount'],$rrak,$kurset_t,0,$lost,$other_t,$wu,$virtual,$d_rrak,$d_kurset,$d_other_t,$salesDate,$last,$data['stn'],$d_virtual,$mutation_date,$bank_acc,$data['realrecid']));
                      }
                      else{
                        $sales_amt2 = $this->getTotalSalesShiftEdit($data['realrecid']);
                        //PENAMBAHAN
                        $rrak2       = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],9) + $this->getTotalPenambahShiftEdit($data['receiptID'],10);  //RRAK
                        //var_dump($rrak);
                        $kurset_t2    = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],11);  //KURANG SETOR
                        $virtual2    = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],12);  //KURANG SETOR VIRTUAL
                        //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
                        $lost2       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
                        $wu2     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
                        $other_t2     = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],13); //LAIN-LAIN
                        //$other_t    = 0 + $other6 + $other7;

                    //PENGURANGAN
                        $d_rrak2    = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],35);
                        $d_kurset2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],27) + $this->getTotalPengurangShiftEdit($data['receiptID'],28) + $this->getTotalPengurangShiftEdit($data['receiptID'],29) + $this->getTotalPengurangShiftEdit($data['realrecid'],30) + $this->getTotalPengurangShiftEdit($data['receiptID'],31);
                        $d_virtual2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
                        $d_other_t2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],34)+$this->getTotalPengurangShift($data['realrecid'],36);

                        $stmt = 'UPDATE cdc_trx_receipts SET "ACTUAL_SALES_FLAG" = ?,"ACTUAL_SALES_AMOUNT" = ?, "ACTUAL_RRAK_AMOUNT" = ?, "ACTUAL_PAY_LESS_DEPOSITED" = ?, "ACTUAL_VOUCHER_AMOUNT" = ?, "ACTUAL_LOST_ITEM_PAYMENT" = ?, "ACTUAL_OTHERS_AMOUNT" = ?,"ACTUAL_WU_ACCOUNTABILITY" = ?,"ACTUAL_VIRTUAL_PAY_LESS" = ?,"RRAK_DEDUCTION" = ?,"LESS_DEPOSIT_DEDUCTION" = ?,"OTHERS_DEDUCTION" = ?,"SALES_DATE" = ?,"LAST_UPDATE_DATE" = ?, "STN_FLAG" = ?,"VIRTUAL_PAY_LESS_DEDUCTION" = ?, "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?';

                        $this->db->query($stmt,array($data['flag'],0+$sales_amt2,$rrak2,$kurset_t2,0,$lost2,$other_t2,$wu2,$virtual2,$d_rrak2,$d_kurset2,$d_other_t2,$salesDate,$last,$data['stn'],$d_virtual2,$mutation_date,$bank_acc,$data['realrecid']));
                      }
                    }
                  }


                  
                }
            }
       
        } catch (Exception $e) {
          // exception is raised and it'll be handled here
          // $e->getMessage() contains the error message
        }
       

    
    
  }
  //     function praInputShift($data){
  //     //  echo str_replace("+"," ",$data['start_input_time']);
  //     //  echo " tes ";
  //       date_default_timezone_set("Asia/Bangkok");
  //       $salesDate  = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);
  //       if($data['start_input_time'])
  //       {
  //           $start_input_time = str_replace("+"," ",$data['start_input_time']);

  //       }else{
  //           $start_input_time =  date("Y-m-d H:i:s");

  //       }
  //       $createBy   = $this->session->userdata('usrId');
  //       $createDate = date("Y-m-d H:i:s");
  //       $lastBy     = $this->session->userdata('usrId');
  //       $last       = date("Y-m-d H:i:s");
  //       $store = $this->getStoreID($data['store_code']);

  //       $this->load->model('master/Mod_cdc_master_branch');
  //       $branchId     = $this->session->userdata('branch_id');
  //       $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

  //       $mutation_date = NULL;
  //       $bank_acc = NULL;
  //       if ($data['mutation_date']) {
  //         $mutation_date = $data['mutation_date'];
  //         $bank_acc = $data['bank_acc'];
  //       }
  //       $flag_shift='';
  //       if($data['flag_shift'] == 'H-1'){
  //         $flag_shift='HS';
  //       }else if($data['flag_shift'] == 'N' || $data['flag_shift'] == 'H' ){
  //         $flag_shift='N';
  //       }else{
  //         $flag_shift='SS';
  //       }
       

  //   //$exp = explode('-',$data['receiptID']);

  //   if($data['statusxx'] == 'n' && $data['flag_shift'] == 'H-1'){
  //      $exp = [$data['receiptID'],$data['receiptID2'],$data['receiptID3']];
  //     if($data['receiptID'] != '--'){
  //       for($i = 0; $i < 3; $i++){
  //         if($exp[$i] != ''){

  //           if($data['flag_shift'] == 'H-1'){
  //              $sales_amt = 0 + $this->getTotalSalesShift($exp[$i],$i+1);
  //           }
  //           else{
  //              $sales_amt = 0 + $data['sales_amount'];
  //           }
           

  //           //PENAMBAHAN
  //           $rrak       = 0 + $this->getTotalPenambahShift($exp[$i],9) + $this->getTotalPenambahShift($exp[$i],10);  //RRAK
  //           //var_dump($rrak);
  //           $kurset_t    = 0 + $this->getTotalPenambahShift($exp[$i],11);  //KURANG SETOR
  //           $virtual    = 0 + $this->getTotalPenambahShift($exp[$i],12);  //KURANG SETOR VIRTUAL
  //           //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
  //           $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
  //           $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
  //           $other_t     = 0 + $this->getTotalPenambahShift($exp[$i],13); //LAIN-LAIN
  //           //$other_t    = 0 + $other6 + $other7;

  //       //PENGURANGAN
  //           $d_rrak    = 0 + $this->getTotalPengurangShift($exp[$i],35);
  //           $d_kurset  = 0 + $this->getTotalPengurangShift($exp[$i],27) + $this->getTotalPengurangShift($exp[$i],28) + $this->getTotalPengurangShift($exp[$i],29) + $this->getTotalPengurangShift($exp[$i],30) + $this->getTotalPengurangShift($exp[$i],31);
  //           $d_virtual  = 0 + $this->getTotalPengurangShift($exp[$i],32) + $this->getTotalPengurangShift($exp[$i],33);
  //           $d_other_t  = 0 + $this->getTotalPengurangShift($exp[$i],34)+$this->getTotalPengurangShift($exp[$i],36);

  //           if($sales_amt != 0)
  //           {
  //             $dataInput = array('CDC_SHIFT_REC_ID'=>intval($exp[$i]),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
  //               'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,'START_INPUT_TIME'=>$start_input_time,
  //               'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
  //               'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
  //               'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
  //               'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=> $flag_shift,'NO_SHIFT'=>$i+1);

  //              $this->db->insert('cdc_trx_receipts_shift',$dataInput);
  //            }
  //         }
  //       }
  //     }
  //   }
  //   else if($data['statusxx'] == 'n' && $data['flag_shift'] != 'H-1' ){

  //           $sales_amt = 0 + $data['sales_amount'];
  //           $shift =$data['flag_shift']  ;
  //           //PENAMBAHAN
  //           $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
  //           //var_dump($rrak);
  //           $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
  //           $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
  //           //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
  //           $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
  //           $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
  //           $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
  //           //$other_t    = 0 + $other6 + $other7;

  //       //PENGURANGAN
  //           $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
  //           $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
  //           $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
  //           $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34)+$this->getTotalPengurangShift($data['receiptID'],36);

  //           if($sales_amt != 0 && $data['flag'] != 'N')
  //           {
  //             $insert_pengganti = array('TRX_CDC_REC_ID'=>intval($data['receiptID']),'NO_SHIFT'=>$shift,'TRX_PENG_AMOUNT'=>$sales_amt,'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last);
  //              $statement_cek='select count(*) as "HITUNG" from cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ?';
  //              $cek=$this->db->query($statement_cek,$data['receiptID'])->row();
  //              if($cek->HITUNG==0)
  //              {

  //                 $this->db->insert('cdc_trx_detail_pengganti',$insert_pengganti);

  //              }else{
                
  //                 $this->db->where('TRX_CDC_REC_ID',$data['receiptID']);
  //                 $this->db->update('cdc_trx_detail_pengganti',$insert_pengganti);
  //              }
  //           }

  //             $dataInput = array('CDC_SHIFT_REC_ID'=>intval($data['receiptID']),'CDC_REC_ID'=>intval($data['realrecid']),'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
  //               'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$sales_amt,'ACTUAL_RRAK_AMOUNT'=>$rrak,
  //               'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
  //               'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
  //               'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,'START_INPUT_TIME'=>$start_input_time,
  //               'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'SHIFT_FLAG'=> $flag_shift,'NO_SHIFT'=>$shift,'BANK_ACCOUNT_ID'=>$bank_acc);

  //              $this->db->insert('cdc_trx_receipts_shift',$dataInput);
             
  //   }
  //   else if($data['statusxx'] == 'e'){
  //        if ($data['receiptID'] != 0 || $data['receiptID'] != '') {

  //         //PENAMBAHAN
  //           $rrak       = 0 + $this->getTotalPenambahShift($data['receiptID'],9) + $this->getTotalPenambahShift($data['receiptID'],10);  //RRAK
  //           //var_dump($rrak);
  //           $kurset_t    = 0 + $this->getTotalPenambahShift($data['receiptID'],11);  //KURANG SETOR
  //           $virtual    = 0 + $this->getTotalPenambahShift($data['receiptID'],12);  //KURANG SETOR VIRTUAL
  //           //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
  //           $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
  //           $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
  //           $other_t     = 0 + $this->getTotalPenambahShift($data['receiptID'],13); //LAIN-LAIN
  //           //$other_t    = 0 + $other6 + $other7;

  //       //PENGURANGAN
  //           $d_rrak    = 0 + $this->getTotalPengurangShift($data['receiptID'],35);
  //           $d_kurset  = 0 + $this->getTotalPengurangShift($data['receiptID'],27) + $this->getTotalPengurangShift($data['receiptID'],28) + $this->getTotalPengurangShift($data['receiptID'],29) + $this->getTotalPengurangShift($data['receiptID'],30) + $this->getTotalPengurangShift($data['receiptID'],31);
  //           $d_virtual  = 0 + $this->getTotalPengurangShift($data['receiptID'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
  //           $d_other_t  = 0 + $this->getTotalPengurangShift($data['receiptID'],34)+$this->getTotalPengurangShift($data['receiptID'],36);


  //           $dataInput = array(
  //           'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
  //           'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
  //           'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL, 'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
  //           'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
  //           'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'SALES_DATE'=>$salesDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
  //           $this->db->where('CDC_SHIFT_REC_ID',$data['receiptID']);
  //           $this->db->update('cdc_trx_receipts_shift',$dataInput);

  //         if($data['batch_id'] != ''){
  //           $cek = $this->cek_rec($data['realrecid']);

  //           if($cek){
  //             if($data['no_shift'] != 'H-1' ){
  //               $stmt = 'UPDATE cdc_trx_receipts SET "ACTUAL_SALES_FLAG" = ?,"ACTUAL_SALES_AMOUNT" = ?, "ACTUAL_RRAK_AMOUNT" = ?, "ACTUAL_PAY_LESS_DEPOSITED" = ?, "ACTUAL_VOUCHER_AMOUNT" = ?, "ACTUAL_LOST_ITEM_PAYMENT" = ?, "ACTUAL_OTHERS_AMOUNT" = ?,"ACTUAL_WU_ACCOUNTABILITY" = ?,"ACTUAL_VIRTUAL_PAY_LESS" = ?,"RRAK_DEDUCTION" = ?,"LESS_DEPOSIT_DEDUCTION" = ?,"OTHERS_DEDUCTION" = ?,"SALES_DATE" = ?,"LAST_UPDATE_DATE" = ?, "STN_FLAG" = ?,"VIRTUAL_PAY_LESS_DEDUCTION" = ?, "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?';

  //               $this->db->query($stmt,array($data['flag'],0+$data['sales_amount'],$rrak,$kurset_t,0,$lost,$other_t,$wu,$virtual,$d_rrak,$d_kurset,$d_other_t,$salesDate,$last,$data['stn'],$d_virtual,$mutation_date,$bank_acc,$data['realrecid']));
  //             }
  //             else{
  //               $sales_amt2 = $this->getTotalSalesShiftEdit($data['realrecid']);
  //               //PENAMBAHAN
  //               $rrak2       = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],9) + $this->getTotalPenambahShiftEdit($data['receiptID'],10);  //RRAK
  //               //var_dump($rrak);
  //               $kurset_t2    = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],11);  //KURANG SETOR
  //               $virtual2    = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],12);  //KURANG SETOR VIRTUAL
  //               //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
  //               $lost2       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
  //               $wu2     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
  //               $other_t2     = 0 + $this->getTotalPenambahShiftEdit($data['realrecid'],13); //LAIN-LAIN
  //               //$other_t    = 0 + $other6 + $other7;

  //           //PENGURANGAN
  //               $d_rrak2    = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],35);
  //               $d_kurset2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],27) + $this->getTotalPengurangShiftEdit($data['receiptID'],28) + $this->getTotalPengurangShiftEdit($data['receiptID'],29) + $this->getTotalPengurangShiftEdit($data['realrecid'],30) + $this->getTotalPengurangShiftEdit($data['receiptID'],31);
  //               $d_virtual2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],32) + $this->getTotalPengurangShift($data['receiptID'],33);
  //               $d_other_t2  = 0 + $this->getTotalPengurangShiftEdit($data['realrecid'],34)+$this->getTotalPengurangShift($data['realrecid'],36);

  //               $stmt = 'UPDATE cdc_trx_receipts SET "ACTUAL_SALES_FLAG" = ?,"ACTUAL_SALES_AMOUNT" = ?, "ACTUAL_RRAK_AMOUNT" = ?, "ACTUAL_PAY_LESS_DEPOSITED" = ?, "ACTUAL_VOUCHER_AMOUNT" = ?, "ACTUAL_LOST_ITEM_PAYMENT" = ?, "ACTUAL_OTHERS_AMOUNT" = ?,"ACTUAL_WU_ACCOUNTABILITY" = ?,"ACTUAL_VIRTUAL_PAY_LESS" = ?,"RRAK_DEDUCTION" = ?,"LESS_DEPOSIT_DEDUCTION" = ?,"OTHERS_DEDUCTION" = ?,"SALES_DATE" = ?,"LAST_UPDATE_DATE" = ?, "STN_FLAG" = ?,"VIRTUAL_PAY_LESS_DEDUCTION" = ?, "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?';

  //               $this->db->query($stmt,array($data['flag'],0+$sales_amt2,$rrak2,$kurset_t2,0,$lost2,$other_t2,$wu2,$virtual2,$d_rrak2,$d_kurset2,$d_other_t2,$salesDate,$last,$data['stn'],$d_virtual2,$mutation_date,$bank_acc,$data['realrecid']));
  //             }
  //           }
  //         }


          
  //       }
  //   }
  // }

  function cekCIMBNIAGA($store_code,$sales_date,$stn_flag){
    date_default_timezone_set("Asia/Bangkok");
    $salesDate  = substr($sales_date, 6).'-'.substr($sales_date, 3,2).'-'.substr($sales_date, 0,2);
    $statement='SELECT count(*) as "HITUNG" FROM cdc_cimb_niaga where "STORE_CODE"=? AND "START_DATE"<=? and "END_DATE">=?';
    $cek=$this->db->query($statement,array($store_code,$salesDate,$salesDate))->row();
    return $cek->HITUNG;
  }
  function praInput($data){
    date_default_timezone_set("Asia/Bangkok");
    $salesDate  = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);
    $createBy   = $this->session->userdata('usrId');
    $createDate = date("Y-m-d H:i:s");
    $lastBy     = $this->session->userdata('usrId');
    $last       = date("Y-m-d H:i:s");
    $store = $this->getStoreID($data['store_code']);

    $this->load->model('master/Mod_cdc_master_branch');
    $branchId     = $this->session->userdata('branch_id');
    $branchCode   = $this->Mod_cdc_master_branch->getBranchCode($branchId);

    $mutation_date = NULL;
    $bank_acc = NULL;
    if ($data['mutation_date']) {
      $mutation_date = $data['mutation_date'];
      $bank_acc = $data['bank_acc'];
    }

//PENAMBAHAN
      $rrak       = 0 + $this->getTotalPenambah($data['receiptID'],9) + $this->getTotalPenambah($data['receiptID'],10);  //RRAK
      //var_dump($rrak);
      $kurset_t    = 0 + $this->getTotalPenambah($data['receiptID'],11);  //KURANG SETOR
      $virtual    = 0 + $this->getTotalPenambah($data['receiptID'],12);  //KURANG SETOR VIRTUAL
      //$kurset_t   = 0 + $kurset2 + $kurset3;  //KURANG SETOR TOTAL
      $lost       = 0; //+ $this->getTotalPenambah($data['receiptID'],4); //NBH
      $wu     = 0; //+ $this->getTotalPenambah($data['receiptID'],6); //WU
      $other_t     = 0 + $this->getTotalPenambah($data['receiptID'],13); //LAIN-LAIN
      //$other_t    = 0 + $other6 + $other7;

//PENGURANGAN
      $d_rrak    = 0 + $this->getTotalPengurang($data['receiptID'],35);
      $d_kurset  = 0 + $this->getTotalPengurang($data['receiptID'],27) + $this->getTotalPengurang($data['receiptID'],28) + $this->getTotalPengurang($data['receiptID'],29) + $this->getTotalPengurang($data['receiptID'],30) + $this->getTotalPengurang($data['receiptID'],31);
      $d_virtual  = 0 + $this->getTotalPengurang($data['receiptID'],32) + $this->getTotalPengurang($data['receiptID'],33);
      $d_other_t  = 0 + $this->getTotalPengurang($data['receiptID'],34)+$this->getTotalPengurangShift($data['receiptID'],36);

      if($data['statusxx'] == 'n'){
      	if ($data['receiptID'] != 0 || $data['receiptID'] != '') {
      		$dataInput = array('CDC_REC_ID'=>intval($data['receiptID']), 'STORE_ID'=>$store,'SALES_DATE'=>$salesDate,'STATUS'=>$data['sta_tus'],
          	'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
          	'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
          	'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL,'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
          	'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
          	'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'BRANCH_CODE'=>$branchCode, 'CREATED_BY'=>$createBy,'CREATION_DATE'=>$createDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
        	$this->db->insert('cdc_trx_receipts',$dataInput);
      	}
      }
      else if($data['statusxx'] == 'e'){
      	if ($data['receiptID'] != 0 || $data['receiptID'] != '') {
      		$dataInput = array(
	          'ACTUAL_SALES_FLAG'=>$data['flag'],'ACTUAL_SALES_AMOUNT'=>0+$data['sales_amount'],'ACTUAL_RRAK_AMOUNT'=>$rrak,
	          'ACTUAL_PAY_LESS_DEPOSITED'=>$kurset_t,'ACTUAL_VOUCHER_AMOUNT'=>0,'ACTUAL_LOST_ITEM_PAYMENT'=>$lost,
	          'ACTUAL_OTHERS_AMOUNT'=>$other_t,'ACTUAL_OTHERS_DESC'=>NULL, 'ACTUAL_WU_ACCOUNTABILITY'=>$wu,'ACTUAL_VIRTUAL_PAY_LESS'=>$virtual,
	          'RRAK_DEDUCTION'=>$d_rrak,'LESS_DEPOSIT_DEDUCTION'=>$d_kurset,
	          'OTHERS_DEDUCTION'=>$d_other_t,'OTHERS_DESC'=>NULL,'SALES_DATE'=>$salesDate,'LAST_UPDATE_BY'=>$lastBy,'LAST_UPDATE_DATE'=>$last,'STN_FLAG'=>$data['stn'],'VIRTUAL_PAY_LESS_DEDUCTION'=>$d_virtual, 'MUTATION_DATE'=>$mutation_date, 'BANK_ACCOUNT_ID'=>$bank_acc);
	        $this->db->where('CDC_REC_ID',$data['receiptID']);
	        $this->db->update('cdc_trx_receipts',$dataInput);
      	}
      }

    }


    function getStoreID($code){
      $storeID  = $this->db->query(' SELECT "STORE_ID" FROM cdc_master_toko WHERE "STORE_CODE" = \''.$code.'\' ');
      $data     = $storeID->row()->STORE_ID;
      return $data;
    }


    function cekTrxBefore($sales_date,$store_code,$stn_flag){
      date_default_timezone_set("Asia/Bangkok");
      $date = substr($sales_date, 6).'-'.substr($sales_date, 3,2).'-'.substr($sales_date, 0,2);
      $statement_cek='select count(*) as "HITUNG" from cdc_trx_receipts_shift where "STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) and "SALES_DATE"=?';
      $result=$this->db->query($statement_cek,array($store_code,$date))->row();
      $as_flag = 'Y';
      if($result->HITUNG=='0'){

        return 'TRUE';
      }else{
         $statement_receipts_shift='select "STN_FLAG" from cdc_trx_receipts_shift where "STORE_ID"=(select "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) and "SALES_DATE"=? and "ACTUAL_SALES_FLAG"=? LIMIT 1';
         $result=$this->db->query($statement_receipts_shift,array($store_code,$date,$as_flag))->row();
         if($result)
         {

            return 'TRUE';
             // if($result->STN_FLAG=='Y'){
             //    if($stn_flag=='Y'){
             //      return 'TRUE';   

             //    }else{

             //      return 'Setoran Shift Sebelumnya STN.'; 
             //    }
             // }else{
             //     if($stn_flag=='Y'){
                    
             //      return 'Setoran Shift Sebelumnya STJ.';
             //    }else{
             //       return 'TRUE';
             //    }

                
             // }
         }else{
            return 'TRUE';
         }
        
      }
    }


  

    function delPraInput($id){
      //UPDATE VOUCHER USED
      $voucher = $this->db->query(' SELECT "TRX_VOUCHER_ID","TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER" FROM cdc_trx_voucher WHERE "TRX_CDC_REC_ID"=\''.$id.'\' ');
      foreach ($voucher->result() as $row) {
        $this->db->query(' UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\' WHERE "VOUCHER_CODE"=\''.$row->TRX_VOUCHER_CODE.'\'  AND "VOUCHER_NUMBER"=\''.$row->TRX_VOUCHER_NUMBER.'\' ');
      }

      $stmt_min = 'DELETE FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ?';
      $stmt_plus = 'DELETE FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ?';
      $stmt_kur = 'UPDATE cdc_trx_kurset_lines SET "CDC_REC_ID" = NULL WHERE "CDC_REC_ID" = ?';

      $this->db->query($stmt_min, $id);
      $this->db->query($stmt_plus, $id);
      $this->db->query($stmt_kur, $id);

      $this->db->where('CDC_REC_ID',$id);
      $this->db->delete('cdc_trx_receipts');
    }

     /*function delPraInputShift($id,$id_rec){
      //UPDATE VOUCHER USED
      $voucher = $this->db->query(' SELECT "TRX_VOUCHER_SHIFT_ID","TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER" FROM cdc_trx_voucher_shift WHERE "TRX_CDC_REC_ID"=\''.$id.'\' ');
      foreach ($voucher->result() as $row) {
        $this->db->query(' UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\' WHERE "VOUCHER_CODE"=\''.$row->TRX_VOUCHER_CODE.'\'  AND "VOUCHER_NUMBER"=\''.$row->TRX_VOUCHER_NUMBER.'\' ');
      }

      $stmt_min = 'DELETE FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" = ?';
      $stmt_plus = 'DELETE FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" = ?';
      $stmt_kur = 'UPDATE cdc_trx_kurset_lines SET "CDC_REC_ID" = NULL WHERE "CDC_REC_ID" = ?';
      $stmt_peng = 'DELETE FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ?';

      $this->db->query($stmt_min, $id);
      $this->db->query($stmt_plus, $id);
      $this->db->query($stmt_kur, $id_rec);
      $this->db->query($stmt_peng,$id);

      $this->db->where('CDC_SHIFT_REC_ID',$id);
      $this->db->delete('cdc_trx_receipts_shift');
    }*/

     function delPraInputShift($id,$id_rec,$no_shift){
      //UPDATE VOUCHER USED
      $voucher = $this->db->query(' SELECT "TRX_VOUCHER_SHIFT_ID","TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER" FROM cdc_trx_voucher_shift WHERE "TRX_CDC_REC_ID"=\''.$id.'\' ');
      foreach ($voucher->result() as $row) {
        $this->db->query(' UPDATE cdc_master_detail_voucher SET "USED_FLAG" = \'Y\' WHERE "VOUCHER_CODE"=\''.$row->TRX_VOUCHER_CODE.'\'  AND "VOUCHER_NUMBER"=\''.$row->TRX_VOUCHER_NUMBER.'\' ');
      }


     

      $stmt_min = 'DELETE FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" = ?';
      $stmt_plus = 'DELETE FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" = ?';
      //if($id_rec != 0){
         $stmt_kur = 'UPDATE cdc_trx_kurset_lines SET "CDC_REC_ID" = NULL WHERE "CDC_REC_ID" = ?';
         $this->db->query($stmt_kur, $id_rec,$id);
      //}
      $stmt_peng = 'DELETE FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ?';
      //CEK DULU INPUT DENOM GA KALAU YA HAPUS
      $stmt_cek_denom = 'SELECT COUNT(*) AS "HITUNG" from cdc_trx_receipts_denom WHERE "CDC_SHIFT_REC_ID"=? ';
      $cek_denom= $this->db->query($stmt_cek_denom, $id)->row();
      if($cek_denom->HITUNG>0)
      {
              $stmt_del_denom = 'DELETE FROM cdc_trx_receipts_denom WHERE "CDC_SHIFT_REC_ID" = ?';
              $this->db->query($stmt_del_denom, $id); 


      } 
         $cek = $this->cek_data($id_rec);


      $this->db->query($stmt_min, $id); 
      $this->db->query($stmt_plus, $id);
      
      $this->db->query($stmt_peng,$id);

      $this->db->where('CDC_SHIFT_REC_ID',$id);
      $this->db->delete('cdc_trx_receipts_shift');
      //if($id_rec != 0){
      //}
      //KALAU UDAH ADA BATCH NYA DIHAPUS
      $stmt_cek_batch = 'SELECT "CDC_BATCH_ID"  from cdc_trx_receipts WHERE "CDC_REC_ID"=? ';
      $cek_batch= $this->db->query($stmt_cek_batch, $id_rec)->row();

      if($cek_batch)
      {
        $stmt_cek_receipt= 'SELECT count(*) AS CEK  from cdc_trx_receipts WHERE "CDC_BATCH_ID"=? ';
        $cek_receipt= $this->db->query($stmt_cek_receipt, $cek_batch->CDC_BATCH_ID)->row();
        if($cek_batch->CDC_BATCH_ID>0 && $cek_receipt->CEK==1 )
       {
         $stmt_del_batch = 'DELETE FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = ?';
         $this->db->query($stmt_del_batch, $cek_batch->CDC_BATCH_ID); 
         $stmt = 'DELETE FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
         $this->db->query($stmt,$id_rec);


       } 
      }
      


      
      if($cek){
          if($no_shift == 'H'){
              $stmt = 'DELETE FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
              $this->db->query($stmt,$id_rec);
          } 
          else{
            $actual_sales = $this->getTotalSalesShiftEdit($id_rec);

            if($actual_sales->ACTUAL_SALES > 0){
                $data_rec = $this->get_data_receipt_shift($id_rec);

                foreach ($data_rec as $rec) {
                   $stmt = 'UPDATE cdc_trx_receipts SET "ACTUAL_SALES_FLAG" = ?,"ACTUAL_SALES_AMOUNT" = ?, "ACTUAL_RRAK_AMOUNT" = ?, "ACTUAL_PAY_LESS_DEPOSITED" = ?, "ACTUAL_VOUCHER_AMOUNT" = ?, "ACTUAL_LOST_ITEM_PAYMENT" = ?, "ACTUAL_OTHERS_AMOUNT" = ?,"ACTUAL_WU_ACCOUNTABILITY" = ?,"ACTUAL_VIRTUAL_PAY_LESS" = ?,"RRAK_DEDUCTION" = ?,"LESS_DEPOSIT_DEDUCTION" = ?,"OTHERS_DEDUCTION" = ?,"SALES_DATE" = ?,"LAST_UPDATE_DATE" = CURRENT_TIMESTAMP, "STN_FLAG" = ?,"VIRTUAL_PAY_LESS_DEDUCTION" = ?, "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?'; 

                  $this->db->query($stmt,array($rec->ACTUAL_SALES_FLAG,$actual_sales->ACTUAL_SALES,$rec->ACTUAL_RRAK_AMOUNT,$rec->ACTUAL_PAY_LESS_DEPOSITED,$rec->ACTUAL_VOUCHER_AMOUNT,$rec->ACTUAL_LOST_ITEM_PAYMENT,$rec->ACTUAL_OTHERS_AMOUNT,$$rec->ACTUAL_WU_ACCOUNTABILITY,$rec->ACTUAL_VIRTUAL_PAY_LESS,$rec->RRAK_DEDUCTION,$rec->LESS_DEPOSIT_DEDUCTION,$rec->OTHERS_DEDUCTION,$rec->SALES_DATE,$rec->STN_FLAG,$rec->VIRTUAL_PAY_LESS_DEDUCTION,$rec->MUTATION_DATE,$rec->BANK_ACCOUNT_ID,$id_rec));
                }
               
            }
            else{
              $stmt = 'DELETE FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
              $this->db->query($stmt,$id_rec);
            }
          }
      }
    }
     function getTotalSalesShift($recId,$shift){  //REC, PLUS ID
      //$total = $this->db->query(' SELECT SUM("TRX_DET_AMOUNT") AS totalPenambah FROM cdc_trx_detail_tambah //WHERE "TRX_CDC_REC_ID"='.$recId.' AND "TRX_PLUS_ID" = '.$plusId.' ');
      $statement = 'SELECT SUM(COALESCE("TRX_PENG_AMOUNT",0)) "TRX_PENG_AMOUNT" FROM cdc_trx_detail_pengganti WHERE "TRX_CDC_REC_ID" = ? AND "NO_SHIFT" = ?';
      $result = $this->db->query($statement,array(intval($recId),strval($shift)))->result();
      return $result[0]->TRX_PENG_AMOUNT;
      /*$this->db->select_sum('TRX_DET_AMOUNT');
      $this->db->from('cdc_trx_detail_tambah');
      $this->db->where('TRX_CDC_REC_ID',$recId);
      $this->db->where('TRX_PLUS_ID',$plusId);
      $total = $this->db->get();
      return $total->row()->TRX_DET_AMOUNT;*/
    }


    function getTotalPenambah($recId,$plusId){  //REC, PLUS ID
      //$total = $this->db->query(' SELECT SUM("TRX_DET_AMOUNT") AS totalPenambah FROM cdc_trx_detail_tambah //WHERE "TRX_CDC_REC_ID"='.$recId.' AND "TRX_PLUS_ID" = '.$plusId.' ');
      $statement = 'SELECT SUM(COALESCE("TRX_DET_AMOUNT",0)) "TRX_DET_AMOUNT" FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      $result = $this->db->query($statement,array(intval($recId),intval($plusId)))->result();
      return $result[0]->TRX_DET_AMOUNT;
      /*$this->db->select_sum('TRX_DET_AMOUNT');
      $this->db->from('cdc_trx_detail_tambah');
      $this->db->where('TRX_CDC_REC_ID',$recId);
      $this->db->where('TRX_PLUS_ID',$plusId);
      $total = $this->db->get();
      return $total->row()->TRX_DET_AMOUNT;*/
    }

    function getTotalPenambahShift($recId,$plusId){  //REC, PLUS ID
      //$total = $this->db->query(' SELECT SUM("TRX_DET_AMOUNT") AS totalPenambah FROM cdc_trx_detail_tambah //WHERE "TRX_CDC_REC_ID"='.$recId.' AND "TRX_PLUS_ID" = '.$plusId.' ');
      $statement = 'SELECT SUM(COALESCE("TRX_DET_AMOUNT",0)) "TRX_DET_AMOUNT" FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ?';
      $result = $this->db->query($statement,array(intval($recId),intval($plusId)))->result();
      return $result[0]->TRX_DET_AMOUNT;
      /*$this->db->select_sum('TRX_DET_AMOUNT');
      $this->db->from('cdc_trx_detail_tambah');
      $this->db->where('TRX_CDC_REC_ID',$recId);
      $this->db->where('TRX_PLUS_ID',$plusId);
      $total = $this->db->get();
      return $total->row()->TRX_DET_AMOUNT;*/
    }

    function getTotalPengurang($recId,$minId){
      /*$this->db->select_sum('TRX_MINUS_AMOUNT');
      $this->db->from('cdc_trx_detail_minus');
      $this->db->where('TRX_CDC_REC_ID',$recId);
      $this->db->where('TRX_MINUS_ID',$minId);
      $total = $this->db->get();
      return $total->row()->TRX_MINUS_AMOUNT;*/

      $statement = 'SELECT SUM(COALESCE("TRX_MINUS_AMOUNT",0)) "TRX_MINUS_AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      $result = $this->db->query($statement,array(intval($recId),intval($minId)))->result();
      return $result[0]->TRX_MINUS_AMOUNT;
    }

    function getTotalPengurangShift($recId,$minId){
      /*$this->db->select_sum('TRX_MINUS_AMOUNT');
      $this->db->from('cdc_trx_detail_minus');
      $this->db->where('TRX_CDC_REC_ID',$recId);
      $this->db->where('TRX_MINUS_ID',$minId);
      $total = $this->db->get();
      return $total->row()->TRX_MINUS_AMOUNT;*/

      $statement = 'SELECT SUM(COALESCE("TRX_MINUS_AMOUNT",0)) "TRX_MINUS_AMOUNT" FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ?';
      $result = $this->db->query($statement,array(intval($recId),intval($minId)))->result();
      return $result[0]->TRX_MINUS_AMOUNT;
    }

    function getDataDetail($id, $is_stn){
      if ($is_stn == 'Y') {
        $data = $this->db->query(' SELECT c."CDC_REC_ID", CONCAT( TRIM(a."STORE_CODE"), EXTRACT(DAY FROM c."SALES_DATE") ) AS "SCANCODE",
        a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD-MM-YYYY\') "SALES_DATE", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG", c."MUTATION_DATE", c."BANK_ACCOUNT_ID", e."BANK_ID",
        (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
        ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
        (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
        ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER"

        FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
        INNER JOIN cdc_master_bank_account AS d ON(c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID")
        INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
        WHERE c."CDC_REC_ID"=\''.$id.'\' ORDER BY "CDC_REC_ID" DESC ');
      }else {
        $data = $this->db->query(' SELECT c."CDC_REC_ID", CONCAT( TRIM(a."STORE_CODE"), EXTRACT(DAY FROM c."SALES_DATE") ) AS "SCANCODE",
        a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD-MM-YYYY\') "SALES_DATE" , c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG", c."MUTATION_DATE",
        (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
        ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
        (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
        ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER"

        FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
        WHERE c."CDC_REC_ID"=\''.$id.'\' ORDER BY "CDC_REC_ID" DESC ');
      }

      $result=$data->row();
      return $result;
    }

    function getDataDetailShift($id, $is_stn){
      if ($is_stn == 'Y') {
        $data = $this->db->query(' SELECT c."CDC_SHIFT_REC_ID",c."CDC_REC_ID", CONCAT( TRIM(a."STORE_CODE"), EXTRACT(DAY FROM c."SALES_DATE") ) AS "SCANCODE",
        a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD-MM-YYYY\') "SALES_DATE", c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG", c."MUTATION_DATE", c."BANK_ACCOUNT_ID", e."BANK_ID",
        (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
        ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
        (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
        ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher_shift where "TRX_CDC_REC_ID" = c."CDC_SHIFT_REC_ID" ) AS "TOTAL_VOUCHER",c."NO_SHIFT",c."SHIFT_FLAG"

        FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts_shift AS c USING ("STORE_ID")
        INNER JOIN cdc_master_bank_account AS d ON(c."BANK_ACCOUNT_ID" = d."BANK_ACCOUNT_ID")
        INNER JOIN cdc_master_bank AS e ON(d."BANK_ID" = e."BANK_ID")
        WHERE c."CDC_SHIFT_REC_ID"=\''.$id.'\' ORDER BY "CDC_SHIFT_REC_ID" DESC ');
      }else {
        $data = $this->db->query(' SELECT c."CDC_SHIFT_REC_ID",c."CDC_REC_ID", CONCAT( TRIM(a."STORE_CODE"), EXTRACT(DAY FROM c."SALES_DATE") ) AS "SCANCODE",
        a."STORE_CODE",a."STORE_NAME", TO_CHAR(c."SALES_DATE", \'DD-MM-YYYY\') "SALES_DATE" , c."ACTUAL_SALES_AMOUNT", c."ACTUAL_SALES_FLAG", c."STN_FLAG", c."MUTATION_DATE",
        (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
        ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
        (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION" + c."VIRTUAL_PAY_LESS_DEDUCTION") AS "TOTAL_PENGURANGAN",
        ( select sum("TRX_VOUCHER_AMOUNT") from cdc_trx_voucher where "TRX_CDC_REC_ID" = c."CDC_REC_ID" ) AS "TOTAL_VOUCHER",c."NO_SHIFT",c."SHIFT_FLAG"

        FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts_shift AS c USING ("STORE_ID")
        WHERE c."CDC_SHIFT_REC_ID"=\''.$id.'\' ORDER BY "CDC_SHIFT_REC_ID" DESC ');
      }

      $result=$data->row();
      return $result;
    }

    function inputBatch($data){
      $session_id = md5( $this->session->userdata('usrId') );
     
      if (count($data) > 2) {
        $statement = '
        SELECT a."CDC_REC_ID",b."STORE_TYPE",b2."BANK_ID",b2."BANK_NAME",a."STN_FLAG",a."MUTATION_DATE",a."ACTUAL_SALES_AMOUNT",a."OTHERS_DESC"
        FROM cdc_trx_receipts AS a INNER JOIN cdc_master_toko AS b ON(a."STORE_ID" = b."STORE_ID")
        INNER JOIN cdc_master_bank_account AS b1 ON(
          CASE
            WHEN a."STN_FLAG" = \'N\' THEN b."BANK_ACCOUNT_ID" = b1."BANK_ACCOUNT_ID"
            ELSE a."BANK_ACCOUNT_ID" = b1."BANK_ACCOUNT_ID"
          END
        )
        INNER JOIN cdc_master_bank AS b2 ON(b1."BANK_ID" = b2."BANK_ID")
        WHERE "CDC_REC_ID" IN(
        ';
        for( $i=0; $i < count($data['receiptID'] ); $i++){
          $statement .= $data['receiptID'][$i];
          if($i < count($data['receiptID']) - 1 ){
            $statement .= ',';
          }
        }
        $statement .= ' ) GROUP BY a."CDC_REC_ID", b."STORE_TYPE", b2."BANK_ID" ,b2."BANK_NAME", a."STN_FLAG", a."MUTATION_DATE", a."OTHERS_DESC" ';

        $tempBatch = $this->db->query($statement);

        //ISI KE TABEL TEMP
        for( $i=0; $i < count( $tempBatch->result() ); $i++){
          $setTemp = $tempBatch->row($i);
          //$setTemp = array('SESSION_ID'=>$session_id);
          $setTemp->SESSION_ID = $session_id;
          $this->db->insert('temp_batch',$setTemp);
        }
      }

      $statement_kur = 'INSERT INTO temp_batch("CDC_REC_ID", "STORE_TYPE", "BANK_ID" ,"BANK_NAME", "STN_FLAG", "MUTATION_DATE", "ACTUAL_SALES_AMOUNT", "OTHERS_DESC", "SESSION_ID") SELECT CTR."CDC_REC_ID", CMT."STORE_TYPE", CMB."BANK_ID", CMB."BANK_NAME", CTR."STN_FLAG", CTR."MUTATION_DATE", CTR."ACTUAL_SALES_AMOUNT", CTR."OTHERS_DESC", \''.$session_id.'\' FROM CDC_TRX_RECEIPTS CTR, CDC_MASTER_TOKO CMT, CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CMT."BANK_ACCOUNT_ID" = CMBA."BANK_ACCOUNT_ID" AND CMBA."BANK_ID" = CMB."BANK_ID" AND CTR."CREATED_BY" = ? AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?) AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" IS NOT NULL GROUP BY CTR."CDC_REC_ID", CMT."STORE_TYPE", CMB."BANK_ID" , CMB."BANK_NAME", CTR."STN_FLAG", CTR."MUTATION_DATE", CTR."OTHERS_DESC"';

      $this->db->query($statement_kur,array(intval($this->session->userdata('usrId')),$this->session->userdata('branch_code')));

      $query  = ' SELECT "STORE_TYPE", "BANK_ID" ,"BANK_NAME", "STN_FLAG", "MUTATION_DATE", SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)) "ACTUAL_SALES_AMOUNT", "OTHERS_DESC" FROM temp_batch WHERE "SESSION_ID" = \''.$session_id.'\' GROUP BY "STORE_TYPE","BANK_ID","BANK_NAME","STN_FLAG","MUTATION_DATE","OTHERS_DESC" ';
      $result = $this->db->query($query)->result();
      $row = count($result);
      //var_dump($result[0]->STORE_TYPE);

      while ( $row > 0 ){
        /*KELOMPOKAN DATA KE BATCH */
        $row  = $row-1;
        if ($data['adaGTU'] == 1 && $result[$row]->STORE_TYPE == "R" && $result[$row]->STN_FLAG == "N") {
        	$statement_2 = 'SELECT "CDC_BANK_ID",SUM("CDC_GTU_AMOUNT") "SUM_AMOUNT" FROM cdc_trx_gtu WHERE "CDC_GTU_ID" IN (';
        	for($i=0; $i<count($data['gtuID']); $i++ ){
	            if ($i == count($data['gtuID'])-1) {
	            	$statement_2 .= $data['gtuID'][$i].') AND "CDC_BATCH_ID" IS NULL GROUP BY "CDC_BANK_ID"';
	            }
	            else{
	            	$statement_2 .= $data['gtuID'][$i].',';
	            }
            }
            $gtu_amount = $this->db->query($statement_2)->result();
            foreach ($gtu_amount as $key_amgtu) {
            	if ($key_amgtu->SUM_AMOUNT < $result[$row]->ACTUAL_SALES_AMOUNT) {
			        $query2  = ' SELECT * FROM temp_batch WHERE "SESSION_ID" = \''.$session_id.'\' AND "STORE_TYPE" = \''.$result[$row]->STORE_TYPE.'\' AND "BANK_NAME"=\''.$result[$row]->BANK_NAME.'\' AND "STN_FLAG"=\''.$result[$row]->STN_FLAG.'\'';
			        if ($result[$row]->MUTATION_DATE) {
			          $query2 .= 'AND "MUTATION_DATE" = \''.$result[$row]->MUTATION_DATE.'\'';
			        }
              if ($result[$row]->OTHERS_DESC != '') {
                $query2 .= ' AND "OTHERS_DESC" = \''.$result[$row]->OTHERS_DESC.'\'';
              }else {
                $query2 .= ' AND "OTHERS_DESC" IS NULL';
              }
			        $result2 = $this->db->query($query2)->result();
			        $row2 = count($result2);

			        /*CREATE BATCH HEADER*/
			        $branchId = $this->session->userdata('branch_id');
			        $bank_id  = $result[$row]->BANK_ID;
			        if (str_replace(' ', '', $result[$row]->OTHERS_DESC) == 'KURSET') {
                if ($result[$row]->STN_FLAG == 'N') {
                  if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-KUR";
                  }else{
                    $type  = "F-KUR";
                  }
                } else {
                  if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-KUN";
                  }else{
                    $type  = "F-KUN";
                  }
                }
              } else if (str_replace(' ', '', $result[$row]->OTHERS_DESC) == 'STL') {
                /////start edit 26-07-22
                if ($result[$row]->STN_FLAG == 'N') {
                    if($result[$row]->STORE_TYPE == "R"){
                        $type  = "R-STL-TN";
                    }else{
                        $type  = "F-STL-TN";
                    }
                } else {
                  if($result[$row]->STORE_TYPE == "R"){
                        $type  = "R-STL-TR";
                    }else{
                        $type  = "F-STL-TR";
                    }
                }
                /////end edit 26-07-22
              } else{
                if ($result[$row]->STN_FLAG == 'Y') {
                  if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-STN";
                  }else{
                    $type  = "F-STN";
                  }
                }else{
                  if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-STJ";
                  }else{
                    $type  = "F-STJ";
                  }
                }
              }
			        // if ($result[$row]->MUTATION_DATE) {
			        //   $now = $result[$row]->MUTATION_DATE;
			        // }
			        // else{

			        // 	//emma 08 06 2021 perubahan tanggal batch  
			      
				       // date_default_timezone_set("Asia/Bangkok");
				       // $now = date("Y-m-d");

				       // // $tgl=date("Y-m-d");
				       // // if($tgl>=$now){
				       // // 	  $now=$tgl;
				       // // }

			        // }
			         date_default_timezone_set("Asia/Bangkok");
               $now = date("Y-m-d");
			        $createBy = $this->session->userdata('usrId');
			        if($data['validate']){
			          $stat = 'V';
			        }else {
			          $stat = 'N';
			        }
			        $header =' INSERT INTO cdc_trx_batches("CDC_BANK_ID","CDC_BRANCH_ID","CDC_BATCH_TYPE","CDC_BATCH_DATE","CDC_BATCH_STATUS","CDC_REFF_NUM","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_DC_CODE")
			        values('.$bank_id.','.$branchId.',\''.$type.'\',\''.$now.'\',\''.$stat.'\',\''.str_replace("'", '', $this->session->userdata('no_ref')).'\','.$createBy.',\''.$now.'\','.$createBy.',current_timestamp,\''.$this->session->userdata('dc_code').'\'); ';
			        $this->db->query($header);
			        $batchId = $this->db->insert_id();

			//////// UPDATE GTU //////////////////
			        if($data['adaGTU'] == 1 && $result[$row]->STORE_TYPE == "R" && $result[$row]->STN_FLAG == "N"){
			          //echo $batchId;
			          for($i=0; $i<count($data['gtuID']); $i++ ){
			            //echo '\n GTU ID'.$data['gtuID'][$i];
			            if ($bank_id == $data['bankID'][$i]) {
			            	$this->db->query(' UPDATE cdc_trx_gtu SET "CDC_BATCH_ID"=\''.$batchId.'\' WHERE "CDC_GTU_ID"=\''.$data['gtuID'][$i].'\' ');
			            }
			          }
			        }

			        ////////////////////  JAM INPUT   //////////////////////////////
			        date_default_timezone_set("Asia/Bangkok");
			        $info = getdate();
			        $date = $info['mday'];
			        $month = $info['mon'];
			        $year = $info['year'];
			        $hour = $info['hours'];
			        $min = $info['minutes'];
			        $sec = $info['seconds'];
			        $current_date = "$date/$month/$year";
			        $current_time = "$hour:$min:$sec";
			          //$current_time = "22:50:01";
			        ///////////////////////////////////////////////////////////////
			        /*$shift = $this->Mod_cdc_master_shift->getShift();
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
			        }*/
			        //var_dump('jam input '.$current_time.'<br>SHIFT NUMBER '.$shift_number);

			        //GET SHIFT ID
			        $branch_code = $this->session->userdata('branch_code');
			        //var_dump('SHIFT_ID = '.$shift_id);

			        $stmt_batch_num = 'SELECT TO_CHAR(current_date, \'YY-\') || LPAD(CAST ( nextval(\'seq_batch_num\') AS varchar ),8,\'0\') "BATCH_NUM"';

			        $stmt_cek_batch_num = 'SELECT "CDC_BATCH_NUMBER" FROM cdc_trx_batches WHERE "CDC_BATCH_NUMBER" = ?';
			        $flag_cek = 0;
			        $batch_num = '';

			        while ($flag_cek == 0) {
			        	$res_batch_num = $this->db->query($stmt_batch_num)->result();
			        	if ($this->db->query($stmt_cek_batch_num, array(strval($res_batch_num[0]->BATCH_NUM)))->num_rows() == 0) {
			        		$batch_num = $res_batch_num[0]->BATCH_NUM;
			        		$flag_cek++;
			        	}
			        }
                      //kasus shift num kosong jadi batch num nya jadi null juga-> solusi timpain aja dlu 1
                    if($this->session->userdata('shift_num')==null)
                    {
                       
                        $shift_num=1;
                    }else{
                        $shift_num=$this->session->userdata('shift_num');

                    }
			        $updateBatchNum = ' UPDATE cdc_trx_batches SET "CDC_SHIFT_NUM"=\''.$shift_num.'\', "CDC_BATCH_NUMBER" = \''.$batch_num.'\' WHERE "CDC_BATCH_ID"=\''.$batchId.'\' ';
			        $this->db->query($updateBatchNum);

			        while ( $row2 > 0 ){
			          $row2 = $row2-1;

			          /* UPDATE RECEIPTS */
			          $queryUpdate = 'UPDATE cdc_trx_receipts SET "CDC_BATCH_ID"=\''.$batchId.'\', "STATUS"=\'S\' WHERE "CDC_REC_ID" =\''.$result2[$row2]->CDC_REC_ID.'\'';
			          $this->db->query($queryUpdate);
			        }
			        $batch_report[$row] = $batchId;
	        	}
	        	elseif($key_amgtu->SUM_AMOUNT >= $result[$row]->ACTUAL_SALES_AMOUNT){

	        	}
            }
        }else{
        	/*$row  = $row-1;*/
	        $query2  = ' SELECT * FROM temp_batch WHERE "SESSION_ID" = \''.$session_id.'\' AND "STORE_TYPE" = \''.$result[$row]->STORE_TYPE.'\' AND "BANK_NAME"=\''.$result[$row]->BANK_NAME.'\' AND "STN_FLAG"=\''.$result[$row]->STN_FLAG.'\'';
	        if ($result[$row]->MUTATION_DATE) {
	          $query2 .= 'AND "MUTATION_DATE" = \''.$result[$row]->MUTATION_DATE.'\'';
	        }
          if ($result[$row]->OTHERS_DESC != '') {
            $query2 .= ' AND "OTHERS_DESC" = \''.$result[$row]->OTHERS_DESC.'\'';
          }else {
            $query2 .= ' AND "OTHERS_DESC" IS NULL';
          }
	        $result2 = $this->db->query($query2)->result();
	        $row2 = count($result2);

	        /*CREATE BATCH HEADER*/
	        $branchId = $this->session->userdata('branch_id');
	        $bank_id  = $result[$row]->BANK_ID;
	        if (str_replace(' ', '', $result[$row]->OTHERS_DESC) == 'KURSET') {
            if ($result[$row]->STN_FLAG == 'N') {
              if($result[$row]->STORE_TYPE == "R"){
                $type  = "R-KUR";
              }else{
                $type  = "F-KUR";
              }
            } else {
              if($result[$row]->STORE_TYPE == "R"){
                $type  = "R-KUN";
              }else{
                $type  = "F-KUN";
              }
            }
          } else if (str_replace(' ', '', $result[$row]->OTHERS_DESC) == 'STL') {
            if ($result[$row]->STN_FLAG == 'N') {
                /////start edit 26-07-22
                if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-STL-TN";
                }else{
                    $type  = "F-STL-TN";
                }
            } else {
              if($result[$row]->STORE_TYPE == "R"){
                    $type  = "R-STL-TR";
                }else{
                    $type  = "F-STL-TR";
                }
            }
            /////end edit 26-07-22
          } else{
            if ($result[$row]->STN_FLAG == 'Y') {
              if($result[$row]->STORE_TYPE == "R"){
                $type  = "R-STN";
              }else{
                $type  = "F-STN";
              }
            }else{
              if($result[$row]->STORE_TYPE == "R"){
                $type  = "R-STJ";
              }else{
                $type  = "F-STJ";
              }
            }
          }
          date_default_timezone_set("Asia/Bangkok");

          //emma 08 06 2021
	        // if ($result[$row]->MUTATION_DATE) {
	        //   $now = $result[$row]->MUTATION_DATE;
	        // }
	        // else{
	        // //  $now = $this->session->userdata('shift_date');
         //     $now = date("Y-m-d");
	        // }

           $now = date("Y-m-d");
	        
	        $createBy = $this->session->userdata('usrId');
	        if($data['validate']){
	          $stat = 'V';
	        }else {
	          $stat = 'N';
	        }
	        $header =' INSERT INTO cdc_trx_batches("CDC_BANK_ID","CDC_BRANCH_ID","CDC_BATCH_TYPE","CDC_BATCH_DATE","CDC_BATCH_STATUS","CDC_REFF_NUM","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","CDC_DC_CODE")
	        values('.$bank_id.','.$branchId.',\''.$type.'\',\''.$now.'\',\''.$stat.'\',\''.str_replace("'", '', $this->session->userdata('no_ref')).'\','.$createBy.',\''.$now.'\','.$createBy.',current_timestamp,\''.$this->session->userdata('dc_code').'\'); ';
	        $this->db->query($header);
	        $batchId = $this->db->insert_id();

	//////// UPDATE GTU //////////////////
	        /*if($data['adaGTU'] == 1 && $result[$row]->STORE_TYPE == "R" && $result[$row]->STN_FLAG == "N" && $bank_id == $data['bankID']){
	          //echo $batchId;
	          for($i=0; $i<count($data['gtuID']); $i++ ){
	            //echo '\n GTU ID'.$data['gtuID'][$i];
	            $this->db->query(' UPDATE cdc_trx_gtu SET "CDC_BATCH_ID"=\''.$batchId.'\' WHERE "CDC_GTU_ID"=\''.$data['gtuID'][$i].'\' ');
	          }
	        }*/

	        ////////////////////  JAM INPUT   //////////////////////////////
	        date_default_timezone_set("Asia/Bangkok");
	        $info = getdate();
	        $date = $info['mday'];
	        $month = $info['mon'];
	        $year = $info['year'];
	        $hour = $info['hours'];
	        $min = $info['minutes'];
	        $sec = $info['seconds'];
	        $current_date = "$date/$month/$year";
	        $current_time = "$hour:$min:$sec";
	          //$current_time = "22:50:01";
	        ///////////////////////////////////////////////////////////////
	        /*$shift = $this->Mod_cdc_master_shift->getShift();
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
	        }*/
	        //var_dump('jam input '.$current_time.'<br>SHIFT NUMBER '.$shift_number);

	        //GET SHIFT ID
	        $branch_code = $this->session->userdata('branch_code');
	        //var_dump('SHIFT_ID = '.$shift_id);

          $stmt_batch_num = 'SELECT TO_CHAR(current_date, \'YY-\') || LPAD(CAST ( nextval(\'seq_batch_num\') AS varchar ),8,\'0\') "BATCH_NUM"';

          $stmt_cek_batch_num = 'SELECT "CDC_BATCH_NUMBER" FROM cdc_trx_batches WHERE "CDC_BATCH_NUMBER" = ?';
          $flag_cek = 0;
          $batch_num = '';

          while ($flag_cek == 0) {
            $res_batch_num = $this->db->query($stmt_batch_num)->result();
            if ($this->db->query($stmt_cek_batch_num, array(strval($res_batch_num[0]->BATCH_NUM)))->num_rows() == 0) {
              $batch_num = $res_batch_num[0]->BATCH_NUM;
              $flag_cek++;
            }
          }

              //kasus shift num kosong jadi batch num nya jadi null juga-> solusi timpain aja dlu 1
            if($this->session->userdata('shift_num')==null)
            {
                       
                $shift_num=1;
            }else{
                $shift_num=$this->session->userdata('shift_num');

            }
	        $updateBatchNum = ' UPDATE cdc_trx_batches SET "CDC_SHIFT_NUM"=\''.$shift_num.'\', "CDC_BATCH_NUMBER"= \''.$batch_num.'\' WHERE "CDC_BATCH_ID"=\''.$batchId.'\' ';
	        $this->db->query($updateBatchNum);

	        while ( $row2 > 0 ){
	          $row2 = $row2-1;

	          /* UPDATE RECEIPTS */
	          $queryUpdate = 'UPDATE cdc_trx_receipts SET "CDC_BATCH_ID"=\''.$batchId.'\', "STATUS"=\'S\' WHERE "CDC_REC_ID" =\''.$result2[$row2]->CDC_REC_ID.'\'';
	          $this->db->query($queryUpdate);
	        }
	        $batch_report[$row] = $batchId;
        }
      }
      /* DELETE TABEL TEMP */
      $this->db->query('DELETE FROM "temp_batch" WHERE "SESSION_ID"= \''.$session_id.'\'');
      return($batch_report);
    }

    function updateRec(){
      $receiptID = $this->input->post();
      $dataDetail = array('CDC_BATCH_ID'=>$id);
      $this->db->where('CREATED_BY',$createBy);

      $this->db->where('CDC_REC_ID',$receiptID['receiptID']);
      $this->db->update('cdc_trx_receipts',$dataDetail);
    }

    function getTotalSetor(){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT") 		  +
        SUM(CTR."ACTUAL_RRAK_AMOUNT") 		  +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT") 	  +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        )	-
        (
        0
        )	AS "TOTAL_SETOR"

        FROM cdc_trx_receipts CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CMT."STORE_TYPE" = \'R\' AND CTR."CREATED_BY"= \''.$createBy.'\' AND CTR."BRANCH_CODE"= \''.$branchCode.'\' AND CTR."STN_FLAG" = \'N\';
        ' );
      return $total->row()->TOTAL_SETOR;
    }

    function getTotalSetorShift(){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT")      +
        SUM(CTR."ACTUAL_RRAK_AMOUNT")       +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT")    +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts_shift CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CMT."STORE_TYPE" = \'R\' AND CTR."STATUS" = \'N\' AND CTR."CREATED_BY"= \''.$createBy.'\' AND CTR."BRANCH_CODE"= \''.$branchCode.'\' AND CTR."STN_FLAG" = \'N\';
        ' );
      return $total->row()->TOTAL_SETOR;
    }

    function getTotalSetorFShift(){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT")      +
        SUM(CTR."ACTUAL_RRAK_AMOUNT")       +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT")    +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts_shift CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CMT."STORE_TYPE" = \'F\' AND CTR."STATUS" = \'N\' AND CTR."CREATED_BY"= \''.$createBy.'\' AND CTR."BRANCH_CODE"= \''.$branchCode.'\' AND CTR."STN_FLAG" = \'N\';
        ' );
      return $total->row()->TOTAL_SETOR;
    }



    function getTotalSetorF(){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT")      +
        SUM(CTR."ACTUAL_RRAK_AMOUNT")       +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT")    +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CMT."STORE_TYPE" = \'F\' AND CTR."CREATED_BY"= \''.$createBy.'\' AND CTR."BRANCH_CODE"= \''.$branchCode.'\' AND CTR."STN_FLAG" = \'N\';
        ' );
      return $total->row()->TOTAL_SETOR;
    }

    function getGrandTotalShift($value='')
    {
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT")      +
        SUM(CTR."ACTUAL_RRAK_AMOUNT")       +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT")    +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts_shift CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND (CTR."CDC_BATCH_ID" IS NULL OR "CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = '.$createBy.')) AND CTR."CREATED_BY"= '.$createBy.' AND BTRIM(CTR."BRANCH_CODE")= BTRIM(\''.$branchCode.'\') AND CTR."STN_FLAG" = \'N\'
        ' );

      $statement = 'SELECT SUM(a."CDC_GTU_AMOUNT") "TOTAL"
                    FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                    WHERE a."CREATED_BY" = ? AND (a."CDC_BATCH_ID" IS NULL OR a."CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = ?))';
      $gtu = $this->db->query($statement, array($createBy,$createBy))->row();

      $gt = $total->row()->TOTAL_SETOR - $gtu->TOTAL;

      return $gt;
    }


    function getGrandTotal($value='')
    {
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM(CTR."ACTUAL_SALES_AMOUNT")      +
        SUM(CTR."ACTUAL_RRAK_AMOUNT")       +
        SUM(CTR."ACTUAL_PAY_LESS_DEPOSITED")+
        SUM(CTR."ACTUAL_VOUCHER_AMOUNT")    +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_OTHERS_AMOUNT")     +
        SUM(CTR."ACTUAL_LOST_ITEM_PAYMENT") +
        SUM(CTR."ACTUAL_WU_ACCOUNTABILITY") +
        SUM(CTR."ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts CTR, cdc_master_toko CMT
        WHERE CTR."STORE_ID" = CMT."STORE_ID" AND (CTR."CDC_BATCH_ID" IS NULL OR "CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = '.$createBy.')) AND CTR."CREATED_BY"= '.$createBy.' AND BTRIM(CTR."BRANCH_CODE")= BTRIM(\''.$branchCode.'\') AND CTR."STN_FLAG" = \'N\'
        ' );

      $statement = 'SELECT SUM(a."CDC_GTU_AMOUNT") "TOTAL"
                    FROM cdc_master_bank AS b INNER JOIN cdc_trx_gtu AS a ON (a."CDC_BANK_ID" = b."BANK_ID")
                    WHERE a."CREATED_BY" = ? AND (a."CDC_BATCH_ID" IS NULL OR a."CDC_BATCH_ID" IN (SELECT "CDC_BATCH_ID" FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = ?))';
      $gtu = $this->db->query($statement, array($createBy,$createBy))->row();

      $gt = $total->row()->TOTAL_SETOR - $gtu->TOTAL;

      return $gt;
    }

    function getTotalSetorReject($batch_id){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM("ACTUAL_SALES_AMOUNT")      +
        SUM("ACTUAL_RRAK_AMOUNT")       +
        SUM("ACTUAL_PAY_LESS_DEPOSITED")+
        SUM("ACTUAL_VOUCHER_AMOUNT")    +
        SUM("ACTUAL_LOST_ITEM_PAYMENT") +
        SUM("ACTUAL_OTHERS_AMOUNT")     +
        SUM("ACTUAL_LOST_ITEM_PAYMENT") +
        SUM("ACTUAL_WU_ACCOUNTABILITY") +
        SUM("ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts
        WHERE "CDC_BATCH_ID" = '.$batch_id.';' );
      return $total->row()->TOTAL_SETOR;
    }

     function getTotalSetorRejectShift($batch_id){
      $createBy   = $this->session->userdata('usrId');
      $branchCode   = $this->session->userdata('branch_code');
      $total = $this->db->query( '
        SELECT(
        SUM("ACTUAL_SALES_AMOUNT")      +
        SUM("ACTUAL_RRAK_AMOUNT")       +
        SUM("ACTUAL_PAY_LESS_DEPOSITED")+
        SUM("ACTUAL_VOUCHER_AMOUNT")    +
        SUM("ACTUAL_LOST_ITEM_PAYMENT") +
        SUM("ACTUAL_OTHERS_AMOUNT")     +
        SUM("ACTUAL_LOST_ITEM_PAYMENT") +
        SUM("ACTUAL_WU_ACCOUNTABILITY") +
        SUM("ACTUAL_VIRTUAL_PAY_LESS")  
        ) -
        (
        0
        ) AS "TOTAL_SETOR"

        FROM cdc_trx_receipts_shift
        WHERE "CDC_BATCH_ID" = '.$batch_id.';' );
      return $total->row()->TOTAL_SETOR;
    }


    function cekData(){
      $data   = $this->input->post();
      $store  = $this->getStoreID($data['store_code']);
      $date = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);

      $cari = $this->db->query(' SELECT COUNT("CDC_REC_ID") AS "CEK" FROM cdc_trx_receipts WHERE "STORE_ID"=\''.$store.'\' AND "SALES_DATE"= \''.$date.'\' AND "ACTUAL_SALES_FLAG" = \''.$data['sales_flag'].'\'');
      //var_dump($cari->row()->CEK);
      return $cari->row()->CEK;
    }

    function cekDataShift(){
      $data   = $this->input->post();
      $store  = $this->getStoreID($data['store_code']);
      $date = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);
      $tipe_shift=$data['tipe_shift'];      

      // kalo udah input harian dan harian shift salah satu aj harusnya ga boleh input lg
     
      if(in_array($tipe_shift, ['H-1','H','HARIAN']))
      {
         $cari = $this->db->query(' SELECT COUNT("CDC_SHIFT_REC_ID") AS "CEK" FROM cdc_trx_receipts_shift WHERE "STORE_ID"=\''.$store.'\' AND "SALES_DATE"= \''.$date.'\'  AND "ACTUAL_SALES_FLAG" = \''.$data['sales_flag'].'\'');

         if($cari->row()->CEK==0)
         {
            $cari_receipts = $this->db->query(' SELECT COUNT(*) AS "CEK" FROM cdc_trx_receipts WHERE "STORE_ID"=\''.$store.'\' AND "SALES_DATE"= \''.$date.'\'  AND "ACTUAL_SALES_FLAG" = \''.$data['sales_flag'].'\'');
            return $cari_receipts->row()->CEK;

         }else{
            return $cari->row()->CEK;
         }
        
      }else if(in_array($tipe_shift, ['S-1','S-2','S-3'])){//khusus single shift
        $cari = $this->db->query(' SELECT COUNT("CDC_SHIFT_REC_ID") AS "CEK" FROM cdc_trx_receipts_shift WHERE "STORE_ID"=\''.$store.'\' AND "SALES_DATE"= \''.$date.'\' AND "NO_SHIFT"=\''.$tipe_shift.'\' AND "ACTUAL_SALES_FLAG" = \''.$data['sales_flag'].'\'');

        if( $cari->row()->CEK==0)
        {
             $cari_receipts= $this->db->query(' SELECT COUNT(*) AS "CEK" FROM cdc_trx_receipts WHERE "STORE_ID"=\''.$store.'\' AND "SALES_DATE"= \''.$date.'\' AND "NO_SHIFT"=\''.$tipe_shift.'\' AND "ACTUAL_SALES_FLAG" = \''.$data['sales_flag'].'\'');
             return $cari_receipts->row()->CEK;
         }else{
             return $cari->row()->CEK;
         }
       
      }
     
    }
    function getReceiptsData($id){

      $createBy   = $this->session->userdata('usrId');
      $branchCode = $this->session->userdata('branch_code');

      $statement = ' SELECT c."CDC_REC_ID", c."STORE_ID", a."STORE_CODE",a."STORE_NAME", c."SALES_DATE", c."ACTUAL_SALES_AMOUNT",
      (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") AS "TOTAL_PENAMBAHAN",
      ( c."ACTUAL_SALES_AMOUNT" + (c."ACTUAL_RRAK_AMOUNT" + c."ACTUAL_PAY_LESS_DEPOSITED" + c."ACTUAL_VOUCHER_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_OTHERS_AMOUNT" + c."ACTUAL_LOST_ITEM_PAYMENT" + c."ACTUAL_WU_ACCOUNTABILITY" + c."ACTUAL_VIRTUAL_PAY_LESS") )AS "ACTUAL_AMOUNT",
      (c."RRAK_DEDUCTION" + c."LESS_DEPOSIT_DEDUCTION" + c."OTHERS_DEDUCTION") AS "TOTAL_PENGURANGAN" FROM cdc_master_toko AS a INNER JOIN cdc_trx_receipts AS c USING ("STORE_ID")
      WHERE "CREATED_BY"=\''.$createBy.'\' AND "BRANCH_CODE"= \''.$branchCode.'\' AND "STATUS"=\'N\'
      AND "CDC_REC_ID" IN ( ';
      for( $i=0; $i < count($id['receiptID'] ); $i++){
        $statement .= $id['receiptID'][$i];
        if($i < count($id['receiptID']) -1 ){
          $statement .= ',';
        }
      }
      $statement .= ' ) ORDER BY "CDC_REC_ID" ASC ';

      $receipts = $this->db->query($statement);
      //var_dump($receipts->result());
      return $receipts->result();
    }

    function getHeaderData($id){
      $result = $this->db->query(' SELECT a."CDC_BATCH_NUMBER", a."CDC_BATCH_DATE", a."CDC_BATCH_TYPE", b."USER_NAME", a."CDC_REFF_NUM", TO_CHAR(a."LAST_UPDATE_DATE",\'HH24:MI:SS\') "INPUT_TIME",
        CASE
        WHEN a."CDC_BATCH_STATUS"=\'N\' THEN \'NEW\'
        WHEN a."CDC_BATCH_STATUS"=\'V\' THEN \'VALIDATE\'
        WHEN a."CDC_BATCH_STATUS"=\'R\' THEN \'REJECT\'
        END AS "CDC_BATCH_STATUS"
        FROM cdc_trx_batches AS a INNER JOIN sys_user_2 AS b ON (a."CREATED_BY" = b."USER_ID") WHERE a."CDC_BATCH_ID"=\''.$id.'\'
        ');
      return $result->row(0);
    }

    function getTableData($id){
      $result = $this->db->query(' SELECT a."CDC_REC_ID", b."STORE_CODE", b."STORE_NAME", a."SALES_DATE", a."ACTUAL_SALES_AMOUNT", TO_CHAR(a."CREATION_DATE",\'HH24:MI:SS\') "INPUT_TIME",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" in (9,10)) AS "ACTUAL_RRAK",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" = 11) AS "ACTUAL_KURSET",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" = 12) AS "ACTUAL_VIRTUAL_KURSET",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" = 4) AS "ACTUAL_NBH",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" = 6) AS "ACTUAL_WU",
        (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND tmbh."TRX_PLUS_ID" = 13) AS "ACTUAL_LAIN",
        a."ACTUAL_SALES_AMOUNT" + (select COALESCE(sum(tmbh."TRX_DET_AMOUNT"),0) FROM cdc_trx_detail_tambah AS tmbh WHERE tmbh."TRX_CDC_REC_ID" = a."CDC_REC_ID" )  AS "ACTUAL_TOTAL",

        (select COALESCE(sum(krg."TRX_MINUS_AMOUNT"),0) FROM cdc_trx_detail_minus AS krg WHERE krg."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND krg."TRX_MINUS_ID" = 35) AS "POTONGAN_RRAK",
        (select COALESCE(sum(krg."TRX_MINUS_AMOUNT"),0) FROM cdc_trx_detail_minus AS krg WHERE krg."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND krg."TRX_MINUS_ID" in (27,28,29,30,31)) AS "POTONGAN_KURSET",
        (select COALESCE(sum(krg."TRX_MINUS_AMOUNT"),0) FROM cdc_trx_detail_minus AS krg WHERE krg."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND krg."TRX_MINUS_ID" in (32,33)) AS "POTONGAN_VIRTUAL",
        (select COALESCE(sum(krg."TRX_MINUS_AMOUNT"),0) FROM cdc_trx_detail_minus AS krg WHERE krg."TRX_CDC_REC_ID" = a."CDC_REC_ID" AND krg."TRX_MINUS_ID" in(34,36)) AS "POTONGAN_LAIN"
        FROM cdc_trx_receipts AS a INNER JOIN cdc_master_toko AS b ON(a."STORE_ID" = b."STORE_ID")
        WHERE a."CDC_BATCH_ID"=\''.$id.'\'
        GROUP BY a."CDC_REC_ID", b."STORE_CODE", b."STORE_NAME"
        ');
      return $result->result();
    }

    function getFooterData($id){
      $result = $this->db->query(' SELECT b."CDC_GTU_NUMBER", b."CDC_GTU_AMOUNT"
        FROM cdc_trx_gtu AS b INNER JOIN cdc_trx_batches AS a ON(a."CDC_BATCH_ID" = b."CDC_BATCH_ID")
        WHERE b."CDC_BATCH_ID" = \''.$id.'\'
        ');
      return $result->result();
    }

    function check_reject_batch($user_id)
    {
      $statement = 'SELECT * FROM cdc_trx_batches WHERE "CDC_BATCH_STATUS" = \'R\' AND "CREATED_BY" = ?';
      return $this->db->query($statement,$user_id)->num_rows();
    }

    function resubmit_batch($batch_id,$batch_type)
    {
      $statement = 'UPDATE cdc_trx_batches SET "CDC_BATCH_STATUS" = \'N\', "LAST_UPDATE_DATE" = current_timestamp, "CDC_BATCH_TYPE" = ? WHERE "CDC_BATCH_ID" = ?';
      $statement_2 = 'SELECT "CDC_BATCH_NUMBER" FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = ?';
      $this->db->query($statement,array($batch_type,intval($batch_id)));
      return $this->db->query($statement_2,$batch_id)->result();
    }

    function check_data_receipts($user_id)
    {
      $statement = 'SELECT * FROM cdc_trx_receipts WHERE "CREATED_BY" = ? AND "CDC_BATCH_ID" IS NULL';
      return $this->db->query($statement,$user_id)->num_rows();
    }

    function getBatchType($batch_id)
    {
      $statement = 'SELECT "CDC_BATCH_TYPE" FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = '.$batch_id.'';
      $result = $this->db->query($statement)->result();
      return $result[0]->CDC_BATCH_TYPE;
    }



    function getBatchNumber($batch_id)
    {
      $statement = 'SELECT "CDC_BATCH_NUMBER" FROM cdc_trx_batches WHERE "CDC_BATCH_ID" = '.$batch_id.'';
      $result = $this->db->query($statement)->result();
      return $result[0]->CDC_BATCH_NUMBER;
    }

    function get_cek_det_tambah($rec_id)
    {
      $statement = 'SELECT COALESCE(SUM("TRX_DET_AMOUNT"),0) "AMOUNT" FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ?';
      $result = $this->db->query($statement,intval($rec_id))->result();
      return $result[0]->AMOUNT;
    }


    function get_cek_det_tambah_shift($rec_id,$rec_id2,$rec_id3)
    {
      $statement = 'SELECT COALESCE(SUM("TRX_DET_AMOUNT"),0) "AMOUNT" FROM cdc_trx_detail_tambah_shift WHERE "TRX_CDC_REC_ID" in (?,?,?)';
      $result = $this->db->query($statement,array(intval($rec_id),intval($rec_id2),intval($rec_id3)))->result();
      return $result[0]->AMOUNT;
    }

    function get_cek_det_kurang($rec_id)
    {
      $statement = 'SELECT COALESCE(SUM("TRX_MINUS_AMOUNT"),0) "AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ?';
      $result = $this->db->query($statement,intval($rec_id))->result();
      return $result[0]->AMOUNT;
    }

    function get_cek_det_kurang_shift($rec_id,$rec_id2,$rec_id3)
    {
      $statement = 'SELECT COALESCE(SUM("TRX_MINUS_AMOUNT"),0) "AMOUNT" FROM cdc_trx_detail_minus_shift WHERE "TRX_CDC_REC_ID" in (?,?,?)';
      $result = $this->db->query($statement,array(intval($rec_id),intval($rec_id2),intval($rec_id3)))->result();
      return $result[0]->AMOUNT;
    }

    function cek_batch_type($batch_id)
    {
      $statement = 'SELECT "STORE_TYPE", "BANK_ID" ,"BANK_NAME", "STN_FLAG", "MUTATION_DATE", SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)) "ACTUAL_SALES_AMOUNT" FROM ( 
        SELECT a."CDC_REC_ID",b."STORE_TYPE",b2."BANK_ID",b2."BANK_NAME",a."STN_FLAG",a."MUTATION_DATE",a."ACTUAL_SALES_AMOUNT"
      FROM cdc_trx_receipts AS a INNER JOIN cdc_master_toko AS b ON(a."STORE_ID" = b."STORE_ID")
      INNER JOIN cdc_master_bank_account AS b1 ON(b."BANK_ACCOUNT_ID" = b1."BANK_ACCOUNT_ID")
      INNER JOIN cdc_master_bank AS b2 ON(b1."BANK_ID" = b2."BANK_ID")
      WHERE "CDC_REC_ID" IN(select "CDC_REC_ID" from cdc_trx_receipts where "CDC_BATCH_ID" = ?)
      GROUP BY a."CDC_REC_ID", b."STORE_TYPE", b2."BANK_ID" ,b2."BANK_NAME", a."STN_FLAG", a."MUTATION_DATE"
      ) as temp GROUP BY "STORE_TYPE","BANK_ID","BANK_NAME","STN_FLAG","MUTATION_DATE"';

      return $this->db->query($statement,intval($batch_id))->num_rows();
    }

    function get_batch_type($batch_id)
    {
      $statement = 'SELECT "STORE_TYPE", "BANK_ID" ,"BANK_NAME", "STN_FLAG", "MUTATION_DATE", SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)) "ACTUAL_SALES_AMOUNT" FROM ( 
        SELECT a."CDC_REC_ID",b."STORE_TYPE",b2."BANK_ID",b2."BANK_NAME",a."STN_FLAG",a."MUTATION_DATE",a."ACTUAL_SALES_AMOUNT"
      FROM cdc_trx_receipts AS a INNER JOIN cdc_master_toko AS b ON(a."STORE_ID" = b."STORE_ID")
      INNER JOIN cdc_master_bank_account AS b1 ON(b."BANK_ACCOUNT_ID" = b1."BANK_ACCOUNT_ID")
      INNER JOIN cdc_master_bank AS b2 ON(b1."BANK_ID" = b2."BANK_ID")
      WHERE "CDC_REC_ID" IN(select "CDC_REC_ID" from cdc_trx_receipts where "CDC_BATCH_ID" = ?)
      GROUP BY a."CDC_REC_ID", b."STORE_TYPE", b2."BANK_ID" ,b2."BANK_NAME", a."STN_FLAG", a."MUTATION_DATE"
      ) as temp GROUP BY "STORE_TYPE","BANK_ID","BANK_NAME","STN_FLAG","MUTATION_DATE"';

      $result = $this->db->query($statement,intval($batch_id))->result();
      if ($result[0]->STN_FLAG == 'N') {
        $type = $result[0]->STORE_TYPE.'-STJ';
      }else $type = $result[0]->STORE_TYPE.'-STN';
      return $type;
    }

    function insert_kurset_lines($ar_num,$line_id,$store_code,$date,$desc,$amount,$actual_amount,$kurset_num,$tipe,$t_flag)
    {
      $statement = 'INSERT INTO CDC_TRX_KURSET_LINES("CDC_INV_AR_NUM","FAS_LINE_ID","STORE_CODE","CDC_TRX_DATE","CDC_DESC","CDC_AMOUNT","CDC_ACTUAL_AMOUNT","CDC_TRX_HEADER_NUMBER","CREATED_DATE","LAST_UPDATE_DATE","CREATED_BY","LAST_UPDATE_BY","FAS_TYPE","TEMPLATE_FLAG") VALUES(?,?,?,?,?,?,?,?,CURRENT_DATE,CURRENT_TIMESTAMP,?,?,?,?)';
      $this->db->query($statement,array($ar_num,intval($line_id),$store_code,$date,$desc,intval($amount),intval($actual_amount),strtoupper($kurset_num),intval($this->session->userdata('usrId')),intval($this->session->userdata('usrId')),$tipe,$t_flag));
      return $this->db->affected_rows();
    }

    function cek_rec($id){
        $stmt = 'SELECT * FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
        return $this->db->query($stmt,$id)->row();
    }

    function get_kurset_lines($kurset_num, $page, $rows)
    {
      $page = ($page - 1) * $rows;
      //$page = $page > 0 ? $page : 1;
      $statement = 'SELECT "CDC_KURSET_LINE_ID", "CDC_INV_AR_NUM" "TRX_AR_NUMBER", "STORE_CODE", "CDC_TRX_DATE" "TRX_AR_DATE", "FAS_TYPE" "TRX_AR_TYPE", "CDC_DESC" "TRX_AR_DESC", "CDC_AMOUNT" "TRX_AR_AMOUNT", "CDC_ACTUAL_AMOUNT" "ACTUAL_AMOUNT" FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ? AND ("CDC_REC_ID" IS NULL OR "CDC_REC_ID" = 0)';
      $result['total'] = $this->db->query($statement,$kurset_num)->num_rows();
      $statement .= ' ORDER BY "CDC_INV_AR_NUM" ASC LIMIT '.$rows.' OFFSET '.$page.'';
      $result['rows'] = $this->db->query($statement,$kurset_num)->result();
      return $result;
    }

    function get_total_line($kurset_num)
    {
      $statement = 'SELECT "CDC_TRX_HEADER_NUMBER", SUM(COALESCE("CDC_AMOUNT",0)) "TOTAL_AMOUNT" FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ? GROUP BY "CDC_TRX_HEADER_NUMBER"';
      $result = $this->db->query($statement,$kurset_num)->result();
      return $result[0]->TOTAL_AMOUNT;
    }

    function update_actual_amount($line_id,$amount)
    {
      $statement = 'UPDATE CDC_TRX_KURSET_LINES SET "CDC_ACTUAL_AMOUNT" = ? WHERE "CDC_KURSET_LINE_ID" = ?';
      $this->db->query($statement,array(intval($amount),intval($line_id)));
      return $this->db->affected_rows();
    }

    function insert_rec_kurset($rec_id,$store_id,$amount,$trf,$acc_id,$mut_date)
    {
      if ($trf == 0) {
        $statement = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","OTHERS_DESC") VALUES(?,?,CURRENT_DATE,\'N\',\'N\',?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,\'KURSET\')';
        $this->db->query($statement,array(intval($rec_id),intval($store_id),intval($amount),$this->session->userdata('branch_code'),intval($this->session->userdata('usrId')),intval($this->session->userdata('usrId'))));
      } else {
        $statement = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","OTHERS_DESC","STN_FLAG","MUTATION_DATE","BANK_ACCOUNT_ID") VALUES(?,?,CURRENT_DATE,\'N\',\'N\',?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,\'KURSET\',\'Y\',?,?)';
        $this->db->query($statement,array(intval($rec_id),intval($store_id),intval($amount),$this->session->userdata('branch_code'),intval($this->session->userdata('usrId')),intval($this->session->userdata('usrId')),$mut_date,$acc_id));
      }
      return $this->db->affected_rows();
    }

    function insert_rec_kurset_shift($rec_id,$store_id,$amount,$trf,$acc_id,$mut_date)
    {
      if ($trf == 0) {
        $statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","OTHERS_DESC","SHIFT_FLAG","NO_SHIFT") VALUES(?,?,CURRENT_DATE,\'N\',\'N\',?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,\'KURSET\',\'N\',\'H\')';
        $this->db->query($statement,array(intval($rec_id),intval($store_id),intval($amount),$this->session->userdata('branch_code'),intval($this->session->userdata('usrId')),intval($this->session->userdata('usrId'))));
      } else {
        $statement = 'INSERT INTO cdc_trx_receipts_shift("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","OTHERS_DESC","STN_FLAG","MUTATION_DATE","BANK_ACCOUNT_ID","SHIFT_FLAG","NO_SHIFT") VALUES(?,?,CURRENT_DATE,\'N\',\'N\',?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,\'KURSET\',\'Y\',?,?,\'N\',\'H\')';
        $this->db->query($statement,array(intval($rec_id),intval($store_id),intval($amount),$this->session->userdata('branch_code'),intval($this->session->userdata('usrId')),intval($this->session->userdata('usrId')),$mut_date,$acc_id));
      }
      return $this->db->affected_rows();
    }


    function update_data_lines($kurset_num,$rec_id)
    {
      $statement = 'UPDATE CDC_TRX_KURSET_LINES SET "CDC_REC_ID" = ? WHERE "CDC_TRX_HEADER_NUMBER" = ?';
      $this->db->query($statement,array(intval($rec_id),$kurset_num));
      return $this->db->affected_rows();
    }

    function get_rec_id_for_kurset()
    {
      $statement = 'SELECT "SEQ_COUNTER"+1 "REC_ID" FROM CDC_SEQ_TABLE WHERE BTRIM("SEQ_TABLE") = \'praInputBatch\'';
      $statement2 = 'UPDATE CDC_SEQ_TABLE SET "SEQ_COUNTER" = "SEQ_COUNTER"+1 WHERE BTRIM("SEQ_TABLE") = \'praInputBatch\'';
      $result = $this->db->query($statement)->result();
      $this->db->query($statement2);
      return $result[0]->REC_ID;
    }

    function get_store_id_kurset($store_code)
    {
      $statement = 'SELECT * FROM CDC_MASTER_TOKO WHERE BTRIM("STORE_CODE") = BTRIM(?)';
      return $this->db->query($statement,array(strtoupper($store_code)))->row();
    }

    function get_store_id_ttk($ttk_num)
    {
        $statement='SELECT cmt."STORE_CODE",cmt."STORE_ID" from CDC_TRX_KURSET_LINES ctkl,CDC_MASTER_TOKO cmt where cmt."STORE_CODE"=ctkl."STORE_CODE" AND ctkl."CDC_TRX_HEADER_NUMBER"=?';
        return $this->db->query($statement,array($ttk_num))->row();
    }
    function get_all_amount_kurset($ttk_num)
    {
      $statement = 'SELECT "CDC_TRX_HEADER_NUMBER", SUM(COALESCE("CDC_ACTUAL_AMOUNT",0)) "AMOUNT" FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ? AND "CDC_REC_ID" IS NULL GROUP BY "CDC_TRX_HEADER_NUMBER"';
      $result = $this->db->query($statement,array($ttk_num))->result();
      return $result[0]->AMOUNT;
    }

    function cek_header_rec_kurset($kurset_num)
    {
      $statement = 'SELECT * FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ? AND "CDC_REC_ID" IS NULL';
      return $this->db->query($statement,$kurset_num)->num_rows();
    }

    function cek_header_kurset($kurset_num)
    {
      $statement = 'SELECT * FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ?';
      return $this->db->query($statement,$kurset_num)->num_rows();
    }

    public function get_lines_amount_kurset($ttk_num)
    {
      $statement = 'SELECT "CDC_KURSET_LINE_ID", "CDC_INV_AR_NUM" "TRX_AR_NUMBER", "STORE_CODE", "CDC_TRX_DATE" "TRX_AR_DATE", "FAS_TYPE" "TRX_AR_TYPE", "CDC_DESC" "TRX_AR_DESC", "CDC_AMOUNT" "TRX_AR_AMOUNT", "CDC_ACTUAL_AMOUNT" "ACTUAL_AMOUNT" FROM CDC_TRX_KURSET_LINES WHERE "CDC_TRX_HEADER_NUMBER" = ? AND ("CDC_REC_ID" IS NULL OR "CDC_REC_ID" = 0) ORDER BY "CDC_INV_AR_NUM" ASC';
      return $this->db->query($statement, $ttk_num)->result();
    }

    public function update_actual_amount_lines($line_id, $amount)
    {
      $statement = 'UPDATE CDC_TRX_KURSET_LINES SET "CDC_ACTUAL_AMOUNT" = ? WHERE "CDC_KURSET_LINE_ID" = ?';
      $this->db->query($statement, array($amount, $line_id));
      return $this->db->affected_rows();
    }

    public function get_batch_num_sales($data)
    {
      $statement = 'SELECT COALESCE(CTB."CDC_BATCH_NUMBER", \'N\') "CDC_BATCH_NUMBER" FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTR."STORE_ID" = ? AND CTR."SALES_DATE" = ? AND CTR."ACTUAL_SALES_FLAG" = ? AND CTR."STN_FLAG" = ? AND CTR."OTHERS_DESC" IS NULL';

      $store  = $this->getStoreID($data['store_code']);
      $date = substr($data['sales_date'], 6).'-'.substr($data['sales_date'], 3,2).'-'.substr($data['sales_date'], 0,2);

      $result = $this->db->query($statement, array($store, $date, $data['sales_flag'], $data['stn_flag']))->result();

      return $result;

    }
////// START EDIT 25-07-22
    public function get_store_id_ktr($toko)
    {
      $statement = 'SELECT * FROM cdc_master_toko WHERE "STORE_CODE" = ? AND "BRANCH_ID" = ?';
      $result = $this->db->query($statement, array($toko,$this->session->userdata('branch_id')))->row();
      return $result->STORE_ID;
    }

    public function get_master_stl()
    {
      $statement = 'SELECT "CDC_MASTER_STL_ID", "DESCRIPTION" FROM CDC_MASTER_STL WHERE "ACTIVE_FLAG" = \'Y\' ORDER BY "CDC_MASTER_STL_ID"';
      return $this->db->query($statement)->result();
    }

    public function save_data_stl($data)
    {
      if ($data['stl_id'] != '') {
       
        $statement = 'UPDATE cdc_trx_stl SET "CDC_MASTER_STL_ID" = ?, "DESCRIPTION" = ?,"STORE_CODE"=?, "TRX_DATE" = ?, "AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP WHERE "CDC_STL_ID" = ? AND "CDC_REC_ID" = ?';
        $this->db->query($statement, array($data['category'], $data['desc'],$data['store'], $data['date'], $data['amount'], $this->session->userdata('usrId'), $data['stl_id'], $data['rec_id'])); 
        
      } else {
        if($data['store'] != 'KTR')
        {
            $statement = 'INSERT INTO cdc_trx_stl("CDC_MASTER_STL_ID", "CDC_REC_ID", "STORE_CODE","DESCRIPTION", "TRX_DATE", "AMOUNT", "CREATED_BY", "CREATION_DATE", "LAST_UPDATE_BY") VALUES(?,?,?,?,?,?,?,CURRENT_DATE,?)';
            $this->db->query($statement, array($data['category'], $data['rec_id'], $data['store'],$data['desc'], $data['date'], $data['amount'], $this->session->userdata('usrId'), $this->session->userdata('usrId')));
        }
        else
        {
            $statement = 'INSERT INTO cdc_trx_stl("CDC_MASTER_STL_ID", "CDC_REC_ID", "DESCRIPTION", "TRX_DATE", "AMOUNT", "CREATED_BY", "CREATION_DATE", "LAST_UPDATE_BY") VALUES(?,?,?,?,?,?,CURRENT_DATE,?)';
            $this->db->query($statement, array($data['category'], $data['rec_id'], $data['desc'], $data['date'], $data['amount'], $this->session->userdata('usrId'), $this->session->userdata('usrId')));
        }
      }
      return $this->db->affected_rows();
    }



    public function save_data_receipt($data)
    {
      if ($data['stl_id'] != '') {
        if ($data['stn_flag'] == 0) {
          $statement = 'UPDATE cdc_trx_receipts SET "STORE_ID"=?, "SALES_DATE" = ?, "ACTUAL_SALES_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP, "STN_FLAG" = \'N\', "MUTATION_DATE" = NULL, "BANK_ACCOUNT_ID" = NULL WHERE "CDC_REC_ID" = ?';
          $this->db->query($statement, array($data['store_id'], $data['date'], $data['amount'], $this->session->userdata('usrId'), $data['rec_id']));
        } else {
          $statement = 'UPDATE cdc_trx_receipts SET "STORE_ID"=?, "SALES_DATE" = ?, "ACTUAL_SALES_AMOUNT" = ?, "LAST_UPDATE_BY" = ?, "LAST_UPDATE_DATE" = CURRENT_TIMESTAMP, "STN_FLAG" = \'Y\', "MUTATION_DATE" = ?, "BANK_ACCOUNT_ID" = ? WHERE "CDC_REC_ID" = ?';
          $this->db->query($statement, array($data['store_id'], $data['date'], $data['amount'], $this->session->userdata('usrId'), $data['mutation_date'], $data['acc_id'], $data['rec_id']));
        }
      } else {
        if ($data['stn_flag'] == 0) {
        $statement = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID", "STORE_ID", "SALES_DATE", "STATUS", "ACTUAL_SALES_FLAG", "ACTUAL_SALES_AMOUNT", "OTHERS_DESC", "BRANCH_CODE", "CREATED_BY", "CREATION_DATE", "LAST_UPDATE_BY", "LAST_UPDATE_DATE") VALUES(?,?,?,\'N\',\'N\',?,\'STL\',?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP)';
        $this->db->query($statement, array($data['rec_id'], $data['store_id'], $data['date'], $data['amount'], $this->session->userdata('branch_code'), $this->session->userdata('usrId'), $this->session->userdata('usrId')));
        } else {
          $statement = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID", "STORE_ID", "SALES_DATE", "STATUS", "ACTUAL_SALES_FLAG", "ACTUAL_SALES_AMOUNT", "OTHERS_DESC", "BRANCH_CODE", "CREATED_BY", "CREATION_DATE", "LAST_UPDATE_BY", "LAST_UPDATE_DATE", "STN_FLAG", "MUTATION_DATE", "BANK_ACCOUNT_ID") VALUES(?,?,?,\'N\',\'N\',?,\'STL\',?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,\'Y\',?,?)';
          $this->db->query($statement, array($data['rec_id'], $data['store_id'], $data['date'], $data['amount'], $this->session->userdata('branch_code'), $this->session->userdata('usrId'), $this->session->userdata('usrId'), $data['mutation_date'], $data['acc_id']));
        }
      }
      return $this->db->affected_rows();
    }

    // END EDIT 25-07-22

    public function get_data_stl_shift($page, $rows)
    {
      $page = ($page - 1) * $rows;
      $statement = 'SELECT STL."CDC_STL_ID", STL."CDC_MASTER_STL_ID", STL."CDC_REC_ID", STL."DESCRIPTION", TO_CHAR(STL."TRX_DATE", \'DD-Mon-YYYY\') "TRX_DATE_FORMAT", STL."TRX_DATE", STL."AMOUNT", CMS."DESCRIPTION" "CATEGORY", CTR."STN_FLAG", CTR."MUTATION_DATE", CTR."BANK_ACCOUNT_ID", (SELECT "BANK_ID" FROM CDC_MASTER_BANK_ACCOUNT WHERE "BANK_ACCOUNT_ID" = CTR."BANK_ACCOUNT_ID") "BANK_ID" FROM CDC_TRX_STL STL, CDC_MASTER_STL CMS, CDC_TRX_RECEIPTS_SHIFT CTR WHERE STL."CDC_MASTER_STL_ID" = CMS."CDC_MASTER_STL_ID" AND STL."CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" = \'STL\' AND STL."CREATED_BY" = ? AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?)';
      $result['total'] = $this->db->query($statement, array($this->session->userdata('usrId'), $this->session->userdata('branch_code')))->num_rows();
      $statement .= ' LIMIT '.$rows.' OFFSET '.$page;
      $result['rows'] = $this->db->query($statement, array($this->session->userdata('usrId'), $this->session->userdata('branch_code')))->result();
      return $result;
    }

    public function get_data_stl($page, $rows)
    {
      $page = ($page - 1) * $rows;
      $statement = 'SELECT STL."CDC_STL_ID", STL."CDC_MASTER_STL_ID", STL."CDC_REC_ID", STL."DESCRIPTION", 
      COALESCE((SELECT "STORE_CODE"||\'-\'||"STORE_NAME" FROM cdc_master_toko WHERE BTRIM("STORE_CODE")=COALESCE(BTRIM(STL."STORE_CODE"),\'KTR\') AND "BRANCH_ID" = (SELECT "BRANCH_ID" FROM cdc_master_branch where "BRANCH_CODE"=?)),NULL) "STORE_CODE",
      TO_CHAR(STL."TRX_DATE", \'DD-Mon-YYYY\') "TRX_DATE_FORMAT", STL."TRX_DATE", STL."AMOUNT", CMS."DESCRIPTION" "CATEGORY", CTR."STN_FLAG", CTR."MUTATION_DATE", CTR."BANK_ACCOUNT_ID", (SELECT "BANK_ID" FROM CDC_MASTER_BANK_ACCOUNT WHERE "BANK_ACCOUNT_ID" = CTR."BANK_ACCOUNT_ID") "BANK_ID" FROM CDC_TRX_STL STL, CDC_MASTER_STL CMS, CDC_TRX_RECEIPTS CTR WHERE STL."CDC_MASTER_STL_ID" = CMS."CDC_MASTER_STL_ID" AND STL."CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."CDC_BATCH_ID" IS NULL AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" = \'STL\' AND STL."CREATED_BY" = ? AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?)';
      $result['total'] = $this->db->query($statement, array($this->session->userdata('branch_code'),$this->session->userdata('usrId'), $this->session->userdata('branch_code')))->num_rows();
      $statement .= ' LIMIT '.$rows.' OFFSET '.$page;
      $result['rows'] = $this->db->query($statement, array($this->session->userdata('branch_code'),$this->session->userdata('usrId'), $this->session->userdata('branch_code')))->result();
      return $result;
    }

    public function delete_stl_receipt($data)
    {
      $statement = 'DELETE FROM CDC_TRX_STL WHERE "CDC_STL_ID" = ? AND "CDC_REC_ID" = ?';
      $statement_2 = 'DELETE FROM CDC_TRX_RECEIPTS WHERE "CDC_REC_ID" = ?';
      $this->db->query($statement, array($data['stl_id'], $data['rec_id']));
      if ($this->db->affected_rows() > 0) {
        $this->db->query($statement_2, array($data['rec_id']));
      }
      return $this->db->affected_rows();
    }

    public function getTotalDataSelect($id){
      $stmt = 'SELECT COUNT("CDC_REC_ID") as "COUNT" from cdc_trx_receipts_shift WHERE "CDC_REC_ID" = ?';

      $result = $this->db->query($stmt,intval($id))->result();

      return $result[0]->COUNT;
    }

    public function get_data_receipt_shift($id){
      $stmt = 'SELECT "CDC_REC_ID","STORE_ID","SALES_DATE",
      "STATUS","ACTUAL_SALES_FLAG",SUM("ACTUAL_SALES_AMOUNT") as "ACTUAL_SALES_AMOUNT",
      SUM("ACTUAL_RRAK_AMOUNT") as "ACTUAL_RRAK_AMOUNT",SUM("ACTUAL_PAY_LESS_DEPOSITED") as "ACTUAL_PAY_LESS_DEPOSITED"
      ,SUM("ACTUAL_VOUCHER_AMOUNT") as "ACTUAL_VOUCHER_AMOUNT",
      SUM("ACTUAL_OTHERS_AMOUNT") as "ACTUAL_OTHERS_AMOUNT","ACTUAL_OTHERS_DESC",SUM("RRAK_DEDUCTION") as "RRAK_DEDUCTION",
      SUM("LESS_DEPOSIT_DEDUCTION") as "LESS_DEPOSIT_DEDUCTION" ,
      SUM("OTHERS_DEDUCTION") as "OTHERS_DEDUCTION","OTHERS_DESC","BRANCH_CODE","CREATED_BY",
      SUM("ACTUAL_LOST_ITEM_PAYMENT") as "ACTUAL_LOST_ITEM_PAYMENT",SUM("ACTUAL_WU_ACCOUNTABILITY") as "ACTUAL_WU_ACCOUNTABILITY",
      SUM("ACTUAL_VIRTUAL_PAY_LESS") as "ACTUAL_VIRTUAL_PAY_LESS",
      "TRANSFER_FLAG",SUM("VIRTUAL_PAY_LESS_DEDUCTION") as "VIRTUAL_PAY_LESS_DEDUCTION","STN_FLAG","MUTATION_DATE",
      "BANK_ACCOUNT_ID","CDC_BATCH_ID","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","CREATED_BY","START_INPUT_TIME","NO_SHIFT" from cdc_trx_receipts_shift WHERE "CDC_REC_ID" =\''.$id.'\'
      GROUP BY "CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_OTHERS_DESC","OTHERS_DESC",
      "BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","CREATED_BY","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE",
      "BANK_ACCOUNT_ID","CDC_BATCH_ID","NO_SHIFT","START_INPUT_TIME"';

      return $this->db->query($stmt)->result();

    }

    public function get_data_tambah_shift($id){
        $stmt = 'SELECT cs."TRX_PLUS_ID",cs."TRX_DETAIL_DATE",
        SUBSTRING(cs."TRX_DETAIL_DESC",1,CASE WHEN cs."TRX_DETAIL_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_DETAIL_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_DETAIL_DESC") END) as "DESC",SUM(cs."TRX_DET_AMOUNT") as "AMT",cs."CREATED_BY",cs."LAST_UPDATE_BY",cs."NO_SHIFT" from cdc_trx_detail_tambah_shift cs WHERE cs."CDC_REC_ID" = \''.$id.'\' GROUP BY cs."TRX_PLUS_ID",cs."TRX_DETAIL_DATE",SUBSTRING(cs."TRX_DETAIL_DESC",1,CASE WHEN cs."TRX_DETAIL_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_DETAIL_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_DETAIL_DESC") END),cs."CREATED_BY",cs."LAST_UPDATE_BY",cs."NO_SHIFT"';

        return $this->db->query($stmt)->result();
    }

    public function get_data_minus_shift($id){
        $stmt = 'SELECT cs."TRX_MINUS_ID",cs."TRX_MINUS_DATE",SUBSTRING(cs."TRX_MINUS_DESC",1,CASE WHEN cs."TRX_MINUS_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_MINUS_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_MINUS_DESC") END) as "DESC",SUM(cs."TRX_MINUS_AMOUNT") as "AMT",cs."CREATED_BY",cs."LAST_UPDATE_BY",cs."NO_SHIFT" from cdc_trx_detail_minus_shift cs WHERE cs."CDC_REC_ID" = \''.$id.'\' GROUP BY cs."TRX_MINUS_ID",cs."TRX_MINUS_DATE",SUBSTRING(cs."TRX_MINUS_DESC",1,CASE WHEN cs."TRX_MINUS_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_MINUS_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_MINUS_DESC") END),cs."CREATED_BY",cs."LAST_UPDATE_BY",cs."NO_SHIFT"';

        return $this->db->query($stmt)->result();
    }

   /* public function get_data_tambah_shift($id){
        $stmt = 'SELECT cs."TRX_PLUS_ID",cs."TRX_DETAIL_DATE",
        SUBSTRING(cs."TRX_DETAIL_DESC",1,CASE WHEN cs."TRX_DETAIL_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_DETAIL_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_DETAIL_DESC") END) as "DESC",SUM(cs."TRX_DET_AMOUNT") as "AMT",cs."CREATED_BY",cs."CREATION_DATE",cs."LAST_UPDATE_BY",cs."LAST_UPDATE_DATE" from cdc_trx_detail_tambah_shift cs WHERE cs."TRX_CDC_REC_ID" = \''.$id.'\' GROUP BY cs."TRX_PLUS_ID",cs."TRX_DETAIL_DATE",SUBSTRING(cs."TRX_DETAIL_DESC",1,CASE WHEN cs."TRX_DETAIL_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_DETAIL_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_DETAIL_DESC") END),cs."CREATED_BY",cs."CREATION_DATE",cs."LAST_UPDATE_BY",cs."LAST_UPDATE_DATE"';

        return $this->db->query($stmt)->result();
    }

    public function get_data_minus_shift($id){
        $stmt = 'SELECT cs."TRX_MINUS_ID",cs."TRX_MINUS_DATE",SUBSTRING(cs."TRX_MINUS_DESC",1,CASE WHEN cs."TRX_MINUS_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_MINUS_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_MINUS_DESC") END) as "DESC",SUM(cs."TRX_MINUS_AMOUNT") as "AMT",cs."CREATED_BY",cs."CREATION_DATE",cs."LAST_UPDATE_BY",cs."LAST_UPDATE_DATE" from cdc_trx_detail_minus_shift cs WHERE cs."TRX_CDC_REC_ID" = \''.$id.'\' GROUP BY cs."TRX_MINUS_ID",cs."TRX_MINUS_DATE",SUBSTRING(cs."TRX_MINUS_DESC",1,CASE WHEN cs."TRX_MINUS_DESC" LIKE \'%Shift%\' THEN STRPOS(cs."TRX_MINUS_DESC",\'Shift\')-2 ELSE LENGTH(cs."TRX_MINUS_DESC") END),cs."CREATED_BY",cs."CREATION_DATE",cs."LAST_UPDATE_BY",cs."LAST_UPDATE_DATE"';

        return $this->db->query($stmt)->result();
    }*/

    public function get_data_voucher_shift($id){
        $stmt = 'SELECT "TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER","TRX_VOUCHER_DATE","TRX_VOUCHER_DESC","TRX_VOUCHER_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","NO_SHIFT" from cdc_trx_voucher_shift where "TRX_CDC_REC_ID" = \''.$id.'\'';
        return $this->db->query($stmt)->result(); 
    }

    public function cek_data($id){
      $stmt = 'SELECT * FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
      return $this->db->query($stmt,$id)->row();
    }

    public function cek_data_others($id){
      $stmt = 'SELECT COUNT(*) as "COUNT" FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
      return $this->db->query($stmt,$id)->row();
    }

    public function cek_data_tmb($id,$trxid,$tgl){
      $stmt = 'SELECT * FROM cdc_trx_detail_tambah WHERE "TRX_CDC_REC_ID" = ? AND "TRX_PLUS_ID" = ? AND "TRX_DETAIL_DATE" = ?';
      return $this->db->query($stmt,array($id,$trxid,$tgl))->row();
    }

    public function cek_data_minus($id,$trxid,$tgl){
      $stmt = 'SELECT * FROM cdc_trx_detail_minus WHERE "TRX_CDC_REC_ID" = ? AND "TRX_MINUS_ID" = ? AND "TRX_MINUS_DATE" = ?';
      return $this->db->query($stmt,array($id,$trxid,$tgl))->result();
    }

    public function cek_data_voucher($id,$vcode,$vnum,$tgl){
      $stmt = 'SELECT * FROM cdc_trx_voucher WHERE "TRX_CDC_REC_ID" = ? AND "TRX_VOUCHER_CODE" = ? and "TRX_VOUCHER_NUMBER" = ? and "TRX_VOUCHER_DATE" = ?';
      return $this->db->query($stmt,array($id,$vcode,$vnum,$tgl))->row();
    }

    public function get_data_others(){
     $stmt = 'SELECT * FROM CDC_TRX_RECEIPTS_SHIFT CTR, CDC_MASTER_TOKO CMT, CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB 
	 WHERE CTR."STORE_ID" = CMT."STORE_ID" AND (CASE WHEN CTR."STN_FLAG" = \'Y\' THEN CTR."BANK_ACCOUNT_ID" else CMT."BANK_ACCOUNT_ID" end) = CMBA."BANK_ACCOUNT_ID" 
	 AND CMBA."BANK_ID" = CMB."BANK_ID" AND CTR."CREATED_BY" = ? 
	 AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?) 
	 AND CTR."STATUS" = \'N\' 
	 AND CTR."OTHERS_DESC" IS NOT NULL ';

     return $this->db->query($stmt,array(intval($this->session->userdata('usrId')),$this->session->userdata('branch_code')))->result();
    }

     public function get_data_others_rec(){
     $stmt = 'SELECT * FROM CDC_TRX_RECEIPTS_SHIFT CTR, CDC_MASTER_TOKO CMT, CDC_MASTER_BANK_ACCOUNT CMBA, CDC_MASTER_BANK CMB 
	 WHERE CTR."STORE_ID" = CMT."STORE_ID" AND (CASE WHEN CTR."STN_FLAG" = \'Y\' THEN CTR."BANK_ACCOUNT_ID" else CMT."BANK_ACCOUNT_ID" end) = CMBA."BANK_ACCOUNT_ID" 
	 AND CMBA."BANK_ID" = CMB."BANK_ID" 
	 AND CTR."CREATED_BY" = ? 
	 AND BTRIM(CTR."BRANCH_CODE") = BTRIM(?) 
	 AND CTR."STATUS" = \'N\' AND CTR."OTHERS_DESC" IS NOT NULL ';

      return $this->db->query($stmt,array(intval($this->session->userdata('usrId')),$this->session->userdata('branch_code')))->result();
    }


    public function Pindah_Data_Shift($rec_id){

      for($i = 0; $i < count($rec_id); $i++){

        $id_tmb = 0;
        $id_minus = 0;
        $id_vou = 0;
        $id_rec = 0;

        $exp = explode('-',$rec_id[$i]);
        $data_rec = $this->get_data_receipt_shift($exp[1]);

        if($data_rec){

          $data_tmb = $this->get_data_tambah_shift($exp[1]);

          if($data_tmb){
            foreach ($data_tmb as $tmb) {
              $cek_tmb = $this->cek_data_tmb($exp[1],$tmb->TRX_PLUS_ID,$tmb->TRX_DETAIL_DATE);

              if(!$cek_tmb){
                $id_tmb = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_tambah');

                /*$stmt_tambah = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)';

                  $this->db->query($stmt_tambah,array($id_tmb,$exp[1],$tmb->TRX_PLUS_ID,$tmb->TRX_DETAIL_DATE,$tmb->DESC,$tmb->AMT,$tmb->CREATED_BY,$tmb->CREATION_DATE,$tmb->LAST_UPDATE_BY));*/

                   $stmt_tambah = 'INSERT INTO cdc_trx_detail_tambah("TRX_DETAIL_ID","TRX_CDC_REC_ID","TRX_PLUS_ID","TRX_DETAIL_DATE","TRX_DETAIL_DESC","TRX_DET_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","NO_SHIFT") VALUES (?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?)';

                  $this->db->query($stmt_tambah,array($id_tmb,$exp[1],$tmb->TRX_PLUS_ID,$tmb->TRX_DETAIL_DATE,$tmb->DESC,$tmb->AMT,$tmb->CREATED_BY,$tmb->LAST_UPDATE_BY,$tmb->NO_SHIFT));
              }
            }
          }

          $data_minus = $this->get_data_minus_shift($exp[1]);

          if($data_minus){
            foreach ($data_minus as $minus) {
              $cek_minus = $this->cek_data_minus($exp[1],$minus->TRX_MINUS_ID,$minus->TRX_MINUS_DATE);
             
              //if($cek_minus){
                $id_minus = $this->Mod_cdc_seq_table->getID('cdc_trx_detail_minus');

                /*$stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE") VALUES (?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)';

                $this->db->query($stmt_minus,array($id_minus,$exp[1],$minus->TRX_MINUS_ID,$minus->TRX_MINUS_DATE,$minus->DESC,$minus->AMT,$minus->CREATED_BY,$minus->CREATION_DATE,$minus->LAST_UPDATE_BY));*/
              
                $stmt_minus = 'INSERT INTO cdc_trx_detail_minus("TRX_DETAIL_MINUS_ID","TRX_CDC_REC_ID","TRX_MINUS_ID","TRX_MINUS_DATE","TRX_MINUS_DESC","TRX_MINUS_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","NO_SHIFT") VALUES (?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?)';

                $this->db->query($stmt_minus,array($id_minus,$exp[1],$minus->TRX_MINUS_ID,$minus->TRX_MINUS_DATE,$minus->DESC,$minus->AMT,$minus->CREATED_BY,$minus->LAST_UPDATE_BY,$minus->NO_SHIFT));
            //  }
            }
          }

          $data_voucher = $this->get_data_voucher_shift($exp[0]);

          if($data_voucher){
            foreach($data_voucher as $vou){
              $cek_vou = $this->cek_data_voucher($exp[1],$vou->TRX_VOUCHER_CODE,$vou->TRX_VOUCHER_NUMBER,$vou->TRX_VOUCHER_DATE);

              if(!$cek_vou){
                  $id_vou = $this->Mod_cdc_seq_table->getID('cdc_trx_voucher');

                 $stmt_voucher = 'INSERT INTO cdc_trx_voucher("TRX_VOUCHER_ID","TRX_CDC_REC_ID","TRX_VOUCHER_CODE","TRX_VOUCHER_NUMBER","TRX_VOUCHER_DATE","TRX_VOUCHER_DESC","TRX_VOUCHER_AMOUNT","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","NO_SHIFT") VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?)';

                 $this->db->query($stmt_voucher,array($id_vou,$exp[1],$vou->TRX_VOUCHER_CODE,$vou->TRX_VOUCHER_NUMBER,$vou->TRX_VOUCHER_DATE,$vou->TRX_VOUCHER_DESC,$vou->TRX_VOUCHER_AMOUNT,$vou->CREATED_BY,$vou->CREATION_DATE,$vou->LAST_UPDATE_BY,$vou->NO_SHIFT));     
              } 
            }
          }

          
          foreach ($data_rec as $rec) {
            $cek = $this->cek_data($exp[1]);

            if(!$cek){
               $stmt_rec = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","ACTUAL_RRAK_AMOUNT","ACTUAL_PAY_LESS_DEPOSITED","ACTUAL_VOUCHER_AMOUNT","ACTUAL_OTHERS_AMOUNT","ACTUAL_OTHERS_DESC","RRAK_DEDUCTION","LESS_DEPOSIT_DEDUCTION","OTHERS_DEDUCTION","OTHERS_DESC","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","ACTUAL_LOST_ITEM_PAYMENT","ACTUAL_WU_ACCOUNTABILITY","ACTUAL_VIRTUAL_PAY_LESS","TRANSFER_FLAG","VIRTUAL_PAY_LESS_DEDUCTION","STN_FLAG","MUTATION_DATE","BANK_ACCOUNT_ID","NO_SHIFT","START_INPUT_TIME") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?,?,?,?,?,?,?,?,?)';

               //$this->db->query($stmt_rec);

               $this->db->query($stmt_rec,array($exp[1],$rec->STORE_ID,$rec->SALES_DATE,$rec->STATUS,$rec->ACTUAL_SALES_FLAG,$rec->ACTUAL_SALES_AMOUNT,$rec->ACTUAL_RRAK_AMOUNT,$rec->ACTUAL_PAY_LESS_DEPOSITED,$rec->ACTUAL_VOUCHER_AMOUNT,$rec->ACTUAL_OTHERS_AMOUNT,$rec->ACTUAL_OTHERS_DESC,$rec->RRAK_DEDUCTION,$rec->LESS_DEPOSIT_DEDUCTION,$rec->OTHERS_DEDUCTION,$rec->OTHERS_DESC,$rec->BRANCH_CODE,$rec->CREATED_BY,$rec->CREATION_DATE,$rec->LAST_UPDATE_BY,$rec->ACTUAL_LOST_ITEM_PAYMENT,$rec->ACTUAL_WU_ACCOUNTABILITY,$rec->ACTUAL_VIRTUAL_PAY_LESS,$rec->TRANSFER_FLAG,$rec->VIRTUAL_PAY_LESS_DEDUCTION,$rec->STN_FLAG,$rec->MUTATION_DATE,$rec->BANK_ACCOUNT_ID,$rec->NO_SHIFT,$rec->START_INPUT_TIME));
           }
          }    
         
        }
      }


          $data_others = $this->get_data_others();

          foreach ($data_others as $others) {
            $cek_others = $this->cek_data_others($others->CDC_REC_ID);

            //print_r($cek_others);
            if($cek_others->COUNT == 0){
                $stmt_others = 'INSERT INTO cdc_trx_receipts("CDC_REC_ID","STORE_ID","SALES_DATE","STATUS","ACTUAL_SALES_FLAG","ACTUAL_SALES_AMOUNT","OTHERS_DESC","BRANCH_CODE","CREATED_BY","CREATION_DATE","LAST_UPDATE_BY","LAST_UPDATE_DATE","TRANSFER_FLAG","STN_FLAG","MUTATION_DATE","BANK_ACCOUNT_ID","NO_SHIFT","START_INPUT_TIME") VALUES (?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?,?,?,?,?)';

                 $this->db->query($stmt_others,array($others->CDC_REC_ID,$others->STORE_ID,$others->SALES_DATE,$others->STATUS,$others->ACTUAL_SALES_FLAG,$others->ACTUAL_SALES_AMOUNT,$others->OTHERS_DESC,$others->BRANCH_CODE,$others->CREATED_BY,$others->CREATION_DATE,$others->LAST_UPDATE_BY,$others->TRANSFER_FLAG,$others->STN_FLAG,$others->MUTATION_DATE,$others->BANK_ACCOUNT_ID,$others->NO_SHIFT,$others->START_INPUT_TIME));

            }
          }
  }

  public function get_batch_id($id){
    $stmt = 'SELECT "CDC_BATCH_ID" FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';

    $result = $this->db->query($stmt,$id)->result();

    return $result[0]->CDC_BATCH_ID;
  }

  public function Update_Receipt_Shift($rec_id,$validate){
   

    for($i = 0;$i < count($rec_id);$i++){
      $exp = explode('-',$rec_id[$i]);

     
      $status = 'S';
      

      $batchid = $this->get_batch_id($exp[1]);

      $stmt_uprec = 'UPDATE cdc_trx_receipts_shift SET "CDC_BATCH_ID" = ?, "STATUS" = ? WHERE "CDC_SHIFT_REC_ID" = ?';

      $this->db->query($stmt_uprec,array($batchid,$status,$exp[0]));

      //$this->db->affected_rows();
    }

     $data_others = $this->get_data_others_rec();

     foreach ($data_others as $others) {
        $batchid_others = $this->get_batch_id($others->CDC_REC_ID);

        $stmt_update_others = 'UPDATE cdc_trx_receipts_shift SET "CDC_BATCH_ID" = ?, "STATUS" = ? WHERE "CDC_REC_ID" = ?';

        $this->db->query($stmt_update_others,array($batchid_others,$status,$others->CDC_REC_ID));
     }
    
  }

  }
  ?>
