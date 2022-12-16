$(document).ready(function(){
	var base_url = window.location;
	var id_bank = $('#bank').combobox('getValue');
	var id_deposit = 0;
	$('#isian').window('close');
	$('#win-deposit').window('close');
	$('#sub').window('close');
	$('#valdep').window('close');
	$('#deldep').window('close');
	$('#notdeldep').window('close');

	$('#close_warn').click(function(event) {
		event.preventDefault();
		$('#isian').window('close');
	});
	$('#deposit_date').datebox().datebox('calendar').calendar({
                validator: function(date){
                    var now = new Date();
                    var d1 = new Date(now.getFullYear(), now.getMonth(), now.getDate()-30);
                    var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate()+1);
                    return d1<=date && date<=d2;
                }
      });

	$('#mutation_date').datebox().datebox('calendar').calendar({
                validator: function(date){
                    var now = new Date();
                    var d1 = new Date(now.getFullYear(), now.getMonth(), now.getDate()-30);
                    var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate()+7);
                    return d1<=date && date<=d2;
                }
      });
	$('#data_deposit').datagrid({
        url: base_url + '/get_data_deposit',
        striped: true,
        rownumbers:true,
		remoteSort:false,
		singleSelect:true,
		pagination:true,
		fit:false,
		autoRowHeight:false,
		fitColumns:true,
		toolbar :'#toolbar',
		onDblClickRow: function () {
			var rows = $(this).datagrid('getSelections');
			$('#depid').val(rows[0].deposit_id);
			$('#requery').attr('depid',rows[0].deposit_id);
			$('#validate').attr('depid',rows[0].deposit_id);
			$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + rows[0].bank_id + '/' + rows[0].deposit_id);
			$('#data_batch').datagrid({
				onLoadSuccess:function(data) {
					var rows2 = $(this).datagrid('getRows');
				    for(i=0;i<rows2.length;++i){
				    	if (rows2[i]['deposit_id'] == rows[0].deposit_id) {
				    		$(this).datagrid('checkRow',i);
				        	rows2[i]['ck'] = 1;
				    	};
				    }
				}
			});
			$('#bank').combobox({
				url: base_url + '/get_single_bank/' + rows[0].bank_id,
			    valueField: 'id',
				textField: 'name'
			});
			$('#deposit_num').textbox('setValue',rows[0].deposit_num);
			$('#deposit_date').datebox('setValue',rows[0].deposit_date);
			$('#deposit_jam').combobox('select',rows[0].deposit_date.substring(11,13));
			$('#deposit_min').combobox('select',rows[0].deposit_date.substring(14,16));
			$('#mutation_date').datebox('setValue',rows[0].mutation_date);
			$('#save').attr('depid',rows[0].deposit_id);
			$('#clear').show();
			$('#win-deposit').window('close');
			if ($('#user_role').val() > 3) {
				$('#validate').show();
				$('#del_deposit').show();
				$('#del_deposit').attr('depid',rows[0].deposit_id);
				$('#del_deposit').attr('depnum',rows[0].deposit_num);
			}
		},
		columns:[[
        	{field:'deposit_id',hidden:true},
        	{field:'bank_id',hidden:true},
        	{field:'deposit_num',title:'Deposit Num',width:150,align:"center",halign:"center"},
            {field:'bank_name',title:'Bank',width:120,align:"center",halign:"center"},
            {field:'deposit_date',title:'Deposit Date',width:100,align:"center",halign:"center",
            	formatter:function (value,row,index) {
	            	var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
        	},
            {field:'mutation_date',title:'Mutation Date',width:100,align:"center",halign:"center",
            	formatter:function (value,row,index) {
	            	var date = new Date(value);
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
        	},
            {field:'status',title:'Status',width:100,align:"center",halign:"center",
            	formatter:function (value,row,index) {
            		if (value == 'N') {
            			return 'New';
            		}else return 'Validated';
            	}
            },
            {field:'created_by',title:'Usename',width:100,align:"center",halign:"center"},
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

	$('#data_batch').datagrid({
        url: base_url + '/get_data_batch/' + id_bank,
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
			var ats = 0;
			var cts = 0;
			var dts = 0;
			for (var i = rows.length - 1; i >= 0; i--) {
				if (parseInt(rows[i].actual_total_amount)) {
					ats += parseInt(rows[i].actual_total_amount);
				}
				if (parseInt(rows[i].check_exchanges_total_amount)) {
					cts += parseInt(rows[i].check_exchanges_total_amount);
				}
			}
			dts = parseInt(ats) - parseInt(cts);

			$('#ats').numberbox('setValue', ats);
			$('#cts').numberbox('setValue', cts);
			$('#dts').numberbox('setValue', dts);
			/*if (rows.length > 0) {
				$.ajax({
					type: 'POST',
					url: base_url+'/get_sum_amount/',
					dataType : 'json',
					data: {
						'rows': rows
					},
					success:function (msg) {
						$('#ats').numberbox('setValue', msg.ata);
						$('#cts').numberbox('setValue', msg.cta);
						$('#dts').numberbox('setValue', msg.dta);
					}
				});
			};*/
		},
		onSelectAll: function () {
			var rows = $(this).datagrid('getSelections');
			var ats = 0;
			var cts = 0;
			var dts = 0;
			for (var i = 0; i < rows.length; i++) {
				if (parseInt(rows[i].actual_total_amount)) {
					ats += parseInt(rows[i].actual_total_amount);
				}
				if (parseInt(rows[i].check_exchanges_total_amount)) {
					cts += parseInt(rows[i].check_exchanges_total_amount);
				}
			}
			dts = parseInt(ats) - parseInt(cts);

			$('#ats').numberbox('setValue', ats);
			$('#cts').numberbox('setValue', cts);
			$('#dts').numberbox('setValue', dts);
			/*if (rows.length > 0) {
				$.ajax({
					type: 'POST',
					url: base_url+'/get_sum_amount/',
					dataType : 'json',
					data: {
						'rows': rows
					},
					success:function (msg) {
						$('#ats').numberbox('setValue', msg.ata);
						$('#cts').numberbox('setValue', msg.cta);
						$('#dts').numberbox('setValue', msg.dta);
					}
				});
			};*/
		},
		onUnselect: function () {
			var rows = $(this).datagrid('getSelections');
			var ats = 0;
			var cts = 0;
			var dts = 0;
			if (rows.length > 0) {
				for (var i = rows.length - 1; i >= 0; i--) {
					if (parseInt(rows[i].actual_total_amount)) {
						ats += parseInt(rows[i].actual_total_amount);
					}
					if (parseInt(rows[i].check_exchanges_total_amount)) {
						cts += parseInt(rows[i].check_exchanges_total_amount);
					}
				}
				dts = parseInt(ats) - parseInt(cts);

				$('#ats').numberbox('setValue', ats);
				$('#cts').numberbox('setValue', cts);
				$('#dts').numberbox('setValue', dts);
				/*$.ajax({
					type: 'POST',
					url: base_url+'/get_sum_amount/',
					dataType : 'json',
					data: {
						'rows': rows
					},
					success:function (msg) {
						$('#ats').numberbox('setValue', msg.ata);
						$('#cts').numberbox('setValue', msg.cta);
						$('#dts').numberbox('setValue', msg.dta);
					}
				});*/
			}else if (rows.length == 0) {
				$('#ats').numberbox('setValue', '0');
				$('#cts').numberbox('setValue', '0');
				$('#dts').numberbox('setValue', '0');
			};
		},
		onUnselectAll: function () {
			$('#ats').numberbox('setValue', '0');
			$('#cts').numberbox('setValue', '0');
			$('#dts').numberbox('setValue', '0');
		},
        columns:[[
        	{field:'ck',checkbox:true},
        	{field:'batch_id',hidden:true},
        	{field:'deposit_id',hidden:true},
            {field:'batch_number',title:'Batch Number',width:120,align:"center",halign:"center"},
            {field:'reference_num',title:'Reference Num',width:150,align:"center",halign:"center"},
            {field:'batch_type',title:'Batch Type',width:100,align:"center",halign:"center"},
            {field:'user_name',title:'Usename',width:100,align:"center",halign:"center"},
            {field:'batch_date',title:'Batch Date',width:150,align:"center",halign:"center",
	            formatter:function (value,row,index) {
	            	var date = new Date(value);
	            	options = {
						  year: 'numeric', month: 'long', day: 'numeric'
						};
					return Intl.DateTimeFormat('id-ID', options).format(date);
	            }
        	},
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

	$('#requery').click(function (event) {
		event.preventDefault();
		id_bank = $('#bank').combobox('getValue');
		//alert(id_bank);
		if ($(this).attr('depid') == "") {
			$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + id_bank);
			$('#ats').numberbox('setValue', '0');
			$('#cts').numberbox('setValue', '0');
			$('#dts').numberbox('setValue', '0');
		}else{
			id_deposit = $(this).attr('depid');
			$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + id_bank + '/' + id_deposit);
			$('#ats').numberbox('setValue', '0');
			$('#cts').numberbox('setValue', '0');
			$('#dts').numberbox('setValue', '0');
		};
	});

	$('#save').click(function (event) {
		event.preventDefault();
		if ($(this).attr('depid') == "") {
			var rows = $('#data_batch').datagrid('getSelections');
			var bank = $('#bank').combobox('getValue');
			var dep_num = $('#deposit_num').textbox('getValue');
			var dep_date = $('#deposit_date').datetimebox('getValue');
			var mut_date = $('#mutation_date').datebox('getValue');
			var dep_jam = $('#deposit_jam').datetimebox('getValue');
			var dep_min = $('#deposit_min').datetimebox('getValue');
			$.ajax({
				type: 'POST',
				url: base_url+'/cek_deposit_num/',
				data: {
					dep_num: $('#deposit_num').textbox('getValue')
				},
				success:function (msg) {
					if (msg == 0) {
						if (rows.length > 0) {
							if (dep_num != '' || dep_date != '' || mut_date != '') {
								$.ajax({
									type: 'POST',
									url: base_url+'/save_data_deposit/0',
									data: {
										'rows': $('#data_batch').datagrid('getSelections'),
										'id_bank': $('#bank').combobox('getValue'),
										'dep_num': $('#deposit_num').textbox('getValue'),
										'dep_date': $('#deposit_date').datebox('getValue'),
										'dep_jam' : $('#deposit_jam').datetimebox('getValue'),
										'dep_min' : $('#deposit_min').datetimebox('getValue'),
										'mut_date': $('#mutation_date').datebox('getValue'),
										'status': $('#status').textbox('getValue'),
										'ats': $('#ats').numberbox('getValue'),
										'cts': $('#cts').numberbox('getValue'),
										'dts': $('#dts').numberbox('getValue')
									},
									success:function (msg) {
										if (msg > 0) {
											$('#yes_val').attr('depid',msg);
											/*alert($('#user_role').val());*/
											if ($('#user_role').val() > 3) {
												$('#sub').window('open');
												$('#sub').window({
													onClose: function(data) {
														$('#deposit_num').textbox('setValue','');
														$('#deposit_date').datebox('setValue','');
														$('#mutation_date').datebox('setValue','');
														$('#ats').numberbox('setValue','');
														$('#cts').numberbox('setValue','');
														$('#dts').numberbox('setValue','');
														$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + bank);
													}
												});
											}
											else{
												$.messager.alert('Caution','Deposit berhasil disimpan.');
												$('#deposit_num').textbox('setValue','');
												$('#deposit_date').datebox('setValue','');
												$('#mutation_date').datebox('setValue','');
												$('#ats').numberbox('setValue','');
												$('#cts').numberbox('setValue','');
												$('#dts').numberbox('setValue','');
												$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + bank);
											}
										};
									}
								});
							}else{
								$('#isian').window('open');
							}
						}else{
							$('#isian').window('open');
						}
					}else $.messager.alert('Alert','Deposit Number sudah digunakan.','info');
				}
			});
		}else{
			var rows = $('#data_batch').datagrid('getSelections');
			var bank = $('#bank').combobox('getValue');
			var dep_num = $('#deposit_num').textbox('getValue');
			var dep_date = $('#deposit_date').datetimebox('getValue');
			var mut_date = $('#mutation_date').datebox('getValue');
			var dep_id = $(this).attr('depid');
			var dep_jam = $('#deposit_jam').datetimebox('getValue');
			var dep_min = $('#deposit_min').datetimebox('getValue');
			if (rows.length > 0) {
				if (bank != '' || dep_num != '' || dep_date != '' || mut_date != '') {
					$.ajax({
						type: 'POST',
						url: base_url+'/save_data_deposit/'+dep_id,
						data: {
							'rows': $('#data_batch').datagrid('getSelections'),
							'id_bank': $('#bank').combobox('getValue'),
							'dep_num': $('#deposit_num').textbox('getValue'),
							'dep_date': $('#deposit_date').datebox('getValue'),
							'dep_jam' : $('#deposit_jam').datetimebox('getValue'),
							'dep_min' : $('#deposit_min').datetimebox('getValue'),
							'mut_date': $('#mutation_date').datebox('getValue'),
							'status': $('#status').textbox('getValue'),
							'ats': $('#ats').numberbox('getValue'),
							'cts': $('#cts').numberbox('getValue'),
							'dts': $('#dts').numberbox('getValue')
						},
						success:function (msg) {
							if (msg > 0) {
								$('#yes_val').attr('depid',msg);
								if ($('#user_role').val() > 3) {
									$('#sub').window('open');
									$('#sub').window({
										onClose: function(data) {
											$('#deposit_num').textbox('setValue','');
											$('#deposit_date').datebox('setValue','');
											$('#mutation_date').datebox('setValue','');
											$('#ats').numberbox('setValue','');
											$('#cts').numberbox('setValue','');
											$('#dts').numberbox('setValue','');
											$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + bank);
											location.reload();
										}
									});
								}
								else{
									$.messager.alert('Caution','Deposit berhasil disimpan.');
									$('#deposit_num').textbox('setValue','');
									$('#deposit_date').datebox('setValue','');
									$('#mutation_date').datebox('setValue','');
									$('#ats').numberbox('setValue','');
									$('#cts').numberbox('setValue','');
									$('#dts').numberbox('setValue','');
									$('#data_batch').datagrid('reload',base_url + '/get_data_batch/' + bank);
									location.reload();
								}
							};
						}
					});
				}else{
					$('#isian').window('open');
				}
			}else{
				$('#isian').window('open');
			}
		}
	});

	$('#mutation_date').datebox({
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

	$('#deposit_date').datebox({
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

	$('#editdep').click(function(event) {
		event.preventDefault();
		$('#data_deposit').datagrid('reload',base_url + '/get_data_deposit');
		$('#win-deposit').window('open');
	});

	$('#clear').click(function (event) {
		location.reload();
	});

	$('#validate').click(function (event) {
		event.preventDefault();
		$.ajax({
			url: base_url+'/validate_deposit/',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success:function(msg) {
				if (msg > 0) {
					$('#valdep').window('open');
					location.reload();
				};
			}
		});
	});

	$('#yes_val').click(function (event) {
		event.preventDefault();
		$.ajax({
			url: base_url+'/validate_deposit/',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success:function(msg) {
				if (msg) {
					$('#sub').window('close');
					$('#valdep').window('open');
					setInterval(function(){ $('#valdep').window('close'); }, 1600);
				};
			}
		});
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

	$('#rrr').click(function(event) {
		event.preventDefault();
		$('#data_deposit').datagrid('load',{
	        deposit_num_sc: '',
	        deposit_date_sc: '',
	        mutation_date_sc: ''
	    });
	});

	$('#no_val').click(function(event) {
		event.preventDefault();
		$('#sub').window('close');
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
			url: base_url+'/delete_deposit',
			type: 'POST',
			data:{
				'depid': $(this).attr('depid')
			},
			success: function(msg) {
				if (msg > 0) {
					$('#deldep').window('close');
					$('#notdeldep').window('open');
					$('#data_inquiry_deposit').datagrid('reload','get_data_deposit_validate');
				};
				location.reload();
			}
		});
	});

	$('#no_del').click(function(event) {
		event.preventDefault();
		$('#deldep').window('close');
	});

	$('#bank_sc').combobox({
        url:base_url+'/getBankSc',
        valueField:'id',
        textField:'name'
    });

    $('#bank').combobox({
		onChange: function (value) {
			$("#requery").trigger( "click" );
		}
	});

});

function doSearch(){
    $('#data_deposit').datagrid('load',{
        bank_sc: $('#bank_sc').combobox('getValue'),
        deposit_num_sc: $('#deposit_num_sc').val(),
        deposit_date_sc: $('#deposit_date_sc').datebox('getValue'),
        mutation_date_sc: $('#mutation_date_sc').datebox('getValue'),
        username_sc: $('#username_sc').textbox('getValue')
    });
}

