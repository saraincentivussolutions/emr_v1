var GV_bklist_filt_ordstatus=0;
function loadBookingMaster()
{
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">Booking</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'booking', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBookingdataTableList'}; 
	
	callCommonLoadFunction(passArr); 
	
	
}
function bklistordstatuschange()
{
	GV_bklist_filt_ordstatus=$('#bklist_filt_ordstatus').val();	
	loadBookingMaster();
}

function loadBookingdataTableList()
{
	$('#bklist_filt_ordstatus').val(GV_bklist_filt_ordstatus);
	
	var a  = "getList";	 
	var pageParams = {action:a, ordstatus_filt:GV_bklist_filt_ordstatus, module:'booking'}; 
	
	$("#bookingMasterTbl").dataTable({
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
					checkPermssion('Booking');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateBookingMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'booking', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBookingData', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadBookingData', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadBookingData()
{
	//var modal_title = Number($("#hid_id").val())>0?'Edit Booking':'New Booking'; 
	//$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	ulTabsClickCommon(); // written in common
	
	$("input[name=ex_vechicle]").click(function(){ 
		bookingValidations();
		bookingExchangeOnchangeValidations();
	});
	$("input[name=vehicle_type]").click(function(){ 
		bkGetQuotPrice(); 
	}); 
	$("input[name=registration_type]").click(function(){ 
		bkGetQuotPrice(); 
	}); 
	$("input[name=insurance_detail]").click(function(){ 
		bkGetQuotPrice(); 
	});
	$("input[name=corporate_type]").click(function(){ 
		bookingValidations(); 
		bookingExchangeOnchangeValidations();
	});
	
	$("input[name=insurance_type]").click(function(){ 
		 
		var insurance_type = $("input[name=insurance_type]:checked").val();	
		if(insurance_type!=1)
		{ 
			$('.clsBkInsShw').hide(); 
			
			$('input[name=insurance_detail]').each(function(){
				this.checked = false;
			});
		}
		else{$('.clsBkInsShw').show();} 
		
		bkGetQuotPrice(); 
	});
	
	
	bookingValidations();
}
function CreateUpdateBookingMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'booking'}
	
	
	
	var order_no = jQuery.trim($('#order_no').val());
	var order_date = jQuery.trim($('#order_date').val());
	var order_status = $('#order_status').val();
	var sales_team = $('#sales_team').val();
	var customer_advisor = $('#customer_advisor').val();
	var source_contact = $('#source_contact').val();
	var customer_name = jQuery.trim($('#customer_name').val());
	var customer_mobile = jQuery.trim($('#customer_mobile').val());
	var customer_alternate_no = jQuery.trim($('#customer_alternate_no').val());
	var parent_product_line = $('#parent_product_line').val();
	var product_line = $('#product_line').val();
	var product_color_primary = $('#product_color_primary').val();
	var edd = jQuery.trim($('#edd').val());
	
	if(order_no==''){alert('Order no. should not be empty!'); goToUlTabsClickCommon(1);  $('#order_no').focus(); return false;	}
	if(order_date==''){alert('Order date should not be empty!'); goToUlTabsClickCommon(1); $('#order_date').focus(); return false;	}
	if(order_status==0){alert('Order status should not be empty!'); goToUlTabsClickCommon(1); $('#order_status').focus(); return false;	}
	if(sales_team==0){alert('Slaes team should not be empty!'); goToUlTabsClickCommon(1); $('#sales_team').focus(); return false;	}
	if(customer_advisor==0){alert('Customer advisor should not be empty!'); goToUlTabsClickCommon(1); $('#customer_advisor').focus(); return false;	}
	if(source_contact==0){alert('Source of contact should not be empty!'); goToUlTabsClickCommon(1); $('#source_contact').focus(); return false;	}
	if(customer_name==''){alert('Customer name should not be empty!'); goToUlTabsClickCommon(1); $('#customer_name').focus(); return false;	}
	if(customer_mobile==''){alert('Customer mobile should not be empty!'); goToUlTabsClickCommon(1); $('#customer_mobile').focus(); return false;	}
	if(customer_mobile.length!=10){alert('Customer mobile is invalid!'); goToUlTabsClickCommon(1); $('#customer_mobile').focus(); return false;	}
	if(customer_alternate_no!=''){ if(customer_alternate_no.length!=10){alert('Alternate mobile is invalid!'); goToUlTabsClickCommon(1); $('#customer_alternate_no').focus(); return false;	}	}
	
	if(parent_product_line==0){alert('Parent Product Line should not be empty!'); goToUlTabsClickCommon(2); $('#parent_product_line').focus(); return false;	}
	if(product_line==0){alert('Product Line should not be empty!'); goToUlTabsClickCommon(2); $('#product_line').focus(); return false;	}
	if(product_color_primary==0){alert('Product colour should not be empty!'); goToUlTabsClickCommon(2); $('#product_color_primary').focus(); return false;	}
	if(edd==''){alert('EDD should not be empty!');  goToUlTabsClickCommon(2); $('#edd').focus(); return false;	} 
	 
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmBookingMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeBookingModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeBookingModalDialog(response)
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
	loadBookingMaster();
}	
	
	

function viewDeleteBookingMaster(id)
{
	$('#frmBookingDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'booking'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteBooking', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteBooking(StrData) 
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

function deleteBookingMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'booking'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmBookingDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadBookingMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 
function BookEntryBack()
{ 
	loadBookingMaster();	
}

function viewBookingEntryOnChangeSalesTeam()
{
	var salesid = $('select#sales_team').val();
	$('select#customer_advisor').val(0); 
	
	$('select#customer_advisor').find('option').hide(); 
	$('select#customer_advisor').find('option').each(function(){
		if(salesid == $(this).attr('atr_salesteam') || $(this).val()==0)
		$(this).show();
	}); 
	 
} 

function viewBookingEntryOnChangeParentProd()
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
	
	bkGetQuotPrice();
} 
function bookingValidations()
{
	// ============ Dont written any new function
	
	
	var ex_vechicle = $("input[name=ex_vechicle]:checked").val();	
	if(ex_vechicle!=1){ $('.clsBkExchange').hide(); $('#ex_price').val(''); $('#exchange_offer').val(''); $('#exchange_offer_srt').val('');  $('#exchange_offer_srt_addition').val('');  }
	else{$('.clsBkExchange').show();}
	
	var corporate_type = $("input[name=corporate_type]:checked").val();	
	if(corporate_type!=1){ $('#corporate_name').attr('readonly','readonly'); $('#corporate_name').val(''); }
	else{$('#corporate_name').removeAttr('readonly');}
	
	
	var insurance_type = $("input[name=insurance_type]:checked").val();	
		if(insurance_type!=1)
		{ 
			$('.clsBkInsShw').hide(); 
			
			$('input[name=insurance_detail]').each(function(){
				this.checked = false;
			});
		}
		else{$('.clsBkInsShw').show();} 
	
	
	
}
function bookingExchangeOnchangeValidations()
{  
	bkGetQuotPrice();
	
	/*var id = $("#hid_id").val(); 
	var ex_vechicle = $("input[name=ex_vechicle]:checked").val();	
	if(id>0 && ex_vechicle==1)
	{   
		var a  = "getOnchangeView";	 
		var modtype='vehicle_exchange';
		var pageParams = {id:id, action:a, modtype:modtype,  module:'booking'};  
					
		var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'bookingOnchangePutVals', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
		
		callCommonLoadFunction(passArr); 
	}
	else
	{
		bkGetQuotPrice();	
	}*/
}
function bookingOnchangePutVals(StrData)
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
		var modtype=opData.modtype;
		
		if(modtype=='vehicle_exchange')
		{
			$("#ex_price").val(rsOp.exchange_price);	
			
			bkGetQuotPrice();
		} 
		if(modtype=='quoatation_offer')
		{ 
			if(rsOp.price_list!=undefined)
			{  
				var price_list=rsOp.price_list;
				
				$("#ex_showroom_price").val(price_list.ex_showroom_amount);	
				$("#insurance_method").val(price_list.insurance_amount);	
				$("#rto_fee").val(price_list.tax_amount);	
				$("#taxi_charges").val(price_list.taxi_chg_amount);	
				$("#accessories").val(price_list.accessories_amount);	
				$("#amc").val(price_list.ew_amount);
				
				$("#ex_price").val(price_list.exchange_price);
				
				bkcalPriceQuoteTotal();
			}
			
			if(rsOp.offer_list!=undefined)
			{  
				var offer_list=rsOp.offer_list;
				
				$("#cosumer_offer").val(offer_list.cash_offer_tata);	
				$("#corporate_offer").val(offer_list.corporate_offer_tata);	
				$("#exchange_offer").val(offer_list.exchange_offer_tata); 
				$("#edr").val(offer_list.edr_offer_tata);
				
				$("#cosumer_offer_srt").val(offer_list.cash_offer_srt);	
				$("#corporate_offer_srt").val(offer_list.corporate_offer_srt);	
				$("#exchange_offer_srt").val(offer_list.exchange_offer_srt); 
				$("#edr_srt").val(offer_list.edr_offer_srt);	 
				
				bkcalOfferPriceTotal();
			}
		} 
	}  
}
function bkGetQuotPrice()
{
	$("#ex_showroom_price").val('');	
	$("#insurance_method").val('');	
	$("#rto_fee").val('');	
	$("#taxi_charges").val('');	
	$("#accessories").val('');	
	$("#amc").val('');
	$("#ex_price").val('');
	bkcalPriceQuoteTotal();
	
	$("#cosumer_offer").val('');	
	$("#corporate_offer").val('');	
	$("#exchange_offer").val(''); 
	$("#edr").val('');
	
	$("#cosumer_offer_srt").val('');	
	$("#corporate_offer_srt").val('');	
	$("#exchange_offer_srt").val(''); 
	$("#edr_srt").val('');	
	
	bkcalOfferPriceTotal();
				
	bookingLoadQuotationPriceOnchangeValidations();	
}
function bookingLoadQuotationPriceOnchangeValidations()
{  
	var id = $("#hid_id").val(); 
	var vehicle_type = $("input[name=vehicle_type]:checked").val();	
	var insurance_detail = $("input[name=insurance_detail]:checked").val();	
	var parent_product_line = $("#parent_product_line").val();	
	var product_line = $("#product_line").val(); 
	var ex_vechicle = $("input[name=ex_vechicle]:checked").val();
	var corporate_type = $("input[name=corporate_type]:checked").val();	
	var insurance_type = $("input[name=insurance_type]:checked").val();	
	var product_color_primary = $("#product_color_primary").val();	
	var registration_type = $("input[name=registration_type]:checked").val();	
	
	if(parent_product_line>0 && product_line>0)
	{  
		var a  = "getOnchangeView";	 
		var modtype='quoatation_offer';
		var pageParams = {id:id, action:a, modtype:modtype, vehicle_type:vehicle_type, insurance_detail:insurance_detail, parent_product_line:parent_product_line, product_line:product_line, ex_vechicle:ex_vechicle, corporate_type:corporate_type, insurance_type:insurance_type,  module:'booking', product_color_primary:product_color_primary, registration_type:registration_type };   
					
		var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'bookingOnchangePutVals', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
		
		callCommonLoadFunction(passArr); 
	} 
}
function bkcalPriceQuoteTotal()
{
	var ex_showroom_price = $("#ex_showroom_price").val() *1;
	var insurance_method = $("#insurance_method").val() *1; 
	var rto_fee = $("#rto_fee").val() *1;
	var taxi_charges = $("#taxi_charges").val() *1;	
	var accessories = $("#accessories").val() *1;
	var amc = $("#amc").val() *1;
	var ex_price = $("#ex_price").val() *1;
	
	var tot = parseFloat(ex_showroom_price) + parseFloat(insurance_method) + parseFloat(rto_fee) + parseFloat(taxi_charges) + parseFloat(accessories) + parseFloat(amc) - parseFloat(ex_price); 
	if(tot<0) tot=0;
	tot=tot.toFixed(2); 
			 
	$("#onroad_price").val(tot); 
}
function bkcalOfferPriceTotal()
{
	var cosumer_offer = $("#cosumer_offer").val() *1;
	var corporate_offer = $("#corporate_offer").val() *1; 
	var exchange_offer = $("#exchange_offer").val() *1;
	var access_offer = $("#access_offer").val() *1;	
	var insurance_offer = $("#insurance_offer").val() *1;
	var add_discount = $("#add_discount").val() *1;
	var edr = $("#edr").val() *1;
	var other_contribution = $("#other_contribution").val() *1;
	
	var tot = parseFloat(cosumer_offer) + parseFloat(corporate_offer) + parseFloat(exchange_offer) + parseFloat(access_offer) + parseFloat(insurance_offer) + parseFloat(add_discount) + parseFloat(edr) + parseFloat(other_contribution); 
	if(tot<0) tot=0;
	tot=tot.toFixed(2); 
			 
	$("#total_tata").val(tot); 
	
	
	//SRT
	
	var cosumer_offer_srt = $("#cosumer_offer_srt").val() *1;
	var corporate_offer_srt = $("#corporate_offer_srt").val() *1; 
	var exchange_offer_srt = $("#exchange_offer_srt").val() *1;
	var access_offer_srt = $("#access_offer_srt").val() *1;	
	var insurance_offer_srt = $("#insurance_offer_srt").val() *1;
	var add_discount_srt = $("#add_discount_srt").val() *1;
	var edr_srt = $("#edr_srt").val() *1;
	var other_contribution_srt = $("#other_contribution_srt").val() *1;
	
	var totsrt = parseFloat(cosumer_offer_srt) + parseFloat(corporate_offer_srt) + parseFloat(exchange_offer_srt) + parseFloat(access_offer_srt) + parseFloat(insurance_offer_srt) + parseFloat(add_discount_srt) + parseFloat(edr_srt) + parseFloat(other_contribution_srt); 
	if(totsrt<0) totsrt=0;
	totsrt=totsrt.toFixed(2); 
			 
	$("#total_srt").val(totsrt); 
	
	
	//SRT Additional
	
	var cosumer_offer_srt_addition = $("#cosumer_offer_srt_addition").val() *1;
	var corporate_offer_srt_addition = $("#corporate_offer_srt_addition").val() *1; 
	var exchange_offer_srt_addition = $("#exchange_offer_srt_addition").val() *1;
	var access_offer_srt_addition = $("#access_offer_srt_addition").val() *1;	
	var insurance_offer_srt_addition = $("#insurance_offer_srt_addition").val() *1;
	var add_discount_srt_addition = $("#add_discount_srt_addition").val() *1;
	var edr_srt_addition = $("#edr_srt_addition").val() *1;
	var other_contribution_srt_addition = $("#other_contribution_srt_addition").val() *1;
	
	//alert(parseFloat(cosumer_offer_srt_addition) +'==='+ parseFloat(corporate_offer_srt_addition) +'==='+ parseFloat(exchange_offer_srt_addition) +'==='+ parseFloat(access_offer_srt_addition) +'==='+ parseFloat(insurance_offer_srt_addition) +'==='+ parseFloat(add_discount_srt_addition) +'==='+ parseFloat(edr_srt_addition) +'==='+ parseFloat(other_contribution_srt_addition) );
	
	var totsrtadd = parseFloat(cosumer_offer_srt_addition) + parseFloat(corporate_offer_srt_addition) + parseFloat(exchange_offer_srt_addition) + parseFloat(access_offer_srt_addition) + parseFloat(insurance_offer_srt_addition) + parseFloat(add_discount_srt_addition) + parseFloat(edr_srt_addition) + parseFloat(other_contribution_srt_addition); 
	if(totsrtadd<0) totsrtadd=0;
	totsrtadd=totsrtadd.toFixed(2); 
			 
	$("#total_srt_addition").val(totsrtadd); 
}
 