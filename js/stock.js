//=================== Stock Master 
function loadStockMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Stock</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'stock', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockdataTableList'};	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadStockMasterList'};
	
	callCommonLoadFunction(passArr); 
	
	
}

function loadStockdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'stock'}; 
	
	$("#stockMasterTbl").dataTable({
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
					//console.log(this);
					//checkPermssion('stock');
					$('.lessStkQtyCls').each(function(){
													   
													  $(this).closest('tr').css('color','red');
													   $(this).closest('tr').attr('title','Low stock products');
													  
													  })
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


function loadStockMasterList()
{
	var a  = "getList";	 
	var pageParams = {action:a, module:'stock'};  				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataStockMasterList', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataStockMasterList(StrData)
{ 
	var jOPData=StrData; 
	var opData=jOPData.formOpData;
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
		$("#stockMasterTbl").dataTable({
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
			
			var custCode=''+vArr.stock_name+'';
			
			
			var appendRw='<tr><td>'+lpSno+'</td><td>'+custCode+'</td><td>'+vArr.stock_price+'</td><td>'+vArr.status_desc+'</td><td><span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateStockMasterList('+vArr.product_id+');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"><i class="fa fa-trash-o"></i> Delete</span></td></tr>';
			//alert(appendRw);
			$('#stockMasterTbl tbody').append(appendRw);
			
		
		 
		}) 
		
	} 
}

function CreateUpdateStockMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'stock', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .modal-body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadStockMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'stock'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataStockMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataStockMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.product_id);
		$("#product_name").val(rsOp.product_name); 
		
	} 
	var modal_title = 'Update Stock'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	$('#viewPageModal').modal({  show:true, backdrop:false });
	
}

function CreateUpdateStockMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'stock'}
	
	
	if($('#hid_id').val()=='')
	{
		alert('Please select product from list'); 
		return false;
	}
	if($('#products_qty').val()==0)
	{
		alert('Enter Valid Quantity');
		$('#products_qty').focus();
		return false;
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmStockMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeStockModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeStockModalDialog(response)
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
	loadStockMaster();
}	
	 
 