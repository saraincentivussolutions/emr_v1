function loadLoginMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Login master</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'login_master', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadLoginMasterdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
}

function loadLoginMasterdataTableList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'login_master'}; 
	
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


function loadLoginMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'login_master'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataLoginMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataLoginMasterList(StrData)
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
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateLoginMasterList('+vArr.user_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#userMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateLoginMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'login_master', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadLoginMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadLoginMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'login_master'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataLoginMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataLoginMasterAddEdit(StrData)
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
		$.each(user_role_list, function(k,v){  
			var selected = '';	 
			option += "<option value='"+v.user_role_id+"' "+selected+">"+v.user_role_name+"</option>";				  
		});  
		$('select#user_role_id').empty();
		$('select#user_role_id').append(option);
		
		var option = "";
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
		
		$('div#chk_multi_sales_team').html(option)
		
		if(Number(rsOp.user_id)>0)
		{  
			$("#user_display_name").val(rsOp.user_display_name);  
			$("#user_role_id").val(rsOp.user_role_id); 
			$("#user_name").val(rsOp.user_name); 
			$("#user_password").val('****');  
			
			$('.user_password_div').hide(); 
			$('.divClsActiveStatus').show();
			 
		} 
		else
		{
			$('.user_password_div').show();
			$('.divClsActiveStatus').hide(); 
		}
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Advisor':'New Advisor'; 
	$('#viewPageModal').find('.modal-title').text(modal_title);
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	funcShwHideEmpDrpDwn();
	
}

function CreateUpdateLoginMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'login_master'}
	
	
	if(jQuery.trim($('#user_display_name').val())=='')
	{
		alert('Enter Name ');
		$('#user_display_name').focus();
		return false;
	} 
	if(jQuery.trim($('#user_name').val())=='')
	{
		alert('Enter Username');
		$('#user_name').focus();
		return false;
	}
	if(jQuery.trim($('#user_password').val())=='')
	{
		alert('Enter Password');
		$('#user_password').focus();
		return false;
	}
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmLoginMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);

	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeLoginMasterModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeLoginMasterModalDialog(response)
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
	loadLoginMaster();
}	


function viewDeleteLoginMaster(id)
{
	$('#frmLoginMasterDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'login_master'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id}); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteLoginMaster', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	 
}
function loadDeleteLoginMaster(StrData) 
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
function deleteLoginMaster()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'login_master'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmLoginMasterDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadLoginMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function changeLoginMasterPassword(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'login_master', view:'change'};  
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
	$('#frmLoginMasterChangeMaster').find("#hid_id").val(id);
	$('#viewChangeModal').modal({  show:true, backdrop:false });
}

function updateLoginMasterPassword()
{
	var a  = "change_password";	 
	var actParams = {name:'action', value:'change_password'};  
	var modParams = {name:'module', value:'login_master'}
	
	
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
	
	var pageParams=$('#frmLoginMasterChangeMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	 var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeLoginMasterChangeModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function closeLoginMasterChangeModalDialog(response)
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
	//loadLoginMaster();
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
// Login login_master change pwd change
function loadLoginLoginMasterChangePassword()
{
	var a  = "getLoginCPSingeView";	 
	var pageParams = {action:a,  module:'login_master'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataLoginLoginMasterChangePassword', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataLoginLoginMasterChangePassword(StrData)
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
	 
	$('#viewLoginMasterChangePwdFoooterModal').modal({  show:true, backdrop:false }); 
}
function userChangePwdFooterOK()
{
	var a  = "change_loginuser_password";	 
	var actParams = {name:'action', value:'change_loginuser_password'};  
	var modParams = {name:'module', value:'login_master'}
	
	
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
	
	var pageParams=$('#frmLoginMasterChangePwdFooter').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	 var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeLoginMasterChangePwdFooterModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function closeLoginMasterChangePwdFooterModalDialog(response)
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
	$('#viewLoginMasterChangePwdFoooterModal').modal('hide'); 
}	
	