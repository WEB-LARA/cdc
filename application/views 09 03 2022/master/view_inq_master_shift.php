<!-- START BODY -->
<div DATA-OPTIONS="region:'center'" style="height:90%;">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_shift.js"></script>

  <div id="atas" class="easyui-panel" title="Form Master Shift Cabang" style="padding:0px; position:static;width:100%;height:175px;" data-options="iconCls: 'icon-download'">
    <div align="right" style="padding:5px; margin-right:2%">
      <b> &nbsp
          <span id="date_time" align="right"></span>
                      <script type="text/javascript">window.onload = date_time('date_time');</script>
      </b>
    </div>

    <div align="left" style="padding:5px; margin-left:15px">
      <table>
        <tr>
          <td style="width:200px;" >
            Toko :
          </td>
          <td colspan="2">
              <input id="store_code" type="text" class="easyui-combobox" data-options="valueField:'STORE_CODE',textField:'STORE',url:'<?php echo base_url(); ?>master/Shift/get_kode_toko'" style="width:330px;">
          </td>
          <td style="width:50px"> &nbsp </td>
         
          <td style="width:200px;">
            Metode Setor :
          </td>
          <td>
            <select class="easyui-combobox" name="metode_setor" id="metode_setor" style="width:150px">
                 <option value=""></option>
              <option value="PIHAK3">Pihak ke 3</option>
              <option value="KODEL">Kodel</option>

              <option value="BANK">Setoran Bank</option>
             
            </select>
          </td>
          <td colspan="2">
            
          <a href="#" id='BtnAddStore' name='BtnAddStore' class="easyui-linkbutton"   style="width:400px">Add New Store</a>
        
          </td>
        </tr>
        <tr>
           <td>
            Tanggal Aktif Shift :
          </td>
          <td>
            <input class="easyui-datebox" name="start_date" id="start_date"> &nbsp &nbsp
          </td>
          <td><input class="easyui-datebox" name="end_date" id="end_date"> &nbsp &nbsp</td>
          <td style="width:50px"> &nbsp </td>
             <td>
            Tipe Shift :
          </td>
          <td>
            <select class="easyui-combobox" name="tipe_shift" id="tipe_shift" style="width:150px">
               <option value=""></option>
              <option value="H">Harian</option>
              <option value="H-1">Harian Shift</option>
              <option value="SS">Sales Shift</option>
            </select> &nbsp &nbsp
          </td>
            
         
         
          <td>
            
          <a href="#" id='BtnSearchForm' class="easyui-linkbutton"  onclick="searchMasterShift()" style="width:200px">Search</a>
          
          </td> 
          <td> <a href="#" id='BtnResetForm' class="easyui-linkbutton" onclick="BtnResetForm()" style="width:200px">Reset</a></td>
        </tr>
        <tr>
           <td>
            Status :
          </td>
          <td colspan="2">
            <select class="easyui-combobox" name="status" id="status" style="width:150px">
               <option value=""></option>
              <option value="A">Active</option>
              <option value="I">Inactive</option>
            </select> &nbsp &nbsp
          </td>
          <td></td>
          <td>
            Jml Shift :
          </td>
          <td>
            <select class="easyui-combobox" name="jml_shift" id="jml_shift" style="width:150px">
              <option value=""></option>
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select> &nbsp &nbsp
          </td>
           <td>
            
          <a href="#" id='BtnEditForm' class="easyui-linkbutton"  onclick="editMasterShift()" style="width:200px">Edit</a>
        </td>
        <td>
           <a href="#" id='BtnDelForm' class="easyui-linkbutton" onclick="delMasterShift()" style="width:200px">Delete</a>
          </td> 
        </tr>

      </table>
    </div>
  </div>

  <div id="bawah">
    <div id="inquiryShift">
      <table id="tblShift" title="Master Shift Toko" class="easyui-datagrid" style="width:100%;height:auto"
              url="<?php echo base_url();?>/master/Shift/getDataMasterShift" sortName="" toolbar="#toolbar" pagination="true" rownumbers="true" sortOrder="asc"
              fitColumns="true" singleSelect="true">
        <thead>
          <tr>
            <th data-options="field:'ID_SHIFT',width:120, align:'center',hidden:true"></th>
            <th data-options="field:'TOKO',width:120, align:'center'">TOKO</th>
            <th data-options="field:'TGL_ACTIVE',width:100, align:'center'">TGL AKTIF SHIFT</th>
            <th data-options="field:'TGL_INACTIVE',width:100, align:'center'">TGL BERAKHIR SHIFT</th>
            <th data-options="field:'STATUS',width:150,align:'center'">STATUS</th>
            <th data-options="field:'BRANCH_NAME',width:50,align:'center'">CABANG</th>
            <th data-options="field:'TIPE_SHIFT',width:70,align:'center'">TIPE SHIFT</th>
            <th data-options="field:'JML_SHIFT',width:70,align:'center'">JML SHIFT</th>
            <th data-options="field:'METODE_SETORAN',width:70,align:'center'">METODE SETORAN</th>
          </tr>
        </thead>
      </table>
      
    </div>

  </div>


</div>
</div> </div> <!-- CLOSE FOOTER -->

<!-- POP UP VIEW  -->
<div id="Batch_dialog" class="easyui-dialog" style="width:90%;height:420px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
  <div>
    <table id="tblEditReceipts" style="width:auto;height:350px">

    </table>
  </div>
</div>


<div id="upload_new_store" class="easyui-window" title="Add New Store" style="width:800x;height:500px; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form id="form_upload_master_shift" enctype="multipart/form-data">
    <!-- action="<?php echo base_url(); ?>Upload/upload_master_shift_toko" method="post" -->
    <table align="center">
      <tr>
        <td style="min-width: 150px!important;">
          <input id="file_store" class="easyui-filebox" name="file_store" style="width: 200px; min-height:30px;" />
          <input type="hidden" name="curcname_shift" value="<?php echo 'master/'.$this->router->fetch_class().'/'.$this->router->fetch_method(); ?>">
        </td>
      
        <td style="min-width: 150px!important;" colspan="4">
          <input class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_new_store" href="" style="min-width:88px !important;min-height:30px !important;" value="Upload" type="submit" />
          <a href="" class="easyui-linkbutton" style="min-width:88px !important;min-height:30px !important;" id="temp_store">Template</a>
           <a href="" class="easyui-linkbutton" style="min-width:88px !important;min-height:30px !important;" id="del_store">Delete</a>
          <a href="" class="easyui-linkbutton" style="min-width:88px !important;min-height:30px !important;" id="validate_store">Validate</a>
        </td>

      </tr>
    </table>
      <table id="temporary_store" title="Master Shift Toko Temp" class="easyui-datagrid" style="width:100%;height:auto"
             sortName="" toolbar="#toolbar" pagination="true" rownumbers="false" sortOrder="asc" fitColumns="true" singleSelect="true">
        <thead>
          <tr>
            <th data-options="field:'KD_TOKO',width:120, align:'center'">TOKO</th>
            <th data-options="field:'ACTIVE_DATE',width:100,align:'center'">TGL AKTIF SHIFT</th>
            <th data-options="field:'END_DATE',width:100,align:'center'">TGL BERAKHIR SHIFT</th>
            <th data-options="field:'TIPE_SHIFT',width:50,align:'center'">TIPE_SHIFT</th>
            <th data-options="field:'JML_SHIFT',width:70,align:'center'">JML SHIFT</th>
            <th data-options="field:'METODE_SETOR',width:70,align:'center'">METODE SETORAN</th>

              
          </tr>
        </thead>
      </table>
  </form>
</div>



<div id="edit_new_store" class="easyui-window" title="Edit New Store" style="width:900;height:250; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form id="form_edit_master_shift" enctype="multipart/form-data">
    <!-- action="<?php echo base_url(); ?>Upload/upload_master_shift_toko" method="post" -->
    <table align="center">
       <tr>
           <td style="width:250px">
            Toko :
          </td>
          <td colspan="2">
              <input id="e_store_code" type="text" class="easyui-combobox" style="width:330px;">
          </td>
         
          <td style="width:50px"> &nbsp </td>
         <td style="width:250px;">
            Metode Setor :
          </td>
          <td>
            <select class="easyui-combobox" name="e_metode_setor" id="e_metode_setor" style="width:150px">
              <option value="PIHAK3">Pihak ke 3</option>
              <option value="KODEL">Kodel</option>

              <option value="BANK">Setoran Bank</option>
             
            </select>
          </td>
        
        </tr>
     <tr>
           <td style="width:200px;">
            Tanggal Aktif Shift :
          </td>
          <td>
            <input class="easyui-datebox" name="e_start_date" id="e_start_date"> &nbsp &nbsp
          </td>
          <td><input class="easyui-datebox" name="e_end_date" id="e_end_date"> &nbsp &nbsp</td>
          <td style="width:50px"> &nbsp </td>
          
        <td>
            Tipe Shift :
          </td>
          <td>
            <select class="easyui-combobox" name="e_tipe_shift" id="e_tipe_shift" style="width:150px">
              <option value="H">Harian</option>
              <option value="H-1">Harian Shift</option>
              <option value="SS">Sales Shift</option>
            </select> &nbsp &nbsp
          </td>
            
         
        </tr>
          <tr>

           <td>
            Jml Shift :
          </td>
          <td>
            <select class="easyui-combobox" name="e_jml_shift" id="e_jml_shift" style="width:150px">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select> &nbsp &nbsp
          </td>
          
         
         
        </tr>
        <tr>
          
 <td colspan="4"></td>
          
        <td colspan="2">
            <a href="#" id='btnCloseForm' class="easyui-linkbutton"  onclick="closeMasterShift()" style="width:100px">Close</a>
           <a href="#" id='BtnDelForm' class="easyui-linkbutton" onclick="saveEMasterShift()" style="width:100px">Save Changes</a>
          </td> 
      

        </tr>
    </table>
      
  </form>
</div>

<script type="text/javascript">
  function formatDate1(val,row){ 
      if(val){
         return formattedDate(val); 
       }else {
        return "";
       }  
      
     }

    function formattedDate(date) {
      var d = new Date(date || Date.now()),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;

      return [day, month, year].join('/');
    }
$(document).ready(function() {


$('#e_start_date').datebox({
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
$('#e_end_date').datebox({
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
  

  $('#start_date').datebox({
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
$('#end_date').datebox({
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
});
</script>