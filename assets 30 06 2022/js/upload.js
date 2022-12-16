$(document).ready(function() {
	$('#upload_data_am_as').window('close');
	$('#upload_data_go').window('close');
	$('#upload_data_voucher').window('close');
	$('#upload_data_stn').window('close');

	$('#upload_am_as').click(function(event) {
		event.preventDefault();
		$('#upload_data_am_as').window('open');
	});

	$('#upload_go').click(function(event) {
		event.preventDefault();
		$('#upload_data_go').window('open');
	});

	$('#upload_voucher').click(function(event) {
		event.preventDefault();
		$('#upload_data_voucher').window('open');
	});

	$('#upload_stn').click(function(event) {
		event.preventDefault();

		$('#upload_data_stn').window('open');

			$('#information').dialog('open');

		
	});

	$("#temp_am_as").click(function (event) {
		event.preventDefault();
		window.open(base_url+'Upload/download_template_am_as/', "Report Batches", "width=1000,height=600,scrollbars=yes");
	});

	$("#temp_go").click(function (event) {
		event.preventDefault();
		window.open(base_url+'Upload/download_template_go/', "Report Batches", "width=1000,height=600,scrollbars=yes");
	});
});