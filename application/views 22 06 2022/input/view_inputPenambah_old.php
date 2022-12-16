<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="penambah_dialog" class="easyui-dialog" style="width:75%;height:62%;padding:10px 20px" closed="true" buttons="#dlg-buttonsPenambah" data-options="closable:false">
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
            <input id="trxTypePenambah" name="TRX_TYPE" class="easyui-combobox" style="width:150px" tabindex="1" required> &nbsp
          </td>
          <td>
            <input type="hidden" value="<?php echo date('d-m-Y'); ?>" id="sys_date">
            <input id="trxDatePenambah" name="TRX_DATE" class="easyui-datebox" style="width:150px" tabindex="2"  > &nbsp
          </td>
          <td>
            <input id="descPenambah" type="text" name="STORE_CODE" class="easyui-textbox" style="width:250px" tabindex="3"> &nbsp
          </td>
          <td>
            <input id="amountPenambah" name="AMOUNT" class="easyui-numberbox" style="width:200px" tabindex="4" data-options="groupSeparator:','" required > &nbsp
          </td>

          <td>
            <a href="#" id="btnSavePenambah" class="easyui-linkbutton" iconCls="icon-save" onclick="simpanPenambah()">Simpan</a>

            <a href="#" id="tambahSelesai" class="easyui-linkbutton" iconCls="icon-ok" onclick="selesaiPenambah()">Selesai</a>
          </td>
        </tr>

      </table>
    </form>


    <div id="bawah">
      <table id="tblInputPenambah" > </table>
    </div>

</div>
