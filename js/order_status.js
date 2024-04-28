function loadOrderStatusMaster()
{  
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Order Status</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'order_status', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOrderStatusdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadOrderStatusdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'order_status'}; 
	
	$("#orderStatusMasterTbl").dataTable({
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
					//checkPermssion('order_status');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


function loadOrderStatusMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'order_status'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataOrderStatusMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataOrderStatusMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#orderStatusMasterTbl").dataTable({
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
			
			var custCode=''+vArr.orderstatus_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateOrderStatusMasterList('+vArr.orderstatus_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#orderStatusMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateOrderStatusMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'order_status', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOrderStatusMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadOrderStatusMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'order_status'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataOrderStatusMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataOrderStatusMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.orderstatus_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#orderstatus_status").val(rsOp.status);
		
		if(Number(rsOp.orderstatus_id)>0)
		{ 
			$("#orderstatus_name").val(rsOp.orderstatus_name);
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Order Status':'New Order Status'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateOrderStatusMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'order_status'}
	
	
	if(jQuery.trim($('#orderstatus_name').val())=='')
	{
		alert('Enter Order Status text');
		$('#orderstatus_name').focus();
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmOrderStatusMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeOrderStatusModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeOrderStatusModalDialog(response)
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
	loadOrderStatusMaster();
}	
	
	

function viewDeleteOrderStatusMaster(id)
{
	$('#frmOrderStatusDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'order_status'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteOrderStatus', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteOrderStatus(StrData) 
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

function deleteOrderStatusMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'order_status'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmOrderStatusDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadOrderStatusMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 