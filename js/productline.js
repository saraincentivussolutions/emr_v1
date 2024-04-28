function loadProductLineMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Product Line</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'productline', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadProductLinedataTableList'};	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadProductLineMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadProductLineMasterList'};
	
	callCommonLoadFunction(passArr); 
	
	
}

function loadProductLinedataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'productline'}; 
	
	$("#productlineMasterTbl").dataTable({
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
					//checkPermssion('productline');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


function loadProductLineMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'productline'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataProductLineMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataProductLineMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#productlineMasterTbl").dataTable({
				  "bPaginate": true,
				  "bLengthChange": false,
				  "bFilter": false,
				  "bSort": true,
				  "bInfo": true,
				  "bAutoWidth": false
			});
	
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
		
		var lpSno=0;
		$.each(rsOp,function(idx,vArr){
		
			lpSno++;
			
			var custCode=''+vArr.productline_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateProductLineMasterList('+vArr.productline_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#productlineMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateProductLineMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'productline', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadProductLineMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadProductLineMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'productline'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataProductLineMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataProductLineMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.productline_id);
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		$("#productline_status").val(rsOp.status);
		$("#productline_vc").val(rsOp.productline_vc);
		
		var parent_productline_list = rsOp.parent_productline_list;
	
		var option = "<option value='0'>Select</option>";
		
		$.each(parent_productline_list, function(k,v){
			var selected = '';			
			if(rsOp.parent_productline_id == v.parent_productline_id) selected = 'selected';
			option += "<option value='"+v.parent_productline_id+"' "+selected+">"+v.parent_productline_name+"</option>";				  
		});
		
		$('select#parent_productline_id').empty();
		$('select#parent_productline_id').append(option)
		
		if(Number(rsOp.productline_id)>0)
		{ 
			$("#productline_name").val(rsOp.productline_name);
			
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

function CreateUpdateProductLineMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'productline'}
	
	
	if(jQuery.trim($('#productline_name').val())=='')
	{
		alert('Enter Product Line');
		$('#productline_name').focus();
		return false;
	}
	if(jQuery.trim($('#parent_productline_id').val())=='0')
	{
		alert('Select Parent Product Line');
		$('#parent_productline_id').focus();
		return false;
	}
	if(jQuery.trim($('#productline_vc').val())=='')
	{
		alert('Enter VC');
		$('#productline_vc').focus();
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmProductLineMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	//console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeProductLineModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeProductLineModalDialog(response)
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
	loadProductLineMaster();
}	
	
	

function viewDeleteProductLineMaster(id)
{
	$('#frmProductLineDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'productline'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteProductLine', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteProductLine(StrData) 
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

function deleteProductLineMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'productline'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmProductLineDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	 
	$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadProductLineMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 