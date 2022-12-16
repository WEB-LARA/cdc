<head>
  <script type="text/javascript" src="<?php echo base_url();?>assets/ui/jquery.min.js"></script>
  <link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/bootstrap/css/styles.css" rel="stylesheet">
  <script type="text/javascript" src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
</head>

<form action="#" id="formID">
  <div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h1 class="text-center"> <b>Login</b> </h1>
            <h5 class="text-center">

            </h5>
        </div>
        <div class="modal-body">
            <form class="form col-md-12 center-block">
              <div class="form-group">
                <input type="text" class="form-control input-lg" name="username" id="IdUser" placeholder="Username" autofocus>
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" name="password" id="IdPass" placeholder="Password">
              </div>
              <div class="form-group">
                <button class="btn btn-sample btn-lg btn-block">Sign In</button>
              </div>
          </form>
      </div>

    </div>
  </div>
  </div>
</div>


<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
		var url = "<?php echo base_url(); ?>";


		$(document).ready(function(){

			$('#formID').submit(function(event){
				event.preventDefault();

				username = $('#IdUser').val();
				password = $('#IdPass').val();

				if(username==''){
					alert('Kolom username harus diisi!');
				}else if(password==''){
					alert('Kolom password harus diisi!');
				}else{
					$.ajax({
						type: "POST",
						url:"<?php echo base_url();?>login/validate_user",
						data:"username="+username+"&password="+password,
						dataType:'json',
						success:function(data){
							if(data.status == 'FAILED'){
								alert(data.msg);
							}
              else if(data.status == 'INACTIVE'){
								alert(data.msg);
							}
              else{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url();?>login/go_login",
									data:"username="+username,
									dataType:'json',
									success:function(data){
										window.location.replace("<?php echo base_url();?>");
									},
									error:function(xhr, status, error) {
										alert("An AJAX error occured: " + status + "\nError: " + error);
									}
								});
							}
						},
						error:function(xhr, status, error) {
							alert("Error on AJAX CALLING : " + status + "\nError: " + error);
						}
					});
				}
			});
		});

	</script>
