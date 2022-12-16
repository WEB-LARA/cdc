<div id="input-pengganti" class="easyui-window" title="Data Penggantian" style="width:300px;height:250px;position:top;padding:10px;" data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150,closable:false,closed:true">
	<table>
		<tr>
			<td>Shift 1</td>
			<td style="min-width: 20px;"></td>
			<td>
				<input type="hidden" id="pengid[1]">
				<input class="easyui-numberbox" style="width:150px" id="peng[1]" data-options="min:0,groupSeparator:','">
			</td>
		</tr>
		<tr>
			<td>Shift 2</td>
			<td style="min-width: 20px;"></td>
			<td>
				<input type="hidden" id="pengid[2]">
				<input class="easyui-numberbox" style="width:150px" id="peng[2]" data-options="min:0,groupSeparator:','">
			</td>
		</tr>
		<tr>
			<td>Shift 3</td>
			<td style="min-width: 20px;"></td>
			<td>
				<input type="hidden" id="pengid[3]">
				<input class="easyui-numberbox" style="width:150px" id="peng[3]" data-options="min:0,groupSeparator:','">
			</td>
		</tr>
		<tr>
			<td><b>TOTAL</b></td>
			<td style="min-width: 20px;"></td>
			<td>
				<input class="easyui-numberbox" style="width:150px" id="total-pengganti-amt" data-options="min:0,groupSeparator:','" disabled="true">
			</td>
		</tr>
		<tr>
			<td colspan="7" align="right">
				<a id="save-pengganti" class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="min-width: 150px;"><u>S</u>ave</a>
			</td>
		</tr>
	</table>
</div>