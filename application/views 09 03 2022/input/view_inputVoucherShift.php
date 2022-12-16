<!-- OPEN PENAMBAH DIALOG POP_UP -->
<div id="voucher_dialog_shift" class="easyui-dialog" style="width:80%;height:62%;padding:10px 20px" closed="true" buttons="#dlg-buttonsVoucher" data-options="closable:false">
    <form id="voucher_form_shift" class="easyui-form" action="#" method="post">
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
          <td>
            <center> Shift </center>
          </td>
        </tr>

        <tr>
          <td>
            <input id="voucherNumShift" name="VOUCHER_NUM" class="easyui-textbox" style="width:150px" tabindex="1" required > &nbsp
          </td>
          <td>
            <input type="hidden" value="<?php echo date('d-m-Y'); ?>" id="sys_date">
            <input id="voucherDateShift" name="SALES_DATE" class="easyui-datebox" style="width:150px" tabindex="2" required > &nbsp
          </td>
          <td>
            <input id="voucherDescShift" name="VOUCHER_DESC" class="easyui-textbox" style="width:250px" tabindex="3"> &nbsp
          </td>
          <td>
            <input id="voucherAmountShift" name="VOUCHER_AMOUNT" class="easyui-numberbox" style="width:200px" tabindex="4" data-options="groupSeparator:','" readonly > &nbsp
          </td>
          <td>
            <select id="no_shift_voucher" class="easyui-combobox" style="width:50px; ">
              <option value="1" selected="selected">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select>
          </td>
          <td>
            <a href="#" id="btnSaveVoucherShift" class="easyui-linkbutton" iconCls="icon-save" onclick="simpanVoucherShift()">Simpan</a>

            <a href="#" id="voucherSelesaiShift" class="easyui-linkbutton" iconCls="icon-ok" onclick="selesaiVoucherShift()">Selesai</a>
          </td>
        </tr>

      </table>
    </form>


    <div id="bawah">
      <table id="tblInputVoucherShift" > </table>
    </div>

</div>
