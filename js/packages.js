//=================== Packages Master 
function loadPackagesMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Packages</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'packages', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPackagesdataTableList'};	
	 
	callCommonLoadFunction(passArr);  
	
}

function loadPackagesdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'packages'}; 
	
	$("#packagesMasterTbl").dataTable({
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
					//checkPermssion('packages');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 
function CreateUpdatePackagesMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'packages', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPackagesMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPackagesMasterAddEdit', sendCustPassVal:custVals};
	
	callCommonLoadFunction(passArr); 
		
}
function loadPackagesMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'packages'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataPackagesMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataPackagesMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.packages_id);
		var categorylist = rsOp.categorylist;
		var serviceslist = rsOp.serviceslist; 
		var package_sub_details = rsOp.package_sub_details;
		
		var option_cat_def = "<option value='0'>Select</option>"; 
		$.each(categorylist, function(k,v){
								  
			var selected = '';
			if(v.active_status == '1')
			option_cat_def += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		}); 

		
		var option_subcat_def = "<option value='0'>Select</option>"; 
		$.each(serviceslist, function(k,v){
			var selected = '';
			if(v.active_status == '1')
			option_subcat_def += "<option value='"+v.services_id+"' "+selected+" catid='"+v.category_id+"' srvrate='"+v.services_price+"'>"+v.services_name+"</option>";				  
		});
		
		$('select#cmb_category_rw').empty();
		$('select#cmb_category_rw').append(option_cat_def);
		
		$('select#cmb_services_rw').empty();
		$('select#cmb_services_rw').append(option_subcat_def);
		
		
		var cnt = package_sub_details.length;
		
		var table = '#customFields';
		var tempRow = $(table).find('#tempRow');
		 
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1));
			
			var rec = package_sub_details[i];
			
			if(rec)
			{
				if(rec.package_services_id )
				{
					$(tr).find('#hdn_bill_detid_'+(i+1)).val(rec.package_services_id); 
				}
				
				 
				var option_cat_deflp = "<option value='0'>Select</option>"; 
				$.each(categorylist, function(k,v){
					var selected = '';
					if(v.active_status == '1' || rec.category_id == v.category_id)
					if(rec.category_id == v.category_id) selected = 'selected';
					option_cat_deflp += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
				}); 
				
				var option_subcat_deflp = "<option value='0'>Select</option>"; 
				$.each(serviceslist, function(k,v){
					var selected = '';
					if(v.active_status == '1' || rec.services_id == v.services_id)
					if(rec.services_id == v.services_id) selected = 'selected';
					option_subcat_deflp += "<option value='"+v.services_id+"' "+selected+" catid='"+v.category_id+"' srvrate='"+v.services_price+"'>"+v.services_name+"</option>";			  
				});
				
				$('select#cmb_category_'+(i+1)).empty();
				$('select#cmb_category_'+(i+1)).append(option_cat_deflp);
				
				$('select#cmb_services_'+(i+1)).empty();
				$('select#cmb_services_'+(i+1)).append(option_subcat_deflp); 
				 
			}
			else
			{
			 
				$('select#cmb_category_'+(i+1)).empty();
				$('select#cmb_category_'+(i+1)).append(option_cat_def);
				
				$('select#cmb_services_'+(i+1)).empty();
				$('select#cmb_services_'+(i+1)).append(option_subcat_def); 
			}
			
		}
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#packages_status").val(rsOp.status);
		
		if(Number(rsOp.packages_id)>0)
		{ 
			$("#packages_name").val(rsOp.packages_name);
			$("#packages_price").val(rsOp.packages_price);
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Packages':'New Packages'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdatePackagesMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'packages'}
	
	
	if(jQuery.trim($('#packages_name').val())=='')
	{
		alert('Enter Package name');
		$('#packages_name').focus();
		return false;
	}
	if(jQuery.trim($('#packages_price').val())=='')
	{
		alert('Enter Amount');
		$('#packages_price').focus();
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPackagesMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closePackagesModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closePackagesModalDialog(response)
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
			
	
	//$('#viewPageModal').modal('hide');
	//$('#viewPageModal').removeBackdrop();
	loadPackagesMaster();
}	
	
	

function viewDeletePackagesMaster(id)
{
	$('#frmPackagesDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'packages'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeletePackages', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr);
}

function loadDeletePackages(StrData) 
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

function deletePackagesMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'packages'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPackagesDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPackagesMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
function addPackagesMasterServiceRow()
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
	
	var rwid = trid+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>"); 
 
}
function PackageMasterDetailsBack()
{ 
	loadPackagesMaster();	
} 
function deletePackagesMasterServiceRow(rw)
{ 
	var tr = $('tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_bill_detid_'+rw).val();
	var hid_temp_del = $('#hid_temp_del').val();
	
	if(del_val!='')
	{
		var con = confirm('Are you sure want to delete?');
		if(!con) return false;
	
		if(hid_temp_del!='')
		{
			del_val = hid_temp_del+','+del_val;
		}
	}
	
	//alert(del_val);
	
	$('#hid_temp_del').val(del_val); 
	
	$(tr).remove(); 
	
	ChkAndAddRowPackagesMasterServiceRow();
}
function ChkAndAddRowPackagesMasterServiceRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow'); 
	
	var rwcnt=0;
	$(table).find('tbody tr:not(#tempRow)').each(function(){
			rwcnt++;			
	});
	if(rwcnt==0) addPackagesMasterServiceRow();
}