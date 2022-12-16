function format_combo_batch_num(row){
  var s = '<span style="font-weight:bold">' + row.CDC_BATCH_NUMBER + '</span><br/>' +
      '<span style="color:#888">' + row.desc + '</span>' +
      '<br> <span style="color:#888">' + row.tgl + '</span>';
  return s;
}

$(document).ready(function() {
   var base_url_rep = '/cdc/Report/';
   $('#rep_sales_toko_idm').window('close');
   $('#req_summary_collect').window('close');
   $('#req_trend_collection').window('close');
   $('#req_monitoring_voucher').window('close');
   $("#req_diff_journal").window('close');
   $('#rep_monitoring_kurang_setor').window('close');
   $('#rep_monitoring_absensi_denom_toko_idm').window('close');
   $("#form_detail_plus_minus").window('close');
   $("#form_print_report").window('close');
   $("#rep_sales_region").window('close');
   $('#rep_penerimaan_sales_detil').window('close');
   $('#rep_penerimaan_sales_per_cbg').window('close');
   $('#rep_sales_toko').window('close');
   $('#rep_pending_setor_toko').window('close');

   $('#start_date_dj').datebox({
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
    
  $('#end_date_dj').datebox({
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

   $('#start_date_absensi').datebox({
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
    
  $('#end_date_absensi').datebox({
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
  $('#start_date_absensi').datebox({
      onChange:  function(newValue,oldValue) {
        var end_date=$('#end_date_pst').datebox('getValue');
        if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });


  $('#end_date_absensi').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_absensi').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });

  
  $('#report_sales_toko_idm').click(function(event) {
      event.preventDefault();
      $('#absensi_branch').combobox('setValue','');
      $('#start_date_absensi').datebox('setValue','');
    $('#end_date_absensi').datebox('setValue','');
    $('#absensi_toko').combobox('setValue','');
   
      $('#rep_sales_toko_idm').window('open');


  });


  
  $('#report_receipt_register').click(function(event) {
    event.preventDefault();
    $('#receipt_register_branch').combobox('select', $("#receipt_register_branch").attr('branch-id'));
        $('#receipt_register_dc_code').combobox('select', $("#receipt_register_dc_code").attr('dc-code'));

    /*$('#combo1_receipt_register').combobox({
       url:base_url+'Report/get_receipt_register_toko',
       valueField:'STORE_CODE',
       textField:'STORE',
       formatter: format_combo_receipt_register1
    }); 
    
    $('#combo2_receipt_register').combobox({
       url:base_url+'Report/get_receipt_register_toko',
       valueField:'STORE_CODE',
       textField:'STORE',
       formatter: format_combo_receipt_register2
    });*/   
    
    $('#date1_receipt_register').datebox({
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
    
    $('#date2_receipt_register').datebox({
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
        
    $('#req_receipt_register').window('open');    
  })

  // $('#absensi_branch').combobox({
  //   onChange: function (value) {
  //     $('#absensi_toko').combobox({
  //       url: base_url+'Report/choose_store/'+value,
  //       valueField:'STORE_CODE',
  //       textField:'STORE'
  //     });
  //   }
  // });


  $('#sub_absensi').click(function(event){
     var absensi_branch  = $('#absensi_branch').combobox('getValue');
     var start_date = $('#start_date_absensi').datebox('getValue');
     var end_date = $('#end_date_absensi').datebox('getValue');
     var toko=$('#absensi_toko').combobox('getValue');
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');
      var tampilan_cetak=$('#tampilan_cetak').combobox('getValue');
      var tglawal = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      var tglakhir = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];

     if(tampilan_cetak=='per Toko'){
      tampilan_cetak='perToko';
     }else if(tampilan_cetak=='per Cabang'){
      tampilan_cetak='perCabang';

     }
     if((absensi_branch=='' && toko=='' )|| start_date=='' || end_date=='' || tampilan_cetak==''){

      $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap. ');
     }else{
        if(tglawal<=tglakhir){
            if(tgl1[1]==tgl2[1]){



                if(absensi_branch==''){
                    absensi_branch=0;
                 }
                if(toko==''){
                  toko=0;
                }
                 $.ajax({
                  method: "POST",
                  url: base_url+'report/cek_report/',
                  data :{
                        branch_id : absensi_branch,
                        tgl_awal:start_date,
                        tgl_akhir:end_date,
                        store_code:toko


                  },
                  success: function (total) {
                                    
                    if(total>0){
                         window.open(base_url+'Report/laporan_sales_pertoko_shift/'+absensi_branch+'/'+toko+'/'+start_date+'/'+end_date+'/'+tampilan_cetak, "Laporan Absens Sales Toko Idm per Shift", "width=600,height=600,scrollbars=yes");
                
                    }else{
                       $.messager.progress('close');
                      $.messager.alert('Warning','Tidak ada data untuk dicetak.');         
                    }
                  }
              });

                

          
            }else{
              $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

            }
        }else{
            $.messager.alert('Warning','Tanggal awal harus lebih kecil dari tanggal akhir');
        }
     
       
     }     

   });


   $('#reset_absensi').click(function(event){
      $('#absensi_branch').combobox('enable');
      $('#absensi_toko').combobox('enable');
      $('#tampilan_cetak').combobox('setValue','');
       $('#absensi_toko').combobox('setValue','');
      $('#absensi_branch').combobox('select','');
      $('#start_date_absensi').datebox('setValue','');
      $('#end_date_absensi').datebox('setValue','');
      $('#absensi_toko').combobox('select','');
       

   });
   $('#report_monitoring_voucher').click(function(event) {
      event.preventDefault();
      $('#monitoring_voucher_branch').combobox('select', $("#monitoring_voucher_branch").attr('branch-id'));
      $('#monitoring_voucher_dc_code').combobox('select', $("#monitoring_voucher_dc_code").attr('dc-code'));
      /*$('#combo_batch_num').combobox({
        url:base_url+'Report/get_batch_num',
        valueField:'CDC_BATCH_NUMBER',
        textField:'CDC_BATCH_NUMBER',
        formatter: format_combo_batch_num
      });*/
    
  $('#date1_monitoring_voucher').datebox({
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
    
  $('#date2_monitoring_voucher').datebox({
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

  $('#start_date_dj').datebox({
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
    
  $('#end_date_dj').datebox({
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
    
      $('#req_monitoring_voucher').window('open');
   });  
    
   
   $('#sub_monitoring_voucher').click(function(event){
     var num  = $('#combo_batch_num').combobox('getValue');
     var from = $('#date1_monitoring_voucher').datebox('getValue');
     var to = $('#date2_monitoring_voucher').datebox('getValue');
     if(num == ''){
        num = 'all';
     }
     
     if(from == '' && to != '' || to == '' && from != ''){
      $.messager.alert('Warning','Error : From & To Date Harus Diisi ');
     }else{
       if( num == 'all' && from == '' || num == 'all' && to   == ''){
         $.messager.alert('Warning','Error : Bacth Number atau Tanggal Harus Diisi');
       }else{
        //alert(num+'\n'+from+'\n'+to);
        window.open(base_url+'Report/print_monitoring_voucher/'+num+'/'+from+'/'+to+'/'+$("#monitoring_voucher_branch").combobox('getValue')+'/'+$("#monitoring_voucher_dc_code").combobox('getValue'), "Report Monitoring Voucher", "width=600,height=600,scrollbars=yes");
       }       
     }     

   });

  
  $('#report_receipt_register').click(function(event) {
    event.preventDefault();
    console.log('ini nih');
    $('#receipt_register_branch').combobox('select', $("#receipt_register_branch").attr('branch-id'));
        $('#receipt_register_dc_code').combobox('select', $("#receipt_register_dc_code").attr('dc-code'));

    /*$('#combo1_receipt_register').combobox({
       url:base_url+'Report/get_receipt_register_toko',
       valueField:'STORE_CODE',
       textField:'STORE',
       formatter: format_combo_receipt_register1
    }); 
    
    $('#combo2_receipt_register').combobox({
       url:base_url+'Report/get_receipt_register_toko',
       valueField:'STORE_CODE',
       textField:'STORE',
       formatter: format_combo_receipt_register2
    });*/   
    
    $('#date1_receipt_register').datebox({
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
    
    $('#date2_receipt_register').datebox({
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
        
    $('#req_receipt_register').window('open');    
  })
///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  

$("#report_sales_region").click(function(event) {
  event.preventDefault();
  $('#rep_sales_region').window('open');

  $('#end_date_mps').datebox('setValue','');
  $('#start_date_mps').datebox('setValue','');
  $("#mps_branch").combobox('setValue','');
  $("#report_type").combobox('setValue','');

//  $('#monitoring_sales_region').form('clear');

  });
  
  $("#report_monitoring_absensi_denom_toko_idm").click(function(event) {
    event.preventDefault();
    $('#rep_monitoring_absensi_denom_toko_idm').window('open');
    // $('#end_date_mps').datebox('setValue','');
    // $('#start_date_mps').datebox('setValue','');
    // $("#mps_branch").combobox('setValue','');
    // $("#report_type").combobox('setValue','');
  
  //  $('#monitoring_sales_region').form('clear');
  
    });

  $("#report_monitoring_kurang_setor").click(function(event) {
    event.preventDefault();
    $('#rep_monitoring_kurang_setor').window('open');
    // $('#end_date_mps').datebox('setValue','');
    // $('#start_date_mps').datebox('setValue','');
    // $("#mps_branch").combobox('setValue','');
    // $("#report_type").combobox('setValue','');
  
  //  $('#monitoring_sales_region').form('clear');
  
    });
  
$('#mks_branch').combobox({
    onChange:  function(value) {
      $('#mks_kode_toko').combobox('reload','Report/choose_store2/'+value);
      // var end_date=$('#end_date_mps').datebox('getValue');
      // if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
      // {
      //   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
      // }
    
    }
});

$('#adt_branch').combobox({
    onChange:  function(value) {
      $('#adt_kode_toko').combobox('reload','Report/choose_store2/'+value);
      // var end_date=$('#end_date_mps').datebox('getValue');
      // if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
      // {
      //   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
      // }
    
    }
});

// $('#periode_mks').datetimespinner({
//   onChange:  function(value) {
//     // alert(value);
//     // $('#mks_kode_toko').combobox('reload','Report/choose_store2/'+value);
//     // var end_date=$('#end_date_mps').datebox('getValue');
//     // if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
//     // {
//     //   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
//     // }
  
//   }
// });
$('#end_date_mps').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_mps').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });

$('#end_date_mps').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });
$('#start_date_mps').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

 $('#mps_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $("#sub_adt_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#adt_branch").combobox('getValue');
      var kode_toko = $("#adt_kode_toko").combobox('getValue');
      // var status_setor = $("#status_setor_mks").combobox('getValue');
      var periode_mks =$("#periode_adt").datetimespinner('getValue');

      // alert(branch);
      // alert(kode_toko);
      // alert(status_setor);
      // alert(periode_mks);
      // var tgl1 = start_date.split('-');
      // var tgl2 = end_date.split('-');

      // start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      // end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && kode_toko != '' && periode_mks != '') {
        window.open(base_url_rep+'adt/'+branch+'/'+kode_toko+'/'+periode_mks+'/pdf', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600");
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_adt_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#adt_branch").combobox('getValue');
      var kode_toko = $("#adt_kode_toko").combobox('getValue');
      // var status_setor = $("#status_setor_mks").combobox('getValue');
      var periode_mks =$("#periode_adt").datetimespinner('getValue');

      // alert(branch);
      // alert(kode_toko);
      // alert(status_setor);
      // alert(periode_mks);
      // var tgl1 = start_date.split('-');
      // var tgl2 = end_date.split('-');

      // start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      // end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && kode_toko != '' && periode_mks != '') {
        window.open(base_url_rep+'adt/'+branch+'/'+kode_toko+'/'+periode_mks+'/csv', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600");
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_mks_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#mks_branch").combobox('getValue');
      var kode_toko = $("#mks_kode_toko").combobox('getValue');
      var status_setor = $("#status_setor_mks").combobox('getValue');
      var periode_mks =$("#periode_mks").datetimespinner('getValue');
      var tipe_setoran = $("#tipe_setor_mks").combobox('getValue');
      // alert(branch);
      // alert(kode_toko);
      // alert(status_setor);
      // alert(periode_mks);
      // var tgl1 = start_date.split('-');
      // var tgl2 = end_date.split('-');

      // start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      // end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && kode_toko != '' && status_setor != '' && periode_mks != '') {
        window.open(base_url_rep+'mks/'+branch+'/'+kode_toko+'/'+status_setor+'/'+periode_mks+'/'+tipe_setoran+'/pdf', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600");
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_mks_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#mks_branch").combobox('getValue');
      var kode_toko = $("#mks_kode_toko").combobox('getValue');
      var status_setor = $("#status_setor_mks").combobox('getValue');
      var periode_mks =$("#periode_mks").datetimespinner('getValue');
      var tipe_setoran = $("#tipe_setor_mks").combobox('getValue');
      // alert(branch);
      // alert(kode_toko);
      // alert(status_setor);
      // alert(periode_mks);
      // var tgl1 = start_date.split('-');
      // var tgl2 = end_date.split('-');

      // start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      // end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && kode_toko != '' && status_setor != '' && periode_mks != '') {
        window.open(base_url_rep+'mks/'+branch+'/'+kode_toko+'/'+status_setor+'/'+periode_mks+'/'+tipe_setoran+'/csv', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600");
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
 $("#sub_mps_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#mps_branch").combobox('getValue');
      var start_date = $("#start_date_mps").datebox('getValue');
      var end_date = $("#end_date_mps").datebox('getValue');
      var report_type= $("#report_type").combobox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && report_type != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
            if(tgl1[1]==tgl2[1]){

              window.open(base_url_rep+'mps/'+branch+'/'+report_type+'/'+start_date+'/'+end_date+'/pdf', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600,scrollbars=yes");


            }else{
              $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

            }
        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });


 $("#sub_mps_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#mps_branch").combobox('getValue');
      var start_date = $("#start_date_mps").datebox('getValue');
      var end_date = $("#end_date_mps").datebox('getValue');
      var report_type= $("#report_type").combobox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && report_type != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
            if(tgl1[1]==tgl2[1]){
              window.open(base_url_rep+'mps/'+branch+'/'+report_type+'/'+start_date+'/'+end_date+'/csv', "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600");


            }else{
              $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

          }
        
        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  

$("#report_pending_setor_toko").click(function(event) {
    event.preventDefault();
   $('#rep_pending_setor_toko').window('open');
   $('#end_date_pst').datebox('setValue','');
   $('#start_date_pst').datebox('setValue','');
   $("#pst_branch").combobox('setValue','');
  
  });
$('#start_date_pst').datebox({
      onChange:  function(newValue,oldValue) {
        var end_date=$('#end_date_pst').datebox('getValue');
        if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_pst').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_pst').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });

$('#end_date_pst').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });
$('#start_date_pst').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

 $('#pst_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

 $("#sub_pst_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#pst_branch").combobox('getValue');
      var start_date = $("#start_date_pst").datebox('getValue');
      var end_date = $("#end_date_pst").datebox('getValue');
      var sort_by=$("#sort_pst").combobox('getValue');
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');
      var jumlah_toko=$('#jumlah_toko_pst').combobox('getValue');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != ''  && start_date != '' && end_date != ''&& sort_by!='' && jumlah_toko!='' ) {
        if(start_date <= end_date){

        if(tgl1[1]==tgl2[1]){
              
              $.messager.progress({
                title:'Please waiting',
                msg: '<img src="assets/image/logo.png" style="height:30px">'
              });
              var bar = $.messager.progress('bar');
              bar.progressbar({
                text: ''
              });
              $.ajax({
                method: "POST",
                url: base_url+'report/cek_data_pending_setor_toko/',
                data :{
                      cabang : branch,
                      start_date:start_date,
                      end_date:end_date,


                },
                success: function (total) {
                  if(JSON.parse(total)=="t"){
                    $.messager.progress('close');
                  
                    window.open(base_url_rep+'pst/'+branch+'/'+start_date+'/'+end_date+'/pdf/'+sort_by+'/'+jumlah_toko, "Report Pending Setor Toko", "width=600,height=600,scrollbars=yes");

                  }else{
                     $.messager.progress('close');
                    $.messager.alert('Warning','Tidak ada data untuk dicetak.');         
                  }
                }
              });


          }else{
                    $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

          }
        
        

        }else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });


 $("#sub_pst_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#pst_branch").combobox('getValue');
      var start_date = $("#start_date_pst").datebox('getValue');
      var end_date = $("#end_date_pst").datebox('getValue');
      var sort_by=$("#sort_pst").combobox('getValue');
    
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');
      var jumlah_toko=$('#jumlah_toko_pst').combobox('getValue');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != ''  && start_date != '' && end_date != '' && sort_by!='' && jumlah_toko!='' ) {
        if(start_date <= end_date){
          if(tgl1[1]==tgl2[1]){
              $.messager.progress({
                title:'Please waiting',
                msg: '<img src="assets/image/logo.png" style="height:30px">'
              });
              var bar = $.messager.progress('bar');
              bar.progressbar({
                text: ''
              });
              $.ajax({
                method: "POST",
                url: base_url+'report/cek_data_pending_setor_toko/',
                data :{
                      cabang : branch,
                      start_date:start_date,
                      end_date:end_date

                },
                success: function (total) {
                  if(JSON.parse(total)=="t"){
                    $.messager.progress('close');
                    
                    window.open(base_url_rep+'pst/'+branch+'/'+start_date+'/'+end_date+'/csv/'+sort_by+'/'+jumlah_toko, "Report Pending Setor Toko", "width=600,height=600,scrollbars=yes");

                  }else{
                     $.messager.progress('close');
                    $.messager.alert('Warning','Tidak ada data untuk dicetak.');         
                  }
                }
              });


          }else{
                    $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

          }
        
        

        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
 ///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  

$("#report_sales_toko").click(function(event) {
    event.preventDefault();
    $('#rep_sales_toko').window('open');
    $('#end_date_rst').datebox('setValue','');
    $('#start_date_rst').datebox('setValue','');
    $("#rst_branch").combobox('setValue','');

  });
$('#start_date_rst').datebox({
      onChange:  function(newValue,oldValue) {
        var end_date=$('#end_date_rst').datebox('getValue');
        if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_rst').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_rst').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_rst').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });
$('#start_date_rst').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

 $('#rst_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

 $("#sub_rst_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#rst_branch").combobox('getValue');
      var start_date = $("#start_date_rst").datebox('getValue');
      var end_date = $("#end_date_rst").datebox('getValue');
   
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != ''  && start_date != '' && end_date != '') {
        if(start_date <= end_date){
          if(tgl1[1]==tgl2[1]){
              window.open(base_url_rep+'rst/'+branch+'/'+start_date+'/'+end_date+'/pdf', "Rekap Penerimaan Sales (per Toko)", "width=600,height=600,scrollbars=yes");
          }else{
              $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');

          }
        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_rst_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#rst_branch").combobox('getValue');
      var start_date = $("#start_date_rst").datebox('getValue');
      var end_date = $("#end_date_rst").datebox('getValue');
   
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != ''  && start_date != '' && end_date != '') {
        if(start_date <= end_date){
             if(tgl1[1]==tgl2[1]){

                  window.open(base_url_rep+'rst/'+branch+'/'+start_date+'/'+end_date+'/csv', "Rekap Penerimaan Sales (per Toko)", "width=600,height=600,scrollbars=yes");

              }else{
                   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
              }

        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  

$("#report_penerimaan_sales_detil").click(function(event) {
    event.preventDefault();
    $('#rep_penerimaan_sales_detil').window('open');
    $('#end_date_psd').datebox('setValue','');
    $('#start_date_psd').datebox('setValue','');
    $("#psd_branch").combobox('setValue','');


  });
$('#start_date_psd').datebox({
      onChange:  function(newValue,oldValue) {
        var end_date=$('#end_date_psd').datebox('getValue');
        if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_psd').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_psd').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_psd').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });
$('#start_date_psd').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

 $('#psd_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

 $("#sub_psd_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#psd_branch").combobox('getValue');
      var start_date = $("#start_date_psd").datebox('getValue');
      var end_date = $("#end_date_psd").datebox('getValue');
     
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');
      

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
          if(tgl1[1]==tgl2[1]){

                window.open(base_url_rep+'psd/'+branch+'/'+start_date+'/'+end_date+'/csv', "Report Monitoring Cabang", "width=600,height=600,scrollbars=yes");

              }else{
                   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
              }

        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_psd_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#psd_branch").combobox('getValue');
      var start_date = $("#start_date_psd").datebox('getValue');
      var end_date = $("#end_date_psd").datebox('getValue');
     
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];

      
      if (branch != '' && start_date != '' && end_date != '') {
              if(start_date <= end_date){
                if(tgl1[1]==tgl2[1]){

                    window.open(base_url_rep+'psd/'+branch+'/'+start_date+'/'+end_date+'/pdf', "Report Monitoring Cabang", "width=600,height=600,scrollbars=yes");

                }else{
                         $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
                    }


              }
              else{
                $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
              }
      } else {
              $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
         
      }
        
     
    }
  });
 ///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  

$("#report_sales_cbg").click(function(event) {
    event.preventDefault();
    $('#rep_penerimaan_sales_per_cbg').window('open');
    $('#end_date_psc').datebox('setValue','');
    $('#start_date_psc').datebox('setValue','');
    $("#psc_branch").combobox('setValue','');
  
  });
$('#start_date_psc').datebox({
      onChange:  function(newValue,oldValue) {
        var end_date=$('#end_date_psc').datebox('getValue');
        if(end_date!='' && end_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_psc').datebox({
      onChange:  function(newValue,oldValue) {
        var start_date=$('#start_date_psc').datebox('getValue');
        if(start_date!='' && start_date.split('-')[1] !=newValue.split('-')[1])
        {
          $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
        }
      
      }
   });
$('#end_date_psc').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });
$('#start_date_psc').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

 $('#psc_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

 $("#sub_psc_csv").linkbutton({
    onClick: function (event) {
      var branch = $("#psc_branch").combobox('getValue');
      var start_date = $("#start_date_psc").datebox('getValue');
      var end_date = $("#end_date_psc").datebox('getValue');
     
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
          if(tgl1[1]==tgl2[1]){

              window.open(base_url_rep+'psc/'+branch+'/'+start_date+'/'+end_date+'/csv', "Rekap Penerimaan Sales (per Cabang)", "width=600,height=600,scrollbars=yes");

          }else{
                   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
              }

        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
  $("#sub_psc_pdf").linkbutton({
    onClick: function (event) {
      var branch = $("#psc_branch").combobox('getValue');
      var start_date = $("#start_date_psc").datebox('getValue');
      var end_date = $("#end_date_psc").datebox('getValue');
     
      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
           if(tgl1[1]==tgl2[1]){

              window.open(base_url_rep+'psc/'+branch+'/'+start_date+'/'+end_date+'/pdf', "Rekap Penerimaan Sales (per Cabang)", "width=600,height=600,scrollbars=yes");

          }else{
                   $.messager.alert('Warning','Tanggal awal dan Tanggal akhir harus dibulan yang sama');
              }

        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });
///////////////////////////////////////////////////////////////////////////////////////////////////////   
/*===================================================================================================*/   
///////////////////////////////////////////////////////////////////////////////////////////////////////  


  $("#report_sales_diff").click(function(event) {
    event.preventDefault();
    $('#diff_journal_branch').combobox('select', $("#diff_journal_branch").attr('branch-id'));
    $('#diff_journal_dc_code').combobox('select', $("#diff_journal_dc_code").attr('dc-code'));
    $('#req_diff_journal').window('open');
  });
  

   $('#report_summary_collect').click(function(event) {
      event.preventDefault();
      $('#summary_collect_branch').combobox('select', $("#summary_collect_branch").attr('branch-id'));
      $('#summary_collect_dc_code').combobox('select', $("#summary_collect_dc_code").attr('dc-code'));
      $('#req_summary_collect').window('open');
   });

   $('#report_trend_collection').click(function(event) {
      event.preventDefault();
      $('#periode_trend_collect').combobox({
         url:base_url_rep+'get_periode_tren_collect',
         valueField:'val',
         textField:'text'
      });
      $('#req_trend_collection').window('open');
   });

   $('#sub_tren_collect').click(function(event) {
      event.preventDefault();
      if ($('#periode_trend_collect').combobox('getValue') == '') {
         $.messager.alert('Warning','Harap memilih periode yang disediakan.');
      }else{
         window.open(base_url_rep+'print_trend_collection/'+$('#periode_trend_collect').combobox('getValue'), "Report Trend Collection", "width=600,height=600,scrollbars=yes");
      }
   });

   /*$('#shift_start').combobox({
      onSelect: function (record) {
          var start = $('#shift_start').combobox('getValue');
          var end = $('#shift_end').combobox('getValue');
          if (end != '') {
            var jumlah = 0;
            jumlah = start - end;
            if (jumlah > 0) {
               alert('Harap memilih akhir shift dengan benar.');
               $('#sub_sum_collect').linkbutton('disable');
            }else{
               $('#sub_sum_collect').linkbutton('enable');
            }
          }
      }
   });

   $('#shift_end').combobox({
      onSelect: function (record) {
          var start = $('#shift_start').combobox('getValue');
          var end = $('#shift_end').combobox('getValue');
          var jumlah = 0;
          jumlah = start - end;
          if (jumlah > 0) {
            alert('Harap memilih akhir shift dengan benar.');
            $('#sub_sum_collect').linkbutton('disable');
          }else{
            $('#sub_sum_collect').linkbutton('enable');
          }
      }
   });*/

   $('#sub_sum_collect').linkbutton({
      onClick : function(event) {
        var date = $('#sum_collect_date').datebox('getValue');
        var start = $('#shift_start').combobox('getValue');
        var end = $('#shift_end').combobox('getValue');
        var branch_id = $("#summary_collect_branch").combobox('getValue');
        var dc_code = $("#summary_collect_dc_code").combobox('getValue');
        if (date == '' || start == '' || end == '') {
          $.messager.alert('Warning','Harap form diisi dengan lengkap.');
        }else{
          var start = $('#shift_start').combobox('getValue');
          var end = $('#shift_end').combobox('getValue');
          var jumlah = 0;
          jumlah = start - end;
          if (jumlah > 0) {
            $.messager.alert('Warning','Harap memilih akhir shift dengan benar.');
            $('#sub_sum_collect').linkbutton('disable');
          }else{
            window.open(base_url_rep+'print_summary_collect/'+date+'/'+start+'/'+end+'/'+branch_id+'/'+dc_code, "Report Summary Collect", "width=600,height=600,scrollbars=yes"); 
          }
        };
      }
   });

   $("#sub_diff_journal").linkbutton({
    onClick : function(event) {
      var start = $('#start_date_dj').datebox('getValue');
      var end = $('#end_date_dj').datebox('getValue');
      var store_type = $("#store_type_dj").combobox('getValue');
      var branch_id = $("#diff_journal_branch").combobox('getValue');
        var dc_code = $("#diff_journal_dc_code").combobox('getValue');
      if (start != '' || end != '') {
        window.open(base_url_rep+'print_sales_diff_journal/'+store_type+'/'+start+'/'+end+'/'+branch_id+'/'+dc_code, "Report Sales Difference Journal", "width=600,height=600,scrollbars=yes");
      }else{
        $.messager.alert('Warning','Harap mengisi periode tanggal dengan lengkap.');
      }
    }
   });

   /*$('#sub_sum_collect').click(function(event) {
      event.preventDefault();
      var date = $('#sum_collect_date').datebox('getValue');
      var start = $('#shift_start').combobox('getValue');
      var end = $('#shift_end').combobox('getValue');
      if (date == '' || start == '' || end == '') {
         alert('Harap form diisi dengan lengkap.');
      }else{
         window.open(base_url_rep+'print_summary_collect/'+date+'/'+start+'/'+end, "Report Summary Collect", "width=600,height=600,scrollbars=yes");
      };
   });*/

  $("#report_plus_minus").click(function(event) {
    event.preventDefault();
    $("#form_detail_plus_minus").window('open');
  });

   $('#sum_collect_date').datebox({
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

   $('#pm_start_date').datebox({
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

  $('#pm_end_date').datebox({
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



  $("#sub_pm").click(function(event) {
    event.preventDefault();

    if ($("#pm_start_date").datebox('getValue') != '' && $("#pm_end_date").datebox('getValue') != '') {
      var start = $("#pm_start_date").datebox('getValue');
      var end = $("#pm_end_date").datebox('getValue');
      var type = $("#pm_type").combobox('getValue');
      var deposit = 'X';
      var batch = 'X';
      if ($("#pm_dep_num").textbox('getValue') != '') {
        deposit = $("#pm_dep_num").textbox('getValue').replace(' ','-').replace('.','-').replace(',','-').replace('/','-').replace('|','-');
      }
      if ($("#pm_batch_num").textbox('getValue') != '') {
        batch = $("#pm_batch_num").textbox('getValue');
      }
    $('#url').textbox('setValue',base_url_rep+'print_data_plus_minus/'+start+'/'+end+'/'+type+'/'+batch+'/'+deposit);
  
    //$('#form_detail_plus_minus').window('close');
    $('#form_print_report').window('open');

    //link report
    //window.open(base_url_rep+'print_data_plus_minus/'+start+'/'+end+'/'+type+'/'+batch+'/'+deposit, "Report Detail Data Penambah dan Pengurang", "width=600,height=600,scrollbars=yes");
    }else{ $.messager.alert('Alert','Tanggal seposit harus diisi.','info');}
  });
  $("#sub_pm_pdf").click(function(event) {
    event.preventDefault();
    var url=$('#url').textbox('getValue')+'/pdf';
    window.open(url, "Report Detail Data Penambah dan Pengurang", "width=600,height=600,scrollbars=yes");

  });
  $("#sub_pm_csv").click(function(event) {
    event.preventDefault();
    var url=$('#url').textbox('getValue')+'/csv';
    window.open(url);

  });

  

  $("#report_mtr_dana_sales").click(function(event) {
    event.preventDefault();
    $('#mtr_dana_branch').combobox('select', $("#mtr_dana_branch").attr('branch-id'));
    $("#req_mtr_dana_sales").window('open');
  });

  $("#report_mtr_dana_sales_shift").click(function(event) {
    event.preventDefault();
    $('#mtr_dana_branch_shift').combobox('select', $("#mtr_dana_branch_shift").attr('branch-id'));
    $("#req_mtr_dana_sales_shift").window('open');
  });

  $("#report_kurset_per_shift").click(function(event) {
    event.preventDefault();
    $('#kurset_branch_shift').combobox('select', $("#kurset_branch_shift").attr('branch-id'));
    $("#req_kurset_per_shift").window('open');
  });

  $("#report_lebset_per_shift").click(function(event) {
    event.preventDefault();
    $('#lebset_branch_shift').combobox('select', $("#lebset_branch_shift").attr('branch-id'));
    $("#req_lebset_per_shift").window('open');
  });

  $("#report_kurset_per_toko").click(function(event) {
    event.preventDefault();
    $('#kurset_branch_toko').combobox('select', $("#kurset_branch_toko").attr('branch-id'));
    $("#req_kurset_per_toko").window('open');
  });


   $('#lebset_branch_shift').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $('#lebset_branch_shift').combobox({
    onChange: function (value) {
      $('#store_lebset_shift').combobox({
        url: base_url+'Report/choose_store_mtr/'+value,
        valueField:'STORE_ID',
        textField:'STORE'
      });
    }
  });

  $('#kurset_branch_shift').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $('#kurset_branch_shift').combobox({
    onChange: function (value) {
      $('#store_kurset_shift').combobox({
        url: base_url+'Report/choose_store_mtr/'+value,
        valueField:'STORE_ID',
        textField:'STORE'
      });
    }
  });

  $('#kurset_branch_toko').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $('#kurset_branch_toko').combobox({
    onChange: function (value) {
      $('#am_kuset_toko').combobox({
        url: base_url+'Report/choose_am/'+value,
        valueField:'AM_NUMBER',
        textField:'AM'
      });
    }
  });


  $('#mtr_dana_branch_shift').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $('#mtr_dana_branch_shift').combobox({
    onChange: function (value) {
      $('#store_mtr_shift').combobox({
        url: base_url+'Report/choose_store_mtr/'+value,
        valueField:'STORE_ID',
        textField:'STORE'
      });
    }
  });

  $('#mtr_dana_branch').combobox({
    url: base_url+'Report/choose_branch',
    valueField:'BRANCH_ID',
    textField:'BRANCH_VALUE'
  });

  $('#mtr_dana_branch').combobox({
    onChange: function (value) {
      $('#store_mtr').combobox({
        url: base_url+'Report/choose_store_mtr/'+value,
        valueField:'STORE_ID',
        textField:'STORE'
      });
    }
  });

  $('#start_date_mtr').datebox({
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

  $('#end_date_mtr').datebox({
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

  $('#start_date_mtr_shift').datebox({
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

  $('#end_date_mtr_shift').datebox({
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

  $('#start_date_kurset_shift').datebox({
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

  $('#end_date_kurset_shift').datebox({
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

  $('#start_date_lebset_shift').datebox({
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

  $('#end_date_lebset_shift').datebox({
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

  $('#start_date_kurset_toko').datebox({
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

  $('#end_date_kurset_toko').datebox({
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

  $("#sub_mtr_dana").linkbutton({
    onClick: function (event) {
      var branch = $("#mtr_dana_branch").combobox('getValue');
      var store = $("#store_mtr").combobox('getValue');
      var start_date = $("#start_date_mtr").datebox('getValue');
      var end_date = $("#end_date_mtr").datebox('getValue');
      if (branch != '' && store != '' && start_date != '' && end_date != '') {
        window.open(base_url_rep+'print_mtr_dana_sales/'+branch+'/'+store+'/'+start_date+'/'+end_date, "Report Monitoring Setoran Dana Sales", "width=600,height=600,scrollbars=yes");
      } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
      }
    }
  });

  $("#sub_mtr_dana_shift").linkbutton({
    onClick: function (event) {
      var branch = $("#mtr_dana_branch_shift").combobox('getValue');
      var store = $("#store_mtr_shift").combobox('getValue');
      var start_date = $("#start_date_mtr_shift").datebox('getValue');
      var end_date = $("#end_date_mtr_shift").datebox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];
      
      if (branch != '' && store != '' && start_date != '' && end_date != '') {
        if(start_date <= end_date){
          window.open(base_url_rep+'print_mtr_dana_sales_shift/'+branch+'/'+store+'/'+start_date+'/'+end_date, "Report Kurang Setor Per Rincian Pimpinan Shift", "width=600,height=600,scrollbars=yes");
        }
        else{
           $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });

  $("#sub_kurset_shift").linkbutton({
    onClick: function (event) {
      var branch = $("#kurset_branch_shift").combobox('getValue');
      var store = $("#store_kurset_shift").combobox('getValue');
      var start_date = $("#start_date_kurset_shift").datebox('getValue');
      var end_date = $("#end_date_kurset_shift").datebox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];

      if (branch != '' && store != '' && start_date != '' && end_date != '') {
        if(start_date < end_date){
          window.open(base_url_rep+'kurset_per_shift/'+branch+'/'+store+'/'+start_date+'/'+end_date, "Report Lebih Setor Per Rincian Pimpinan Shift", "width=600,height=600,scrollbars=yes");
        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });

  $("#sub_lebset_shift").linkbutton({
    onClick: function (event) {
      var branch = $("#lebset_branch_shift").combobox('getValue');
      var store = $("#store_lebset_shift").combobox('getValue');
      var start_date = $("#start_date_lebset_shift").datebox('getValue');
      var end_date = $("#end_date_lebset_shift").datebox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && store != '' && start_date != '' && end_date != '') {
         if(start_date < end_date){
            window.open(base_url_rep+'lebset_per_shift/'+branch+'/'+store+'/'+start_date+'/'+end_date, "Rincian Kurang Setor Sales Per Toko IDM", "width=600,height=600,scrollbars=yes");
          }
          else{
            $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');
          }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });

  $("#sub_kurset_toko").linkbutton({
    onClick: function (event) {
      var branch = $("#kurset_branch_toko").combobox('getValue');
      var am = $("#am_kuset_toko").combobox('getValue');
      var start_date = $("#start_date_kurset_toko").datebox('getValue');
      var end_date = $("#end_date_kurset_toko").datebox('getValue');

      var tgl1 = start_date.split('-');
      var tgl2 = end_date.split('-');

      start_date = tgl1[2]+'-'+tgl1[1]+'-'+tgl1[0];
      end_date = tgl2[2]+'-'+tgl2[1]+'-'+tgl2[0];


      if (branch != '' && am != '' && start_date != '' && end_date != '') {
        if(start_date < end_date){
          window.open(base_url_rep+'kurset_per_toko/'+branch+'/'+am+'/'+start_date+'/'+end_date, "Report Monitoring Setoran Dana Sales per Shift", "width=600,height=600,scrollbars=yes");
        }
        else{
          $.messager.alert('Warning','Tanggal awal tidak boleh lebih besar dari akhir');  
        }
     } else {
        $.messager.alert('Warning','Mohon untuk mengisi kolom dengan lengkap.');
     }
    }
  });

});