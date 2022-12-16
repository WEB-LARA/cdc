<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/entryBatch.css">

<div id="atas" class="easyui-panel" title="Receipt Batch" style="position:static;width:auto;height:35%;">
  <form id="branch_form" class="easyui-form" action="#" method="post">
    <div id="kiri" align="" style="width:45%;">
        <table> <br>
          <tr id="bank_name">
              <td>Bank Name </td>
              <td> : </td>
              <td>
                <input id="bankName" type="text" name="BANK_NAME" class="easyui-textbox" style="width:300px" tabindex="1" required autofocus>
              </td>
          </tr>
          <tr id="batch_type">
              <td>Batch Type </td>
              <td> : </td>
              <td>
                <input id="batchType" type="text" name="BATCH_TYPE" class="easyui-combobox" style="width:100px" tabindex="2" required >
                <input id="batchType" type="text" name="BATCH_TYPE" class="easyui-textbox" style="width:195px" tabindex="-1" disabled >
              </td>
          </tr>
          <tr id="batch_number">
              <td>Batch Number </td>
              <td> : </td>
              <td>
                <input id="batchNumber" type="text" name="BATCH_NUMBER" class="easyui-textbox" style="width:300px" tabindex="-1"  >
              </td>
          </tr>
          <tr id="ref_num">
              <td>Ref Num/No Sticker </td>
              <td> : </td>
              <td>
                <input id="refNum" type="text" name="REF_NUM" class="easyui-textbox" style="width:300px" tabindex="3" required >
              </td>
          </tr>
          <tr id="tgl_batch">
              <td>Tgl Batch/Tgl Slip Bank </td>
              <td> : </td>
              <td>
                <input id="tglBatch" type="text" name="TGL_BATCH" class="easyui-datebox" tabindex="4" required >
              </td>
          </tr>
          <tr id="bank_name">
              <td>Status </td>
              <td> : </td>
              <td>
                <input id="status" type="text" name="STATUS" class="easyui-textbox" tabindex="-1" disabled >
              </td>
          </tr>
        </table>
    </div>


    <div id="kanan" style="width:45%; position:static">
        <table> <br>
          <tr id="create_user">
              <td>Create User Name </td>
              <td> : </td>
              <td>
                <input id="createUser" type="text" name="CREATE_USER" class="easyui-textbox" style="width:300px" tabindex="-1" >
              </td>
          </tr>
          <tr id="actual_grand_total">
              <td>Actual Grand Total </td>
              <td> : </td>
              <td>
                <input id="actualGrandTotal" type="text" name="ACTUAL_GRAND_TOTAL" class="easyui-textbox" style="width:300px" tabindex="-1" >
              </td>
          </tr>
          <tr id="total_giro">
              <td>Total Giro Tukar Uang </td>
              <td> : </td>
              <td>
                <input id="total_giro" type="text" name="TOTAL_GIRO" class="easyui-textbox" style="width:300px" tabindex="-1" >
              </td>
          </tr>
          <tr id="total_setor">
              <div id="total">
                <td> <b>Total Yang Harus Disetor </b></td>
                <td> : </td>
                <td>
                  <input id="totalSetor" type="text" name="TOTAL_SETOR" class="easyui-textbox" style="width:300px; height:80px;" tabindex="-1" >
                </td>
              </div>
          </tr>
        </table>
    </div>
  </form>
</div>

<!--------------------------------------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------------------------------------->

<div id="bawah">
  <table id="tblMasterBank" title="Input Sales Scan" class="easyui-datagrid" style="width:auto;height:auto"
          url="<?php echo base_url();?>/master/Bank/getData" sortName="" toolbar="#toolbar" pagination="false" rownumbers="true" sortOrder="asc"
          fitColumns="true" singleSelect="true" data-options="iconCls: 'icon-add'">

    <thead>
      <tr>
        <th data-options="field:'a',align:'center', width:100">Code</th>
        <th data-options="field:'b',align:'center', width:100">Store Code</th>
        <th data-options="field:'c',align:'center', width:150">Tgl Sales</th>
        <th data-options="field:'d',align:'center', width:100">Sales Flag</th>
        <th data-options="field:'e',align:'center', width:150">Cash + Penggantian</th>
        <th data-options="field:'f',align:'center', width:150">Total Penambahan</th>
        <th data-options="field:'g',align:'center', width:150">Total Actual Amount</th>
        <th data-options="field:'h',align:'center', width:150">Total Pengurangan</th>
        <th data-options="field:'i',align:'center', width:150">Total Voucher</th>
      </tr>
    </thead>


  </table>
</div>

<div id="buttonSubmit" align="right">
  <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Next >></a>

</div>
