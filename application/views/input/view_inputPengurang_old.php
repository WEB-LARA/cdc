<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="pengurang_dialog" class="easyui-dialog"  style="width:75%;height:62%;padding:10px 20px" closed="true" buttons="#dlg-buttons" data-options="closable:false">
    <form id="penagurang_form" class="easyui-form" action="#" method="post">
      <table>
        <tr>
          <td>
            <center> Trx Type Name </center>
          </td>
          <td>
            <center> Trx Minus Date </center>
          </td>
          <td>
            <center> Description</center>
          </td>
          <td>
            <center> Trx Det Amount </center>
          </td>
        </tr>

        <tr>
          <td>
            <input id="trxTypePengurang" name="TRX_TYPE" class="easyui-combobox" style="width:150px" tabindex="1" required > &nbsp
          </td>
          <td>
            <input type="hidden" value="<?php echo date('d-m-Y'); ?>" id="sys_date">
            <input id="trxDatePengurang" name="TRX_DATE" class="easyui-datebox" style="width:150px" tabindex="2" required > &nbsp
          </td>
          <td>
            <input id="descPengurang" name="STORE_CODE" class="easyui-textbox" style="width:250px" tabindex="3"> &nbsp
          </td>
          <td>
            <input id="amountPengurang" name="AMOUNT" class="easyui-numberbox" style="width:200px" tabindex="4" data-options="groupSeparator:','" required > &nbsp
          </td>
          <td>
            <a href="#" id="btnSavePengurang" class="easyui-linkbutton" iconCls="icon-save" onclick="simpanPengurang()">Simpan</a>

            <a href="#" id="pengurangSelesai" class="easyui-linkbutton" iconCls="icon-ok" onclick="selesaiPengurang()">Selesai</a>
          </td>
        </tr>

      </table>
    </form>


    <div id="bawah">
      <table id="tblInputPengurang" > </table>
    </div>

</div>
