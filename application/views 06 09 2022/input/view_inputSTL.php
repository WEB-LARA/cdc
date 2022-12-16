<div id="modal-stl" class="easyui-window" title="Data Setoran Lain - lain" style="width:1250px;height:400px;position:top;"
 data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150,closed:true">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'north',split:true" style="height:80px; padding:10px;">
			<table>
				<tr>
					<td>Category</td>
					<td>Toko</td>
					<td>Description</td>
					<td>Transaction date</td>
					<td>Total amount</td>
					<td style="padding-right: 10px;">Transfer</td>
					<td></td>
				</tr>
				<tr>
					<td style="padding-right: 10px;">
						<input type="hidden" id="stl-id" value="">
						<input type="hidden" id="rec-id" value="">
						<input id="stl-category" class="easyui-combobox" data-options="valueField:'CDC_MASTER_STL_ID',textField:'DESCRIPTION',url:'<?php echo base_url(); ?>InputBatch/get_master_stl/'" style="width: 125px">
					</td>
					<td>
						<input type="hidden" id="stl_store_code" value="">
						<input id="stl-toko" type="text" name="STL_TOKO" class="easyui-textbox" style="width:300px;" >
					</td>
					<td style="padding-right: 10px;">
						<input id="stl-desc" class="easyui-textbox" style="width: 200px">
					</td>
					<td style="padding-right: 10px;">
						<input id="stl-date" class="easyui-datebox" type="text" style="width: 125px">
					</td>
					<td style="padding-right: 10px;">
						<input id="stl-amount" class="easyui-numberbox" data-options="groupSeparator:','">
					</td>
					<td style="padding-right: 10px;">
						<input type="hidden" id="stl-acc-id" value="">
            			<input type="hidden" id="stl-mutation-date" value="">					
						<input id="stl-stn-flag" type="checkbox" value="0" onclick="" tabindex="-1">
					</td>
					<td>
						<a id="save-stl" class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="min-width: 120px;"><u>S</u>ave</a>
						<a id="clear-stl" class="easyui-linkbutton" data-options="iconCls:'icon-clear'" style="min-width: 25px; display: none;"></a>
					</td>
				</tr>
			</table>
		</div>
		<div data-options="region:'center'">
			<table class="easyui-datagrid" id="data-trx-stl" style="height:100%;width:100%;"></table>
		</div>
	</div>
</div>

<div id="form-bank-stl" class="easyui-window" title="Mutation Date dan Bank STL Transfer" style="width:360px;height:200px; padding:10px;" data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false,closed:true">
	<table>
		<tr>
			<td style="min-width: 100px!important;">Bank</td>
			<td style="min-width: 150px!important;">
				<input id="stl-bank" class="easyui-combobox" data-options="valueField:'BANK_ID',textField:'BANK_NAME',url:'<?php echo base_url(); ?>InputDeposit/get_bank_stn'" style="min-width: 200px; min-height:30px;">
			</td>
		</tr>
		<tr>
			<td style="min-width: 100px!important;">Bank Account</td>
			<td style="min-width: 150px!important;">
				<input id="stl-bank-acc" class="easyui-combobox" style="min-width: 200px; min-height:30px;">
			</td>
		</tr>
		<tr>
		<td style="min-width: 100px!important;">Tanggal Mutasi</td>
			<td style="min-width: 150px!important;">
				<input class="easyui-datebox" type="text" id="stl-mut-date" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
			</td>
		</tr>
		<tr>
			<td style="min-width: 100px!important;"></td>
			<td style="min-width: 150px!important;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub-stl-bank" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="can-stl-bank" href="" style="min-width:88px !important;min-height:30px !important;">Cancel</a>
			</td>
		</tr>
	</table>
</div>