<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="penambah_dialog" class="easyui-dialog" style="width:70%;height:62%;padding:10px 20px" closed="true" buttons="#dlg-buttons">
    <form id="penambah_form" class="easyui-form" action="#" method="post">
      <table>
        <tr>
          <td>
            <center> Trx Type Name </center>
          </td>
          <td>
            <center> Trx Date </center>
          </td>
          <td>
            <center> Description</center>
          </td>
          <td>
            <center> Amount </center>
          </td>
        </tr>

        <tr>
          <td>
            <input id="trxType" name="TRX_TYPE" class="easyui-combobox" style="width:150px" tabindex="1" required > &nbsp
          </td>
          <td>
            <input id="trxDate" name="TRX_DATE" class="easyui-datebox" style="width:150px" tabindex="2" required > &nbsp
          </td>
          <td>
            <input id="desc" type="text" name="STORE_CODE" class="easyui-textbox" style="width:250px" tabindex="3"> &nbsp
          </td>
          <td>
            <input id="amount" name="AMOUNT" class="easyui-textbox" style="width:200px" tabindex="4" required > &nbsp
          </td>
          <td>
            <a href="#test" id="btnSave" class="easyui-linkbutton" data-options="iconCls:'icon-ok'"></a>
          </td>
        </tr>

      </table>
    </form>

<!------------------------------------------------------------------------------------------------------>
<!-- BUTTON DIALOG PENAMBAH POP_UP -->
    <div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="save()" style="width:90px" >Selesai</a>
    </div>
<!------------------------------------------------------------------------------------------------------>

    <div id="bawah">
      <table id="tblMasterBank" title="" class="easyui-datagrid" style="width:auto;height:auto"
              url="<?php echo base_url();?>master/Branch/getData" sortName="" toolbar="#toolbar" pagination="false" rownumbers="true" sortOrder="asc"
              fitColumns="true" singleSelect="true">

        <thead>
          <tr>
            <th data-options="field:'a',align:'center', width:150">Trx Type Name</th>
            <th data-options="field:'b',align:'center', width:150">Trx Date</th>
            <th data-options="field:'c',align:'center', width:250">Description</th>
            <th data-options="field:'e',align:'center', width:200">Amount</th>
          </tr>
        </thead>
      </table>
    </div>

</div>
