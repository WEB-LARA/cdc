<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="GTU_dialog" class="easyui-dialog" style="width:75%;height:62%;padding:10px 20px" data-options="iconCls:'icon-tip',closable:false" closed="true" buttons="#dlg-buttonsPenambah">
    <form id="GTU_form" class="easyui-form" action="#" method="post">
      <table>
        <tr>
          <td>
            <center> Check Num </center>
          </td>
          <td>
            <center> Bank Name </center>
          </td>
<!--
          <td>
            <center> Bank Account Num</center>
          </td>
-->
          <td>
            <center> Check Amount </center>
          </td>
        </tr>

        <tr>
          <td>
            <input id="checkNum" name="CDC_GTU_NUMBER" class="easyui-textbox" style="width:150px" tabindex="1" required> &nbsp
          </td>
          <td>
            <input id="bankName" name="BANK_NAME" class="easyui-combobox" style="width:150px" tabindex="2" > &nbsp
          </td>
<!--
          <td>
            <input id="bankAccountNum" name="BANK_ACCOUNT_NUM" class="easyui-textbox" style="width:250px" tabindex="-1" disabled> &nbsp
          </td>
-->
          <td>
            <input id="checkAmount" name="CDC_GTU_AMOUNT" class="easyui-numberbox" style="width:200px" tabindex="3" data-options="groupSeparator:','" required > &nbsp
          </td>
          <td id="td_GtuId">
            <input id="GtuId" name="CDC_GTU_ID" class="easyui-textbox" style="width:150px" tabindex="-1" >
          </td>

          <td>
            <a href="#" id="btnSaveGTU" class="easyui-linkbutton" iconCls="icon-save" batchid="" onclick="simpanGTU()">Simpan</a>

            <a href="#" id="GTUSelesai" class="easyui-linkbutton" iconCls="icon-ok" onclick="selesaiGTU()">Selesai</a>
          </td>
        </tr>

      </table>
    </form>


    <div id="bawah">
      <table id="tblInputGTU" > </table>
    </div>

</div>



<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="GTU_sent" class="easyui-dialog" style="width:75%;height:62%;padding:10px 20px" data-options="iconCls:'icon-tip'" closed="true" buttons="#dlg-buttonsPenambah">
    <div>
      <table id="tblSentGTU" > </table>
    </div>

    <div id="buttonSentGTU" align="left" style="	margin-left:5px; padding:5px;">
      <!-- <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:200px">Save Data</a> -->
      <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" onclick="btnSentGTUShift()" style="width:200px" id="pilGTU">Pilih</a>
    </div>
    <div align="right" style="padding:5px">
        Total GTU :
        <input id="totalGTU" name="GTU_TOTAL" class="easyui-numberbox" style="width:150px" tabindex="-1" readonly>
    </div>

</div>
