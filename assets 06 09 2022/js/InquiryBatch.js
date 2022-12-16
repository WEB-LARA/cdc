$.messager.defaults.ok = 'Ya';
$.messager.defaults.cancel = 'Tidak';


$(document).ready(function(){
	$('#cari').linkbutton('resize', {
		width: '120%',
		height: 32
	});	
	$('#reset').linkbutton('resize', {
		width: '120%',
		height: 32
	});		
	
	/*$('#batch_status').combobox({
		onSelect: function(param){
			if( $('#batch_status').combobox('getValue')=='N' ){
				$('#inquiryPrint').linkbutton('enable');
				$('#inquiryReject').linkbutton('enable');
			}
			if( $('#batch_status').combobox('getValue')=='R' ){
				$('#inquiryPrint').linkbutton('enable');
				$('#inquiryReject').linkbutton('disable');
			}
			if( $('#batch_status').combobox('getValue')=='V' ){
				$('#inquiryPrint').linkbutton('disable');
				$('#inquiryReject').linkbutton('disable');
			}
		}
	});*/
	
	$('#batch_status').combobox('setValue','N');
	$("#transferSTN").linkbutton('disable');
	$("#prog-trans").window('close');
	
	$('#batch_date').datebox({
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
	
	//Entry batch datagrid	
	$('#tblTrxBatch').datagrid({
		url:base_url+'InquiryBatch/getBatch',
		onSelect: function () {
			var rows = $(this).datagrid('getSelections');
			var status = 0;
			for (var i = rows.length - 1; i >= 0; i--) {
				if (rows[i].CDC_BATCH_TYPE.substring(2) != 'STN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
					if (rows[i].CDC_BATCH_TYPE.substring(2) != 'KUN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
						if (rows[i].CDC_BATCH_TYPE.substring(2) != '-TR' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
							status = 1;
						}
					}
				}
			}
			if (status == 1) {
				$("#transferSTN").linkbutton('disable');
			}else $("#transferSTN").linkbutton('enable');
		},
		onSelectAll: function () {
			var rows = $(this).datagrid('getSelections');
			var status = 0;
			for (var i = rows.length - 1; i >= 0; i--) {
				if (rows[i].CDC_BATCH_TYPE.substring(2) != 'STN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
					if (rows[i].CDC_BATCH_TYPE.substring(2) != 'KUN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
						if (rows[i].CDC_BATCH_TYPE.substring(2) != '-TR' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
							status = 1;
						}
					}
				}
			}
			if (status == 1) {
				$("#transferSTN").linkbutton('disable');
			}else $("#transferSTN").linkbutton('enable');
		},
		onUnselect: function () {
			var rows = $(this).datagrid('getSelections');
			if (rows.length != 0) {
				var status = 0;
				for (var i = rows.length - 1; i >= 0; i--) {
					if (rows[i].CDC_BATCH_TYPE.substring(2) != 'STN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
						if (rows[i].CDC_BATCH_TYPE.substring(2) != 'KUN' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
							if (rows[i].CDC_BATCH_TYPE.substring(2) != '-TR' || rows[i].CDC_BATCH_STATUS != 'VALIDATE') {
								status = 1;
							}
						}
					}
				}
				if (status == 1) {
					$("#transferSTN").linkbutton('disable');
				}else $("#transferSTN").linkbutton('enable');
			}else $("#transferSTN").linkbutton('disable');
		},
		onUnselectAll: function () {
			$("#transferSTN").linkbutton('disable');
		},
		columns:[[
			{field:'ck',checkbox:true},
			{field:'CDC_BATCH_ID',			hidden:true},
			{field:'CDC_BATCH_NUMBER',		title:'Batch Number',width:100,align:'center'},
			{field:'CDC_BATCH_TYPE',		title:'Batch Type',width:100,align:'center'},
			{field:'CDC_BATCH_DATE',		title:'Tgl Batch',width:100,align:'center',
           		formatter:function (value,row,index) {
	            	var date = new Date(Date.UTC(value.substring(0,4), parseInt(value.substring(5,7)-1), parseInt(value.substring(8)), 3, 0, 0));
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }			
			},
			{field:'CDC_BATCH_STATUS',		title:'Status',width:80,align:'center'},
			{field:'BRANCH_CODE',		title:'Branch Code',width:80,align:'center'},
			{field:'BRANCH_NAME',		title:'Branch Name',width:90,align:'center'},
			{field:'CREATED_BY',			title:'Create By',width:120,align:'center'},
			{field:'TOTAL_SETOR',		title:'Total Setor',width:100,align:'right',
				formatter:function (value,row,index) {
					return Intl.NumberFormat('en-US').format(value);
				}
			},
			{field:'LAST_UPDATE_DATE',		title:'Last Update Date',width:100,align:'center',
           		formatter:function (value,row,index) {
	            	var date = new Date(Date.UTC(value.substring(0,4), parseInt(value.substring(5,7)-1), parseInt(value.substring(8)), 3, 0, 0));
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }				
			},
			{field:'INPUT_TIME',		title:'Input Time',width:80,align:'center'},

/*			
			{field: 'BUTTON_VIEW', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
				var col;
				col = ' <input type="button" id="btnViewBatch" value="View" onClick="viewBatch('+row.CDC_BATCH_ID+')"> ';
				return col;
			}},	
			
			{field: 'BUTTON_DELETE', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
				var col;
				col = ' <input type="button" id="btnDelBatch" value="Delete" onClick="delBatch('+row.CDC_BATCH_ID+')"> ';
				return col;
			}}
*/		
		]],
		rownumbers : true, singleSelect:false, fitColumns:true
	});		
		
	
});



function viewBatch(batchId){
	$('#Batch_dialog').dialog('open');
	$('#Batch_dialog').dialog('center');
	$('#Batch_dialog').dialog('setTitle','View Batch');

/*	
	$.('#batch_status').combobox.onChange=function(newValue,oldValue){
		alert(newValue);
	}
*/	
		//Entry batch datagrid	
	$('#tblEditReceipts').datagrid({
		url:base_url+'InquiryBatch/getReceipt/'+batchId,
		columns:[[
			{field:'ck',checkbox:true},
			{field:'CDC_REC_ID', hidden:true},
			{field:'STORE_CODE',			title:'Store Code',width:70,align:'center'},
			{field:'STORE_NAME',			title:'Store Name',width:100,align:'center'},
			{field:'SALES_DATE',			title:'Tgl Sales',width:100,align:'center',
	    		formatter:function (value,row,index) {
	            	var date = new Date(Date.UTC(value.substring(0,4), parseInt(value.substring(5,7)), parseInt(value.substring(8)), 3, 0, 0));
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
			},
			{field:'ACTUAL_SALES_AMOUNT',	title:'Cash + Penggantian',width:130,align:'right',
				formatter:function (value,row,index) {
					return Intl.NumberFormat('en-US').format(value);
				}			
			},
			{field:'TOTAL_PENAMBAHAN',		title:'Total Penambahan',width:130,align:'right',
				formatter:function (value,row,index) {
					return Intl.NumberFormat('en-US').format(value);
				}			
			},
			{field:'ACTUAL_AMOUNT',			title:'Total Actual Amount',width:150,align:'right',
				formatter:function (value,row,index) {
					return Intl.NumberFormat('en-US').format(value);
				}			
			},
			{field:'TOTAL_PENGURANGAN',		title:'Total Pengurangan',width:130,align:'right',
				formatter:function (value,row,index) {
					return Intl.NumberFormat('en-US').format(value);
				}			
			},
			{field:'i',title:'Total Voucher',width:100,align:'right'},			
		]],
		rownumbers : true, singleSelect:false, fitColumns:true,singleSelect:true,
	});		
}


function btnDeleteBatch(){
	var count = 0;  var row = [];
	var data = $('#tblTrxBatch').datagrid('getSelections');
	for(var i =0;i<data.length;i++){
		row[i] = data[i].CDC_BATCH_ID;
		count++;
	}	
	if(count >= 1){	
		$.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data tersebut?',function(r){
			if (r){
				//alert(count);
			$.ajax({
				  method: "POST",
				  url: base_url+"InquiryBatch/delBatch",
				  data: { 
							batchID	: row
						},
				  success: function (message) {
					location.reload();
					$('#tblTrxBatch').datagrid('reload');
					alert(message);
				  }
				});		
			}
		});
	}
	else{
		alert("Data yang dipilih TIDAK ADA");	
	}
}

function btnPrintBatch(){
	var count = 0;  var row = [];
	var data = $('#tblTrxBatch').datagrid('getSelections');
	$.messager.defaults.ok = 'PDF';
	$.messager.defaults.cancel = 'Excel';
	for(var i =0;i<data.length;i++){
		row[i] = data[i].CDC_BATCH_ID;
		count++;
	}
	$.messager.confirm('Confirm','Pilih bentuk Report?',function(r){
	    if (r){
	        if(count >= 1){
				var batch_id = row.join('-');
				window.open(base_url+'InputBatch/printBatch/'+batch_id+'/P', "Report Batches", "width=1000,height=600,scrollbars=yes");
			}
			else{
				alert("Data yang dipilih TIDAK ADA");
			}
	    }else{
	    	if(count >= 1){
				var batch_id = row.join('-');
				window.open(base_url+'InputBatch/printBatch/'+batch_id+'/X', "Report Batches", "width=1000,height=600,scrollbars=yes");
			}
			else{
				alert("Data yang dipilih TIDAK ADA");
			}
	    }
	});
}

function btnValidate(){
	var count = 0;  var row = [];
	var data = $('#tblTrxBatch').datagrid('getSelections');
	for(var i =0;i<data.length;i++){
		row[i] = data[i].CDC_BATCH_ID;
		count++;
	}	
	if(count >= 1){
		var batch_id = row.join('-');	
		$.messager.confirm('Confirm','Apakah anda yakin untu memvalidasi data tersebut?',function(r){
			if(r){
				$.ajax({
				  method: "POST",
				  url: base_url+"InquiryBatch/validateBatch",
				  data: { 
							batchID	: row
						},

				  success: function (message) {
					//location.reload();
					$('#tblTrxBatch').datagrid('reload');
					window.open(base_url+'InputBatch/printBatch/'+batch_id+'/P', "Report Batches", "width=1000,height=600,scrollbars=yes");
				  	 // window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");

				  }
				});				
			}
		})	
	}
	else{
		alert("Data yang dipilih TIDAK ADA");	
	}	
}

function btnRejectBatch(){
	var count = 0;  var row = [];
	var data = $('#tblTrxBatch').datagrid('getSelections');
	for(var i =0;i<data.length;i++){
		row[i] = data[i].CDC_BATCH_ID;
		count++;
	}	
	if(count >= 1){	
		$.messager.confirm('Confirm','Apakah anda yakin menolak batch tersebut?',function(r){
			if(r){
				$.ajax({
				  method: "POST",
				  url: base_url+"InquiryBatch/rejectBatch",
				  data: { 
							batchID	: row
						},
				  success: function (message) {
				  	alert(message);
					$('#tblTrxBatch').datagrid('reload');
				  }
				});				
			}
		})	
	}
	else{
		alert("Data yang dipilih TIDAK ADA");	
	}	
}

function btnCari(){
	var batchNumber	 = $('#batch_num').textbox('getValue');
	var tglBatch  	 = $('#batch_date').datebox('getValue');
	var status  	 = $('#batch_status').combobox('getValue');
	var createBy  	 = $('#create_by').textbox('getValue');
	var type 		 = $('#batch_type').combobox('getValue');
	//alert(batchNumber+'\n'+tglBatch+'\n'+status+'\n'+createBy);
	
	$('#tblTrxBatch').datagrid('load',{
		batchNumber	: batchNumber,
		tglBatch	: tglBatch,
		status		: status,
		createBy	: createBy,
		type        : type
	});

	if( $('#batch_status').combobox('getValue')=='N' ){
		$('#inquiryPrint').linkbutton('enable');
		$('#inquiryReject').linkbutton('enable');
		$('#inquiryValidate').linkbutton('enable');
	}
	if( $('#batch_status').combobox('getValue')=='R' ){
		$('#inquiryPrint').linkbutton('enable');
		$('#inquiryReject').linkbutton('disable');
		$('#inquiryValidate').linkbutton('enable');
	}
	if( $('#batch_status').combobox('getValue')=='V' ){
		$('#inquiryPrint').linkbutton('disable');
		$('#inquiryReject').linkbutton('enable');
		$('#inquiryValidate').linkbutton('disable');
	}
	if( $('#batch_status').combobox('getValue')=='T' ){
		$('#inquiryPrint').linkbutton('disable');
		$('#inquiryReject').linkbutton('disable');
		$('#inquiryValidate').linkbutton('disable');
		$("#transferSTN").linkbutton('disable');
	}
}

function btnTransfer() {
	var rows = $('#tblTrxBatch').datagrid('getSelections');
	var batch_id = '';
	var row=[];
	var row2=[];
	$.messager.confirm('Confirm','Apakah anda yakin untuk transfer data STN tersebut?',function(r){
	    if (r){
	    	$("#prog-trans").window('open');
	        if (rows.length != 0) {
				for (var i = rows.length - 1; i >= 0; i--) {
					if (i == 0) {
						batch_id += rows[i].CDC_BATCH_ID;
						row[i] = rows[i].CDC_BATCH_ID;
						row2[i] = rows[i].CDC_BATCH_ID;
					}else {
							row[i] = rows[i].CDC_BATCH_ID;
							row2[i] = rows[i].CDC_BATCH_ID;
							
							batch_id += rows[i].CDC_BATCH_ID+'-';
					}
				}

			
	//	var batch_id = row.join('-');	
				$.ajax({
					url:base_url+'InquiryBatch/get_batch_type/',
					method: 'POST',
					data:{
						batch_id : row2
					},
					success:function(msg) {
						var cek=msg;
						$("#prog-trans").window('close');
						if(cek!=0){
							$("#prog-trans").window('open');
								$.messager.alert('Alert','Transfer setoran STL tidak membentuk jurnal di oracle.','info');
				    		
				    			$.ajax({
									url:base_url+'InquiryBatch/transfer_stn/',
									method: 'POST',
									data:{
										batch_id : batch_id
									},
									success:function(msg) {
										$("#prog-trans").window('close');
										if (msg > 0) {
											$('#tblTrxBatch').datagrid('reload', base_url+'InquiryBatch/getBatch');
											$.messager.alert('Alert','Transfer data Batch STN berhasil, silahkan lakukan request "IDM CDC Sync DBR Web to Oracle".','info');
										}else $.messager.alert('Alert','Transfer data Batch STN gagal.','info');
										$("#prog-trans").window('close');
						
									}

								});
							
						}else{
							$.ajax({
									url:base_url+'InquiryBatch/transfer_stn/',
									method: 'POST',
									data:{
										batch_id : batch_id
									},
									success:function(msg) {
										$("#prog-trans").window('close');
										if (msg > 0) {
											$('#tblTrxBatch').datagrid('reload', base_url+'InquiryBatch/getBatch');
											$.messager.alert('Alert','Transfer data Batch STN berhasil, silahkan lakukan request "IDM CDC Sync DBR Web to Oracle".','info');
										}else $.messager.alert('Alert','Transfer data Batch STN gagal.','info');
										$("#prog-trans").window('close');
						
									}
								});
						}
						
						

					}
				});
				
			}else $.messager.alert('Alert','Mohon untuk memilih datanya terlebih dahulu.','info');
	    }
	});
}

function btnReset(){
	var batchNumber	 = $('#batch_num').textbox('clear');
	var tglBatch  	 = $('#batch_date').datebox('clear');
	var status  	 = $('#batch_status').combobox('setValue','N');
	var createBy  	 = $('#create_by').textbox('clear');
	
	$('#tblTrxBatch').datagrid('load',{
		batchNumber	: null,
		tglBatch	: null,
		status		: 'N',
		createBy	: null
	});	
	
}