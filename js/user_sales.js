var GV_MonYear_UserSalesMonPick=''; 

function loadUserSalesDetails()
{
	var titleCont= ' <li> User Sales </li>'; 
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'user_sales', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserSalesdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
}

function loadUserSalesdataTableList()
{
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	$('#clsMonthYearCustomUserSales').Monthpicker({ 
	  onSelect: function() { funcCallGetMonthYearValUserSales('');  }  
	}); 
	if(GV_MonYear_UserSalesMonPick!="") $('span#clsMonthYearCustomUserSales').closest('div').find(".clsChangeYearVal").val(GV_MonYear_UserSalesMonPick); 
	
	fnChangeUserSalesSearchType(); 
	
	highlightRightMenu();
	 
	searchDateMonthUserSalesGrid();
	
}
function funcCallGetMonthYearValUserSales(loadPage)
{
	var monArr={"Jan":{monId:"1",monName:"January"}, "Feb":{monId:"2",monName:"February"}, "Mar":{monId:"3",monName:"March"}, "Apr":{monId:"4",monName:"April"}, "May":{monId:"5",monName:"May"}, "Jun":{monId:"6",monName:"June"}, "Jui":{monId:"7",monName:"July"}, "Aug":{monId:"8",monName:"August"}, "Sep":{monId:"9",monName:"September"}, "Oct":{monId:"10",monName:"October"}, "Nov":{monId:"11",monName:"November"}, "Dec":{monId:"12",monName:"December"}};
	
	var currObjName='span#clsMonthYearCustomUserSales'; 
	
	var selMonYr=$(currObjName).closest('div').find("div.monthpicker_input").html(); 
	var SelMonArr=selMonYr.split(" ");
	
	var selOMonth=SelMonArr[0];
	var selOYear=SelMonArr[1]; 
	
	var currArrVal=monArr[selOMonth];  
	
	var placeText=currArrVal["monName"]+' '+selOYear;
	
	$('#effective_month').val(currArrVal["monId"]);
	$('#effective_year').val(selOYear); 
	$('.lblCustUserSalesMonPick').text(placeText);
	$('.clsBtnserSalesSearch').focus();
	GV_MonYear_UserSalesMonPick=selOYear;  
	 
}

function divtab_user_total_sales(tab_type)
{
	if(tab_type=="total_sales")
	{
		$("div#div_user_sales").hide();	
		$("div#div_total_sales").show();	
	
	}
	else
	{
		$("div#div_total_sales").hide();	
		$("div#div_user_sales").show();		
	}
	
}
function fnChangeUserSalesSearchType()
{
	var usersales_search_type = $("#usersales_search_type").val();
	 
	if(usersales_search_type=="perday")
	{  
		$("div.clsPerMonth").hide(); 
		$("div.clsFromDate").hide();
		$("div.clsToDate").show();	
	}
	else if(usersales_search_type=="permonth")
	{ 
		$("div.clsPerMonth").show();
		$("div.clsToDate").hide();	
		$("div.clsFromDate").hide();	
	}
	else if(usersales_search_type=="datediff")
	{  
		$("div.clsPerMonth").hide();
		$("div.clsToDate").show();	
		$("div.clsFromDate").show();	
	}
}
function searchDateMonthUserSalesGrid()
{
	divtab_user_total_sales('');
	
	var usersales_search_type = $("#usersales_search_type").val();
	var effective_month = jQuery.trim($('#effective_month').val());
	var effective_year = jQuery.trim($('#effective_year').val()); 
	var search_from_date = jQuery.trim($('#search_from_date').val()); 
	var search_to_date = jQuery.trim($('#search_to_date').val());  
	var usersales_search_billby = $("#usersales_search_billby").val();
	
	if(usersales_search_type=="perday" && search_to_date==""){ alert('Select date!'); $('#search_to_date').focus(); return false; }
	if(usersales_search_type=="permonth" && (effective_month=="" || effective_year=="")){ alert('Select Month!');  return false; }
	if(usersales_search_type=="datediff" && search_from_date==""){ alert('Select from date!'); $('#search_from_date').focus(); return false; }
	if(usersales_search_type=="datediff" && search_to_date==""){ alert('Select to date!'); $('#search_to_date').focus(); return false; }
	 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'user_sales', usersales_search_type:usersales_search_type, search_from_date:search_from_date, search_to_date:search_to_date, effective_month:effective_month, effective_year:effective_year, usersales_search_billby:usersales_search_billby   }; 
	
	$("#user_salesMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				  "bAutoWidth": false,
				  "bPaginate": false,
				  "bLengthChange": false,
				  "bInfo": false, 
				  "searching": false, 
				  "bDestroy": true,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					}
				  
	 });
	
	var a  = "getTotalSalesList";	 
	var pageParams = {action:a, module:'user_sales', usersales_search_type:usersales_search_type, search_from_date:search_from_date, search_to_date:search_to_date, effective_month:effective_month, effective_year:effective_year, usersales_search_billby:usersales_search_billby}; 
	
	$("#total_salesMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				  "bAutoWidth": false,
				   "bPaginate": false,
				  "bLengthChange": false,
				  "bInfo": false, 
				  "searching": false, 
				  "bDestroy": true,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					}
				  
	 });	
}