<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_report extends CI_Model {

  public function choose_branch()
    {
      if($this->session->userdata('role_id') == 7 || $this->session->userdata('role_id') == 8){
        $statement = 'SELECT \'100\' "BRANCH_ID" ,\'000\' "BRANCH_CODE" ,BTRIM(\'ALL CABANG\') "BRANCH_NAME" ,BTRIM(\'000\')||\'-\'||BTRIM(\'All Cabang\') "BRANCH_VALUE" union SELECT "BRANCH_ID", "BRANCH_CODE", BTRIM("BRANCH_NAME") "BRANCH_NAME", BTRIM("BRANCH_CODE")||\'-\'||BTRIM("BRANCH_NAME") "BRANCH_VALUE" FROM CDC_MASTER_BRANCH  WHERE BTRIM("BRANCH_CODE") NOT IN (\'001\')';
      
      

        $statement .= ' ORDER BY "BRANCH_CODE"';
      }else{
        $statement = 'SELECT "BRANCH_ID", "BRANCH_CODE", BTRIM("BRANCH_NAME") "BRANCH_NAME", BTRIM("BRANCH_CODE")||\'-\'||BTRIM("BRANCH_NAME") "BRANCH_VALUE" FROM CDC_MASTER_BRANCH WHERE BTRIM("BRANCH_CODE") NOT IN (\'001\')';
      
        if ($this->session->userdata('role_id') < 5) {
          $statement .= ' AND "BRANCH_ID" = '.$this->session->userdata('branch_id');
        }

        $statement .= ' ORDER BY "BRANCH_CODE"';
      }
      
      
      return $this->db->query($statement)->result();
    }


    public function get_detail_toko($store_code)
    {
      $statement='SELECT "STORE_NAME","TIPE_SETORAN","STORE_CODE" FROM cdc_master_toko where "STORE_CODE"=?';

      return $this->db->query($statement,$store_code)->row();


    }

    public function loop_toko($branch_id,$store_code,$tgl_awal,$tgl_akhir)
    {
      if($branch_id!=0 && $branch_id!=100){
            $statement='SELECT cmt."STORE_CODE",cmt."STORE_NAME" FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"  AND  cmt."BRANCH_ID"=? and cms."TIPE_SHIFT"=\'SS\' and cms."TGL_ACTIVE"<=? AND (cms."TGL_INACTIVE"<? OR cms."TGL_INACTIVE" IS NULL) group by cmt."STORE_CODE",cmt."STORE_NAME" ';

            return $this->db->query($statement,array($branch_id,$tgl_akhir,$tgl_akhir))->result();
      }else if($branch_id==100 ||$branch_id==0){
         $statement='SELECT cmt."STORE_CODE",cmt."STORE_NAME" FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"   and cms."TIPE_SHIFT"=\'SS\' and cms."TGL_ACTIVE"<=? AND (cms."TGL_INACTIVE"<? OR cms."TGL_INACTIVE" IS NULL) group by cmt."STORE_CODE",cmt."STORE_NAME" ';

            return $this->db->query($statement,array($tgl_akhir,$tgl_akhir))->result();
      }
      if($store_code!=0){
            $statement='SELECT cmt."STORE_CODE",cmt."STORE_NAME" FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"  AND  cmt."STORE_CODE"=? and cms."TIPE_SHIFT"=\'SS\' and cms."TGL_ACTIVE"<=? AND (cms."TGL_INACTIVE"<? OR cms."TGL_INACTIVE" IS NULL) group by cmt."STORE_CODE",cmt."STORE_NAME" ';

            return $this->db->query($statement,array($store_code,$tgl_akhir,$tgl_akhir))->result();
      }
      


    }

     public function cek_report($branch_id,$store_code,$tgl_awal,$tgl_akhir)
    {

      if($branch_id!=100 && $branch_id!=0)
      {

        $statement='SELECT count(*) as cek FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"  AND  cmt."BRANCH_ID"=? and cms."TIPE_SHIFT"=\'SS\' and cms."TGL_ACTIVE"<=? and (cms."TGL_INACTIVE" >? OR cms."TGL_INACTIVE" IS NULL) ';

        return $this->db->query($statement,array($branch_id,$tgl_akhir,$tgl_akhir))->row();


      }else if ($branch_id==100 || $branch_id==0){

        $statement='SELECT count(*) as cek FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"   and cms."TIPE_SHIFT"=\'SS\'  and cms."TGL_ACTIVE"<=? and (cms."TGL_INACTIVE" >? OR cms."TGL_INACTIVE" IS NULL) ';

      return $this->db->query($statement,array($tgl_akhir,$tgl_akhir))->row();

    }else{
        $statement='SELECT count(*) as cek FROM cdc_master_toko cmt,cdc_master_shift cms,cdc_trx_receipts_shift ctrs where cms."STORE_CODE"=cmt."STORE_CODE" AND cmt."STORE_ID"=ctrs."STORE_ID"   and cms."TIPE_SHIFT"=\'SS\' and cmt."STORE_CODE"=? and cms."TGL_ACTIVE"<=? and (cms."TGL_INACTIVE" >? OR cms."TGL_INACTIVE" IS NULL) ';

      return $this->db->query($statement,array($store_code,$tgl_akhir,$tgl_akhir))->row();
      }
      


    }
    public function cek_absensi_sales($branch_id,$store_code,$start_date,$end_date)
    {
      $where='';
      if($branch_id==100){
        if($store_code=='0000'){
          $statement='SELECT count(*) AS "HITUNG" FROM cdc_trx_receipts ctr where ctr."SALES_DATE">=? AND ctr."SALES_DATE"<=? AND ctr."ACTUAL_SALES_FLAG"=\'Y\' AND ctr."NO_SHIFT" IN (\'S-1\',\'S-2\',\'S-3\')';
          $rs=$this->db->query($statement,array($start_date,$end_date))->row();
          return $rs->HITUNG;
        }else{
          $statement='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts ctr where ctr."SALES_DATE">=? AND ctr."SALES_DATE"<=? AND ctr."ACTUAL_SALES_FLAG"=\'Y\' and ctr."STORE_ID"=(SELECT "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?)  AND ctr."NO_SHIFT" IN (\'S-1\',\'S-2\',\'S-3\')';
          $rs=$this->db->query($statement,array($start_date,$end_date,$store_code))->row();
          return $rs->HITUNG;
        }
      }else{
        if($store_code=='0000'){
          $statement='SELECT count(*) as "HITUNG" FROM cdc_trx_receipts ctr where ctr."SALES_DATE">=? AND ctr."SALES_DATE"<=? AND ctr."ACTUAL_SALES_FLAG"=\'Y\' and ctr."BRANCH_CODE"=(SELECT "BRANCH_CODE" FROM cdc_master_branch where "BRANCH_ID"=?)  AND ctr."NO_SHIFT" IN (\'S-1\',\'S-2\',\'S-3\')';
          $rs=$this->db->query($statement,array($start_date,$end_date,$branch_id))->row();
          return $rs->HITUNG;
        }else{
          $statement='SELECT count(*) as  "HITUNG" FROM cdc_trx_receipts ctr where ctr."SALES_DATE">=? AND ctr."SALES_DATE"<=? AND ctr."ACTUAL_SALES_FLAG"=\'Y\' and ctr."STORE_ID"=(SELECT "STORE_ID" FROM cdc_master_toko where "STORE_CODE"=?) and ctr."BRANCH_CODE"=(SELECT "BRANCH_CODE" FROM cdc_master_branch where "BRANCH_ID"=?)  AND ctr."NO_SHIFT" IN (\'S-1\',\'S-2\',\'S-3\')';
          $rs=$this->db->query($statement,array($start_date,$end_date,$store_code,$branch_id))->row();
          return $rs->HITUNG;
        }
      }

    }

    public function choose_store()
    {
      date_default_timezone_set("Asia/Bangkok");
      $tgl= date("Y-m-d");
      $branch_id=$this->session->userdata('branch_id');


      if($branch_id==28){
         $statement='select CONCAT (cmt."STORE_CODE",\'-\',cmt."STORE_NAME") as "STORE", cmt."STORE_CODE" from cdc_master_toko cmt,cdc_master_shift  cms where  cmt."STORE_CODE"=cms."STORE_CODE" AND cms."STATUS"=\'A\' AND cms."TIPE_SHIFT" =\'SS\' order by cmt."BRANCH_ID",cmt."STORE_CODE" ASC';
        return $this->db->query($statement)->result();
      }else{

        $statement=' select CONCAT (cmt."STORE_CODE",\'-\',cmt."STORE_NAME") as "STORE", cmt."STORE_CODE" from cdc_master_toko cmt,cdc_master_shift cms where  cmt."STORE_CODE"=cms."STORE_CODE" AND cms."STATUS"=\'A\' AND cms."TIPE_SHIFT" =\'SS\' and cmt."BRANCH_ID"=? order by cmt."STORE_CODE" ASC';
        return $this->db->query($statement,array($branch_id))->result();
      }
    }

    public function choose_store2($branch_id)
    {
     if($branch_id!='100'){
       $statement='SELECT CONCAT(\'0000\',\'-\',\'All Store\') as "STORE" , \'0000\' AS "STORE_CODE" UNION select CONCAT(cmt."STORE_CODE",\'-\',cmt."STORE_NAME") as "STORE",  cmt."STORE_CODE" from cdc_master_toko cmt , cdc_stores cs where cmt."STORE_CODE" =cs."STORE_CODE" and cmt."BRANCH_ID" = ? ORDER BY "STORE_CODE" ASC';
     }else{
      $statement='SELECT CONCAT(\'0000\',\'-\',\'All Store\') as "STORE" , \'0000\' AS "STORE_CODE" UNION select CONCAT(cmt."STORE_CODE",\'-\',cmt."STORE_NAME") as "STORE",  cmt."STORE_CODE" from cdc_master_toko cmt , cdc_stores cs where cmt."STORE_CODE" =cs."STORE_CODE" ORDER BY "STORE_CODE" ASC';
     }
      return $this->db->query($statement,array($branch_id))->result();
    }

    public function get_data_absensi_sales_toko($branch_id,$store_code,$tglawal){
      if($branch_id!=100 && $branch_id!=0)
      {
        $statement='SELECT cmt."STORE_CODE",
                  coalesce((select cmas."AM_SHORT" from cdc_master_am_as cmas where cmas."STORE_CODE"=cmt."STORE_CODE"),\'\') as "AM_SHORT",
                  coalesce((select cmas."AS_SHORT" from cdc_master_am_as cmas where cmas."STORE_CODE"=cmt."STORE_CODE"),\'\') as "AS_SHORT",
                  cms."TOTAL_SHIFT"AS "TOTAL_SHIFT",tgl_shift(?,?,?,1,\'N\')  as tgl_stj_shift_1 ,
                  tgl_shift(?,?,?,2,\'N\') as tgl_stj_shift_2 ,
                  tgl_shift(?,?,?,3,\'N\') as tgl_stj_shift_3,
                  tgl_shift(?,?,?,1,\'Y\') as tgl_stn_shift_1 ,
                  tgl_shift(?,?,?,2,\'Y\')  as tgl_stn_shift_2 ,
                  tgl_shift(?,?,?,3,\'Y\')  as tgl_stn_shift_3
                  FROM cdc_master_toko cmt,cdc_master_shift cms
                  where   cms."TGL_ACTIVE"<=? and (cms."TGL_INACTIVE" is null or cms."TGL_INACTIVE"<=? or cms."TGL_INACTIVE" >=NOW())  and cmt."STORE_CODE"=cms."STORE_CODE"  and cmt."STORE_CODE"=? and cmt."BRANCH_ID"=?';
      return $this->db->query($statement,array($tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$tglawal,$store_code,$branch_id))->result();
    }else{
      $statement_branch='SELECT "BRANCH_ID" FROM cdc_master_toko where "STORE_CODE"=?';
      $hasil=$this->db->query($statement_branch,array($store_code))->row();
      $branch_id=$hasil->BRANCH_ID;
      $statement='SELECT cmt."STORE_CODE",
                  coalesce((select cmas."AM_SHORT" from cdc_master_am_as cmas where cmas."STORE_CODE"=cmt."STORE_CODE"),\'\') as "AM_SHORT",
                  coalesce((select cmas."AS_SHORT" from cdc_master_am_as cmas where cmas."STORE_CODE"=cmt."STORE_CODE"),\'\') as "AS_SHORT",
                  cms."TOTAL_SHIFT"AS "TOTAL_SHIFT",tgl_shift(?,?,?,1,\'N\')  as tgl_stj_shift_1 ,
                  tgl_shift(?,?,?,2,\'N\') as tgl_stj_shift_2 ,
                  tgl_shift(?,?,?,3,\'N\') as tgl_stj_shift_3,
                  tgl_shift(?,?,?,1,\'Y\') as tgl_stn_shift_1 ,
                  tgl_shift(?,?,?,2,\'Y\')  as tgl_stn_shift_2 ,
                  tgl_shift(?,?,?,3,\'Y\')  as tgl_stn_shift_3
                  FROM cdc_master_toko cmt,cdc_master_shift cms
                  where   cms."TGL_ACTIVE"<=? and (cms."TGL_INACTIVE" is null or cms."TGL_INACTIVE"<=? or cms."TGL_INACTIVE" >=NOW())  and cmt."STORE_CODE"=cms."STORE_CODE"  and cmt."STORE_CODE"=? and cmt."BRANCH_ID"=?';
      return $this->db->query($statement,array($tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$store_code,$branch_id,$tglawal,$tglawal,$store_code,$branch_id))->result();
    }
      
    }

    public function monitoring_region($start_date,$end_date,$branch_code,$report_type)
    {   //if($branch_code=='000'){
            if($report_type=='Qty'){
                $statement='(Select asdf."BRANCH_CODE", sum(JUMLAH) AS JUMLAH, \'PENDING DEPOSIT\' as "STATUS"
                            from 

                            (SELECT ctrs."BRANCH_CODE", COUNT (*) AS JUMLAH,ctrs."SALES_DATE"

                               FROM cdc_trx_receipts_shift ctrs

                              WHERE   EXISTS (  SELECT 1 FROM cdc_master_slp cms
                                                JOIN cdc_master_toko cmt
                                                ON cmt."STORE_CODE" =

                                                                              cms."STORE_CODE"

                                                               WHERE (    cms."SALES_DATE" >= ?

                                                                      AND cms."SALES_DATE" <= ?)
                                                               AND cms."BRANCH_CODE" = ?

                                                               and cmt."BRANCH_ID" = (select "BRANCH_ID" from cdc_master_branch where "BRANCH_CODE" = ?)

                                                            GROUP BY cmt."STORE_ID")

                                    AND (ctrs."SALES_DATE" >= ? AND ctrs."SALES_DATE" <= ?)

                                    AND ctrs."ACTUAL_SALES_FLAG" = \'Y\'

                                    AND ctrs."BRANCH_CODE" = ?

                                            AND ctrs."CDC_BATCH_ID" is NULL

                            GROUP BY ctrs."BRANCH_CODE", ctrs."STORE_ID", ctrs."SALES_DATE"               

                            union all

                            SELECT asd."BRANCH_CODE", COUNT (*) AS JUMLAH, asd."SALES_DATE"

                                 FROM (  SELECT ctr."BRANCH_CODE", COUNT (ctr."STORE_ID"), ctr."SALES_DATE"

                                           FROM cdc_trx_receipts ctr,

                                                cdc_trx_batches ctb

                                          WHERE     ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"

                                                AND NOT EXISTS(   SELECT 1

                                                                           FROM cdc_master_slp cms

                                                                                JOIN cdc_master_toko cmt

                                                                                   ON cmt."STORE_CODE" =

                                                                                         cms."STORE_CODE"

                                                                          WHERE (    cms."SALES_DATE" >= ?

                                                                                 AND cms."SALES_DATE" <= ?)

                                                                          AND cms."BRANCH_CODE" =?

                                                                          and cmt."BRANCH_ID" = (select "BRANCH_ID" from cdc_master_branch where "BRANCH_CODE" =?)

                                                                       GROUP BY cmt."STORE_ID")

                                                AND ctr."CDC_BATCH_ID" IS NOT NULL

                                                AND (ctr."SALES_DATE" >= ? AND ctr."SALES_DATE" <= ?)

                                                AND ctr."BRANCH_CODE" = ?

                                                AND ctr."ACTUAL_SALES_FLAG" = \'Y\'

                                                and (ctr."STN_FLAG" = \'N\' AND ctb."CDC_DEPOSIT_ID" IS NULL)

                                       GROUP BY ctr."BRANCH_CODE", ctr."STORE_ID", ctr."SALES_DATE"

                                       ORDER BY ctr."BRANCH_CODE") asd

                            GROUP BY asd."BRANCH_CODE",asd."SALES_DATE") asdf

                            group by asdf."BRANCH_CODE"

                            )

                          UNION ALL

                          (  SELECT asd."BRANCH_CODE", sum(JUMLAH) AS JUMLAH, \'PENDING HITUNG\' AS STATUS

                               FROM (  SELECT cms."BRANCH_CODE", count(*) JUMLAH, cms."SALES_DATE"

                                         FROM cdc_master_slp cms

                                              LEFT JOIN cdc_handheld_table cht

                                                 ON cms."STORE_CODE" = cht."STORE_CODE"

                                        WHERE     cms."SALES_DATE" >= ?

                                              AND cms."SALES_DATE" <= ?

                                              AND (    cht."SCAN_DATE" >= TO_DATE (?, \'YYYY-MM-DD\') - 3

                                                   AND cht."SCAN_DATE" <= TO_DATE (?, \'YYYY-MM-DD\') + 3)

                                              AND cms."BRANCH_CODE" = ?

                                            
                                               AND  NOT EXISTS (SELECT 1

                                                                             FROM cdc_trx_receipts ctr,

                                                                                  cdc_master_toko cmt

                                                                            WHERE     ctr."STORE_ID" = cmt."STORE_ID"

                                                                                  AND (    ctr."SALES_DATE" >=?

                                                                                       AND ctr."SALES_DATE" <=?)

                                                                                  AND ctr."BRANCH_CODE" =?

                                                                                  and ctr."ACTUAL_SALES_FLAG" = \'Y\')

                                     GROUP BY cms."BRANCH_CODE", cms."SALES_DATE"

                                     ORDER BY cms."BRANCH_CODE") asd

                          GROUP BY asd."BRANCH_CODE")
                          UNION ALL
                          (select asd."BRANCH_CODE",SUM(asd."CEK_ORACLE")AS JUMLAH, \'CLEAR\' AS STATUS from ( SELECT ctr."BRANCH_CODE",
                                  cmt."STORE_CODE",
                                  ctr."SALES_DATE" AS TGL,
                                         (SELECT COUNT (*)
                                            FROM cdc_oracle
                                           WHERE     "BRANCH_CODE" = ?
                                                 AND "STORE_CODE" = cmt."STORE_CODE"
                                                 AND "TGL_SALES" = ctr."SALES_DATE")
                                            AS "CEK_ORACLE"
                                    FROM cdc_master_toko cmt,
                                         cdc_trx_receipts ctr,
                                         cdc_trx_batches ctb
                                   WHERE     cmt."STORE_ID" = ctr."STORE_ID"
                                         AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
                                         AND EXISTS
                                                (  SELECT 1
                                                     FROM cdc_master_slp cms
                                                          JOIN cdc_master_toko cmt
                                                             ON cmt."STORE_CODE" = cms."STORE_CODE"
                                                    WHERE     (    cms."SALES_DATE" >= ?
                                                               AND cms."SALES_DATE" <= ?)
                                                          AND cms."BRANCH_CODE" = ?
                                                          AND cmt."BRANCH_ID" =
                                                                 (SELECT "BRANCH_ID"
                                                                    FROM cdc_master_branch
                                                                   WHERE "BRANCH_CODE" = ?)
                                                 GROUP BY cmt."STORE_ID")
                                         AND ctr."CDC_BATCH_ID" IS NOT NULL
                                         AND (    ctr."SALES_DATE" >= ?
                                              AND ctr."SALES_DATE" <= ?)
                                         AND ctr."BRANCH_CODE" = ?
                                         AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                                         AND (   (    ctr."STN_FLAG" = \'N\'
                                                  AND ctb."CDC_DEPOSIT_ID" IS NOT NULL)
                                              OR (ctr."STN_FLAG" = \'Y\' AND ctb."TRANSFER_FLAG" = \'Y\'))
                                ) asd WHERE asd."CEK_ORACLE"=1 group by asd."BRANCH_CODE")
                        UNION ALL
                        (select asd."BRANCH_CODE",count(*)AS JUMLAH, \'PENDING JURNAL\' AS STATUS from ( SELECT ctr."BRANCH_CODE",
                                cmt."STORE_CODE",
                                ctr."SALES_DATE" AS TGL
                        FROM cdc_master_toko cmt,
                             cdc_trx_receipts ctr,
                             cdc_trx_batches ctb
                                   WHERE     cmt."STORE_ID" = ctr."STORE_ID"
                                         AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
                                         AND EXISTS
                                                (  SELECT 1
                                                     FROM cdc_master_slp cms
                                                          JOIN cdc_master_toko cmt
                                                             ON cmt."STORE_CODE" = cms."STORE_CODE"
                                                    WHERE     (    cms."SALES_DATE" >= ?
                                                               AND cms."SALES_DATE" <= ?)
                                                          AND cms."BRANCH_CODE" = ?
                                                          AND cmt."BRANCH_ID" =
                                                                 (SELECT "BRANCH_ID"
                                                                    FROM cdc_master_branch
                                                                   WHERE "BRANCH_CODE" = ?)
                                                 GROUP BY cmt."STORE_ID")
                                         AND ctr."CDC_BATCH_ID" IS NOT NULL
                                         AND (    ctr."SALES_DATE" >= ?
                                              AND ctr."SALES_DATE" <= ?)
                                         AND ctr."BRANCH_CODE" = ?
                                         AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                                         AND (   (    ctr."STN_FLAG" = \'N\'
                                                  AND ctb."CDC_DEPOSIT_ID" IS NOT NULL)
                                              OR (ctr."STN_FLAG" = \'Y\' AND ctb."TRANSFER_FLAG" = \'Y\'))
                        AND NOT EXISTS (SELECT 1
                                            FROM cdc_oracle
                                           WHERE     "BRANCH_CODE" = ?
                                                 AND "STORE_CODE" = cmt."STORE_CODE"
                                                 AND "TGL_SALES" = ctr."SALES_DATE")
                                ) asd  group by asd."BRANCH_CODE")
                          UNION ALL
                          (  SELECT asd."BRANCH_CODE", COUNT (*) AS JUMLAH, \'TOKO TUTUP\' AS STATUS

                               FROM (  SELECT sdc."BRANCH_CODE", COUNT (chl."STORE_CODE")

                                         FROM cdc_hari_libur chl,cdc_stores cst,sys_map_dc sdc

                                        WHERE   chl."BRANCH_CODE"=sdc."DC_CODE"
                                        and  chl."STORE_CODE"=cst."STORE_CODE"
                                  AND ((cst."TGL_INACTIVE_CABANG"< ? AND cst."TGL_INACTIVE_CABANG" > ?) OR cst."TGL_INACTIVE_CABANG" IS NULL)

                                  AND (chl."TGL_TUTUP" >= ? AND chl."TGL_TUTUP" <= ?)

                                              AND sdc."BRANCH_CODE" = ?

                                     GROUP BY sdc."BRANCH_CODE", chl."STORE_CODE",chl."TGL_TUTUP"

                                     ORDER BY sdc."BRANCH_CODE") asd

                          GROUP BY asd."BRANCH_CODE")
                          UNION ALL
                          (select asd."BRANCH_CODE",count(*) AS JUMLAH,\'PENDING SETOR\' AS STATUS from (SELECT
                                         cms."BRANCH_CODE",
                                         cmt."STORE_CODE"          
                                      FROM  cdc_master_slp cms, 
                                            cdc_master_toko cmt,
                                            cdc_stores cs 
                                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                                             AND cms."SALES_DATE" >= ?
                                             AND cms."SALES_DATE" <=?
                                             AND ((cs."TGL_INACTIVE_CABANG"<? AND cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                                             AND cms."BRANCH_CODE"=?
                                             AND cms."SALES_AMOUNT"!=0
                                             AND  NOT EXISTS(SELECT 1
                                                                          FROM   cdc_handheld_table cht 
                                                                WHERE  ( cht."SCAN_DATE" >= 
                                                                         To_date( 
                                                                         ?, 
                                                                         \'YYYY-MM-DD\') 
                                                                         - 3 
                                                                         AND cht."SCAN_DATE" <= 
                                                                             To_date( ?, 
                                                                             \'YYYY-MM-DD\') + 3 )
                                                                      AND "STORE_CODE"=cmt."STORE_CODE"
                                            )             
                                            AND NOT EXISTS (SELECT 1
                                                             FROM   cdc_trx_receipts ctr 
                                                             WHERE  ( ctr."SALES_DATE" >=?
                                                                      AND ctr."SALES_DATE" <= ? ) 
                                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                                                                    AND ctr."BRANCH_CODE"=?
                                        
                                            ) 
                                          GROUP BY
                                             cms."BRANCH_CODE",
                                             cmt."STORE_CODE" ,
                                             cmt."STORE_NAME",
                                             cms."SALES_DATE") asd
                                        GROUP BY asd."BRANCH_CODE"

                )';



                             
                  return $this->db->query($statement,array($start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                        //  $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $start_date,
                                          $end_date,
                                          $branch_code,$start_date,$end_date,$start_date,$end_date,$branch_code,$start_date,$end_date,$start_date,$end_date,$branch_code))->result();

            }else{
                $statement='(select asdf."BRANCH_CODE", sum(asdf."JUMLAH") AS JUMLAH, \'PENDING DEPOSIT\' as "STATUS"
                            from 
                            (SELECT ctrs."BRANCH_CODE", 
                                   sum((ctrs."ACTUAL_SALES_AMOUNT")+(ctrs."ACTUAL_RRAK_AMOUNT")+(ctrs."ACTUAL_PAY_LESS_DEPOSITED")+(ctrs."ACTUAL_VOUCHER_AMOUNT")+(ctrs."ACTUAL_OTHERS_AMOUNT")) AS "JUMLAH",ctrs."SALES_DATE"
                              FROM cdc_trx_receipts_shift ctrs
                              WHERE    EXISTS(  SELECT 1 FROM cdc_master_slp cms JOIN cdc_master_toko cmt
                                                            ON cmt."STORE_CODE" =cms."STORE_CODE"
                                                            WHERE cms."SALES_DATE" >= ? AND cms."SALES_DATE" <=?
                                                            AND cms."BRANCH_CODE" = ?
                                                            and cmt."BRANCH_ID" = (select "BRANCH_ID" from cdc_master_branch where "BRANCH_CODE" = ?)
                                                            GROUP BY cmt."STORE_ID")
                                    AND (ctrs."SALES_DATE" >= ? AND ctrs."SALES_DATE" <= ?)
                                    AND ctrs."ACTUAL_SALES_FLAG" = \'Y\'
                                    AND ctrs."BRANCH_CODE" =?
                                    AND ctrs."CDC_BATCH_ID" is NULL
                            GROUP BY ctrs."BRANCH_CODE", ctrs."STORE_ID", ctrs."SALES_DATE"               
                            union all
                            SELECT asd."BRANCH_CODE",sum(asd."JUMLAH") AS JUMLAH, asd."SALES_DATE"
                                 FROM (  SELECT ctr."BRANCH_CODE", sum((ctr."ACTUAL_SALES_AMOUNT")+(ctr."ACTUAL_RRAK_AMOUNT")+(ctr."ACTUAL_PAY_LESS_DEPOSITED")+(ctr."ACTUAL_VOUCHER_AMOUNT")+(ctr."ACTUAL_OTHERS_AMOUNT")) as "JUMLAH", ctr."SALES_DATE"
                                           FROM cdc_trx_receipts ctr,
                                                cdc_trx_batches ctb
                                          WHERE     ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
                                                AND EXISTS (   SELECT 1 FROM cdc_master_slp cms JOIN cdc_master_toko cmt
                                                                       ON cmt."STORE_CODE" = cms."STORE_CODE"
                                                                       WHERE (cms."SALES_DATE" >=?
                                                                       AND cms."SALES_DATE" <= ?)
                                                                       AND cms."BRANCH_CODE" =?
                                                                       and cmt."BRANCH_ID" = (select "BRANCH_ID" from cdc_master_branch where "BRANCH_CODE" = ?)
                                                                       GROUP BY cmt."STORE_ID")
                                                AND ctr."CDC_BATCH_ID" IS NOT NULL
                                                AND (ctr."SALES_DATE" >=? AND ctr."SALES_DATE" <=?)
                                                AND ctr."BRANCH_CODE" = ?
                                                AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                                                and (ctr."STN_FLAG" = \'N\' AND ctb."CDC_DEPOSIT_ID" IS NULL)
                                       GROUP BY ctr."BRANCH_CODE", ctr."STORE_ID", ctr."SALES_DATE"
                                       ORDER BY ctr."BRANCH_CODE") asd
                            GROUP BY asd."BRANCH_CODE",asd."SALES_DATE") asdf
                            group by asdf."BRANCH_CODE"
                            )UNION ALL
                              (  SELECT asd."BRANCH_CODE", sum("JUMLAH") AS JUMLAH, \'PENDING HITUNG\' AS STATUS
                               FROM (  SELECT cms."BRANCH_CODE",SUM(cms."SALES_AMOUNT") "JUMLAH", cms."SALES_DATE"
                                         FROM cdc_master_slp cms
                                         LEFT JOIN cdc_handheld_table cht
                                                 ON cms."STORE_CODE" = cht."STORE_CODE"
                                        WHERE     cms."SALES_DATE" >= ?
                                              AND cms."SALES_DATE" <= ?
                                              AND (cht."SCAN_DATE" >= TO_DATE ( ?, \'YYYY-MM-DD\') - 3
                                                   AND cht."SCAN_DATE" <= TO_DATE (? ,\'YYYY-MM-DD\') + 3)
                                              AND cms."BRANCH_CODE" = ?
                                              AND NOT EXISTS (SELECT 1 FROM cdc_trx_receipts ctr,cdc_master_toko cmt
                                                               WHERE     ctr."STORE_ID" = cmt."STORE_ID"
                                                                     AND (ctr."SALES_DATE" >=?
                                                                     AND ctr."SALES_DATE" <=?)
                                                                     AND ctr."BRANCH_CODE" =?
                                                                     AND cmt."STORE_CODE"=cht."STORE_CODE"
                                                                     AND ctr."ACTUAL_SALES_FLAG" = \'Y\')
                                     GROUP BY cms."BRANCH_CODE", cms."SALES_DATE"
                                     ORDER BY cms."BRANCH_CODE") asd
                          GROUP BY asd."BRANCH_CODE")
                          union all
                           (select asd."BRANCH_CODE",SUM(asd."JUMLAH")AS JUMLAH, \'CLEAR\' AS STATUS from ( SELECT ctr."BRANCH_CODE",
                                  cmt."STORE_CODE",
                                  ctr."SALES_DATE" AS TGL,
                                  (SELECT COUNT (*)
                                            FROM cdc_oracle
                                           WHERE     "BRANCH_CODE" = ?
                                                 AND "STORE_CODE" = cmt."STORE_CODE"
                                                 AND "TGL_SALES" = ctr."SALES_DATE")
                                            AS "CEK_ORACLE",
                                  sum((ctr."ACTUAL_SALES_AMOUNT")+(ctr."ACTUAL_RRAK_AMOUNT")+(ctr."ACTUAL_PAY_LESS_DEPOSITED")+(ctr."ACTUAL_VOUCHER_AMOUNT")+(ctr."ACTUAL_OTHERS_AMOUNT")) AS "JUMLAH"
                                    FROM cdc_master_toko cmt,
                                         cdc_trx_receipts ctr,
                                         cdc_trx_batches ctb
                                   WHERE     cmt."STORE_ID" = ctr."STORE_ID"
                                         AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
                                         AND EXISTS
                                                (  SELECT 1
                                                     FROM cdc_master_slp cms
                                                          JOIN cdc_master_toko cmt
                                                             ON cmt."STORE_CODE" = cms."STORE_CODE"
                                                    WHERE     (    cms."SALES_DATE" >= ?
                                                               AND cms."SALES_DATE" <= ?)
                                                          AND cms."BRANCH_CODE" = ?
                                                          AND cmt."BRANCH_ID" =
                                                                 (SELECT "BRANCH_ID"
                                                                    FROM cdc_master_branch
                                                                   WHERE "BRANCH_CODE" = ?)
                                                 GROUP BY cmt."STORE_ID")
                                         AND ctr."CDC_BATCH_ID" IS NOT NULL
                                         AND (    ctr."SALES_DATE" >= ?
                                              AND ctr."SALES_DATE" <= ?)
                                         AND ctr."BRANCH_CODE" = ?
                                         AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                                         AND (   (    ctr."STN_FLAG" = \'N\'
                                                  AND ctb."CDC_DEPOSIT_ID" IS NOT NULL)
                                              OR (ctr."STN_FLAG" = \'Y\' AND ctb."TRANSFER_FLAG" = \'Y\'))
                        GROUP BY ctr."BRANCH_CODE",cmt."STORE_CODE",ctr."SALES_DATE"
                                ) asd WHERE asd."CEK_ORACLE"=1 group by asd."BRANCH_CODE")
                         UNION ALL
    
                        (select asd."BRANCH_CODE",sum(asd."JUMLAH")AS JUMLAH, \'PENDING JURNAL\' AS STATUS from ( SELECT ctr."BRANCH_CODE",
                                cmt."STORE_CODE",
                                ctr."SALES_DATE" AS TGL,
                                (SELECT COUNT (*)
                                            FROM cdc_oracle
                                           WHERE     "BRANCH_CODE" = ?
                                                 AND "STORE_CODE" = cmt."STORE_CODE"
                                                 AND "TGL_SALES" = ctr."SALES_DATE")
                                            AS "CEK_ORACLE",
                                sum((ctr."ACTUAL_SALES_AMOUNT")+(ctr."ACTUAL_RRAK_AMOUNT")+(ctr."ACTUAL_PAY_LESS_DEPOSITED")+(ctr."ACTUAL_VOUCHER_AMOUNT")+(ctr."ACTUAL_OTHERS_AMOUNT")) "JUMLAH"
                        FROM cdc_master_toko cmt,
                             cdc_trx_receipts ctr,
                             cdc_trx_batches ctb
                                   WHERE     cmt."STORE_ID" = ctr."STORE_ID"
                                         AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID"
                                         AND EXISTS
                                                (  SELECT 1
                                                     FROM cdc_master_slp cms
                                                          JOIN cdc_master_toko cmt
                                                             ON cmt."STORE_CODE" = cms."STORE_CODE"
                                                    WHERE     (    cms."SALES_DATE" >= ?
                                                               AND cms."SALES_DATE" <= ?)
                                                          AND cms."BRANCH_CODE" = ?
                                                          AND cmt."BRANCH_ID" =
                                                                 (SELECT "BRANCH_ID"
                                                                    FROM cdc_master_branch
                                                                   WHERE "BRANCH_CODE" = ?)
                                                 GROUP BY cmt."STORE_ID")
                                         AND ctr."CDC_BATCH_ID" IS NOT NULL
                                         AND (    ctr."SALES_DATE" >=?
                                              AND ctr."SALES_DATE" <= ?)
                                         AND ctr."BRANCH_CODE" = ?
                                         AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                                         AND (   (    ctr."STN_FLAG" = \'N\'
                                                  AND ctb."CDC_DEPOSIT_ID" IS NOT NULL)
                                              OR (ctr."STN_FLAG" = \'Y\' AND ctb."TRANSFER_FLAG" = \'Y\'))
                        AND NOT EXISTS (SELECT 1
                                            FROM cdc_oracle
                                           WHERE     "BRANCH_CODE" = ?
                                           AND "STORE_CODE" = cmt."STORE_CODE"
                                                 AND "TGL_SALES" = ctr."SALES_DATE")
                        GROUP BY ctr."BRANCH_CODE",cmt."STORE_CODE",ctr."SALES_DATE"
                                ) asd  group by asd."BRANCH_CODE")
                        union all
                         (Select asd."BRANCH_CODE",SUM(asd."JUMLAH") AS JUMLAH,\'PENDING SETOR\' AS STATUS from (SELECT
                                         cms."BRANCH_CODE",
                                         cmt."STORE_CODE",
                                         SUM(cms."SALES_AMOUNT") AS "JUMLAH"          
                                      FROM  cdc_master_slp cms, 
                                            cdc_master_toko cmt,
                                            cdc_stores cs 
                                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                                             AND cms."SALES_DATE" >= ?
                                             AND cms."SALES_DATE" <=?
                                             AND ((cs."TGL_INACTIVE_CABANG"<? AND cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                                             AND cms."BRANCH_CODE"=?
                                             AND cms."SALES_AMOUNT"!=0
                                             AND  NOT EXISTS(SELECT 1
                                                                          FROM   cdc_handheld_table cht 
                                                                WHERE  ( cht."SCAN_DATE" >= 
                                                                         To_date( 
                                                                         ?, 
                                                                         \'YYYY-MM-DD\') 
                                                                         - 3 
                                                                         AND cht."SCAN_DATE" <= 
                                                                             To_date( ?, 
                                                                             \'YYYY-MM-DD\') + 3 )
                                                                     AND "STORE_CODE"=cmt."STORE_CODE"
                                            )             
                                            AND NOT EXISTS (SELECT 1
                                                             FROM   cdc_trx_receipts ctr 
                                                             WHERE  ( ctr."SALES_DATE" >=?
                                                                      AND ctr."SALES_DATE" <= ? ) 
                                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                                                                    AND ctr."BRANCH_CODE"=?
                                        
                                            ) 
                                          GROUP BY
                                             cms."BRANCH_CODE",
                                             cmt."STORE_CODE" ,
                                             cmt."STORE_NAME",
                                             cms."SALES_DATE") asd
                                        GROUP BY asd."BRANCH_CODE"

                )';


                  return $this->db->query($statement,array($start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,$start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,
                                          $start_date,
                                          $end_date,
                                          $branch_code,
                                          $branch_code,$start_date,$end_date,$start_date,$end_date,$branch_code,$start_date,$end_date,$start_date,$end_date,$branch_code
                                                                                             ))->result();

                             

      

            }
            

      //  }
      
    }
    public function cek_data_pending_setor_toko($branch_id,$tglawal,$tglakhir){

        if($branch_id=='100'){
            $statement='select exists(select 1         
                      FROM  cdc_master_slp cms, 
                            cdc_master_toko cmt,
                            cdc_stores cs 
                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                             AND cms."SALES_DATE" >= ?
                             AND cms."SALES_DATE" <= ?
                             and cms."SALES_AMOUNT"!=0
                             AND ((cs."TGL_INACTIVE_CABANG"<? OR cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                             AND  NOT EXISTS(SELECT 1
                                                          FROM   cdc_handheld_table cht 
                                                WHERE  ( cht."SCAN_DATE" >= 
                                                         To_date( 
                                                         ?, 
                                                         \'YYYY-MM-DD\') 
                                                         - 3 
                                                         AND cht."SCAN_DATE" <= 
                                                             To_date( ?, 
                                                             \'YYYY-MM-DD\') + 3 )
                                                        AND "STORE_CODE"=cmt."STORE_CODE"
                                                     
                            )             
                            AND NOT EXISTS (SELECT 1
                                             FROM   cdc_trx_receipts ctr 
                                             WHERE  ( ctr."SALES_DATE" >=? 
                                                      AND ctr."SALES_DATE" <= ? ) 
                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                        
                            )) AS "HITUNG"  ';
            $result=$this->db->query($statement,array($tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir))->row()->HITUNG;

        }else
        {
            $statement='select exists(select 1            
                      FROM  cdc_master_slp cms, 
                            cdc_master_toko cmt,
                            cdc_stores cs 
                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                             AND cms."SALES_DATE" >= ?
                             AND cms."SALES_DATE" <= ?
                             AND cms."SALES_AMOUNT"!=0
                             AND ((cs."TGL_INACTIVE_CABANG"<? OR cs."TGL_INACTIVE_CABANG">?) OR cs."TGL_INACTIVE_CABANG" IS NULL)
                             AND cmt."BRANCH_ID"=?
                             AND  NOT EXISTS(SELECT 1
                                                          FROM   cdc_handheld_table cht 
                                                WHERE  ( cht."SCAN_DATE" >= 
                                                         To_date( 
                                                         ?, 
                                                         \'YYYY-MM-DD\') 
                                                         - 3 
                                                         AND cht."SCAN_DATE" <= 
                                                             To_date( ?, 
                                                             \'YYYY-MM-DD\') + 3 )
                                                      AND "STORE_CODE"=cmt."STORE_CODE"
                            )             
                            AND NOT EXISTS (SELECT  1
                                             FROM   cdc_trx_receipts ctr 
                                             WHERE  ( ctr."SALES_DATE" >=? 
                                                      AND ctr."SALES_DATE" <= ? ) 
                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                                                    AND ctr."BRANCH_CODE"=(SELECT 
                                                           "BRANCH_CODE" 
                                                                       FROM 
                                                           cdc_master_branch 
                                                                       WHERE 
                                                           "BRANCH_ID" =?
                                                     )
                        
                            )) as "HITUNG" ';
            $result=$this->db->query($statement,array($tglawal,$tglakhir,$tglawal,$tglakhir,$branch_id,$tglawal,$tglakhir,$tglawal,$tglakhir,$branch_id))->row()->HITUNG;
        }
            return $result;
    }


    public function m($tglawal,$tglakhir){
      $statement='select generate_series(?,?) m';
      $result=$this->db->query($statement,array($tglawal,$tglakhir))->result();

      return $result;


    }
    public function monitoring_sales_detail($branch_code,$start_date,$end_date){

      
      //  $datediff = strtotime($end_date) - strtotime($start_date); 
      //  $hari = floor($datediff / (60 * 60 * 24))+1;
    
        $day = '';
        $day2 = '';
        $result='';
        $string = $start_date;
        $date = DateTime::createFromFormat("Y-m-d", $string);
        $tglawal=trim($date->format("d"));

        if(substr($tglawal, 0, 1)=='0'){
          $tglawal=substr($tglawal, 1, 1);
        }
        $string2 = $end_date;
        $date2 = DateTime::createFromFormat("Y-m-d", $string2);
        $tglakhir=trim($date2->format("d"));
        if(substr($tglakhir, 0, 1)=='0'){
          $tglakhir=substr($tglakhir, 1, 1);
        }

        for ($i=trim($tglawal); $i <= trim($tglakhir) ; $i++) { 

          	  $tgl_temp = array(1,2,3,4,5,6,7,8,9);
              if($i != trim($tglakhir))
              {
                  $day .= '"0'.$i.'" TEXT,';
                  $day2.= '"0'.$i.'", ';
                
              }else{

                  $day .= '"0'.$i.'" TEXT';
                  $day2.= '"0'.$i.'" ';
              }
            
          
        } 
        if($branch_code!='000'){
            $statement ='SELECT 
                          "STORE_CODE", 
                          '.$day2.'
                        FROM 
                          crosstab (
                            \'select 
                          asdf1."STORE_CODE" :: text, 
                          asdf1.TGL :: integer as "VAL",
                          asdf1.status 
                        from 
                          (
                            (
                              SELECT 
                                asd."STORE_CODE" :: text, 
                                extract(
                                  day 
                                  from 
                                    asd.TGL
                                ):: integer AS TGL, 
                                CONCAT(\'\'PENDING SETOR\'\', \'\';\'\', asd.rp):: text AS STATUS 
                              FROM 
                                (
                                  SELECT 
                                    cms."STORE_CODE", 
                                    cms."SALES_DATE" AS TGL, 
                                    sum(cms."SALES_AMOUNT") as rp 
                                  FROM 
                                    cdc_master_slp cms, 
                                    cdc_master_toko cmt, 
                                    cdc_stores cs 
                                  WHERE 
                                    cms."STORE_CODE" = cmt."STORE_CODE" 
                                    AND cmt."STORE_CODE" = cs."STORE_CODE" 
                                    AND cms."SALES_DATE" >=\'\''.$start_date.'\'\'
                                    AND cms."SALES_DATE" <=\'\''.$end_date.'\'\'
                                    AND cms."SALES_AMOUNT" != 0 
                                    AND NOT EXISTS (
                                      SELECT 
                                        1 
                                      FROM 
                                        cdc_trx_receipts ctr 
                                      WHERE 
                                        (
                                          ctr."SALES_DATE" =cms."SALES_DATE"
                                        ) 
                                        AND ctr."STORE_ID" = cmt."STORE_ID"
                                    ) 
                                    AND cms."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                  GROUP BY 
                                    cms."STORE_CODE", 
                                    cms."SALES_DATE" 
                                  ORDER BY 
                                    cms."STORE_CODE"
                                ) asd
                            ) 
                            UNION ALL 
                              (
                                SELECT 
                                  asd."STORE_CODE" :: text, 
                                  extract(
                                    day 
                                    from 
                                      asd.TGL
                                  ) :: integer as TGL, 
                                  CONCAT(
                                    (
                                      CASE WHEN "CEK_ORACLE" = 1 THEN \'\'CLEAR \'\' ELSE \'\'PENDING JURNAL \'\' END
                                    ), 
                                    \'\';\'\', 
                                    asd.rp
                                  ) :: text AS STATUS 
                                FROM 
                                  (
                                    SELECT 
                                      cmt."STORE_CODE", 
                                      ctr."SALES_DATE" AS TGL, 
                                      sum(
                                        (ctr."ACTUAL_SALES_AMOUNT") +(ctr."ACTUAL_RRAK_AMOUNT") +(
                                          ctr."ACTUAL_PAY_LESS_DEPOSITED"
                                        ) +(ctr."ACTUAL_VOUCHER_AMOUNT") +(ctr."ACTUAL_OTHERS_AMOUNT")
                                      ) as rp, 
                                      (
                                        SELECT 
                                          COUNT (*) 
                                        FROM 
                                          cdc_oracle 
                                        WHERE 
                                          "BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                          AND "STORE_CODE" = cmt."STORE_CODE" 
                                          AND "TGL_SALES" = ctr."SALES_DATE"
                                      ) AS "CEK_ORACLE" 
                                    FROM 
                                      cdc_master_toko cmt, 
                                      cdc_trx_receipts ctr, 
                                      cdc_trx_batches ctb 
                                    WHERE 
                                      cmt."STORE_ID" = ctr."STORE_ID" 
                                      AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" 
                                      AND EXISTS (
                                        SELECT 
                                          1 
                                        FROM 
                                          cdc_master_slp cms 
                                          JOIN cdc_master_toko cmt ON cmt."STORE_CODE" = cms."STORE_CODE" 
                                        WHERE 
                                          (
                                            cms."SALES_DATE" >=\'\''.$start_date.'\'\'
                                            AND cms."SALES_DATE" <= \'\''.$end_date.'\'\'
                                          ) 
                                          AND cms."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                          AND cmt."BRANCH_ID" = (
                                            SELECT 
                                              "BRANCH_ID" 
                                            FROM 
                                              cdc_master_branch 
                                            WHERE 
                                              "BRANCH_CODE" =  \'\''.$branch_code.'\'\'
                                          ) 
                                        GROUP BY 
                                          cmt."STORE_ID"
                                      ) 
                                      AND ctr."CDC_BATCH_ID" IS NOT NULL 
                                      AND (
                                        ctr."SALES_DATE" >= \'\''.$start_date.'\'\'
                                        AND ctr."SALES_DATE" <= \'\''.$end_date.'\'\'
                                      ) 
                                      AND ctr."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                      AND ctr."ACTUAL_SALES_FLAG" = \'\'Y\'\' 
                                      AND (
                                        (
                                          ctr."STN_FLAG" = \'\'N\'\' 
                                          AND ctb."CDC_DEPOSIT_ID" IS NOT NULL
                                        ) 
                                        OR (
                                          ctr."STN_FLAG" = \'\'Y\'\' 
                                          AND ctb."TRANSFER_FLAG" = \'\'Y\'\'
                                        )
                                      ) 
                                    GROUP BY 
                                      cmt."STORE_CODE", 
                                      ctr."SALES_DATE"
                                  ) asd
                              ) 
                            UNION ALL 
                              (
                                SELECT 
                                  cmt."STORE_CODE" :: text, 
                                  extract(
                                    day 
                                    from 
                                      ctrs."SALES_DATE"
                                  ) :: integer AS TGL, 
                                  concat(
                                    \'\'PENDING DEPOSIT\'\', 
                                    \'\';\'\', 
                                    sum(
                                      (ctrs."ACTUAL_SALES_AMOUNT") +(ctrs."ACTUAL_RRAK_AMOUNT") +(
                                        ctrs."ACTUAL_PAY_LESS_DEPOSITED"
                                      ) +(ctrs."ACTUAL_VOUCHER_AMOUNT") +(ctrs."ACTUAL_OTHERS_AMOUNT")
                                    )
                                  ) :: text AS STATUS 
                                FROM 
                                  cdc_trx_receipts_shift ctrs, 
                                  cdc_master_toko cmt 
                                WHERE 
                                  ctrs."STORE_ID" = cmt."STORE_ID" 
                                  AND EXISTS (
                                    SELECT 
                                      1 
                                    FROM 
                                      cdc_master_slp cms 
                                      JOIN cdc_master_toko cmt ON cmt."STORE_CODE" = cms."STORE_CODE" 
                                    WHERE 
                                      (
                                        cms."SALES_DATE" >=  \'\''.$start_date.'\'\'
                                        AND cms."SALES_DATE" <=  \'\''.$end_date.'\'\'
                                      ) 
                                      AND cms."BRANCH_CODE" =  \'\''.$branch_code.'\'\'
                                      AND cmt."BRANCH_ID" =(
                                        SELECT 
                                          "BRANCH_ID" 
                                        FROM 
                                          cdc_master_branch 
                                        WHERE 
                                          "BRANCH_CODE" =  \'\''.$branch_code.'\'\'
                                      ) 
                                    GROUP BY 
                                      cmt."STORE_ID"
                                  ) 
                                  AND (
                                    ctrs."SALES_DATE" >=  \'\''.$start_date.'\'\'
                                    AND ctrs."SALES_DATE" <=  \'\''.$end_date.'\'\'
                                  ) 
                                  AND ctrs."ACTUAL_SALES_FLAG" = \'\'Y\'\' 
                                  AND ctrs."BRANCH_CODE" =  \'\''.$branch_code.'\'\'
                                  AND ctrs."CDC_BATCH_ID" IS NULL 
                                GROUP BY 
                                  cmt."STORE_CODE", 
                                  ctrs."SALES_DATE" 
                                UNION ALL 
                                SELECT 
                                  cmt."STORE_CODE" :: text, 
                                  extract(
                                    day 
                                    from 
                                      ctrs."SALES_DATE"
                                  ) :: integer AS TGL, 
                                  concat(
                                    \'\'PENDING DEPOSIT\'\',\'\';\'\', 
                                    sum(
                                      (ctrs."ACTUAL_SALES_AMOUNT") +(ctrs."ACTUAL_RRAK_AMOUNT") +(
                                        ctrs."ACTUAL_PAY_LESS_DEPOSITED"
                                      ) +(ctrs."ACTUAL_VOUCHER_AMOUNT") +(ctrs."ACTUAL_OTHERS_AMOUNT")
                                    )
                                  ) :: text AS STATUS 
                                FROM 
                                  cdc_trx_receipts_shift ctrs, 
                                  cdc_master_toko cmt, 
                                  cdc_trx_batches ctb 
                                WHERE 
                                  ctrs."STORE_ID" = cmt."STORE_ID" 
                                  AND EXISTS (
                                    SELECT 
                                      1 
                                    FROM 
                                      cdc_master_slp cms 
                                      JOIN cdc_master_toko cmt ON cmt."STORE_CODE" = cms."STORE_CODE" 
                                    WHERE 
                                      (
                                        cms."SALES_DATE" >= \'\''.$start_date.'\'\'
                                        AND cms."SALES_DATE" <= \'\''.$end_date.'\'\'
                                      ) 
                                      AND cms."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                      AND cmt."BRANCH_ID" =(
                                        SELECT 
                                          "BRANCH_ID" 
                                        FROM 
                                          cdc_master_branch 
                                        WHERE 
                                          "BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                      ) 
                                    GROUP BY 
                                      cmt."STORE_ID"
                                  ) 
                                  AND (
                                    ctrs."SALES_DATE" >= \'\''.$start_date.'\'\'
                                    AND ctrs."SALES_DATE" <= \'\''.$end_date.'\'\'
                                  ) 
                                  AND NOT EXISTS(
                                          SELECT 
                                            1 
                                          FROM 
                                            cdc_oracle co 
                                            JOIN cdc_master_toko cmt ON cmt."STORE_CODE" = co."STORE_CODE" 
                                          WHERE 
                                            (
                                              co."TGL_SALES" >= ctrs."SALES_DATE"
                                              AND co."TGL_SALES" <= ctrs."SALES_DATE"
                                              AND cmt."STORE_ID" = ctrs."STORE_ID"
                                            ) 
                                            AND cmt."BRANCH_ID" = (
                                              SELECT 
                                                "BRANCH_ID" 
                                              FROM 
                                                cdc_master_branch 
                                              WHERE 
                                                "BRANCH_CODE" =\'\''.$branch_code.'\'\' 
                                            ) 
                                          GROUP BY 
                                            cmt."STORE_ID"
                                        ) 
                                  AND ctrs."ACTUAL_SALES_FLAG" = \'\'Y\'\' 
                                  AND ctrs."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                  AND ctrs."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" 
                                  AND ctb."CDC_DEPOSIT_ID" IS NULL 
                                GROUP BY 
                                  cmt."STORE_CODE", 
                                  ctrs."SALES_DATE"
                              ) 
                            UNION ALL 
                              
                              (
                                SELECT 
                                  asd."STORE_CODE" :: text, 
                                  extract(
                                    day 
                                    from 
                                      asd."TGL"
                                  ) :: integer, 
                                  CONCAT(\'\'TOKO TUTUP\'\',\'\';\'\', asd.rp) :: text AS STATUS 
                                FROM 
                                  (
                                    SELECT 
                                      chl."STORE_CODE", 
                                      chl."TGL_TUTUP" AS "TGL", 
                                      0 as rp 
                                    FROM 
                                      cdc_hari_libur chl, 
                                      sys_map_dc sdc, 
                                      cdc_stores cst 
                                    WHERE 
                                      sdc."DC_CODE" = chl."BRANCH_CODE" 
                                      AND chl."STORE_CODE" = cst."STORE_CODE" 
                                      AND cst."TGL_ACTIVE_CABANG" <=\'\''.$start_date.'\'\'
                                      AND (
                                        cst."TGL_INACTIVE_CABANG" <=\'\''.$end_date.'\'\'
                                        or cst."TGL_INACTIVE_CABANG" IS NULL
                                      ) 
                                      AND (
                                        chl."TGL_TUTUP" >= \'\''.$start_date.'\'\'
                                        AND chl."TGL_TUTUP" <=\'\''.$end_date.'\'\'
                                      ) 
                                      AND sdc."BRANCH_CODE" = \'\''.$branch_code.'\'\'
                                    GROUP BY 
                                      chl."STORE_CODE", 
                                      chl."TGL_TUTUP"
                                  ) asd 
                                GROUP BY 
                                  asd."STORE_CODE", 
                                  asd."TGL", 
                                  asd.rp
                              ) 
                            union all 
                             
                              (
                                (
                                  SELECT 
                                    asd."STORE_CODE" :: text, 
                                    extract(
                                      days 
                                      from 
                                        asd.TGL
                                    ) :: integer, 
                                    concat(\'\'PENDING HITUNG\'\',\'\';\'\', asd.rp) :: text AS STATUS 
                                  FROM 
                                    (
                                      SELECT 
                                        cmt."STORE_CODE", 
                                        ctr."SALES_DATE" AS TGL, 
                                        sum(
                                          (ctr."ACTUAL_SALES_AMOUNT") +(ctr."ACTUAL_RRAK_AMOUNT") +(
                                            ctr."ACTUAL_PAY_LESS_DEPOSITED"
                                          ) +(ctr."ACTUAL_VOUCHER_AMOUNT") +(ctr."ACTUAL_OTHERS_AMOUNT")
                                        ) AS rp 
                                      FROM 
                                        cdc_master_toko cmt, 
                                        cdc_trx_receipts ctr, 
                                        cdc_trx_batches ctb 
                                      WHERE 
                                        cmt."STORE_ID" = ctr."STORE_ID" 
                                        AND ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" 
                                        AND NOT EXISTS(
                                          SELECT 
                                            1 
                                          FROM 
                                            cdc_master_slp cms 
                                            JOIN cdc_master_toko cmt ON cmt."STORE_CODE" = cms."STORE_CODE" 
                                          WHERE 
                                            (
                                              cms."SALES_DATE" >= \'\''.$start_date.'\'\' 
                                              AND cms."SALES_DATE" <=\'\''.$end_date.'\'\' 
                                              AND cmt."STORE_ID" = ctr."STORE_ID"
                                            ) 
                                            AND cms."BRANCH_CODE" = \'\''.$branch_code.'\'\' 
                                            AND cmt."BRANCH_ID" = (
                                              SELECT 
                                                "BRANCH_ID" 
                                              FROM 
                                                cdc_master_branch 
                                              WHERE 
                                                "BRANCH_CODE" =\'\''.$branch_code.'\'\' 
                                            ) 
                                          GROUP BY 
                                            cmt."STORE_ID"
                                        ) 
                                        AND ctr."CDC_BATCH_ID" IS NOT NULL 
                                        AND (
                                          ctr."SALES_DATE" >= \'\''.$start_date.'\'\' 
                                          AND ctr."SALES_DATE" <= \'\''.$end_date.'\'\' 
                                        ) 
                                        AND ctr."BRANCH_CODE" = \'\''.$branch_code.'\'\' 
                                        AND ctr."ACTUAL_SALES_FLAG" = \'\'Y\'\' 
                                        AND (
                                          ctr."STN_FLAG" = \'\'N\'\' 
                                          AND ctb."CDC_DEPOSIT_ID" IS NULL
                                        ) 
                                      GROUP BY 
                                        cmt."STORE_CODE", 
                                        ctr."SALES_DATE"
                                    ) asd 
                                  GROUP BY 
                                    asd."STORE_CODE", 
                                    asd.TGL, 
                                    asd.rp
                                ) 
                                UNION ALL 
                                  (
                                    (
                                      SELECT 
                                        asd."STORE_CODE" :: text, 
                                        extract(
                                          day 
                                          from 
                                            asd."SALES_DATE"
                                        ) :: integer AS TGL, 
                                        CONCAT(\'\'PENDING HITUNG\'\',\'\';\'\', asd.rp) :: text AS STATUS 
                                      FROM 
                                        (
                                          SELECT 
                                            cms."STORE_CODE", 
                                            cms."SALES_DATE", 
                                            sum(cms."SALES_AMOUNT") as rp 
                                          FROM 
                                            cdc_master_slp cms 
                                            LEFT JOIN cdc_handheld_table cht ON cms."STORE_CODE" = cht."STORE_CODE" 
                                          WHERE 
                                            cms."SALES_DATE" >= \'\''.$start_date.'\'\'  
                                            AND cms."SALES_DATE" <= \'\''.$start_date.'\'\' 
                                            AND (
                                              cht."SCAN_DATE" >= TO_DATE (\'\''.$start_date.'\'\' , \'\'YYYY-MM-DD\'\') -3 
                                              AND cht."SCAN_DATE" <= TO_DATE (\'\''.$end_date.'\'\' , \'\'YYYY-MM-DD\'\') + 3
                                            ) 
                                            AND cms."BRANCH_CODE" =\'\''.$branch_code.'\'\'  
                                            AND NOT EXISTS (
                                              SELECT 
                                                1 
                                              FROM 
                                                cdc_trx_receipts ctr, 
                                                cdc_master_toko cmt 
                                              WHERE 
                                                ctr."STORE_ID" = cmt."STORE_ID" 
                                                AND (
                                                  ctr."SALES_DATE" >=\'\''.$start_date.'\'\'  
                                                  AND ctr."SALES_DATE" <= \'\''.$end_date.'\'\' 
                                                ) 
                                                AND ctr."BRANCH_CODE" = \'\''.$branch_code.'\'\' 
                                                AND ctr."ACTUAL_SALES_FLAG" = \'\'Y\'\'
                                            ) 
                                          GROUP BY 
                                            cms."STORE_CODE", 
                                            cms."SALES_DATE"
                                        ) asd 
                                      GROUP BY 
                                        asd."STORE_CODE", 
                                        asd."SALES_DATE", 
                                        asd.rp
                                    )
                                  )
                              )
                              ) asdf1 
                        order by "STORE_CODE" ASC,1,2      
                                                                 \'
                                                        ,\'select generate_series('.trim($tglawal).','.$tglakhir.')::int\'

                                                        ) as ("STORE_CODE" text,'.$day.')';
                         
           
            $result = $this->db->query($statement)->result();
           // print_r($result);
            return $result;
         
        }

        


        }
       
        //return $result;

    

        

    public function rekap_pending_setor($branch_id,$tglawal,$tglakhir,$sort_by,$jumlah_toko){
        if($sort_by=='QAsc'){
          $sort_by=' SUM(asd.QTY) ASC';
        }else if($sort_by=='QDesc'){
          $sort_by=' SUM(asd.QTY) DESC';
        }else if($sort_by=='RDesc'){
          $sort_by=' SUM(asd.TOTAL) DESC';
        }else if($sort_by=='RAsc'){
          $sort_by=' SUM(asd.TOTAL) ASC';
        }
        if($branch_id=='100'){
            $statement='select asd."BRANCH_CODE",asd."STORE_CODE",asd."STORE_NAME",SUM(asd.TOTAL) TOTAL,SUM(asd.QTY) QTY from (SELECT
                         cms."BRANCH_CODE",
                         cmt."STORE_CODE" ,
                         cmt."STORE_NAME",
                         sum (cms."SALES_AMOUNT") AS TOTAL  ,
                         count(cmt."STORE_ID") AS QTY           
                      FROM  cdc_master_slp cms, 
                            cdc_master_toko cmt,
                            cdc_stores cs 
                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                             AND cms."SALES_DATE" >= ?
                             AND cms."SALES_DATE" <= ?
                             AND ((cs."TGL_INACTIVE_CABANG"<? AND cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                             AND  NOT EXISTS(SELECT 1
                                                          FROM   cdc_handheld_table cht 
                                                WHERE  ( cht."SCAN_DATE" >= 
                                                         To_date( 
                                                         ?, 
                                                         \'YYYY-MM-DD\') 
                                                         - 3 
                                                         AND cht."SCAN_DATE" <= 
                                                             To_date( ?, 
                                                             \'YYYY-MM-DD\') + 3 )
                                                        AND "STORE_CODE"=cmt."STORE_CODE"
                                                     
                            )             
                            AND NOT EXISTS (SELECT 1
                                             FROM   cdc_trx_receipts ctr 
                                             WHERE  ( ctr."SALES_DATE" >=? 
                                                      AND ctr."SALES_DATE" <= ? ) 
                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                        
                            ) 
                          GROUP BY
                             cms."BRANCH_CODE",
                             cmt."STORE_CODE" ,
                             cmt."STORE_NAME",
                             cms."SALES_DATE") asd
                        GROUP BY asd."BRANCH_CODE",asd."STORE_NAME",asd."STORE_CODE"
                        ORDER BY '.$sort_by .'LIMIT '.$jumlah_toko;
            $result=$this->db->query($statement,array($tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir))->result();

        }else
        {
            $statement='select asd."BRANCH_CODE",asd."STORE_CODE",asd."STORE_NAME",SUM(asd.TOTAL) TOTAL,SUM(asd.QTY) QTY from (SELECT
                         cms."BRANCH_CODE",
                         cmt."STORE_CODE" ,
                         cmt."STORE_NAME",
                         sum (cms."SALES_AMOUNT") AS TOTAL  ,
                         count(cmt."STORE_ID") AS QTY           
                      FROM  cdc_master_slp cms, 
                            cdc_master_toko cmt,
                            cdc_stores cs 
                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                             AND cms."SALES_DATE" >= ?
                             AND cms."SALES_DATE" <= ?
                             AND ((cs."TGL_INACTIVE_CABANG"<? AND cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                             AND cmt."BRANCH_ID"=?
                             AND  NOT EXISTS(SELECT 1
                                                          FROM   cdc_handheld_table cht 
                                                WHERE  ( cht."SCAN_DATE" >= 
                                                         To_date( 
                                                         ?, 
                                                         \'YYYY-MM-DD\') 
                                                         - 3 
                                                         AND cht."SCAN_DATE" <= 
                                                             To_date( ?, 
                                                             \'YYYY-MM-DD\') + 3 )
                                                       AND "STORE_CODE"=cmt."STORE_CODE"
                            )             
                            AND NOT EXISTS (SELECT 1
                                             FROM   cdc_trx_receipts ctr 
                                             WHERE  ( ctr."SALES_DATE" >=? 
                                                      AND ctr."SALES_DATE" <= ? ) 
                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                                                    AND ctr."BRANCH_CODE"=(SELECT 
                                                           "BRANCH_CODE" 
                                                                       FROM 
                                                           cdc_master_branch 
                                                                       WHERE 
                                                           "BRANCH_ID" =?
                                                     )
                        
                            ) 
                          GROUP BY
                             cms."BRANCH_CODE",
                             cmt."STORE_CODE" ,
                             cmt."STORE_NAME",
                             cms."SALES_DATE") asd
                        GROUP BY asd."BRANCH_CODE",asd."STORE_NAME",asd."STORE_CODE"
                         ORDER BY '.$sort_by .' LIMIT '.$jumlah_toko;
            $result=$this->db->query($statement,array($tglawal,$tglakhir,$tglawal,$tglakhir,$branch_id,$tglawal,$tglakhir,$tglawal,$tglakhir,$branch_id))->result();
        }
            return $result;
    }


    public function insertTemp($region,$cabang,$store_code,$tgl_sales,$status)
    {
        // $statement='insert into monitoring_sales_tmp (REGION,BRANCH_ALT,STORE_CODE,SALES_DATE,STATUS) VALUES(?,?,?,?,?)';
        // $this->db->query($statement,array($region,$cabang,$store_code,$tgl_sales,$status));
        // return $this->db->affected_rows();
    }
     public function getDepositDate($branch_code,$branch_id,$tglawal,$tglakhir)
    {
      $statement='SELECT
                     (select btrim(cmt."STORE_CODE") FROM cdc_master_toko cmt where cmt."STORE_ID"=ctr."STORE_ID") AS STORE_CODE,
                      ctr."SALES_DATE" as sales_date,
                      ctd."DEPOSIT_DATE" as deposit_date,
                      (select cmb."FINREG"  from cdc_master_branch cmb where cmb."BRANCH_ID"=ctb."CDC_BRANCH_ID") AS server
                     
                  FROM
                     cdc_trx_receipts ctr,
                     cdc_trx_batches ctb,
                     cdc_trx_deposit ctd 
                  WHERE
                     ctr."CDC_BATCH_ID" = ctb."CDC_BATCH_ID" 
                     AND ctb."CDC_DEPOSIT_ID" = ctd."CDC_DEPOSIT_ID" 
                     AND ctr."CDC_BATCH_ID" IS NOT NULL 
                     AND 
                     (
                        ctr."SALES_DATE" >= ?
                        AND ctr."SALES_DATE" <= ?
                     )
                     and ctb."CDC_BRANCH_ID"=?
                     AND ctr."ACTUAL_SALES_FLAG" = \'Y\'
                     and ctr."BRANCH_CODE"=?
                     AND ctb."CDC_DEPOSIT_ID" IS NOT NULL 
                 
                 ';

      return $this->db->query($statement,array($tglawal,$tglakhir,$branch_id,$branch_code))->result();

    

  
    }

    public function getNIK($usr_id){
      $statement = 'SELECT "NIK" FROM SYS_USER_2 where "USER_ID" = ?';

      return $this->db->query($statement,array($usr_id))->result();
    }
     
    public function mps_header($branch_code)
    {
      if($branch_code=='000'){
        $statement='select cmb."REGION" AS REGION ,cmb."BRANCH_NAME" AS BRANCH_NAME,cmb."BRANCH_CODE" AS BRANCH_CODE, cmb."BRANCH_ALT" AS BRANCH_ALT,cmt."STORE_CODE" AS STORE_CODE,cmb."BRANCH_ID" FROM cdc_master_branch cmb,cdc_master_toko cmt where cmb."BRANCH_ID"=cmt."BRANCH_ID" ORDER BY cmb."BRANCH_ID"';

      return $this->db->query($statement)->result();
      }else{
        $statement='select cmb."REGION" AS REGION ,cmb."BRANCH_CODE" AS BRANCH_CODE, cmb."BRANCH_ALT" AS BRANCH_ALT,cmt."STORE_CODE" AS STORE_CODE,cmb."BRANCH_ID" AS BRANCH_ID FROM cdc_master_branch cmb,cdc_master_toko cmt where cmb."BRANCH_ID"=cmt."BRANCH_ID" and cmb."BRANCH_CODE"=?';

        return $this->db->query($statement,array($branch_code))->result();
      }
      
    }

    public function mps_header2($branch_code)
    {
      if($branch_code=='000'){
        $statement='select cmb."REGION" AS REGION ,cmb."BRANCH_NAME" AS BRANCH_NAME,btrim(cmb."BRANCH_CODE") AS BRANCH_CODE, cmb."BRANCH_ALT" AS BRANCH_ALT,cmb."BRANCH_ID" AS BRANCH_ID FROM cdc_master_branch cmb  where "BRANCH_CODE"!=\'001\' ORDER BY  cast(SUBSTRING(cmb."REGION",2,2) as int) ,cmb."BRANCH_CODE" ASC';

      return $this->db->query($statement)->result();
      }else{
            $statement='select cmb."REGION" AS REGION ,btrim(cmb."BRANCH_CODE") AS BRANCH_CODE, cmb."BRANCH_ALT" AS BRANCH_ALT,cmb."BRANCH_ID" AS BRANCH_ID FROM cdc_master_branch cmb  where "BRANCH_CODE"!=\'001\' and "BRANCH_CODE"=?  ORDER BY "BRANCH_ID" asc';

             return $this->db->query($statement,$branch_code)->result();

        }
      
    }
    public function loop_cabang($branch_code,$tglawal,$tglakhir,$sort_by)
    { if($sort_by=='QAsc'){
          $sort_by=' sum(asd.qty) ASC';
          $sort_2='asd.qty';
        }else if($sort_by=='QDesc'){
          $sort_by=' sum(asd.qty) DESC';
           $sort_2='asd.qty';
        }else if($sort_by=='RDesc'){
          $sort_by=' sum(asd.rp) DESC';
           $sort_2='asd.rp';
        }else if($sort_by=='RAsc'){
          $sort_by=' sum(asd.rp) ASC';
           $sort_2='asd.rp';
        }
      if($branch_code=='000'){
        $statement='select asd."BRANCH_CODE" as BRANCH_CODE,asd."REGION" AS REGION,asd."BRANCH_ID" AS BRANCH_ID,asd."BRANCH_ALT" AS BRANCH_ALT,asd."BRANCH_NAME" AS BRANCH_NAME from (SELECT
                      count(cmt."STORE_ID") as qty, sum(cms."SALES_AMOUNT") as rp,cmb."REGION" AS "REGION" ,cmb."BRANCH_NAME" AS "BRANCH_NAME",btrim(cmb."BRANCH_CODE") AS "BRANCH_CODE", cmb."BRANCH_ALT" AS "BRANCH_ALT",cmb."BRANCH_ID" AS "BRANCH_ID", cmt."STORE_CODE" , cmt."STORE_NAME"           
                      FROM  cdc_master_slp cms, 
                            cdc_master_toko cmt,
                            cdc_stores cs ,
                            cdc_master_branch cmb
                      WHERE  cms."STORE_CODE" = cmt."STORE_CODE"
                       AND cmt."STORE_CODE"=cs."STORE_CODE"
                       AND cms."BRANCH_CODE"=cs."BRANCH_CODE"
                       AND cmb."BRANCH_ID"=cmt."BRANCH_ID"
                             AND cms."SALES_DATE" >= ?
                             AND cms."SALES_DATE" <= ?
                             AND ((cs."TGL_INACTIVE_CABANG"<? AND cs."TGL_INACTIVE_CABANG">?) or cs."TGL_INACTIVE_CABANG" IS NULL)
                             and cms."SALES_AMOUNT"!=0
                             AND  NOT EXISTS(SELECT 1
                                                          FROM   cdc_handheld_table cht 
                                                WHERE  ( cht."SCAN_DATE" >= 
                                                         To_date( 
                                                         ?, 
                                                         \'YYYY-MM-DD\') 
                                                         - 3 
                                                         AND cht."SCAN_DATE" <= 
                                                             To_date( ?, 
                                                             \'YYYY-MM-DD\') + 3 )
                                                        AND "STORE_CODE"=cmt."STORE_CODE"
                            )             
                            AND NOT EXISTS (SELECT 1
                                             FROM   cdc_trx_receipts ctr 
                                             WHERE  ( ctr."SALES_DATE" >=? 
                                                      AND ctr."SALES_DATE" <= ? ) 
                                                    AND ctr."BRANCH_CODE" =cms."BRANCH_CODE"
                                                    AND ctr."STORE_ID"=cmt."STORE_ID"
                        
                            ) 
                        GROUP BY cms."BRANCH_CODE", cmt."STORE_CODE" , cmt."STORE_NAME", cms."SALES_DATE",cmb."BRANCH_ID",cmb."REGION",cmb."BRANCH_NAME"
                        ) asd
                        GROUP BY asd."BRANCH_CODE",asd."REGION",asd."BRANCH_ID",asd."BRANCH_ALT",asd."BRANCH_NAME"'.' order by  '.$sort_by;
         $result=$this->db->query($statement,array($tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir,$tglawal,$tglakhir))->result();
         return $result;
      }else{
            $statement='select cmb."REGION" AS REGION ,btrim(cmb."BRANCH_CODE") AS BRANCH_CODE, cmb."BRANCH_ALT" AS BRANCH_ALT,cmb."BRANCH_ID" AS BRANCH_ID FROM cdc_master_branch cmb  where "BRANCH_CODE"!=\'001\' and "BRANCH_CODE"=?  ORDER BY "BRANCH_ID" asc';

             return $this->db->query($statement,$branch_code)->result();

        }
      
    }
    public function choose_dc($branch_id)
    {
      $statement = 'SELECT \'ALL\' "DC_CODE", \'ALL\' "DC_NAME", \'ALL\' "DC_VALUE" UNION ALL
      SELECT DC."DC_CODE", DC."DC_NAME", DC."DC_CODE"||\'-\'||DC."DC_NAME" "DC_VALUE" FROM SYS_MAP_DC DC, CDC_MASTER_BRANCH CMB WHERE DC."BRANCH_CODE" = BTRIM(CMB."BRANCH_CODE") AND CMB."BRANCH_ID" = ?';
      
      if ($this->session->userdata('dc_type') != 'DCI' && $this->session->userdata('role_id') < 5) {
        $statement .= ' AND DC."DC_CODE" = \''.$this->session->userdata('dc_code').'\'';
      }

      $statement .= ' ORDER BY "DC_CODE"';
      
      return $this->db->query($statement,$branch_id)->result();
    }

    public function choose_dc_user($branch_id)
    {
      $statement = 'SELECT DC."DC_CODE", DC."DC_NAME", DC."DC_CODE"||\'-\'||DC."DC_NAME" "DC_VALUE" FROM SYS_MAP_DC DC, CDC_MASTER_BRANCH CMB WHERE DC."BRANCH_CODE" = BTRIM(CMB."BRANCH_CODE") AND CMB."BRANCH_ID" = ?';

      $statement .= ' ORDER BY "DC_CODE"';
      
      return $this->db->query($statement,$branch_id)->result();
    }

    public function choose_store_mtr($branch_id)
    {
      $statement = 'SELECT 0 "STORE_ID", \'ALL\' "STORE" UNION ALL SELECT "STORE_ID", "STORE_CODE"||\' - \'||"STORE_NAME" "STORE" FROM cdc_master_toko WHERE "BRANCH_ID" = ? ORDER BY "STORE"';
      return $this->db->query($statement, array($branch_id))->result();
    }

    public function choose_role()
    {
      $statement = 'SELECT * FROM SYS_ROLE WHERE "ROLE_ID" NOT IN (2,5) ORDER BY "ROLE_ID"';
      return $this->db->query($statement)->result();  
    }

     public function choose_am($branch_code)
    {
      $statement = 'SELECT \'0\' "AM_NUMBER",\'ALL\' "AM" UNION ALL SELECT "AM_NUMBER","AM_NUMBER" || \'-\' || "AM_NAME" as "AM" FROM cdc_master_am_as WHERE "BRANCH_CODE" = ? GROUP BY "AM_NUMBER", "AM_NUMBER" || \'-\' || "AM_NAME"';
      return $this->db->query($statement,array($branch_code))->result();  
    }

    public function get_pemegang_shift($store_code,$tgl,$shift){
    
    $return_val = '';
    
    $check = 'SELECT count(*) as "TOTAL_DATA" FROM cdc_master_pemegang_shift WHERE "KODE_TOKO" = ? AND "TGL_SALES" = ? AND "SHIFT" = ?';
    $counter_check = $this->db->query($check,array($store_code,$tgl,$shift))->row()->TOTAL_DATA;
    
//    echo 'COUNTER MOD '.$counter_check.'<br>';
    
    if ($counter_check > 0)
    {
      $statement = 'SELECT coalesce("NIK_SHIFT" || \'-\' || "NAMA_SHIFT",\'-\') AS "PEMEGANG_SHIFT" FROM cdc_master_pemegang_shift WHERE "KODE_TOKO" = ? AND "TGL_SALES" = ? AND "SHIFT" = ? ORDER BY "ID_JABATAN" desc LIMIT 1';

        $return_val = $this->db->query($statement,array($store_code,$tgl,$shift))->row()->PEMEGANG_SHIFT; 
    }
    else
    {
      $return_val= '-';
    }
    
//    echo '<br> RETURN :'.$return_val;
    
    return $return_val;
      
    }

      public function get_am_data($am,$branch_code){
      if($am == 0){
        $statement = 'SELECT "AM_NUMBER","AM_NAME" FROM cdc_master_am_as WHERE "BRANCH_CODE" = ? GROUP BY "AM_NUMBER","AM_NAME","AM_SHORT" ORDER BY "AM_SHORT"';
        $result = $this->db->query($statement,array($branch_code));
      }
      else{
        $statement = 'SELECT "AM_NUMBER","AM_NAME" FROM cdc_master_am_as WHERE "AM_NUMBER" = ? AND "BRANCH_CODE" = ? GROUP BY "AM_NUMBER","AM_NAME"';
        $result = $this->db->query($statement,array($am,$branch_code));
      }

      return $result->result();
    }
        public function rekap_penerimaan_sales_toko($branch_id,$start_date,$end_date){
        if($branch_id=='100'){
            $statement='SELECT asd."REGION",asd."BRANCH_ID",cmb."BRANCH_ALT",asd."STORE_CODE",asd."STORE_NAME",asd."PAYMENT_POINT",asd."SALES",asd."KURSET" FROM
                        (SELECT (select cmb."REGION" FROM cdc_master_branch cmb where cmb."BRANCH_ID"=cmt."BRANCH_ID") AS "REGION","BRANCH_ID",cmt."STORE_CODE",cmt."STORE_NAME", \' \' AS "PAYMENT_POINT",
                        (sum("ACTUAL_SALES_AMOUNT")+sum("ACTUAL_LOST_ITEM_PAYMENT")+sum("ACTUAL_WU_ACCOUNTABILITY")+sum("ACTUAL_VIRTUAL_PAY_LESS")
                        +sum("ACTUAL_RRAK_AMOUNT")+sum("ACTUAL_PAY_LESS_DEPOSITED")+sum("ACTUAL_VOUCHER_AMOUNT")+sum("ACTUAL_OTHERS_AMOUNT")) AS "SALES",
                         (sum("RRAK_DEDUCTION")+sum("LESS_DEPOSIT_DEDUCTION")+sum("OTHERS_DEDUCTION")+sum("VIRTUAL_PAY_LESS_DEDUCTION")) AS "KURSET"
                         FROM cdc_trx_receipts_shift ctrs,cdc_master_toko cmt  where ctrs."STORE_ID"=cmt."STORE_ID"
                         AND ("SALES_DATE">=? AND "SALES_DATE"<=?)group by "BRANCH_ID","STORE_CODE","STORE_NAME") asd,cdc_master_branch cmb
                         where asd."BRANCH_ID"=cmb."BRANCH_ID"
                         ORDER BY  cast(SUBSTRING(asd."REGION",2,2) as int) ASC,asd."BRANCH_ID" ASC,asd."STORE_CODE" asc';

            $result = $this->db->query($statement,array($start_date,$end_date))->result();

        }else{
             $statement='SELECT asd."REGION",asd."BRANCH_ID",cmb."BRANCH_ALT",asd."STORE_CODE",asd."STORE_NAME",asd."PAYMENT_POINT",asd."SALES",asd."KURSET" FROM
                        (SELECT (select cmb."REGION" FROM cdc_master_branch cmb where cmb."BRANCH_ID"=cmt."BRANCH_ID") AS "REGION","BRANCH_ID",cmt."STORE_CODE",cmt."STORE_NAME", \' \' AS "PAYMENT_POINT",
                        (sum("ACTUAL_SALES_AMOUNT")+sum("ACTUAL_LOST_ITEM_PAYMENT")+sum("ACTUAL_WU_ACCOUNTABILITY")+sum("ACTUAL_VIRTUAL_PAY_LESS")
                        +sum("ACTUAL_RRAK_AMOUNT")+sum("ACTUAL_PAY_LESS_DEPOSITED")+sum("ACTUAL_VOUCHER_AMOUNT")+sum("ACTUAL_OTHERS_AMOUNT")) AS "SALES",
                         (sum("RRAK_DEDUCTION")+sum("LESS_DEPOSIT_DEDUCTION")+sum("OTHERS_DEDUCTION")+sum("VIRTUAL_PAY_LESS_DEDUCTION")) AS "KURSET"
                         FROM cdc_trx_receipts_shift ctrs,cdc_master_toko cmt  where ctrs."STORE_ID"=cmt."STORE_ID"  
                         AND cmt."BRANCH_ID"=?
                         AND ("SALES_DATE">=? AND "SALES_DATE"<=?)group by "BRANCH_ID","STORE_CODE","STORE_NAME") asd,cdc_master_branch cmb
                         where asd."BRANCH_ID"=cmb."BRANCH_ID" AND cmb."BRANCH_ID"=?
                            ORDER BY  cast(SUBSTRING(asd."REGION",2,2) as int) ASC,asd."BRANCH_ID" ASC,asd."STORE_CODE" asc';

            $result = $this->db->query($statement,array($branch_id,$start_date,$end_date,$branch_id))->result();
        }
        return $result;
    }

    public function get_data_absensi_denom_toko_idm_toko($start_periode,$end_periode,$branch_code,$branch_id,$kode_toko){
      if($branch_id != '100'){
        $whereKodetoko = '';
        if($kode_toko != '0000'){
          $whereKodetoko = ' AND cmt."STORE_CODE" = \''.$kode_toko.'\''; 
        }

        $statement='select asd."STORE_ID",asd."STORE_CODE",asd."STORE_NAME",asd."BRANCH_CODE", case when asd."TIPE_INPUTAN" is not null then asd."TIPE_INPUTAN" when asd."TIPE_INPUTAN" is null then \'Harian\' end "TIPE_INPUTAN" from(SELECT
            cmt."STORE_ID" ,
            cmt."STORE_CODE",
            cmt."STORE_NAME",
            cs."BRANCH_CODE",
            case
	          	when (
	          	select
	          		cms."TIPE_SHIFT"
	          	from
	          		cdc_master_shift cms
	          	where
	          		cms."STATUS" = \'A\'
	          		and cmt."STORE_CODE" = cms."STORE_CODE") = \'SS\' then \'Sales-Shift\'
	          	when (
	          	select
	          		cms."TIPE_SHIFT"
	          	from
	          		cdc_master_shift cms
	          	where
	          		cms."STATUS" = \'A\'
	          		and cmt."STORE_CODE" = cms."STORE_CODE") = \'HS\' then \'Harian-Shift\'
	          	when (
	          	select
	          		cms."TIPE_SHIFT"
	          	from
	          		cdc_master_shift cms
	          	where
	          		cms."STATUS" = \'A\'
	          		and cmt."STORE_CODE" = cms."STORE_CODE") = \'H\' then \'Harian\'
	          end "TIPE_INPUTAN"   
          from
            cdc_master_toko cmt ,
            cdc_stores cs
          where
            cmt."STORE_CODE" = cs."STORE_CODE"
            and cs."BRANCH_CODE" = ?
            '.$whereKodetoko.'
            and (cmt."INACTIVE_DATE" is null
              or cmt."INACTIVE_DATE" >= ? )
            and (cs."INACTIVE_DATE" is null
              or cs."INACTIVE_DATE" >= ?)) asd order by asd."STORE_CODE" ASC';
        $result = $this->db->query($statement,array($branch_code,$start_periode,$start_periode))->result();
      }else{
        $whereKodetoko = '';
        if($kode_toko != '0000'){
          $whereKodetoko = ' AND cmt."STORE_CODE" = \''.$kode_toko.'\''; 
        }
        $statement='*select asd."STORE_ID",asd."STORE_CODE",asd."STORE_NAME",asd."BRANCH_CODE", case when asd."TIPE_INPUTAN" is not null then asd."TIPE_INPUTAN" when asd."TIPE_INPUTAN" is null then \'Harian\' end "TIPE_INPUTAN" from(SELECT
        cmt."STORE_ID" ,
        cmt."STORE_CODE",
        cmt."STORE_NAME",
        cs."BRANCH_CODE",
        case
          when (
          select
            cms."TIPE_SHIFT"
          from
            cdc_master_shift cms
          where
            cms."STATUS" = \'A\'
            and cmt."STORE_CODE" = cms."STORE_CODE") = \'SS\' then \'Sales-Shift\'
          when (
          select
            cms."TIPE_SHIFT"
          from
            cdc_master_shift cms
          where
            cms."STATUS" = \'A\'
            and cmt."STORE_CODE" = cms."STORE_CODE") = \'HS\' then \'Harian-Shift\'
          when (
          select
            cms."TIPE_SHIFT"
          from
            cdc_master_shift cms
          where
            cms."STATUS" = \'A\'
            and cmt."STORE_CODE" = cms."STORE_CODE") = \'H\' then \'Harian\'
        end "TIPE_INPUTAN"   
      from
        cdc_master_toko cmt ,
        cdc_stores cs
      where
        cmt."STORE_CODE" = cs."STORE_CODE"
        '.$whereKodetoko.'
        and (cmt."INACTIVE_DATE" is null
          or cmt."INACTIVE_DATE" >= ? )
        and (cs."INACTIVE_DATE" is null
          or cs."INACTIVE_DATE" >= ?)) asd order by asd."STORE_CODE" ASC';
        $result = $this->db->query($statement,array($start_periode,$start_periode))->result();
      }
      return $result;
    }

    public function  get_data_shift_per_tanggal($sales_date,$store_code){
      $statement = 'select *
    from
      (
      select
        SUM(case when asd."SHIFT" = \'1\' then 1 else 0 end) "SHIFT_1",
        SUM(case when asd."SHIFT" = \'2\' then 1 else 0 end) "SHIFT_2",
        SUM(case when asd."SHIFT" = \'3\' then 1 else 0 end) "SHIFT_3"
      from
        (
        select
          cdspd."SHIFT"
        from
          cdc_data_sales_per_denom cdspd
        where
          cdspd."SALES_DATE" = ?
          and cdspd."STORE_CODE" = ?) asd) bcd';

      $result = $this->db->query($statement,array($sales_date,$store_code))->result();

      return $result;
    }
    public function get_data_monitoring_sales_fisik($start_periode,$end_periode,$branch_code,$branch_id,$kode_toko,$tipe_setoran,$selisih){
      if($branch_id != '100'){
        $whereKodetoko = '';
        if($kode_toko != '0000'){
          $whereKodetoko = ' AND cmt."STORE_CODE" = \''.$kode_toko.'\''; 
        }
        $whereSelisih = '';
        if($selisih == 'All'){
          $whereSelisih = '';
        }else if($selisih == 'Match'){
          $whereSelisih = ' WHERE bcd."SELISIH" = 0 ';
        }else if($selisih == 'Selisih'){
          $whereSelisih = ' WHERE bcd."SELISIH" > 0 ';
        }else if($selisih == 'Selisih > 1000'){
          $whereSelisih = ' WHERE bcd."SELISIH" > 1000 ';
        }

        $whereMetodeSetoranDana = '';
        if($tipe_setoran == 'STJ'){
          if($whereSelisih == '' ){
            $whereMetodeSetoranDana = ' WHERE bcd."METODE_SETORAN_DANA" = \'STJ\' ';

          }else{
            $whereMetodeSetoranDana = ' AND bcd."METODE_SETORAN_DANA" = \'STJ\' ';
          }
        }else if($tipe_setoran == 'STN'){
          if($whereSelisih == ''){
            $whereMetodeSetoranDana = ' WHERE bcd."METODE_SETORAN_DANA" = \'STN\' ';
          }else{
            $whereMetodeSetoranDana = ' AND bcd."METODE_SETORAN_DANA" = \'STN\' ';
          }
        }

        $statement='SELECT *,ABS((select coalesce("TOTAL_SALES_AMOUNT",0) - case when "SHIFT_FLAG" = \'N\' then "HARIAN" else (coalesce("1", 0)+ coalesce("2", 0)+ coalesce("3", 0)) end)) "SELISIH" 
        from (SELECT asd."STORE_ID",asd."BRANCH_CODE",asd."AM",asd."AS",asd."STORE_CODE",asd."STORE_NAME",asd."SALES_DATE",	asd."SHIFT_FLAG",asd."HARIAN",asd."1",asd."2",asd."3",
        (CASE WHEN asd."SHIFT_FLAG" = \'N\' THEN \'HARIAN\' else \'SHIFT\' END) "TIPE_INPUTAN",
        (CASE WHEN asd."SHIFT_FLAG" = \'N\' THEN asd."HARIAN" else (coalesce(asd."1",0)+coalesce(asd."2",0)+coalesce(asd."3",0)) END) "TOTAL_SALES",
        (case
          when (
          select
            count(*)
          from
            cdc_data_sales_per_denom cdspd
          where
            cdspd."STORE_CODE" = asd."STORE_CODE"
            and cdspd."SALES_DATE" = asd."SALES_DATE"
            and cdspd."SHIFT" = \'H\') = 0 then (
        select
          SUM("SALES_AMOUNT")
        from
          cdc_data_sales_per_denom cdspd
        where
          cdspd."STORE_CODE" = asd."STORE_CODE"
          and cdspd."SALES_DATE" = asd."SALES_DATE" and cdspd."SHIFT" != \'H\')
        else (
        select
          SUM("SALES_AMOUNT")
        from
          cdc_data_sales_per_denom cdspd
        where
          cdspd."STORE_CODE" = asd."STORE_CODE"
          and cdspd."SALES_DATE" = asd."SALES_DATE" and cdspd."SHIFT" = \'H\')
        end) "TOTAL_SALES_AMOUNT",
        (CASE WHEN coalesce(asd."STN1",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN1", coalesce(asd."1",0) "SHIFT1", asd."CREATE_DATE1" "CREATE_DATE_SHIFT1",
        (CASE WHEN coalesce(asd."STN2",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN2", coalesce(asd."2",0) "SHIFT2", asd."CREATE_DATE2" "CREATE_DATE_SHIFT2",
        (CASE WHEN coalesce(asd."STN3",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN3", coalesce(asd."3",0) "SHIFT3", asd."CREATE_DATE3" "CREATE_DATE_SHIFT3",
        (case when coalesce(asd."HARIAN_STN", \'-\')= \'N\' then \'STJ\' when coalesce(asd."HARIAN_STN", \'-\')= \'Y\' then \'STN\' end) "TIPE_SETORAN_HARIAN",
        (case 
		    	when coalesce(asd."HARIAN_STN", \'-\') = \'-\' then (case
		    		when coalesce(asd."STN1", \'-\')= \'N\' then \'STJ\'
		    		when coalesce(asd."STN1", \'-\')= \'Y\' then \'STN\'
		    	end)
		    	else (case
		    	when coalesce(asd."HARIAN_STN", \'-\')= \'N\' then \'STJ\'
		    	when coalesce(asd."HARIAN_STN", \'-\')= \'Y\' then \'STN\'
		    end)
		    end
		    ) "METODE_SETORAN_DANA"
        from (
          SELECT cmt."STORE_ID",
                 cmt."STORE_CODE",
                 cmt."STORE_NAME",
                 cs."BRANCH_CODE",
                 (SELECT cmaa."AM_SHORT"
                    FROM cdc_master_am_as cmaa
                   WHERE     cmaa."BRANCH_CODE" = cs."BRANCH_CODE"
                         AND cmaa."STORE_CODE" = cmt."STORE_CODE")
                   as "AM",
                 (SELECT cmaa."AS_SHORT"
                    FROM cdc_master_am_as cmaa
                   WHERE     cmaa."BRANCH_CODE" = cs."BRANCH_CODE"
                         AND cmaa."STORE_CODE" = cmt."STORE_CODE")
                    AS "AS",
                 ctrs."SALES_DATE",
                 ctrs."SHIFT_FLAG",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."STN_FLAG" END) as "STN1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."CREATION_DATE" END)AS "CREATE_DATE1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."STN_FLAG" END) as "STN2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."STN_FLAG" END) as "STN3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."STN_FLAG" END) as "HARIAN_STN",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "HARIAN",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE4"
            FROM cdc_master_toko cmt, cdc_stores cs, cdc_trx_receipts_shift ctrs
           WHERE     cmt."STORE_CODE" = cs."STORE_CODE"
                 AND cmt."STORE_ID" = ctrs."STORE_ID"
                 AND cs."BRANCH_CODE" = ctrs."BRANCH_CODE"
                 AND (    ctrs."SALES_DATE" >= ?
                      AND ctrs."SALES_DATE" <= ?)
                 AND ctrs."ACTUAL_SALES_FLAG" = \'Y\'
                 AND cs."BRANCH_CODE" = ?
                 '.$whereKodetoko.'
                 AND (   cmt."INACTIVE_DATE" IS NULL
                      OR cmt."INACTIVE_DATE" >= ?)
                 AND (cs."INACTIVE_DATE" IS NULL OR cs."INACTIVE_DATE" >= ?)
        GROUP BY cmt."STORE_ID",
                 cmt."STORE_CODE",
                 cmt."STORE_NAME",
                 cs."BRANCH_CODE",
                 ctrs."STORE_ID",
                 ctrs."SALES_DATE",
                 ctrs."SHIFT_FLAG"
        ORDER BY cmt."STORE_ID", ctrs."SALES_DATE") asd)bcd'.$whereSelisih.$whereMetodeSetoranDana;
        $result = $this->db->query($statement,array($start_periode,$end_periode,$branch_code,$start_periode,$start_periode))->result();
      }else{
        $whereKodetoko = '';
        if($kode_toko != '0000'){
          $whereKodetoko = ' AND cmt."STORE_CODE" = \''.$kode_toko.'\''; 
        }
        $statement='SELECT *,ABS((select coalesce("TOTAL_SALES_AMOUNT",0) - case when "SHIFT_FLAG" = \'N\' then "HARIAN" else (coalesce("1", 0)+ coalesce("2", 0)+ coalesce("3", 0)) end)) "SELISIH" from (SELECT asd."STORE_ID",asd."BRANCH_CODE",asd."AM",asd."AS",asd."STORE_CODE",asd."STORE_NAME",asd."SALES_DATE",asd."SHIFT_FLAG",asd."HARIAN",asd."1",asd."2",asd."3",
        (CASE WHEN asd."SHIFT_FLAG" = \'N\' THEN \'HARIAN\' else \'SHIFT\' END) "TIPE_INPUTAN",
        (CASE WHEN asd."SHIFT_FLAG" = \'N\' THEN asd."HARIAN" else (coalesce(asd."1",0)+coalesce(asd."2",0)+coalesce(asd."3",0)) END) "TOTAL_SALES",
        (case
          when (
          select
            count(*)
          from
            cdc_data_sales_per_denom cdspd
          where
            cdspd."STORE_CODE" = asd."STORE_CODE"
            and cdspd."SALES_DATE" = asd."SALES_DATE"
            and cdspd."SHIFT" = \'H\') = 0 then (
        select
          SUM("SALES_AMOUNT")
        from
          cdc_data_sales_per_denom cdspd
        where
          cdspd."STORE_CODE" = asd."STORE_CODE"
          and cdspd."SALES_DATE" = asd."SALES_DATE" and cdspd."SHIFT" != \'H\')
        else (
        select
          SUM("SALES_AMOUNT")
        from
          cdc_data_sales_per_denom cdspd
        where
          cdspd."STORE_CODE" = asd."STORE_CODE"
          and cdspd."SALES_DATE" = asd."SALES_DATE" and cdspd."SHIFT" = \'H\')
        end) "TOTAL_SALES_AMOUNT",
        (select SUM("SALES_AMOUNT") from cdc_data_sales_per_denom cdspd where cdspd."STORE_CODE"=asd."STORE_CODE" and cdspd."SALES_DATE"=asd."SALES_DATE") "TOTAL_SALES_AMOUNT",
        (CASE WHEN coalesce(asd."STN1",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN1", coalesce(asd."1",0) "SHIFT1", asd."CREATE_DATE1" "CREATE_DATE_SHIFT1",
        (CASE WHEN coalesce(asd."STN2",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN2", coalesce(asd."2",0) "SHIFT2", asd."CREATE_DATE2" "CREATE_DATE_SHIFT2",
        (CASE WHEN coalesce(asd."STN3",\'-\')=\'N\' THEN \'STJ\' WHEN coalesce(asd."STN1",\'-\')=\'Y\' THEN \'STN\' END) "TIPE_SETORAN3", coalesce(asd."3",0) "SHIFT3", asd."CREATE_DATE3" "CREATE_DATE_SHIFT3",
        (case when coalesce(asd."HARIAN_STN", \'-\')= \'N\' then \'STJ\' when coalesce(asd."HARIAN_STN", \'-\')= \'Y\' then \'STN\' end) "TIPE_SETORAN_HARIAN",
        (case 
		    	when coalesce(asd."HARIAN_STN", \'-\') = \'-\' then (case
		    		when coalesce(asd."STN1", \'-\')= \'N\' then \'STJ\'
		    		when coalesce(asd."STN1", \'-\')= \'Y\' then \'STN\'
		    	end)
		    	else (case
		    	when coalesce(asd."HARIAN_STN", \'-\')= \'N\' then \'STJ\'
		    	when coalesce(asd."HARIAN_STN", \'-\')= \'Y\' then \'STN\'
		    end)
		    end
		    ) "METODE_SETORAN_DANA"
        from (
          SELECT cmt."STORE_ID",
                 cmt."STORE_CODE",
                 cmt."STORE_NAME",
                 cs."BRANCH_CODE",
                 (SELECT cmaa."AM_SHORT"
                    FROM cdc_master_am_as cmaa
                   WHERE     cmaa."BRANCH_CODE" = cs."BRANCH_CODE"
                         AND cmaa."STORE_CODE" = cmt."STORE_CODE")
                   as "AM",
                 (SELECT cmaa."AS_SHORT"
                    FROM cdc_master_am_as cmaa
                   WHERE     cmaa."BRANCH_CODE" = cs."BRANCH_CODE"
                         AND cmaa."STORE_CODE" = cmt."STORE_CODE")
                    AS "AS",
                 ctrs."SALES_DATE",
                 ctrs."SHIFT_FLAG",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."STN_FLAG" END) as "STN1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'1\' THEN ctrs."CREATION_DATE" END)AS "CREATE_DATE1",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."STN_FLAG" END) as "STN2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'2\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE2",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."STN_FLAG" END) as "STN3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'3\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE3",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."STN_FLAG" END) as "HARIAN_STN",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."ACTUAL_SALES_AMOUNT" END) AS "HARIAN",
                 MAX(CASE WHEN REPLACE(ctrs."NO_SHIFT", \'S-\', \'\') = \'H\' THEN ctrs."CREATION_DATE" END) AS "CREATE_DATE4"
            FROM cdc_master_toko cmt, cdc_stores cs, cdc_trx_receipts_shift ctrs
           WHERE     cmt."STORE_CODE" = cs."STORE_CODE"
                 AND cmt."STORE_ID" = ctrs."STORE_ID"
                 AND cs."BRANCH_CODE" = ctrs."BRANCH_CODE"
                 AND (    ctrs."SALES_DATE" >= ?
                      AND ctrs."SALES_DATE" <= ?)
                 AND ctrs."ACTUAL_SALES_FLAG" = \'Y\'
                 '.$whereKodetoko.'
                 AND (   cmt."INACTIVE_DATE" IS NULL
                      OR cmt."INACTIVE_DATE" >= ?)
                 AND (cs."INACTIVE_DATE" IS NULL OR cs."INACTIVE_DATE" >= ?)
        GROUP BY cmt."STORE_ID",
                 cmt."STORE_CODE",
                 cmt."STORE_NAME",
                 cs."BRANCH_CODE",
                 ctrs."STORE_ID",
                 ctrs."SALES_DATE",
                 ctrs."SHIFT_FLAG"
        ORDER BY cmt."STORE_ID", ctrs."SALES_DATE") asd)bcd'.$whereSelisih.$whereMetodeSetoranDana;
        $result = $this->db->query($statement,array($start_periode,$end_periode,$start_periode,$start_periode))->result();
      }
      return $result;
    }
    public function rekap_penerimaan_sales_cbg($branch_id,$start_date,$end_date){
        if($branch_id=='100'){
            $statement='SELECT asd."REGION",asd."BRANCH_ID",cmb."REGION" as "REGION",cmb."BRANCH_ALT" AS "BRANCH_ALT",asd."PAYMENT_POINT" AS "PAYMENT_POINT",asd."SALES" AS "SALES",asd."KURSET" AS "KURSET" FROM
                        (SELECT (select cmb."REGION" FROM cdc_master_branch cmb where cmb."BRANCH_ID"=cmt."BRANCH_ID") AS "REGION","BRANCH_ID", \' \' AS "PAYMENT_POINT",
                        (sum("ACTUAL_SALES_AMOUNT")+sum("ACTUAL_LOST_ITEM_PAYMENT")+sum("ACTUAL_WU_ACCOUNTABILITY")+sum("ACTUAL_VIRTUAL_PAY_LESS")
                        +sum("ACTUAL_RRAK_AMOUNT")+sum("ACTUAL_PAY_LESS_DEPOSITED")+sum("ACTUAL_VOUCHER_AMOUNT")+sum("ACTUAL_OTHERS_AMOUNT")) AS "SALES",
                         (sum("RRAK_DEDUCTION")+sum("LESS_DEPOSIT_DEDUCTION")+sum("OTHERS_DEDUCTION")+sum("VIRTUAL_PAY_LESS_DEDUCTION")) AS "KURSET"
                         FROM cdc_trx_receipts_shift ctrs,cdc_master_toko cmt  where ctrs."STORE_ID"=cmt."STORE_ID"  
                         AND ("SALES_DATE">=? AND "SALES_DATE"<=?)group by "BRANCH_ID") asd,cdc_master_branch cmb
                         where asd."BRANCH_ID"=cmb."BRANCH_ID"
                          ORDER BY  cast(SUBSTRING(asd."REGION",2,2) as int) ASC,asd."BRANCH_ID" ASC';

            $result = $this->db->query($statement,array($start_date,$end_date))->result();

        }else{
             $statement='SELECT asd."REGION",asd."BRANCH_ID",cmb."REGION",cmb."BRANCH_ALT",asd."PAYMENT_POINT",asd."SALES",asd."KURSET" FROM
                        (SELECT (select cmb."REGION" FROM cdc_master_branch cmb where cmb."BRANCH_ID"=cmt."BRANCH_ID") AS "REGION","BRANCH_ID", \' \' AS "PAYMENT_POINT",
                        (sum("ACTUAL_SALES_AMOUNT")+sum("ACTUAL_LOST_ITEM_PAYMENT")+sum("ACTUAL_WU_ACCOUNTABILITY")+sum("ACTUAL_VIRTUAL_PAY_LESS")
                        +sum("ACTUAL_RRAK_AMOUNT")+sum("ACTUAL_PAY_LESS_DEPOSITED")+sum("ACTUAL_VOUCHER_AMOUNT")+sum("ACTUAL_OTHERS_AMOUNT")) AS "SALES",
                         (sum("RRAK_DEDUCTION")+sum("LESS_DEPOSIT_DEDUCTION")+sum("OTHERS_DEDUCTION")+sum("VIRTUAL_PAY_LESS_DEDUCTION")) AS "KURSET"
                         FROM cdc_trx_receipts_shift ctrs,cdc_master_toko cmt  where ctrs."STORE_ID"=cmt."STORE_ID"  AND "BRANCH_ID"=?
                         AND ("SALES_DATE">=? AND "SALES_DATE"<=?)group by "BRANCH_ID") asd,cdc_master_branch cmb
                         where asd."BRANCH_ID"=cmb."BRANCH_ID" and cmb."BRANCH_ID"=? 
                            ORDER BY  cast(SUBSTRING(asd."REGION",2,2) as int) ASC,asd."BRANCH_ID" ASC';

            $result = $this->db->query($statement,array($branch_id,$start_date,$end_date,$branch_id))->result();
        }
        return $result;
    }
    public function get_as_data($am,$branch_code){
      $statement = 'SELECT "AS_NUMBER","AS_NAME" FROM cdc_master_am_as WHERE "AM_NUMBER" = ? AND "BRANCH_CODE" = ? GROUP BY "AS_NUMBER","AS_NAME"';
      $result = $this->db->query($statement,array($am,$branch_code));
      return $result->result();
    }

    public function get_toko_as($as,$branch_code){
      $statement = 'SELECT "STORE_CODE" FROM cdc_master_am_as WHERE "AS_NUMBER" = ? AND "BRANCH_CODE" = ?';
      $result = $this->db->query($statement,array($as,$branch_code));
      return $result->result();
    }

    public function get_store_name($store_code){
      $statement = 'SELECT "STORE_NAME" FROM cdc_master_toko WHERE "STORE_CODE" = ?';
      $result = $this->db->query($statement,array($store_code));
      return $result->row();
    }

    public function get_am_for_title($am,$branch_code){
      $statement = 'SELECT "AM_NUMBER","AM_NAME" FROM cdc_master_am_as WHERE "AM_NUMBER" = ? AND "BRANCH_CODE" = ? GROUP BY "AM_NUMBER","AM_NAME"';
      $result = $this->db->query($statement,array($am,$branch_code));
      return $result->row();
    }

    public function get_branch_code($branch_id){
      $statement = 'SELECT "BRANCH_CODE" FROM cdc_master_branch WHERE "BRANCH_ID" = ?';
      $result = $this->db->query($statement,array($branch_id));
      return $result->row();
    }

    public function get_hand_date($store_code,$tgl){
      $statement = 'SELECT "LAST_UPDATE_DATE" FROM cdc_handheld_table WHERE "STORE_CODE" = ? AND "SCAN_DATE" = ?';
      $result = $this->db->query($statement,array($store_code,$tgl));
      return $result->row();

    }
    

    public function get_store_by_id($store_id)
    {
      $statement = 'SELECT * FROM cdc_master_toko WHERE "STORE_ID" = ?';
      return $this->db->query($statement, array($store_id))->row();
    }

    public function get_store_by_branch($branch_id)
    {
      $statement = 'SELECT * FROM cdc_master_toko WHERE "BRANCH_ID" = ? ORDER BY "STORE_CODE"';
      return $this->db->query($statement, array($branch_id))->result();
    }

    public function get_slp_mtr_dana_2($branch_code, $store_code, $start, $end)
    {
      /*$statement = 'SELECT * FROM cdc_master_slp2 WHERE BTRIM("BRANCH_CODE") = BTRIM(?) AND BTRIM("STORE_CODE") = BTRIM(?) AND "SALES_DATE" BETWEEN ? AND ? ORDER BY "SALES_DATE"';*/
      $statement = 'SELECT"STORE_CODE","SALES_DATE","SALES_AMOUNT","BRANCH_CODE","SHIFT1","SHIFT2","SHIFT3",1 AS "SHIFT" FROM cdc_master_slp2 
    WHERE "BRANCH_CODE" = ? AND "STORE_CODE" = ? 
    AND "SALES_DATE" BETWEEN ? AND ?
    UNION SELECT "STORE_CODE","SALES_DATE","SALES_AMOUNT","BRANCH_CODE",0 AS "SHIFT1",0 AS "SHIFT2",0 AS "SHIFT3",0 AS "SHIFT" FROM cdc_master_slp cms WHERE NOT EXISTS
            (
              SELECT  *
              FROM    cdc_master_slp2 cms2
              WHERE   cms2."BRANCH_CODE" = cms."BRANCH_CODE"
               AND cms2."SALES_DATE" = cms."SALES_DATE"
               AND cms2."STORE_CODE" = cms."STORE_CODE"
            ) AND "BRANCH_CODE" = ? AND "STORE_CODE" = ?
    AND "SALES_DATE" BETWEEN ? AND ? ORDER BY "SALES_DATE"';


      return $this->db->query($statement, array($branch_code, $store_code, $start, $end,$branch_code, $store_code, $start, $end))->result();
    }

    public function get_slp_mtr_dana($branch_code, $store_code, $start, $end)
    {
      $statement = 'SELECT * FROM cdc_master_slp WHERE "BRANCH_CODE" = ? AND "STORE_CODE" = ? AND "SALES_DATE" BETWEEN ? AND ? ORDER BY "SALES_DATE"';
      return $this->db->query($statement, array($branch_code, $store_code, $start, $end))->result();
    }

    public function get_receipt_by_slp($store_code, $sales_date)
    {
      $statement = 'SELECT CTR.*, CMT."STORE_CODE" FROM cdc_trx_receipts CTR, cdc_master_toko CMT WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CMT."STORE_CODE" = ? AND CTR."SALES_DATE" = ?';
      return $this->db->query($statement, array($store_code, $sales_date))->row();
    }

     public function get_receipt_by_slp_shift($store_code, $sales_date)
    {
      //CTR.*, CMT."STORE_CODE"
      
      $statement = 'SELECT CTR."STN_FLAG",SUM(CTR."ACTUAL_SALES_AMOUNT") as "ACTUAL_SALES_AMOUNT",CTR."SHIFT_FLAG",CTR."CREATION_DATE", CMT."STORE_CODE",CTR."NO_SHIFT" FROM cdc_trx_receipts_shift CTR, cdc_master_toko CMT WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CMT."STORE_CODE" = ? AND CTR."SALES_DATE" = ? GROUP BY CTR."STN_FLAG",CTR."SHIFT_FLAG",CTR."CREATION_DATE", CMT."STORE_CODE",CTR."NO_SHIFT" ORDER BY CTR."NO_SHIFT"';
      return $this->db->query($statement, array($store_code, $sales_date))->result();
    }

    public function get_kurset_fin_mtr($rec_id)
    {
      $statement = 'SELECT SUM("TRX_MINUS_AMOUNT") "AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_MINUS_ID" IN (21,22,23,24,25,28,29) AND "TRX_CDC_REC_ID" = ?';
      $result = $this->db->query($statement, array($rec_id))->row();
      return $result->AMOUNT;
    }

    public function get_kurset_vir_mtr($rec_id)
    {
      $statement = 'SELECT SUM("TRX_MINUS_AMOUNT") "AMOUNT" FROM cdc_trx_detail_minus WHERE "TRX_MINUS_ID" IN (26,27) AND "TRX_CDC_REC_ID" = ?';
      $result = $this->db->query($statement, array($rec_id))->row();
      return $result->AMOUNT;
    }


  // START IWAN CODE //
    function getListingGTU($from,$to,$branch_id,$dc_code){
      $query = '
        SELECT deposit."CDC_DEPOSIT_NUM", deposit."MUTATION_DATE", batch."CDC_BATCH_NUMBER", batch."CDC_BATCH_DATE",
          gtu."CDC_GTU_NUMBER", gtu."CDC_GTU_AMOUNT", branch."BRANCH_CODE" ||\' - \'|| name."USER_NAME" AS "USERNAME"

        FROM cdc_trx_batches AS "batch" INNER JOIN cdc_trx_deposit AS "deposit" ON (batch."CDC_DEPOSIT_ID"=deposit."CDC_DEPOSIT_ID")
            INNER JOIN cdc_trx_gtu AS "gtu" ON (batch."CDC_BATCH_ID" = gtu."CDC_BATCH_ID")
            INNER JOIN sys_user_2 AS "name" ON (batch."CREATED_BY" = name."USER_ID")
            INNER JOIN cdc_master_branch AS "branch" ON (name."BRANCH_ID" = branch."BRANCH_ID")

        WHERE batch."CDC_BATCH_DATE" BETWEEN \''.$from.'\' AND \''.$to.'\' AND branch."BRANCH_ID" = \''.$branch_id.'\'
      ';

      if ($dc_code != 'ALL') {
        $query .= ' AND batch."CDC_DC_CODE" = \''.$dc_code.'\'';
      }

      $result = $this->db->query($query);
      return $result->result();
    }

    function getMonitoringKodel($from,$barang,$kodel){
      $branchId = $this->session->userdata('branch_id');
      $query ='
        SELECT toko."STORE_CODE", toko."STORE_NAME",
          CASE
            WHEN (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table AS "barang"
                  WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "BARANG",

          CASE
            WHEN (SELECT COUNT(sales."STORE_CODE") FROM cdc_handheld_table AS "sales"
                  WHERE sales."SCAN_DATE" = \''.$from.'\' AND sales."STORE_CODE" = toko."STORE_CODE" AND sales."SALES_FLAG" = \'Y\') >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "SALES",

          CASE
            WHEN (SELECT COUNT(coin."STORE_CODE") FROM cdc_handheld_table AS "coin"
                  WHERE coin."SCAN_DATE" = \''.$from.'\' AND coin."STORE_CODE" = toko."STORE_CODE" AND coin."COIN_FLAG" = \'Y\') >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "COIN",
          (SELECT MAX(total."TOTAL_SALES") FROM cdc_handheld_table AS "total"
                  WHERE total."SCAN_DATE" = \''.$from.'\' AND total."STORE_CODE" = toko."STORE_CODE" GROUP BY total."STORE_CODE",total."SCAN_DATE")
                  AS "TOTAL_SALES",
          (SELECT MAX(time."LAST_UPDATE_DATE"::timestamp::time) FROM cdc_handheld_table AS "time"
                  WHERE time."SCAN_DATE" = \''.$from.'\' AND time."STORE_CODE" = toko."STORE_CODE"
                  GROUP BY time."STORE_CODE",time."SCAN_DATE")
                  AS "JAM"        
        FROM cdc_master_toko AS "toko"
        ,cdc_stores as "cs" WHERE 
        toko."STORE_CODE" = cs."STORE_CODE"  AND \''.$from.'\' >=  coalesce(cs."TGL_ACTIVE_CABANG",\''.$from.'\')
AND \''.$from.'\' <=  coalesce(cs."TGL_INACTIVE_CABANG",\''.$from.'\') and "BRANCH_ID" = \''.$branchId.'\'
        ';

        if($barang=='Y'){
          $query.='
            AND
            (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table AS "barang"
            WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) >= 1
          ';
        }
        else if($barang=='N'){
          $query.='
            AND
            (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table AS "barang"
            WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) < 1
          ';
        }

        if($kodel=='Y'){
          $query.='
            AND
            (SELECT COUNT(kodel."STORE_CODE") FROM cdc_handheld_table AS "kodel"
            WHERE kodel."SCAN_DATE" = \''.$from.'\' AND kodel."STORE_CODE" = toko."STORE_CODE" ) >= 1
          ';
        }
        else if($kodel=='N'){
          $query.='
            AND
            (SELECT COUNT(kodel."STORE_CODE") FROM cdc_handheld_table AS "kodel"
            WHERE kodel."SCAN_DATE" = \''.$from.'\' AND kodel."STORE_CODE" = toko."STORE_CODE" ) < 1
          ';
        }

        $query.='
        GROUP BY toko."STORE_CODE",toko."STORE_NAME"
        ORDER BY "BARANG" ASC, "SALES" ASC, toko."STORE_CODE" ASC
      ';
      $result = $this->db->query($query);
      return $result->result();
    }

    function getMonitoringKodel2($from,$barang,$kodel){
      $branchId = $this->session->userdata('branch_id');
      $query ='
        SELECT toko."STORE_CODE", toko."STORE_NAME",
          CASE
            WHEN (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table2 AS "barang"
                  WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "BARANG",

          CASE
            WHEN (SELECT COUNT(sales."STORE_CODE") FROM cdc_handheld_table2 AS "sales"
                  WHERE sales."SCAN_DATE" = \''.$from.'\' AND sales."STORE_CODE" = toko."STORE_CODE" AND sales."SALES_FLAG" = \'Y\') >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "SALES",

          CASE
            WHEN (SELECT COUNT(coin."STORE_CODE") FROM cdc_handheld_table2 AS "coin"
                  WHERE coin."SCAN_DATE" = \''.$from.'\' AND coin."STORE_CODE" = toko."STORE_CODE" AND coin."COIN_FLAG" = \'Y\') >= 1
              THEN \'YA\'
            ELSE \'TDK\'
          END AS "COIN",
          (SELECT MAX(total."TOTAL_SALES") FROM cdc_handheld_table2 AS "total"
                  WHERE total."SCAN_DATE" = \''.$from.'\' AND total."STORE_CODE" = toko."STORE_CODE" GROUP BY total."STORE_CODE",total."SCAN_DATE")
                  AS "TOTAL_SALES",
          (SELECT MAX(time."LAST_UPDATE_DATE"::timestamp::time) FROM cdc_handheld_table2 AS "time"
                  WHERE time."SCAN_DATE" = \''.$from.'\' AND time."STORE_CODE" = toko."STORE_CODE"
                  GROUP BY time."STORE_CODE",time."SCAN_DATE")
                  AS "JAM"        
        FROM cdc_master_toko AS "toko"
        ,cdc_stores as "cs" WHERE 
        toko."STORE_CODE" = cs."STORE_CODE" and (cs."TGL_INACTIVE_CABANG" <= \''.$from.'\' or cs."TGL_INACTIVE_CABANG" is NULL) and "BRANCH_ID" = \''.$branchId.'\'
        ';

        if($barang=='Y'){
          $query.='
            AND
            (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table2 AS "barang"
            WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) >= 1
          ';
        }
        else if($barang=='N'){
          $query.='
            AND
            (SELECT COUNT(barang."STORE_CODE") FROM cdc_go_table2 AS "barang"
            WHERE barang."TGL_KIRIM" = \''.$from.'\' AND barang."STORE_CODE" = toko."STORE_CODE" ) < 1
          ';
        }

        if($kodel=='Y'){
          $query.='
            AND
            (SELECT COUNT(kodel."STORE_CODE") FROM cdc_handheld_table2 AS "kodel"
            WHERE kodel."SCAN_DATE" = \''.$from.'\' AND kodel."STORE_CODE" = toko."STORE_CODE" ) >= 1
          ';
        }
        else if($kodel=='N'){
          $query.='
            AND
            (SELECT COUNT(kodel."STORE_CODE") FROM cdc_handheld_table2 AS "kodel"
            WHERE kodel."SCAN_DATE" = \''.$from.'\' AND kodel."STORE_CODE" = toko."STORE_CODE" ) < 1
          ';
        }

        $query.='
        GROUP BY toko."STORE_CODE",toko."STORE_NAME"
        ORDER BY "BARANG" ASC, toko."STORE_CODE" ASC
      ';
      $result = $this->db->query($query);
      return $result->result();
    }

    function getMonitoringKodel_freq2($from,$toko){
      $query='
        SELECT date \''.$from.'\' - MAX("TGL_KIRIM")  AS "FREQ"
        FROM cdc_go_table2
        WHERE "STORE_CODE" = \''.$toko.'\'
          AND "TGL_KIRIM" < \''.$from.'\'
      ';
      $result = $this->db->query($query);
      return $result->row()->FREQ;
    }

    function getMonitoringKodel_freq($from,$toko){
      $query='
        SELECT date \''.$from.'\' - MAX("TGL_KIRIM")  AS "FREQ"
        FROM cdc_go_table
        WHERE "STORE_CODE" = \''.$toko.'\'
          AND "TGL_KIRIM" < \''.$from.'\'
      ';
      $result = $this->db->query($query);
      return $result->row()->FREQ;
    }

    function getMonitoringKodel_terima($from,$toko){
      $query='
      SELECT(
        (SELECT MAX("SCAN_DATE")  AS "handheld" FROM cdc_handheld_table WHERE "STORE_CODE" = \''.$toko.'\' AND "SCAN_DATE" = \''.$from.'\')
        -
        (SELECT MAX("TGL_KIRIM")  AS "go" FROM cdc_go_table WHERE "STORE_CODE" = \''.$toko.'\' AND "TGL_KIRIM" = \''.$from.'\')
      ) AS "TERIMA"
      ';
      $result = $this->db->query($query);
      return $result->row()->TERIMA;
    }


    function getPenerimaanSales($from,$pending){
      $query='
      SELECT toko."STORE_ID", toko."STORE_CODE", toko."STORE_NAME",

      CASE
        WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
              WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'5\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
          THEN \'V\'
        ELSE \' \'
      END AS "CEK5",

      CASE
        WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
              WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'4\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
          THEN \'V\'
        ELSE \' \'
      END AS "CEK4",


      CASE
        WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
              WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'3\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
          THEN \'V\'
        ELSE \' \'
      END AS "CEK3",

      CASE
        WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
              WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'2\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
          THEN \'V\'
        ELSE \' \'
      END AS "CEK2",

      CASE
        WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
              WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'1\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
          THEN \'V\'
        ELSE \' \'
      END AS "CEK1"

      FROM cdc_master_toko AS "toko"
      WHERE toko."BRANCH_ID" = '.$this->session->userdata('branch_id').'
      ';

      /*INNER JOIN cdc_handheld_table AS "handheld" ON(toko."STORE_CODE" = handheld."STORE_CODE")*/

      if($pending == 'Y'){
        $query.='
             AND CASE
              WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
                    WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'1\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
                THEN \'V\'
              ELSE \' \'
            END
          = \' \'
        ';
      } elseif ($pending == 'N') {
        $query.='
            AND CASE
              WHEN (SELECT COUNT(barang."STORE_ID") FROM cdc_trx_receipts AS "barang"
                    WHERE barang."SALES_DATE" = (date \''.$from.'\' - integer \'1\') AND barang."STORE_ID" = toko."STORE_ID" ) >= 1
                THEN \'V\'
              ELSE \' \'
            END
          = \'V\' 
        ';
      }

      $query.='
      GROUP BY toko."STORE_ID",toko."STORE_CODE",toko."STORE_NAME"
      ';
  //  WHERE handheld."SCAN_DATE" = \''.$from.'\'

      $result = $this->db->query($query);
      return $result->result();
    }


    function getReceiptSalesQty($from){
      $query='
      SELECT toko."STORE_CODE", toko."STORE_NAME", handheld."TOTAL_SALES" AS "COUNT"

      FROM cdc_master_toko "toko", cdc_handheld_table "handheld"
      WHERE toko."STORE_CODE" = handheld."STORE_CODE" AND toko."BRANCH_ID" = '.$this->session->userdata('branch_id').' AND handheld."SCAN_DATE" = \''.$from.'\'
      GROUP BY toko."STORE_CODE",toko."STORE_NAME",handheld."TOTAL_SALES"
      ORDER BY toko."STORE_CODE"
      ';

      $result = $this->db->query($query);
      return $result->result();
    }


    function get_toko_monitoring_voucher($branch_id, $dc_code){
      $branchId = $branch_id;
      $statement = '
      SELECT toko."STORE_ID", toko."STORE_CODE", toko."STORE_CODE" || \' - \' || toko."STORE_NAME" AS "STORE",
        toko."STORE_NAME" || \' - \' || toko."STORE_ADDRESS" AS "desc"

      FROM cdc_master_toko AS "toko"
        INNER JOIN cdc_trx_receipts AS "rec" ON (toko."STORE_ID" = rec."STORE_ID")
        INNER JOIN cdc_trx_voucher AS "voucher" ON (rec."CDC_REC_ID" = voucher."TRX_CDC_REC_ID")
        INNER JOIN cdc_trx_batches AS "batch" ON (rec."CDC_BATCH_ID" = batch."CDC_BATCH_ID")
      WHERE toko."BRANCH_ID" = \''.$branchId.'\'';

      if ($dc_code != 'ALL') {
        $statement .= ' AND batch."CDC_DC_CODE" = \''.$dc_code.'\'';
      }

      $statement .= 'GROUP BY toko."STORE_ID", toko."STORE_CODE"
      ORDER BY toko."STORE_CODE" ASC';

      $result = $this->db->query($statement);
      return $result->result();
    }

    function getVoucherHeader_perToko($toko){
      $result = $this->db->query('
      SELECT toko."STORE_CODE" || \' - \' || toko."STORE_NAME" AS "STORE"

      FROM cdc_master_toko AS "toko"
      WHERE toko."STORE_ID" = \''.$toko.'\'
      ');
      return $result->row()->STORE;
    }

    function getVoucherBody_perToko($store_id,$from,$to){
      $query = '
          SELECT batch."CDC_BATCH_NUMBER", batch."CDC_BATCH_DATE", rec."SALES_DATE", vouc."TRX_VOUCHER_CODE" ||\' \'||vouc."TRX_VOUCHER_NUMBER" AS "VOUCHER_NUM", vouc."TRX_VOUCHER_AMOUNT"
          FROM cdc_master_toko AS toko INNER JOIN cdc_trx_receipts AS rec ON(toko."STORE_ID" = rec."STORE_ID")
                    INNER JOIN cdc_trx_batches AS batch ON(rec."CDC_BATCH_ID" = batch."CDC_BATCH_ID")
                    INNER JOIN cdc_trx_voucher AS vouc ON(rec."CDC_REC_ID" = vouc."TRX_CDC_REC_ID")
          WHERE rec."CDC_REC_ID" IN (select "TRX_CDC_REC_ID" from cdc_trx_voucher) AND  rec."STORE_ID" = \''.$store_id.'\'
                AND batch."CDC_BATCH_DATE" BETWEEN \''.$from.'\' AND \''.$to.'\'
      ';

      $result = $this->db->query($query);
      return $result->result();
    }

    function getPending_sales($cek_tgl,$cek_toko,$include_go){
      if($include_go=="Y"){
        $query='
            SELECT COUNT(1) AS "hasilCek" FROM cdc_go_table WHERE "STORE_CODE" = \''.$cek_toko.'\' AND "TGL_KIRIM" = \''.$cek_tgl.'\'
        ';
      }
      else{
        $query='
          SELECT COUNT(1) AS "hasilCek"
          FROM cdc_master_toko AS "toko" INNER JOIN cdc_trx_receipts AS "rec"
            ON (rec."STORE_ID" = toko."STORE_ID" )
          WHERE toko."STORE_CODE" = \''.$cek_toko.'\' AND rec."SALES_DATE" = \''.$cek_tgl.'\'
        ';

        /*SELECT COUNT(1) AS "hasilCek"
          FROM cdc_go_table AS "go" INNER JOIN cdc_master_toko AS "toko" ON (go."STORE_CODE" = toko."STORE_CODE")
            INNER JOIN cdc_trx_receipts AS "rec" ON (rec."STORE_ID" = toko."STORE_ID" )*/
      }

      $result = $this->db->query($query);
      return $result->row()->hasilCek;
    }

    function getSemuaToko(){
      $branchId = $this->session->userdata('branch_id');
      $query='
          SELECT toko."STORE_CODE", toko."STORE_NAME" FROM cdc_master_toko AS "toko"  WHERE toko."BRANCH_ID" = \''.$branchId.'\'
          LIMIT 5
      ';
      $result = $this->db->query($query);
      return $result->result();
    }


    function get_receipt_register_toko($branch_id, $dc_code){
      $branchId = $branch_id;
      $statement = '
      SELECT toko."STORE_ID", toko."STORE_CODE", toko."STORE_CODE" || \' - \' || toko."STORE_NAME" AS "STORE",
        toko."STORE_NAME" || \' - \' || toko."STORE_ADDRESS" AS "desc"

      FROM cdc_master_toko AS "toko"
        INNER JOIN cdc_trx_receipts AS "rec" ON (toko."STORE_ID" = rec."STORE_ID")
        INNER JOIN cdc_trx_batches AS "batch" ON (rec."CDC_BATCH_ID" = batch."CDC_BATCH_ID")
      WHERE toko."BRANCH_ID" = \''.$branchId.'\'';
      if ($dc_code != 'ALL') {
        $statement .= ' AND batch."CDC_DC_CODE" = \''.$dc_code.'\'';
      }
      $statement .= 'GROUP BY toko."STORE_ID", toko."STORE_CODE"
      ORDER BY toko."STORE_CODE" ASC
      ';
      $result = $this->db->query($statement);
      return $result->result();
    }

    function getReceipt_register($from,$to,$toko1,$toko2,$branch_id,$dc_code){
      $branchId = $branch_id;
      $query='
        SELECT TOKO."STORE_CODE", TOKO."STORE_NAME", REC."CDC_REC_ID", REC."SALES_DATE", to_char(REC."CREATION_DATE", \'HH24:MI:SS\') "REC_TIME", REC."ACTUAL_SALES_AMOUNT", REC."ACTUAL_RRAK_AMOUNT", REC."ACTUAL_PAY_LESS_DEPOSITED", REC."ACTUAL_VIRTUAL_PAY_LESS", REC."ACTUAL_VOUCHER_AMOUNT" , REC."ACTUAL_LOST_ITEM_PAYMENT" , REC."ACTUAL_WU_ACCOUNTABILITY" , REC."ACTUAL_OTHERS_AMOUNT", REC."ACTUAL_OTHERS_DESC", REC."RRAK_DEDUCTION", REC."LESS_DEPOSIT_DEDUCTION", REC."OTHERS_DEDUCTION", REC."VIRTUAL_PAY_LESS_DEDUCTION", REC."START_INPUT_TIME",REC."CREATION_DATE", BATCH."CDC_BATCH_TYPE", BATCH."CDC_BATCH_NUMBER", BATCH."CDC_BATCH_DATE", OLEH."USER_NAME", CBG."BRANCH_CODE" FROM cdc_master_toko TOKO, cdc_trx_receipts REC, cdc_trx_batches BATCH, sys_user_2 OLEH, cdc_master_branch CBG 
          WHERE TOKO."STORE_ID" = REC."STORE_ID"
          AND REC."CDC_BATCH_ID" = BATCH."CDC_BATCH_ID"
          AND BATCH."CREATED_BY" = OLEH."USER_ID"
          AND OLEH."BRANCH_ID" = CBG."BRANCH_ID"
          AND BATCH."CDC_BRANCH_ID" = '.$branchId.'
          AND BATCH."CDC_BATCH_DATE" BETWEEN \''.$from.'\' AND \''.$to.'\'';

      if ($dc_code != 'ALL') {
        $query .= ' AND BATCH."CDC_DC_CODE" = \''.$dc_code.'\'';
      }

      if($toko1 != 'all' && $toko2 != 'all'){
        $query.='
          AND TOKO."STORE_CODE" BETWEEN \''.$toko1.'\' AND \''.$toko2.'\'
        ';
      }

      $query.='
        ORDER BY TOKO."STORE_CODE" ASC, REC."SALES_DATE" ASC
      ';

      $result = $this->db->query($query);
      return $result->result();
    }

    function get_time_from_receipt($rec_id)
    {
      $statement = 'SELECT to_char("CREATION_DATE", \'HH24:MI:SS\') "REC_TIME" FROM cdc_trx_receipts WHERE "CDC_REC_ID" = ?';
      $rec_time = $this->db->query($statement,$rec_id)->result();
      return $rec_time[0]->REC_TIME;
    }


    function get_sales_tgl_am(){
      //$branchId = $this->session->userdata('branch_id');
      $result = $this->db->query('
        SELECT TRIM("AM_SHORT") AS "AM_SHORT", "AM_SHORT" || \' - \' || "AM_NAME" || \' \' || "AM_NUMBER"   AS "AM"
        FROM cdc_master_am_as
        GROUP BY "AM_SHORT", "AM_NUMBER", "AM_NAME"
      ');
      return $result->result();
    }

    function getHeader_am($am){
      $query='
        SELECT "AM_NAME" FROM cdc_master_am_as WHERE "AM_SHORT" = \''.$am.'\'
      ';
      $result = $this->db->query($query);
      return $result->row()->AM_NAME;
    }

    function getReport_sales_tgl_am_toko($from, $am, $status){
      $branchId = $this->session->userdata('branch_id');
      $query='
        SELECT am."AS_SHORT", am."STORE_CODE", toko."STORE_NAME"
        FROM cdc_master_am_as AS "am"
              INNER JOIN cdc_master_toko AS "toko" ON (toko."STORE_CODE" = am."STORE_CODE")
      ';

      if($am != "all"){
        $query.='
            WHERE am."AM_SHORT" = \''.$am.'\'
        ';
          /*if($status == "Y"){
            $query.='
                AND
                  (
                    SELECT COUNT(1) FROM cdc_trx_receipts AS "status"
                    WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                  ) < 1
            ';
          }

          if($status == "N"){
            $query.='
                AND
                  (
                    SELECT COUNT(1) FROM cdc_trx_receipts AS "status"
                    WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                  ) > 0
            ';
          }*/

          //penggantian karena input shift
          if($status == "Y"){
            $query.='
                AND
                  (
                    SELECT COUNT(1) FROM cdc_trx_receipts_shift AS "status"
                    WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                  ) < 1
            ';
          }

          if($status == "N"){
            $query.='
                AND
                  (
                    SELECT COUNT(1) FROM cdc_trx_receipts_shift AS "status"
                    WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                  ) > 0
            ';
          }
      }else{
        /*if($status == "Y"){
          $query.='
              WHERE
                (
                  SELECT COUNT(1) FROM cdc_trx_receipts AS "status"
                  WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                ) < 1
          ';
        }

        if($status == "N"){
          $query.='
              WHERE
                (
                  SELECT COUNT(1) FROM cdc_trx_receipts AS "status"
                  WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                ) > 0
          ';
        }*/

        if($status == "Y"){
          $query.='
              WHERE
                (
                  SELECT COUNT(1) FROM cdc_trx_receipts_shift AS "status"
                  WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                ) < 1
          ';
        }

        if($status == "N"){
          $query.='
              WHERE
                (
                  SELECT COUNT(1) FROM cdc_trx_receipts_shift AS "status"
                  WHERE status."STORE_ID" = am."STORE_ID" AND status."SALES_DATE" = (date \''.$from.'\' - integer \'1\' )
                ) > 0
          ';
        }
      }


      $query.='
        AND btrim(am."BRANCH_CODE") = btrim(\''.$this->session->userdata('branch_code').'\') ORDER BY am."AS_SHORT" ASC
      ';
      $result = $this->db->query($query);
      return $result->result();
    }


    function getReport_sales_tgl_am_shiping($toko,$from){
      /*$query='
        SELECT MAX(rec."SALES_DATE") AS "lastShiping"
        FROM cdc_trx_receipts AS "rec", cdc_master_toko "toko"
        WHERE rec."STORE_ID" = toko."STORE_ID" AND toko."STORE_CODE" = \''.trim($toko).'\' AND rec."SALES_DATE" < \''.$from.'\'
      ';*/

      $query='
        SELECT MAX(rec."SALES_DATE") AS "lastShiping"
        FROM cdc_trx_receipts_shift AS "rec", cdc_master_toko "toko"
        WHERE rec."STORE_ID" = toko."STORE_ID" AND toko."STORE_CODE" = \''.trim($toko).'\' AND rec."SALES_DATE" < \''.$from.'\'
      ';
      $result = $this->db->query($query);
      return $result->row()->lastShiping;
      //return $toko;
    }


    function getReport_sales_tgl_am_cek($cek_toko, $cek_tgl){
      /*$query='
          SELECT COUNT(1) AS "hasilCek"
          FROM cdc_trx_receipts rec, cdc_master_toko toko WHERE rec."STORE_ID" = toko."STORE_ID" AND toko."STORE_CODE" = \''.trim($cek_toko).'\' AND rec."SALES_DATE" = \''.$cek_tgl.'\'
      ';*/

      $query='
          SELECT COUNT(1) AS "hasilCek"
          FROM cdc_trx_receipts_shift rec, cdc_master_toko toko WHERE rec."STORE_ID" = toko."STORE_ID" AND toko."STORE_CODE" = \''.trim($cek_toko).'\' AND rec."SALES_DATE" = \''.$cek_tgl.'\'
      ';
      //var_dump($query);
      $result = $this->db->query($query);
      return $result->row()->hasilCek;
    }

  // END IWAN CODE //

  function getVoucherHeader($num='all',$from='all',$to='all',$branch_id,$dc_code){
    $query = 'SELECT toko."STORE_ID", toko."STORE_CODE", toko."STORE_NAME"
              FROM cdc_master_toko AS toko INNER JOIN cdc_trx_receipts AS rec ON(toko."STORE_ID" = rec."STORE_ID")
                        INNER JOIN cdc_trx_batches AS batch ON(rec."CDC_BATCH_ID" = batch."CDC_BATCH_ID")
              WHERE rec."CDC_REC_ID" IN (select "TRX_CDC_REC_ID" from cdc_trx_voucher) AND batch."CDC_BRANCH_ID" = ?
    ';
    if($num != 'all'){
      $query .= ' AND batch."CDC_BATCH_NUMBER" = \''.$num.'\' ';
    }
    if($from != 'all' && $to != 'all'){
      $query .= ' AND batch."CDC_BATCH_DATE" BETWEEN \''.$from.'\' AND \''.$to.'\'  ';
    }
    if ($dc_code != 'ALL') {
      $query .= ' AND batch."CDC_DC_CODE" = \''.$dc_code.'\'';
    }

    $query .= ' GROUP BY toko."STORE_ID"
              ';

    $result = $this->db->query($query,$branch_id);
    return $result->result();
  }

  function getVoucherBody($store_id,$num){
    $query = '
        SELECT batch."CDC_BATCH_NUMBER", batch."CDC_BATCH_DATE", rec."SALES_DATE", vouc."TRX_VOUCHER_CODE" ||\' \'||vouc."TRX_VOUCHER_NUMBER" AS "VOUCHER_NUM", vouc."TRX_VOUCHER_AMOUNT"
        FROM cdc_master_toko AS toko INNER JOIN cdc_trx_receipts AS rec ON(toko."STORE_ID" = rec."STORE_ID")
                  INNER JOIN cdc_trx_batches AS batch ON(rec."CDC_BATCH_ID" = batch."CDC_BATCH_ID")
                  INNER JOIN cdc_trx_voucher AS vouc ON(rec."CDC_REC_ID" = vouc."TRX_CDC_REC_ID")
        WHERE rec."CDC_REC_ID" IN (select "TRX_CDC_REC_ID" from cdc_trx_voucher) AND  rec."STORE_ID" = \''.$store_id.'\'
    ';
    if($num!='all'){
      $query .= 'AND batch."CDC_BATCH_NUMBER"=\''.$num.'\' ';
    }
    $result = $this->db->query($query);
    return $result->result();
  }


  function getVoucherTable($num='all',$from='all',$to='all'){
    $query = 'SELECT batch."CDC_BATCH_NUMBER", batch."CDC_BATCH_DATE", rec."SALES_DATE",
                      vouc."TRX_VOUCHER_NUMBER", vouc."TRX_VOUCHER_AMOUNT"
              FROM  cdc_trx_receipts AS rec INNER JOIN cdc_trx_voucher AS vouc ON (rec."CDC_REC_ID" = vouc."TRX_CDC_REC_ID")
                                            INNER JOIN cdc_trx_batches AS batch ON (batch."CDC_BATCH_ID" = rec."CDC_BATCH_ID")
    ';
  }

  function get_batch_num($dc_code, $branch_id){
    $branchId = $branch_id;//$this->session->userdata('branch_id');
    $indc_code  = '';
    $in_role = '';

    if ($this->session->userdata('dc_type') == 'DCI' && $dc_code == 'ALL') {
      $indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
    }else{
      $indc_code = "'".$dc_code."'";
    }

    if ($this->session->userdata('role_id') < 3) {
      $in_role = 'AND c."USER_ID" = '.$this->session->userdata('usrId').'';
    }
    else{
      $in_role = 'AND c."ROLE_ID" <= '.$this->session->userdata('role_id').'';
    }
    $result = $this->db->query(' SELECT "CDC_BATCH_NUMBER",
      CASE
        WHEN "CDC_BATCH_TYPE" = \'F-STJ\' THEN \' Franchise - Setoran Tunai Jemputan \'
        WHEN "CDC_BATCH_TYPE" = \'R-STJ\' THEN \' Reguler - Setoran Tunai Jemputan \'
      END AS "desc",
      to_char("CDC_BATCH_DATE", \'DD Month YYYY\') AS "tgl"
      FROM cdc_trx_batches AS a INNER JOIN cdc_trx_receipts AS b ON(a."CDC_BATCH_ID" = b."CDC_BATCH_ID")
      INNER JOIN sys_user_2 AS c ON (c."USER_ID" = a."CREATED_BY")
      WHERE "CDC_BRANCH_ID"=\''.$branchId.'\' AND a."CDC_DC_CODE" IN ('.$indc_code.') '.$in_role.' AND b."CDC_REC_ID" IN (select "TRX_CDC_REC_ID" from cdc_trx_voucher)
      ORDER BY "CDC_BATCH_NUMBER" DESC
    ');
    return $result->result();
  }

  public function get_sum_collect($date_rep,$start,$end,$branch_id,$dc_code)
  {
    $shift = 'and CTB."CDC_SHIFT_NUM" in (';
    for ($i=$start; $i <= $end; $i++) {
      if ($i == $end) {
        $shift .= $i.')';
      }else{
        $shift .= $i.',';
      }
    }

    $indc_code  = '';
    $in_role = '';

    if ($this->session->userdata('dc_type') == 'DCI' && $dc_code == 'ALL') {
      $indc_code  = 'SELECT "DC_CODE" FROM sys_map_dc WHERE "DC_INDUK" = \''.$this->session->userdata('dc_code').'\'';
    }else{
      $indc_code = "'".$dc_code."'";
    }
    echo $indc_code;
    if ($this->session->userdata('role_id') < 3) {
      $in_role = 'AND SU."USER_ID" = '.$this->session->userdata('usrId').'';
    }
    else{
      $in_role = 'AND SU."ROLE_ID" <= '.$this->session->userdata('role_id').'';
    }

    $statement = 'select coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STJ\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'T%\')),0) REG, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STJ\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'F%\')),0) FRC, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STJ\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'R%\')),0) CRM, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? and (CTB."CDC_BATCH_TYPE" like \'%STJ\' OR CTB."CDC_BATCH_TYPE" like \'%KUR\' OR CTB."CDC_BATCH_TYPE" like \'%-TN\') '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTR."ACTUAL_SALES_FLAG" = \'N\'),0) TITIPAN';

    $statement_2 = 'select coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STN\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'T%\')),0) REG, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STN\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'F%\')),0) FRC, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_TYPE" like \'%STN\' and CTR."ACTUAL_SALES_FLAG" = \'Y\' and CTR."STORE_ID" in (select "STORE_ID" from cdc_master_toko where "STORE_CODE" like \'R%\')),0) CRM, coalesce((select SUM(COALESCE(CTR."ACTUAL_SALES_AMOUNT",0)+COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0)+COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0)+COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0)+COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0)) from cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? and (CTB."CDC_BATCH_TYPE" like \'%STN\' OR CTB."CDC_BATCH_TYPE" like \'%KUN\' OR CTB."CDC_BATCH_TYPE" like \'%-TR\') '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTR."ACTUAL_SALES_FLAG" = \'N\'),0) TITIPAN';

    $statement_3 = 'select coalesce((SELECT SUM(GIRO."CDC_GTU_AMOUNT") FROM (SELECT DISTINCT GTU."CDC_GTU_AMOUNT",GTU."CDC_BATCH_ID" FROM cdc_trx_batches CTB, cdc_trx_receipts CTR, cdc_trx_gtu GTU, sys_user_2 SU WHERE CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" AND CTB."CDC_BATCH_ID" = GTU."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' AND CTB."CDC_BATCH_DATE" = ? '.$shift.' AND CTB."CDC_BRANCH_ID" = ? AND CTB."CDC_BATCH_TYPE" LIKE \'%STJ\' AND CTR."STORE_ID" IN ( SELECT "STORE_ID" FROM cdc_master_toko WHERE "STORE_CODE" LIKE \'T%\')) GIRO),0) REG, coalesce((SELECT SUM(GIRO."CDC_GTU_AMOUNT") FROM (SELECT DISTINCT GTU."CDC_GTU_AMOUNT",GTU."CDC_BATCH_ID" FROM cdc_trx_batches CTB, cdc_trx_receipts CTR, cdc_trx_gtu GTU, sys_user_2 SU WHERE CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" AND CTB."CDC_BATCH_ID" = GTU."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' AND CTB."CDC_BATCH_DATE" = ? '.$shift.' AND CTB."CDC_BRANCH_ID" = ? AND CTB."CDC_BATCH_TYPE" LIKE \'%STJ\' AND CTR."STORE_ID" IN ( SELECT "STORE_ID" FROM cdc_master_toko WHERE "STORE_CODE" LIKE \'F%\')) GIRO),0) FRC, coalesce((SELECT SUM(GIRO."CDC_GTU_AMOUNT") FROM (SELECT DISTINCT GTU."CDC_GTU_AMOUNT",GTU."CDC_BATCH_ID" FROM cdc_trx_batches CTB, cdc_trx_receipts CTR, cdc_trx_gtu GTU, sys_user_2 SU WHERE CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" AND CTB."CDC_BATCH_ID" = GTU."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and SU."USER_ID" = CTR."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' AND CTB."CDC_BATCH_DATE" = ? '.$shift.' AND CTB."CDC_BRANCH_ID" = ? AND CTB."CDC_BATCH_TYPE" LIKE \'%STJ\' AND CTR."STORE_ID" IN ( SELECT "STORE_ID" FROM cdc_master_toko WHERE "STORE_CODE" LIKE \'R%\')) GIRO),0) CRM, coalesce((select sum(GTU."CDC_GTU_AMOUNT") from cdc_trx_batches CTB, cdc_trx_gtu GTU, sys_user_2 SU where CTB."CDC_BATCH_ID" = GTU."CDC_BATCH_ID" and SU."USER_ID" = CTB."CREATED_BY" and CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' and CTB."CDC_BRANCH_ID" = ? and CTB."CDC_BATCH_ID" in (select "CDC_BATCH_ID" from cdc_trx_receipts where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID" and "ACTUAL_SALES_FLAG" = \'N\')),0) TITIPAN';

    $statement_4 = 'SELECT SU."USER_NAME" USERNAME, CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER" BATCHNUM, COUNT(CTR."CDC_REC_ID") QTY, COALESCE((SELECT SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) FROM cdc_trx_receipts WHERE "CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND "ACTUAL_SALES_FLAG" = \'Y\'),0) NILAI_SALES, COALESCE((SELECT SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)) FROM cdc_trx_receipts WHERE "CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND "ACTUAL_SALES_FLAG" = \'N\'),0) TITIPAN, COALESCE((select sum("CDC_GTU_AMOUNT") from cdc_trx_gtu where "CDC_BATCH_ID" = CTB."CDC_BATCH_ID"),0) GIRO FROM cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and CTB."CREATED_BY" = SU."USER_ID" AND CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' AND CTB."CDC_BATCH_DATE" = ? '.$shift.' AND CTB."CDC_BRANCH_ID" = ? group by SU."USER_NAME", CTB."CDC_BATCH_ID", CTB."CDC_BATCH_NUMBER"';

    $statement_5 = 'SELECT GTU."CDC_GTU_NUMBER" NO_GIRO, GTU."CDC_GTU_AMOUNT" JML, SU."USER_NAME" KASIR FROM cdc_trx_gtu GTU, sys_user_2 SU WHERE GTU."CREATED_BY" = SU."USER_ID" '.$in_role.' AND GTU."CDC_BATCH_ID" IN (SELECT CTB."CDC_BATCH_ID" FROM cdc_trx_batches CTB WHERE CTB."CDC_BATCH_DATE" = ? AND CTB."CDC_BRANCH_ID" = ? '.$shift.'  AND CTB."CDC_DC_CODE" in ('.$indc_code.'))';

    $statement_6 = 'SELECT CMT."STORE_CODE" TOKO, SU."USER_NAME" USERNAME, coalesce(SUM(COALESCE("ACTUAL_SALES_AMOUNT",0)+COALESCE("ACTUAL_RRAK_AMOUNT",0)+COALESCE("ACTUAL_PAY_LESS_DEPOSITED",0)+COALESCE("ACTUAL_VOUCHER_AMOUNT",0)+COALESCE("ACTUAL_LOST_ITEM_PAYMENT",0)+COALESCE("ACTUAL_OTHERS_AMOUNT",0)+COALESCE("ACTUAL_WU_ACCOUNTABILITY",0)+COALESCE("ACTUAL_VIRTUAL_PAY_LESS",0)),0) TITIPAN FROM cdc_trx_batches CTB, cdc_trx_receipts CTR, sys_user_2 SU, cdc_master_toko CMT where CTB."CDC_BATCH_ID" = CTR."CDC_BATCH_ID" and CTB."CREATED_BY" = SU."USER_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTB."CDC_DC_CODE" in ('.$indc_code.') '.$in_role.' and CTB."CDC_BATCH_DATE" = ? '.$shift.' AND CTB."CDC_BRANCH_ID" = ? and CTR."ACTUAL_SALES_FLAG" = \'N\' group by CMT."STORE_CODE", SU."USER_NAME"';

    $result['tunai'] = $this->db->query($statement,array($date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id))->result();
    $result['slip_bank'] = $this->db->query($statement_2,array($date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id))->result();
    $result['giro'] = $this->db->query($statement_3,array($date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id,$date_rep,$branch_id))->result();
    $result['detail_batch'] = $this->db->query($statement_4,array($date_rep,$branch_id))->result();
    $result['detail_giro'] = $this->db->query($statement_5,array($date_rep,$branch_id))->result();
    $result['sum_titipan'] = $this->db->query($statement_6,array($date_rep,$branch_id))->result();

    return $result;
  }

  public function get_cabang_session($branch_id)
  {
    if($branch_id!=100 && $branch_id!=0)
    {
      $statement = 'select * from cdc_master_branch where "BRANCH_ID" = ?';
      return $this->db->query($statement,$branch_id)->result();
    }else{
       $statement = 'select * from cdc_master_branch ';
      return $this->db->query($statement)->result();
    }
    
  }

  public function get_data_tren_collection()
  {
    $statement = 'SELECT CMA."AM_SHORT" NAMA_AM,CMA."AS_SHORT" NAMA_AS,CMT."STORE_CODE" KODE_TOKO,CMT."STORE_NAME" NAMA_TOKO,(SELECT MAX("TGL_KIRIM") FROM cdc_go_table WHERE "STORE_CODE" = CMT."STORE_CODE") MAKS FROM cdc_master_am_as CMA, cdc_master_toko CMT  WHERE CMA."STORE_CODE" = CMT."STORE_CODE" AND BTRIM(CMA."BRANCH_CODE") = BTRIM(?) order by CMA."STORE_CODE"';
    return $this->db->query($statement,$this->session->userdata('branch_code'))->result();
  }

  public function cek_sales_pertanggal($kode_toko,$date,$month_year)
  {
    $statement = 'select count(*) CNT from cdc_trx_receipts where "SALES_DATE" = to_date(\''.$date.' \'||\''.$month_year.'\', \'DD MM YYYY\') AND "STORE_ID" = (SELECT "STORE_ID" FROM cdc_master_toko WHERE "STORE_CODE" = ? AND "BRANCH_ID" = (SELECT "BRANCH_ID" FROM cdc_master_branch where "BRANCH_CODE" =?))  AND "ACTUAL_SALES_FLAG" = \'Y\'';

  //  echo "".$statement ." ".$kode_toko." ".$this->session->userdata('branch_code');
    $result = $this->db->query($statement,array($kode_toko,$this->session->userdata('branch_code')))->result();
    return $result[0]->cnt;
  }

  public function create_header_file($local_path,$file_name)
  {
    $this->db->query("Copy (SELECT 'DEPOSIT_ID', 'DEPOSIT_NUM', 'DEPOSIT_DATE', 'MUTATION_DATE', 'DEPOSIT_STATUS', 'BRANCH_CODE', 'BANK_NAME','BATCH_ID', 'BATCH_NUMBER', 'BATCH_TYPE', 'BATCH_DATE', 'BATCH_STATUS', 'DESCRIPTION', 'REFF_NUM','REC_ID', 'STORE_CODE', 'SALES_DATE', 'STATUS', 'ACTUAL_SALES_AMOUNT', 'ACTUAL_RRAK_AMOUNT', 'ACTUAL_PAY_LESS_DEPOSITED', 'ACTUAL_VOUCHER_AMOUNT','ACTUAL_LOST_ITEM_PAYMENT', 'ACTUAL_WU_ACCOUNTABILITY', 'ACTUAL_OTHERS_AMOUNT', 'ACTUAL_OTHERS_DESC', 'RRAK_DEDUCTION', 'LESS_DEPOSIT_DEDUCTION', 'OTHERS_DEDUCTION', 'OTHERS_DESC', 'ACTUAL_VIRTUAL_PAY_LESS', 'ACTUAL_SALES_FLAG', 'VIRTUAL_PAY_LESS_DEDUCTION') To '".$local_path."".$file_name."' With CSV");
  }

  public function create_data_sync($batch_type)
  {
    $statement = 'SELECT CTD."CDC_DEPOSIT_ID" DEPOSIT_ID, CTD."CDC_DEPOSIT_NUM" DEPOSIT_NUM, to_char(CTD."DEPOSIT_DATE", \'DD-Mon-YY\') DEPOSIT_DATE, to_char(CTD."MUTATION_DATE", \'DD-Mon-YY\') MUTATION_DATE, CTD."DEPOSIT_STATUS" DEPOSIT_STATUS, btrim(CTD."BRANCH_CODE") BRANCH_CODE, CMB."BANK_NAME" BANK_NAME, CTB."CDC_BATCH_ID" BATCH_ID, CTB."CDC_BATCH_NUMBER" BATCH_NUMBER, CTB."CDC_BATCH_TYPE" BATCH_TYPE, to_char(CTB."CDC_BATCH_DATE", \'DD-Mon-YY\') BATCH_DATE, CTB."CDC_BATCH_STATUS" BATCH_STATUS, \'SOURCE DATA WEB\' DESCRIPTION, CTB."CDC_REFF_NUM" REFF_NUM, CTR."CDC_REC_ID" REC_ID, CMT."STORE_CODE" STORE_CODE, to_char(CTR."SALES_DATE", \'DD-Mon-YY\') SALES_DATE, CTR."STATUS" STATUS, COALESCE(CTR."ACTUAL_SALES_AMOUNT",0) ACTUAL_SALES_AMOUNT, COALESCE(CTR."ACTUAL_RRAK_AMOUNT",0) ACTUAL_RRAK_AMOUNT, COALESCE(CTR."ACTUAL_PAY_LESS_DEPOSITED",0) ACTUAL_PAY_LESS_DEPOSITED, COALESCE(CTR."ACTUAL_VOUCHER_AMOUNT",0) ACTUAL_VOUCHER_AMOUNT, COALESCE(CTR."ACTUAL_LOST_ITEM_PAYMENT",0) ACTUAL_LOST_ITEM_PAYMENT, COALESCE(CTR."ACTUAL_WU_ACCOUNTABILITY",0) ACTUAL_WU_ACCOUNTABILITY, COALESCE(CTR."ACTUAL_OTHERS_AMOUNT",0) ACTUAL_OTHERS_AMOUNT, btrim(CTR."ACTUAL_OTHERS_DESC") ACTUAL_OTHERS_DESC, COALESCE(CTR."RRAK_DEDUCTION",0) RRAK_DEDUCTION, COALESCE(CTR."LESS_DEPOSIT_DEDUCTION",0) LESS_DEPOSIT_DEDUCTION, COALESCE(CTR."OTHERS_DEDUCTION",0) OTHERS_DEDUCTION, btrim(CTR."OTHERS_DESC") OTHERS_DESC, COALESCE(CTR."ACTUAL_VIRTUAL_PAY_LESS",0) ACTUAL_VIRTUAL_PAY_LESS, CTR."ACTUAL_SALES_FLAG" ACTUAL_SALES_FLAG, COALESCE(CTR."VIRTUAL_PAY_LESS_DEDUCTION",0) VIRTUAL_PAY_LESS_DEDUCTION FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_BANK CMB, CDC_MASTER_TOKO CMT WHERE CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."CDC_BANK_ID" = CMB."BANK_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTD."DEPOSIT_STATUS" = \'V\' AND CTB."CDC_BATCH_STATUS" = \'V\' AND CTD."TRANSFER_FLAG" = \'N\' AND CTB."TRANSFER_FLAG" = \'N\' AND CTR."TRANSFER_FLAG" = \'N\' AND CTB."CDC_BATCH_TYPE" LIKE \'%'.$batch_type.'\' AND CTD."BRANCH_CODE" = ?';
    return $this->db->query($statement,$this->session->userdata('branch_code'))->result();
  }

  public function update_status_deposit_batch($deposit_id,$batch_id)
  {
    $statement = 'UPDATE CDC_TRX_DEPOSIT SET "TRANSFER_FLAG" = \'Y\' WHERE "CDC_DEPOSIT_ID" = ?';
    $statement_2 = 'UPDATE CDC_TRX_BATCHES SET "TRANSFER_FLAG" = \'Y\' WHERE "CDC_BATCH_ID" = ?';
    $statement_3 = 'UPDATE CDC_TRX_RECEIPTS SET "TRANSFER_FLAG" = \'Y\' WHERE "CDC_BATCH_ID" = ?';
    $this->db->query($statement,$deposit_id);
    $this->db->query($statement_2,$batch_id);
    $this->db->query($statement_3,$batch_id);
    return $this->db->affected_rows();
  }

  public function create_header_dll($local_path,$file_name_pnb,$file_name_pgr,$file_name_vcr)
  {
    $this->db->query("Copy (SELECT 'TRX_DETAIL_ID','TRX_CDC_REC_ID','TRX_PLUS_NAME','TRX_DETAIL_DATE','TRX_DETAIL_DESC','TRX_DET_AMOUNT') to '".$local_path.$file_name_pnb."' With CSV");
    $this->db->query("Copy (SELECT 'TRX_DETAIL_MINUS_ID','TRX_CDC_REC_ID','TRX_MINUS_NAME','TRX_MINUS_DATE','TRX_MINUS_DESC','TRX_MINUS_AMOUNT') to '".$local_path.$file_name_pgr."' With CSV");
    $this->db->query("Copy (SELECT 'TRX_VOUCHER_ID', 'TRX_CDC_REC_ID', 'TRX_VOUCHER_CODE', 'TRX_VOUCHER_NUMBER', 'VOUCHER_NUM', 'TRX_VOUCHER_DATE', 'TRX_VOUCHER_DESC', 'TRX_VOUCHER_AMOUNT') to '".$local_path.$file_name_vcr."' With CSV");
  }

  public function create_data_pnb($rec_id)
  {
    $statement = 'SELECT CDT."TRX_DETAIL_ID", CDT."TRX_CDC_REC_ID", BTRIM(CDP."TRX_PLUS_NAME") TRX_PLUS_NAME, TO_CHAR(CDT."TRX_DETAIL_DATE",\'DD-Mon-YY\') TRX_DETAIL_DATE, BTRIM(CDT."TRX_DETAIL_DESC") TRX_DETAIL_DESC, CDT."TRX_DET_AMOUNT" FROM CDC_TRX_DETAIL_TAMBAH CDT, CDC_MASTER_DETAIL_PENAMBAH CDP WHERE CDT."TRX_PLUS_ID" = CDP."TRX_PLUS_ID" AND CDT."TRX_CDC_REC_ID" = ?';
    return $this->db->query($statement,$rec_id)->result();
  }

  public function create_data_pgr($rec_id)
  {
    $statement = 'SELECT CDT."TRX_DETAIL_MINUS_ID", CDT."TRX_CDC_REC_ID", BTRIM(CDP."TRX_MINUS_NAME") TRX_MINUS_NAME, TO_CHAR(CDT."TRX_MINUS_DATE",\'DD-Mon-YY\') TRX_MINUS_DATE, BTRIM(CDT."TRX_MINUS_DESC") TRX_MINUS_DESC, CDT."TRX_MINUS_AMOUNT" FROM CDC_TRX_DETAIL_MINUS CDT, CDC_MASTER_DETAIL_PENGURANG CDP WHERE CDT."TRX_MINUS_ID" = CDP."TRX_MINUS_ID" AND CDT."TRX_CDC_REC_ID" = ?';
    return $this->db->query($statement,$rec_id)->result();
  }

  public function create_data_vcr($rec_id)
  {
    $statement = 'SELECT "TRX_VOUCHER_ID", "TRX_CDC_REC_ID", BTRIM("TRX_VOUCHER_CODE") TRX_VOUCHER_CODE, "TRX_VOUCHER_NUMBER", "TRX_VOUCHER_CODE"||\' \'||"TRX_VOUCHER_NUMBER" VOUCHER_NUM, TO_CHAR("TRX_VOUCHER_DATE",\'DD-Mon-YY\') TRX_VOUCHER_DATE, BTRIM("TRX_VOUCHER_DESC") TRX_VOUCHER_DESC, "TRX_VOUCHER_AMOUNT" FROM CDC_TRX_VOUCHER WHERE "TRX_CDC_REC_ID" = ?';
    return $this->db->query($statement,$rec_id)->result();
  }

  public function get_data_diff_journal($store_code, $sales_date, $branch_code, $dc_code)
  {
    $statement = 'SELECT CTR."ACTUAL_SALES_AMOUNT", CTR."RRAK_DEDUCTION", CTR."LESS_DEPOSIT_DEDUCTION", CTR."VIRTUAL_PAY_LESS_DEDUCTION", CTR."OTHERS_DEDUCTION", CTB."CDC_BATCH_NUMBER", TO_CHAR(CTD."MUTATION_DATE", \'DD-Mon-YY\') "MUTATION_DATE_V", CTD."MUTATION_DATE" FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_TRX_DEPOSIT CTD, CDC_MASTER_TOKO CMT WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."CDC_DEPOSIT_ID" = CTD."CDC_DEPOSIT_ID" AND CTD."DEPOSIT_STATUS" IN (\'V\', \'T\') AND CTR."ACTUAL_SALES_FLAG" = \'Y\' AND BTRIM(CTR."BRANCH_CODE") = \''.str_replace(' ', '', $branch_code).'\' AND CMT."STORE_CODE" = \''.$store_code.'\' AND CTR."SALES_DATE" = \''.$sales_date.'\' UNION ALL SELECT CTR."ACTUAL_SALES_AMOUNT", CTR."RRAK_DEDUCTION", CTR."LESS_DEPOSIT_DEDUCTION", CTR."VIRTUAL_PAY_LESS_DEDUCTION", CTR."OTHERS_DEDUCTION", CTB."CDC_BATCH_NUMBER", TO_CHAR(CTR."MUTATION_DATE", \'DD-Mon-YY\') "MUTATION_DATE_V", CTR."MUTATION_DATE" FROM CDC_TRX_RECEIPTS CTR, CDC_TRX_BATCHES CTB, CDC_MASTER_TOKO CMT WHERE CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = CTB."CDC_BATCH_ID" AND CTB."TRANSFER_FLAG" = \'Y\' AND CTR."STN_FLAG" = \'Y\' AND CTR."ACTUAL_SALES_FLAG" = \'Y\' AND BTRIM(CTR."BRANCH_CODE") = \''.str_replace(' ', '', $branch_code).'\' AND CMT."STORE_CODE" = \''.$store_code.'\' AND CTR."SALES_DATE" = \''.$sales_date.'\'';

    if ($dc_code != 'ALL') {
      $statement .= ' AND CTB."CDC_DC_CODE" = \''.$dc_code.'\'';
    }

    return $this->db->query($statement)->result();
  }

  public function get_data_diff_journal_slp($store_type, $start, $end, $branch_code, $dc_code)
  {
    /*if ($store_type != 'all') {
      //$statement .= ' AND CMT."STORE_TYPE" = \''.$store_type.'\'';
      $state = ' AND CMT."STORE_TYPE" = \''.$store_type.'\'';
    }*/

    if($store_type != 'all'){
      $statement = 'SELECT DISTINCT CMS."STORE_CODE", CMT."STORE_NAME", TO_CHAR(CMS."SALES_DATE", \'DD-Mon-YY\') "SALES_DATE_V", CMS."SALES_DATE" FROM CDC_MASTER_SLP2 CMS, CDC_MASTER_TOKO CMT WHERE CMS."STORE_CODE" = CMT."STORE_CODE" AND CMS."BRANCH_CODE" = \''.str_replace(' ', '', $branch_code).'\' AND CMS."SALES_DATE" BETWEEN \''.$start.'\' AND \''.$end.'\' AND CMT."STORE_TYPE" = \''.$store_type.'\'
      UNION
      SELECT DISTINCT CMS."STORE_CODE", CMT."STORE_NAME", TO_CHAR(CMS."SALES_DATE", \'DD-Mon-YY\') "SALES_DATE_V", CMS."SALES_DATE" FROM CDC_MASTER_SLP CMS, CDC_MASTER_TOKO CMT WHERE CMS."STORE_CODE" = CMT."STORE_CODE" AND CMS."BRANCH_CODE" = \''.str_replace(' ', '', $branch_code).'\' AND CMS."SALES_DATE" BETWEEN \''.$start.'\' AND \''.$end.'\' AND CMT."STORE_TYPE" = \''.$store_type.'\' ORDER BY "STORE_CODE", "SALES_DATE"';
    }else{
      $statement = 'SELECT DISTINCT CMS."STORE_CODE", CMT."STORE_NAME", TO_CHAR(CMS."SALES_DATE", \'DD-Mon-YY\') "SALES_DATE_V", CMS."SALES_DATE" FROM CDC_MASTER_SLP2 CMS, CDC_MASTER_TOKO CMT WHERE CMS."STORE_CODE" = CMT."STORE_CODE" AND CMS."BRANCH_CODE" = \''.str_replace(' ', '', $branch_code).'\' AND CMS."SALES_DATE" BETWEEN \''.$start.'\' AND \''.$end.'\'
      UNION
      SELECT DISTINCT CMS."STORE_CODE", CMT."STORE_NAME", TO_CHAR(CMS."SALES_DATE", \'DD-Mon-YY\') "SALES_DATE_V", CMS."SALES_DATE" FROM CDC_MASTER_SLP CMS, CDC_MASTER_TOKO CMT WHERE CMS."STORE_CODE" = CMT."STORE_CODE" AND CMS."BRANCH_CODE" = \''.str_replace(' ', '', $branch_code).'\' AND CMS."SALES_DATE" BETWEEN \''.$start.'\' AND \''.$end.'\'  ORDER BY "STORE_CODE", "SALES_DATE"';
    }
    
    /*if ($store_type != 'all') {
      $statement .= ' AND CMT."STORE_TYPE" = \''.$store_type.'\'';
    }*/
    //$statement .= ' ORDER BY CMS."STORE_CODE", CMS."SALES_DATE"';

    return $this->db->query($statement)->result();
  }

  public function get_amount_slp($store_code,$sales_date){
    $stmt = 'SELECT "SALES_AMOUNT" FROM cdc_master_slp WHERE "STORE_CODE" = ? AND "SALES_DATE" = ?';

    return $this->db->query($stmt,array($store_code,$sales_date))->row();
  }

  public function get_amount_slp2($store_code,$sales_date){
    $stmt = 'SELECT "SALES_AMOUNT" FROM cdc_master_slp2 WHERE "STORE_CODE" = ? AND "SALES_DATE" = ?';

    return $this->db->query($stmt,array($store_code,$sales_date))->row();
  }

  public function insert_diff_tmp($st,$store_code,$store_name,$sales_date,$sales_amount,$actual_sales,$diff,$rrak_ded,$lessdep_ded,$payless_ded,$other_ded,$batch_num,$mutattion_date)
  {
    if ($st == '') {
      $st = 'NULL';
    }else $st = "'".$st."'";

    if ($batch_num == '') {
      $batch_num = 'NULL';
    }else $batch_num = "'".$batch_num."'";

    if ($mutattion_date == '') {
      $mutattion_date = 'NULL';
    }else $mutattion_date = "'".$mutattion_date."'";

    $statement = 'INSERT INTO cdc_sales_diff_tmp("STATUS","STORE_CODE","STORE_NAME","SALES_DATE","SALES_AMOUNT","ACTUAL_SALES_AMOUNT","DIFF_AMOUNT","RRAK_DEDUCTION","LESS_DEPOSIT_DEDUCTION","VIRTUAL_PAY_LESS_DEDUCTION","OTHERS_DEDUCTION","CDC_BATCH_NUMBER","MUTATION_DATE","CREATE_DATE","CREATE_BY") VALUES('.$st.',?,?,?,?,?,'.intval($diff).',?,?,?,?,'.$batch_num.','.$mutattion_date.',CURRENT_DATE,?)';
    $this->db->query($statement,array($store_code,$store_name,$sales_date,intval($sales_amount),intval($actual_sales),intval($rrak_ded),intval($lessdep_ded),intval($payless_ded),intval($other_ded),intval($this->session->userdata('usrId'))));
    return $this->db->affected_rows();
  }

  public function delete_slp_tmp($user_id)
  {
    $statement = 'DELETE FROM cdc_sales_diff_tmp WHERE "CREATE_BY" = ?';
    $this->db->query($statement,intval($user_id));
  }

  public function get_diff_tmp($user_id)
  {
    $statement = 'SELECT *, TO_CHAR("SALES_DATE", \'DD-Mon-YY\') "SALES_DATE_V", TO_CHAR("MUTATION_DATE", \'DD-Mon-YY\') "MUTATION_DATE_V" FROM cdc_sales_diff_tmp WHERE "CREATE_BY" = ? ORDER BY "STATUS", "STORE_CODE", "SALES_DATE"';
    return $this->db->query($statement,intval($user_id))->result();
  }

  public function get_data_deposit($star,$end,$deposit_num)
  {
    $statement = 'SELECT * FROM CDC_TRX_DEPOSIT WHERE TO_CHAR("DEPOSIT_DATE", \'YYYY-MM-DD\') BETWEEN ? AND ?';
    if ($deposit_num != 'X') {
      $statement .= ' AND "CDC_DEPOSIT_NUM" LIKE \'%'.$deposit_num.'%\'';
    }
    return $this->db->query($statement,array($star,$end))->result();
  }

  public function get_data_deposit_by_batch($star,$end,$batch_num,$deposit_num)
  {
    $statement = 'SELECT CTD.*, CTB."CDC_BATCH_NUMBER" FROM CDC_TRX_DEPOSIT CTD, CDC_TRX_BATCHES CTB WHERE CTD."CDC_DEPOSIT_ID" = CTB."CDC_DEPOSIT_ID" AND TO_CHAR(CTD."DEPOSIT_DATE", \'YYYY-MM-DD\') BETWEEN ? AND ? AND CTB."CDC_BATCH_NUMBER" LIKE \'%'.$batch_num.'%\'';
    if ($deposit_num != 'X') {
      $statement .= ' AND CTD."CDC_DEPOSIT_NUM" LIKE \'%'.$deposit_num.'%\'';
    }
    return $this->db->query($statement,array($star,$end))->result();
  }

  public function get_batch_by_deposit($deposit_id,$batch_num)
  {
    $statement = 'SELECT * FROM CDC_TRX_BATCHES WHERE "CDC_DEPOSIT_ID" = ?';
    if ($batch_num != 'X') {
      $statement .= ' AND "CDC_BATCH_NUMBER" LIKE \'%'.$batch_num.'%\'';
    }
    return $this->db->query($statement,$deposit_id)->result();
  }

  public function get_detail_penambah($batch_id)
  {
    $statement = 'SELECT CTP.*, CMP."TRX_PLUS_NAME", CMT."STORE_CODE"||\' - \'||CMT."STORE_NAME" "STORE", TO_CHAR(CTR."SALES_DATE", \'DD-Mon-YYYY\') "SALES_DATE" FROM CDC_TRX_DETAIL_TAMBAH CTP, CDC_TRX_RECEIPTS CTR, CDC_MASTER_TOKO CMT, CDC_MASTER_DETAIL_PENAMBAH CMP WHERE CTP."TRX_PLUS_ID" = CMP."TRX_PLUS_ID" AND CTP."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = ?';
    return $this->db->query($statement,$batch_id)->result();
  }

  public function get_detail_pengurang($batch_id)
  {
    $statement = 'SELECT CTM.*, CMM."TRX_MINUS_NAME", CMT."STORE_CODE"||\' - \'||CMT."STORE_NAME" "STORE", TO_CHAR(CTR."SALES_DATE", \'DD-Mon-YYYY\') "SALES_DATE" FROM CDC_TRX_DETAIL_MINUS CTM, CDC_TRX_RECEIPTS CTR, CDC_MASTER_TOKO CMT, CDC_MASTER_DETAIL_PENGURANG CMM WHERE CTM."TRX_MINUS_ID" = CMM."TRX_MINUS_ID" AND CTM."TRX_CDC_REC_ID" = CTR."CDC_REC_ID" AND CTR."STORE_ID" = CMT."STORE_ID" AND CTR."CDC_BATCH_ID" = ?';
    return $this->db->query($statement,$batch_id)->result();
  }

}

/* End of file mod_report.php */
/* Location: ./application/models/mod_report.php */
