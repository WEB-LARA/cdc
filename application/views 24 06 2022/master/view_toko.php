<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/master.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_toko.js"></script>

<div class="panelWindows" style="position:relative;width:100%;height:100%;;overflow:auto;">
<div id="w" class="easyui-window" title="Master" data-options="inline:true, iconCls:'icon-Script'" style="width:85%;height:425px;padding:5px;">

<!---------------DATAGRID------------------------------------------------->
  <center>
    	<div style="margin:0px 0;"></div>
    	<table id="tblMasterToko" title="Master Toko" class="easyui-datagrid" style="width:100%;height:auto"
              url="<?php echo base_url();?>/master/Toko/getData" sortName="" toolbar="#toolbar" pagination="true" rownumbers="true" sortOrder="asc"
              fitColumns="true" singleSelect="true">
    		<thead>
    			<tr>
    				<th data-options="field:'STORE_CODE',width:120, align:'center'">STORE CODE</th>
    				<th data-options="field:'STORE_NAME',width:250, align:'center'">STORE NAME</th>
    				<th data-options="field:'STORE_TYPE',width:100,align:'center'">STORE TYPE</th>
		        <th data-options="field:'STORE_ADDRESS',width:100,align:'center'">STORE ADDRESS</th>
    				<th data-options="field:'BRANCH',width:100,align:'center'">BRANCH</th>
	        	<th data-options="field:'ACTIVE_FLAG',width:100,align:'center'">ACTIVE</th>
            <th data-options="field:'INACTIVE_DATE',width:100,align:'center'">INACTIVE DATE</th>
    			</tr>
    		</thead>
    	</table>
    </center>

</div>
</div>


<!-- BUTTON IN TABELL -->
    <div id="toolbar">
      <a href="#" id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambah()">Add</a>
      <a href="#" id="ubah"   class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="ganti()">Edit</a>
      <a href="#" id="hapus"  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="hapus()">Delete</a>
      <a href="#" id="cari"   class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="cari()">Search</a>
    </div>
<!------------------------------------------------------------------------------------------------------>


<!-- OPEN ADD/EDIT DIALOG POP_UP -->>
    <div id="toko_dialog" class="easyui-dialog" style="width:440px;height:350px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <div class="ftitle">Detail Store</div>
        <form id="toko_form" class="easyui-form" action="#" method="post">
          <table>
            <tr id="store_id">
                <td>STORE ID </td>
                <td>:</td>
                <td>
                  <input id="storeId" name="STORE_ID" class="easyui-textbox" tabindex="-1">
                </td>
            </tr>
            <tr id="store_code">
                <td>STORE CODE :</td>
                <td>:</td>
                <td>
                  <input id="storeCode" name="STORE_CODE" class="easyui-textbox" tabindex="1" required autofocus validType="length[1,8]">
                </td>
            </tr>
            <tr id="store_name">
                <td>STORE NAME :</td>
                <td>:</td>
                <td>
                  <input id="storeName" name="STORE_NAME" class="easyui-textbox" tabindex="2" required="true">
                </td>
            </tr>
            <tr id="store_type">
                <td>STORE TYPE :</td>
                <td>:</td>
                <td>
                  <input id="storeType" name="STORE_TYPE" class="easyui-textbox" tabindex="3" required="true">
                </td>
            </tr>
            <tr id="store_address">
                <td>STORE ADDRESS :</td>
                <td>:</td>
                <td>
                  <input id="storeAddress" name="STORE_ADDRESS" class="easyui-textbox" tabindex="4" required="true">
                </td>
            </tr>
            <tr id="branch_tr">
                <td>BRANCH :</td>
                <td>:</td>
                <td>
                  <input id="branch" name="BRANCH" value="aa" >
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
                   <input id="inactiveDate" name="INACTIVE_DATE" class="easyui-datebox" required="false" disabled>
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
            <label>BRANCH :</label>
            <select id="BRANCH_SEARCH" name="BRANCH" class="easyui-combobox" style="width:160px" tabindex="5" required="true">
              <?php
                foreach($branch as $row){
                  echo "<option selected value=".$row->BRANCH_ID.">".$row->BRANCH_CODE." - ".$row->BRANCH_NAME."</option>";
                }
              ?>
            </select>
        </div>
        <div class="fitem">
            <label>STORE CODE :</label>
            <input id="STORE_CODE" name="STORE_CODE"  class="easyui-textbox" prompt="Search by STORE CODE">
        </div>
        <div class="fitem">
            <label>ACTIVE DATE :</label>
            <input id="ACTIVE_DATE" type="date" name="ACTIVE_DATE" class="easyui-datebox" prompt="Search by Active Date">
        </div>
    </form>
</div>

<!---------------FORM DIALOG SEARCH BUTTON------------------------------------------------->
<div id="dlg-buttons2">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="cariGO()" style="width:90px">Search</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dialogSearch').dialog('close')" style="width:90px">Cancel</a>
</div>
