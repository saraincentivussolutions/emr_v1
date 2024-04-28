//=================== User Master 
function loadUserMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">User</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'user', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserdataTableList'};	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadUserMasterList'};
	
	callCommonLoadFunction(passArr); 
}

function loadUserdataTableList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'user'}; 
	
	$("#userMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				  "bAutoWidth": false,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					}
				  
	 });
	
	highlightRightMenu();
}


function loadUserMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'user'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#userMasterTbl").dataTable({
				  "bPaginate": true,
				  "bLengthChange": false,
				  "bFilter": false,
				  "bSort": true,
				  "bInfo": true,
				  "bAutoWidth": false
			});
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		var lpSno=0;
		$.each(rsOp,function(idx,vArr){
		
			lpSno++;
			
			var custCode=''+vArr.user_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateUserMasterList('+vArr.user_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#userMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateUserMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'user', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadUserMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'user'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		$("#hid_id").val(rsOp.user_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#user_status").val(rsOp.status);
		
		var sales_teamlist = rsOp.sales_teamlist; 
		var user_role_list = rsOp.user_role_list;
		var option = "<option value='0'>Select</option>"; 
		$.each(sales_teamlist, function(k,v){  
			var selected = '';	 
			option += "<option value='"+v.sales_team_id+"' "+selected+">"+v.sales_team_name+"</option>";				  
		});  
		$('select#sales_team_id').empty();
		$('select#sales_team_id').append(option);
		
		/*var option = "<option value='0'>Select</option>"; 
		$.each(user_role_list, function(k,v){  
			var selected = '';	 
			option += "<option value='"+v.user_role_id+"' "+selected+">"+v.user_role_name+"</option>";				  
		});  
		$('select#user_role_id').empty();
		$('select#user_role_id').append(option);*/
		
		/*var option = "";
		var chkArr = new Array();
		if(rsOp.sales_team_access_ids)
		{
			var chkArr = rsOp.sales_team_access_ids;
			chkArr = chkArr.split(',');
		}
		$.each(sales_teamlist, function(k,v){
			var selected = '';		
			var tmp = jQuery.inArray(v.sales_team_id,chkArr);
			if(tmp>-1) selected = 'checked=true';
			var str = '<div  class="col-md-4 prdcolor-list"><label><input name="chk_sales_team[]" type="checkbox" value="'+v.sales_team_id+'" '+selected+'> '+v.sales_team_name+'</label></div>';
			//
			option += str;				  
		});
		
		$('div#chk_multi_sales_team').html(option)*/
		
		if(Number(rsOp.user_id)>0)
		{ 
			$("#employee_code").val(rsOp.employee_code);   
			$("#user_display_name").val(rsOp.user_display_name); 
			$("#employee_mobile").val(rsOp.employee_mobile); 
			$("#sales_team_id").val(rsOp.sales_team_id); 
			//$("#user_role_id").val(rsOp.user_role_id); 
			
			$('.divClsActiveStatus').show();
			 
		} 
		else
		{
			$('.divClsActiveStatus').hide(); 
		}
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Advisor':'New Advisor'; 
	$('#viewPageModal').find('.modal-title').text(modal_title);
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	funcShwHideEmpDrpDwn();
	
}

function CreateUpdateUserMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'user'}
	
	
	if(jQuery.trim($('#employee_code').val())=='')
	{
		alert('Enter Advisor ID');
		$('#employee_code').focus();
		return false;
	}
	if(jQuery.trim($('#user_display_name').val())=='')
	{
		alert('Enter Advisor name');
		$('#user_display_name').focus();
		return false;
	} 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);

	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeUserModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeUserModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message;
	}
	else
	{
		if(data.message!=undefined)
		{
			customAlert(data.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	}				
			
	
	$('#viewPageModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	loadUserMaster();
}	


function viewDeleteUserMaster(id)
{
	$('#frmUserDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'user'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id}); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteUserMaster', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	 
}
function loadDeleteUserMaster(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 

	if(opData.status == 'success')
	$('#viewDeleteModal').modal({  show:true, backdrop:false });
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}
function deleteUserMaster()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'user'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadUserMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function changeUserPassword(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'user', view:'change'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadChangePassword', sendCustPassVal:custVals, pageLoadContent:'#viewChangeModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
}

function loadChangePassword(response)
{ 
	//console.log(response);
	var id = response.customData.id
	$('#frmUserChangeMaster').find("#hid_id").val(id);
	$('#viewChangeModal').modal({  show:true, backdrop:false });
}

function updateUserPassword()
{
	var a  = "change_password";	 
	var actParams = {name:'action', value:'change_password'};  
	var modParams = {name:'module', value:'user'}
	
	
	/*if($('#old_password').val()=='')
	{
		alert('Enter Password');
		$('#old_password').focus();
		return false;
	}*/
	
	if($('#new_password').val()=='')
	{
		alert('Enter Password');
		$('#new_password').focus();
		return false;
	}
	
	if($('#re_password').val()=='')
	{
		alert('Enter Password');
		$('#re_password').focus();
		return false;
	}
	
	if($('#new_password').val()!=$('#re_password').val())
	{
		alert('Both passwords are not same');
		$('#re_password').focus();
		return false;
	} 
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserChangeMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	 var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeUserChangeModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function closeUserChangeModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message;
	}
	else
	{
		if(data.message!=undefined)
		{
			customAlert(data.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	}				
			
	
	$('#viewChangeModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	//loadUserMaster();
}	
function funcShwHideEmpDrpDwn()
{
	var utyp=$('#user_type').val();
	
	if(utyp==2 || utyp==3)
	{
		$('#user_display_name').hide();
		$('#user_display_id').show();
	}
	else
	{
		$('#user_display_name').show();
		$('#user_display_id').hide();	
	}
	
}
// Login user change pwd change
function loadLoginUserChangePassword()
{
	var a  = "getLoginCPSingeView";	 
	var pageParams = {action:a,  module:'user'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataLoginUserChangePassword', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataLoginUserChangePassword(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		$("#ucpft_hid_id").val(rsOp.user_id); 
		//$("#user_display_name").val(rsOp.user_display_name);  
	} 
	 
	$('#viewUserChangePwdFoooterModal').modal({  show:true, backdrop:false }); 
}
function userChangePwdFooterOK()
{
	var a  = "change_loginuser_password";	 
	var actParams = {name:'action', value:'change_loginuser_password'};  
	var modParams = {name:'module', value:'user'}
	
	
	if(jQuery.trim($('#ucpft_old_password').val())=='')
	{
		alert('Enter Current Password');
		$('#ucpft_old_password').focus();
		return false;
	}
	
	if(jQuery.trim($('#ucpft_new_password').val())=='')
	{
		alert('Enter New Password');
		$('#ucpft_new_password').focus();
		return false;
	}
	if(jQuery.trim($('#ucpft_old_password').val())==jQuery.trim($('#ucpft_new_password').val()))
	{
		alert('Old password and new passwords are not same.');
		$('#ucpft_new_password').focus();
		return false;
	} 
	if(jQuery.trim($('#ucpft_new_password').val()).length<6)  
	{
		alert('Password should be minimum 5 characters');
		$('#ucpft_new_password').focus();
		return false;
	}
	
	if(jQuery.trim($('#ucpft_re_password').val())=='')
	{
		alert('Enter Confirm Password');
		$('#ucpft_re_password').focus();
		return false;
	}
	
	if(jQuery.trim($('#ucpft_new_password').val())!=jQuery.trim($('#ucpft_re_password').val()))
	{
		alert('Both passwords are not same');
		$('#ucpft_re_password').focus();
		return false;
	} 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserChangePwdFooter').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	 var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeUserChangePwdFooterModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function closeUserChangePwdFooterModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		customAlert(data.message);  
	}
	else
	{
		if(data.message!=undefined)
		{
			customAlert(data.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	}
	$('#ucpft_old_password').val('');
	$('#ucpft_new_password').val('');
	$('#ucpft_re_password').val('');
	$('#ucpft_hid_id').val('');
	$('#viewUserChangePwdFoooterModal').modal('hide'); 
}	
	