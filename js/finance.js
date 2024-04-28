var GV_finlist_filt_finstatus=-1;
var GV_finlist_filt_bytype='';
function loadFinanceListMaster()
{  
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Finance</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'finance', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadFinanceListdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}
function finlistfinstatuschange()
{
	//GV_finlist_filt_bytype=1;
	GV_finlist_filt_finstatus=$('#financelist_filt_finstatus').val();	
	loadFinanceListMaster();
}
function finlistfintypeApplystatusCOnd()
{
	 
	$('select#financelist_filt_finstatus').find('option').hide();
	//$('select#financelist_filt_finstatus').val(0);
	$('select#financelist_filt_finstatus').find('option').each(function()
	{
		if($(this).val()<=0 || $(this).val()==7 || $(this).val()==8 || $(this).val()==10 ) $(this).show();
		
		if(GV_finlist_filt_bytype==1 || GV_finlist_filt_bytype==3 )
		{
			if( $(this).val()==1 || $(this).val()==2 || $(this).val()==3 || $(this).val()==4 || $(this).val()==5 || $(this).val()==6 ) $(this).show();
		}
		else if(GV_finlist_filt_bytype==2 || GV_finlist_filt_bytype==4 )
		{
			if( $(this).val()==11 || $(this).val()==12 || $(this).val()==13 || $(this).val()==14  ) $(this).show();
		}
	});
}
function finlistfintypechange()
{
	GV_finlist_filt_finstatus=-1;
	GV_finlist_filt_bytype=$('#financelist_filt_bytype').val();	 
	finlistfintypeApplystatusCOnd();
	loadFinanceListMaster();
}
function loadFinanceListdataTableList()
{
	
	if(GV_finlist_filt_bytype == '')
	   {
		   var hdn_finance_view = $('#hdn_finance_view').val();		  
		   $('#financelist_filt_bytype').val(hdn_finance_view);
		   GV_finlist_filt_bytype = hdn_finance_view;
	   }
	   
	
	finlistfintypeApplystatusCOnd();
	
	
	
	$('#financelist_filt_bytype').val(GV_finlist_filt_bytype);	
	$('#financelist_filt_finstatus').val(GV_finlist_filt_finstatus);	
	
	var a  = "getList";	 
	var pageParams = {action:a, filt_bytype:GV_finlist_filt_bytype, filt_finstatus:GV_finlist_filt_finstatus,  module:'finance'}; 
	
	$("#financeListMasterTbl").dataTable({
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
					checkPermssion('Finance');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 

function CreateUpdateFinanceListMasterList(idVal, type)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var template = '';
	if(type == 1 || type == 3) template = 'view';
	else template = 'view_customer';
	
	var pageParams = {id:idVal, action:a, module:'finance', view:template};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadFinanceListMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadFinanceListMasterAddEdit', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}

function pageRestictionFinance()
{
	var kyc_date = $('#kyc_date').val();
	var expected_do_date = $('#expected_do_date').val();
	var login_date = $('#login_date').val();
	var document_date = $('#document_date').val();
	var do_date = $('#do_date').val();
	
	var approval_status = $('input[name=approval_status]:checked').val();
	var mmr_status = $('input[name=mmr_status]:checked').val();
	var do_approved = $('input[name=do_approved]:checked').val();
	
	/*$('#login_date, #document_date, #do_date, #remark_desc').attr('disabled', true);
		$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
			this.disabled = true;
		});*/
		
		//alert('test');
		
	if(kyc_date!='' || expected_do_date!='')
	{
		$('#login_date').removeAttr('disabled');
		
		if(login_date == '')
		{
			$('#document_date, #do_date').attr('disabled', true);
			$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
				this.disabled = true;
			});
		}
		else
		{
			$('input[name=approval_status]').each(function(){
				this.disabled = false;
			});
			
			if(approval_status==undefined || approval_status == 2)
			{
				$('#document_date, #do_date').attr('disabled', true);
				$('input[name=mmr_status], input[name=do_approved]').each(function(){
					this.disabled = true;
				});
			}
			else if(approval_status == 1)
			{
				$('#document_date').removeAttr('disabled');
				
				if(document_date == '')
				{
					$('#do_date').attr('disabled', true);
					$('input[name=mmr_status], input[name=do_approved]').each(function(){
						this.disabled = true;
					});
				}
				else
				{
					$('input[name=mmr_status]').each(function(){
						this.disabled = false;
					});
					
					if(mmr_status==undefined || mmr_status == 2)
					{
						$('#do_date').attr('disabled', true);
						$('input[name=do_approved]').each(function(){
							this.disabled = true;
						});
					}
					else if(mmr_status == 1)
					{
						$('#do_date').removeAttr('disabled');
						if(do_date == '')
						{
							//$('#remark_desc').attr('disabled', true);
							$('input[name=do_approved]').each(function(){
								this.disabled = true;
							});
						}
						else
						{
							$('input[name=do_approved]').each(function(){
								this.disabled = false;
							});
							
							if(do_approved==undefined || do_approved == 2)
							{
								//$('#remark_desc').attr('disabled', true);
								
							}
							else if(do_approved == 1)
							{
								//$('#remark_desc').attr('disabled', false);	
							}
						}
						
					}
				}
			}
		}
		
	}
	else
	{
		
		$('#document_date, #do_date, #login_date').attr('disabled', true);
		$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
			this.disabled = true;
		});
	}
	
	
}

function loadFinanceListMasterAddEdit(StrData)
{
	
	
	
	
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'finance'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataFinanceListMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataFinanceListMasterAddEdit(StrData)
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
		
		var financierlist = rsOp.financierlist; 
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(financierlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.financier_id+"' "+selected+">"+v.financier_name+"</option>";				  
		});  
		 
		$('select#financier_id').empty();
		$('select#financier_id').append(option_cat_def); 
		
		$("#order_no").val(rsOp.order_no);
		
		if(Number(rsOp.finance_transaction_id)>0)
		{  
			$('#hid_finance_transaction').val(rsOp.finance_transaction_id);
			$("#financier_id").val(rsOp.financier_id);
			$("#finance_amount").val(rsOp.finance_amount); 
			$("#followed_by").val(rsOp.followed_by);
			$("#kyc_date").val(rsOp.kyc_date);
			$("#expected_do_date").val(rsOp.expected_do_date);
			$("#login_date").val(rsOp.login_date); 
			$('input[name=approval_status]').each(function(){
														if($(this).val()==rsOp.approval_status) this.checked=true;
														});
			
			$("#document_date").val(rsOp.document_date);
			$('input[name=mmr_status]').each(function(){
														if($(this).val()==rsOp.mmr_status) this.checked=true;
														});
			$("#do_date").val(rsOp.do_date);
			$('input[name=do_approved]').each(function(){
														if($(this).val()==rsOp.do_approved) this.checked=true;
														});
			$("#remark_desc").val(rsOp.remark_desc);
			$("#kyc_notes").val(rsOp.kyc_notes);
			$("#login_notes").val(rsOp.login_notes);
			$("#document_notes").val(rsOp.document_notes);
			$("#do_notes").val(rsOp.do_notes);
			
			$("#first_followup_date").val(rsOp.first_followup_date);
			$("#second_followup_date").val(rsOp.second_followup_date);
			$("#third_followup_date").val(rsOp.third_followup_date);
			$("#fourth_followup_date").val(rsOp.fourth_followup_date);
			$("#next_followup_date1").val(rsOp.next_followup_date1);
			$("#next_followup_date2").val(rsOp.next_followup_date2);
			$("#next_followup_date3").val(rsOp.next_followup_date3);
			
			if(rsOp.finance_process_status == 10)
			{
				$('button#savefinance').hide();
				$('#addrcptbtn').html('<i class="fa fa-eye"></i> View Receipt');
			}
			
			 
		} 
		else
		{
			 //do some validations
		} 
	} 
	///var modal_title = Number($("#hid_id").val())>0?'Edit Finance details':'New Finance details'; 
	//$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	
	ulTabsClickCommon(); // written in common
	
	 
	
	
	/*$('.datepicker').datepicker().on('changeDate', function (ev) {
		pageRestictionFinance(); 
	});*/

	if(rsOp.finance == 1 || rsOp.finance ==3)
	{
		
		$('#login_date, #document_date, #do_date').attr('disabled', true);
	$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
		this.disabled = true;
	});
		
	$('#kyc_date, #expected_do_date, #login_date, #document_date, #do_date, input[name=approval_status], input[name=mmr_status], input[name=do_approved]').change(function(){
		pageRestictionFinance(); 					   
	});
		
		pageRestictionFinance(); 
		
		
	}
	else
	{
		showReceiptAddOpt();
	}
	
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
 
function CreateUpdateFinanceListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'finance'}  
	 
	if(jQuery.trim($('#order_no').val())=="") { alert('Order No. is missong. Please try after some time'); return false; }
	if($('#financier_id').val()==0) { alert('Select Financier'); $('#financier_id').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmFinanceListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeFinanceListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeFinanceListModalDialog(response)
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
	loadFinanceListMaster();
}	
function FinanceEntryBack()
{ 
	loadFinanceListMaster();	
}	
	 
	 
function addReceiptFromFinance()
{
 	var booking_id = $('#hid_id').val();
	var finance_transaction_id = $('#hid_finance_transaction').val();
	var a  = "view";	 
	var pageParams = {booking_id:booking_id, action:a, module:'finance', view:'receipt_add', finance_transaction_id:finance_transaction_id};  
	var custVals = {};  
	custVals["booking_id"]=booking_id;
	custVals["finance_transaction_id"]=finance_transaction_id;
	//custVals["booking_id"]=booking_id;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadFinanceReceiptDetailData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	callCommonLoadFunction(passArr); 
}

function loadFinanceReceiptDetailData(StrData)
{
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 	
	
	var a  = "getReceiptView";	 
	var pageParams = {booking_id:opData.booking_id, finance_transaction_id:opData.finance_transaction_id, action:a,  module:'finance'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataFinanceReceiptDetailMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataFinanceReceiptDetailMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	var finance_amount = $('#finance_amount').val();
	$('#frmReceiptDetailsMaster').find('#hid_booking_id').val(GV_booking_id);
	$('#frmReceiptDetailsMaster').find('#receipt_amount').val(finance_amount);
	
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
		
		$('#frmReceiptDetailsMaster').find("#hid_id").val(rsOp.receipt_transaction_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		//$("#category_status").val(rsOp.status);
		
		$('#frmReceiptDetailsMaster').find("#entry_date").val(rsOp.entry_date);
		$('#frmReceiptDetailsMaster').find("#receipt_date").val(rsOp.receipt_date);
		
		if(Number(rsOp.receipt_transaction_id)>0)
		{ 
		
			$('#viewPageModal').find('#btnModalSave').hide();
			
			$('#frmReceiptDetailsMaster').find("#order_no").val(rsOp.order_no);
			
			$('#frmReceiptDetailsMaster').find("#entry_by").val(rsOp.entry_by);
			$('#frmReceiptDetailsMaster').find("#receipt_no").val(rsOp.receipt_no);
			
			$('#frmReceiptDetailsMaster').find('input[name=payment_mode]').each(function(){
				if(this.value == rsOp.payment_mode ) { this.checked = true; } 
			});
			$('#frmReceiptDetailsMaster').find("#receipt_amount").val(rsOp.receipt_amount);
			$('#frmReceiptDetailsMaster').find("#receipt_remarks").val(rsOp.receipt_remarks);
			$('#frmReceiptDetailsMaster').find('input[name=chque_dd_type]').each(function(){
				if(this.value == rsOp.chque_dd_type ) { this.checked = true; } 
			});
			$('#frmReceiptDetailsMaster').find("#bank_name").val(rsOp.bank_name);
			$('#frmReceiptDetailsMaster').find("#cheque_no").val(rsOp.cheque_no);
			
			$('#frmReceiptDetailsMaster').find('.clsHideRcptNo').show();
		
			
			//$('#frmReceiptDetailsMaster').find('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('#frmReceiptDetailsMaster').find("#receipt_remarks").val('By finance: ');
			if(opData.order_no)
			{
				$('#frmReceiptDetailsMaster').find("#order_no").val(opData.order_no);
			}
			 $('#frmReceiptDetailsMaster').find('#btnModalSave').show();
			//$('#frmReceiptDetailsMaster').find('.divClsActiveStatus').hide();
		} 
	} 
	else
	{
		if(opData.order_no)
			{
				$('#frmReceiptDetailsMaster').find("#order_no").val(opData.order_no);
			}
	}
	var modal_title = Number($('#frmReceiptDetailsMaster').find("#hid_id").val())>0?'View Receipt detail':'New Receipt detail'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$('#frmReceiptDetailsMaster').find("input[name=payment_mode]").click(function(){ 
		receiptPaymentModeChangeValidations(); 
	});
	
	receiptPaymentModeChangeValidations();
}

function CreateUpdateCustomerFinanceListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save_customer'};  
	var modParams = {name:'module', value:'finance'}  
	 
	if(jQuery.trim($('#order_no').val())=="") { alert('Order No. is missong. Please try after some time'); return false; }
	if($('#financier_id').val()==0) { alert('Select Financier'); $('#financier_id').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmFinanceListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeFinanceListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
}

function pageRestictionFinanceCustomer()
{
	//, second_followup_date, third_followup_date, fourth_followup_date, next_followup_date1, next_followup_date2, next_followup_date3
	
	var kyc_date = $('#kyc_date').val();
	var first_followup_date = $('#first_followup_date').val();
	var second_followup_date = $('#second_followup_date').val();
	var third_followup_date = $('#third_followup_date').val();
	var fourth_followup_date = $('#fourth_followup_date').val();
	
	var next_followup_date1 = $('#second_followup_date1').val();
	var next_followup_date2 = $('#third_followup_date2').val();
	var next_followup_date3 = $('#fourth_followup_date3').val();
	
	//var approval_status = $('input[name=approval_status]:checked').val();
	//var mmr_status = $('input[name=mmr_status]:checked').val();
	var do_approved = $('input[name=do_approved]:checked').val();
	
	/*$('#login_date, #document_date, #do_date, #remark_desc').attr('disabled', true);
		$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
			this.disabled = true;
		});*/
		
		//alert('test');
		
	if(kyc_date!='' || expected_do_date!='')
	{
		$('#login_date').removeAttr('disabled');
		
		if(login_date == '')
		{
			$('#document_date, #do_date').attr('disabled', true);
			$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
				this.disabled = true;
			});
		}
		else
		{
			$('input[name=approval_status]').each(function(){
				this.disabled = false;
			});
			
			if(approval_status==undefined || approval_status == 2)
			{
				$('#document_date, #do_date').attr('disabled', true);
				$('input[name=mmr_status], input[name=do_approved]').each(function(){
					this.disabled = true;
				});
			}
			else if(approval_status == 1)
			{
				$('#document_date').removeAttr('disabled');
				
				if(document_date == '')
				{
					$('#do_date').attr('disabled', true);
					$('input[name=mmr_status], input[name=do_approved]').each(function(){
						this.disabled = true;
					});
				}
				else
				{
					$('input[name=mmr_status]').each(function(){
						this.disabled = false;
					});
					
					if(mmr_status==undefined || mmr_status == 2)
					{
						$('#do_date').attr('disabled', true);
						$('input[name=do_approved]').each(function(){
							this.disabled = true;
						});
					}
					else if(mmr_status == 1)
					{
						$('#do_date').removeAttr('disabled');
						if(do_date == '')
						{
							//$('#remark_desc').attr('disabled', true);
							$('input[name=do_approved]').each(function(){
								this.disabled = true;
							});
						}
						else
						{
							$('input[name=do_approved]').each(function(){
								this.disabled = false;
							});
							
							if(do_approved==undefined || do_approved == 2)
							{
								//$('#remark_desc').attr('disabled', true);
								
							}
							else if(do_approved == 1)
							{
								//$('#remark_desc').attr('disabled', false);	
							}
						}
						
					}
				}
			}
		}
		
	}
	else
	{
		
		$('#document_date, #do_date, #login_date').attr('disabled', true);
		$('input[name=approval_status], input[name=mmr_status], input[name=do_approved]').each(function(){
			this.disabled = true;
		});
	}
	
	
}

function CreateUpdateFinanceReceiptMasterSave()
{
	var entry_date = $('#frmReceiptDetailsMaster').find("#entry_date").val();		
	var receipt_date = $('#frmReceiptDetailsMaster').find("#receipt_date").val();	
	var entry_by = $('#frmReceiptDetailsMaster').find("#entry_by").val();
	var payment_mode = $('#frmReceiptDetailsMaster').find("input[name=payment_mode]:checked").val();
	var chque_dd_type = $('#frmReceiptDetailsMaster').find("input[name=chque_dd_type]:checked").val();
	var bank_name = $('#frmReceiptDetailsMaster').find("#bank_name").val();
	var cheque_no = $('#frmReceiptDetailsMaster').find("#cheque_no").val();
	var receipt_remarks = $('#frmReceiptDetailsMaster').find("#receipt_remarks").val();
	var receipt_amount = $('#frmReceiptDetailsMaster').find("#receipt_amount").val();
	var hdn_receipt_chk = 1;
	$('#frmFinanceListMaster').find("#entry_date").val(entry_date);
	$('#frmFinanceListMaster').find("#receipt_date").val(receipt_date);
	$('#frmFinanceListMaster').find("#entry_by").val(entry_by);
	$('#frmFinanceListMaster').find("#payment_mode").val(payment_mode);
	$('#frmFinanceListMaster').find("#chque_dd_type").val(chque_dd_type);
	$('#frmFinanceListMaster').find("#bank_name").val(bank_name);
	$('#frmFinanceListMaster').find("#cheque_no").val(cheque_no);
	$('#frmFinanceListMaster').find("#receipt_remarks").val(receipt_remarks);
	$('#frmFinanceListMaster').find("#hdn_receipt_chk").val(hdn_receipt_chk);
	$('#frmFinanceListMaster').find("#receipt_amount").val(receipt_amount);
	$('#viewPageModal').modal('hide');
}

function showReceiptAddOpt()
{
	var do_approved = $('input[name=do_approved]:checked').val();
	if(do_approved == 1)
	{
		$('#addrcptbtn').show();
	}
	else
	{
		$('#addrcptbtn').hide();
	}
}