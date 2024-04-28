function loadStockEntryDetails()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Stock details</li>';
	topHeadTitle(titleCont);
	
	 
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_entry', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view);  
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockEntryDetailsTableList'};	 
	
	callCommonLoadFunction(passArr);  
} 
function loadStockEntryDetailsTableList()
{  
	var a  = "getList";	 
	var pageParams = {action:a, module:'stock_entry' }; 
	
	$("#stockentryListDetailsTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				  "bAutoWidth": false,
				  "ajax":  {
						"url": TemplateModDir+pageParams.module+"/controller.php",
						"type": "POST",
						"data":pageParams
					}
				  
	 });
	 
	highlightRightMenu();
} 
function loadMakeBillsDetails()
{  
	//CreateUpdateStockEntryDetails();
	alert('Manually made hidden');
}
 

function CreateUpdateStockEntryDetails(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'stock_entry', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockEntryDetailsAddEdit', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadStockEntryDetailsAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'stock_entry'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataStockEntryDetailsAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataStockEntryDetailsAddEdit(StrData)
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
		
		$("#hid_id").val('');
		//$("#bill_date").val(rsOp.bill_date); 
		 
		 
		var parent_productlinelist = rsOp.parent_productlinelist;
		var productlinelist = rsOp.productlinelist;
		var productcolourlist = rsOp.productcolourlist; 
		
		
		var option_parprd_def = "<option value='0'>Select</option>"; 
		$.each(parent_productlinelist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_parprd_def += "<option value='"+v.parent_productline_id+"' "+selected+">"+v.parent_productline_name+"</option>";				  
		}); 

		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(productlinelist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_prdline_def += "<option value='"+v.productline_id+"' "+selected+" parprdid='"+v.parent_productline_id+"'  >"+v.productline_name+"</option>";				  
		});
		
		
		var option_prdclr_def = "<option value='0'>Select</option>"; 
		$.each(productcolourlist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_prdclr_def += "<option value='"+v.productcolour_id+"' "+selected+" parprdids='"+v.parent_productline_ids+"' >"+v.productcolour_name+"</option>";				  
		});
		
		$('select#cmb_parentproductline_rw').empty();
		$('select#cmb_parentproductline_rw').append(option_parprd_def);
		
		$('select#cmb_productline_rw').empty();
		$('select#cmb_productline_rw').append(option_prdline_def);
		
		$('select#cmb_product_colour_rw').empty();
		$('select#cmb_product_colour_rw').append(option_prdclr_def);
		
		var table = '#customFields';
		var tempRow = $(table).find('#tempRow');
		
		var cnt = 5; 
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1)); 
			 
			$('select#cmb_parentproductline_'+(i+1)).empty();
			$('select#cmb_parentproductline_'+(i+1)).append(option_parprd_def);
			
			$('select#cmb_productline_'+(i+1)).empty();
			$('select#cmb_productline_'+(i+1)).append(option_prdline_def); 
			
			$('select#cmb_product_colour_'+(i+1)).empty();
			$('select#cmb_product_colour_'+(i+1)).append(option_prdclr_def); 
		} 
	} 
	
	$('.datepicker').datepicker({
			  autoclose: true
	});	 
}
function addStockEntryDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getStockEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	 
 
}

function viewStockEntryOnChangeParentProd(id)
{
	var parprdid = $('select#cmb_parentproductline_'+(id)).val();
	$('select#cmb_productline_'+(id)).val(0);
	$('select#cmb_product_colour_'+(id)).val(0);
	
	$('select#cmb_productline_'+(id)).find('option').hide(); 
	$('select#cmb_productline_'+(id)).find('option').each(function(){
		if(parprdid == $(this).attr('parprdid') || $(this).val()==0)
		$(this).show();
	});
	
	$('select#cmb_product_colour_'+(id)).find('option').hide(); 
	$('select#cmb_product_colour_'+(id)).find('option').each(function(){
		var parprdids=$(this).attr('parprdids');
		var parprdAr=new Array();
		if(parprdids) var parprdAr=parprdids.split(',');
		
		if((jQuery.inArray(parprdid,parprdAr)!=-1) || $(this).val()==0)
		$(this).show();
	});
} 

function getStockEntryTableMaxId()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	var trid = 0;
	$(table).find('tbody tr:not(#tempRow)').each(function(){
			var id = $(this).attr('id');
			id = id.replace('bill_','');
			id = Number(id);
			if(trid<id) trid = id;
	});
	
	return trid;
}

function CreateUpdateStockEntrySave()
{  
	var firstValid="";
	var multrwfillcnt=0;
	$('#customFields').find('tbody tr:not(#tempRow)').each(function(){
			var id = $(this).attr('id');
			id = id.replace('bill_','');
			id = Number(id);
			
			//if(id>2) { alert('sara'); firstValid="fail"; return false; }
			
			var c_stock_entry_date = '#txt_stock_entry_date_'+id;
			var c_parentproductline = '#cmb_parentproductline_'+id;
			var c_productline = '#cmb_productline_'+id;
			var c_product_colour = '#cmb_product_colour_'+id;
			var c_chasis_no = '#txt_chasis_no_'+id;
			var c_purchase_cost = '#txt_purchase_cost_'+id;
			
			if(jQuery.trim($(c_stock_entry_date).val())!="" || $(c_parentproductline).val()!=0 || $(c_productline).val()!=0 || $(c_product_colour).val()!=0 || jQuery.trim($(c_chasis_no).val())!="" || jQuery.trim($(c_purchase_cost).val())!="")
			{
				if(jQuery.trim($(c_stock_entry_date).val())==""){alert('Select date'); $(c_stock_entry_date).focus(); firstValid="fail"; return false;}
				if($(c_parentproductline).val()==0){alert('Select Parent productline'); $(c_parentproductline).focus(); firstValid="fail"; return false;}
				if($(c_productline).val()==0){alert('Select Productline'); $(c_productline).focus(); firstValid="fail"; return false;}
				if($(c_product_colour).val()==0){alert('Select Product coulur'); $(c_product_colour).focus(); firstValid="fail"; return false;}
				if(jQuery.trim($(c_chasis_no).val())==""){alert('Enter Chasisno'); $(c_chasis_no).focus(); firstValid="fail"; return false;}
				if(jQuery.trim($(c_purchase_cost).val())==""){alert('Enter purchase cost'); $(c_purchase_cost).focus(); firstValid="fail"; return false;}
				multrwfillcnt++;	
			} 
			 
	});
	 
	if(firstValid=="fail") return false;
	
	$('.clsStockChasisNo').each(function(){
										 // Have to write duplicate entry for chasis no
										 });
	
	if(multrwfillcnt<=0){alert('No items entered'); return false;}
	
	 
	
	 
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'stock_entry'} 
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmStockEntry').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
  
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeStockEntryModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}
function closeStockEntryModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	var new_id=0; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message; 
		
		if(data.new_id) new_id=data.new_id;
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
	loadStockEntryDetails();
}	
 
function StockEntryBack()
{ 
	loadStockEntryDetails();	
} 

function deleteStockEntryRow(rw)
{
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_bill_detid_'+rw).val();
	var hid_temp_del = $('#hid_temp_del').val();
	
	if(del_val!='')
	{
		if(hid_temp_del!='')
		{
			del_val = hid_temp_del+','+del_val;
		}
	}
	
	//alert(del_val);
	
	$('#hid_temp_del').val(del_val); 
	
	$(tr).remove(); 
	 
	var addtblcnt = 0;
	$('#customFields').find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addStockEntryDetailRow();
}

 function downloadExcelStackEntryData()
 {
	 var a  = "view";	 
	var pageParams = {action:a, module:'stock_entry', view:'download_xls'};  
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'viewExportFile',sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
 }
 
 function viewExportFile(StrData)
{
	//console.log(StrData);
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	if(opData.status == 'success')
	{
		if(opData.file)
		{
			window.open(opData.file)
		}
	}
}

function importExcelStackEntryData()
{
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_entry', view:'import'};  
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadImportStockEntry', sendCustPassVal:custVals, pageLoadContent:'#viewImportModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
}

function loadImportStockEntry()
{
	$('#viewImportModal').modal({  show:true, backdrop:false });
}

function importStockEntryFile()
{
	var upfile=$("#import_file").val();
	if(upfile==""){ alert('Choose file!'); return false; }
	
	var ext = upfile.split('.').pop().toLowerCase();
				if($.inArray(ext, ['xls','xlsx']) == -1) {
					alert('invalid file!');
					return false;
				}
				
	
	$("#frmFileImport").ajaxForm({
						target: '#output',
						url:'process.php',						
						success:  function(data) {  
						//console.log('praga');
						//console.log(data);
						
						var op=$('#output').html();
						var opData = jQuery.parseJSON( op );
						 
						
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
						else
						{
							customAlert(opData.message); 
							$('#viewImportModal').modal('hide');	
							
							if($('.modal-backdrop').is(':visible'))
							{
								$('.modal-backdrop').hide();
							}
							
							CreateUpdateStockEntryFromImport(opData);
						}  
							//('#frmStudent').ajaxForm(ajxfmroptionsStudent); 
						}
		}).submit();
}

function CreateUpdateStockEntryFromImport(opData)
{
	var rsData = opData.rsData;
	if(rsData)
	{
		var stock_details = rsData.stock_details;
		$('#hid_temp_import_file').val(rsData.file_path);
		$('#hid_import_opt').val(1);
		if(stock_details)
		{
			$.each(stock_details, function(k,v){
				$('#txt_stock_entry_date_'+(k+1)).val(v.txt_stock_entry_date);
				$('#cmb_parentproductline_'+(k+1)).val(v.cmb_parentproductline);
				$('#cmb_productline_'+(k+1)).val(v.cmb_productline);
				$('#cmb_product_colour_'+(k+1)).val(v.cmb_product_colour);
				$('#txt_chasis_no_'+(k+1)).val(v.txt_chasis_no);
				$('#txt_purchase_cost_'+(k+1)).val(v.txt_purchase_cost);
				$('#cmb_stock_status_'+(k+1)).val(v.cmb_stock_status);
			});
		}
	}
}
