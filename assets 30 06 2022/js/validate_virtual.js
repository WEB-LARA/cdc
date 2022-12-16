$(document).ready(function(){
	var dep_num = '-';

    $("#modal_edit_deskripsi_kurset").window('close');

	$('#data-kurset-virtual').datagrid({
        url: 'get_data_kurset_virtual/',
        striped: true,
        rownumbers:true,
		remoteSort:false,
		singleSelect:false,
		fit:false,
		autoRowHeight:false,
		fitColumns:true,
		toolbar :'#toolbar',
		selectOnCheck:true,
		checkOnSelect:true,
		onSelect: function () {
			var rows = $(this).datagrid('getSelections');
			if (rows.length > 0) {
				
			};
		},
		onSelectAll: function () {
			var rows = $(this).datagrid('getSelections');
			if (rows.length > 0) {
				
			};
		},
		onUnselect: function () {
			var rows = $(this).datagrid('getSelections');
			if (rows.length > 0) {
				
			}else if (rows.length == 0) {
				
			};
		},
		onUnselectAll: function () {
			
		},
		onLoadSuccess:function(data) {
			var rows2 = $(this).datagrid('getRows');
		    for(i=0;i<rows2.length;++i){
		    	if (rows2[i]['CEK'] == 'Y') {
		    		$(this).datagrid('checkRow',i);
		        	rows2[i]['ck'] = 1;
		    	};
		    }
		},
        columns:[[
        	{field:'ck',checkbox:true},
        	{field:'TRX_DETAIL_MINUS_ID',hidden:true},
        	{field:'TRX_CDC_REC_ID',hidden:true},
        	{field:'CDC_BATCH_ID',hidden:true},
        	{field:'CDC_DEPOSIT_ID',hidden:true},
        	{field:'CEK',hidden:true},
        	{field:'CDC_BATCH_NUMBER',title:'Batch Number',width:120,align:"center",halign:"center"},
            {field:'TRX_MINUS_DESC',title:'Description',width:200,align:"center",halign:"center"},
            {field:'TRX_MINUS_DATE',title:'Date',width:100,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	var date = new Date(value);
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
        	},
            {field:'TRX_MINUS_AMOUNT',title:'Amount',width:100,align:"right",halign:"center",
	            formatter:function (value,row,index) {
	            	return Intl.NumberFormat('en-US').format(value);
	            }
        	},
        	{field:'USER_NAME',title:'Created By',width:150,align:"center",halign:"center"},
            {field: 'EDIT_DESK', title: 'Action' ,width:80 ,align:'center',
                formatter: function (value, row, index) {
                    var col = '<input type="button" id="" value="Edit Deskripsi" onClick="editDeskripsi('+row.TRX_DETAIL_MINUS_ID+')">';
                    return col;
                }
            }
        ]]
    });

    $("#sc-kurset-vir").click(function(event) {
    	event.preventDefault();
    	$.ajax({
    		url: 'cek_vir_status_deposit/',
    		method: 'POST',
    		data: {dep_num: $('#sc-deposit-num').textbox('getValue')},
    		success: function(msg) {
    			if (msg.substring(0, 1) == 'T') {
    				$.messager.alert('Warning','Data deposit sudah ditransfer ke Oracle.');
    			}else if (msg.substring(0, 1) == 'N') {
    				$.messager.alert('Warning','Data deposit tidak memiliki Kurset Virtual.');
    			}else if (msg.substring(0, 1) == 'Y') {
    				$('#data-kurset-virtual').datagrid('load',
			    		{
				        	dep_num: $('#sc-deposit-num').textbox('getValue')
				        }
			        );
                    $("#sc-deposit-id").val(msg.substring(1));
    			}else $.messager.alert('Warning','Data tidak ditemukan.');
    		}
    	});
    });

    $("#submit-vir").click(function(event) {
    	event.preventDefault();
    	var rows = $('#data-kurset-virtual').datagrid('getSelections');
    	if (rows.length > 0) {
    		$.ajax({
    			url: 'validate_vir/',
    			method: 'POST',
    			data: {data: rows},
    			success: function(msg) {
    				if (msg > 0) {
                        $('#sc-deposit-num').textbox('setValue', '');
                        $('#data-kurset-virtual').datagrid('load',
                            {
                                dep_num: '-'
                            }
                        );
                        $("#sc-deposit-id").val('');
                        $.messager.alert('Warning','Data kurset virtual berhasil divalidate.');
    				}
    			}
    		});
    	}else {
            $.messager.defaults.ok = 'Ya';
            $.messager.defaults.cancel = 'Tidak';
            $.messager.confirm('Confirm','Apakah anda yakin tidak memvalidasi semua data virtual untuk deposit tersebut ?',function(r){
                if (r){
                    $.ajax({
                        url: 'unvalidate_vir_all/'+$("#sc-deposit-id").val(),
                        method: 'POST',
                        success: function(msg) {
                            if (msg > 0) {
                                $('#sc-deposit-num').textbox('setValue', '');
                                $('#data-kurset-virtual').datagrid('load',
                                    {
                                        dep_num: '-'
                                    }
                                );
                                $("#sc-deposit-id").val('');
                                $.messager.alert('Warning','Data kurset virtual berhasil divalidate.');
                            }
                        }
                    });
                }
            });
    	}
    });

    $("#print-vir").click(function(event) {
    	event.preventDefault();
        $.messager.defaults.ok = 'PDF';
        $.messager.defaults.cancel = 'CSV';
    	if ($('#sc-deposit-num').textbox('getValue') != '') {
    		$("#sc-kurset-vir").trigger("click");
            $.messager.confirm('Confirm','Pilih bentuk Report ?',function(r){
                if (r){
                    window.open('print_data_kurset_virtual/'+$('#sc-deposit-num').textbox('getValue')+'/P', "Report_kurset_virtual", "width=300,height=200");
                } else window.open('print_data_kurset_virtual/'+$('#sc-deposit-num').textbox('getValue')+'/C', "Report_kurset_virtual", "width=300,height=200");
            });
    	}else $.messager.alert('Warning','Harap untuk mengisi deposit numbernya terlebih dahulu.');
    });

    $("#sub-deskripsi").click(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'update_deskripsi_virtual',
            method: 'POST',
            data: {deskripsi: $("#det-deskripsi").textbox('getValue'), det_id: $("#det-id").val()},
            success: function(msg) {
                if (msg > 0) {
                    $('#data-kurset-virtual').datagrid('load',
                        {
                            dep_num: $('#sc-deposit-num').textbox('getValue')
                        }
                    );
                    $("#det-deskripsi").textbox('setValue', '');
                    $("#det-id").val('');
                    $("#modal_edit_deskripsi_kurset").window('close');
                    $.messager.alert('Success','Deskripsi kurset Virtual berhasil diupdate.');
                } else $.messager.alert('Warning','Deskripsi kurset Virtual Gagal diupdate.');
            }
        });
    });
});

function editDeskripsi(trx_det_id) {
    $.ajax({
        url: 'get_deskripsi_kur_virtual/'+trx_det_id,
        method: 'POST',
        dataType: 'json',
        success: function(msg) {
            if (msg.length > 0) {
                $("#det-deskripsi").textbox('setValue', msg[0].DESK);
                $("#det-id").val(msg[0].TRX_DETAIL_MINUS_ID);
                $("#modal_edit_deskripsi_kurset").window('open');
            }
        }
    });
}