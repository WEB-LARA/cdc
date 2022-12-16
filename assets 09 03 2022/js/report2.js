$(document).ready(function(){
	$('#req_listing_gtu').window('close');	
	$('#req_monitoring_kodel').window('close');
	$('#req_penerimaan_sales').window('close');
	$('#req_receipt_sales').window('close');
	$('#req_monitoring_voucher_perToko').window('close');
	$('#req_pending_sales').window('close');
	$('#req_receipt_register').window('close');
	$('#req_sales_tgl_am').window('close');
	
	
	$('#report_listing_gtu').click(function(event) {
		event.preventDefault();
		$('#listing_gtu_branch').combobox('select', $("#listing_gtu_branch").attr('branch-id'));
        $('#listing_gtu_dc_code').combobox('select', $("#listing_gtu_dc_code").attr('dc-code'));
		$('#date1_listing_gtu').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});
		  
		$('#date2_listing_gtu').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});	   
		$('#req_listing_gtu').window('open');		
	})
	
	$('#sub_listing_gtu').click(function(event){
	   var from = $('#date1_listing_gtu').datebox('getValue');
	   var to	= $('#date2_listing_gtu').datebox('getValue');		
	   
	   if( from != '' && to != ''){
	  		//alert(from+'\n'+to);
			window.open(base_url+'Report/print_listing_gtu/'+from+'/'+to+'/'+$("#listing_gtu_branch").combobox('getValue')+'/'+$("#listing_gtu_dc_code").combobox('getValue'), "Report Listing GTU", "width=600,height=600,scrollbars=yes");
	   }
	   else{
		   alert('Error : Tanggal Harus Diisi');	 
	   }
	})	
/*======================================================================*/	
	

	$('#report_monitoring_kodel').click(function(event) {
		event.preventDefault();
		$('#date1_monitoring_kodel').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});	
		$('#req_monitoring_kodel').window('open');	
	});
	
	$('#sub_monitoring_kodel').click(function(event){
	   var from = $('#date1_monitoring_kodel').datebox('getValue');
	   var barang = $('#status_kirim_barang').combobox('getValue');
	   var kodel = $('#status_kodel').combobox('getValue');
	
		if(from != ''){
			window.open(base_url+'Report/print_monitoring_kodel/'+from+'/'+barang+'/'+kodel, "Report Monitoring Penerimaan Kodel", "width=600,height=600,scrollbars=yes");	
		}else{
			alert('Error : Tanggal Harus Diisi');	 
		}	
		//alert(from+'\n'+barang+'\n'+kodel);

	})	
/*======================================================================*/	


	$('#report_penerimaan_sales').click(function(event) {
		event.preventDefault();
		$('#date1_penerimaan_sales').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});	
		$('#req_penerimaan_sales').window('open');	
	});	
	
	$('#sub_penerimaan_sales').click(function(event){
	   var from = $('#date1_penerimaan_sales').datebox('getValue');
	   var pending = $('#sales_pending_flag').combobox('getValue');
	
		//alert(from+'\n'+barang+'\n'+kodel);
	  	if(from != ''){
window.open(base_url+'Report/print_penerimaan_sales/'+from+'/'+pending, "Report Monitoring Penerimaan Sales Toko", "width=600,height=600,scrollbars=yes");		
		}else{
			alert('Error : Tanggal Harus Diisi');	 
		}
		
	})	
/*======================================================================*/	
	
	$('#report_receipt_sales').click(function(event) {
		event.preventDefault();
		$('#date1_receipt_sales').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});
		$('#req_receipt_sales').window('open');		
	})
	
	$('#sub_receipt_sales').click(function(event){
	   var from = $('#date1_receipt_sales').datebox('getValue');	
	   
	   if( from != '' ){
	  		//alert(from);
			window.open(base_url+'Report/print_receipt_sales_qty/'+from, "Report Receipt Sales (Qty)-Handheld", "width=600,height=600,scrollbars=yes");
	   }
	   else{
		   alert('Error : Tanggal Harus Diisi');	 
	   }
	})		
	
/*======================================================================*/		
	$('#report_monitoring_voucher_perToko').click(function(event) {
		event.preventDefault();
		/*$('#combo_monitoring_voucher_perToko').combobox({
			 url:base_url+'Report/get_toko_monitoring_voucher',
			 valueField:'STORE_ID',
			 textField:'STORE',
			 formatter: format_combo_monitoring_voucher_perToko
		});*/
		$('#monitoring_voucher_perToko_branch').combobox('select', $("#monitoring_voucher_perToko_branch").attr('branch-id'));
        $('#monitoring_voucher_perToko_dc_code').combobox('select', $("#monitoring_voucher_perToko_dc_code").attr('dc-code'));		
		  
		$('#date1_monitoring_voucher_perToko').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});
		  
		$('#date2_monitoring_voucher_perToko').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});	   
		$('#req_monitoring_voucher_perToko').window('open');		
	})
	
	$('#sub_monitoring_voucher_perToko').click(function(event){
	   var toko = $('#combo_monitoring_voucher_perToko').combobox('getValue');
	   var from = $('#date1_monitoring_voucher_perToko').datebox('getValue');
	   var to	= $('#date2_monitoring_voucher_perToko').datebox('getValue');		
	   
	   if( toko != '' && from != '' && to != ''){
	  		//alert(toko+'\n'+from+'\n'+to);
			window.open(base_url+'Report/print_monitoring_voucher_perToko/'+toko+'/'+from+'/'+to+'/'+$("#monitoring_voucher_perToko_branch").combobox('getValue')+'/'+$("#monitoring_voucher_perToko_dc_code").combobox('getValue'), "Report Listing GTU", "width=600,height=600,scrollbars=yes");
	   }
	   else{
		   alert('Error : Toko dan Tanggal Harus Diisi');	 
	   }
	})		
	
/*======================================================================*/		
	

	$('#report_pending_sales').click(function(event) {
		event.preventDefault();
		$('#date1_pending_sales').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});
		$('#req_pending_sales').window('open');		
	})
	
	$('#sub_pending_sales').click(function(event){
	   var from = $('#date1_pending_sales').datebox('getValue');
	   var include_go = $('#combo_pending_flag').combobox('getValue');		
	   
	   if( from != '' ){
	  		//alert(from+'\n'+include_go);
			window.open(base_url+'Report/print_pending_sales/'+from+'/'+include_go, "Report Pending Sales", "width=600,height=600,scrollbars=yes");
	   }
	   else{
		   alert('Error : Tanggal Harus Diisi');	 
	   }
	})		
		
/*======================================================================*/		


	
	$('#sub_receipt_register').click(function(event){
		$.messager.defaults.ok = 'PDF';
		$.messager.defaults.cancel = 'Excel';
		$.messager.confirm('Confirm','Pilih format report?',function(r){
			if (r){
				var from = $('#date1_receipt_register').datebox('getValue');	
			    var to	= $('#date2_receipt_register').datebox('getValue');	
			    var toko1 = $('#combo1_receipt_register').combobox('getValue');	
			    var toko2 = $('#combo2_receipt_register').combobox('getValue');	
			    
			    if( from != '' || to != '' ){
				    if( toko1 != '' && toko2 == ''){
				 	    alert('Error : Toko harus lengkap');
				    }
				    if( toko1 == '' && toko2 != ''){
					    alert('Error : Toko harus lengkap');
				    }
				    if( toko1 == '' && toko2 == ''){
						//alert(from+'\n'+to+'\n'+toko1+'\n'+toko2);
						window.open(base_url+'Report/print_receipt_register/'+from+'/'+to+'/'+toko1+'/'+toko2+'/'+$("#receipt_register_branch").combobox('getValue')+'/'+$("#receipt_register_dc_code").combobox('getValue'), "Report Pending Sales", "width=600,height=600,scrollbars=yes");	
				    }
				    if( toko1 != '' && toko2 != ''){
						//alert(from+'\n'+to+'\n'+toko1+'\n'+toko2);
						window.open(base_url+'Report/print_receipt_register/'+from+'/'+to+'/'+toko1+'/'+toko2+'/'+$("#receipt_register_branch").combobox('getValue')+'/'+$("#receipt_register_dc_code").combobox('getValue'), "Report Pending Sales", "width=600,height=600,scrollbars=yes");	
				    }   
			    }
			    else{
					alert('Error : Tanggal Harus Diisi');	 
			    }
			}else{
				var from = $('#date1_receipt_register').datebox('getValue');	
			    var to	= $('#date2_receipt_register').datebox('getValue');	
			    var toko1 = $('#combo1_receipt_register').combobox('getValue');	
			    var toko2 = $('#combo2_receipt_register').combobox('getValue');	
			    
			    if( from != '' || to != '' ){
				    if( toko1 != '' && toko2 == ''){
				 	    alert('Error : Toko harus lengkap');
				    }
				    if( toko1 == '' && toko2 != ''){
					    alert('Error : Toko harus lengkap');
				    }
				    if( toko1 == '' && toko2 == ''){
						//alert(from+'\n'+to+'\n'+toko1+'\n'+toko2);
						window.open(base_url+'Report/print_receipt_register_excel/'+from+'/'+to+'/'+toko1+'/'+toko2+'/'+$("#receipt_register_branch").combobox('getValue')+'/'+$("#receipt_register_dc_code").combobox('getValue'), "Report Pending Sales", "width=600,height=600,scrollbars=yes");	
				    }
				    if( toko1 != '' && toko2 != ''){
						//alert(from+'\n'+to+'\n'+toko1+'\n'+toko2);
						window.open(base_url+'Report/print_receipt_register_excel/'+from+'/'+to+'/'+toko1+'/'+toko2+'/'+$("#receipt_register_branch").combobox('getValue')+'/'+$("#receipt_register_dc_code").combobox('getValue'), "Report Pending Sales", "width=600,height=600,scrollbars=yes");	
				    }   
			    }
			    else{
					alert('Error : Tanggal Harus Diisi');	 
			    }
			}
		});
	})		
		
/*======================================================================*/	


	
	$('#report_sales_tgl_am').click(function(event) {
		event.preventDefault();
	
		$('#combo1_sales_tgl_am').combobox({
			 url:base_url+'Report/get_sales_tgl_am',
			 valueField:'AM_SHORT',
			 textField:'AM',
			 formatter: format_combo1_sales_tgl_am
		});		
		
		$('#date1_sales_tgl_am').datebox({
			formatter : function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			},
			parser : function(s){
				if (!s) return new Date();
				var ss = s.split('-');
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
					return new Date(y,m-1,d);
				} else {
					return new Date();
				}
			}
		});
		
				
		$('#req_sales_tgl_am').window('open');		
	})
	
	$('#sub_sales_tgl_am').click(function(event){
	   var from = $('#date1_sales_tgl_am').datebox('getValue');		
	   var am = $('#combo1_sales_tgl_am').combobox('getValue');	
	   var status = $('#combo2_sales_tgl_am').combobox('getValue');
	   
	   if(am == ''){
	   	var am = 'all';
	   }
	   
	   if( from != '' ){
	  		//alert(from+'\n'+am+'\n'+status);
			window.open(base_url+'Report/print_sales_tgl_am/'+from+'/'+am+'/'+status, "Report Sales Toko per Tanggal per Am AS", "width=600,height=600,scrollbars=yes");	
	   }
	   else{
			alert('Error : Tanggal Harus Diisi');	 
	   }
	})		
		
/*======================================================================*/	


})



function format_combo_monitoring_voucher_perToko(row){
	var s = '<span style="font-weight:bold">' + row.STORE + '</span><br/>' +
			'<span style="color:#888">' + row.desc + '</span>' 
	;
	return s;
}

function format_combo_receipt_register1(row){
	var s = '<span style="font-weight:bold">' + row.STORE + '</span><br/>' +
			'<span style="color:#888">' + row.desc + '</span>' 
	;
	return s;
}

function format_combo_receipt_register2(row){
	var s = '<span style="font-weight:bold">' + row.STORE + '</span><br/>' +
			'<span style="color:#888">' + row.desc + '</span>' 
	;
	return s;
}

function format_combo1_sales_tgl_am(row){
	var s = '<span style="font-weight:bold">' + row.AM_SHORT + '</span><br/>' +
			'<span style="color:#888">' + row.AM + '</span>' 
	;
	return s;
}