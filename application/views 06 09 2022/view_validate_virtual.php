<script type="text/javascript" src="<?php echo base_url();?>assets/js/validate_virtual.js"></script>
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
        			<td>Deposit Number</td>
                    <td style="min-width:20px;"></td>
        			<td>
        				<input class="easyui-textbox" type="text" id="sc-deposit-num" data-options="required:false,disabled:false,prompt:'Deposit Number'" style="min-width:150px !important;"/>
                        <input type="hidden" id="sc-deposit-id" value="">
        			</td>
        		</tr>
        		<tr>
        			<td></td>
                    <td style="min-width:20px;"></td>
        			<td>
        				<a href="#" class="easyui-linkbutton" plain="false" id="sc-kurset-vir" data-options="iconCls:'icon-search'" style="min-width:150px !important;">Search</a>
        			</td>
        		</tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="min-width:850px;"></td>
                    <td>
                        <a class="easyui-linkbutton" plain="false" id="print-vir" data-options="iconCls:'icon-print'" style="min-width:150px !important;">Print</a>
                    </td>
                    <td>
                        <a class="easyui-linkbutton" plain="false" id="submit-vir" data-options="iconCls:'icon-redo'" style="min-width:150px !important;">Submit</a>
                    </td>
                </tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="data-kurset-virtual"></table>
	</div>
</div>

<div id="modal_edit_deskripsi_kurset" class="easyui-window" title="Edit Deskripsi" style="width:450px;height:140px;position:top;"
        data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150">
    <div class="easyui-layout" data-options="fit:true" style="padding: 20px;">
        <table>
            <tr>
                <td style="width:80px">Deskripsi</td>
                <td>
                    <input class="easyui-textbox" style="width:300px" id="det-deskripsi">
                    <input type="hidden" id="det-id" value="">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href="" class="easyui-linkbutton" style="width:150px;" id="sub-deskripsi">Submit</a>
                </td>
            </tr>
       </table>
    </div>
</div>