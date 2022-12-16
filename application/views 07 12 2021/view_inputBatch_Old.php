<!-- START BODY -->
<div DATA-OPTIONS="region:'center'" style="height:90%;">
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.hotkeys.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/inputBatch.js"></script>
  
  <?php echo $this->session->flashdata('form_shift'); ?>
  
  <?php echo $this->session->flashdata('data_batch_reject'); ?>

  <div id="atas" class="easyui-panel" title="Receipt Batch" style="padding:0px; position:static;width:100%;height:28%;" data-options="iconCls: 'icon-add'">
    <div align="right" style="padding:5px; margin-right:2%">
      &nbsp <input type="checkbox" id="sales_flag" name="salesFlag" value="1" onclick="flag(this)" tabindex="-1" checked> Sales Flag
      &nbsp <input type="hidden" id="CDC_REC_ID">
      &nbsp <input type="checkbox" id="stnFlag" name="stnFlag" value="0" onclick="" tabindex="-1"> STN
      &nbsp 

      <b> &nbsp
        <span id="date_time" align="right"></span>
        <script type="text/javascript">window.onload = date_time('date_time');</script>
      </b>
    </div> <br>
    <div id="topInput">
      <table align="center">
        <tr>
          <td>
            <b> Sales Scan code </b>
          </td>
          <td>
            <b>Store Code</b>
          </td>
          <td>
            <b>Store Name</b>
          </td>
          <td>
            <b>Tgl Sales</b>
          </td>

          <td>
            <b> Cash+ Penggantian </b>
          </td>
          <td>
            <b> Total Penambahan </b>
          </td>
          <td>
            <b> Total Pengurangan </b>
          </td>
          <td>
            <b> Input Voucher </b>
          </td>
          <td>
            &nbsp
          </td>
        </tr>

        <tr>
          <td>
            <input type="hidden" id="role-id" value="<?php echo $this->session->userdata('role_id'); ?>">
            <input id="scanCode" type="text" name="SCAN_CODE" class="easyui-textbox" style="width:120px; height:25px;" tabindex="1" required > &nbsp
          </td>
          <td>
            <input id="storeCode" type="text" name="STORE_CODE" class="easyui-textbox" style="width:120px; height:25px;" tabindex="-1" disabled > &nbsp
          </td>
          <td>
            <input id="storeName" type="text" name="STORE_NAME" class="easyui-textbox" style="width:120px; height:25px;" tabindex="-1" disabled > &nbsp
          </td>
          <td>
            <input id="tglSales" type="text" name="TGL_BATCH" class="easyui-datebox" style="width:100px; height:25px;" tabindex="-1" > &nbsp
          </td>

          <td>
            <input id="cashPenggantian" type="text" name="CHASH" class="easyui-numberbox" style="width:150px; height:25px;" tabindex="2" data-options="min:0,groupSeparator:','" > &nbsp
          </td>
          <td>
            <input id="totalPenambah" type="text" name="PENAMBAH" class="easyui-numberbox" style="width:150px; height:25px;" tabindex="3" data-options="min:0,groupSeparator:','" readonly> &nbsp
          </td>
          <td>
            <input id="totalPengurang" type="text" name="PENGURANG" class="easyui-numberbox" style="width:150px; height:25px;" tabindex="4" data-options="min:0,groupSeparator:','" readonly> &nbsp
          </td>
          <td>
            <input id="totalVoucher" type="text" name="VOUCHER" class="easyui-numberbox" style="width:150px; height:25px;" tabindex="5" data-options="groupSeparator:','" readonly> &nbsp
            <input type="hidden" id="tglMutasi" name="TGL_MUTASI" value="">
            <input type="hidden" id="bankAcc" name="BANK_ACCOUNT" value="">
          </td>
          <td>
            <a href="#" id="btnSave" class="easyui-linkbutton" onclick="receiptSave()" data-options="iconCls:'icon-save'"><u>S</u>ave</a>
            <a href="#" id="btnReset" class="easyui-linkbutton" data-options="iconCls:'icon-reload'"><u>R</u>eset</a>
          </td>
        </tr>
        <!--    </table> -->
<!------------------------------------------------------------------------------------------------------------------------------------------->

<!--    <table align="center" style="	padding:10px;"> -->

</table>


</div>
</div>


<div id="bawah">
  <table id="tblTrxReceipts" style="width:auto;height:275px">

  </table>
</div>


<div id="buttonSubmit" align="left" style="	margin-left:5px; padding:5px;">
  <!-- <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:200px">Save Data</a> -->
  <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="genBatch" onclick="btnGenerate()" style="width:200px;padding:10px;">Generate <u>B</u>atch</a>
  <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="savBatch" batchid="" onclick="btnSaveBatch()" style="display:none;width:200px;margin-top: 0 !important;max-height: 150px; padding-bottom: 10px;">Save Batch</a>
  <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="gtuBatch" batchid="" onclick="btnGTU()" style="width:200px;padding:10px;" id="input-GTU">Input <u>G</u>TU</a>
  <a class="easyui-linkbutton" data-options="iconCls:'icon-script'" style="width:200px;padding:10px;" id="input-kurset">Bayar <u>K</u>urset</a>
  <a class="easyui-linkbutton" data-options="iconCls:'icon-script'" style="width:200px;padding:10px;" id="input-stl">Setoran <u>L</u>ain - lain</a>
  <!-- <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-print'" onClick="btnPrint()" style="width:200px">Print</a> -->
</div>

<div id="resultKanan" align="right" style="margin-right:25px;margin-top:-40px;padding:0px;">
  JUMLAH SETOR R-STJ :
  <input id="totalSetor" name="Total" class="easyui-numberbox" style="width:200px; height:30px;" tabindex="-1" data-options="groupSeparator:','" disabled="true">
  <br>
  <br>
  JUMLAH SETOR F-STJ :
  <input id="totalSetorF" name="Total" class="easyui-numberbox" style="width:200px; height:30px;" tabindex="-1" data-options="groupSeparator:','" disabled="true">
  <br>
  <br>
  JUMLAH GTU :
  <input id="totalGTUInput" name="Total" class="easyui-numberbox" style="width:200px; height:30px;" tabindex="-1" data-options="groupSeparator:','" disabled="true">
  <br>
  <br>
  GRAND TOTAL STJ :
  <input id="grandTotal" name="Total" class="easyui-numberbox" style="width:200px; height:30px;" tabindex="-1" data-options="groupSeparator:','" disabled="true">
</div>

</div> <!-- CLOSE -->

</div> </div> <!-- CLOSE FOOTER -->

<div id="form_mutation_date" class="easyui-window" title="Mutation Date dan Bank STN" style="width:360px;height:200px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Bank</td>
        <td style="min-width: 150px!important;">
              <input id="in_bank" class="easyui-combobox" data-options="valueField:'BANK_ID',textField:'BANK_NAME',url:'<?php echo base_url(); ?>InputDeposit/get_bank_stn'" style="min-width: 200px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Bank Account</td>
        <td style="min-width: 150px!important;">
              <input id="in_bank_account" class="easyui-combobox" style="min-width: 200px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Tanggal Mutasi</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-datebox" type="text" id="in_mutation_date" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_mutation_date" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
          <a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="can_mutation_date" href="" style="min-width:88px !important;min-height:30px !important;">Cancel</a>
        </td>
      </tr>
    </table>
</div>

<div id="modal_input_kurset" class="easyui-window" title="Bayar Kurset" style="width:800px;height:400px;position:top;"
        data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true" style="height:60px; padding:10px;">
          <table>
            <tr>
              <td style="width:80px">TTK Num</td>
              <td>
                <input class="easyui-textbox" style="width:150px" id="ttk_num">
                <input type="hidden" id="branch_ttk" value="<?php echo $this->session->userdata('branch_code'); ?>">
              </td>
              <td>
                <a href="" class="easyui-linkbutton" data-options="iconCls:'icon-search'" style="width:150px;" id="search_ttk" hid="">Search</a>
              </td>
            </tr>
          </table>
        </div>
        <div data-options="region:'center'">
            <table class="easyui-datagrid" id="data_trx_kurset" style="height:100%;width:100%;"></table>
        </div>
    </div>
</div>

<div id="modal_detail_kurset" class="easyui-window" title="Input Kurset" style="width:800px;height:400px;position:top;"
        data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,top:150">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true" style="height:80px; padding:10px;">
          <table>
            <tr>
              <td style="width:80px">TTK Num</td>
              <td>
                <input class="easyui-textbox" style="width:150px" id="ttk_num_det" disabled="true">
                <input type="hidden" id="tth_hid" value="">
                <input type="hidden" id="kurn-acc-id" value="">
                <input type="hidden" id="kurn-mutation-date" value="">
              </td>
              <td style="width:80px">Total</td>
              <td>
                <input class="easyui-numberbox" style="width:150px" id="ttk_total_line" disabled="true" data-options="min:0,groupSeparator:','">
              </td>
              <td style="width:20px">
                <input type="checkbox" id="kurn-trf" value="0">
              </td>
              <td>
                Transfer
              </td>
              <td style="width:150px"></td>
              <td>
                <a href="" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px;float:right;" id="submit_kurset">Submit</a>
              </td>
            </tr>
            <tr>
              <td style="width:80px">TTK Date</td>
              <td>
                <input class="easyui-textbox" style="width:150px" id="ttk_date_det" disabled="true">
              </td>
              <td style="width:80px">Amount</td>
              <td>
                <input class="easyui-numberbox" style="width:150px" id="ttk_total_amount" data-options="min:0,groupSeparator:','">
              </td>
            </tr>
          </table>
        </div>
        <div data-options="region:'center'">
            <table class="easyui-datagrid" id="data_det_kurset" style="height:100%;width:100%;"></table>
        </div>
    </div>
</div>

<div id="form-bank-kurn" class="easyui-window" title="Mutation Date dan Bank Kurset Transfer" style="width:360px;height:200px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Bank</td>
        <td style="min-width: 150px!important;">
              <input id="kurn-bank" class="easyui-combobox" data-options="valueField:'BANK_ID',textField:'BANK_NAME',url:'<?php echo base_url(); ?>InputDeposit/get_bank_stn'" style="min-width: 200px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Bank Account</td>
        <td style="min-width: 150px!important;">
              <input id="kurn-bank-acc" class="easyui-combobox" style="min-width: 200px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Tanggal Mutasi</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-datebox" type="text" id="kurn-mut-date" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub-kurn-bank" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
          <a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="can-kurn-bank" href="" style="min-width:88px !important;min-height:30px !important;">Cancel</a>
        </td>
      </tr>
    </table>
</div>

<style>
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 65px;
    height: 65px;
    animation: spin 2s linear infinite;
    margin:10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div id="prog-trans" class="easyui-window" title="Loading..." style="width:170px;height:200px;"
            data-options="iconCls:'icon-list',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true" style="padding: 20px;">
        <div class="loader"></div>
    </div>
</div>
