function loadSourceOfContactMaster()
{  
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Source Of Contact</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'source_of_contact', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadSourceOfContactdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadSourceOfContactdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'source_of_contact'}; 
	
	$("#sourceOfContactMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				   "bAutoWidth": false,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					},
				"fnDrawCallback":function()
				{
					//checkPermssion('source_of_contact');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


function loadSourceOfContactMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'source_of_contact'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataSourceOfContactMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataSourceOfContactMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#sourceOfContactMasterTbl").dataTable({
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
			
			var custCode=''+vArr.source_of_contact_name+'';
			 
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateSourceOfContactMasterList('+vArr.source_of_contact_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#sourceOfContactMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateSourceOfContactMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'source_of_contact', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadSourceOfContactMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadSourceOfContactMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'source_of_contact'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataSourceOfContactMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataSourceOfContactMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.source_of_contact_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#source_of_contact_status").val(rsOp.status);
		
		if(Number(rsOp.source_of_contact_id)>0)
		{ 
			$("#source_of_contact_name").val(rsOp.source_of_contact_name); 
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Source Of Contact':'New Source Of Contact'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateSourceOfContactMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'source_of_contact'}
	
	
	if(jQuery.trim($('#source_of_contact_name').val())=='')
	{
		alert('Enter Source Of Contact name');
		$('#source_of_contact_name').focus();
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmSourceOfContactMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeSourceOfContactModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeSourceOfContactModalDialog(response)
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
	loadSourceOfContactMaster();
}	
	
	

function viewDeleteSourceOfContactMaster(id)
{
	$('#frmSourceOfContactDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'source_of_contact'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteSourceOfContact', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteSourceOfContact(StrData) 
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

function deleteSourceOfContactMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'source_of_contact'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmSourceOfContactDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadSourceOfContactMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 