var GV_offer_list_date = new Date();
var GV_monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
function loadOfferListMaster()
{  
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Offer List</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'offer_list', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOfferListdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadOfferListdataTableList()
{
	$('.month-datepicker').datepicker({
			  autoclose: false,
			  format: "mm-yyyy",
				viewMode: "months", 
				minViewMode: "months"
	}).on('changeDate', function (ev) {
		  
		  loadOfferListByMonthYear(ev);
		});
	
	$('.offer-datepicker').datepicker({
			  autoclose: true,
			  format: "mm-yyyy",
				viewMode: "months", 
				minViewMode: "months"
	});
	
	var a  = "getList";	
	var dt = GV_offer_list_date;
	var offer_date =  dt.getFullYear()+ '-' + (dt.getMonth()+1) + '-' + dt.getDate();
	var strDate = GV_monthNames[dt.getMonth()]+ '-' + dt.getFullYear();
	$('#viewShowDate').text(strDate);
	var pageParams = {action:a, module:'offer_list', offer_date:offer_date}; 
	
	$("#offerListMasterTbl").dataTable({
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
					//checkPermssion('offer_list');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 

function loadOfferListByMonthYear(obj)
{
	/*alert('test');
	console.log(obj);
	alert((obj).date);*/
	
	GV_offer_list_date = (obj).date;
	
	loadOfferListMaster();
	
/* var tbl_offer = $("#offerListMasterTbl").dataTable();
tbl_offer.fnServerData = function ( sSource, aoData ) {
aoData.push({"name": "z_StartDate", "value": 'TEST'});
}*/
}

function DuplicateEntryOfferListMasterList()
{
	$('#viewPageDuplicateModal').modal({  show:true, backdrop:false });
}

function DuplicateEntryOfferListMasterSave()
{
	//alert($('.offer-datepicker').val());
	
	var a  = "save";	 
	var actParams = {name:'action', value:'duplicate_entry'};  
	var modParams = {name:'module', value:'offer_list'} 
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'offer_date',value:$('.offer-datepicker').val()});
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadOfferListMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr);
}
function CreateUpdateOfferListMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'offer_list', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadOfferListMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadOfferListMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'offer_list'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataOfferListMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataOfferListMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.offer_list_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#offer_list_status").val(rsOp.status);
		
		var parent_productlinelist = rsOp.parent_productlinelist;
		var productlinelist = rsOp.productlinelist;
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(parent_productlinelist, function(k,v){
								  
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.parent_productline_id+"' "+selected+">"+v.parent_productline_name+"</option>";				  
		}); 

		
		/*var option_subcat_def = "<option value='0'>Select</option>"; 
		$.each(productlinelist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_subcat_def += "<option value='"+v.productline_id+"' "+selected+" catid='"+v.parent_productline_id+"' >"+v.productline_name+"</option>";				  
		});*/
		
		var productcolourlist = rsOp.productcolourlist;
		
		
		var option = "";
		var chkArr = new Array();
		if(rsOp.productline_id)
		{
			var chkArr = rsOp.productline_id;
			chkArr = chkArr.split(',');
		}
		$.each(productlinelist, function(k,v){
			var selected = '';		
			var tmp = jQuery.inArray(v.productline_id,chkArr);
			if(tmp>-1) selected = 'checked=true';
			var str = '<div prdlines="'+v.parent_productline_id+'" class="col-md-4 prdcolor-list"><label><input name="chk_product_line[]" type="checkbox" value="'+v.productline_id+'" '+selected+'> '+v.productline_name+'</label></div>';
			//
			option += str;				  
		});
		
		$('div#chk_multi_product_line').html(option)

		
	
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
		
		
		$('div#chk_multi_product_colour').html(option);
		
		
		
		$('select#parent_productline_id').empty();
		$('select#parent_productline_id').append(option_cat_def);
		
		//$('select#productline_id').empty();
		//$('select#productline_id').append(option_subcat_def);
		
		viewOffersListOnChangeParentProductLine();
		
		if(Number(rsOp.offer_list_id)>0)
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
			$("#offer_date").val(rsOp.offer_date);
			$("#cash_offer_tata").val(rsOp.cash_offer_tata);
			$("#cash_offer_srt").val(rsOp.cash_offer_srt);
			$("#exchange_offer_tata").val(rsOp.exchange_offer_tata);
			$("#exchange_offer_srt").val(rsOp.exchange_offer_srt);
			$("#corporate_offer_tata").val(rsOp.corporate_offer_tata);
			$("#corporate_offer_srt").val(rsOp.corporate_offer_srt);
			$("#edr_offer_tata").val(rsOp.edr_offer_tata);
			$("#edr_offer_srt").val(rsOp.edr_offer_srt);
			
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
		
		$('div#chk_multi_product_line').find('.prdcolor-list').each(function(){
				var parent_line_id = $(this).attr('prdlines');
				var chkArr = parent_line_id.split(',');
				var tmp = jQuery.inArray(catid,chkArr);
				//alert(catid+'--'+parent_line_id);
				if(tmp>-1)
				{
					//alert($(this).html());
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
					//alert('ss');
					$(this).find('input[type=checkbox]').each(function(){
						this.checked = false;
					});
					
					$(this).hide();
				}
			});
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Offer List':'New Offer List'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
}
function viewOffersListOnChangeParentProductLine()
{
	var catid = $('select#parent_productline_id').val();
		
	$('div#chk_multi_product_line').find('.prdcolor-list').each(function(){
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

function CreateUpdateOfferListMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'offer_list'} 
	
	var vechile_type=$('input[name=vechile_type]:checked').val();
	var registration_type=$('input[name=registration_type]:checked').val();
	if($('#parent_productline_id').val()==0) { alert('Select parent productline'); $('#parent_productline_id').focus(); return false; }
	if($('#productline_id').val()==0) { alert('Select productline'); $('#productline_id').focus(); return false; }
	if(vechile_type!=1 && vechile_type!=2 && vechile_type!=3) { alert('Select vechile type');   return false; }
	if($('#offer_date').val()=='') { alert('Select offer date'); $('#offer_date').focus(); return false; }
	if(registration_type!=1 && registration_type!=2) { alert('Select Registration type');   return false; }
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmOfferListMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeOfferListModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeOfferListModalDialog(response)
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
	loadOfferListMaster();
}	
	
	

function viewDeleteOfferListMaster(id)
{
	$('#frmOfferListDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'offer_list'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteOfferList', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteOfferList(StrData) 
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

function deleteOfferListMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'offer_list'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmOfferListDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadOfferListMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 