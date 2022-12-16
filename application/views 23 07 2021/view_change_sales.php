<script type="text/javascript" src="<?php echo base_url();?>assets/js/change_sales.js"></script>
<div id="in_dep" class="easyui-panel" title="Change Sales" data-options="iconCls:'icon-script',region:'center'" style="padding:10px; min-height:300px;">
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
        			<td>Batch Number</td>
                    <td style="min-width:20px;"></td>
        			<td>
        				<input class="easyui-textbox" type="text" id="sc-batch-num" data-options="required:true,disabled:false" style="min-width:150px !important;"/>
                        <input type="hidden" id="sc-batch-id" value="">
        			</td>
                    <td style="min-width:20px;"></td>
                    <td>Sales Date</td>
                    <td style="min-width:20px;"></td>
                    <td>
                        <input class="easyui-datebox" type="text" id="sc-sales-date" data-options="required:false,disabled:false" style="min-width:150px !important;"/>
                    </td>
        		</tr>
                <tr>
                    <td>Store Code</td>
                    <td style="min-width:20px;"></td>
                    <td>
                        <input id="sc-store-code" class="easyui-combobox" data-options="valueField:'STORE_ID',textField:'STORE',url:'<?php echo base_url(); ?>InquiryBatch/get_combo_store'" style="min-width:150px !important;">
                    </td>
                    <td style="min-width:20px;"></td>
                    <td></td>
                    <td style="min-width:20px;"></td>
                    <td>
                        <a href="#" class="easyui-linkbutton" plain="false" id="sc-clear-rec" style="min-width:45px !important;">Clear</a>
                        <a href="#" class="easyui-linkbutton" plain="false" id="sc-sales-rec" data-options="iconCls:'icon-search'" style="min-width:97px !important;">Search</a>
                    </td>
                </tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="data-sales-receipts"></table>
	</div>
</div>

<div id="modal_edit_sales_date" class="easyui-window" title="Edit Sales Date" style="width:300px;height:230px;position:top;"
        data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150">
    <div class="easyui-layout" data-options="fit:true" style="padding: 20px;">
        <table>
            <tr>
                <td style="width:80px">Store Code</td>
                <td>
                    <input class="easyui-textbox" style="width:150px" id="rec-store-code" disabled="true">
                    <input type="hidden" id="ch-rec-id" value="">
                    <input type="hidden" id="ch-stn-flag" value="">
                    <input type="hidden" id="ch-act-sales-flag" value="">
                </td>
            </tr>
            <tr>
                <td style="width:80px">Sales Date</td>
                <td>
                    <input class="easyui-textbox" style="width:150px" id="rec-sales-date" disabled="true">
                </td>
            </tr>
       </table>
       <hr>
       <table>
            <tr>
                <td style="width:80px">Store Code</td>
                <td>   
                    <input id="rec-store-code-new" class="easyui-combobox" data-options="valueField:'STORE_ID',textField:'STORE',url:'<?php echo base_url(); ?>InquiryBatch/get_combo_store'" style="min-width:150px !important;">
                </td>
            </tr>
            <tr>
                <td style="width:80px">Sales Date</td>
                <td>
                    <input class="easyui-datebox" style="width:150px" id="rec-sales-date-new">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href="" class="easyui-linkbutton" style="width:150px;" id="sub-change-sales">Submit</a>
                </td>
            </tr>
       </table>
    </div>
</div>