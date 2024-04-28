function loadVehNoAvailMaster()
{
	var titleCont= ' <li> Transactions </li>';
        titleCont += '<li class="active">VNA</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'veh_not_avail', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadVehNoAvaildataTableList'}; 
	
	callCommonLoadFunction(passArr); 
	
	
}


function loadVehNoAvaildataTableList()
{
	 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'veh_not_avail'}; 
	
	$("#vehnotavailMasterTbl").dataTable({
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
					//checkPermssion('VNA');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}
