<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/master.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_shift.js"></script>

<div class="panelWindows" style="position:relative;width:100%;height:100%;;overflow:100%;">
<div id="w" class="easyui-window" title="Master" data-options="inline:true, iconCls:'icon-Script'" style="width:85%;height:425px;padding:5px;">

<!---------------DATAGRID------------------------------------------------->
  	<table id="tblMasterShift" title="Master Shift" toolbar="#toolbar" style="width:100%;height:auto">

    </table>

</div>
</div>


<?php
  $branchCode = $this->session->userdata('branch_code');
 ?>
<!-- BUTTON IN TABELL -->
    <div id="toolbar">
      <a href="#" id="tambah" branchCode="<?php echo $branchCode;?>" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambah()">Add</a>
      <a href="#" id="ubah" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="ganti()">Edit</a>
    </div>
<!------------------------------------------------------------------------------------------------------>

<!-- OPEN EDIT DIALOG POP_UP -->>
<div id="shift_edit_dialog" class="easyui-dialog" style="width:380px;height:230px;padding:10px 20px" closed="true" buttons="#dlg-buttons-edit">
    <div class="ftitle">Edit Shift</div>
    <form id="shift_edit_form" class="easyui-form" action="#" method="post">
      <table>
          <tr id="shift_id_edit">
              <td>SHIFT ID</td>
              <td>:</td>
              <td>
                <input id="shiftId_edit" type="text" name="SHIFT_ID" class="easyui-textbox" tabindex="-1">
              </td>
          </tr>
          <tr>
            <td> Shift </td>
            <td>:</td>
            <td>
              <input id="shift_start_edit" name="SHIFT_TIME_FROM" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
            </td>
            <td> - </td>
            <td>
              <input id="shift_end_edit" name="SHIFT_TIME_TO" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
            </td>
          </tr>
          <tr id="active_edit">
              <td>ACTIVE</td>
              <td>:</td>
              <td>
                <select id="activeFlag_edit" name="ACTIVE_FLAG" class="easyui-combobox" style="width:50px" >
                  <option value="Y" selected="selected">Y</option>
                  <option value="N">N</option>
                </select>
              </td>
          </tr>
          <tr id="inactive_edit">
              <td>INACTIVE DATE</td>
              <td>:</td>
              <td>
                <input id="inactiveDate_edit" name="INACTIVE_DATE" class="easyui-datebox" style="width:100px;" required="false"  disabled>
              </td>
          </tr>

      </table>
    </form>
</div>

<div id="dlg-buttons-edit">
<a href="javascript:void(0)" id="bntSave" class="easyui-linkbutton" iconCls="icon-save" onclick="saveEdit()" style="width:90px" >Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelEdit()" style="width:90px">Cancel</a>
</div>
<!------------------------------------------------------------------------------------------------------>


<!-- OPEN ADD DIALOG POP_UP -->
    <div id="shift_dialog" class="easyui-dialog" style="width:380px;height:350px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <div class="ftitle">Detail Shift</div>
        <form id="shift_form" class="easyui-form" action="#" method="post">
          <table>
              <tr id="shift_id">
                  <td>SHIFT ID</td>
                  <td>:</td>
                  <td>
                    <input id="shiftId" type="text" name="SHIFT_ID" class="easyui-textbox" tabindex="-1">
                  </td>
              </tr>
              <tr>
                <td> Shift 1</td>
                <td>:</td>
                <td>
                  <input id="shift1_start" name="SHIFT_TIME_FROM" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
                </td>
                <td> - </td>
                <td>
                  <input id="shift1_end" name="SHIFT_TIME_TO" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
                </td>
              </tr>

              <tr>
                <td> Shift 2</td>
                <td>:</td>
                <td>
                  <input id="shift2_start" name="" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
                </td>
                <td> - </td>
                <td>
                  <input id="shift2_end" name="" class="easyui-timespinner"  style="width:100px;" required="required" data-options="showSeconds:false">
                </td>
              </tr>

              <tr>
                <td> Shift 3</td>
                <td>:</td>
                <td>
                  <input id="shift3_start" name="" class="easyui-timespinner"  style="width:100px;"required="required" data-options="showSeconds:false">
                </td>
                <td> - </td>
                <td>
                  <input id="shift3_end" name="" class="easyui-timespinner"  style="width:100px;"required="required" data-options="showSeconds:false">
                </td>
              </tr>

              <tr id="active">
                  <td>ACTIVE</td>
                  <td>:</td>
                  <td>
                    <select id="activeFlag" name="ACTIVE_FLAG" class="easyui-combobox" style="width:50px" >
                      <option value="Y" selected="selected">Y</option>
                      <option value="N">N</option>
                    </select>
                  </td>
              </tr>
              <tr id="inactive">
                  <td>INACTIVE DATE</td>
                  <td>:</td>
                  <td>
                    <input id="inactiveDate" name="INACTIVE_DATE" class="easyui-datebox" style="width:100px;" required="false"  disabled>
                  </td>
              </tr>
          </table>
        </form>
    </div>
<!------------------------------------------------------------------------------------------------------>


<!-- BUTTON DIALOG ADD/EDIT POP_UP -->>
    <div id="dlg-buttons">
    <a href="javascript:void(0)" id="bntSave" class="easyui-linkbutton" iconCls="icon-save" onclick="save()" style="width:90px" >Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancel()" style="width:90px">Cancel</a>
    </div>
