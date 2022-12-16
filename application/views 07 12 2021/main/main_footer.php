	</div>
</div>
<!-- END BODY -->

<!-- FOOTER -->
<div DATA-OPTIONS="region:'south'" style=" height:50px;background:#FFFFFF">

</div>



<!-- HIDDEN MENU -->
<div id="changePassPanel" style="width:40%;display: none;">
	<form class="easyui-form" id="update_user_form" action="<?php echo base_url();?>data/user/update_pass" data-options="novalidate:true" method="POST" enctype="multipart/form-data">
		<table style="margin:10px" width="96%">
			<tr>
				<td>Old Password<br></td>
				<td><B>:</B></td>
				<td>
					<input class="easyui-textbox" id="pwdUserOld" name="pwdUserOld" style="width:100%;height:32px;" type="password" DATA-OPTIONS="prompt:'PASSWORD',required:true">
				</td>
			</tr>
			<tr>
				<td>New Password<br>(3-8 Karakter)</td>
				<td><B>:</B></td>
				<td>
					<input class="easyui-textbox" id="pwdUser1" name="pwdUser1" style="width:100%;height:32px;" type="password" DATA-OPTIONS="prompt:'PASSWORD',required:true" validType="length[3,8]">
				</td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><B>:</B></td>
				<td>
					<input class="easyui-textbox" id="pwdUser2" name="pwdUser2" style="width:100%;height:32px;" type="password" DATA-OPTIONS="prompt:'PASSWORD',required:true" validType="PasswordEquals['#pwdUser1']">
				</td>
			</tr>

			<tr>
				<td colspan="3" align="right">
					<a id="saved_update_user" class="easyui-linkbutton" style="width: 100px;height:30px" DATA-OPTIONS="iconCls:'icon-save'">Save</a>
					<!--<a id="canceled_update_user" class="easyui-linkbutton" DATA-OPTIONS="iconCls:'icon-cancel'">Cancel</a>-->
				</td>
			</tr>
		</table>
	</form>
</div>


<div id="resetPassPanel" style="display: none;">
	<form class="easyui-form" id="reset_user_form" action="<?php echo base_url();?>data/user/reset_pass" data-options="novalidate:true" method="POST" enctype="multipart/form-data">
		<table style="margin:10px" border="0" width="96%" cellpadding="5px">
			<tr>
				<td>New Password<br>(3-8 Karakter)</td>
				<td><B>:</B></td>
				<td>
					<input class="easyui-textbox" id="resetPwdUser1" name="resetPwdUser1" style="width:300px;height:32px;" type="password" DATA-OPTIONS="prompt:'PASSWORD',required:true" validType="length[3,8]">
				</td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><B>:</B></td>
				<td>
					<input class="easyui-textbox" id="resetPwdUser2" name="resetPwdUser2" style="width:300px;height:32px;" type="password" DATA-OPTIONS="prompt:'PASSWORD',required:true" validType="PasswordEquals['#resetPwdUser1']">
				</td>
			</tr>

			<tr>
				<td colspan="3" align="right">
					<a id="saved_reset_pass" class="easyui-linkbutton"  style="width: 100px;height:30px" DATA-OPTIONS="iconCls:'icon-save'">Save</a>
				</td>
			</tr>
		</table>
	</form>
</div>

</body>
</html>
