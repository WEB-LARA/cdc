$(document).ready(function() {

	$("#modal-add-user").window('close');

	$('#data-user').datagrid({
        url: 'User/get_data_user/',
        striped: true,
        rownumbers:true,
		remoteSort:false,
		singleSelect:true,
		pagination:true,
		fit:false,
		autoRowHeight:false,
		fitColumns:true,
		toolbar :'#toolbar',
        columns:[[
        	{field:'USER_ID',hidden:true},
        	{field:'ROLE_ID',hidden:true},
        	{field:'BRANCH_ID',hidden:true},
        	{field:'DC_CODE',hidden:true},
        	{field:'USER_NAME',title:'Username',width:200,align:"center",halign:"center"},
            {field:'NIK',title:'NIK',width:150,align:"center",halign:"center"},
            {field:'ROLE_NAME',title:'Role',width:100,align:"center",halign:"center"},
            {field:'BRANCH',title:'Branch',width:150,align:"center",halign:"center"},
            {field:'DC',title:'DC',width:220,align:"center",halign:"center"},
            {field:'ACTIVE_DATE',title:'Active Date',width:130,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	var date = new Date(value);
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
        	},
        	{field:'ACTIVE_FLAG',title:'Status',width:100,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	if (value == 'Y') {
	            		return 'Active';
	            	} else return 'Inactive';
	            }
        	}
        ]]
    });

    $('#sc-user').click(function(event) {
		event.preventDefault();
		$('#data-user').datagrid('load',{
	        nik: $("#sc-user-nik").textbox('getValue')
	    });
	});

	$("#add-user").click(function(event) {
		event.preventDefault();
		$("#modal-add-user").window('open');
		$("#username").textbox('setValue', '');
		$("#nik").textbox('setValue', '');
		$("#pass").textbox('setValue', '');
		$("#role").combobox('setValue', '');
		$("#branch").combobox('setValue', '');
		$("#dc").combobox('setValue', '');
	});

	$('#branch').combobox({
		url: base_url+'Report/choose_branch',
		valueField:'BRANCH_ID',
		textField:'BRANCH_VALUE'
	});

	$('#role').combobox({
		url: base_url+'Report/choose_role',
		valueField:'ROLE_ID',
		textField:'ROLE_NAME'
	});
	
	$('#branch').combobox({
		onChange: function (value) {
			$('#dc').combobox({
				url: base_url+'Report/choose_dc_user/'+value,
				valueField:'DC_CODE',
				textField:'DC_VALUE'
			});
		}
	});

	$("#sub-user").click(function(event) {
		event.preventDefault();
		if ($("#username").textbox('getValue') != '' && $("#nik").textbox('getValue') != '' && $("#pass").textbox('getValue') != '' && $("#role").combobox('getValue') != '' && $("#branch").combobox('getValue') != '' && $("#dc").combobox('getValue') != '') {
			$.ajax({
				url: 'User/add_user',
				method: 'POST',
				data: {
					username: $("#username").textbox('getValue'),
					nik: $("#nik").textbox('getValue'),
					pass: $("#pass").textbox('getValue'),
					role: $("#role").combobox('getValue'),
					branch: $("#branch").combobox('getValue'),
					dc: $("#dc").combobox('getValue')
				},
				success: function (msg) {
					if (msg =='S') {
						$("#modal-add-user").window('close');
						$.messager.alert('Success','User berhasil ditambah.');
					} else if (msg == 'A') {
						$.messager.alert('Failed','NIK sudah pernah didaftarkan.');
					} else {
						$("#modal-add-user").window('close');
						$.messager.alert('Failed','User gagal ditambah.');
					}
				}
			});
		} else $.messager.alert('Failed','Mohon untuk mengisi form secara lengkap.');
	});
});