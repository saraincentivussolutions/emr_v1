var GV_retaillist_filt_cmpstatus=1;
function loadRetailListMaster()
{  
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Retail</li>';
	topHeadTitle(titleCont);
	
	if(GV_retaillist_filt_cmpstatus==1)
	{
	
		var a  = "view";	 
		var pageParams = {action:a, module:'retail', view:'list'};  
		
		var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
		var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadRetailListdataTableList'};	 
		
		callCommonLoadFunction(passArr);  
	}
	else if(GV_retaillist_filt_cmpstatus==2)
	{
	
		var a  = "view";	 
		var pageParams = {action:a, module:'retail', view:'completed_list'};  
		
		var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
		var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadRetailCompletedListdataTableList'};	 
		
		callCommonLoadFunction(passArr);  
	}
	else { alert('Retail: Invalid list call'); }
}
function retaillistfiltcmpstatuschange()
{
	GV_retaillist_filt_cmpstatus=$('#retaillist_filt_cmpstatus').val();	
	loadRetailListMaster();
}
function loadRetailListdataTableList()
{
	
	$('#retaillist_filt_cmpstatus').val(GV_retaillist_filt_cmpstatus);	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'retail'}; 
	
	$("#retailListMasterTbl").dataTable({
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
					//checkPermssion('retail');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 
function loadRetailCompletedListdataTableList()
{
	$('#retaillist_filt_cmpstatus').val(GV_retaillist_filt_cmpstatus);	
	
	var a  = "getCompletedList";	 
	var pageParams = {action:a, module:'retail'}; 
	
	$("#retailListMasterTbl").dataTable({
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
					//checkPermssion('retail');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 

function CreateUpdateRetailListMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'retail', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadRetailListMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadRetailListMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'retail'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataRetailListMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataRetailListMasterAddEdit(StrData)
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
		$("#finance_amount").val(rsOp.finance_amount);
		$("#exchange_amount").val(rsOp.exchange_amount); 
		$("#offer_amount").val(rsOp.offer_amount);
		
		if(Number(rsOp.retail_id)>0)
		{  
			
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
			
			$('input[name=stock_status]').each(function(){
														if($(this).val()==rsOp.stock_status) this.checked=true;
														});
			$("#stock_chasis_id").val(rsOp.stock_chasis_id); 
			$("#stock_chasis_no").val(rsOp.stock_chasis_no); 
			
			$('#btnModalSave').hide();
			 
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
	 
	/*$("input[name=stock_type]").click(function(){ 
											  
		//retailStockTypeChangeValidations(); 
	});*/
	//retailStockTypeChangeValidations();
	 
	
}
function retailStockTypeChangeValidations()
{
	//var stock_type = $("input[name=stock_type]:checked").val();	
	//if(stock_type==1){ $('.stockChasisShw').show(); }
	//else{$('.stockChasisShw').hide();}
	
}
 
function CreateUpdateRetailListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'retail'}  
	 
	if(jQuery.trim($('#order_no').val())=="") { alert('Order No. is missong. Please try after some time'); return false; }
	if(jQuery.trim($('#stock_chasis_id').val())==0) { alert('Chasis no. should not be empty'); $('#stock_chasis_id').focus(); return false; } 
	if(jQuery.trim($('#invoice_no').val())=="") { alert('Invoice No. should not be empty'); $('#invoice_no').focus(); return false; } 
	if(jQuery.trim($('#invoice_date').val())=="") { alert('Invoice date should not be empty'); $('#invoice_date').focus(); return false; }  
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmRetailListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeRetailListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeRetailListModalDialog(response)
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
	loadRetailListMaster();
}	
	
	 
 function getChasisNoFromStockList()
{
	$('#viewStockListModal').find('.modal-title').text('Stock List'); 
	$('#viewStockListModal').modal({  show:true, backdrop:false });
	loadRetailStockListdataTableList();
		
}
 
 function loadRetailStockListdataTableList()
{
	
	
	var a  = "getStockList";	 
	
	
	/*if($('#retailStockListMasterTbl').hasClass('dataTable'))
	{
		
	}
	else*/
	{
	var stock_type = $('input[name=stock_status]:checked').val();
	var booking_id = $('#hid_id').val();
	var pageParams = {action:a, module:'retail',hdn_booking_id:booking_id, stock_type:stock_type}; 
	$("#retailStockListMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				   "bAutoWidth": false,
				   "bDestroy": true,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					},
					
					"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
						/*var rid = $(nRow).attr('id');
						$('#retailStockListMasterTbl tbody').on( 'click', 'tr#'+rid,function () {
								if ( $(this).hasClass('selected') ) {
									$(this).removeClass('selected');
								}
								else {
									$('#retailStockListMasterTbl').find('tr.selected').removeClass('selected');
									$(this).addClass('selected');
								}
						});*/
					},
				"fnDrawCallback":function()
				{
					
					$('#retailStockListMasterTbl tbody').on( 'click', 'tr', function () {
						if ( $(this).hasClass('selected') ) {
							$(this).removeClass('selected');
						}
						else {
							$('#retailStockListMasterTbl').find('tr.selected').removeClass('selected');
							$(this).addClass('selected');
						}
					} );
					
					/*$('#retailStockListMasterTbl tbody').on( 'dblclick', 'tr', function () {
							var row = this;															 
							setChasisNo(row);
					} );*/
					//checkPermssion('retail');
				}
				  
	 }); 
	
	
	}
} 

function selectStockChasisNo(id)
{
	/*var len = $("#retailStockListMasterTbl").find('tr.selected').length;
	
	if(Number(len) == 0)
	{
		alert('Select any Chasis No');
		return false
	}*/
	var row =  $("#retailStockListMasterTbl").find('tr#row_'+id);
	setChasisNo(row);
	
}
function setChasisNo(row)
{
	
	var id = $(row).attr('id').substr(4);
	var chasis_no = $(row).find('td:eq(3)').text();
	$('#stock_chasis_no').val(chasis_no);
	$('#stock_chasis_id').val(id);
	$('#retailStockListMasterTbl').dataTable().fnClearTable();
	$("#retailStockListMasterTbl").dataTable().fnDestroy();
	$('#viewStockListModal').modal('hide');
}