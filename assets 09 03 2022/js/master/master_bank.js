$(document).ready(function(){
	
	var val_option;
		
	/*************************************************************************************************************************/	
	/* ADD */	
	/*************************************************************************************************************************/	
	$("#tambah").click(function(){
		val_option = 'add';	
		$('#bank_dialog').dialog('open');
		$('#bank_dialog').dialog('center');
		$('#bank_dialog').dialog('setTitle','New Bank');
		$('#bank_id').attr('style','display:none');

		$('#bank_form').form('clear');  //membersihkan halaman
		$('#activeFlag').combobox('setValue','Y');
		$('#activeFlag').combobox('disable');
		/*
		$("#bank_panel").show();
		$("#bank_panel").dialog({
			title:'Add Bank',
			autoOpen: false, 
			modal:false,
			maximizable:false,
			minimizable:false,
			collapsible:false
		});
		*/
	});
	
	
	/*************************************************************************************************************************/	
	/* EDIT */	
	/*************************************************************************************************************************/	
	$("#ubah").click(function()
	{
		val_option = 'edit';
		
		$('#bank_id').attr('style','display:none');

		var row = $('#tblMasterBank').datagrid('getSelected'); //datagrid
        if (row)
        {
        	$('#bank_dialog').dialog('open');
			$('#bank_dialog').dialog('center');
			$('#bank_dialog').dialog('setTitle','Edit Bank');
		
			$('#activeFlag').combobox('enable');
        	$('#bank_form').form('load',row); //form diisi data ssuai row

        }		
	});

	
	/*************************************************************************************************************************/	
	/* ACTION CANCEL */	
	/*************************************************************************************************************************/	
	$("#submitCancel").click(function(event){
		$('#bank_form').form('clear');
		$("#bank_dialog").dialog('close');	
	});
	
	/*************************************************************************************************************************/	
	/* ACTION ADD or EDIT */	
	/*************************************************************************************************************************/	

	$("#submitOke").click(function(event)
	{
		if (val_option == 'add')
		{
			if($('#bank_form').form('validate') == true)
					{
						$.ajax({
						  method: "POST",
						  url: base_url+"master/Bank/addData",
						  data: { 
						  			bankName : $('#bankName').textbox('getValue'),
						  			bankAccountType : $('#bankAccountType').textbox('getValue'),
						  			bankAccountNum : $('#bankAccountType').textbox('getValue'),
						  			activeFlag : $('#activeFlag').combobox('getValue'),
						  			//inactiveDate : $('#inactiveDate').datebox('getValue'),
						  		},

						  success: function (message) {
						  	//alert(message);
							$.messager.show({title: 'Success',msg: message});
						  	$("#bank_dialog").dialog('close');	
						  	$('#tblMasterBank').datagrid('load');
						  }
						});
					}
					else
					{
						alert('Cek kembali field Anda !');
						return false;
					}
		}
		else
		{
			$.ajax({
					method: "POST",
					url: base_url+"master/Bank/editData",
					data: { 
								bankId			: $('#bankId').textbox('getValue'),
								bankName 		: $('#bankName').textbox('getValue'),
								bankAccountType : $('#bankAccountType').textbox('getValue'),
								bankAccountNum 	: $('#bankAccountType').textbox('getValue'),
								activeFlag 		: $('#activeFlag').combobox('getValue'),
								inactiveDate 	: $('#inactiveDate').datebox('getValue'),
						  },

						  success: function (message) 
						  {	
							$.messager.show({title: 'Success',msg: message});
						  	$('#bank_form').form('clear');
							$("#bank_dialog").dialog('close');							
						  	$('#tblMasterBank').datagrid('load');
						  }
						});
		}
	});

	
	/************************************************************************************************************************	
	/* DELETE  */	
	/*************************************************************************************************************************/	

	$("#hapus").click(function()
	{
		var row = $('#tblMasterBank').datagrid('getSelected');
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to destroy this item?', function(r){
				if (r){
					$.post(base_url+"master/Bank/deleteData",{bankId:row.BANK_ID},function(result){ 
						if (result.success){
							//alert('Data berhasil dihapus !');
							$.messager.show({title: 'Success',msg: 'Data berhasil dihapus !'});
							$('#tblMasterBank').datagrid('load'); //reload data
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
	});

	
	/************************************************************************************************************************	
	/* SEARCH */	
	/*************************************************************************************************************************/	

	
  //function cari(){
	$("#cari").click(function(){  
	  $('#dialogSearch').dialog('open').dialog('center').dialog('setTitle','Search');
	  $('#formSearch').form('clear');
	  url = base_url+'master/Bank/getData';
	});

});


	function cariGO(){
	  var name = $("#BANK_NAME").textbox('getValue');
	  var type = $("#BANK_ACCUNT_TYPE").textbox('getValue');
	  var num  = $("#BANK_ACCUNT_NUM").textbox('getValue');
	  
	  //window.alert(name);
	 
		$('#dialogSearch').dialog('close');
		$('#tblMasterBank').datagrid('load',{
			name: name,
			type: type,
			num	: num
		});

	}
	
	