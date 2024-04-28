function loadCustomerFollowupDetails()
{
	var titleCont= ' <li> Customer Followup</li>'; 
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'customer_followup', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerFollowupdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
}
function loadCustomerFollowupdataTableList(StrData)
{
	 
	highlightRightMenu();
	 
	searchCustomerFollowupGrid();  
} 

function searchCustomerFollowupGrid()
{   
	var customer_name_mobile = jQuery.trim($('#customer_name_mobile').val()); 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'customer_followup',customer_name_mobile:customer_name_mobile }; 
	
	$("#customerFollowupTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				  "bAutoWidth": false,
				/*  "bPaginate": false,
				  "bLengthChange": false,
				  "bInfo": false, */
				  "searching": false, 
				  "bDestroy": true,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					} 
	 }); 
}
 
function filterCustomerFollowupSearch()
{ 
	searchCustomerFollowupGrid();
}
function filterCustomerFollowupReset()
{ 
	$("#customer_name_mobile").val('');
	
	searchCustomerFollowupGrid();
}
function customerFollowupSendSMS()
{
	alert('Praga will do');	
}