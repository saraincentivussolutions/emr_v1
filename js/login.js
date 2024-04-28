function validateLogin()
{
	var username  		= jQuery.trim($("#username").val());
	var password  		= jQuery.trim($("#password").val());	
	
	if (username==""){
		$("#username").css("border","1px solid #e77776"); 
		$("#username").css("background","#f8dbdb"); 
		$("#username").focus();  
		return false;
	}
	if (password==""){
		$("#password").css("border","1px solid #e77776"); 
		$("#password").css("background","#f8dbdb");
		$("#password").focus();  
		return false;
	} 
		
	 
		
	var a  = "chk_details";	 
	var param = { username:username, password:password,module:'login', action:a};  
		$.ajax({
				type		: 'POST',
				url			: 'app/modules/'+param.module+"/controller.php",
				dataType	: 'json',
				data		:  param,					   	 
				success	:  function(data){ 
						 
						  
					//alert(opStatus);	 
					var opStatus='failure';
					if(data.status!=undefined) opStatus=data.status;
					
					if(opStatus=='success')
					{
						location.href='main.php'; 	
					}
					else
					{
						if(data.message!=undefined)
						{
							alert(data.message); 
						}
						else
						{ 
							alert('Something went wrong!'); 
						}	
					}  
	
					},
					error	:  function(data) { alert('Something went wrong!');    }
				}); 
}
function spaSetupCreate()
{
	var setupname = jQuery.trim($("#setupname").val());
	
	if (setupname=="")
	{
		$("#setupname").css("border","1px solid #e77776"); 
		$("#setupname").css("background","#f8dbdb"); 
		$("#setupname").focus();  
		return false;
	}
	spaSetupChkName('proceed_save');
}
function spaSetupChkName(next_oprn)
{
	var setupname = jQuery.trim($("#setupname").val());
	var a  = "chk_name_exists";	
	
	var param = { setupname:setupname, module:'setup', action:a};  
		$.ajax({
				type		: 'POST',
				url			: 'app/modules/'+param.module+"/controller.php",
				dataType	: 'json',
				data		:  param,					   	 
				success	:  function(data){  
						 
					var opStatus='failure';
					if(data.status!=undefined) opStatus=data.status;
					
					if(opStatus=='success')
					{
						if(next_oprn=="proceed_save") 
						{
							spaSetupSave();	
						}
					}
					else
					{
						if(data.message!=undefined)
						{
							alert(data.message); 
							$("#setupname").focus();
						}
						else
						{ 
							alert('Something went wrong!'); 
						}	
					}  
	
					},
					error	:  function(data) { alert('Something went wrong!');    }
				}); 
}
function spaSetupSave()
{
	var setupname = jQuery.trim($("#setupname").val());
	var setupmobile = jQuery.trim($("#setupmobile").val());
	var setupemail = jQuery.trim($("#setupemail").val());
	
	if(setupname=="")
	{
		$("#setupname").css("border","1px solid #e77776"); 
		$("#setupname").css("background","#f8dbdb"); 
		$("#setupname").focus();  
		return false;
	}
	if(setupmobile=="")
	{
		$("#setupmobile").css("border","1px solid #e77776"); 
		$("#setupmobile").css("background","#f8dbdb"); 
		$("#setupmobile").focus();  
		return false;
	}
	if(setupemail=="")
	{
		$("#setupemail").css("border","1px solid #e77776"); 
		$("#setupemail").css("background","#f8dbdb"); 
		$("#setupemail").focus();  
		return false;
	}
	else
	{
		if(EmailCheck(setupemail)==false){ alert('Email is invalid'); $("#setupemail").focus();   return false; }	
	}
	alert('save here');
	 
}