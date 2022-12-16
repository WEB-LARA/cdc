<script type="text/javascript" src="<?php echo base_url();?>assets/js/master/master_user.js"></script>
<div id="in_dep" class="easyui-panel" title="Validate Kurset Virtual" data-options="iconCls:'icon-script',region:'center'" style="padding:10px; min-height:300px;">
    <div align="right" style="padding:5px; margin-right:2%">
        <b> &nbsp
            <span id="date_time" align="right"></span>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
        </b>
    </div>
    <div id="p" class="easyui-panel" title="Search" 
        style="width:100%;height:135px;padding:10px;background:#fafafa;"
        data-options="iconCls:'icon-search',closable:false,collapsible:false,minimizable:false,maximizable:false">
        <div id="tb" style="padding:3px">
        	<table>
        		<tr>
        			<td>NIK</td>
                    <td style="min-width:20px;"></td>
        			<td>
        				<input class="easyui-textbox" type="text" id="sc-user-nik" data-options="required:false,disabled:false" style="min-width:150px !important;"/>
        			</td>
        		</tr>
        		<tr>
        			<td></td>
                    <td style="min-width:20px;"></td>
        			<td>
        				<a href="#" class="easyui-linkbutton" plain="false" id="sc-user" data-options="iconCls:'icon-search'" style="min-width:150px !important;">Search</a>
        			</td>
        		</tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="min-width:1050px;"></td>
                    <td>
                    </td>
                    <td>
                        <a class="easyui-linkbutton" plain="false" id="add-user" data-options="iconCls:'icon-add'" style="min-width:150px !important;">Add User</a>
                    </td>
                </tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="data-user"></table>
	</div>
</div>

<div id="modal-add-user" class="easyui-window" title="Add User" style="width:350px;height:270px;position:top;"
        data-options="iconCls:'icon-add',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150">
    <div class="easyui-layout" data-options="fit:true" style="padding: 20px;">
        <table>
            <tr>
                <td style="width:80px">Username</td>
                <td>
                    <input class="easyui-textbox" style="width:200px" id="username">
                </td>
            </tr>
            <tr>
                <td style="width:80px">NIK</td>
                <td>
                    <input class="easyui-textbox" style="width:200px" id="nik">
                </td>
            </tr>
            <tr>
                <td style="width:80px">Password</td>
                <td>
                    <input class="easyui-textbox" type="password" style="width:200px" id="pass">
                </td>
            </tr>
            <tr>
                <td style="width:80px">Role</td>
                <td>
                    <input class="easyui-combobox" style="width:200px" id="role">
                </td>
            </tr>
            <tr>
                <td style="width:80px">Branch</td>
                <td>
                    <input class="easyui-combobox" style="width:200px" id="branch">
                </td>
            </tr>
            <tr>
                <td style="width:80px">DC</td>
                <td>
                    <input class="easyui-combobox" style="width:200px" id="dc">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href="" class="easyui-linkbutton" style="width:150px;" id="sub-user">Submit</a>
                </td>
            </tr>
       </table>
    </div>
</div>