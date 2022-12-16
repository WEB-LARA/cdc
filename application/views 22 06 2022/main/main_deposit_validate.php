<script type="text/javascript" src="<?php echo base_url();?>assets/js/inquiry_deposit.js"></script>
<div id="in_dep" class="easyui-panel" title="Inquiry Deposit" data-options="iconCls:'icon-save',region:'center'" style="padding:10px; min-height:300px;">
    <div align="right" style="padding:5px; margin-right:2%">
        <b> &nbsp
            <span id="date_time" align="right"></span>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
        </b>
    </div>
    <div id="p" class="easyui-panel" title="Search" 
        style="width:100%;height:180px;padding:10px;background:#fafafa;"
        data-options="iconCls:'icon-search',closable:false,collapsible:false,minimizable:false,maximizable:false">
        <div id="tb" style="padding:3px">
        	<table>
        		<tr>
        			<td style="min-width: 150px!important;">Bank</td>
        			<td><input type="text" id="bank_sc" class="easyui-combobox" style="min-width:200px !important;"></td>
        			<td style="min-width:20px;"></td>
        			<td style="min-width: 150px!important;">Mutation Date</td>
        			<td>
        				<input class="easyui-datebox" type="text" id="mutation_date_sc" data-options="required:false,disabled:false,prompt:'Mutation Date'" style="min-width:200px !important;"/>
        			</td>
        		</tr>
        		<tr>
        			<td>Deposit Number</td>
        			<td>
        				<input class="easyui-textbox" type="text" id="deposit_num_sc" data-options="required:false,disabled:false,prompt:'Deposit Number'" style="min-width:200px !important;"/>
        			</td>
        			<td style="min-width:20px;"></td>
        			<td style="min-width: 150px!important;">Status</td>
        			<td>
    				    <select id="status_sc" class="easyui-combobox" style="width:200px !important;">
					        <option></option>
                            <option value="N">New</option>
					        <option value="V">Validated</option>
                            <option value="T">Transfered</option>
					    </select>
        			</td>
        		</tr>
        		<tr>
        			<td>Deposit Date</td>
        			<td>
        				<input class="easyui-datebox" type="text" id="deposit_date_sc" data-options="required:false,disabled:false,prompt:'Deposit Date'" style="min-width:200px !important;"/>
        			</td>
        			<td style="min-width:20px;"></td>
        			<td style="min-width: 150px!important;">User Name</td>
        			<td>
        				<input class="easyui-textbox" type="text" id="username_sc" data-options="required:false,disabled:false,prompt:'Username'" style="min-width:200px !important;"/>
        			</td>
        		</tr>
        		<tr>
        			<td style="min-width:20px;"></td>
        			<td></td>
        			<td></td>
        			<td></td>
        			<td>
        				<a href="#" class="easyui-linkbutton" plain="false" onclick="doSearch()" data-options="iconCls:'icon-search'" style="min-width:116px !important;min-height:30px !important;">Search</a>
        				<a id="refresh" class="easyui-linkbutton" plain="false" data-options="iconCls:'icon-reload'" style="min-height:30px !important;min-width:76px !important;">Refresh</a>
        			</td>
        		</tr>
        	</table>
	    </div>
    </div>
	<div data-options="region:'center'" style="min-height:280px;">
		<table id="data_inquiry_deposit"></table>
	</div>
	<div data-options="region:'south',split:false" style="height:50px">
        Double Click to View.
  	</div>
</div>
<div id="detail_deposit" class="easyui-window" title="Data" style="width:1000px;height:500px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <div id="p" class="easyui-panel" title="Deposit" data-options="iconCls:'icon-script',region:'center'" style="padding:10px;width:982px;">
                <input type="hidden" id="user_role" value="<?php echo $role; ?>">
			      	<table style="margin-bottom: 10px;">
			      		<tr>
			      			<td>Bank</td>
			      			<td>
			      				<input class="easyui-textbox" type="text" name="bank" id="bank" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">Actual Total Selected</td>
			      			<td rowspan="2">
			      				<input class="easyui-numberbox" type="text" name="ats" id="ats" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">
			      				<a class="easyui-linkbutton" data-options="iconCls:'icon-print'" id="print_deposit" href="" style="min-width:150px !important;min-height:30px !important;">Print</a>
			      			</td>
			      		</tr>
			      		<tr>
			      			<td>Deposit Num</td>
			      			<td>
					        		<input class="easyui-textbox" type="text" name="deposit_num" id="deposit_num" data-options="required:true,disabled:true" style="min-width:200px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      		</tr>
			      		<tr>
			      			<td>Deposit Date &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
			      			<td>
			      				<input class="easyui-textbox" name="deposit_date" id="deposit_date" data-options="required:true,showSeconds:true,disabled:true" style="min-width:200px !important;">
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">Check Exc Total Selected</td>
			      			<td rowspan="2">
			      				<input class="easyui-numberbox" type="text" name="cts" id="cts" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">
			      				<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="val_deposit" href="" style="min-width:150px !important;min-height:30px !important; display:none;" depid="">Validate</a>
                                <a class="easyui-linkbutton" data-options="iconCls:'icon-undo'" id="rej_deposit" href="" style="min-width:150px !important;min-height:30px !important; display:none;" depid=""  depnum="">Reject</a>
			      			</td>
			      		</tr>
			      		<tr>
			      			<td>Mutation Date</td>
			      			<td><input type="text" class="easyui-textbox" name="mutation_date" id="mutation_date" data-options="required:true,disabled:true" style="min-width: 200px;"></td>
			      		</tr>
			      		<tr>
			      			<td>Status</td>
			      			<td>
			      				<input class="easyui-textbox" type="text" name="status" id="status" data-options="required:true,disabled:true,disabled:true" style="min-width:200px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">Deposit Total Selected</td>
			      			<td rowspan="2">
			      				<input class="easyui-numberbox" type="text" name="dts" id="dts" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
			      			</td>
			      			<td style="min-width:20px;"></td>
			      			<td rowspan="2">
			      				<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="del_deposit" href="" style="min-width:150px !important;min-height:30px !important; display:none;" depid="" depnum="">Delete</a>
                                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="transfer_deposit" href="" style="min-width:150px !important;min-height:30px !important; display:none;" depid=""  depnum="">Transfer</a>
			      			</td>
			      		</tr>
			      		<tr></tr>
			      	</table>
			      	<table id="data_batch"></table>
			    </div>
          </div>
    </div>
</div>

<div id="valdep" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Deposit Berhasil Divalidasi</h4></center>
          </div>
    </div>
</div>

<div id="deldep" class="easyui-window" title="Warning !" style="width:350px;height:180px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Apakah anda yakin menghapus deposit <p id="dep_num_del"></p> Klik 'Yes' untuk Menghapus.</h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="yes_del" depid="">Yes</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_del" >No</a></center>
          </div>
    </div>
</div>

<div id="rejdep" class="easyui-window" title="Warning !" style="width:350px;height:180px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Apakah anda yakin untu Reject deposit <p id="dep_num_rej"></p></h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="yes_rej" depid="">Yes</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_rej" >No</a></center>
          </div>
    </div>
</div>

<div id="transdep" class="easyui-window" title="Warning !" style="width:350px;height:180px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Apakah anda yakin untu Transfer deposit <p id="dep_num_trans"></p> ke Oracle?</h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="yes_trans" depid="" depnum="">Yes</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_trans" >No</a></center>
          </div>
    </div>
</div>

<div id="notdeldep" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:'icon-save',modal:true">
    <div class="easyui-layout" data-options="fit:true,closed:true,collapsible:false,minimizable:false,maximizable:false">
          <div data-options="region:'center'">
                <center><h4>Deposit Berhasil Dihapus</h4></center>
          </div>
    </div>
</div>

<div id="notrejdep" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:'icon-save',modal:true">
    <div class="easyui-layout" data-options="fit:true,closed:true,collapsible:false,minimizable:false,maximizable:false">
          <div data-options="region:'center'">
                <center><h4>Deposit Berhasil Direject</h4></center>
          </div>
    </div>
</div>

<div id="nottransdep" class="easyui-window" title="Caution" style="width:300px;height:165px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
          <div data-options="region:'center'">
                <center><h4>Deposit <span id="trans_suc"></span> berhasil dikirim, Mohon untuk menjalankan request "IDM CDC Sync DBR Web To Oracle" pada Oracle.</h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="ok_trans">OK</a></center>
          </div>
    </div>
</div>

<div id="report-dep-excel" class="easyui-window" title="Format Bank" style="width:280px;height:130px;padding:10px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true">
    <div class="easyui-layout" data-options="fit:true,closed:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
        <table>
            <tr>
                <td>Format&nbsp&nbsp&nbsp&nbsp</td>
                <td>
                    <select id="format-print-excel" class="easyui-combobox" style="width:150px;">
                        <option value="Default">Default</option>
                        <option value="Niaga">Niaga</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a id="btn-print-excel" class="easyui-linkbutton" data-options="iconCls:'icon-print'" style="width:150px;">Print</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<style>
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 65px;
    height: 65px;
    animation: spin 2s linear infinite;
    margin-left:35%;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div id="prog-trans-dep" class="easyui-window" title="Loading..." style="width:350px;height:200px;"
            data-options="iconCls:'icon-list',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true" style="padding: 20px;">
        <div class="loader"></div>
    </div>
</div>