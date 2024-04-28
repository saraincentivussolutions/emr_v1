var GV_apprlist_filt_paidstatus=1;
function loadApprovalMaster()
{
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Approval</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'approval', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadApprovaldataTableList'}; 
	
	callCommonLoadFunction(passArr); 
	
	
}
function apprlistpaidstatuschange()
{
	GV_apprlist_filt_paidstatus=$('#apprlist_filt_paidstatus').val();	
	loadApprovalMaster();
}

function loadApprovaldataTableList()
{
	$('#apprlist_filt_paidstatus').val(GV_apprlist_filt_paidstatus);
	
	var a  = "getList";	 
	var pageParams = {action:a, apprlist_filt_paidstatus:GV_apprlist_filt_paidstatus, module:'approval'}; 
	
	$("#approvalMasterTbl").dataTable({
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
					checkPermssion('Approval');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateApprovalMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'approval', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadApprovalData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadApprovalData', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadApprovalData()
{
	//var modal_title = Number($("#hid_id").val())>0?'Edit Approval':'New Approval'; 
	//$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	 
	
	ulTabsClickCommon(); // written in common
	
	$('input[type=text]').each(function(){
										if($(this).hasClass('clsAlwaysShw')){  } else { $(this).attr('readonly','readonly'); if($(this).hasClass('datepicker')) $(this).removeClass('datepicker'); }
										});
	
	$('input[type=radio]').each(function(){
										if($(this).hasClass('clsAlwaysShw')){  } else { $(this).attr('disabled','disabled'); }
										});
	$('input[type=chechbox]').each(function(){
										if($(this).hasClass('clsAlwaysShw')){  } else { $(this).attr('disabled','disabled');   }
										});
	
	$('select').each(function(){
										if($(this).hasClass('clsAlwaysShw')){  } else { $(this).attr('disabled','disabled'); }
										});
	$('textarea').each(function(){
										if($(this).hasClass('clsAlwaysShw')){  } else { $(this).attr('disabled','disabled'); }
										});
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
}
function loadApprovalMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'approval'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataApprovalMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataApprovalMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.approval_transaction_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#approval_status").val(rsOp.status);
		$("#approval_vc").val(rsOp.approval_vc);
		
		var productline_list = rsOp.productline_list;
	
		var option = "";
		var chkArr = rsOp.productline_id;
		chkArr = chkArr.split(',');
		$.each(productline_list, function(k,v){
			var selected = '';		
			var tmp = jQuery.inArray(v.productline_id,chkArr);
			if(tmp>-1) selected = 'checked';
			var str = '<label><input name="chk_product_line[]" type="checkbox" value="'+v.productline_id+'" '+selected+'>'+v.productline_name+'</label><br>';
			//
			option += str;				  
		});
		
		
		$('div#chk_multi_product_line').html(option)
		
		if(Number(rsOp.approval_transaction_id)>0)
		{ 
			$("#approval_name").val(rsOp.approval_name);
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Product Line':'New Product Line'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateApprovalMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'approval'}  
	
	var order_no = jQuery.trim($('#order_no').val());
	var approved_by = jQuery.trim($('#approved_by').val()); 
	//if(order_no==''){alert('Order no. is missing. Please try after some time!');  return false;	}
	if(approved_by==''){alert('Approved By should not be empty!'); goToUlTabsClickCommon(6); $('#approved_by').focus(); return false;	}
	 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmApprovalMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeApprovalModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr);  
}

function closeApprovalModalDialog(response)
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
	loadApprovalMaster();
}	
	
	

function viewDeleteApprovalMaster(id)
{
	$('#frmApprovalDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'approval'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteApproval', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteApproval(StrData) 
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

function deleteApprovalMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'approval'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmApprovalDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadApprovalMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 
function ApprovalMasterBack()
{ 
	loadApprovalMaster();	
}

  
 