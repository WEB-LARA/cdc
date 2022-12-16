

$(document).ready(function(){
	
	$('#branch').combobox({
        url: base_url+'master/Branch/getOption',
        valueField:'BRANCH',
        textField:'BRANCH'
    });
	

});	

var url;
function tambah(){
	val_option = 'add';	
	$('#toko_dialog').dialog('open');
	$('#toko_dialog').dialog('center');
	$('#toko_dialog').dialog('setTitle','New Toko');
	$('#store_id').attr('style','display:none');
	
	$('#toko_form').form('clear');
	$('#activeFlag').combobox('setValue','Y');
	$('#activeFlag').combobox('disable');
}

function ganti(){
	val_option = 'edit';
	
	$('#store_id').attr('style','display:none');

	var row = $('#tblMasterToko').datagrid('getSelected'); //datagrid
	if (row)
	{
		$('#toko_dialog').dialog('open');
		$('#toko_dialog').dialog('center');
		$('#toko_dialog').dialog('setTitle','Edit Toko');
	
		$('#activeFlag').combobox('enable');
		$('#toko_form').form('load',row); //form diisi data ssuai row
		
	}	
}


function hapus(){
	var row = $('#tblMasterToko').datagrid('getSelected');
	//alert(row.toko_ID);
	if (row){
		$.messager.confirm('Confirm','Are you sure you want to destroy this item?',function(r){
			if (r){
				$.post(base_url+"master/Toko/deleteData",{tokoId:row.toko_ID},function(result){ 
					if (result.success){
						//alert('Data berhasil dihapus !');
						$.messager.show({title: 'Success',msg: 'Data berhasil dihapus !'});
						$('#tblMasterToko').datagrid('load'); //reload data
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
		if($('#toko_form').form('validate') == true){
					$.ajax({
					  method: "POST",
					  url: base_url+"master/Toko/addData",
					  data: { 
								storeCode 		: $('#storeCode').textbox('getValue'),
								storeName 		: $('#storeName').textbox('getValue'),
								storeType 		: $('#storeType').textbox('getValue'),
								storeAddress	: $('#storeAddress').textbox('getValue'),
								branchId 		: $('#branch').combobox('getValue'),
								activeFlag 		: $('#activeFlag').combobox('getValue')
								//inactiveDate 	: $('#inactiveDate').datebox('getValue')
							},

					  success: function (message) {
						//alert(message);
						$.messager.show({title: 'Success',msg: message});
						$("#toko_dialog").dialog('close');	
						$('#tblMasterToko').datagrid('load');
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
			
		//var xxx = $('#branch').combobox('getValue');
		//POST CARI ID
		
		var b_id = 0;
			$.ajax({
				method	: "POST",
				url		: base_url+"master/Branch/getBranchId",
				data	: {
							xxx :  $('#branch').combobox('getValue')
				},
				success: function (id){
					//window.alert(id);
					b_id = id;  
					//alert(id);
					window.alert(b_id);
				}
			});
			//return b_id;
			//b_id = ;
			window.alert(b_id);
		//POST EDIT, SIMPAN DATABASE
		$.ajax({
				method: "POST",
				url: base_url+"master/Toko/editData",
				data: { 			
							storeId			: $('#storeId').textbox('getValue'),
							storeCode 		: $('#storeCode').textbox('getValue'),
							storeName 		: $('#storeName').textbox('getValue'),
							storeType 		: $('#storeType').textbox('getValue'),
							storeAddress	: $('#storeAddress').textbox('getValue'),
							branchId 		: b_id,
							activeFlag 		: $('#activeFlag').combobox('getValue'),
							inactiveDate 	: $('#inactiveDate').datebox('getValue')
					  },

					  success: function (message){	
						$.messager.show({title: 'Success',msg: message});
						$('#toko_form').form('clear');
						$("#toko_dialog").dialog('close');							
						$('#tblMasterToko').datagrid('load');
					  }
					});
	}
	
  }

  
/*************************************************************************************************************************/	
/* ACTION CANCEL */	
/*************************************************************************************************************************/	
  function cancel(){
	$('#toko_form').form('clear');
	$("#toko_dialog").dialog('close');
  }
  

  function cari(){
	  $('#dialogSearch').dialog('open').dialog('center').dialog('setTitle','Search');
	  $('#formSearch').form('clear');
	  url = base_url+'master/Toko/getData';
  }

  function cariGO(){
	  var branchId 		= $('#BRANCH_SEARCH').combobox('getValue');
	  var code 			= $('#STORE_CODE').textbox('getValue');
	  var activeDate	= $('#ACTIVE_DATE').combobox('getValue');
	  
		$('#dialogSearch').dialog('close');
		$('#tblMasterToko').datagrid('load',{
			branchId	: branchId,
			code		: code,
			activeDate	: activeDate
		});

  }




