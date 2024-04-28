var GV_booking_id = '';
var GV_rcptlist_filt_ordstatus=0;
function loadReceiptsMaster()
{
	GV_booking_id = '';
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Receipts</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'receipts', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadReceiptsdataTableList'}; 
	
	callCommonLoadFunction(passArr); 
	
	
}
function rcptlistordstatuschange()
{
	GV_rcptlist_filt_ordstatus=$('#rcptlist_filt_ordstatus').val();	
	loadReceiptsMaster();
}
function loadReceiptsdataTableList()
{
	$('#rcptlist_filt_ordstatus').val(GV_rcptlist_filt_ordstatus);	
	
	var a  = "getList";	 
	var pageParams = {action:a, rcptlist_filt_status:GV_rcptlist_filt_ordstatus, module:'receipts'}; 
	
	$("#receiptsMasterTbl").dataTable({
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
					
					checkPermssion('Receipts');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}
 
 

function CreateUpdateReceiptsMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	
	var custVals = {};  
	custVals["id"]=idVal;
	GV_booking_id = idVal;
 
	
	
	/*var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Receipts</li>';
	topHeadTitle(titleCont);*/
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'receipts', view:'details'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadReceiptdataTableList',sendDataOnSuccess:'send'};	
	callCommonLoadFunction(passArr); 

		
}

function loadReceiptdataTableList()
{
	var a  = "getDetailList";	 
	var pageParams = {action:a, module:'receipts', hid_booking_id:GV_booking_id};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadReceiptsDetaildataTableList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function loadReceiptsDetaildataTableList(StrData)
{
	$('#hdn_booking_id').val(GV_booking_id);
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		/*$("#receiptsDetailsMasterTbl").dataTable({
				  "bPaginate": true,
				  "bLengthChange": false,
				  "bFilter": false,
				  "bSort": true,
				  "bInfo": true,
				  "bAutoWidth": false
			});*/
	
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
	
	if(opData.recp_list_order_no!=undefined)
	{
		$('#recp_list_order_no').val(opData.recp_list_order_no);
	}
	
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		var lpSno=0;
		$.each(rsOp,function(idx,vArr){
		
			lpSno++;
			
			var custCode=''+vArr.entry_date+'';
			
			var actbtn=""; var rwclr="";  var bankrel=""; 
			if(vArr.receip_cancelled==1)
			{
				var rwclr='style="color:#FF0000;"';	
				var bankrel='Cancelled';
			}
			else if(vArr.amount_reveived_status==1)
			{
				var actbtn='<span class="delete"  onclick="viewDeleteReceiptsMaster('+vArr.receipt_transaction_id+');" ><i class="fa fa-trash-o"></i> Cancel</span> ';	
			}
			else if(vArr.amount_reveived_status=='0')
			{
				var actbtn='<span class="delete"  onclick="viewDeleteReceiptsMaster('+vArr.receipt_transaction_id+');" ><i class="fa fa-trash-o"></i> Cancel</span> ';	
				var bankrel='<span onclick="CreateUpdateReceiptBankReconsiliation('+vArr.receipt_transaction_id+');" ><i class="fa fa-file-o"></i> Bank reconsiliation</span>';	
			}
			
			
			var appendRw='<tr '+rwclr+'><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.entry_by+'</td><td>'+vArr.receipt_no+'</td><td>'+vArr.receipt_date+'</td><td>'+vArr.payment_mode+'</td><td>'+vArr.receipt_amount+'</td><td>'+vArr.receipt_remarks+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateReceiptDetailsList('+vArr.receipt_transaction_id+');"><i class="fa fa-edit"></i> View </span>'+actbtn+'</td><td>'+bankrel+'</td></tr>';
			//alert(appendRw);
			$('#receiptsDetailsMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateReceiptDetailsList(idVal)
{
	
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'receipts', view:'receipt_add'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadReceiptDetailData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	callCommonLoadFunction(passArr); 
}

function loadReceiptDetailData(StrData)
{
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 	
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'receipts', hid_booking_id:GV_booking_id};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataReceiptDetailMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataReceiptDetailMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	$('#frmReceiptDetailsMaster').find('#hid_booking_id').val(GV_booking_id);
	
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
		
		$("#hid_id").val(rsOp.receipt_transaction_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		//$("#category_status").val(rsOp.status);
		
		$("#entry_date").val(rsOp.entry_date);
		$("#receipt_date").val(rsOp.receipt_date);
		
		if(Number(rsOp.receipt_transaction_id)>0)
		{ 
			$('#btnModalSave').hide();
			
			$("#order_no").val(rsOp.order_no);
			
			$("#entry_by").val(rsOp.entry_by);
			$("#receipt_no").val(rsOp.receipt_no);
			
			$('input[name=payment_mode]').each(function(){
				if(this.value == rsOp.payment_mode ) { this.checked = true; } 
			});
			$("#receipt_amount").val(rsOp.receipt_amount);
			$("#receipt_remarks").val(rsOp.receipt_remarks);
			$('input[name=chque_dd_type]').each(function(){
				if(this.value == rsOp.chque_dd_type ) { this.checked = true; } 
			});
			$("#bank_name").val(rsOp.bank_name);
			$("#cheque_no").val(rsOp.cheque_no);
			
			$('.clsHideRcptNo').show();
		
			
			//$('.divClsActiveStatus').show(); 
		} 
		else
		{
			
			if(opData.order_no)
			{
				$("#order_no").val(opData.order_no);
			}
			 $('#btnModalSave').show();
			//$('.divClsActiveStatus').hide();
		} 
	} 
	else
	{
		if(opData.order_no)
			{
				$("#order_no").val(opData.order_no);
			}
	}
	var modal_title = Number($("#hid_id").val())>0?'Edit Receipt detail':'New Receipt detail'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$("input[name=payment_mode]").click(function(){ 
		receiptPaymentModeChangeValidations(); 
	});
	
	receiptPaymentModeChangeValidations();
}
function receiptPaymentModeChangeValidations()
{
	var payment_mode = $("input[name=payment_mode]:checked").val();	
	if(payment_mode==2){ $('.clsBankDd').show(); }
	else{$('.clsBankDd').hide();}
	
}
function CreateUpdateReceiptBankReconsiliation(idVal)
{
	
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'receipts', view:'bank_reconsiliation'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadReceiptBankReconsiliationDetailData', sendCustPassVal:custVals, pageLoadContent:'#viewPageReconsModal .modal-body'};
	callCommonLoadFunction(passArr); 
}
function loadReceiptBankReconsiliationDetailData(StrData)
{
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 	
	
	var a  = "getSingeBankReconsView";	 
	var pageParams = {id:PidVal, action:a,  module:'receipts', hid_booking_id:GV_booking_id};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataReceiptBankReconsiliationDetailAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataReceiptBankReconsiliationDetailAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	$('#frmReceiptBankReconsDetailsMaster').find('#hid_booking_id').val(GV_booking_id);
	
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
		
		$("#hid_bank_recons_id").val(rsOp.receipt_transaction_id);
		
		rsOp.status = Number($("#hid_bank_recons_id").val())>0?rsOp.status:1; //set default active
		//$("#category_status").val(rsOp.status);
		
		if(Number(rsOp.receipt_transaction_id)>0)
		{ 
			if(rsOp.bank_recons_entry_status==1) $('#btnModalSave').hide();
			
			$('input[name=bank_recons_entry_status]').each(function(){
				if(this.value == rsOp.bank_recons_entry_status ) { this.checked = true; } 
			});
			
			$("#bank_recons_entry_date").val(rsOp.bank_recons_entry_date);
			$("#bank_recons_entry_by").val(rsOp.bank_recons_entry_by); 
			
			$('input[name=bank_recons_reason_type]').each(function(){
														if($(this).val()==rsOp.bank_recons_reason_type) this.checked=true;
														});
			$("#bank_recons_remarks").val(rsOp.bank_recons_remarks);  
		} 
		 
	} 
	 
	var modal_title = 'Bank reconsiliation'; 
	$('#viewPageReconsModal').find('.modal-title').text(modal_title); 
	$('#viewPageReconsModal').modal({  show:true, backdrop:false });
	
	$("input[name=bank_recons_entry_status]").click(function(){ 
		bkRcptReconsApprovalValidations(); 
	});
	
	
	bkRcptReconsApprovalValidations();
	 
}
function bkRcptReconsApprovalValidations()
{  
	var bank_recons_entry_status = $("input[name=bank_recons_entry_status]:checked").val(); 
	if(bank_recons_entry_status!=2)
	{ 
		$("input[name=bank_recons_reason_type]").each(function(){
												this.checked=false;	 
												$(this).attr('disabled','disabled');   
													  });
		$('.clsRcpBankReconsHide').hide();
		
	}
	else
	{
		$("input[name=bank_recons_reason_type]").each(function(){
												 
												$(this).removeAttr('disabled');   
													  }); 
		$('.clsRcpBankReconsHide').show();
	}
	 
}
function loadReceiptsData()
{
	
}
function CreateUpdateReceiptsMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'receipts'} 
	
	var order_no = jQuery.trim($('#order_no').val());
	var receipt_no = jQuery.trim($('#receipt_no').val());
	var receipt_date = jQuery.trim($('#receipt_date').val());
	var receipt_amount = jQuery.trim($('#receipt_amount').val()); 
	
	if(order_no==''){alert('Order no. should not be empty!');  $('#order_no').focus(); return false;	}
	//if(receipt_no==''){alert('Receipt no. should not be empty!'); $('#receipt_no').focus(); return false; }
	if(receipt_date==''){alert('Receipt date should not be empty!'); $('#receipt_date').focus(); return false; }
	if(receipt_amount==''){alert('Receipt amount should not be empty!'); $('#receipt_amount').focus(); return false; }

	 
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmReceiptDetailsMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeReceiptsModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeReceiptsModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		//GV_show_list_page_succmsg=data.message;
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
	refreshReceiptDetailData();
}	
	
function refreshReceiptDetailData()
{
	
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Receipts</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'receipts', view:'details'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadReceiptdataTableList',sendDataOnSuccess:'send'};	
	callCommonLoadFunction(passArr); 

		
}


function viewDeleteReceiptsMaster(id)
{
	$('#frmReceiptsDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'receipts'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
	pageParams.push({name:'hid_booking_id',value:GV_booking_id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteReceipts', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteReceipts(StrData) 
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

function deleteReceiptsMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'receipts'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmReceiptsDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	pageParams.push({name:'hid_booking_id',value:GV_booking_id});
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'refreshReceiptDetailData', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 

function viewReceiptsEntryOnChangeSalesTeam()
{
	var salesid = $('select#sales_team').val();
	$('select#customer_advisor').val(0); 
	
	$('select#customer_advisor').find('option').hide(); 
	$('select#customer_advisor').find('option').each(function(){
		if(salesid == $(this).attr('atr_salesteam') || $(this).val()==0)
		$(this).show();
	}); 
	 
} 

function viewReceiptsEntryOnChangeParentProd()
{
	var parprdid = $('select#parent_product_line').val();
	$('select#product_line').val(0);
	$('select#product_color_primary').val(0);
	$('select#product_color_secondary').val(0);
	$('select#product_color_additional').val(0);
	
	$('select#product_line').find('option').hide(); 
	$('select#product_line').find('option').each(function(){
		if(parprdid == $(this).attr('parprdid') || $(this).val()==0)
		$(this).show();
	});
	
	$('select#product_color_primary').find('option').hide(); 
	$('select#product_color_primary').find('option').each(function(){
		var parprdids=$(this).attr('parprdids');
		var parprdAr=new Array();
		if(parprdids) var parprdAr=parprdids.split(',');
		
		if((jQuery.inArray(parprdid,parprdAr)!=-1) || $(this).val()==0)
		$(this).show();
	});
	
	//
	$('select#product_color_secondary').find('option').hide(); 
	$('select#product_color_secondary').find('option').each(function(){
		var parprdids=$(this).attr('parprdids');
		var parprdAr=new Array();
		if(parprdids) var parprdAr=parprdids.split(',');
		
		if((jQuery.inArray(parprdid,parprdAr)!=-1) || $(this).val()==0)
		$(this).show();
	});
	
	//
	$('select#product_color_additional').find('option').hide(); 
	$('select#product_color_additional').find('option').each(function(){
		var parprdids=$(this).attr('parprdids');
		var parprdAr=new Array();
		if(parprdids) var parprdAr=parprdids.split(',');
		
		if((jQuery.inArray(parprdid,parprdAr)!=-1) || $(this).val()==0)
		$(this).show();
	});
} 
function receiptsValidations()
{
	var ex_vechicle = $("input[name=ex_vechicle]:checked").val();	
	if(ex_vechicle!=1){ $('.clsBkExchange').hide(); $('#ex_price').val(''); }
	else{$('.clsBkExchange').show();}
	
}
function receiptsExchangeOnchangeValidations()
{  
	var id = $("#hid_id").val(); 
	var ex_vechicle = $("input[name=ex_vechicle]:checked").val();	
	if(id>0 && ex_vechicle==1)
	{  
		var a  = "getOnchangeView";	 
		var modtype='vehicle_exchsnge';
		var pageParams = {id:id, action:a, modtype:modtype,  module:'retail'};  
					
		var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'receiptsOnchangePutVals', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
		
		callCommonLoadFunction(passArr); 
	}
}
function receiptsOnchangePutVals(StrData)
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
		
		if(Number(rsOp.retail_id)>0)
		{  
			$("#finance_amount").val(rsOp.finance_amount);
			$("#exchange_amount").val(rsOp.exchange_amount); 
			$("#offer_amount").val(rsOp.offer_amount);
			$('input[name=payment_received]').each(function(){
														if($(this).val()==rsOp.payment_received) this.checked=true;
														});
			$('input[name=vehicle_allotted]').each(function(){
														if($(this).val()==rsOp.vehicle_allotted) this.checked=true;
														});
			$('input[name=stock_type]').each(function(){
														if($(this).val()==rsOp.stock_type) this.checked=true;
														});
			$("#invoice_no").val(rsOp.invoice_no);
			$("#invoice_date").val(rsOp.invoice_date);
			$('input[name=rto_approved]').each(function(){
														if($(this).val()==rsOp.rto_approved) this.checked=true;
														});
			$("#rto_date").val(rsOp.rto_date);  
			 
		} 
		else
		{
			 //do some validations
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Retail details':'Retail details'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	 
	
}
function ReceiptEntryBack()
{
	loadReceiptsMaster();	
}
function CreateUpdateReceiptsBankReconsSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'bank_reconssave'};  
	var modParams = {name:'module', value:'receipts'}  
	
	var bank_recons_entry_status = $("input[name=bank_recons_entry_status]:checked").val();	
	var bank_recons_entry_date = jQuery.trim($('#bank_recons_entry_date').val());
	var bank_recons_entry_by = jQuery.trim($('#bank_recons_entry_by').val()); 
	
	if(!bank_recons_entry_status){alert('Select status');  return false;	}
	//if(receipt_no==''){alert('Receipt no. should not be empty!'); $('#receipt_no').focus(); return false; }
	if(bank_recons_entry_date==''){alert('Entry Date should not be empty!'); $('#bank_recons_entry_date').focus(); return false; }
	if(bank_recons_entry_by==''){alert('Entered By should not be empty!'); $('#bank_recons_entry_by').focus(); return false; }

	 
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmReceiptBankReconsDetailsMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeReceiptsBankReconsModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr);   //closeReceiptsModalDialog
		
}

function closeReceiptsBankReconsModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		//GV_show_list_page_succmsg=data.message;
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
			
	
	$('#viewPageReconsModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	refreshReceiptDetailData();
}
 