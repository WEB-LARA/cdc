function formatDetail(value,row){
	if(row.ACTIVE == 'YES'){
		return '<a href="#" id="btn' + row.USER_ID + '" class="easyui-linkbutton" row-id="'+row.USER_ID+'">Reset</a>';
	}
}

$(document).ready(function(){
	$('#addEntryUser').window('close');
	$('#editEntryUser').window('close');
	$("#resetPass").window('close');
	$("#addEntryBankSTN").window('close');
	$("#editEntryBankSTN").window('close');
	

	$("#addUserBtn").click(function (event) {
		event.preventDefault();
		$("#addEntryUser").window('open');
	});

	$("#addBankBtn").click(function (event) {
		event.preventDefault();
		$("#addEntryBankSTN").window('open');
	});

	$("#searchUser").click(function(){
		var nik = $("#src-nik-user").combobox('getValue');

		if(nik != ''){
			$("#UserGrid").datagrid('load',{nik : nik});
		}else{
			alert('Tidak dapat dilakukan pencarian');
		}
	});

	$("#refresh").click(function(){
		var nik = '';
		$("#src-nik-user").combobox('setValue','');
		$("#UserGrid").datagrid('reload',{nik : nik});
	});

	$("#searchBank").click(function(){
		var bank_acc = $("#src-bank-acc").combobox('getValue');

		if(bank_acc != ''){
			$("#BankGrid").datagrid('load',{bank_acc : bank_acc});
		}else{
			alert('Tidak dapat dilakukan pencarian');
		}
	});

	$("#refreshbank").click(function(){
		var bank_acc = '';
		$("#src-bank-acc").combobox('setValue','');
		$("#BankGrid").datagrid('reload',{bank_acc : bank_acc});
	});

	$("#UserGrid").datagrid({
		url: base_url+'Admin/getDataUser',
	    striped: true,
	    rownumbers:true,
		remoteSort:false,
		singleSelect:true,
		pagination:true,
		fit:false,
		autoRowHeight:false,
		fitColumns:true,
		rowStyler:function(index,row){
			if (row.ACTIVE == 'NO'){
			    return 'background-color:red;';
			}
		},
		onLoadSuccess:function(data){
				$(this).datagrid('getPanel').find('a.easyui-linkbutton').linkbutton();
				$(this).datagrid('getPanel').find('a.easyui-linkbutton').each(function(){
					$(this).linkbutton({
	                    onClick:function(){ 
		                    	var id = $(this).attr('row-id');
		                        $.messager.confirm('Confirm','Apakah yakin untuk mereset password user ini?',function(r){
		                        	if(r){
		                        		 $("#resetid-user").val(id);
		                        		 $("#resetPass").window('open');
		                        	}
		                        });
	                    }
	                });
           		 });
		},
		onDblClickRow: function(){
			var data = $(this).datagrid('getSelected');

			$("#ecabang-user").combobox('select',data.BRANCH_CODE);
			$("#edc-user").combobox('select',data.DC_CODE);
			$("#erole-user").combobox('select',data.ROLE_ID);
			$("#enik-user").textbox('setValue',data.NIK);
			$("#enama-user").textbox('setValue',data.USER_NAME);
			$("#id-user").val(data.USER_ID);

			
			if(data.ACTIVE_FLAG == 'Y'){
				$("#eactiveFlagSave").prop('checked', true);
			}else{
				$("#eactiveFlagSave").prop('checked', false);
			}

			if(data.RESET_FLAG == 'Y'){
				$("#eresetFlagSave").prop('checked', true);
			}else{
				$("#eresetFlagSave").prop('checked', false);
			}

			$('#editEntryUser').window('open');
		},
		columns:[[
			{field:'USER_ID',hidden:true},
			{field:'BRANCH_CODE',hidden:true},
			{field:'ACTIVE_FLAG',hidden:true},
			{field:'RESET_FLAG',hidden:true},
			{field:'BRANCH',title:'Cabang',width:80,align:"left",halign:"center"},
			{field:'DC_CODE',title:'Gudang',width:50,align:"left",halign:"center"},
			{field:'ROLE',title:'Role',width:50,align:"left",halign:"center"},
			{field:'NIK',title:'NIK',width:60,align:"left",halign:"center"},
			{field:'USER_NAME',title:'Username',width:100,align:"left",halign:"center"},
			{field:'ACTIVE_DATE',title:'Tgl Aktif',width:50,align:"left",halign:"center",
				formatter:function (value,row,index) {
			        var date = new Date(value);
			        options = {
						year: 'numeric', month: 'short', day: 'numeric'
					};
					return Intl.DateTimeFormat('id-ID', options).format(date);
			    }
	        },
	        {field:'ACTIVE',title:'Aktif',width:20,align:"left",halign:"center"},
	        {field:'RESET_PASS',title:'<b>Reset Password</b>', width:50,align:'center',formatter: formatDetail}
		]]
	});

	$("#save_add_user").click(function (event) {
		event.preventDefault();
		var branch = $("#cabang-user").combobox('getValue');
		var dc = $("#dc-user").combobox('getValue');
		var role = $("#role-user").combobox('getValue');
		var nik = $("#nik-user").textbox('getValue');
		var username = $("#nama-user").textbox('getValue');
		var password = $("#pass-user").textbox('getValue');

		if(branch != '' && dc != '' && role != '' && nik != '' && username != '' && password != ''){
			$.ajax({
				type: "POST",
				dataType:'json',
				async:false,
				url:"cek_nik",
				data:{
					nik : nik
				},
				success:function(msg){
					if(msg == 0){
						$.ajax({
							type:"POST",
							dataType:'json',
							async:false,
							url:"insert_user",
							data:{
								branch : branch,
								dc : dc,
								role : role,
								nik : nik,
								username : username,
								password : password
							},
							success:function(msg2){
								if(msg2 > 0){
									$.messager.alert('Alert','Data berhasil disimpan!','info');
									$('#addEntryUser').dialog('close');
									$('#UserGrid').datagrid('reload');
									$("#src-nik-user").combobox('reload');
									$("#cabang-user").combobox('setValue','');
									$("#dc-user").combobox('setValue','');
									$("#role-user").combobox('setValue','');
									$("#nik-user").textbox('setValue','');
									$("#nama-user").textbox('setValue','');
									$("#pass-user").textbox('setValue','');
								}else{
									$.messager.alert('Alert','Data gagal disimpan!','info');
									$('#addEntryUser').dialog('close');
									$('#UserGrid').datagrid('reload');
									$("#src-nik-user").combobox('reload');
									$("#cabang-user").combobox('setValue','');
									$("#dc-user").combobox('setValue','');
									$("#role-user").combobox('setValue','');
									$("#nik-user").textbox('setValue','');
									$("#nama-user").textbox('setValue','');
									$("#pass-user").textbox('setValue','');
								}
							}
						});
					}else{
						$.messager.alert('Alert','User dengan nik tersebut sudah ada!','info');
					}
				}
			});	
		}else{
			$.messager.alert('Alert','Mohon data diisi dengan lengkap!','info');
		}

	});

	$("#save_edit_user").click(function (event) {
		event.preventDefault();
		var branch = $("#ecabang-user").combobox('getValue');
		var dc = $("#edc-user").combobox('getValue');
		var role = $("#erole-user").combobox('getValue');
		var nik = $("#enik-user").textbox('getValue');
		var username = $("#enama-user").textbox('getValue');
		var user_id = $("#id-user").val();
		if($('#eactiveFlagSave').is(':checked'))
		{
			var active = 'Y';
		}
		else
		{
			var active = 'N';
		}
				
		if($('#eresetFlagSave').is(':checked'))
		{
			var reset = 'Y';
		}
		else
		{
			var reset = 'N';
		}
		
		if(branch != '' && dc != '' && role != '' && nik != '' && username != '' && user_id != ''){
			var oldNIK = '';
			var cek_nik = 0;

			
			$.ajax({
				type: "POST",
				dataType:'json',
				async:false,
				url:"getOldNIK",
				data:{
					user_id : user_id
				},
				success:function(msg){
					oldNIK = msg;
				}
			});


			if(nik != oldNIK){
				$.ajax({
					type: "POST",
					dataType:'json',
					async:false,
					url:"cek_nik",
					data:{
						nik : nik
					},
					success:function(msg){
						cek_nik = msg;
					}
				});
			}

			if(cek_nik == 0){
				$.ajax({
					type:"POST",
					dataType:'json',
					async:false,
					url:"edit_user",
					data:{
						user_id : user_id,
						branch : branch,
						dc : dc,
						role : role,
						nik : nik,
						username : username,
						active : active,
						reset : reset
					},
					success:function(msg2){
						if(msg2 > 0){
							$.messager.alert('Alert','Data berhasil disimpan!','info');
							$('#editEntryUser').dialog('close');
							$('#UserGrid').datagrid('reload');
							$("#src-nik-user").combobox('reload');
							$("#ecabang-user").combobox('setValue','');
							$("#edc-user").combobox('setValue','');
							$("#erole-user").combobox('setValue','');
							$("#enik-user").textbox('setValue','');
							$("#enama-user").textbox('setValue','');
							$("#id-user").val('');
						}else{
							$.messager.alert('Alert','Data gagal disimpan!','info');
							$('#editEntryUser').dialog('close');
							$('#UserGrid').datagrid('reload');
							$("#src-nik-user").combobox('reload');
							$("#ecabang-user").combobox('setValue','');
							$("#edc-user").combobox('setValue','');
							$("#erole-user").combobox('setValue','');
							$("#enik-user").textbox('setValue','');
							$("#enama-user").textbox('setValue','');
							$("#id-user").val('');
						}
					}
				});
			}else{
				$.messager.alert('Alert','User dengan nik tersebut sudah ada!','info');
			}
		}else{
			$.messager.alert('Alert','Mohon data diisi dengan lengkap!','info');
		}

	});

	$("#reset_pass").click(function (event) {
		event.preventDefault();
		var user_id = $("#resetid-user").val();
		var password = $("#epass-user").textbox('getValue');

		if(user_id != '' && password != ''){
			$.ajax({
				type: "POST",
				dataType:'json',
				async:false,
				url:"resetPassword",
				data:{
					user_id : user_id,
					password : password
				},
				success:function(msg){
					if(msg > 0){
						$.messager.alert('Alert','Password berhasil direset','info');
						$("#resetid-user").val('');
						$("#epass-user").textbox('setValue','');
						$('#UserGrid').datagrid('reload');
						$("#resetPass").window('close');
					}else{
						$.messager.alert('Alert','Password gagal direset','info');
						$("#resetid-user").val('');
						$("#epass-user").textbox('setValue','');
						$('#UserGrid').datagrid('reload');
						$("#resetPass").window('close');
					}
				}
			});
		}else{
			$.messager.alert('Alert','Mohon password baru diisi','info');
		}
	});

	$("#BankGrid").datagrid({
		url: base_url+'Admin/getDataBank',
	    striped: true,
	    rownumbers:true,
		remoteSort:false,
		singleSelect:true,
		pagination:true,
		fit:false,
		autoRowHeight:false,
		fitColumns:true,
		rowStyler:function(index,row){
			if (row.ACTIVE == 'NO'){
			    return 'background-color:red;';
			}
		},
		onDblClickRow: function(){
			var data = $(this).datagrid('getSelected');

			$("#ecabang-bank").combobox('setValue',data.BRANCH_ID);
			$("#emaster-bank").combobox('setValue',data.BANK_ID);
			$("#enama-bank").textbox('setValue',data.BANK_ACCOUNT_NAME);
			$("#eno-bank").textbox('setValue',data.BANK_ACCOUNT_NUM);
			$("#bank-id").val(data.BANK_ACCOUNT_ID);

			
			if(data.ACTIVE_FLAG == 'Y'){
				$("#eactiveFlagSave").prop('checked', true);
			}else{
				$("#eactiveFlagSave").prop('checked', false);
			}

			$('#editEntryBankSTN').window('open');
		},
		columns:[[
			{field:'BANK_ACCOUNT_ID',hidden:true},
			{field:'BANK_ID',hidden:true},
			{field:'ACTIVE_FLAG',hidden:true},
			{field:'BRANCH_ID',hidden:true},
			{field:'BRANCH',title:'Cabang',width:50,align:"left",halign:"center"},
			{field:'BANK',title:'Bank',width:50,align:"left",halign:"center"},
			{field:'BANK_ACCOUNT_NAME',title:'Nama Akun Bank',width:80,align:"left",halign:"center"},
			{field:'BANK_ACCOUNT_NUM',title:'No Akun Bank',width:70,align:"left",halign:"center"},
			{field:'ACTIVE_DATE',title:'Tgl Aktif',width:50,align:"left",halign:"center",
				formatter:function (value,row,index) {
			        var date = new Date(value);
			        options = {
						year: 'numeric', month: 'short', day: 'numeric'
					};
					return Intl.DateTimeFormat('id-ID', options).format(date);
			    }
	        },
	        {field:'ACTIVE',title:'Aktif',width:20,align:"left",halign:"center"}
		]]
	});

	$("#save_add_bank").click(function (event) {
		event.preventDefault();
		var branch = $("#cabang-bank").combobox('getValue');
		var bank = $("#master-bank").combobox('getValue');
		var nama_bank = $("#nama-bank").textbox('getValue');
		var no_bank = $("#no-bank").textbox('getValue');

		if(branch != '' && bank != '' && nama_bank != '' && no_bank != ''){
			$.ajax({
				type: "POST",
				dataType:'json',
				async:false,
				url:"cek_bank",
				data:{
					bank : bank,
					no_bank : no_bank
				},
				success:function(msg){
					if(msg == 0){
						$.ajax({
							type:"POST",
							dataType:'json',
							async:false,
							url:"insert_bank",
							data:{
								branch : branch,
								bank : bank,
								no_bank : no_bank,
								nama_bank : nama_bank
							},
							success:function(msg2){
								if(msg2 > 0){
									$.messager.alert('Alert','Data berhasil disimpan!','info');
									$('#addEntryBankSTN').dialog('close');
									$('#BankGrid').datagrid('reload');
									$("#src-bank-acc").combobox('reload');
									$("#cabang-bank").combobox('setValue','');
									$("#master-bank").combobox('setValue','');
									$("#nama-bank").textbox('setValue','');
									$("#no-bank").textbox('setValue','');
								}else{
									$.messager.alert('Alert','Data gagal disimpan!','info');
									$('#addEntryBankSTN').dialog('close');
									$('#BankGrid').datagrid('reload');
									$("#src-bank-acc").combobox('reload');
									$("#cabang-bank").combobox('setValue','');
									$("#master-bank").combobox('setValue','');
									$("#nama-bank").textbox('setValue','');
									$("#no-bank").textbox('setValue','');
								}
							}
						});
					}else{
						$.messager.alert('Alert','Bank tersebut sudah ada!','info');
					}
				}
			});	
		}else{
			$.messager.alert('Alert','Mohon data diisi dengan lengkap!','info');
		}

	});

	$("#save_edit_bank").click(function (event) {
		event.preventDefault();
		var branch = $("#ecabang-bank").combobox('getValue');
		var bank = $("#emaster-bank").combobox('getValue');
		var nama_bank = $("#enama-bank").textbox('getValue');
		var no_bank = $("#eno-bank").textbox('getValue');
		var bank_id = $("#bank-id").val();

		if($('#eactiveFlagSave').is(':checked'))
		{
			var active = 'Y';
		}
		else
		{
			var active = 'N';
		}

		if(branch != '' && bank != '' && nama_bank != '' && no_bank != '' && bank_id != ''){
			var oldBankNum = '';
			var oldBankID = 0;
			var cek_bank = 0;

			
			$.ajax({
				type: "POST",
				dataType:'json',
				async:false,
				url:"getOldBank",
				data:{
					bank_id : bank_id
				},
				success:function(msg){
					oldBankID = msg.BANK_ID;
					oldBankNum = msg.BANK_ACCOUNT_NUM;
				}
			});

			//alert(oldBankID);
			//alert(oldBankNum);


			if((bank != oldBankID && no_bank == oldBankNum) || (bank == oldBankID && no_bank != oldBankNum) 
				|| (bank != oldBankID && no_bank != oldBankNum)){
				$.ajax({
					type: "POST",
					dataType:'json',
					async:false,
					url:"cek_bank",
					data:{
						bank : bank,
						no_bank : no_bank
					},
					success:function(msg){
						cek_bank = msg;
					}
				});
			}


			if(cek_bank == 0){
				$.ajax({
					type:"POST",
					dataType:'json',
					async:false,
					url:"edit_bank",
					data:{
						branch : branch,
						bank : bank,
						no_bank : no_bank,
						nama_bank : nama_bank,
						active : active,
						bank_id : bank_id
					},
					success:function(msg2){
						if(msg2 > 0){
							$.messager.alert('Alert','Data berhasil disimpan!','info');
							$('#editEntryBankSTN').dialog('close');
							$('#BankGrid').datagrid('reload');
							$("#src-bank-acc").combobox('reload');
							$("#ecabang-bank").combobox('setValue','');
							$("#emaster-bank").combobox('setValue','');
							$("#enama-bank").textbox('setValue','');
							$("#eno-bank").textbox('setValue','');
						}else{
							$.messager.alert('Alert','Data gagal disimpan!','info');
							$('#editEntryBankSTN').dialog('close');
							$('#BankGrid').datagrid('reload');
							$("#src-bank-acc").combobox('reload');
							$("#ecabang-bank").combobox('setValue','');
							$("#emaster-bank").combobox('setValue','');
							$("#enama-bank").textbox('setValue','');
							$("#eno-bank").textbox('setValue','');
						}
					}
				});
			}else{
				$.messager.alert('Alert','Bank tersebut sudah ada!','info');
			}
		}else{
			$.messager.alert('Alert','Mohon data diisi dengan lengkap!','info');
		}

	});
});