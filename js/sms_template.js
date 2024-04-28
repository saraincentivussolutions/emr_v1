function loadSMSTemplateMaster()
{
	
	var titleCont= ' <li> SMS Template </li>';
      //  titleCont += '<li class="active">Payslip Report</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'sms_template',  view:'view'}; 
	//var pageParams = {action:a, module:'payslip_report', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'SmsTemplateMasterAfterLoad'};	 
	
	callCommonLoadFunction(passArr); 
	
	
}


function SmsTemplateMasterAfterLoad()
{   
	highlightRightMenu();
	setPageDivContentHeight(); 
	
	var PidVal=0;
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'sms_template'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataSMSTemplateView', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
	
}
function putDataSMSTemplateView(StrData)
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
		
		//$("#hid_id").val(rsOp.product_id);
		$("#sms_templ_content").val(rsOp.sms_text); 
		
	} 
	
}
function SmsTemplateMasterBack()
{
	loadSMSTemplateMaster();
}
function CreateUpdateSmsTemplateMasterSave()
{
	var sms_templ_content=jQuery.trim($('#sms_templ_content').val());
	if(sms_templ_content==""){ alert('Content should not be empty!'); $('#sms_templ_content').focus(); return; }
	
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'sms_template'}
	//var lcdParams = {name:'lcd', value:_expenseLCDDetails}
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmSMSTemplateMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	//pageParams.push(lcdParams);
	
 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeSMSTemplateModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeSMSTemplateModalDialog(response)
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
	 
	loadSMSTemplateMaster();
}
 