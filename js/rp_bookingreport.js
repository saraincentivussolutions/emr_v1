function loadRpBookingReport()
{
	var bkrpt_customer_advisor=$('#bkrpt_customer_advisor').val();	
	var bkrpt_finstatus=$('#bkrpt_finstatus').val();	
	var bkrpt_order_status=$('#bkrpt_order_status').val();	
	
	var bkrpt_customer_advisor_2=$('#bkrpt_customer_advisor_2').val();	
	var bkrpt_finstatus_2=$('#bkrpt_finstatus_2').val();	
	var bkrpt_order_status_2=$('#bkrpt_order_status_2').val();	
	
	var titleCont= ' <li> Reports </li>';
        titleCont += '<li class="active">Booking report</li>';
	topHeadTitle(titleCont); 
	 
	var a  = "view";	 
	var pageParams = { action:a, module:'rp_bookingreport', view:'view', bkrpt_customer_advisor:bkrpt_customer_advisor, bkrpt_finstatus:bkrpt_finstatus, bkrpt_order_status:bkrpt_order_status, bkrpt_customer_advisor_2:bkrpt_customer_advisor_2, bkrpt_finstatus_2:bkrpt_finstatus_2, bkrpt_order_status_2:bkrpt_order_status_2};  
	var custVals = {};  
	//custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBookingData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBookingReportData', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 	
	
}
 
 
function loadBookingReportData()
{ 
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	ulTabsClickCommon(); // written in common  
}
 
function exportBookingReportPDF()
{
	var bkrpt_customer_advisor=$('#bkrpt_customer_advisor').val();	
	var bkrpt_finstatus=$('#bkrpt_finstatus').val();	
	var bkrpt_order_status=$('#bkrpt_order_status').val();
	var bkrpt_customer_advisor_2=$('#bkrpt_customer_advisor_2').val();	
	var bkrpt_finstatus_2=$('#bkrpt_finstatus_2').val();	
	var bkrpt_order_status_2=$('#bkrpt_order_status_2').val();	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'rp_bookingreport', view:'report_pdf', bkrpt_customer_advisor:bkrpt_customer_advisor, bkrpt_finstatus:bkrpt_finstatus, bkrpt_order_status:bkrpt_order_status, bkrpt_customer_advisor_2:bkrpt_customer_advisor_2, bkrpt_finstatus_2:bkrpt_finstatus_2, bkrpt_order_status_2:bkrpt_order_status_2};  
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'viewExportFile',sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function loadBookingReportFilter()
{
	$('#viewPageBkRptSrchModal').modal({  show:true, backdrop:false });
}
function loadBookingReportSearchOK()
{
	$('#viewPageBkRptSrchModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	 loadRpBookingReport();
}