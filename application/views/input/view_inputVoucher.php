<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="voucher_dialog" class="easyui-dialog" style="width:75%;height:62%;padding:10px 20px" closed="true" buttons="#dlg-buttonsVoucher" data-options="closable:false">
    <form id="voucher_form" class="easyui-form" action="#" method="post">
      <table>
        <tr>
          <td>
            <center> Voucher Num </center>
          </td>
          <td>
            <center> Sales Date </center>
          </td>
          <td>
            <center> Voucher Desc</center>
          </td>
          <td>
            <center> Voucher Amount </center>
          </td>
        </tr>

        <tr>
          <td>
            <input id="voucherNum" name="VOUCHER_NUM" class="easyui-textbox" style="width:150px" tabindex="1" required > &nbsp
          </td>
          <td>
            <input type="hidden" value="<?php echo date('d-m-Y'); ?>" id="sys_date">
            <input id="voucherDate" name="SALES_DATE" class="easyui-datebox" style="width:150px" tabindex="2" required > &nbsp
          </td>
          <td>
            <input id="voucherDesc" name="VOUCHER_DESC" class="easyui-textbox" style="width:250px" tabindex="3"> &nbsp
          </td>
          <td>
            <input id="voucherAmount" name="VOUCHER_AMOUNT" class="easyui-numberbox" style="width:200px" tabindex="4" data-options="groupSeparator:','" readonly > &nbsp
          </td>
          <td>
            <a href="#" id="btnSaveVoucher" class="easyui-linkbutton" iconCls="icon-save" onclick="simpanVoucher()">Simpan</a>

            <a href="#" id="voucherSelesai" class="easyui-linkbutton" iconCls="icon-ok" onclick="selesaiVoucher()">Selesai</a>
          </td>
        </tr>

      </table>
    </form>


    <div id="bawah">
      <table id="tblInputVoucher" > </table>
    </div>

</div>
