<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/master.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_bank.js"></script>

<div class="panelWindows" style="position:relative;width:100%;height:100%;;overflow:100%;">
<div id="w" class="easyui-window" title="Master" data-options="inline:true, iconCls:'icon-Script'" style="width:85%;height:425px;padding:5px;">

<!---------------DATAGRID------------------------------------------------->
  <center>
    	<div style="margin:0px 0;"></div>
    	<table id="tblMasterBank" title="Master Bank" class="easyui-datagrid" style="width:100%;height:auto"
              url="<?php echo base_url();?>/master/Bank/getData" sortName="" toolbar="#toolbar" pagination="true" rownumbers="true" sortOrder="asc"
              fitColumns="true" singleSelect="true">
    		<thead>
    			<tr>
    				<th data-options="field:'BANK_NAME',width:120, align:'center'">BANK NAME</th>
    				<th data-options="field:'BANK_ACCOUNT_TYPE',width:100, align:'center'">BANK ACCOUNT TYPE</th>
    				<th data-options="field:'BANK_ACCOUNT_NUM',width:150,align:'center'">BANK ACCOUNT NUMBER</th>
    				<th data-options="field:'ACTIVE_FLAG',width:50,align:'center'">ACTIVE FLAG</th>
            <th data-options="field:'INACTIVE_DATE',width:70,align:'center'">INACTIVE DATE</th>
    			</tr>
    		</thead>
    	</table>
    </center>

</div>
</div>


<!-- BUTTON IN TABELL -->
    <div id="toolbar">
      <a href="#" id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true">Add</a>
      <a href="#" id="ubah" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
      <a href="#" id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Delete</a>
      <a href="#" id="cari" class="easyui-linkbutton" iconCls="icon-search" plain="true" >Search</a>
    </div>
<!------------------------------------------------------------------------------------------------------>


<!-- OPEN ADD/EDIT DIALOG POP_UP -->>
  <!-- <div id="bank_panel" style="width:30%;height:35%;padding:10px 20px; display: none;"> -->
    <div id="bank_dialog" class="easyui-dialog" style="width:400px;height:290px;padding:10px 20px;" closed="true" buttons="#dlg-buttons">
        <div class="ftitle">Detail Bank</div>
        <form class="easyui-form" id="bank_form" action="#" method="post">
          <table>
            <tr id="bank_id">
                <td>BANK ID </td>
                <td> : </td>
                <td>
                  <input id="bankId" type="text" name="BANK_ID" class="easyui-textbox" tabindex="-1">
                </td>
            </tr>

            <tr id="bank_name">
                <td>BANK NAME </td>
                <td> : </td>
                <td>
                  <input id="bankName" type="text" name="BANK_NAME" class="easyui-textbox" tabindex="1" required autofocus>
                </td>
            </tr>

            <tr id="bank_account_type">
                <td>BANK ACCOUNT TYPE</td>
                <td> : </td>
                <td>
                  <input id="bankAccountType" type="text" name="BANK_ACCOUNT_TYPE" class="easyui-textbox" tabindex="2" required>
                </td>
            </tr>

            <tr id="bank_account_number">
                <td>BANK ACCOUNT NUMBER </td>
                <td> : </td>
                <td>
                  <input id="bankAccountNum" type="text" name="BANK_ACCOUNT_NUM" class="easyui-textbox" tabindex="3" required>
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
                <td>INACTIVE DATE </td>
                <td>:</td>
                <td>
                  <input id="inactiveDate" name="INACTIVE_DATE" class="easyui-datebox" required="false"  disabled>
                </td>
            </tr>
          </table>
        </form>
    </div>
<!------------------------------------------------------------------------------------------------------>
<!-- BUTTON DIALOG ADD/EDIT POP_UP -->>
    <div id="dlg-buttons">
      <a id="submitOke" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" style="width:90px" >Save</a>
      <a id="submitCancel" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" style="width:90px">Cancel</a>
    </div>



<!---------------FORM DIALOG SEARCH------------------------------------------------->
<div id="dialogSearch" class="easyui-dialog" style="width:420px;height:225px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons2">
    <div class="ftitle">Search</div>
    <form id="formSearch" method="#" novalidate>
        <div class="fitem">
            <label>BANK NAME :</label>
            <input type="text" name="BANK_NAME" id="BANK_NAME" class="easyui-textbox" prompt="Search by Name">
        </div>
        <div class="fitem">
            <label>BANK ACCOUNT TYPE :</label>
            <input type="text" name="BANK_ACCUNT_TYPE" id="BANK_ACCUNT_TYPE" class="easyui-textbox" prompt="Search by Acount Type">
        </div>
        <div class="fitem">
            <label>BANK ACCOUNT NUMBER :</label>
            <input type="text" name="BANK_ACCUNT_NUM" id="BANK_ACCUNT_NUM" class="easyui-textbox" prompt="Search by Acount Number">
        </div>
    </form>
</div>

<!---------------FORM DIALOG SEARCH BUTTON------------------------------------------------->
<div id="dlg-buttons2">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="cariGO()" style="width:90px">Search</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dialogSearch').dialog('close')" style="width:90px">Cancel</a>
</div>
