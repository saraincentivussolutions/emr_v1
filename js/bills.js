var GV_bill_status = {id:-1, name:'All'};

function loadViewBillsDetails()
{
	var titleCont= ' <li> View Bill </li>';
	topHeadTitle(titleCont);
	
	var bill_status = GV_bill_status.id;
	var a  = "view";	 
	var pageParams = {action:a, module:'bills', view:'list', bill_status:bill_status};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view);  
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadViewBillsTableList'};	 
	
	callCommonLoadFunction(passArr);  
}


function viewBillsListByStatus(obj)
{
	var status = $(obj).attr('stausattr');
	var status_name = $(obj).text();
	
	GV_bill_status = {id:status, name:status_name};
	loadViewBillsDetails();
}

function loadViewBillsTableList()
{
	var bill_status = GV_bill_status.id;
	var bill_status_name = GV_bill_status.name;
	
	var statusDesc = bill_status_name+'  <span class="caret"></span>';
	
	$('button#bill_status').html(statusDesc);
	$('button#bill_status').attr('stausattr', bill_status);
	
	var a  = "getList";	 
	var bill_status = GV_bill_status.id;
	var pageParams = {action:a, module:'bills',bill_status:bill_status}; 
	
	$("#billListDetailsTbl").dataTable({
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
function loadMakeBillsDetails()
{  
	CreateUpdateBillEntryDetails();
}
 

function CreateUpdateBillEntryDetails(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'bills', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBillEntryDetailsAddEdit', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadBillEntryDetailsAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'bills'};  
	
	var titleCont= ' <li> Bills </li>';
	    titleCont += '<li class="active">Bill Entry</li>';
	topHeadTitle(titleCont);
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataBillEntryDetailsAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataBillEntryDetailsAddEdit(StrData)
{
	
	_removefilelist = new Array();
	_billenseLCDDetails = [];
	
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
		
		$("#hid_id").val(rsOp.billenses_id);
		$("#bill_date").val(rsOp.bill_date); 
		 
		 
		var categorylist = rsOp.categorylist;
		var serviceslist = rsOp.serviceslist;
		var employeelist = rsOp.employeelist; 
		
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(categorylist, function(k,v){
								  
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		}); 

		
		var option_subcat_def = "<option value='0'>Select</option>"; 
		$.each(serviceslist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_subcat_def += "<option value='"+v.services_id+"' "+selected+" catid='"+v.category_id+"' srvrate='"+v.services_price+"'>"+v.services_name+"</option>";				  
		});
		
		
		var option_billBy = "<option value='0'>Select</option>";
	
		$.each(employeelist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_billBy += "<option value='"+v.employee_id+"' "+selected+" >"+v.employee_name+"</option>";				  
		});
		
		$('select#cmb_category_rw').empty();
		$('select#cmb_category_rw').append(option_cat_def);
		
		$('select#cmb_services_rw').empty();
		$('select#cmb_services_rw').append(option_subcat_def);
		
		$('select#bill_by_user').empty();
		$('select#bill_by_user').append(option_billBy);
		
		var table = '#customFields';
		var tempRow = $(table).find('#tempRow');
		
		var cnt = 3; 
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1)); 
			 
			$('select#cmb_category_'+(i+1)).empty();
			$('select#cmb_category_'+(i+1)).append(option_cat_def);
			
			$('select#cmb_services_'+(i+1)).empty();
			$('select#cmb_services_'+(i+1)).append(option_subcat_def); 
			
		} 
	} 
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	 $('.txt_qty, .txt_rate, .txt_disc, .txt_round_off').keyup(function(){
		calCulateMakeBillSubTotal(this);
	});  
	$('#txt_roundoff_value, #txt_disc_perc').keyup(function(){
		calculateMakeBillMainTotal();
	})
}
function calCulateMakeBillSubTotal(obj)
{
	var tr = $(obj).closest('tr');
	var qty = $(tr).find('.txt_qty').val();
	var rate = $(tr).find('.txt_rate').val(); 
	 
	if(qty!='' && rate!='')
	{
		var tot = qty*rate;  
		var famt = tot.toFixed(2);
		
		$(tr).find('.txt_item_total').val(famt); 
	}
	else
	{
		$(tr).find('.txt_item_total').val('');
	}
	
	calculateMakeBillMainTotal();
}

function calculateMakeBillMainTotal()
{ 
	var txt_sub_total = 0;
	var txt_main_total = 0;
	 
	$('.txt_item_total').each(function(){ 
		if(jQuery.trim($(this).val())!="")
		{
			txt_sub_total+=parseFloat($(this).val())*1; 
		} 
	});
	 
	var txt_sub_total_fx = txt_sub_total.toFixed(2); 
	$('#txt_sub_total').val(txt_sub_total_fx); 
	
	var txtperc = $('#txt_disc_perc').val(); 
	var valofperc = 0;
	if(txtperc!="")	valofperc = (txt_sub_total/100)*parseFloat(txtperc)*1;  
	
	var txtroundoff = $('#txt_roundoff_value').val();
	if(txtroundoff=="") txtroundoff=0;
	
	var total_less_val = parseFloat(valofperc)+parseFloat(txtroundoff);
	$('#hid_total_disc_amount').val(total_less_val);
	
	var txt_main_total = parseFloat(txt_sub_total)-parseFloat(total_less_val);
	
	var txt_main_totalfx = txt_main_total.toFixed(2); 
	$('#txt_main_total').val(txt_main_totalfx);
}

function setBillStatus()
{
	if($('input[type=radio][name=opn_app_reject]').is(':checked'))
	{
		var checked_val = $('input[type=radio][name=opn_app_reject]:checked').val();
		if(checked_val == 2)
		{
			$('#reject_notes').show();
		}
		else
		{
			$('#txt_reject_notes').val('');
			$('#reject_notes').hide();
		}
	}
}

function addMakeBillDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getBillDetailTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	$('.txt_qty, .txt_rate, .txt_disc, .txt_round_off').keyup(function(){
		calCulateMakeBillSubTotal(this);
	});
 
}

function viewMakeBillOnChangeCategory(id)
{
	var catid = $('select#cmb_category_'+(id)).val();
	$('select#cmb_services_'+(id)).find('option').hide();
	$('select#cmb_services_'+(id)).find('option').each(function(){
		if(catid == $(this).attr('catid'))
		$(this).show();
	});
}
function viewMakeBillOnChangeServices(id)
{
	var servval = $('select#cmb_services_'+(id)).val();
	 //alert($('select#cmb_services_'+(id)).attr('srvrate'));
	$('select#cmb_services_'+(id)).find('option').each(function(){
		if(servval == $(this).val())
		{
			$('input#txt_services_rate_'+(id)).val($(this).attr('srvrate'));
			$('input#txt_services_rate_'+(id)).trigger('keyup');
		}
	});
}

function getBillDetailTableMaxId()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	var trid = 0;
	$(table).find('tbody tr:not(#tempRow)').each(function(){
			var id = $(this).attr('id');
			id = id.replace('bill_','');
			id = Number(id);
			if(trid<id) trid = id;
	});
	
	return trid;
}

function CreateUpdateMakeBillSave()
{  
	var customer_name = jQuery.trim($('#customer_name').val()); 
	var customer_mobile = jQuery.trim($('#customer_mobile').val());
	var bill_date = jQuery.trim($('#bill_date').val());
	var bill_by_user = jQuery.trim($('#bill_by_user').val());
	var amnt = jQuery.trim($('#txt_main_total').val());
	
	if(customer_name=="" && customer_mobile=="") { alert('Enter customer details!'); $('#customer_name').focus(); return false; }
	if(bill_date=="" ) { alert('Enter bill date!'); $('#bill_date').focus(); return false; }
	if(bill_by_user==0 ) { alert('Select Bill by user!'); $('#bill_by_user').focus(); return false; }
	
	if(amnt<=0){ alert('Total amount is empty!'); return false; }
	
	if(confirm("Are you sure want to generate bill?")==false){ return false; }
	
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'bills'} 
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmMakeBilLEntry').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
  
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeMakeBIllModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeMakeBIllModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	var new_id=0; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message; 
		
		if(data.new_id) new_id=data.new_id;
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
	billEntryConfirmPrint(new_id);
}	
function billEntryConfirmPrint(id)
{
	if(id>0)
	{
		if(confirm("Are you sure want to print?")==false){ loadViewBillsDetails(); }
		else { billEntryPrintOK(id,'load'); }
	}
	else
	{
		loadViewBillsDetails();		
	}
}	
function CreateUpdatePrintBill()
{
	var id = $('#frmMakeBillView').find("#hid_id").val();  	
	
	if(confirm("Are you sure want to print?")==true){ billEntryPrintOK(id, 'dont_load_page'); } 
}
function billEntryPrintOK(id, page_load_type)
{
	alert('Print option will be done by praga');
	if(page_load_type!="dont_load_page") { loadViewBillsDetails(); }
}
function ViewBilledDetails(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'bills', view:'viewbill'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); 
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'ViewOnLoadBilledDetails'};	
	
	callCommonLoadFunction(passArr); 
		
}	
function ViewOnLoadBilledDetails()
{
	//will do later	
}

function CreateUpdateMakeBillCancel()
{
	var id = $('#frmMakeBillView').find("#hid_id").val();  
	$('#frmCancelBillDetails').find("#hid_id").val(id);
	$('#viewDeleteModal').modal({  show:true, backdrop:false });
}

function CreateUpdateMakeBillConfirmCancel()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'cancel'};  
	var modParams = {name:'module', value:'bills'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCancelBillDetails').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadViewBillsDetails', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
} 
 
function MakeBillBack()
{ 
	loadViewBillsDetails();	
} 

function deleteMakeBillRow(rw)
{
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_bill_detid_'+rw).val();
	var hid_temp_del = $('#hid_temp_del').val();
	
	if(del_val!='')
	{
		if(hid_temp_del!='')
		{
			del_val = hid_temp_del+','+del_val;
		}
	}
	
	//alert(del_val);
	
	$('#hid_temp_del').val(del_val); 
	
	$(tr).remove();
	calculateMakeBillMainTotal(); 
	 
	var addtblcnt = 0;
	$('#customFields').find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addMakeBillDetailRow();
}

 