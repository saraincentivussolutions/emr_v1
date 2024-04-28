var jsModDir = 'public/js/';
var TemplateModDir = 'app/modules/';
var GV_show_list_page_succmsg="";
var GV_menu_permission = {};

function callCommonLoadFunction(passArr)
{  
 
	var pageURL=''; 
	var pageParams={};
	var pageDataType='text'; 
	var pageLoadContent='#rightMenuDiv'; 
	var pagePlsWaitMsg='Loading...Please wait...';  
	var onSuccLoadFunc='';
	var onFailLoadFunc='';
	var displayDataContent='display';
	var sendDataOnSuccess='';  
	var sendCustPassVal='';  
	var onSuccAlert='';
	var pageModule='';
	
	if(passArr.pURL!=undefined) pageURL=passArr.pURL;
	if(passArr.pageParams!=undefined) pageParams=passArr.pageParams;
	if(passArr.pageDataType!=undefined) pageDataType=passArr.pageDataType;
	if(passArr.pageLoadContent!=undefined) pageLoadContent=passArr.pageLoadContent;
	if(passArr.pagePlsWaitMsg!=undefined) pagePlsWaitMsg=passArr.pagePlsWaitMsg; 
	if(passArr.onSuccLoadFunc!=undefined) onSuccLoadFunc=passArr.onSuccLoadFunc;
	if(passArr.onFailLoadFunc!=undefined) onFailLoadFunc=passArr.onFailLoadFunc;
	if(passArr.displayDataContent!=undefined) displayDataContent=passArr.displayDataContent;
	if(passArr.sendDataOnSuccess!=undefined) sendDataOnSuccess=passArr.sendDataOnSuccess;
	if(passArr.sendCustPassVal!=undefined) sendCustPassVal=passArr.sendCustPassVal; 
	if(passArr.onSuccAlert!=undefined) onSuccAlert=passArr.onSuccAlert;
	if(passArr.pageModule!=undefined) pageModule=passArr.pageModule;

	if(pageURL=='' || pageURL==undefined) { customAlert('Page is empty.'); return; }
	
	if(pageURL=='process.php') pageURL = TemplateModDir+pageParams.module+'/process.php'; 
	
	
	$('#loading_div').html(pagePlsWaitMsg);
	showOverlayDiv();
	
	
	$.ajax({ 
		type : 'POST', 
		url : pageURL, 
		dataType	: pageDataType,
		data		:  pageParams,
		success : function(data)
		{ 
			HideOverlayDiv();
			
			if(displayDataContent=='display')
			{
				$(pageLoadContent).show(); 
				$(pageLoadContent).html(data); 
			}
			if(onSuccAlert=="show" || onSuccAlert=="showInPage")
			{
				var opStatus="";
				if(data.status!=undefined) opStatus=data.status; 
				 
				if(opStatus=='success') 
				{  
					if(onSuccAlert=="showInPage") GV_show_list_page_succmsg=data.message; 
					else customAlert(data.message);
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
			}
			
			if(onSuccLoadFunc!='') 
			{   
				var passDataArr={};
				passDataArr["customData"]=sendCustPassVal;
				
				var passData=sendCustPassVal;
				
				
				var SendData=')';
				if(sendDataOnSuccess=='send') 
				{
					passDataArr["formOpData"]=data; 
				}
				var appJ=JSON.stringify(passDataArr);
				 
				var sendFunc=onSuccLoadFunc+'('+appJ+')'; 
				  
				eval(sendFunc);
				
			}
			
			
		}, 
		error : function() 
		{  
			customAlert("Error while loading pages..."); 
			HideOverlayDiv();
		} 
	});
}
function loadLayout()
{ 
	var a  = "view";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'left_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'loadDashboardPage'};
	
	callCommonLoadFunction(passArr);
		
}
function loadDashboardPage()
{  
	alert('emergeny');
	$('.sidebar-menu li').click(function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		})
	var a  = "view";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'dashboard.php',pageParams:pageParams};
	
	callCommonLoadFunction(passArr); 
	
	 
}



function loadModuleJS(module, callBack) //
{
	var exec_function = callBack+'()';
	
	try{	
		eval(exec_function);
	}
	catch(e)
	{ 
		//$.ajaxSetup({ cache: true });
		
		$.getScript(jsModDir+''+module+'.js').done(function(script, textStatus){
			//$.ajaxSetup({ cache: false });
			eval(exec_function);
		});	
	}
	
}

function getModuleTemplateFile(module, view, qrystr)
{
	var loadTemplateFile = TemplateModDir+module+'/'+view+'.php';
	
	if(qrystr)
	{
		loadTemplateFile+='?'+qrystr;
	}
	
	return loadTemplateFile;
}
function getModuleFile(module, view, qrystr)
{
	var loadTemplateFile = TemplateModDir+module+'/'+view+'.php';
	
	if(qrystr)
	{
		loadTemplateFile+='?'+qrystr;
	}
	
	return loadTemplateFile;
}

function createDialog(diag_params)
{
	var width = (diag_params.width)?(diag_params.width):'300';
	var height = (diag_params.height)?(diag_params.height):'150';
	var title = (diag_params.title)?(diag_params.title):'Dialog';
	var param = (diag_params.param)?(diag_params.param):'';
	var onsucessFunc = (diag_params.onsucessFunc)?(diag_params.onsucessFunc):'';
	var btn = [{
							id:"btn-ok",
							text: "OK",
							click: function() {
									closeDialogForm();
							}
					},{
							id:"btn-cancel",
							text: "Cancel",
							click: function() {
									closeDialogForm();
							}
					}]; 
	var buttons = (diag_params.buttons)?(diag_params.buttons):btn;
	$('#dialogViewer').dialog(
	{
		
		autoOpen: true,
		width: width,  
		height: height,
		modal: true,
		title: title,
		resizable: false,
		draggable: true, 
		close: function(event, ui) {    $('#dialogViewer').empty();  }, 
		buttons		: buttons
		 
	});
	var pageParams = param;  
	var onSuccLoadFunc = "loadModuleJS('"+pageParams.module+"', '"+onsucessFunc+"')";	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, pageLoadContent:'#dialogViewer', onSuccLoadFunc:onSuccLoadFunc};
	
	callCommonLoadFunction(passArr);
	
}



function closeDialogForm(){
		$('#dialogViewer').empty();
		$('#dialogViewer').dialog("close"); 
} 
function customAlert(aTxt,aType,aFocus)
{   
	alert(aTxt);	 
	
	if(aFocus){ $(aFocus).focus(); }
}
function shwAlertInPage(aTxt)
{ 
	$('#succDivMsg').html(aTxt);	
	$('.alertSuccDivMsg').show();	
	//$('.modal-backdrop').hide();
}

function loadCategoryLayout()
{
	var a  = "view";	 
	var pageParams = {action:a};  
	var passArr={pURL:'left_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'loadCategoryPage'};
	
	callCommonLoadFunction(passArr);
}

function loadCategoryPage()
{
	var a  = "list";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'category_list.php',pageParams:pageParams};
	
	callCommonLoadFunction(passArr); 
	
	 
}

function topHeadTitle(titleCont)
{
	$('.breadcrumb').html(titleCont);
}

function CheckEmailId(email) {  
	email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;           
	if(!email_regex.test(email)){ return false;}
	return true;
}

function funcCallDate(thisObj)
{

	$(thisObj).find('.datepicker').focus(); 
}

function toSqlDate(date)
{
	var dt = new Date(date);
	var dt = dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
	
	return dt;
}


function ValidateNumberKeyPress(field, evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	var keychar = String.fromCharCode(charCode);

   // if (charCode > 31   && (charCode < 48 || charCode > 57) && keychar != "."  && keychar != "-" )
	 if (charCode > 31  && (charCode < 48 || charCode > 57) && keychar != "."   )
	{

		return false;

	}



	if (keychar == "." && field.value.indexOf(".") != -1) 

	{

		return false;

	}

		

	if(keychar == "-")

	{

		if (field.value.indexOf("-") != -1 /* || field.value[0] == "-" */) 

		{

			return false;

		}

		else

		{

			//save caret position

			var caretPos = getCaretPosition(field);

			if(caretPos != 0)

			{

				return false;

			}

		}

	}



	return true;

}

 function getCaretPosition(objTextBox){



            var objTextBox = window.event.srcElement;



            var i = objTextBox.value.length;



            if (objTextBox.createTextRange){

                objCaret = document.selection.createRange().duplicate();

                while (objCaret.parentElement()==objTextBox &&

                  objCaret.move("character",1)==1) --i;

            }

            return i;

        }


var GV_rightSub_cont_obj={};
function loadHeaderMenus(thisObj,menuName)
{
	switch(menuName) 
	{ 
		case 'hdr_transactions' : 			loadTransactionLayout(); 			break;
		case 'hdr_settings' : 				loadSettingsLayout(); 			break;
		case 'hdr_dashboard' : 				loadDashboardPage(); 			break;
		case 'hdr_reports' : 				loadReportsLayout(); 			break;
		
		default: alert('Menu not found'); break;
	}
}
function loadTransactionLayout()
{
	var a  = "view";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'transaction_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'successTransactionLayout'};
	GV_rightSub_cont_obj={};
	callCommonLoadFunction(passArr);	
}
function successTransactionLayout()
{
	$('.sidebar-menu li').click(function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		});
	loadBookingMaster();	
}
function loadSettingsLayout()
{
	var a  = "view";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'settings_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'sucessSettingsLayout'};
	GV_rightSub_cont_obj={};
	callCommonLoadFunction(passArr);	
}
function sucessSettingsLayout()
{
	$('.sidebar-menu li').click(function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		});
	loadOrderStatusMaster();	
} 
function loadReportsLayout()
{
	var a  = "view";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'reports_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'sucessReportsLayout'};
	GV_rightSub_cont_obj={};
	callCommonLoadFunction(passArr);	
}
function sucessReportsLayout()
{
	$('.sidebar-menu li').click(function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		});
	loadRpBookingReport();	
} 

function callLeftMenuPages(thisObj,menuName)
{
	GV_rightSub_cont_obj=thisObj;	
	switch(menuName) 
	{ 
		case 'opn_orderstatus' : 			loadOrderStatusMaster(); 			break;
		case 'opn_salesteam' : 				loadSalesTeamMaster(); 				break;
		case 'opn_employee_master' : 		loadUserMaster(); 					break; 
		case 'opn_financier_master' : 		loadFinancierMaster(); 				break;  
		case 'opn_parent_productline' : 	loadParentProductLineMaster(); 		break; 
		case 'opn_productline' : 			loadProductLineMaster(); 			break; 
		case 'opn_productcolour' : 			loadProductColourMaster(); 			break;
		case 'opn_message' : 				loadMessagesMaster(); 				break;
		case 'opn_source_contact' : 		loadSourceOfContactMaster(); 		break;
		case 'opn_offer_list' : 			loadOfferListMaster(); 				break;
		case 'opn_price_list' : 			loadPriceListMaster(); 				break;
		case 'opn_stock_list' : 			loadStockEntryDetails(); 			break;
		case 'opn_user_role_master' : 		loadUserRoleMaster(); 				break;
		case 'opn_login_master' : 			loadLoginMaster(); 					break;
		
		case 'trans_booking' : 				GV_bklist_filt_ordstatus=0; loadBookingMaster(); 				break;
		case 'trans_finance' : 				GV_finlist_filt_finstatus=-1;  GV_finlist_filt_bytype=''; loadFinanceListMaster(); 			break;
		case 'trans_vehicle_exchange' : 	loadVehicleExchangeListMaster(); 	break;
		case 'trans_approval' : 			GV_apprlist_filt_paidstatus=1; loadApprovalMaster(); 				break;
		case 'trans_retail' : 				loadRetailListMaster(); 			break;
		case 'trans_receipts' : 			GV_rcptlist_filt_ordstatus=0; loadReceiptsMaster(); 				break;
		case 'trans_offerapproval' : 		GV_offapplist_filttypeval=1; loadOfferapprovalMaster(); 			break;
		case 'trans_veh_not_avail' : 		loadVehNoAvailMaster(); 			break;
		
		
		case 'rp_bookingreport' : 			loadRpBookingReport(); 				break; 
		case 'home_dashboard_page' : 		loadDashboardPage(); 				break; 
	}
}
function highlightRightMenu()
{  
	var thisObj=GV_rightSub_cont_obj;	 
	$('ul.treeview-menu li ').removeClass('active'); 
	$(thisObj).closest('li').addClass('active');
	
	if(GV_show_list_page_succmsg!="")
	{
		shwAlertInPage(GV_show_list_page_succmsg);
		GV_show_list_page_succmsg="";	
	}
	
}
function showOverlayDiv() 
{
	$('#overlay_div').show();
	var w = $('#loading_div').width();
	var h = $('#loading_div').height();
	$('#loading_div').css ({
		left:($(document).width() - w)/2,
		top:(($(document).height() - h)/2)-25
	}); 
	
	$("#overlay_div").fadeIn();
}

function HideOverlayDiv()
{
	$('#overlay_div').hide();
}
function setLftMenuHt()
{
	/* subhu will do*/	
}
function headerlogout()
{
	location.href='logout.php';	
}
function ulTabsClickCommon()
{
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})	
}
function goToUlTabsClickCommon(tabno)
{
	var togotab='tab-'+tabno;
	$('ul.tabs li').each(function(){
		var tab_id = $(this).attr('data-tab');
		
		if(togotab==tab_id) $(this).trigger('click');

		/*$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');*/
	})	
}
function numberOnlyValidate(e) 
{
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57)); 
	return ret;
}

function checkPermssion(type)
{
	/*var str = (GV_menu_permission[type]);
	
	var chk_add = str.indexOf('2');
	
	if(chk_add==-1)
	{
		$('#rightMenuDiv').find('.act-add').remove();
	}
	
	var chk_edit = str.indexOf('3');
	
	if(chk_edit==-1)
	{
		$('#rightMenuDiv').find('.act-edit').remove();
	}
	
	var chk_delete = str.indexOf('4');
	
	if(chk_delete==-1)
	{
		$('#rightMenuDiv').find('.act-delete').remove();
	}*/
	
 
	
}

