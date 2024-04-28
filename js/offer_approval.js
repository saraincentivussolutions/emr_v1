var GV_offapplist_filttypeval=1;
function loadOfferapprovalMaster()
{
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Offer approval</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'offer_approval', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOfferapprovaldataTableList'}; 
	
	callCommonLoadFunction(passArr); 
	
	
}
function offerlistordstatuschange()
{
	GV_offapplist_filttypeval=$('#offerapp_list_filttype').val();	
	loadOfferapprovalMaster();
}
function loadOfferapprovaldataTableList()
{
	$('#offerapp_list_filttype').val(GV_offapplist_filttypeval);
	
	var a  = "getList";	 
	var pageParams = {action:a, off_approve_type_filt:GV_offapplist_filttypeval, module:'offer_approval'}; 
	
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
					checkPermssion('Offer approval');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateOfferapprovalMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, off_approve_type:GV_offapplist_filttypeval, action:a, module:'offer_approval', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOfferapprovalData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOfferapprovalData', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadOfferapprovalData()
{
	//var modal_title = Number($("#hid_id").val())>0?'Edit Offerapproval':'New Offerapproval'; 
	//$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	
	
	ulTabsClickCommon(); // written in common
	
	$("input[name=off_acc_approved_status]").click(function(){ 
		bkOfferApprovalValidations(); 
	});
	
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
function bkOfferApprovalValidations()
{  
	var off_acc_approved_status = $("input[name=off_acc_approved_status]:checked").val(); 
	if(off_acc_approved_status!=2){ $('#off_acc_send_to_md').attr('disabled','disabled');   }
	else{ $('#off_acc_send_to_md').removeAttr('disabled'); }
	 
}
function CreateUpdateOfferapprovalMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'offer_approval'}; 
	var offtypeParams = {name:'off_approve_type', value:GV_offapplist_filttypeval};
	
	var order_no = jQuery.trim($('#order_no').val()); 
	if(order_no==''){alert('Order no. is missing. Please try after some time!');  return false;	}
	
	if(GV_offapplist_filttypeval==1)
	{
		var off_acc_approved_status = $("input[name=off_acc_approved_status]:checked").val(); 
		if(!off_acc_approved_status){alert('Select Approve status!'); goToUlTabsClickCommon(4); return false;	}
		
		var off_acc_approved_by = jQuery.trim($('#off_acc_approved_by').val());  
		if(off_acc_approved_by==''){alert('Approved By should not be empty!'); goToUlTabsClickCommon(4); $('#off_acc_approved_by').focus(); return false;	}
	}
	else if(GV_offapplist_filttypeval==2)
	{
		var off_admin_approved_status = $("input[name=off_admin_approved_status]:checked").val();	
		if(!off_admin_approved_status){alert('Select Approve status!'); goToUlTabsClickCommon(4); return false;	}
		
		var off_admin_approved_by = jQuery.trim($('#off_admin_approved_by').val());  
		if(off_admin_approved_by==''){alert('Approved By should not be empty!'); goToUlTabsClickCommon(4); $('#off_admin_approved_by').focus(); return false;	}
	}
	else
	{
		alert('OfferSave: Invalid Call.!'); goToUlTabsClickCommon(4); return false;	
	}
	 
	
	var passArr={pURL:'process.php',pageParams:pageParams, off_approve_type:GV_offapplist_filttypeval };
	
	var pageParams=$('#frmOfferapprovalMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	pageParams.push(offtypeParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeOfferapprovalModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr);  
}

function closeOfferapprovalModalDialog(response)
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
	loadOfferapprovalMaster();
}	
	
	

function viewDeleteOfferapprovalMaster(id)
{
	$('#frmOfferapprovalDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'offer_approval'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteOfferapproval', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteOfferapproval(StrData) 
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

function deleteOfferapprovalMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'offer_approval'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmOfferapprovalDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadOfferapprovalMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 
function OfferapprovalMasterBack()
{ 
	loadOfferapprovalMaster();	
}
function bkOfferApprcalOfferPriceTotal()
{
	  
	//SRT
	
	var cosumer_offer_srt = $("#cosumer_offer_srt").val() *1;
	var corporate_offer_srt = $("#corporate_offer_srt").val() *1; 
	var exchange_offer_srt = $("#exchange_offer_srt").val() *1;
	var access_offer_srt = $("#access_offer_srt").val() *1;	
	var insurance_offer_srt = $("#insurance_offer_srt").val() *1;
	var add_discount_srt = $("#add_discount_srt").val() *1;
	var edr_srt = $("#edr_srt").val() *1;
	var other_contribution_srt = $("#other_contribution_srt").val() *1;
	
	var totsrt = parseFloat(cosumer_offer_srt) + parseFloat(corporate_offer_srt) + parseFloat(exchange_offer_srt) + parseFloat(access_offer_srt) + parseFloat(insurance_offer_srt) + parseFloat(add_discount_srt) + parseFloat(edr_srt) + parseFloat(other_contribution_srt); 
	if(totsrt<0) totsrt=0;
	totsrt=totsrt.toFixed(2); 
			 
	$("#total_srt").val(totsrt); 
	
	
	//SRT Additional
	
	var cosumer_offer_srt_addition = $("#cosumer_offer_srt_addition").val() *1;
	var corporate_offer_srt_addition = $("#corporate_offer_srt_addition").val() *1; 
	var exchange_offer_srt_addition = $("#exchange_offer_srt_addition").val() *1;
	var access_offer_srt_addition = $("#access_offer_srt_addition").val() *1;	
	var insurance_offer_srt_addition = $("#insurance_offer_srt_addition").val() *1;
	var add_discount_srt_addition = $("#add_discount_srt_addition").val() *1;
	var edr_srt_addition = $("#edr_srt_addition").val() *1;
	var other_contribution_srt_addition = $("#other_contribution_srt_addition").val() *1;
	
	//alert(parseFloat(cosumer_offer_srt_addition) +'==='+ parseFloat(corporate_offer_srt_addition) +'==='+ parseFloat(exchange_offer_srt_addition) +'==='+ parseFloat(access_offer_srt_addition) +'==='+ parseFloat(insurance_offer_srt_addition) +'==='+ parseFloat(add_discount_srt_addition) +'==='+ parseFloat(edr_srt_addition) +'==='+ parseFloat(other_contribution_srt_addition) );
	
	var totsrtadd = parseFloat(cosumer_offer_srt_addition) + parseFloat(corporate_offer_srt_addition) + parseFloat(exchange_offer_srt_addition) + parseFloat(access_offer_srt_addition) + parseFloat(insurance_offer_srt_addition) + parseFloat(add_discount_srt_addition) + parseFloat(edr_srt_addition) + parseFloat(other_contribution_srt_addition); 
	 
	if(totsrtadd<0) totsrtadd=0;
	
	var srtAddofferonly=totsrtadd;
	totsrtadd=totsrtadd.toFixed(2); 
			 
	$("#total_srt_addition").val(totsrtadd); 
	
	// SRT total offer
	
	var totsrtoffs = parseFloat(srtAddofferonly) + parseFloat(access_offer_srt) + parseFloat(insurance_offer_srt) + parseFloat(add_discount_srt) + parseFloat(other_contribution_srt); 
	if(totsrtoffs<0) totsrtoffs=0;
	totsrtoffs=totsrtoffs.toFixed(2); 
			 
	$("#total_srt_addition_offer").val(totsrtoffs); 
}
  
 