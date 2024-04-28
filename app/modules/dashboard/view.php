<?php 
	$maindir = dirname(realpath('..'));
	include  $maindir.'/common/class.common.php'; 
	include 'class.script.php'; 
	 
	
	$dashboard = new dashboard();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	/* year drop down list */
	$shwFromYear=$dashboard->shwYearDropDownFrom;
	$drpDwnYearList=array();
	for($dd_y=$shwFromYear;$dd_y<=date('Y');$dd_y++){ $drpDwnYearList[]=$dd_y;} 
	rsort($drpDwnYearList);
	
	/* month drop down list */
	$shwFromMonth=$dashboard->shwMonthDropDownFrom;
	$drpDwnMonthList=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');	
	
	/*$cmbbx_year=$dashboard->purifyInsertString($postArr["v_cmbbx_year"]);
	if($cmbbx_year==""){ $cmbbx_year=date('Y'); } */
	$cmbbx_year=date('Y');
	$cmbbx_month=date('M');
	
	$get_arr = $dashboard->statuswiselistview($postArr);  
	$rsStsWiseData = $get_arr['rsData']; 
	
	$srt_addnarr = $dashboard->srtaddnofferlistview($postArr);  
	$rsSrtAddnData = $srt_addnarr['rsData']; 
	
	$stockdisarr = $dashboard->stockballistview($postArr);  
	$rsStockData = $stockdisarr['rsData']; 
	
	 
	$teamwiseStatusArr=array(); 
	$prodwiseStatusArr=array();
	 
	foreach($rsStsWiseData as $rsStsVal)
	{  
		$stsDefined="";
		
		if($rsStsVal["last_month_yellow"]=='prev_month') $stsDefined="CFB";
		else if($rsStsVal["order_status"]=='1') $stsDefined="PreBk";
		else if($rsStsVal["order_status"]=='5') $stsDefined="RT";
		else if($rsStsVal["order_status"]=='7') $stsDefined="RTG"; 
		else $stsDefined="BK";
		
		$teamwiseStatusArr["sales"][$rsStsVal["sales_team_name"]][$stsDefined]++;
		$teamwiseStatusArr["status"][$stsDefined]++;
		
		$prodwiseStatusArr["sales"][$rsStsVal["parent_productline_name"]][$stsDefined]++;
		$prodwiseStatusArr["status"][$stsDefined]++;
 	
	}
	
	$srtAddnOfferDisArr=array();  
	$srtAddnOfferPLDisArr=array();  
	 
	foreach($rsSrtAddnData as $rsSrtAddnVal)
	{   
		
		$srtAddnOfferPLDisArr[$rsSrtAddnVal["parent_product_line"]]=$rsSrtAddnVal["parent_productline_name"];
		
		$srtAddnOfferDisArr[$rsSrtAddnVal["sales_team_name"]][$rsSrtAddnVal["parent_product_line"]]["value"]=number_format($rsSrtAddnVal["avg_srt_add"],2); 
		$srtAddnOfferDisArr[$rsSrtAddnVal["sales_team_name"]][$rsSrtAddnVal["parent_product_line"]]["count"]=$rsSrtAddnVal["srt_count"]; 
		$srtAddnOfferDisTotArr[$rsSrtAddnVal["parent_product_line"]]["total"]+=$rsSrtAddnVal["avg_srt_add"]; 
	}
	
	 
	$stockPLDisArr=array();  
	 
	foreach($rsStockData as $rsStockVal)
	{   
		$stockPLDisArr[$rsStockVal["parent_productline_name"]]['open_stock_cnt']=$rsStockVal["open_stock_cnt"];
		$stockPLDisArr[$rsStockVal["parent_productline_name"]]['gstock_cnt']=$rsStockVal["gstock_cnt"]; 
	}
	 
	 
?>
<div class="content-wcommon"> 
        <section class="content-header">
          <h1>
             Dashboard
			 <!--<div class="dropdown pull-right">
			 <span style="font-size:15px">Year</span>
			  <button id="dd_year" class="btn btn-year dropdown-toggle" type="button" data-toggle="dropdown" style="width:150px;margin-right:10px;margin-top:-5px;"><?=$cmbbx_year;?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  <?php foreach($drpDwnYearList as $drpDwnYearVal){ ?>
				<li onclick="callDahboardeportChangecombo('<?=$drpDwnYearVal;?>',this,'dd_year');" ><a href="#"><?=$drpDwnYearVal;?></a></li><?php } ?> 
			  </ul>
			</div>-->
			<!--<div class="dropdown pull-right">
			 <span style="font-size:15px">Month</span>
			  <button id="dd_month" class="btn btn-year dropdown-toggle" type="button" data-toggle="dropdown" style="width:150px;margin-right:10px;margin-top:-5px;"><?=$cmbbx_month;?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  <?php foreach($drpDwnMonthList as $drpDwnMonthVal){ ?>
				<li onclick="callDahboardeportChangecombo('<?=$drpDwnMonthVal;?>',this,'dd_month');" ><a href="#"><?=$drpDwnMonthVal;?></a></li><?php } ?> 
			  </ul>
			</div>-->
			
		</h1>	
        </section>

        <section class="content">
          <div class="row">
            <div class="col-lg-6 col-xs-6">
			  	<div class="dashboard-box-1">
					<h4>Teamwise Status</h4>
					<div id="div_pie_chart" style="min-width: 310px; height: 400px; max-width: 600px; margin:0 auto;display:none;"></div>		
					<table class="tmTbl">
						<thead>
							<tr>
								<td width="35%">Team Name</td>
								<td width="13%">CFB</td>
								<td width="13%">Pre BK</td>
								<td width="13%">BK</td>
								<td width="13%">RT-O</td> 
							    <td width="13%">RT-G</td>
							</tr>							
						</thead>
						<tbody>
						<?php 
						$teamSwCno=0;
							foreach($teamwiseStatusArr["sales"] as $teamNameSw=>$teamwiseStatusVal)
							{
								$teamSwCno++;
						?>
							<tr>
								<td><?php echo $teamNameSw;?></td>
								<td><?php echo $teamwiseStatusVal["CFB"];?></td>
								<td><?php echo $teamwiseStatusVal["PreBk"];?></td>
								<td><?php echo $teamwiseStatusVal["BK"];?></td>
								<td><?php echo $teamwiseStatusVal["RT"];?></td> 							
							    <td><?php echo $teamwiseStatusVal["RTG"];?></td>
							</tr>
							<?php }
							if(!$teamSwCno){
							 ?>
							<tr>
								<td colspan="6" >No data</td> 		
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>	
							<tr>
								<td>Total</td>
								<td><?php echo $teamwiseStatusArr["status"]["CFB"];?></td>
								<td><?php echo $teamwiseStatusArr["status"]["PreBk"];?></td>
								<td><?php echo $teamwiseStatusArr["status"]["BK"];?></td>
								<td><?php echo $teamwiseStatusArr["status"]["RT"];?></td> 
							    <td><?php echo $teamwiseStatusArr["status"]["RTG"];?></td>
							</tr>
						</tfoot>
					</table>
				</div>
            </div>
            <div class="col-lg-6 col-xs-6 left-padding-none">
				<div class="dashboard-box-2">
					<h4>Product Line Wise</h4>
              		<div id="div_bar_chart" style="min-width: 310px; height: 400px; margin: 0 auto;display:none"></div>
					<table class="proTbl">
						<thead>
							<tr>
								<td width="35%">Product</td>
								<td width="13%">CFB</td>
								<td width="13%">Pre BK</td>
								<td width="13%">BK</td>
								<td width="13%">RT-O</td> 
							    <td width="13%">RT-G</td>
							</tr>	
						</thead>
						<tbody>
						<?php 
						$prodSwCno=0;
							foreach($prodwiseStatusArr["sales"] as $prodNameSw=>$prodwiseStatusVal)
							{
								$prodSwCno++;
						?>
							<tr>
								<td><?php echo $prodNameSw;?></td>
								<td><?php echo $prodwiseStatusVal["CFB"];?></td>
								<td><?php echo $prodwiseStatusVal["PreBk"];?></td>
								<td><?php echo $prodwiseStatusVal["BK"];?></td>
								<td><?php echo $prodwiseStatusVal["RT"];?></td> 							
							    <td><?php echo $prodwiseStatusVal["RTG"];?></td> 
							</tr>
							<?php }
							if(!$prodSwCno){
							 ?>
							<tr>
								<td colspan="6" >No data</td> 		
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>	
							<tr>
								<td>Total</td>
								<td><?php echo $prodwiseStatusArr["status"]["CFB"];?></td>
								<td><?php echo $prodwiseStatusArr["status"]["PreBk"];?></td>
								<td><?php echo $prodwiseStatusArr["status"]["BK"];?></td>
								<td><?php echo $prodwiseStatusArr["status"]["RT"];?></td> 
							    <td><?php echo $prodwiseStatusArr["status"]["RTG"];?></td> 
							</tr>
						</tfoot>
					</table>
				</div>			  
				
            </div>
          </div>
		  <div class="row" style="margin-top:15px;">
            <div class="col-lg-8 col-xs-8">
			  	<div class="dashboard-box-1">
					<h4>SRT Addtional Offer</h4>
					<div id="div_pie_chart" style="min-width: 310px; height: 400px; max-width: 600px; margin:0 auto;display:none;"></div>		
					<table class="ofrTbl">
						<thead>
							<tr>
								<td width="28%">Team Name</td>
								<?php foreach($srtAddnOfferPLDisArr as $srtAddnOfferPLKey=>$srtAddnOfferPLVal){ ?>
								<td colspan="2" ><?php echo $srtAddnOfferPLVal;?></td>
								<?php } ?> 
							</tr>	
							<tr>
								<td  >&nbsp;</td>
								<?php foreach($srtAddnOfferPLDisArr as $srtAddnOfferPLKey=>$srtAddnOfferPLVal){ ?>
								<td   >Nos.</td>
								<td   >Avg. dis</td>
								<?php } ?> 
							</tr>							
						</thead>
						<tbody>
						<?php 
						$dispSrtaddTotArr=array();
						foreach($srtAddnOfferDisArr as $srtAddnOfferDisName=>$srtAddnOfferDisVal)
						{ 
						?> 
							<tr>
								<td><?php echo $srtAddnOfferDisName;?></td>
								<?php 
								foreach($srtAddnOfferPLDisArr as $srtAddnOfferPLKey=>$srtAddnOfferPLVal)
								{ 
									//$dispSrtaddTotArr[$srtAddnOfferPLKey]+=$srtAddnOfferDisVal[$srtAddnOfferPLKey];
								?>
								<td  ><?php echo $srtAddnOfferDisVal[$srtAddnOfferPLKey]["count"]; ?> </td>
								<td  > <?php if($srtAddnOfferDisVal[$srtAddnOfferPLKey]["value"]<>0) echo $srtAddnOfferDisVal[$srtAddnOfferPLKey]["value"]; else echo '';?></td> 
								<?php } ?> 							
							</tr>
							<?php } ?>  
							 						
						</tbody>
						<tfoot>	
							<tr>
								<td>Total</td>
								<?php 
								
								foreach($srtAddnOfferDisTotArr as $srtAddnOfferDisTotVal)
								{ 
									 
								?>
								<td   ><?php echo $srtAddnOfferDisTotVal["total_count"];?></td>
								<td   ><?php echo number_format($srtAddnOfferDisTotVal["total"],2);?></td>
								<?php } ?> 		
							</tr>
						</tfoot>
					</table>
				</div>
            </div>
            <div class="col-lg-4 col-xs-4 left-padding-none">
				<div class="dashboard-box-2">
					<h4>Stock Wise</h4>
              		<div id="div_bar_chart" style="min-width: 310px; height: 400px; margin: 0 auto;display:none"></div>
					<table class="stkTbl">
						<thead>
							<tr>
								<td width="60%">Product</td>
								<td width="20%">Open</td>
								<td width="20%">G Stock</td>
							</tr>	
						</thead>
						<tbody>
						<?php 
						$dstockcno=0;
						$stkTotArr=array();
						foreach($stockPLDisArr as $stockPLDisKey=>$stockPLDisVal)
						{
							$dstockcno++;
							
							$stkTotArr['open_stock_cnt']+=$stockPLDisVal['open_stock_cnt'];
							$stkTotArr['gstock_cnt']+=$stockPLDisVal['gstock_cnt'];
							
						 ?>
							<tr>
								<td><?php echo $stockPLDisKey;?></td>
								<td><?php echo $stockPLDisVal['open_stock_cnt'];?></td>
								<td><?php echo $stockPLDisVal['gstock_cnt'];?></td>
							</tr>
							<?php }  
								if(!$dstockcno)
								{
							 ?> 
							 <tr>
								<td colspan="3" >No data</td> 
							</tr>
							 <?php } ?>			
						</tbody>
						<tfoot>	
							<tr>
								<td>Total</td>
								<td><?php echo $stkTotArr['open_stock_cnt'];?></td>
								<td><?php echo $stkTotArr['gstock_cnt'];?></td>
							</tr>
						</tfoot>
					</table>
				</div>			  
				
            </div>
          </div>
        </section>
      </div>    