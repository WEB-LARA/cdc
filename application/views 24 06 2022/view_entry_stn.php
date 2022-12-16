<script type="text/javascript" src="<?php echo base_url();?>assets/js/admin.js"></script>
<div id="body_stn" class="easyui-panel" title="Entry Bank STN" data-options="iconCls:'icon-save',region:'center'" style="padding:10px; min-height:300px;">
    <div align="right" style="padding:5px; margin-right:2%">
        <b> &nbsp
            <span id="date_time" align="right"></span>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
        </b>
    </div>
    <div align="left" style="padding:5px; margin-right:2%">
		<th><a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="addBankBtn" data-options="iconCls:'icon-add'">Add</a></th>
	</div>
    <div id="p" class="easyui-panel" title="Search" 
        style="width:100%;height:120px;padding:10px;background:#fafafa;"
        data-options="iconCls:'icon-search',closable:false,collapsible:false,minimizable:false,maximizable:false">
        <div id="tb" style="padding:3px">
        	<table>
        		<tr>
        			<td style="min-width: 50px!important;">Bank Account</td>
        			<td><input type="text" id="src-bank-acc" class="easyui-combobox" data-options="prompt:'NO BANK AKUN',valueField:'BANK_ACCOUNT_ID',textField:'BANK',url:'getBankAcc'" style="min-width:350px !important;"></td>
        			<td style="min-width:20px;"></td>
        		</tr>
        		<tr>
        			<td></td>
        			<td align="center">
        				<a href="#" class="easyui-linkbutton" plain="false" id="searchBank" data-options="iconCls:'icon-search'" style="min-width:100px !important;min-height:20px !important;">Search</a>
        				<a id="refreshbank" class="easyui-linkbutton" plain="false" data-options="iconCls:'icon-reload'" style="min-height:20px !important;min-width:100px !important;">Refresh</a>
        			</td>
        			<td></td>
        		</tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="BankGrid"></table>
	</div>
</div>

<div id="addEntryBankSTN" class="easyui-window" title="Add Bank STN" style="width:450px;height:275px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 200px!important;">
         <input class="easyui-combobox" id="cabang-bank" data-options="valueField:'BRANCH_ID',textField:'BRANCH',url:'<?php echo base_url('Admin/getBranchWithID'); ?>'" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-combobox" id="master-bank" data-options="valueField:'BANK_ID',textField:'BANK_NAME',url:'<?php echo base_url('Admin/getMasterBank'); ?>'" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Nama Akun Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="nama-bank" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">No Akun Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" id="no-bank" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Active Flag</td>
        <td style="min-width: 150px!important;">
          <input type="checkbox" id="activeFlagSave"  disabled="true" checked="true">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
        <a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="save_add_bank" data-options="iconCls:'icon-save'">Save</a>
        </td>
      </tr>
    </table>
</div>

<div id="editEntryBankSTN" class="easyui-window" title="Add Bank STN" style="width:450px;height:275px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 200px!important;">
        <input type="hidden" id="bank-id" value="">
         <input class="easyui-combobox" id="ecabang-bank" data-options="valueField:'BRANCH_ID',textField:'BRANCH',url:'<?php echo base_url('Admin/getBranchWithID'); ?>'" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-combobox" id="emaster-bank" data-options="valueField:'BANK_ID',textField:'BANK_NAME',url:'<?php echo base_url('Admin/getMasterBank'); ?>'" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Nama Akun Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" type="text" id="enama-bank" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">No Akun Bank</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-textbox" id="eno-bank" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Active Flag</td>
        <td style="min-width: 150px!important;">
          <input type="checkbox" id="eactiveFlagSave">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
        <a href="#" class="easyui-linkbutton" style="width:100px;height:25px" id="save_edit_bank" data-options="iconCls:'icon-save'">Save</a>
        </td>
      </tr>
    </table>
</div>