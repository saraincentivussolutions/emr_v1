function loadVehicleExchangeListMaster()
{  
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Vehicle Exchange</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'vehicle_exchange', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadVehicleExchangeListdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadVehicleExchangeListdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'vehicle_exchange'}; 
	
	$("#vehicleExchangeListMasterTbl").dataTable({
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
					checkPermssion('Vehicle Exchange');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 

function CreateUpdateVehicleExchangeListMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'vehicle_exchange', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadVehicleExchangeListMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadVehicleExchangeListMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'vehicle_exchange'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataVehicleExchangeListMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataVehicleExchangeListMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.booking_transaction_id);  
		
		$("#order_no").val(rsOp.order_no);
		
		var financierlist = rsOp.financierlist; 
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(financierlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.financier_id+"' "+selected+">"+v.financier_name+"</option>";				  
		});  
		 
		$('select#finance_previous_financier').empty();
		$('select#finance_previous_financier').append(option_cat_def);
		
		$("#scheme_bonus_tata").val(rsOp.exchange_offer);
		$("#scheme_bonus_srt").val(rsOp.srt_total_offer);
		
		if(Number(rsOp.vehicle_exchange_id)>0)
		{  
			$("#exchange_model").val(rsOp.exchange_model);
			$("#manufacture_year").val(rsOp.manufacture_year); 
			$("#numberof_owners").val(rsOp.numberof_owners);
			$("#running_km").val(rsOp.running_km);
			$("#registration_number").val(rsOp.registration_number);
			$("#exchange_price").val(rsOp.entered_exchange_price);    
			//$("#entered_exchange_price").val(rsOp.exchange_price);
			$('input[name=finance_previous_status]').each(function(){
														if($(this).val()==rsOp.finance_previous_status) this.checked=true;
														});
			$("#finance_previous_financier").val(rsOp.finance_previous_financier);  
			$("#finance_previous_loanamnt").val(rsOp.finance_previous_loanamnt);  
			
			var chklist_avai_list = rsOp.chklist_avai_list; 
		
			$('input[name="chk_available[]"]').each(function(){ 
														  if((jQuery.inArray($(this).val(),chklist_avai_list)!=-1) ) this.checked=true;
														});
			
			$('input[name=exchange_type]').each(function(){
														if($(this).val()==rsOp.exchange_type) this.checked=true;
														});
			
			$("#scheme_bonus_tata").val(rsOp.scheme_bonus_tata);
			$("#scheme_bonus_srt").val(rsOp.scheme_bonus_srt);
			$("#actual_paid_tata").val(rsOp.actual_paid_tata);
			$("#actual_paid_srt").val(rsOp.actual_paid_srt);
			
			$("#actual_value").val(rsOp.actual_value);
			$('input[name=owner_different]').each(function(){
														if($(this).val()==rsOp.owner_different) this.checked=true;
														});
			
			$("#owner_name").val(rsOp.owner_name);
			$("#owner_relationship").val(rsOp.owner_relationship);
			$("#proff_collected").each(function(){
														if($(this).val()==rsOp.proff_collected) this.checked=true;
														});
			
			 
		} 
		else
		{
			 //do some validations
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Vehicle Exchange details':'Vehicle Exchange details'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	$("input[name=finance_previous_status]").click(function(){ 
		ve_financePrecLoanChangeValidations(); 
	});
	
	$('input[name=exchange_type]').click(function(){
		setExchangeTypeFilter();											  
	});
	 
	$('input[name=owner_different]').click(function(){
		setOwnerRestrictionFilter();
	});
	setExchangeTypeFilter();
	//ve_financePrecLoanChangeValidations(); 
	setOwnerRestrictionFilter();
}
function setExchangeTypeFilter()
{
	var exchange_type = $('input[name=exchange_type]:checked').val();
	
	if(exchange_type == 1)
	{
		$('.clsClaimType').show(); 
		
		//$('.clsActualType').find('input[type=text]').val('');
		$('.clsActualType').hide();
	}
	else
	{
		$('.clsActualType').show(); 
		
		//$('.clsClaimType').find('input[type=text]').val('');
		$('.clsClaimType').hide();
		
		ve_financePrecLoanChangeValidations();
		setOwnerRestrictionFilter();
	}
}

function setOwnerRestrictionFilter()
{
	var owner_different = $('input[name=owner_different]:checked').val();
	
	if(owner_different == 1)
	{
		$('.clsOwnerType').show();
	}
	else
	{
		$('.clsOwnerType').find('input[type=text]').val('');
		$('.clsOwnerType').find('select').val('');
		$('.clsOwnerType').find('input[type=checkbox]').each(function(){
			this.checked = false;
		});
		$('.clsOwnerType').hide();
	}
}

function ve_financePrecLoanChangeValidations()
{
	var finance_previous_status = $("input[name=finance_previous_status]:checked").val();	
	 
	if(finance_previous_status==1){ $('.clsFinbankAmnt').show(); }
	else{$('.clsFinbankAmnt').hide();}
	
}
 
function CreateUpdateVehicleExchangeListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'vehicle_exchange'}  
	 
	if(jQuery.trim($('#order_no').val())=="") { alert('Order No. is missong. Please try after some time'); return false; }
	
	var exchange_type = $('input[name=exchange_type]:checked').val();
	
	if(exchange_type==1)
	{
		if(jQuery.trim($('#actual_paid_tata').val())==0) { alert('Actual paid TATA should not be empty'); $('#actual_paid_tata').focus(); return false; } 
		if(jQuery.trim($('#actual_paid_srt').val())==0) { alert('Actual paid SRT should not be empty'); $('#actual_paid_srt').focus(); return false; }  
		 
	}
	else
	{ 
		if(jQuery.trim($('#exchange_model').val())=="") { alert('Exchange model should not be empty'); $('#exchange_model').focus(); return false; } 
		if(jQuery.trim($('#exchange_price').val())=="") { alert('Exchange price should not be empty'); $('#exchange_price').focus(); return false; }	
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmVehicleExchangeListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeVehicleExchangeListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeVehicleExchangeListModalDialog(response)
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
	loadVehicleExchangeListMaster();
}	
	
	 
 