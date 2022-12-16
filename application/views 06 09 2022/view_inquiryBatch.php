<!-- START BODY -->
<div DATA-OPTIONS="region:'center'" style="height:90%;">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/InquiryBatch.js"></script>

  <div id="atas" class="easyui-panel" title="Inquiry Batch" style="padding:0px; position:static;width:100%;height:125px;" data-options="iconCls: 'icon-download'">
    <div align="right" style="padding:5px; margin-right:2%">
      <b> &nbsp
          <span id="date_time" align="right"></span>
                      <script type="text/javascript">window.onload = date_time('date_time');</script>
      </b>
    </div>

    <div align="left" style="padding:5px; margin-left:15px">
      <table>
        <tr>
          <td>
            Batch Number :
          </td>
          <td>
            <input class="easyui-textbox" type="text" name="batch_num" id="batch_num" /> &nbsp &nbsp
          </td>
          <td>
            Tanggal Batch :
          </td>
          <td>
            <input class="easyui-datebox" name="batch_date" id="batch_date"> &nbsp &nbsp
          </td>
          <td>
            Status :
          </td>
          <td>
            <select class="easyui-combobox" name="batch_status" id="batch_status" style="width:150px">
              <option value="N">NEW</option>
              <option value="V">VALIDATE</option>
              <option value="R">REJECT</option>
              <option value="T">TRANSFER</option>
            </select> &nbsp &nbsp
          </td>
          <td>
            Type :
          </td>
          <td>
            <select class="easyui-combobox" name="batch_type" id="batch_type" style="width:150px">
              <option value=""></option>
              <option value="STJ">STJ</option>
              <option value="STN">STN</option>
              <option value="KUR">KUR</option>
              <option value="KUN">KUN</option>
              <option value="STL-TN">STL-TN</option>
              <option value="STL-TR">STL-TR</option>
            </select> &nbsp &nbsp
          </td>
          <td>
            Created By :
          </td>
          <td>
            <input class="easyui-textbox" name="create_by" id="create_by"> &nbsp &nbsp
          </td>
          <td>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="cari" onclick="btnCari()" ></a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload'" id="reset" onclick="btnReset()" ></a>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div id="bawah">
    <div id="inquiryBatch">
      <table id="tblTrxBatch" style="width:auto;height:400px">

      </table>
    </div>

  </div>


  <div id="buttonValidate" align="left" style="	margin-left:5px; padding:5px;">
    <table width="100%">
      <tr>
        <td align="left">
          <a href="#" id='iquryPrint' class="easyui-linkbutton" data-options="iconCls:'icon-print'" onclick="btnPrintBatch()" style="width:200px">Print</a>
          <a href="#" id='inquiryValidate' class="easyui-linkbutton" data-options="iconCls:'icon-ok'" onclick="btnValidate()" style="width:200px;">Validate</a>
          <a href="#" id='transferSTN' class="easyui-linkbutton" data-options="iconCls:'icon-up'" onclick="btnTransfer()" style="width:200px;">Transfer (STN)</a>
        </td>
        <td align="right">
        <a href="#" id='inquiryReject' class="easyui-linkbutton" data-options="iconCls:'icon-undo'" onclick="btnRejectBatch()" style="align:right; width:100px;<?php if ($role <=1) {
            echo "display:none;";
          } ?>">Reject</a>
          <a href="#" id='inquiryDelete' class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" onclick="btnDeleteBatch()" style="align:right; width:100px;<?php if ($role <=1) {
            echo "display:none;";
          } ?>">Delete</a> &nbsp &nbsp
        </td>
      </tr>
    <table>
  </div>


</div>
</div> </div> <!-- CLOSE FOOTER -->

<!-- POP UP VIEW  -->
<div id="Batch_dialog" class="easyui-dialog" style="width:90%;height:420px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
  <div>
    <table id="tblEditReceipts" style="width:auto;height:350px">

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

<div id="prog-trans" class="easyui-window" title="Loading..." style="width:350px;height:200px;"
            data-options="iconCls:'icon-list',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true" style="padding: 20px;">
        <div class="loader"></div>
    </div>
</div>
