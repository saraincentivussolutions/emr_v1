//=================== UserRole Master 
function loadUserRoleMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">User Role</li>';
	topHeadTitle(titleCont);
	
	var a  = "view";	 
	var pageParams = {action:a, module:'user_role', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserRoledataTableList'};	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserRoleMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadUserRoleMasterList'};
	
	callCommonLoadFunction(passArr); 
}

function loadUserRoledataTableList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'user_role'}; 
	
	$("#user_roleMasterTbl").dataTable({
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


function loadUserRoleMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'user_role'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserRoleMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserRoleMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#user_roleMasterTbl").dataTable({
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
			
			var custCode=''+vArr.user_role_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateUserRoleMasterList('+vArr.user_role_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#user_roleMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateUserRoleMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'user_role', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'UserRoleMasterAfterLoad'};	
	
	callCommonLoadFunction(passArr); 
		
}

function UserRoleMasterAfterLoad()
{
	$('input.chk_module_head').click(function(){
											  
		var parent_id = $(this).val();
		var chk = $(this).is(':checked')?true:false;
		$('input.chk_module_sub[parent='+parent_id+']').each(function(){
		
			if(chk == true)
			{
				$(this).removeAttr('disabled');
				this.checked = true;
			}
			else
			{
				this.checked = false;
				$(this).attr('disabled','');	
			}
		
		});
		
		
	});
	
	$('input.sub_access').click(function(){
		var chk = $(this).is(':checked')?true:false;
		var tr = $(this).closest('tr');
		$(tr).find('input.chk_module_sub:not(.sub_access)').each(function(){
		
			if(chk == true)
			{
				//this.checked = true;
			}
			else
			{
				this.checked = false;
			}
		
		});
	});
	
	$('input.chk_module_sub').click(function(){
		var chk = $(this).is(':checked')?true:false;
		var tr = $(this).closest('tr');
		
		if(chk == true)
		{
			$(tr).find('input:eq(0)').each(function(){
				this.checked = true;
			});
		}
		
		/*$(tr).find('input.chk_module_sub:not(.sub_access)').each(function(){
		
			if(chk == true)
			{
				//this.checked = true;
			}
			else
			{
				//this.checked = false;
			}
		
		});*/
	});
	
	setCheckboxSettings();
}

function setCheckboxSettings()
{
	$('input.sub_access:checked').each(function(){
	
		var parent = $(this).attr('parent');
		
		$('input.chk_module_head[value='+parent+']').each(function(){
			//this.checked = true;
		});
	});
	
	$('input.chk_module_head').each(function(){
		var parent_id = $(this).val();
		var chk = $(this).is(':checked')?true:false;
		$('input.chk_module_sub[parent='+parent_id+']').each(function(){
		
			if(chk == true)
			{
				//$(this).removeAttr('disabled');
				//this.checked = true;
			}
			else
			{
				//this.checked = false;
				$(this).attr('disabled','disabled');	
			}
		
		});
	});
}
function loadUserRoleMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'user_role'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserRoleMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserRoleMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.user_role_id);
		$("#user_role_name").val(rsOp.user_role_name);
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#user_role_status").val(rsOp.status);
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit User Role':'New User Role'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateUserRoleMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'user_role'}
	
	
	if($('#user_role_name').val()=='')
	{
		alert('Enter User Role');
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	//var pageParams=$('#frmUserRoleMaster').serializeArray();
	
	var pageParams=new Array();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	$('input.chk_module_head').each(function(){
		var module_id = $(this).val();
		var str='';
		if($(this).is(':checked')) str = '1';
		pageParams.push({name:'module_id[]', value:module_id});
		pageParams.push({name:'module_actions[]', value:str});
		pageParams.push({name:'module_type[]', value:1});
	});
	
	$('input.sub_access').each(function(){
		var tr = $(this).closest('tr');
		var module_id = $(this).attr('orgid');
		
		if($(this).is(':checked'))
		{
			var str=new Array();
			$(tr).find('input:checked').each(function(){
				str.push($(this).val());
			});
			str = str.join(',');
		}
		else
		{
			str = '';
		}
		
		pageParams.push({name:'module_id[]', value:module_id});
		pageParams.push({name:'module_actions[]', value:str});
		pageParams.push({name:'module_type[]', value:2});
	});
	
	var payslip_report_to_others = $('input[name=payslip_report_to_others]').is(':checked')?1:0;
	var expense_to_others = $('input[name=expense_to_others]').is(':checked')?1:0;
	var approve_expense = $('input[name=approve_expense]').is(':checked')?1:0;
	var user_role_name = $('#user_role_name').val();
	var user_role_status = $('#user_role_status').val();
	var hid_id =  $('#hid_id').val();
	
	pageParams.push({name:'hid_id', value:hid_id});
	pageParams.push({name:'approve_expense', value:approve_expense});
	pageParams.push({name:'expense_to_others', value:expense_to_others});
	pageParams.push({name:'payslip_report_to_others', value:payslip_report_to_others});
	
	pageParams.push({name:'user_role_name', value:user_role_name});
	pageParams.push({name:'user_role_status', value:user_role_status});
	
	
	$('input.extra_modules').each(function(){
										   
		var name = $(this).attr('name');
		if($(this).is(':checked'))
		{
			pageParams.push({name:'extra_modules[]', value:name+':1'});
		}
		else
		{
		}
										   
	});
	//
	
	
	
	
	console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeUserRoleModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeUserRoleModalDialog(response)
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
	//loadUserRoleMaster();
	
	setUserPrevilageRestriction();
}	

function setUserPrevilageRestriction()
{
	//alert('test');
	var a  = "getUserPrevillage";	 
	var pageParams = {action:a,  module:'user_role'};  
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'setPrevilageValues', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function setPrevilageValues(response)
{
	//console.log(response.formOpData.menu_actions);
	GV_menu_permission = response.formOpData.menu_actions;
	//console.log(GV_menu_permission);
	loadUserRoleMaster();
}
									 

function closeUserRoleMaster()
{
	loadUserRoleMaster();
}
	
	

function viewDeleteUserRoleMaster(id)
{
	$('#frmUserRoleDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'user_role'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteUserRole', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteUserRole(StrData) 
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

function deleteUserRoleMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'user_role'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserRoleDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadUserRoleMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 