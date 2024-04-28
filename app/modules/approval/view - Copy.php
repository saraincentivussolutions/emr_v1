<?php 
	include  dirname(realpath('..')).'/common/class.common.php';  
	include 'class.script.php'; 
	$approval = new approval(); 
	
	$action=$_POST["action"];
	$postArr=$_POST; 
	
	$booking_id=$approval->purifyInsertString($postArr["id"]);
	$get_arr = $approval->getSingleView($postArr);
	$rsData = $get_arr['rsData'];
	$combo_list = $get_arr['combo_list'];
	
	$receipt_arr = $approval->getBookingApprovalReceiptDetailsView($postArr["id"]);
	
?>
<form role="form" id="frmApprovalMaster">
  <input type="hidden" name="hid_id" id="hid_id"  value="<?php echo $rsData['booking_transaction_id']; ?>"/>
  <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Approval Entry 
			 <div class="col-md-4 no-padding text-right pull-right">				
				<button class="btn btn-primary btn-sm"  onclick="CreateUpdateApprovalMasterSave()" style="margin-right:10px;" type="button"  ><i class="fa fa-floppy-o"></i> Submit</button>		  						
				<button class="btn btn-warning btn-sm" type="button"  onclick="ApprovalMasterBack()" ><i class="fa fa-times"></i> Back to list</button>
			</div>	
		</h1>	
        <div class="pull-right no-padding"  >
			  <label id="created_by"></label>
			</div>	
        </section>

        <section class="content"> 	
          <div class="box-body white-bg">
   			<div class="container no-padding">
			<ul class="tabs">
				<li class="tab-link current" data-tab="tab-1">Approval</li> 
				<li class="tab-link" data-tab="tab-2">Vehicle Quotation</li>
				<li class="tab-link" data-tab="tab-3">Offer Details</li>
				<li class="tab-link" data-tab="tab-4">Customer Payments & Receipts</li>
			</ul>
                <div id="tab-1" class="tab-content current">
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Order No <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<input name="order_no" type="text" maxlength="50" id="order_no"  class="form-control" value="<?php echo $rsData['order_no']; ?>" readonly="" />
						</div>  
						 
					</fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Vehicle OnRoad Price  </label>
                                    </div>
                                    <div class="col-sm-4 pull-left no-padding">
                                       <input name="vehicle_onroad_price" type="text" maxlength="50" id="vehicle_onroad_price"  class="form-control"   value="<?php echo $rsData['onroad_price']; ?>" readonly="" />
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Amount Received  </label>
                                    </div>
                                    <div class="col-sm-4 pull-left no-padding">
                                      <input name="amount_received" type="text" maxlength="50" id="amount_received"  class="form-control"   value="<?php echo $rsData['bk_amount_received']; ?>" readonly="" />
                                    </div>
                                    
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Approved By <font color="red"><b> * </b></font></label>
                                    </div>
                                    <div class="col-sm-4 pull-left no-padding">
                                        <input name="approved_by" type="text" maxlength="50" id="approved_by" class="form-control" placeholder="Approved By" value="<?php echo $rsData['approved_by']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
										<label class="lblText">Approved Date </label>
									</div>
									<div class="col-sm-4 pull-left no-padding">
									<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input name="approved_date" type="text" maxlength="20" id="approved_date" class="form-control pull-right datepicker" placeholder="Approved Date" value="<?php echo $rsData['approved_date']; ?>"/></div>
									</div>
                                </fieldset>
                                <fieldset>
								<div class="col-sm-2 pull-left">
                                        <label class="lblText">Retail Status  </label>
                                    </div>
                                    <div class="col-sm-2 no-padding">
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="retail_status1" type="radio" name="retail_status" value="1" <?php echo ($rsData['retail_status']==1)?'checked':''; ?>/> Yes</label>
										</div>
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="retail_status2" type="radio" name="retail_status" value="2" <?php echo ($rsData['retail_status']==2)?'checked':''; ?>/> No</label>
										</div> 
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Remarks </label>
                                    </div>
                                    <div class="col-sm-4 pull-left no-padding">
                                        <textarea class="form-control" id="remark_desc" name="remark_desc" style="height:75px;"><?php echo $rsData['remark_desc'];?></textarea>
                                    </div>
                                     
                                </fieldset>
                                 
                </div>

                 

                <div id="tab-2" class="tab-content">
					<fieldset>
						<div class="col-sm-3">
							<label class="lblText">Ex-Showroom Price </label>
						</div>
						<div class="col-sm-3 no-padding">
							<input name="ex_showroom_price" type="text" maxlength="20" id="ex_showroom_price"class="form-control" value="<?php echo $rsData['ex_showroom_price']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3">
							<label class="lblText">Insurance (Normal / Nil Depriciation) </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="insurance_method" type="text" maxlength="20" id="insurance_method" class="form-control" value="<?php echo $rsData['insurance_method']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText">RTO Registration Tax & Fees </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="rto_fee" type="text" maxlength="20" id="rto_fee" class="form-control" value="<?php echo $rsData['rto_fee']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText">Taxi Proceeding Charges </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="taxi_charges" type="text" maxlength="20" id="taxi_charges" class="form-control" value="<?php echo $rsData['taxi_charges']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText">Accessories </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="accessories" type="text" maxlength="20" id="accessories" class="form-control" value="<?php echo $rsData['accessories']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText">AMC / Extended Warranty </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="amc" type="text" maxlength="20" id="amc" class="form-control"  value="<?php echo $rsData['amc']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText" ID ="lblExcPrice">Vehicle Exchange Price ( - )</label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="ex_price" type="text" maxlength="20" id="ex_price" class="form-control" value="<?php echo $rsData['ex_price']; ?>" readonly="" />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText"><b>Vehicle On Road Price </b> </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="onroad_price" type="text" maxlength="20" id="onroad_price" class="form-control" value="<?php echo $rsData['onroad_price']; ?>" readonly="" />
						</div>
					</fieldset>
                </div>

                <div id="tab-3" class="tab-content">
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText"><b>Offer Break-up</b></label>
					</div>
					<div class="col-sm-2">
						<label class="lblText"><b>Tata Contribution</b> </label>
					</div>
					<div class="col-sm-2">
						<label class="lblText"><b>SRT Contribution</b> </label>
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4 pull-left">
						<label class="lblText">Sch : Cash / Consumer Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="cosumer_offer" type="text" maxlength="20" id="cosumer_offer" class="form-control"  value="<?php echo $rsData['cosumer_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="cosumer_offer_srt" type="text" maxlength="20" id="cosumer_offer_srt" class="form-control" value="<?php echo $rsData['cosumer_offer_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Sch : Corporate / B2B Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="corporate_offer" type="text" maxlength="20" id="corporate_offer" class="form-control"  value="<?php echo $rsData['corporate_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="corporate_offer_srt" type="text" maxlength="20" id="corporate_offer_srt" class="form-control"  value="<?php echo $rsData['corporate_offer_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Sch : Exchange Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="exchange_offer" type="text" maxlength="20" id="exchange_offer" class="form-control"  value="<?php echo $rsData['exchange_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="exchange_offer_srt" type="text" maxlength="20" id="exchange_offer_srt" class="form-control"  value="<?php echo $rsData['exchange_offer_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Accessories Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="access_offer" type="text" maxlength="20" id="access_offer" class="form-control"  value="<?php echo $rsData['access_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="access_offer_srt" type="text" maxlength="20" id="access_offer_srt" class="form-control"  value="<?php echo $rsData['access_offer_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Insurance Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="insurance_offer" type="text" maxlength="20" id="insurance_offer" class="form-control"  value="<?php echo $rsData['insurance_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="insurance_offer_srt" type="text" maxlength="20" id="insurance_offer_srt" class="form-control"  value="<?php echo $rsData['insurance_offer_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Additional Discount </label>
					</div>
					<div class="col-sm-2">
						<input name="add_discount" type="text" maxlength="20" id="add_discount" class="form-control"  value="<?php echo $rsData['add_discount']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="add_discount_srt" type="text" maxlength="20" id="add_discount_srt" class="form-control"  value="<?php echo $rsData['add_discount_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">EDR</label>
					</div>
					<div class="col-sm-2">
						<input name="edr" type="text" maxlength="20" id="edr" class="form-control"  value="<?php echo $rsData['edr']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="edr_srt" type="text" maxlength="20" id="edr_srt" class="form-control"  value="<?php echo $rsData['edr_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<input name="other_offer_desc" type="text" maxlength="50" id="other_offer_desc" class="form-control" placeholder="Others"  value="<?php echo $rsData['other_offer_desc']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="other_contribution" type="text" maxlength="50" id="other_contribution" class="form-control"  value="<?php echo $rsData['other_contribution']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="other_contribution_srt" type="text" maxlength="50" id="other_contribution_srt" class="form-control" value="<?php echo $rsData['other_contribution_srt']; ?>" readonly="" />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText" ID ="lblTotal"><b>Total Net Offer </b> </label>
					</div>
					<div class="col-sm-2">
						<input name="total_tata" type="text" maxlength="50" id="total_tata" class="form-control"  value="<?php echo $rsData['total_tata']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="total_srt" type="text" maxlength="50" id="total_srt" class="form-control" value="<?php echo $rsData['total_srt']; ?>" readonly="" />
					</div>
				</fieldset> 
                </div>

                <div id="tab-4" class="tab-content">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <div class="col-sm-12 pull-left">
                                        <table class="table table-bordered table-striped"  >
                    <thead>
                      <tr>
                        <th width="10%">S.No</th>
                        <th width="15%">Receipt No.</th>
                        <th width="13%">Receipt Date</th>
                        <th width="15%">Amount</th>
                        <th width="17%">Mode of payment</th>
                        <th width="30%">Remarks</th> 
                        
                      </tr>
                    </thead>
                    <tbody>
					<?php
					$recptCnt=0;
					foreach($receipt_arr as $receipt_vals)
					{
						$recptCnt++;
					?>
					<tr>
                        <td width="10%"><?php echo $recptCnt;?></td>
                        <td width="15%"><?php echo $receipt_vals["receipt_no"];?></td>
                        <td width="13%"><?php echo $approval->convertDate($approval->purifyString($receipt_vals["receipt_date"]));?></td>
                        <td width="15%"><?php echo number_format($receipt_vals["receipt_amount"],2);?></td>
                        <td width="17%"><?php echo $receipt_vals["paymode_desc"];?></td>
                        <td width="30%"><?php echo $receipt_vals["receipt_remarks"];?></td>                        
                      </tr>
					  <?php }
					  if(!$recptCnt){
					   ?>
					   <tr>
                        <td colspan="6"  >No data found</td>                        
                      </tr>
					   <?php } ?>
                    </tbody>                    
                  </table>
                                    </div>
                                </fieldset>
                                 
                            </div>
                        </div>
                    </div>
                </div>
				<div class="col-md-12 no-padding"> 
				<div class="col-md-4 no-padding"> 
		  	<p>&nbsp;</p>
			<div class="form-group">
				
			</div>
		  </div>
		  </div>
		  </div>
        </section>
      </div>

</form>