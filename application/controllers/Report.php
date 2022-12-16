<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_report');
		$this->load->library('Pdf');
	//	$this->load->library('Curl');
	}

	public function index()
	{

	}

	public function choose_branch()
    {
    	$result = $this->Mod_report->choose_branch();
    	echo json_encode($result);
    }

	public function choose_dc($branch_id)
	{
		$result = $this->Mod_report->choose_dc($branch_id);
		echo json_encode($result);
	}

	public function choose_dc_user($branch_id)
	{
		$result = $this->Mod_report->choose_dc_user($branch_id);
		echo json_encode($result);
	}

	public function choose_store_mtr($branch_id)
	{
		$result = $this->Mod_report->choose_store_mtr($branch_id);
		echo json_encode($result);
	}

	public function choose_store_mtr_shift($branch_id)
	{
		$result = $this->Mod_report->choose_store_mtr_shift($branch_id);
		echo json_encode($result);
	}

	public function choose_role()
	{
		$result = $this->Mod_report->choose_role();
		echo json_encode($result);
	}

	public function choose_am($branch_id)
    {
    	$branch_code = $this->Mod_report->get_branch_code($branch_id);
    	$result = $this->Mod_report->choose_am($branch_code->BRANCH_CODE);
    	echo json_encode($result);
    }


    public function choose_store()
    {
    	$result = $this->Mod_report->choose_store();
    	echo json_encode($result);
    }

    public function choose_store2($branch_id)
    {
    	$result = $this->Mod_report->choose_store2($branch_id);
    	echo json_encode($result);
    }

    public function cek_data_pending_setor_toko()
    {

    	$cabang = $this->input->post('cabang');
      	$start_date = $this->input->post('start_date');
      	$end_date = $this->input->post('end_date');
    	$result = $this->Mod_report->cek_data_pending_setor_toko($cabang,$start_date,$end_date);
    	echo json_encode($result);
    }

	public function print_bar_code($text,$text2)
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		//$pdf->SetAuthor($userName);
		//$pdf->SetTitle('Report Monitoring Setoran Dan a Sales');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		// set a barcode on the page footer
		$pdf->setBarcode(date('Y-m-d H:i:s'));
		// define barcode style
		$style = array(
		    'position' => '',
		    'align' => 'C',
		    'stretch' => false,
		    'fitwidth' => true,
		    'cellfitalign' => '',
		    'border' => true,
		    'hpadding' => 'auto',
		    'vpadding' => 'auto',
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255),
		    'text' => true,
		    'font' => 'helvetica',
		    'fontsize' => 8,
		    'stretchtext' => 4
		);
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('P','A4');

		// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
		$pdf->Cell(0, 0, $text, 0, 1);
		$pdf->write1DBarcode($text, $text2, '', '', '', 18, 0.4, $style, 'N');

		$pdf->Ln();
		$pdf->Output('example_027.pdf', 'I');
	}

	public function kurset_per_shift($branch_id,$store_id,$tglawal,$tglakhir){
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branch = $this->Mod_report->get_cabang_session($branch_id);
		$store = $this->Mod_report->get_store_by_id($store_id);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Monitoring Setoran Dana Sales');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		$str_store = ($store) ? trim($store->STORE_CODE).' - '.trim($store->STORE_NAME) : 'ALL - ALL';

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
		$pdf->Ln(10);

		$pdf->SetFont('helveticaB', '', 15, '', true);
		$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
		$pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 11, '', true);
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(7);
		$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 7, '', true);
		$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();

		$page = 1;

		if($store_id == 0){ // start if store 0
			$total_shift1 = 0;
			$total_shift2 = 0;
			$total_shift3 = 0;
			$total_sales = 0;
			

			$store_data = $this->Mod_report->get_store_by_branch($branch_id);

			if($store_data){//start if store data
				foreach ($store_data as $sd) { // start loop data store
					$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $tglawal, $tglakhir);
					$count = 0;
					$no = 1;
					$subtotal_shift1 = 0;
					$subtotal_shift2 = 0;
					$subtotal_shift3 = 0;
					$subtotal_sales = 0;

					if($data_slp){//start if data slp
						foreach ($data_slp as $slp) {//start loop data slp
							$slp_date = date_create($slp->SALES_DATE);
							$receipt = $this->Mod_report->get_receipt_by_slp_shift($sd->STORE_CODE, $slp->SALES_DATE);
						
							$sales_shift1 = 0;
							$sales_shift2 = 0;
							$sales_shift3 = 0;
							$sales_harian = 0;
							$stn_f;
							$shift_flag;
							$selisih_sales1 = 0;
							$selisih_sales2 = 0;
							$selisih_sales3 = 0;
							$kurset = 0;
							$kurset1 = 0;
							$kurset2 = 0;
							$kurset3 = 0;
							$lebset = 0;
							$lebset1 = 0;
							$lebset2 = 0;
							$lebset3 = 0;
							$slp_shift1 = $slp->SHIFT1;
							$slp_shift2 = $slp->SHIFT2;
							$slp_shift3 = $slp->SHIFT3;
							$pemegang1 = '';
							$pemegang2 = '';
							$pemegang3 = '';
							$pemegangh = '';

							if ($receipt) {//start if receipt
									foreach ($receipt as $data_rec) {//start loop receipt

									if($data_rec->SHIFT_FLAG == 'Y'){
										if($data_rec->NO_SHIFT == '1'){
										$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;


										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp;
										}

										if($selisih_sales1 > 0){
											$kurset1 = $selisih_sales1;
										}
										elseif ($selisih_sales1 < 0) {
											$lebset1 = abs($selisih_sales1);
										}
									}	
									else if($data_rec->NO_SHIFT == '2'){
										$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang2 = $pemegangtmp;
										}


										if($selisih_sales2 > 0){
											$kurset2 = $selisih_sales2;
										}
										elseif ($selisih_sales2 < 0) {
											$lebset2 = abs($selisih_sales2);
										}
									}
									else if($data_rec->NO_SHIFT == '3'){
										$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang3 = $pemegangtmp;
										}


										if($selisih_sales3 > 0){
											$kurset3 =$selisih_sales3;
										}
										elseif ($selisih_sales3 < 0) {
											$lebset3 = abs($selisih_sales3);
										}
									}
									else{
										$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,1);

										$pemegangtmp2 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,2);

										$pemegangtmp3 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,3);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp;
										}

										if($pemegangtmp2){
											$pemegang2 = $pemegangtmp;
										}

										if($pemegangtmp3){
											$pemegang3 = $pemegangtmp;
										}


										if ($selisih_sales > 0) {
											$kurset = $selisih_sales;
										} elseif ($selisih_sales < 0) {
											$lebset = abs($selisih_sales);
										}
									}

									$stn_f = $data_rec->STN_FLAG;
									$shift_flag = $data_rec->SHIFT_FLAG;
									$rec_date = date_create($data_rec->CREATION_DATE);
								}//end if shift flag y
							  }//end loop receipt
							}// end if receipt

							$subtotal_shift1 += $kurset1;
							$subtotal_shift2 += $kurset2;
							$subtotal_shift3 += $kurset3;
							$subtotal_sales += $kurset1+$kurset2+$kurset3;

							$total_shift1 += $kurset1;
							$total_shift2 += $kurset2;
							$total_shift3 += $kurset3;
							$total_sales += $kurset1+$kurset2+$kurset3;

							if($kurset1 != 0  || $kurset2 != 0  || $kurset3 != 0 || $kurset != 0){
										//$pdf->AddPage('P','A4');
								if($count == 0){
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(246, 0, trim($sd->STORE_CODE).' - '.trim($sd->STORE_NAME), 1, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$page++;
										$count++;
										if($page == 20){// start if page 0
											$pdf->AddPage('L','A4');
											$pdf->SetFont('helveticaB', '', 15, '', true);
											$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
											$pdf->Ln(4);
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(7);
											$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helveticaB', '', 7, '', true);
											$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page = 1;
									}// end if page 0
								}//end $count
									$pdf->SetFont('helvetica', '', 7, '', true);
									$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(18, 0, number_format($kurset1+$kurset2+$kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang1,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang2,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang3,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$page++;
									if($page == 20){// start if page 1
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 1	

							}//end if kurset


							
						}//end loop data slp

							if($count > 0){
								$pdf->SetFont('helveticaB', '',7, '', true);
									$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(33, 4.2, 'Sub Total Per Toko Idm.', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(18, 0, number_format($subtotal_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($subtotal_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($subtotal_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($subtotal_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$page++;
									if($page == 20){ // start if page 2
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 2	
							}//end count
							
					}//end if data slp
				}//end loop data store
							$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							$page++;
							if($page == 20){ // start if page 3
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 15, '', true);
								$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 11, '', true);
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page = 1;
						}// end if page 3

							/*$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();*/
			}//end if store data
		}// end if store 0
		else{ // start if store != 0
			if($store){//start if store
				$total_shift1 = 0;
				$total_shift2 = 0;
				$total_shift3 = 0;
				$total_sales = 0;

				$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($store->STORE_CODE), $tglawal, $tglakhir);
					$no = 1;
					$count = 0;
					$subtotal_shift1 = 0;
					$subtotal_shift2 = 0;
					$subtotal_shift3 = 0;
					$subtotal_sales = 0;
					
					
					
					if($data_slp){//start data slp
						foreach ($data_slp as $slp) {//start loop data slp
						
						$pemegang1='';
						$pemegang2='';
						$pemegang3='';
						
						$kurset1 = 0;
						$kurset2 = 0;
						$kurset3 = 0;
						
								$slp_date = date_create($slp->SALES_DATE);
								$receipt = $this->Mod_report->get_receipt_by_slp_shift($store->STORE_CODE, $slp->SALES_DATE);
							
//								echo "<br>".$store->STORE_CODE.' - '.$slp->SALES_DATE."<br>";

								if ($receipt) {//start if receipt
									$sales_shift1 = 0;
									$sales_shift2 = 0;
									$sales_shift3 = 0;
									$sales_harian = 0;
									$stn_f;
									$shift_flag;
									$selisih_sales1 = 0;
									$selisih_sales2 = 0;
									$selisih_sales3 = 0;
									$kurset = 0;
									$kurset1 = 0;
									$kurset2 = 0;
									$kurset3 = 0;
									$lebset = 0;
									$lebset1 = 0;
									$lebset2 = 0;
									$lebset3 = 0;
									$slp_shift1 = $slp->SHIFT1;
									$slp_shift2 = $slp->SHIFT2;
									$slp_shift3 = $slp->SHIFT3;
									


									foreach ($receipt as $data_rec) {//start loop receipt
									
//									echo '<br>'.$store->STORE_CODE.' - '.$slp->SALES_DATE.' - '.$data_rec->NO_SHIFT;
									
									if($data_rec->SHIFT_FLAG == 'Y'){
										if($data_rec->NO_SHIFT == '1'){
										$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp;
										}
										
										if($selisih_sales1 > 0){
											$kurset1 =$selisih_sales1;
										}
										elseif ($selisih_sales1 < 0) {
											$lebset1 = abs($selisih_sales1);
										}
									}	
									else if($data_rec->NO_SHIFT == '2'){
										$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang2 = $pemegangtmp;
										}

										if($selisih_sales2 > 0){
											$kurset2 = $selisih_sales2;
										}
										elseif ($selisih_sales2 < 0) {
											$lebset2 = abs($selisih_sales2);
										}
									}
									else if($data_rec->NO_SHIFT == '3'){
										$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);
										
										/*echo '<br>'.$store->STORE_CODE.' - '.$slp->SALES_DATE.' - '.$data_rec->NO_SHIFT;*/
										
//										echo '<br>PEMEGANG ='.$pemegangtmp;

										if($pemegangtmp){
											$pemegang3 = $pemegangtmp;
										}

										if($selisih_sales3 > 0){
											$kurset3 =$selisih_sales3;
										}
										elseif ($selisih_sales3 < 0) {
											$lebset3 = abs($selisih_sales3);
										}
									}
									else{
										$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,1);

										$pemegangtmp2 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,2);

										$pemegangtmp3 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,3);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp;
										}

										if($pemegangtmp2){
											$pemegang2 = $pemegangtmp;
										}

										if($pemegangtmp3){
											$pemegang3 = $pemegangtmp;
										}


										if ($selisih_sales > 0) {
											$kurset = $selisih_sales;
										} elseif ($selisih_sales < 0) {
											$lebset = abs($selisih_sales);
										}
									}

									$stn_f = $data_rec->STN_FLAG;
									$shift_flag = $data_rec->SHIFT_FLAG;
									$rec_date = date_create($data_rec->CREATION_DATE);
								}// end if receipt
							  }//end if shift flag y
							}//end loop receipt

							$subtotal_shift1 += $kurset1;
							$subtotal_shift2 += $kurset2;
							$subtotal_shift3 += $kurset3;
							$subtotal_sales += $kurset1+$kurset2+$kurset3+$kurset;

							$total_shift1 += $kurset1;
							$total_shift2 += $kurset2;
							$total_shift3 += $kurset3;
							$total_sales += $kurset1+$kurset2+$kurset3;

							if($kurset1 != 0  || $kurset2 != 0  || $kurset3 != 0 || $kurset != 0){
									if($count == 0){
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(246, 0, trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$page++;
										$count++;
											if($page == 20){// start if page 0
												$pdf->AddPage('L','A4');
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$page = 1;
										}// end if page 0	
									}//end if count


									$pdf->SetFont('helvetica', '', 7, '', true);
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);

									$pdf->MultiCell(18, 0, number_format($kurset1+$kurset2+$kurset3+$kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, $pemegang1, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, $pemegang2, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, $pemegang3, 1, 'C', 0, 0, '', '', true);
									$pdf->ln();

									$page++;
									if($page == 20){// start if page 1
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 1
							}//end if kurset

							
						}//end loop data slp
							if($count > 0){
								$pdf->SetFont('helveticaB', '',7, '', true);
								$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(33, 4.2, 'Sub Total Per Toko Idm.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 0, number_format($subtotal_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							}//end if count
				}//end data slp
							$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->Ln();

							$page++;
							if($page == 20){// start if page 2
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 15, '', true);
								$pdf->Cell(0, 0, 'LAPORAN KURANG SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 11, '', true);
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page = 1;
						}// end if page 2
			}//end if store
		}// end if store != 0

		ob_end_clean();
		$pdf->Output('kurset_per_shift'.date('YmdHi').'.pdf', 'I');
	}
	
	// public function web_service($branch_code,$store_code,$sales_date,$deposit_date,$server){

	// 	$deposit_date=date( "Y-m-d", strtotime($deposit_date) );
 //        $url = 'http://fadigfs.indomaret.lan/rest_ci/CDC/WS_MonitorSalesCDC?format=json';
 //        $this->load->library('Curl'); 
 //        $data = array('BRANCH_CODE'=>$branch_code, 'STORE_CODE'=> $store_code,'SALES_DATE'=> $sales_date,'DEPOSIT_DATE'=> $deposit_date,'SERVER'=> $server);
	// 	$output = $this->curl->simple_post($url, $data,array('useragent' => true,'timeout'=>0,'returntransfer'=>true));

	// 	return $output;

	// }


	public function cek_absensi_sales(){

		$branch_id=$this->input->post('branch');
		$store_code=$this->input->post('store_code');
		$start_date=$this->input->post('start_date');
		$end_date=$this->input->post('end_date');
		$result=$this->Mod_report->cek_absensi_sales($branch_id,$store_code,$start_date,$end_date);

		   
      	echo json_encode($result);
		
			 
		

	}

	public function cek_report(){
		$branch_id=$this->input->post('branch_id');
		$start_date=$this->input->post('tgl_awal');
		$end_date=$this->input->post('tgl_akhir');
		$store_code=$this->input->post('store_code');
		$result=$this->Mod_report->cek_report($branch_id,$store_code,$start_date,$end_date);

		   
      	echo $result->cek;
		
			 
		

	}





	   public function laporan_sales_pertoko_shift($branch_id,$store_code,$tglawal,$tglakhir,$tampilan_cetak){

			date_default_timezone_set("Asia/Bangkok");
		   	$this->load->library('Pdf');
			set_time_limit(0);

			ini_set('memory_limit', '-1');
			
			$now = date('d-m-Y');
			$start_date = date_create($tglawal);
			$end_date = date_create($tglakhir);
			$time = date("H:i:s");
			$userName = $this->session->userdata('username');
			ini_set('max_execution_time',0);
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			$this->load->model('master/Mod_cdc_master_branch');
			//$pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
			//Add a custom size  
			
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Laporan Absens Sales Toko Idm per Shift');
			$pdf->SetSubject('');
			$pdf->setPrintHeader(false);
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetAutoPageBreak(false);

			$pdf->SetMargins(5, 10, 8);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//	$pdf->SetAutoPageBreak(TRUE, 0);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFontSubsetting(true);
			
			if(trim($tampilan_cetak)=='perCabang'){

							$branch = $this->Mod_report->get_cabang_session($branch_id);
							$statement= $this->Mod_report->loop_toko($branch_id,$store_code,$tglawal,$tglakhir);
							foreach ($statement as $store) {
								$pdf->AddPage('P','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Finance Receiving Regional IDM', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);

								
								$pdf->Ln();
								$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
							
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'Laporan Absen Sales Toko Idm per Shift', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Ln();
								$pdf->Cell(0, 0, '(Cetakan/Tampilan : per Cabang IDM)**', 0, 1, 'C', 0, '', 0);
								
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								if($branch_id!=100 && $branch_id!=0)
								{
									$pdf->MultiCell(50, 0, ' '.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME).'**', 0, 'L', 0, 0, '', '', true);
									$pdf->SetFont('helvetica', '', 8, '', true);
								}else{
									$pdf->MultiCell(50, 0, '000 - All Cabang'.'**', 0, 'L', 0, 0, '', '', true);
									$pdf->SetFont('helvetica', '', 8, '', true);
								}

						
								$pdf->Ln();
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(70, 0,' '.trim($store->STORE_CODE).' - '.trim($store->STORE_NAME).'**', 0, 'L', 0, 0, '', '', true);
							
								
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(8);
								
								$pdf->setCellPaddings(0.5, 0.5, 0.5, 0.5);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(8, 20.5, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 20.5, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 20.5, 'Nama Area Supv / Jr .Supv', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 20.5, 'Nama Area Mgr/Jr Mgr', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 20.5, 'Jumlah Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 5, 'Setoran Fisik (melalui Kodel)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$no=1;
								$begin = date_create($tglawal);
								$end = date_create($tglakhir);
								$pdf->MultiCell(184, 5, ' '.trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$ln=1;
								for($i = $begin; $i <= $end; $i->modify('+1 day')){
		    					//	echo $i->format("Y-m-d");
		    						$data=$this->Mod_report->get_data_absensi_sales_toko($branch_id,trim($store->STORE_CODE),$i->format("Y-m-d"));
									foreach ($data as $key) {
											$pdf->MultiCell(8,5,$no, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$i->format("d-m-Y"), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(30, 5, ''.$key->AS_SHORT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(30, 5, ''.$key->AM_SHORT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(11, 5, ''.$key->TOTAL_SHIFT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_1, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_2, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_3, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_1, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_2, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_3, 1, 'C', 0, 0, '', '', true);
											$no++;
											$ln++;
											$pdf->Ln();
											if($ln%32==0){
												$pdf->Ln();
												$pdf->AddPage('P','A4');
												$pdf->SetFont('helveticaB', '', 8, '', true);
												$pdf->MultiCell(50, 5,'Finance Receiving Regional IDM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
											
												
												$pdf->Ln();
												$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
											
												$pdf->Ln();
												$pdf->SetFont('helveticaB', '', 12, '', true);
												$pdf->Cell(0, 0, 'Laporan Absen Sales Toko Idm per Shift', 0, 1, 'C', 0, '', 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Ln();
												$pdf->Cell(0, 0, '(Cetakan/Tampilan : per Cabang IDM)**', 0, 1, 'C', 0, '', 0);
												
												$pdf->SetFont('helvetica', '', 10, '', true);
												$pdf->Ln();
												$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, ' '.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME).'**', 0, 'L', 0, 0, '', '', true);
												$pdf->SetFont('helvetica', '', 8, '', true);
										
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 10, '', true);
												$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(70, 0,' '.trim($store->STORE_CODE).' - '.trim($store->STORE_NAME).'**', 0, 'L', 0, 0, '', '', true);
											
												
												$pdf->Ln();
												$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(8);
												
												$pdf->setCellPaddings(0.5, 0.5, 0.5, 0.5);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(8, 20.5, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 20.5, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(30, 20.5, 'Nama Area Supv / Jr .Supv', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(30, 20.5, 'Nama Area Mgr/Jr Mgr', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(11, 20.5, 'Jumlah Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(90, 5, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 5, 'Setoran Fisik (melalui Kodel)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												
												$begin = date_create($tglawal);
												$end = date_create($tglakhir);
												$pdf->MultiCell(184, 5, ' '.trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$ln=1;
											}

									
									}
								}
								
							}


			}else if($tampilan_cetak=='perToko'){

							$branch = $this->Mod_report->get_cabang_session($branch_id);
							//$branch_id=$this->session->userdata('branch_id');
							$pdf->AddPage('P','A4');
							$toko = $this->Mod_report->get_detail_toko($store_code);
							$pdf->SetFont('helveticaB', '', 8, '', true);
							$pdf->MultiCell(50, 5,'Finance Receiving Regional IDM', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
						
							
							$pdf->Ln();
							$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
							$pdf->Ln();
							$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
						
							$pdf->Ln();
							$pdf->SetFont('helveticaB', '', 12, '', true);
							$pdf->Cell(0, 0, 'Laporan Absen Sales Toko Idm per Shift', 0, 1, 'C', 0, '', 0);
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->Ln();
							$pdf->Cell(0, 0, '(Cetakan/Tampilan : per Toko IDM)***', 0, 1, 'C', 0, '', 0);
							
							$pdf->SetFont('helvetica', '', 10, '', true);
							$pdf->Ln();
							$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(50, 0, ' '.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME).'  ***', 0, 'L', 0, 0, '', '', true);
							$pdf->SetFont('helvetica', '', 8, '', true);
					
							$pdf->Ln();
							$pdf->SetFont('helvetica', '', 10, '', true);
							$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(70, 0,' '.trim($toko->STORE_CODE).' - '.trim($toko->STORE_NAME).'  ***', 0, 'L', 0, 0, '', '', true);
						
							
							$pdf->Ln();
							$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
							$pdf->Ln(8);
							
							$pdf->setCellPaddings(0.5, 0.5, 0.5, 0.5);
							$pdf->SetFont('helveticaB', '', 7, '', true);
							$pdf->MultiCell(8, 20.5, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 20.5, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 20.5, 'Nama Area Supv / Jr .Supv', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 20.5, 'Nama Area Mgr/Jr Mgr', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(11, 20.5, 'Jumlah Shift', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(90, 5, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 5, 'Setoran Fisik (melalui Kodel)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							$no=1;
							$ln=1;
							$begin =  date_create($tglawal);
							$end = date_create($tglakhir);
							$pdf->MultiCell(184, 5, ''.trim($toko->STORE_CODE).' - '.trim($toko->STORE_NAME), 1, 'L', 0, 0, '', '', true);
							$pdf->Ln();
							for($i = $begin; $i <= $end; $i->modify('+1 day')){
		    					//	echo $i->format("Y-m-d");
		    						$data=$this->Mod_report->get_data_absensi_sales_toko($branch_id,$store_code,$i->format("Y-m-d"));
									foreach ($data as $key) {
											$pdf->MultiCell(8,5,$no, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$i->format("d-m-Y"), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(30, 5, ''.$key->AS_SHORT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(30, 5, ''.$key->AM_SHORT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(11, 5, ''.$key->TOTAL_SHIFT, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_1, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_2, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stj_shift_3, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_1, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_2, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, ''.$key->tgl_stn_shift_3, 1, 'C', 0, 0, '', '', true);
											$no++;
											$ln++;
											$pdf->Ln();

											if($ln%32==0){
													
													$pdf->AddPage('P','A4');
													$toko = $this->Mod_report->get_detail_toko($store_code);
													$pdf->SetFont('helveticaB', '', 8, '', true);
													$pdf->MultiCell(50, 5,'Finance Receiving Regional IDM', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
												
													
													$pdf->Ln();
													$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
												
													$pdf->Ln();
													$pdf->SetFont('helveticaB', '', 12, '', true);
													$pdf->Cell(0, 0, 'Laporan Absen Sales Toko Idm per Shift', 0, 1, 'C', 0, '', 0);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->Ln();
													$pdf->Cell(0, 0, '(Cetakan/Tampilan : per Toko IDM)***', 0, 1, 'C', 0, '', 0);
													
													$pdf->SetFont('helvetica', '', 10, '', true);
													$pdf->Ln();
													$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, ' '.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME).'  ***', 0, 'L', 0, 0, '', '', true);
													$pdf->SetFont('helvetica', '', 8, '', true);
											
													$pdf->Ln();
													$pdf->SetFont('helvetica', '', 10, '', true);
													$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(70, 0,' '.trim($toko->STORE_CODE).' - '.trim($toko->STORE_NAME).'  ***', 0, 'L', 0, 0, '', '', true);
												
													
													$pdf->Ln();
													$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
													$pdf->Ln(8);
													
													$pdf->setCellPaddings(0.5, 0.5, 0.5, 0.5);
													$pdf->SetFont('helveticaB', '', 7, '', true);
													$pdf->MultiCell(8, 20.5, 'No', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 20.5, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(30, 20.5, 'Nama Area Supv / Jr .Supv', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(30, 20.5, 'Nama Area Mgr/Jr Mgr', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(11, 20.5, 'Jumlah Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(90, 5, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(45, 5, 'Setoran Fisik (melalui Kodel)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(45, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(94, 5, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 1', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 2', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(15, 5, 'Tanggal Setor Shift 3', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln();
													$ln=1;
													$begin =  date_create($tglawal);
													$end = date_create($tglakhir);
													$pdf->MultiCell(184, 5, ''.trim($toko->STORE_CODE).' - '.trim($toko->STORE_NAME), 1, 'L', 0, 0, '', '', true);
													$pdf->Ln();
											}

									
									}
							}
							
							
			}
		//	$pdf->lastPage(); 
			$pdf->SetFont('helvetica', '', 6, '', true);
			$pdf->Cell(0, 0,  '* pilih salah satu', '', 0, 'L');
			$pdf->Ln();
			$pdf->Cell(0, 0,  '** diisi jika pilihan report adalah per Cabang IDM', '', 0, 'L');
			$pdf->Ln();		
			$pdf->Cell(0, 0,  '*** diisi jika pilihan report adalah per Toko IDM', '', 0, 'L');			
			$pdf->Ln();
			$pdf->Output('Laporan Absensi Sales Toko Idm per Shift'.date('YmdHi').'.pdf', 'I');
	   }





		public function mps($branch_id,$report_type,$tglawal,$tglakhir,$print){

		if($print=='pdf'){
			date_default_timezone_set("Asia/Bangkok");

		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

	
		$this->load->library('Pdf');
		set_time_limit(0);

		ini_set('memory_limit', '-1');
		
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');
		ini_set('max_execution_time',0);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$this->load->model('master/Mod_cdc_master_branch');
		//$pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
		//Add a custom size  
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Monitoring Setoran Dana Sales');
		$pdf->SetSubject('');
		$pdf->setPrintHeader(false);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(5, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, 0);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('P','A4');


		if($branch_id=='100'){
						$branch = $this->Mod_report->get_cabang_session($branch_id);
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'LAPORAN MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'All Cabang  ', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Tipe Report.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(70, 0, $report_type, 0, 'L', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->SetFont('helveticaB', '', 5, '', true);
						$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
			
						if($report_type=='Qty'){
							$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'REG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(255, 0, 0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,255);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(176,224,230);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,100,0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,255,0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);

						
							$pdf->Ln();
				
						}else{
							$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 5, 'REG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(255, 0, 0);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,255);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(176,224,230);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,100,0);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,255,0);
							$pdf->MultiCell(28, 5, '', 1, 'C', 1, 0, '', '', true);

							$pdf->Ln();
						}
						
						
						$page = 1;
						$header= $this->Mod_report->mps_header2('000');
						$no=1;
						$total_toko_tutup=0;
						$total_pending_hitung=0;
						$total_pending_jurnal=0;
						$total_clear=0;
						$total_pending_setor=0;
						$total_pending_deposit=0;
						foreach ($header as $key ) {
							$region=$key->region;
							$cabang=$key->branch_alt;
							$branch_code=$key->branch_code;
							$pending_setor=0;
							$web_service=0;
							$pending_hitung=0;
							$clear=0;
							$toko_tutup=0;
							$pending_deposit=0;
							$pending_jurnal=0;
							
							$header2=$this->Mod_report->monitoring_region($tglawal,$tglakhir,trim($key->branch_code),$report_type);
							if($header2){
								foreach ($header2 as $key2) {
									if($key2->STATUS=='PENDING DEPOSIT'){
										if(isset($key2->jumlah)){
											$total_pending_deposit+=$key2->jumlah;
											$pending_deposit=$key2->jumlah;	
										}else{
											$pending_deposit+=0;
										}
									}
									if($key2->STATUS=='PENDING HITUNG'){
										if(isset($key2->jumlah)){
											$total_pending_hitung+=$key2->jumlah;
											$pending_hitung=$key2->jumlah;	
										}else{
											$pending_hitung+=0;
										}
									}
									if($key2->STATUS=='PENDING SETOR'){
										if(isset($key2->jumlah)){
											$total_pending_setor+=$key2->jumlah;
											$pending_setor=$key2->jumlah;

										}else{
											$pending_setor+=0;
										}
									}
									if($key2->STATUS=='TOKO TUTUP'){
										if(isset($key2->jumlah)){

											$toko_tutup=$key2->jumlah;
											$total_toko_tutup+=$key2->jumlah;	
										}else{
											$toko_tutup+=0;
										}
									}
								
									if($key2->STATUS=='PENDING JURNAL'){
										if(isset($key2->jumlah)){

											$pending_jurnal+=$key2->jumlah;			
											$total_pending_jurnal+=$key2->jumlah;
										}else{
											$pending_jurnal+=0;
										}
									}if($key2->STATUS=='CLEAR'){
										if(isset($key2->jumlah)){

											$clear+=$key2->jumlah;			
											$total_clear+=$key2->jumlah;
										}else{
											$pending_clear+=0;
										}
									}
								}
								if($report_type=='Qty'){
									$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,$region, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,number_format($pending_hitung, 0, '.', ',') , 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format( $pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,number_format( $clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$no++;
									$pdf->Ln();
				
								}else{
									$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 5,$region, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 5,$cabang, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$no++;
									$pdf->Ln();

								}
								}else{
									if($report_type=='Qty'){
									$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,$region, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5,number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(20, 5, number_format($clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$no++;
									$pdf->Ln();
				
								}else{
									$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 5,$region, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 5,$cabang, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$pdf->MultiCell(28, 5,number_format($clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									$no++;
									$pdf->Ln();

								}
						


								}
							
							
							
							
						}
						
						if($report_type=='Qty'){
							$pdf->MultiCell(48, 5,'Total', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($total_clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									
									

						}else{
							$pdf->MultiCell(34, 5,'Total', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(28, 5,number_format($total_clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									
						}
					

		}else{			$branch = $this->Mod_report->get_cabang_session($branch_id);
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'LAPORAN MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Tipe Report.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(70, 0, $report_type, 0, 'L', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->SetFont('helveticaB', '', 5, '', true);
						$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
						
						

						if($report_type=='Qty'){
							$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'REG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(255, 0, 0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,255);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(176,224,230);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,100,0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,255,0);
							$pdf->MultiCell(20, 5, '', 1, 'C', 1, 0, '', '', true);

						
							$pdf->Ln();
				
						}else{
							$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'REG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(255, 0, 0);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,255);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(176,224,230);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,0,100,0);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);
							$pdf->SetFillColor(0,255,0);
							$pdf->MultiCell(25, 5, '', 1, 'C', 1, 0, '', '', true);

							$pdf->Ln();
						}
						

						
						
						$page = 1;
						$header= $this->Mod_report->mps_header2(($branch[0]->BRANCH_CODE));
						$clear=0;
						$pending_hitung=0;
						$toko_tutup=0;
						$pending_deposit=0;
						$pending_jurnal=0;
						$pending_setor=0;
						$web_service=0;
						$total_toko_tutup=0;
						$total_pending_deposit=0;
						$total_pending_jurnal=0;
						$total_clear=0;
						$total_pending_setor=0;
						$total_pending_deposit=0;
						$total_pending_hitung=0;

						foreach ($header as $key ) {
							$region=$key->region;
							$cabang=$key->branch_alt;
							$branch_code=$key->branch_code;
								$no=1;	
							$header2=$this->Mod_report->monitoring_region($tglawal,$tglakhir,trim($branch[0]->BRANCH_CODE),$report_type);

							if($header2){
									foreach ($header2 as $key2) {

									if($key2->STATUS=='PENDING DEPOSIT'){
										if(isset($key2->jumlah)){

											$pending_deposit+=$key2->jumlah;
											$total_pending_deposit+=$key2->jumlah;	
										}else{
											$pending_deposit+=0;
										}
									}
									if($key2->STATUS=='PENDING HITUNG'){
										if(isset($key2->jumlah)){

											$pending_hitung+=$key2->jumlah;
											$total_pending_hitung+=$key2->jumlah;	
										}else{
											$pending_hitung+=0;
										}
									}
									if($key2->STATUS=='PENDING SETOR'){
										if(isset($key2->jumlah)){

											$pending_setor+=$key2->jumlah;			
											$total_pending_setor+=$key2->jumlah;
										}else{
											$pending_setor+=0;
										}
									}
									if($key2->STATUS=='PENDING JURNAL'){
										if(isset($key2->jumlah)){

											$pending_jurnal+=$key2->jumlah;			
											$total_pending_jurnal+=$key2->jumlah;
										}else{
											$pending_jurnal+=0;
										}
									}if($key2->STATUS=='CLEAR'){
										if(isset($key2->jumlah)){

											$clear+=$key2->jumlah;			
											$total_clear+=$key2->jumlah;
										}else{
											$pending_clear+=0;
										}
									}
									if($key2->STATUS=='TOKO TUTUP'){
										if(isset($key2->jumlah)){

											$toko_tutup+=$key2->jumlah;			
											$total_toko_tutup+=$key2->jumlah;
										}else{
											$toko_tutup+=0;
										}
									}
									
								}
							
							
								
							
						}

							}
						
						if($report_type=='Qty'){
							$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$region, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$pdf->MultiCell(48, 5,'Total', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,number_format($total_pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5, number_format($total_clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									
									

						}else{
							$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$region, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$pdf->MultiCell(48, 5,'Total', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_toko_tutup, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_pending_setor, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_pending_hitung, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_pending_deposit, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_pending_jurnal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5,number_format($total_clear, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
									
						}
							
						
							
						

		}
		

		 $pdf->Output('Monitoring Penerimaan Sales'.date('YmdHi').'.pdf', 'I');

		}else{
			$nama_cabang = '';
			$no=1;
			$branch='';
			date_default_timezone_set("Asia/Bangkok");
			set_time_limit(0);

			ini_set('memory_limit', '-1');
		
			if($branch_id == 100){
					$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			$branch = $this->Mod_report->get_cabang_session($branch_id);
						
						
			$html = 'Laporan Monitoring Penerimaan Sales'."\n".'PT.INDOMARCO PRISMATAMA '."\n".''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($tglawal))).' s/d '.urldecode(date('d-M-Y',strtotime($tglakhir)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tipe Report : '.$report_type."\n".'Tgl Cetak : '.date('d-m-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$this->session->userdata('username')."\n";
				
			
			$html.="\n".'Keterangan Warna';
			$html.="\n".'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR';


			if($branch_id==100){
				
				
				$html .= "\n".'No;REG;CBG;TOKO TUTUP;PENDING SETOR;PENDING HITUNG;PENDING DEPOSIT;PENDING JURNAL;CLEAR'."\n";

				$header= $this->Mod_report->mps_header2('000');
				$no=1;
				$total_toko_tutup=0;
				$total_pending_hitung=0;
				$total_pending_jurnal=0;
				$total_clear=0;
				$total_pending_setor=0;
				$total_pending_deposit=0;
					foreach ($header as $key ) {
						$region=$key->region;
						$cabang=$key->branch_alt;
						$branch_code=$key->branch_code;
						$pending_setor=0;
						$web_service=0;
						$pending_hitung=0;
						$clear=0;
						$toko_tutup=0;
						$pending_deposit=0;
						$pending_jurnal=0;
						$header2=$this->Mod_report->monitoring_region($tglawal,$tglakhir,trim($key->branch_code),$report_type);


							if($header2){
								foreach ($header2 as $key2) {
									if($key2->STATUS=='PENDING DEPOSIT'){
										if(isset($key2->jumlah)){
											$total_pending_deposit+=$key2->jumlah;
											$pending_deposit=$key2->jumlah;	
										}else{
											$pending_deposit+=0;
										}
									}
									if($key2->STATUS=='PENDING HITUNG'){
										if(isset($key2->jumlah)){
											$total_pending_hitung+=$key2->jumlah;
											$pending_hitung=$key2->jumlah;	
										}else{
											$pending_hitung+=0;
										}
									}
									if($key2->STATUS=='PENDING SETOR'){
										if(isset($key2->jumlah)){
											$total_pending_setor+=$key2->jumlah;
											$pending_setor=$key2->jumlah;

										}else{
											$pending_setor+=0;
										}
									}
									if($key2->STATUS=='TOKO TUTUP'){
										if(isset($key2->jumlah)){

											$toko_tutup=$key2->jumlah;
											$total_toko_tutup+=$key2->jumlah;	
										}else{
											$toko_tutup+=0;
										}
									}
								
									if($key2->STATUS=='PENDING JURNAL'){
										if(isset($key2->jumlah)){

											$pending_jurnal+=$key2->jumlah;			
											$total_pending_jurnal+=$key2->jumlah;
										}else{
											$pending_jurnal+=0;
										}
									}if($key2->STATUS=='CLEAR'){
										if(isset($key2->jumlah)){

											$clear+=$key2->jumlah;			
											$total_clear+=$key2->jumlah;
										}else{
											$pending_clear+=0;
										}
									}
								}
								if($report_type=='Qty'){

									$html .= $no.';'.$region.';'.$cabang.';'. $toko_tutup.';'. $pending_setor.';'.$pending_hitung.';'.$pending_deposit.';'.$pending_jurnal.';'.$clear."\n";
									$no++;
				
								}else{
									$html .= $no.';'.$region.';'.$cabang.';'. $toko_tutup.';'.$pending_setor.';'.$pending_hitung.';'.$pending_deposit.';'.$pending_jurnal.';'.$clear."\n";
									$no++;
								}
								}else{
									if($report_type=='Qty'){
									$html .= $no.';'.$region.';'.$cabang.';'. $toko_tutup.';'. $pending_setor.';'.$pending_hitung.';'.$pending_deposit.';'.$pending_jurnal.';'.$clear."\n";
									$no++;
				
									}else{
										$html .= $no.';'.$region.';'.$cabang.';'. $toko_tutup.';'.$pending_setor.';'.$pending_hitung.';'.$pending_deposit.';'.$pending_jurnal.';'.$clear."\n";
										$no++;

									}
							


								}
							

					}
					
					 $html .= ';;Grand Total;'.$total_toko_tutup.';'.$total_pending_setor.';'.$total_pending_hitung.';'.$total_pending_deposit.';'.$total_pending_jurnal.';'.$total_clear.';';
						
					$cetak['html'] = $html;
					$cetak['file_name'] = 'Monitoring Penerimaan Sales_'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  

				}else{

						$html .= "\n".'No;REG;CBG;TOKO TUTUP;PENDING SETOR;PENDING HITUNG;PENDING DEPOSIT;PENDING JURNAL;CLEAR'."\n";

						$header= $this->Mod_report->mps_header2(($branch[0]->BRANCH_CODE));
						$clear=0;
						$pending_hitung=0;
						$toko_tutup=0;
						$pending_deposit=0;
						$pending_jurnal=0;
						$pending_setor=0;
						$web_service=0;
						$total_toko_tutup=0;
						$total_pending_deposit=0;
						$total_pending_jurnal=0;
						$total_clear=0;
						$total_pending_setor=0;
						$total_pending_deposit=0;
						$total_pending_hitung=0;
						$no=1;
						foreach ($header as $key ) {
							$region=$key->region;
							$cabang=$key->branch_alt;
							$branch_code=$key->branch_code;
								$no=1;	
							$header2=$this->Mod_report->monitoring_region($tglawal,$tglakhir,trim($branch[0]->BRANCH_CODE),$report_type);

							if($header2){
									foreach ($header2 as $key2) {

									if($key2->STATUS=='PENDING DEPOSIT'){
										if(isset($key2->jumlah)){

											$pending_deposit+=$key2->jumlah;
											$total_pending_deposit+=$key2->jumlah;	
										}else{
											$pending_deposit+=0;
										}
									}
									if($key2->STATUS=='PENDING HITUNG'){
										if(isset($key2->jumlah)){

											$pending_hitung+=$key2->jumlah;
											$total_pending_hitung+=$key2->jumlah;	
										}else{
											$pending_hitung+=0;
										}
									}
									if($key2->STATUS=='PENDING SETOR'){
										if(isset($key2->jumlah)){

											$pending_setor+=$key2->jumlah;			
											$total_pending_setor+=$key2->jumlah;
										}else{
											$pending_setor+=0;
										}
									}
									if($key2->STATUS=='PENDING JURNAL'){
										if(isset($key2->jumlah)){

											$pending_jurnal+=$key2->jumlah;			
											$total_pending_jurnal+=$key2->jumlah;
										}else{
											$pending_jurnal+=0;
										}
									}if($key2->STATUS=='CLEAR'){
										if(isset($key2->jumlah)){

											$clear+=$key2->jumlah;			
											$total_clear+=$key2->jumlah;
										}else{
											$pending_clear+=0;
										}
									}
									if($key2->STATUS=='TOKO TUTUP'){
										if(isset($key2->jumlah)){

											$toko_tutup+=$key2->jumlah;			
											$total_toko_tutup+=$key2->jumlah;
										}else{
											$toko_tutup+=0;
										}
									}
									
								}
							
							
								
							
						}

					}
						
						$html .= $no.';'.$region.';'.$cabang.';'. $toko_tutup.';'.$pending_setor.';'.$pending_hitung.';'.$pending_deposit.';'.$pending_jurnal.';'.$clear."\n";
						 $html .= ';;Grand Total;'.$total_toko_tutup.';'.$total_pending_setor.';'.$total_pending_hitung.';'.$total_pending_deposit.';'.$total_pending_jurnal.';'.$total_clear.';';
						
						$cetak['html'] = $html;
						$cetak['file_name'] = 'Monitoring_Penerimaan_Sales_'.$nama_cabang.'csv';
						$this->load->view('view_csv', $cetak, FALSE);
										
				}
		}
		
		
	}

	public function psd($branch_id,$tglawal,$tglakhir,$print){
		if($print=='pdf')
		{
			date_default_timezone_set("Asia/Bangkok");
		
		
		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		
		$this->load->library('Pdf');
		set_time_limit(0);

		ini_set('memory_limit', '-1');
		
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');
		ini_set('max_execution_time',1200);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$this->load->model('master/Mod_cdc_master_branch');
		

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('DETAIL MONITORING PENERIMAAN SALES');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(8, 10, 8);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L', array( 210,
1200));
		//$pdf->AddPage('L','A4');

	
		if($branch_id=='100'){

						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(60, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'DETAIL MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->SetFont('helveticaB', '', 5, '', true);
						$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);

						$pdf->MultiCell(12, 5, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'COLLECT', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'BBT', 1, 'C', 0, 0, '', '', true);
										

						$begin = new DateTime($tglawal);
						$end = new DateTime($tglakhir);
						$cabang=$this->Mod_report->mps_header2('000');
						$no=1;
						$hitung=1;
						$page=1;
						foreach ($cabang as $branch) {
							$interval = DateInterval::createFromDateString('1 day');
							$period = new DatePeriod($begin, $interval, $end);
						
							$data=$this->Mod_report->monitoring_sales_detail(trim($branch->branch_code),$tglawal,$tglakhir);
					
								

						for($i = $begin; $i <= $end; $i->modify('+1 day')){

							$pdf->MultiCell(35, 5,$i->format("d\n"), 1, 'C', 0, 0, '', '', true);
							
						}
						$awal='';
						$akhir='';
						$pdf->Ln();
						foreach ($data as $key) {




										if($page==20){
											$page=1;
										//	$pdf->setFontSubsetting(true);
											$pdf->AddPage('L', array( 210,1200));
											$pdf->SetFont('helveticaB', '', 8, '', true);
											$pdf->MultiCell(60, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
										
											
											$pdf->Ln();
											$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
										
											$pdf->Ln();
											$pdf->SetFont('helveticaB', '', 12, '', true);
											$pdf->Cell(0, 0, 'DETAIL MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
											$pdf->SetFont('helvetica', '', 8, '', true);
											
											$pdf->SetFont('helvetica', '', 10, '', true);
											$pdf->Ln();
											$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
										
											
											$pdf->Ln();
											$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(8);
											$pdf->SetFont('helveticaB', '', 5, '', true);
											$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helveticaB', '', 7, '', true);

											$pdf->MultiCell(12, 5, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'COLLECT', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'BBT', 1, 'C', 0, 0, '', '', true);
						
											$begin = new DateTime($tglawal);
											$end = new DateTime($tglakhir);

											for($i = $begin; $i <= $end; $i->modify('+1 day')){

												$pdf->MultiCell(35, 5,$i->format("d\n"), 1, 'C', 0, 0, '', '', true);
												//$no++;
											    
											}
											$pdf->Ln();

										}
										$data2=$this->Mod_report->get_detail_toko($key->STORE_CODE);
										$tipe_setoran='';
										$nama_toko='';
										if($data2){
											$tipe_setoran=$data2->TIPE_SETORAN;
											$nama_toko=$data2->STORE_NAME;
										}
										$pdf->MultiCell(12, 5,$no, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(15, 5,$branch->branch_alt, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5,$key->STORE_CODE, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(50, 5,$nama_toko, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5,trim($tipe_setoran), 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5, 'BBT', 1, 'C', 0, 0, '', '', true);
										if(substr($tglakhir, 8, 1)=='0'){
									          $akhir=substr($tglakhir, 9, 1);
									    }else{

									          $akhir=substr($tglakhir, 8, 2);
									    }

									    if(substr($tglawal, 8, 1)=='0'){
									          $awal=substr($tglawal, 9, 1);;
									    }else{

									          $akhir=substr($tglakhir, 8, 2);
									    }
									
										
								        $kolom=$this->Mod_report->m(intval($awal),intval($akhir));
								        foreach ($kolom as $key2) {
								        	$m='0'.$key2->m;
								        	  $pisah=explode(";", $key->$m);
								        	  $status=$pisah[0];
								        	  $rp=0;
								        	  if((isset($pisah[1]))){
								        	  	$rp=$pisah[1];
								        	  }
								        	  if($status=='PENDING SETOR'){
								        	 	$pdf->SetFillColor(255,0,0);
								        	 
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if($status=='TOKO TUTUP'){
								        	 	$pdf->SetFillColor(0,0,0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if($status=='PENDING DEPOSIT'){
								        	 //	$pdf->SetFillColor(0,255,255);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if($status=='PENDING JURNAL'){
								        	 	$pdf->SetFillColor(0,0,100,0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if($status=='CLEAR'){
								        	 	$pdf->SetFillColor(0, 255, 0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if($status=='PENDING HITUNG'){
								        	 	$pdf->SetFillColor(176,224,230);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else{
								        	
								        	 	$pdf->SetFillColor(255,255,255);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }
								        	 
								            
								        }
								        $no++; 
								        $page++;
								          
								        
										$pdf->Ln();
						}
						
						$pdf->Ln();
					
						}
						
					

		}else{
						$branch = $this->Mod_report->get_cabang_session($branch_id);
						

						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(60, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(1000, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'DETAIL MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, ''. trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->SetFont('helveticaB', '', 5, '', true);
						$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);

						$pdf->MultiCell(12, 5, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'COLLECT', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'BBT', 1, 'C', 0, 0, '', '', true);
						
						$begin = new DateTime($tglawal);
						$end = new DateTime($tglakhir);

						$interval = DateInterval::createFromDateString('1 day');
						$period = new DatePeriod($begin, $interval, $end);
						$hitung=1;
						$data=$this->Mod_report->monitoring_sales_detail(trim($branch[0]->BRANCH_CODE),$tglawal,$tglakhir);
						$no=1;
						$page=1;

						for($i = $begin; $i <= $end; $i->modify('+1 day')){

							$pdf->MultiCell(35, 5,$i->format("d\n"), 1, 'C', 0, 0, '', '', true);
							
						}
						$awal='';
						$akhir='';
						$pdf->Ln();
						foreach ($data as $key) {
							




										if($page==21){
											$page=1;
										//	$pdf->setFontSubsetting(true);
											$pdf->AddPage('L', array( 210,
1200));
											$pdf->SetFont('helveticaB', '', 8, '', true);
											$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
										
											
											$pdf->Ln();
											$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
										
											$pdf->Ln();
											$pdf->SetFont('helveticaB', '', 12, '', true);
											$pdf->Cell(0, 0, 'DETAIL MONITORING PENERIMAAN SALES', 0, 1, 'C', 0, '', 0);
											$pdf->SetFont('helvetica', '', 8, '', true);
											
											$pdf->SetFont('helvetica', '', 10, '', true);
											$pdf->Ln();
											$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, ''. trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
											$pdf->SetFont('helvetica', '', 8, '', true);
										
											
											$pdf->Ln();
											$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(8);
											$pdf->SetFont('helveticaB', '', 5, '', true);
											$pdf->MultiCell(30, 5, 'Keterangan Warna', 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(150, 5, 'Hitam -> TOKO TUTUP  |   Merah -> PENDING SETOR   | Biru  -> PENDING HITUNG | Biru Muda -> PENDING DEPOSIT  | Kuning -> PENDING JURNAL | Hijau -> CLEAR', 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helveticaB', '', 7, '', true);

											$pdf->MultiCell(12, 5, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(15, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'COLLECT', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 5, 'BBT', 1, 'C', 0, 0, '', '', true);
											$begin = new DateTime($tglawal);
											$end = new DateTime($tglakhir);

											for($i = $begin; $i <= $end; $i->modify('+1 day')){

												$pdf->MultiCell(35, 5,$i->format("d\n"), 1, 'C', 0, 0, '', '', true);
												
											    
											}
											$pdf->Ln();

										}
										$data=$this->Mod_report->get_detail_toko($key->STORE_CODE);
										$pdf->MultiCell(12, 5,$no, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(15, 5,$branch[0]->BRANCH_ALT, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5,$key->STORE_CODE, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(50, 5,$data->STORE_NAME, 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5,trim($data->TIPE_SETORAN), 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 5, '', 1, 'C', 0, 0, '', '', true);
										if(substr($tglakhir, 8, 1)=='0'){
									          $akhir=substr($tglakhir, 9, 1);
									    }else{

									          $akhir=substr($tglakhir, 8, 2);
									    }

									    if(substr($tglawal, 8, 1)=='0'){
									          $awal=substr($tglawal, 9, 1);;
									    }else{

									          $akhir=substr($tglakhir, 8, 2);
									    }
									
										
								        $kolom=$this->Mod_report->m(intval($awal),intval($akhir));
								         foreach ($kolom as $key2) {
								        	  $tgl = array(1,2,3,4,5,6,7,8,9);
								        	  $m='0'.$key2->m;
								        	  $pisah=explode(";", $key->$m);
								        	  		$status=$pisah[0];
								        	  		$rp=0;
								        	  		if((isset($pisah[1]))){
										        	  	$rp=$pisah[1];
										        	  }

								        	  // if (in_array($key2->m, $tgl)) {
								        	  // 	$m='0'.$key2->m;
								        	  // 	print_r($key);
								        	  // // 	$pisah=explode(";", $key->$m);
								        	  // // 	$status=$pisah[0];
								        	  // // 	$rp=0;
								        	 	// // if((isset($pisah[1]))){
										        	// //   	$rp=$pisah[1];
										        	// //   }

								        	  // }else{
								        	  // 	$m='0'.$key2->m;
								        	  // 	if((isset( $key->$m)))
								        	  // 	{
								        	  // 		$pisah=explode(";", $key->$m);
								        	  // 		$status=$pisah[0];
								        	  // 		$rp=0;
								        	  // 		if((isset($pisah[1]))){
										        	//   	$rp=$pisah[1];
										        	//   }
								        	  // 	}else{
								        	  // 		$status=''.$key2->m;
								        	  // 		$rp=0;
								        	  // 	}
								        	  	
								        	  // }
								        	 

								        	  
								        	  if(trim($status)=='PENDING SETOR'){
								        	  	$pdf->SetFillColor(255,0,0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if(trim($status)=='TOKO TUTUP'){
								        	 	$pdf->SetFillColor(0,0,0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if(trim($status)=='PENDING DEPOSIT'){
								        	 	$pdf->SetFillColor(0,255,255);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if(trim($status)=='PENDING JURNAL'){
								        	 	$pdf->SetFillColor(0,0,100,0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if(trim($status)=='CLEAR'){
								        	 	$pdf->SetFillColor(0, 255, 0);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else if(trim($status)=='PENDING HITUNG'){
								        	 		$pdf->SetFillColor(176,224,230);
								        	 	$pdf->MultiCell(35, 5,number_format($rp, 0, '.', ','), 1, 'R', 1, 0, '', '', true);
								        	 }else{
								        	 
								        	 	 $pdf->SetFillColor(255,255,255);
								       			 $pdf->MultiCell(35, 5,number_format($rp, 0, '.', ',').$status, 1, 'R', 1, 0, '', '', true);

								        	 
								        	 }
								        	 
								            
								        }
								       	
								       	$no++; 
								        $page++;
								          
								        
										$pdf->Ln();
						}
						
						$pdf->Ln();
						
						

		}
		
		$pdf->MultiCell(100, 5, '*Kolom BBT untuk sementara dikosongkan.', 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->Output('Report Monitoring Cabang'.date('YmdHi').'.pdf', 'I');
		

		}else{

			$nama_cabang = '';
			$no=1;
			$branch='';
			date_default_timezone_set("Asia/Bangkok");
			set_time_limit(0);
			$kode_cabang='';
			ini_set('memory_limit', '-1');
		
			if($branch_id == 100){
					$nama_cabang = 'All Cabang IDM';
					$kode_cabang='000';
			}else{
					
				
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
					$kode_cabang=trim($branch[0]->BRANCH_CODE);
			}
			$html = 'DETAIL MONITORING PENERIMAAN SALES'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($tglawal))).' s/d '.urldecode(date('d-M-Y',strtotime($tglakhir)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-m-Y')."\n".'Waktu : '.date('H:i:s')."\n".'User : '.$this->session->userdata('username')."\n";
			
			$html .= "\n".'No;CAB;TOKO;NAMA;COLLECT;BBT';

			$begin = new DateTime($tglawal);
			$end = new DateTime($tglakhir);

			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			$hitung=1;


			if($branch_id==100){
				$no=1;

				$cabang=$this->Mod_report->mps_header2('000');
					foreach ($cabang as $branch) {
							$interval = DateInterval::createFromDateString('1 day');
							$period = new DatePeriod($begin, $interval, $end);
						
							$data=$this->Mod_report->monitoring_sales_detail(trim($branch->branch_code),$tglawal,$tglakhir);
								for($i = $begin; $i <= $end; $i->modify('+1 day')){
							$html .= ";".$i->format("d");
										
										
						}
						$html.="\n";
						$awal='';
						$akhir='';
						
						foreach ($data as $key) {
							$data2=$this->Mod_report->get_detail_toko($key->STORE_CODE);
							$tipe_setoran='';
							$nama_toko='';
							if($data2){
								$tipe_setoran=$data2->TIPE_SETORAN;
								$nama_toko=$data2->STORE_NAME;
							}
							if(substr($tglakhir, 8, 1)=='0'){
								$akhir=substr($tglakhir, 9, 1);
							}else{

								$akhir=substr($tglakhir, 8, 2);
							}

							if(substr($tglawal, 8, 1)=='0'){
								$awal=substr($tglawal, 9, 1);;
							}else{

								$akhir=substr($tglakhir, 8, 2);
							}
							$html .= $no.';'.$branch->branch_alt.';'.$key->STORE_CODE.';'. $nama_toko.';'.trim($tipe_setoran).';'.''.';';									
													
							$kolom=$this->Mod_report->m(intval($awal),intval($akhir));
							foreach ($kolom as $key2) {
								
								$status='';
								$m='0'.$key2->m;
								if(isset($key->$m)){
									$pisah=explode(";", $key->$m);
							 		$status=$pisah[0];
								}
									
								        	  
								
								if($status=='PENDING SETOR'){
									$html .= 'PENDING SETOR;';	//MERAH
								//	$pdf->MultiCell(5, 5,'', 1, 'C', 1, 0, '', '', true);
								}else if($status=='TOKO TUTUP'){
									$html .= 'TOKO TUTUP;';	//HITAM
								}else if($status=='PENDING DEPOSIT'){
									$html .= 'PENDING DEPOSIT;';	//BIRU MUDA
								}else if($status=='PENDING JURNAL'){
									$html .= 'PENDING JURNAL;';	//KUNING
								}else if($status=='CLEAR'){
									$html .= 'CLEAR;';	//HIJAU
								}else if($status=='PENDING HITUNG'){
									$html .= 'PENDING HITUNG;';	//BIRU TUA	    
								}else{
									$html .= 'DATA TIDAK MEMENUHI SYARAT;';	//GA TAU	 
								}
										        	 
											            
							}
							/*if($report_type=='Warna'){
								foreach ($kolom as $key2) {
								
								$m='0'.$key2->m;
								if($key->$m=='PENDING SETOR'){
									$html .= 'PENDING SETOR;';	//MERAH
								//	$pdf->MultiCell(5, 5,'', 1, 'C', 1, 0, '', '', true);
								}else if($key->$m=='TOKO TUTUP'){
									$html .= 'TOKO TUTUP;';	//HITAM
								}else if($key->$m=='PENDING DEPOSIT'){
									$html .= 'PENDING DEPOSIT;';	//BIRU MUDA
								}else if($key->$m=='PENDING JURNAL'){
									$html .= 'PENDING JURNAL;';	//KUNING
								}else if($key->$m=='CLEAR'){
									$html .= 'CLEAR;';	//HIJAU
								}else if($key->$m=='PENDING HITUNG'){
									$html .= 'PENDING HITUNG;';	//BIRU TUA	    
								}else{
									$html .= 'DATA TIDAK MEMENUHI SYARAT;';	//GA TAU	 
								}
										        	 
											            
								}
							}else{
								foreach ($kolom as $key2) {
								
								$m='0'.$key2->m;
								//var_dump($key);
								 if($key->$m){
								 	$html .= $key->$m.';';
									        	 
								 }else{
									$html .= '0;';	//GA TAU	 
								}
								
								
											            
								}
							}*/
							
							$html.="\n";	
							$no++; 
											      
						}
							
							



					}


			}else{

				$data=$this->Mod_report->monitoring_sales_detail($kode_cabang,$tglawal,$tglakhir);

				$no=1;
						
				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					$html .= ";".$i->format("d");
								
								
				}
				$html.="\n";
				$awal='';
				$akhir='';
				foreach ($data as $key) {
					$data2=$this->Mod_report->get_detail_toko($key->STORE_CODE);
					$tipe_setoran='';
					$nama_toko='';
					if($data2){
						$tipe_setoran=$data2->TIPE_SETORAN;
						$nama_toko=$data2->STORE_NAME;
					}
					if(substr($tglakhir, 8, 1)=='0'){
						$akhir=substr($tglakhir, 9, 1);
					}else{

						$akhir=substr($tglakhir, 8, 2);
					}

					if(substr($tglawal, 8, 1)=='0'){
						$awal=substr($tglawal, 9, 1);;
					}else{

						$akhir=substr($tglakhir, 8, 2);
					}
					$html .= $no.';'.$branch[0]->BRANCH_ALT.';'.$key->STORE_CODE.';'. $nama_toko.';'.trim($tipe_setoran).';'.''.';';									
											
					$kolom=$this->Mod_report->m(intval($awal),intval($akhir));

					foreach ($kolom as $key2) {
							$status='';
								$m='0'.$key2->m;
								if(isset($key->$m)){
									$pisah=explode(";", $key->$m);
							 		$status=$pisah[0];
							 		

								}
								
							
									
									if($status=='PENDING SETOR'){
										$html .= 'PENDING SETOR;';	//MERAH
									//	$pdf->MultiCell(5, 5,'', 1, 'C', 1, 0, '', '', true);
									}else if($status=='TOKO TUTUP'){
										$html .= 'TOKO TUTUP;';	//HITAM
									}else if($status=='PENDING DEPOSIT'){
										$html .= 'PENDING DEPOSIT;';	//BIRU MUDA
									}else if($status=='PENDING JURNAL'){
										$html .= 'PENDING JURNAL;';	//KUNING
									}else if($status=='CLEAR'){
										$html .= 'CLEAR;';	//HIJAU
									}else if($status=='PENDING HITUNG'){
										$html .= 'PENDING HITUNG;';	//BIRU TUA	    
									}else{
										$html .= 'DATA TIDAK MEMENUHI SYARAT;';	//GA TAU	 
									}
											     
							
							
							   	 
									            
						}

					
					$html.="\n";	
					$no++; 
									      
				}

			}


			
						
						
			$html .= '*Kolom BBT untuk sementara dikosongkan.;'."\n";			 
			$cetak['html'] = $html;

			$cetak['file_name'] = 'Report_Monitoring_Cabang_'.$nama_cabang.'.csv';
			$this->load->view('view_csv', $cetak, FALSE);
									
		}
		
	}

	public function psc($branch_id,$tglawal,$tglakhir,$print){
		if($print=='pdf'){
				date_default_timezone_set("Asia/Bangkok");

			$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		
		$this->load->library('Pdf');
		set_time_limit(0);

		ini_set('memory_limit', '-1');
		
		
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');
		ini_set('max_execution_time',1200);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$this->load->model('master/Mod_cdc_master_branch');
	
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('REKAP PENERIMAAN SALES (per Cabang)');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setPrintHeader(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('P','A4');


		if($branch_id==100){
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Cabang)', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 10, '', true);
					
				
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
						$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'REG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);
						
						


			
						
						
						$pdf->Ln();
						
						$page = 1;
						$no=1;
						$total_sales=0;
						$total_kurset=0;
						$data=$this->Mod_report->rekap_penerimaan_sales_cbg($branch_id,$tglawal,$tglakhir);
						foreach ($data as $key) {

							$region=$key->REGION;
							$cabang=$key->BRANCH_ALT;
							$sales=$key->SALES;
							$payment_point=$key->PAYMENT_POINT;
							$kurset=$key->KURSET;
							
							$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$region, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5,number_format($sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5,$payment_point, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5,number_format($kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$total_sales+=$sales;
							$total_kurset+=$kurset;
							# code...
						}
						$pdf->MultiCell(68, 5,'TOTAL', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, number_format($total_sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5,'', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5,number_format($total_kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();

						$pdf->MultiCell(100, 5, '*Kolom Payment Point untuk sementara dikosongkan.', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						
						
						
					

		}else{
						$branch = $this->Mod_report->get_cabang_session($branch_id);
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Cabang)', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0,''.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 10, '', true);
					
				
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
						$pdf->MultiCell(8, 5, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'REG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);

						
						
						
						$pdf->Ln();
						
						$page = 1;
						$no=1;
						$total_sales=0;
						$total_kurset=0;
						$data=$this->Mod_report->rekap_penerimaan_sales_cbg($branch_id,$tglawal,$tglakhir);
						foreach ($data as $key) {
							$region=$key->REGION;
							$cabang=$key->BRANCH_ALT;
							$sales=$key->SALES;
							$payment_point=$key->PAYMENT_POINT;
							$kurset=$key->KURSET;
							$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$region, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5, number_format($sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5,$payment_point, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(40, 5,number_format($kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$total_sales+=$sales;
							$total_kurset+=$kurset;
						}

						$pdf->MultiCell(68, 5,'TOTAL', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5, number_format($total_sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5,'', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(40, 5,number_format($total_kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(100, 5, '*Kolom Payment Point untuk sementara dikosongkan.', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();

						
		}

		$pdf->Output('REKAP PENERIMAAN SALES (per Cabang)'.date('YmdHi').'.pdf', 'I');
		}else{
			date_default_timezone_set("Asia/Bangkok");

		
			set_time_limit(0);

			ini_set('memory_limit', '-1');
			if($branch_id==100){
				$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			$html = 'REKAP PENERIMAAN SALES (per Cabang)'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($tglawal))).' s/d '.urldecode(date('d-M-Y',strtotime($tglakhir)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-m-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$this->session->userdata('username')."\n";
				
			
			$html .= "\n".'No;REGION;CBG;SALES;PAYMENT POINT;KURSET;'."\n";
				$no=1;
		
				$total_sales=0;
				$total_kurset=0;
				$data=$this->Mod_report->rekap_penerimaan_sales_cbg($branch_id,$tglawal,$tglakhir);
				foreach ($data as $key) {

					$region=$key->REGION;
					$cabang=$key->BRANCH_ALT;
					$sales=$key->SALES;
					$payment_point=$key->PAYMENT_POINT;
					$kurset=$key->KURSET;
							
						$html .= $no.';'.$region.';'.$cabang.';'.$sales.';'.$payment_point.';'.$kurset."\n";
								
						$no++;
							
						$total_sales+=$sales;
						$total_kurset+=$kurset;
							# code...
					}
						
				
							
								
					
						
						
					
					$html .= ';;Grand Total;'.$total_sales.';'.''.';'.$total_kurset."\n";
					$html .= '*Kolom Payment Point untuk sementara dikosongkan.;'."\n";
					$cetak['html'] = $html;
					$cetak['file_name'] = 'REKAP_PENERIMAAN_SALES_per_Cabang_'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  


		}
	
		

	}
	public function adt($branch_id,$kode_toko,$periode,$print){
		$start_date = date('Y-M-01', strtotime($periode));
		$end_date = date('Y-M-t', strtotime($periode));
		$start_date_sql = date('Y-m-d',strtotime($start_date));
		$end_date_sql = date('Y-m-d',strtotime($end_date));
		date_default_timezone_set("Asia/Bangkok");
		
	

		$user_id = $this->session->userdata('usrId');
		$nik_user = $this->Mod_report->getNIK($user_id);
		$nik_user = $nik_user[0]->NIK;

		if($print=='pdf'){
			$this->load->library('Pdf');
			set_time_limit(0);
	
			ini_set('memory_limit', '-1');
			
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$userName = $this->session->userdata('username');
			ini_set('max_execution_time',1200);
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->setPrintHeader(false);
			$this->load->model('master/Mod_cdc_master_branch');
			
	
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Laporan Absensi Pengiriman Data Sales Tunai dan Dana Input-an Sales per Denom Toko Idm');
			$pdf->SetSubject('');
	
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
			$pdf->SetMargins(8, 10, 8);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFontSubsetting(true);
			$pdf->AddPage('L', array( 210,3000));
			$branch = $this->Mod_report->get_cabang_session($branch_id);
			$toko = $this->Mod_report->get_detail_toko($kode_toko);

			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(60, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(2700, 5,'', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
			
				
			$pdf->Ln();
			$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(2700, 5,'', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
			$pdf->Ln();
			$pdf->MultiCell(2710, 5,'', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(100, 5,'User : '.$nik_user.'-'.trim($userName), 0, 'R', 0, 0, '', '', true);
			
			$pdf->Ln();
			$pdf->SetFont('helveticaB', '', 12, '', true);
			$pdf->Cell(0, 0, 'Laporan Absensi Pengiriman Data Sales Tunai dan Dana Input-an Sales per Denom Toko Idm', 0, 1, 'C', 0, '', 0);
			$pdf->SetFont('helvetica', '', 8, '', true);
				
			$pdf->SetFont('helvetica', '', 10, '', true);
			$pdf->Ln();
			$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			$arr_shift=['Shift 1','Shift 2','Shift 3'];
			$param=['SHIFT_1','SHIFT_2','SHIFT_3'];

			if($branch_id=='100'){
				
				$pdf->MultiCell(50, 0,'All Cabang', 0, 'L', 0, 0, '', '', true);
			}else{
				$pdf->MultiCell(50, 0, ''. trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
			}
			$pdf->Ln();
			$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			if($kode_toko == '0000'){
				$pdf->MultiCell(50, 0, 'All Toko', 0, 'L', 0, 0, '', '', true);
			}else{
				$pdf->MultiCell(50, 0, $kode_toko.'-'.trim($toko->STORE_NAME), 0, 'L', 0, 0, '', '', true);
			}
			$pdf->SetFont('helvetica', '', 10, '', true);

			$pdf->Ln();
			$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(100, 0, date('d-M-Y',strtotime($start_date)).' s/d '.date('d-M-Y',strtotime($end_date)), 0, 'L', 0, 0, '', '', true);
			$pdf->Ln(8);
			$pdf->Ln();
			$pjgTgl = 0;
			$date = date('F Y',strtotime($start_date_sql));//Current Month Year
			while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
				$day = date('Y-m-d',strtotime($date));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
				$pjgTgl = $pjgTgl + 90;
				if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
						break;
					}
			}
			$pdf->setCellPaddings(1, 1, 1, 1);
			$pdf->SetFont('helveticaB', '', 7, '', true);
			$pdf->Cell(20,18,'No', 1,0, 'C');
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->Cell(100,6,'Toko Idm', 1,0,'C');
			$pdf->Cell($pjgTgl,6,'Tanggal', 1,1,'C');
			$pdf->SetX($x);
			$pdf->Cell(20,12,'Kode', 1,0,'C');
			$pdf->Cell(60,12,'Nama', 1,0,'C');
			$pdf->Cell(20,12,'Type Inputan', 1,0,'C');
			$date = date('F Y',strtotime($start_date_sql));//Current Month Year
			while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
				$day = date('Y-m-d',strtotime($date));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
				$pdf->Cell(90,6,date("d",strtotime($day)), 1,0,'C');
				if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
						break;
					}
			}
			$pdf->Cell(5,6,'', 0,1,'C');
			$pdf->SetX($x);
			$pdf->Cell(20,0,'', 0,0,'C');
			$pdf->Cell(60,0,'', 0,0,'C');
			$pdf->Cell(20,0,'', 0,0,'C');
			$date = date('F Y',strtotime($start_date_sql));//Current Month Year
			while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
				$day = date('Y-m-d',strtotime($date));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
				for($y=0;$y<count($arr_shift);$y++)
				{
					$pdf->Cell(30,6,$arr_shift[$y], 1,0,'C');
				}
				
				if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
						break;
					}
				}
				$pdf->Ln();
				$branch = $this->Mod_report->get_cabang_session($branch_id);
				$toko = $this->Mod_report->get_detail_toko($kode_toko);
				if($branch_id=='100'){
					$get_toko= $this->Mod_report->get_data_absensi_denom_toko_idm_toko($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko);
				}else{
					$get_toko= $this->Mod_report->get_data_absensi_denom_toko_idm_toko($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko);
				}
				$nomor = 1;
				$line = 1;
				foreach($get_toko as $a){
					if($line == 18){
						$line = 1;
						$pdf->AddPage('L', array( 210,3000));
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(60, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(2700, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);					
						$pdf->Ln();
						$pdf->MultiCell(60, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(2700, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(60, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(2710, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(100, 5,'User : '.$nik_user.'-'.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'Laporan Absensi Pengiriman Data Sales Tunai dan Dana Input-an Sales per Denom Toko Idm', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			$arr_shift=['Shift 1','Shift 2','Shift 3'];
			$param=['SHIFT_1','SHIFT_2','SHIFT_3'];

			if($branch_id=='100'){
				
				$pdf->MultiCell(50, 0,'All Cabang', 0, 'L', 0, 0, '', '', true);
			}else{
				$pdf->MultiCell(50, 0, ''. trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
			}
			$pdf->Ln();
			$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			if($kode_toko == '0000'){
				$pdf->MultiCell(50, 0, 'All Toko', 0, 'L', 0, 0, '', '', true);
			}else{
				$pdf->MultiCell(50, 0, $kode_toko.'-'.trim($toko->STORE_NAME), 0, 'L', 0, 0, '', '', true);
			}
			$pdf->SetFont('helvetica', '', 10, '', true);

			$pdf->Ln();
			$pdf->MultiCell(1470, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(100, 0, date('d-M-Y',strtotime($start_date)).' s/d '.date('d-M-Y',strtotime($end_date)), 0, 'L', 0, 0, '', '', true);
			$pdf->Ln(8);
						$pdf->Ln();
						$pjgTgl = 0;
						$date = date('F Y',strtotime($start_date_sql));//Current Month Year
						while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
							$day = date('Y-m-d',strtotime($date));
							$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
							$pjgTgl = $pjgTgl + 90;
							if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
								break;
							}
						}
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
						$pdf->Cell(20,18,'No', 1,0, 'C');
						$x = $pdf->GetX();
						$y = $pdf->GetY();
						$pdf->Cell(100,6,'Toko Idm', 1,0,'C');
						$pdf->Cell($pjgTgl,6,'Tanggal', 1,1,'C');
						$pdf->SetX($x);
						$pdf->Cell(20,12,'Kode', 1,0,'C');
						$pdf->Cell(60,12,'Nama', 1,0,'C');
						$pdf->Cell(20,12,'Type Inputan', 1,0,'C');
						$date = date('F Y',strtotime($start_date_sql));//Current Month Year
						while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
							$day = date('Y-m-d',strtotime($date));
							$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
							$pdf->Cell(90,6,date("d",strtotime($day)), 1,0,'C');
							if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
								break;
							}
						}

						$pdf->Cell(5,6,'', 0,1,'C');
						$pdf->SetX($x);
						$pdf->Cell(20,0,'', 0,0,'C');
						$pdf->Cell(60,0,'', 0,0,'C');
						$pdf->Cell(20,0,'', 0,0,'C');
						$date = date('F Y',strtotime($start_date_sql));//Current Month Year
						while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
							$day = date('Y-m-d',strtotime($date));
							$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
							for($y=0;$y<count($arr_shift);$y++)
							{
								$pdf->Cell(30,6,$arr_shift[$y], 1,0,'C');
							}
							if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
								break;
							}
						}
						$pdf->Ln();
					}
											$pdf->SetFont('helveticaB', '', 7, '', true);

					$pdf->MultiCell(20, 5, $nomor , 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(20, 5, $a->STORE_CODE , 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(60, 5, $a->STORE_NAME , 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(20, 5, $a->TIPE_INPUTAN , 1, 'C', 0, 0, '', '', true);
					// $get_tanggal = $this->Mod_report->get_data_tanggal_per_toko_for_absensi($a->BRANCH_CODE,$a->STORE_CODE,$start_date_sql,$end_date_sql);
					$date = date('F Y',strtotime($start_date_sql));//Current Month Year
					while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
						$day = date('Y-m-d',strtotime($date));
						$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
						$get_shift = $this->Mod_report->get_data_shift_per_tanggal($day,$a->STORE_CODE);
						foreach($get_shift as $b){
							for($x=0;$x<count($param);$x++)
							{

								
								$parameter="".$param[$x];
								if($b->$parameter == '0'){
								$pdf->MultiCell(30, 5, 0 , 1, 'R', 0, 0, '', '', true);
								}else if ($b->$parameter == '' ){
								$pdf->MultiCell(30, 5,'-', 1, 'R', 0, 0, '', '', true);
								}else{
									$pdf->MultiCell(30, 5, number_format( $b->$parameter, 0, '.', ',') , 1, 'R', 0, 0, '', '', true);
								}
							}
							
						}
						if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
							break;
						}
					

					}
					$nomor ++;
					$line ++;
					$pdf->Ln();
					$pdf->SetFont('helvetica', '', 10, '', true);
				}
				
			ob_end_clean();
			$pdf->Output('Absensi_Sales_Per_Denom'.date('YmdHi').'.pdf', 'I');
		}else{
			date_default_timezone_set("Asia/Bangkok");

		
			set_time_limit(0);

			ini_set('memory_limit', '-1');
			if($branch_id==100){
				$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			if($kode_toko=='0000'){
				$kode_dan_nama_toko = 'All Toko';
			}else{
				$toko = $this->Mod_report->get_detail_toko($kode_toko);
				$kode_dan_nama_toko = $kode_toko.'-'.$toko->STORE_NAME;
			}
			$userName = $this->session->userdata('username');
			$html = 'Laporan Absensi Pengiriman Data Sales Tunai dan Dana Inputan Sales per Denom Toko  Idm'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Kode Toko - Nama Toko : '.$kode_dan_nama_toko."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($start_date))).' s/d '.urldecode(date('d-M-Y',strtotime($end_date)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-M-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$nik_user.'-'.trim($userName)."\n";
			if($branch_id==100){
				$get_toko= $this->Mod_report->get_data_absensi_denom_toko_idm_toko($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko);
			}else{
				$get_toko= $this->Mod_report->get_data_absensi_denom_toko_idm_toko($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko);
			}
			$nomor = 1;
			$html .= "\n".'No;KODE_TOKO;NAMA_TOKO;TYPE_INPUTAN;';
			$date = date('F Y',strtotime($start_date_sql));
			while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
				$day = date('Y-m-d',strtotime($date));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
				$html.= 'TGL'.date("d",strtotime($day)).'_SHIFT1;TGL'.date("d",strtotime($day)).'_SHIFT2;TGL'.date("d",strtotime($day)).'_SHIFT3;';
				if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
					break;
				}
			}
			$html.="\n";
			foreach($get_toko as $a){
				$html.=$nomor.';'.$a->STORE_CODE.';'.$a->STORE_NAME.';'.$a->TIPE_INPUTAN.';';
				// $get_tanggal = $this->Mod_report->get_data_tanggal_per_toko_for_absensi($a->BRANCH_CODE,$a->STORE_CODE,$start_date_sql,$end_date_sql);
				$date = date('F Y',strtotime($start_date_sql));//Current Month Year
				while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
					$day = date('Y-m-d',strtotime($date));
					$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
					$get_shift = $this->Mod_report->get_data_shift_per_tanggal($day,$a->STORE_CODE);
					foreach($get_shift as $b){
						if($b->SHIFT_1 == ''){
							$html.='-;';
						}else if($b->SHIFT_1 == '0'){
							$html.='0;';
						}else{
							$html.=$b->SHIFT_1.';';
						}
						if($b->SHIFT_2 == '0'){
							$html.='0;';
						}else if($b->SHIFT_2 == ''){
							$html.='-;';
						}else{
							$html.=$b->SHIFT_2.';';
						}
						if($b->SHIFT_3 == ''){
							$html.='-;';
						}else if($b->SHIFT_3 == '0'){
							$html.='0;';
						}else{
							$html.=$b->SHIFT_3.';';
						}
					}
					if(date('d',strtotime($day)) == date('t',strtotime($end_date_sql))){
						break;
					}
				}
				$nomor ++;
				$html.="\n";
			}
			
			
					$cetak['html'] = $html;
					$cetak['file_name'] = 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  

		}


	}


	public function get_count_mks($branch_id,$kode_toko,$status_setor,$periode,$tipe_setor,$print){
		$start_date = date('Y-M-01', strtotime($periode));
		$end_date = date('Y-M-t', strtotime($periode));
		$start_date_sql = date('Y-m-d',strtotime($start_date));
		$end_date_sql = date('Y-m-d',strtotime($end_date));
		if($status_setor == 'A'){
			$status_setor = 'All';
		}else if($status_setor == 'M'){
			$status_setor = 'Match';
		}else if($status_setor == 'S'){
			$status_setor = 'Selisih';
		}else if($status_setor == 'S1'){
			$status_setor = 'Selisih > 1000';
		}
		$user_id = $this->session->userdata('usrId');
		$nik_user = $this->Mod_report->getNIK($user_id);
		$nik_user = $nik_user[0]->NIK;
		if($branch_id=='100'){
		      $data = $this->Mod_report->get_count_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko,$tipe_setor,$status_setor);
			  print_r($data);
		}else{
			  $branch = $this->Mod_report->get_cabang_session($branch_id);
			  $toko = $this->Mod_report->get_detail_toko($kode_toko);
			  $data= $this->Mod_report->get_count_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko,$tipe_setor,$status_setor);
			  print_r($data);
		}
	}
	
	
	// public function mks($branch_id,$kode_toko,$status_setor,$periode,$tipe_setor,$print){
	// 	date_default_timezone_set("Asia/Bangkok");
	// 	$start_date = date('Y-M-01', strtotime($periode));
	// 	$end_date = date('Y-M-t', strtotime($periode));
	// 	$start_date_sql = date('Y-m-d',strtotime($start_date));
	// 	$end_date_sql = date('Y-m-d',strtotime($end_date));
	// 	if($status_setor == 'A'){
	// 		$status_setor = 'All';
	// 	}else if($status_setor == 'M'){
	// 		$status_setor = 'Match';
	// 	}else if($status_setor == 'S'){
	// 		$status_setor = 'Selisih';
	// 	}else if($status_setor == 'S1'){
	// 		$status_setor = 'Selisih > 1000';
	// 	}
	// 	$user_id = $this->session->userdata('usrId');
	// 	$nik_user = $this->Mod_report->getNIK($user_id);
	// 	$nik_user = $nik_user[0]->NIK;
	// 	if($print=='pdf'){
			
	// 	$this->load->library('Pdf');
	// 	set_time_limit(0);

	// 	ini_set('memory_limit', '-1');
		
		
	// 	$now = date('d-m-Y');
	// 	$time = date("H:i:s");
	// 	$userName = $this->session->userdata('username');
	// 	ini_set('max_execution_time',-1);
	// 	// create new PDF document
	// 	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
	// 	$this->load->model('master/Mod_cdc_master_branch');
	// 	$pdf->setPrintHeader(false);
	// 	// set document information
	// 	$pdf->SetCreator(PDF_CREATOR);
	// 	$pdf->SetAuthor($userName);
	// 	$pdf->SetTitle('REKAP PENERIMAAN SALES (per Toko)');
	// 	$pdf->SetSubject('');

	// 	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	// 	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// 	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// 	$pdf->SetMargins(10, 18, 10);
	// 	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// 	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// 	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// 	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// 	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	// 		require_once(dirname(__FILE__).'/lang/eng.php');
	// 		$pdf->setLanguageArray($l);
	// 	}
	// 	$pdf->setFontSubsetting(true);
	// 	$pdf->AddPage('L','A4');
	// 	$html='';
	// 	$pdf->SetFont('helveticaB', '', 8, '', true);
	// 	$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->SetFont('helvetica', '', 8, '', true);
	// 	$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
	// 	$pdf->Ln();
	// 	$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->SetFont('helvetica', '', 8, '', true);
	// 	$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
	// 	$pdf->Ln();
	// 	$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->SetFont('helvetica', '', 8, '', true);
	// 	$pdf->MultiCell(50, 5,'User : '.$nik_user.'-'.trim($userName), 0, 'R', 0, 0, '', '', true);
					
	// 	$pdf->Ln();
	// 	$pdf->SetFont('helveticaB', '', 12, '', true);
	// 	$pdf->Cell(0, 0, 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm', 0, 1, 'C', 0, '', 0);
	// 	$pdf->SetFont('helvetica', '', 8, '', true);
						
	// 	$pdf->SetFont('helvetica', '', 10, '', true);
	// 	$pdf->Ln();
	// 	$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
	// 	if($branch_id=='100'){

	// 	$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
	// 	}else{
	// 		$branch = $this->Mod_report->get_cabang_session($branch_id);
	// 		$toko = $this->Mod_report->get_detail_toko($kode_toko);
	// 		$pdf->MultiCell(50, 0,trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);

	// 	}
	// 	$pdf->SetFont('helvetica', '', 10, '', true);
					
	// 	$pdf->Ln();
	// 	$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(50, 0, 'Status Setor.', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(50, 0, $status_setor, 0, 'L', 0, 0, '', '', true);
	// 	$pdf->SetFont('helvetica', '', 10, '', true);
						
	// 	$pdf->Ln();
	// 	$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
	// 	if($kode_toko == '0000'){
	// 			$pdf->MultiCell(50, 0, 'All Toko', 0, 'L', 0, 0, '', '', true);
	// 	}else{
	// 			$pdf->MultiCell(50, 0, $kode_toko.'-'.trim($toko->STORE_NAME), 0, 'L', 0, 0, '', '', true);
	// 	}
	// 	$pdf->SetFont('helvetica', '', 10, '', true);

	// 	$pdf->Ln();
	// 	$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
	// 	$pdf->MultiCell(100, 0, date('d-M-Y',strtotime($start_date)).' s/d '.date('d-M-Y',strtotime($end_date)), 0, 'L', 0, 0, '', '', true);
	// 	$pdf->Ln(8);
	// 	$pdf->SetFont('helvetica', '', 6, '', true);
	// 	$html.= '<table border="1px" align="center">
	// 							<tr>
	// 								<th rowspan="2" width="2%">  <b> No. </b> </th>
	// 								<th rowspan="1" colspan="2" width="14%"> <b>Toko Idm </b> </th>
	// 								<th rowspan="2" width="3%"> <b> AM </b> </th>
	// 								<th rowspan="2" width="3%"> <b> AS </b> </th>
	// 								<th rowspan="2" width="5%"> <b> Type Inputan </b> </th>
	// 								<th rowspan="2" width="7%"> <b> Sales Date </b> </th>
	// 								<th rowspan="2" width="5%"> <b> Metode Setoran Dana </b> </th>
	// 								<th rowspan="2" width="9%"> <b> Total Sales </b> </th>
	// 								<th rowspan="1" colspan="3" width="14%"> <b>Shift 1 </b> </th>
	// 								<th rowspan="1" colspan="3" width="14%"> <b>Shift 2 </b> </th>
	// 								<th rowspan="1" colspan="3" width="14%"> <b>Shift 3 </b> </th>
	// 								<th rowspan="2" width="5%"> <b>Total Setor </b> </th>
	// 								<th rowspan="2" width="4%"> <b>Selisih </b> </th>
	// 							</tr>
	// 							<tr>
	// 								<th rowspan="1" colspan="1">  <b> Kode </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Nama </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Type </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Type </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Type </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
	// 								<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
	// 							</tr>
	// 					';
	// 					$no=1;
	// 					$page = 1;
	// 					$total_sales=0;
	// 					$total_kurset=0;
	// 	if($branch_id=='100'){
	// 		$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko,$tipe_setor,$status_setor);			
	// 		$pdf->Ln();
	// 	}else{
	// 		$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko,$tipe_setor,$status_setor);		
	// 	}
	// 	foreach($data_content as $a){
	// 		if($a->TIPE_INPUTAN == 'SHIFT'){
	// 			$html.='<tr>
	// 										<td>'.$no.'</td>
	// 										<td>'.$a->STORE_CODE.'</td>
	// 										<td>'.$a->STORE_NAME.'</td>
	// 										<td>'.$a->AM.'</td>
	// 										<td>'.$a->AS.'</td>
	// 										<td>'.$a->TIPE_INPUTAN.'</td>
	// 										<td>'.(($a->SALES_DATE == '') ? 	'' : date('d-M-Y',strtotime($a->SALES_DATE))).'</td>
	// 										<td>'.$a->TIPE_SETORAN1.'</td>
	// 										<td align="right">'.number_format($a->TOTAL_SALES_AMOUNT).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : $a->TIPE_SETORAN1).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT1))).'</td>
	// 										<td align="right">'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : number_format($a->SHIFT1)).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : $a->TIPE_SETORAN2).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT2))).'</td>
	// 										<td align="right">'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : number_format($a->SHIFT2)).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : $a->TIPE_SETORAN3).'</td>
	// 										<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT3))).'</td>
	// 										<td align="right">'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : number_format($a->SHIFT3)).'</td>
	// 										<td align="right">'.number_format($a->TOTAL_SALES).'</td>
	// 										<td align="right">'.number_format($a->SELISIH).' </td>
	// 									</tr>
	// 									';
	// 								}else{
	// 									$html.='
	// 										<tr>
	// 											<td>'.$no.'</td>
	// 											<td>'.$a->STORE_CODE.'</td>
	// 											<td>'.$a->STORE_NAME.'</td>
	// 											<td>'.$a->AM.'</td>
	// 											<td>'.$a->AS.'</td>
	// 											<td>'.$a->TIPE_INPUTAN.'</td>
	// 											<td>'.(($a->SALES_DATE == '') ? 	'' : date('d-M-Y',strtotime($a->SALES_DATE))).'</td>
	// 											<td>'.$a->TIPE_SETORAN_HARIAN.'</td>
	// 											<td align="right">'.number_format($a->TOTAL_SALES_AMOUNT).'</td>
	// 											<td>'.$a->TIPE_SETORAN1.'</td>
	// 											<td>'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT1))).'</td>
	// 											<td> </td>
	// 											<td>'.$a->TIPE_SETORAN2.'</td>
	// 											<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT2))).'</td>
	// 											<td> </td>
	// 											<td>'.$a->TIPE_SETORAN3.'</td>
	// 											<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT3))).'</td>
	// 											<td> </td>
	// 											<td align="right">'.number_format($a->TOTAL_SALES).'</td>
	// 											<td align="right">'.number_format($a->SELISIH).' </td>
	// 										</tr>
	// 									';
	// 								}
	// 								$no++;
	// 							}
	// 							$html .='</table>';
	// 	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
	// 	ob_end_clean();
	// 	$pdf->Output('Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'.date('YmdHi').'.pdf', 'I');

	// 	}else{
	// 		date_default_timezone_set("Asia/Bangkok");
	// 		set_time_limit(0);
	// 		ini_set('memory_limit', '-1');
	// 		if($branch_id==100){
	// 			$nama_cabang = 'All Cabang IDM';
	// 		}else{
					
	// 				$branch = $this->Mod_report->get_cabang_session($branch_id);
	// 				$nama_cabang = $branch[0]->BRANCH_NAME;
	// 		}
	// 		if($kode_toko=='0000'){
	// 			$kode_dan_nama_toko = 'All Toko';
	// 		}else{
	// 			$toko = $this->Mod_report->get_detail_toko($kode_toko);
	// 			$kode_dan_nama_toko = $kode_toko.'-'.$toko->STORE_NAME;
	// 		}
	// 		$userName = $this->session->userdata('username');
	// 		$html = 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Kode Toko - Nama Toko : '.$kode_dan_nama_toko."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($start_date))).' s/d '.urldecode(date('d-M-Y',strtotime($end_date)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-M-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$nik_user.'-'.trim($userName)."\n";
				
			
	// 		$html .= "\n".'No;KODE_TOKO;NAMA_TOKO;AM;AS;TYPE_INPUTAN;SALES_DATE;METODE_SETORAN_DANA;TOTAL_SALES;TIPE_SHIFT1;TGL_SETOR_SHIFT1;NILAI_SHIFT1;TIPE_SHIFT2;TGL_SETOR_SHIFT2;NILAI_SHIFT2;TIPE_SHIFT3;TGL_SETOR_SHIFT3;NILAI_SHIFT3;TOTAL_SETOR;SELISIH'."\n";
	// 		$no=1;
		
	// 		$total_sales=0;
	// 		$total_kurset=0;
	// 		if($branch_id=='100'){
	// 			$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko,$tipe_setor,$status_setor);
					
	// 		}else{
	// 				$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko,$tipe_setor,$status_setor);

					
	// 			}
	// 			foreach ($data_content as $key) {
	// 					if($key->TIPE_INPUTAN == 'SHIFT'){
	// 						$kode_toko=$key->STORE_CODE;
	// 						$nama_toko=$key->STORE_NAME;
	// 						$am=$key->AM;
	// 						$as=$key->AS;
	// 						$tipe_inputan=$key->TIPE_INPUTAN;
	// 						$sales_date=$key->SALES_DATE;
	// 						$metode_setoran_dana=$key->TIPE_SETORAN1;
	// 						$total_sales=$key->TOTAL_SALES_AMOUNT;
	// 						$tipe_setoran1=$key->TIPE_SETORAN1;
	// 						$create_date_shift1=$key->CREATE_DATE_SHIFT1;
	// 						$shift1=$key->SHIFT1;
	// 						$tipe_setoran2=$key->TIPE_SETORAN2;
	// 						$create_date_shift2=$key->CREATE_DATE_SHIFT2;
	// 						$shift2=$key->SHIFT2;
	// 						$tipe_setoran3=$key->TIPE_SETORAN3;
	// 						$create_date_shift3=$key->CREATE_DATE_SHIFT3;
	// 						$shift3=$key->SHIFT3;
	// 						$total_setor=$key->TOTAL_SALES;
	// 						$selisih=$key->SELISIH;
							
	// 						$html .= $no.';'.$kode_toko.';'.$nama_toko.';'.trim($am).';'.trim($as).';'.$tipe_inputan.';'.date('d-M-Y',strtotime($sales_date)).';'.$metode_setoran_dana.';'.number_format($total_sales).';'.$tipe_setoran1.';'.(($create_date_shift1 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift1))).';'.(($create_date_shift1 == '') ? 	'' : number_format($shift1)).';'.$tipe_setoran2.';'.(($create_date_shift2 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift2))).';'.(($create_date_shift2 == '') ? 	'' : number_format($shift2)).';'.$tipe_setoran3.';'.(($create_date_shift3 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift3))).';'.(($create_date_shift3 == '') ? 	'' : number_format($shift3)).';'.number_format($total_setor).';'.number_format($selisih)."\n";
	// 					}else{
	// 						$kode_toko=$key->STORE_CODE;
	// 						$nama_toko=$key->STORE_NAME;
	// 						$am=$key->AM;
	// 						$as=$key->AS;
	// 						$tipe_inputan=$key->TIPE_INPUTAN;
	// 						$sales_date=$key->SALES_DATE;
	// 						$metode_setoran_dana=$key->TIPE_SETORAN_HARIAN;
	// 						$total_sales=$key->TOTAL_SALES_AMOUNT;
	// 						$tipe_setoran1=$key->TIPE_SETORAN1;
	// 						$create_date_shift1=$key->CREATE_DATE_SHIFT1;
	// 						$shift1=$key->SHIFT1;
	// 						$tipe_setoran2=$key->TIPE_SETORAN2;
	// 						$create_date_shift2=$key->CREATE_DATE_SHIFT2;
	// 						$shift2=$key->SHIFT2;
	// 						$tipe_setoran3=$key->TIPE_SETORAN3;
	// 						$create_date_shift3=$key->CREATE_DATE_SHIFT3;
	// 						$shift3=$key->SHIFT3;
	// 						$total_setor=$key->TOTAL_SALES;
	// 						$selisih=$key->SELISIH;
							
	// 						$html .= $no.';'.$kode_toko.';'.$nama_toko.';'.trim($am).';'.trim($as).';'.$tipe_inputan.';'.date('d-M-Y',strtotime($sales_date)).';'.$metode_setoran_dana.';'.number_format($total_sales).';'.$tipe_setoran1.';'.(($create_date_shift1 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift1))).';'.(($create_date_shift1 == '') ? 	'' : number_format($shift1)).';'.$tipe_setoran2.';'.(($create_date_shift2 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift2))).';'.(($create_date_shift2 == '') ? 	'' : number_format($shift2)).';'.$tipe_setoran3.';'.(($create_date_shift3 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift3))).';'.(($create_date_shift3 == '') ? 	'' : number_format($shift3)).';'.number_format($total_setor).';'.number_format($selisih)."\n";
	// 					}
	// 					$no++;
	// 				}
	// 				$cetak['html'] = $html;
	// 				$cetak['file_name'] = 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'.$nama_cabang.'.csv';
	// 				$this->load->view('view_csv', $cetak, FALSE);
  

	// 	}
	
		

	// }

	public function mks($branch_id,$kode_toko,$status_setor,$periode,$tipe_setor,$print){
		date_default_timezone_set("Asia/Bangkok");
		$start_date = date('Y-M-01', strtotime($periode));
		$end_date = date('Y-M-t', strtotime($periode));
		$start_date_sql = date('Y-m-d',strtotime($start_date));
		$end_date_sql = date('Y-m-d',strtotime($end_date));
		if($status_setor == 'A'){
			$status_setor = 'All';
		}else if($status_setor == 'M'){
			$status_setor = 'Match';
		}else if($status_setor == 'S'){
			$status_setor = 'Selisih';
		}else if($status_setor == 'S1'){
			$status_setor = 'Selisih > 1000';
		}
		$user_id = $this->session->userdata('usrId');
		$nik_user = $this->Mod_report->getNIK($user_id);
		$nik_user = $nik_user[0]->NIK;
		if($print=='pdf'){
			
		$this->load->library('Pdf');
		set_time_limit(0);

		ini_set('memory_limit', '-1');
		
		
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');
		ini_set('max_execution_time',-1);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$this->load->model('master/Mod_cdc_master_branch');
		$pdf->setPrintHeader(false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('REKAP PENERIMAAN SALES (per Toko)');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');
		$html='';
		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
		$pdf->Ln();
		$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(180, 5,'', 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->MultiCell(50, 5,'User : '.$nik_user.'-'.trim($userName), 0, 'R', 0, 0, '', '', true);
					
		$pdf->Ln();
		$pdf->SetFont('helveticaB', '', 12, '', true);
		$pdf->Cell(0, 0, 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm', 0, 1, 'C', 0, '', 0);
		$pdf->SetFont('helvetica', '', 8, '', true);
						
		$pdf->SetFont('helvetica', '', 10, '', true);
		$pdf->Ln();
		$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		if($branch_id=='100'){

		$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
		}else{
			$branch = $this->Mod_report->get_cabang_session($branch_id);
			$toko = $this->Mod_report->get_detail_toko($kode_toko);
			$pdf->MultiCell(50, 0,trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);

		}
		$pdf->SetFont('helvetica', '', 10, '', true);
					
		$pdf->Ln();
		$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Status Setor.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, $status_setor, 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 10, '', true);
						
		$pdf->Ln();
		$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		if($kode_toko == '0000'){
				$pdf->MultiCell(50, 0, 'All Toko', 0, 'L', 0, 0, '', '', true);
		}else{
				$pdf->MultiCell(50, 0, $kode_toko.'-'.trim($toko->STORE_NAME), 0, 'L', 0, 0, '', '', true);
		}
		$pdf->SetFont('helvetica', '', 10, '', true);

		$pdf->Ln();
		$pdf->MultiCell(90, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date('d-M-Y',strtotime($start_date)).' s/d '.date('d-M-Y',strtotime($end_date)), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(8);
		$pdf->SetFont('helvetica', '', 6, '', true);
		$html.= '<table border="1px" align="center">
								<tr>
									<th rowspan="2" width="2%">  <b> No. </b> </th>
									<th rowspan="1" colspan="2" width="14%"> <b>Toko Idm </b> </th>
									<th rowspan="2" width="3%"> <b> AM </b> </th>
									<th rowspan="2" width="3%"> <b> AS </b> </th>
									<th rowspan="2" width="5%"> <b> Type Inputan </b> </th>
									<th rowspan="2" width="7%"> <b> Sales Date </b> </th>
									<th rowspan="2" width="5%"> <b> Metode Setoran Dana </b> </th>
									<th rowspan="2" width="9%"> <b> Total Sales </b> </th>
									<th rowspan="1" colspan="3" width="14%"> <b>Shift 1 </b> </th>
									<th rowspan="1" colspan="3" width="14%"> <b>Shift 2 </b> </th>
									<th rowspan="1" colspan="3" width="14%"> <b>Shift 3 </b> </th>
									<th rowspan="2" width="5%"> <b>Total Setor </b> </th>
									<th rowspan="2" width="4%"> <b>Selisih </b> </th>
								</tr>
								<tr>
									<th rowspan="1" colspan="1">  <b> Kode </b> </th>
									<th rowspan="1" colspan="1"> <b>Nama </b> </th>
									<th rowspan="1" colspan="1"> <b>Type </b> </th>
									<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
									<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
									<th rowspan="1" colspan="1"> <b>Type </b> </th>
									<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
									<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
									<th rowspan="1" colspan="1"> <b>Type </b> </th>
									<th rowspan="1" colspan="1"> <b>Tgl Setor </b> </th>
									<th rowspan="1" colspan="1"> <b>Nilai (Rp) </b> </th>
								</tr>
						';
						$no=1;
						$page = 1;
						$total_sales=0;
						$total_kurset=0;
		if($branch_id=='100'){
			$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko,$tipe_setor,$status_setor);			
			$pdf->Ln();
		}else{
			$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko,$tipe_setor,$status_setor);		
		}
		foreach($data_content as $a){
			if($a->TIPE_INPUTAN == 'SHIFT'){
				$html.='<tr>
											<td>'.$no.'</td>
											<td>'.$a->STORE_CODE.'</td>
											<td>'.$a->STORE_NAME.'</td>
											<td>'.$a->AM.'</td>
											<td>'.$a->AS.'</td>
											<td>'.$a->TIPE_INPUTAN.'</td>
											<td>'.(($a->SALES_DATE == '') ? 	'' : date('d-M-Y',strtotime($a->SALES_DATE))).'</td>
											<td>'.$a->TIPE_SETORAN1.'</td>
											<td align="right">'.number_format($a->TOTAL_SALES_AMOUNT).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : $a->TIPE_SETORAN1).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT1))).'</td>
											<td align="right">'.(($a->CREATE_DATE_SHIFT1 == '') ? 	'' : number_format($a->SHIFT1)).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : $a->TIPE_SETORAN2).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT2))).'</td>
											<td align="right">'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : number_format($a->SHIFT2)).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : $a->TIPE_SETORAN3).'</td>
											<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT3))).'</td>
											<td align="right">'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : number_format($a->SHIFT3)).'</td>
											<td align="right">'.number_format($a->TOTAL_SALES).'</td>
											<td align="right">'.number_format($a->SELISIH).' </td>
										</tr>
										';
			}else{
				$html.='
					<tr>
						<td>'.$no.'</td>
						<td>'.$a->STORE_CODE.'</td>
						<td>'.$a->STORE_NAME.'</td>
						<td>'.$a->AM.'</td>
						<td>'.$a->AS.'</td>
						<td>'.$a->TIPE_INPUTAN.'</td>
						<td>'.(($a->SALES_DATE == '') ? 	'' : date('d-M-Y',strtotime($a->SALES_DATE))).'</td>
						<td>'.$a->TIPE_SETORAN_HARIAN.'</td>
						<td align="right">'.number_format($a->TOTAL_SALES_AMOUNT).'</td>
						<td>'.$a->TIPE_SETORAN_HARIAN.'</td>
						<td>'.(($a->CREATE_DATE4 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE4))).'</td>
						<td>'.number_format($a->HARIAN).'</td>
						<td>'.$a->TIPE_SETORAN2.'</td>
						<td>'.(($a->CREATE_DATE_SHIFT2 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT2))).'</td>
						<td> </td>
						<td>'.$a->TIPE_SETORAN3.'</td>
						<td>'.(($a->CREATE_DATE_SHIFT3 == '') ? 	'' : date('d-M-Y',strtotime($a->CREATE_DATE_SHIFT3))).'</td>
						<td> </td>
						<td align="right">'.number_format($a->TOTAL_SALES).'</td>
						<td align="right">'.number_format($a->SELISIH).' </td>
					</tr>
				';
			}
				$no++;
		}
		$html .='</table>';
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		ob_end_clean();
		$pdf->Output('Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'.date('YmdHi').'.pdf', 'I');

		}else{
			date_default_timezone_set("Asia/Bangkok");
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			if($branch_id==100){
				$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			if($kode_toko=='0000'){
				$kode_dan_nama_toko = 'All Toko';
			}else{
				$toko = $this->Mod_report->get_detail_toko($kode_toko);
				$kode_dan_nama_toko = $kode_toko.'-'.$toko->STORE_NAME;
			}
			$userName = $this->session->userdata('username');
			$html = 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Kode Toko - Nama Toko : '.$kode_dan_nama_toko."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($start_date))).' s/d '.urldecode(date('d-M-Y',strtotime($end_date)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-M-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$nik_user.'-'.trim($userName)."\n";
				
			
			$html .= "\n".'No;KODE_TOKO;NAMA_TOKO;AM;AS;TYPE_INPUTAN;SALES_DATE;METODE_SETORAN_DANA;TOTAL_SALES;TIPE_SHIFT1;TGL_SETOR_SHIFT1;NILAI_SHIFT1;TIPE_SHIFT2;TGL_SETOR_SHIFT2;NILAI_SHIFT2;TIPE_SHIFT3;TGL_SETOR_SHIFT3;NILAI_SHIFT3;TOTAL_SETOR;SELISIH'."\n";
			$no=1;
		
			$total_sales=0;
			$total_kurset=0;
			if($branch_id=='100'){
				$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,'x',$branch_id,$kode_toko,$tipe_setor,$status_setor);
					
			}else{
					$data_content= $this->Mod_report->get_data_monitoring_sales_fisik($start_date_sql,$end_date_sql,$branch[0]->BRANCH_CODE,$branch_id,$kode_toko,$tipe_setor,$status_setor);

					
				}
				foreach ($data_content as $key) {
						if($key->TIPE_INPUTAN == 'SHIFT'){
							$kode_toko=$key->STORE_CODE;
							$nama_toko=$key->STORE_NAME;
							$am=$key->AM;
							$as=$key->AS;
							$tipe_inputan=$key->TIPE_INPUTAN;
							$sales_date=$key->SALES_DATE;
							$metode_setoran_dana=$key->TIPE_SETORAN1;
							$total_sales=$key->TOTAL_SALES_AMOUNT;
							$tipe_setoran1=$key->TIPE_SETORAN1;
							$create_date_shift1=$key->CREATE_DATE_SHIFT1;
							$shift1=$key->SHIFT1;
							$tipe_setoran2=$key->TIPE_SETORAN2;
							$create_date_shift2=$key->CREATE_DATE_SHIFT2;
							$shift2=$key->SHIFT2;
							$tipe_setoran3=$key->TIPE_SETORAN3;
							$create_date_shift3=$key->CREATE_DATE_SHIFT3;
							$shift3=$key->SHIFT3;
							$total_setor=$key->TOTAL_SALES;
							$selisih=$key->SELISIH;
							
							$html .= $no.';'.$kode_toko.';'.$nama_toko.';'.trim($am).';'.trim($as).';'.$tipe_inputan.';'.date('d-M-Y',strtotime($sales_date)).';'.$metode_setoran_dana.';'.number_format($total_sales).';'.$tipe_setoran1.';'.(($create_date_shift1 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift1))).';'.(($create_date_shift1 == '') ? 	'' : number_format($shift1)).';'.$tipe_setoran2.';'.(($create_date_shift2 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift2))).';'.(($create_date_shift2 == '') ? 	'' : number_format($shift2)).';'.$tipe_setoran3.';'.(($create_date_shift3 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift3))).';'.(($create_date_shift3 == '') ? 	'' : number_format($shift3)).';'.number_format($total_setor).';'.number_format($selisih)."\n";
						}else{
							$kode_toko=$key->STORE_CODE;
							$nama_toko=$key->STORE_NAME;
							$am=$key->AM;
							$as=$key->AS;
							$tipe_inputan=$key->TIPE_INPUTAN;
							$sales_date=$key->SALES_DATE;
							$metode_setoran_dana=$key->TIPE_SETORAN_HARIAN;
							$total_sales=$key->TOTAL_SALES_AMOUNT;
							$tipe_setoran1=$key->TIPE_SETORAN_HARIAN;
							$create_date_shift1=$key->CREATE_DATE4;
							$shift1=$key->HARIAN;
							$tipe_setoran2=$key->TIPE_SETORAN2;
							$create_date_shift2=$key->CREATE_DATE_SHIFT2;
							$shift2=$key->SHIFT2;
							$tipe_setoran3=$key->TIPE_SETORAN3;
							$create_date_shift3=$key->CREATE_DATE_SHIFT3;
							$shift3=$key->SHIFT3;
							$total_setor=$key->TOTAL_SALES;
							$selisih=$key->SELISIH;
							
							$html .= $no.';'.$kode_toko.';'.$nama_toko.';'.trim($am).';'.trim($as).';'.$tipe_inputan.';'.date('d-M-Y',strtotime($sales_date)).';'.$metode_setoran_dana.';'.number_format($total_sales).';'.$tipe_setoran1.';'.(($create_date_shift1 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift1))).';'.(($create_date_shift1 == '') ? 	'' : number_format($shift1)).';'.$tipe_setoran2.';'.(($create_date_shift2 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift2))).';'.(($create_date_shift2 == '') ? 	'' : number_format($shift2)).';'.$tipe_setoran3.';'.(($create_date_shift3 == '') ? 	'' : date('d-M-Y',strtotime($create_date_shift3))).';'.(($create_date_shift3 == '') ? 	'' : number_format($shift3)).';'.number_format($total_setor).';'.number_format($selisih)."\n";
						}
						$no++;
					}
					$cetak['html'] = $html;
					$cetak['file_name'] = 'Laporan Monitoring Kurang Setor Fisik Sales Toko Idm'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  

		}
	
		

	}

	public function rst($branch_id,$tglawal,$tglakhir,$print){
		if($print=='pdf'){
				date_default_timezone_set("Asia/Bangkok");

		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		
		$this->load->library('Pdf');
		set_time_limit(0);

		ini_set('memory_limit', '-1');
		
		
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');
		ini_set('max_execution_time',1200);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$this->load->model('master/Mod_cdc_master_branch');
		$pdf->setPrintHeader(false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('REKAP PENERIMAAN SALES (per Toko)');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setFontSubsetting(true);
		$pdf->AddPage('P','A4');


		if($branch_id=='100'){
						
						

						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
					
						
						$pdf->Ln();
						$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
					
						$pdf->Ln();
						$pdf->SetFont('helveticaB', '', 12, '', true);
						$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Toko)', 0, 1, 'C', 0, '', 0);
						$pdf->SetFont('helvetica', '', 8, '', true);
						
						$pdf->SetFont('helvetica', '', 10, '', true);
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 10, '', true);
					
				
						
						$pdf->Ln();
						$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
						$pdf->Ln(8);
						$pdf->setCellPaddings(1, 1, 1, 1);
						$pdf->SetFont('helveticaB', '', 7, '', true);
						$pdf->MultiCell(15, 5, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);
						
						
						$pdf->Ln();
						
						
						$page = 1;
						$no=1;
						$total_sales=0;
						$total_kurset=0;
						$data=$this->Mod_report->rekap_penerimaan_sales_toko($branch_id,$tglawal,$tglakhir);
						foreach ($data as $key) {

							if($page == 35){
								$page=0;
								$page++;
								$pdf->AddPage('P','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
							
								
								$pdf->Ln();
								$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
							
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Toko)', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
								
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'All Cabang', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 10, '', true);
							
						
								
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(8);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(15, 5, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
						

							}
							$cabang=$key->BRANCH_ALT;
							$toko=$key->STORE_CODE;
							$nama=$key->STORE_NAME;
							$kurset=$key->KURSET;
							$payment_point=$key->PAYMENT_POINT;
							$sales=$key->SALES;
							$pdf->MultiCell(15, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$toko, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5,$nama, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5, number_format($sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$payment_point, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$total_sales+=$sales;
							$total_kurset+=$kurset;
							$page++;
						}
						$pdf->MultiCell(105, 5,'TOTAL', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, number_format($total_sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5,'', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5,number_format($total_kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(100, 5, '*Kolom Payment Point untuk sementara dikosongkan.', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();		
				

		}else{
						$branch = $this->Mod_report->get_cabang_session($branch_id);
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
							
								
								$pdf->Ln();
								$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
							
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Toko)', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
								
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0,trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 10, '', true);
							
						
								
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(8);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(15, 5, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();


						
						
						
						
						$page = 1;
						$no=1;
						$total_sales=0;
						$total_kurset=0;
						$data=$this->Mod_report->rekap_penerimaan_sales_toko($branch_id,$tglawal,$tglakhir);
						foreach ($data as $key) {

							if($page == 35){
								$page=0;
								$page++;
								$pdf->AddPage('P','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
							
								
								$pdf->Ln();
								$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
							
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'REKAP PENERIMAAN SALES (per Toko)', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
								
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0,trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 10, '', true);
							
						
								
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(8);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(15, 5, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'CBG', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5, 'TOKO', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'NAMA', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'SALES', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'PAYMENT POINT', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'KURSET', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();

								
						

							}
							$cabang=$key->BRANCH_ALT;
							$toko=$key->STORE_CODE;
							$nama=$key->STORE_NAME;
							$kurset=$key->KURSET;
							$payment_point=$key->PAYMENT_POINT;
							$sales=$key->SALES;
							$pdf->MultiCell(15, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$cabang, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$toko, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5,$nama, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5, number_format($sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,$payment_point, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$no++;
							$pdf->Ln();
							$total_sales+=$sales;
							$total_kurset+=$kurset;
							$page++;
						}
						$pdf->MultiCell(105, 5,'TOTAL', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5, number_format($total_sales, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5,'', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 5,number_format($total_kurset, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						$pdf->MultiCell(100, 5, '*Kolom Payment Point untuk sementara dikosongkan.', 0, 'L', 0, 0, '', '', true);
						$pdf->Ln();

		}
		

		$pdf->Output('REKAP PENERIMAAN SALES (per Toko)'.date('YmdHi').'.pdf', 'I');

		}else{
			date_default_timezone_set("Asia/Bangkok");

		
			set_time_limit(0);

			ini_set('memory_limit', '-1');
			if($branch_id==100){
				$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			$html = 'REKAP PENERIMAAN SALES (per Toko)'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($tglawal))).' s/d '.urldecode(date('d-M-Y',strtotime($tglakhir)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Tgl Cetak : '.date('d-m-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$this->session->userdata('username')."\n";
				
			
			$html .= "\n".'No;CBG;TOKO;NAMA;SALES;PAYMENT POINT;KURSET;'."\n";
				$no=1;
		
				$total_sales=0;
				$total_kurset=0;
				$data=$this->Mod_report->rekap_penerimaan_sales_toko($branch_id,$tglawal,$tglakhir);
				foreach ($data as $key) {

					$cabang=$key->BRANCH_ALT;
					$toko=$key->STORE_CODE;
					$nama=$key->STORE_NAME;
					$kurset=$key->KURSET;
					$payment_point=$key->PAYMENT_POINT;
					$sales=$key->SALES;
							
			
							
							
					$html .= $no.';'.$cabang.';'.$toko.';'.$nama.';'.$sales.';'.$payment_point.';'.$kurset."\n";
								
					$no++;
							
					$total_sales+=$sales;
					$total_kurset+=$kurset;
							
							# code...
					}
						
				
							
								
					
						
						
					
					$html .= ';;;Grand Total;'.$total_sales.';'.''.';'.$total_kurset."\n";
					$html .= '*Kolom Payment Point untuk sementara dikosongkan.;'."\n";
					$cetak['html'] = $html;
					$cetak['file_name'] = 'REKAP_PENERIMAAN_SALES_per_Toko_'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  

		}
	
		

	}
	public function pst($branch_id,$tglawal,$tglakhir,$print,$sort_by,$jumlah_toko){

		if($sort_by=='QAsc'){
          $sort_desc=' Qty ASCENDING';
        }else if($sort_by=='QDesc'){
          $sort_desc=' Qty DESCENDING';
        }else if($sort_by=='RDesc'){
          $sort_desc=' Rp DESCENDING';
        }else if($sort_by=='RAsc'){
          $sort_desc=' Rp ASCENDING';
        }
		if($print=='pdf'){
			date_default_timezone_set("Asia/Bangkok");

			$start_date = date_create($tglawal);
			$end_date = date_create($tglakhir);

			
			$this->load->library('Pdf');
			set_time_limit(0);

			ini_set('memory_limit', '-1');
		
		
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$userName = $this->session->userdata('username');
			ini_set('max_execution_time',1200);
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			$this->load->model('master/Mod_cdc_master_branch');
	
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('REKAP TOKO PENDING SETOR');
			$pdf->SetSubject('');

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->setPrintHeader(false);
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFontSubsetting(true);
			$pdf->AddPage('P','A4');


			if($branch_id=='100'){
				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
							
								
				$pdf->Ln();
				$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
				$pdf->Ln();
				$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
							
				$pdf->Ln();
				$pdf->SetFont('helveticaB', '', 12, '', true);
				$pdf->Cell(0, 0, 'REKAP TOKO PENDING SETOR', 0, 1, 'C', 0, '', 0);
				$pdf->SetFont('helvetica', '', 8, '', true);
								
				$pdf->SetFont('helvetica', '', 10, '', true);
				$pdf->Ln();
				$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 0,'All Cabang', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 10, '', true);
							
						
								
				$pdf->Ln();
				$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
				$pdf->Ln();

				$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 0, 'Sort By', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(100, 0, $sort_desc, 0, 'L', 0, 0, '', '', true);

				$pdf->Ln(8);
				$pdf->setCellPaddings(1, 1, 1, 1);
								
				$pdf->SetFont('helveticaB', '', 7, '', true);
				$pdf->MultiCell(8, 10, 'No', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 10, 'TOKO', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(60, 10, 'NAMA', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(60, 5, 'PENDING SETOR', 1, 'C', 0, 0, '', '', true);
				$pdf->Ln();
				$pdf->MultiCell(88, 5, '', 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(30, 5, 'Rp', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(30, 5, 'Qty', 1, 'C', 0, 0, '', '', true);
				$pdf->Ln();



				
				$page = 1;
				$no=1;
				$rp=0;
				$qty=0;
				$total_rp=0;
				$total_qty=0;
				$subtotal_rp=0;
				$subtotal_qty=0;
				$data2=$this->Mod_report->loop_cabang('000',$tglawal,$tglakhir,$sort_by);

				foreach ($data2 as $cabang) {

						$data=$this->Mod_report->rekap_pending_setor($cabang->branch_id,$tglawal,$tglakhir,$sort_by,$jumlah_toko);
						$pdf->MultiCell(148, 5,$cabang->branch_name, 1, 'L', 0, 0, '', '', true);
						$page++;
						$pdf->Ln();
						$subtotal_rp=0;
						$subtotal_qty=0;
						foreach ($data as $key ) {
							if($page==20){
								$page=0;
								$pdf->AddPage('P','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
											
												
								$pdf->Ln();
								$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
											
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'REKAP TOKO PENDING SETOR', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
												
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0,'All Cabang', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 10, '', true);
											
										
												
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();

								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Sort By', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, $sort_desc, 0, 'L', 0, 0, '', '', true);

								$pdf->Ln(8);
								$pdf->setCellPaddings(1, 1, 1, 1);
												
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(8, 10, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 10, 'TOKO', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(60, 10, 'NAMA', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(60, 5, 'PENDING SETOR', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(88, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'Rp', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'Qty', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();


							}

								$toko=$key->STORE_CODE;
								$nama=$key->STORE_NAME;
								$rp=$key->total;
								$qty=$key->qty;

								$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 5,$toko, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(60, 5,$nama, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5,number_format($rp, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5,number_format($qty, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$total_rp+=$key->total;
								$total_qty+=$key->qty;
								$subtotal_rp+=$key->total;
								$subtotal_qty+=$key->qty;
								$no++;
								$page++;
					}
							$pdf->MultiCell(88, 5,'SUB TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($subtotal_rp, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($subtotal_qty, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->Ln();

							
			
				}
					
						

			}else{
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$pdf->SetFont('helvetica', '', 8, '', true);
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
					$pdf->SetFont('helvetica', '', 8, '', true);
					$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
											
												
					$pdf->Ln();
					$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
					$pdf->SetFont('helvetica', '', 8, '', true);
					$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
					$pdf->Ln();
					$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
					$pdf->SetFont('helvetica', '', 8, '', true);
					$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
											
					$pdf->Ln();
					$pdf->SetFont('helveticaB', '', 12, '', true);
					$pdf->Cell(0, 0, 'REKAP TOKO PENDING SETOR', 0, 1, 'C', 0, '', 0);
					$pdf->SetFont('helvetica', '', 8, '', true);
												
					$pdf->SetFont('helvetica', '', 10, '', true);
					$pdf->Ln();
					$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(50, 0,''.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
					$pdf->SetFont('helvetica', '', 10, '', true);
											
										
												
					$pdf->Ln();
					$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
					$pdf->Ln();

					$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(50, 0, 'Sort By', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(100, 0, $sort_desc, 0, 'L', 0, 0, '', '', true);

					$pdf->Ln(8);
					$pdf->setCellPaddings(1, 1, 1, 1);
												
					$pdf->SetFont('helveticaB', '', 7, '', true);
					$pdf->MultiCell(8, 10, 'No', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(20, 10, 'TOKO', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(60, 10, 'NAMA', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(60, 5, 'PENDING SETOR', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
					$pdf->MultiCell(88, 5, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(30, 5, 'Rp', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(30, 5, 'Qty', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();

						$page = 1;
						$no=1;
						$rp=0;
						$qty=0;
						$total_rp=0;
						$total_qty=0;
						$pdf->MultiCell(148, 5,trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						
						$data=$this->Mod_report->rekap_pending_setor($branch_id,$tglawal,$tglakhir,$sort_by,$jumlah_toko);
						foreach ($data as $key ) {
							if($page==20){
								$page=0;
								$pdf->AddPage('P','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(50, 5,'PT.INDOMARCO PRISMATAMA', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Tgl Cetak : '.date('d-M-Y'), 0, 'R', 0, 0, '', '', true);
														
															
								$pdf->Ln();
								$pdf->MultiCell(50, 5,''.$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME, 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'Pkl Cetak : '.date('H:i:s'), 0, 'R', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(50, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 5,'', 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->MultiCell(50, 5,'User : '.trim($userName), 0, 'R', 0, 0, '', '', true);
														
								$pdf->Ln();
								$pdf->SetFont('helveticaB', '', 12, '', true);
								$pdf->Cell(0, 0, 'REKAP TOKO PENDING SETOR', 0, 1, 'C', 0, '', 0);
								$pdf->SetFont('helvetica', '', 8, '', true);
															
								$pdf->SetFont('helvetica', '', 10, '', true);
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0,''.trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->SetFont('helvetica', '', 10, '', true);
														
													
															
								$pdf->Ln();
								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();

								$pdf->MultiCell(55, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Sort By', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, $sort_desc, 0, 'L', 0, 0, '', '', true);

								$pdf->Ln(8);
								$pdf->setCellPaddings(1, 1, 1, 1);
															
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(8, 10, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 10, 'TOKO', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(60, 10, 'NAMA', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(60, 5, 'PENDING SETOR', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(88, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'Rp', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(30, 5, 'Qty', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();


							}
							
							$toko=$key->STORE_CODE;
							$nama=$key->STORE_NAME;
							$rp=$key->total;
							$qty=$key->qty;
							
							$page++;
							
							$pdf->MultiCell(8, 5,$no, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 5,$toko, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(60, 5,$nama, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($rp, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->MultiCell(30, 5,number_format($qty, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							
							$total_rp+=$key->total;
							$total_qty+=$key->qty;
							$pdf->Ln();
							$no++;
							$page++;
							
						}
						
		}
			$pdf->MultiCell(88, 5,'TOTAL', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5,number_format($total_rp, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5,number_format($total_qty, 0, '.', ','), 1, 'R', 0, 0, '', '', true);

		$pdf->Output('REKAP TOKO PENDING SETOR'.date('YmdHi').'.pdf', 'I');
		


		}else{

			$nama_cabang = '';
			$no=1;
			$branch='';
			date_default_timezone_set("Asia/Bangkok");
			set_time_limit(0);

			ini_set('memory_limit', '-1');
		
			if($branch_id == 100){
					$nama_cabang = 'All Cabang IDM';
			}else{
					
					$branch = $this->Mod_report->get_cabang_session($branch_id);
					$nama_cabang = $branch[0]->BRANCH_NAME;
			}
			$html = 'REKAP TOKO PENDING SETOR'."\n".'PT.INDOMARCO PRISMATAMA'."\n".$this->Mod_report->get_cabang_session($this->session->userdata('branch_id'))[0]->BRANCH_NAME."\n".'Tanggal : '.urldecode(date('d-M-Y',strtotime($tglawal))).' s/d '.urldecode(date('d-M-Y',strtotime($tglakhir)))."\n".'Cabang : '.urldecode($nama_cabang)."\n".'Sort By : '.$sort_desc."\n".'Tgl Cetak : '.date('d-m-Y')."\n".'Pukul Cetak : '.date('H:i:s')."\n".'User : '.$this->session->userdata('username')."\n";
				
			



			if($branch_id==100){
				
				
				$html .= "\n".'No;TOKO;NAMA;PENDING SETOR;;';
				$html .= "\n".';;;Rp;Qty'."\n";
				$header= $this->Mod_report->loop_cabang('000',$tglawal,$tglakhir,$sort_by);
				$no=1;
		
				$rp=0;
				$qty=0;
				$total_rp=0;
				$total_qty=0;
				$subtotal_rp=0;
				$subtotal_qty=0;
				foreach ($header as $value1) {
					$data=$this->Mod_report->rekap_pending_setor($value1->branch_id,$tglawal,$tglakhir,$sort_by,$jumlah_toko);
					$html .= $value1->branch_name.';'."\n";
					$subtotal_rp=0;
					$subtotal_qty=0;
					foreach ($data as $key ) {
							
								$toko=$key->STORE_CODE;
								$nama=$key->STORE_NAME;
								$rp=$key->total;
								$qty=$key->qty;
										
								
								$html .= $no.';'.$toko.';'.$nama.';'.$rp.';'.$qty.';'."\n";
								$no++;
				
								$total_rp+=$key->total;
								$total_qty+=$key->qty;
								$subtotal_rp+=$key->total;
								$subtotal_qty+=$key->qty;
								
								
					}
						
					$html .= 'Sub Total;'.';;'.$subtotal_rp.';'.$subtotal_qty.';'."\n";
								
					
					
				}
				
  					$html .= ';;Grand Total;'.$total_rp.';'.$total_qty.';';
						
					$cetak['html'] = $html;
					$cetak['file_name'] = 'Monitoring_Penerimaan_Sales_'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);

				}else{

						$html .= "\n".'No;TOKO;NAMA;PENDING SETOR;;';
						$html .= "\n".';;;Rp;Qty'."\n";

						$rp=0;
						$qty=0;
						$total_rp=0;
						$total_qty=0;
						$no=1;
						$data=$this->Mod_report->rekap_pending_setor($branch_id,$tglawal,$tglakhir,$sort_by,$jumlah_toko);
						$html .= $nama_cabang.';'."\n";
						foreach ($data as $key ) {
								
									$toko=$key->STORE_CODE;
									$nama=$key->STORE_NAME;
									$rp=$key->total;
									$qty=$key->qty;
											
									$html .= $no.';'.$toko.';'.$nama.';'.$rp.';'.$qty.';'."\n";
									$no++;
					
									$total_rp+=$key->total;
									$total_qty+=$key->qty;
									
									
						}
							
						
					
					$html .= ';;Grand Total;'.$total_rp.';'.$total_qty.';';
						
						
					$cetak['html'] = $html;
					$cetak['file_name'] = 'REKAP_TOKO_PENDING_SETOR_'.$nama_cabang.'.csv';
					$this->load->view('view_csv', $cetak, FALSE);
  
										
				}
		}
		
	}
	public function lebset_per_shift($branch_id,$store_id,$tglawal,$tglakhir){
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branch = $this->Mod_report->get_cabang_session($branch_id);
		$store = $this->Mod_report->get_store_by_id($store_id);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Monitoring Setoran Dana Sales');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		$str_store = ($store) ? trim($store->STORE_CODE).' - '.trim($store->STORE_NAME) : 'ALL - ALL';

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
		$pdf->Ln(10);

		$pdf->SetFont('helveticaB', '', 15, '', true);
		$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
		$pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 11, '', true);
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(7);
		$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 7, '', true);
		$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();

		$page = 1;

		if($store_id == 0){ // start if store 0
			$total_shift1 = 0;
			$total_shift2 = 0;
			$total_shift3 = 0;
			$total_sales = 0;

			$store_data = $this->Mod_report->get_store_by_branch($branch_id);

			if($store_data){//start if store data
				foreach ($store_data as $sd) { // start loop data store
					$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $tglawal, $tglakhir);
					$count = 0;
					$no = 1;
					$subtotal_shift1 = 0;
					$subtotal_shift2 = 0;
					$subtotal_shift3 = 0;
					$subtotal_sales = 0;

					if($data_slp){//start if data slp
						foreach ($data_slp as $slp) {//start loop data slp
							$slp_date = date_create($slp->SALES_DATE);
							$receipt = $this->Mod_report->get_receipt_by_slp_shift($sd->STORE_CODE, $slp->SALES_DATE);
							
							
							$sales_shift1 = 0;
							$sales_shift2 = 0;
							$sales_shift3 = 0;
							$sales_harian = 0;
							$sales_harian2 = 0;
							$stn_f;
							$shift_flag;
							$selisih_sales1 = 0;
							$selisih_sales2 = 0;
							$selisih_sales3 = 0;
							$selisih_sales_h2 = 0;
							$kurset1 = 0;
							$kurset2 = 0;
							$kurset3 = 0;
							$lebset_h2 = 0;
							$lebset1 = 0;
							$lebset2 = 0;
							$lebset3 = 0;
							$slp_shift1 = $slp->SHIFT1;
							$slp_shift2 = $slp->SHIFT2;
							$slp_shift3 = $slp->SHIFT3;
							$pemegang1 = '';
							$pemegang2 = '';
							$pemegang3 = '';
							$pemegangh = '';


							if ($receipt) {//start if receipt
									foreach ($receipt as $data_rec) {//start loop receipt

									//if($data_rec->SHIFT_FLAG == 'Y'){
										if($data_rec->NO_SHIFT == '1'){
										$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp->PEMEGANG_SHIFT;
										}


										if($selisih_sales1 > 0){
											$kurset1 =$selisih_sales1;
										}
										elseif ($selisih_sales1 < 0) {
											$lebset1 = abs($selisih_sales1);
										}
									}	
									else if($data_rec->NO_SHIFT == '2'){
										$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang2 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($selisih_sales2 > 0){
											$kurset2 = $selisih_sales2;
										}
										elseif ($selisih_sales2 < 0) {
											$lebset2 = abs($selisih_sales2);
										}
									}
									else if($data_rec->NO_SHIFT == '3'){
										$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang3 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($selisih_sales3 > 0){
											$kurset3 =$selisih_sales3;
										}
										elseif ($selisih_sales3 < 0) {
											$lebset3 = abs($selisih_sales3);
										}
									}
									else{
										$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,1);

										$pemegangtmp2 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,2);

										$pemegangtmp3 = $this->Mod_report->get_pemegang_shift($sd->STORE_CODE,$slp->SALES_DATE,3);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($pemegangtmp2){
											$pemegang2 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($pemegangtmp3){
											$pemegang3 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if ($selisih_sales > 0) {
											$kurset = $selisih_sales;
										} elseif ($selisih_sales < 0) {
											$lebset = abs($selisih_sales);
										}
									}

									if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
										$sales_harian2 += $data_rec->ACTUAL_SALES_AMOUNT;
									}

									$stn_f = $data_rec->STN_FLAG;
									$shift_flag = $data_rec->SHIFT_FLAG;
									$rec_date = date_create($data_rec->CREATION_DATE);
								}// end if receipt
							  //}//end if shift flag y
								if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
									$selisih_sales_h2 = $slp->SALES_AMOUNT - $sales_harian2;
									$lebset1 = 0;
									$lebset2 = 0;
									$lebset3 = 0;
									if($selisih_sales_h2 < 0){
										$lebset_h2 = abs($selisih_sales_h2);
									}
								}
							}//end loop receipt 

							$subtotal_shift1 += $lebset1;
							$subtotal_shift2 += $lebset2;
							$subtotal_shift3 += $lebset3;
							$subtotal_sales += $lebset1+$lebset2+$lebset3;

							$total_shift1 += $lebset1;
							$total_shift2 += $lebset2;
							$total_shift3 += $lebset3;
							$total_sales += $lebset1+$lebset2+$lebset3;

							if($lebset1 != 0 || $lebset2 != 0 || $lebset3 != 0 || $lebset_h2 != 0){
									if($count == 0){
										$pdf->SetFont('helveticaB', '', 7, '', true);
											$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(246, 0, trim($sd->STORE_CODE).' - '.trim($sd->STORE_NAME), 1, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											$count++;
											if($page == 20){// start if page 0
												$pdf->AddPage('L','A4');
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$page = 1;
										}// end if page 0	
									}


									$pdf->SetFont('helvetica', '', 7, '', true);
									$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);

									$pdf->MultiCell(18, 0, number_format($lebset1+$lebset2+$lebset3+$lebset_h2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang1,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang2,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang3,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$page++;
									if($page == 20){// start if page 1
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 1	

							}//end if lebset	
						}//end loop data slp

							if($count > 0){
								$pdf->SetFont('helveticaB', '',7, '', true);
								$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(33, 4.2, 'Sub Total Per Toko Idm.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 0, number_format($subtotal_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page++;
									if($page == 20){ // start if page 2
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 2	
							}
					}//end if data slp
				}//end loop data store
							$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
							$page++;
							if($page == 20){ // start if page 3
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 15, '', true);
								$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 11, '', true);
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page = 1;
						}// end if page 3

							/*$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, ' ', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();*/
			}//end if store data
		}// end if store 0
		else{ // start if store != 0	
			$total_shift1 = 0;
			$total_shift2 = 0;
			$total_shift3 = 0;
			$total_sales = 0;

			if($store){//start if store
				$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($store->STORE_CODE), $tglawal, $tglakhir);

				$no = 1;
				$subtotal_shift1 = 0;
				$subtotal_shift2 = 0;
				$subtotal_shift3 = 0;
				$subtotal_sales = 0;
				$count = 0;
					if($data_slp){//start data slp
						foreach ($data_slp as $slp) {//start loop data slp
								$slp_date = date_create($slp->SALES_DATE);
								$receipt = $this->Mod_report->get_receipt_by_slp_shift($store->STORE_CODE, $slp->SALES_DATE);
								

								$sales_shift1 = 0;
								$sales_shift2 = 0;
								$sales_shift3 = 0;
								$sales_harian = 0;
								$sales_harian2 = 0;
								$stn_f;
								$shift_flag;
								$selisih_sales1 = 0;
								$selisih_sales2 = 0;
								$selisih_sales3 = 0;
								$selisih_sales_h2 = 0;
								$kurset1 = 0;
								$kurset2 = 0;
								$kurset3 = 0;
								$lebset_h2 = 0;
								$lebset1 = 0;
								$lebset2 = 0;
								$lebset3 = 0;
								$slp_shift1 = $slp->SHIFT1;
								$slp_shift2 = $slp->SHIFT2;
								$slp_shift3 = $slp->SHIFT3;
								

								if ($receipt) {//start if receipt
									


									foreach ($receipt as $data_rec) {//start loop receipt

									//if($data_rec->SHIFT_FLAG == 'Y'){
										if($data_rec->NO_SHIFT == '1'){
										$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp->PEMEGANG_SHIFT;
										}


										if($selisih_sales1 > 0){
											$kurset1 =$selisih_sales1;
										}
										elseif ($selisih_sales1 < 0) {
											$lebset1 = abs($selisih_sales1);
										}
									}	
									else if($data_rec->NO_SHIFT == '2'){
										$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang2 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($selisih_sales2 > 0){
											$kurset2 = $selisih_sales2;
										}
										elseif ($selisih_sales2 < 0) {
											$lebset2 = abs($selisih_sales2);
										}
									}
									else if($data_rec->NO_SHIFT == '3'){
										$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,$data_rec->NO_SHIFT);

										if($pemegangtmp){
											$pemegang3 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($selisih_sales3 > 0){
											$kurset3 =$selisih_sales3;
										}
										elseif ($selisih_sales3 < 0) {
											$lebset3 = abs($selisih_sales3);
										}
									}
									else{
										$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
										$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;

										$pemegangtmp = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,1);

										$pemegangtmp2 = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,2);

										$pemegangtmp3 = $this->Mod_report->get_pemegang_shift($store->STORE_CODE,$slp->SALES_DATE,3);

										if($pemegangtmp){
											$pemegang1 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($pemegangtmp2){
											$pemegang2 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if($pemegangtmp3){
											$pemegang3 = $pemegangtmp->PEMEGANG_SHIFT;
										}

										if ($selisih_sales > 0) {
											$kurset = $selisih_sales;
										} elseif ($selisih_sales < 0) {
											$lebset = abs($selisih_sales);
										}
									}

									if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
										$sales_harian2 += $data_rec->ACTUAL_SALES_AMOUNT;
									}

									$stn_f = $data_rec->STN_FLAG;
									$shift_flag = $data_rec->SHIFT_FLAG;
									$rec_date = date_create($data_rec->CREATION_DATE);
								}// end if receipt
							  //}//end if shift flag y
								if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
									$selisih_sales_h2 = $slp->SALES_AMOUNT - $sales_harian2;
									$lebset1 = 0;
									$lebset2 = 0;
									$lebset3 = 0;
										if($selisih_sales_h2 < 0){
											$lebset_h2 = abs($selisih_sales_h2);
										}
								}
							}//end loop receipt

							/*$subtotal_shift1 += $lebset1;
							$subtotal_shift2 += $lebset2;
							$subtotal_shift3 += $lebset3;
							$subtotal_sales += $lebset1+$lebset2+$lebset3;*/

							$total_shift1 += $lebset1;
							$total_shift2 += $lebset2;
							$total_shift3 += $lebset3;
							$total_sales += $lebset1+$lebset2+$lebset3;

							if($lebset1 != 0 || $lebset2 != 0 || $lebset3 != 0 || $lebset_h2 != 0){

								if($count == 0){
									$pdf->SetFont('helveticaB', '', 7, '', true);
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(171, 0, trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$page++;
									$count++;
										if($page == 30){// start if page 0
											$pdf->AddPage('L','A4');
											$pdf->SetFont('helveticaB', '', 15, '', true);
											$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
											$pdf->Ln(4);
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(7);
											$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helveticaB', '', 7, '', true);
											$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page = 1;
									}// end if page 0	
								}//end if count

									$pdf->SetFont('helvetica', '', 7, '', true);
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);

									if($shift_flag == 'Y' && $slp->SHIFT != 0){
										$total_lebset_shift = $lebset1+$lebset2+$lebset3;
										$pdf->MultiCell(18, 0, number_format($total_lebset_shift, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
										$subtotal_shift1 += $lebset1;
										$subtotal_shift2 += $lebset2;
										$subtotal_shift3 += $lebset3;
										$subtotal_sales += $total_lebset_shift;
									}
									else if($shift_flag == 'Y' && $slp->SHIFT != 1){
										$pdf->MultiCell(18, 0, number_format($sales_harian2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
										$subtotal_sales += $sales_harian2;
									}
									else{
										$pdf->MultiCell(18, 0, number_format($sales_harian, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
										$subtotal_sales += $sales_harian;
									}

									//$pdf->MultiCell(18, 0, number_format($lebset1+$lebset2+$lebset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang1,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang2,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(20, 0, number_format($lebset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(45, 0, substr($pemegang3,0,28), 1, 'C', 0, 0, '', '', true);
									$pdf->ln();

									$page++;
									if($page == 20){// start if page 1
										$pdf->AddPage('L','A4');
										$pdf->SetFont('helveticaB', '', 15, '', true);
										$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
										$pdf->Ln(4);
										$pdf->SetFont('helvetica', '', 11, '', true);
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
										$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
										$pdf->Ln(7);
										$pdf->setCellPaddings(1, 1, 1, 1);
										$pdf->SetFont('helveticaB', '', 7, '', true);
										$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
										$pdf->Ln();
										$page = 1;
								}// end if page 1
							}//end if lebset

						}//end loop data slp

							if($count > 0){
								$pdf->SetFont('helveticaB', '',7, '', true);
								$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(33, 4.2, 'Sub Total Per Toko Idm.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 0, number_format($subtotal_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($subtotal_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
							}//end if count
							
				}//end data slp
							$pdf->SetFont('helveticaB', '',7, '', true);
							$pdf->MultiCell(10, 0, ' ', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(33, 0, 'TOTAL', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(18, 0, number_format($total_sales, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift1 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift2 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(20, 0, number_format($total_shift3 , 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(45, 0, ' ' ,1, 'C', 0, 0, '', '', true);
							$pdf->Ln();

							$page++;
							if($page == 20){// start if page 2
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 15, '', true);
								$pdf->Cell(0, 0, 'LAPORAN LEBIH SETOR SALES PER RINCIAN PIMPINAN SHIFT', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 11, '', true);
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(70, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(95, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 7, '', true);
								$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18.2, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 18.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(213, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(43, 5, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(18, 13.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(65, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(61, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 8, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(45, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page = 1;
						}// end if page 2
			}//end if store
		}// end if store != 0

		ob_end_clean();
		$pdf->Output('lebset_per_shift'.date('YmdHi').'.pdf', 'I');
	}

	public function kurset_per_toko($branch_id,$am,$tglawal,$tglakhir){
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branch = $this->Mod_report->get_cabang_session($branch_id);
		$am_title = $this->Mod_report->get_am_for_title($am,$branch[0]->BRANCH_CODE);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Rincian Monitoring Kurang Setor Sales Per Toko IDM');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$start_date = date_create($tglawal);
		$end_date = date_create($tglakhir);

		$str_am = ($am_title) ? trim($am_title->AM_NUMBER).' - '.trim($am_title->AM_NAME) : 'ALL - ALL';

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
		$pdf->Ln(10);

		$pdf->SetFont('helveticaB', '', 15, '', true);
		$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
		$pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 11, '', true);
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->SetFont('helvetica', '', 11, '', true);
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(80, 0, $str_am, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(7);
		$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 7, '', true);
		$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln(5);
		$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln(11.2);


		/*$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 7, '', true);
		$pdf->MultiCell(10, 5, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 16, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 16, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 16, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(60, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 16, 'Kurang Setor Finance', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 16, 'Kurang Setor Virtual ', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(10, 0, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 0, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 0, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(40, 0, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(86, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->Multi+Cell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);*/

		$page = 1;
	
		if($am == '0'){//if am 0

			$am_data = $this->Mod_report->get_am_data($am,$branch[0]->BRANCH_CODE);
			if($am_data){//start if am data

			foreach ($am_data as $amd) {//start loop data
					$amn = 1;
					$total_am = 0;
					$total_am_s1 = 0;
					$total_am_s2 = 0;
					$total_am_s3 = 0;
					$total_am_kurset = 0;
					$total_am_kurvir = 0;
					$as_data = $this->Mod_report->get_as_data($amd->AM_NUMBER,$branch[0]->BRANCH_CODE);

					if($as_data){//start if as data
						
						foreach ($as_data as $asd) {//start loop as data
							$asn = 1;
							$total_as = 0;
							$total_as_s1 = 0;
							$total_as_s2 = 0;
							$total_as_s3 = 0;
							$total_as_kurset = 0;
							$total_as_kurvir = 0;
							$toko_as = $this->Mod_report->get_toko_as($asd->AS_NUMBER,$branch[0]->BRANCH_CODE);
							
							if($toko_as){//start if toko as
								foreach ($toko_as as $as) { // start loop toko as
									$tokon = 1;
									$store_name = $this->Mod_report->get_store_name(trim($as->STORE_CODE));

									if($store_name){
										$name_store = $store_name->STORE_NAME;
									}
									else{
										$name_store = '';
									}
									
									$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($as->STORE_CODE), $tglawal, $tglakhir); 
									$no = 1;

									if($data_slp){//start if data slp
										if($amn == 1){
											//$am_name = $this->Mod_report->get_am_name();
											$amn += 1;
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(248, 0, 'Nama AM : '.$amd->AM_NAME, 1, 'L', 0, 0, '', '', true);
											//$pdf->MultiCell(5, 0, ':', 1, 'C', 0, 0, '', '', true);
											//$pdf->MultiCell(221, 0, $amd->AM_NAME , 1, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												//$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);

												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am, 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												//$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
									}//end cetak header am

									if($asn == 1){
										$asn +=1;
										$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(248, 0, 'Nama AS : '.$asd->AS_NAME, 1, 'L', 0, 0, '', '', true);
										//$pdf->MultiCell(5, 0, ':', 1, 'C', 0, 0, '', '', true);
										//$pdf->MultiCell(221, 0,$asd->AS_NAME, 1, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$page++;
										if($page == 18){
											$pdf->AddPage('L','A4');
											//$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
											$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
											$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
											$pdf->Ln(10);

											$pdf->SetFont('helveticaB', '', 15, '', true);
											$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
											$pdf->Ln(4);
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(7);
											//$pdf->setCellPaddings(0, 0, 0, 0);
											$pdf->SetFont('helveticaB', '', 7, '', true);
											$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln(5);
											$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln(11.2);
											$page = 1;
										}
									}//end cetak as

									if($tokon == 1){
										$tokon += 1;
										$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
										$pdf->MultiCell(248, 0, $as->STORE_CODE.' - '.$name_store, 1, 'L', 0, 0, '', '', true);
										$pdf->Ln();
										$page++;
										if($page == 18){
											$pdf->AddPage('L','A4');
											//$pdf->setCellPaddings(1, 1, 1, 1);
											$pdf->SetFont('helvetica', '', 8, '', true);
											$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
											$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
											$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
											$pdf->Ln(10);
											$pdf->SetFont('helveticaB', '', 15, '', true);
											$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
											$pdf->Ln(4);
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->SetFont('helvetica', '', 11, '', true);
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
											$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
											$pdf->Ln(7);
											//$pdf->setCellPaddings(0, 0, 0, 0);
											$pdf->SetFont('helveticaB', '', 7, '', true);
											$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln(5);
											$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
											$pdf->Ln(11.2);
											$page = 1;
										}
								}//end cetak header toko




										foreach ($data_slp as $slp) {//start loop slp
											$slp_date = date_create($slp->SALES_DATE);
											$receipt = $this->Mod_report->get_receipt_by_slp_shift(trim($as->STORE_CODE), $slp->SALES_DATE);
											$hand_date = $this->Mod_report->get_hand_date(trim($as->STORE_CODE),$slp->SALES_DATE);

											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0,date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);
											

											$kurset1 = 0;
											$kurset2 = 0;
											$kurset3 = 0;
											$lebset1 = 0;
											$lebset2 = 0;
											$lebset3 = 0;
											$kurset_fin = 0;
											$kurset_vir = 0;
											$rec_date = '';

												if ($receipt) {//start if receipt
													$sales_shift1 = 0;
													$sales_shift2 = 0;
													$sales_shift3 = 0;
													$sales_harian = 0;
													$stn_f;
													$shift_flag;
													$selisih_sales1 = 0;
													$selisih_sales2 = 0;
													$selisih_sales3 = 0;
													$slp_shift1 = $slp->SHIFT1;
													$slp_shift2 = $slp->SHIFT2;
													$slp_shift3 = $slp->SHIFT3;


													foreach ($receipt as $data_rec) {//start loop receipt

													if($data_rec->SHIFT_FLAG == 'Y'){
														if($data_rec->NO_SHIFT == '1'){
														$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
														if($selisih_sales1 > 0){
															$kurset1 =$selisih_sales1;
														}
														elseif ($selisih_sales1 < 0) {
															$lebset1 = abs($selisih_sales1);
														}
													}	
													else if($data_rec->NO_SHIFT == '2'){
														$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
														if($selisih_sales2 > 0){
															$kurset2 = $selisih_sales2;
														}
														elseif ($selisih_sales2 < 0) {
															$lebset2 = abs($selisih_sales2);
														}
													}
													else if($data_rec->NO_SHIFT == '3'){
														$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
														if($selisih_sales3 > 0){
															$kurset3 =$selisih_sales3;
														}
														elseif ($selisih_sales3 < 0) {
															$lebset3 = abs($selisih_sales3);
														}
													}
													else{
														$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;
														if ($selisih_sales > 0) {
															$kurset = $selisih_sales;
														} elseif ($selisih_sales < 0) {
															$lebset = abs($selisih_sales);
														}
													}

													$stn_f = $data_rec->STN_FLAG;
													$shift_flag = $data_rec->SHIFT_FLAG;
													$rec_date = date_create($data_rec->CREATION_DATE);
												}//end if shift flag y
											  }//end loop receipt
											}// end if receipt

											if($hand_date){
												$rec_date = date_format($hand_date,"d-M-Y");
											}
											else{
												$rec_date = '';
											}

											$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.trim($as->STORE_CODE).'/'.$slp->SALES_DATE);
											$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.trim($as->STORE_CODE).'/'.$slp->SALES_DATE);

											$total_am += $kurset1+$kurset2+$kurset3;
											$total_am_s1 += $kurset1;
											$total_am_s2 +=  $kurset2;
											$total_am_s3 +=  $kurset3;
											$total_am_kurset += $kurset_fin;
											$total_am_kurvir += $kurset_vir;

											$total_as += $kurset1+$kurset2+$kurset3;
											$total_as_s1 += $kurset1;
											$total_as_s2 += $kurset2;
											$total_as_s3 += $kurset3;
											$total_as_kurset += $kurset_fin;
											$total_as_kurvir += $kurset_vir;

											$pdf->MultiCell(25, 0,$rec_date, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset1+$kurset2+$kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($kurset_fin, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($kurset_vir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												//$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												//$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
										}//end loop slp
									}//end if data slp
								}//end loop toko as
										if($asn == 2){
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(58, 0, 'TOTAL PER AS', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($total_as_kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($total_as_kurvir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												//$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												//$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}//end ganti page
										}//end if asn 2
							}//end if toko as
						}//end loop as data
							if($amn == 2){
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(58, 0, 'TOTAL PER AM', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($total_am_kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($total_am_kurvir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page++;
												if($page == 18){
													$pdf->AddPage('L','A4');
													//$pdf->setCellPaddings(0, 0, 0, 0);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
													$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
													$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
													$pdf->Ln(10);
													$pdf->SetFont('helveticaB', '', 15, '', true);
													$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
													$pdf->Ln(4);
													$pdf->SetFont('helvetica', '', 11, '', true);
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->SetFont('helvetica', '', 11, '', true);
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
													$pdf->Ln(7);
													//$pdf->setCellPaddings(0, 0, 0, 0);
													$pdf->SetFont('helveticaB', '', 7, '', true);
													$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln(5);
													$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln(11.2);
													$page = 1;
										}//end ganti page
								}//end if amn 2
					}//end if as data
				}//end loop am data
			}//end if am data
		}//end if am 0
		else{//start else if am !=0
		 	$am_data = $this->Mod_report->get_am_data($am,$branch[0]->BRANCH_CODE);
			if($am_data){//start if am data

			foreach ($am_data as $amd) {//start loop data
					$amn = 1;
					$total_am = 0;
					$total_am_s1 = 0;
					$total_am_s2 =  0;
					$total_am_s3 =  0;
					$total_am_kurset = 0;
					$total_am_kurvir = 0;
					$as_data = $this->Mod_report->get_as_data($amd->AM_NUMBER,$branch[0]->BRANCH_CODE);

					if($as_data){//start if as data
						
						foreach ($as_data as $asd) {//start loop as data
							$asn = 1;
							$total_as = 0;
							$total_as_s1 = 0;
							$total_as_s2 =  0;
							$total_as_s3 =  0;
							$total_as_kurset = 0;
							$total_as_kurvir = 0;
							$toko_as = $this->Mod_report->get_toko_as($asd->AS_NUMBER,$branch[0]->BRANCH_CODE);
							
							if($toko_as){//start if toko as
								foreach ($toko_as as $as) { // start loop toko as
									$tokon = 1;
									$store_name = $this->Mod_report->get_store_name(trim($as->STORE_CODE));

									if($store_name){
										$name_store = $store_name->STORE_NAME;
									}
									else{
										$name_store = '';
									}
									
									$data_slp = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($as->STORE_CODE), $tglawal, $tglakhir); 
									$no = 1;

									if($data_slp){//start if data slp

										if($amn == 1){
											$amn += 1;
											//$am_name = $this->Mod_report->get_am_name();
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(248, 0, 'Nama AM : '.$amd->AM_NAME, 1, 'L', 0, 0, '', '', true);
											//$pdf->MultiCell(5, 0, ':', 1, 'C', 0, 0, '', '', true);
											//$pdf->MultiCell(221, 0, $amd->AM_NAME , 1, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);

												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am, 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
										}//end cetak header

										if($asn == 1){
											$asn += 1;
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(248, 0, 'Nama AS : '.$asd->AS_NAME, 1, 'L', 0, 0, '', '', true);
											//$pdf->MultiCell(5, 0, ':', 1, 'C', 0, 0, '', '', true);
											//$pdf->MultiCell(221, 0,$asd->AS_NAME, 1, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);

												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
										}

										if($tokon == 1){
											$tokon += 1;
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(248, 0, $as->STORE_CODE.' - '.$name_store, 1, 'L', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
										}

										foreach ($data_slp as $slp) {//start loop slp
											$slp_date = date_create($slp->SALES_DATE);
											$receipt = $this->Mod_report->get_receipt_by_slp_shift(trim($as->STORE_CODE), $slp->SALES_DATE);

											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0,date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);
											

											$kurset1 = 0;
											$kurset2 = 0;
											$kurset3 = 0;
											$lebset1 = 0;
											$lebset2 = 0;
											$lebset3 = 0;
											$kurset_fin = 0;
											$kurset_vir = 0;
											$rec_date = '';

												if ($receipt) {//start if receipt
													$sales_shift1 = 0;
													$sales_shift2 = 0;
													$sales_shift3 = 0;
													$sales_harian = 0;
													$stn_f;
													$shift_flag;
													$selisih_sales1 = 0;
													$selisih_sales2 = 0;
													$selisih_sales3 = 0;
													$slp_shift1 = $slp->SHIFT1;
													$slp_shift2 = $slp->SHIFT2;
													$slp_shift3 = $slp->SHIFT3;


													foreach ($receipt as $data_rec) {//start loop receipt

													if($data_rec->SHIFT_FLAG == 'Y'){
														if($data_rec->NO_SHIFT == '1'){
														$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
														if($selisih_sales1 > 0){
															$kurset1 =$selisih_sales1;
														}
														elseif ($selisih_sales1 < 0) {
															$lebset1 = abs($selisih_sales1);
														}
													}	
													else if($data_rec->NO_SHIFT == '2'){
														$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
														if($selisih_sales2 > 0){
															$kurset2 = $selisih_sales2;
														}
														elseif ($selisih_sales2 < 0) {
															$lebset2 = abs($selisih_sales2);
														}
													}
													else if($data_rec->NO_SHIFT == '3'){
														$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
														if($selisih_sales3 > 0){
															$kurset3 =$selisih_sales3;
														}
														elseif ($selisih_sales3 < 0) {
															$lebset3 = abs($selisih_sales3);
														}
													}
													else{
														$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
														$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;
														if ($selisih_sales > 0) {
															$kurset = $selisih_sales;
														} elseif ($selisih_sales < 0) {
															$lebset = abs($selisih_sales);
														}
													}

													$stn_f = $data_rec->STN_FLAG;
													$shift_flag = $data_rec->SHIFT_FLAG;
													$rec_date = date_create($data_rec->CREATION_DATE);
												}//end if shift flag y
											  }//end loop receipt
											}// end if receipt

											if($rec_date){
												$rec_date = date_format($rec_date,"d-M-Y");
											}
											else{
												$rec_date = '';
											}

											$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.trim($as->STORE_CODE).'/'.$slp->SALES_DATE);
											$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.trim($as->STORE_CODE).'/'.$slp->SALES_DATE);

											$total_am += $kurset1+$kurset2+$kurset3;
											$total_am_s1 += $kurset1;
											$total_am_s2 +=  $kurset2;
											$total_am_s3 +=  $kurset3;
											$total_am_kurset += $kurset_fin;
											$total_am_kurvir += $kurset_vir;

											$total_as += $kurset1+$kurset2+$kurset3;
											$total_as_s1 += $kurset1;
											$total_as_s2 += $kurset2;
											$total_as_s3 += $kurset3;
											$total_as_kurset += $kurset_fin;
											$total_as_kurvir += $kurset_vir;

											$pdf->MultiCell(25, 0,$rec_date, 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset1+$kurset2+$kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '-', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($kurset_fin, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($kurset_vir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES'."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(1, 1, 1, 1);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}
										}//end loop slp
									}//end if data slp
								}//end loop toko as
											if($asn == 2){
											$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(58, 0, 'TOTAL PER AS', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, number_format($total_as_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($total_as_kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->MultiCell(25, 0, number_format($total_as_kurvir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
											$pdf->Ln();
											$page++;
											if($page == 18){
												$pdf->AddPage('L','A4');
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helvetica', '', 8, '', true);
												$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
												$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
												$pdf->Ln(10);
												$pdf->SetFont('helveticaB', '', 15, '', true);
												$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
												$pdf->Ln(4);
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->SetFont('helvetica', '', 11, '', true);
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
												$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
												$pdf->Ln(7);
												$pdf->setCellPaddings(0, 0, 0, 0);
												$pdf->SetFont('helveticaB', '', 7, '', true);
												$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(5);
												$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln();
												$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
												$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
												$pdf->Ln(11.2);
												$page = 1;
											}//end ganti page
										}//end if asn 2
							}//end if toko as
						}//end loop as data
							if($amn == 2){
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(58, 0, 'TOTAL PER AM', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, number_format($total_am_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(20, 0, '', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($total_am_kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($total_am_kurvir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$page++;
												if($page == 18){
													$pdf->AddPage('L','A4');
													$pdf->setCellPaddings(0, 0, 0, 0);
													$pdf->SetFont('helvetica', '', 8, '', true);
													$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
													$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
													$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
													$pdf->Ln(10);
													$pdf->SetFont('helveticaB', '', 15, '', true);
													$pdf->Cell(0, 0, 'RINCIAN MONITORING KURANG SETOR SALES '."\n".'PER TOKO IDM', 0, 1, 'C', 0, '', 0);
													$pdf->Ln(4);
													$pdf->SetFont('helvetica', '', 11, '', true);
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Cabang', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->SetFont('helvetica', '', 11, '', true);
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Nama AM', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(80, 0, $str_am , 0, 'L', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(50, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
													$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
													$pdf->Ln(7);
													$pdf->setCellPaddings(0, 0, 0, 0);
													$pdf->SetFont('helveticaB', '', 7, '', true);
													$pdf->MultiCell(10, 10, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(8, 21.2, 'No', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Tgl. Terima Oleh Fin. di DC ', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(140, 5, 'Kurang Setor', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(25, 21.2, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln(5);
													$pdf->MultiCell(68, 5, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 16.2, 'Nilai Total', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 1', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 2', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(40, 5, 'Shift 3', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln();
													$pdf->MultiCell(88, 10, '', 0, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 11.2, 'Nilai'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
													$pdf->MultiCell(20, 7, 'Nama Pemegang'."\n".'Shift', 1, 'C', 0, 0, '', '', true);
													$pdf->Ln(11.2);
													$page = 1;
										}//end ganti page
								}//end if amn 2
					}//end if as data
				}//end loop am data
			}//end if am data
		}//end else if am != 0
		ob_end_clean();
		$pdf->Output('kurset_per_toko'.date('YmdHi').'.pdf', 'I');
	}

	public function print_mtr_dana_sales($branch_id, $store_id, $start, $end)
	{
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branch = $this->Mod_report->get_cabang_session($branch_id);
		$store = $this->Mod_report->get_store_by_id($store_id);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Monitoring Setoran Dana Sales');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$start_date = date_create($start);
		$end_date = date_create($end);

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');
		$ln = 1;
		$mod = 17;

		$total_a = 0;
		$total_b1 = 0;
		$total_b2 = 0;
		$total_c1 = 0;
		$total_c2 = 0;
		$total_fin = 0;
		$total_vir = 0;

		$str_store = ($store) ? trim($store->STORE_CODE).' - '.trim($store->STORE_NAME) : 'ALL - ALL';

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
		$pdf->Ln(10);

		$pdf->SetFont('helveticaB', '', 15, '', true);
		$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
		$pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 11, '', true);
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(80, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(7);
		$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 5, '', true);
		$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln(9);
		$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln(5.5);
		$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(258, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
		$pdf->Ln();

		if ($store_id == 0) {
			$store_data = $this->Mod_report->get_store_by_branch($branch_id);
			if ($store_data) {
				$total_a_b = 0;
				$total_b1_b = 0;
				$total_b2_b = 0;
				$total_c1_b = 0;
				$total_c2_b = 0;
				$total_fin_b = 0;
				$total_vir_b = 0;

				foreach ($store_data as $sd) {
					$total_a_t = 0;
					$total_b1_t = 0;
					$total_b2_t = 0;
					$total_c1_t = 0;
					$total_c2_t = 0;
					$total_fin_t = 0;
					$total_vir_t = 0;

					$data_slp_2 = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $start, $end);
					$data_slp = $data_slp_2;
					/*if ($data_slp_2) {
						$data_slp = $data_slp_2;
					} else {
						$data_slp = $this->Mod_report->get_slp_mtr_dana(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $start, $end);
					}*/
					$pdf->SetFont('helvetica', '', 8, '', true);
					if ($data_slp) {
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(258, 0, trim($sd->STORE_CODE).' - '.trim($sd->STORE_NAME), 1, 'L', 0, 0, '', '', true);
						if (($ln++ % $mod) == 0) {
							if ($ln == 18) {
								$mod = 26;
								$ln = 1;
							}
							$pdf->AddPage('L','A4');
							$pdf->SetFont('helveticaB', '', 8, '', true);
							$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln(9);
							$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln(5.5);
							$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
						} else {
							$pdf->Ln();
						}
						$no = 1;
						foreach ($data_slp as $slp) {
							$slp_date = date_create($slp->SALES_DATE);
							$receipt = $this->Mod_report->get_receipt_by_slp($sd->STORE_CODE, $slp->SALES_DATE);
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, number_format($slp->SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$total_a_t += $slp->SALES_AMOUNT;

							if ($receipt) {
								$rec_date = date_format(date_create($receipt->CREATION_DATE),"d-M-Y");
								$selisih_sales = $slp->SALES_AMOUNT - $receipt->ACTUAL_SALES_AMOUNT;
								$kurset = '-';
								$lebset = '-';
								if ($selisih_sales > 0) {
									$total_c1_t += $selisih_sales;
									$total_c2_t += 0;
									$kurset = number_format($selisih_sales, 0, '.', ',');
								} elseif ($selisih_sales < 0) {
									$total_c1_t += 0;
									$total_c2_t += abs($selisih_sales);
									$lebset = number_format(abs($selisih_sales), 0, '.', ',');
								}
								if ($receipt->STN_FLAG == 'N') {
									$pdf->MultiCell(25, 0, $rec_date, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, number_format($receipt->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
									$total_b1_t += $receipt->ACTUAL_SALES_AMOUNT;
									$total_b2_t += 0;
								} else {
									$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, $rec_date, 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 0, number_format($receipt->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
									$total_b1_t += 0;
									$total_b2_t += $receipt->ACTUAL_SALES_AMOUNT;
								}
								//$kurset_fin = $this->Mod_report->get_kurset_fin_mtr($receipt->CDC_REC_ID);
								//$kurset_vir = $this->Mod_report->get_kurset_vir_mtr($receipt->CDC_REC_ID);

								$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.$sd->STORE_CODE.'/'.$slp->SALES_DATE);
								$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.$sd->STORE_CODE.'/'.$slp->SALES_DATE);

								$total_fin_t += $kurset_fin;
								$total_vir_t += $kurset_vir;

								$str_kurset_fin = $kurset_fin != 0 ? number_format($kurset_fin, 0, '.', ',') : '-';
								$str_kurset_vir = $kurset_vir != 0 ? number_format($kurset_vir, 0, '.', ',') : '-';

								$pdf->MultiCell(25, 0, $kurset, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, $str_kurset_fin, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, $str_kurset_vir, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, $lebset, 1, 'C', 0, 0, '', '', true);
							} else {
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);

								if (($ln++ % $mod) == 0) {
									if ($ln == 18) {
										$mod = 26;
										$ln = 1;
									}
									$pdf->AddPage('L','A4');
									$pdf->SetFont('helveticaB', '', 8, '', true);
									$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln(9);
									$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln(5.5);
									$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
								} else {
									$pdf->Ln();
								}
							}

							if (($ln++ % $mod) == 0) {
								if ($ln == 18) {
									$mod = 26;
									$ln = 1;
								}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(5.5);
								$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
							} else {
								$pdf->Ln();
							}
						}
					}

					if ($data_slp) {
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(33, 0, 'Sub Total per Toko', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_a_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_b1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_b2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($total_c2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->Ln();
					}

					if (($ln++ % $mod) == 0) {
						if ($ln == 18) {
							$mod = 26;
							$ln = 1;
						}
						$pdf->AddPage('L','A4');
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln(9);
						$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln(5.5);
						$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln();
					}

					$total_a_b += $total_a_t;
					$total_b1_b += $total_b1_t;
					$total_b2_b += $total_b2_t;
					$total_c1_b += $total_c1_t;
					$total_c2_b += $total_c2_t;
					$total_fin_b += $total_fin_t;
					$total_vir_b += $total_vir_t;
				}

				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(33, 0, 'Sub Total per Cabang', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_a_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_b1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_b2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_c2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->Ln();

				if (($ln++ % $mod) == 0) {
					if ($ln == 18) {
						$mod = 26;
						$ln = 1;
					}
					$pdf->AddPage('L','A4');
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(9);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(5.5);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}

				$total_a += $total_a_b;
				$total_b1 += $total_b1_b;
				$total_b2 += $total_b2_b;
				$total_c1 += $total_c1_b;
				$total_c2 += $total_c2_b;
				$total_fin += $total_fin_b;
				$total_vir += $total_vir_b;
			}
		} else {
			if ($store) {

				$total_a_b = 0;
				$total_b1_b = 0;
				$total_b2_b = 0;
				$total_c1_b = 0;
				$total_c2_b = 0;
				$total_fin_b = 0;
				$total_vir_b = 0;

				$total_a_t = 0;
				$total_b1_t = 0;
				$total_b2_t = 0;
				$total_c1_t = 0;
				$total_c2_t = 0;
				$total_fin_t = 0;
				$total_vir_t = 0;

				$data_slp_2 = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($store->STORE_CODE), $start, $end);
				if ($data_slp_2) {
					$data_slp = $data_slp_2;
				} else {
					$data_slp = $this->Mod_report->get_slp_mtr_dana(trim($branch[0]->BRANCH_CODE), trim($store->STORE_CODE), $start, $end);
				}
				$pdf->SetFont('helvetica', '', 8, '', true);
				if ($data_slp) {
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(258, 0, trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);
					if (($ln++ % $mod) == 0) {
						if ($ln == 18) {
							$mod = 26;
							$ln = 1;
						}
						$pdf->AddPage('L','A4');
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln(9);
						$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln(5.5);
						$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln();
					} else {
						$pdf->Ln();
					}
					$no = 1;
					foreach ($data_slp as $slp) {
						$slp_date = date_create($slp->SALES_DATE);
						$receipt = $this->Mod_report->get_receipt_by_slp($store->STORE_CODE, $slp->SALES_DATE);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, date_format($slp_date,"d-M-Y"), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 0, number_format($slp->SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$total_a_t += $slp->SALES_AMOUNT;

						if ($receipt) {
							$rec_date = date_format(date_create($receipt->CREATION_DATE),"d-M-Y");
							$selisih_sales = $slp->SALES_AMOUNT - $receipt->ACTUAL_SALES_AMOUNT;
							$kurset = '-';
							$lebset = '-';
							if ($selisih_sales > 0) {
								$total_c1_t += $selisih_sales;
								$total_c2_t += 0;
								$kurset = number_format($selisih_sales, 0, '.', ',');
							} elseif ($selisih_sales < 0) {
								$total_c1_t += 0;
								$total_c2_t += abs($selisih_sales);
								$lebset = number_format(abs($selisih_sales), 0, '.', ',');
							}
							if ($receipt->STN_FLAG == 'N') {
								$pdf->MultiCell(25, 0, $rec_date, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($receipt->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $receipt->ACTUAL_SALES_AMOUNT;
								$total_b2_t += 0;
							} else {
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, $rec_date, 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 0, number_format($receipt->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += 0;
								$total_b2_t += $receipt->ACTUAL_SALES_AMOUNT;
							}
							$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.$store->STORE_CODE.'/'.$slp->SALES_DATE);
							$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.$store->STORE_CODE.'/'.$slp->SALES_DATE);

							$total_fin_t += $kurset_fin;
							$total_vir_t += $kurset_vir;

							$str_kurset_fin = $kurset_fin != 0 ? number_format($kurset_fin, 0, '.', ',') : '-';
							$str_kurset_vir = $kurset_vir != 0 ? number_format($kurset_vir, 0, '.', ',') : '-';

							$pdf->MultiCell(25, 0, $kurset, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, $str_kurset_fin, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, $str_kurset_vir, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, $lebset, 1, 'C', 0, 0, '', '', true);
						} else {
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 0, '-', 1, 'C', 0, 0, '', '', true);

							if (($ln++ % $mod) == 0) {
								if ($ln == 18) {
									$mod = 26;
									$ln = 1;
								}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helveticaB', '', 8, '', true);
								$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(5.5);
								$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
							} else {
								$pdf->Ln();
							}
						}

						if (($ln++ % $mod) == 0) {
							if ($ln == 18) {
								$mod = 26;
								$ln = 1;
							}
							$pdf->AddPage('L','A4');
							$pdf->SetFont('helveticaB', '', 8, '', true);
							$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln(9);
							$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln(5.5);
							$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
							$pdf->Ln();
						} else {
							$pdf->Ln();
						}
					}
				}

				if ($data_slp) {
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(33, 0, 'Sub Total per Toko', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_a_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_b1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_b2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 0, number_format($total_c2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}

				if (($ln++ % $mod) == 0) {
					if ($ln == 18) {
						$mod = 26;
						$ln = 1;
					}
					$pdf->AddPage('L','A4');
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(9);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(5.5);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}

				$total_a_b += $total_a_t;
				$total_b1_b += $total_b1_t;
				$total_b2_b += $total_b2_t;
				$total_c1_b += $total_c1_t;
				$total_c2_b += $total_c2_t;
				$total_fin_b += $total_fin_t;
				$total_vir_b += $total_vir_t;

				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(33, 0, 'Sub Total per Cabang', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_a_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_b1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_b2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(25, 0, number_format($total_c2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->Ln();

				if (($ln++ % $mod) == 0) {
					if ($ln == 18) {
						$mod = 26;
						$ln = 1;
					}
					$pdf->AddPage('L','A4');
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 20, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(8, 20, 'No', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(100, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'KurSet Finance & KurSet RRAK (111802 & 111807)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Kurang Setor Virtual (111803)'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 20, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(9);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(50, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(5.5);
					$pdf->MultiCell(68, 10, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Tanggal', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}

				$total_a += $total_a_b;
				$total_b1 += $total_b1_b;
				$total_b2 += $total_b2_b;
				$total_c1 += $total_c1_b;
				$total_c2 += $total_c2_b;
				$total_fin += $total_fin_b;
				$total_vir += $total_vir_b;
			}
		}

		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(33, 0, 'Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_a, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_b1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, '', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_b2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_c1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_fin, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_vir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(25, 0, number_format($total_c2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);

		ob_end_clean();
		$pdf->Output('monitoring_setoran_dana_sales'.date('YmdHi').'.pdf', 'I');
	}

	public function print_mtr_dana_sales_shift($branch_id, $store_id, $start, $end)
	{
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branch = $this->Mod_report->get_cabang_session($branch_id);
		$store = $this->Mod_report->get_store_by_id($store_id);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Monitoring Setoran Dana Sales');
		$pdf->SetSubject('');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(-5, 18, 0);
		$pdf->SetCellPadding(0,0,0,0);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$start_date = date_create($start);
		$end_date = date_create($end);

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('L','A4');
		$page = 1;
		$ln = 1;
		$mod = 20;

		$total_a = 0;
		$total_b1 = 0;
		$total_b2 = 0;
		$total_c1 = 0;
		$total_c2 = 0;
		$total_fin = 0;
		$total_vir = 0;

		$total_a_s1 = 0;
		$total_a_s2 = 0;
		$total_a_s3 = 0;
		$total_b1_s1 = 0;
		$total_b1_s2 = 0;
		$total_b1_s3 = 0;
		$total_b2_s1 = 0;
		$total_b2_s2 = 0;
		$total_b2_s3 = 0;
		$total_c1_h = 0;
		$total_c1_s1 = 0;
		$total_c1_s2 = 0;
		$total_c1_s3 = 0;
		$total_c2_s1 = 0;
		$total_c2_s2 = 0;
		$total_c2_s3 = 0;

		$str_store = ($store) ? trim($store->STORE_CODE).' - '.trim($store->STORE_NAME) : 'ALL - ALL';

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
		$pdf->Ln(10);

		$pdf->SetFont('helveticaB', '', 13, '', true);
		$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
		$pdf->Ln(4);
		$pdf->SetFont('helvetica', '', 9, '', true);
		$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln(7);
		$pdf->setCellPaddings(1, 1, 1, 1);
		$pdf->SetFont('helveticaB', '', 5, '', true);
		$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
		//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
		//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln(9);
		$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
		/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
		$pdf->Ln(5);
		$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
		//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
		//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		if ($store_id == 0) {
			$store_data = $this->Mod_report->get_store_by_branch($branch_id);
			if ($store_data) {
				$total_a_b = 0;
				$total_b1_b = 0;
				$total_b2_b = 0;
				$total_c1_b = 0;
				$total_c2_b = 0;
				$total_fin_b = 0;
				$total_vir_b = 0;

				$total_a_b_s1 = 0;
				$total_a_b_s2 = 0;
				$total_a_b_s3 = 0;
				$total_b1_b_s1 = 0;
				$total_b1_b_s2 = 0;
				$total_b1_b_s3 = 0;
				$total_b2_b_s1 = 0;
				$total_b2_b_s2 = 0;
				$total_b2_b_s3 = 0;
				$total_c1_b_h = 0;
				$total_c1_b_s1 = 0;
				$total_c1_b_s2 = 0;
				$total_c1_b_s3 = 0;
				$total_c2_b_s1 = 0;
				$total_c2_b_s2 = 0;
				$total_c2_b_s3 = 0;

				foreach ($store_data as $sd) {
					$total_a_t = 0;
					$total_b1_t = 0;
					$total_b2_t = 0;
					$total_c1_t = 0;
					$total_c2_t = 0;
					$total_fin_t = 0;
					$total_vir_t = 0;

					$total_a_t_s1 = 0;
					$total_a_t_s2 = 0;
					$total_a_t_s3 = 0;
					$total_b1_t_s1 = 0;
					$total_b1_t_s2 = 0;
					$total_b1_t_s3 = 0;
					$total_b2_t_s1 = 0;
					$total_b2_t_s2 = 0;
					$total_b2_t_s3 = 0;
					$total_c1_t_h = 0;
					$total_c1_t_s1 = 0;
					$total_c1_t_s2 = 0;
					$total_c1_t_s3 = 0;
					$total_c2_t_s1 = 0;
					$total_c2_t_s2 = 0;
					$total_c2_t_s3 = 0;

					$data_slp_2 = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $start, $end);
					$data_slp = $data_slp_2;
					/*if ($data_slp_2) {
						$data_slp = $data_slp_2;
					} else {
						$data_slp = $this->Mod_report->get_slp_mtr_dana(trim($branch[0]->BRANCH_CODE), trim($sd->STORE_CODE), $start, $end);
					}*/
					$pdf->SetFont('helvetica', '', 4, '', true);
					if ($data_slp) {
						$pdf->SetFont('helveticaB', '', 7, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(281, 0, trim($sd->STORE_CODE).' - '.trim($sd->STORE_NAME), 1, 'L', 0, 0, '', '', true);
						$pdf->Ln();
						if (($ln++ % $mod) == 0) {
							if ($ln == 20) {
								$mod = 20;
								$ln = 1;
							}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
								$pdf->Ln(10);

								$pdf->SetFont('helveticaB', '', 13, '', true);
								$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 9, '', true);
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 5, '', true);
								$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
								$pdf->Ln(5);
								$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
							}
						$no = 1;
						foreach ($data_slp as $slp) {
							$slp_shift1 = 0;
							$slp_shift2 = 0;
							$slp_shift3 = 0;

							$slp_date = date_create($slp->SALES_DATE);
							$receipt = $this->Mod_report->get_receipt_by_slp_shift($sd->STORE_CODE, $slp->SALES_DATE);

							$pdf->SetFont('helvetica', '', 5, '', true);
							$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, date_format($slp_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);
							
							/*$pdf->MultiCell(13, 0, number_format('0', 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format('0', 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format('0', 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/
							
							$pdf->MultiCell(13, 0, number_format($slp->SHIFT1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($slp->SHIFT2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($slp->SHIFT3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($slp->SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							//$pdf->MultiCell(45, 0, number_format($slp->SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$total_a_t += $slp->SALES_AMOUNT;
							/*$total_a_t_s1 += $slp->SHIFT1;
							$total_a_t_s2 += $slp->SHIFT2;
							$total_a_t_s3 += $slp->SHIFT3;*/

							$total_a_t_s1 += 0;
							$total_a_t_s2 += 0;
							$total_a_t_s3 += 0;

							$sales_shift1 = 0;
							$sales_shift2 = 0;
							$sales_shift3 = 0;
							$sales_harian = 0;
							$sales_harian2 = 0;
							$stn_f = '';
							$shift_flag = '';
							$rec_date = '';
							$selisih_sales = 0;
							$selisih_sales_h2 = 0;
							$selisih_sales1 = 0;
							$selisih_sales2 = 0;
							$selisih_sales3 = 0;
							$kurset = 0;
							$kurset_h2 = 0;
							$kurset1 = 0;
							$kurset2 = 0;
							$kurset3 = 0;
							$lebset1 = 0;
							$lebset2 = 0;
							$lebset3 = 0;


							if ($receipt) {//start if receipt
							$a = 1;
							foreach ($receipt as $data_rec) {//start loop receipt sales
								if($data_rec->NO_SHIFT == '1'){
									$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
									if($selisih_sales1 > 0){
										$kurset1 = $selisih_sales1;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset1 = abs($selisih_sales1);
									}
								}
								else if($data_rec->NO_SHIFT == '2'){
									$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
									if($selisih_sales2 > 0){
										$kurset2 = $selisih_sales2;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset2 = abs($selisih_sales2);
									}
								}
								else if($data_rec->NO_SHIFT == '3'){
									$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
									if($selisih_sales3 > 0){
										$kurset3 =$selisih_sales3;
									}
									elseif ($selisih_sales3 < 0) {
										$lebset3 = abs($selisih_sales3);
									}
								}
								else{
									$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales = $slp->SALES_AMOUNT - $data_rec->ACTUAL_SALES_AMOUNT;
									if($selisih_sales > 0){
										$kurset = abs($selisih_sales);
									}
									
								}

								if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
									$sales_harian2 += $data_rec->ACTUAL_SALES_AMOUNT;
								}


								/*if($data_rec->NO_SHIFT == '1' && $slp->SHIFT != 0){
									$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
									if($selisih_sales1 > 0){
										$kurset1 = $selisih_sales1;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset1 = abs($selisih_sales1);
									}
								}
								else if($data_rec->NO_SHIFT == '2' && $slp->SHIFT != 0){
									$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
									if($selisih_sales2 > 0){
										$kurset2 = $selisih_sales2;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset2 = abs($selisih_sales2);
									}
								}
								else if($data_rec->NO_SHIFT == '3' && $slp->SHIFT != 0){
									$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
									if($selisih_sales3 > 0){
										$kurset3 =$selisih_sales3;
									}
									elseif ($selisih_sales3 < 0) {
										$lebset3 = abs($selisih_sales3);
									}
								}
								else{
									$sales_harian += $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;
									if($selisih_sales > 0){
										$kurset = abs($selisih_sales);
									}
									 
								}*/

								$stn_f = $data_rec->STN_FLAG;
								$shift_flag = $data_rec->SHIFT_FLAG;
								$rec_date = date_create($data_rec->CREATION_DATE);
							}//end loop receipt sales
							if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
								$selisih_sales_h2 = $slp->SALES_AMOUNT - $sales_harian2;
									if($selisih_sales_h2 > 0){
										$kurset_h2 = abs($selisih_sales_h2);
									}
							}
						}//end if receipt


							//$kurset = '-';
							//$lebset = '-';
						$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.trim($sd->STORE_CODE).'/'.$slp->SALES_DATE);
						$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.trim($sd->STORE_CODE).'/'.$slp->SALES_DATE);

						$total_fin_t += $kurset_fin;
						$total_vir_t += $kurset_vir;
						$total_c1_t += $kurset1 + $kurset2 + $kurset3 + $kurset;
						$total_c1_t_s1 += $kurset1;
						$total_c1_t_s2 += $kurset2;
						$total_c1_t_s3 += $kurset3;
						/*if ($selisih_sales > 0) {
							$total_c1_t += $selisih_sales;
							$total_c2_t += 0;
							$kurset = number_format($selisih_sales, 0, '.', ',');
						} elseif ($selisih_sales < 0) {
							$total_c1_t += 0;
							$total_c2_t += abs($selisih_sales);
							$lebset = number_format(abs($selisih_sales), 0, '.', ',');
						}*/
						//echo $stn_f;
						if ($stn_f == 'N') {//start if stn
							$pdf->MultiCell(12, 0,date_format($rec_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							if($shift_flag == 'Y' && $slp->SHIFT != 0){
								$total_sales_shift = $sales_shift1+$sales_shift2+$sales_shift3;
								$pdf->MultiCell(13, 0, number_format($total_sales_shift, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $total_sales_shift;
								$total_b1_t_s1 += $sales_shift1;
								$total_b1_t_s2 += $sales_shift2;
								$total_b1_t_s3 += $sales_shift3;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;

							}
							else if($shift_flag == 'Y' && $slp->SHIFT != 1){
								$pdf->MultiCell(13, 0, number_format($sales_harian2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $sales_harian2;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}
							else{
								$pdf->MultiCell(13, 0, number_format($sales_harian, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $sales_harian;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}
	
							$pdf->MultiCell(12, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1+$kurset2+$kurset3+$kurset+$kurset_h2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_fin, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_vir, 1, 'C', 0, 0, '', '', true);
						}//end if stn
						else {//start else stn
							$pdf->MultiCell(12, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							if($rec_date != ''){
								$pdf->MultiCell(12, 0,date_format($rec_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);
							}else{
								$pdf->MultiCell(12, 0,'-', 1, 'C', 0, 0, '', '', true);
							}
							$pdf->MultiCell(13, 0, number_format($sales_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							if($shift_flag == 'Y' && $slp->SHIFT != 0){
								$total_sales_shift = $sales_shift1+$sales_shift2+$sales_shift3;
								$pdf->MultiCell(13, 0, number_format($total_sales_shift, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += 0;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += $total_sales_shift;
								$total_b2_t_s1 += $sales_shift1;
								$total_b2_t_s2 += $sales_shift2;
								$total_b2_t_s3 += $sales_shift3;
							}
							else{
								$pdf->MultiCell(13, 0, number_format($sales_harian, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += 0;
								$total_b2_t += $sales_harian;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}

							$pdf->MultiCell(13, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1+$kurset2+$kurset3+$kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_fin, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_vir, 1, 'C', 0, 0, '', '', true);
						}//end else stn
						//$pdf->Ln();

						if (($ln++ % $mod) == 0) {//start add page 2
							if ($ln == 20) {
								$mod = 20;
								$ln = 1;
							}
									$pdf->AddPage('L','A4');
									$pdf->SetFont('helvetica', '', 8, '', true);
									$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
									$pdf->Ln(10);

									$pdf->SetFont('helveticaB', '', 13, '', true);
									$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
									$pdf->Ln(4);
									$pdf->SetFont('helvetica', '', 9, '', true);
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln(7);
									$pdf->setCellPaddings(1, 1, 1, 1);
									$pdf->SetFont('helveticaB', '', 5, '', true);
									$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln(9);
									$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
									$pdf->Ln(5);
									$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
									$pdf->Ln();
							//$pdf->Ln();
						} else {
							$pdf->Ln();
						}//end add page 2
					}//end loop data slp
				}//end if data slp

					if ($data_slp) {
						$pdf->SetFont('helveticaB', '', 5, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(21, 0, 'Sub Total per Toko', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_a_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_a_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_a_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_a_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b2_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b2_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b2_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_b2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_c1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_c1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_c1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(14, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(14, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						//$pdf->MultiCell(10, 0, '', 1, 'C', 0, 0, '', '', true);
						/*$pdf->MultiCell(11, 0, number_format($total_c1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(11, 0, number_format($total_c1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(11, 0, number_format($total_c1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(11, 0, number_format($total_c2_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(11, 0, number_format($total_c2_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(11, 0, number_format($total_c2_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(12, 0, number_format($total_c2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/
						$pdf->Ln();
					}
				if (($ln++ % $mod) == 0) {
					if ($ln == 20) {
						$mod = 20;
						$ln = 1;
					}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
								$pdf->Ln(10);

								$pdf->SetFont('helveticaB', '', 13, '', true);
								$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 9, '', true);
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 5, '', true);
								$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
								$pdf->Ln(5);
								$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
				}

					$total_a_b += $total_a_t;
					$total_b1_b += $total_b1_t;
					$total_b2_b += $total_b2_t;
					$total_c1_b += $total_c1_t;
					$total_c2_b += $total_c2_t;
					$total_fin_b += $total_fin_t;
					$total_vir_b += $total_vir_t;

					$total_a_b_s1 += $total_a_t_s1;
					$total_a_b_s2 += $total_a_t_s2;
					$total_a_b_s3 += $total_a_t_s3;
					$total_b1_b_s1 += $total_b1_t_s1;
					$total_b1_b_s2 += $total_b1_t_s2;
					$total_b1_b_s3 += $total_b1_t_s3;
					$total_b2_b_s1 += $total_b2_t_s1;
					$total_b2_b_s2 += $total_b2_t_s2;
					$total_b2_b_s3 += $total_b2_t_s3;
					$total_c1_b_s1 += $total_c1_t_s1;
					$total_c1_b_s2 += $total_c1_t_s2;
					$total_c1_b_s3 += $total_c1_t_s3;
					$total_c2_b_s1 += $total_c2_t_s1;
					$total_c2_b_s2 += $total_c2_t_s2;
					$total_c2_b_s3 += $total_c2_t_s3;
				}

				$pdf->SetFont('helveticaB', '', 5, '', true);
				$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(21, 0, 'Sub Total per Cbg', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(14, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(14, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				/*$pdf->MultiCell(11, 0, number_format($total_c1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_c2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/
				$pdf->Ln();

				if (($ln++ % $mod) == 0) {
					if ($ln == 20) {
						$mod = 20;
						$ln = 1;
					}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
								$pdf->Ln(10);

								$pdf->SetFont('helveticaB', '', 13, '', true);
								$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 9, '', true);
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 5, '', true);
								$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
								$pdf->Ln(5);
								$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
						}
				$total_a += $total_a_b;
				$total_b1 += $total_b1_b;
				$total_b2 += $total_b2_b;
				$total_c1 += $total_c1_b;
				$total_c2 += $total_c2_b;
				$total_fin += $total_fin_b;
				$total_vir += $total_vir_b;

				$total_a_s1 += $total_a_b_s1;
				$total_a_s2 += $total_a_b_s2;
				$total_a_s3 += $total_a_b_s3;
				$total_b1_s1 += $total_b1_b_s1;
				$total_b1_s2 += $total_b1_b_s2;
				$total_b1_s3 += $total_b1_b_s3;
				$total_b2_s1 += $total_b2_b_s1;
				$total_b2_s2 += $total_b2_b_s2;
				$total_b2_s3 += $total_b2_b_s3;
				$total_c1_s1 += $total_c1_b_s1;
				$total_c1_s2 += $total_c1_b_s2;
				$total_c1_s3 += $total_c1_b_s3;
				$total_c2_s1 += $total_c2_b_s1;
				$total_c2_s2 += $total_c2_b_s2;
				$total_c2_s3 += $total_c2_b_s3;
			}
		} else {
			if ($store) {

				$total_a_b = 0;
				$total_b1_b = 0;
				$total_b2_b = 0;
				$total_c1_b = 0;
				$total_c2_b = 0;
				$total_fin_b = 0;
				$total_vir_b = 0;

				$total_a_t = 0;
				$total_b1_t = 0;
				$total_b2_t = 0;
				$total_c1_t = 0;
				$total_c2_t = 0;
				$total_fin_t = 0;
				$total_vir_t = 0;

				$total_a_t_s1 = 0;
				$total_a_t_s2 = 0;
				$total_a_t_s3 = 0;
				$total_b1_t_s1 = 0;
				$total_b1_t_s2 = 0;
				$total_b1_t_s3 = 0;
				$total_b2_t_s1 = 0;
				$total_b2_t_s2 = 0;
				$total_b2_t_s3 = 0;
				$total_c1_t_s1 = 0;
				$total_c1_t_s2 = 0;
				$total_c1_t_s3 = 0;
				$total_c2_t_s1 = 0;
				$total_c2_t_s2 = 0;
				$total_c2_t_s3 = 0;


				$total_a_b_s1 = 0;
				$total_a_b_s2 = 0;
				$total_a_b_s3 = 0;
				$total_b1_b_s1 = 0;
				$total_b1_b_s2 = 0;
				$total_b1_b_s3 = 0;
				$total_b2_b_s1 = 0;
				$total_b2_b_s2 = 0;
				$total_b2_b_s3 = 0;
				$total_c1_b_s1 = 0;
				$total_c1_b_s2 = 0;
				$total_c1_b_s3 = 0;
				$total_c2_b_s1 = 0;
				$total_c2_b_s2 = 0;
				$total_c2_b_s3 = 0;

				$data_slp_2 = $this->Mod_report->get_slp_mtr_dana_2(trim($branch[0]->BRANCH_CODE), trim($store->STORE_CODE), $start, $end);
				$data_slp = $data_slp_2;
				$pdf->SetFont('helvetica', '', 5, '', true);
				if ($data_slp) { // start if data slp
					$pdf->SetFont('helveticaB', '', 5, '', true);
					$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(281, 0, trim($store->STORE_CODE).' - '.trim($store->STORE_NAME), 1, 'L', 0, 0, '', '', true);

					if (($ln++ % $mod) == 0) {//start add page 1
						if ($ln == 20) {
							$mod = 20;
							$ln = 1;
						}
									$pdf->AddPage('L','A4');
									$pdf->SetFont('helvetica', '', 8, '', true);
									$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
									$pdf->Ln(10);

									$pdf->SetFont('helveticaB', '', 13, '', true);
									$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
									$pdf->Ln(4);
									$pdf->SetFont('helvetica', '', 9, '', true);
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln(7);
									$pdf->setCellPaddings(1, 1, 1, 1);
									$pdf->SetFont('helveticaB', '', 5, '', true);
									$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln(9);
									$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
									$pdf->Ln(5);
									$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
									$pdf->Ln();
					} else {
						$pdf->Ln();
					}//end add page 1

					$no = 1;
					foreach ($data_slp as $slp) { //start loop data slp
						$slp_date = date_create($slp->SALES_DATE);
						$receipt = $this->Mod_report->get_receipt_by_slp_shift($store->STORE_CODE, $slp->SALES_DATE);

						//echo $no++;
						//data sales toko
						$pdf->SetFont('helvetica', '', 5, '', true);
						$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(8, 0, $no++, 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, date_format($slp_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);

						$pdf->MultiCell(13, 0, number_format($slp->SHIFT1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($slp->SHIFT2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(13, 0, number_format($slp->SHIFT3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						
						$pdf->MultiCell(13, 0, number_format($slp->SALES_AMOUNT, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
						//end data sales toko

						$total_a_t += $slp->SALES_AMOUNT;
						$total_a_t_s1 += $slp->SHIFT1;
						$total_a_t_s2 += $slp->SHIFT2;
						$total_a_t_s3 += $slp->SHIFT3;
						$sales_shift1 = 0;
						$sales_shift2 = 0;
						$sales_shift3 = 0;
						$sales_harian = 0;
						$sales_harian2 = 0;
						$stn_f = '';
						$shift_flag = '';
						$rec_date = '';
						$selisih_sales = 0;
						$selisih_sales_h2 = 0;
						$selisih_sales1 = 0;
						$selisih_sales2 = 0;
						$selisih_sales3 = 0;
						$kurset = 0;
						$kurset_h2 = 0;
						$kurset1 = 0;
						$kurset2 = 0;
						$kurset3 = 0;
						$lebset1 = 0;
						$lebset2 = 0;
						$lebset3 = 0;

						if ($receipt) {//start if receipt
							$a = 1;
							foreach ($receipt as $data_rec) {//start loop receipt sales
								if($data_rec->NO_SHIFT == '1'){
									$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
									if($selisih_sales1 > 0){
										$kurset1 = $selisih_sales1;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset1 = abs($selisih_sales1);
									}
								}
								else if($data_rec->NO_SHIFT == '2'){
									$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
									if($selisih_sales2 > 0){
										$kurset2 = $selisih_sales2;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset2 = abs($selisih_sales2);
									}
								}
								else if($data_rec->NO_SHIFT == '3'){
									$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
									if($selisih_sales3 > 0){
										$kurset3 =$selisih_sales3;
									}
									elseif ($selisih_sales3 < 0) {
										$lebset3 = abs($selisih_sales3);
									}
								}
								else{
									$sales_harian = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales = $slp->SALES_AMOUNT - $data_rec->ACTUAL_SALES_AMOUNT;
									if($selisih_sales > 0){
										$kurset = abs($selisih_sales);
									}
									
								}

								if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
									$sales_harian2 += $data_rec->ACTUAL_SALES_AMOUNT;
								}


								/*if($data_rec->NO_SHIFT == '1' && $slp->SHIFT != 0){
									$sales_shift1 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales1 = $slp->SHIFT1 - $sales_shift1;
									if($selisih_sales1 > 0){
										$kurset1 = $selisih_sales1;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset1 = abs($selisih_sales1);
									}
								}
								else if($data_rec->NO_SHIFT == '2' && $slp->SHIFT != 0){
									$sales_shift2 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales2 = $slp->SHIFT2 - $sales_shift2;
									if($selisih_sales2 > 0){
										$kurset2 = $selisih_sales2;
									}
									elseif ($selisih_sales2 < 0) {
										$lebset2 = abs($selisih_sales2);
									}
								}
								else if($data_rec->NO_SHIFT == '3' && $slp->SHIFT != 0){
									$sales_shift3 = $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales3 = $slp->SHIFT3 - $sales_shift3;
									if($selisih_sales3 > 0){
										$kurset3 =$selisih_sales3;
									}
									elseif ($selisih_sales3 < 0) {
										$lebset3 = abs($selisih_sales3);
									}
								}
								else{
									$sales_harian += $data_rec->ACTUAL_SALES_AMOUNT;
									$selisih_sales = $slp->SALES_AMOUNT - $sales_harian;
									if($selisih_sales > 0){
										$kurset = abs($selisih_sales);
									}
									
								}*/

								$stn_f = $data_rec->STN_FLAG;
								$shift_flag = $data_rec->SHIFT_FLAG;
								$rec_date = date_create($data_rec->CREATION_DATE);
							}//end loop receipt sales
							if($data_rec->NO_SHIFT != 'H' && $slp->SHIFT != 1){
								$selisih_sales_h2 = $slp->SALES_AMOUNT - $sales_harian2;
									if($selisih_sales_h2 > 0){
										$kurset_h2 = abs($selisih_sales_h2);
									}
							}
						}//end if receipt


							//$kurset = '-';
							//$lebset = '-';
						$kurset_fin = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_fin/'.trim($store->STORE_CODE).'/'.$slp->SALES_DATE);
						$kurset_vir = file_get_contents('http://fadfas.indomaret.lan/IDM_FAS/External/get_kurset_by_slp_vir/'.trim($store->STORE_CODE).'/'.$slp->SALES_DATE);

						$total_fin_t += $kurset_fin;
						$total_vir_t += $kurset_vir;
						$total_c1_t += $kurset1 + $kurset2 + $kurset3 + $kurset;
						$total_c1_t_s1 += $kurset1;
						$total_c1_t_s2 += $kurset2;
						$total_c1_t_s3 += $kurset3;
						/*if ($selisih_sales > 0) {
							$total_c1_t += $selisih_sales;
							$total_c2_t += 0;
							$kurset = number_format($selisih_sales, 0, '.', ',');
						} elseif ($selisih_sales < 0) {
							$total_c1_t += 0;
							$total_c2_t += abs($selisih_sales);
							$lebset = number_format(abs($selisih_sales), 0, '.', ',');
						}*/
						//echo $stn_f;
						if ($stn_f == 'N') {//start if stn
							$pdf->MultiCell(12, 0,date_format($rec_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							if($shift_flag == 'Y' && $slp->SHIFT != 0){
								$total_sales_shift = $sales_shift1+$sales_shift2+$sales_shift3;
								$pdf->MultiCell(13, 0, number_format($total_sales_shift, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $total_sales_shift;
								$total_b1_t_s1 += $sales_shift1;
								$total_b1_t_s2 += $sales_shift2;
								$total_b1_t_s3 += $sales_shift3;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;

							}
							else if($shift_flag == 'Y' && $slp->SHIFT != 1){
								$pdf->MultiCell(13, 0, number_format($sales_harian2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $sales_harian2;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}
							else{
								$pdf->MultiCell(13, 0, number_format($sales_harian, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += $sales_harian;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}
	
							$pdf->MultiCell(12, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1+$kurset2+$kurset3+$kurset+$kurset_h2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_fin, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_vir, 1, 'C', 0, 0, '', '', true);
						}//end if stn
						else {//start else stn
							$pdf->MultiCell(12, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0,0, 1, 'C', 0, 0, '', '', true);
							if($rec_date != ''){
								$pdf->MultiCell(12, 0,date_format($rec_date,"d/m/y"), 1, 'C', 0, 0, '', '', true);
							}else{
								$pdf->MultiCell(12, 0,'-', 1, 'C', 0, 0, '', '', true);
							}
							
							$pdf->MultiCell(13, 0, number_format($sales_shift1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($sales_shift3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							if($shift_flag == 'Y' && $slp->SHIFT != 0){
								$total_sales_shift = $sales_shift1+$sales_shift2+$sales_shift3;
								$pdf->MultiCell(13, 0, number_format($total_sales_shift, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += 0;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t += $total_sales_shift;
								$total_b2_t_s1 += $sales_shift1;
								$total_b2_t_s2 += $sales_shift2;
								$total_b2_t_s3 += $sales_shift3;
							}
							else{
								$pdf->MultiCell(13, 0, number_format($sales_harian, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
								$total_b1_t += 0;
								$total_b2_t += $sales_harian;
								$total_b1_t_s1 += 0;
								$total_b1_t_s2 += 0;
								$total_b1_t_s3 += 0;
								$total_b2_t_s1 += 0;
								$total_b2_t_s2 += 0;
								$total_b2_t_s3 += 0;
							}

							$pdf->MultiCell(13, 0, number_format($kurset1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(13, 0, number_format($kurset1+$kurset2+$kurset3+$kurset, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_fin, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(14, 0, $kurset_vir, 1, 'C', 0, 0, '', '', true);
						}//end else stn
						//$pdf->Ln();

						if (($ln++ % $mod) == 0) {//start add page 2
							if ($ln == 20) {
								$mod = 20;
								$ln = 1;
							}
									$pdf->AddPage('L','A4');
									$pdf->SetFont('helvetica', '', 8, '', true);
									$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
									$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
									$pdf->Ln(10);

									$pdf->SetFont('helveticaB', '', 13, '', true);
									$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
									$pdf->Ln(4);
									$pdf->SetFont('helvetica', '', 9, '', true);
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
									$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
									$pdf->Ln(7);
									$pdf->setCellPaddings(1, 1, 1, 1);
									$pdf->SetFont('helveticaB', '', 5, '', true);
									$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln(9);
									$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
									/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
									$pdf->Ln(5);
									$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
									//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
									$pdf->Ln();
									$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
									$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
									$pdf->Ln();
							//$pdf->Ln();
						} else {
							$pdf->Ln();
						}//end add page 2
					}//end loop data slp
				}//end if data slp

				if ($data_slp) {//start data slp 2
					$pdf->SetFont('helveticaB', '', 5, '', true);
					$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(21, 0, 'Sub Total per Toko', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_a_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_a_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_a_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_a_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b2_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b2_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b2_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_b2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_c1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_c1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_c1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(13, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(14, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(14, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					//$pdf->MultiCell(10, 0, '', 1, 'C', 0, 0, '', '', true);
					/*$pdf->MultiCell(11, 0, number_format($total_c1_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(11, 0, number_format($total_c1_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(11, 0, number_format($total_c1_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, number_format($total_c1_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, number_format($total_fin_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, number_format($total_vir_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(11, 0, number_format($total_c2_t_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(11, 0, number_format($total_c2_t_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(11, 0, number_format($total_c2_t_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(12, 0, number_format($total_c2_t, 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/
					$pdf->Ln();
				}// end if data slp 2

				if (($ln++ % $mod) == 0) {
					if ($ln == 20) {
						$mod = 20;
						$ln = 1;
					}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
								$pdf->Ln(10);

								$pdf->SetFont('helveticaB', '', 13, '', true);
								$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 9, '', true);
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 5, '', true);
								$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
								$pdf->Ln(5);
								$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
				}

				$total_a_b += $total_a_t;
				$total_b1_b += $total_b1_t;
				$total_b2_b += $total_b2_t;
				$total_c1_b += $total_c1_t;
				$total_c2_b += $total_c2_t;
				$total_fin_b += $total_fin_t;
				$total_vir_b += $total_vir_t;

				$total_a_b_s1 += $total_a_t_s1;
				$total_a_b_s2 += $total_a_t_s2;
				$total_a_b_s3 += $total_a_t_s3;
				$total_b1_b_s1 += $total_b1_t_s1;
				$total_b1_b_s2 += $total_b1_t_s2;
				$total_b1_b_s3 += $total_b1_t_s3;
				$total_b2_b_s1 += $total_b2_t_s1;
				$total_b2_b_s2 += $total_b2_t_s2;
				$total_b2_b_s3 += $total_b2_t_s3;
				$total_c1_b_s1 += $total_c1_t_s1;
				$total_c1_b_s2 += $total_c1_t_s2;
				$total_c1_b_s3 += $total_c1_t_s3;
				$total_c2_b_s1 += $total_c2_t_s1;
				$total_c2_b_s2 += $total_c2_t_s2;
				$total_c2_b_s3 += $total_c2_t_s3;

				$pdf->SetFont('helveticaB', '', 5, '', true);
				$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(21, 0, 'Sub Total per Cbg', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_a_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_b2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(13, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(14, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(14, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				/*$pdf->MultiCell(11, 0, number_format($total_c1_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c1_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c1_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_c1_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_fin_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_vir_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(11, 0, number_format($total_c2_b_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(12, 0, number_format($total_c2_b, 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/
				$pdf->Ln();

				if (($ln++ % $mod) == 0) {
					if ($ln == 20) {
						$mod = 20;
						$ln = 1;
					}
								$pdf->AddPage('L','A4');
								$pdf->SetFont('helvetica', '', 8, '', true);
								$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
								$pdf->Cell(0, 0, 'User : '.trim($userName), 0, 1, 'R', 0, '', 0);
								$pdf->Ln(10);

								$pdf->SetFont('helveticaB', '', 13, '', true);
								$pdf->Cell(0, 0, 'LAPORAN MONITORING SETORAN DANA SALES DAN KURANG SETOR PENJUALAN', 0, 1, 'C', 0, '', 0);
								$pdf->Ln(4);
								$pdf->SetFont('helvetica', '', 9, '', true);
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Cabang IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(50, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Kode - Nama Toko IDM.', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(90, 0, $str_store, 0, 'L', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(60, 0, 'Periode SLP', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(5, 0, ':', 0, 'L', 0, 0, '', '', true);
								$pdf->MultiCell(100, 0, date_format($start_date,"d-M-Y").' s/d '.date_format($end_date,"d-M-Y"), 0, 'L', 0, 0, '', '', true);
								$pdf->Ln(7);
								$pdf->setCellPaddings(1, 1, 1, 1);
								$pdf->SetFont('helveticaB', '', 5, '', true);
								$pdf->MultiCell(10, 18, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(8, 18, 'No', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 18, 'Tanggal SLP', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Sales Tunai SLP'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(128, 9, 'Setoran Dana Sales', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(52, 18, 'Kurang Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Finance (111802)', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(14, 18, 'Kurang Setor Virtual (111803)', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(15, 18, 'Keterangan', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(45, 18, 'Lebih Setor'."\n".'(Rp.)', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln(9);
								$pdf->MultiCell(31, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Setoran Fisik Melalui Kodel', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(64, 5, 'Slip Setoran Bank', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 9, 'Total', 1, 'C', 0, 0, '', '', true);
								/*$pdf->MultiCell(24, 9, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(11, 9, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 9, 'Total', 1, 'C', 0, 0, '', '', true);*/
								$pdf->Ln(5);
								$pdf->MultiCell(83, 4, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(12, 4, 'Tgl', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 1', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 2', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Shift 3', 1, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(13, 4, 'Total', 1, 'C', 0, 0, '', '', true);
								//$pdf->MultiCell(25, 5, 'Rp.', 1, 'C', 0, 0, '', '', true);
								$pdf->Ln();
								$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
								$pdf->MultiCell(281, 0, trim($branch[0]->BRANCH_CODE).' - '.trim($branch[0]->BRANCH_NAME), 1, 'L', 0, 0, '', '', true);
								$pdf->Ln();
				}

				$total_a += $total_a_b;
				$total_b1 += $total_b1_b;
				$total_b2 += $total_b2_b;
				$total_c1 += $total_c1_b;
				$total_c2 += $total_c2_b;
				$total_fin += $total_fin_b;
				$total_vir += $total_vir_b;

				$total_a_s1 += $total_a_b_s1;
				$total_a_s2 += $total_a_b_s2;
				$total_a_s3 += $total_a_b_s3;
				$total_b1_s1 += $total_b1_b_s1;
				$total_b1_s2 += $total_b1_b_s2;
				$total_b1_s3 += $total_b1_b_s3;
				$total_b2_s1 += $total_b2_b_s1;
				$total_b2_s2 += $total_b2_b_s2;
				$total_b2_s3 += $total_b2_b_s3;
				$total_c1_s1 += $total_c1_b_s1;
				$total_c1_s2 += $total_c1_b_s2;
				$total_c1_s3 += $total_c1_b_s3;
				$total_c2_s1 += $total_c2_b_s1;
				$total_c2_s2 += $total_c2_b_s2;
				$total_c2_s3 += $total_c2_b_s3;
		}//end if store
	}//end else store

		$pdf->SetFont('helveticaB', '', 5, '', true);
		$pdf->MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(21, 0, 'Total', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_a_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_a_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_a_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_a, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b1_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b1_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b1_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, '', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b2_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b2_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b2_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_b2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_c1_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_c1_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_c1_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(13, 0, number_format($total_c1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(14, 0, number_format($total_fin, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(14, 0, number_format($total_vir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		/*$pdf->MultiCell(11, 0, number_format($total_c1_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 0, number_format($total_c1_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 0, number_format($total_c1_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, number_format($total_c1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, number_format($total_fin, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, number_format($total_vir, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 0, number_format($total_c2_s1, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 0, number_format($total_c2_s2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(11, 0, number_format($total_c2_s3, 0, '.', ','), 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(12, 0, number_format($total_c2, 0, '.', ','), 1, 'C', 0, 0, '', '', true);*/

		ob_end_clean();
		$pdf->Output('monitoring_setoran_dana_sales_shift'.date('YmdHi').'.pdf', 'I');
	}
	// Emma 25-11-2019
	public function print_data_plus_minus($star,$end,$batch,$deposit,$type)
	{
		if($type=='pdf'){
			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Report Detail Data Penambah dan Pengurang');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Detail Data Penambah dan Pengurang', 'Cabang : '.trim($branchCode).' - '.trim($branchName));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$start_date = date_create($start);
			$end_date = date_create($end);

			$pdf->setFontSubsetting(true);
			$pdf->AddPage('P','A4');

			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->Cell(0, 0, 'Tgl. Cetak : '.date('d/m/Y H:i:s'), 0, 1, 'R', 0, '', 0);
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(23, 0, 'Deposit Date', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 0, ': '.date_format($start_date,"d M Y").' - '.date_format($end_date,"d M Y"), 0, 'L', 0, 0, '', '', true);
			$pdf->Ln();
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(23, 0, 'Type', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 0, ': '.$type, 0, 'L', 0, 0, '', '', true);
			$pdf->Ln();

			$count = 1;
			$total = 0;

			$deposit = str_replace('-', '%', $deposit);

			if ($deposit == 'X' && $batch != 'X') {
				$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
			}elseif ($deposit != 'X' && $batch != 'X') {
				$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
			}else {
				$data_deposit = $this->Mod_report->get_data_deposit($star,$end,$deposit);
			}

			if ($deposit != 'X') {
				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->MultiCell(23, 0, 'Deposit Num', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(50, 0, ': '.$data_deposit[0]->CDC_DEPOSIT_NUM, 0, 'L', 0, 0, '', '', true);
				$pdf->Ln();
			}
			if ($batch != 'X') {
				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->MultiCell(23, 0, 'Batch Num', 0, 'L', 0, 0, '', '', true);
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(50, 0, ': '.$data_deposit[0]->CDC_BATCH_NUMBER, 0, 'L', 0, 0, '', '', true);
				$pdf->Ln();
			}

			$pdf->Ln();

			foreach ($data_deposit as $dep) {
				$data_batch = $this->Mod_report->get_batch_by_deposit($dep->CDC_DEPOSIT_ID,$batch);
				$pdf->SetFont('helveticaB', '', 8, '', true);
				$pdf->Cell(0, 5, 'Deposit Num : '.$dep->CDC_DEPOSIT_NUM, 1, 0, 'L', 0, '', 0);
				$pdf->Ln();
				if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
				foreach ($data_batch as $bat) {
					$subtotal = 0;
					if ($type == 'ALL') {
						$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
						$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
					}elseif ($type == 'plus') {
						$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
					}else {
						$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
					}

					if (@$data_plus || @$data_minus) {
						$no = 0;
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->Cell(0, 5, 'Batch Num : '.$bat->CDC_BATCH_NUMBER, 1, 0, 'L', 0, '', 0);
						$pdf->Ln();
						if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
						$pdf->MultiCell(6, 9, 'No.', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(35, 9, 'Kode & Nama Toko', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, 'Tipe Data', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, 'Detail Data', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(60, 9, 'Deskripsi', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Nominal', 1, 'C', 0, 0, '', '', true);
						$pdf->Ln();
						if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
					}

					if (@$data_plus) {
						foreach ($data_plus as $plus) {
							$no++;
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(6, 9, $no, 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(35, 9, $plus->STORE, 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(22, 9, $plus->SALES_DATE, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(22, 9, 'Penambah', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 9, trim($plus->TRX_PLUS_NAME), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(60, 9, trim($plus->TRX_DETAIL_DESC), 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(20, 9, number_format($plus->TRX_DET_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->Ln();
							if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
							$total += $plus->TRX_DET_AMOUNT;
							$subtotal += $plus->TRX_DET_AMOUNT;
						}
					}

					if (@$data_minus) {
						foreach ($data_minus as $minus) {
							$no++;
							$pdf->SetFont('helvetica', '', 8, '', true);
							$pdf->MultiCell(6, 9, $no, 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(35, 9, $minus->STORE, 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(22, 9, $minus->SALES_DATE, 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(22, 9, 'Pengurang', 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(25, 9, trim($minus->TRX_MINUS_NAME), 1, 'C', 0, 0, '', '', true);
							$pdf->MultiCell(60, 9, trim($minus->TRX_MINUS_DESC), 1, 'L', 0, 0, '', '', true);
							$pdf->MultiCell(20, 9, number_format($minus->TRX_MINUS_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
							$pdf->Ln();
							if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
							$total += $minus->TRX_MINUS_AMOUNT;
							$subtotal += $minus->TRX_MINUS_AMOUNT;
						}
					}

					if (@$data_plus || @$data_minus) {
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(170, 9, 'Sub Total', 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, number_format($subtotal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
					}
				}
			}

			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(170, 9, 'Total', 1, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, number_format($total, 0, '.', ','), 1, 'R', 0, 0, '', '', true);

			ob_end_clean();
			$pdf->Output('listing_gtu'.date('YmdHi').'.pdf', 'I');

		}else if($type=='csv'){
			date_default_timezone_set("Asia/Bangkok");
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);
			$now=date('d-m-Y');
			$hour=date('H:i:s');
			
			//$star,$end,$type,$batch,$deposit,$type
			$html = 'Report Detail Data Penambah dan Pengurang'."\n".'Deposit Date : '.$star.' s/d '.$end."\n".'Type : '.$type."\n".'Cabang : '.urldecode($branchCode).'-'.$branchName."\n".'Tgl Cetak : '.$now."\n".'Waktu : '.$hour."\n".'User : '.$this->session->userdata('user_id')."\n";
			$count = 1;
			$total = 0;

			$deposit = str_replace('-', '%', $deposit);

			if ($deposit == 'X' && $batch != 'X') {
				$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
			}elseif ($deposit != 'X' && $batch != 'X') {
				$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
			}else {
				$data_deposit = $this->Mod_report->get_data_deposit($star,$end,$deposit);
			}

			if ($deposit != 'X') {
				$html .= 'Deposit Num'.':'.$data_deposit[0]->CDC_DEPOSIT_NUM."\n";
			}
			if ($batch != 'X') {
				$html .= 'Batch Num'.':'.$data_deposit[0]->CDC_BATCH_NUMBER."\n";
			}

			$html .= "\n";
			$no = 0;
			$html .= 'No.,Deposit Num,Batch Num,Kode & Nama Toko,Tgl Sales,Tipe Data,Detail Data,Deskripsi,Nominal';
		
			foreach ($data_deposit as $dep) {
				$data_batch = $this->Mod_report->get_batch_by_deposit($dep->CDC_DEPOSIT_ID,$batch);
			//	$html .= 'Deposit Num'.':'.$dep->CDC_DEPOSIT_NUM."\n";
				$html .= "\n";
			
				if ($count++ % 28 == 0) {$html .= "\n";}
				foreach ($data_batch as $bat) {
					$subtotal = 0;
					if ($type == 'ALL') {
						$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
						$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
					}elseif ($type == 'plus') {
						$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
					}else {
						$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
					}
				
				
					//if (@$data_plus || @$data_minus) {
						
					//	$html .= 'No.,Deposit Num,Batch Num,Kode & Nama Toko,Tgl Sales,Tipe Data,Detail Data,Deskripsi,Nominal';
				//		$html .= "\n";
					//	$pdf->SetFont('helveticaB', '', 8, '', true);
					//	$html .= 'Batch Num'.':'.$bat->CDC_BATCH_NUMBER."\n";
						
						
					//}

					if (@$data_plus) {
						foreach ($data_plus as $plus) {
							$no++;
							$html .=  $no.','.$dep->CDC_DEPOSIT_NUM.','.$bat->CDC_BATCH_NUMBER.','.$plus->STORE.','.$plus->SALES_DATE.',Penambah'.','.trim($plus->TRX_PLUS_NAME).','. trim($plus->TRX_PLUS_DESC).','.trim($plus->TRX_DETAIL_DESC).','.$plus->TRX_DET_AMOUNT."\n";
							
						
							$total += $plus->TRX_DET_AMOUNT;
							$subtotal += $plus->TRX_DET_AMOUNT;
						}
					}

					if (@$data_minus) {
						foreach ($data_minus as $minus) {
							$no++;
							$html .=  $no.','. $minus->STORE.','.$minus->SALES_DATE.',Pengurang'.','.trim($minus->TRX_MINUS_NAME).','. trim($minus->TRX_MINUS_DESC).','.$minus->TRX_MINUS_AMOUNT."\n";
							
						
							$total += $minus->TRX_MINUS_AMOUNT;
							$subtotal += $minus->TRX_MINUS_AMOUNT;
						}
					}

				
				}
			}

		
			$html .='Total,'.$total;
			$print['html'] = $html;
			$print['file_name'] = 'Report Detail Data Penambah dan Pengurang_Per_Tanggal_';
			$this->load->view('view_export_csv', $print, FALSE);  

		}
		
	}

	/*
	public function print_data_plus_minus($star,$end,$type,$batch,$deposit)
	{
		$this->load->library('Pdf');
		date_default_timezone_set("Asia/Bangkok");
		$now = date('d-m-Y');
		$time = date("H:i:s");
		$branchCode = $this->session->userdata('branch_code');
		$userName = $this->session->userdata('username');

		$this->load->model('master/Mod_cdc_master_branch');
		$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($userName);
		$pdf->SetTitle('Report Detail Data Penambah dan Pengurang');
		$pdf->SetSubject('');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Detail Data Penambah dan Pengurang', 'Cabang : '.trim($branchCode).' - '.trim($branchName));

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(10, 18, 10);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
		}

		$start_date = date_create($start);
		$end_date = date_create($end);

		$pdf->setFontSubsetting(true);
		$pdf->AddPage('P','A4');

		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tgl. Cetak : '.date('d/m/Y H:i:s'), 0, 1, 'R', 0, '', 0);
		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->MultiCell(23, 0, 'Deposit Date', 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->MultiCell(50, 0, ': '.date_format($start_date,"d M Y").' - '.date_format($end_date,"d M Y"), 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();
		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->MultiCell(23, 0, 'Type', 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->MultiCell(50, 0, ': '.$type, 0, 'L', 0, 0, '', '', true);
		$pdf->Ln();

		$count = 1;
		$total = 0;

		$deposit = str_replace('-', '%', $deposit);

		if ($deposit == 'X' && $batch != 'X') {
			$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
		}elseif ($deposit != 'X' && $batch != 'X') {
			$data_deposit = $this->Mod_report->get_data_deposit_by_batch($star,$end,$batch,$deposit);
		}else {
			$data_deposit = $this->Mod_report->get_data_deposit($star,$end,$deposit);
		}

		if ($deposit != 'X') {
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(23, 0, 'Deposit Num', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 0, ': '.$data_deposit[0]->CDC_DEPOSIT_NUM, 0, 'L', 0, 0, '', '', true);
			$pdf->Ln();
		}
		if ($batch != 'X') {
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(23, 0, 'Batch Num', 0, 'L', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->MultiCell(50, 0, ': '.$data_deposit[0]->CDC_BATCH_NUMBER, 0, 'L', 0, 0, '', '', true);
			$pdf->Ln();
		}

		$pdf->Ln();

		foreach ($data_deposit as $dep) {
			$data_batch = $this->Mod_report->get_batch_by_deposit($dep->CDC_DEPOSIT_ID,$batch);
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->Cell(0, 5, 'Deposit Num : '.$dep->CDC_DEPOSIT_NUM, 1, 0, 'L', 0, '', 0);
			$pdf->Ln();
			if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
			foreach ($data_batch as $bat) {
				$subtotal = 0;
				if ($type == 'ALL') {
					$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
					$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
				}elseif ($type == 'plus') {
					$data_plus = $this->Mod_report->get_detail_penambah($bat->CDC_BATCH_ID);
				}else {
					$data_minus = $this->Mod_report->get_detail_pengurang($bat->CDC_BATCH_ID);
				}

				if (@$data_plus || @$data_minus) {
					$no = 0;
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->Cell(0, 5, 'Batch Num : '.$bat->CDC_BATCH_NUMBER, 1, 0, 'L', 0, '', 0);
					$pdf->Ln();
					if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
					$pdf->MultiCell(6, 9, 'No.', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(35, 9, 'Kode & Nama Toko', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(22, 9, 'Tgl Sales', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(22, 9, 'Tipe Data', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(25, 9, 'Detail Data', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(60, 9, 'Deskripsi', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(20, 9, 'Nominal', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
					if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
				}

				if (@$data_plus) {
					foreach ($data_plus as $plus) {
						$no++;
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(6, 9, $no, 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(35, 9, $plus->STORE, 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, $plus->SALES_DATE, 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, 'Penambah', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, trim($plus->TRX_PLUS_NAME), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(60, 9, trim($plus->TRX_DETAIL_DESC), 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, number_format($plus->TRX_DET_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
						$total += $plus->TRX_DET_AMOUNT;
						$subtotal += $plus->TRX_DET_AMOUNT;
					}
				}

				if (@$data_minus) {
					foreach ($data_minus as $minus) {
						$no++;
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->MultiCell(6, 9, $no, 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(35, 9, $minus->STORE, 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, $minus->SALES_DATE, 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(22, 9, 'Pengurang', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, trim($minus->TRX_MINUS_NAME), 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(60, 9, trim($minus->TRX_MINUS_DESC), 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, number_format($minus->TRX_MINUS_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
						$pdf->Ln();
						if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
						$total += $minus->TRX_MINUS_AMOUNT;
						$subtotal += $minus->TRX_MINUS_AMOUNT;
					}
				}

				if (@$data_plus || @$data_minus) {
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(170, 9, 'Sub Total', 1, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(20, 9, number_format($subtotal, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
					$pdf->Ln();
					if ($count++ % 28 == 0) {$pdf->AddPage(); $pdf->Ln(5);}
				}
			}
		}

		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->MultiCell(170, 9, 'Total', 1, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(20, 9, number_format($total, 0, '.', ','), 1, 'R', 0, 0, '', '', true);

		ob_end_clean();
		$pdf->Output('listing_gtu'.date('YmdHi').'.pdf', 'I');
	}
	*/
	// START IWAN CODE //
		public function print_listing_gtu($from='all',$to='all',$branch_id,$dc_code){
			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_report->get_cabang_session($branch_id);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Listing GTU');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Listing GTU (Deposit)', 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(5, 18, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->AddPage('P','A4');

			$html = '
			<div align="center">
				<table>
					<tr>
						<td colspan="2" width="75%">
							<br> <br> <br>
							<b> <font size="14"> Listing Giro Tukar Uang </font> <br>
							Tanggal Transaksi  : '.strtoupper(date_format(date_create($from),"d-M-Y")).' s/d '.strtoupper(date_format(date_create($to),"d-M-Y")).'
							</b>
						</td>
						<td width="30%" align="left">
							<br> <br>
							Date Time : '.$now.' '.$time.'<br>
							User Name : '.$userName.'
						</td>
					</tr>
				</table>
			</div>
			<br>
			';

			$html .= '
				<table border="1px" align="center">
					<tr>
						<td width="5%">  <b> No. </b> </td>
						<td width="13%"> <b> Deposit Number </b> </td>
						<td width="12%"> <b> Mutation Date </b> </td>
						<td width="12%"> <b> Batch Number </b> </td>
						<td width="12%"> <b> Batch Date </b> </td>
						<td width="12%"> <b> Check Numer </b> </td>
						<td width="20%"> <b> Check Amount </b> </td>
						<td width="14%"> <b> Batch Username </b> </td>
					</tr>
			';

			$table_body	= $this->Mod_report->getListingGTU($from,$to,$branch_id,$dc_code);
			$no = 0; $grandTotal = 0;
			foreach ($table_body as $row) {
				$no++;
				$grandTotal = $grandTotal + $row->CDC_GTU_AMOUNT;
				$html .='
					<tr>
						<td width="5%"  align="left"> '.$no.' </td>
						<td width="13%" align="left"> '.$row->CDC_DEPOSIT_NUM.' </td>
						<td width="12%" align="left"> '.strtoupper(date_format(date_create($row->MUTATION_DATE),"d-M-Y")).' </td>
						<td width="12%" align="left"> '.$row->CDC_BATCH_NUMBER.' </td>
						<td width="12%" align="left"> '.strtoupper(date_format(date_create($row->CDC_BATCH_DATE),"d-M-Y")).' </td>
						<td width="12%" align="left"> '.$row->CDC_GTU_NUMBER.' </td>
						<td width="20%" align="right"> '.number_format($row->CDC_GTU_AMOUNT, 0, '.', ',').' </td>
						<td width="14%" align="left"> '.$row->USERNAME.' </td>
					</tr>
				';
			}

			$html .= '
					<tr>
						<td colspan="6" align="left"> <b> Grand Total : </b> </td>
						<td align="right"> <b> '.number_format($grandTotal, 0, '.', ',').' </b> </td>
					</tr>
				</table>
			';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			ob_end_clean();
			$pdf->Output('listing_gtu'.date('YmdHi').'.pdf', 'I');

		}


		/*public function print_monitoring_kodel2($from='all',$barang='all',$kodel='all'){
			if($from != 'all'){
				$from = strtoupper(date_format(date_create($from),"d-M-Y"));
			}

			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Monitoring Penerimaan Kodel');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monitoring Penerimaan Kodel', 'Branch :'.trim($branchCode).' - '.trim($branchName));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->AddPage('P','A4');

			$html = '
			<div align="center">
				<h1> <b> Monitoring Penerimaan Kodel </b> </h1> <br>
				Tanggal Kirim Barang : '.strtoupper($from).' <br>
				Status Kirim Barang  : '.strtoupper($barang).' <br>
				Status Terima Kodel  : '.strtoupper($kodel).' <br>
			</div>
			';

			$html .= '
			<table align="center" border="1px">
				<tr>
					<td rowspan="2" width="5%"><b> No. </b></td>
					<td rowspan="2" width="8%"><b> Kode Toko </b></td>
					<td rowspan="2" width="32%"><b> Nama Toko </b></td>
					<td rowspan="2" width="8%"><b> Freq. Kirim </b></td>
					<td colspan="3" width="18%"><b> Kirim </b></td>
					<td rowspan="2" width="8%"><b> Terima Kodel </b></td>
					<td rowspan="2" width="20%"><b> Jam Terima Kodel </b></td>
				</tr>
				<tr>
					<td><b> Brg </b></td>
					<td><b> Sales </b></td>
					<td><b> Coin </b></td>
				</tr>
			';

			$table_body	= $this->Mod_report->getMonitoringKodel($from,$barang,$kodel);
			$no = 0; $terima = 0;
			foreach ($table_body as $row) {
				$freq = '';
				if($row->BARANG == 'YA'){
					$freq = $this->Mod_report->getMonitoringKodel_freq($from,$row->STORE_CODE);
				}

				$no++;
				$html .='
					<tr>
						<td> '.$no.' </td>
						<td> '.$row->STORE_CODE.' </td>
						<td> '.$row->STORE_NAME.' </td>
						<td> '.$freq.' </td>
						<td> '.$row->BARANG.' </td>
						<td> '.$row->SALES.' </td>
						<td> '.$row->COIN.' </td>
						<td> '.$row->TOTAL_SALES.' </td>
						<td> '.$row->JAM. '</td>
					</tr>
				';
			};

			$html .= '
			</table>
			';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			ob_end_clean();
			$pdf->Output('monitoring_kodel'.date('YmdHi').'.pdf', 'I');

		}*/

		public function print_monitoring_kodel($from='all',$barang='all',$kodel='all'){
			if($from != 'all'){
				$from = strtoupper(date_format(date_create($from),"d-M-Y"));
			}

			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Monitoring Penerimaan Kodel');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monitoring Penerimaan Kodel', 'Branch :'.trim($branchCode).' - '.trim($branchName));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->AddPage('P','A4');


			$pdf->SetFont('helveticaB', '', 12, '', true);
			$pdf->Cell(0, 0, 'Monitoring Penerimaan Kodel', 0, 1, 'C', 0, '', 0);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->Cell(0, 0, 'Tanggal Kirim Barang : '.strtoupper($from), 0, 1, 'C', 0, '', 0);
			$pdf->Cell(0, 0, 'Status Kirim Barang  : '.strtoupper($barang), 0, 1, 'C', 0, '', 0);
			$pdf->Cell(0, 0, 'Status Terima Kodel  : '.strtoupper($kodel), 0, 1, 'C', 0, '', 0);
			$pdf->Ln();

			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(10, 7.5, 'No.', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 7.5, 'Kode Toko', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(60, 7.5, 'Nama Toko', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 7.5, 'Freq. Kirim', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(30, 0, 'Kirim', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 7.5, 'Terima Kodel', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(40, 7.5, 'Jam Terima Kodel', 1, 'C', 0, 0, '', '', true);
			$pdf->Ln(3.7);
			$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(10, 0, 'Brg', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(10, 0, 'Sales', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(10, 0, 'Coin', 1, 'C', 0, 0, '', '', true);
			$pdf->Ln();

			$table_body	= $this->Mod_report->getMonitoringKodel($from,$barang,$kodel);
			$no = 1; $terima = 0; $page = 1;

			foreach ($table_body as $row) {
				$freq = '';
				if($row->BARANG == 'YA'){
					$freq = $this->Mod_report->getMonitoringKodel_freq($from,$row->STORE_CODE);
				}
				$pdf->SetFont('helvetica', '', 8, '', true);
				$pdf->MultiCell(10, 0, $no++, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(15, 0, $row->STORE_CODE, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(60, 0, $row->STORE_NAME, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(15, 0, $freq, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(10, 0, $row->BARANG, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(10, 0, $row->SALES, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(10, 0, $row->COIN, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(15, 0, $row->TOTAL_SALES, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(40, 0, $row->JAM, 1, 'C', 0, 0, '', '', true);
				$pdf->Ln();
				$page++;
				if($page == 61){
					$page = 1;
					$pdf->AddPage('P','A4');
					$pdf->SetFont('helveticaB', '', 12, '', true);
					$pdf->Cell(0, 0, 'Monitoring Penerimaan Kodel', 0, 1, 'C', 0, '', 0);
					$pdf->SetFont('helvetica', '', 8, '', true);
					$pdf->Cell(0, 0, 'Tanggal Kirim Barang : '.strtoupper($from), 0, 1, 'C', 0, '', 0);
					$pdf->Cell(0, 0, 'Status Kirim Barang  : '.strtoupper($barang), 0, 1, 'C', 0, '', 0);
					$pdf->Cell(0, 0, 'Status Terima Kodel  : '.strtoupper($kodel), 0, 1, 'C', 0, '', 0);
					$pdf->Ln();

					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(10, 7.5, 'No.', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(15, 7.5, 'Kode Toko', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(60, 7.5, 'Nama Toko', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(15, 7.5, 'Freq. Kirim', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(30, 0, 'Kirim', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(15, 7.5, 'Terima Kodel', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(40, 7.5, 'Jam Terima Kodel', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln(3.7);
					$pdf->MultiCell(100, 0, '', 0, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(10, 0, 'Brg', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(10, 0, 'Sales', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(10, 0, 'Coin', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}
			}
			
			ob_end_clean();
			$pdf->Output('monitoring_kodel'.date('YmdHi').'.pdf', 'I');

		}


		public function print_penerimaan_sales($from,$pending){
			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Monitoring Penerimaan Sales Toko');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monitoring Penerimaan Sales Toko', 'Branch :'.trim($branchCode).' - '.trim($branchName));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->AddPage('P','A4');

			$html = '
			<div align="center">
				<h1> <b> Monitoring Penerimaan Sales Toko </b> </h1> <br>
				<b> '.trim($branchCode).' - '.trim($branchName).' </b> <br>
				<b> Tanggal '.strtoupper(date_format(date_create($from),"d-M-Y")).' </b> <br>

			</div>
			';

			$tgl = date_create($from);

			$html .= '
			<table align="center" border="1px">
				<tr>
					<td rowspan="2" width="5%"><b> No. </b></td>
					<td rowspan="2" width="10%"><b> Store Code </b></td>
					<td rowspan="2" width="45%"><b> Store Name </b></td>
					<td colspan="5" width="40%"><b> Sales Date </b></td>
				</tr>
				<tr>
					<td><b> '.date_sub($tgl, date_interval_create_from_date_string('5 days'))->format('d').' </b></td>
					<td><b> '.date_sub($tgl, date_interval_create_from_date_string('-1 days'))->format('d').' </b></td>
					<td><b> '.date_sub($tgl, date_interval_create_from_date_string('-1 days'))->format('d').' </b></td>
					<td><b> '.date_sub($tgl, date_interval_create_from_date_string('-1 days'))->format('d').' </b></td>
					<td><b> '.date_sub($tgl, date_interval_create_from_date_string('-1 days'))->format('d').' </b></td>
				</tr>
			';

			$table_body	= $this->Mod_report->getPenerimaanSales($from,$pending);
			$no = 0;
			$cek1_v = 0;
			$cek1_n = 0;
			$cek2_v = 0;
			$cek2_n = 0;
			$cek3_v = 0;
			$cek3_n = 0;
			$cek4_v = 0;
			$cek4_n = 0;
			$cek5_v = 0;
			$cek5_n = 0;
			foreach ($table_body as $row) {
				$no++;

				if ($row->CEK5 == 'V') {
					$cek5_v++;
				}else{
					$cek5_n++;
				}

				if ($row->CEK4 == 'V') {
					$cek4_v++;
				}else{
					$cek4_n++;
				}

				if ($row->CEK3 == 'V') {
					$cek3_v++;
				}else{
					$cek3_n++;
				}

				if ($row->CEK2 == 'V') {
					$cek2_v++;
				}else{
					$cek2_n++;
				}

				if ($row->CEK1 == 'V') {
					$cek1_v++;
				}else{
					$cek1_n++;
				}
				$html .='
				<tr>
					<td> '.$no.' </td>
					<td> '.$row->STORE_CODE.' </td>
					<td> '.$row->STORE_NAME.' </td>

					<td> '.$row->CEK5.' </td>
					<td> '.$row->CEK4.' </td>
					<td> '.$row->CEK3.' </td>
					<td> '.$row->CEK2.' </td>
					<td> '.$row->CEK1.' </td>
				</tr>
				';
			};

			$html .='
				<tr>
					<td colspan="3"> Total Tidak Pending </td>
					<td> '.$cek5_v.' </td>
					<td> '.$cek4_v.' </td>
					<td> '.$cek3_v.' </td>
					<td> '.$cek2_v.' </td>
					<td> '.$cek1_v.' </td>
				</tr>
				<tr>
					<td colspan="3"> Total Pending </td>
					<td> '.$cek5_n.' </td>
					<td> '.$cek4_n.' </td>
					<td> '.$cek3_n.' </td>
					<td> '.$cek2_n.' </td>
					<td> '.$cek1_n.' </td>
				</tr>
				';

			$html .= '
			</table>
			';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			ob_end_clean();
			$pdf->Output('penerimaan_sales'.date('YmdHi').'.pdf', 'I');

		}

		public function print_sales_diff_journal($store_type, $start, $end, $branch_id, $dc_code)
		{
			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_report->get_cabang_session($branch_id);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Daily Sales Difference Journal Listing');
			$pdf->SetSubject('');

			if ($store_type == 'R') {
				$owner_name = 'Reguler';
			}elseif ($store_type == 'F') {
				$owner_name = 'Franchise';
			}else{
				$owner_name = 'All';
			}

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Daily Sales Difference Journal Listing', 'Cabang : '.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helveticaB', '', 15, '', true);
			$pdf->AddPage('L','A3');

			$date = date_create_from_format('Y-m-d', $start);
			$start_date = date_format($date,'d M Y');
			$date = date_create_from_format('Y-m-d', $end);
			$end_date = date_format($date,'d M Y');

			$pdf->setCellPaddings(1, 1, 1, 1);

			// set cell margins
			$pdf->setCellMargins(0, 0, 0, 0);

			// test Cell stretching
			$pdf->Cell(0, 0, 'Period : '.$start_date.' To '.$end_date, 0, 1, 'L', 0, '', 0);
			$pdf->Cell(0, 0, 'Owner Type : '.$owner_name, 0, 1, 'L', 0, '', 0);
	        $pdf->Ln(4);

			// Multicell test
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(10, 9, 'No.', 1, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Selisih', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(35, 9, 'Store Code', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(45, 9, 'Store Name', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Sales Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(30, 9, 'File Amount', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(30, 9, 'Actual Sales', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Difference Amount', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(25, 9, 'RRAK Deduction', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(25, 9, 'Kurset Deduction', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(25, 9, 'Virtual Deduction', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Others Deduction', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(25, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Mutation Date', 1, 'C', 0, 0, '', '', true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->Ln(9);

			$no = 0;
			$slp_amount = 0;

			$this->Mod_report->delete_slp_tmp($this->session->userdata('usrId'));

			$data_slp = $this->Mod_report->get_data_diff_journal_slp($store_type, $start, $end, $branchName[0]->BRANCH_CODE, $dc_code);

			foreach ($data_slp as $key_slp) {
				$data = $this->Mod_report->get_data_diff_journal($key_slp->STORE_CODE, $key_slp->SALES_DATE, $branchName[0]->BRANCH_CODE, $dc_code);

				$amount = $this->Mod_report->get_amount_slp($key_slp->STORE_CODE, $key_slp->SALES_DATE);

				if($amount){
					$slp_amount = $amount->SALES_AMOUNT;
				}else{
					$amount = $this->Mod_report->get_amount_slp2($key_slp->STORE_CODE, $key_slp->SALES_DATE);

					if($amount){
						$slp_amount = $amount->SALES_AMOUNT;
					}
				}

				if ($data) {
					foreach ($data as $key) {

						//$diff = $key_slp->SALES_AMOUNT - $key->ACTUAL_SALES_AMOUNT;
						$diff = $slp_amount - $key->ACTUAL_SALES_AMOUNT;

						if ($diff == 0) {
							$st = 'S';
						}else $st = 'J';

						$this->Mod_report->insert_diff_tmp($st,$key_slp->STORE_CODE,$key_slp->STORE_NAME,$key_slp->SALES_DATE,$slp_amount,$key->ACTUAL_SALES_AMOUNT,$diff,$key->RRAK_DEDUCTION,$key->LESS_DEPOSIT_DEDUCTION,$key->VIRTUAL_PAY_LESS_DEDUCTION,$key->OTHERS_DEDUCTION,$key->CDC_BATCH_NUMBER,$key->MUTATION_DATE);

						/*$this->Mod_report->insert_diff_tmp($st,$key_slp->STORE_CODE,$key_slp->STORE_NAME,$key_slp->SALES_DATE,$key_slp->SALES_AMOUNT,$key->ACTUAL_SALES_AMOUNT,$diff,$key->RRAK_DEDUCTION,$key->LESS_DEPOSIT_DEDUCTION,$key->VIRTUAL_PAY_LESS_DEDUCTION,$key->OTHERS_DEDUCTION,$key->CDC_BATCH_NUMBER,$key->MUTATION_DATE);*/
					}
				}else{
					$this->Mod_report->insert_diff_tmp('',$key_slp->STORE_CODE,$key_slp->STORE_NAME,$key_slp->SALES_DATE,$slp_amount,0,$slp_amount,0,0,0,0,'','');

					/*$this->Mod_report->insert_diff_tmp('',$key_slp->STORE_CODE,$key_slp->STORE_NAME,$key_slp->SALES_DATE,$key_slp->SALES_AMOUNT,0,$key_slp->SALES_AMOUNT,0,0,0,0,'','');*/
				}
			}

			$data_all = $this->Mod_report->get_diff_tmp($this->session->userdata('usrId'));

			foreach ($data_all as $key_all) {
				$no++;
				$st = '';
				if ($no >= 25) {
					if ($no % 25 == 0) {
						$pdf->AddPage('L','A3');
						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 9, 'No.', 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Selisih', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(35, 9, 'Store Code', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(45, 9, 'Store Name', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Sales Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 9, 'File Amount', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(30, 9, 'Actual Sales', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Difference Amount', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, 'RRAK Deduction', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, 'Kurset Deduction', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, 'Virtual Deduction', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Others Deduction', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(25, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Mutation Date', 1, 'C', 0, 0, '', '', true);
						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->Ln(9);
					}
				}
				$status_selisih='';
				if($key_all->STATUS=='S')
				{
					$status_selisih='Sesuai';
				}else{
					$status_selisih='Journal';
				}
				$pdf->MultiCell(10, 9, $no, 1, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(20, 9, $status_selisih, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(35, 9, $key_all->STORE_CODE, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(45, 9, $key_all->STORE_NAME, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 9, $key_all->SALES_DATE_V, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(30, 9, number_format($key_all->SALES_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(30, 9, number_format($key_all->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 9, number_format($key_all->DIFF_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(25, 9, number_format($key_all->RRAK_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(25, 9, number_format($key_all->LESS_DEPOSIT_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(25, 9, number_format($key_all->VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 9, number_format($key_all->OTHERS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(25, 9, $key_all->CDC_BATCH_NUMBER, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 9, $key_all->MUTATION_DATE_V, 1, 'C', 0, 0, '', '', true);
				$pdf->Ln(9);
			}

			ob_end_clean();
			$pdf->Output('sales_difference_journal'.date('YmdHi').'.pdf', 'I');
		}


		public function print_receipt_sales_qty($from){
			$this->load->library('Pdf');
			date_default_timezone_set("Asia/Bangkok");
			$now = date('d-m-Y');
			$time = date("H:i:s");
			$branchCode = $this->session->userdata('branch_code');
			$userName = $this->session->userdata('username');

			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($userName);
			$pdf->SetTitle('Receipt Sales (Qty)-Handheld');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Receipt Sales (Qty)-Handheld', 'Branch :'.trim($branchCode).' - '.trim($branchName));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			$pdf->SetMargins(10, 18, 10);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
			}

			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->AddPage('P','A4');

			$html = ' <br>
			<div align="center">
				<font size="14">
					<b> Penerimaan Jumlah (Qty) Sales </b> <br>
					<b> Versi Handled </b>
				</font>
				<br> <br>

				<font size="12">
					<b> CBG - '.trim($branchName).' </b> <br>
					<b> Tanggal '.strtoupper(date_format(date_create($from),"d-M-Y")).' </b> <br>
				</font>
			</div>
			';

			$html .= '
			<table align="center" border="1px">
				<tr>
					<td width="5%"><b> No. </b></td>
					<td width="20%"><b> Store Code </b></td>
					<td width="55%"><b> Store Name </b></td>
					<td width="20%"><b> Sales Count </b></td>
				</tr>
			';

			$table_body	= $this->Mod_report->getReceiptSalesQty($from);
			$no = 0;
			foreach ($table_body as $row) {
				$no++;
				$html .='
				<tr>
					<td> '.$no.' </td>
					<td> '.$row->STORE_CODE.' </td>
					<td> '.$row->STORE_NAME.' </td>
					<td> '.$row->COUNT.' </td>
				</tr>
				';
			};

			$html .= '
			</table>
			';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			ob_end_clean();
			$pdf->Output('receipt_sales_qty'.date('YmdHi').'.pdf', 'I');

		}


		public function print_monitoring_voucher_perToko($toko='all',$from,$to,$branch_id,$dc_code){
			$this->load->library('Pdf');
			$now = date('d-m-Y');
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_report->get_cabang_session($branch_id);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($this->session->userdata('username'));
			$pdf->SetTitle('Monitoring Voucher per Toko');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monitoring Voucher per Toko', 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, 18, PDF_MARGIN_RIGHT);
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

			$pdf->SetFont('helvetica', '', 8, '', true);

			$pdf->AddPage('P','A4');

			$table_header = $this->Mod_report->getVoucherHeader_perToko($toko);
			$html = '
			<div align="center">
			<b>
				<h1> Monitoring Voucher per Toko</h1> <br>
				Toko : '.$table_header.'<br>
				Tanggal Transaksi : '.$from.' s/d '.$to.' <br><br>
			</b>
			</div>
			';

			$html.=	'
			<table border="1px" cellpadding="3px" align="center">
			 		<tr>
						<td colspan="6" align="left"><b> '.$table_header.' </b></td>
					</tr>
			';

			$table_body	= $this->Mod_report->getVoucherBody_perToko($toko,$from,$to);
			$html .= '
			<tr>
				<td width="5%"> <b> No. </b> </td>
				<td width="18%"> <b> Batch Number </b> </td>
				<td width="18%"> <b> Batch Date </b> </td>
				<td width="18%"> <b> Sales Date </b> </td>
				<td width="18%"> <b> Voucher Num </b> </td>
				<td width="23%"> <b> Voucher Amount </b> </td>
			</tr>
			';
			$no = 0; $totalVoucher = 0;
			foreach ($table_body as $row) {
				$no = $no+1;
				$totalVoucher = $totalVoucher+$row->TRX_VOUCHER_AMOUNT;
				$html .= '
				<tr>
					<td> '.$no.' </td>
					<td> '.$row->CDC_BATCH_NUMBER.' </td>
					<td> '.$row->CDC_BATCH_DATE.' </td>
					<td> '.$row->SALES_DATE.' </td>
					<td> '.$row->VOUCHER_NUM.' </td>
					<td align="right"> '.number_format($row->TRX_VOUCHER_AMOUNT, 0, '.', ',').' </td>
				</tr>
				';
			}

			$html.='
				<tr>
					<td colspan="4" align="right"> <b> Jumlah Voucher  </b> </td>
					<td> '.$no.' </td>
					<td> </td>
				</tr>
				<tr>
					<td colspan="5" align="right"> <b> Total Amount Voucher </b> </td>
					<td align="right"> '.number_format($totalVoucher, 0, '.', ',').' </td>
				</tr>
			</table>
			';

			$html.='
			<table border="1px">
			<tr>
				<td width="27%" align="right"> 	<font size="12"><b> Total All Voucher 														</b></font> </td>
				<td width="10%" align="center"> 	<font size="12"><b> '.$no.' 																			</b></font> </td>
				<td width="40%" align="right"> 	<font size="12"><b>	Total All Voucher Amount 											</b></font> </td>
				<td width="23%" align="right">		<font size="12"><b> '.number_format($totalVoucher, 0, '.', ',').' </b></font> </td>
			</tr>
			</table>
			';
			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

			ob_end_clean();
			$pdf->Output('monitoring_voucher_perToko'.date('YmdHi').'.pdf', 'I');
		}


		public function print_pending_sales($from, $include_go){
			$this->load->library('Pdf');
			$now = date('d-m-Y');
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($this->session->userdata('username'));
			$pdf->SetTitle('Report Pending Sales');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Pending Sales', 'Branch :'.trim($branchCode).' - '.trim($branchName));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(10, 18, 10);
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

			$pdf->SetFont('helvetica', '', 8, '', true);

			$pdf->AddPage('P','A4');

			$html = '
			<div align="center">
			<b>
				<h1> Report Pending Sales </h1> <br>
				Per Tanggal : '.strtoupper(date_format(date_create($from),"d-M-Y")).'<br>
				(Tidak Ada Kirim Barang) <br> <br>
				'.date_format(date_create($from-5),"d-M-Y").'
			</b>
			</div>
			';

			$html .= '
			<table border="1px" cellpadding="3px" align="center">
			<tr>
				<td width="5%"> <b> No. </b> </td>
				<td width="12%"> <b> Kode </b> </td>
				<td width="30%"> <b> Nama Toko </b> </td>
				<td width="12%"> <b> Tgl Sales </b> </td>
				<td width="20%"> <b> Keterangan </b> </td>
				<td width="20%"> <b> Follow Up </b> </td>
			</tr>
			';
			$i=0; $no = 0;
			$toko = $this->Mod_report->getSemuaToko();
			foreach ($toko as $cek_toko) {
				for($i=1; $i<=31; $i++){
					$cek_tgl= date("Y-m-d", strtotime($from. " - $i days"));
					$date = date_create($cek_tgl);
					$hasilCek	= $this->Mod_report->getPending_sales($cek_tgl, $cek_toko->STORE_CODE, $include_go);

					if($hasilCek == 0){
						$no = $no+1;
						$html .= '
						<tr>
							<td> '.$no.' </td>
							<td> '.$cek_toko->STORE_CODE.' </td>
							<td align="left"> '.$cek_toko->STORE_NAME.' </td>
							<td> '.date_format($date,"d-M-y").' </td>
							<td>  </td>
							<td>  </td>
						</tr>
						';
					}

				}
			}
			$html.='
			</table>
			';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

			ob_end_clean();
			$pdf->Output('pending_sales'.date('YmdHi').'.pdf', 'I');
		}


		public function get_receipt_register_toko($branch_id, $dc_code){
			$result = $this->Mod_report->get_receipt_register_toko($branch_id, $dc_code);
			echo json_encode($result);
		}

		public function print_receipt_register_clone($from, $to, $toko1='all', $toko2='all'){
			/*$this->load->library('Pdf');*/
			$now = date('d-m-Y');
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);
			$table_body	= $this->Mod_report->getReceipt_register($from,$to,$toko1,$toko2);

			// create new PDF document
			/*$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($this->session->userdata('username'));
			$pdf->SetTitle('Report Receipt Register');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Receipt Register', 'Branch :'.trim($branchCode).' - '.trim($branchName));

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

			$pdf->SetFont('helvetica', '', 8, '', true);

			$pdf->AddPage('L','A2');*/

			$html = '
			<div align="center">
			<b>
				<h1> Cashier DC Receipt Register </h1> <br>
				Period : '.strtoupper(date_format(date_create($from),"d-M-Y")).' s/d '.strtoupper(date_format(date_create($to),"d-M-Y")).' <br>
	 			<br>
			</b>
			</div>
			';

			$html .= '
				<table border="1" align="center">
					<tr>
						<td width="2%"> No. </td>
						<td width="3%"> Store Code </td>
						<td width="9%"> Store Name </td>
						<td width="4%"> Sales Date </td>
						<td width="2%"> Rec Type </td>
						<td width="5%"> Batch Number </td>
						<td width="4%"> Batch Date </td>
						<td width="4%"> Act Cash+ Penggantian </td>
						<td width="3.5%"> Actual RRAK </td>
						<td width="2%"> Kurset Date </td>
						<td width="3.5%"> Actual Kurset </td>
						<td width="2%"> Virt Date </td>
						<td width="3.5%"> Virtual Kurset </td>
						<td width="2%"> Vouc Date  </td>
						<td width="3.5%"> Actual Voucher </td>
						<td width="2%"> Byr NBH Date </td>
						<td width="3.5%"> Actual Byr NBH </td>
						<td width="2%"> Ptgjwb WU Date </td>
						<td width="3.5%"> Actual Ptgjwb WU </td>
						<td width="3.5%"> Actual Lain </td>
						<td width="3.5%"> Actual Lain Desc </td>
						<td width="4%"> Total Actual </td>
						<td width="3.5%"> Potongan RRAK </td>
						<td width="3.5%"> Potongan Kurset </td>
						<td width="3.5%"> Potongan Lain </td>
						<td width="3.5%"> Potongan Virtual </td>
						<td width="3%"> Potongan Lain Desc </td>
						<td width="3%"> User Name </td>
						<td width="3%"> Input Time </td>
					</tr>
			';

			$no=0;
			$ACTUAL_SALES_AMOUNT = 0;   		$ACTUAL_RRAK_AMOUNT = 0;      $ACTUAL_PAY_LESS_DEPOSITED = 0;
			$ACTUAL_VIRTUAL_PAY_LESS = 0; 	$ACTUAL_VOUCHER_AMOUNT = 0;  	$ACTUAL_LOST_ITEM_PAYMENT = 0;
			$ACTUAL_WU_ACCOUNTABILITY = 0;  $ACTUAL_OTHERS_AMOUNT = 0;    $tot_ACTUAL_TOTAL = 0;
			$RRAK_DEDUCTION = 0;       			$LESS_DEPOSIT_DEDUCTION = 0;  $OTHERS_DEDUCTION = 0;
			$VIRTUAL_PAY_LESS_DEDUCTION = 0;
			foreach ($table_body as $row) {
				$no++;
				$ACTUAL_TOTAL = $row->ACTUAL_SALES_AMOUNT + $row->ACTUAL_RRAK_AMOUNT+ $row->ACTUAL_PAY_LESS_DEPOSITED + $row->ACTUAL_VIRTUAL_PAY_LESS +
					$row->ACTUAL_VOUCHER_AMOUNT + $row->ACTUAL_LOST_ITEM_PAYMENT + $row->ACTUAL_WU_ACCOUNTABILITY + $row->ACTUAL_OTHERS_AMOUNT;

					$ACTUAL_SALES_AMOUNT    		= $ACTUAL_SALES_AMOUNT    		+ $row->ACTUAL_SALES_AMOUNT;
					$ACTUAL_RRAK_AMOUNT         = $ACTUAL_RRAK_AMOUNT     		+ $row->ACTUAL_RRAK_AMOUNT;
					$ACTUAL_PAY_LESS_DEPOSITED  = $ACTUAL_PAY_LESS_DEPOSITED  + $row->ACTUAL_PAY_LESS_DEPOSITED;
					$ACTUAL_VIRTUAL_PAY_LESS  	= $ACTUAL_VIRTUAL_PAY_LESS  	+ $row->ACTUAL_VIRTUAL_PAY_LESS;
					$ACTUAL_VOUCHER_AMOUNT      = $ACTUAL_VOUCHER_AMOUNT      + $row->ACTUAL_VOUCHER_AMOUNT;
					$ACTUAL_LOST_ITEM_PAYMENT   = $ACTUAL_LOST_ITEM_PAYMENT   + $row->ACTUAL_LOST_ITEM_PAYMENT;
					$ACTUAL_WU_ACCOUNTABILITY   = $ACTUAL_WU_ACCOUNTABILITY   + $row->ACTUAL_WU_ACCOUNTABILITY;
					$ACTUAL_OTHERS_AMOUNT       = $ACTUAL_OTHERS_AMOUNT       + $row->ACTUAL_OTHERS_AMOUNT;
					$tot_ACTUAL_TOTAL          	= $tot_ACTUAL_TOTAL          	+ $ACTUAL_TOTAL;
					$RRAK_DEDUCTION							= $RRAK_DEDUCTION							+ $row->RRAK_DEDUCTION;
					$LESS_DEPOSIT_DEDUCTION			= $LESS_DEPOSIT_DEDUCTION			+ $row->LESS_DEPOSIT_DEDUCTION;
					$OTHERS_DEDUCTION						= $OTHERS_DEDUCTION						+ $row->OTHERS_DEDUCTION;
					$VIRTUAL_PAY_LESS_DEDUCTION	= $VIRTUAL_PAY_LESS_DEDUCTION	+ $row->VIRTUAL_PAY_LESS_DEDUCTION;

				$create_time = $row->REC_TIME; /*$this->Mod_report->get_time_from_receipt($row->CDC_REC_ID);*/

				$html .= '
						<tr>
							<td> '.$no.' </td>
							<td> '.$row->STORE_CODE.' </td>
							<td> '.$row->STORE_NAME.' </td>
							<td> '.$row->SALES_DATE.' </td>
							<td> '.substr($row->CDC_BATCH_TYPE,3,5).' </td>
							<td> '.$row->CDC_BATCH_NUMBER.' </td>
							<td> '.$row->CDC_BATCH_DATE.' </td>

							<td align="right"> '.number_format($row->ACTUAL_SALES_AMOUNT, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->ACTUAL_RRAK_AMOUNT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_VOUCHER_AMOUNT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_WU_ACCOUNTABILITY, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->ACTUAL_OTHERS_AMOUNT, 0, '.', ',') .' </td>
							<td> '.$row->ACTUAL_OTHERS_DESC.' </td>
							<td align="right"> '.number_format($ACTUAL_TOTAL, 0, '.', ',') .' </td>

							<td align="right"> '.number_format($row->RRAK_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->LESS_DEPOSIT_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->OTHERS_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ',') .' </td>
							<td> </td>
							<td align="left"> '.$row->BRANCH_CODE.' - '.$row->USER_NAME.' </td>
							<td align="left"> '.$create_time.' </td>
						</tr>
				';
			}

			$html.='
					<tr>
						<td colspan="7" align="left"> Total : </td>
						<td align="right"> '.number_format($ACTUAL_SALES_AMOUNT, 0, '.', ',').' </td>
						<td align="right"> '.number_format($ACTUAL_RRAK_AMOUNT, 0, '.', ',').' </td>
						<td> </td>
						<td align="right"> '.number_format($ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_VOUCHER_AMOUNT, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ',').'</td>
						<td> </td>
						<td align="right"> '.number_format($ACTUAL_WU_ACCOUNTABILITY, 0, '.', ',').' </td>
						<td align="right">'.number_format($ACTUAL_OTHERS_AMOUNT, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($tot_ACTUAL_TOTAL, 0, '.', ',').' </td>
						<td align="right"> '.number_format($RRAK_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($LESS_DEPOSIT_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($OTHERS_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ',').' </td>
						<td>  </td>
						<td>  </td>
						<td>  </td>
					</tr>
			';

			$html.='
			</table>
			';
			/*$pdf->writeHTML($html, true, false, true, false, '');*/
			/*$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);*/

			if ($html) {
				$this->create_pdf_receipt_reg($html, $branchName);
			}

			/*ob_end_clean();
			$pdf->Output('receipt_register'.date('YmdHi').'.pdf', 'I');*/
		}

		public function print_receipt_register($from, $to, $toko1='all', $toko2='all', $branch_id, $dc_code){
			date_default_timezone_set('Asia/Jakarta');
			$this->load->library('Pdf');
			$now = date('d-m-Y');
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_report->get_cabang_session($branch_id);
			$table_body	= $this->Mod_report->getReceipt_register($from,$to,$toko1,$toko2,$branch_id,$dc_code);

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($this->session->userdata('username'));
			$pdf->SetTitle('Report Receipt Register');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Receipt Register', 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));

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

			$pdf->AddPage('L','A2');

			// set cell padding
			$pdf->setCellPaddings(1, 1, 1, 1);

			// set cell margins
			$pdf->setCellMargins(0, 0, 0, 0);

			//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->Cell(0, 0, 'Tgl Cetak : '.date('d-M-Y'), 0, 1, 'R', 0, '', 0);
			$pdf->Cell(0, 0, 'Pkl Cetak : '.date('H:i:s'), 0, 1, 'R', 0, '', 0);
			$pdf->Cell(0, 0, 'User : '.$this->session->userdata('username'), 0, 1, 'R', 0, '', 0);
			$pdf->Ln(10);

			// test Cell stretching
			$pdf->SetFont('helveticaB', '', 20, '', true);
			$pdf->Cell(0, 0, 'Cashier DC Receipt Register', 0, 1, 'C', 0, '', 0);
			$pdf->SetFont('helvetica', '', 12, '', true);
			$pdf->Cell(0, 0, 'Period : '.strtoupper(date_format(date_create($from),"d-M-Y")).' s/d '.strtoupper(date_format(date_create($to),"d-M-Y")), 0, 1, 'C', 0, '', 0);
	         
			$pdf->Ln(4);

			// Multicell test
			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(10, 9, 'No.', 1, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Store Code', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Store Name', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Sales Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(10, 9, 'Rec Type', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Batch Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Act Cash+ Penggantian', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual RRAK', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Kurset Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Kurset', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Virt Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Virtual Kurset', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Vouc Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Voucher', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Byr NBH Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Byr NBH', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(15, 9, 'Ptgjwb WU Date', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Ptgjwb WU', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Lain', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Actual Lain Desc', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Total Actual', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Potongan RRAK', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Potongan Kurset', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Potongan Lain', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Potongan Virtual', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Potongan Lain Desc', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'User Name', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Start Input Time', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'End Input Time', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 9, 'Waktu Hitung', 1, 'C', 0, 0, '', '', true);

			$pdf->SetFont('helvetica', '', 8, '', true);
			$pdf->Ln(9);

			$no=0;
			$ACTUAL_SALES_AMOUNT = 0;   		$ACTUAL_RRAK_AMOUNT = 0;      $ACTUAL_PAY_LESS_DEPOSITED = 0;
			$ACTUAL_VIRTUAL_PAY_LESS = 0; 	$ACTUAL_VOUCHER_AMOUNT = 0;  	$ACTUAL_LOST_ITEM_PAYMENT = 0;
			$ACTUAL_WU_ACCOUNTABILITY = 0;  $ACTUAL_OTHERS_AMOUNT = 0;    $tot_ACTUAL_TOTAL = 0;
			$RRAK_DEDUCTION = 0;       			$LESS_DEPOSIT_DEDUCTION = 0;  $OTHERS_DEDUCTION = 0;
			$VIRTUAL_PAY_LESS_DEDUCTION = 0;
			foreach ($table_body as $row) {
				$no++;
				$ACTUAL_TOTAL = $row->ACTUAL_SALES_AMOUNT + $row->ACTUAL_RRAK_AMOUNT+ $row->ACTUAL_PAY_LESS_DEPOSITED + $row->ACTUAL_VIRTUAL_PAY_LESS +
					$row->ACTUAL_VOUCHER_AMOUNT + $row->ACTUAL_LOST_ITEM_PAYMENT + $row->ACTUAL_WU_ACCOUNTABILITY + $row->ACTUAL_OTHERS_AMOUNT;

					$ACTUAL_SALES_AMOUNT    	= $ACTUAL_SALES_AMOUNT    		+ $row->ACTUAL_SALES_AMOUNT;
					$ACTUAL_RRAK_AMOUNT         = $ACTUAL_RRAK_AMOUNT     		+ $row->ACTUAL_RRAK_AMOUNT;
					$ACTUAL_PAY_LESS_DEPOSITED  = $ACTUAL_PAY_LESS_DEPOSITED  + $row->ACTUAL_PAY_LESS_DEPOSITED;
					$ACTUAL_VIRTUAL_PAY_LESS  	= $ACTUAL_VIRTUAL_PAY_LESS  	+ $row->ACTUAL_VIRTUAL_PAY_LESS;
					$ACTUAL_VOUCHER_AMOUNT      = $ACTUAL_VOUCHER_AMOUNT      + $row->ACTUAL_VOUCHER_AMOUNT;
					$ACTUAL_LOST_ITEM_PAYMENT   = $ACTUAL_LOST_ITEM_PAYMENT   + $row->ACTUAL_LOST_ITEM_PAYMENT;
					$ACTUAL_WU_ACCOUNTABILITY   = $ACTUAL_WU_ACCOUNTABILITY   + $row->ACTUAL_WU_ACCOUNTABILITY;
					$ACTUAL_OTHERS_AMOUNT       = $ACTUAL_OTHERS_AMOUNT       + $row->ACTUAL_OTHERS_AMOUNT;
					$tot_ACTUAL_TOTAL          	= $tot_ACTUAL_TOTAL          	+ $ACTUAL_TOTAL;
					$RRAK_DEDUCTION				= $RRAK_DEDUCTION							+ $row->RRAK_DEDUCTION;
					$LESS_DEPOSIT_DEDUCTION		= $LESS_DEPOSIT_DEDUCTION			+ $row->LESS_DEPOSIT_DEDUCTION;
					$OTHERS_DEDUCTION			= $OTHERS_DEDUCTION						+ $row->OTHERS_DEDUCTION;
					$VIRTUAL_PAY_LESS_DEDUCTION	= $VIRTUAL_PAY_LESS_DEDUCTION	+ $row->VIRTUAL_PAY_LESS_DEDUCTION;

				$create_time = $row->CREATION_DATE;
				$START_INPUT_DATE = $row->START_INPUT_TIME;
				$salesDate = $row->SALES_DATE;
				$batchDate = $row->CDC_BATCH_DATE;
				
				$salesDateTime = date("d-M-Y", strtotime($salesDate));

				$batchDateTime = date("d-M-Y", strtotime($batchDate));

				$endInputTime = $create_time;
				$endTime = date("d-M-Y H:i:s", strtotime($endInputTime));
				$endTimeH = date("H:i:s", strtotime($endInputTime));
				$endTimeS = date("s", strtotime($endInputTime));
				$endTimeI = date("i", strtotime($endInputTime));

				if($START_INPUT_DATE == null){
					$startInputTime = '';
					$startTime = '';
					$countTime=	'';
				} else {
					$startInputTime = $START_INPUT_DATE;
					$startTime = date("d-M-Y H:i:s", strtotime($startInputTime));
					$startTimeH = date("H:i:s", strtotime($startInputTime));
					$startTimeS = date("s", strtotime($startInputTime));
					$startTimeI = date("i", strtotime($startInputTime));

					$menit = abs(floor((int)(strtotime($endTimeH) - strtotime($startTimeH)) / 60)) . " menit ";
			
					if($endTimeI == $startTimeI){
						$detik=	abs(($endTimeS) - ($startTimeS)). " detik";
					}else{
						$detik=	abs(($endTimeS+60) - ($startTimeS)). " detik";
					}
					$countTime=	$menit.$detik;
				}
			
				if ($no >= 25) {
					if ($no % 25 == 0) {
						$pdf->AddPage('L','A2');
						// set cell padding
						$pdf->setCellPaddings(1, 1, 1, 1);

						// set cell margins
						$pdf->setCellMargins(0, 0, 0, 0);

						$pdf->SetFont('helveticaB', '', 8, '', true);
						$pdf->MultiCell(10, 9, 'No.', 1, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Store Code', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Store Name', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Sales Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(10, 9, 'Rec Type', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Batch Number', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Batch Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Act Cash+ Penggantian', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual RRAK', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Kurset Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Kurset', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Virt Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Virtual Kurset', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Vouc Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Voucher', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Byr NBH Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Byr NBH', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(15, 9, 'Ptgjwb WU Date', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Ptgjwb WU', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Lain', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Actual Lain Desc', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Total Actual', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Potongan RRAK', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Potongan Kurset', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Potongan Lain', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Potongan Virtual', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Potongan Lain Desc', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'User Name', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Start Input Time', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'End Input Time', 1, 'C', 0, 0, '', '', true);
						$pdf->MultiCell(20, 9, 'Waktu Hitung', 1, 'C', 0, 0, '', '', true);

						$pdf->SetFont('helvetica', '', 8, '', true);
						$pdf->Ln(9);
					}
				}

				$pdf->MultiCell(10, 14, $no, 1, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, $row->STORE_CODE, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $row->STORE_NAME, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $salesDateTime, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(10, 14, substr($row->CDC_BATCH_TYPE,3,5), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $row->CDC_BATCH_NUMBER, 1, 'C', 0, 0, '', '', true);	
				$pdf->MultiCell(20, 14, $batchDateTime, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_RRAK_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, '-', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, '-', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, '-', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_VOUCHER_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, '-', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(15, 14, '-', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_WU_ACCOUNTABILITY, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->ACTUAL_OTHERS_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $row->ACTUAL_OTHERS_DESC, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($ACTUAL_TOTAL, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->RRAK_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->LESS_DEPOSIT_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->OTHERS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, number_format($row->VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $row->BRANCH_CODE.' - '.$row->USER_NAME, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $startTime, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $endTime, 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(20, 14, $countTime, 1, 'C', 0, 0, '', '', true);
				$pdf->Ln(14);

			}

			$pdf->SetFont('helveticaB', '', 8, '', true);
			$pdf->MultiCell(115, 12, 'Total : ', 1, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_SALES_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_RRAK_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(15, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(15, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(15, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_VOUCHER_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(15, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(15, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_WU_ACCOUNTABILITY, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($ACTUAL_OTHERS_AMOUNT, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($tot_ACTUAL_TOTAL, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($RRAK_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($LESS_DEPOSIT_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($OTHERS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, number_format($VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ','), 1, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(20, 12, '', 1, 'C', 0, 0, '', '', true);
			
			ob_end_clean();
			$pdf->Output('receipt_register'.date('YmdHi').'.pdf', 'I');
		}

		public function print_receipt_register_excel($from, $to, $toko1='all', $toko2='all', $branch_id, $dc_code){
			date_default_timezone_set('Asia/Jakarta');
			$now = date('d-m-Y');
			
			$branchCode = $this->session->userdata('branch_code');
			$this->load->model('master/Mod_cdc_master_branch');
			$branchName = $this->Mod_report->get_cabang_session($branch_id);
			$table_body	= $this->Mod_report->getReceipt_register($from,$to,$toko1,$toko2,$branch_id,$dc_code);

			$cabang = 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME);
			
			$html = '
			<div align="center">
			<b>
				<h1> Cashier DC Receipt Register - '.$cabang.' </h1>
				<h2> '.$cabang.' </h2>
				Tgl Cetak : '.date("d-M-Y").' <br>
				Pkl Cetak : '.date('H:i:s').' <br>
				User : '.$this->session->userdata('username').' <br>
				Period : '.strtoupper(date_format(date_create($from),"d-M-Y")).' s/d '.strtoupper(date_format(date_create($to),"d-M-Y")).' <br>
	 			<br>
			</b>
			</div>
			';

			$html .= '
				<table border="1" align="center">
					<tr>
						<td width="2%"> No. </td>
						<td width="3%"> Store Code </td>
						<td width="9%"> Store Name </td>
						<td width="4%"> Sales Date </td>
						<td width="2%"> Rec Type </td>
						<td width="5%"> Batch Number </td>
						<td width="4%"> Batch Date </td>
						<td width="4%"> Act Cash+ Penggantian </td>
						<td width="3.5%"> Actual RRAK </td>
						<td width="2%"> Kurset Date </td>
						<td width="3.5%"> Actual Kurset </td>
						<td width="2%"> Virt Date </td>
						<td width="3.5%"> Virtual Kurset </td>
						<td width="2%"> Vouc Date  </td>
						<td width="3.5%"> Actual Voucher </td>
						<td width="2%"> Byr NBH Date </td>
						<td width="3.5%"> Actual Byr NBH </td>
						<td width="2%"> Ptgjwb WU Date </td>
						<td width="3.5%"> Actual Ptgjwb WU </td>
						<td width="3.5%"> Actual Lain </td>
						<td width="3.5%"> Actual Lain Desc </td>
						<td width="4%"> Total Actual </td>
						<td width="3.5%"> Potongan RRAK </td>
						<td width="3.5%"> Potongan Kurset </td>
						<td width="3.5%"> Potongan Lain </td>
						<td width="3.5%"> Potongan Virtual </td>
						<td width="3%"> Potongan Lain Desc </td>
						<td width="3%"> User Name </td>
						<td width="3%"> Start Input Time </td>
						<td width="3%"> End Input Time </td>
						<td width="3%"> Waktu Input </td>
					</tr>
			';

			$no=0;
			$ACTUAL_SALES_AMOUNT = 0;   		$ACTUAL_RRAK_AMOUNT = 0;      $ACTUAL_PAY_LESS_DEPOSITED = 0;
			$ACTUAL_VIRTUAL_PAY_LESS = 0; 	$ACTUAL_VOUCHER_AMOUNT = 0;  	$ACTUAL_LOST_ITEM_PAYMENT = 0;
			$ACTUAL_WU_ACCOUNTABILITY = 0;  $ACTUAL_OTHERS_AMOUNT = 0;    $tot_ACTUAL_TOTAL = 0;
			$RRAK_DEDUCTION = 0;       			$LESS_DEPOSIT_DEDUCTION = 0;  $OTHERS_DEDUCTION = 0;
			$VIRTUAL_PAY_LESS_DEDUCTION = 0;
			foreach ($table_body as $row) {
				$no++;
				$ACTUAL_TOTAL = $row->ACTUAL_SALES_AMOUNT + $row->ACTUAL_RRAK_AMOUNT+ $row->ACTUAL_PAY_LESS_DEPOSITED + $row->ACTUAL_VIRTUAL_PAY_LESS +
					$row->ACTUAL_VOUCHER_AMOUNT + $row->ACTUAL_LOST_ITEM_PAYMENT + $row->ACTUAL_WU_ACCOUNTABILITY + $row->ACTUAL_OTHERS_AMOUNT;

					$ACTUAL_SALES_AMOUNT    		= $ACTUAL_SALES_AMOUNT    		+ $row->ACTUAL_SALES_AMOUNT;
					$ACTUAL_RRAK_AMOUNT         = $ACTUAL_RRAK_AMOUNT     		+ $row->ACTUAL_RRAK_AMOUNT;
					$ACTUAL_PAY_LESS_DEPOSITED  = $ACTUAL_PAY_LESS_DEPOSITED  + $row->ACTUAL_PAY_LESS_DEPOSITED;
					$ACTUAL_VIRTUAL_PAY_LESS  	= $ACTUAL_VIRTUAL_PAY_LESS  	+ $row->ACTUAL_VIRTUAL_PAY_LESS;
					$ACTUAL_VOUCHER_AMOUNT      = $ACTUAL_VOUCHER_AMOUNT      + $row->ACTUAL_VOUCHER_AMOUNT;
					$ACTUAL_LOST_ITEM_PAYMENT   = $ACTUAL_LOST_ITEM_PAYMENT   + $row->ACTUAL_LOST_ITEM_PAYMENT;
					$ACTUAL_WU_ACCOUNTABILITY   = $ACTUAL_WU_ACCOUNTABILITY   + $row->ACTUAL_WU_ACCOUNTABILITY;
					$ACTUAL_OTHERS_AMOUNT       = $ACTUAL_OTHERS_AMOUNT       + $row->ACTUAL_OTHERS_AMOUNT;
					$tot_ACTUAL_TOTAL          	= $tot_ACTUAL_TOTAL          	+ $ACTUAL_TOTAL;
					$RRAK_DEDUCTION							= $RRAK_DEDUCTION							+ $row->RRAK_DEDUCTION;
					$LESS_DEPOSIT_DEDUCTION			= $LESS_DEPOSIT_DEDUCTION			+ $row->LESS_DEPOSIT_DEDUCTION;
					$OTHERS_DEDUCTION						= $OTHERS_DEDUCTION						+ $row->OTHERS_DEDUCTION;
					$VIRTUAL_PAY_LESS_DEDUCTION	= $VIRTUAL_PAY_LESS_DEDUCTION	+ $row->VIRTUAL_PAY_LESS_DEDUCTION;

				$create_time = $row->REC_TIME; /*$this->Mod_report->get_time_from_receipt($row->CDC_REC_ID);*/
				//$START_INPUT_DATE = $row->START_INPUT_TIME;
				$create_time2 = $row->CREATION_DATE;

				$START_INPUT_DATE = $row->START_INPUT_TIME;
				$salesDate = $row->SALES_DATE;
				$batchDate = $row->CDC_BATCH_DATE;
				
				$salesDateTime = date("d-M-Y", strtotime($salesDate));

				$batchDateTime = date("d-M-Y", strtotime($batchDate));

				

				
				$endInputTime = $create_time2;
				$endTime = date("d-M-Y H:i:s", strtotime($endInputTime));
				$endTimeH = date("H:i:s", strtotime($endInputTime));
				$endTimeS = date("s", strtotime($endInputTime));
				$endTimeI = date("i", strtotime($endInputTime));

				if($START_INPUT_DATE == null){
					$startInputTime = '';
					$startTime = '';
					$countTime=	'';
				} else {
					$startInputTime = $START_INPUT_DATE;
					$startTime = date("d-M-Y H:i:s", strtotime($startInputTime));
					$startTimeH = date("H:i:s", strtotime($startInputTime));
					$startTimeS = date("s", strtotime($startInputTime));
					$startTimeI = date("i", strtotime($startInputTime));

					$menit = abs(floor((int)(strtotime($endTimeH) - strtotime($startTimeH)) / 60)) . " menit ";
			
					if($endTimeI == $startTimeI){
						$detik=	abs(($endTimeS) - ($startTimeS)). " detik";
					}else{
						$detik=	abs(($endTimeS+60) - ($startTimeS)). " detik";
					}
					$countTime=	$menit.$detik;
				}
				
				$html .= '
						<tr>
							<td> '.$no.' </td>
							<td> '.$row->STORE_CODE.' </td>
							<td> '.$row->STORE_NAME.' </td>
							<td> '.$salesDateTime.' </td>
							<td> '.substr($row->CDC_BATCH_TYPE,3,5).' </td>
							<td> '.$row->CDC_BATCH_NUMBER.' </td>
							<td> '.$batchDateTime.' </td>

							<td align="right"> '.number_format($row->ACTUAL_SALES_AMOUNT, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->ACTUAL_RRAK_AMOUNT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_VOUCHER_AMOUNT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ',') .' </td>
							<td> - </td>
							<td align="right"> '.number_format($row->ACTUAL_WU_ACCOUNTABILITY, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->ACTUAL_OTHERS_AMOUNT, 0, '.', ',') .' </td>
							<td> '.$row->ACTUAL_OTHERS_DESC.' </td>
							<td align="right"> '.number_format($ACTUAL_TOTAL, 0, '.', ',') .' </td>

							<td align="right"> '.number_format($row->RRAK_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->LESS_DEPOSIT_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->OTHERS_DEDUCTION, 0, '.', ',') .' </td>
							<td align="right"> '.number_format($row->VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ',') .' </td>
							<td> </td>
							<td align="left"> '.$row->BRANCH_CODE.' - '.$row->USER_NAME.' </td>
							<td align="left"> '.$startTime.' </td>
							<td align="left"> '.$endTime.' </td>
							<td align="left"> '.$countTime.' </td>
							
						</tr>
				';
			}

			$html.='
					<tr>
						<td colspan="7" align="left"> Total : </td>
						<td align="right"> '.number_format($ACTUAL_SALES_AMOUNT, 0, '.', ',').' </td>
						<td align="right"> '.number_format($ACTUAL_RRAK_AMOUNT, 0, '.', ',').' </td>
						<td> </td>
						<td align="right"> '.number_format($ACTUAL_PAY_LESS_DEPOSITED, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_VIRTUAL_PAY_LESS, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_VOUCHER_AMOUNT, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($ACTUAL_LOST_ITEM_PAYMENT, 0, '.', ',').'</td>
						<td> </td>
						<td align="right"> '.number_format($ACTUAL_WU_ACCOUNTABILITY, 0, '.', ',').' </td>
						<td align="right">'.number_format($ACTUAL_OTHERS_AMOUNT, 0, '.', ',').' </td>
						<td>  </td>
						<td align="right"> '.number_format($tot_ACTUAL_TOTAL, 0, '.', ',').' </td>
						<td align="right"> '.number_format($RRAK_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($LESS_DEPOSIT_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($OTHERS_DEDUCTION, 0, '.', ',').' </td>
						<td align="right"> '.number_format($VIRTUAL_PAY_LESS_DEDUCTION, 0, '.', ',').' </td>
						<td>  </td>
						<td>  </td>
						<td>  </td>
						<td>  </td>
						<td>  </td>
					</tr>
			';

			$html.='
			</table>
			';

			if ($html) {
				$data['html'] = $html;
				$data['file_name'] = 'RECEIPT_REGISTER';
				$this->load->view('view_report_excel', $data, FALSE);
			}
		}

		public function create_pdf_receipt_reg($html, $branch_name)
		{
			$this->load->library('Pdf');
			$now = date('d-m-Y');
			$branchCode = $this->session->userdata('branch_code');

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($this->session->userdata('username'));
			$pdf->SetTitle('Report Receipt Register');
			$pdf->SetSubject('');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Receipt Register', 'Branch :'.trim($branchCode).' - '.trim($branch_name));

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

			$pdf->SetFont('helvetica', '', 8, '', true);

			$pdf->AddPage('L','A2');

			/*$pdf->writeHTML($html, true, false, true, false, '');*/
			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

			ob_end_clean();
			$pdf->Output('receipt_register'.date('YmdHi').'.pdf', 'D');
		}


	function get_sales_tgl_am(){
		$result = $this->Mod_report->get_sales_tgl_am();
		echo json_encode($result);
	}

	public function print_sales_tgl_am($from, $am='all', $status='all'){
		$this->load->library('Pdf');
		$now = date('d-m-Y');
		$branchCode = $this->session->userdata('branch_code');
		$this->load->model('master/Mod_cdc_master_branch');
		$branchName = $this->Mod_cdc_master_branch->getBranchName($branchCode);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->session->userdata('username'));
		$pdf->SetTitle('Report Monitoring Sales Toko per Tanggal per Am AS ');
		$pdf->SetSubject('');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Report Monitoring Sales Toko per Tanggal per Am AS', 'Branch :'.trim($branchCode).' - '.trim($branchName));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(5, 15, 5);
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

		$pdf->SetFont('helveticaB', '', 14, '', true);

		$pdf->AddPage('L','A4');

		$pdf->Cell(0, 0, 'Absensi Sales Toko per Tanggal', 0, 1, 'C', 0, '', 0);
		$pdf->SetFont('helveticaB', '', 8, '', true);
		$pdf->Cell(0, 0, 'Tanggal Proses : '.strtoupper(date_format(date_create($from),"d-M-Y")).'', 0, 1, 'C', 0, '', 0);
		$pdf->Cell(0, 0, 'Group By Area Manager : '.trim($am).'', 0, 1, 'C', 0, '', 0);
		$pdf->Cell(0, 0, 'Status Pending Sales : '.$status.'', 0, 1, 'C', 0, '', 0);
		$pdf->Ln();

		$start =1;
		
		$tgl_rem = date_format(date_create($from),"d");

		if (intval($tgl_rem) == 1) {
			$month = intval(date('n'))-1;
			if ($month == 0) {
				$month = 12;
				$year = intval(date('Y'))-1;
			}else $year = date('Y');
			$tgl = cal_days_in_month(CAL_GREGORIAN, $month, $year)+1;
		}else $tgl = date_format(date_create($from),"d");
		$lebar = 202/($tgl-1);

		$data_am = $this->Mod_report->getHeader_am($am);
		$table_body_toko	= $this->Mod_report->getReport_sales_tgl_am_toko($from, $am, $status);
		$pdf->Cell(0, 0, $data_am.'  ('.trim($am).')', 1, 1, 'L', 0, '', 0);

		$pdf->MultiCell(50, 8, '', 1, 'C', 0, 0, '', '', true);
		for($start=1; $start<$tgl; $start++){
			$pdf->MultiCell($lebar, 8, $start, 1, 'C', 0, 0, '', '', true);
		}

		$pdf->MultiCell(20, 8, 'Last Date Shiping', 1, 'C', 0, 0, '', '', true);
		$pdf->MultiCell(15, 8, 'Ttl. Pend', 1, 'C', 0, 0, '', '', true);
		$pdf->Ln();

		$counter = 0;
		$flag = 0;

		foreach ($table_body_toko as $row) {
			$table_body_shiping  = $this->Mod_report->getReport_sales_tgl_am_shiping($row->STORE_CODE,$from);

			if($table_body_shiping == ''){
				$table_body_shiping = '0001-01-01';
			}

			$counter++;
			if ($flag == 0) {
				if ($counter % 17 == 0) {
					$pdf->AddPage('L','A4');
					$pdf->Ln();
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(50, 8, '', 1, 'C', 0, 0, '', '', true);
					for($start=1; $start<$tgl; $start++){
						$pdf->MultiCell($lebar, 8, $start, 1, 'C', 0, 0, '', '', true);
					}
					$pdf->MultiCell(20, 8, 'Last Date Shiping', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(15, 8, 'Ttl. Pend', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
					$counter = 0;
					$flag = 1;
				}
			}else{
				if ($counter % 19 == 0) {
					$pdf->AddPage('L','A4');
					$pdf->Ln();
					$pdf->SetFont('helveticaB', '', 8, '', true);
					$pdf->MultiCell(50, 8, '', 1, 'C', 0, 0, '', '', true);
					for($start=1; $start<$tgl; $start++){
						$pdf->MultiCell($lebar, 8, $start, 1, 'C', 0, 0, '', '', true);
					}
					$pdf->MultiCell(20, 8, 'Last Date Shiping', 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(15, 8, 'Ttl. Pend', 1, 'C', 0, 0, '', '', true);
					$pdf->Ln();
				}
			}
			$pdf->SetFont('helvetica', '', 7, '', true);
			$pdf->MultiCell(10, 8, $row->AS_SHORT, 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(10, 8, $row->STORE_CODE, 1, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(30, 8, $row->STORE_NAME, 1, 'C', 0, 0, '', '', true);

			$tot_pending=0;
			$start_body=1;

			for($start_body=1; $start_body<$tgl; $start_body++){
				if (intval($tgl_rem) == 1) {
					$month = intval(date('n'))-1;
					if ($month == 0) {
						$month = 12;
						$years = intval(date('Y'))-1;
						$tgl_cek = strval($years)."-".$month."-".$start_body;
					}else{
						$tgl_cek = date_format(date_create($from),"Y")."-".$month."-".$start_body;
					}
				}else $tgl_cek = date_format(date_create($from),"Y-m")."-".$start_body;
				$table_body_cek	= $this->Mod_report->getReport_sales_tgl_am_cek($row->STORE_CODE,$tgl_cek);

				if($table_body_cek > 0){
					$pdf->MultiCell($lebar, 8, 'V', 1, 'C', 0, 0, '', '', true);
				}else{
					//$hasil_cek = " ";
					if(date_format(date_create($table_body_shiping),"d") > $start_body){
						$pdf->SetFillColor(255, 0, 0);
						$pdf->MultiCell($lebar, 8, '', 1, 'C', 1, 0, '', '', true);
						$tot_pending++;
					}else{
						$pdf->MultiCell($lebar, 8, '', 1, 'C', 0, 0, '', '', true);
					}
				}
			}

			if($table_body_shiping != '0001-01-01'){
				$pdf->MultiCell(20, 8, date_format(date_create($table_body_shiping),"d-M-y"), 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(15, 8, $tot_pending, 1, 'C', 0, 0, '', '', true);
			}else{
				$pdf->MultiCell(20, 8, '', 1, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(15, 8, '', 1, 'C', 0, 0, '', '', true);
			}

			$pdf->Ln();
		}

		ob_end_clean();
		$pdf->Output('sales_tgl_am_as'.date('YmdHi').'.pdf', 'I');
	}


	public function get_toko_monitoring_voucher($branch_id, $dc_code){
		$result = $this->Mod_report->get_toko_monitoring_voucher($branch_id, $dc_code);
		echo json_encode($result);
	}
	// END IWAN CODE //


	public function print_monitoring_voucher($num='all',$from='all',$to='all',$branch_id,$dc_code){
		$this->load->library('Pdf');
		$now = date('d-m-Y');
		$branchCode = $this->session->userdata('branch_code');
		$this->load->model('master/Mod_cdc_master_branch');
		$branchName = $this->Mod_report->get_cabang_session($branch_id);

	//var_dump($receipts);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->session->userdata('username'));
		$pdf->SetTitle('Monitoring Voucher');
		$pdf->SetSubject('');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monitoring Voucher', 'Branch :'.trim($branchName[0]->BRANCH_CODE).' - '.trim($branchName[0]->BRANCH_NAME));
		$pdf->setFooterData('testing1', 'coba1');

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 18, PDF_MARGIN_RIGHT);
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
		$pdf->AddPage('P','A4');

		$html = '
		<div align="center">
		<b>
			<h1> Monitoring Voucher </h1> <br>
			Number Batch	: '.$num.'<br>
			Tanggal Transaksi : '.$from.' s/d '.$to.' <br><br>
		</b>
		</div>
		';

		$table_header = $this->Mod_report->getVoucherHeader($num,$from,$to,$branch_id,$dc_code);
		//$table_body = $this->Mod_report->getVoucherTable($num,$from,$to)
		$grand_total = 0;
		foreach ($table_header as $header) {
			$html.=	'
			<table border="1px" cellpadding="3px" align="center">
			 		<tr>
						<td colspan="6" align="left"><b> '.$header->STORE_CODE.' - '.$header->STORE_NAME.' </b></td>
					</tr>
							';
					$table_body	= $this->Mod_report->getVoucherBody($header->STORE_ID,$num);
					$html .= '
					<tr>
						<td width="5%"> No. </td>
						<td width="18%"> Batch Number </td>
						<td width="18%"> Batch Date </td>
						<td width="18%"> Sales Date </td>
						<td width="18%"> Voucher Num </td>
						<td width="23%"> Voucher Amount </td>
					</tr>
					'; $no = 0; $totalVoucher = 0;
					foreach ($table_body as $row) {
						$no = $no+1;
						$totalVoucher = $totalVoucher+$row->TRX_VOUCHER_AMOUNT;
						$html .= '
						<tr>
							<td> '.$no.' </td>
							<td> '.$row->CDC_BATCH_NUMBER.' </td>
							<td> '.$row->CDC_BATCH_DATE.' </td>
							<td> '.$row->SALES_DATE.' </td>
							<td> '.$row->VOUCHER_NUM.' </td>
							<td align="right"> '.number_format($row->TRX_VOUCHER_AMOUNT, 0, '.', ',').' </td>
						</tr>
						';
					}

					$grand_total = $grand_total+$totalVoucher;
					$html.='
						<tr>
							<td colspan="4" align="right"> <b> Jumlah Voucher  </b> </td>
							<td> '.$no.' </td>
							<td> </td>
						</tr>
						<tr>
							<td colspan="5" align="right"> <b> Sub Total </b> </td>
							<td align="right"> '.number_format($totalVoucher, 0, '.', ',').' </td>
						</tr>
					</table>
					<br><br><br><br>
					';
		}
		$html .='
			<div align="right">
				<h3> <b> Total Voucher : '.number_format($grand_total, 0, '.', ',').' </b> </h3>
			</div>
		';


		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		ob_end_clean();
		$pdf->Output('monitoring_voucher_'.date('YmdHi').'.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+

	}

	public function get_batch_num($dc_code, $branch_id){
		$result = $this->Mod_report->get_batch_num($dc_code, $branch_id);
		echo json_encode($result);
	}

	public function print_summary_collect($date_rep,$start,$end, $branch_id, $dc_code)
	{
		$date = date_create($date_rep);
		$cabang = $this->Mod_report->get_cabang_session($branch_id);
		$data_summary = $this->Mod_report->get_sum_collect($date_rep,$start,$end,$branch_id,$dc_code);

		$total_frc = $data_summary['tunai'][0]->frc + $data_summary['slip_bank'][0]->frc - $data_summary['giro'][0]->frc;
		$total_crm = $data_summary['tunai'][0]->crm + $data_summary['slip_bank'][0]->crm - $data_summary['giro'][0]->crm;
		$total_reg = $data_summary['tunai'][0]->reg + $data_summary['slip_bank'][0]->reg - $data_summary['giro'][0]->reg;
		$total_titipan = $data_summary['tunai'][0]->titipan + $data_summary['slip_bank'][0]->titipan - $data_summary['giro'][0]->titipan;
		// create new PDF document
      	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->session->userdata('username'));
		$pdf->SetTitle('Print Summary Collect');
		$pdf->SetSubject('Summary Collect');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'SUMMARY COLLECT PERIODE '.strtoupper(date_format($date,"M - Y")),'IDM Cabang '.$cabang[0]->BRANCH_NAME, array(0,0,0), array(0,0,0));
		$pdf->setFooterData(array(0,0,0), array(0,0,0));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
		$pdf->AddPage('L','A4');

		$html = '<p style="font-size: 10px;">Tanggal Hitung : '.strtoupper(date_format($date,"d-M-y")).', Shift '.$start.' - '.$end.'</p>';

		$html .= '
		<table cellpadding="2px">
		<tr>
		<td width="60%">
		<table width="100%" cellpadding="2px">
		  <tr align="center">
		    <th border="1"></th>
		    <th border="1"><b>Tunai</b></th>
		    <th border="1"><b>Slip Bank</b></th>
		    <th border="1"><b>Giro</b></th>
		    <th border="1"><b>Total</b></th>
		  </tr>';

		$html .='
		<tr>
		    <td align="center" border="1"><b>FRC</b></td>
		    <td align="right" border="1">'.number_format($data_summary['tunai'][0]->frc, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['slip_bank'][0]->frc, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['giro'][0]->frc, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($total_frc, 0, '.', ',').'</td>
		</tr>
		<tr>
		    <td align="center" border="1"><b>CRM</b></td>
		    <td align="right" border="1">'.number_format($data_summary['tunai'][0]->crm, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['slip_bank'][0]->crm, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['giro'][0]->crm, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($total_crm, 0, '.', ',').'</td>
		</tr>
		<tr>
		    <td align="center" border="1"><b>REG</b></td>
		    <td align="right" border="1">'.number_format($data_summary['tunai'][0]->reg, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['slip_bank'][0]->reg, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['giro'][0]->reg, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($total_reg, 0, '.', ',').'</td>
		</tr>
		<tr>
		    <td align="center" border="1"><b>TITIPAN</b></td>
		    <td align="right" border="1">'.number_format($data_summary['tunai'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['slip_bank'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['giro'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($total_titipan, 0, '.', ',').'</td>
		</tr>
		<tr>
		    <td align="center" border="1"><b>GAB</b></td>
		    <td align="right" border="1" style="background-color: #bbb;">'.number_format($data_summary['tunai'][0]->frc+$data_summary['tunai'][0]->crm+$data_summary['tunai'][0]->reg+$data_summary['tunai'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1" style="background-color: #bbb;">'.number_format($data_summary['slip_bank'][0]->frc+$data_summary['slip_bank'][0]->crm+$data_summary['slip_bank'][0]->reg+$data_summary['slip_bank'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($data_summary['giro'][0]->frc+$data_summary['giro'][0]->crm+$data_summary['giro'][0]->reg+$data_summary['giro'][0]->titipan, 0, '.', ',').'</td>
		    <td align="right" border="1">'.number_format($total_frc+$total_crm+$total_reg+$total_titipan, 0, '.', ',').'</td>
		</tr>
		<tr>
		    <td align="center"></td>
		    <td align="right"></td>
		    <td align="right" border="1" style="background-color: #bbb;">'.number_format($total_frc+$total_crm+$total_reg+$total_titipan, 0, '.', ',').'</td>
		    <td align="right"></td>
		    <td align="right"></td>
		</tr>
		<tr>
		    <td align="center"></td>
		    <td align="right"></td>
		    <td align="right"></td>
		    <td align="right"></td>
		    <td align="right"></td>
		</tr>
		</table>
		</td>
		<td width="10%"></td>
		<td rowspan="2" width="45%">
		<table width="50%" cellpadding="2px">
			<tr>
				<td align="center"></td>
				<td align="center"><b>TITIPAN</b></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center" border="1"><b>Toko</b></td>
				<td align="center" border="1"><b>Jumlah</b></td>
				<td align="center" border="1"><b>Kasir</b></td>
			</tr>';

		$total_titipan_detail = 0;

		foreach ($data_summary['sum_titipan'] as $key) {
			$html .= '
			<tr>
				<td align="center" border="1">'.$key->toko.'</td>
				<td align="right" border="1">'.number_format($key->titipan, 0, '.', ',').'</td>
				<td align="center" border="1">'.$key->username.'</td>
			</tr>';
			$total_titipan_detail = $total_titipan_detail + $key->titipan;
		}

		$html .= '
			<tr>
				<td align="center" border="1"><b>Total :</b></td>
				<td align="right" border="1">'.number_format($total_titipan_detail, 0, '.', ',').'</td>
				<td align="right" border="1"></td>
			</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td>
		<table width="100%" cellpadding="2px" border="1">
		  <tr>
		    <td align="center" width="15%"><b>Kasir</b></td>
		    <td align="center" width="15%"><b>Batch Number</b></td>
		    <td align="center" width="10%"><b>Qty Sales</b></td>
		    <td align="center" width="15%"><b>Nilai Sales</b></td>
		    <td align="center" width="15%"><b>Titipan</b></td>
		    <td align="center" width="15%"><b>Giro</b></td>
		    <td align="center" width="15%"><b>Total</b></td>
		  </tr>';

		$total_tunai_setor = $total_frc+$total_crm+$total_reg+$total_titipan;

		$total_qty = 0;
		$total_sales = 0;
		$total_titipan = 0;
		$total_giro = 0;
		$total_total = 0;

		foreach ($data_summary['detail_batch'] as $key) {
			$total = $key->nilai_sales + $key->titipan - $key->giro;
			$total_qty = $total_qty + $key->qty;
			$total_sales = $total_sales + $key->nilai_sales;
			$total_titipan = $total_titipan + $key->titipan;
			$total_giro = $total_giro + $key->giro;
			$total_total = $total_total + $total;
			$html .= '
			<tr>
			    <td align="center" width="15%">'.$key->username.'</td>
			    <td align="center" width="15%">'.$key->batchnum.'</td>
			    <td align="center" width="10%">'.$key->qty.'</td>
			    <td align="right" width="15%">'.number_format($key->nilai_sales, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($key->titipan, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($key->giro, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($total, 0, '.', ',').'</td>
			</tr>';
		}

		$html .= '
			<tr>
			    <td align="center" width="30%"><b>Total :</b></td>
			    <td align="center" width="10%">'.$total_qty.'</td>
			    <td align="right" width="15%">'.number_format($total_sales, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($total_titipan, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($total_giro, 0, '.', ',').'</td>
			    <td align="right" width="15%">'.number_format($total_total, 0, '.', ',').'</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		<div></div>
		<table width="30%" cellpadding="2px" align = "right">
		<tr>
			<td align="center"></td>
			<td align="center"><b>GIRO</b></td>
			<td align="center"></td>
		</tr>
		<tr>
			<td align="center" border="1"><b>No Giro</b></td>
			<td align="center" border="1"><b>Jumlah</b></td>
			<td align="center" border="1"><b>Kasir</b></td>
		</tr>';

		$total_giro = 0;

		foreach ($data_summary['detail_giro'] as $key) {
			$total_giro = $total_giro + $key->jml;
			$html .='
			<tr>
			    <td align="center" border="1">'.$key->no_giro.'</td>
			    <td align="right" border="1">'.number_format($key->jml, 0, '.', ',').'</td>
			    <td align="center" border="1">'.$key->kasir.'</td>
			</tr>';
		}

		$html .= '
			<tr>
			    <td align="center" border="1"><b>Total :</b></td>
			    <td align="right" border="1">'.number_format($total_giro, 0, '.', ',').'</td>
			    <td align="center" border="1"></td>
			</tr>
		</table>
		<div></div>
		<table width="30%" cellpadding="2px">
			<tr>
				<td align="left" width="80%"><b>TOTAL TUNAI SETOR KE VENDOR</b></td>
			    <td align="center" width="10%"></td>
			    <td align="center" width="5%"></td>
			    <td align="center" width="5%"></td>
			</tr>
			<tr>
				<td border="1" align="center" width="25%"><b>Tunai</b></td>
			    <td border="1" align="center" width="25%"><b>Slip Bank</b></td>
			    <td border="1" align="center" width="25%"><b>Giro</b></td>
			    <td border="1" align="center" width="25%"><b>Total</b></td>
			</tr>
			<tr>
				<td border="1" align="right" width="25%">'.number_format($data_summary['tunai'][0]->frc+$data_summary['tunai'][0]->crm+$data_summary['tunai'][0]->reg+$data_summary['tunai'][0]->titipan, 0, '.', ',').'</td>
			    <td border="1" align="right" width="25%">'.number_format($data_summary['slip_bank'][0]->frc+$data_summary['slip_bank'][0]->crm+$data_summary['slip_bank'][0]->reg+$data_summary['slip_bank'][0]->titipan, 0, '.', ',').'</td>
			    <td border="1" align="right" width="25%">'.number_format($data_summary['giro'][0]->frc+$data_summary['giro'][0]->crm+$data_summary['giro'][0]->reg+$data_summary['giro'][0]->titipan, 0, '.', ',').'</td>
			    <td border="1" align="right" width="25%">'.number_format($total_tunai_setor, 0, '.', ',').'</td>
			</tr>
		</table>';

		/*$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);*/
		$pdf->writeHTML($html, true, false, true, false, '');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		ob_end_clean();
		$pdf->Output('report_summary_collect_'.date('YmdHi').'.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+
	}

	public function get_periode_tren_collect()
	{
		$data[] = array();
		$data[] = array();
		$j = 0;
		for ($i=date('m')-1; $i <= date('m'); $i++) {
			$date = new DateTime($i.'/1/'.date('Y'));
			$data[$j]['val'] = strtoupper(date_format($date, 'Y-m-d'));
			$data[$j]['text'] = strtoupper(date_format($date, 'M - Y'));
			$j++;
		}
		echo json_encode($data);
	}

	public function print_trend_collection($date_tren)
	{
		//ini_set('memory_limit', '256M');
		$date = date_create($date_tren);
		$cabang = $this->Mod_report->get_cabang_session($this->session->userdata('branch_id'));
		$data = $this->Mod_report->get_data_tren_collection($date_tren);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->session->userdata('username'));
		$pdf->SetTitle('Print Trend Collection');
		$pdf->SetSubject('Trend Collection');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Laporan Trend Collection','Periode : '.strtoupper(date_format($date,"M - Y")), array(0,0,0), array(0,0,0));
		$pdf->setFooterData(array(0,0,0), array(0,0,0));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, '22', PDF_MARGIN_RIGHT);
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
		$pdf->SetFont('helvetica', '', 7, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage('L','A3');

		$html = '
		<p><b>Cabang : '.$cabang[0]->BRANCH_CODE.' - '.$cabang[0]->BRANCH_NAME.'<b></p>
		<br>
		<br>
		<table cellpadding="2px">
		  <tr>
		    <th align="center" border="1" width="2%"><b>No.</b></th>
		    <th align="center" border="1"><b>AM</b></th>
		    <th align="center" border="1"><b>AS</b></th>
		    <th align="center" border="1" width="4%"><b>Store</b></th>
		    <th align="center" border="1" width="8%"><b>Store</b></th>
		    <th align="center" border="1" width="4%"><b>Max</b></th>';


		if (date_format($date,"M") != date('M')) {
			for ($i=1; $i <= intval(date("t", strtotime($date_tren))) ; $i++) {
				$html .= '<th align="center" border="1" width="2.5%"><b>'.$i.'</b></th>';
			}
		}else{
			for ($i=1; $i <= intval(date('d')) ; $i++) {
				$html .= '<th align="center" border="1" width="2.5%"><b>'.$i.'</b></th>';
			}
		}

		$html .= '</tr>';
		$no = 1;

		foreach ($data as $key) {
			$html .= '
			<tr>
			    <td align="center" border="1" width="2%">'.$no.'</td>
			    <td align="center" border="1">'.$key->nama_am.'</td>
			    <td align="center" border="1">'.$key->nama_as.'</td>
			    <td align="center" border="1" width="4%">'.$key->kode_toko.'</td>
			    <td align="center" border="1" width="8%">'.$key->nama_toko.'</td>
			    <td align="center" border="1" width="4%">'.$key->maks.'</td>';
		    
			if (date_format($date,"m") != date('m')) {
				for ($i=1; $i <= intval(date("t", strtotime($date_tren))) ; $i++) {
					$tgl = $i < 10 ? '0'.$i : $i;
					if ($this->Mod_report->cek_sales_pertanggal($key->kode_toko,$tgl,date_format($date,"m Y")) != 0) {
						$html .= '<td align="center" border="1" width="2.5%">SETOR</td>';
					}
					else{
						$html .= '<td align="center" border="1" width="2.5%" style="background-color: #bbb;">PDG</td>';
					}
				}
			}else{
				for ($i=1; $i <= intval(date('d')) ; $i++) {
					$tgl = $i < 10 ? '0'.$i : $i;
					if ($this->Mod_report->cek_sales_pertanggal($key->kode_toko,$tgl,date_format($date,"m Y")) != 0) {
						$html .= '<td align="center" border="1" width="2.5%">SETOR</td>';
					}
					else{
						$html .= '<td align="center" border="1" width="2.5%" style="background-color: #bbb;">PDG</td>';
					}
				}
			}
			$html .= '
			</tr>';
			$no++;
		}

		$html .= '
		</table>';

		/*$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);*/
		$pdf->writeHTML($html, true, false, true, false, '');

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		ob_end_clean();
		$pdf->Output('report_trend_collection_'.date('YmdHi').'.pdf', 'I');
	}

	public function sync_to_oracle()
	{
		$local_path = 'D:/Gopi/';
		$ftp_server = "192.168.2.129";
		$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to ".$ftp_server);

		$data_stj = $this->Mod_report->create_data_sync('STJ');
		$data_stn = $this->Mod_report->create_data_sync('STN');

		$file_name_pnb = 'IDMCDCPNB'.date('dmYHis').'.'.$this->session->userdata('branch_code');
		$file_name_pgr = 'IDMCDCPGR'.date('dmYHis').'.'.$this->session->userdata('branch_code');
		$file_name_vcr = 'IDMCDCVCR'.date('dmYHis').'.'.$this->session->userdata('branch_code');
		$this->Mod_report->create_header_dll($local_path,$file_name_pnb,$file_name_pgr,$file_name_vcr);

		if ($data_stj) {
			$file_name_stj = 'IDMCDCSTJ'.date('dmYHis').'.'.$this->session->userdata('branch_code');
			$this->Mod_report->create_header_file($local_path,$file_name_stj);

			$fp = fopen($local_path.$file_name_stj, 'a');
			$fp_pnb = fopen($local_path.$file_name_pnb, 'a');
			$fp_pgr = fopen($local_path.$file_name_pgr, 'a');
			$fp_vcr = fopen($local_path.$file_name_vcr, 'a');

			foreach ($data_stj as $key) {
				fwrite($fp, $key->deposit_id.','.$key->deposit_num.','.$key->deposit_date.','.$key->mutation_date.','.$key->deposit_status.','.$key->branch_code.','.$key->bank_name.','.$key->batch_id.','.$key->batch_number.','.$key->batch_type.','.$key->batch_date.','.$key->batch_status.','.$key->description.','.$key->reff_num.','.$key->rec_id.','.$key->store_code.','.$key->sales_date.','.$key->status.','.$key->actual_sales_amount.','.$key->actual_rrak_amount.','.$key->actual_pay_less_deposited.','.$key->actual_voucher_amount.','.$key->actual_lost_item_payment.','.$key->actual_wu_accountability.','.$key->actual_others_amount.','.$key->actual_others_desc.','.$key->rrak_deduction.','.$key->less_deposit_deduction.','.$key->others_deduction.','.$key->others_desc.','.$key->actual_virtual_pay_less.','.$key->actual_sales_flag.','.$key->virtual_pay_less_deduction."\n");

				$this->Mod_report->update_status_deposit_batch($key->deposit_id,$key->batch_id);

				$data_pnb = $this->Mod_report->create_data_pnb($key->rec_id);
				$data_pgr = $this->Mod_report->create_data_pgr($key->rec_id);
				$data_vcr = $this->Mod_report->create_data_vcr($key->rec_id);

				if ($data_pnb) {
					foreach ($data_pnb as $key_pnb) {
						fwrite($fp_pnb, $key_pnb->TRX_DETAIL_ID.','.$key_pnb->TRX_CDC_REC_ID.','.$key_pnb->trx_plus_name.','.$key_pnb->trx_detail_date.','.$key_pnb->trx_detail_desc.','.$key_pnb->TRX_DET_AMOUNT."\n");
					}
				}

				if ($data_pgr) {
					foreach ($data_pgr as $key_pgr) {
						fwrite($fp_pgr, $key_pgr->TRX_DETAIL_MINUS_ID.','.$key_pgr->TRX_CDC_REC_ID.','.$key_pgr->trx_minus_name.','.$key_pgr->trx_minus_date.','.$key_pgr->trx_minus_desc.','.$key_pgr->TRX_MINUS_AMOUNT."\n");
					}
				}

				if ($data_vcr) {
					foreach ($data_vcr as $key_vcr) {
						fwrite($fp_vcr, $key_vcr->TRX_VOUCHER_ID.','.$key_vcr->TRX_CDC_REC_ID.','.$key_vcr->trx_voucher_code.','.$key_vcr->TRX_VOUCHER_NUMBER.','.$key_vcr->voucher_num.','.$key_vcr->trx_voucher_date.','.$key_vcr->trx_voucher_desc.','.$key_vcr->TRX_VOUCHER_AMOUNT."\n");
					}
				}
			}

			fclose($fp);
			fclose($fp_pnb);
			fclose($fp_pgr);
			fclose($fp_vcr);

			if (ftp_login($conn_id, 'ftpfinbu', 'ftpfinbu')) {
				ftp_pasv($conn_id, true);

				$dir_list = ftp_nlist($conn_id, '/u01/bu/interface_data/');

				for ($i=0; $i < count($dir_list) ; $i++) {
					if (substr($this->session->userdata('branch_code'), 0, 3) == substr($dir_list[$i], 23, 3)) {
						ftp_put($conn_id, $dir_list[$i]."/cdc/DATA_CDC/".$file_name_stj, $local_path.$file_name_stj, FTP_BINARY);
					}
				}
				chmod($local_path.$file_name_stj,0777);
				unlink($local_path.$file_name_stj);
			}
		}

		if ($data_stn) {
			$file_name_stn = 'IDMCDCSTN'.date('dmYHis').'.'.$this->session->userdata('branch_code');
			$this->Mod_report->create_header_file($local_path,$file_name_stn);

			$fp = fopen($local_path.$file_name_stj, 'a');
			$fp_pnb = fopen($local_path.$file_name_pnb, 'a');
			$fp_pgr = fopen($local_path.$file_name_pgr, 'a');
			$fp_vcr = fopen($local_path.$file_name_vcr, 'a');

			foreach ($data_stn as $key) {
				fwrite($fp, ''.$key->deposit_id.','.$key->deposit_num.','.$key->deposit_date.','.$key->mutation_date.','.$key->deposit_status.','.$key->branch_code.','.$key->bank_name.','.$key->batch_id.','.$key->batch_number.','.$key->batch_type.','.$key->batch_date.','.$key->batch_status.','.$key->description.','.$key->reff_num.','.$key->rec_id.','.$key->store_code.','.$key->sales_date.','.$key->status.','.$key->actual_sales_amount.','.$key->actual_rrak_amount.','.$key->actual_pay_less_deposited.','.$key->actual_voucher_amount.','.$key->actual_lost_item_payment.','.$key->actual_wu_accountability.','.$key->actual_others_amount.','.$key->actual_others_desc.','.$key->rrak_deduction.','.$key->less_deposit_deduction.','.$key->others_deduction.','.$key->others_desc.','.$key->actual_virtual_pay_less.','.$key->actual_sales_flag.','.$key->virtual_pay_less_deduction."\n");

				$this->Mod_report->update_status_deposit_batch($key->deposit_id,$key->batch_id);

				$data_pnb = $this->Mod_report->create_data_pnb($key->rec_id);
				$data_pgr = $this->Mod_report->create_data_pgr($key->rec_id);
				$data_vcr = $this->Mod_report->create_data_vcr($key->rec_id);

				if ($data_pnb) {
					foreach ($data_pnb as $key_pnb) {
						fwrite($fp_pnb, $key_pnb->TRX_DETAIL_ID.','.$key_pnb->TRX_CDC_REC_ID.','.$key_pnb->trx_plus_name.','.$key_pnb->trx_detail_date.','.$key_pnb->trx_detail_desc.','.$key_pnb->TRX_DET_AMOUNT."\n");
					}
				}

				if ($data_pgr) {
					foreach ($data_pgr as $key_pgr) {
						fwrite($fp_pgr, $key_pgr->TRX_DETAIL_MINUS_ID.','.$key_pgr->TRX_CDC_REC_ID.','.$key_pgr->trx_minus_name.','.$key_pgr->trx_minus_date.','.$key_pgr->trx_minus_desc.','.$key_pgr->TRX_MINUS_AMOUNT."\n");
					}
				}

				if ($data_vcr) {
					foreach ($data_vcr as $key_vcr) {
						fwrite($fp_vcr, $key_vcr->TRX_VOUCHER_ID.','.$key_vcr->TRX_CDC_REC_ID.','.$key_vcr->trx_voucher_code.','.$key_vcr->TRX_VOUCHER_NUMBER.','.$key_vcr->voucher_num.','.$key_vcr->trx_voucher_date.','.$key_vcr->trx_voucher_desc.','.$key_vcr->TRX_VOUCHER_AMOUNT."\n");
					}
				}
			}

			fclose($fp);
			fclose($fp_pnb);
			fclose($fp_pgr);
			fclose($fp_vcr);

			if (ftp_login($conn_id, 'ftpfinbu', 'ftpfinbu')) {
				ftp_pasv($conn_id, true);

				$dir_list = ftp_nlist($conn_id, '/u01/bu/interface_data/');

				for ($i=0; $i < count($dir_list) ; $i++) {
					if (substr($this->session->userdata('branch_code'), 0, 3) == substr($dir_list[$i], 23, 3)) {
						ftp_put($conn_id, $dir_list[$i]."/cdc/DATA_CDC/".$file_name_stj, $local_path.$file_name_stj, FTP_BINARY);
					}
				}
				chmod($local_path.$file_name_stj,0777);
				unlink($local_path.$file_name_stj);
			}
		}

		if ($data_stj || $data_stn) {
			ftp_pasv($conn_id, true);
			$dir_list = ftp_nlist($conn_id, '/u01/bu/interface_data/');

			for ($i=0; $i < count($dir_list) ; $i++) {
				if (substr($this->session->userdata('branch_code'), 0, 3) == substr($dir_list[$i], 23, 3)) {
					ftp_put($conn_id, $dir_list[$i]."/cdc/DATA_CDC/".$file_name_pnb, $local_path.$file_name_pnb, FTP_BINARY);
					ftp_put($conn_id, $dir_list[$i]."/cdc/DATA_CDC/".$file_name_pgr, $local_path.$file_name_pgr, FTP_BINARY);
					ftp_put($conn_id, $dir_list[$i]."/cdc/DATA_CDC/".$file_name_vcr, $local_path.$file_name_vcr, FTP_BINARY);
				}
			}
			chmod($local_path.$file_name_pnb,0777);
			chmod($local_path.$file_name_pgr,0777);
			chmod($local_path.$file_name_vcr,0777);

			unlink($local_path.$file_name_pnb);
			unlink($local_path.$file_name_pgr);
			unlink($local_path.$file_name_vcr);
		}else{
			chmod($local_path.$file_name_pnb,0777);
			chmod($local_path.$file_name_pgr,0777);
			chmod($local_path.$file_name_vcr,0777);

			unlink($local_path.$file_name_pnb);
			unlink($local_path.$file_name_pgr);
			unlink($local_path.$file_name_vcr);
		}
	}



}

/* End of file report.php */
/* Location: ./application/controllers/report.php */
