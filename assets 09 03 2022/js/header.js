$(document).ready(function(){

	$('#form_close_shift').window('close');
	$('#form_cl_shift').window('close');
	$("#form_choose_branch_ch").window('close');

	/*************************************************************************************************************************/	
	/* EXTEND */	
	/*************************************************************************************************************************/	

	$.extend($.fn.validatebox.defaults.rules, {
		isCSV: {
			validator: function(value){
				var ext = value.split('.').pop();
				if(ext.toUpperCase() == 'CSV'){
					return true;
				}else{
					return false;
				}
			},
			message: 'Format file is not CSV.'
		},
		PasswordEquals: {
	        validator: function(value,param){
	            return value == $(param[0]).val();
	        },
	        message: 'Password do not match.'
	    }
	});
	
	/*************************************************************************************************************************/	
	/* RESET PASSWORD FIRST LOGIN */	
	/*************************************************************************************************************************/	

	if(flag == 'Y'){
		$("#resetPassPanel").show();
		$("#resetPassPanel").dialog({
			title:'Reset User Password',
			autoOpen: false, 
			modal:true,
			closable:false,
			maximizable:false,
			minimizable:false,
			collapsible:false
		});
	}	
	
	$("#saved_reset_pass").click(function(){
		if($('#reset_user_form').form('enableValidation').form('validate')){
			$('#reset_user_form').form('submit', {
				success:function(data){
					var data2 = eval('(' + data + ')');					
			        if(data2.status == 'SUKSES'){
						$.messager.alert('Info',data2.pesan,'info',function(){
							window.location.replace(base_url+"login/logout");
						});
					}else{
						$.messager.alert('Info',data2.pesan,'error');
					}
			    }
			});
		}
	});

	/*************************************************************************************************************************/	
	/* LOGOUT */	
	/*************************************************************************************************************************/	

	/*$('#logOut').click(function(){
		window.location.replace(base_url+"login/logout");
	});*/

	$('#logOut').click(function(){
		$.ajax({
			type: 'POST',
			url: base_url+'Login/check_role/',
			success: function(msg) {
				if (msg < 4) {
					$('#form_close_shift').window('open');
				}else{
					window.location.replace(base_url+"login/logout");
				}
			}
		});
	});

	$('#ya_close').click(function(event) {
		event.preventDefault();
		var user_id = $(this).attr('userid');
		var check = 0;
		$.ajax({
			type: 'POST',
			url: base_url+'InputBatch/check_data_receipts/',
			data: {
				'user_id': user_id
			},
			success:function (msg) {
				check = msg;
				if (check == 0) {
					$.ajax({
						type: 'POST',
						url: base_url+'Login/del_shift/',
						data: {
							'user_id': user_id
						},
						success:function (msg) {
							$('#form_close_shift').window('open');
							window.location.replace(base_url+"login/logout");
						}
					});
				}else{
					$.messager.show({
						title:'Alert',
						msg:'Shift tidak ditutup karena masih terdapat Receipts tersisa.',
						timeout:3000,
						showType:'show',
						style:{
							right:'',
							top:document.body.scrollTop+document.documentElement.scrollTop,
							bottom:''
						}
					});
					setInterval(function(){ window.location.replace(base_url+"login/logout"); }, 3500);
				}
			}
		});
	});

	$('#no_close').click(function(event) {
		event.preventDefault();
		$('#form_close_shift').window('open');
		window.location.replace(base_url+"login/logout");
	});
	
	/*************************************************************************************************************************/	
	/* CHANGE PASSWORD */	
	/*************************************************************************************************************************/	
	
	$("#userCheck").click(function(){
		$('#update_user_form').form('disableValidation');
		
		$("#changePassPanel").show();
		$("#changePassPanel").dialog({
			title:'Update User Password',
			autoOpen: false, 
			modal:true,
			maximizable:false,
			minimizable:false,
			collapsible:false
		});
	});
	
	$("#saved_update_user").click(function(){
		
		if($('#update_user_form').form('enableValidation').form('validate')){
			$('#update_user_form').form('submit', {
				success:function(data){
					var data2 = eval('(' + data + ')');	
					//$.messager.alert("berhasil");					
			        if(data2.status == 'SUKSES'){
						$.messager.alert('Info',data2.pesan,'info',function(){
							window.location.replace(base_url+"login/logout");
						});
						//$.messager.alert('Info',data2.pesan);
						//$("#changePassPanel").dialog('close');
						//window.location.replace(base_url);
					}else{
						$.messager.alert('Info',data2.pesan,'error'); 
					}
			    }
			});
		}
	});
	
	/*************************************************************************************************************************/	
	/* HOME */	
	/*************************************************************************************************************************/	

	$('#HM').click(function(){
		window.location.replace(base_url);
	});
	
	/*************************************************************************************************************************/	
	/* TO DOWNLOAD BPB */	
	/*************************************************************************************************************************/	

	$('#DBPB').click(function(){
		window.location.replace(base_url+"dbpb");
	});

	
	
	/*************************************************************************************************************************/	
	/* MASTER BANK */	
	/*************************************************************************************************************************/	
	
	$("#masterBank_btn").click(function(){
		//$('#masterBank_form').form('disableValidation');
		
		$("#masterBank").show();
		$("#masterBank").dialog({
			title:'Update User Password',
			autoOpen: false, 
			modal:true,
			maximizable:false,
			minimizable:false,
			collapsible:false
		});
	});

	/*************************************************************************************************************************/	
	/* SHIFT */	
	/*************************************************************************************************************************/	

	$('#sub_shift').click(function(event) {
		event.preventDefault();
		var user_id = $('#user_id').val();
		var no_ref = $('#ref_num').textbox('getValue');
		var no_shift = $('#col_shift').combobox('getValue');
		if ($('#dc_shift').length) {
			var dc_shift = $('#dc_shift').combobox('getValue');
		}else {
			var dc_shift = 'N';
		}
		$.ajax({
			type: 'POST',
			url: base_url+'Login/set_shift/',
			data: {
				'user_id': user_id,
				'no_ref': no_ref,
				'no_shift': no_shift,
				'dc_shift': dc_shift
			},
			success:function (msg) {
				$('#form_shift').window('close');
				window.location.replace(base_url);
			}
		});
	});

	$('#cl_shift').click(function(event) {
		event.preventDefault();
		$('#form_cl_shift').window('open');
	});

	$('#ya_cl').click(function(event) {
		event.preventDefault();
		var user_id = $(this).attr('userid');
		var check = 0;
		$.ajax({
			type: 'POST',
			url: base_url+'InputBatch/check_data_receipts/',
			data: {
				'user_id': user_id
			},
			success:function (msg) {
				check = msg;
				if (check == 0) {
					$.ajax({
						type: 'POST',
						url: base_url+'Login/del_shift/',
						data: {
							'user_id': user_id
						},
						success:function (msg) {
							window.location.replace(base_url);
						}
					});
				}
				else{
					$('#form_cl_shift').window('close');
					$.messager.show({
						title:'Alert',
						msg:'Mohon untuk Generate Batch dari Receipts yang ada.',
						timeout:3000,
						showType:'show',
						style:{
							right:'',
							top:document.body.scrollTop+document.documentElement.scrollTop,
							bottom:''
						}
					});
				}
			}
		});
	});

	$('#no_cl').click(function(event) {
		event.preventDefault();
		$('#form_cl_shift').window('close');
	});

	$('#col_branch').combobox({
		url: base_url+'Login/admin_choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#col_branch').combobox({
		onChange: function (value) {
			$('#col_dc_code').combobox({
				url: base_url+'Login/admin_choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$("#sub_admin_branch").click(function(event) {
		event.preventDefault();
		if ($('#col_branch').combobox('getValue') != '' && $('#col_dc_code').combobox('getValue') != '') {
			$.ajax({
				type: 'POST',
				url: base_url+'Login/set_admin_branch/',
				data: {
					'branch': $('#col_branch').combobox('getValue'),
					'dc': $('#col_dc_code').combobox('getValue')
				},
				success:function (msg) {
					window.location.replace(base_url);
				}
			});
		}else{
			$.messager.alert('Warning','Harap memilih cabang dan kode gudang.','info');
		}
	});

	$("#cl_branch").click(function(event) {
		$("#form_choose_branch_ch").window('open');
	});


	$('#summary_collect_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#summary_collect_branch').combobox({
		onChange: function (value) {
			$('#summary_collect_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$('#monitoring_voucher_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#monitoring_voucher_branch').combobox({
		onChange: function (value) {
			$('#monitoring_voucher_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$('#monitoring_voucher_dc_code').combobox({
		onChange: function (value) {
			$('#combo_batch_num').combobox({
		        url:base_url+'Report/get_batch_num/'+$('#monitoring_voucher_dc_code').combobox('getValue')+'/'+$('#monitoring_voucher_branch').combobox('getValue'),
		        valueField:'CDC_BATCH_NUMBER',
		        textField:'CDC_BATCH_NUMBER',
				formatter: format_combo_batch_num
	        });
		}
	});

	$('#listing_gtu_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#listing_gtu_branch').combobox({
		onChange: function (value) {
			$('#listing_gtu_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$('#monitoring_voucher_perToko_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#monitoring_voucher_perToko_branch').combobox({
		onChange: function (value) {
			$('#monitoring_voucher_perToko_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$('#monitoring_voucher_perToko_dc_code').combobox({
		onChange: function (value) {
			$('#combo_monitoring_voucher_perToko').combobox({
				url:base_url+'Report/get_toko_monitoring_voucher/'+$('#monitoring_voucher_perToko_branch').combobox('getValue')+'/'+$('#monitoring_voucher_perToko_dc_code').combobox('getValue'),
				valueField:'STORE_ID',
				textField:'STORE',
				formatter: format_combo_monitoring_voucher_perToko
			});
		}
	});

	$('#receipt_register_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#receipt_register_branch').combobox({
		onChange: function (value) {
			$('#receipt_register_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$('#receipt_register_dc_code').combobox({
		onChange: function (value) {
			$('#combo1_receipt_register').combobox({
				url:base_url+'Report/get_receipt_register_toko/'+$('#receipt_register_branch').combobox('getValue')+'/'+$('#receipt_register_dc_code').combobox('getValue'),
				valueField:'STORE_CODE',
				textField:'STORE',
				formatter: format_combo_receipt_register1
			});	
			
			$('#combo2_receipt_register').combobox({
				url:base_url+'Report/get_receipt_register_toko/'+$('#receipt_register_branch').combobox('getValue')+'/'+$('#receipt_register_dc_code').combobox('getValue'),
				valueField:'STORE_CODE',
				textField:'STORE',
				formatter: format_combo_receipt_register2
			});
		}
	});

	$('#diff_journal_branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});
	
	$('#diff_journal_branch').combobox({
		onChange: function (value) {
			console.log(value)
			console.log('test')
			$('#diff_journal_dc_code').combobox({
				url: base_url+'Report/choose_dc/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

});