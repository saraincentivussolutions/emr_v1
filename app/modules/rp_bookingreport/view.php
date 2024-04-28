<?php 
	include  dirname(realpath('..')).'/common/class.common.php';  //ini_set('display_errors',1);
	include 'class.script.php'; 
	$bookingreport = new bookingreport(); 
	
	$action=$_POST["action"];
	$postArr=$_POST;  
	
	$bkrpt_customer_advisor=$postArr['bkrpt_customer_advisor']; 
	$bkrpt_finstatus=$postArr['bkrpt_finstatus'];
	$bkrpt_order_status=$postArr['bkrpt_order_status']; 
	
	$bkrpt_customer_advisor_2=$postArr['bkrpt_customer_advisor_2']; 
	$bkrpt_finstatus_2=$postArr['bkrpt_finstatus_2'];
	$bkrpt_order_status_2=$postArr['bkrpt_order_status_2'];
	
	$sess_log_superuser = $bookingreport->sess_log_superuser; 
	$sess_sales_team_access_ids = $bookingreport->sess_sales_team_access_ids; 
	if($sess_sales_team_access_ids=="") $sess_sales_team_access_ids=0; 
	$salesTeamChkArr = explode(",",$sess_sales_team_access_ids); 
	if($sess_log_superuser) { $salesTeamChkArr=array();  } 
	
	 
	$get_arr = $bookingreport->listview($postArr); 
	$rsData = $get_arr['rsData']; 
	
	 
	
	$dispArr = array();
	$dispTabArr = array();
	
	foreach($rsData as $rsVal)
	{
		
		if(!in_array($rsVal["sales_team_name"],$dispTabArr)) $dispTabArr[]=$rsVal["sales_team_name"];
		
		$dispArr[$rsVal["sales_team_name"]][$rsVal["customer_advisor_name"]][]=$rsVal;		
	}
	
	$order_statuslist = $bookingreport->getModuleComboList('order_status', 'all'); 
	$customer_advisorlist = $bookingreport->getModuleComboList('user');  
	
?>
<form role="form" id="frmRpBookingReport">
  <div class="content-wcommon"> 
         <section class="content-header">
		  <h1>
             Booking Report 
			 <div class="pull-right"> <a class="js-open-modal btn btn-warning act-add" href="#" data-modal-id="popup1" onclick="loadBookingReportFilter();"><i class="fa fa-plus"></i> Filter</a> </div>
			 <div class="col-md-4 no-padding text-right pull-right">				
				<button class="btn btn-primary btn-sm"  onclick="exportBookingReportPDF()" style="margin-right:10px;" type="button"  ><i class="fa fa-file-pdf-o"></i> Print</button>		  	
				<!--<button class="btn btn-warning btn-sm" type="button" onclick="BookEntryBackAA()" ><i class="fa fa-times"></i> Back to list</button>	-->					
				</div>			 
		 </h1>	
		 	
        	<div class="pull-right no-padding"  >
			  <label id="created_by"></label>
			</div>	
        </section>

        <section class="content">   
        <div class="box-body white-bg"> 
   		<div class="container no-padding">
		<?php
		if(count($dispTabArr)==0)
		{
		?>
		No records found for current month
		<?php } else {
			 ?>
			<ul class="tabs">
			<?php 
			$tabcnt=0;
			foreach($dispTabArr as $dispTabNames)
			{ 
				$tabcls="";
				$tabcnt++;
				
				if($tabcnt==1) $tabcls="current";
			
			?>
				<li class="tab-link <?php echo $tabcls;?>" data-tab="tab-<?php echo $tabcnt;?>">Sales team: <?php echo $dispTabNames;?></li> 
				<?php } ?>
			</ul>
			
			<?php } ?>
			<?php 
			$tabShwcnt=0;
			foreach($dispTabArr as $dispTabNames)
			{ 
				$tabShwcls="";
				$tabShwcnt++;
				
				if($tabShwcnt==1) $tabShwcls="current";
			
			?>
			
			<div id="tab-<?php echo $tabShwcnt;?>" class="tab-content <?php echo $tabShwcls;?>">
                    <div class="col-sm-12 no-padding"   >
                        <div class="row no-padding">
                            <div class="col-sm-12 no-padding">
                                <fieldset>
                                    <div class="col-sm-12 pull-left no-padding">
                                        <table class="table table-bordered table-striped" style="width:100%"  >
                    <thead>
                      <tr>
                        <th width="5%">S.No</th>
                        <th width="9%">Ord. Date</th>
                        <th width="9%">Ord. Status</th> 
                        <th width="9%">Cust. Name</th>
                        <th width="9%">Cust. Mobile</th>
                        <th width="9%">Product Line</th>
                        <th width="7%">Veh. Quote</th>
                        <th width="7%">Contribution Offer</th>
                        <th width="6%">SRT additional</th>
						<th width="7%">Received payment</th>
						<th width="5%">Finance by</th>
						<th width="6%">Financier</th>
                        <th width="5%">Loan amount</th> 
						 <th width="7%">Finance status</th> 
                      </tr>
                    </thead>
                    <tbody>
					<?php
					$caArr=$dispArr[$dispTabNames]; 
					 
					foreach($caArr as $custAdvisorName=>$custAdvisorVals)
					{ 
					?>
					 <tr>
                        <td colspan="14"  ><strong>CA: <?php echo $custAdvisorName?></strong></td>                        
                      </tr>
					  <?php 
					$caSno=0;
					foreach($custAdvisorVals as $dispCustBkDet)
					{
						$caSno++;
					?>
					<tr>
                        <td ><?php echo $caSno;?></td>
                        <td ><?php echo $bookingreport->convertDate($bookingreport->purifyString($dispCustBkDet["order_date"]));?></td>
                        <td ><?php echo $dispCustBkDet["orderstatus_name"];?></td>
						<td ><?php echo $dispCustBkDet["customer_name"];?></td>
						<td ><?php echo $dispCustBkDet["customer_mobile"];?></td>
						<td ><?php echo $dispCustBkDet["productline_name"];?></td>
                        <td ><?php echo number_format($dispCustBkDet["onroad_price"],2);?></td>
						<td ><?php echo number_format($dispCustBkDet["contribution_offer"],2);?></td>
						<td ><?php echo number_format($dispCustBkDet["srt_addition_offer"],2);?></td>
						<td ><?php echo number_format($dispCustBkDet["bk_amount_received"],2);?></td>
                        <td ><?php echo $dispCustBkDet["finance_desc"];?></td>
                        <td ><?php echo $dispCustBkDet["financier_name"];?></td>  
						<td ><?php echo $dispCustBkDet["finance_amount"];?></td>
						<td ><?php echo $dispCustBkDet["fin_status"];?></td>                      
                      </tr>
					  <?php }
					  if(!$caSno){
					   ?>
					   <tr>
                        <td colspan="14" align="center" >No data found</td>                        
                      </tr>
					   <?php } ?>
					   <?php } ?>
                    </tbody>                    
                  </table>
                                    </div>
                                </fieldset> 
                            </div>
                        </div>
                    </div>
                </div> 
				<?php } ?>
                 				
		  </div> 
        </section> 
      </div>

</form>
<form id="frmBkRptFilter" name="frmBkRptFilter">

<div class="modal " id="viewPageBkRptSrchModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter</h4>
      </div>
      <div class="modal-body">
	   <div class="row">
          <div class="col-md-12">
		   <label class="lblText">Filter #1 </label>
		  </div>
		  </div>
        <div class="row">
          <div class="col-md-12">
		  <div class="col-sm-6 pull-left">
                                        <label class="lblText">Customer Advisor </label>
                                    
                                        <!-- Praga Data load from master-->
										<select name="bkrpt_customer_advisor" id="bkrpt_customer_advisor" class="form-control">
											<option value="0">Select</option>
											<?php foreach($customer_advisorlist as $rec)
												{
													if(count($salesTeamChkArr)==0 or in_array($rec['sales_team_id'],$salesTeamChkArr))
													{
														$select = ($bkrpt_customer_advisor==$rec['employee_id'])?'selected':'';
														echo '<option value="'.$rec['employee_id'].'" atr_salesteam="'.$rec['sales_team_id'].'" '.$select.'>'.$rec['employee_name'].'</option>';
													}
												}?>
										</select>
                                    </div>
           <div class="col-sm-6 pull-left">
							<label class="lblText">Finance Status</label>
						 
							<select name="bkrpt_finstatus" id="bkrpt_finstatus" class="form-control filter" >
					<option value="-1">-All Status-</option>
					<option value="0" <?php  if($bkrpt_finstatus=="0") echo 'selected'; ?> >Pending - Yet to process</option>
					<option value="1" <?php  if($bkrpt_finstatus=="1") echo 'selected'; ?> >KYC pending</option>
					<option value="2" <?php  if($bkrpt_finstatus=="2") echo 'selected'; ?> >Expected DO pending</option>
					<option value="3" <?php  if($bkrpt_finstatus=="3") echo 'selected'; ?> >Login pending</option>
					<option value="4" <?php  if($bkrpt_finstatus=="4") echo 'selected'; ?> >Approval pending</option>
					<option value="5" <?php  if($bkrpt_finstatus=="5") echo 'selected'; ?> >Document sign pending</option>
					<option value="6" <?php  if($bkrpt_finstatus=="6") echo 'selected'; ?> >MMR pending</option>
					
					<option value="11" <?php  if($bkrpt_finstatus=="11") echo 'selected'; ?> >First followup pending</option>
					<option value="12" <?php  if($bkrpt_finstatus=="12") echo 'selected'; ?> >Second followup pending</option>
					<option value="13" <?php  if($bkrpt_finstatus=="13") echo 'selected'; ?> >Third followup pending</option>
					<option value="14" <?php  if($bkrpt_finstatus=="14") echo 'selected'; ?> >Fourth followup pending</option>
					
					<option value="7" <?php  if($bkrpt_finstatus=="7") echo 'selected'; ?> >DO pending</option>
					<option value="8" <?php  if($bkrpt_finstatus=="8") echo 'selected'; ?> >DO approval pending</option> 
					<option value="10" <?php  if($bkrpt_finstatus=="10") echo 'selected'; ?> >Completed</option> 
				   </select>
						</div>
          </div>
        </div>
		<div class="row">
          <div class="col-md-12">
		   
           <div class="col-sm-6 pull-left">
							<label class="lblText">Order Status </label>
						 
							<!-- Praga Data load from master-->
							<select name="bkrpt_order_status" id="bkrpt_order_status" class="form-control">
								<option value="0">Select</option>
								<?php foreach($order_statuslist as $rec)
								{
									$select = ($bkrpt_order_status==$rec['orderstatus_id'])?'selected':'';
									echo '<option value="'.$rec['orderstatus_id'].'" '.$select.'>'.$rec['orderstatus_name'].'</option>';
								}?>
							</select>
						</div>
          </div>
        </div>
		
		
		<div class="row">
          <div class="col-md-12">
		   <label class="lblText">Filter #2 </label>
		  </div>
		  </div>
		<div class="row">
          <div class="col-md-12">
		  <div class="col-sm-6 pull-left">
                                        <label class="lblText">Customer Advisor </label>
                                    
                                        <!-- Praga Data load from master-->
										<select name="bkrpt_customer_advisor_2" id="bkrpt_customer_advisor_2" class="form-control">
											<option value="0">Select</option>
											<?php foreach($customer_advisorlist as $rec)
												{
													if(count($salesTeamChkArr)==0 or in_array($rec['sales_team_id'],$salesTeamChkArr))
													{
														$select = ($bkrpt_customer_advisor_2==$rec['employee_id'])?'selected':'';
														echo '<option value="'.$rec['employee_id'].'" atr_salesteam="'.$rec['sales_team_id'].'" '.$select.'>'.$rec['employee_name'].'</option>';
													}
												}?>
										</select>
                                    </div>
           <div class="col-sm-6 pull-left">
							<label class="lblText">Finance Status </label>
						 
							<select name="bkrpt_finstatus_2" id="bkrpt_finstatus_2" class="form-control filter" >
					<option value="-1">-All Status-</option>
					<option value="0" <?php  if($bkrpt_finstatus_2=="0") echo 'selected'; ?> >Pending - Yet to process</option>
					<option value="1" <?php  if($bkrpt_finstatus_2=="1") echo 'selected'; ?> >KYC pending</option>
					<option value="2" <?php  if($bkrpt_finstatus_2=="2") echo 'selected'; ?> >Expected DO pending</option>
					<option value="3" <?php  if($bkrpt_finstatus_2=="3") echo 'selected'; ?> >Login pending</option>
					<option value="4" <?php  if($bkrpt_finstatus_2=="4") echo 'selected'; ?> >Approval pending</option>
					<option value="5" <?php  if($bkrpt_finstatus_2=="5") echo 'selected'; ?> >Document sign pending</option>
					<option value="6" <?php  if($bkrpt_finstatus_2=="6") echo 'selected'; ?> >MMR pending</option>
					
					<option value="11" <?php  if($bkrpt_finstatus_2=="11") echo 'selected'; ?> >First followup pending</option>
					<option value="12" <?php  if($bkrpt_finstatus_2=="12") echo 'selected'; ?> >Second followup pending</option>
					<option value="13" <?php  if($bkrpt_finstatus_2=="13") echo 'selected'; ?> >Third followup pending</option>
					<option value="14" <?php  if($bkrpt_finstatus_2=="14") echo 'selected'; ?> >Fourth followup pending</option>
					
					<option value="7" <?php  if($bkrpt_finstatus_2=="7") echo 'selected'; ?> >DO pending</option>
					<option value="8" <?php  if($bkrpt_finstatus_2=="8") echo 'selected'; ?> >DO approval pending</option> 
					<option value="10" <?php  if($bkrpt_finstatus_2=="10") echo 'selected'; ?> >Completed</option> 
				   </select>
						</div>
          </div>
        </div>
		<div class="row">
          <div class="col-md-12">
		   
           <div class="col-sm-6 pull-left">
							<label class="lblText">Order Status </label>
						 
							<!-- Praga Data load from master-->
							<select name="bkrpt_order_status_2" id="bkrpt_order_status_2" class="form-control">
								<option value="0">Select</option>
								<?php foreach($order_statuslist as $rec)
								{
									$select = ($bkrpt_order_status_2==$rec['orderstatus_id'])?'selected':'';
									echo '<option value="'.$rec['orderstatus_id'].'" '.$select.'>'.$rec['orderstatus_name'].'</option>';
								}?>
							</select>
						</div>
          </div>
        </div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnModalSave" onclick="loadBookingReportSearchOK();"><i class="fa fa-floppy-o"></i>&nbsp; Search</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
      </div>
    </div>
  </div>
</div>
</form>