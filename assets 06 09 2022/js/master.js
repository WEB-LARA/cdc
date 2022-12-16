$(document).ready(function(){


	/*************************************************************************************************************************/	
	/* MASTER BANK */	
	/*************************************************************************************************************************/	
	
	$("#masterBank_btn").click(function(){
		//$('#masterBank_form').form('disableValidation');
		
		$("#masterBank_form").show();
		$("#masterBank_form").dialog({
			title:'Update User Password',
			autoOpen: false, 
			modal:true,
			maximizable:false,
			minimizable:false,
			collapsible:false
		});
	});
	
/*	
	$("#saved_update_user").click(function(){
		
		if($('#update_user_form').form('enableValidation').form('validate')){
			$('#update_user_form').form('submit', {
				success:function(data){
					var data2 = eval('(' + data + ')');	
					//$.messager.alert("berhasil");					
			        if(data2.status == 'SUKSES'){
						$.messager.alert('Info',data2.pesan,'info',function(){
							window.location.replace(base_url+"login/logout");
						});
						//$.messager.alert('Info',data2.pesan);
						//$("#changePassPanel").dialog('close');
						//window.location.replace(base_url);
					}else{
						$.messager.alert('Info',data2.pesan,'error'); 
					}
			    }
			});
		}
	});
	
*/	
	
});