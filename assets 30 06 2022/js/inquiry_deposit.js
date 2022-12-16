$(document).ready(function(){
	$('#detail_deposit').window('close');
	$('#valdep').window('close');
	$('#deldep').window('close');
	$('#notdeldep').window('close');
	$('#rejdep').window('close');
	$('#notrejdep').window('close');
	$('#transdep').window('close');
	$('#nottransdep').window('close');
	$("#prog-trans-dep").window('close');
	$("#report-dep-excel").window('close');

	$("#ok_trans").click(function(event) {
		$('#nottransdep').window('close');
		location.reload();
	});

	$('#data_inquiry_deposit').datagrid({
        url: 'get_data_deposit_validate',
        striped: true,
        rownumbers:true,
		remoteSort:false,
		pagination:true,
		singleSelect:true,
		fit:true,
		autoRowHeight:false,
		fitColumns:true,
		toolbar :'#toolbar',
		onDblClickRow: function () {
			var rows = $(this).datagrid('getSelections');
			$('#detail_deposit').window('open');
			$('#bank').textbox('setValue',rows[0].bank_name);
			$('#data_batch').datagrid({
		        url: 'get_data_batch_val/' + rows[0].bank_id + '/' + rows[0].deposit_id,
		        striped: true,
		        rownumbers:true,
				remoteSort:false,
				singleSelect:false,
				fit:false,
				autoRowHeight:false,
				fitColumns:true,
				toolbar :'#toolbar',
				selectOnCheck:false,
				checkOnSelect:false,
		        columns:[[
		        	{field:'batch_id',hidden:true},
		        	{field:'deposit_id',hidden:true},
		            {field:'batch_number',title:'Batch Number',width:120,align:"center",halign:"center"},
		            {field:'reference_num',title:'Reference Num',width:150,align:"center",halign:"center"},
		            {field:'batch_type',title:'Batch Type',width:100,align:"center",halign:"center"},
		            {field:'user_name',title:'Username',width:100,align:"center",halign:"center"},
		            {field:'batch_date',title:'Batch Date',width:180,align:"center",halign:"center"},
		            {field:'actual_total_amount',title:'Actual Total Amount',width:200,align:"right",halign:"center",
			            formatter:function (value,row,index) {
			            	return Intl.NumberFormat('en-US').format(value);
			            }
		        	},
		            {field:'check_exchanges_total_amount',title:'Check Exc Total Amount',width:200,align:"right",halign:"center",
			            formatter:function (value,row,index) {
			            	return Intl.NumberFormat('en-US').format(value);
			            }
		        	},
		            {field:'deposit',title:'Deposit',width:150,align:"right",halign:"center",
			            formatter:function (value,row,index) {
			            	return Intl.NumberFormat('en-US').format(value);
			            }
		        	}
		        ]]
		    });
			$('#deposit_num').textbox('setValue',rows[0].deposit_num);
			/*var date = new Date(Date.UTC(rows[0].deposit_date.substring(0,4), parseInt(rows[0].deposit_date.substring(5,7))-1, parseInt(rows[0].deposit_date.substring(8)), 3, 0, 0));
        	options = {year: 'numeric', month: 'long', day: 'numeric'};*/
			$('#deposit_date').textbox('setValue',rows[0].deposit_date);
			/*var date = new Date(Date.UTC(rows[0].mutation_date.substring(0,4), parseInt(rows[0].mutation_date.substring(5,7))-1, parseInt(rows[0].mutation_date.substring(8)), 3, 0, 0));
        	options = {year: 'numeric', month: 'long', day: 'numeric'};*/
			$('#mutation_date').textbox('setValue',rows[0].mutation_date);
			var status = "";
			if ($('#user_role').val() > 2) {
				if (rows[0].status == "V") {
					status = "Validated";
					$('#val_deposit').hide();
					$('#del_deposit').hide();
					$('#rej_deposit').show();
					$('#rej_deposit').attr('depid',rows[0].deposit_id);
					$('#rej_deposit').attr('depnum',rows[0].deposit_num);

					$('#transfer_deposit').show();
					$('#transfer_deposit').attr('depid',rows[0].deposit_id);
					$('#transfer_deposit').attr('depnum',rows[0].deposit_num);
				}else if(rows[0].status == "N") {
					status = "New";
					$('#val_deposit').show();
					$('#val_deposit').attr('depid',rows[0].deposit_id);
					$('#del_deposit').show();
					$('#del_deposit').attr('depid',rows[0].deposit_id);
					$('#del_deposit').attr('depnum',rows[0].deposit_num);
					$('#rej_deposit').hide();
				}else {
					status = "Transfered";
					$('#val_deposit').hide();
					$('#del_deposit').hide();
					$('#rej_deposit').hide();
					$('#transfer_deposit').hide();
				}
			}
			$('#status').textbox('setValue',status);
			$('#ats').numberbox('setValue',rows[0].ats);
			$('#cts').numberbox('setValue',rows[0].cts);
			$('#dts').numberbox('setValue',rows[0].dts);
		},
		columns:[[
        	{field:'deposit_id',hidden:true},
        	{field:'bank_id',hidden:true},
        	{field:'cetakan_ke',hidden:true},
        	{field:'deposit_num',title:'Deposit Number',width:150,align:"center",halign:"center"},
            {field:'bank_name',title:'Bank',width:120,align:"center",halign:"center"},
            {field:'deposit_date',title:'Deposit Date',width:100,align:"center",halign:"center"},
            {field:'mutation_date',title:'Mutation Date',width:100,align:"center",halign:"center"},
            {field:'status',title:'Status',width:100,align:"center",halign:"center",
            	formatter:function (value,row,index) {
            		if (value == "N") {
            			return "New";
            		}else if (value == "V") {
            			return "Validated";
            		}else return "Transfered";
            	}
            },
            {field:'created_by',title:'Username',width:100,align:"center",halign:"center"},
            {field:'ats',title:'Actual Total Amount',width:100,align:"right",halign:"center",
            	formatter:function (value,row,index) {
	            	return Intl.NumberFormat('en-US').format(value);
	            }
    		},
            {field:'cts',title:'Check Total Amount',width:100,align:"right",halign:"center",
            	formatter:function (value,row,index) {
	            	return Intl.NumberFormat('en-US').format(value);
	            }
        	},
            {field:'dts',title:'Deposit Total Amount',width:100,align:"right",halign:"center",
            	formatter:function (value,row,index) {
	            	return Intl.NumberFormat('en-US').format(value);
	            }
    		}
        ]]
    });

	$('#mutation_date').textbox({
		formatter:function (value) {
	    	var date = new Date(value);
	    	options = {
				  year: 'numeric', month: 'long', day: 'numeric'
				};
			return Intl.DateTimeFormat('id-ID', options).format(date);
	    }
	});

	$('#deposit_date').textbox({
		formatter:function (value) {
	    	var date = new Date(value);
	    	options = {
				  year: 'numeric', month: 'long', day: 'numeric'
				};
			return Intl.DateTimeFormat('id-ID', options).format(date);
	    }
	});

	$('#print_deposit').click(function (event) {
		event.preventDefault();
		var rows = $('#data_inquiry_deposit').datagrid('getSelections');
		$.messager.defaults.ok = 'PDF';
		$.messager.defaults.cancel = 'Excel';
		$.messager.confirm('Confirm','Pilih bentuk Report?',function(r){
		    if (r){
		        window.open('print_deposit/'+rows[0].deposit_id+'/'+rows[0].bank_id+'/P', "Print Deposit", "width=600,height=600,scrollbars=yes");
		    }else{
		    	//window.open('print_deposit/'+rows[0].deposit_id+'/'+rows[0].bank_id+'/X', "Print Deposit", "width=600,height=600,scrollbars=yes");
		    	$("#report-dep-excel").window('open');
		    }
		});
	});

	$("#btn-print-excel").click(function(event) {
		event.preventDefault();
		var rows = $('#data_inquiry_deposit').datagrid('getSelected');
		var format = $("#format-print-excel").combobox('getValue');
		if (format != 'Default') {
			window.open('print_deposit_other_format/'+rows.deposit_id+'/'+rows.bank_id+'/'+format, "Print Deposit", "width=600,height=600,scrollbars=yes");
		}else {
			window.open('print_deposit/'+rows.deposit_id+'/'+rows.bank_id+'/X', "Print Deposit", "width=600,height=600,scrollbars=yes");
		}
	});

	$('#deposit_date_sc').datebox({
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

	$('#mutation_date_sc').datebox({
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

	$('#refresh').click(function (event) {
		event.preventDefault();
		$('#data_inquiry_deposit').datagrid('load',{
	        deposit_num_sc: '',
	        deposit_date_sc: '',
	        mutation_date_sc: ''
	    });
	});

	$('#val_deposit').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: 'validate_deposit',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success: function(msg) {
				if (msg > 0) {
					$('#valdep').window('open');
					location.reload();
					$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
				};
			}
		});
	});

	$('#del_deposit').click(function(event) {
		event.preventDefault();
		var depnum = $(this).attr('depnum');
		$('#dep_num_del').text('"'+depnum+'"');
		$('#yes_del').attr('depid',$(this).attr('depid'));
		$('#deldep').window('open');
	});

	$('#yes_del').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: 'delete_deposit',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success: function(msg) {
				if (msg > 0) {
					$('#deldep').window('close');
					$('#notdeldep').window('open');
					location.reload();
					$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
				};
			}
		});
	});

	$('#no_del').click(function(event) {
		event.preventDefault();
		$('#deldep').window('close');
	});

	$('#bank_sc').combobox({
        url:'getBankSc',
        valueField:'id',
        textField:'name'
    });

    $('#rej_deposit').click(function(event) {
		event.preventDefault();
		var depnum = $(this).attr('depnum');
		$('#dep_num_rej').text('"'+depnum+'"');
		$('#yes_rej').attr('depid',$(this).attr('depid'));
		$('#rejdep').window('open');
	});

	$('#yes_rej').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: 'reject_deposit',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success: function(msg) {
				if (msg > 0) {
					$('#rejdep').window('close');
					$('#notrejdep').window('open');
					location.reload();
					$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
				};
			}
		});
	});

	$('#no_rej').click(function(event) {
		event.preventDefault();
		$('#rejdep').window('close');
	});

	$('#transfer_deposit').click(function(event) {
		event.preventDefault();
		var depnum = $(this).attr('depnum');
		$('#dep_num_trans').text('"'+depnum+'"');
		$('#yes_trans').attr('depid',$(this).attr('depid'));
		$('#yes_trans').attr('depnum',$(this).attr('depnum'));
		$('#transdep').window('open');
	});

	$('#yes_trans').click(function(event) {
		var depnum = $(this).attr('depnum');
		var depid=$(this).attr('depid');
		$("#prog-trans-dep").window('open');
		$.ajax({
					url:base_url+'InputDeposit/get_batch_type/',
					method: 'POST',
					data:{
						depid: $(this).attr('depid')
					},
					success:function(msg) {
						var cek=msg;
						$("#prog-trans-dep").window('close');
						if(cek!=0){
							$("#prog-trans-dep").window('open');
							$.messager.alert('Alert','Transfer setoran STL tidak membentuk jurnal di oracle.','info');
				    			$.ajax({
									url: 'transfer_deposit',
									type: 'POST',
									data:{
										depid: depid
									},
									success: function(msg) {
										if (msg > 0) {
											$("#prog-trans-dep").window('close');
											$('#transdep').window('close');
											$('#detail_deposit').window('close');
											$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
											$('#trans_suc').text(''+depnum+'');
											$('#nottransdep').window('open');
										}else{
											$("#prog-trans-dep").window('close');
											$('#transdep').window('close');
											$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
											$.messager.alert('Alert','Trasnfer gagal, harap untuk menghubungi IT Support SD 6.','info');
										}
										$("#prog-trans-dep").window('close');
									}
								});
							
						}else{
							$("#prog-trans-dep").window('open');
							$.ajax({
									url: 'transfer_deposit',
									type: 'POST',
									data:{
										depid: depid
									},
									success: function(msg) {
										if (msg > 0) {
											$("#prog-trans-dep").window('close');
											$('#transdep').window('close');
											$('#detail_deposit').window('close');
											$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
											$('#trans_suc').text(''+depnum+'');
											$('#nottransdep').window('open');
										}else{
											$("#prog-trans-dep").window('close');
											$('#transdep').window('close');
											$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
											$.messager.alert('Alert','Trasnfer gagal, harap untuk menghubungi IT Support SD 6.','info');
										}
									}
								});
							$("#prog-trans-dep").window('close');
						}
						
						

					}
				});
		
		
		/*event.preventDefault();
		var depnum = $(this).attr('depnum');
		$("#prog-trans-dep").window('open');
		$.ajax({
			url: 'cek_validasi_virtual',
			type: 'POST',
			data: {depid: $(this).attr('depid')},
			success: function(msg) {
				if (msg != 'Y') {
					$.ajax({
						url: 'transfer_deposit',
						type: 'POST',
						data:{
							depid: $('#yes_trans').attr('depid'),
							vir_status: msg
						},
						success: function(msg) {
							if (msg > 0) {
								$("#prog-trans-dep").window('close');
								$('#transdep').window('close');
								$('#detail_deposit').window('close');
								$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
								$('#trans_suc').text(''+depnum+'');
								$('#nottransdep').window('open');
							}else{
								$("#prog-trans-dep").window('close');
								$('#transdep').window('close');
								$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
								$.messager.alert('Alert','Trasnfer gagal, harap untuk menghubungi IT Support SD 6.','info');
							}
						}
					});
				} else {
					$("#prog-trans-dep").window('close');
					$.messager.alert('Alert','Transfer tidak dapat dilakukan karena deposit ini, belum dilakukan validasi kurset virtual.','info');
				}
			}
		});*/
	});

	$('#no_trans').click(function(event) {
		event.preventDefault();
		$('#transdep').window('close');
	});

});

function doSearch(){
    $('#data_inquiry_deposit').datagrid('load',{
    	bank_sc: $('#bank_sc').combobox('getValue'),
        deposit_num_sc: $('#deposit_num_sc').val(),
        deposit_date_sc: $('#deposit_date_sc').datebox('getValue'),
        mutation_date_sc: $('#mutation_date_sc').datebox('getValue'),
        status_sc: $('#status_sc').combobox('getValue'),
        username_sc: $('#username_sc').textbox('getValue')
    });
}