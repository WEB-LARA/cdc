<?php
  class InputDeposit extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('login'));
      }
      $this->load->model('Mod_deposit');
      $this->load->model('Mod_login');

      $tag_chose_dc = '';

      $shift = $this->Mod_login->check_shift($this->session->userdata('usrId'));

      if ($this->session->userdata('role_id') < 4) {
        if ($shift) {
          $this->session->set_userdata('shift_num',$shift[0]->SHIFT_NUMBER);
          $this->session->set_userdata('no_ref',$shift[0]->NO_REF);
        }else{
          $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
          if ($dc_type[0]->DC_TYPE == 'DCI') {
            $dc = $this->Mod_login->get_dc($this->session->userdata('dc_code'));
            $tag_chose_dc = '
              <tr>
                <td style="min-width: 100px!important;">DC</td>
                <td style="min-width: 150px!important;">
                  <select id="col_shift" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                    <option value="'.$this->session->userdata('dc_code').'">'.$this->session->userdata('dc_code').'</option>';
            foreach ($dc as $key) {
              $tag_chose_dc .= '<option value="'.$key->DC_CODE.'">'.$key->DC_CODE.'</option>';
            }
            $tag_chose_dc .= '
                </select>
              </td>
            </tr>';
          }
          $this->session->set_flashdata('form_shift','
            <div id="form_shift" class="easyui-window" title="Pilih Shift"  style="width:360px;height:170px; padding:10px;"
              data-options="iconCls:\'icon-script\',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
              <div data-options="region:\'center\'">
                <table>
                  <tr>
                    <td style="min-width: 100px!important;">No Sticker</td>
                    <td style="min-width: 150px!important;">
                      <input type="hidden" id="user_id" value="'.$this->session->userdata('usrId').'">
                      <input class="easyui-textbox" type="text" id="ref_num" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
                    </td>
                  </tr>
                  '.$tag_chose_dc.'
                  <tr>
                    <td style="min-width: 100px!important;">Shift</td>
                    <td style="min-width: 150px!important;">
                      <select id="col_shift" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
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
                      <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" id="sub_shift" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
                    </td>
                  </tr>
                </table>
              </div>
          </div>');
        }
      }

    }

    function index(){
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      $data['bank'] = $this->Mod_deposit->getBank();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
      $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      /*$this->load->view("main/main_body");*/
      $this->load->view('main/main_deposit', $data);
    }

    public function inquiry_deposit()
    {
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      $data['bank'] = $this->Mod_deposit->getBank();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
      $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      /*$this->load->view("main/main_body");*/
      $this->load->view('main/main_deposit_validate',$data);
    }

    public function validate_virtual()
    {
      $data['user'] = $this->Mod_login->getData();
      $data['menu'] = $this->Mod_sys_menu->getMenu();
      $data['subMenu'] = $this->Mod_sys_menu->getSub();
      $data['bank'] = $this->Mod_deposit->getBank();
      /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/
      $data['role'] = $this->session->userdata('role_id');

      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
      /*$this->load->view("main/main_body");*/
      $this->load->view('view_validate_virtual',$data);
    }

    public function get_data_kurset_virtual()
    {
      $deposit_num = $this->input->post('dep_num') != '' ? $this->input->post('dep_num') : '-';
      $result = $this->Mod_deposit->get_data_kurset_virtual($deposit_num);
      echo json_encode($result);
    }

    public function get_batch_type(){
      $depid=$this->input->post('depid');
      $result = $this->Mod_deposit->get_batch_type($depid);
      echo $result;
    }

    public function cek_vir_status_deposit()
    {
      if ($this->input->post()) {
        $result = $this->Mod_deposit->cek_vir_status_deposit($this->input->post('dep_num'));
        if ($result) {
          if ($result[0]->TRANSFER_FLAG == 'Y') {
            echo 'T';
          }else {
            if ($result[0]->VIR_STATUS == 'N') {
              echo 'N';
            }else echo 'Y'.$result[0]->CDC_DEPOSIT_ID;
          }
        }else echo 'C';
      }
    }

    public function validate_vir()
    {
      if ($this->input->post()) {
        $data = $this->input->post('data');
        $count = 0;
        $dep_id = 0;
        $this->Mod_deposit->reset_status_vir($data[0]['CDC_DEPOSIT_ID']);
        foreach ($data as $key) {
          $count += $this->Mod_deposit->validate_vir($key['TRX_DETAIL_MINUS_ID'], $key['TRX_CDC_REC_ID'], $key['CDC_BATCH_ID'], $key['CDC_DEPOSIT_ID'], 'Y');
          $dep_id = $key['CDC_DEPOSIT_ID'];
        }
        $count += $this->Mod_deposit->update_deposit_vir($dep_id);

        echo $count;
      }
    }

    public function unvalidate_vir_all($deposit_id)
    {
      $data = $this->Mod_deposit->get_data_kurset_virtual_by_id($deposit_id);
      $count = 0;
      foreach ($data as $key) {
        $count += $this->Mod_deposit->validate_vir($key->TRX_DETAIL_MINUS_ID, $key->TRX_CDC_REC_ID, $key->CDC_BATCH_ID, $key->CDC_DEPOSIT_ID, 'N');
      }
      $count += $this->Mod_deposit->update_deposit_vir($deposit_id);

      echo $count;
    }

    public function cek_validasi_virtual()
    {
      if ($this->input->post()) {
        $result = $this->Mod_deposit->cek_validasi_virtual($this->input->post('depid'));
        echo $result[0]->VIR_STATUS;
      }
    }

    public function get_deskripsi_kur_virtual($det_id)
    {
      $result = $this->Mod_deposit->get_deskripsi_kur_virtual($det_id);
      echo json_encode($result);
    }

    public function update_deskripsi_virtual()
    {
      $result = 0;
      if ($this->input->post()) {
        $data = $this->input->post();
        $result += $this->Mod_deposit->update_deskripsi_virtual($data['det_id'], $data['deskripsi']);
      }
      echo $result;
    }

    public function print_data_kurset_virtual($deposit_num, $format)
    {
      if ($format == 'P') {
        $this->load->library('Pdf');
        $now = date('d-m-Y');
        $branchCode = $this->session->userdata('branch_code');
        $this->load->model('master/Mod_cdc_master_branch');
        $branchName = $this->Mod_deposit->get_cabang_session($this->session->userdata('branch_id'));

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->session->userdata('username'));
        $pdf->SetTitle('Listing Data Kurset Virtual');
        $pdf->SetSubject('');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Listing Data Kurset Virtual', 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(15, 18, 15);
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

        $pdf->setFontSubsetting(true);

        $pdf->SetFont('helveticaB', '', 20, '', true);

        $pdf->AddPage('P','A4');

        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(0, 0, 0, 0);

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        // test Cell stretching
        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->Cell(0, 0, 'No. Deposit : '.urldecode($deposit_num), 0, 1, 'C', 0, '', 0);
        $pdf->Ln(4);

        // Multicell test
        $pdf->SetFont('helveticaB', '', 8, '', true);
        $pdf->MultiCell(8, 9, 'No.', 1, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(40, 9, 'Store', 1, 'C', 0, 0, '', '', true);
        $pdf->MultiCell(20, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
        $pdf->MultiCell(57, 9, 'Description', 1, 'C', 0, 0, '', '', true);
        $pdf->MultiCell(20, 9, 'Date', 1, 'C', 0, 0, '', '', true);
        $pdf->MultiCell(25, 9, 'Amount', 1, 'C', 0, 0, '', '', true);
        $pdf->MultiCell(10, 9, 'Cek', 1, 'C', 0, 0, '', '', true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->Ln();

        $data = $this->Mod_deposit->get_data_listing_val_vir(urldecode($deposit_num));
        $no = 1;
        $num = 1;
        $total = 0;

        foreach ($data as $virtual) {
          if ($no++ % 26 == 0) {
            $pdf->AddPage('P','A4');
            $pdf->SetFont('helveticaB', '', 8, '', true);
            $pdf->MultiCell(8, 9, 'No.', 1, 'L', 0, 0, '', '', true);
            $pdf->MultiCell(40, 9, 'Store', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(20, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(57, 9, 'Description', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(20, 9, 'Date', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(25, 9, 'Amount', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(10, 9, 'Cek', 1, 'C', 0, 0, '', '', true);
            $pdf->SetFont('helvetica', '', 8, '', true);
            $pdf->Ln();
          }
          $pdf->MultiCell(8, 9, $num++, 1, 'L', 0, 0, '', '', true);
          $pdf->MultiCell(40, 9, $virtual->STORE, 1, 'C', 0, 0, '', '', true);
          $pdf->MultiCell(20, 9, $virtual->CDC_BATCH_NUMBER, 1, 'C', 0, 0, '', '', true);
          $pdf->MultiCell(57, 9, $virtual->TRX_MINUS_DESC, 1, 'C', 0, 0, '', '', true);
          $pdf->MultiCell(20, 9, $virtual->MIN_DATE, 1, 'C', 0, 0, '', '', true);
          $pdf->MultiCell(25, 9, number_format($virtual->TRX_MINUS_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
          $pdf->MultiCell(10, 9, '', 1, 'C', 0, 0, '', '', true);
          $pdf->Ln();
          $total += $virtual->TRX_MINUS_AMOUNT;
        }

        $pdf->SetFont('helveticaB', '', 8, '', true);
        $pdf->MultiCell(145, 9, 'Total', 1, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(25, 9, number_format($total, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
        $pdf->MultiCell(10, 9, '', 1, 'C', 0, 0, '', '', true);
        $pdf->Ln();

        ob_end_clean();
        $pdf->Output('validate_virtual'.date('YmdHi').'.pdf', 'I');

      } elseif ($format == 'C') {
        $now = date('d-m-Y');
        $branchCode = $this->session->userdata('branch_code');
        $this->load->model('master/Mod_cdc_master_branch');
        $branchName = $this->Mod_deposit->get_cabang_session($this->session->userdata('branch_id'));

        $html = '
          <table>
            <tr>
              <td>Listing Data Kurset Virtual</td>
            </tr>
            <tr>
              <td>Branch :</td>
              <td>'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME).'</td>
            </tr>
            <tr>
              <td>No. Deposit :</td> 
              <td>'.urldecode($deposit_num).'</td>
            </tr>
          </table>
          <br>
          <table>
            <tr>
              <th>No.</th>
              <th>Store</th>
              <th>Batch Number</th>
              <th>Description</th>
              <th>Date</th>
              <th>Amont</th>
              <th>Cek</th>
            </tr>';
        $data = $this->Mod_deposit->get_data_listing_val_vir(urldecode($deposit_num));
        $no = 0;
        $total = 0;
        foreach ($data as $virtual) {
          $no++;
          $html .= '
            <tr>
              <td>'.$no.'</td>
              <td>'.$virtual->STORE.'</td>
              <td>'.$virtual->CDC_BATCH_NUMBER.'</td>
              <td>'.$virtual->TRX_MINUS_DESC.'</td>
              <td>'.$virtual->MIN_DATE.'</td>
              <td>'.$virtual->TRX_MINUS_AMOUNT.'</td>
              <td> <td>
            </tr>';
          $total += $virtual->TRX_MINUS_AMOUNT;
        }
         $html .= '
            <tr>
              <td colspan="5">Total<td>
              <td>'.$total.'</td>
              <td> <td>
            </tr>
          </table>
        ';

        $data['html'] = $html;
        $this->load->view('view_excel_valvir', $data);
      }
    }

    public function get_data_batch($id_bank,$id_deposit = 0)
    {
      if ($id_deposit == 0) {
        $return = $this->Mod_deposit->get_data_batch($id_bank,$id_deposit);
        echo json_encode($return);
      }
      else{
        $return = $this->Mod_deposit->get_data_batch($id_bank,$id_deposit);
        echo json_encode($return);
      }
    }

    public function get_data_batch_val($id_bank,$id_deposit)
    {
      $return = $this->Mod_deposit->get_data_batch_val($id_bank,$id_deposit);
      echo json_encode($return);
    }

    public function getBank()
    {
      return $this->Mod_deposit->getBank();
    }

    public function getBankSc()
    {
      echo json_encode($this->Mod_deposit->getBank());
    }

    public function get_single_bank($id_bank)
    {
      echo json_encode($this->Mod_deposit->get_single_bank($id_bank));
    }

    public function get_sum_amount()
    {
      $ata = 0;
      $cta = 0;
      $dta = 0;
      $rows = $this->input->post('rows');
      foreach ($rows as $key) {
        $ata = $ata + floatval($key['actual_total_amount']);
        $cta = $cta + floatval($key['check_exchanges_total_amount']);
        $dta = $dta + floatval($key['deposit']);
      }
      $ret['ata'] = $ata;
      $ret['cta'] = $cta;
      $ret['dta'] = $dta;
      echo json_encode($ret);
    }

    public function save_data_deposit($dep_id)
    {
      echo $this->Mod_deposit->save_data_deposit($this->input->post(),$dep_id);
    }



    public function get_data_deposit()
    {
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      if($this->input->post('page') == 0){
        $page = 1;
      }
      $bank_id = 0;
      $deposit_num = '';
      $deposit_date = '';
      $mutation_date = '';
      $username = '';
      if ($this->input->post('bank_sc')) {
        $bank_id = $this->input->post('bank_sc');
      }
      if ($this->input->post('deposit_num_sc')) {
        $deposit_num = $this->input->post('deposit_num_sc');
      }
      if ($this->input->post('deposit_date_sc')) {
        $deposit_date = $this->input->post('deposit_date_sc');
      }
      if ($this->input->post('mutation_date_sc')) {
        $mutation_date = $this->input->post('mutation_date_sc');
      }
      if ($this->input->post('username_sc')) {
        $username = $this->input->post('username_sc');
      }
      $return['rows'] = $this->Mod_deposit->get_data_deposit($page,$rows,$deposit_num,$deposit_date,$mutation_date,$bank_id,$username);
      $return['total'] = $this->Mod_deposit->get_data_deposit_rows();
      //var_dump($return);
      echo json_encode($return);
    }

    public function validate_deposit()
    {
      echo $this->Mod_deposit->validate_deposit($this->input->post('depid'));
    }

    public function delete_deposit()
    {
      echo $this->Mod_deposit->delete_deposit($this->input->post('depid'));
    }

    public function reject_deposit()
    {
      echo $this->Mod_deposit->reject_deposit($this->input->post('depid'));
    }

    public function get_data_deposit_validate()
    {
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      if($this->input->post('page') == 0){
        $page = 1;
      }
      $bank_id = 0;
      $deposit_num = '';
      $deposit_date = '';
      $mutation_date = '';
      $status = '';
      $username = '';
      if ($this->input->post('bank_sc')) {
        $bank_id = $this->input->post('bank_sc');
      }
      if ($this->input->post('deposit_num_sc')) {
        $deposit_num = $this->input->post('deposit_num_sc');
      }
      if ($this->input->post('deposit_date_sc')) {
        $deposit_date = $this->input->post('deposit_date_sc');
      }
      if ($this->input->post('mutation_date_sc')) {
        $mutation_date = $this->input->post('mutation_date_sc');
      }
      if ($this->input->post('status_sc')) {
        $status = $this->input->post('status_sc');
      }
      if ($this->input->post('username_sc')) {
        $username = $this->input->post('username_sc');
      }
      $return = $this->Mod_deposit->get_data_deposit_validate($page,$rows,$deposit_num,$deposit_date,$mutation_date,$bank_id,$status,$username);
      // $return['total'] = $this->Mod_deposit->get_data_deposit_validate_rows();
      //var_dump($return);
      echo json_encode($return);
    }

    public function print_deposit($deposit_id,$bank_id,$format)
    {
      $this->load->library('Pdf');
      
      $status = $this->Mod_deposit->get_status_deposit($deposit_id);
      $cetakan_ke = $this->Mod_deposit->get_print_count($deposit_id) + 1;

      if ($status == 'V') {
        if ($format == 'P') {
          $this->Mod_deposit->up_cetakan_deposit($deposit_id,$cetakan_ke);
        }
      }

      $branch_name = $this->Mod_deposit->get_branch_name($this->session->userdata('branch_id'));
      $bank_name = $this->Mod_deposit->get_single_bank($bank_id);
      $data = $this->Mod_deposit->get_report_deposit($deposit_id,$bank_id);
      $deposit_num = $this->Mod_deposit->get_deposit_num($deposit_id);
      $date_c = new DateTime($deposit_num[0]->DEPOSIT_DATE);
      $date_pickup = date_format($date_c, 'd-M-Y H:i');
      //print_r($bank_name);exit;
		
		//print_r($data);
      // create new PDF document
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor($this->session->userdata('username'));
      $pdf->SetTitle('Print Deposit');
      $pdf->SetSubject('Deposit');

      // set default header data
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Daftar Rekening Tujuan pada Layanan Cash Pick Up Service', $bank_name[0]->name, array(0,0,0), array(0,0,0));
      $pdf->setFooterData(array(0,0,0), array(0,0,0));  

      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA)); 
      $pdf->SetKeywords('TCPDF, PDF, example, test, guide');  

      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);  

      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, '18', PDF_MARGIN_RIGHT);
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
      $pdf->AddPage();
      date_default_timezone_set("Asia/Jakarta");

      // Set some content to print
      if (($status == 'V' || $status == 'T') && $cetakan_ke == 1) {
        $html = '
        <table cellpadding="5px" align="left">
          <tr>
            <td width="86%"></td>
            <td width="14%"><b>Validated</b></td>
          </tr>';
      }
      elseif (($status == 'V' || $status == 'T') && $cetakan_ke > 1) {
        $html = '
        <table cellpadding="5px" align="left">
          <tr>
            <td width="65%"></td>
            <td width="35%"><b>Cetakan Copy Ke.'.$cetakan_ke.' ('.date('d-M-Y H:i').') </b></td>
          </tr>';
      }
      elseif ($status == 'N') {
        $html = '
        <table cellpadding="5px" align="left">
          <tr>
            <td width="86%"></td>
            <td width="14%"><b>Not Valid.</b></td>
          </tr>';
      }

      $html .= '
          <tr>
            <td width="15%">Nama Perusahaan</td>
            <td width="3%">:</td>
            <td width="82%">PT. Indomarco Prismatama</td>
          </tr>
          <tr>
            <td width="15%">Lokasi Pickup</td>
            <td width="3%">:</td>
            <td width="82%">PT. Indomarco Prismatama - Cabang '.$branch_name[0]->BRANCH_NAME.'</td>
          </tr>
          <tr>
            <td width="15%">Tgl Pickup</td>
            <td width="3%">:</td>
            <td width="82%">'.$date_pickup.'</td>
          </tr>
        </table>
        <div></div>
        <table cellpadding="5px" align="left">
          <tr>
            <td width="5%" align="center"></td>
            <td width="20%" align="center"></td>
            <td width="31%" align="center"></td>
            <td width="44%" align="left"><b>OR. NO. '.$deposit_num[0]->CDC_DEPOSIT_NUM.'</b></td>
          </tr>
        </table>
        <table cellpadding="5px" border="1">
          <tr>
            <td width="5%" align="center"><b>No</b></td>
            <td width="20%" align="center"><b>No Rekening</b></td>
            <td width="31%" align="center"><b>Nama Pemilik Rekening</b></td>
            <td width="20%" align="center"><b>Nominal</b></td>
            <td width="10%" align="center"><b>Berita</b></td>
            <td width="14%" align="center">
              <table cellpadding="2px">
                <tr>
                  <td width="100%" align="center"><b>Ket *)</b></td>
                </tr>
                <tr>
                  <td width="50%" align="center"><b>CAC</b></td>
                  <td width="50%" align="center"><b>PYC</b></td>
                </tr>
              </table>
            </td>
          </tr>
      ';

      $i = 1;
      $sum = 0;
      foreach ($data['frc'] as $key) {
        $html .= '
          <tr>
            <td width="5%" align="center">'.$i.'</td>
            <td width="20%">'.$key->BANK_ACCOUNT_NUM.'</td>
            <td width="31%">'.$key->BANK_ACCOUNT_NAME.'</td>
            <td width="20%" align="right">'.number_format($key->nominal, 0, '.', ',').'</td>
            <td width="10%"></td>
            <td width="7%"></td>
            <td width="7%"></td>
          </tr>';
          $i++;
          $sum = $sum + $key->nominal;
      }

      foreach ($data['reg'] as $key) {
        $html .= '
          <tr>
            <td width="5%" align="center">'.$i.'</td>
            <td width="20%">'.$key->BANK_ACCOUNT_NUM.'</td>
            <td width="31%">'.$key->BANK_ACCOUNT_NAME.'</td>
            <td width="20%" align="right">'.number_format($key->nominal, 0, '.', ',').'</td>
            <td width="10%"></td>
            <td width="7%"></td>
            <td width="7%"></td>
          </tr>';
          $i++;
          $sum = $sum + $key->nominal;
      }

      $html .= '
        <tr>
            <td width="56%" align="left"><b>Total : </b></td>
            <td width="20%" align="right"><b>'.number_format($sum, 0, '.', ',').'</b></td>
            <td width="10%" align="center"></td>
            <td width="7%"></td>
            <td width="7%"></td>
          </tr>
      </table><div><br></div>
      <table cellpadding="5px" border="1">
        <tr>
          <td width="60%" align="center"><b>Tanda Tangan Nasabah **)</b></td>
        </tr>
        <tr>
          <td width="20%" align="center"><b>1</b></td>
          <td width="40%" align="center"><b>2</b></td>
        </tr>
        <tr>
          <td width="20%" align="center" height="60px"></td>
          <td width="40%" align="center" height="60px"></td>
        </tr>
      </table>
      <div><br></div>
      <table cellpadding="5px" border="1">
        <tr>
          <td width="60%" align="center"><b>Tanda Tangan Petugas '.$bank_name[0]->name.' ***)</b></td>
        </tr>
        <tr>
          <td width="20%" align="center"><b>CAC</b></td>
          <td width="40%" align="center"><b>PYC</b></td>
        </tr>
        <tr>
          <td width="20%" align="center" height="60px"></td>
          <td width="40%" align="center" height="60px"></td>
        </tr>
      </table>
      <div><br></div>
      <table cellpadding="2px">
        <tr>
          <td width="100%" align="left">*) diisi oleh pihak '.$bank_name[0]->name.'</td>
        </tr>
        <tr>
          <td width="100%" align="left">**) sesuai dengan PIC yang telah didaftarkan sebelumnya (yang juga TTD di OR) serta dibubuhi stempel nasabah</td>
        </tr>
        <tr>
          <td width="100%" align="left">***) dibubuhi dengan stempel '.$bank_name[0]->name.'</td>
        </tr>
      </table>
      ';

      if ($format == 'P') {
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);  

        // ---------------------------------------------------------  

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        ob_end_clean();
        $pdf->Output('report_deposit_'.date('YmdHi').'.pdf', 'I'); 
      }else {
        $data['html'] = $html;
        $this->load->view('view_excel_deposit', $data);
      }

      //============================================================+
      // END OF FILE
      //============================================================+
    }

    public function print_deposit_other_format($deposit_id,$bank_id,$format)
    {
      $html = '';
      $branch_name = $this->Mod_deposit->get_branch_name($this->session->userdata('branch_id'));
      if ($format == 'Niaga') {
        $data = $this->Mod_deposit->get_report_other_format_deposit($deposit_id,$bank_id);
        $html = 'P_NOREK'."\t".'P_NAMA'."\t".'P_AMOUNT'."\t".'P_KOTA'."\t".'P_BANK'."\t".'P_CABANG'."\t".'P_REMARK'."\n";
        foreach ($data['frc'] as $dep) {
          $html .= "''".$dep->BANK_ACCOUNT_NUM."'\t".$dep->BANK_ACCOUNT_NAME."\t".$dep->NOMINAL."\t".$branch_name[0]->BRANCH_NAME."\t".str_replace('BANK ', '', $dep->BANK_NAME)."\t"."\t".$dep->REMARK."\n";
        }
        foreach ($data['reg'] as $dep) {
          $html .= "''".$dep->BANK_ACCOUNT_NUM."'
          \t".$dep->BANK_ACCOUNT_NAME."\t".$dep->NOMINAL."\t".$branch_name[0]->BRANCH_NAME."\t".str_replace('BANK ', '', $dep->BANK_NAME)."\t"."\t".$dep->REMARK."\n";
        }
      }

      $data['html'] = $html;
      $this->load->view('view_export_csv', $data);
    }

    public function cek_deposit_num()
    {
      if ($this->input->post()) {
        return $this->Mod_deposit->cek_deposit_num($this->input->post('dep_num'));
      }
    }


    public function get_tipe_shift($branch_code,$store_code,$rec_id){
      return $this->Mod_deposit->get_tipe_shift($branch_code,$store_code,$rec_id);


    }

    public function get_flag_final($tipe_shift,$branch_code,$store_code,$sales_date,$rec_id){
      echo $this->Mod_deposit->get_flag_final($tipe_shift,$branch_code,$store_code,$sales_date,$rec_id);
    }


    public function transfer_deposit()
    {
      date_default_timezone_set("Asia/Jakarta");
      $result = 0;

      if ($this->input->post()) {
        $this->load->library('ftp');
        
        $config['hostname'] = 'ftpfadidm.indomaret.lan';
        //  $config['username'] = 'ftpdevba';
        // $config['password'] = 'ftpdevba';
        // $config['username'] = 'ftpdevba';
        // $config['password'] = 'ftpdevba';
       $config['username'] = 'ftpfinbu';
        $config['password'] = 'New!dom4r@1dm';
        $config['port']     = 21;
        $config['debug']    = TRUE;
        $config['passive']  = TRUE;
       // $path_lokal='C://xampp/CDC_LOCAL/';
        $path_lokal = '/opt/CDC_LOCAL/';
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
			    
         // $list_dir = $this->ftp->list_files('/u01/budev/interface_ba/');
		      $list_dir = $this->ftp->list_files('/u01/bu/interface_data/');

          foreach ($list_dir as $dir) {
            if (trim($this->session->userdata('branch_code')) == substr($dir, 23, 3)) {

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
                          $jumlah_shift=$this->Mod_deposit->get_jumlah_shift($store_code)->TOTAL_SHIFT;
                          $data_final = $this->Mod_deposit->get_flag_final($store_code,$tgl_sales);
                          if ($data_final) {
                            foreach ($data_final as $fnl) {
                                fwrite($fp, 'FNL|'.$fnl->STORE_CODE.'|'.$fnl->SALES_DATE.'|'.$jumlah_shift."\n");
                            }
                          }
                      }

                  
                  }
                  


                  fclose($fp);
                 // $path_lokal2='C:\xampp\CDC_LOCAL\\';
                  $path_lokal2 = '/opt/CDC_LOCAL/';
                  if ($this->ftp->upload($path_lokal.$file_name, $dir."/cdc/DATA_CDC/".$file_name, 'binary', 0777)) {
              
                     rename($path_lokal.$file_name, $path_lokal.'tmp/'.trim($this->session->userdata('branch_code')).'/'.$file_name);
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

    public function get_bank_stn()
    {
      ini_set('max_execution_time', 300);
      $result = $this->Mod_deposit->get_bank_stn();
      echo json_encode($result);
    }

    public function get_bank_account_stn($bank_id)
    {
      $result = $this->Mod_deposit->get_bank_account_stn($bank_id);
      echo json_encode($result);
    }

  }
?>
