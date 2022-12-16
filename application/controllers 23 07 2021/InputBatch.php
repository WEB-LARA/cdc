<?php
  class InputBatch extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('Mod_input_batch');
      $this->load->model('Mod_login');
      $this->load->model('master/Mod_cdc_seq_table');

      if (!$this->session->userdata('shift_num') || $this->session->userdata('dc_code') == '') {
        $tag_chose_dc = '';
        $height = '170';

        if ($this->session->userdata('role_id') < 4) {
          $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
          if ($dc_type[0]->DC_TYPE == 'DCI') {
              $dc = $this->Mod_login->get_dc($this->session->userdata('dc_code'));
              $tag_chose_dc = '
                <tr>
                  <td style="min-width: 100px!important;">DC</td>
                  <td style="min-width: 150px!important;">
                    <select id="dc_shift_batch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                      <option value="'.$this->session->userdata('dc_code').'">'.$this->session->userdata('dc_code').'</option>';
              foreach ($dc as $key) {
                $tag_chose_dc .= '<option value="'.$key->DC_CODE.'">'.$key->DC_CODE.'</option>';
              }
              $tag_chose_dc .= '
                  </select>
                </td>
              </tr>';
              $height = '200';
            }
            $this->session->set_flashdata('form_shift_batch','
              <div id="form_shift_batch" class="easyui-window" title="Pilih Shift"  style="width:360px;height:'.$height.'px; padding:10px;"
                data-options="iconCls:\'icon-script\',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
                <div data-options="region:\'center\'">
                  <table>
                    <tr>
                      <td style="min-width: 100px!important;">No Sticker</td>
                      <td style="min-width: 150px!important;">
                        <input type="hidden" id="user_id_batch" value="'.$this->session->userdata('usrId').'">
                        <input class="easyui-textbox" type="text" id="ref_num_batch" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
                      </td>
                    </tr>
                    '.$tag_chose_dc.'
                    <tr>
                      <td style="min-width: 100px!important;">Shift</td>
                      <td style="min-width: 150px!important;">
                        <select id="col_shift_batch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                          <option value=""></option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td style="min-width: 100px!important;"></td>
                      <td style="min-width: 150px!important;">
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" id="sub_shift_batch" style="min-width:88px !important;min-height:30px !important;">Submit</a>
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-exit\'" id="sub_shift_logout" href="'.base_url().'login/logout" style="min-width:88px !important;min-height:30px !important;">Logout</a>
                      </td>
                    </tr>
                  </table>
                </div>
            </div>');
        }
      }
    }

    function index(){
      date_default_timezone_set("Asia/Bangkok");

      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
	    $data['subMenu'] = $this->Mod_sys_menu->getSub();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/

      $batch_reject = $this->Mod_input_batch->check_reject_batch($this->session->userdata('usrId'));

      if ($batch_reject > 0) {
        $this->session->set_flashdata('data_batch_reject','
          <div id="data_batch_reject" class="easyui-window" title="Data Batch Reject"  style="width:1000px;height:250px; padding:10px;"
            data-options="iconCls:\'icon-script\',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
            <div data-options="region:\'center\'">
              <table id="batch_reject"></table>
                  <div data-options="region:\'south\',split:false" style="height:50px">
                        Double Click to Choose.
                  </div>
            </div>
        </div>');
      }

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      //$this->load->view("main/main_body");
      $this->load->view("view_inputBatch");
      $this->load->view("main/main_footer");

      $this->load->view("input/view_inputPenggantian");
      $this->load->view("input/view_inputPenambah");
      $this->load->view("input/view_inputPenambahShift");
      $this->load->view("input/view_inputPengurang");
      $this->load->view("input/view_inputPengurangShift");
      $this->load->view("input/view_inputVoucher");
      $this->load->view("input/view_inputVoucherShift");
      $this->load->view("input/view_inputGTU");
      $this->load->view("input/view_inputSTL");
    }

    function get_data_kurset()
    {
      $result = $this->Mod_input_batch->getPraInputKurRec();
      echo json_encode($result);
    }

    function get_data_kurset_shift()
    {
      $result = $this->Mod_input_batch->getPraInputKurRecShift();
      echo json_encode($result);
    }

    function getPraInput(){
      $result['data'] = $this->Mod_input_batch->getPraInput();
      echo json_encode($result['data']);
    }
    function get_tipe_shift($store_code,$sales_date,$sales_flag)
    {
      $result = $this->Mod_input_batch->get_tipe_shift($store_code,$sales_date,$sales_flag);
      echo json_encode($result);
    }
    function getPraInputShift(){
      $result['data'] = $this->Mod_input_batch->getPraInputShift();
      echo json_encode($result['data']);
    }

    function getPraInputReject($batch_id){
      $result['data'] = $this->Mod_input_batch->getPraInputReject($batch_id);
      echo json_encode($result['data']);
    }

    function getPraInputRejectShift($batch_id){
      $result['data'] = $this->Mod_input_batch->getPraInputRejectShift($batch_id);
      echo json_encode($result['data']);
    }

    function getPraID(){
      $tbl = "praInputBatch";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);
      echo json_encode($id);
    }

    function getPraIDShift(){
      $tbl = "cdc_trx_receipts_shift";
      $id  = $this->Mod_cdc_seq_table->getID($tbl);
      echo json_encode(intval($id));
    }

    function getPraIDN(){
      $id = $this->Mod_cdc_seq_table->getIDN();
      echo $id;
    }

    function cekCIMBNIAGA(){
      $store_code = $this->input->post('store_code');
      $sales_date = $this->input->post('sales_date');
      $stn_flag = $this->input->post('stnFlag');
      $cek= $this->Mod_input_batch->cekCIMBNIAGA($store_code,$sales_date,$stn_flag);
      echo $cek;
    }

    function getPraIDNShift(){
      $id = $this->Mod_cdc_seq_table->getIDNShift();
      echo $id;
    }

    function Update_Receipt_Shift(){
      $data = $this->input->post('receiptID');
      $validate = $this->input->post('validate');
      $result = $this->Mod_input_batch->Update_Receipt_Shift($data,$validate);
      echo json_encode($result);
    }

    function Pindah_Data_Shift(){
      $data = $this->input->post('receiptID');
      $result= $this->Mod_input_batch->Pindah_Data_Shift($data);
      echo json_encode($result);
    }

     function cekTrxBefore(){

      $sales_date= $this->input->post('sales_date');
      $store_code= $this->input->post('store_code');

      $stn_flag= $this->input->post('stnFlag');
   
      $result= $this->Mod_input_batch->cekTrxBefore($sales_date,$store_code,$stn_flag);
      echo $result;
    }

    function getTotalDataSelect(){
      $id = json_decode($this->input->post('rec_id'));
      $result= $this->Mod_input_batch->getTotalDataSelect($id);
      echo intval($result);
    }

    function praInput(){
      $data = $this->input->post();
      $this->Mod_input_batch->praInput($data);
      echo "Entry ditambahkan !";
    }

    function praInputShift(){
      $data = $this->input->post();
      ini_set('max_execution_time', 300);
      $this->Mod_input_batch->praInputShift($data);
      echo "Entry ditambahkan !";
    }

    function praEditShift(){
      $data = $this->input->post();
      $this->Mod_input_batch->praInputShift($data);
      echo "Entry diupdate !";
    }


    function praEdit(){
      $data = $this->input->post();
      $this->Mod_input_batch->praInput($data);
      echo "Entry diupdate !";
    }

    function delPraInput($id){
      $this->Mod_input_batch->delPraInput($id);
      echo "Delete berhasil !";
    }

    function delPraInputShift($id,$id_rec,$no_shift){
      $this->Mod_input_batch->delPraInputShift($id,$id_rec,$no_shift);
      echo "Delete berhasil !";
    }

    function getDataDetail($id, $is_stn){
      $result= $this->Mod_input_batch->getDataDetail($id, $is_stn);
      echo json_encode($result);
    }

    function getDataDetailShift($id, $is_stn){
      $result= $this->Mod_input_batch->getDataDetailShift($id, $is_stn);
      echo json_encode($result);
    }

    function inputBatch(){
      //$tbl = "cdc_trx_batches";
      //$id  = $this->Mod_cdc_seq_table->getID($tbl);

      $data = $this->input->post();
      $batch_report = $this->Mod_input_batch->inputBatch($data);

      $sent_batch_report = implode("-",$batch_report);
      echo $sent_batch_report;
    }

    function getSysDate()
    {
      date_default_timezone_set("Asia/Bangkok");

      $curdate = date('dmY');
      echo $curdate;
    }

    function scanCodeSales()
    {
      $scan = $this->input->post('scan');
      $store = '';
      if (substr($scan, 0,1) == '1') {
        $store .= 'T';
      }
      elseif (substr($scan, 0,1) == '2') {
        $store .= 'F';
      }
      elseif (substr($scan, 0,1) == '3') {
        $store .= 'R';
      }
      $store .= chr(intval(substr($scan, 1,2))).chr(intval(substr($scan, 3,2))).chr(intval(substr($scan, 5,2)));
      echo $store;
    }

    function cek_sales(){
      $cek = $this->Mod_input_batch->cek_sales($this->input->post('store'),$this->input->post('tgl'));
    }

    function getTotalSetor(){
      $total = $this->Mod_input_batch->getTotalSetor();
      //var_dump($total);
      echo $total;
    }

    function getTotalSetorF(){
      $total = $this->Mod_input_batch->getTotalSetorF();
      //var_dump($total);
      echo $total;
    }

    function getGrandTotal(){
      $total = $this->Mod_input_batch->getGrandTotal();
      //var_dump($total);
      echo $total;
    }


    function getTotalSetorShift(){
      $total = $this->Mod_input_batch->getTotalSetorShift();
      //var_dump($total);
      echo $total;
    }

    function getTotalSetorFShift(){
      $total = $this->Mod_input_batch->getTotalSetorFShift();
      //var_dump($total);
      echo $total;
    }

    function getGrandTotalShift(){
      ini_set('max_execution_time', 300);
      $total = $this->Mod_input_batch->getGrandTotalShift();
      //var_dump($total);
      echo $total;
    }

    function getTotalSetorReject($batch_id){
      $total = $this->Mod_input_batch->getTotalSetorReject($batch_id);
      //var_dump($total);
      echo $total;
    }

    function getTotalSetorRejectShift($batch_id){
      $total = $this->Mod_input_batch->getTotalSetorRejectShift($batch_id);
      //var_dump($total);
      echo $total;
    }

    function getBatchType($batch_id)
    {
      $type = $this->Mod_input_batch->getBatchType($batch_id);
      echo str_replace(" ", "", $type);
    }

    function cekData(){
      $cek = $this->Mod_input_batch->cekData();
      echo $cek;
    }

    function cekDataShift(){
      $cek = $this->Mod_input_batch->cekDataShift();
      echo $cek;
    }

    function cekTanggal()
    {
      date_default_timezone_set("Asia/Bangkok");

      if ($this->input->post('sales_date')) {
        $date = date_create_from_format('d-m-Y', $this->input->post('sales_date'));
        if (date_format($date, 'Y-m-d') > date('Y-m-d')) {
          echo 0;
        }else{
          echo 1;
        }
      }
    }

    function resubmit_batch()
    {
      if ($this->input->post('batch_id')) {
        $batch_type = $this->Mod_input_batch->get_batch_type($this->input->post('batch_id'));
        $batch_id = $this->Mod_input_batch->resubmit_batch($this->input->post('batch_id'), $batch_type);
        echo $batch_id[0]->CDC_BATCH_NUMBER;
      }
    }

    function check_data_receipts()
    {
      $user_id = $this->input->post('user_id');
      $return = $this->Mod_input_batch->check_data_receipts($user_id);
      echo $return;
    }

    function printBatch($implode,$mode){
      date_default_timezone_set("Asia/Bangkok");

      $data = explode("-",$implode);
      $this->load->library('Pdf');
      $now = date('d-m-Y');
      $createdBy = $this->session->userdata('username');
      $branchCode = $this->session->userdata('branch_code');
      $this->load->model('master/Mod_cdc_master_branch');
      $branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);
      // create new PDF document
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor($createdBy);
      $pdf->SetTitle('Casher DC Batches Receipts');
      $pdf->SetSubject('Batches');

      // set default header data
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Cashier DC Batch Receipts', $branchCode.' - '.$branchName);
      //$pdf->setFooterData('testing1', 'coba1');

      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      // set some language-dependent strings (optional)
      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          $pdf->setLanguageArray($l);
      }

      // ---------------------------------------------------------

      // set default font subsetting mode
      $pdf->setFontSubsetting(true);

      // Set font
      // dejavusans is a UTF-8 Unicode font, if you only need to
      // print standard ASCII chars, you can use core fonts like
      // helvetica or times to reduce file size.
      $pdf->SetFont('helvetica', '', 8, '', true);

      // Add a page
      // This method has several options, check the source code documentation for more information.
      $pdf->AddPage('L','A3');

      $html = '<div align="center"> <b> Cashier DC Batch Receipts </b> </div> <br>';
      for($i=0; $i<count($data); $i++){
        //$html .= $data[$i];
        $headerData = $this->Mod_input_batch->getHeaderData($data[$i]);
        $html .= '<br>
          <table align="left">
            <tr>
              <td width="6%"> Batch Number </td>
              <td width="2%"> : </td>
              <td width="6%">'.trim($headerData->CDC_BATCH_NUMBER).' </td>
              <td width="25%"> </td>

              <td width="6%"> User </td>
              <td width="2%"> : </td>
              <td width="6%">'.trim($headerData->USER_NAME).' </td>
              <td width="25%"> </td>

              <td width="6%"> Input Time </td>
              <td width="2%"> : </td>
              <td width="6%">'.trim($headerData->INPUT_TIME).' </td>
            </tr>
            <tr>
              <td> Batch Date </td>
              <td> : </td>
              <td> '.trim($headerData->CDC_BATCH_DATE).'</td>
              <td> </td>

              <td> Reference Num </td>
              <td> : </td>
              <td> '.trim($headerData->CDC_REFF_NUM).' </td>
            </tr>
            <tr>
              <td> Type </td>
              <td> : </td>
              <td> '.trim($headerData->CDC_BATCH_TYPE).' </td>
              <td> </td>

              <td> Status </td>
              <td> : </td>
              <td> '.trim($headerData->CDC_BATCH_STATUS).' </td>
            </tr>
          </table>
          <br><br>
        ';


        $html .= '
          <table border="1">
            <tr>
              <td width="20px"> No. </td>
              <td width="3%"> Store Code </td>
              <td width="10%"> Store Name </td>
              <td> Sales Date </td>
              <td width="5%"> Act Cash+ Penggantian </td>
              <td> Actual RRAK </td>
              <td width="2%"> Kurset Date </td>
              <td> Actual Kurset </td>
              <td width="2%"> Virt Date </td>
              <td> Virtual Kurset </td>
              <td width="2%"> Vouc Date  </td>
              <td> Actual Voucher </td>
              <td width="2%"> NBH Date </td>
              <td> Actual Byr NBH </td>
              <td width="2%"> WU Date </td>
              <td> Actual WU </td>
              <td> Actual Lain-Lain </td>
              <td> Deskripsi Actual Lain-Lain </td>
              <td width="5%"> Total Actual </td>
              <td> Potongan RRAK </td>
              <td> Potongan Kurset </td>
              <td> Potongan Virtual </td>
              <td> Potongan Lain-Lain </td>
              <td> Deskripsi Pot Lain-Lain </td>
              <td> Input Time </td>
            </tr>

        ';

        $tableData = $this->Mod_input_batch->getTableData($data[$i]);
        $no_rec = 0;
        $ACTUAL_SALES_AMOUNT = 0;   $ACTUAL_RRAK = 0;       $ACTUAL_KURSET = 0;
        $ACTUAL_VIRTUAL_KURSET = 0; $ACTUAL_NBH = 0;        $ACTUAL_WU = 0;
        $ACTUAL_LAIN = 0;           $ACTUAL_TOTAL = 0;      $POTONGAN_RRAK = 0;
        $POTONGAN_KURSET = 0;       $POTONGAN_VIRTUAL = 0;  $POTONGAN_LAIN = 0;

        foreach ($tableData as $rec) {
          $no_rec++;
          $ACTUAL_SALES_AMOUNT    = $ACTUAL_SALES_AMOUNT    + $rec->ACTUAL_SALES_AMOUNT;
          $ACTUAL_RRAK            = $ACTUAL_RRAK            + $rec->ACTUAL_RRAK;
          $ACTUAL_KURSET          = $ACTUAL_KURSET          + $rec->ACTUAL_KURSET;
          $ACTUAL_VIRTUAL_KURSET  = $ACTUAL_VIRTUAL_KURSET  + $rec->ACTUAL_VIRTUAL_KURSET;
          $ACTUAL_NBH             = $ACTUAL_NBH             + $rec->ACTUAL_NBH;
          $ACTUAL_WU              = $ACTUAL_WU              + $rec->ACTUAL_WU;
          $ACTUAL_LAIN            = $ACTUAL_LAIN            + $rec->ACTUAL_LAIN;
          $ACTUAL_TOTAL           = $ACTUAL_TOTAL           + $rec->ACTUAL_TOTAL;
          $POTONGAN_RRAK          = $POTONGAN_RRAK          + $rec->POTONGAN_RRAK;
          $POTONGAN_KURSET        = $POTONGAN_KURSET        + $rec->POTONGAN_KURSET;
          $POTONGAN_VIRTUAL       = $POTONGAN_VIRTUAL       + $rec->POTONGAN_VIRTUAL;
          $POTONGAN_LAIN          = $POTONGAN_LAIN          + $rec->POTONGAN_LAIN;

          $html .='
            <tr>
              <td> '.$no_rec.' </td>
              <td> '.$rec->STORE_CODE.' </td>
              <td> '.$rec->STORE_NAME.' </td>
              <td> '.$rec->SALES_DATE.' </td>
              <td align="right"> '.number_format($rec->ACTUAL_SALES_AMOUNT, 0, '.', ',') .' </td>
              <td align="right"> '.number_format($rec->ACTUAL_RRAK, 0, '.', ',') .' </td>
              <td> - </td>
              <td align="right"> '.number_format($rec->ACTUAL_KURSET, 0, '.', ',') .' </td>
              <td> - </td>
              <td align="right"> '.number_format($rec->ACTUAL_VIRTUAL_KURSET, 0, '.', ',') .' </td>
              <td> - </td>
              <td> - </td>
              <td> - </td>
              <td align="right"> '.number_format($rec->ACTUAL_NBH, 0, '.', ',') .' </td>
              <td> - </td>
              <td align="right"> '.number_format($rec->ACTUAL_WU, 0, '.', ',') .' </td>
              <td align="right"> '.number_format($rec->ACTUAL_LAIN, 0, '.', ',') .' </td>
              <td> - </td>
              <td align="right"> '.number_format($rec->ACTUAL_TOTAL, 0, '.', ',') .' </td>

              <td align="right"> '.number_format($rec->POTONGAN_RRAK, 0, '.', ',') .' </td>
              <td align="right"> '.number_format($rec->POTONGAN_KURSET, 0, '.', ',') .' </td>
              <td align="right"> '.number_format($rec->POTONGAN_VIRTUAL, 0, '.', ',') .' </td>
              <td align="right"> '.number_format($rec->POTONGAN_LAIN, 0, '.', ',') .' </td>
              <td> - </td>
              <td> '.$rec->INPUT_TIME.' </td>
            </tr>
          ';
        }

        $html .='
        <tr>
          <td colspan="3" align="center"> Total </td>
          <td colspan="2" align="right"> '.number_format($ACTUAL_SALES_AMOUNT, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($ACTUAL_RRAK, 0, '.', ',') .' </td>
          <td colspan="2" align="right"> '.number_format($ACTUAL_KURSET, 0, '.', ',') .' </td>
          <td colspan="2" align="right"> '.number_format($ACTUAL_VIRTUAL_KURSET, 0, '.', ',') .' </td>
          <td colspan="2" align="right">  </td>
          <td colspan="2" align="right"> '.number_format($ACTUAL_NBH, 0, '.', ',') .' </td>
          <td colspan="2" align="right"> '.number_format($ACTUAL_WU, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($ACTUAL_LAIN, 0, '.', ',') .' </td>
          <td>      </td>
          <td align="right"> '.number_format($ACTUAL_TOTAL, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($POTONGAN_RRAK, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($POTONGAN_KURSET, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($POTONGAN_VIRTUAL, 0, '.', ',') .' </td>
          <td align="right"> '.number_format($POTONGAN_LAIN, 0, '.', ',') .' </td>
          <td align="right">  </td>
          <td align="right">  </td>
        </tr>
        ';

        $html .='
          </table>
          <br> <br>
        ';

        $html .='<br> <br>
          Giro Tukar Uang : <br> <br>
          <table align="left">
            <tr>
              <td width="3%"> No </td>
              <td width="8%"> Check Num </td>
              <td width="8%" align="right"> Check Amount </td>
            </tr>
        ';

        $footerData = $this->Mod_input_batch->getFooterData($data[$i]);
        $no_gtu = 0;
        $tot_gtu = 0;
        foreach ($footerData as $gtu) {
          $no_gtu++;
          $tot_gtu = $tot_gtu + $gtu->CDC_GTU_AMOUNT;
          $html .='
            <tr>
              <td> '.$no_gtu.' </td>
              <td> '.$gtu->CDC_GTU_NUMBER.' </td>
              <td align="right"> '.number_format($gtu->CDC_GTU_AMOUNT, 0, '.', ',') .' </td>
            </tr>
          ';
        }

        $tot_setor = $ACTUAL_TOTAL - $tot_gtu;
        $html .= '
            <tr>
              <td> </td>
              <td> </td>
              <td align="right"> --------------------- </td>
            </tr>
            <tr>
              <td colspan="2" align="center"> Total </td>
              <td align="right"> '.number_format($tot_gtu, 0, '.', ',') .' </td>
            </tr>
            <tr>
              <td> </td>
              <td> </td>
              <td align="right"> --------------------- </td>
            </tr>
            <tr>
              <td colspan="2" align="center"> <b> Total Cash Yg Harus Disetor : </b> </td>
              <td align="right"> '.number_format($tot_setor, 0, '.', ',') .'</td>
            </tr>
          </table> <br>


        ';

        $html.= '<br><br><br> <br><br>';

        if (count($data)-1 != $i) {
          $html .= '<br pagebreak="true"/>';
        }
      }

      if ($mode == 'P') {
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        ob_end_clean();
        $pdf->Output('report_receipts_'.date('YmdHi').'.pdf', 'I');
      }else{
        $data['html'] = $html;
        $this->load->view('view_excel_batch', $data);
      }

      //============================================================+
      // END OF FILE
      //============================================================+
    }

    function printReceipts($id){
      $this->load->library('Pdf');
      $now = date('d-m-Y');
      //$id = $this->input->post();
      $receipts = $this->Mod_input_batch->getReceiptsData($id);
		//var_dump($receipts);
	  
      // create new PDF document
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor($this->session->userdata('username'));
      $pdf->SetTitle('Print Repeipts');
      $pdf->SetSubject('Repeipts');

      // set default header data
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Receipts List Report', $now);
      $pdf->setFooterData('testing1', 'coba1');

      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      // set some language-dependent strings (optional)
      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          $pdf->setLanguageArray($l);
      }

      // ---------------------------------------------------------

      // set default font subsetting mode
      $pdf->setFontSubsetting(true);

      // Set font
      // dejavusans is a UTF-8 Unicode font, if you only need to
      // print standard ASCII chars, you can use core fonts like
      // helvetica or times to reduce file size.
      $pdf->SetFont('helvetica', '', 8, '', true);

      // Add a page
      // This method has several options, check the source code documentation for more information.
      $pdf->AddPage('L','A4');

      $html = '
        <table border="1px" cellpadding="3px" align="center">
          <tr>
            <td width="3%"> <b> No. </b> </td>
            <td width="8%"> <b> Store <br> Code </b> </td>
            <td width="12%"> <b> Store Name </b> </td>
            <td width="10%"> <b> Tanggal <br> Sales </b> </td>
            <td width="14%"> <b> Cash+ <br> Penggantian </b> </td>
            <td width="14%"> <b> Total <br> Penambahan </b> </td>
            <td width="14%"> <b> Total <br> Actual Amount </b> </td>
            <td width="14%"> <b> Total <br> Pengurangan </b> </td>
            <td width="14%"> <b> Total <br> Voucher </b> </td>
          </tr>

      ';

      $i=1;
      foreach($receipts as $row){
        $html .= '
          <tr>
            <td width="3%" align="center">'.$i.'</td>
            <td width="8%">'.$row->STORE_CODE.'</td>
            <td width="12%">'.$row->STORE_NAME.'</td>
            <td width="10%">'.$row->SALES_DATE.'</td>
            <td width="14%">'.number_format($row->ACTUAL_SALES_AMOUNT, 0, '.', ',').'</td>
            <td width="14%">'.number_format($row->TOTAL_PENAMBAHAN, 0, '.', ',').'</td>
            <td width="14%">'.number_format($row->ACTUAL_AMOUNT, 0, '.', ',').'</td>
            <td width="14%">'.number_format($row->TOTAL_PENGURANGAN, 0, '.', ',').'</td>
            <td width="14%">0</td>
          </tr>
        ';
        $i++;
      }

      $html .= '
        </table>
       ';

      // Print text using writeHTMLCell()
      $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

      // ---------------------------------------------------------

      // Close and output PDF document
      // This method has several options, check the source code documentation for more information.
      ob_end_clean();
      $pdf->Output('report_receipts_'.date('YmdHi').'.pdf', 'I');

      //============================================================+
      // END OF FILE
      //============================================================+

    }

    public function cek_trx_detail()
    {
      $det_tambah = $this->Mod_input_batch->get_cek_det_tambah($this->input->post('rec_id'));
      $det_kurang = $this->Mod_input_batch->get_cek_det_kurang($this->input->post('rec_id'));

      if ($this->input->post('tambah') == '') {
        $tambah = 0;
      }else $tambah = intval($this->input->post('tambah'));

      if ($this->input->post('kurang') == '') {
        $kurang = 0;
      }else $kurang = intval($this->input->post('kurang'));

      if (intval($det_tambah) == $tambah && intval($det_kurang) == $kurang) {
        echo 1;
      }else echo 0;
    }

    public function cek_trx_detail_shift()
    {

          $det_tambah = $this->Mod_input_batch->get_cek_det_tambah_shift($this->input->post('rec_id'),$this->input->post('rec_id2'),$this->input->post('rec_id3'));
          $det_kurang = $this->Mod_input_batch->get_cek_det_kurang_shift($this->input->post('rec_id'),$this->input->post('rec_id2'),$this->input->post('rec_id3'));

          if ($this->input->post('tambah') == '') {
            $tambah = 0;
          }else $tambah = intval($this->input->post('tambah'));

          if ($this->input->post('kurang') == '') {
            $kurang = 0;
          }else $kurang = intval($this->input->post('kurang'));

          if (intval($det_tambah) == $tambah && intval($det_kurang) == $kurang) {
            echo 1;
          }else echo 0;
    }

    public function cek_batch_type($batch_id)
    {
      $result = $this->Mod_input_batch->cek_batch_type($batch_id);
      echo $result;
    }

    public function get_header_kurset($kurset_num, $branch_code)
    {
      $cek_rec = $this->Mod_input_batch->cek_header_rec_kurset($kurset_num);
      $cek = $this->Mod_input_batch->cek_header_kurset($kurset_num);
      if ($cek == 0 && $cek_rec == 0) {
        echo file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_header_kurset/'.strtoupper($kurset_num).'/'.$branch_code);
      }elseif ($cek > 0 && $cek_rec > 0) {
        echo file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_header_kurset/'.strtoupper($kurset_num).'/'.$branch_code);
      }elseif ($cek > 0 && $cek_rec == 0) {
        echo 'X';
      }
    }

    public function get_lines_kurset($kurset_id,$kurset_num)
    {
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

      $from_cdc = $this->Mod_input_batch->get_kurset_lines($kurset_num, $page, $rows);

      if ($from_cdc['total'] > 0) {
        echo json_encode($from_cdc);
      }else{
        $data = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_lines_kurset/'.$kurset_id);
        $obj_data = json_decode($data);
        if ($obj_data && count($obj_data) > 0) {
          foreach ($obj_data as $line) {
            $this->Mod_input_batch->insert_kurset_lines($line->TRX_AR_NUMBER,$line->FIS_TRX_LINE_ID,$line->STORE_CODE,$line->TRX_AR_DATE,$line->TRX_AR_DESC,$line->TRX_AR_AMOUNT,$line->ACTUAL_AMOUNT,$kurset_num,$line->TRX_AR_TYPE,$line->TEMPLATE_FLAG);
          }

          $after_insert = $this->Mod_input_batch->get_kurset_lines($kurset_num, $page, $rows);
          echo json_encode($after_insert);
        }
      }
    }

    public function get_total_line($kurset_num)
    {
      $result = $this->Mod_input_batch->get_total_line($kurset_num);
      echo $result;
    }

    public function update_actual_amount($line_id,$amount)
    {
      $result = $this->Mod_input_batch->update_actual_amount($line_id,$amount);
      echo $result;
    }

    public function set_receipt_kurset($ttk_num, $amount_all, $trf, $acc_id, $mut_date)
    {
      $ttk_num = strtoupper($ttk_num);
      $cek_rec = $this->Mod_input_batch->cek_header_rec_kurset($ttk_num);
      $cek = $this->Mod_input_batch->cek_header_kurset($ttk_num);

        /*
            $amount di bawah diisi nominal control yang di input oleh user
          */
	  
      if ($cek == 0 && $cek_rec == 0) 
	  {
        $data_lines = $this->Mod_input_batch->get_lines_amount_kurset($ttk_num);
        $amt_cont = $amount_all;
        foreach ($data_lines as $lines) 
		    {
          if ($amt_cont - $lines->TRX_AR_AMOUNT >= 0) 
		      {
            $this->Mod_input_batch->update_actual_amount_lines($lines->CDC_KURSET_LINE_ID, $lines->TRX_AR_AMOUNT);
          }
		      else $this->Mod_input_batch->update_actual_amount_lines($lines->CDC_KURSET_LINE_ID, $amt_cont);
		  
		  
          $amt_cont -= $lines->TRX_AR_AMOUNT;
          if ($amt_cont < 0) 
		      {
            $amt_cont = 0;
          }
        }

        $store_code = substr($ttk_num, strpos($ttk_num, '-')+1, 4);
        $store_id = $this->Mod_input_batch->get_store_id_kurset($store_code);
       // $rec_id = $this->Mod_input_batch->get_rec_id_for_kurset();
        $rec_id = $this->Mod_cdc_seq_table->getIDN();

        $amount = $this->Mod_input_batch->get_all_amount_kurset($ttk_num);
        
        $res = $this->Mod_input_batch->insert_rec_kurset_shift($rec_id,$store_id->STORE_ID,$amount,$trf,$acc_id,$mut_date);
        //$res = $this->Mod_input_batch->insert_rec_kurset($rec_id,$store_id->STORE_ID,$amount,$trf,$acc_id,$mut_date);
        if ($res > 0) {
          $resup = $this->Mod_input_batch->update_data_lines($ttk_num,$rec_id);
          if ($resup > 0) {
            echo $res;
          } else echo 0;
        } else echo 0;
      } 
		elseif ($cek > 0 && $cek_rec > 0) 
		{
        	$data_lines = $this->Mod_input_batch->get_lines_amount_kurset($ttk_num);
        	$amt_cont = $amount_all;
			
       	 	foreach ($data_lines as $lines) 
			{
          		if ($amt_cont - $lines->TRX_AR_AMOUNT >= 0) 
				{
            		$this->Mod_input_batch->update_actual_amount_lines($lines->CDC_KURSET_LINE_ID, $lines->TRX_AR_AMOUNT);
          		}
				else 
				$this->Mod_input_batch->update_actual_amount_lines($lines->CDC_KURSET_LINE_ID, $amt_cont);
				
          		$amt_cont -= $lines->TRX_AR_AMOUNT;
          		if ($amt_cont < 0) 
				{
            		$amt_cont = 0;
          		}
        	}

        	$store_code = substr($ttk_num, strpos($ttk_num, '-')+1, 4);
        	$store_id = $this->Mod_input_batch->get_store_id_kurset($store_code);
        	//$rec_id = $this->Mod_input_batch->get_rec_id_for_kurset();
        	$rec_id = $this->Mod_cdc_seq_table->getIDN();

        	$amount = $this->Mod_input_batch->get_all_amount_kurset($ttk_num);
        

        	$res = $this->Mod_input_batch->insert_rec_kurset_shift($rec_id,$store_id->STORE_ID,$amount,$trf,$acc_id,$mut_date);
        //$res = $this->Mod_input_batch->insert_rec_kurset($rec_id,$store_id->STORE_ID,$amount,$trf,$acc_id,$mut_date);
		
	        if ($res > 0)
			{
          		$resup = $this->Mod_input_batch->update_data_lines($ttk_num,$rec_id);
				
          		if ($resup > 0)
				{
            		echo $res;
          		}
				else echo 0;
				
        	} 
			else echo 0;
			
      	}
		elseif ($cek > 0 && $cek_rec == 0) 
		{
        	echo 0;
      	} 
		else echo 0;
    }

    public function get_master_stl()
    {
      $result = $this->Mod_input_batch->get_master_stl();
      echo json_encode($result);
    }

    public function save_data_stl()
    {
      if ($this->input->post()) {
        $data = $this->input->post();
        $data['rec_id'] = $data['stl_id'] != '' ? $data['rec_id'] : $this->Mod_cdc_seq_table->getIDN();
        $data['store_id'] = $this->Mod_input_batch->get_store_id_ktr();

        $result_detail = $this->Mod_input_batch->save_data_stl($data);
        
        if ($result_detail > 0) {
          $result_detail += $this->Mod_input_batch->save_data_receipt($data);
        }

        if ($result_detail > 1) {
          echo 1;
        } else {
          echo 0;
        }
      } else {
        echo 0;
      }
    }

    public function get_data_stl()
    {
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      $page = $page == 0 ? 1 : $page;
      $result = $this->Mod_input_batch->get_data_stl($page, $rows);
      echo json_encode($result);
    }

    public function delete_data_stl()
    {
      $result = 0;
      if ($this->input->post()) {
        $data = $this->input->post();
        $result = $this->Mod_input_batch->delete_stl_receipt($data);
      }
      echo $result;
    }

  }
?>
