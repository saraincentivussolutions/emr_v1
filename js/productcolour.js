//=================== ProductColour Master 
function loadProductColourMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Product Colour</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'productcolour', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadProductColourdataTableList'};	
	 
	
	callCommonLoadFunction(passArr); 
	
	
}

function loadProductColourdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'productcolour'}; 
	
	$("#productcolourMasterTbl").dataTable({
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
					//checkPermssion('productcolour');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


 

function CreateUpdateProductColourMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'productcolour', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadProductColourMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadProductColourMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'productcolour'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataProductColourMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataProductColourMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.productcolour_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#productcolour_status").val(rsOp.status);
		$("#productcolour_vc").val(rsOp.productcolour_vc);
		
		var productline_list = rsOp.productline_list;
	
		var option = "";
		var chkArr = new Array();
		if(rsOp.parent_productline_ids)
		{
			var chkArr = rsOp.parent_productline_ids;
			chkArr = chkArr.split(',');
		}
		$.each(productline_list, function(k,v){
			var selected = '';		
			var tmp = jQuery.inArray(v.parent_productline_id,chkArr);
			if(tmp>-1) selected = 'checked';
			var str = '<div class="col-md-4"><label><input name="chk_product_line[]" type="checkbox" value="'+v.parent_productline_id+'" '+selected+'> '+v.parent_productline_name+'</label></div>';
			//
			option += str;				  
		});
		
		
		$('div#chk_multi_product_line').html(option)
		
		if(Number(rsOp.productcolour_id)>0)
		{ 
			$("#productcolour_name").val(rsOp.productcolour_name);
			
			$('.divClsActiveStatus').show(); 
		} 
		else
		{
			$('.divClsActiveStatus').hide();
		} 
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Product Line':'New Product Line'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateProductColourMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'productcolour'}
	
	
	if(jQuery.trim($('#productcolour_name').val())=='')
	{
		alert('Enter Product Colour');
		$('#productcolour_name').focus();
		return false;
	}
	
	/*if(jQuery.trim($('#parent_productline_id').val())=='0')
	{
		alert('Select Parent Product Line');
		$('#parent_productline_id').focus();
		return false;
	}
	if(jQuery.trim($('#productcolour_vc').val())=='')
	{
		alert('Enter VCH');
		$('#productcolour_vc').focus();
		return false;
	}*/
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmProductColourMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeProductColourModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeProductColourModalDialog(response)
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
	loadProductColourMaster();
}	
	
	

function viewDeleteProductColourMaster(id)
{
	$('#frmProductColourDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'productcolour'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteProductColour', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteProductColour(StrData) 
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

function deleteProductColourMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'productcolour'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmProductColourDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadProductColourMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 