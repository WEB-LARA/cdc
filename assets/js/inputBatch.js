var salesDate   = null;
var storeID     = null;
var salesFlag = 'Y';
var stnFlag = 'N';
var tgl = new Date(), date=tgl.getDate() ,month = tgl.getMonth(), year = tgl.getFullYear();
var now = tgl.getDate()+'/'+tgl.getMonth()+'/'+tgl.getFullYear();
var dataId;
var recID, penambahID, pengurangID,voucherID, voucherNum;
var recStatus = 'n';
var GTU_status, voucher_status;
$.messager.defaults.ok = 'Ya';
$.messager.defaults.cancel = 'Tidak';

function totalGTUInput() {
    $.ajax({
        method: "POST",
        url: base_url+'input/Trx_GTU/get_total_gtu/',
        success: function (total) {
            $("#totalGTUInput").numberbox('setValue',total);
        }
    });

    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getGrandTotalShift",
        success: function (total) {
            $('#grandTotal').numberbox('setValue', total); 
        }
    });
}

function totalSetor(){
    $('#btnSave').linkbutton('resize', {
        width: '100%',
        height: 32
    });
    
    $('#btnReset').linkbutton('resize', {
        width: '100%',
        height: 32
    });

    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getTotalSetorShift",
        success: function (total) {
            $('#totalSetor').numberbox('setValue', total);
        }
    }); 

    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getTotalSetorFShift",
        success: function (total) {
            $('#totalSetorF').numberbox('setValue', total); 
        }
    });

    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getGrandTotalShift",
        success: function (total) {
            $('#grandTotal').numberbox('setValue', total); 
        }
    });
}

function totalSetorReject(){
    $('#btnSave').linkbutton('resize', {
        width: '100%',
        height: 32
    }); 
    $('#btnReset').linkbutton('resize', {
        width: '100%',
        height: 32
    });
    var batch_id = $('#savBatch').attr('batchid');
    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getBatchType/"+batch_id,
        success: function (msg) {
            if (msg == 'R-STJ') {
                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/getTotalSetorReject/"+$('#savBatch').attr('batchid'),
                    success: function (total) {
                        $('#totalSetor').numberbox('setValue', total); 
                    }
                });
            }else{
                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/getTotalSetorReject/"+$('#savBatch').attr('batchid'),
                    success: function (total) {
                        $('#totalSetorF').numberbox('setValue', total); 
                    }
                });
            }
        }
    });
}

function totalSetorRejectShift(){
    $('#btnSave').linkbutton('resize', {
        width: '100%',
        height: 32
    }); 
    $('#btnReset').linkbutton('resize', {
        width: '100%',
        height: 32
    });
    var batch_id = $('#savBatch').attr('batchid');
    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getBatchType/"+batch_id,
        success: function (msg) {
            if (msg == 'R-STJ') {
                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/getTotalSetorRejectShift/"+$('#savBatch').attr('batchid'),
                    success: function (total) {
                        $('#totalSetor').numberbox('setValue', total); 
                    }
                });
            }else{
                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/getTotalSetorRejectShift/"+$('#savBatch').attr('batchid'),
                    success: function (total) {
                        $('#totalSetorF').numberbox('setValue', total); 
                    }
                });
            }
        }
    });
}




function delete_data_stl(rec_id, stl_id) {
    $.ajax({
        url: base_url+'InputBatch/delete_data_stl/',
        type: 'POST',
        data: {
            rec_id: rec_id,
            stl_id: stl_id
        },
        success: function (msg) {
            $("#data-trx-stl").datagrid('reload');
            if (msg == 0) {
                $.messager.alert('Warning','Hapus data Setoran Lain - lain gagal.');
            }
        }
    });
}

$(document).ready(function(){

    $("#flagshift").combobox('disable');
    $('#form_mutation_date').window('close');
    $('#form-bank-kurn').window('close');
    $("#modal_input_kurset").window('close');
    $("#modal_detail_kurset").window('close');
    $("#prog-trans").window('close');
    var start_input_time = $('#start_input_time').textbox('getValue');

    jQuery(document).bind('keydown', 'ctrl+b',function (evt){
        $("#genBatch").trigger('click');
        return false;
    });

    jQuery(document).bind('keydown', 'ctrl+g',function (evt){
        $("#gtuBatch").trigger('click');
        return false;
    });

    jQuery(document).bind('keydown', 'ctrl+k',function (evt){
        $("#input-kurset").trigger('click');
        return false;
    });

    jQuery(document).bind('keydown', 'ctrl+l',function (evt){
        $("#input-stl").trigger('click');
        return false;
    });

    

    $("#input-stl").click(function(event) {
        event.preventDefault();
        $("#data-trx-stl").datagrid('reload');
        $("#stl-id").val('');
        $("#rec-id").val('');
        $('#stl-category').combobox('setValue', '');
        $('#stl-desc').textbox('setValue', '');
        $('#stl-date').datebox('setValue', '');
        $('#stl-amount').numberbox('setValue', '');
        $('#stl-stn-flag').prop('checked', false);
        $("#stl-bank").combobox('select', '');
        $("#stl-bank-acc").combobox('select', '');
        $('#stl-mut-date').datebox('setValue', '');
        $('#stl-stn-flag').val('0');
        $("#stl-acc-id").val('');
        $("#stl-mutation-date").val('');
        $("#clear-stl").hide();
        $("#modal-stl").window('open');
    });

    $('#stl-date').datebox({
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

    $('#stl-category').combobox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#save-stl").trigger('click');
        $("#data-trx-stl").datagrid('reload');
        return false;
    });

    $('#stl-desc').textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#save-stl").trigger('click');
        $("#data-trx-stl").datagrid('reload');
        return false;
    });

    $('#stl-date').datebox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#save-stl").trigger('click');
        $("#data-trx-stl").datagrid('reload');
        return false;
    });

    $('#stl-amount').numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#save-stl").trigger('click');
        $("#data-trx-stl").datagrid('reload');
        return false;
    });

    /////////START UPDATE 25-07-22/////////////
    /*
        inputan STL bisa pilih toko untuk kepentingan pembeda BATCH pada REG/FRC
    */

    $("#stl-toko").textbox("textbox").keydown(function(e){

        if(e.which == 13){
            $('#stl-toko').textbox('disable');
            var value = $(this).val().toUpperCase();
            //alert(value);
            if(value.length == 0){
                alert('TIDAK ADA DATA');
            }
            else if((value.length == 3)||(value.length >4))
            {
                alert('JUMLAH KODE TOKO SALAH / UNTUK INPUTAN KTR TIDAK PERLU DIINPUT !');
            }
            else if(value.length == 4)
            {
                 $.ajax({
                            method: "POST",
                            url: base_url+"master/Toko/getStore",
                            data: { 
                                storeCode       : value
                            },

                            success: function (storeNameHasil) {
                                if (storeNameHasil == 'FALSE') {
                                    $('#stl-toko').textbox('enable');
                                    alert('Kode Toko Tidak Terdaftar.');
                                    $('#stl-toko').textbox('setValue', '');  
                                }else{
                                    $('#stl-toko').textbox('enable');
                                    $('#stl-toko').textbox('setValue', value+'-'+storeNameHasil);
                                    $('#stl_store_code').val(value);
                                    $("#stl-desc").textbox('textbox').focus();
                                }
                            }
                        });


            }
         
        }
    });

    

    $("#save-stl").click(function(event) {
        event.preventDefault();
        var category = $('#stl-category').combobox('getValue');
        var desc = $('#stl-desc').textbox('getValue');
        var store = $('#stl_store_code').val();
        var date = $('#stl-date').datebox('getValue');
        var amount = $('#stl-amount').numberbox('getValue');
        var stn_flag = $("#stl-stn-flag").val();
        var acc_id = $("#stl-acc-id").val();
        var mutation_date = $("#stl-mutation-date").val();
        var stl_id = $("#stl-id").val();
        var rec_id = $("#rec-id").val();

        if(store=='')
        {
            store = 'KTR';
        }

        if (category != '' && desc != '' && date != '' && amount != '') {
            $.ajax({
                url: base_url+'InputBatch/save_data_stl/',
                type: 'POST',
                data: {
                    stl_id: stl_id,
                    rec_id: rec_id,
                    category: category,
                    desc: desc,
                    date: date,
                    amount: amount,
                    stn_flag: stn_flag,
                    acc_id: acc_id,
                    mutation_date: mutation_date,
                    store:store
                },
                success: function (msg) {
                    $("#data-trx-stl").datagrid('reload');
                    $("#stl-id").val('');
                    $("#rec-id").val('');
                    $('#stl-category').combobox('setValue', '');
                    $('#stl_store_code').val('');
                    $('#stl-toko').textbox('setValue', '');
                    $('#stl-desc').textbox('setValue', '');
                    $('#stl-date').datebox('setValue', '');
                    $('#stl-amount').numberbox('setValue', '');
                    $('#stl-stn-flag').prop('checked', false);
                    $("#stl-bank").combobox('select', '');
                    $("#stl-bank-acc").combobox('select', '');
                    $('#stl-mut-date').datebox('setValue', '');
                    $('#stl-stn-flag').val('0');
                    $("#stl-acc-id").val('');
                    $("#stl-mutation-date").val('');
                    $("#clear-stl").hide();
                    if (msg == 0) {
                        $.messager.alert('Warning','Input data Setoran Lain - lain gagal.');
                    }
                }
            });
        } else {
            $.messager.alert('Warning','Kolom harus diisi dengan lengkap.');
        }
    });

    $("#clear-stl").click(function(event) {
        event.preventDefault();
        $("#data-trx-stl").datagrid('reload');
        $("#stl-id").val('');
        $("#rec-id").val('');
        $('#stl-category').combobox('setValue', '');
        $('#stl_store_code').val('');
        $('#stl-toko').textbox('setValue', '');
        $('#stl-desc').textbox('setValue', '');
        $('#stl-date').datebox('setValue', '');
        $('#stl-amount').numberbox('setValue', '');
        $('#stl-stn-flag').prop('checked', false);
        $("#stl-bank").combobox('select', '');
        $("#stl-bank-acc").combobox('select', '');
        $('#stl-mut-date').datebox('setValue', '');
        $('#stl-stn-flag').val('0');
        $("#stl-acc-id").val('');
        $("#stl-mutation-date").val('');
        $("#clear-stl").hide();
    });

    $("#data-trx-stl").datagrid({
        url: base_url+'InputBatch/get_data_stl/',
        striped: true,
        rownumbers:true,
        remoteSort:false,
        pagination:true,
        singleSelect:true,
        fit:true,
        autoRowHeight:false,
        fitColumns:true,
        toolbar :'#toolbar',
        onDblClickRow: function () {
            var rows = $(this).datagrid('getSelected');
            $("#stl-id").val(rows.CDC_STL_ID);
            $("#rec-id").val(rows.CDC_REC_ID);
            $('#stl-category').combobox('select', rows.CDC_MASTER_STL_ID);
            $('#stl-toko').textbox('setValue', rows.STORE_CODE);
            $('#stl-desc').textbox('setValue', rows.DESCRIPTION);
            $('#stl-date').datebox('setValue', rows.TRX_DATE);
            $('#stl-amount').numberbox('setValue', rows.AMOUNT);
            if (rows.STN_FLAG == 'Y') {
                $('#stl-stn-flag').val('1');
                $('#stl-stn-flag').prop('checked', true);
                $("#stl-acc-id").val(rows.BANK_ACCOUNT_ID);
                $("#stl-mutation-date").val(rows.MUTATION_DATE);
                $("#stl-bank").combobox('select', rows.BANK_ID);
                $("#stl-bank-acc").combobox('select', rows.BANK_ACCOUNT_ID);
                $('#stl-mut-date').datebox('setValue', rows.MUTATION_DATE);
            } else {
                $('#stl-stn-flag').val('0');
                $('#stl-stn-flag').prop('checked', false);
                $("#stl-acc-id").val('');
                $("#stl-mutation-date").val('');
                $("#stl-bank").combobox('select', '');
                $("#stl-bank-acc").combobox('select', '');
                $('#stl-mut-date').datebox('setValue', '');
            }
            $("#clear-stl").show();
        },
        columns:[[
            {field:'CDC_STL_ID',hidden:true},
            {field:'CDC_MASTER_STL_ID',hidden:true},
            {field:'CDC_REC_ID',hidden:true},
            {field:'TRX_DATE',hidden:true},
            {field:'MUTATION_DATE',hidden:true},
            {field:'BANK_ACCOUNT_ID',hidden:true},
            {field:'BANK_ID',hidden:true},
            {field:'CATEGORY',title:'Category',width:100,align:"center",halign:"center"},
            {field:'STORE_CODE',title:'Store Code',width:120,align:"center",halign:"center"},
            {field:'DESCRIPTION',title:'Description',width:150,align:"center",halign:"center"},
            {field:'TRX_DATE_FORMAT',title:'Transaction Date',width:100,align:"center",halign:"center"},
            {field:'STN_FLAG',title:'Via',width:80,align:"center",halign:"center",
                formatter:function (value,row,index) {
                    if (value == "N") {
                        return "Tunai";
                    }else {
                        return "Transfer";
                    }
                }
            },
            {field:'AMOUNT',title:'Total Amount',width:100,align:"right",halign:"center",
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }
            },
            {field: 'BUTTON_DELETE', title: 'Action' ,width:60 ,align:'center',
                formatter: function (value, row, index) {
                    var col = '<input type="button" value="Delete" onClick="delete_data_stl('+row.CDC_REC_ID+', '+row.CDC_STL_ID+')">';
                    return col;
                }
            }
        ]]
    });

    /////////END UPDATE 25-07-22/////////////

    $("#input-kurset").click(function(event) {
        event.preventDefault();
        $("#ttk_num").textbox('setValue', '');
        $("#modal_input_kurset").window('open');
    });

    /*$("#cashPenggantian").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSave();
        return false;
    });*/
    $("#cashPenggantian").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSaveShift();
        return false;
    });

    $("#cashPenggantian").numberbox('textbox').bind('keydown', 'ctrl+r',function (evt){
        $("#btnReset").trigger('click');
        return false;
    });

    /*$("#totalPenambah").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSave();
        return false;
    });*/

    $("#totalPenambah").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSaveShift();
        return false;
    });

    $("#totalPenambah").numberbox('textbox').bind('keydown', 'ctrl+r',function (evt){
        $("#btnReset").trigger('click');
        return false;
    });

    /*$("#totalPengurang").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSave();
        return false;
    });*/

    $("#totalPengurang").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSaveShift();
        return false;
    });

    $("#totalPengurang").numberbox('textbox').bind('keydown', 'ctrl+r',function (evt){
        $("#btnReset").trigger('click');
        return false;
    });

    /*$("#totalVoucher").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSave();
        return false;
    });*/

    $("#totalVoucher").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        receiptSaveShift();
        return false;
    });

    $("#totalVoucher").numberbox('textbox').bind('keydown', 'ctrl+r',function (evt){
        $("#btnReset").trigger('click');
        return false;
    });

    $("#save-penambah").click(function(event) {
        event.preventDefault();
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
    });

    $("#save-penambah-shift").click(function(event) {
        event.preventDefault();
        $("#input-penambah-shift").window('close');
        for(var i = 1; i <= 3; i++){
            save_data_penambah_shift(i);    
        }
        $("#totalPengurang").numberbox('textbox').focus();
    });

    $("#save-pengganti").click(function(event) {
        event.preventDefault();
        $("#input-pengganti").window('close');
        for(var i = 1;i <= 3; i++){
            save_data_pengganti(i); 
        }
        $("#totalPenambah").numberbox('textbox').focus();
    });



    $('#pendate\\[9\\]').datebox({
        onSelect: function (date) {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/9',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    var df = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
                    if (msg.length > 0) {
                        $("#pendesc\\[9\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+df);
                    }
                }
            });         
        },
        onChange: function() {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/9',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var dat = $('#pendate\\[9\\]').datebox('getValue');
                    if (msg.length > 0) {
                        $("#pendesc\\[9\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+dat.replace(/-/gi, '/'));
                    }
                }
            });
        },
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });

    //total peng
    $("#peng\\[1\\]").numberbox({
        onChange: function() {
            set_total_column_pengganti();
        }
    });

    $("#peng\\[2\\]").numberbox({
        onChange: function() {
            set_total_column_pengganti();
        }
    });

    $("#peng\\[3\\]").numberbox({
        onChange: function() {
            set_total_column_pengganti();
        }
    });


    //total penambah shift
    //penambah shift 1
    $("#penam\\[1\\]\\[9\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift1(1);
            
        }
    });

    $("#penam\\[1\\]\\[10\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift1(1);
            
        }
    });

    $("#penam\\[1\\]\\[11\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift1(1);
            
        }
    });

    $("#penam\\[1\\]\\[12\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift1(1);
            
        }
    });

    $("#penam\\[1\\]\\[13\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift1(1);
            
        }
    });

    $("#total-penambah-amt-shift1").numberbox({
        onChange: function() {
            set_gtotal_column_penambah_shift();
        }
    });

    
    //penambah shift 2
    
    $("#penam\\[2\\]\\[9\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift2(2);
        }
    });

    $("#penam\\[2\\]\\[10\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift2(2);
        }
    });

    $("#penam\\[2\\]\\[11\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift2(2);
        }
    });

    $("#penam\\[2\\]\\[12\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift2(2);
        }
    });

    $("#penam\\[2\\]\\[13\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift2(2);
        }
    });

    $("#total-penambah-amt-shift2").numberbox({
        onChange: function() {
            set_gtotal_column_penambah_shift();
        }
    });

    //penambah shift 3

    $("#penam\\[3\\]\\[9\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift3(3);
        }
    });

    $("#penam\\[3\\]\\[10\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift3(3);
        }
    });

    
    $("#penam\\[3\\]\\[11\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift3(3);
        }
    });

    $("#penam\\[3\\]\\[12\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift3(3);
        }
    });

    $("#penam\\[3\\]\\[13\\]").numberbox({
        onChange: function() {
            set_total_column_penambah_shift3(3);
        }
    });

    $("#total-penambah-amt-shift3").numberbox({
        onChange: function() {
            set_gtotal_column_penambah_shift();
        }
    });


    $("#pendate\\[9\\]").textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    }); 

    $("#penam\\[9\\]").numberbox({
        onChange: function() {
            set_total_column_penambah();
        }
    });

    $("#penam\\[9\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    });

    $('#pendate\\[10\\]').datebox({
        onSelect: function (date) {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/10',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    var df = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
                    if (msg.length > 0) {
                        $("#pendesc\\[10\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+df);
                    }
                }
            });         
        },
        onChange: function() {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/10',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var dat = $('#pendate\\[10\\]').datebox('getValue');
                    if (msg.length > 0) {
                        $("#pendesc\\[10\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+dat.replace(/-/gi, '/'));
                    }
                }
            });
        },
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });

    $("#pendate\\[10\\]").textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    }); 

    $("#penam\\[10\\]").numberbox({
        onChange: function() {
            set_total_column_penambah();
        }
    });
    
    $("#penam\\[10\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    });

    $('#pendate\\[11\\]').datebox({
        onSelect: function (date) {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/11',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    var df = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
                    if (msg.length > 0) {
                        $("#pendesc\\[11\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+df);
                    }
                }
            });         
        },
        onChange: function() {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/11',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var dat = $('#pendate\\[11\\]').datebox('getValue');
                    if (msg.length > 0) {
                        $("#pendesc\\[11\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+dat.replace(/-/gi, '/'));
                    }
                }
            });
        },
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });

    $("#pendate\\[11\\]").textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    }); 

    $("#penam\\[11\\]").numberbox({
        onChange: function() {
            set_total_column_penambah();
        }
    });
    
    $("#penam\\[11\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    });

    $('#pendate\\[12\\]').datebox({
        onSelect: function (date) {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/12',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    var df = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
                    if (msg.length > 0) {
                        $("#pendesc\\[12\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+df);
                    }
                }
            });         
        },
        onChange: function() {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/12',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var dat = $('#pendate\\[12\\]').datebox('getValue');
                    if (msg.length > 0) {
                        $("#pendesc\\[12\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+dat.replace(/-/gi, '/'));
                    }
                }
            });
        },
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });

    $("#pendate\\[12\\]").textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    }); 

    $("#penam\\[12\\]").numberbox({
        onChange: function() {
            set_total_column_penambah();
        }
    });
    
    $("#penam\\[12\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    });

    $('#pendate\\[13\\]').datebox({
        onSelect: function (date) {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/13',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    var df = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
                    if (msg.length > 0) {
                        $("#pendesc\\[13\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+df);
                    }
                }
            });         
        },
        onChange: function() {
            $.ajax({
                url: base_url+'input/Trx_Tambah/get_master_penambah/13',
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    var store = $("#storeCode").textbox('getValue');
                    var dat = $('#pendate\\[13\\]').datebox('getValue');
                    if (msg.length > 0) {
                        $("#pendesc\\[13\\]").textbox('setValue', msg[0].TRX_PLUS_DESC+' '+store+' '+dat.replace(/-/gi, '/'));
                    }
                }
            });
        },
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });

    $("#pendate\\[13\\]").textbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    }); 

    $("#penam\\[13\\]").numberbox({
        onChange: function() {
            set_total_column_penambah();
        }
    });
    
    $("#penam\\[13\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-penambah").window('close');
        save_data_penambah();
        $("#totalPengurang").numberbox('textbox').focus();
        return false;
    });

    $("#save-pengurang").click(function(event) {
        event.preventDefault();
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
    });

    $("#save-pengurang-shift").click(function(event) {
        event.preventDefault();
        $("#input-pengurang-shift").window('close');
        for(var i = 1; i <= 3; i++){
            save_data_pengurang_shift(i);   
        }
        $("#totalVoucher").numberbox('textbox').focus();
    });

    //total pengurang shift
    //pengurang shift 1
    $("#kurset\\[1\\]\\[27\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });


    $("#kurset\\[1\\]\\[28\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });


    $("#kurset\\[1\\]\\[29\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });


    $("#kurset\\[1\\]\\[30\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });


    $("#kurset\\[1\\]\\[31\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });


    $("#kurset\\[1\\]\\[32\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });

    $("#kurset\\[1\\]\\[33\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });

    $("#kurset\\[1\\]\\[34\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });

    $("#kurset\\[1\\]\\[35\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });

    $("#kurset\\[1\\]\\[36\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift1(1);
        }
    });

    $("#total-pengurang-amt-shift1").numberbox({
        onChange: function() {
            set_gtotal_column_pengurang_shift();
        }
    });

    //pengurang shift 2
    $("#kurset\\[2\\]\\[27\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });


    $("#kurset\\[2\\]\\[28\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });


    $("#kurset\\[2\\]\\[29\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });


    $("#kurset\\[2\\]\\[30\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });


    $("#kurset\\[2\\]\\[31\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });


    $("#kurset\\[2\\]\\[32\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });

    $("#kurset\\[2\\]\\[33\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });

    $("#kurset\\[2\\]\\[34\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });

    $("#kurset\\[2\\]\\[35\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });

    $("#kurset\\[2\\]\\[36\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift2(2);
        }
    });

    $("#total-pengurang-amt-shift2").numberbox({
        onChange: function() {
            set_gtotal_column_pengurang_shift();
        }
    });

    //pengurang shift 3
    $("#kurset\\[3\\]\\[27\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });


    $("#kurset\\[3\\]\\[28\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });


    $("#kurset\\[3\\]\\[29\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });


    $("#kurset\\[3\\]\\[30\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });


    $("#kurset\\[3\\]\\[31\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });


    $("#kurset\\[3\\]\\[32\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });

    $("#kurset\\[3\\]\\[33\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });

    $("#kurset\\[3\\]\\[34\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });

    $("#kurset\\[3\\]\\[35\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });

    $("#kurset\\[3\\]\\[36\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang_shift3(3);
        }
    });

    $("#total-pengurang-amt-shift3").numberbox({
        onChange: function() {
            set_gtotal_column_pengurang_shift();
        }
    });




    $("#kurset\\[27\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[27\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[28\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[28\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[29\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[29\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[30\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[30\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[31\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[31\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[32\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[32\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[33\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[33\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[34\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[34\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#kurset\\[35\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[35\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });
    $("#kurset\\[36\\]").numberbox({
        disabled:true
    });

    $("#kurset\\[36\\]").numberbox({
        onChange: function() {
            set_total_column_pengurang();
        }
    });

    $("#kurset\\[36\\]").numberbox('textbox').bind('keydown', 'ctrl+s',function (evt){
        $("#input-pengurang").window('close');
        save_data_pengurang();
        $("#totalVoucher").numberbox('textbox').focus();
        return false;
    });

    $("#input-kurset").click(function(event) {
        event.preventDefault();
        $("#ttk_num").textbox('setValue', '');
        $("#modal_input_kurset").window('open');
    });

    //start focus tambah
    $("#peng\\[1\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#peng\\[2\\]").numberbox('textbox').focus();
        }
    });

    $("#peng\\[2\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#peng\\[3\\]").numberbox('textbox').focus();
        }
    });

    $("#peng\\[3\\]").textbox('textbox').keyup(function(e){
        event.preventDefault();
        if(e.which == 13 || e.keyCode == 13){
            $("#total-pengganti-amt").numberbox('textbox').focus();
        }
    });
    //end focus pengganti

    //start focus tambah shift
    $("#penam\\[1\\]\\[9\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[1\\]\\[10\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[1\\]\\[10\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[1\\]\\[11\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[1\\]\\[11\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[1\\]\\[12\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[1\\]\\[12\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[1\\]\\[13\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[1\\]\\[13\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[2\\]\\[9\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[2\\]\\[9\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[2\\]\\[10\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[2\\]\\[10\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[2\\]\\[11\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[2\\]\\[11\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[2\\]\\[12\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[2\\]\\[12\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[2\\]\\[13\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[2\\]\\[13\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[3\\]\\[9\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[3\\]\\[9\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[3\\]\\[10\\]").numberbox('textbox').focus();
        }
    });


    $("#penam\\[3\\]\\[10\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[3\\]\\[11\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[3\\]\\[11\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[3\\]\\[12\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[3\\]\\[12\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#penam\\[3\\]\\[13\\]").numberbox('textbox').focus();
        }
    });

    $("#penam\\[3\\]\\[13\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#gtotal-penambah-amt-shift").numberbox('textbox').focus();
        }
    });

    // end focus tambah shift

    //start focus kurang shift
    /*$("#kurset\\[1\\]\\[27\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[28\\]").numberbox('textbox').focus();
        }
    });*/

    $("#kurset\\[1\\]\\[27\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[28\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[28\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[29\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[29\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[30\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[30\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[31\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[31\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[32\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[32\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[33\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[33\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[34\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[34\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[35\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[35\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[1\\]\\[36\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[1\\]\\[36\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[27\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[27\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[28\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[28\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[29\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[29\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[30\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[30\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[31\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[31\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[32\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[32\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[33\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[33\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[34\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[2\\]\\[34\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[35\\]").numberbox('textbox').focus();
        }
    });


    $("#kurset\\[2\\]\\[35\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[2\\]\\[36\\]").numberbox('textbox').focus();
        }
    });
   
    $("#kurset\\[2\\]\\[36\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[27\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[27\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[28\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[28\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[29\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[29\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[30\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[30\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[31\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[31\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[32\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[32\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[33\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[33\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[34\\]").numberbox('textbox').focus();
        }
    });


    $("#kurset\\[3\\]\\[34\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[35\\]").numberbox('textbox').focus();
        }
    });
    $("#kurset\\[3\\]\\[35\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#kurset\\[3\\]\\[36\\]").numberbox('textbox').focus();
        }
    });

    $("#kurset\\[3\\]\\[36\\]").textbox('textbox').keyup(function(e){
        if(e.which == 13){
             $("#gtotal-pengurang-amt-shift").numberbox('textbox').focus();
        }
    });
    //end focus kurang shift

    totalGTUInput();

    $('#sub_shift_batch').click(function(event) {
        event.preventDefault();
        var user_id = $('#user_id_batch').val();
        var no_ref = $('#ref_num_batch').textbox('getValue');
        var no_shift = $('#col_shift_batch').combobox('getValue');
        if (user_id != '' && no_ref != '' && no_shift != '') {
            if ($('#dc_shift_batch').length) {
                var dc_shift = $('#dc_shift_batch').combobox('getValue');
            }else {
                var dc_shift = 'N';
            }
            $.ajax({
                type: 'POST',
                url: base_url+'Login/set_shift/',
                data: {
                    'user_id': user_id,
                    'no_ref': no_ref,
                    'no_shift': no_shift,
                    'dc_shift': dc_shift
                },
                success:function (msg) {
                    $('#form_shift_batch').window('close');
                    window.location.replace(base_url+'InputBatch/');
                }
            });
        } else {
            $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
        }
    });

    $('#data_trx_kurset').datagrid({
        url: base_url + 'InputBatch/get_data_kurset_shift',
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
            var rows = $(this).datagrid('getSelected');
        },
        columns:[[
            {field:'CDC_REC_ID',hidden:true},
            {field:'STORE_CODE',title:'Store Code',width:100,align:"center",halign:"center"},
            {field:'STORE_NAME',title:'Store Name',width:120,align:"center",halign:"center"},
            {field:'SALES_DATE',title:'Trx Date',width:100,align:"center",halign:"center",
                formatter:function (value,row,index) {
                    var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
                    options = {
                          year: 'numeric', month: 'long', day: 'numeric'
                        };
                    return Intl.DateTimeFormat('id-ID', options).format(date);
                }
            },
            {field:'ACTUAL_SALES_AMOUNT',title:'Actual Total Amount',width:100,align:"right",halign:"center",
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }
            },
            {field: 'BUTTON_DELETE', title: 'Action' ,width:80 ,align:'center', formatter: function (value, row, index) {
//              var col = '<input type="button" id="btnDelRecKur" value="Delete" onClick="delEntry('+row.CDC_REC_ID+')">';
                var col = '<input type="button" id="btnDelRecKur" value="Delete" onClick="delEntryShift('+row.CDC_SHIFT_REC_ID+',\''+row.CDC_REC_ID+'\',\''+row.NO_SHIFT+'\')">';
                
                return col;
            }}
        ]]
    });

    $("#submit_kurset").click(function(event) {
        event.preventDefault();
        var ttknum = $("#ttk_num").textbox('getValue');
        var amount = $("#ttk_total_amount").numberbox('getValue');
        var trf = $("#kurn-trf").val();
        var acc_id = $("#kurn-acc-id").val() != '' ? $("#kurn-acc-id").val() : 'N';
        var mut_date = $("#kurn-mutation-date").val() != '' ? $("#kurn-mutation-date").val() : 'N';
        if (ttknum && amount > 0) {
            $.ajax({
                url: 'inputBatch/set_receipt_kurset/'+ttknum+'/'+amount+'/'+trf+'/'+acc_id+'/'+mut_date,
                method: 'POST',
                success: function(msg) {
                    if (msg > 0) {
                        $("#modal_detail_kurset").window('close');
                        $("#data_trx_kurset").datagrid('reload');
                    }else $.messager.alert('Alert','Receipt kurset gagal atau sudah pernah dibentuk.','info');
                }
            });
        }
        else
        {
            $.messager.alert('Alert','Amount harus di isi !','info');
        }
    });

    $("#search_ttk").click(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'inputBatch/get_header_kurset/'+$("#ttk_num").textbox('getValue')+'/'+$("#branch_ttk").val(),
            method: 'POST',
            success: function(msg) {
                if (msg != 'X') {
                    var ret = jQuery.parseJSON(msg);
                    if (ret[0]) {
                        $("#ttk_num_det").textbox('setValue',ret[0]['FIS_TRX_NUMBER']);
                        $("#ttk_date_det").textbox('setValue',ret[0]['CREATED_DATE']);
                        $("#ttk_total_amount").textbox('setValue', '');
                        $('#data_det_kurset').datagrid({
                            url: 'inputBatch/get_lines_kurset/'+ret[0]['FIS_TRX_HEADER_ID']+'/'+$("#ttk_num").textbox('getValue'),
                            striped: true,
                            rownumbers:true,
                            remoteSort:false,
                            singleSelect:true,
                            pagination:true,
                            fit:false,
                            autoRowHeight:false,
                            fitColumns:true,
                            toolbar :'#toolbar',
                            onLoadSuccess: function() {
                                $.ajax({
                                    url: 'inputBatch/get_total_line/'+$("#ttk_num").textbox('getValue'),
                                    method: 'POST',
                                    success: function(msg) {
                                        $("#ttk_total_line").numberbox('setValue',msg);
                                    }
                                });
                            },
                            /*onDblClickRow: function() {
                                var rows = $(this).datagrid('getSelected');
                                $("#act_line_id").val(rows.CDC_KURSET_LINE_ID);
                                $("#act_max").val(rows.TRX_AR_AMOUNT);
                                $("#act_amount").numberbox('setValue',rows.ACTUAL_AMOUNT);
                                $("#modal_edit_amount").window('open');
                            },*/
                            columns:[[
                                {field:'CDC_KURSET_LINE_ID',hidden:true},
                                {field:'TRX_AR_NUMBER',hidden:true},
                                {field:'STORE_CODE',title:'Store Code',width:100,align:"center",halign:"center"},
                                {field:'TRX_AR_DATE',title:'Trx Date',width:100,align:"center",halign:"center",
                                    formatter:function (value,row,index) {
                                        var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
                                        options = {
                                              year: 'numeric', month: 'long', day: 'numeric'
                                            };
                                        return Intl.DateTimeFormat('id-ID', options).format(date);
                                    }
                                },
                                {field:'TRX_AR_TYPE',title:'Type',width:100,align:"center",halign:"center"},
                                {field:'TRX_AR_DESC',title:'Description',width:150,align:"center",halign:"center"},
                                {field:'TRX_AR_AMOUNT',title:'Amount',width:100,align:"right",halign:"center",
                                    formatter:function (value,row,index) {
                                        return Intl.NumberFormat('en-US').format(value);
                                    }
                                },
                                {field:'ACTUAL_AMOUNT',title:'Actual Amount',width:100,align:"right",halign:"center",
                                    formatter:function (value,row,index) {
                                        return Intl.NumberFormat('en-US').format(value);
                                    }
                                }
                            ]]
                        });
                        $("#modal_detail_kurset").window('open');
                    }else $.messager.alert('Alert','Data kurset tidak ditemukan.','info');
                }else $.messager.alert('Alert','Data kurset sudah pernah diproses.','info');                
            }
        });
    });

    $('#tglSales').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });
    $('#tglSales').datebox({
      onChange:  function(newValue,oldValue) {
         if (newValue) {
            $("#flagshift").combobox('enable');
            $('#flagshift').combobox({
                                                        url:'inputBatch/get_tipe_shift/'+$('#storeCode').textbox('getValue')+'/'+$('#tglSales').datebox('getValue')+'/'+salesFlag,
                                                        valueField:'SHIFT',
                                                        textField:'SHIFT_DESC'
                                                    });
         }
      }
   });

    if ($('#savBatch:visible').length) {
        //totalSetorReject();
        totalSetorRejectShift();
    }else{
        totalSetor();
    }
    $("#totalSetor").textbox('readonly');
    //$("#totalSetor").textbox('setValue','0');
    $("#tglSales").datebox('clear');
    $("#tglSales").datebox('disable');
    $("#cashPenggantian").numberbox('disable').numberbox('setValue',0);
    $("#totalPenambah").numberbox('disable').numberbox('setValue',0);
    $("#totalPengurang").numberbox('disable').numberbox('setValue',0);
    $("#totalVoucher").numberbox('disable').numberbox('setValue',0);
    
    //$("#scanCode").textbox('clear').textbox('textbox').focus();

    $('#stl-mut-date').datebox({
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

    $('#stl-stn-flag').change(function(){
        if($(this).is(':checked')){
            $("#stl-bank").combobox('setValue', '');
            $("#stl-bank-acc").combobox('setValue', '');
            $("#stl-mut-date").datebox('setValue', '');
            $('#form-bank-stl').window('open');
            $(this).val('1');
        } else {
            $(this).val('0');
            $("#stl-acc-id").val('');
            $("#stl-mutation-date").val('');
        }
    });

    $('#stl-bank').combobox({
        onChange: function (value) {
            $('#stl-bank-acc').combobox({
                url: base_url+'InputDeposit/get_bank_account_stn/'+value,
                valueField:'BANK_ACCOUNT_ID',
                textField:'BANK_ACCOUNT_NUM'
            });
        }
    });

    $('#can-stl-bank').click(function (event) {
        event.preventDefault();
        $('#form-bank-stl').window('close');
        $('#stl-stn-flag').prop('checked', false);
        $("#stl-bank").combobox('select', '');
        $("#stl-bank-acc").combobox('select', '');
        $('#stl-mut-date').datebox('setValue', '');
        $('#stl-stn-flag').val('0');
        $("#stl-acc-id").val('');
        $("#stl-mutation-date").val('');
    });

    $("#sub-stl-bank").click(function(event) {
        event.preventDefault();
        if ($('#stl-mut-date').datebox('getValue') != '' && $('#stl-bank-acc').combobox('getValue') != '') {
            $('#stl-mutation-date').val($('#stl-mut-date').datebox('getValue'));
            $('#stl-acc-id').val($('#stl-bank-acc').combobox('getValue'));
            $('#form-bank-stl').window('close');
        }else{
            $.messager.alert('Warning','Kolom harus diisi dengan lengkap.');
        }
    });

    $('#kurn-mut-date').datebox({
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

    $('#kurn-trf').change(function(){
        if($(this).is(':checked')){
            $("#kurn-bank").combobox('setValue', '');
            $("#kurn-bank-acc").combobox('setValue', '');
            $("#kurn-mut-date").datebox('setValue', '');
            $('#form-bank-kurn').window('open');
            $(this).val('1');
        } else {
            $(this).val('0');
            $("#kurn-acc-id").val('');
            $("#kurn-mutation-date").val('');
        }
    });

    $('#kurn-bank').combobox({
        onChange: function (value) {
            $('#kurn-bank-acc').combobox({
                url: base_url+'InputDeposit/get_bank_account_stn/'+value,
                valueField:'BANK_ACCOUNT_ID',
                textField:'BANK_ACCOUNT_NUM'
            });
        }
    });

    $('#can-kurn-bank').click(function (event) {
        event.preventDefault();
        $('#form-bank-kurn').window('close');
        $('#kurn-trf').prop('checked', false);
        $("#kurn-bank").combobox('select', '');
        $("#kurn-bank-acc").combobox('select', '');
        $('#kurn-mut-date').datebox('setValue', '');
        $('#kurn-trf').val('0');
        $("#kurn-acc-id").val('');
        $("#kurn-mutation-date").val('');
    });

    $("#sub-kurn-bank").click(function(event) {
        event.preventDefault();
        if ($('#kurn-mut-date').datebox('getValue') != '' && $('#kurn-bank-acc').combobox('getValue') != '') {
            $('#kurn-mutation-date').val($('#kurn-mut-date').datebox('getValue'));
            $('#kurn-acc-id').val($('#kurn-bank-acc').combobox('getValue'));
            $('#form-bank-kurn').window('close');
        }else{
            $.messager.alert('Warning','Kolom harus diisi dengan lengkap.');
        }
    });

    $('#in_mutation_date').datebox({
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

    $('#stnFlag').change(function(){
        if($(this).is(':checked')){
           // $('#flagshift').prop('checked', false);
            $('#flagshift').attr("disabled", true);
            $('#form_mutation_date').window('open');
            $('#gtuBatch').linkbutton('disable');
            $(this).val('1');
            stnFlag = 'Y';
            var num_shift = $("#CDC_REC_ID_SHIFT").val();

 
            if(num_shift=="")
            {
                $("#kurset\\[36\\]").numberbox('setValue',0);
                $("#kurset\\[36\\]").numberbox({
                    disabled:false
                });
                set_total_column_pengurang();
            }else{
                $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox('setValue',0);
                $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox({
                    disabled:false
                });
                set_total_column_pengurang(num_shift);

            }

            
        } else {
          //  $('#flagshift').prop('checked', true);
            $('#flagshift').attr("disabled", false);
            $('#gtuBatch').linkbutton('enable');
            $(this).val('0');
            stnFlag = 'N';
            $('#tglMutasi').val("");
            var num_shift = $("#CDC_REC_ID_SHIFT").val();

 
            if(num_shift=="")
            {
                $("#kurset\\[36\\]").numberbox('setValue',0);
                $("#kurset\\[36\\]").numberbox({
                    disabled:true
                });
                set_total_column_pengurang();

            }else{
                $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox('setValue',0);
                $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox({
                    disabled:true
                });

                $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox('setValue',0);
                set_total_column_pengurang(num_shift);

            }
        }
    });


    $('#in_bank').combobox({
        onChange: function (value) {
            $('#in_bank_account').combobox({
                url: base_url+'InputDeposit/get_bank_account_stn/'+value,
                valueField:'BANK_ACCOUNT_ID',
                textField:'BANK_ACCOUNT_NUM'
            });
        }
    });

    $('#can_mutation_date').click(function (event) {
        event.preventDefault();
        $('#form_mutation_date').window('close');
        $('#stnFlag').prop('checked', false);
        $("#in_bank").combobox('select', '');
        $("#in_bank_account").combobox('select', '');
        $('#gtuBatch').linkbutton('enable');
        $(this).val('0');
        stnFlag = 'N';
        $('#tglMutasi').val("");
        $('#bankAcc').val('');
    });

    $('#sub_mutation_date').click(function (event) {
        event.preventDefault();
        if ($('#in_mutation_date').datebox('getValue') != '' && $('#in_bank_account').combobox('getValue') != '') {
            $('#tglMutasi').val($('#in_mutation_date').datebox('getValue'));
            $('#bankAcc').val($('#in_bank_account').combobox('getValue'));
            $('#form_mutation_date').window('close');
        }else{
            $.messager.alert('Warning','Kolom harus diisi dengan lengkap.');
            $('#form_mutation_date').window('close');
            $('#stnFlag').prop('checked', false);
            $('#gtuBatch').linkbutton('enable');
            $(this).val('0');
            stnFlag = 'N';
            $('#tglMutasi').val("");
            $('#bankAcc').val('');
        }
    });

    if ($('#data_batch_reject').length) {
        $('#batch_reject').datagrid({
            url:base_url+'InquiryBatch/getBatchReject',
            onDblClickRow: function () {
                var rows = $(this).datagrid('getSelections');
                $('#data_batch_reject').window('close');
                $('#savBatch').show();
                $('#genBatch').hide();
                $('#scanCode').textbox('disable');
                $('#savBatch').attr('batchid',rows[0].CDC_BATCH_ID);
                $('#gtuBatch').attr('batchid',rows[0].CDC_BATCH_ID);
                $('#tblTrxReceipts').datagrid({
                    url:base_url+'InputBatch/getPraInputRejectShift/'+rows[0].CDC_BATCH_ID,
                    columns:[[
                    {field:'ck',checkbox:false},
                    {field:'CDC_REC_ID', hidden:true},
                    {field:'CDC_SHIFT_REC_ID', hidden:true},
                    {field:'ACTUAL_SALES_FLAG', hidden:true},
                    {field:'STN_FLAG', hidden:true},
                    {field:'STORE_CODE',title:'Store Code',width:70,align:'center'},
                    {field:'STORE_NAME',title:'Store Name',width:100,align:'center'},
                    {field:'SALES_DATE',title:'Tgl Sales',width:100,align:'center'},
                {field:'ACTUAL_SALES_AMOUNT',   title:'Cash + Penggantian',width:130,align:'right',
                    formatter:function (value,row,index) {
                        return Intl.NumberFormat('en-US').format(value);
                    }
                },
                {field:'TOTAL_PENAMBAHAN',      title:'Total Penambahan',width:130,align:'right',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
                },
                {field:'ACTUAL_AMOUNT',         title:'Total Actual Amount',width:150,align:'right',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
                },
                {field:'TOTAL_PENGURANGAN',     title:'Total Pengurangan',width:130,align:'right',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
                },
                {field:'TOTAL_VOUCHER',         title:'Total Voucher',width:100,align:'right',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
                },
                {field:'NO_SHIFT',          title:'Shift',width:70,align:'center'},
                {field: 'BUTTON_EDIT', title: '' ,width:40 ,align:'center', formatter: function (value, row, index) {
                    var col;
                    col = ' <input type="button" id="btnEditEntry" value="Edit" onClick="editEntryShift('+row.CDC_SHIFT_REC_ID+',\''+row.STN_FLAG+'\')"> ';
                    return col;
                }},

                {field: 'BUTTON_DELETE', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
                    var col;
                    col = ' <input type="button" id="btnDelEntry" value="Delete" onClick="delEntryShift('+row.CDC_SHIFT_REC_ID+',\''+row.CDC_REC_ID+'\',\''+row.NO_SHIFT+'\')"> ';
                    return col;
                }}
                ]],
                rownumbers : true, singleSelect:false, fitColumns:true
                });
                totalSetorRejectShift();
            },
            columns:[[
                {field:'CDC_BATCH_ID',          hidden:true},
                {field:'CDC_BATCH_NUMBER',      title:'Batch Number',width:100,align:'center'},
                {field:'CDC_BATCH_TYPE',        title:'Batch Number',width:100,align:'center'},
                {field:'CDC_BATCH_DATE',        title:'Tgl Batch',width:100,align:'center',
                    formatter:function (value,row,index) {
                        var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
                        options = {
                              year: 'numeric', month: 'long', day: 'numeric'
                            };
                        return Intl.DateTimeFormat('id-ID', options).format(date);
                    }           
                },
                {field:'CDC_BATCH_STATUS',      title:'Status',width:80,align:'center',
                    formatter:function (value,row,index) {
                        if (value == 'N') {return "NEW";}
                            else if (value == 'V') {return "VALIDATE";}
                                else if (value == 'R') {return "REJECT";}
                    }
                },
                {field:'BRANCH_CODE',       title:'Branch Code',width:80,align:'center'},
                {field:'BRANCH_NAME',       title:'Branch Name',width:130,align:'center'},
                {field:'CREATED_BY',            title:'Create By',width:80,align:'center'},
                {field:'TOTAL_SETOR',       title:'Total Setor',width:100,align:'right',
                    formatter:function (value,row,index) {
                        return Intl.NumberFormat('en-US').format(value);
                    }
                },
                {field:'LAST_UPDATE_DATE',      title:'Last Update Date',width:100,align:'center',
                    formatter:function (value,row,index) {
                        var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
                        options = {
                              year: 'numeric', month: 'long', day: 'numeric'
                            };
                        return Intl.DateTimeFormat('id-ID', options).format(date);
                    }               
                },

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
            rownumbers : true, singleSelect:true, fitColumns:true
        });
    }

    $('#btnReset').click(function(event) {
        event.preventDefault();
        $('#CDC_REC_ID1').val(null);
        $('#CDC_REC_ID2').val(null);
        $('#CDC_REC_ID3').val(null);
        $('#CDC_REC_REAL_ID').val(null);
        $('#CDC_REC_ID_SHIFT').val(null);
        recStatus = 'n';
        salesDate = null;
        storeID   = null;
        $("#storeCode").textbox('clear');
        $("#storeName").textbox('clear');
        $("#tglSales").datebox('clear');
        $("#tglSales").datebox('disable');
        $("#flagshift").combobox('clear');
        $("#flagshift").combobox('disable');
        $("#cashPenggantian").numberbox('disable').textbox('setValue','0');
        $("#totalPenambah").numberbox('disable').textbox('setValue','0');
        $("#totalPengurang").numberbox('disable').textbox('setValue','0');
        $("#totalVoucher").numberbox('disable').textbox('setValue','0');
        $("#scanCode").textbox('clear').textbox('textbox').focus();
    });
    
    //Entry batch datagrid  
    $('#tblTrxReceipts').datagrid({
        url:base_url+'InputBatch/getPraInputShift',
        onCheckAll: function () {
            $("#genBatch").trigger('click');
        },
        columns:[[
            {field:'ck',checkbox:true},
            {field:'CDC_REC_ID', hidden:true},
            {field:'CDC_SHIFT_REC_ID', hidden:true},
            {field:'ACTUAL_SALES_FLAG', hidden:true},
            {field:'STN_FLAG', hidden:true},
            {field:'STORE_CODE',            title:'Store Code',width:70,align:'center'},
            {field:'STORE_NAME',            title:'Store Name',width:140,align:'center'},
            {field:'SALES_DATE',            title:'Tgl Sales',width:100,align:'center'},
            {field:'BANK_NAME',             title:'Bank',width:100,align:'center'},
            {field:'ACTUAL_SALES_AMOUNT',   title:'Cash + Penggantian',width:120,align:'right',
            formatter:function (value,row,index) {
                return Intl.NumberFormat('en-US').format(value);
            }
            },
            {field:'TOTAL_PENAMBAHAN',      title:'Total Penambahan',width:120,align:'right',
            formatter:function (value,row,index) {
                return Intl.NumberFormat('en-US').format(value);
            }           
            },
            {field:'ACTUAL_AMOUNT',         title:'Total Actual Amount',width:140,align:'right',
            formatter:function (value,row,index) {
                return Intl.NumberFormat('en-US').format(value);
            }           
            },
            {field:'TOTAL_PENGURANGAN',     title:'Total Pengurangan',width:120,align:'right',
            formatter:function (value,row,index) {
                return Intl.NumberFormat('en-US').format(value);
            }           
            },
            {field:'TOTAL_VOUCHER',         title:'Total Voucher',width:100,align:'right',
            formatter:function (value,row,index) {
                return Intl.NumberFormat('en-US').format(value);
            }           
            },
            {field:'NO_SHIFT',          title:'Shift',width:70,align:'center'},

            {field: 'BUTTON_EDIT', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnEditEntry" value="Edit" onClick="editEntryShift('+row.CDC_SHIFT_REC_ID+',\''+row.STN_FLAG+'\')"> ';
                return col;
            }},

            {field: 'BUTTON_DELETE', title: '' ,width:60 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnDelEntry" value="Delete" onClick="delEntryShift('+row.CDC_SHIFT_REC_ID+',\''+row.CDC_REC_ID+'\',\''+row.NO_SHIFT+'\')"> ';
                return col;
            }}
        ]],
        rownumbers : true, singleSelect:false, fitColumns:true
    });     

    //CEK CEKBOX        
    $('#sales_flag').change(function () {
        var box = document.getElementById("sales_flag");
        if( box.checked == 1 ){
            $("#cashPenggantian").numberbox('enable').textbox('setValue','0');
            salesFlag = "Y";

             $("#flagshift").combobox('enable');
             $('#flagshift').combobox({
                                                        url:'inputBatch/get_tipe_shift/'+$('#storeCode').textbox('getValue')+'/'+$('#tglSales').datebox('getValue')+'/'+salesFlag,
                                                        valueField:'SHIFT',
                                                        textField:'SHIFT_DESC'
                                                    });
            //alert(salesFlag);
        }
        else{
            salesFlag = "N" ;
            $('#cashPenggantian').textbox('setValue','0');
            $("#cashPenggantian").numberbox('disable');
             $("#flagshift").combobox('enable');
             $('#flagshift').combobox({
                                                        url:'inputBatch/get_tipe_shift/'+$('#storeCode').textbox('getValue')+'/'+$('#tglSales').datebox('getValue')+'/'+salesFlag,
                                                        valueField:'SHIFT',
                                                        textField:'SHIFT_DESC'
                                                    });
            //alert(salesFlag);
        }   
    });

    // $("#start_input_time").textbox('textbox').keyup(function(e){
    //     if(e.which == 13){
    //     // console.log($(this).val() + " aaaaaa");
    //      console.log(e);
    //     console.log(" masuk");
    //     }
    //     else{
    //         console.log(" gamasuk");
    //     }
    // });

    $("#scanCode").textbox('textbox').keyup(function(e){
        if(e.which == 13){
            var today = new Date();
            today.setDate(today.getDate());

            var MyDateString = today.getFullYear() + '-' + ('0' + (today.getMonth()+1)).slice(-2) + '-' +  ('0' + today.getDate()).slice(-2);
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var dateTime = MyDateString.concat(" ",time);
            $('#start_input_time').textbox('setValue',dateTime);
            var value = $(this).val();
            var tgl_sales;
            var cek;
            if(value.length < 6){
                storeID = null;
                salesDate = null;
                $("#cashPenggantian").numberbox('disable');
                $("#totalPenambah").numberbox('disable');
                $("#totalPengurang").numberbox('disable');
                $("#totalVoucher").numberbox('disable');
                $("#flagshift").combobox('disable');
            }
            else if (value.length > 6) {
                storeID = '';


                if (value.substring(0, 1) == '1') {
                    storeID += 'T';
                }
                else if (value.substring(0, 1) == '2') {
                    storeID += 'F';
                }
                else if (value.substring(0, 1) == '3') {
                    storeID += 'R';
                }
                storeID     += String.fromCharCode(parseInt(value.substring(1, 3)))+String.fromCharCode(parseInt(value.substring(3, 5)))+String.fromCharCode(parseInt(value.substring(5, 7)));
               
                        $.ajax({
                            method: "POST",
                            url: base_url+"inputBatch/scanCodeSales",
                            data: { 
                                scan : value
                            },
                            success: function (msg) {
                                /*var curdate = new Date();
                                var getdate = curdate.getDate();*/

                                var getdate = 0;

                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/getSysDate",
                                    success: function (sysdate) {
                                        getdate = sysdate;

                                        //alert(getdate);
                                        //alert(storeID);

                                        if (parseInt(value.substring(7, 9)) > parseInt(getdate.substring(0,2))) {
                                            salesDate = ((value.substring(7, 9))<10?('0'+(parseInt(value.substring(7, 9)))):(value.substring(7, 9)))+'-'+(((parseInt(getdate.substring(2,4))-1)<10?('0'+(parseInt(getdate.substring(2,4))-1)):(parseInt(getdate.substring(2,4))-1)))+'-'+parseInt(getdate.substring(4));
                                        }else{
                                            salesDate = ((value.substring(7, 9))<10?('0'+(parseInt(value.substring(7, 9)))):(value.substring(7, 9)))+'-'+((parseInt(getdate.substring(2,4)))<10?('0'+(parseInt(getdate.substring(2,4)))):(parseInt(getdate.substring(2,4))))+'-'+parseInt(getdate.substring(4));
                                        }

                                        $('#storeCode').textbox('setValue', msg );  
                                      
                                        $('#tglSales').datebox('setValue', salesDate);
                                        $('#tglSales').datebox('enable');
                                        $("#flagshift").combobox('enable');

                                        $.ajax({
                                            method: "POST",
                                            url: base_url+"master/Toko/getStore",
                                            data: { 
                                                storeCode       : msg
                                            },
                                            success: function (storeNameHasil) {
                                                if (storeNameHasil == 'FALSE') {
                                                    alert('Kode Toko Tidak Terdaftar.');
                                                    $('#storeCode').textbox('setValue', '');  
                                                    $('#tglSales').datebox('setValue', '');
                                                }else{
                                                    $('#storeName').textbox('setValue', storeNameHasil);
                                                    $('#scanCode').textbox('setValue', msg+value.substring(7, 9));
                                                    $("#flagshift").combobox('enable');

                                                    $('#flagshift').combobox({
                                                        url:'inputBatch/get_tipe_shift/'+msg+'/'+$('#tglSales').datebox('getValue')+'/'+salesFlag,
                                                        valueField:'SHIFT',
                                                        textField:'SHIFT_DESC'
                                                    });
                                                   // alert(msg);
                                                   
                                                }
                                            }
                                        });
                                    }
                                });

                                /*alert(getdate);*/

                                    
                    //GENERATE PRAINPUT SEQ ID/////////////////////////////////////////////
                            
                                /*$.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID1').val($id);
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID2').val($id);
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID3').val($id);
                                        }
                                    }); 
                        

                            

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraID",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_REAL_ID').val($id);
                                        }
                                    }); */  


                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID1').val($id);
                                            //alert($('#CDC_REC_ID1').val());
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID2').val($id);
                                            //alert($('#CDC_REC_ID2').val());
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID3').val($id);
                                            //alert($('#CDC_REC_ID3').val());   
                                        }
                                    }); 
                        

                            

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDN",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id);
                                                    $('#CDC_REC_REAL_ID').val($id);
                                                    //alert($('#CDC_REC_REAL_ID').val());   
                                        }
                                    });

                                                

                                if($('#CDC_REC_REAL_ID').val() && $('#CDC_REC_REAL_ID').val() > 0){
                                    if(salesFlag == "Y"){
                                        $("#cashPenggantian").numberbox('enable').numberbox('setValue','');
                                        $("#totalPenambah").numberbox('enable');
                                        $("#totalPengurang").numberbox('enable');
                                        $("#totalVoucher").numberbox('enable');
                                        $("#flagshift").combobox('enable');
                                        $("#cashPenggantian").numberbox('textbox').focus();

                                    } else{
                                        $("#totalPenambah").numberbox('disable');
                                        $("#totalPengurang").numberbox('disable');
                                        $("#totalVoucher").numberbox('enable');
                                        $("#flagshift").combobox('enable');
                                        $("#totalPenambah").numberbox('textbox').focus();
                                    }
                                }
                                else{
                                    alert('Terjadi Kesalahan, Mohon untuk diinput ulang');
                                    location.reload();
                                }
                            }
                        });
                    /*}
                    else{
                        alert('Absensi Sales Belum Diinput');
                    }*/
            }
            else if (value.length == 6){
                storeID     = value.substring(0, 4);
                var today = new Date();
                today.setDate(today.getDate());

                var MyDateString = today.getFullYear() + '-' + ('0' + (today.getMonth()+1)).slice(-2) + '-' +  ('0' + today.getDate()).slice(-2);
                var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                var dateTime = MyDateString.concat(" ",time);
                $('#start_input_time').textbox('setValue',dateTime);
             

                var getdate = 0;

                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/getSysDate",
                    success: function (sysdate) {
                        getdate = sysdate;

                        if (parseInt(value.substring(4, 6)) > parseInt(getdate.substring(0,2))) {
                            salesDate = ((value.substring(4, 6))<10?('0'+(parseInt(value.substring(4, 6)))):(value.substring(4, 6)))+'-'+(((parseInt(getdate.substring(2,4))-1)<10?('0'+(parseInt(getdate.substring(2,4))-1)):(parseInt(getdate.substring(2,4))-1)))+'-'+parseInt(getdate.substring(4));
                        }else{
                            salesDate = ((value.substring(4, 6))<10?('0'+(parseInt(value.substring(4, 6)))):(value.substring(4, 6)))+'-'+((parseInt(getdate.substring(2,4)))<10?('0'+(parseInt(getdate.substring(2,4)))):(parseInt(getdate.substring(2,4))))+'-'+parseInt(getdate.substring(4));
                        }

                        $('#storeCode').textbox('setValue', storeID );  
                        $('#tglSales').datebox('setValue', salesDate);
                        $('#tglSales').datebox('enable');
                        $("#flagshift").combobox('enable');
                        //alert(sysdate);
                        $.ajax({
                            method: "POST",
                            url: base_url+"master/Toko/getStore",
                            data: { 
                                storeCode       : $('#storeCode').textbox('getValue')
                            },

                            success: function (storeNameHasil) {
                                if (storeNameHasil == 'FALSE') {
                                    alert('Kode Toko Tidak Terdaftar.');
                                    $('#storeCode').textbox('setValue', '');  
                                    $('#tglSales').datebox('setValue', '');
                                }else{
                                     $("#flagshift").combobox('enable');
                                     $('#flagshift').combobox({
                                                        url:'inputBatch/get_tipe_shift/'+$('#storeCode').textbox('getValue')+'/'+$('#tglSales').datebox('getValue')+'/'+salesFlag,
                                                        valueField:'SHIFT',
                                                        textField:'SHIFT_DESC'
                                                    });
                                    $('#storeName').textbox('setValue', storeNameHasil);
                                    $('#scanCode').textbox('setValue', $('#storeCode').textbox('getValue')+value.substring(4, 6));
                                }
                            }
                        });
                    }
                });

                /*alert(getdate);*/

                    
    //GENERATE PRAINPUT SEQ ID/////////////////////////////////////////////     
                /*$.ajax({
                            method: "POST",
                            async:false,
                            url: base_url+"InputBatch/getPraIDShift",
                            success: function ($id) {
                                    //$('#CDC_REC_ID').textbox('setValue', $id); 
                                    $('#CDC_REC_ID1').val($id);
                                }
                            }); 

                $.ajax({
                            method: "POST",
                            async:false,
                            url: base_url+"InputBatch/getPraIDShift",
                            success: function ($id) {
                                    //$('#CDC_REC_ID').textbox('setValue', $id); 
                                    $('#CDC_REC_ID2').val($id);
                                }
                            }); 

                $.ajax({
                            method: "POST",
                            async:false,
                            url: base_url+"InputBatch/getPraIDShift",
                            success: function ($id) {
                                    //$('#CDC_REC_ID').textbox('setValue', $id); 
                                    $('#CDC_REC_ID3').val($id);
                                }
                            }); 

                $.ajax({
                    method: "POST",
                    async:false,
                    url: base_url+"InputBatch/getPraID",
                    success: function ($id) {
                                    //$('#CDC_REC_ID').textbox('setValue', $id); 
                            $('#CDC_REC_REAL_ID').val($id);
                    }
                });     */

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID1').val($id);
                                            //alert($('#CDC_REC_ID1').val());
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID2').val($id);
                                            //alert($('#CDC_REC_ID2').val());
                                        }
                                    }); 

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDNShift",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id); 
                                            $('#CDC_REC_ID3').val($id);
                                            //alert($('#CDC_REC_ID3').val());   
                                        }
                                    }); 
                        

                            

                                $.ajax({
                                    method: "POST",
                                    async:false,
                                    url: base_url+"InputBatch/getPraIDN",
                                    success: function ($id) {
                                            //$('#CDC_REC_ID').textbox('setValue', $id);
                                                    $('#CDC_REC_REAL_ID').val($id);
                                                    //alert($('#CDC_REC_REAL_ID').val());   
                                        }
                                    });

                                    //alert($('#CDC_REC_REAL_ID').val());   

                if($('#CDC_REC_REAL_ID').val() && $('#CDC_REC_REAL_ID').val() > 0){                 
                    if(salesFlag == "Y"){
                        $("#cashPenggantian").numberbox('enable').numberbox('setValue','');
                        $("#totalPenambah").numberbox('enable');
                        $("#totalPengurang").numberbox('enable');
                        $("#totalVoucher").numberbox('enable');
                        $("#cashPenggantian").numberbox('textbox').focus();

                    } else{
                        $("#totalPenambah").numberbox('enable');
                        $("#totalPengurang").numberbox('enable');
                        $("#totalVoucher").numberbox('enable');
                        $("#totalPenambah").numberbox('textbox').focus();
                    }
                    
                }
                else{
                        alert('Terjadi Kesalahan, Mohon untuk diinput ulang');
                        location.reload();
                    }
            }
        }
    });

    //ISI STORE & TGL OTOMATIS DARI SALES SCAN
    
    $("#cashPenggantian").numberbox('textbox').keyup(function(e){
    //  alert($("#flagshift").combobox('getValue'));

        if($("#flagshift").combobox('getValue')=='H-1'){
            //alert("penambah");
              //  alert("penggantian");
                for(var i = 1; i <= 3;i++){
                    penggantian(i);
                }   
            
        }
    });
    
    $("#totalPenambah").numberbox('textbox').keyup(function(e){
    //    alert("penambah");
      //  if(e.which == 13){
            //alert("penambah");
            if($("#flagshift").combobox('getValue')=='H-1'){
                for(var i = 1; i <= 3; i++){
                    penambahshift(i);
                }
                
            }
            else{
                penambah();             
            }

        //}
    });
    
    $("#totalPengurang").numberbox('textbox').keyup(function(e){
       // if(e.which == 13){
            if($("#flagshift").combobox('getValue')=='H-1'){
                for(var i = 1; i <= 3; i++){
                    pengurangshift(i);
                }
                
            }
            else{
                pengurang();                
            }
        //}
    });

    
    $("#totalVoucher").numberbox('textbox').keyup(function(e){
       // if(e.which == 13){
            if($("#flagshift").combobox('getValue')=='H-1'){                
                vouchershift(); 
            }
            else{
                voucher();              
            }
                    
        //}
    });
    
    
///////////////////////// POP UP PENAMBAH /////////////////////////////////
    //COMBO BOX PENAMBAHAN
    $('#trxTypePenambah').combobox({
        url: base_url+'master/Penambah/getOption',
        //valueField:'TRX_PLUS_ID',
        valueField:'TRX_PLUS_NAME',
        textField:'TRX_PLUS_NAME'
    });

    //ISI DESC PENAMBAH
    $('#trxTypePenambah').combobox({
        onChange: function(value){
            var trxDate      = $('#trxDatePenambah').datebox('getValue');
            $('#descPenambah').textbox('setValue', value + ' - ' + trxDate );
        }
    });
////////////////////////////////////////////////////////////////////////////    



///////////////////////// POP UP PENGURANG /////////////////////////////////
    //COMBO BOX PENGURANG
    $('#trxTypePengurang').combobox({
        url: base_url+'master/Pengurang/getOption',
        valueField:'TRX_MINUS_NAME',
        textField:'TRX_MINUS_NAME'
    });

    //ISI DESC PENGURANG
    $('#trxTypePengurang').combobox({
        onChange: function(value){
            var trxDateMin   = $('#trxDatePengurang').datebox('getValue');
            $('#descPengurang').textbox('setValue', value + ' - ' + trxDateMin );  

        }
    });
////////////////////////////////////////////////////////////////////////////    



///////////////////////// POP UP VOUCHER /////////////////////////////////
    
    //ISI DESC VOUCHER
    /*$('#voucherNum').textbox({
        onChange: function(value){
            
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/cekVoucher",
                data: { 
                    voucherNum : $('#voucherNum').textbox('getValue')
                },

                success: function (hasilCek) {
                    var data_voucher = JSON.parse(hasilCek);
                    if(data_voucher.flag == 1){
                        //var data_voucher = JSON.parse(hasilCek);
                        if( data_voucher.data.USED_FLAG == 'N' ){
                            $('#voucherAmount').numberbox('setValue',data_voucher.data.VOUCHER_NOMINAL);                
                        }else{
                            $.messager.alert('Alert','Kode Voucher sudah pernah digunakan.','info');
                            $('#voucher_form').form('clear');
                            $('#voucherDate').datebox('setValue', 'current' );
                        }
                    }else{
                        $.messager.alert('Alert','Kode Voucher tidak ditemukan.','info');
                        $('#voucher_form').form('clear');
                        $('#voucherDate').datebox('setValue', 'current' ); 
                    }
                }
            }); 
            
            
            var trxDate      = $('#voucherDate').datebox('getValue');
            $('#voucherDesc').textbox('setValue', value + ' - ' + trxDate );  

        }
    });*/

    $("#voucherNum").textbox('textbox').keyup(function(e){
        if(e.which == 13){
            $("#prog-trans").window('open');
            var value = $(this).val();
                $.ajax({
                    method: "POST",
                    url: base_url+"input/Trx_Voucher/cekVoucher",
                    data: { 
                        voucherNum : value
                    },
                    dataType: 'json',
                    success: function (hasilCek) {
                        $("#prog-trans").window('close');
                        if(hasilCek[0]){
                            if( hasilCek[0].USED_FLAG == 'N' ){
                                $('#voucherAmount').numberbox('setValue',hasilCek[0].VOUCHER_NOMINAL);              
                            }else{
                                $.messager.alert('Alert','Kode Voucher sudah pernah digunakan.','info');
                                $('#voucher_form').form('clear');
                                $('#voucherDate').datebox('setValue', 'current' );
                            }
                        }else{
                            $.messager.alert('Alert','Kode Voucher tidak ditemukan.','info');
                            $('#voucher_form').form('clear');
                            $('#voucherDate').datebox('setValue', 'current' ); 
                        }
                    }
                });
            var trxDate = $('#voucherDate').datebox('getValue');
            $('#voucherDesc').textbox('setValue', value + ' - ' + trxDate );
        }
    });

    $("#voucherNumShift").textbox('textbox').keyup(function(e){
        if(e.which == 13){
            var value = $(this).val();
            if(value != ''){
                $("#prog-trans").window('open')
                $.ajax({
                    method: "POST",
                    url: base_url+"input/Trx_Voucher/cekVoucher",
                    data: { 
                        voucherNum : value
                    },
                    dataType: 'json',
                    success: function (hasilCek) {
                        $("#prog-trans").window('close');
                        if(hasilCek[0]){
                            if( hasilCek[0].USED_FLAG == 'N' ){
                                $('#voucherAmountShift').numberbox('setValue',hasilCek[0].VOUCHER_NOMINAL);             
                            }else{
                                $.messager.alert('Alert','Kode Voucher sudah pernah digunakan.','info');
                                $('#voucher_form_shift').form('clear');
                                $('#voucherDateShift').datebox('setValue', 'current' );
                            }
                        }else{
                            $.messager.alert('Alert','Kode Voucher tidak ditemukan.','info');
                            $('#voucher_form_shift').form('clear');
                            $('#voucherDateShift').datebox('setValue', 'current' ); 
                        }
                    }
                });
                var trxDate = $('#voucherDateShift').datebox('getValue');
                $('#voucherDescShift').textbox('setValue', value + ' - ' + trxDate );
            }
            else{
                alert('Voucher tidak boleh kosong');
            }
        }
    });

    /*$("#voucherNum").numberbox('textbox').keyup(function(e){
        if(e.which == 13){
            alert('Mengakses Data');
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/cekVoucher",
                data: { 
                    voucherNum : $("#voucherNum").textbox('getValue')
                },
                success: function (hasilCek) {
                    var data_voucher = JSON.parse(hasilCek);
                    if(data_voucher.flag == 1){ 
                        alert ('Mengakses Data');
                        var data_voucher = JSON.parse(hasilCek);    
                        if( data_voucher.data.USED_FLAG == 'N' ){
                            $('#voucherAmount').numberbox('setValue',data_voucher.data.VOUCHER_NOMINAL);                
                        }else{
                            $.messager.alert('Alert','Kode Voucher sudah pernah digunakan.','info');
                            $('#voucher_form').form('clear');
                            $('#voucherDate').datebox('setValue', 'current' );
                        }
                    }else{
                        $.messager.alert('Alert','Kode Voucher tidak ditemukan.','info');
                        $('#voucher_form').form('clear');
                        $('#voucherDate').datebox('setValue', 'current' ); 
                    }
                }
            });

            var trxDate = $('#voucherDate').datebox('getValue');
            var value = $("#voucherNum").textbox('getValue');
            $('#voucherDesc').textbox('setValue', value + ' - ' + trxDate );
        }
    });*/

////////////////////////////////////////////////////////////////////////////    

}); //END DOCUMENTS READY

/*
//ACTION SALES FLAG CHECKBOX
function flag(a){
    //salesFlag = 0;
    var box = document.getElementById("sales_flag");
    if( box.checked == 1 ){
        $("#cashPenggantian").numberbox('enable').textbox('setValue','0');
        salesFlag = "Y";
    }
    else{
        salesFlag = "N" ;
        $('#cashPenggantian').textbox('setValue','0');
        $("#cashPenggantian").numberbox('disable');
        
    }
}
*/

function editEntryShift(recId, isStn){
    recID = recId;
    isSTN = isStn;
    $("#cashPenggantian").numberbox('enable');
    $("#totalPenambah").numberbox('enable');
    $("#totalPengurang").numberbox('enable');
    $("#totalVoucher").numberbox('enable');
    $('#CDC_REC_ID1').val('');
    $('#CDC_REC_ID2').val('');
    $('#CDC_REC_ID3').val('');

    //$("#totalVoucher").numberbox('disable');
    
    //$("#scanCode").textbox('clear').textbox('textbox').focus();
    
    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getDataDetailShift/"+recID+'/'+isSTN,

        success: function (rows) {
            var data = JSON.parse(rows);
          //alert();
        //$('#scanCode').textbox('setValue', data['SCANCODE']);
        $("#scanCode").textbox('disable');
        $('#storeCode').textbox('setValue', data['STORE_CODE']);
        $('#storeName').textbox('setValue', data['STORE_NAME']);
        $('#tglSales').textbox('setValue', data['SALES_DATE']);

        $('#cashPenggantian').numberbox('setValue', data['ACTUAL_SALES_AMOUNT']);
        $('#totalPenambah').numberbox('setValue', data['TOTAL_PENAMBAHAN']);
        $('#totalPengurang').numberbox('setValue', data['TOTAL_PENGURANGAN']);
        $('#totalVoucher').numberbox('setValue', data['TOTAL_VOUCHER']);

        if(data['NO_SHIFT'] == '1'){
            $('#CDC_REC_ID1').val( data['CDC_SHIFT_REC_ID']);
        }
        else if(data['NO_SHIFT'] == '2'){
            $('#CDC_REC_ID2').val( data['CDC_SHIFT_REC_ID']);
        }
        else if(data['NO_SHIFT'] == '3'){
            $('#CDC_REC_ID3').val( data['CDC_SHIFT_REC_ID']);
        }
        else{
            $('#CDC_REC_ID1').val( data['CDC_SHIFT_REC_ID']);
        }
        //EMMA
         $('#flagshift').combobox({
                                                url: 'InputBatchDenom/get_tipe_shift/' + $('#storeCode').textbox('getValue') + '/' + $('#tglSales').datebox('getValue') + '/' + salesFlag,
                                                valueField: 'SHIFT',
                                                textField: 'SHIFT_DESC'
                                            });
                                          
        if(data['NO_SHIFT'] == 'H'){
            $('#flagshift').combobox('select', 'HARIAN');
        }
        else if(data['NO_SHIFT'] == 'S-1'){
            $('#flagshift').combobox('select', 'SHIFT-1');
        }
        else if(data['NO_SHIFT'] == 'S-2'){
                                                $('#flagshift').combobox('select', 'SHIFT-2');
        }
        else if(data['NO_SHIFT'] == 'S-3'){
                                                $('#flagshift').combobox('select', 'SHIFT-3');
        }

        

        $('#flagshift').attr("disabled", true);
        
        $('#CDC_REC_REAL_ID').val( data['CDC_REC_ID']);
        $('#CDC_REC_ID_SHIFT').val( data['NO_SHIFT']);

        if (data['ACTUAL_SALES_FLAG'] == 'Y') {
            $('#sales_flag').prop('checked', true);
        }else{
            $('#sales_flag').prop('checked', false);
        }
        if (data['STN_FLAG'] == 'Y') {
            $('#stnFlag').prop('checked', true);
            $('#gtuBatch').linkbutton('disable');
            $('#stnFlag').val('1');
            stnFlag = 'Y';
            $('#in_mutation_date').datebox('setValue',data['MUTATION_DATE']);
            $("#in_bank").combobox('select', data['BANK_ID']);
            $("#in_bank_account").combobox('select', data['BANK_ACCOUNT_ID']);
            $('#tglMutasi').val(data['MUTATION_DATE']);
            $('#bankAcc').val(data['BANK_ACCOUNT_ID']);
        }else{
            $('#stnFlag').prop('checked', false);
            $('#gtuBatch').linkbutton('enable');
            $('#stnFlag').val('0');
            stnFlag = 'N';
            $('#in_mutation_date').datebox('setValue',"");
            $('#tglMutasi').val("");
            $('#bankAcc').val('');
        }
        
        recStatus = 'e';
    }
});         
}

function delEntryShift(recshiftId,recid,no_shift){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){
            //alert(recId);
            $.ajax({
                method: "POST",
                url: base_url+"InputBatch/delPraInputShift/"+recshiftId+'/'+recid+'/'+no_shift,

                success: function (a) {
                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                    $("#data_trx_kurset").datagrid('reload');
                    totalSetor();
                }
            });         
        }
    });
}



function editEntry(recId, isStn){
    recID = recId;
    isSTN = isStn;
    $("#cashPenggantian").numberbox('enable');
    $("#totalPenambah").numberbox('enable');
    $("#totalPengurang").numberbox('enable');
    $("#totalVoucher").numberbox('enable');
    //$("#totalVoucher").numberbox('disable');
    
    //$("#scanCode").textbox('clear').textbox('textbox').focus();
    
    $.ajax({
        method: "POST",
        url: base_url+"InputBatch/getDataDetail/"+recID+'/'+isSTN,

        success: function (rows) {
            var data = JSON.parse(rows);
          //alert();
        //$('#scanCode').textbox('setValue', data['SCANCODE']);
        $("#scanCode").textbox('disable');
        $('#storeCode').textbox('setValue', data['STORE_CODE']);
        $('#storeName').textbox('setValue', data['STORE_NAME']);
        $('#tglSales').textbox('setValue', data['SALES_DATE']);

        $('#cashPenggantian').numberbox('setValue', data['ACTUAL_SALES_AMOUNT']);
        $('#totalPenambah').numberbox('setValue', data['TOTAL_PENAMBAHAN']);
        $('#totalPengurang').numberbox('setValue', data['TOTAL_PENGURANGAN']);
        $('#totalVoucher').numberbox('setValue', data['TOTAL_VOUCHER']);
        $('#CDC_REC_ID').val( data['CDC_REC_ID'] );

        if (data['ACTUAL_SALES_FLAG'] == 'Y') {
            $('#sales_flag').prop('checked', true);
        }else{
            $('#sales_flag').prop('checked', false);
        }
        if (data['STN_FLAG'] == 'Y') {
            $('#stnFlag').prop('checked', true);
            $('#gtuBatch').linkbutton('disable');
            $('#stnFlag').val('1');
            stnFlag = 'Y';
            $('#in_mutation_date').datebox('setValue',data['MUTATION_DATE']);
            $("#in_bank").combobox('select', data['BANK_ID']);
            $("#in_bank_account").combobox('select', data['BANK_ACCOUNT_ID']);
            $('#tglMutasi').val(data['MUTATION_DATE']);
            $('#bankAcc').val(data['BANK_ACCOUNT_ID']);
        }else{
            $('#stnFlag').prop('checked', false);
            $('#gtuBatch').linkbutton('enable');
            $('#stnFlag').val('0');
            stnFlag = 'N';
            $('#in_mutation_date').datebox('setValue',"");
            $('#tglMutasi').val("");
            $('#bankAcc').val('');
        }
        
        recStatus = 'e';
    }
});         
}

function delEntry(recId){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){
            //alert(recId);
            $.ajax({
                method: "POST",
                url: base_url+"InputBatch/delPraInput/"+recId,

                success: function (a) {
                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                    $("#data_trx_kurset").datagrid('reload');
                    totalSetor();
                }
            });         
        }
    });
}

function validate_date(str) {
    var arr = str.split('-');
    if (arr.length != 3) {
        return false;
    }

    var day = parseInt(arr[0]);
    var month = parseInt(arr[1]);
    var year = parseInt(arr[2]);

    if (day < 1 || day > 31) {
        return false;
    } else if (month < 1 || month > 12) {
        return false;
    } else if ((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
        return false;
    } else if (month == 2) {
        var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
        if (day > 29 || (day == 29 && !isleap)) {
            return false;
        }
    }

    return true;
}

function set_total_column_penambah() {
    var total = 0;
    $.ajax({
        url: base_url+'input/Trx_Tambah/get_master_penambah/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                total += parseInt($("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'));
            }
            $("#total-penambah-amt").numberbox('setValue', total);
        }
    });
}

function save_data_penambah() {
    $.ajax({
        url: base_url+'input/Trx_Tambah/get_master_penambah/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                if ($("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue') != '') {
                    if (parseInt($("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue')) > 0) {
                        if (validate_date($("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue'))) {
                            $.ajax({
                                url: base_url+'input/Trx_Tambah/save_data_penambah_shift/',
                                type: 'POST',
                                data: {
                                    id: $("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                    rec_id: $('#CDC_REC_ID1').val(),
                                    no_shift: 'H',
                                    real_id: $('#CDC_REC_REAL_ID').val(),
                                    plus_id: msg[i].TRX_PLUS_ID,
                                    plus_date: $("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue'),
                                    plus_desc: $("#pendesc\\["+msg[i].TRX_PLUS_ID+"\\]").textbox('getValue'),
                                    plus_amount: $("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'),
                                    batch_id : $("#savBatch").attr('batchid')
                                },
                                success: function (msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        } else {
                            $("#total-penambah-amt").numberbox('setValue', '0');
                            $.messager.alert('Warning','Format tanggal "'+$("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue')+'" salah.');
                        }
                    } else {
                        if ($("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val() != '') {
                            $.ajax({
                                url: base_url+'input/Trx_Tambah/delete_data_penambah_shift/',
                                type: 'POST',
                                data: {
                                    plus_det_id: $("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                    plus_id : msg[i].TRX_PLUS_ID,
                                    rec_id: $('#CDC_REC_REAL_ID').val(),
                                    batch_id: $("#savBatch").attr('batchid'),
                                    no_shift: 'H'
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        }
                    }
                } else {
                    if ($("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val() != '') {
                        $.ajax({
                            url: base_url+'input/Trx_Tambah/delete_data_penambah_shift/',
                            type: 'POST',
                            data: {
                                plus_det_id: $("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                plus_id : msg[i].TRX_PLUS_ID,
                                rec_id: $('#CDC_REC_REAL_ID').val(),
                                batch_id: $("#savBatch").attr('batchid'),
                                no_shift: 'H'
                            },
                            success: function(msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    }
                }
            }
        }
    });
    $("#totalPenambah").numberbox('setValue', $("#total-penambah-amt").numberbox('getValue'));
}

//POP UP WINDOW PENGGANTIAN
function penggantian(i){
    $("#input-pengganti").window('open');
    /*var receipt_id1 = $('#CDC_REC_ID1').val();
    var receipt_id2 = $('#CDC_REC_ID2').val();
    var receipt_id3 = $('#CDC_REC_ID3').val();*/
    //alert($("#CDC_REC_ID_SHIFT").val());

    if($("#CDC_REC_ID_SHIFT").val() == '1'){
        $('#peng\\[1\\]').numberbox({
               disabled:false
            });
      //  $("#peng\\[1\\]").numberbox('textbox').focus();
        $('#peng\\[2\\]').numberbox({
               disabled:true
            });
        $('#peng\\[3\\]').numberbox({
               disabled:true
            });
    }
    else if($("#CDC_REC_ID_SHIFT").val() == '2'){
        $('#peng\\[2\\]').numberbox({
               disabled:false
            });
      //  $("#peng\\[2\\]").numberbox('textbox').focus();
        $('#peng\\[1\\]').numberbox({
               disabled:true
            });
        $('#peng\\[3\\]').numberbox({
               disabled:true
            });
    }
    else if($("#CDC_REC_ID_SHIFT").val() == '3'){
        $('#peng\\[3\\]').numberbox({
               disabled:false
            });
      //  $("#peng\\[3\\]").numberbox('textbox').focus();
        $('#peng\\[2\\]').numberbox({
               disabled:true
            });
        $('#peng\\[1\\]').numberbox({
               disabled:true
            });
    }
    else{
        $('#peng\\[1\\]').numberbox({
               disabled:false
            });
        $('#peng\\[2\\]').numberbox({
               disabled:false
            });
        $('#peng\\[3\\]').numberbox({
               disabled:false
            });
      //  $("#peng\\[1\\]").numberbox('textbox').focus();
    }

    var store = $("#storeCode").textbox('getValue');

            var rec = $('#CDC_REC_ID'+i).val();
            //for (var i = 1; i <= 3; i++) {
                $("#pengid\\["+i+"\\]").val('');
                $("#peng\\["+i+"\\]").numberbox('setValue', 0);
                $.ajax({
                    url: base_url+'input/Trx_Ganti/get_rec_pengganti/',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        rec_id: rec,
                        peng_id: i
                    },
                    success: function (res) {
                        if (res.length > 0) {
                            $("#pengid\\["+res[0].NO_SHIFT+"\\]").val(res[0].TRX_DETAIL_PENG_ID);
                            $("#peng\\["+res[0].NO_SHIFT+"\\]").numberbox('setValue', res[0].TRX_PENG_AMOUNT);
                        }
                    }
                });

            //}
}

function set_total_column_pengganti() {
    var total = 0;
            for (var i = 1; i <= 3; i++) {
                total += parseInt($("#peng\\["+i+"\\]").numberbox('getValue'));
            }
            $("#total-pengganti-amt").numberbox('setValue', total);
}



function save_data_pengganti(i) {
            //for (var i = 1; i <= 3; i++) {
                //alert('CDC_REC_ID'+i);
                //alert($('#CDC_REC_ID'+i).val());
                if ($("#peng\\["+i+"\\]").numberbox('getValue') != '') {
                    if (parseInt($("#peng\\["+i+"\\]").numberbox('getValue')) > 0) {
                        //if (validate_date($("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue'))) {
                            $.ajax({
                                url: base_url+'input/Trx_Ganti/save_data_pengganti/',
                                type: 'POST',
                                data: {
                                    id: $("#pengid\\["+i+"\\]").val(),
                                    rec_id: $('#CDC_REC_ID'+i).val(),
                                    no_shift: i,
                                    
                                    peng_amount: $("#peng\\["+i+"\\]").numberbox('getValue')
                                },
                                success: function (msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                    } else {
                        if ($("#pengid\\["+i+"\\]").val() != '') {
                            $.ajax({
                                url: base_url+'input/Trx_Ganti/delete_data_pengganti/',
                                type: 'POST',
                                data: {
                                    peng_del_id: $("#pengid\\["+i+"\\]").val()
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        }
                    }
                } else {
                    if ($("#pengid\\["+i+"\\]").val() != '') {
                        $.ajax({
                            url: base_url+'input/Trx_Tambah/delete_data_pengganti/',
                            type: 'POST',
                            data: {
                                peng_del_id: $("#pengid\\["+i+"\\]").val()
                            },
                            success: function(msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    }
                }
            //}
        //}
    //});
    $("#cashPenggantian").numberbox('setValue', $("#total-pengganti-amt").numberbox('getValue'));
}




//POP UP PENAMBAH SHIFT
function penambahshift(j){
    $("#input-penambah-shift").window('open');
    var receipt_id = $('#CDC_REC_ID'+j).val();
    var store = $("#storeCode").textbox('getValue');
    var no;
    var no1;
    var no2;
    //var loop = [1,2,3];
    var num_shift = $("#CDC_REC_ID_SHIFT").val();
    if(num_shift == '1'){
        no = 1;
        no1 = '2';
        no2 = '3';
    }
    else if(num_shift == '2')
    {
        no = 2;
        no1 = '1';
        no2 = '3';
    }
    else if(num_shift == '3')
    {
        no = 3;
        no1 = '1';
        no2 = '2';
    }

    if($("#CDC_REC_ID_SHIFT").val()){
        $("#penam\\["+no+"\\]\\[9\\]").numberbox('textbox').focus();
        $("#penam\\["+no+"\\]\\[9\\]").numberbox({
               disabled:false
            });
        $("#penam\\["+no+"\\]\\[10\\]").numberbox({
               disabled:false
            });
        $("#penam\\["+no+"\\]\\[11\\]").numberbox({
               disabled:false
            });
        $("#penam\\["+no+"\\]\\[12\\]").numberbox({
               disabled:false
            });
        $("#penam\\["+no+"\\]\\[13\\]").numberbox({
                disabled:false
                    });

        $("#penam\\["+no1+"\\]\\[9\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no1+"\\]\\[10\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no1+"\\]\\[11\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no1+"\\]\\[12\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no1+"\\]\\[13\\]").numberbox({
               disabled:true
            });

        $("#penam\\["+no2+"\\]\\[9\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no2+"\\]\\[10\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no2+"\\]\\[11\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no2+"\\]\\[12\\]").numberbox({
               disabled:true
            });
        $("#penam\\["+no2+"\\]\\[13\\]").numberbox({
               disabled:true
            });
    }
    else{
        

        
        $("#penam\\[1\\]\\[9\\]").numberbox('textbox').focus();

    }

        $.ajax({
            url: base_url+'input/Trx_Tambah/get_master_penambah/',
            method: 'POST',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    $("#penid\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").val('');
                    $("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('setValue', 0);
                    $("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('setValue','current');
                    $("#pendesc\\["+msg[i].TRX_PLUS_ID+"\\]").textbox('setValue', msg[i].TRX_PLUS_DESC+' '+store+' '+$("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue').replace(/-/gi, '/'));

                    $.ajax({
                        url: base_url+'input/Trx_Tambah/get_rec_penambah_shift/',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            rec_id: receipt_id,
                            plus_id: msg[i].TRX_PLUS_ID
                        },
                        success: function (res) {
                            if (res.length > 0) {
                                for(var x = 0; x < res.length; x++){
                                    $("#penid\\["+res[x].NO_SHIFT+"\\]\\["+res[x].TRX_PLUS_ID+"\\]").val(res[x].TRX_DETAIL_SHIFT_ID);
                                    $("#penam\\["+res[x].NO_SHIFT+"\\]\\["+res[x].TRX_PLUS_ID+"\\]").numberbox('setValue', res[x].TRX_DET_AMOUNT);
                                    $("#pendate\\["+res[x].TRX_PLUS_ID+"\\]").datebox('setValue',res[x].TRX_DETAIL_DATE);
                                    $("#pendesc\\["+res[x].TRX_PLUS_ID+"\\]").textbox('setValue', res[x].TRX_DETAIL_DESC);
                                }
                            }
                        }
                    });

                    if (msg[i].TRX_PLUS_ID == 9) {
                        $("#penam\\[1\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('textbox').focus();
                    }
                    
                }
            }
        });
}

function set_total_column_penambah_shift1(j) {
    var total = 0;
    //alert(j);
        $.ajax({
            url: base_url+'input/Trx_Tambah/get_master_penambah/',
            method: 'POST',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    total += parseInt($("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'));
                }
                $("#total-penambah-amt-shift1").numberbox('setValue', total);
            }
        });
}

function set_total_column_penambah_shift2(j) {
    var total = 0;
    //alert(j);
        $.ajax({
            url: base_url+'input/Trx_Tambah/get_master_penambah/',
            method: 'POST',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    total += parseInt($("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'));
                }
                $("#total-penambah-amt-shift2").numberbox('setValue', total);
            }
        });
}

function set_total_column_penambah_shift3(j) {
    var total = 0;
    //alert(j);
        $.ajax({
            url: base_url+'input/Trx_Tambah/get_master_penambah/',
            method: 'POST',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    total += parseInt($("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'));
                }
                $("#total-penambah-amt-shift3").numberbox('setValue', total);
            }
        });
}

function set_gtotal_column_penambah_shift() {
    var gtotal = 0;
        
        gtotal += parseInt($("#total-penambah-amt-shift1").numberbox('getValue'));
        gtotal += parseInt($("#total-penambah-amt-shift2").numberbox('getValue'));
        gtotal += parseInt($("#total-penambah-amt-shift3").numberbox('getValue'));
        $("#gtotal-penambah-amt-shift").numberbox('setValue', gtotal);
}


function save_data_penambah_shift(j) {
        $.ajax({
            url: base_url+'input/Trx_Tambah/get_master_penambah/',
            method: 'POST',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    if ($("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue') != '') {
                        if (parseInt($("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue')) > 0) {
                            if (validate_date($("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue'))) {
                                $.ajax({
                                    url: base_url+'input/Trx_Tambah/save_data_penambah_shift/',
                                    type: 'POST',
                                    data: {
                                        id: $("#penid\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                        rec_id: $('#CDC_REC_ID'+j).val(),
                                        no_shift: j,
                                        real_id: $('#CDC_REC_REAL_ID').val(),
                                        plus_id: msg[i].TRX_PLUS_ID,
                                        plus_date: $("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue'),
                                        plus_desc: $("#pendesc\\["+msg[i].TRX_PLUS_ID+"\\]").textbox('getValue'),
                                        plus_amount: $("#penam\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('getValue'),
                                        batch_id : $("#savBatch").attr('batchid')
                                    },
                                    success: function (msg) {
                                        if (msg == 0) {
                                            $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                        }
                                    }
                                });
                            } else {
                                $("#total-penambah-amt-shift").numberbox('setValue', '0');
                                $.messager.alert('Warning','Format tanggal "'+$("#pendate\\["+j+"]["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue')+'" salah.');
                            }
                        } else {
                            if ($("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val() != '') {
                                $.ajax({
                                    url: base_url+'input/Trx_Tambah/delete_data_penambah_shift/',
                                    type: 'POST',
                                    data: {
                                        plus_det_id: $("#penid\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                        plus_id : msg[i].TRX_PLUS_ID,
                                        rec_id: $('#CDC_REC_REAL_ID').val(),
                                        batch_id: $("#savBatch").attr('batchid'),
                                        no_shift: j
                                    },
                                    success: function(msg) {
                                        if (msg == 0) {
                                            $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                        }
                                    }
                                });
                            }
                        }
                    } else {
                        if ($("#penid\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").val() != '') {
                            $.ajax({
                                url: base_url+'input/Trx_Tambah/delete_data_penambah_shift/',
                                type: 'POST',
                                data: {
                                    plus_det_id: $("#penid\\["+j+"\\]\\["+msg[i].TRX_PLUS_ID+"\\]").val(),
                                    plus_id : msg[i].TRX_PLUS_ID,
                                    rec_id: $('#CDC_REC_REAL_ID').val(),
                                    batch_id: $("#savBatch").attr('batchid'),
                                    no_shift: j
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        });
        $("#totalPenambah").numberbox('setValue', $("#gtotal-penambah-amt-shift").numberbox('getValue'));
}

//POP UP PENGURANG SHIFT
function pengurangshift(j){
    $("#input-pengurang-shift").window('open');
    //alert('CDC_REC_ID'+j+' '+$('#CDC_REC_ID'+j).val());
    var dataId = $('#CDC_REC_ID'+j).val();
    var no;
    var no1;
    var no2;
    var kur_date = $("#tglSales").datebox('getValue');
    var store = $("#storeCode").textbox('getValue');
    $("#kur-date").val($("#tglSales").datebox('getValue'));

    var num_shift = $("#CDC_REC_ID_SHIFT").val();
    if(num_shift == '1'){
        no = 1;
        no1 = '2';
        no2 = '3';
    }
    else if(num_shift == '2')
    {
        no = 2;
        no1 = '1';
        no2 = '3';
    }
    else if(num_shift == '3')
    {
        no = 3;
        no1 = '1';
        no2 = '2';
    }

    if($("#CDC_REC_ID_SHIFT").val()){
        $("#kurset\\["+num_shift+"\\]\\[27\\]").numberbox('textbox').focus();
        $("#kurset\\["+num_shift+"\\]\\[27\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[28\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[29\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[30\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[31\\]").numberbox({
               disabled:false
            });

        $("#kurset\\["+num_shift+"\\]\\[32\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[33\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[34\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[35\\]").numberbox({
               disabled:false
            });
        $("#kurset\\["+num_shift+"\\]\\[36\\]").numberbox({
               disabled:true
            });

        $("#kurset\\["+no1+"\\]\\[27\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[28\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[29\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[30\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[31\\]").numberbox({
               disabled:true
            });

        $("#kurset\\["+no1+"\\]\\[32\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[33\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[34\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no1+"\\]\\[35\\]").numberbox({
               disabled:true
            });
         $("#kurset\\["+no1+"\\]\\[36\\]").numberbox({
               disabled:true
            });

        $("#kurset\\["+no2+"\\]\\[27\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[28\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[29\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[30\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[31\\]").numberbox({
               disabled:true
            });

        $("#kurset\\["+no2+"\\]\\[32\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[33\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[34\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[35\\]").numberbox({
               disabled:true
            });
        $("#kurset\\["+no2+"\\]\\[36\\]").numberbox({
               disabled:true
            });
        
    }
    else{
        $("#kurset\\[1\\]\\[27\\]").numberbox('textbox').focus();
    }


    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                $("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val('');
                $("#kurdesc\\["+msg[i].TRX_MINUS_ID+"\\]").textbox('setValue', msg[i].TRX_MINUS_DESC+' '+store+' '+kur_date.replace(/-/gi, '/'));
                $("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('setValue', 0);
                
                $.ajax({
                    url: base_url+'input/Trx_Kurang/get_rec_pengurang_shift/',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        rec_id: dataId,
                        min_id: msg[i].TRX_MINUS_ID
                    },
                    success: function(res) {
                        if (res.length > 0) {
                            for(var x = 0; x < res.length; x++){

                                $("#kurid\\["+res[x].NO_SHIFT+"\\]\\["+res[x].TRX_MINUS_ID+"\\]").val(res[x].TRX_DETAIL_MINUS_SHIFT_ID);
                                $("#kurdesc\\["+res[x].TRX_MINUS_ID+"\\]").textbox('setValue', res[x].TRX_MINUS_DESC.replace(/-/gi, '/'));
                                $("#kurset\\["+res[x].NO_SHIFT+"\\]\\["+res[x].TRX_MINUS_ID+"\\]").numberbox('setValue', res[x].TRX_MINUS_AMOUNT);
                            }
                        }
                    }
                });

                /*if (msg[i].TRX_MINUS_ID == '27') {
                    $("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('textbox').focus();
                }*/
            }
        }
    });
}


function set_total_column_pengurang_shift1(j) {
    var total = 0;
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                total += parseInt($("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'));
            }
            $("#total-pengurang-amt-shift1").numberbox('setValue', total);
        }
    });
}

function set_total_column_pengurang_shift2(j) {
    var total = 0;
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                total += parseInt($("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'));
            }
            $("#total-pengurang-amt-shift2").numberbox('setValue', total);
        }
    });
}

function set_total_column_pengurang_shift3(j) {
    var total = 0;
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                total += parseInt($("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'));
            }
            $("#total-pengurang-amt-shift3").numberbox('setValue', total);
        }
    });
}


function set_gtotal_column_pengurang_shift() {
    var gtotal = 0;
        
        gtotal += parseInt($("#total-pengurang-amt-shift1").numberbox('getValue'));
        gtotal += parseInt($("#total-pengurang-amt-shift2").numberbox('getValue'));
        gtotal += parseInt($("#total-pengurang-amt-shift3").numberbox('getValue'));
        $("#gtotal-pengurang-amt-shift").numberbox('setValue', gtotal);
}

function save_data_pengurang_shift(j) {
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                if ($("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue') != '') {
                    if (parseInt($("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue')) > 0) {
                        $.ajax({
                            url: base_url+'input/Trx_Kurang/save_data_pengurang_shift/',
                            type: 'POST',
                            data: {
                                id: $("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                rec_id: $('#CDC_REC_ID'+j).val(),
                                no_shift: j,
                                real_id: $('#CDC_REC_REAL_ID').val(),
                                min_id: msg[i].TRX_MINUS_ID,
                                min_date: $("#kur-date").val(),
                                min_desc: $("#kurdesc\\["+msg[i].TRX_MINUS_ID+"\\]").textbox('getValue'),
                                min_amount: $("#kurset\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'),
                                batch_id : $("#savBatch").attr('batchid') 
                            },
                            success: function (msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    } else {
                        if ($("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val() != '') {
                            $.ajax({
                                url: base_url+'input/Trx_Kurang/delete_data_pengurang_shift/',
                                type: 'POST',
                                data: {
                                    min_det_id: $("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                    min_id : msg[i].TRX_MINUS_ID,
                                    rec_id: $('#CDC_REC_REAL_ID').val(),
                                    batch_id: $("#savBatch").attr('batchid'),
                                    no_shift: j
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        }
                    }
                } else {
                    if ($("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val() != '') {
                        $.ajax({
                            url: base_url+'input/Trx_Kurang/delete_data_pengurang_shift/',
                            type: 'POST',
                            data: {
                                min_det_id: $("#kurid\\["+j+"\\]\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                min_id : msg[i].TRX_MINUS_ID,
                                rec_id: $('#CDC_REC_REAL_ID').val(),
                                batch_id: $("#savBatch").attr('batchid'),
                                no_shift: j
                            },
                            success: function(msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    }
                }
            }
        }
    });
    $("#totalPengurang").numberbox('setValue', $("#gtotal-pengurang-amt-shift").numberbox('getValue'));
}



//POP UP WINDOW  
function penambah(){
    $("#input-penambah").window('open');
    var receipt_id = $('#CDC_REC_ID1').val();
    var store = $("#storeCode").textbox('getValue');

    $.ajax({
        url: base_url+'input/Trx_Tambah/get_master_penambah/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                $("#penid\\["+msg[i].TRX_PLUS_ID+"\\]").val('');
                $("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('setValue', 0);
                $("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('setValue','current');
                $("#pendesc\\["+msg[i].TRX_PLUS_ID+"\\]").textbox('setValue', msg[i].TRX_PLUS_DESC+' '+store+' '+$("#pendate\\["+msg[i].TRX_PLUS_ID+"\\]").datebox('getValue').replace(/-/gi, '/'));

                $.ajax({
                    url: base_url+'input/Trx_Tambah/get_rec_penambah_shift/',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        rec_id: receipt_id,
                        plus_id: msg[i].TRX_PLUS_ID
                    },
                    success: function (res) {
                        if (res.length > 0) {
                            $("#penid\\["+res[0].TRX_PLUS_ID+"\\]").val(res[0].TRX_DETAIL_SHIFT_ID);
                            $("#penam\\["+res[0].TRX_PLUS_ID+"\\]").numberbox('setValue', res[0].TRX_DET_AMOUNT);
                            $("#pendate\\["+res[0].TRX_PLUS_ID+"\\]").datebox('setValue',res[0].TRX_DETAIL_DATE);
                            $("#pendesc\\["+res[0].TRX_PLUS_ID+"\\]").textbox('setValue', res[0].TRX_DETAIL_DESC);
                        }
                    }
                });

                if (msg[i].TRX_PLUS_ID == 9) {
                    $("#penam\\["+msg[i].TRX_PLUS_ID+"\\]").numberbox('textbox').focus();
                }
                
            }
        }
    });
}


function editPenambah(tmbhId){
    //alert(tmbhId);
    penambahID = tmbhId;
    
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Tambah/getDataDetail/"+tmbhId,

        success: function (rows) {
            var data = JSON.parse(rows);
            var trx_date = '';
          //alert();
          trx_date = data['TRX_DETAIL_DATE'].substring(8,10)+'-'+data['TRX_DETAIL_DATE'].substring(5,7)+'-'+data['TRX_DETAIL_DATE'].substring(0,4);
          $('#trxTypePenambah').combobox('setValue', data['TRX_PLUS_NAME']);
          $('#trxDatePenambah').datebox('setValue', trx_date);
          $('#descPenambah').textbox('setValue', data['TRX_DETAIL_DESC']);
          $('#amountPenambah').numberbox('setValue', data['TRX_DET_AMOUNT']);
        }
    }); 
}

function delPenambah(tmbhId){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){ 
            //alert(tmbhId);
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Tambah/deleteData",
                data: { 
                    dataId : tmbhId,    
                },

                success: function (a) {
                    $('#tblInputPenambah').datagrid('reload');

                    $('#penambah_form').form('clear');
                    $('#trxDatePenambah').datebox({
                        formatter : function(date){
                            var y = date.getFullYear();
                            var m = date.getMonth()+1;
                            var d = date.getDate();
                            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
                        },
                        parser : function(s){
                            if (!s) return new Date();
                            var ss = s.split('-');
                            var y = parseInt(ss[0],10);
                            var m = parseInt(ss[1],10);
                            var d = parseInt(ss[2],10);
                            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                                return new Date(d,m-1,y);
                            } else {
                                return new Date();
                            }
                        }
                    });
                //ISI TGL PENAMBAH
                $('#trxDatePenambah').datebox('setValue', 'current' );
                $("#trxTypePenambah").combobox('clear');                
                
            }
        });
        }
    });
}

function set_total_column_pengurang() {
    var total = 0;
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                total += parseInt($("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'));
            }
            $("#total-pengurang-amt").numberbox('setValue', total);
        }
    });
}

function save_data_pengurang() {
    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                if ($("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue') != '') {
                    if (parseInt($("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue')) > 0) {
                        $.ajax({
                            url: base_url+'input/Trx_Kurang/save_data_pengurang_shift/',
                            type: 'POST',
                            data: {
                                id: $("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                rec_id: $('#CDC_REC_ID1').val(),
                                no_shift: 'H',
                                real_id: $('#CDC_REC_REAL_ID').val(),
                                min_id: msg[i].TRX_MINUS_ID,
                                min_date: $("#kur-date").val(),
                                min_desc: $("#kurdesc\\["+msg[i].TRX_MINUS_ID+"\\]").textbox('getValue'),
                                min_amount: $("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('getValue'),
                                batch_id : $("#savBatch").attr('batchid')
                            },
                            success: function (msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    } else {
                        if ($("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val() != '') {
                            $.ajax({
                                url: base_url+'input/Trx_Kurang/delete_data_pengurang_shift/',
                                type: 'POST',
                                data: {
                                    min_det_id: $("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                    min_id : msg[i].TRX_MINUS_ID,
                                    rec_id: $('#CDC_REC_REAL_ID').val(),
                                    batch_id: $("#savBatch").attr('batchid'),
                                    no_shift: 'H'
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                    }
                                }
                            });
                        }
                    }
                } else {
                    if ($("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val() != '') {
                        $.ajax({
                            url: base_url+'input/Trx_Kurang/delete_data_pengurang_shift/',
                            type: 'POST',
                            data: {
                                min_det_id: $("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val(),
                                min_id : msg[i].TRX_MINUS_ID,
                                rec_id: $('#CDC_REC_REAL_ID').val(),
                                batch_id: $("#savBatch").attr('batchid'),
                                no_shift: 'H'
                            },
                            success: function(msg) {
                                if (msg == 0) {
                                    $.messager.alert('Warning','Terjadi kesalahan, mohon untuk refresh page kemudian dicoba kembali.');
                                }
                            }
                        });
                    }
                }
            }
        }
    });
    $("#totalPengurang").numberbox('setValue', $("#total-pengurang-amt").numberbox('getValue'));
}

function pengurang(){
    $("#input-pengurang").window('open');
    dataId = $('#CDC_REC_ID1').val();

    var kur_date = $("#tglSales").datebox('getValue');
    var store = $("#storeCode").textbox('getValue');
    $("#kur-date").val($("#tglSales").datebox('getValue'));

    $.ajax({
        url: base_url+'input/Trx_Kurang/get_master_pengurang/',
        method: 'POST',
        dataType: 'json',
        success: function (msg) {
            for (var i = 0; i < msg.length; i++) {
                $("#kurid\\["+msg[i].TRX_MINUS_ID+"\\]").val('');
                $("#kurdesc\\["+msg[i].TRX_MINUS_ID+"\\]").textbox('setValue', msg[i].TRX_MINUS_DESC+' '+store+' '+kur_date.replace(/-/gi, '/'));
                $("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('setValue', 0);
                console.log(msg[i].TRX_MINUS_ID);
                $.ajax({
                    url: base_url+'input/Trx_Kurang/get_rec_pengurang_shift/',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        rec_id: dataId,
                        min_id: msg[i].TRX_MINUS_ID
                    },
                    success: function(res) {
                        if (res.length > 0) {
                            $("#kurid\\["+res[0].TRX_MINUS_ID+"\\]").val(res[0].TRX_DETAIL_MINUS_SHIFT_ID);
                            $("#kurdesc\\["+res[0].TRX_MINUS_ID+"\\]").textbox('setValue', res[0].TRX_MINUS_DESC.replace(/-/gi, '/'));
                            $("#kurset\\["+res[0].TRX_MINUS_ID+"\\]").numberbox('setValue', res[0].TRX_MINUS_AMOUNT);
                        }
                    }
                });

                if (msg[i].TRX_MINUS_ID == '27') {
                    $("#kurset\\["+msg[i].TRX_MINUS_ID+"\\]").numberbox('textbox').focus();
                }
            }
        }
    });
}


function editPengurang(krgId){
    //alert(krgId);
    pengurangID = krgId;
    
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Kurang/getDataDetail/"+krgId,

        success: function (rows) {
            var data = JSON.parse(rows);
            var trx_date = '';
          //alert();
          trx_date = data['TRX_MINUS_DATE'].substring(8,10)+'-'+data['TRX_MINUS_DATE'].substring(5,7)+'-'+data['TRX_MINUS_DATE'].substring(0,4);
          $('#trxTypePengurang').combobox('setValue', data['TRX_MINUS_NAME']);
          $('#trxDatePengurang').datebox('setValue', trx_date);
          $('#descPengurang').textbox('setValue', data['TRX_MINUS_DESC']);
          $('#amountPengurang').numberbox('setValue', data['TRX_MINUS_AMOUNT']);

        }
    }); 
}


function delPengurang(tmbhId){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){     
            //alert(tmbhId);
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Kurang/deleteData/"+tmbhId,
                data: { 
                    dataId : tmbhId,    
                },

                success: function (a) {
                    $('#tblInputPengurang').datagrid('reload');

                    $('#penagurang_form').form('clear');
                    $('#trxDatePengurang').datebox({
                        formatter : function(date){
                            var y = date.getFullYear();
                            var m = date.getMonth()+1;
                            var d = date.getDate();
                            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
                        },
                        parser : function(s){
                            if (!s) return new Date();
                            var ss = s.split('-');
                            var y = parseInt(ss[0],10);
                            var m = parseInt(ss[1],10);
                            var d = parseInt(ss[2],10);
                            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                                return new Date(d,m-1,y);
                            } else {
                                return new Date();
                            }
                        }
                    });     
                    
                    $('#trxDatePengurang').datebox('setValue', 'current' );
                    $("#trxTypePengurang").combobox('clear');               
                }
            });
        }
    });         
}

//POP UP voucher shift
function vouchershift(){
    voucher_status = "add";
    $('#voucher_dialog_shift').dialog('open');
    $('#voucher_dialog_shift').dialog('center');
    $('#voucher_dialog_shift').dialog('setTitle','Voucher IDM');
    
    $('#voucher_form_shift').form('clear');
    

    
    $('#voucherDate').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });
    //ISI TGL VOUCHER
    $('#voucherDateShift').datebox('setValue', $("#tglSales").datebox('getValue') );
    dataId = $('#CDC_REC_ID').val();
    $("#voucherNumShift").textbox('clear').textbox('textbox').focus();
    
//VOUCHER DATAGRID  
$('#tblInputVoucherShift').datagrid({
    url:base_url+'input/Trx_Voucher/getDataVoucherShift/'+dataId,
    columns:[[
    {field:'TRX_VOUCHER_SHIFT_ID',hidden:true},
    {field:'TRX_VOUCHER_NUM',title:'Voucher Num',width:150,align:'center'},
    {field:'TRX_VOUCHER_DATE',title:'Sales Date',width:150,align:'center',
    formatter:function (value,row,index) {
        var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
        options = {
            year: 'numeric', month: 'long', day: 'numeric'
        };
        return Intl.DateTimeFormat('id-ID', options).format(date);
    }           
},
{field:'TRX_VOUCHER_DESC',title:'Voucher Description',width:250,align:'center'},
{field:'TRX_VOUCHER_AMOUNT',title:'Voucher Amount',width:200,align:'center',
formatter:function (value,row,index) {
    return Intl.NumberFormat('en-US').format(value);
}           
},
{field:'NO_SHIFT',title:'Shift',width:50,align:'center'}
,

/*          
            {field: 'EDIT', title: '',align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnEditVoucher" value="Edit" onClick="editVoucher('+row.TRX_VOUCHER_ID+')"> ';
                return col;
            }},
            */
            
            {field: 'DELETE', title: '',align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnDelVoucherShift" value="Delete" onClick="delVoucherShift('+row.TRX_VOUCHER_SHIFT_ID+')"> ';
                return col;
            }}
            ]],
            rownumbers : true, singleSelect:true, fitColumns:true,pageSize: 10
        }); 

}

function simpanVoucherShift(){
    if(voucherID != null){  //SAVE EDIT
        //alert(penambahID);
        if($('#voucher_form_shift').form('validate') == true){
            //alert($('#voucherNum').textbox('getValue'));
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/updateDataShift",
                data: { 
                    voucherID   : voucherID,
                    receiptID   : $('#CDC_REC_ID').val(),
                    num         : $('#voucherNumShift').textbox('getValue'),
                    date        : $('#voucherDateShift').datebox('getValue'),
                    desc        : $('#voucherDescShift').textbox('getValue'),
                    amount      : $('#voucherAmountShift').numberbox('getValue'),
                    no_shift    : $('#no_shiftShift').combobox('getValue')
                },

                success: function (message) {
                    voucherID = null;  
                    $.messager.show({title: 'Success',msg: message});

                    $('#voucher_form_shift').form('clear');
                    $('#voucherDate').datebox('setValue', tgl.getDate()+'-'+tgl.getMonth()+'-'+tgl.getFullYear() );
                    $("#voucherNum").textbox('clear').focus();  
                }
            }); 
        }       
        
    }
    else{  //BUAT BARU
        if($('#voucher_form_shift').form('validate') == true){
            //alert($('#voucherNumShift').textbox('getValue'));
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/addDataShift",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    num         : $('#voucherNumShift').textbox('getValue'),
                    date        : $('#voucherDateShift').datebox('getValue'),
                    desc        : $('#voucherDescShift').textbox('getValue'),
                    amount      : $('#voucherAmountShift').numberbox('getValue'),
                    no_shift    : $('#no_shift_voucher').combobox('getValue')
                },

                success: function (message) {
                //alert($('#CDC_REC_ID').val());
                $.messager.show({title: 'Success',msg: message});
                
                $('#voucher_form_shift').form('clear');
                $('#voucherDateShift').datebox('setValue', tgl.getDate()+'-'+tgl.getMonth()+'-'+tgl.getFullYear() );
                $("#voucherNumShift").textbox('clear').focus(); 
            }
        }); 
        }       
    }

    $('#tblInputVoucherShift').datagrid('load');
}


function selesaiVoucherShift(){
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Voucher/getTotalShift/"+dataId,

        success: function (total) {
        //ISI TOTAL PENAMBAH
        $('#totalVoucher').numberbox('setValue',total);
    }
});     
    
    $('#voucher_dialog_shift').dialog('close');
}   

function delVoucherShift(voucherId){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){ 
            //alert(tmbhId);
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/deleteDataShift",
                data:{
                    id : voucherId
                },

                success: function (a) {
                    $('#tblInputVoucherShift').datagrid('reload');

                    $('#voucher_form_shift').form('clear');
                    $('#voucherDateShift').datebox({
                        formatter : function(date){
                            var y = date.getFullYear();
                            var m = date.getMonth()+1;
                            var d = date.getDate();
                            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
                        },
                        parser : function(s){
                            if (!s) return new Date();
                            var ss = s.split('-');
                            var y = parseInt(ss[0],10);
                            var m = parseInt(ss[1],10);
                            var d = parseInt(ss[2],10);
                            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                                return new Date(d,m-1,y);
                            } else {
                                return new Date();
                            }
                        }
                    });
                //ISI TGL PENAMBAH
                $('#voucherDateShift').datebox('setValue', 'current' );
                $("#voucherNumShift").textbox('clear').focus();         
                
            }
        });
        }
    });
}

function salesharianshift(){


    if ($("#flagshift").combobox('getValue') == 'H-1') {
        // KALAU DIA HARIAN SHIFT
        flag1 = 'H-1';
    } else if ($("#flagshift").combobox('getValue') == 'H') {
        flag1 = 'H';
    } else {
        flag1 = $("#flagshift").combobox('getValue');
    }
    var start_input_time = $('#start_input_time').textbox('getValue');

    $.ajax({
    method: "POST",
    url: base_url + "InputBatch/cekTrxBefore/",
    data: {
        sales_date: $("#tglSales").datebox('getValue'),
        store_code: $("#storeCode").textbox('getValue'),
        stnFlag: stnFlag

    },
    success: function(msg) {

        if (msg == "TRUE") {

            $.ajax({
                method: "POST",
                url: base_url + "InputBatch/cekTanggal/",
                data: {
                    sales_date: $("#tglSales").datebox('getValue')
                },
                success: function(msg) {
                    if (msg == 0) {
                        $.messager.alert('Warning', 'Tanggal sales melebihi tanggal hari ini');
                    } else {
                        if (recStatus == 'n') {
                            $.ajax({
                                method: "POST",
                                url: base_url + "InputBatch/cekDataShift/",
                                data: {
                                    store_code: $("#storeCode").textbox('getValue'),
                                    sales_date: $("#tglSales").datebox('getValue'),
                                    sales_flag: salesFlag,
                                    stn_flag: stnFlag,
                                    tipe_shift: flag1
                                },
                                success: function(hasilCek) {
                                    if (hasilCek < 1) {
                                        //alert("aman");
                                        var checking;
                                        if (salesFlag == 'Y') {
                                            checking = 1;
                                        } //CLOSE IF SALES FLAG > 0
                                        else {
                                            var r = parseInt($("#totalPenambah").textbox('getValue'));
                                            var k = parseInt($("#totalPengurang").textbox('getValue'));
                                            if (r <= 0 && k <= 0) {
                                                checking = 0;
                                            } else {
                                                checking = 1;
                                                //alert("aman");
                                            }
                                        } //CLOSE ELSE SALES FLAG

                                        if (checking) {
                                            if ($("#cashPenggantian").textbox('getValue') <= 0 && salesFlag == 'Y') {
                                                alert("Cash Penggantian Tidak Boleh Kosong");
                                            } else {
                                                var cash, tmbh, kurang, total;
                                                cash = parseInt($("#cashPenggantian").textbox('getValue'));
                                                tmbh = parseInt($("#totalPenambah").textbox('getValue'));
                                                kurang = parseInt($("#totalPengurang").textbox('getValue'));

                                                total = parseInt(cash + tmbh);
                                                /*if( kurang >= total ){
                                                    alert("Input Tidak Valid"); 
                                                }
                                                else{*/
                                                //alert("Setoran Valid");

                                                //alert(recStatus);
                                                start_input_time = $('#start_input_time').textbox('getValue');
                                                tgl_sales = $('#tglSales').datebox('getValue');
                                                var receipt_id1 = $('#CDC_REC_ID1').val();
                                                var receipt_id2 = $('#CDC_REC_ID2').val();
                                                var receipt_id3 = $('#CDC_REC_ID3').val();
                                                var rec = receipt_id1 + '-' + receipt_id2 + '-' + receipt_id3;
                                                var flags = '';
                                                if ($("#flagshift").combobox('getValue') == 'H-1') {
                                                    // KALAU DIA HARIAN SHIFT
                                                    flags = 'H-1';
                                                } else if ($("#flagshift").combobox('getValue') == 'H') {
                                                    flags = 'H';
                                                } else {
                                                    flags = $("#flagshift").combobox('getValue');
                                                }

                                                $.ajax({
                                                    method: 'POST',
                                                    url: base_url + "InputBatch/cek_trx_detail_shift",
                                                    data: {
                                                        tambah: parseInt($("#totalPenambah").textbox('getValue')),
                                                        kurang: parseInt($("#totalPengurang").textbox('getValue')),
                                                        rec_id: receipt_id1,
                                                        rec_id2: receipt_id2,
                                                        rec_id3: receipt_id3,
                                                        flags: flags
                                                    },
                                                    success: function(msg) {
                                                        if (msg > 0) {
                                                            var flag = '';
                                                            if ($("#flagshift").combobox('getValue') == 'H-1' || $("#flagshift").combobox('getValue') == 'HARIAN-SHIFT') {
                                                                // KALAU DIA HARIAN SHIFT
                                                                flag = 'H-1';
                                                            } else if ($("#flagshift").combobox('getValue') == 'H' || $("#flagshift").combobox('getValue') == 'HARIAN') {
                                                                    flag = 'H';
                                                            } else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-1' || $("#flagshift").combobox('getValue') == 'SHIFT-1'){
                                                                    flag = 'S-1';
                                                            }else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-2' || $("#flagshift").combobox('getValue') == 'SHIFT-2'){
                                                                    flag = 'S-2';
                                                            }else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-3' || $("#flagshift").combobox('getValue') == 'SHIFT-3'){
                                                                    flag = 'S-3';
                                                             
                                                            }

                                                            $.ajax({
                                                                method: "POST",
                                                                url: base_url + "InputBatch/praInputShift",
                                                                data: {
                                                                    statusxx: recStatus,
                                                                    receiptID: $('#CDC_REC_ID1').val(),
                                                                    receiptID2: $('#CDC_REC_ID2').val(),
                                                                    receiptID3: $('#CDC_REC_ID3').val(),
                                                                    realrecid: $('#CDC_REC_REAL_ID').val(),
                                                                    start_input_time: start_input_time,
                                                                    store_code: storeID,
                                                                    flag_shift: flag,
                                                                    //sales_date            : salesDate.substring(0, 2),
                                                                    sales_date: tgl_sales,
                                                                    sta_tus: 'N',
                                                                    flag: salesFlag,
                                                                    stn: stnFlag,
                                                                    sales_amount: $('#cashPenggantian').textbox('getValue'),
                                                                    mutation_date: $('#tglMutasi').val(),
                                                                    bank_acc: $('#bankAcc').val(),
                                                                    batch_id: $("#savBatch").attr('batchid')
                                                                },
                                                                success: function(message) {
                                                                    if ($("#savBatch").attr('batchid') != '') {
                                                                        $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputRejectShift/' + $("#savBatch").attr('batchid'));
                                                                    } else {
                                                                        $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputShift');
                                                                    }
                                                                    $.messager.show({
                                                                        title: 'Success',
                                                                        msg: message
                                                                    });
                                                                    totalSetor();
                                                                    recStatus = 'n';
                                                                    $('#CDC_REC_ID1').val(null);
                                                                    $('#CDC_REC_ID2').val(null);
                                                                    $('#CDC_REC_ID3').val(null);
                                                                    $('#CDC_REC_REAL_ID').val(null);
                                                                    $('#CDC_REC_ID_SHIFT').val(null);
                                                                    $('#flagshift').removeAttr("disabled");
                                                                    salesDate = null;
                                                                    storeID = null;
                                                                    salesFlag = 'Y';
                                                                    $("#storeCode").textbox('clear');
                                                                    $("#storeName").textbox('clear');
                                                                    $("#tglSales").datebox('clear');
                                                                    $("#cashPenggantian").numberbox('disable').textbox('setValue', '0');
                                                                    $("#totalPenambah").numberbox('disable').textbox('setValue', '0');
                                                                    $("#totalPengurang").numberbox('disable').textbox('setValue', '0');
                                                                    $("#totalVoucher").numberbox('disable').textbox('setValue', '0');
                                                                    $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                                                                    $('#sales_flag').prop('checked', true);
                                                                    $('#stnFlag').prop('checked', false);
                                                                    $('#gtuBatch').linkbutton('enable');
                                                                }
                                                            });

                                                        } else $.messager.show({
                                                            title: 'Error',
                                                            msg: 'Terdapat selisih pada Data Penambah atau Pengurang'
                                                        });
                                                    }
                                                });
                                                /*}*/ //CLOSE ELSE SETORAN TIDAK VALID
                                            } //CLOSE ELSE ALERT CASH TDK BOLEH KOSONG  
                                        } else { //CLOSE CHEKING
                                            $.messager.alert('Warning', 'Input Tidak Valid');
                                        }
                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////          
                                    } else {
                                        $.messager.alert('Warning', 'Data Sudah Ada');
                                    }
                                }
                            });
                        } else if (recStatus == 'e') {
                            if ($("#cashPenggantian").textbox('getValue') <= 0) {
                                alert("Cash Penggantian Tidak Boleh Kosong");
                            } else {
                                var cash, tmbh, kurang, total;
                                var recid;
                                cash = parseInt($("#cashPenggantian").textbox('getValue'));
                                tmbh = parseInt($("#totalPenambah").textbox('getValue'));
                                kurang = parseInt($("#totalPengurang").textbox('getValue'));

                                if ($('#CDC_REC_ID1').val()) {
                                    recid = $('#CDC_REC_ID1').val();
                                } else if ($('#CDC_REC_ID2').val()) {
                                    recid = $('#CDC_REC_ID2').val();
                                } else if ($('#CDC_REC_ID3').val()) {
                                    recid = $('#CDC_REC_ID3').val();
                                }

                                total = parseInt(cash + tmbh);
                                if (kurang >= total) {
                                    $.messager.alert('Warning', 'Input Tidak Valid');
                                } else {
                                    tgl_sales = $('#tglSales').datebox('getValue');
                                    $.ajax({
                                        method: "POST",
                                        url: base_url + "InputBatch/praEditShift",
                                        data: {
                                            statusxx: recStatus,
                                            receiptID: recid,
                                            realrecid: $('#CDC_REC_REAL_ID').val(),
                                            store_code: $('#storeCode').textbox('getValue'),
                                            start_input_time: start_input_time,
                                            sales_date: tgl_sales,
                                            sta_tus: 'n',
                                            flag: salesFlag,
                                            stn: stnFlag,
                                            sales_amount: $('#cashPenggantian').textbox('getValue'),
                                            mutation_date: $('#tglMutasi').val(),
                                            bank_acc: $('#bankAcc').val(),
                                            batch_id: $("#savBatch").attr('batchid'),
                                            no_shift: $('#CDC_REC_ID_SHIFT').val()
                                        },

                                        success: function(message) {
                                            $.messager.show({
                                                title: 'Success',
                                                msg: message
                                            });
                                            if ($("#savBatch").attr('batchid') != '') {
                                                $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputRejectShift/' + $("#savBatch").attr('batchid'));
                                            } else {
                                                $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputShift');
                                            }
                                            totalSetor();
                                            recStatus = 'n';
                                        }
                                    });

                                    $('#CDC_REC_ID1').val(null);
                                    $('#CDC_REC_ID2').val(null);
                                    $('#CDC_REC_ID3').val(null);
                                    $('#CDC_REC_REAL_ID').val(null);
                                    $('#CDC_REC_ID_SHIFT').val(null);
                                    $('#flagshift').removeAttr("disabled");
                                    salesDate = null;
                                    storeID = null;
                                    $("#storeCode").textbox('clear');
                                    $("#storeName").textbox('clear');
                                    $("#tglSales").datebox('clear');
                                    $("#cashPenggantian").numberbox('disable').textbox('setValue', '0');
                                    $("#totalPenambah").numberbox('disable').textbox('setValue', '0');
                                    $("#totalPengurang").numberbox('disable').textbox('setValue', '0');
                                    $("#totalVoucher").numberbox('disable').textbox('setValue', '0');
                                    if ($('#savBatch:visible').length) {
                                        $("#scanCode").textbox('disable');
                                    } else {
                                        $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                                    }
                                    $('#sales_flag').prop('checked', true);
                                    salesFlag = 'Y';
                                    $('#stnFlag').prop('checked', false);
                                    $('#gtuBatch').linkbutton('enable');
                                }
                            }
                        }
                    }
                }
            });

        } else {
            $.messager.alert('Warning', msg);
         }
        }
    });
}


function nonsalesharianshift(){
    var start_input_time = $('#start_input_time').textbox('getValue');
    if ($("#flagshift").combobox('getValue') == 'H-1') {
        // KALAU DIA HARIAN SHIFT
        flag1 = 'H-1';
    } else if ($("#flagshift").combobox('getValue') == 'H') {
        flag1 = 'H';
    } else {
        flag1 = $("#flagshift").combobox('getValue');
    }


    $.ajax({
        method: "POST",
        url: base_url + "InputBatch/cekTanggal/",
        data: {
            sales_date: $("#tglSales").datebox('getValue')
        },
        success: function(msg) {
            if (msg == 0) {
                $.messager.alert('Warning', 'Tanggal sales melebihi tanggal hari ini');
            } else {
                if (recStatus == 'n') {
                    $.ajax({
                        method: "POST",
                        url: base_url + "InputBatch/cekDataShift/",
                        data: {
                            store_code: $("#storeCode").textbox('getValue'),
                            sales_date: $("#tglSales").datebox('getValue'),
                            sales_flag: salesFlag,
                            stn_flag: stnFlag,
                            tipe_shift: flag1
                        },
                        success: function(hasilCek) {
                            if (hasilCek < 1) {
                                //alert("aman");
                                var checking;
                                if (salesFlag == 'Y') {
                                    checking = 1;
                                } //CLOSE IF SALES FLAG > 0
                                else {
                                    var r = parseInt($("#totalPenambah").textbox('getValue'));
                                    var k = parseInt($("#totalPengurang").textbox('getValue'));
                                    if (r <= 0 && k <= 0) {
                                        checking = 0;
                                    } else {
                                        checking = 1;
                                        //alert("aman");
                                    }
                                } //CLOSE ELSE SALES FLAG

                                if (checking) {
                                    if ($("#cashPenggantian").textbox('getValue') <= 0 && salesFlag == 'Y') {
                                        alert("Cash Penggantian Tidak Boleh Kosong");
                                    } else {
                                        var cash, tmbh, kurang, total;
                                        cash = parseInt($("#cashPenggantian").textbox('getValue'));
                                        tmbh = parseInt($("#totalPenambah").textbox('getValue'));
                                        kurang = parseInt($("#totalPengurang").textbox('getValue'));

                                        total = parseInt(cash + tmbh);
                                        /*if( kurang >= total ){
                                            alert("Input Tidak Valid"); 
                                        }
                                        else{*/
                                        //alert("Setoran Valid");

                                        //alert(recStatus);
                                        start_input_time = $('#start_input_time').textbox('getValue');
                                        tgl_sales = $('#tglSales').datebox('getValue');
                                        var receipt_id1 = $('#CDC_REC_ID1').val();
                                        var receipt_id2 = $('#CDC_REC_ID2').val();
                                        var receipt_id3 = $('#CDC_REC_ID3').val();
                                        var rec = receipt_id1 + '-' + receipt_id2 + '-' + receipt_id3;
                                        var flags = '';
                                        if ($("#flagshift").combobox('getValue') == 'H-1') {
                                            // KALAU DIA HARIAN SHIFT
                                            flags = 'H-1';
                                        } else if ($("#flagshift").combobox('getValue') == 'H') {
                                            flags = 'H';
                                        } else {
                                            flags = $("#flagshift").combobox('getValue');
                                        }

                                        $.ajax({
                                            method: 'POST',
                                            url: base_url + "InputBatch/cek_trx_detail_shift",
                                            data: {
                                                tambah: parseInt($("#totalPenambah").textbox('getValue')),
                                                kurang: parseInt($("#totalPengurang").textbox('getValue')),
                                                rec_id: receipt_id1,
                                                rec_id2: receipt_id2,
                                                rec_id3: receipt_id3,
                                                flags: flags
                                            },
                                            success: function(msg) {
                                                if (msg > 0) {
                                                    var flag = '';
                                                        if ($("#flagshift").combobox('getValue') == 'H-1' || $("#flagshift").combobox('getValue') == 'HARIAN-SHIFT') {
                                                                // KALAU DIA HARIAN SHIFT
                                                            flag = 'H-1';
                                                    } else if ($("#flagshift").combobox('getValue') == 'H' || $("#flagshift").combobox('getValue') == 'HARIAN') {
                                                            flag = 'H';
                                                    } else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-1' || $("#flagshift").combobox('getValue') == 'SHIFT-1'){
                                                            flag = 'S-1';
                                                    }else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-2' || $("#flagshift").combobox('getValue') == 'SHIFT-2'){
                                                                flag = 'S-2';
                                                    }else if(($("#flagshift").combobox('getValue')).toUpperCase() == 'S-3' || $("#flagshift").combobox('getValue') == 'SHIFT-3'){
                                                                flag = 'S-3';
                                                     }



                                                    $.ajax({
                                                        method: "POST",
                                                        url: base_url + "InputBatch/praInputShift",
                                                        data: {
                                                            statusxx: recStatus,
                                                            receiptID: $('#CDC_REC_ID1').val(),
                                                            receiptID2: $('#CDC_REC_ID2').val(),
                                                            receiptID3: $('#CDC_REC_ID3').val(),
                                                            realrecid: $('#CDC_REC_REAL_ID').val(),
                                                            start_input_time: start_input_time,
                                                            store_code: storeID,
                                                            flag_shift: flag,
                                                            //sales_date            : salesDate.substring(0, 2),
                                                            sales_date: tgl_sales,
                                                            sta_tus: 'N',
                                                            flag: salesFlag,
                                                            stn: stnFlag,
                                                            sales_amount: $('#cashPenggantian').textbox('getValue'),
                                                            mutation_date: $('#tglMutasi').val(),
                                                            bank_acc: $('#bankAcc').val(),
                                                            batch_id: $("#savBatch").attr('batchid')
                                                        },
                                                        success: function(message) {
                                                            if ($("#savBatch").attr('batchid') != '') {
                                                                $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputRejectShift/' + $("#savBatch").attr('batchid'));
                                                            } else {
                                                                $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputShift');
                                                            }
                                                            $.messager.show({
                                                                title: 'Success',
                                                                msg: message
                                                            });
                                                            totalSetor();
                                                            recStatus = 'n';
                                                            $('#CDC_REC_ID1').val(null);
                                                            $('#CDC_REC_ID2').val(null);
                                                            $('#CDC_REC_ID3').val(null);
                                                            $('#CDC_REC_REAL_ID').val(null);
                                                            $('#CDC_REC_ID_SHIFT').val(null);
                                                            $('#flagshift').removeAttr("disabled");
                                                            salesDate = null;
                                                            storeID = null;
                                                            salesFlag = 'Y';
                                                            $("#storeCode").textbox('clear');
                                                            $("#storeName").textbox('clear');
                                                            $("#tglSales").datebox('clear');
                                                            $("#cashPenggantian").numberbox('disable').textbox('setValue', '0');
                                                            $("#totalPenambah").numberbox('disable').textbox('setValue', '0');
                                                            $("#totalPengurang").numberbox('disable').textbox('setValue', '0');
                                                            $("#totalVoucher").numberbox('disable').textbox('setValue', '0');
                                                            $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                                                            $('#sales_flag').prop('checked', true);
                                                            $('#stnFlag').prop('checked', false);
                                                            $('#gtuBatch').linkbutton('enable');
                                                        }
                                                    });

                                                } else $.messager.show({
                                                    title: 'Error',
                                                    msg: 'Terdapat selisih pada Data Penambah atau Pengurang'
                                                });
                                            }
                                        });
                                        /*}*/ //CLOSE ELSE SETORAN TIDAK VALID
                                    } //CLOSE ELSE ALERT CASH TDK BOLEH KOSONG  
                                } else { //CLOSE CHEKING
                                    $.messager.alert('Warning', 'Input Tidak Valid');
                                }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////          
                            } else {
                                $.messager.alert('Warning', 'Data Sudah Ada');
                            }
                        }
                    });
                } else if (recStatus == 'e') {
                    if ($("#cashPenggantian").textbox('getValue') <= 0) {
                        alert("Cash Penggantian Tidak Boleh Kosong");
                    } else {
                        var cash, tmbh, kurang, total;
                        var recid;
                        cash = parseInt($("#cashPenggantian").textbox('getValue'));
                        tmbh = parseInt($("#totalPenambah").textbox('getValue'));
                        kurang = parseInt($("#totalPengurang").textbox('getValue'));

                        if ($('#CDC_REC_ID1').val()) {
                            recid = $('#CDC_REC_ID1').val();
                        } else if ($('#CDC_REC_ID2').val()) {
                            recid = $('#CDC_REC_ID2').val();
                        } else if ($('#CDC_REC_ID3').val()) {
                            recid = $('#CDC_REC_ID3').val();
                        }

                        total = parseInt(cash + tmbh);
                        if (kurang >= total) {
                            $.messager.alert('Warning', 'Input Tidak Valid');
                        } else {
                            tgl_sales = $('#tglSales').datebox('getValue');
                            $.ajax({
                                method: "POST",
                                url: base_url + "InputBatch/praEditShift",
                                data: {
                                    statusxx: recStatus,
                                    receiptID: recid,
                                    realrecid: $('#CDC_REC_REAL_ID').val(),
                                    store_code: $('#storeCode').textbox('getValue'),
                                    sales_date: tgl_sales,
                                    sta_tus: 'n',
                                    flag: salesFlag,
                                    stn: stnFlag,
                                    sales_amount: $('#cashPenggantian').textbox('getValue'),
                                    mutation_date: $('#tglMutasi').val(),
                                    bank_acc: $('#bankAcc').val(),
                                    batch_id: $("#savBatch").attr('batchid'),
                                    no_shift: $('#CDC_REC_ID_SHIFT').val()
                                },

                                success: function(message) {
                                    $.messager.show({
                                        title: 'Success',
                                        msg: message
                                    });
                                    if ($("#savBatch").attr('batchid') != '') {
                                        $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputRejectShift/' + $("#savBatch").attr('batchid'));
                                    } else {
                                        $('#tblTrxReceipts').datagrid('reload', base_url + 'InputBatch/getPraInputShift');
                                    }
                                    totalSetor();
                                    recStatus = 'n';
                                }
                            });

                            $('#CDC_REC_ID1').val(null);
                            $('#CDC_REC_ID2').val(null);
                            $('#CDC_REC_ID3').val(null);
                            $('#CDC_REC_REAL_ID').val(null);
                            $('#CDC_REC_ID_SHIFT').val(null);
                            $('#flagshift').removeAttr("disabled");
                            salesDate = null;
                            storeID = null;
                            $("#storeCode").textbox('clear');
                            $("#storeName").textbox('clear');
                            $("#tglSales").datebox('clear');
                            $("#cashPenggantian").numberbox('disable').textbox('setValue', '0');
                            $("#totalPenambah").numberbox('disable').textbox('setValue', '0');
                            $("#totalPengurang").numberbox('disable').textbox('setValue', '0');
                            $("#totalVoucher").numberbox('disable').textbox('setValue', '0');
                            if ($('#savBatch:visible').length) {
                                $("#scanCode").textbox('disable');
                            } else {
                                $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                            }
                            $('#sales_flag').prop('checked', true);
                            salesFlag = 'Y';
                            $('#stnFlag').prop('checked', false);
                            $('#gtuBatch').linkbutton('enable');
                        }
                    }
                }
            }
        }
    });

}
function receiptSaveShift() {
    //SAVE BARU 


    if( $("#flagshift").combobox('getValue')!=''){

                if ($("#flagshift").combobox('getValue') == 'H-1') {
                        // KALAU DIA HARIAN SHIFT
                        flag1 = 'H-1';
                    } else if ($("#flagshift").combobox('getValue') == 'H') {
                        flag1 = 'H';
                    } else {
                        flag1 = $("#flagshift").combobox('getValue');
                    }


                    if (salesFlag == 'N' && parseInt($("#cashPenggantian").numberbox('getValue')) > 0) {
                        $.messager.alert('Warning', 'Data sales diakui sebagai titipan, mohon untuk Check dan Uncheck Sales Flag atau refresh halaman kemudian input kembali.');
                    } else if (stnFlag == 'Y' && flag1 == 'H-1') {
                        $.messager.alert('Warning', 'Tidak bisa input sales Harian Shift dengan STN');
                    } else {
                        if ((flag1 != 'H-1' && flag1 != 'H') && salesFlag == 'Y') {
                            if (stnFlag == 'Y') {
                                if($('#in_bank').combobox('getText')=='CIMB NIAGA' || $('#in_bank').combobox('getText')=='BANK CIMB NIAGA'){
                                      $.ajax({
                                        method: "POST",
                                        url: base_url + "InputBatch/cekCIMBNIAGA/",
                                        data: {
                                            sales_date: $("#tglSales").datebox('getValue'),
                                            store_code: $("#storeCode").textbox('getValue'),
                                            stnFlag: stnFlag

                                        },
                                        success: function(msg) {
                                           if(msg=='0'){
                                             $.messager.alert('Warning','Toko ini tidak diijinkan input setor dengan CIMB NIAGA.');
                            
                                           }else{
                                             salesharianshift();
                                           }
                                        }
                                    });
                                }else{
                                     salesharianshift();
                                }
                                
                              
                               
                            } else {
                                salesharianshift();
                            }


                        } else {

                            if(stnFlag=='Y'){
                               
                                  if($('#in_bank').combobox('getText')=='BANK CIMB NIAGA'){
                                        $.ajax({
                                        method: "POST",
                                        url: base_url + "InputBatch/cekCIMBNIAGA/",
                                        data: {
                                            sales_date: $("#tglSales").datebox('getValue'),
                                            store_code: $("#storeCode").textbox('getValue'),
                                            stnFlag: stnFlag

                                        },
                                        success: function(msg) {
                                           if(msg=='0'){
                                                $.messager.alert('Warning','Toko ini tidak diijinkan input setor dengan CIMB NIAGA.');
                                           }else{
                                             nonsalesharianshift();
                                           }
                                        }
                                    });
                                    }else{
                                        nonsalesharianshift();
                                    }
                                  
                            }else{
                                nonsalesharianshift();
                            }

                           
                        }


                    }




    }else{
          $.messager.alert('Warning','Harap pilih tipe shift toko.');
    }

}

function voucher(){
    voucher_status = "add";
    $('#voucher_dialog').dialog('open');
    $('#voucher_dialog').dialog('center');
    $('#voucher_dialog').dialog('setTitle','Voucher IDM');
    
    $('#voucher_form').form('clear');
    

    
    $('#voucherDate').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){
            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    });
    //ISI TGL VOUCHER
    $('#voucherDate').datebox('setValue', $("#tglSales").datebox('getValue') );
    dataId = $('#CDC_REC_ID').val();
    $("#voucherNum").textbox('clear').textbox('textbox').focus();
    
//VOUCHER DATAGRID  
$('#tblInputVoucher').datagrid({
    url:base_url+'input/Trx_Voucher/getData/'+dataId,
    columns:[[
    {field:'TRX_VOUCHER_ID',hidden:true},
    {field:'TRX_VOUCHER_NUM',title:'Voucher Num',width:150,align:'center'},
    {field:'TRX_VOUCHER_DATE',title:'Sales Date',width:150,align:'center',
    formatter:function (value,row,index) {
        var date = new Date(value.substring(0,4)+'-'+value.substring(5,7)+'-'+value.substring(8,10));
        options = {
            year: 'numeric', month: 'long', day: 'numeric'
        };
        return Intl.DateTimeFormat('id-ID', options).format(date);
    }           
},
{field:'TRX_VOUCHER_DESC',title:'Voucher Description',width:250,align:'center'},
{field:'TRX_VOUCHER_AMOUNT',title:'Voucher Amount',width:200,align:'center',
formatter:function (value,row,index) {
    return Intl.NumberFormat('en-US').format(value);
}           
},

/*          
            {field: 'EDIT', title: '',align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnEditVoucher" value="Edit" onClick="editVoucher('+row.TRX_VOUCHER_ID+')"> ';
                return col;
            }},
            */
            
            {field: 'DELETE', title: '',align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnDelVoucher" value="Delete" onClick="delVoucher('+row.TRX_VOUCHER_ID+')"> ';
                return col;
            }}
            ]],
            rownumbers : true, singleSelect:true, fitColumns:true,pageSize: 10
        }); 

}

function receiptReset(){
    $('#CDC_REC_ID').val(null);
    recStatus = 'n';
    salesDate = null;
    storeID   = null;
    $("#storeCode").textbox('clear');
    $("#storeName").textbox('clear');
    $("#tglSales").datebox('clear');
    $("#cashPenggantian").numberbox('disable').textbox('setValue','0');
    $("#totalPenambah").numberbox('disable').textbox('setValue','0');
    $("#totalPengurang").numberbox('disable').textbox('setValue','0');
    $("#totalVoucher").numberbox('disable').textbox('setValue','0');
    $("#scanCode").textbox('clear').textbox('textbox').focus();
    $('#gtuBatch').linkbutton('enable');
}

function receiptSave(){
    //SAVE BARU 
    if (salesFlag == 'N' && parseInt($("#cashPenggantian").numberbox('getValue')) > 0) {
        $.messager.alert('Warning','Data sales diakui sebagai titipan, mohon untuk Check dan Uncheck Sales Flag atau refresh halaman kemudian input kembali.');
    } else {
        $.ajax({
            method: "POST",
            url: base_url+"InputBatch/cekTanggal/",
            data:{
                sales_date : $("#tglSales").datebox('getValue')
            },
            success: function(msg) {
                if (msg == 0) {
                    $.messager.alert('Warning','Tanggal sales melebihi tanggal hari ini');
                }else{
                    if(recStatus == 'n'){
                        $.ajax({
                            method: "POST",
                            url: base_url+"InputBatch/cekData/",
                            data: { 
                                store_code          : $("#storeCode").textbox('getValue'),
                                sales_date          : $("#tglSales").datebox('getValue'),
                                sales_flag          : salesFlag,
                                stn_flag            : stnFlag
                            },
                            success: function (hasilCek) {
                                if(hasilCek < 1){
                                    //alert("aman");
                                    var checking;
                                    if(salesFlag == 'Y'){
                                        checking = 1;
                                        } //CLOSE IF SALES FLAG > 0
                                        else{
                                            var r = parseInt($("#totalPenambah").textbox('getValue'));
                                            var k = parseInt($("#totalPengurang").textbox('getValue'));
                                            if (r <= 0 && k <= 0){
                                                checking = 0;
                                            }
                                            else{
                                                checking =1;
                                            //alert("aman");
                                        }
                                    } //CLOSE ELSE SALES FLAG
                                    
                                    if(checking){
                                        if($("#cashPenggantian").textbox('getValue') <= 0 && salesFlag=='Y'){
                                            alert("Cash Penggantian Tidak Boleh Kosong");
                                        }
                                        else{
                                            var cash, tmbh, kurang, total;
                                            cash    = parseInt($("#cashPenggantian").textbox('getValue'));
                                            tmbh    = parseInt($("#totalPenambah").textbox('getValue'));
                                            kurang  = parseInt($("#totalPengurang").textbox('getValue'));
                                            
                                            total   = parseInt(cash+tmbh);
                                            /*if( kurang >= total ){
                                                alert("Input Tidak Valid"); 
                                            }
                                            else{*/
                                                //alert("Setoran Valid");
                                                
                                                //alert(recStatus);
                                                tgl_sales = $('#tglSales').datebox('getValue');
                                                $.ajax({
                                                    method: 'POST',
                                                    url: base_url+"InputBatch/cek_trx_detail",
                                                    data:{
                                                        tambah  : parseInt($("#totalPenambah").textbox('getValue')),
                                                        kurang  : parseInt($("#totalPengurang").textbox('getValue')),
                                                        rec_id  : $('#CDC_REC_ID').val()
                                                    },
                                                    success: function (msg) {
                                                        if (msg > 0) {
                                                            $.ajax({
                                                                method: "POST",
                                                                url: base_url+"InputBatch/praInput",
                                                                data: { 
                                                                    statusxx            : recStatus,
                                                                    receiptID           : $('#CDC_REC_ID').val(),
                                                                    store_code          : storeID,
                                                                    //sales_date            : salesDate.substring(0, 2),
                                                                    sales_date          : tgl_sales,
                                                                    sta_tus             : 'N',
                                                                    flag                : salesFlag,
                                                                    stn                 : stnFlag,
                                                                    sales_amount        : $('#cashPenggantian').textbox('getValue'),
                                                                    mutation_date       : $('#tglMutasi').val(),
                                                                    bank_acc            : $('#bankAcc').val()
                                                                },
                                                                success: function (message) {
                                                                    if ($("#savBatch").attr('batchid') != '') {
                                                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputReject/'+$("#savBatch").attr('batchid'));
                                                                    }else{
                                                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                                                                    }
                                                                    $.messager.show({title: 'Success',msg: message});
                                                                    totalSetor();
                                                                    recStatus = 'n';
                                                                    $('#CDC_REC_ID').val(null);
                                                                    salesDate = null;
                                                                    storeID   = null;
                                                                    salesFlag = 'Y';
                                                                    $("#storeCode").textbox('clear');
                                                                    $("#storeName").textbox('clear');
                                                                    $("#tglSales").datebox('clear');
                                                                    $("#cashPenggantian").numberbox('disable').textbox('setValue','0');
                                                                    $("#totalPenambah").numberbox('disable').textbox('setValue','0');
                                                                    $("#totalPengurang").numberbox('disable').textbox('setValue','0');
                                                                    $("#totalVoucher").numberbox('disable').textbox('setValue','0');
                                                                    $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                                                                    $('#sales_flag').prop('checked', true);
                                                                    $('#stnFlag').prop('checked', false);
                                                                    $('#gtuBatch').linkbutton('enable');
                                                                }
                                                            });
                                                        }else $.messager.show({title: 'Error',msg: 'Terdapat selisih pada Data Penambah atau Pengurang'});
                                                    }
                                                });             
                                            /*}*/ //CLOSE ELSE SETORAN TIDAK VALID
                                        } //CLOSE ELSE ALERT CASH TDK BOLEH KOSONG  
                                    } else{ //CLOSE CHEKING
                                        $.messager.alert('Warning','Input Tidak Valid');
                                    }           
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////          
                                }
                                else{
                                    $.messager.alert('Warning','Data Sudah Ada');
                                }
                            }
                        });
                    }
                    else if(recStatus == 'e'){
                        if($("#cashPenggantian").textbox('getValue') <= 0){
                            alert("Cash Penggantian Tidak Boleh Kosong");
                        }
                        else{
                            var cash, tmbh, kurang, total;
                            cash    = parseInt($("#cashPenggantian").textbox('getValue'));
                            tmbh    = parseInt($("#totalPenambah").textbox('getValue'));
                            kurang  = parseInt($("#totalPengurang").textbox('getValue'));
                            
                            total   = parseInt(cash+tmbh);
                            if( kurang >= total ){
                                $.messager.alert('Warning','Input Tidak Valid');
                            }
                            else{
                                tgl_sales = $('#tglSales').datebox('getValue');
                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/praEdit",
                                    data: { 
                                        statusxx            : recStatus,
                                        receiptID           : $('#CDC_REC_ID').val(),
                                        store_code          : $('#storeCode').textbox('getValue'),
                                        sales_date          : tgl_sales,
                                        sta_tus             : 'n',
                                        flag                : salesFlag,
                                        stn                 : stnFlag,
                                        sales_amount        : $('#cashPenggantian').textbox('getValue'),
                                        mutation_date       : $('#tglMutasi').val(),
                                        bank_acc            : $('#bankAcc').val()
                                    },

                                    success: function (message) {
                                        $.messager.show({title: 'Success',msg: message});
                                        if ($("#savBatch").attr('batchid') != '') {
                                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputReject/'+$("#savBatch").attr('batchid'));
                                        }else{
                                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                                        }
                                        totalSetor();
                                        recStatus = 'n';
                                    }
                                });

                                $('#CDC_REC_ID').val(null);
                                salesDate = null;
                                storeID   = null;
                                $("#storeCode").textbox('clear');
                                $("#storeName").textbox('clear');
                                $("#tglSales").datebox('clear');
                                $("#cashPenggantian").numberbox('disable').textbox('setValue','0');
                                $("#totalPenambah").numberbox('disable').textbox('setValue','0');
                                $("#totalPengurang").numberbox('disable').textbox('setValue','0');
                                $("#totalVoucher").numberbox('disable').textbox('setValue','0');
                                if ($('#savBatch:visible').length) {
                                    $("#scanCode").textbox('disable');
                                }else{
                                    $("#scanCode").textbox('enable').textbox('clear').textbox('textbox').focus();
                                }
                                $('#sales_flag').prop('checked', true);
                                salesFlag = 'Y';
                                $('#stnFlag').prop('checked', false);
                                $('#gtuBatch').linkbutton('enable');    
                            }
                        }                   
                    }
                }
            }
        });
    }
}

/*  
    var checking;
    if(salesFlag > 0){
        checking = 1;
    } //CLOSE IF SALES FLAG > 0
    else{
        var r = parseInt($("#totalPenambah").numberbox('getValue'));
        var k = parseInt($("#totalPengurang").numberbox('getValue'));
        if (r <= 0 && k <= 0){
            checking = 0;
        }
        else{
            checking =1;
            //alert("aman");
        }
    } //CLOSE ELSE SALES FLAG
    
    if(checking){
        if($("#cashPenggantian").numberbox('getValue') <= 0){
            alert("Cash Penggantian Tidak Boleh Kosong");
        }
        else{
            var cash, tmbh, kurang, total;
                cash    = parseInt($("#cashPenggantian").numberbox('getValue'));
                tmbh    = parseInt($("#totalPenambah").numberbox('getValue'));
                kurang  = parseInt($("#totalPengurang").numberbox('getValue'));
            
            total   = parseInt(cash+tmbh);
            if( kurang >= total ){
                alert("Input Tidak Valid"); 
            }
            else{
                //alert("Setoran Valid");
                if(recStatus == 'n'){
                    //alert(recStatus);
                    tgl_sales = $('#tglSales').textbox('getValue');
                    $.ajax({
                      method: "POST",
                      url: base_url+"InputBatch/praInput",
                      data: { 
                                statusxx            : recStatus,
                                receiptID           : $('#CDC_REC_ID').val(),
                                store_code          : storeID,
                                //sales_date            : salesDate.substring(0, 2),
                                sales_date          : tgl_sales.substring(0,2),
                                sta_tus             : 'n',
                                flag                : 1,
                                sales_amount        : $('#cashPenggantian').textbox('getValue'),
                            },
                
                      success: function (message) {
                        $.messager.show({title: 'Success',msg: message});
                        $('#tblTrxReceipts').datagrid('reload');
                        totalSetor();
                        recStatus = 'n';
                      }
                    }); 
                }
                else if(recStatus == 'e'){
                    tgl_sales = $('#tglSales').textbox('getValue');
                    $.ajax({
                      method: "POST",
                      url: base_url+"InputBatch/praEdit",
                      data: { 
                                statusxx            : recStatus,
                                receiptID           : $('#CDC_REC_ID').val(),
                                store_code          : $('#storeCode').textbox('getValue'),
                                sales_date          : tgl_sales.substring(0,2),
                                sta_tus             : 'n',
                                flag                : 1,
                                sales_amount        : $('#cashPenggantian').textbox('getValue'),
                            },
                
                      success: function (message) {
                        $.messager.show({title: 'Success',msg: message});
                        $('#tblTrxReceipts').datagrid('reload');
                        totalSetor();
                        recStatus = 'n';
                      }
                    });         
                }
                                
                $('#CDC_REC_ID').val(null);
                salesDate = null;
                storeID   = null;
                $("#storeCode").textbox('clear');
                $("#storeName").textbox('clear');
                $("#tglSales").datebox('clear');
                $("#cashPenggantian").numberbox('disable').textbox('setValue','0');
                $("#totalPenambah").numberbox('disable').textbox('setValue','0');
                $("#totalPengurang").numberbox('disable').textbox('setValue','0');
                $("#totalVoucher").numberbox('disable').textbox('setValue','0');
                $("#scanCode").textbox('clear').textbox('textbox').focus();
                //location.reload();
                        
            } //CLOSE ELSE SETORAN TIDAK VALID
        } //CLOSE ELSE ALERT CASH TDK BOLEH KOSONG  
    } else{ //CLOSE CHEKING
        alert("Input Tidak Valid");
    }
    
    */  
    
    
///////////////////////// POP UP SIMPAN PENAMBAH /////////////////////////////////  
function simpanPenambah(){
    if(penambahID != null){  //SAVE EDIT
        //alert(penambahID);
        if($('#penambah_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Tambah/updateData",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    penambahID  : penambahID,
                    name        : $('#trxTypePenambah').textbox('getValue'),
                    date        : $('#trxDatePenambah').datebox('getValue'),
                    desc        : $('#descPenambah').textbox('getValue'),
                    amount      : $('#amountPenambah').numberbox('getValue')
                },

                success: function (message) {
                    penambahID = null;  
                    $.messager.show({title: 'Success',msg: message});

                    $('#penambah_form').form('clear');
                //ISI TGL PENAMBAH
                $('#trxDatePenambah').datebox('setValue', tgl.getDate()+'/'+tgl.getMonth()+'/'+tgl.getFullYear() );
                $("#trxTypePenambah").combobox('clear').focus();    
            }
        }); 
        }       
        
    }
    else{  //BUAT BARU
        if($('#penambah_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Tambah/addData",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    name        : $('#trxTypePenambah').textbox('getValue'),
                    date        : $('#trxDatePenambah').datebox('getValue'),
                    desc        : $('#descPenambah').textbox('getValue'),
                    amount      : $('#amountPenambah').textbox('getValue')
                },

                success: function (message) {
                    /*alert($('#descPenambah').textbox('getValue'));*/
                //alert($('#CDC_REC_ID').val());
                $.messager.show({title: 'Success',msg: message});
                
                $('#penambah_form').form('clear');
                //ISI TGL PENAMBAH
                $('#trxDatePenambah').datebox('setValue', tgl.getDate()+'/'+tgl.getMonth()+'/'+tgl.getFullYear() );
                $("#trxTypePenambah").combobox('clear').focus();    
            }
        }); 
        }       
    }

    $('#tblInputPenambah').datagrid('load');
}

function selesaiPenambah(){
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Tambah/getTotal/"+dataId,

        success: function (total) {
        //ISI TOTAL PENAMBAH
        $('#totalPenambah').numberbox('setValue',total);
    }
});     
    
    $('#penambah_dialog').dialog('close');
    $('#totalPengurang').numberbox('textbox').focus();
}   
////////////////////////////////////////////////////////////////////////////    


///////////////////////// POP UP SIMPAN PENGURANG ///////////////////////////////// 
function simpanPengurang(){
    if(pengurangID != null){  //SAVE EDIT
        if($('#penagurang_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Kurang/updateData",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    pengurangID : pengurangID,
                    name        : $('#trxTypePengurang').textbox('getValue'),
                    date        : $('#trxDatePengurang').datebox('getValue'),
                    desc        : $('#descPengurang').textbox('getValue'),
                    amount      : $('#amountPengurang').numberbox('getValue')
                },

                success: function (message) {
                    pengurangID = null;
                    $.messager.show({title: 'Success',msg: message});

                    $('#penagurang_form').form('clear');
                //ISI TGL PENAMBAH
                $('#trxDatePengurang').datebox('setValue', tgl.getDate()+'/'+tgl.getMonth()+'/'+tgl.getFullYear() );
                $("#trxTypePengurang").combobox('clear').focus();   
            }
        }); 
        }
    }
    else{
        if($('#penagurang_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Kurang/addData",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    name        : $('#trxTypePengurang').textbox('getValue'),
                    date        : $('#trxDatePengurang').datebox('getValue'),
                    desc        : $('#descPengurang').textbox('getValue'),
                    amount      : $('#amountPengurang').numberbox('getValue')
                },

                success: function (message) {
                //alert($('#CDC_REC_ID').val());
                $.messager.show({title: 'Success',msg: message});
                
                $('#penagurang_form').form('clear');
                //ISI TGL PENAMBAH
                $('#trxDatePengurang').datebox('setValue', tgl.getDate()+'/'+tgl.getMonth()+'/'+tgl.getFullYear() );
                $("#trxTypePengurang").combobox('clear').focus();   
            }
        }); 
        }
    }
    $('#tblInputPengurang').datagrid('load');
}

function selesaiPengurang(){
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Kurang/getTotal/"+dataId,

        success: function (total) {
        //ISI TOTAL PENAMBAH
        $('#totalPengurang').numberbox('setValue',total);
    }
});     
    
    $('#pengurang_dialog').dialog('close');
    $('#totalVoucher').textbox('textbox').focus();
}   
////////////////////////////////////////////////////////////////////////////    


function btnGenerate(){
    var validate = 0;
    var count = 0;
    var data = $('#tblTrxReceipts').datagrid('getSelections');
    var row = [];
    
    for(var i =0;i<data.length;i++){
        row[i] = data[i].CDC_REC_ID;
        count++;
    }   

    if(count >= 1){     
        $.messager.confirm('Confirm','Input dengan GTU ?',function(x){
            if(x){
                $('#GTU_sent').dialog('open');
                $('#GTU_sent').dialog('center');
                $('#GTU_sent').dialog('setTitle','Pilih GTU');
                $('#pilGTU').linkbutton('disable');
                
                $('#tblSentGTU').datagrid({
                    url:base_url+'input/Trx_GTU/getData',
                    columns:[[
                    {field:'ck',checkbox:true},
                    {field:'CDC_GTU_ID',hidden:true},
                    {field:'CDC_BANK_ID',hidden:true},
                    {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
                    {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                    {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                    formatter:function (value,row,index) {
                        return Intl.NumberFormat('en-US').format(value);
                    }               
                },  
                ]],     
                rownumbers : true, singleSelect:false, selectOnCheck:true, checkOnSelect:false, fitColumns:true,
                onCheck: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }
                },
                onCheckAll: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }               
                },
                onUncheck: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }else if(GTU_selected.length == 0){
                        $('#totalGTU').numberbox('setValue','0');
                        $('#pilGTU').linkbutton('disable');
                    }
                },
                onUncheckAll: function(){
                    $('#totalGTU').numberbox('setValue','0');
                    $('#pilGTU').linkbutton('disable');
                }
                
            });
            } //END IF(x)
            else{       
                $.messager.confirm('Confirm','Apakah anda yakin untuk Submit?',function(a){
                    if(a){
                        $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                            if(b){ 
                            //langsung di validate
                            $("#prog-trans").window('open');
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row2,
                                    validate    : 1,
                                    adaGTU      : 0
                                },

                                success: function (message) {
                                    $("#prog-trans").window('close');
                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                    $('#data_trx_kurset').datagrid('reload');
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }
                        else{
                            //masuk inquiry 
                            $("#prog-trans").window('open');
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row,
                                    validate    : 0,
                                    adaGTU      : 0
                                },

                                success: function (message) {
                                    //location.reload();
                                    $("#prog-trans").window('close');
                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                    $('#data_trx_kurset').datagrid('reload');
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }

                    });

                    }
                });
            }
            
        });
    }else{
        var data_kur = $('#data_trx_kurset').datagrid('getData');
        // YG INI parseInt($("#role-id").val()) == 3 &&
        if ( parseInt(data_kur.total) > 0) {
            $.messager.confirm('Confirm','Input dengan GTU ?',function(x){
                if(x){
                    $('#GTU_sent').dialog('open');
                    $('#GTU_sent').dialog('center');
                    $('#GTU_sent').dialog('setTitle','Pilih GTU');
                    $('#pilGTU').linkbutton('disable');
                    
                    $('#tblSentGTU').datagrid({
                        url:base_url+'input/Trx_GTU/getData',
                        columns:[[
                        {field:'ck',checkbox:true},
                        {field:'CDC_GTU_ID',hidden:true},
                        {field:'CDC_BANK_ID',hidden:true},
                        {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
                        {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                        {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                        formatter:function (value,row,index) {
                            return Intl.NumberFormat('en-US').format(value);
                        }               
                    },  
                    ]],     
                    rownumbers : true, singleSelect:false, selectOnCheck:true, checkOnSelect:false, fitColumns:true,
                    onCheck: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }
                    },
                    onCheckAll: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }               
                    },
                    onUncheck: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }else if(GTU_selected.length == 0){
                            $('#totalGTU').numberbox('setValue','0');
                            $('#pilGTU').linkbutton('disable');
                        }
                    },
                    onUncheckAll: function(){
                        $('#totalGTU').numberbox('setValue','0');
                        $('#pilGTU').linkbutton('disable');
                    }
                    
                });
                } //END IF(x)
                else{       
                    $.messager.confirm('Confirm','Apakah anda yakin untuk Submit?',function(a){
                        if(a){
                            $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                                if(b){ 
                                //langsung di validate
                                $("#prog-trans").window('open');
                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/inputBatch",
                                    data: { 
                                        receiptID   : row,
                                        validate    : 1,
                                        adaGTU      : 0
                                    },

                                    success: function (message) {
                                        $("#prog-trans").window('close');
                                        window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                        $('#data_trx_kurset').datagrid('reload');
                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                                        totalSetor();
                                        $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                    }
                                });
                            }
                            else{
                                //masuk inquiry 
                                $("#prog-trans").window('open');
                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/inputBatch",
                                    data: { 
                                        receiptID   : row,
                                        validate    : 0,
                                        adaGTU      : 0
                                    },

                                    success: function (message) {
                                        //location.reload();
                                        $("#prog-trans").window('close');
                                        window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                        $('#data_trx_kurset').datagrid('reload');
                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                                        totalSetor();
                                        $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                    }
                                });
                            }

                        });

                        }
                    });
                }
            });
        } else {
            $.messager.alert('Alert','Tidak terdapat data Receipts.','info');
        }
    }
}



function btnGenerateShift(){
    var validate = 0;
    var count = 0;
    var jumlah = 0;
    var data = $('#tblTrxReceipts').datagrid('getSelections');
    var row = [];
    var row2 = [];
    
    for(var i =0;i<data.length;i++){
        row[i] = data[i].CDC_SHIFT_REC_ID+'-'+data[i].CDC_REC_ID;
        row2[i] = data[i].CDC_REC_ID;
        count++;
        console.log(row[i]);
    }   

    var uniq = row2.filter(function(itm,i){
        return i == row2.indexOf(itm);
    });

    var uniqlength = uniq.length;
    
    //alert(uniq);

    var num = 0;
    for(var x = 0;x < uniq.length;x++){
        var rcp;
        rcp = uniq[x];

        $.ajax({
            method: 'POST',
            async: false,
            url:base_url+'inputBatch/getTotalDataSelect',
            data:{
                rec_id: parseInt(rcp)
            },
            success: function(r){
                //alert(r);
                jumlah += parseInt(r);
            }
        });
    }
    
if(jumlah === count){
    if(count >= 1){     
        $.messager.confirm('Confirm','Input dengan GTU ?',function(x){
            if(x){
                $('#GTU_sent').dialog('open');
                $('#GTU_sent').dialog('center');
                $('#GTU_sent').dialog('setTitle','Pilih GTU');
                $('#pilGTU').linkbutton('disable');
                
                $('#tblSentGTU').datagrid({
                    url:base_url+'input/Trx_GTU/getData',
                    columns:[[
                    {field:'ck',checkbox:true},
                    {field:'CDC_GTU_ID',hidden:true},
                    {field:'CDC_BANK_ID',hidden:true},
                    {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
                    {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                    {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                    formatter:function (value,row,index) {
                        return Intl.NumberFormat('en-US').format(value);
                    }               
                },  
                ]],     
                rownumbers : true, singleSelect:false, selectOnCheck:true, checkOnSelect:false, fitColumns:true,
                onCheck: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }
                },
                onCheckAll: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }               
                },
                onUncheck: function(){
                    var GTU_selected = $(this).datagrid('getSelections');   
                    if(GTU_selected.length > 0){

                        $.ajax({
                            type: 'POST',
                            url: base_url+'input/Trx_GTU/getTotalAmount',
                            data:{
                                data : GTU_selected
                            },
                            success: function(ttlGTU){
                                $('#totalGTU').numberbox('setValue',ttlGTU);
                                if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                    $('#pilGTU').linkbutton('disable');
                                    $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                }
                                else {
                                    $('#pilGTU').linkbutton('enable');
                                }
                            }
                        });
                    }else if(GTU_selected.length == 0){
                        $('#totalGTU').numberbox('setValue','0');
                        $('#pilGTU').linkbutton('disable');
                    }
                },
                onUncheckAll: function(){
                    $('#totalGTU').numberbox('setValue','0');
                    $('#pilGTU').linkbutton('disable');
                }
                
            });
            } //END IF(x)
            else{       
                $.messager.confirm('Confirm','Apakah anda yakin untuk Submit?',function(a){
                    if(a){
                        $.ajax({
                            method: "POST",
                            async:false,
                            url: base_url+"InputBatch/Pindah_data_shift",
                            data:{
                                receiptID : row
                            },
                            success:function(message){
                                $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                                        if(b){ 
                                        //langsung di validate
                                        $("#prog-trans").window('open');
                                        $.ajax({
                                            method: "POST",
                                            url: base_url+"InputBatch/inputBatch",
                                            data: { 
                                                receiptID   : row2,
                                                validate    : 1,
                                                adaGTU      : 0
                                            },

                                            success: function (message) {
                                                $.ajax({
                                                    method: "POST",
                                                    url: base_url+"InputBatch/Update_Receipt_Shift",
                                                    data: {
                                                        receiptID : row,
                                                        validate : 1
                                                    }
                                                });

                                                $("#prog-trans").window('close');
                                                window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                                $('#data_trx_kurset').datagrid('reload');
                                                $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                                totalSetor();
                                                $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                            }
                                        });
                                    }
                                    else{
                                        //masuk inquiry 
                                        $("#prog-trans").window('open');
                                        $.ajax({
                                            method: "POST",
                                            url: base_url+"InputBatch/inputBatch",
                                            data: { 
                                                receiptID   : row2,
                                                validate    : 0,
                                                adaGTU      : 0
                                            },

                                            success: function (message) {
                                                //location.reload();
                                                $.ajax({
                                                    method: "POST",
                                                    url: base_url+"InputBatch/Update_Receipt_Shift",
                                                    data: {
                                                        receiptID : row,
                                                        validate : 0
                                                    }
                                                });

                                                $("#prog-trans").window('close');
                                                window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                                $('#data_trx_kurset').datagrid('reload');
                                                $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                                totalSetor();
                                                $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                            }
                                        });
                                    }

                                });
                            }
                        });

                        /*$.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                            if(b){ 
                            //langsung di validate
                            $("#prog-trans").window('open');
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row2,
                                    validate    : 1,
                                    adaGTU      : 0
                                },

                                success: function (message) {
                                    $.ajax({
                                        method: "POST",
                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                        data: {
                                            receiptID : row,
                                            validate : 1
                                        }
                                    });

                                    $("#prog-trans").window('close');
                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                    $('#data_trx_kurset').datagrid('reload');
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }
                        else{
                            //masuk inquiry 
                            $("#prog-trans").window('open');
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row2,
                                    validate    : 0,
                                    adaGTU      : 0
                                },

                                success: function (message) {
                                    //location.reload();
                                    $.ajax({
                                        method: "POST",
                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                        data: {
                                            receiptID : row,
                                            validate : 0
                                        }
                                    });

                                    $("#prog-trans").window('close');
                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                    $('#data_trx_kurset').datagrid('reload');
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }

                    });*/

                    }
                });
            }
            
        });
    }else{
        var data_kur = $('#data_trx_kurset').datagrid('getData');
        //YG INI parseInt($("#role-id").val()) == 3 && 
        if (parseInt(data_kur.total) > 0) {
            $.messager.confirm('Confirm','Input dengan GTU ?',function(x){
                if(x){
                    $('#GTU_sent').dialog('open');
                    $('#GTU_sent').dialog('center');
                    $('#GTU_sent').dialog('setTitle','Pilih GTU');
                    $('#pilGTU').linkbutton('disable');
                    
                    $('#tblSentGTU').datagrid({
                        url:base_url+'input/Trx_GTU/getData',
                        columns:[[
                        {field:'ck',checkbox:true},
                        {field:'CDC_GTU_ID',hidden:true},
                        {field:'CDC_BANK_ID',hidden:true},
                        {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
                        {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                        {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                        formatter:function (value,row,index) {
                            return Intl.NumberFormat('en-US').format(value);
                        }               
                    },  
                    ]],     
                    rownumbers : true, singleSelect:false, selectOnCheck:true, checkOnSelect:false, fitColumns:true,
                    onCheck: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }
                    },
                    onCheckAll: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }               
                    },
                    onUncheck: function(){
                        var GTU_selected = $(this).datagrid('getSelections');   
                        if(GTU_selected.length > 0){

                            $.ajax({
                                type: 'POST',
                                url: base_url+'input/Trx_GTU/getTotalAmount',
                                data:{
                                    data : GTU_selected
                                },
                                success: function(ttlGTU){
                                    $('#totalGTU').numberbox('setValue',ttlGTU);
                                    if (($('#totalSetor').numberbox('getValue')-ttlGTU) < 0 || ($('#totalSetor').numberbox('getValue')-ttlGTU) == 0) {
                                        $('#pilGTU').linkbutton('disable');
                                        $.messager.alert('Warning','Total GTU tidak boleh sama atau melebihi total setor.');
                                    }
                                    else {
                                        $('#pilGTU').linkbutton('enable');
                                    }
                                }
                            });
                        }else if(GTU_selected.length == 0){
                            $('#totalGTU').numberbox('setValue','0');
                            $('#pilGTU').linkbutton('disable');
                        }
                    },
                    onUncheckAll: function(){
                        $('#totalGTU').numberbox('setValue','0');
                        $('#pilGTU').linkbutton('disable');
                    }
                    
                });
                } //END IF(x)
                else{       
                    $.messager.confirm('Confirm','Apakah anda yakin untuk Submit?',function(a){
                        if(a){
                            $.ajax({
                                method: "POST",
                                async:false,
                                url: base_url+"InputBatch/Pindah_data_shift",
                                data:{
                                    receiptID : row
                                },
                                success:function(message){
                                    $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                                            if(b){ 
                                            //langsung di validate
                                            $("#prog-trans").window('open');
                                            $.ajax({
                                                method: "POST",
                                                url: base_url+"InputBatch/inputBatch",
                                                data: { 
                                                    receiptID   : row2,
                                                    validate    : 1,
                                                    adaGTU      : 0
                                                },

                                                success: function (message) {
                                                    $.ajax({
                                                    method: "POST",
                                                    url: base_url+"InputBatch/Update_Receipt_Shift",
                                                    data: {
                                                        receiptID : row,
                                                        validate : 1
                                                    }
                                                });

                                                    $("#prog-trans").window('close');
                                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                                    $('#data_trx_kurset').datagrid('reload');
                                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                                    totalSetor();
                                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                                }
                                            });
                                        }
                                        else{
                                            //masuk inquiry 
                                            $("#prog-trans").window('open');
                                            $.ajax({
                                                method: "POST",
                                                url: base_url+"InputBatch/inputBatch",
                                                data: { 
                                                    receiptID   : row2,
                                                    validate    : 0,
                                                    adaGTU      : 0
                                                },

                                                success: function (message) {
                                                    //location.reload();
                                                    $.ajax({
                                                        method: "POST",
                                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                                        data: {
                                                            receiptID : row,
                                                            validate : 0
                                                        }
                                                    });

                                                    $("#prog-trans").window('close');
                                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                                    $('#data_trx_kurset').datagrid('reload');
                                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                                    totalSetor();
                                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                                }
                                            });
                                        }

                                    });
                                }
                            });

                            /*$.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                                if(b){ 
                                //langsung di validate
                                $("#prog-trans").window('open');
                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/inputBatch",
                                    data: { 
                                        receiptID   : row2,
                                        validate    : 1,
                                        adaGTU      : 0
                                    },

                                    success: function (message) {
                                        $.ajax({
                                        method: "POST",
                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                        data: {
                                            receiptID : row,
                                            validate : 1
                                        }
                                    });

                                        $("#prog-trans").window('close');
                                        window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                        $('#data_trx_kurset').datagrid('reload');
                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                        totalSetor();
                                        $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                    }
                                });
                            }
                            else{
                                //masuk inquiry 
                                $("#prog-trans").window('open');
                                $.ajax({
                                    method: "POST",
                                    url: base_url+"InputBatch/inputBatch",
                                    data: { 
                                        receiptID   : row2,
                                        validate    : 0,
                                        adaGTU      : 0
                                    },

                                    success: function (message) {
                                        //location.reload();
                                        $.ajax({
                                            method: "POST",
                                            url: base_url+"InputBatch/Update_Receipt_Shift",
                                            data: {
                                                receiptID : row,
                                                validate : 0
                                            }
                                        });

                                        $("#prog-trans").window('close');
                                        window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batch Receipts", "width=1000,height=600,scrollbars=yes");
                                        $('#data_trx_kurset').datagrid('reload');
                                        $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                        totalSetor();
                                        $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                    }
                                });
                            }

                        });*/

                        }
                    });
                }
            });
            } else {
                $.messager.alert('Alert','Tidak terdapat data Receipts.','info');
            }
    }
    }else{
            $.messager.alert('Alert','Jumlah Data tidak sesuai.count: '+ count+' Total:'+jumlah ,'info');
    }
}



function btnPrint(){
    var data = $('#tblTrxReceipts').datagrid('getSelections');
    var row = [];
    var count = 0;
    
    for(var i =0;i<data.length;i++){
        row[i] = data[i].CDC_REC_ID;
        count++;
    }   

    if(count >= 1){ 
        window.open(base_url+'InputBatch/printReceipts/'+row, "Report Receipts", "width=1000,height=600,scrollbars=yes");
/*  
        $.ajax({
          method: "POST",
          url: base_url+"InputBatch/printReceipts",
          data: { 
                    receiptID   : row,
                },
    
          success: function (message) {
            //location.reload();
            $.messager.show({title: 'Success',msg:"Print Data"});
          }
        });
        */  
    }else{
        $.messager.alert('Alert','Data yang dipilih TIDAK ADA.','info');
    }   
}


function btnGTU(){
    GTU_status = 'add';
    $('#GTU_dialog').dialog('open');
    $('#GTU_dialog').dialog('center');
    $('#GTU_dialog').dialog('setTitle','Input GTU');
    
    $('#GTU_form').form('clear');
    //$('#GtuId').textbox('hidden');
    $('#td_GtuId').attr('style','display:none');
    
    $('#bankName').combobox({
        url: base_url+'input/Trx_GTU/getBank',
        valueField  : 'BANK_ID',
        textField   : 'BANK_NAME' 
    });
    $('#bankName').combobox('setValue',1);
    //$('#bankName').combobox('setValue','2');

/*  
    $('#bankName').combobox({
        onChange: function(value){
            var bank_account_id  = $('#bankName').datebox('getValue');
            
            $.ajax({
              method: "POST",
              url: base_url+"input/Trx_GTU/getBankNum",
              data: { 
                        bank_account_id : bank_account_id,
                    },
        
              success: function (bankNum) {
                //location.reload();
                $('#bankAccountNum').textbox('setText', bankNum);
              }
            });         
            
        }
    });
    */
    if (!$('#gtuBatch').attr('batchid')) {
        $('#tblInputGTU').datagrid({
            url:base_url+'input/Trx_GTU/getData/'+dataId,
            columns:[[
            {field:'CDC_GTU_ID',hidden:true},
            {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
            {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                //{field:'BANK_ACCOUNT_NUM',title:'Bank Account Num',width:150,align:'center'},
                {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
            },

            {field: 'BUTTON_EDIT', title: '' ,width:40 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnEditGTU" value="Edit" onClick="editGTU('+row.CDC_GTU_ID+')"> ';
                return col;
            }},

            {field: 'BUTTON_DELETE', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnDelGTU" value="Delete" onClick="delGTU('+row.CDC_GTU_ID+')"> ';
                return col;
            }}          
            ]],     
            rownumbers : true, singleSelect:true, fitColumns:true,pageSize: 10
        });
    }else{
        $('#btnSaveGTU').attr('batchid',$('#gtuBatch').attr('batchid'));
        $('#tblInputGTU').datagrid({
            url:base_url+'input/Trx_GTU/getDataGTUReject/'+$('#gtuBatch').attr('batchid'),
            columns:[[
            {field:'CDC_GTU_ID',hidden:true},
            {field:'CDC_GTU_NUMBER',title:'Check Num',width:150,align:'center'},
            {field:'BANK_NAME',title:'Bank Name',width:150,align:'center'},
                //{field:'BANK_ACCOUNT_NUM',title:'Bank Account Num',width:150,align:'center'},
                {field:'CDC_GTU_AMOUNT',title:'Check Amount',width:150,align:'center',
                formatter:function (value,row,index) {
                    return Intl.NumberFormat('en-US').format(value);
                }           
            },

            {field: 'BUTTON_EDIT', title: '' ,width:40 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnEditGTU" value="Edit" onClick="editGTU('+row.CDC_GTU_ID+')"> ';
                return col;
            }},

            {field: 'BUTTON_DELETE', title: '' ,width:50 ,align:'center', formatter: function (value, row, index) {
                var col;
                col = ' <input type="button" id="btnDelGTU" value="Delete" onClick="delGTU('+row.CDC_GTU_ID+')"> ';
                return col;
            }}          
            ]],     
            rownumbers : true, singleSelect:true, fitColumns:true,pageSize: 10
        });
    }

}


function simpanGTU(){
    if(GTU_status == 'add'){
        if (!$('#btnSaveGTU').attr('batchid')) {
            if($('#GTU_form').form('validate') == true){
                $.ajax({
                    method: "POST",
                    url: base_url+"input/Trx_GTU/addData",
                    data: { 
                        check_num   : $('#checkNum').textbox('getValue'),
                        bank_id     : $('#bankName').combobox('getValue'),
                        check_amount: $('#checkAmount').numberbox('getValue')
                    },
                    success: function (message) {
                        $.messager.show({title: 'Success',msg: message});

                        $('#GTU_form').form('clear');
                        $('#tblInputGTU').datagrid('reload');
                        $("#checkNum").textbox('clear').focus();    
                    }
                }); 
            }
        }
        else{
            if($('#GTU_form').form('validate') == true){
                $.ajax({
                    method: "POST",
                    url: base_url+"input/Trx_GTU/addDataGTUReject",
                    data: { 
                        batch_id    : $('#btnSaveGTU').attr('batchid'),
                        check_num   : $('#checkNum').textbox('getValue'),
                        bank_id     : $('#bankName').combobox('getValue'),
                        check_amount: $('#checkAmount').numberbox('getValue')
                    },

                    success: function (message) {
                        $.messager.show({title: 'Success',msg: message});

                        $('#GTU_form').form('clear');
                        $('#tblInputGTU').datagrid('reload');
                        $("#checkNum").textbox('clear').focus();    
                    }
                }); 
            }
        }
    }
    if(GTU_status == 'edit'){
        if($('#GTU_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_GTU/updateData",
                data :{
                    check_num       : $('#checkNum').textbox('getValue'),
                    bank_id         : $('#bankName').combobox('getValue'),
                    check_amount    : $('#checkAmount').numberbox('getValue'),
                    gtu_id          : $('#GtuId').textbox('getValue')
                },
                success: function (message){
                    $.messager.show({title: 'Success', msg: message});
                    
                    $('#GTU_form').form('clear');
                    $('#tblInputGTU').datagrid('reload');
                    $("#checkNum").textbox('clear').focus();
                }
            });
        }
    }
    GTU_status = 'add';
    totalGTUInput();
}

function selesaiGTU(){
    $('#GTU_form').form('clear');
    $('#GTU_dialog').dialog('close');
    totalGTUInput();
}

function editGTU(GTU_id){
    GTU_status = 'edit';
    //alert(GTU_id);
    $.ajax({
        method: "POST",
        url:base_url+"input/Trx_GTU/getGTU_detail",
        data:{
            GTU_id : GTU_id
        },
        success: function(result){
            var data = JSON.parse(result);
            $('#GtuId').textbox('setValue',data['CDC_GTU_ID']);
            $('#checkNum').textbox('setValue',data['CDC_GTU_NUMBER']);
            $('#bankName').combobox('setValue',data['BANK_ID']);
            //$('#bankAccountNum').textbox('setValue',data['BANK_ACCOUNT_NUM']);
            $('#checkAmount').numberbox('setValue',data['CDC_GTU_AMOUNT']);
        }
    });
}


function delGTU(GTU_id){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){ 
            //alert(tmbhId);
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_GTU/deleteData",
                data: { 
                    dataId : GTU_id,    
                },

                success: function (message) {
                    $.messager.show({title: 'Success',msg: message});
                    $('#tblInputGTU').datagrid('reload');
                }
            });
        }
    });
}

function btnSentGTUShift(){
    var data = $('#tblTrxReceipts').datagrid('getSelections');
    var row = [];
    var row3 = [];

    var totGTU = $('#totalGTU').textbox('getValue'); 
    var jmlh = parseInt(0);
    var storeType;
    
    for(var i =0;i<data.length;i++){
        storeType = data[i].STORE_CODE;
        row[i] = data[i].CDC_SHIFT_REC_ID+'-'+data[i].CDC_REC_ID;
        row3[i] = data[i].CDC_REC_ID;
        
        //HITUNG TOTAL SETOR REGULER SAJA
        if( storeType.substring(0,1) == 'T' ){
            jmlh = parseInt(jmlh) + parseInt(data[i].ACTUAL_AMOUNT);            
        }
    }       
    
    var data2 = $('#tblSentGTU').datagrid('getSelections');
    var row2    = [];
    var row_bank_id = [];
    for(var i =0;i<data2.length;i++){
        row2[i] = data2[i].CDC_GTU_ID;
        row_bank_id[i] = data2[i].CDC_BANK_ID;
    }
    /*alert(data2[0].CDC_BANK_ID);
    exit;*/
    
    if( parseInt(jmlh) > parseInt(totGTU) ){
        //alert(jmlh + ' > ' + totGTU); 
        $.messager.confirm('Confirm','Apakah anda yakin Submit data tersebut?',function(a){
            if(a){
                $.ajax({
                            method: "POST",
                            async:false,
                            url: base_url+"InputBatch/Pindah_data_shift",
                            data:{
                                receiptID : row
                   },
                   success:function(message){
                        $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                            if(b){ 
                            //langsung di validate
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row3,
                                    validate    : 1,
                                    gtuID       : row2,
                                    adaGTU      : 1,
                                    bankID      : row_bank_id
                                },

                                success: function (message) {
                                    //location.reload();
                                    $.ajax({
                                        method: "POST",
                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                        data: {
                                            receiptID : row,
                                            validate : 1
                                        }
                                    });


                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batches", "width=1000,height=600,scrollbars=yes");
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                    $('#GTU_sent').dialog('close');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }
                        else{
                            //masuk inquiry 
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/inputBatch",
                                data: { 
                                    receiptID   : row3,
                                    validate    : 0,
                                    gtuID       : row2,
                                    adaGTU      : 1,
                                    bankID      : row_bank_id
                                },

                                success: function (message) {
                                    //location.reload();
                                    $.ajax({
                                        method: "POST",
                                        url: base_url+"InputBatch/Update_Receipt_Shift",
                                        data: {
                                            receiptID : row,
                                            validate : 0
                                        }
                                    });

                                    window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Summary Collect", "width=1000,height=600,scrollbars=yes");
                                    $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                                    $('#GTU_sent').dialog('close');
                                    totalSetor();
                                    $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                                }
                            });
                        }

                    });
                   }
                });

                /*$.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                    if(b){ 
                    //langsung di validate
                    $.ajax({
                        method: "POST",
                        url: base_url+"InputBatch/inputBatch",
                        data: { 
                            receiptID   : row3,
                            validate    : 1,
                            gtuID       : row2,
                            adaGTU      : 1,
                            bankID      : row_bank_id
                        },

                        success: function (message) {
                            //location.reload();
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/Update_Receipt_Shift",
                                data: {
                                    receiptID : row,
                                    validate : 1
                                }
                            });


                            window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batches", "width=1000,height=600,scrollbars=yes");
                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                            $('#GTU_sent').dialog('close');
                            totalSetor();
                            $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                        }
                    });
                }
                else{
                    //masuk inquiry 
                    $.ajax({
                        method: "POST",
                        url: base_url+"InputBatch/inputBatch",
                        data: { 
                            receiptID   : row3,
                            validate    : 0,
                            gtuID       : row2,
                            adaGTU      : 1,
                            bankID      : row_bank_id
                        },

                        success: function (message) {
                            //location.reload();
                            $.ajax({
                                method: "POST",
                                url: base_url+"InputBatch/Update_Receipt_Shift",
                                data: {
                                    receiptID : row,
                                    validate : 0
                                }
                            });

                            window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Summary Collect", "width=1000,height=600,scrollbars=yes");
                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInputShift');
                            $('#GTU_sent').dialog('close');
                            totalSetor();
                            $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                        }
                    });
                }

            });*/

            }
        });             
    }else{
        alert('#error : Total GTU Melebihi Setoran');   
        //alert(jmlh + ' > ' + totGTU);
    }
}



function btnSentGTU(){
    var data = $('#tblTrxReceipts').datagrid('getSelections');
    var row = [];

    var totGTU = $('#totalGTU').textbox('getValue'); 
    var jmlh = parseInt(0);
    var storeType;
    
    for(var i =0;i<data.length;i++){
        storeType = data[i].STORE_CODE;
        row[i] = data[i].CDC_REC_ID;
        
        //HITUNG TOTAL SETOR REGULER SAJA
        if( storeType.substring(0,1) == 'T' ){
            jmlh = parseInt(jmlh) + parseInt(data[i].ACTUAL_AMOUNT);            
        }
    }       
    
    var data2 = $('#tblSentGTU').datagrid('getSelections');
    var row2    = [];
    var row_bank_id = [];
    for(var i =0;i<data2.length;i++){
        row2[i] = data2[i].CDC_GTU_ID;
        row_bank_id[i] = data2[i].CDC_BANK_ID;
    }
    /*alert(data2[0].CDC_BANK_ID);
    exit;*/
    
    if( parseInt(jmlh) > parseInt(totGTU) ){
        //alert(jmlh + ' > ' + totGTU); 
        $.messager.confirm('Confirm','Apakah anda yakin Submit data tersebut?',function(a){
            if(a){
                $.messager.confirm('Validate','Apakah ingin langsung dikirim ke VALIDATE ?',function(b){
                    if(b){ 
                    //langsung di validate
                    $.ajax({
                        method: "POST",
                        url: base_url+"InputBatch/inputBatch",
                        data: { 
                            receiptID   : row,
                            validate    : 1,
                            gtuID       : row2,
                            adaGTU      : 1,
                            bankID      : row_bank_id
                        },

                        success: function (message) {
                            //location.reload();
                            window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Batches", "width=1000,height=600,scrollbars=yes");
                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                            $('#GTU_sent').dialog('close');
                            totalSetor();
                            $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                        }
                    });
                }
                else{
                    //masuk inquiry 
                    $.ajax({
                        method: "POST",
                        url: base_url+"InputBatch/inputBatch",
                        data: { 
                            receiptID   : row,
                            validate    : 0,
                            gtuID       : row2,
                            adaGTU      : 1,
                            bankID      : row_bank_id
                        },

                        success: function (message) {
                            //location.reload();
                            window.open(base_url+'InputBatch/printBatch/'+message+'/P', "Report Summary Collect", "width=1000,height=600,scrollbars=yes");
                            $('#tblTrxReceipts').datagrid('reload', base_url+'InputBatch/getPraInput');
                            $('#GTU_sent').dialog('close');
                            totalSetor();
                            $.messager.show({title: 'Success',msg: 'Generate Batch Success'});
                        }
                    });
                }

            });

            }
        });             
    }else{
        alert('#error : Total GTU Melebihi Setoran');   
        //alert(jmlh + ' > ' + totGTU);
    }
}


function simpanVoucher(){
    if(voucherID != null){  //SAVE EDIT
        //alert(penambahID);
        if($('#voucher_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/updateData",
                data: { 
                    voucherID   : voucherID,
                    receiptID   : $('#CDC_REC_ID').val(),
                    num         : $('#voucherNum').textbox('getValue'),
                    date        : $('#voucherDate').datebox('getValue'),
                    desc        : $('#voucherDesc').textbox('getValue'),
                    amount      : $('#voucherAmount').numberbox('getValue')
                },

                success: function (message) {
                    voucherID = null;  
                    $.messager.show({title: 'Success',msg: message});

                    $('#voucher_form').form('clear');
                    $('#voucherDate').datebox('setValue', tgl.getDate()+'-'+tgl.getMonth()+'-'+tgl.getFullYear() );
                    $("#voucherNum").textbox('clear').focus();  
                }
            }); 
        }       
        
    }
    else{  //BUAT BARU
        if($('#voucher_form').form('validate') == true){
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/addData",
                data: { 
                    receiptID   : $('#CDC_REC_ID').val(),
                    num         : $('#voucherNum').textbox('getValue'),
                    date        : $('#voucherDate').datebox('getValue'),
                    desc        : $('#voucherDesc').textbox('getValue'),
                    amount      : $('#voucherAmount').numberbox('getValue')
                },

                success: function (message) {
                //alert($('#CDC_REC_ID').val());
                $.messager.show({title: 'Success',msg: message});
                
                $('#voucher_form').form('clear');
                $('#voucherDate').datebox('setValue', tgl.getDate()+'-'+tgl.getMonth()+'-'+tgl.getFullYear() );
                $("#voucherNum").textbox('clear').focus();  
            }
        }); 
        }       
    }

    $('#tblInputVoucher').datagrid('load');
}


function selesaiVoucher(){
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Voucher/getTotal/"+dataId,

        success: function (total) {
        //ISI TOTAL PENAMBAH
        $('#totalVoucher').numberbox('setValue',total);
    }
});     
    
    $('#voucher_dialog').dialog('close');
}   


function editVoucher(voucherId){
    //alert(tmbhId);
    voucherID = voucherId;
    
    $.ajax({
        method: "POST",
        url: base_url+"input/Trx_Voucher/getDataDetail/"+voucherId,

        success: function (rows) {
            var data = JSON.parse(rows);
          //alert();
          var trx_date = data['TRX_VOUCHER_DATE'].substring(8,10)+'-'+data['TRX_VOUCHER_DATE'].substring(5,7)+'-'+data['TRX_VOUCHER_DATE'].substring(0,4);
          $('#voucherNum').textbox('setValue', data['TRX_VOUCHER_NUM']);
          $('#voucherDate').datebox('setValue', trx_date);
          $('#voucherDesc').textbox('setValue', data['TRX_VOUCHER_DESC']);
          $('#voucherAmount').numberbox('setValue', data['TRX_VOUCHER_AMOUNT']);
        }
    }); 
}


function delVoucher(voucherId){
    $.messager.confirm('Confirm','Apakah anda yakin untuk menghapus data ini?',function(r){
        if (r){ 
            //alert(tmbhId);
            $.ajax({
                method: "POST",
                url: base_url+"input/Trx_Voucher/deleteData",
                data:{
                    id : voucherId
                },

                success: function (a) {
                    $('#tblInputVoucher').datagrid('reload');

                    $('#voucher_form').form('clear');
                    $('#voucherDate').datebox({
                        formatter : function(date){
                            var y = date.getFullYear();
                            var m = date.getMonth()+1;
                            var d = date.getDate();
                            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
                        },
                        parser : function(s){
                            if (!s) return new Date();
                            var ss = s.split('-');
                            var y = parseInt(ss[0],10);
                            var m = parseInt(ss[1],10);
                            var d = parseInt(ss[2],10);
                            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                                return new Date(d,m-1,y);
                            } else {
                                return new Date();
                            }
                        }
                    });
                //ISI TGL PENAMBAH
                $('#voucherDate').datebox('setValue', 'current' );
                $("#voucherNum").textbox('clear').focus();          
                
            }
        });
        }
    });
}

function btnSaveBatch() {
    $.ajax({
        method: "POST",
        url: base_url+'InputBatch/cek_batch_type/'+$('#savBatch').attr('batchid'),
        success: function(group_count) {
            if (group_count > 1) {
                $.messager.alert('Warning','Terdapat tipe batch yang berbeda dari data ini.');
            }
            else{
                $.ajax({
                    method: "POST",
                    url: base_url+"InputBatch/resubmit_batch/",
                    data:{
                        batch_id : $('#savBatch').attr('batchid')
                    },
                    success: function (batch_number) {
                        $.messager.show({
                            title:'Caution',
                            msg:'Batch '+batch_number+' Berhasil Di Submit Ulang.',
                            timeout:1000,
                            showType:'show',
                            style:{
                                right:'',
                                top:document.body.scrollTop+document.documentElement.scrollTop,
                                bottom:''
                            }
                        });
                        setInterval(function(){ location.reload(); }, 1500);
                    }
                });
            }
        }
    });
}