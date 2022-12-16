<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/master.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_branch.js"></script>

<div class="panelWindows" style="position:relative;width:100%;height:100%;;overflow:100%;">
<div id="w" class="easyui-window" title="Master" data-options="inline:true, iconCls:'icon-Script'" style="width:85%;height:425px;padding:5px;">

<!---------------DATAGRID------------------------------------------------->
  <center>
    	<div style="margin:0px 0;"></div>
    	<table id="tblMasterBranch" title="Master Branch" class="easyui-datagrid" style="width:100%;height:auto"
              url="<?php echo base_url();?>/master/Branch/getData" sortName="" toolbar="#toolbar" pagination="true" rownumbers="true" sortOrder="asc"
              fitColumns="true" singleSelect="true">
    		<thead>
    			<tr>
    				<th data-options="field:'BRANCH_CODE',width:120, align:'center'">BRANCH CODE</th>
    				<th data-options="field:'BRANCH_NAME',width:200, align:'center'">BRANCH NAME</th>
    				<th data-options="field:'REG_ORG_ID',width:100,align:'center'">REG ORG ID</th>
					<th data-options="field:'FRC_ORG_ID',width:100,align:'center'">FRC ORG ID</th>
    				<th data-options="field:'ACTIVE_FLAG',width:55,align:'center'">ACTIVE FLAG</th>
					<!-- <th data-options="field:'ACTIVE_DATE',width:100,align:'center'">ACTIVE DATE</th> -->
					<th data-options="field:'INACTIVE_DATE',width:70,align:'center'">INACTIVE DATE</th>
    				<!-- <th data-options="field:'CREATION_DATE',width:100, align:'center'">CREATION DATE</th> -->
    				<!-- <th data-options="field:'LAST_UPDATE_DATE',width:100,align:'center'">LAST UPDATE</th> -->
    			</tr>
    		</thead>
    	</table>
    </center>

</div>
</div>


<!-- BUTTON IN TABELL -->
    <div id="toolbar">
      <a href="#" id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambah()">Add</a>
      <a href="#" id="ubah" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="ganti()">Edit</a>
      <a href="#" id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapus()">Delete</a>
      <a href="#" id="cari" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="cari()">Search</a>
    </div>
<!------------------------------------------------------------------------------------------------------>


<!-- OPEN ADD/EDIT DIALOG POP_UP -->>
    <div id="branch_dialog" class="easyui-dialog" style="width:350px;height:320px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <div class="ftitle">Detail Branch</div>
        <form id="branch_form" class="easyui-form" action="#" method="post">
          <table>
              <tr id="branch_id">
                  <td>BRANCH ID</td>
                  <td>:</td>
                  <td>
                    <input id="branchId" type="text" name="BRANCH_ID" class="easyui-textbox" tabindex="-1">
                  </td>
              </tr>
              <tr id="branch_code">
                  <td>BRANCH CODE :</td>
                  <td>:</td>
                  <td>
                    <input id="branchCode" name="BRANCH_CODE" class="easyui-textbox" maxlength="5" tabindex="1" required autofocus>
                  </td>
              </tr>
              <tr id="branch_name">
                  <td>BRANCH NAME :</td>
                  <td>:</td>
                  <td>
                    <input id="branchName" name="BRANCH_NAME" class="easyui-textbox" tabindex="1" required="true">
                  </td>
              </tr>
              <tr id="reg_org_id">
                  <td>REG ORG ID :</td>
                  <td>:</td>
                  <td>
                    <input id="regOrg" name="REG_ORG_ID" class="easyui-textbox" tabindex="2" required="true">
                  </td>
              </tr>
              <tr id="frc_org_id">
                  <td>FRC ORG ID :</td>
                  <td>:</td>
                  <td>
                    <input id="frcOrg" name="FRC_ORG_ID" class="easyui-textbox" tabindex="3" required="true">
                  </td>
              </tr>
              <tr id="active">
                  <td>ACTIVE :</td>
                  <td>:</td>
                  <td>
                    <select id="activeFlag" name="ACTIVE_FLAG" class="easyui-combobox" style="width:50px" >
                      <option value="Y" selected="selected">Y</option>
                      <option value="N">N</option>
                    </select>
                  </td>
              </tr>
              <tr id="inactive">
                  <td>INACTIVE DATE :</td>
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
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="save()" style="width:90px" >Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancel()" style="width:90px">Cancel</a>
    </div>




<!---------------FORM DIALOG SEARCH------------------------------------------------->
<div id="dialogSearch" class="easyui-dialog" style="width:440px;height:220px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons2">
    <div class="ftitle">Search</div>
    <form id="formSearch" method="#" novalidate>
        <div class="fitem">
            <label>BRANCH CODE :</label>
            <input type="text" name="BRANCH_CODE" id="BRANCH_CODE" class="easyui-textbox" prompt="Search by Code">
        </div>
        <div class="fitem">
            <label>BRANCH NAME :</label>
            <input type="text" name="BRANCH_NAME" id="BRANCH_NAME" class="easyui-textbox" prompt="Search by Name">
        </div>
    </form>
</div>

<!---------------FORM DIALOG SEARCH BUTTON------------------------------------------------->
<div id="dlg-buttons2">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="cariGO()" style="width:90px">Search</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dialogSearch').dialog('close')" style="width:90px">Cancel</a>
</div>
