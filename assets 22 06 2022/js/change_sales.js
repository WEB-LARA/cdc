$(document).ready(function(){
	$("#modal_edit_sales_date").window('close');

	$("#data-sales-receipts").datagrid({
		url: 'get_data_sales_rec/',
        striped: true,
	    rownumbers:true,
	    remoteSort:true,
	    singleSelect:true,
	    pagination:true,
	    fit:false,
	    autoRowHeight:false,
	    fitColumns:true,
	    toolbar :'#toolbar',
		onDblClickRow: function () {
			var row = $(this).datagrid('getSelected');
			$("#rec-store-code").textbox('setValue', row.STORE);
			$("#rec-sales-date").textbox('setValue', row.SALES_DATE);
			$('#rec-store-code-new').combobox('setValue', row.STORE_ID);
			$("#rec-sales-date-new").datebox('setValue', '');
			$("#ch-rec-id").val(row.CDC_REC_ID);
			$("#ch-stn-flag").val(row.STN_FLAG);
			$("#ch-act-sales-flag").val(row.ACTUAL_SALES_FLAG);
			$("#modal_edit_sales_date").window('open');
		},
        columns:[[
        	{field:'CDC_REC_ID',hidden:true},
        	{field:'CDC_BATCH_ID',hidden:true},
        	{field:'STORE_ID',hidden:true},
        	{field:'STN_FLAG',hidden:true},
        	{field:'STORE',title:'Toko',width:150,align:"center",halign:"center"},
            {field:'SALES_DATE',title:'Tanggal Sales',width:150,align:"center",halign:"center"},
            {field:'ACTUAL_SALES_AMOUNT',title:'Nominal',width:150,align:"right",halign:"center",
	            formatter:function (value,row,index) {
	            	return Intl.NumberFormat('en-US').format(value);
	            }
        	},
        	{field:'ACTUAL_SALES_FLAG',title:'Sales/Titipan',width:100,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	if (value == 'N') {
	            		return 'Titipan';
	            	} else return 'Sales';
	            }
	        },
            {field:'CDC_BATCH_STATUS',title:'Status',width:150,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	if (value == 'N') {
	            		return 'New';
	            	} else if (value == 'V') {
	            		return 'Validated';
	            	} else return 'Transfered';
	            }
	        },
            {field:'TRANSFER_FLAG',title:'Status Transfer',width:100,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	if (value == 'N') {
	            		return 'Belum';
	            	} else return 'Sudah';
	            }
	        },
        ]]
	});

	$('#rec-sales-date-new').datebox({
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

	$("#sc-sales-rec").click(function(event) {
		event.preventDefault();
		if ($('#sc-batch-num').textbox('getValue') != '') {
			$.ajax({
				url: 'get_batch_id/'+$('#sc-batch-num').textbox('getValue'),
				method: 'POST',
				success: function (msg) {
					if (msg > 0) {
						$("#sc-batch-id").val(msg);
						$('#data-sales-receipts').datagrid('load',
							{
					        	batch_num: $('#sc-batch-num').textbox('getValue'),
					        	store_code: $("#sc-store-code").combobox('getValue'),
					        	sales_date: $("#sc-sales-date").datebox('getValue')
					        }
					    );
					}
				}
			});
		} else $.messager.alert('Warning','Mohon untuk mengisi kolom Batch Number.');
	});

	$("#sc-clear-rec").click(function(event) {
		event.preventDefault();
		$("#sc-batch-id").val('');
		$('#sc-batch-num').textbox('setValue', '');
		$("#sc-store-code").combobox('setValue', '');
		$("#sc-sales-date").datebox('setValue', '');
		$('#data-sales-receipts').datagrid('load',
			{
	        	batch_num: 'X',
	        	store_code: '',
	        	sales_date: ''
	        }
	    );
	});

	$("#sub-change-sales").click(function(event) {
		event.preventDefault();
		if ($("#rec-sales-date-new").datebox('getValue') != '') {
			$.ajax({
				url: 'cek_tanggal_sales',
				type: 'POST',
				data: {
					rec_id: $("#ch-rec-id").val(),
					store_id: $('#rec-store-code-new').combobox('getValue'),
					sales_date: $("#rec-sales-date-new").datebox('getValue'),
					stn_flag: $("#ch-stn-flag").val(),
					act_sales_flag: $("#ch-act-sales-flag").val()
				},
				success: function(msg) {
					if (msg.substring(0,1) == 'O') {
						$.messager.alert('Warning','Tanggal sales tidak dapat melebihi tanggal hari ini.');
					} else if (msg.substring(0,1) == 'F') {
						if (msg.substring(1) == 'N') {
							$.messager.alert('Warning','Data sudah ada namun belum terbentuk Batch.');
						} else $.messager.alert('Warning','Data sudah ada pada batch '+msg.substring(1)+'.');
					} else {
						$.ajax({
							url: 'change_sales',
							type: 'POST',
							data: {
								rec_id: $("#ch-rec-id").val(),
								store_id: $('#rec-store-code-new').combobox('getValue'),
								sales_date: $("#rec-sales-date-new").datebox('getValue'),
								stn_flag: $("#ch-stn-flag").val(),
								act_sales_flag: $("#ch-act-sales-flag").val()
							},
							success: function(msg) {
								if (msg > 0) {
									$("#data-sales-receipts").datagrid('reload');
									$("#modal_edit_sales_date").window('close');
									$.messager.alert('Warning','Data sales berhasil diupdate.');
								}
							}
						});
						
					}
				}
			});
		} else $.messager.alert('Warning','Mohon untuk mengisi kolom Tanggal Sales Baru.');
	});
});