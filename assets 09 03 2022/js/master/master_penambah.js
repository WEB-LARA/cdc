var url;
function tambah(){
	val_option = 'add';	
	$('#penambah_dialog').dialog('open');
	$('#penambah_dialog').dialog('center');
	$('#penambah_dialog').dialog('setTitle','New Trx Penambah');
	$('#plus_id').attr('style','display:none');
	
	$('#penambah_form').form('clear');
	$('#activeFlag').combobox('setValue','Y');
	$('#activeFlag').combobox('disable');
	
	//url = base_url+'master/Branch/addData';
}

function ganti(){
	val_option = 'edit';
	
	$('#plus_id').attr('style','display:none');

	var row = $('#tblMasterPenambah').datagrid('getSelected'); //datagrid
	if (row)
	{
		$('#penambah_dialog').dialog('open');
		$('#penambah_dialog').dialog('center');
		$('#penambah_dialog').dialog('setTitle','Edit Trx Penambah');
	
		$('#activeFlag').combobox('enable');
		$('#penambah_form').form('load',row); //form diisi data ssuai row

	}	
}


function hapus(){
	var row = $('#tblMasterPenambah').datagrid('getSelected');
	//alert(row.BRANCH_ID);
	if (row){
		$.messager.confirm('Confirm','Are you sure you want to destroy this item?',function(r){
			if (r){
				$.post(base_url+"master/Penambah/deleteData",{plusId:row.TRX_PLUS_ID},function(result){ 
					if (result.success){
						//alert('Data berhasil dihapus !');
						$.messager.show({title: 'Success',msg: 'Data berhasil dihapus !'});
						$('#tblMasterPenambah').datagrid('load'); //reload data
					}
					else{
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					}
				},'json');
			}

		});
	}
  }

  /* ACTION ON KLIK SAVE, ON DIALOG*/
  function save(){
	if (val_option == 'add'){
		if($('#penambah_form').form('validate') == true){
					$.ajax({
					  method: "POST",
					  url: base_url+"master/Penambah/addData",
					  data: { 
								plusName 		: $('#plusName').textbox('getValue'),
								plusDesc 		: $('#plusDesc').textbox('getValue'),
								plusAccount		: $('#plusAccount').textbox('getValue'),
								activeFlag 		: $('#activeFlag').combobox('getValue')
							},

					  success: function (message) {
						//alert(message);
						$.messager.show({title: 'Success',msg: message});
						$("#penambah_dialog").dialog('close');	
						$('#tblMasterPenambah').datagrid('load');
					  }
					});
				}
				else
				{
					alert('Cek kembali field Anda !');
					return false;
				}
	}
	else{
		$.ajax({
				method: "POST",
				url: base_url+"master/Penambah/editData",
				data: { 
							plusId			: $('#plusId').textbox('getValue'),
							plusName 		: $('#plusName').textbox('getValue'),
							plusDesc 		: $('#plusDesc').textbox('getValue'),
							plusAccount 	: $('#plusAccount').textbox('getValue'),
							activeFlag 		: $('#activeFlag').combobox('getValue'),
							inactiveDate 	: $('#inactiveDate').datebox('getValue'),
					  },

					  success: function (message) 
					  {	
						$.messager.show({title: 'Success',msg: message});
						$('#penambah_form').form('clear');
						$("#penambah_dialog").dialog('close');							
						$('#tblMasterPenambah').datagrid('load');
					  }
					});
	}
	
  }

/*************************************************************************************************************************/	
/* ACTION CANCEL */	
/*************************************************************************************************************************/	
  function cancel(){
	$('#penambah_form').form('clear');
	$("#penambah_dialog").dialog('close');
  }
  
  
  function cari(){
	  $('#dialogSearch').dialog('open').dialog('center').dialog('setTitle','Search');
	  $('#formSearch').form('clear');
	  url = base_url+'master/Penambah/getData';
  }

  function cariGO(){
	  var name = $("#TRX_PLUS_NAME").textbox('getValue');
	  var account = $("#TRX_DETAIL_ACCOUNT").textbox('getValue');
	  
		$('#dialogSearch').dialog('close');
		$('#tblMasterPenambah').datagrid('load',{
			name	: name,
			account	: account
		});

  }




