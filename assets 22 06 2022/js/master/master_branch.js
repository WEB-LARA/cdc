var url;
function tambah(){
	val_option = 'add';	
	$('#branch_dialog').dialog('open');
	$('#branch_dialog').dialog('center');
	$('#branch_dialog').dialog('setTitle','New Branch');
	$('#branch_id').attr('style','display:none');
	
	$('#branch_form').form('clear');
	$('#activeFlag').combobox('setValue','Y');
	$('#activeFlag').combobox('disable');
	
	//url = base_url+'master/Branch/addData';
}

function ganti(){
	val_option = 'edit';
	
	$('#branch_id').attr('style','display:none');

	var row = $('#tblMasterBranch').datagrid('getSelected'); //datagrid
	if (row)
	{
		$('#branch_dialog').dialog('open');
		$('#branch_dialog').dialog('center');
		$('#branch_dialog').dialog('setTitle','Edit Branch');
	
		$('#activeFlag').combobox('enable');
		$('#branch_form').form('load',row); //form diisi data ssuai row

	}	
}


function hapus(){
	var row = $('#tblMasterBranch').datagrid('getSelected');
	//alert(row.BRANCH_ID);
	if (row){
		$.messager.confirm('Confirm','Are you sure you want to destroy this item?',function(r){
			if (r){
				$.post(base_url+"master/Branch/deleteData",{branchId:row.BRANCH_ID},function(result){ 
					if (result.success){
						//alert('Data berhasil dihapus !');
						$.messager.show({title: 'Success',msg: 'Data berhasil dihapus !'});
						$('#tblMasterBranch').datagrid('load'); //reload data
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
		if($('#branch_form').form('validate') == true){
					$.ajax({
					  method: "POST",
					  url: base_url+"master/Branch/addData",
					  data: { 
								branchCode 		: $('#branchCode').textbox('getValue'),
								branchName 		: $('#branchName').textbox('getValue'),
								regOrg 			: $('#regOrg').textbox('getValue'),
								frcOrg 			: $('#frcOrg').textbox('getValue'),
								activeFlag 		: $('#activeFlag').combobox('getValue')
								//inactiveDate 	: $('#inactiveDate').datebox('getValue')
							},

					  success: function (message) {
						//alert(message);
						$.messager.show({title: 'Success',msg: message});
						$("#branch_dialog").dialog('close');	
						$('#tblMasterBranch').datagrid('load');
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
				url: base_url+"master/Branch/editData",
				data: { 
							branchId		: $('#branchId').textbox('getValue'),
							branchCode 		: $('#branchCode').textbox('getValue'),
							branchName 		: $('#branchName').textbox('getValue'),
							regOrg 			: $('#regOrg').textbox('getValue'),
							frcOrg 			: $('#frcOrg').textbox('getValue'),
							activeFlag 		: $('#activeFlag').combobox('getValue'),
							inactiveDate 	: $('#inactiveDate').datebox('getValue'),
					  },

					  success: function (message) 
					  {	
						$.messager.show({title: 'Success',msg: message});
						$('#branch_form').form('clear');
						$("#branch_dialog").dialog('close');							
						$('#tblMasterBranch').datagrid('load');
					  }
					});
	}
	
  }

/*************************************************************************************************************************/	
/* ACTION CANCEL */	
/*************************************************************************************************************************/	
  function cancel(){
	$('#branch_form').form('clear');
	$("#branch_dialog").dialog('close');
  }
  
  
  function cari(){
	  $('#dialogSearch').dialog('open').dialog('center').dialog('setTitle','Search');
	  $('#formSearch').form('clear');
	  url = base_url+'master/Branch/getData';
  }

  function cariGO(){
	  var code = $("#BRANCH_CODE").textbox('getValue');
	  var name = $("#BRANCH_NAME").textbox('getValue');
	  
		$('#dialogSearch').dialog('close');
		$('#tblMasterBranch').datagrid('load',{
			code: code,
			name: name
		});

  }




