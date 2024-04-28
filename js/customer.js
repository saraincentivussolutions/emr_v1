//=================== Customer Master 
function loadCustomerMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Customer</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'customer', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerdataTableList'};	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadCustomerMasterList'};
	
	callCommonLoadFunction(passArr); 
}

function loadCustomerdataTableList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'customer'}; 
	
	$("#customerMasterTbl").dataTable({
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


function loadCustomerMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'customer'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataCustomerMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataCustomerMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#customerMasterTbl").dataTable({
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
			
			var custCode=''+vArr.customer_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateCustomerMasterList('+vArr.customer_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#customerMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateCustomerMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'customer', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadCustomerMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'customer'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataCustomerMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataCustomerMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.customer_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#customer_status").val(rsOp.status);
		
		var employeelist = rsOp.employeelist; 
	
		var option = "<option value='0'>Select</option>";
		
		$.each(employeelist, function(k,v){  
			var selected = '';	 
			option += "<option value='"+v.employee_id+"' "+selected+">"+v.employee_name+"</option>";				  
		}); 
		
		$('select#refered_employee_id').empty();
		$('select#refered_employee_id').append(option);
		
		if(Number(rsOp.customer_id)>0)
		{ 
			$("#customer_name").val(rsOp.customer_name);  
			$("#customer_mobile").val(rsOp.customer_mobile);  
			$("#customer_email").val(rsOp.customer_email); 
			$("#refered_employee_id").val(rsOp.refered_employee_id); 
			
			$('.divClsActiveStatus').show();
			 
		} 
		else
		{
			$('.divClsActiveStatus').hide();	 
		}
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Customer':'New Customer'; 
	$('#viewPageModal').find('.modal-title').text(modal_title);
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	funcShwHideEmpDrpDwn();
	
}

function CreateUpdateCustomerMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'customer'}
	
	
	if(jQuery.trim($('#customer_name').val())=='')
	{
		alert('Enter Name');
		$('#customer_name').focus();
		return false;
	}
	
	if(jQuery.trim($('#customer_mobile').val())=='' )
	{
		alert('Enter Customer mobile');
		$('#customer_mobile').focus();
		return false;
	}
	/*if($('#customer_mobile').val()=='' && $('#customer_email').val()=='')
	{
		alert('Enter Customer mobile/ email');
		$('#customer_mobile').focus();
		return false;
	}*/
	
	if($('#refered_employee_id').val()==0)
	{
		alert('Select ref. employee');
		$('#refered_employee_id').focus();
		return false;
	} 	 
	
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCustomerMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);

	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeCustomerModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeCustomerModalDialog(response)
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
	loadCustomerMaster();
}	


function viewDeleteCustomerMaster(id)
{
	$('#frmCustomerDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'customer'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id}); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteCustomerMaster', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	 
}
function loadDeleteCustomerMaster(StrData) 
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
function deleteCustomerMaster()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'customer'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCustomerDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadCustomerMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function changeCustomerPassword(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'customer', view:'change'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadChangeCustomerPassword', sendCustPassVal:custVals, pageLoadContent:'#viewChangeModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
}

function loadChangeCustomerPassword(response)
{
	
	//console.log(response);
	var id = response.customData.id
	$('#frmCustomerChangeMaster').find("#hid_id").val(id);
	$('#viewChangeModal').modal({  show:true, backdrop:false });
}

function updateCustomerPassword()
{
	var a  = "change_password";	 
	var actParams = {name:'action', value:'change_password'};  
	var modParams = {name:'module', value:'customer'}
	
	
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
	
	var pageParams=$('#frmCustomerChangeMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	 var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeCustomerChangeModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function closeCustomerChangeModalDialog(response)
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
	//loadCustomerMaster();
}	
function funcShwHideEmpDrpDwn()
{
	var utyp=$('#customer_type').val();
	
	if(utyp==2 || utyp==3)
	{
		$('#customer_display_name').hide();
		$('#customer_display_id').show();
	}
	else
	{
		$('#customer_display_name').show();
		$('#customer_display_id').hide();	
	}
	
}
	