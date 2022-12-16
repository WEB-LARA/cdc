<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_upload');
		$this->load->model('master/Mod_cdc_seq_table');
	}

	public function index()
	{
		
	}

	public function upload_am_as()
	{
		$source_path = './uploads/';
		$file_name_like = 'DATA_AM_AS';
		$ins_count = 0;
		$curcname = $this->input->post('curcname');

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size']  = '10000';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('file_am_as')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>'.$this->upload->display_errors().'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
		else{
			$alert_list = '';
			$data = array('upload_data' => $this->upload->data());
			if (is_dir($source_path)) {
				if ($dh = opendir($source_path)) {
					while ($file = readdir($dh)) {
						if (strpos($file,$file_name_like) !== false && strpos($file,$file_name_like) == 0) {
							$pos_titik = strpos($file,'.');
							$format = substr($file,$pos_titik+1);

							$stream_file = fopen($source_path.$file, 'r');
							$isi_file = fgetcsv($stream_file,0,',');
							if ($isi_file[0]=='NIK_AM'&&$isi_file[1]=='NAMA_AM'&&$isi_file[2]=='SHORTNAME_AM'&&$isi_file[3]=='NIK_AS'&&$isi_file[4]=='NAMA_AS'&&$isi_file[5]=='SHORTNAME_AS'&&$isi_file[6]=='STORE_CODE') {
								
								/*var_dump(
								$isi_file[0].','.$isi_file[1].','.$isi_file[2].','.$isi_file[3].','.$isi_file[4].','.$isi_file[5].','.$isi_file[6]
								);*/
								
								$this->Mod_upload->delete_current_amas($this->session->userdata('branch_code'));
								$str_list = '';
								while (!feof($stream_file)) {
									$isi_file = fgetcsv($stream_file,0,',');

									if ($isi_file[0] != '') {
										if(strlen($isi_file[0])>10){
											$alert_list .= '<center><h4>NIK tidak valid</h4></center>
												<center><p>Mohon cek pada NIK AM/AS apakah panjang melebihi 10 karakter.</p></center>';
										} else{
											$res_ins = $this->Mod_upload->insert_am_as($isi_file[0],$isi_file[1],$isi_file[2],$isi_file[3],$isi_file[4],$isi_file[5],$isi_file[6],$format);
											$ins_count = $ins_count + $res_ins;
											if ($res_ins == 0) {
												$str_list .= $isi_file[6].',';
											}
										}

									}
									
									/*$cek_dup_ins = $this->Mod_upload->cek_am_as_ins($isi_file[2],$isi_file[5],$isi_file[6]);
									if ($cek_dup_ins == 0) {
										$cek_dup_up = $this->Mod_upload->cek_am_as_up($isi_file[6]);
										if ($cek_dup_up > 0) {
											$ins_count = $ins_count + $this->Mod_upload->update_am_as($isi_file[0],$isi_file[1],$isi_file[2],$isi_file[3],$isi_file[4],$isi_file[5],$isi_file[6],$cek_dup_up);
										}else{
											$ins_count = $ins_count + $this->Mod_upload->insert_am_as($isi_file[0],$isi_file[1],$isi_file[2],$isi_file[3],$isi_file[4],$isi_file[5],$isi_file[6],$format);
										}
									}*/
								}
							}
							fclose($stream_file);
							chmod($source_path.$file, 0777);
							unlink($source_path.$file);
						}
						/*chmod($source_path.$file, 0777);
						unlink($source_path.$file);*/
					}
				}
			}
			if ($str_list != '') {
				$alert_list .= '<center><h4>Berikut toko yang tidak terdapat pada Master Toko Web : '.$str_list.'</h4></center>
				<center><p>Mohon cek pada Store Management Oracle apakah toko tersebut sudah memiliki Bank dan Bank Account, jika sudah maka lakukan Request "IDM CDC Sync Data Bank Store" dan tunggu 10 menit, kemudian upload data AM dan AS kembali.</p></center>';
			}
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>Sukses!, Jumlah Yang Terupload '.$ins_count.'</h4></center>
			                        '.$alert_list.'
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
	}

	public function upload_go()
	{
		$source_path = './uploads/';
		$file_name_like = 'GO';
		$ins_count = 0;
		$curcname = $this->input->post('curcname_go');

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size']  = '10000';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('file_go')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>'.$this->upload->display_errors().'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			if (is_dir($source_path)) {
				if ($dh = opendir($source_path)) {
					while ($file = readdir($dh)) {
						if (strpos($file,$file_name_like) !== false && strpos($file,$file_name_like) == 0) {
							$pos_titik = strpos($file,'.');
							$format = substr($file,$pos_titik+1);
							$stream_file = fopen($source_path.$file, 'r');
							$isi_file = fgetcsv($stream_file,0,',');
							if ($isi_file[0]=='KDTOKO'&&$isi_file[1]=='TGLKIRIM'&&$isi_file[2]=='JAMKIRIM'&&$isi_file[3]=='NOPOLISI'&&$isi_file[4]=='NOLAMBUNG'&&$isi_file[5]=='NMSUPIR') {
								while (!feof($stream_file)) {
									$isi_file = fgetcsv($stream_file,0,',');
									if ($isi_file[0] != '') {
										$cek_go = $this->Mod_upload->cek_data_go($isi_file[0],$isi_file[1]);
										if ($cek_go == 0) {
											$ins_count = $ins_count + $this->Mod_upload->ins_data_go($isi_file[0],$isi_file[1],$isi_file[2],$isi_file[3],$isi_file[4],$isi_file[5]);
										}
									}
								}
							}
							fclose($stream_file);
							chmod($source_path.$file, 0777);
							unlink($source_path.$file);
						}
						/*chmod($source_path.$file, 0777);
						unlink($source_path.$file);*/
					}
				}
			}
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>Sukses!, Jumlah Yang Terupload '.$ins_count.'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
	}

	public function upload_voucher()
	{
		$source_path = './uploads/';
		$file_name_like = 'DATA_VOUCHER';
		$ins_count = 0;
		$curcname = $this->input->post('curcname_voucher');

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size']  = '10000';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('file_voucher')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>'.$this->upload->display_errors().'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			if (is_dir($source_path)) {
				if ($dh = opendir($source_path)) {
					while ($file = readdir($dh)) {
						if (strpos($file,$file_name_like) !== false && strpos($file,$file_name_like) == 0) {
							$pos_titik = strpos($file,'.');
							$format = substr($file,$pos_titik+1);

							$stream_file = fopen($source_path.$file, 'r');
							$isi_file = fgetcsv($stream_file,0,',');
							if ($isi_file[0]=='SERIAL_CODE'&&$isi_file[1]=='SERIAL_NUM'&&$isi_file[2]=='AMOUNT') {
								while (!feof($stream_file)) {
									$isi_file = fgetcsv($stream_file,0,',');
									$cek_voucher = $this->Mod_upload->cek_data_voucher($isi_file[0],$isi_file[1]);
									if ($cek_voucher == 0) {
										$ins_count = $ins_count + $this->Mod_upload->ins_data_voucher($isi_file[0],$isi_file[1],$isi_file[2]);
									}
								}
							}
							fclose($stream_file);
							chmod($source_path.$file, 0777);
							unlink($source_path.$file);
						}
						/*chmod($source_path.$file, 0777);
						unlink($source_path.$file);*/
					}
				}
			}
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>Sukses!, Jumlah Yang Terupload '.$ins_count.'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}
	}

	public function download_stn_template(){
		$kd_cabang = $this->session->userdata('branch_code');
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=DATA_STN_'.trim($kd_cabang).'.csv');  
		$output = fopen("php://output", "w");  
		fputcsv($output, array('TIPE','TOKO','TGL_SALES','KOLOM1','KOLOM2','KOLOM3','KOLOM4'), '|');
		fclose($output);
		exit();
	}


	public function download_template_store(){
		$kd_cabang = $this->session->userdata('branch_code');
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=MASTER_SHIFT_CBG_'.trim($kd_cabang).'.csv');  
		$output = fopen("php://output", "w");  
		fputcsv($output, array('KD_TOKO','ACTIVE_DATE','END_DATE','TIPE_SHIFT','JML_SHIFT','METODE_SETOR'), '|');
		fclose($output);
		exit();
	}


	public function upload_master_shift_toko()
	{
		$source_path = './uploads/';
		$file_name_like = 'MASTER_SHIFT_CBG_';
		$ins_count = 0;
		$curcname = $this->input->post('curcname_shift');
		$error=0;
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size']  = '10000';
		$this->load->library('upload', $config);
		$json_result=array();
		$kd_toko='';
		if ( ! $this->upload->do_upload('file_store')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>'.$this->upload->display_errors().'</h4></center>
			                  </div>
			            </div>
			      </div>');
		
			redirect($curcname);
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			if (is_dir($source_path)) {
				if ($dh = opendir($source_path)) {
					while ($file = readdir($dh)) {
						if (strpos($file,$file_name_like) !== false && strpos($file,$file_name_like) == 0) {
							$pos_titik = strpos($file,'.');
							$format = substr($file,$pos_titik+1);
							$stream_file = fopen($source_path.$file, 'r');
							$isi_file = fgetcsv($stream_file,0,'|');
							if ($isi_file[0]=='KD_TOKO'&&$isi_file[1]=='ACTIVE_DATE'&&$isi_file[2]=='END_DATE'&&$isi_file[3]=='TIPE_SHIFT'&&$isi_file[4]=='JML_SHIFT'&&$isi_file[5]=='METODE_SETOR') {

								while (!feof($stream_file)) {
									$isi_file = fgetcsv($stream_file,0,'|');
									if ($isi_file[0] != '' && $isi_file[1]!=''&&$isi_file[3]!='' &&$isi_file[4]!='' && $isi_file[5]!='' ) {

										if($isi_file[4]<=3 && $isi_file[4]>0){
											if($isi_file[5]=='KODEL' || $isi_file[5]=='BANK' || $isi_file[5]=='PIHAK3'){

												if($isi_file[3]=='HARIAN' || $isi_file[3]=='HARIAN_SHIFT' || $isi_file[3]=='SALES_SHIFT'){

															$cek_store=$this->Mod_upload->cek_store($isi_file[0]);
															if($cek_store==1){

															  $json_details['KD_TOKO']=$isi_file[0];
															   $json_details['ACTIVE_DATE']=$isi_file[1];
															   $json_details['END_DATE']=$isi_file[2];
															   $json_details['TIPE_SHIFT']=$isi_file[3];
															   $json_details['JML_SHIFT']=$isi_file[4];
															   $json_details['METODE_SETOR']=$isi_file[5];
												
															   $ins_count++;

																	array_push($json_result,$json_details);
															}else{
																	$kd_toko.='  ,'.$isi_file[0];
																	$error=5;

															}
														  		// $cek_go = $this->Mod_upload->cek_data_go($isi_file[0],$isi_file[1]);
														// if ($cek_go == 0) {
														// 	$ins_count = $ins_count + $this->Mod_upload->ins_data_go($isi_file[0],$isi_file[1],$isi_file[2],$isi_file[3],$isi_file[4],$isi_file[5]);
														// }
														
													}else{

														$error=1;
										
													}
											}else{
												$error=2;
												
											}
											  

										}else{

										  	 $error=3;
											
										}
								}else{
									 $error=4;
											
								}

								}
							}
							fclose($stream_file);
							chmod($source_path.$file, 0777);
							unlink($source_path.$file);
						}
						
					}
				}
			}
			// $this->session->set_flashdata('msg', '
			// 	<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
   //          data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			//             <div class="easyui-layout" data-options="fit:true,closed:true">
			//                   <div data-options="region:\'center\'">
			//                         <center><h4>Sukses!, Jumlah Yang Terupload '.$ins_count.'</h4></center>
			//                   </div>
			//             </div>
			//       </div>');
			// redirect($curcname);
		}
		if($error==0){

		 	echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'success'));
		}else if($error==1){
			echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'Tipe Shift hanya boleh diisi HARIAN_SHIFT,SALES_SHIFT,HARIAN.'));

		}else if($error==2){
			echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'Metode Setor hanya boleh KODEL,BANK ATAU PIHAK3'));
		}else if($error==3){
			 echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'Jumlah Shift minimal 1 dan maksimal 3.'));
		}else if($error==4){
			 echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'Kolom ada yang kosong.'));

		}else{
			echo json_encode(array('rows'=> $json_result,'total'=>$ins_count,'msg'=>'Data toko  '.$kd_toko.' tidak valid.'));
		}

	}
	public function upload_stn(){
		$source_path = './uploads/';
		$path_file = './uploads/*';
		$kd_cabang = $this->session->userdata('branch_code');
		$file_name_like = 'DATA_STN_'.trim($kd_cabang);
		//$file_name_like = 'DATA_STN';
		$ins_tmp_count = 0;
		$count_error = 0;
		$list_error = '';
		$list_error2 = '';
		$user_id = '';
		$sess_id = '';
		$branch_code = '';
		$curcname = $this->input->post('curcname_stn');
		$filename=$_FILES['file_stn']['name'];
		$no2 = 1;
		
		//echo $filename;

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$config['max_size']  = '1000000';

		$files = glob($path_file); // get all file names
		foreach($files as $file_del){ // iterate files
  			// echo $file_del;
			if(is_file($file_del)){
		  		// echo $file_del.' vs '.$file_name_like.' '.strpos($file_del,$file_name_like).'<br>';

				$filenamedel = substr($file_del,strrpos($file_del, '/', 1)+1);
				if(strpos($filenamedel,$file_name_like) !== false && strpos($filenamedel,$file_name_like) == 0 ){
		  			unlink($file_del); // delete file
		  			echo $file_del.' deleted<br>';
		  		} 
		  		// else{

		  		// 	echo $file_del.' gak deleted vs '.$file_name_like.'<br>';
		  		// }
			}
			// echo '<br>';
		}


		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file_stn')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('msg', '
				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>'.$this->upload->display_errors().'</h4></center>
			                  </div>
			            </div>
			      </div>');
			redirect($curcname);
		}else{
			$user_id = $this->session->userdata('usrId');
			$sess_id = session_id();
			$branch_code = trim($this->session->userdata('branch_code'));
			$branch_id = $this->Mod_upload->getBranchID($branch_code);
			$valid = false;
			// echo $branch_id;

			
			if($sess_id != ''){
				// $data = array('upload_data' => $this->upload->data());
				//$error = $this->upload->display_errors();
				if (is_dir($source_path)) {
					if ($dh = opendir($source_path)) {
						while ($file = readdir($dh)) {
							if (strpos($file,$file_name_like) !== false && strpos($file,$file_name_like) == 0) {
								$pos_titik = strpos($file,'.');
								$format = substr($file,$pos_titik+1);

								$stream_file = fopen($source_path.$file, 'r');
								//if(strpos($stream_file,'|') !== false && strpos($stream_file,'|') == 0){
									$isi_file = fgetcsv($stream_file,0,'|');
									if($isi_file[0]=='TIPE'&&$isi_file[1]=='TOKO'&&$isi_file[2]=='TGL_SALES'&&$isi_file[3]=='KOLOM1'&&$isi_file[4]=='KOLOM2'&&$isi_file[5]=='KOLOM3'&&$isi_file[6]=='KOLOM4') {
																	
										$this->Mod_upload->delete_rec_stn_tmp($user_id);
										
										while (!feof($stream_file)) {
											$isi_file = fgetcsv($stream_file,0,'|');
											if($isi_file != ''){
												$cek_tgl_sales = TRUE;
												$cek_tgl_mutasi = TRUE;
												$cek_tgl_pot = TRUE;
												$kolom1 = '';
												$kolom1 = preg_replace('~[!\s!]~', '', $isi_file[3]);
												// $kolom1 = strtoupper(str_replace(' ', '', $kolom1));
												$kolom1 = strtoupper($kolom1);

												if($isi_file[0]=='HEADER'){
													$cek_tgl_sales = $this->Mod_upload->validateDate($isi_file[2]);
													$cek_tgl_mutasi = $this->Mod_upload->validateDate($isi_file[6]);
												} else{
													$cek_tgl_sales = $this->Mod_upload->validateDate($isi_file[2]);
													$cek_tgl_pot = $this->Mod_upload->validateDate($isi_file[4]);	
												}


												if($cek_tgl_sales == TRUE && $cek_tgl_mutasi == TRUE && $cek_tgl_pot == TRUE){
													$insert_to_tmp = $this->Mod_upload->insert_rec_stn_to_tmp($user_id,$isi_file[0],$isi_file[1],$isi_file[2],$kolom1,$isi_file[4],substr($isi_file[5],0,50),$isi_file[6]);

													$ins_tmp_count = $ins_tmp_count + $insert_to_tmp;
												}else{
													if($cek_tgl_sales == FALSE){
														$list_error2 .=  '<tr><td align="center">'.$no2.'.</td><td>Tgl sales toko '.$isi_file[1].' salah format '.$isi_file[2].' seharusnya YYYY-MM-DD</td></tr>';
														$no2++;
													}

													if($cek_tgl_mutasi == FALSE){
														$list_error2 .=  '<tr><td align="center">'.$no2.'.</td><td>Tgl mutasi toko '.$isi_file[1].' salah format '.$isi_file[6].' seharusnya YYYY-MM-DD</td></tr>';
														$no2++;
													}
													if($cek_tgl_pot == FALSE){
														$list_error2 .=  '<tr><td align="center">'.$no2.'.</td><td>Tgl potongan toko '.$isi_file[1].' salah format '.$isi_file[4].' seharusnya YYYY-MM-DD</td></tr>';
														$no2++;
													}
												}
											}
											
										}

										if($ins_tmp_count > 0 && $list_error2 == ''){
											if($this->Mod_upload->check_rec_stn_tmp_header_duplicate($user_id)==1){
												if($this->Mod_upload->check_line_yang_tanpa_header($user_id)==0){
													if($this->Mod_upload->check_toko_valid($user_id, $branch_id, 'num_rows')==0){
														if($this->Mod_upload->check_stn_tmp_trx_duplicate($user_id,'num_rows')==0){
															if($this->Mod_upload->cek_bank_tmp($user_id)==0){
																if($this->Mod_upload->cek_bank_account_tmp($user_id, $branch_id)==0){
																	if($this->Mod_upload->cek_detail_pengurang($user_id,'num_rows')==0){
																		if($this->Mod_upload->cek_detail_penambah($user_id,'num_rows')==0){
																			$valid = true;
																			// $this->session->set_flashdata('msg', '
																			// 	<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
																   //          		data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
																		 //            <div class="easyui-layout" data-options="fit:true,closed:true">
																		 //                  <div data-options="region:\'center\'">
																		 //                        <center><h4>Jumlah STN tmp yg berhasil diupload '.$ins_tmp_count.' rows</h4></center>
																		 //                  </div>
																		 //            </div>
																		 //      	</div>');
																		} else{
																			$penambah_error = $this->Mod_upload->cek_detail_penambah($user_id,'result');
																			$this->Mod_upload->delete_rec_stn_tmp($user_id);
																			foreach ($penambah_error as $key) {
																				
																				$list_error2 .=  '<tr><td align="center"></td><td>Kode penambah salah pada toko <b>'.$key->TOKO.'</b> tanggal <b>'.$key->TGL_SALES.'</b> tipe <b>'.$key->KOLOM1.'</b></td></tr>';
																				$no2++;
																			}
																		}
																	} else{
																		$pengurang_error = $this->Mod_upload->cek_detail_pengurang($user_id,'result');
																		$this->Mod_upload->delete_rec_stn_tmp($user_id);
																		foreach ($pengurang_error as $key) {
																			$list_error2 .=  '<tr><td align="center"></td><td>Kode pengurang salah pada toko <b>'.$key->TOKO.'</b> tanggal <b>'.$key->TGL_SALES.'</b> tipe <b>'.$key->KOLOM1.'</b></td></tr>';
																			$no2++;
																		}
																	}
																}else{
																	$this->Mod_upload->delete_rec_stn_tmp($user_id);
																	$list_error2 .=  '<tr><td align="center"></td><td>Akun bank tidak terdaftar</td></tr>';
																	$no2++;
																}
															} else{
																$this->Mod_upload->delete_rec_stn_tmp($user_id);
																$list_error2 .=  '<tr><td align="center"></td><td>Bank tidak terdaftar</td></tr>';
																$no2++;
															}
														} else{
															$duplicates = $this->Mod_upload->check_stn_tmp_trx_duplicate($user_id,'result');
															$this->Mod_upload->delete_rec_stn_tmp($user_id);
															foreach ($duplicates as $key) {
																$list_error2 .=  '<tr><td align="center"></td><td>Transaksi sudah ada pada toko <b>'.$key->TOKO.'</b> tanggal <b>'.$key->TGL_SALES.'</b></td></tr>';
																$no2++;
															}
															// $list_error2 .=  '<tr><td align="center"></td><td>Transaksi sudah ada</td></tr>';
															$no2++;
														}
													} else{
														$toko_invalid = $this->Mod_upload->check_toko_valid($user_id, $branch_id, 'result');
														$this->Mod_upload->delete_rec_stn_tmp($user_id);
														foreach ($toko_invalid as $key) {
															$list_error2 .=  '<tr><td align="center"></td><td>Toko <b>'.$key->TOKO.'</b> tidak valid.</td></tr>';
															$no2++;
														}
														// $list_error2 .=  '<tr><td align="center"></td><td>Ada toko yang tidak valid</td></tr>';
														$no2++;
													}

													
												} else{
													$this->Mod_upload->delete_rec_stn_tmp($user_id);
													$list_error2 .=  '<tr><td align="center"></td><td>Ada lines yang tidak ada header</td></tr>';
													$no2++;
												}
											} else{
												$this->Mod_upload->delete_rec_stn_tmp($user_id);
												$list_error2 .=  '<tr><td align="center"></td><td>Header ada yang duplicate</td></tr>';
												$no2++;
											}
											// $data_stn_tmp = $this->Mod_upload->data_stn_tmp($sess_id,$user_id);

											// //foreach ($data_stn_tmp as $stn){
											// $validate_tmp = $this->Mod_upload->validate_stn_tmp($sess_id,$user_id);	
											// //}

											// $count_error = count($validate_tmp);
											// $no = 1;
											// if($count_error > 0){
											// 	for($i = 0;$i<10;$i++){
											// 		if(array_key_exists($i,$validate_tmp)){
											// 			if($list_error == ''){
											// 				$list_error = '<tr><td align="center">'.$no.'.</td><td>'.$validate_tmp[$i].'</td></tr>';
											// 			}else{
											// 				$list_error .=  '<tr><td align="center">'.$no.'.</td><td>'.$validate_tmp[$i].'</td></tr>';
											// 			}
											// 			$no = $no + 1;
											// 		}
											// 	}

											// 	if(array_key_exists(10,$validate_tmp)){
											// 		$list_error .= '<tr><td colspan="2" align="center">...<td></tr>';
											// 	}


											// $this->session->set_flashdata('msg', '
											// 		<div id="warning_upload" class="easyui-window" title="Caution" style="width:550px;height:250px;"
									  //           data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
											// 	            <div class="easyui-layout" data-options="fit:true,closed:true">
											// 	                  <div data-options="region:\'center\'">
											// 	                        <center><h4><table align="center">'.$list_error.'</table></h4></center>
											// 	                  </div>
											// 	            </div>
											// 	      </div>');
											// }else{

											// 	$store_id = '';
											// 	$branch_id = '';
											// 	$cdc_rec_id = 0;
											// 	$bank_id = 0;
											// 	$bank_account_id = 0;
											// 	$count_ins = 0;

											// 	foreach ($data_stn_tmp as $stn) {
											// 		$branch_id = $this->Mod_upload->getBranchID(trim($stn->BRANCH_CODE));
											// 		$store_id = $this->Mod_upload->getStoreID(trim($stn->STORE_CODE),$branch_id);
											// 		$bank_id = $this->Mod_upload->getBankID(trim($stn->BANK));
											// 		$bank_account_id = $this->Mod_upload->getBankAccountID($bank_id,$branch_id,trim($stn->BANK_ACCOUNT));
											// 		$cdc_rec_id = $this->Mod_cdc_seq_table->getIDN();

											// 		if($cdc_rec_id > 0){
											// 			$insert_stn =  $this->Mod_upload->insert_stn($cdc_rec_id,$store_id,$stn->STORE_CODE,$stn->SALES_DATE,$stn->SALES_AMOUNT,$stn->BRANCH_CODE,$stn->BANK,$stn->BANK_ACCOUNT,$bank_account_id,$stn->MUTATION_DATE,$user_id);

											// 			if($insert_stn > 0){
											// 				$count_ins = $count_ins + 1;
											// 			}
											// 		}	
											// 	}
												


											// 	$this->session->set_flashdata('msg', '
											// 		<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
									  //           data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
											// 	            <div class="easyui-layout" data-options="fit:true,closed:true">
											// 	                  <div data-options="region:\'center\'">
											// 	                        <center><h4>Jumlah STN yg berhasil diupload '.$count_ins.' rows</h4></center>
											// 	                  </div>
											// 	            </div>
											// 	      </div>');
											// }

											// /*if($count_error == 0){
											// 	foreach ($data_stn_tmp as $stn2) {
											// 		$insert_stn_rec = $this->Mod_upload->insert_stn_rec();
											// 	}
											// }else{

											// }*/
										}//end if ins tmp count

										if($list_error2 != ''){
											$this->session->set_flashdata('msg', '
													<div id="warning_upload" class="easyui-window" title="Caution" style="width:550px;height:250px;"
									            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
												            <div class="easyui-layout" data-options="fit:true,closed:true">
												                  <div data-options="region:\'center\'">
												                        <center><h4><table align="center">'.$list_error2.'</table></h4></center>
												                  </div>
												            </div>
												      </div>');
										}

										$this->Mod_upload->delete_stn_tmp($sess_id,$user_id);
									}else{
										$this->session->set_flashdata('msg', '
											<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
							            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
										            <div class="easyui-layout" data-options="fit:true,closed:true">
										                  <div data-options="region:\'center\'">
										                        <center><h4>Header kolom salah</h4></center>
										                  </div>
										            </div>
										      </div>');
									}
									// if ($isi_file[0]=='TOKO'&&$isi_file[1]=='TGL_SALES'&&$isi_file[2]=='SALES_AMOUNT'&&$isi_file[3]=='BANK'&&$isi_file[4]=='BANK_ACCOUNT'&&$isi_file[5]=='TGL_MUTASI') {
									// 	while (!feof($stream_file)) {
									// 		$isi_file = fgetcsv($stream_file,0,'|');
									// 		if($isi_file != ''){
									// 			$cek_tgl_sales = '';
									// 			$cek_tgl_mutasi = '';
									// 			$isi4 = '';
									// 			$isi4 = preg_replace('~[-.!\s!]~', ' ', $isi_file[4]);
									// 			$isi4 = str_replace(' ', '', $isi4);

									// 			$cek_tgl_sales = $this->Mod_upload->validateDate($isi_file[1]);
									// 			$cek_tgl_mutasi = $this->Mod_upload->validateDate($isi_file[5]);


									// 			if($cek_tgl_sales == TRUE && $cek_tgl_mutasi == TRUE){
									// 				$insert_to_tmp = $this->Mod_upload->insert_stn_to_tmp($isi_file[0],$isi_file[1],$isi_file[2],$branch_code,$isi_file[3],$isi4,$isi_file[5],$sess_id,$user_id);

									// 				$ins_tmp_count = $ins_tmp_count + $insert_to_tmp;
									// 			}else{
									// 				if($cek_tgl_sales == FALSE){
									// 					if($list_error2 == ''){
									// 					$list_error2 = '<tr><td align="center">'.$no2.'.</td><td>Tgl sales toko '.$isi_file[0].' salah format '.$isi_file[1].' seharusnya YYYY-MM-DD</td></tr>';
									// 					}else{
									// 						$list_error2 .=  '<tr><td align="center">'.$no2.'.</td><td>Tgl sales toko '.$isi_file[0].' salah format '.$isi_file[1].' seharusnya YYYY-MM-DD</td></tr>';
									// 					}
									// 					$no2++;
									// 				}

									// 				if($cek_tgl_mutasi == FALSE){
									// 					if($list_error2 == ''){
									// 						$list_error2 = '<tr><td align="center">'.$no2.'.</td><td>Tgl mutasi toko '.$isi_file[0].' salah format '.$isi_file[5].' seharusnya YYYY-MM-DD</td></tr>';
									// 					}else{
									// 						$list_error2 .=  '<tr><td align="center">'.$no2.'.</td><td>Tgl mutasi toko '.$isi_file[0].' salah format '.$isi_file[5].' seharusnya YYYY-MM-DD</td></tr>';
									// 					}
									// 					$no2++;
									// 				}
									// 			}
									// 		}
											
									// 	}

									// 	if($ins_tmp_count > 0 && $list_error2 == ''){
									// 		$data_stn_tmp = $this->Mod_upload->data_stn_tmp($sess_id,$user_id);

									// 		//foreach ($data_stn_tmp as $stn){
									// 		$validate_tmp = $this->Mod_upload->validate_stn_tmp($sess_id,$user_id);	
									// 		//}

									// 		$count_error = count($validate_tmp);
									// 		$no = 1;
									// 		if($count_error > 0){
									// 			for($i = 0;$i<10;$i++){
									// 				if(array_key_exists($i,$validate_tmp)){
									// 					if($list_error == ''){
									// 						$list_error = '<tr><td align="center">'.$no.'.</td><td>'.$validate_tmp[$i].'</td></tr>';
									// 					}else{
									// 						$list_error .=  '<tr><td align="center">'.$no.'.</td><td>'.$validate_tmp[$i].'</td></tr>';
									// 					}
									// 					$no = $no + 1;
									// 				}
									// 			}

									// 			if(array_key_exists(10,$validate_tmp)){
									// 				$list_error .= '<tr><td colspan="2" align="center">...<td></tr>';
									// 			}


									// 		$this->session->set_flashdata('msg', '
									// 				<div id="warning_upload" class="easyui-window" title="Caution" style="width:550px;height:250px;"
									//             data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									// 			            <div class="easyui-layout" data-options="fit:true,closed:true">
									// 			                  <div data-options="region:\'center\'">
									// 			                        <center><h4><table align="center">'.$list_error.'</table></h4></center>
									// 			                  </div>
									// 			            </div>
									// 			      </div>');
									// 		}else{

									// 			$store_id = '';
									// 			$branch_id = '';
									// 			$cdc_rec_id = 0;
									// 			$bank_id = 0;
									// 			$bank_account_id = 0;
									// 			$count_ins = 0;

									// 			foreach ($data_stn_tmp as $stn) {
									// 				$branch_id = $this->Mod_upload->getBranchID(trim($stn->BRANCH_CODE));
									// 				$store_id = $this->Mod_upload->getStoreID(trim($stn->STORE_CODE),$branch_id);
									// 				$bank_id = $this->Mod_upload->getBankID(trim($stn->BANK));
									// 				$bank_account_id = $this->Mod_upload->getBankAccountID($bank_id,$branch_id,trim($stn->BANK_ACCOUNT));
									// 				$cdc_rec_id = $this->Mod_cdc_seq_table->getIDN();

									// 				if($cdc_rec_id > 0){
									// 					$insert_stn =  $this->Mod_upload->insert_stn($cdc_rec_id,$store_id,$stn->STORE_CODE,$stn->SALES_DATE,$stn->SALES_AMOUNT,$stn->BRANCH_CODE,$stn->BANK,$stn->BANK_ACCOUNT,$bank_account_id,$stn->MUTATION_DATE,$user_id);

									// 					if($insert_stn > 0){
									// 						$count_ins = $count_ins + 1;
									// 					}
									// 				}	
									// 			}
												


									// 			$this->session->set_flashdata('msg', '
									// 				<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
									//             data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									// 			            <div class="easyui-layout" data-options="fit:true,closed:true">
									// 			                  <div data-options="region:\'center\'">
									// 			                        <center><h4>Jumlah STN yg berhasil diupload '.$count_ins.' rows</h4></center>
									// 			                  </div>
									// 			            </div>
									// 			      </div>');
									// 		}

									// 		/*if($count_error == 0){
									// 			foreach ($data_stn_tmp as $stn2) {
									// 				$insert_stn_rec = $this->Mod_upload->insert_stn_rec();
									// 			}
									// 		}else{

									// 		}*/
									// 	}//end if ins tmp count

									// 	if($list_error2 != ''){
									// 		$this->session->set_flashdata('msg', '
									// 				<div id="warning_upload" class="easyui-window" title="Caution" style="width:550px;height:250px;"
									//             data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									// 			            <div class="easyui-layout" data-options="fit:true,closed:true">
									// 			                  <div data-options="region:\'center\'">
									// 			                        <center><h4><table align="center">'.$list_error2.'</table></h4></center>
									// 			                  </div>
									// 			            </div>
									// 			      </div>');
									// 	}

									// 	$this->Mod_upload->delete_stn_tmp($sess_id,$user_id);
									// }else{
									// 	$this->session->set_flashdata('msg', '
									// 		<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
							  //           data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									// 	            <div class="easyui-layout" data-options="fit:true,closed:true">
									// 	                  <div data-options="region:\'center\'">
									// 	                        <center><h4>Header kolom salah</h4></center>
									// 	                  </div>
									// 	            </div>
									// 	      </div>');
									// }
									fclose($stream_file);
									chmod($source_path.$file, 0777);
									unlink($source_path.$file);
								//}//end cek separator
								/*else{
									$this->session->set_flashdata('msg', '
										<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
						            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									            <div class="easyui-layout" data-options="fit:true,closed:true">
									                  <div data-options="region:\'center\'">
									                        <center><h4>Separator salah</h4></center>
									                  </div>
									            </div>
									      </div>');

									chmod($source_path.$file, 0777);
									unlink($source_path.$file);
								}//else cek separator*/
								
							}/*else{
								$this->session->set_flashdata('msg', '
										<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
						            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
									            <div class="easyui-layout" data-options="fit:true,closed:true">
									                  <div data-options="region:\'center\'">
									                        <center><h4>Nama File salah</h4></center>
									                  </div>
									            </div>
									      </div>');

								chmod($source_path.$file, 0777);
								unlink($source_path.$file);
							}*/
							/*chmod($source_path.$file, 0777);
							unlink($source_path.$file);*/
						}
					}
				}// if source path dir
			}//end if sess id null
			else{
				$this->session->set_flashdata('msg', '
									<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
					            data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
								            <div class="easyui-layout" data-options="fit:true,closed:true">
								                  <div data-options="region:\'center\'">
								                        <center><h4>Session habis!</h4></center>
								                  </div>
								            </div>
								      </div>');
			}
			if($valid){
				$store_id = '';
				// $branch_id = '';
				$cdc_rec_id = 0;
				$bank_id = 0;
				$bank_account_id = 0;
				$count_header = 0;
				$count_minus = 0;
				$count_plus = 0;
				$data_stn_tmp = $this->Mod_upload->data_rec_stn_tmp($user_id);
				$trx_cdc_rec_id = 0;

				foreach ($data_stn_tmp as $stn) {
					$store_id = $this->Mod_upload->getStoreID(trim($stn->TOKO),$branch_id);
					if($stn->TIPE == 'HEADER'){
						$bank_id = $this->Mod_upload->getBankID(trim($stn->KOLOM2));
						$bank_account_id = $this->Mod_upload->getBankAccountID($bank_id,$branch_id,trim($stn->KOLOM3));
						$cdc_rec_id = $this->Mod_cdc_seq_table->getIDN();
						echo $cdc_rec_id.'<br>';
						if($cdc_rec_id > 0){
							$trx_cdc_rec_id =  $this->Mod_upload->insert_stn($cdc_rec_id,$store_id,$stn->TOKO,$stn->TGL_SALES,$stn->KOLOM1,$branch_code,$stn->KOLOM2,$stn->KOLOM3,$bank_account_id,$stn->KOLOM4,$user_id);
							if($trx_cdc_rec_id){
								$count_header++;
							}
						}
					} else if($stn->TIPE == 'MINUS' && $cdc_rec_id > 0 && $trx_cdc_rec_id > 0){
						$minus_id = $this->Mod_upload->get_minus_id($stn->KOLOM1)->TRX_MINUS_ID;
						if($minus_id){
							$insert_stn =  $this->Mod_upload->insert_stn_minus($trx_cdc_rec_id, $minus_id, $stn->KOLOM2, $stn->KOLOM3, $stn->KOLOM4, $cdc_rec_id);

							if($insert_stn > 0){
								$count_minus++;
							}
						}
					} else if($stn->TIPE == 'PLUS' && $cdc_rec_id > 0 && $trx_cdc_rec_id > 0){
						$plus_id = $this->Mod_upload->get_plus_id($stn->KOLOM1)->TRX_PLUS_ID;
						if($plus_id){
							$insert_stn =  $this->Mod_upload->insert_stn_plus($trx_cdc_rec_id, $plus_id, $stn->KOLOM2, $stn->KOLOM3, $stn->KOLOM4, $cdc_rec_id);

							if($insert_stn > 0){
								$count_plus++;
							}
						}
					}	
				}

				$this->Mod_upload->delete_rec_stn_tmp($user_id);

				$this->session->set_flashdata('msg', '
					<div id="warning_upload" class="easyui-window" title="Caution" style="width:300px;height:95px;"
	            		data-options="iconCls:\'icon-ok\',modal:true,collapsible:false,minimizable:false,maximizable:false">
			            <div class="easyui-layout" data-options="fit:true,closed:true">
			                  <div data-options="region:\'center\'">
			                        <center><h4>Jumlah STN yg berhasil diupload '.$count_header.' header, '.$count_plus.' penambah, '.$count_minus.' pengurang</h4></center>
			                  </div>
			            </div>
				    </div>');
			}
			//unlink($source_path.$filename);
		}
	
		redirect($curcname);
	}

	public function download_template_am_as()
	{
		$data['file_name'] = base_url().'download/DATA_AM_AS.csv';
		$this->load->view('view_download', $data, FALSE);
	}

	public function download_template_go()
	{
		$data['file_name'] = base_url().'download/GODDMMYY.csv';
		$this->load->view('view_download', $data, FALSE);
	}

}

/* End of file Upload.php */
/* Location: ./application/controllers/Upload.php */