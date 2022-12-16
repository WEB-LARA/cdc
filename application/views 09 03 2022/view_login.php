<body class="easyui-layout" background="<?php echo base_url();?>assets/image/wall_login.jpg">
  <div DATA-OPTIONS="region:'north'" style="margin-top: 5px; height:74px;background:none">
    <table>
      <tr>
        <td><img src="<?php  echo base_url('assets/css/image/logo_indomaret.png');?>" style="width:142px;height:65px; no-repeat;"> </td>
<!--    <td> <h2> &nbsp &nbsp Cashier Distribution Center</h2> </td> -->

      </tr>
    </table>
  </div>

  <div align="center" style="margin-top: 18%;margin-right: 0%;">
    <div id="login"  style="width:320px;height:145px;">
      <table> <br>
        <form action="#" id="formID" style="padding:10px 20px 10px 40px;">
          <tr>
            <td align="center"> <h1> LOGIN </h1> </td>
          </tr>
          <tr>
<!--          <td> <p>Username : </td> -->
<!--          <td> <input type="text" name="username" id="IdUser" placeholder="Username" autofocus ></p> </td> -->
            <td> <input class="easyui-textbox" name="username" id="IdUser" style="width:110%;height:35px;padding:12px" data-options="prompt:'Username'" tabindex="1" autofocus> </td>
          </tr>
          <tr>
<!--          <td> <p>Password : </td> -->
<!--          <td> <input type="password" name="password" id="IdPass" placeholder="Password"></p> </td> -->
            <td> <input class="easyui-textbox" type="password" name="password" id="IdPass" style="width:100%;height:35px;padding:12px" data-options="prompt:'Password'" tabindex="2"> </td>
          </tr>
          <tr>
            <td align='center'>
              <!-- <input type="submit" class="button" value="Login" tabindex="3" > -->
              <a href="#" id="btnLogin" class="easyui-linkbutton" data-options="iconCls:'icon-man'">Login</a>
            </td>
          </tr>
        </form>
      </table>
    </div>
  </div>

<!--
  <div DATA-OPTIONS="region:'south'" style=" height:50px;background:none">

  </div>
-->
</body>


<style>
.button {
    background-color: #008CBA;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    width:95%;
}
</style>

<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
		var url = "<?php echo base_url(); ?>";


		$(document).ready(function(){
      $("#IdPass").textbox('clear');
      $("#IdUser").textbox('clear').textbox('textbox').focus();

      $('#btnLogin').linkbutton('resize', {
        width: '100%',
        height: 32
      });

			$('#btnLogin').click(function(event){
				event.preventDefault();

				username = $('#IdUser').val();
				password = $('#IdPass').val();

				if(username==''){
          $.messager.alert('Warning','Kolom username harus diisi!');
					//alert('Kolom username harus diisi!');
				}else if(password==''){
          $.messager.alert('Warning','Kolom password harus diisi!');
					//alert('Kolom password harus diisi!');
				}else{
					$.ajax({
						type: "POST",
						url:"<?php echo base_url();?>login/validate_user",
						data:"username="+username+"&password="+password,
						dataType:'json',
						success:function(data){
							if(data.status == 'FAILED'){
								//alert(data.msg);
                $.messager.alert('Warning',data.msg);
							}
              else if(data.status == 'INACTIVE'){
								//alert(data.msg);
                $.messager.alert('Warning',data.msg);
							}
              else{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url();?>login/go_login",
									data:"username="+username,
									success:function(data){
										window.location.replace("<?php echo base_url();?>");
									},
									error:function(xhr, status, error) {
										alert("An AJAX error occured: " + status + "\nError1: " + error);
									}
								});
							}
						},
						error:function(xhr, status, error) {
							alert("Error on AJAX CALLING : " + status + "\nError2: " + error);
						}
					});
				}
			});
		});

	</script>
