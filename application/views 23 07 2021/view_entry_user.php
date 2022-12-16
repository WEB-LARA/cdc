<script type="text/javascript" src="<?php echo base_url();?>assets/js/admin.js"></script>
<div id="body_user" class="easyui-panel" title="Entry User" data-options="iconCls:'icon-save',region:'center'" style="padding:10px; min-height:300px;">
    <div align="right" style="padding:5px; margin-right:2%">
        <b> &nbsp
            <span id="date_time" align="right"></span>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
        </b>
    </div>
    <div align="left" style="padding:5px; margin-right:2%">
		<th><a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="addUserBtn" data-options="iconCls:'icon-add'">Add</a></th>
	</div>
    <div id="p" class="easyui-panel" title="Search" 
        style="width:100%;height:120px;padding:10px;background:#fafafa;"
        data-options="iconCls:'icon-search',closable:false,collapsible:false,minimizable:false,maximizable:false">
        <div id="tb" style="padding:3px">
        	<table>
        		<tr>
        			<td style="min-width: 50px!important;">NIK</td>
        			<td><input type="text" id="src-nik-user" class="easyui-combobox" data-options="prompt:'NIK',valueField:'NIK',textField:'USER',url:'getUserNIK'" style="min-width:250px !important;"></td>
        			<td style="min-width:20px;"></td>
        		</tr>
        		<tr>
        			<td></td>
        			<td align="center">
        				<a href="#" class="easyui-linkbutton" plain="false" id="searchUser" data-options="iconCls:'icon-search'" style="min-width:100px !important;min-height:20px !important;">Search</a>
        				<a id="refresh" class="easyui-linkbutton" plain="false" data-options="iconCls:'icon-reload'" style="min-height:20px !important;min-width:100px !important;">Refresh</a>
        			</td>
        			<td></td>
        		</tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="UserGrid"></table>
	</div>
</div>

<div id="addEntryUser" class="easyui-window" title="Add User" style="width:500px;height:375px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 200px!important;">
         <input class="easyui-combobox" id="cabang-user" data-options="valueField:'BRANCH_CODE',textField:'BRANCH',url:'<?php echo base_url('Admin/getBranch'); ?>', onSelect: function(rec){
				var url = '<?php echo base_url('Admin/getDCode'); ?>/' +rec.BRANCH_CODE;
				$('#dc-user').combobox('reload', url);
				$('#dc-user').combobox('setValue', '');
        	}" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Gudang</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-combobox" id="dc-user" data-options="valueField:'DC_CODE',textField:'DC', panelHeight:'auto'" style="min-width: 250px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Role</td>
        <td style="min-width: 200px!important;">
         <input class="easyui-combobox" id="role-user" data-options="valueField:'ROLE_ID',textField:'ROLE',url:'<?php echo base_url('Admin/getRole'); ?>', panelHeight:'auto'"  style="width:200px; min-height:30px;" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">NIK</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="nik-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Nama User</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="nama-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Password</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="password" id="pass-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Active Flag</td>
        <td style="min-width: 150px!important;">
          <input type="checkbox" id="activeFlagSave"  disabled="true" checked="true">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Reset Flag</td>
        <td style="min-width: 150px!important;">
         <input type="checkbox" id="resetFlagSave"  disabled="true" checked="true">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
        <a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="save_add_user" data-options="iconCls:'icon-save'">Save</a>
        </td>
      </tr>
    </table>
</div>

<div id="editEntryUser" class="easyui-window" title="Edit User" style="width:500px;height:350px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 200px!important;">
        <input type="hidden" id="id-user" value="">
         <input class="easyui-combobox" id="ecabang-user" data-options="valueField:'BRANCH_CODE',textField:'BRANCH',url:'<?php echo base_url('Admin/getBranch'); ?>', onSelect: function(rec){
				var url = '<?php echo base_url('Admin/getDCode'); ?>/' +rec.BRANCH_CODE;
				$('#edc-user').combobox('reload', url);
				$('#edc-user').combobox('setValue', '');
        	}" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Gudang</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-combobox" id="edc-user" data-options="valueField:'DC_CODE',textField:'DC', panelHeight:'auto'" style="min-width: 250px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Role</td>
        <td style="min-width: 200px!important;">
         <input class="easyui-combobox" id="erole-user" data-options="valueField:'ROLE_ID',textField:'ROLE',url:'<?php echo base_url('Admin/getRole'); ?>', panelHeight:'auto'"  style="width:200px; min-height:30px;" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">NIK</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="enik-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Nama User</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="enama-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Active Flag</td>
        <td style="min-width: 150px!important;">
          <input type="checkbox" id="eactiveFlagSave">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Reset Flag</td>
        <td style="min-width: 150px!important;">
         <input type="checkbox" id="eresetFlagSave"  disabled="true">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
        <a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="save_edit_user" data-options="iconCls:'icon-save'">Save</a>
        </td>
      </tr>
    </table>
</div>

<div id="resetPass" class="easyui-window" title="Reset Password" style="width:250px;height:150px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
    	<tr>
	        <td><input type="hidden" id="resetid-user" value=""></td>
	        <td><input class="easyui-textbox" type="password" id="epass-user" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"> <a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="reset_pass" data-options="iconCls:'icon-save'">Reset</a></td>
        </tr>
    </table>
</div>



<script type="text/javascript">
	function myformatter(date){
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
	}
	function myparser(s){
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
</script>