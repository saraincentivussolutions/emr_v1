//=================== Appointments  
function loadAppointmentDetails()
{
	var titleCont= ' <li> Appointments </li>'; 
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'appointments', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadAppointmentsdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
}

function loadAppointmentsdataTableList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'appointments'}; 
	
	$("#appointmentsMasterTbl").dataTable({
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


function loadAppointmentsMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'appointments'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataAppointmentsMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataAppointmentsMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#appointmentsMasterTbl").dataTable({
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
			
			var custCode=''+vArr.appointments_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateAppointmentsMasterList('+vArr.appointment_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#appointmentsMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateAppointmentsMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'appointments', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	//var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadAppointmentsMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadAppointmentsMasterAddEdit', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadAppointmentsMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'appointments'};  
	
	var titleCont= ' <li> Appointments </li>';
	    titleCont += '<li class="active">Appointment Entry</li>';
	topHeadTitle(titleCont);
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataAppointmentsMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataAppointmentsMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.appointment_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#appointments_status").val(rsOp.status);
		
		var customerlist = rsOp.customerlist;  
		var customerption = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){  
			var selected = '';	 
			customerption += "<option value='"+v.customer_id+"' "+selected+">"+v.customer_name+"</option>";				  
		});  
		$('select#customer_id').empty();
		$('select#customer_id').append(customerption);
		
		if(Number(rsOp.appointment_id)>0)
		{  
			$("#appointment_number").val(rsOp.appointment_number); 
			$("#customer_id").val(rsOp.customer_id); 
			$("#appointment_date").val(rsOp.app_date_only);  
			$("#appointment_time").val(rsOp.app_time_only); 
			$("#remind_duration_val").val(rsOp.remind_duration_val); 
			$("#remind_duration_type").val(rsOp.remind_duration_type); 
			$("#appointment_description").val(rsOp.appointment_description); 
			
			
			$(".shwHideOnNewEdirDiv").show();
			 
		} 
		else
		{
			$(".shwHideOnNewEdirDiv").hide();	 
		}
		
	} 
	/*var modal_title = Number($("#hid_id").val())>0?'Edit Appointments':'New Appointments'; 
	$('#viewPageModal').find('.modal-title').text(modal_title);
	$('#viewPageModal').modal({  show:true, backdrop:false });*/ 
	 
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
}

function CreateUpdateAppointmentsMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'appointments'} 	
	
	if($('#customer_id').val()==0)
	{
		alert('Select customer');
		$('#customer_id').focus();
		return false;
	} 
	if(jQuery.trim($('#appointment_date').val())=='' )
	{
		alert('Enter Appointment date');
		$('#appointment_date').focus();
		return false;
	}
	if(jQuery.trim($('#appointment_time').val())=='' )
	{
		alert('Enter Appointment time');
		$('#appointment_time').focus();
		return false;
	} 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmAppointmentsMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);

	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeAppointmentsModalDialog', displayDataContent:'', sendDataOnSuccess:'send', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeAppointmentsModalDialog(response)
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
			
	
	//$('#viewPageModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	loadAppointmentDetails();
}	


function viewDeleteAppointmentsMaster(id)
{
	$('#frmAppointmentsDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'appointments'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id}); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteAppointmentsMaster', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	 
}
function loadDeleteAppointmentsMaster(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 

	if(opData.status == 'success')
	{
		$('#viewDeleteModal').modal({  show:true, backdrop:false }); 
	}
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}
function deleteAppointmentsMaster()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'appointments'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmAppointmentsDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadAppointmentDetails', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
 
 
function AppointmentDetailsBack()
{
	loadAppointmentDetails();
}
	