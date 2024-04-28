function loadSalesReportDetails()
{
	var titleCont= ' <li> Sales Report</li>'; 
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'sales_report', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadSalesReportComboDefaultValues'};	 
	
	callCommonLoadFunction(passArr); 
}
function loadSalesReportComboDefaultValues(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'sales_report'};  
	 
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadSalesReportdataTableList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function loadSalesReportdataTableList(StrData)
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
		
		$("#search_from_date").val(rsOp.from_date);
		$("#search_to_date").val(rsOp.to_date); 
		 
		 
		var categorylist = rsOp.categorylist;
		var serviceslist = rsOp.serviceslist;
		var employeelist = rsOp.employeelist; 
		
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(categorylist, function(k,v){								  
			var selected = ''; 
			option_cat_def += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		});
		
		var option_subcat_def = "<option value='0'>Select</option>"; 
		$.each(serviceslist, function(k,v){
			var selected = ''; 
			option_subcat_def += "<option value='"+v.services_id+"' "+selected+" catid='"+v.category_id+"' >"+v.services_name+"</option>";				  
		});
		
		
		var option_billBy = "<option value='0'>Select</option>";	
		$.each(employeelist, function(k,v){
			var selected = ''; 
			option_billBy += "<option value='"+v.employee_id+"' "+selected+" >"+v.employee_name+"</option>";				  
		});
		
		$('select#cmb_category').empty();
		$('select#cmb_category').append(option_cat_def);
		
		$('select#cmb_services').empty();
		$('select#cmb_services').append(option_subcat_def);
		
		$('select#bill_by_user').empty();
		$('select#bill_by_user').append(option_billBy); 
		
		$('select#refered_employee_id').empty();
		$('select#refered_employee_id').append(option_billBy);  
		 
	}  
	$('.datepicker').datepicker({
			  autoclose: true
	});
	highlightRightMenu();
	 
	searchDetailsSalesReportGrid(); 
} 
function viewSalesRpOnChangeCategory()
{
	var catid = $('select#cmb_category').val(); 
	if(catid==0)
	{
		$('select#cmb_services').find('option').show();
	}
	else
	{ 
		$('select#cmb_services').find('option').hide();
		$('select#cmb_services').find('option').each(function(){
			if(catid == $(this).attr('catid'))
			$(this).show();
		});	
	}
}

function searchDetailsSalesReportGrid()
{   
	var search_from_date = jQuery.trim($('#search_from_date').val()); 
	var search_to_date = jQuery.trim($('#search_to_date').val());  
	
	if(search_from_date==""){ alert('Select from date!'); $('#search_from_date').focus(); return false; }
	if(search_to_date==""){ alert('Select to date!'); $('#search_to_date').focus(); return false; }
	
	var cmb_category = $("#cmb_category").val();
	var cmb_services = $("#cmb_services").val();
	var bill_by_user = $("#bill_by_user").val();	
	var search_customer = jQuery.trim($('#search_customer').val()); 
	var bill_status = $("#bill_status").val();
	var refered_employee_id = $("#refered_employee_id").val();
	 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'sales_report',search_from_date:search_from_date, search_to_date:search_to_date, cmb_category:cmb_category, cmb_services:cmb_services, bill_by_user:bill_by_user, search_customer:search_customer, bill_status:bill_status, refered_employee_id:refered_employee_id  }; 
	
	$("#salesReportMasterTbl").dataTable({
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
function filterSalesReportDetails()
{
	$('#viewPageModal').modal({  show:true, backdrop:false });
}
function filterSalesReportSearch()
{
	$('#viewPageModal').modal('hide');
	searchDetailsSalesReportGrid();
}
function filterSalesReportReset()
{
	$('#viewPageModal').modal('hide');
	
	$("#cmb_category").val(0);
	$("#cmb_services").val(0);
	$("#bill_by_user").val(0);	
	$('#search_customer').val(''); 
	$("#bill_status").val(-1);
	$("#refered_employee_id").val(0);
	
	searchDetailsSalesReportGrid();
}
function exportSalesReportDetails()
{
	alert('Praga will do');	
}