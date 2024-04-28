var GV_price_list_date = new Date();
function loadPriceListMaster()
{  
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Price List</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'price_list', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPriceListdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadPriceListdataTableList()
{
	
	
	$('.month-datepicker').datepicker({
			  autoclose: true,
			  format: "mm-yyyy",
				viewMode: "months", 
				minViewMode: "months"
	}).on('changeDate', function (ev) {
		  
		  loadPriceListByMonthYear(ev);
		});
	
	$('.price-datepicker').datepicker({
			  autoclose: true,
			  format: "mm-yyyy",
				viewMode: "months", 
				minViewMode: "months"
	});
	
	var a  = "getList";	
	var dt = GV_price_list_date;
	var price_date =  dt.getFullYear()+ '-' + (dt.getMonth()+1) + '-' + dt.getDate();
	var strDate = GV_monthNames[dt.getMonth()]+ '-' + dt.getFullYear();
	$('#viewShowDate').text(strDate);
	var pageParams = {action:a, module:'price_list',price_date:price_date}; 
	
	$("#priceListMasterTbl").dataTable({
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
					//checkPermssion('price_list');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 
function loadPriceListByMonthYear(obj)
{
	/*alert('test');
	console.log(obj);
	alert((obj).date);*/
	
	GV_price_list_date = (obj).date;
	
	loadPriceListMaster();
	
/* var tbl_offer = $("#offerListMasterTbl").dataTable();
tbl_offer.fnServerData = function ( sSource, aoData ) {
aoData.push({"name": "z_StartDate", "value": 'TEST'});
}*/
}

function DuplicateEntryPriceListMasterList()
{
	$('#viewPageDuplicateModal').modal({  show:true, backdrop:false });
}

function DuplicateEntryPriceListMasterSave()
{
	//alert($('.offer-datepicker').val());
	
	var a  = "save";	 
	var actParams = {name:'action', value:'duplicate_entry'};  
	var modParams = {name:'module', value:'price_list'} 
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'price_date',value:$('.price-datepicker').val()});
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPriceListMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr);
}
function CreateUpdatePriceListMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'price_list', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPriceListMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadPriceListMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'price_list'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataPriceListMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataPriceListMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.price_list_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#price_list_status").val(rsOp.status);
		
		var parent_productlinelist = rsOp.parent_productlinelist;
		var productlinelist = rsOp.productlinelist;
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(parent_productlinelist, function(k,v){
								  
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.parent_productline_id+"' "+selected+">"+v.parent_productline_name+"</option>";				  
		}); 

		
		var option_subcat_def = "<option value='0'>Select</option>"; 
		$.each(productlinelist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_subcat_def += "<option value='"+v.productline_id+"' "+selected+" catid='"+v.parent_productline_id+"' >"+v.productline_name+"</option>";				  
		});
		
		var productcolourlist = rsOp.productcolourlist;
		
		
	
		var option = "";
		var chkArr = new Array();
		if(rsOp.product_colour_ids)
		{
			var chkArr = rsOp.product_colour_ids;
			chkArr = chkArr.split(',');
		} 
		 
		$.each(productcolourlist, function(k,v){
			var selected = '';		
			var tmp = jQuery.inArray(v.productcolour_id,chkArr);  
			if(tmp>-1) selected = 'checked=true';
			var str = '<div prdlines="'+v.parent_productline_ids+'" class="col-md-4 prdcolor-list"><label><input name="chk_product_colour[]" type="checkbox" value="'+v.productcolour_id+'" '+selected+'> '+v.productcolour_name+'</label></div>';
			//
			option += str;				  
		});
		
		$('div#chk_multi_product_colour').html(option)
		
		$('select#parent_productline_id').empty();
		$('select#parent_productline_id').append(option_cat_def);
		
		$('select#productline_id').empty();
		$('select#productline_id').append(option_subcat_def);
		
		viewOffersListOnChangeParentProductLine();
		
		if(Number(rsOp.price_list_id)>0)
		{ 
			$("#parent_productline_id").val(rsOp.parent_productline_id);
			$("#productline_id").val(rsOp.productline_id);
			//$("#vechile_type").val(rsOp.vechile_type);
			$('input[name=vechile_type]').each(function(){
														if($(this).val()==rsOp.vechile_type) this.checked=true;
														});
			$('input[name=registration_type]').each(function(){
														if($(this).val()==rsOp.registration_type) this.checked=true;
														});
			
			$("#price_date").val(rsOp.price_date);
			$("#ex_showroom_amount").val(rsOp.ex_showroom_amount);
			$("#insurance_amount").val(rsOp.insurance_amount);
			$("#taxi_chg_amount").val(rsOp.taxi_chg_amount);
			$("#accessories_amount").val(rsOp.accessories_amount);
			$("#tax_amount").val(rsOp.tax_amount);
			$("#ew_amount").val(rsOp.ew_amount);
			$("#nill_depriciation_amount").val(rsOp.nill_depriciation_amount);
			//$("#cc_amount").val(rsOp.cc_amount);
			$("#onroad_amount").val(rsOp.onroad_amount);
			$("#onroad_nill_amount").val(rsOp.onroad_nill_amount);
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
		
		var catid = $('select#parent_productline_id').val();
		$('div#chk_multi_product_colour').find('.prdcolor-list').each(function(){
				var parent_line_id = $(this).attr('prdlines');
				var chkArr = parent_line_id.split(',');
				var tmp = jQuery.inArray(catid,chkArr);
				if(tmp>-1)
				{
					$(this).find('input[type=checkbox]').each(function(){
						if($(this).attr('checked') == 'checked')
						{
							this.checked = true;
						}
						//alert(this.checked);
						//
					});
					$(this).show();
				}
				else
				{
					$(this).find('input[type=checkbox]').each(function(){
						this.checked = false;
					});
					
					$(this).hide();
				}
			});
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Price List':'New Price List'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	pricelistCalcTotal();
	
}
function pricelistCalcTotal()
{
	var ex_showroom_amount = $("#ex_showroom_amount").val() *1;
	var insurance_amount = $("#insurance_amount").val() *1; 
	var tax_amount = $("#tax_amount").val() *1;
	var accessories_amount = $("#accessories_amount").val() *1;	
	var taxi_chg_amount = $("#taxi_chg_amount").val() *1;
	var ew_amount = $("#ew_amount").val() *1;
	var nill_depriciation_amount = $("#nill_depriciation_amount").val() *1;
	
	var tot = parseFloat(ex_showroom_amount) + parseFloat(insurance_amount) + parseFloat(tax_amount) + parseFloat(accessories_amount) + parseFloat(taxi_chg_amount) + parseFloat(ew_amount);
	
	var nill_tot = parseFloat(tot) + parseFloat(nill_depriciation_amount);
	
	tot=tot.toFixed(2);
	nill_tot=nill_tot.toFixed(2); 
			 
	$("#onroad_amount").val(tot);
	$("#onroad_nill_amount").val(nill_tot);
}
/*function viewOffersListOnChangeParentProductLine()
{
	var catid = $('select#parent_productline_id').val();
	$('select#productline_id').find('option').hide();
	$('select#productline_id').val(0);
	$('select#productline_id').find('option').each(function(){
		if(catid == $(this).attr('catid') || $(this).val()==0 )
		$(this).show();
	});
}*/

function CreateUpdatePriceListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'price_list'} 
	
	var vechile_type=$('input[name=vechile_type]:checked').val();
	var registration_type=$('input[name=registration_type]:checked').val();
	if($('#parent_productline_id').val()==0) { alert('Select parent productline'); $('#parent_productline_id').focus(); return false; }
	if($('#productline_id').val()==0) { alert('Select productline'); $('#productline_id').focus(); return false; }
	if(vechile_type!=1 && vechile_type!=2 && vechile_type!=3) { alert('Select vechile type');   return false; }
	if($('#price_date').val()=='') { alert('Select price date'); $('#price_date').focus(); return false; }
	if(registration_type!=1 && registration_type!=2) { alert('Select Registration type');   return false; }
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPriceListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closePriceListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closePriceListModalDialog(response)
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
	loadPriceListMaster();
}	
	
	

function viewDeletePriceListMaster(id)
{
	$('#frmPriceListDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'price_list'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeletePriceList', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeletePriceList(StrData) 
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

function deletePriceListMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'price_list'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPriceListDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPriceListMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

function viewPriceListOnChangeParentProductLine()
{
	var catid = $('select#parent_productline_id').val();
	$('select#productline_id').find('option').hide();
	$('select#productline_id').val(0);
	$('select#productline_id').find('option').each(function(){
		if(catid == $(this).attr('catid') || $(this).val()==0 )
		$(this).show();
	});
	
	$('div#chk_multi_product_colour').find('.prdcolor-list').each(function(){
		var parent_line_id = $(this).attr('prdlines');
		var chkArr = parent_line_id.split(',');
		var tmp = jQuery.inArray(catid,chkArr);
		if(tmp>-1)
		{
			$(this).show();
		}
		else
		{
			$(this).find('input[type=checkbox]').each(function(){
				this.checked = false;
			});
			
			$(this).hide();
		}
	});
}
 