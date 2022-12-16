<head>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ui/themes/default/easyui.css" >
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ui/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/demo.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/public.css">

  <script type="text/javascript" src="<?php echo base_url();?>assets/ui/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/ui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/datagrid-detailview.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/datagrid-groupview.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/date_time.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/header.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/report.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/upload.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/report2.js"></script>


  <title> Home </title>
</head>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var x = "<?php echo $this->session->userdata('session_id'); ?>";
	var usrId = "<?php echo $this->session->userdata('usrId'); ?>"
	var flag =  "<?php echo $this->session->userdata('resetFlag'); ?>";
	var isLogged = "<?php echo $this->session->userdata('logged_in'); ?>";
</script>
