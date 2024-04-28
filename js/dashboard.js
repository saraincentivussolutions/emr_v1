function loadDashboardPage()
{
	var titleCont= ' <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>	<li class="active">Dashboard</li>';
	
	$('.sidebar-menu li').click(function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		})
        
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'dashboard', view:'view'};  
	
	var loadTemplateFile =getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'dashboardLoadGraphs'};	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams };	
	//var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCategoryMasterList'};	
	//var passArr={pURL:'build.php',pageParams:pageParams, onSuccLoadFunc:'loadCategoryMasterList'};
	
	callCommonLoadFunction(passArr); 
}
function callDahboardeportChangecombo(cbPassVal, thisObj,btn_id)
{
	var btnhtml=jQuery.trim($('button#'+btn_id).html());
	var btntxt=jQuery.trim($('button#'+btn_id).text());
	var cbVal=jQuery.trim($(thisObj).text());
	
	var ctx=btnhtml.replace(btntxt,'');
	var nwtx=cbVal+' '+ctx; 
	
	$('button#'+btn_id).html(nwtx); 
	
	
	if(btn_id=='dd_bar_exp_type')
	{   
		if(cbVal=='Expenses by Category')
		{ 
			$('#divCatMonthOpn').show();	
		}
		else
		{
			$('#divCatMonthOpn').hide();		
		}
	}
	
	if(btn_id=='dd_bar_exp_type' || btn_id=='dd_bar_yr_duraion')
	{
		loadBarChartGraphData();	
	} 
	else if(btn_id=='dd_pie_yr_duraion')
	{
		loadPieChartGraphData();	
	}
	else if(btn_id=='dd_year')
	{
		dashboardLoadGraphs();
	}
}
function dashboardLoadGraphs()
{
	loadPieChartGraphData();
	loadBarChartGraphData();
}
function loadPieChartGraphData()
{
	var dd_pie_yr_duraion=jQuery.trim($('button#dd_pie_yr_duraion').text());
	var dd_year=jQuery.trim($('button#dd_year').text()); 
	
	var a  = "getSingeView";	 
	var pageParams = {dd_pie_yr_duraion:dd_pie_yr_duraion, dd_year:dd_year, action:a,  module:'dashboard', call_chart:'pie'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPieChartGraphChart', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
	
	 
}
function loadBarChartGraphData()
{
	var dd_bar_exp_type=jQuery.trim($('button#dd_bar_exp_type').text());
	var dd_bar_yr_duraion=jQuery.trim($('button#dd_bar_yr_duraion').text());
	
	var dd_year=jQuery.trim($('button#dd_year').text()); 
	
	var a  = "getSingeView";	 
	var pageParams = {dd_bar_yr_duraion:dd_bar_yr_duraion, dd_bar_exp_type:dd_bar_exp_type, dd_year:dd_year, action:a,  module:'dashboard', call_chart:'bar'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadBarChartGraphChart', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr);  
	 
}
function loadPieChartGraphChart(StrData)
{
	
	$('label#pie_bud_inramount').html('0.00');
	$('label#pie_exp_inramount').html('0.00'); 
			
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
	if(opData.rsData.piechart!=undefined)
	{
		var pieOp=opData.rsData.piechart; 
		
		if(pieOp.status!='success') 
		{
			//alert('Unable to load data');
			
			$('#div_pie_chart').html('<span style="padding-left:280px;">No data</span>');
			return ;
		}
		else
		{
			var pieData=pieOp.data;
			var pieLbl=pieOp.label;
			
			$('label#pie_bud_inramount').html(pieData[0]);
			$('label#pie_exp_inramount').html(pieData[1]); 
			var pie_plot = [];
			$.each(pieData,function(ixd,iky){
									var val = pieLbl[ixd];
									var arr = [val,parseFloat(iky)];
									pie_plot.push(arr);
									
									}); 
			
			var pie_month_title=jQuery.trim($('button#dd_pie_yr_duraion').text());
			
			$('#div_pie_chart').highcharts({
				colors: ['#8e44ad', '#e74c3c'], //['#3498db', '#f1c40f']
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: 0,
					plotShadow: false
				},
				
				title: {
					text: pie_month_title,
					align: 'center',
					verticalAlign: 'middle',
					y: 140
				},
				tooltip: {
					pointFormat: '{series.name} <b>{point.y:f}</b>'
				},
				plotOptions: {
					pie: {
						dataLabels: {
							enabled: true,
							distance: -50,
							style: {
								fontWeight: 'bold',
								color: 'white',
								textShadow: '0px 1px 2px black'
							}
						},
						startAngle: -90,
						endAngle: 90,
						center: ['50%', '75%']
					}
				},
				series: [{
					type: 'pie',
					name: ' ',
					innerSize: '50%',
					data: pie_plot
				}]
			});	
		}
		
	} 
	
	
	
}
function loadBarChartGraphChart(StrData)
{
	$('label#bar_bud_inramount').html('0.00');
	$('label#bar_exp_inramount').html('0.00'); 
	
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
	if(opData.rsData.barchart!=undefined)
	{
		var barOp=opData.rsData.barchart; 
		
		if(barOp.status!='success') 
		{
			//alert('Unable to load data');
			$('#div_bar_chart').html('<span style="padding-left:280px;">No data</span>');
			return ;
		}
		else
		{ 
			
			var series_cat=barOp.series;
			var bar_data=barOp.data; 
			var bar_sumvals=barOp.sum_values;
			var plorArr=[];
			
			
			$('label#bar_bud_inramount').html(bar_sumvals.budget);
			$('label#bar_exp_inramount').html(bar_sumvals.expenses); 
			
			$.each(bar_data,function(ixd,vals){
																 
						var dm_arr={name:ixd, data:vals };		
						
						plorArr.push(dm_arr);
									 
									 }); 
			
			$('#div_bar_chart').highcharts({
				colors: ['#8e44ad', '#e74c3c'],  
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				  xAxis: {
					categories: series_cat,
					crosshair: true
				},
				yAxis: {
					allowDecimals: false,
					title: {
						text: 'Amounts'
					}
				},
			/*	tooltip: {
					formatter: function () {
						return '<b>' + this.series.name + '</b><br/>' +
							this.point.y + ' ' + this.point.name.toLowerCase();
					}
				},*/
				    tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y} </b></td></tr>',
						footerFormat: '</table>',
						shared: true,
						useHTML: true
					},
				series: plorArr
			});
		}
	}
	
}
 