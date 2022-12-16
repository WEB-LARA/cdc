var url;

$(document).ready(function() {
    var toko = [];
    $('#upload_new_store').dialog('close');
    $('#edit_new_store').dialog('close');
    $('#tblMasterShift').datagrid({
        url: base_url + 'master/Shift/getData',
        columns: [
            [{
                    field: 'SHIFT_ID',
                    hidden: true
                },
                {
                    field: 'BRANCH',
                    title: 'Branch',
                    width: 100,
                    align: 'center'
                },
                {
                    field: 'SHIFT_NUMBER',
                    title: 'Shift Number',
                    width: 50,
                    align: 'center'
                },
                {
                    field: 'SHIFT_TIME_FROM',
                    title: 'Shift Start',
                    width: 100,
                    align: 'center'
                },
                {
                    field: 'SHIFT_TIME_TO',
                    title: 'Shift To',
                    width: 100,
                    align: 'center'
                },
                {
                    field: 'ACTIVE_FLAG',
                    title: 'Active',
                    width: 25,
                    align: 'center'
                },
                {
                    field: 'INACTIVE_DATE',
                    title: 'Inactive Date',
                    width: 100,
                    align: 'center'
                }

            ]
        ],
        rownumbers: true,
        singleSelect: true,
        fitColumns: true
    });


    $("#BtnAddStore").click(function(event) {
        event.preventDefault();
        var toko = [];
        $('#temporary_store').datagrid('loadData', toko);
        $('#file_store').textbox('setValue', '');
        $('#upload_new_store').dialog('open');
    });

    $("#del_store").click(function(event) {
        event.preventDefault();
        var rows = [];
        var dg = $('#temporary_store');
        $.map(dg.datagrid('getChecked'), function(row) {
            var index = dg.datagrid('getRowIndex', row);

            //console.log(toko);

            toko.splice(index, 1);
            // console.log(toko);
            $('#temporary_store').datagrid('loadData', toko);

        });

    });




    $("#validate_store").click(function(event) {
        event.preventDefault();
        //    alert(JSON.parse(toko));
        $.ajax({
            method: "POST",
            url: base_url + "master/Shift/insertMasterShift",
            data: {
                toko: toko,

            },

            success: function(message) {
                if (message != '0') {


                    $.messager.show({
                        title: 'Success',
                        msg: 'Data berhasil diupdate !'
                    });
                    $('#upload_new_store').dialog('close');

                } else {
                    $.messager.show({
                        title: 'Success',
                        msg: 'Data gagal diupdate !'
                    });
                    $('#upload_new_store').dialog('close');
                }

            }
        });

        $('#tblShift').datagrid('load', base_url + '/master/Shift/getDataMasterShift'); //
    });
    $("#form_upload_master_shift").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('curcname_shift', $('#curcname_shift').val());

        $.ajax({
            url: '../../Upload/upload_master_shift_toko',
            type: 'POST',
            data: formData,
            success: function(response) {
                data = JSON.parse(response);

                if (response != '{}') {


                    var hasil = JSON.parse(response);



                    if (hasil['msg'] == 'success') {

                        if (hasil['rows'].length > 0) {

                            header = hasil['rows'];
                            toko = header;
                            //        var data_array={"total":""+hasil['total']+"", "rows":hasil['rows']};

                            $('#temporary_store').datagrid('loadData', header);
                            //                  $('#temporary_store').datagrid({
                            //     data: data_array,

                            // });
                        }

                    } else {
                        //                	$.messager.show({
                        // 	title:'Error',
                        // 	msg:''+hasil['msg'],
                        // 	timeout:5000,
                        // 	showType:'slide'
                        // });


                        $.messager.alert('Warning', hasil['msg']).window({
                            width: 500,
                            height: 500
                        });
                    }



                }

                $('#file_store').textbox('setValue', '');



                //                 }

                //               }



                //            }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("#temp_store").click(function(event) {
        event.preventDefault();
        window.open(base_url + 'Upload/download_template_store/', "Template Upload Store", "width=1000,height=600,scrollbars=yes");

    });



});


function BtnResetForm() {

    $('#store_code').combobox('select', '');
    $('#metode_setor').combobox('select', '');
    $('#start_date').datebox('setValue', '');
    $('#end_date').datebox('setValue', '');
    $('#jml_shift').combobox('select', '');
    $('#tipe_shift').combobox('select', '');
    $('#status').combobox('select', '');
    $('#tblShift').datagrid({
        url: base_url + "master/Shift/getDataMasterShift",
        queryParams: {
            store_code: $('#store_code').combobox('getValue'),
            start_date: $('#start_date').datebox('getValue'),
            end_date: $('#end_date').datebox('getValue'),
            tipe_shift: $('#tipe_shift').combobox('getValue'),
            jumlah_shift: $('#jml_shift').combobox('getValue'),
            metode_setor: $('#metode_setor').combobox('getValue'),
            status: $('#status').combobox('getValue')

        }
    });

}


function searchMasterShift() {
    //alert('aneh');
    $('#tblShift').datagrid({
        url: base_url + "master/Shift/getDataMasterShift",
        queryParams: {
            store_code: $('#store_code').combobox('getValue'),
            start_date: $('#start_date').datebox('getValue'),
            end_date: $('#end_date').datebox('getValue'),
            tipe_shift: $('#tipe_shift').combobox('getValue'),
            jumlah_shift: $('#jml_shift').combobox('getValue'),
            metode_setor: $('#metode_setor').combobox('getValue'),
            status: $('#status').combobox('getValue')

        }
    });
    // $.ajax({
    // 		  method: "POST",
    // 		  url: base_url+"master/Shift/getDataMasterShift",
    // 		  data: { 
    // 					store_code : $('#store_code').combobox('getValue'),
    // 					start_date : $('#start_date').datebox('getValue'),
    // 					end_date:$('#end_date').datebox('getValue'),
    // 				    tipe_shift : $('#tipe_shift').combobox('getValue'),
    // 				    jml_shift : $('#jml_shift').combobox('getValue'),
    // 					metode_setor  : $('#metode_setor').combobox('getValue'),
    // 					status  : $('#status').combobox('getValue')

    // 				},

    // 		  success: function (message) {

    // 			 data = JSON.parse(message);

    // 				  if(message!='{}'){


    //                      var hasil=JSON.parse(message);



    //                      if(typeof hasil['rows'] !== 'undefined' ){

    //                        if(hasil['rows'].length>0){

    //                          header=hasil['rows'];
    //                            toko=header;




    //                        }

    //                      }



    //                    }
    //                    alert(header);
    //                     $('#tblShift').datagrid('loadData',header);
    // 		  }
    // 		});
}


function closeMasterShift() {
    $('#edit_new_store').dialog('close');



}

function saveEMasterShift() {
    if ($('#e_start_date').datebox('getValue') != '' && $('#e_tipe_shift').combobox('getValue') != '' && $('#e_tipe_shift').combobox('getValue') != '' && $('#e_jml_shift').combobox('getValue') != '' && $('#e_metode_setor').combobox('getValue') != '') {
        var tipe_shift = '';
        if ($('#e_tipe_shift').combobox('getValue') == 'Harian Shift' || $('#e_tipe_shift').combobox('getValue') == 'H-1') {
            tipe_shift = 'H-1';

        } else if ($('#e_tipe_shift').combobox('getValue') == 'Harian' || $('#e_tipe_shift').combobox('getValue') == 'H') {
            tipe_shift = 'H';
        } else {
            tipe_shift = 'SS';
        }

        $.ajax({
            method: "POST",
            url: base_url + "master/Shift/updateMasterShift",
            data: {
                store_code: $('#e_store_code').combobox('getValue'),
                start_date: $('#e_start_date').datebox('getValue'),
                end_date: $('#e_end_date').datebox('getValue'),
                tipe_shift: tipe_shift,
                jml_shift: $('#e_jml_shift').combobox('getValue'),
                metode_setor: $('#e_metode_setor').combobox('getValue'),
            },

            success: function(message) {
                //alert(message);
                if (message == 0) {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Data tidak berhasil diupdate.'
                    });

                } else {
                    $.messager.show({
                        title: 'Success',
                        msg: 'Data berhasil diupdate.'
                    });

                }
                $('#edit_new_store').dialog('close');
                $('#tblShift').datagrid('load', base_url + '/master/Shift/getDataMasterShift'); //
            }
        });


    } else {
        $.messager.show({
            title: 'Warning',
            msg: 'Harap Lengkapi data terlebih dahulu.'
        });
    }

}

function editMasterShift() {
    var row = $('#tblShift').datagrid('getSelected'); //datagrid
    var tipe_shift = '';
    if (row) {

        $.ajax({
            method: "POST",
            url: base_url + "master/Shift/getDataMasterShiftDetail",
            data: {
                ID_SHIFT: row.ID_SHIFT
            },

            success: function(message) {
                //alert(message);


                var result = JSON.parse(message);
                //    alert(result.STORE_CODE);

                var dateParts = result.TGL_ACTIVE.split("-");
                var dateParts1 = '';
                if (result.TGL_INACTIVE != null) {
                    dateParts1 = result.TGL_INACTIVE.split("-");
                    $('#e_end_date').datebox('setValue', dateParts1[2] + '-' + dateParts1[1] + '-' + dateParts1[0]);
                } else {
                    $('#e_end_date').datebox('setValue', '');
                }

                // month is 0-based, that's why we need dataParts[1] - 1
                $('#e_store_code').combobox('setValue', result.STORE_CODE);
                $("#e_store_code").combobox('disable');
                $('#e_metode_setor').combobox('select', result.TIPE_SETORAN);
                $('#e_start_date').datebox('setValue', dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0]);
                if (result.TIPE_SHIFT == 'H-1') {
                    tipe_shift = 'Harian Shift';

                } else if (result.TIPE_SHIFT == 'H') {
                    tipe_shift = 'Harian';
                } else {
                    tipe_shift = 'Sales Shift';
                }
                $('#e_tipe_shift').combobox('setValue', tipe_shift);
                $('#e_jml_shift').combobox('setValue', result.TOTAL_SHIFT);
                $('#edit_new_store').dialog('open');

            }
        });

        //alert(row.ID_SHIFT);

    } else {
        $.messager.show({
            title: 'Warning',
            msg: 'Harap pilih dahulu data yang ingin diedit.',
            timeout: 5000,
            showType: 'slide'
        });
    }


}

function delMasterShift() {
    var row = $('#tblShift').datagrid('getSelected'); //datagrid
    if (row) {

        $.messager.confirm('Confirm', 'Apakah anda yakin untuk menghapus Data ' + row.TOKO + '  ?', function(r) {
            if (r) {

                $.ajax({
                    method: "POST",
                    url: base_url + "master/Shift/deleteMasterShift",
                    data: {
                        ID_SHIFT: row.ID_SHIFT
                    },

                    success: function(message) {
                        //alert(message);
                        if (message == 0) {
                            $.messager.show({
                                title: 'Error',
                                msg: 'Data tidak berhasil dihapus.'
                            });

                        } else {
                            $.messager.show({
                                title: 'Success',
                                msg: 'Data berhasil dihapus.'
                            });

                        }
                        $('#tblShift').datagrid('load', base_url + '/master/Shift/getDataMasterShift'); //
                    }
                });

            }
        });

        //alert(row.ID_SHIFT);

    } else {
        $.messager.show({
            title: 'Warning',
            msg: 'Harap pilih dahulu data yang ingin dihapus.',
            timeout: 5000,
            showType: 'slide'
        });
    }
}

function tambah() {
    var branchCode = $('#tambah').attr('branchCode');

    $.ajax({
        method: "POST",
        url: base_url + "master/Shift/cekData",
        data: {
            branchCode: branchCode
        },

        success: function(message) {
            //alert(message);
            if (message == 0) {
                //alert(message);
                $('#shift_dialog').dialog('open');
                $('#shift_dialog').dialog('center');
                $('#shift_dialog').dialog('setTitle', 'New Shift');
                $('#shift_id').attr('style', 'display:none');

                $('#shift_form').form('clear');
                $('#activeFlag').combobox('setValue', 'Y');
                $('#activeFlag').combobox('disable');
            } else {
                alert('Data Shift Sudah Ada!!');
            }

        }
    });
}

function ganti() {
    $('#shift_id_edit').attr('style', 'display:none');

    var row = $('#tblMasterShift').datagrid('getSelected'); //datagrid
    if (row) {
        $('#shift_edit_dialog').dialog('open');
        $('#shift_edit_dialog').dialog('center');
        $('#shift_edit_dialog').dialog('setTitle', 'Edit Shift');

        $('#activeFlag').combobox('enable');


        $('#shift_edit_form').form('load', row); //form diisi data ssuai row

    }

}

/* ACTION ON KLIK SAVE, ON DIALOG*/
function save() {
    if ($('#shift_form').form('validate') == true) {
        $.ajax({
            method: "POST",
            url: base_url + "master/Shift/addData",
            data: {
                start1: $('#shift1_start').timespinner('getValue'),
                end1: $('#shift1_end').timespinner('getValue'),
                start2: $('#shift2_start').timespinner('getValue'),
                end2: $('#shift2_end').timespinner('getValue'),
                start3: $('#shift3_start').timespinner('getValue'),
                end3: $('#shift3_end').timespinner('getValue'),
                activeFlag: $('#activeFlag').combobox('getValue')
                //inactiveDate 	: $('#inactiveDate').datebox('getValue')
            },

            success: function(message) {
                //alert(message);
                $.messager.show({
                    title: 'Success',
                    msg: message
                });
                $('#shift_form').form('clear');
                $("#shift_dialog").dialog('close');
                $('#tblMasterShift').datagrid('reload');
            }
        });

    } else {
        alert('Cek kembali field Anda !');
        return false;
    }

}


function hapus() {
    var row = $('#tblMasterShift').datagrid('getSelected');
    //alert(row.BRANCH_ID);
    if (row) {
        $.messager.confirm('Confirm', 'Are you sure you want to destroy this item?', function(r) {
            if (r) {
                $.post(base_url + "master/Shift/deleteData", {
                    shiftId: row.BRANCH_ID
                }, function(result) {
                    if (result.success) {
                        //alert('Data berhasil dihapus !');
                        $.messager.show({
                            title: 'Success',
                            msg: 'Data berhasil dihapus !'
                        });
                        $('#tblMasterShift').datagrid('load'); //reload data
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                }, 'json');
            }

        });
    }
}


function cancel() {
    $('#shift_form').form('clear');
    $("#shift_dialog").dialog('close');
}

function saveEdit() {
    $.ajax({
        method: "POST",
        url: base_url + "master/Shift/saveEdit",
        data: {
            shiftId: $('#shiftId_edit').textbox('getValue'),
            start: $('#shift_start_edit').timespinner('getValue'),
            end: $('#shift_end_edit').timespinner('getValue'),
            activeFlag: $('#activeFlag_edit').combobox('getValue')
        },

        success: function(message) {
            $.messager.show({
                title: 'Success',
                msg: message
            });
            $('#shift_edit_form').form('clear');
            $("#shift_edit_dialog").dialog('close');
            $('#tblMasterShift').datagrid('load');
        }
    });
}

function cancelEdit() {
    $('#shift_edit_form').form('clear');
    $("#shift_edit_dialog").dialog('close');
}