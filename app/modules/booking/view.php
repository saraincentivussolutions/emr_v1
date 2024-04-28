<?php 
	include  dirname(realpath('..')).'/common/class.common.php'; 
	include 'class.script.php'; 
	$booking = new booking(); 
	
	$action=$_POST["action"];
	$postArr=$_POST;  
	
	$booking_id=$booking->purifyInsertString($postArr["id"]);
	$get_arr = $booking->getSingleView($postArr);
	$rsData = $get_arr['rsData'];
	$combo_list = $get_arr['combo_list'];
	
	$receipt_arr = $booking->getBookingReceiptDetailsView($postArr["id"]);
	
	 
	
	if(!$rsData['booking_transaction_id'])
	{
		$rsData['corporate_type']="2";
		$rsData['ex_vechicle']="2";
		$rsData['vehicle_type']="1";
		$rsData['finance']="1";
		$rsData['insurance_type']="1";
		$rsData['insurance_detail']="1";
		$rsData['registration_type']="1";
		
	} 
	 
	$acc_dis=""; $acc_read="";
	if($rsData['off_acc_approved_status'] or $rsData['off_admin_approved_status'])
	{
		$acc_dis="disabled"; $acc_read="readonly";
	}
	$fin_dis=""; $fin_read="";
	if($rsData['finance_process_status']==10)
	{
		$fin_dis="disabled"; $fin_read="readonly";
	}
	
?>
<form role="form" id="frmBookingMaster">
  <input type="hidden" name="hid_id" id="hid_id"  value="<?php echo $rsData['booking_transaction_id']; ?>"/>
  <div class="content-wcommon"> 
         <section class="content-header">

		  <h1>
             Booking Entry 
			 <div class="col-md-4 no-padding text-right pull-right">				
				<button class="btn btn-primary btn-sm"  onclick="CreateUpdateBookingMasterSave()" style="margin-right:10px;" type="button"  ><i class="fa fa-floppy-o"></i> Submit</button>		  	
				<button class="btn btn-warning btn-sm" type="button" onclick="BookEntryBack()" ><i class="fa fa-times"></i> Back to list</button>					
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
				<li class="tab-link current" data-tab="tab-1">Customer Details</li>
				<li class="tab-link" data-tab="tab-2">Booking Status</li>
				<li class="tab-link" data-tab="tab-3">Vehicle Quotation</li>
				<li class="tab-link" data-tab="tab-4">Offer Details</li>
				<li class="tab-link" data-tab="tab-5">Customer Payments & Receipts</li>
			</ul>
                <div id="tab-1" class="tab-content current">
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Order No <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<input name="order_no" type="text" maxlength="50" id="order_no"  class="form-control" placeholder="Order Number" value="<?php echo $rsData['order_no']; ?>"/>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Order Date <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
                        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input name="order_date" type="text" maxlength="20" id="order_date" class="form-control pull-right datepicker" placeholder="Order Date" value="<?php echo $rsData['order_date']; ?>"/></div>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Order Status <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="order_status" id="order_status" class="form-control">
								<option value="0">Select</option>
								<?php foreach($combo_list['order_statuslist'] as $rec)
								{
									$select = ($rsData['order_status']==$rec['orderstatus_id'])?'selected':'';
									echo '<option value="'.$rec['orderstatus_id'].'" '.$select.'>'.$rec['orderstatus_name'].'</option>';
								}?>
							</select>
						</div>
					</fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Sales Team <font color="red"><b> * </b></font></label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <!-- Praga Data load from master-->
										<select name="sales_team" id="sales_team" class="form-control" onchange="viewBookingEntryOnChangeSalesTeam();" >
											<option value="0">Select</option>
											<?php foreach($combo_list['sales_team'] as $rec)
												{
													$select = ($rsData['sales_team']==$rec['sales_team_id'])?'selected':'';
													echo '<option value="'.$rec['sales_team_id'].'" '.$select.'>'.$rec['sales_team_name'].'</option>';
												}?>
										</select>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Customer Advisor <font color="red"><b> * </b></font></label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <!-- Praga Data load from master-->
										<select name="customer_advisor" id="customer_advisor" class="form-control">
											<option value="0">Select</option>
											<?php foreach($combo_list['customer_advisorlist'] as $rec)
												{
													$select = ($rsData['customer_advisor']==$rec['employee_id'])?'selected':'';
													echo '<option value="'.$rec['employee_id'].'" atr_salesteam="'.$rec['sales_team_id'].'" '.$select.'>'.$rec['employee_name'].'</option>';
												}?>
										</select>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Source of Contact <font color="red"><b> * </b></font></label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <!-- Praga Data load from master-->
										<select name="source_contact" id="source_contact" class="form-control">
											<option value="0">Select</option>
											<?php foreach($combo_list['source_of_contactlist'] as $rec)
												{
													$select = ($rsData['source_contact']==$rec['source_of_contact_id'])?'selected':'';
													echo '<option value="'.$rec['source_of_contact_id'].'" '.$select.'>'.$rec['source_of_contact_name'].'</option>';
												}?>
										</select>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Customer Name <font color="red"><b> * </b></font></label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="customer_name" type="text" maxlength="100" id="customer_name" class="form-control" placeholder="Customer Name" value="<?php echo $rsData['customer_name']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Mobile<font color="red"><b> * </b></font> </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="customer_mobile" type="text" maxlength="50" id="customer_mobile" class="form-control" placeholder="Mobile" value="<?php echo $rsData['customer_mobile']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">PAN </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="customer_pan" type="text" maxlength="50" id="customer_pan" class="form-control" placeholder="PAN Number"  value="<?php echo $rsData['customer_pan']; ?>"/>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">DOB </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input name="dob" type="text" maxlength="100" id="dob" class="form-control pull-right datepicker" placeholder="DOB" value="<?php echo $rsData['dob']; ?>"/></div>

                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Nominee Name </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="nominee_name" type="text" maxlength="100" id="nominee_name" class="form-control" placeholder="Nominee Name"  value="<?php echo $rsData['nominee_name']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Nominee DOB </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
									<div class="col-sm-8 pull-left no-padding">
                                    <div class="input-group date" style="width:130px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input name="nominee_dob" type="text" maxlength="50" id="nominee_dob" class="form-control pull-right datepicker" placeholder="Nom. DOB"  value="<?php echo $rsData['nominee_dob']; ?>"/></div>   
                                    </div>
									<div class="col-sm-4 pull-left no-padding"> <input name="nominee_age" type="text" maxlength="3" id="nominee_age" class="form-control pull-right " placeholder="Age"  value="<?php echo $rsData['nominee_age']; ?>" onkeypress="return numberOnlyValidate(event);" /> 
                                    </div> 
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">City </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="city" type="text" maxlength="100" id="city" class="form-control" placeholder="City" value="<?php echo $rsData['city']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Area </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="area" type="text" maxlength="100" id="area" class="form-control" placeholder="Area" value="<?php echo $rsData['area']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">PinCode </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="pincode" type="text" maxlength="100" id="pincode" class="form-control" placeholder="Pincode" value="<?php echo $rsData['pincode']; ?>"/>
                                    </div>
                                </fieldset>
                                <fieldset>
								<div class="col-sm-2 pull-left">
                                        <label class="lblText">Corporate </label>
                                    </div>
                                    <div class="col-sm-2 no-padding">
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="corporate_type1" type="radio" name="corporate_type" value="1" <?php echo ($rsData['corporate_type']==1)?'checked':''; ?>/> Yes</label>
										</div>
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="corporate_type2" type="radio" name="corporate_type" value="2" <?php echo ($rsData['corporate_type']==2)?'checked':''; ?>/> No</label>
										</div>
                                    </div>
									
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Corporate Name </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="corporate_name" type="text" maxlength="100" id="corporate_name" class="form-control" placeholder="Corporate Name" value="<?php echo $rsData['corporate_name']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Exchange Vehicle </label>
                                    </div>
                                    <div class="col-sm-2 no-padding">
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="ex_vechicle1" type="radio" name="ex_vechicle" value="1" <?php echo ($rsData['ex_vechicle']==1)?'checked':''; ?>/> Yes</label>
										</div>
										<div class="col-sm-6 no-padding">
                                        	<label class="lblText"><input id="ex_vechicle2" type="radio" name="ex_vechicle" value="2" <?php echo ($rsData['ex_vechicle']==2)?'checked':''; ?>/> No</label>
										</div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="col-sm-2">
                                        <label class="lblText">Customer Address</label>
                                    </div>
                                    <div class="col-sm-10 no-padding">
                                        <input name="customer_address" type="text" maxlength="100" id="customer_address" class="form-control" placeholder="Customer Address" value="<?php echo $rsData['customer_address']; ?>"/>
                                    </div>
                                </fieldset>
								
								<fieldset>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Email  </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="customer_email" type="text" maxlength="100" id="customer_email" class="form-control" placeholder="Email" value="<?php echo $rsData['customer_email']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <label class="lblText">Alternate mobile </label>
                                    </div>
                                    <div class="col-sm-2 pull-left no-padding">
                                        <input name="customer_alternate_no" type="text" maxlength="100" id="customer_alternate_no" class="form-control" placeholder="Alternate mobile" value="<?php echo $rsData['customer_alternate_no']; ?>"/>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                     
                                </fieldset>
                </div>

                <div id="tab-2" class="tab-content">
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Parent Product Line <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="parent_product_line" id="parent_product_line" class="form-control" onchange="viewBookingEntryOnChangeParentProd()" >
								<option value="0">Select</option>
								<?php foreach($combo_list['parent_product_line'] as $rec)
								{
									$select = ($rsData['parent_product_line']==$rec['parent_productline_id'])?'selected':'';
									echo '<option value="'.$rec['parent_productline_id'].'" '.$select.'>'.$rec['parent_productline_name'].'</option>';
								}?>
							</select>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Product Line <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="product_line" id="product_line" class="form-control" onchange="bkGetQuotPrice();" >
								<option value="0">Select</option>
								<?php foreach($combo_list['productline'] as $rec)
								{
									$select = ($rsData['product_line']==$rec['productline_id'])?'selected':'';
									echo '<option value="'.$rec['productline_id'].'" parprdid="'.$rec['parent_productline_id'].'" '.$select.'>'.$rec['productline_name'].'</option>';
								}?>
							</select>
						</div>
						<div class="col-sm-4 pull-left">
							<div class="col-sm-4 no-padding">
								<label class="lblText">Vehicle Type </label>
							</div>
							<div class="col-sm-8">
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="vehicle_type1" type="radio" name="vehicle_type" value="1"  <?php echo ($rsData['vehicle_type']==1)?'checked':''; ?>/> Own&nbsp;Board</label>
								</div>
								<div class="col-sm-3 no-padding">	
									<label class="lblText"><input id="vehicle_type2" type="radio" name="vehicle_type" value="2"  <?php echo ($rsData['vehicle_type']==2)?'checked':''; ?>/> Taxi</label>
								</div>
								<div class="col-sm-3 no-padding">	
									<label class="lblText"><input id="vehicle_type3" type="radio" name="vehicle_type" value="3"  <?php echo ($rsData['vehicle_type']==3)?'checked':''; ?>/> CSD</label>
								</div>
							</div>
						</div>						
					</fieldset>
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Product Colour 1 &nbsp; <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="product_color_primary" id="product_color_primary" class="form-control" onchange="bkGetQuotPrice();" >
								<option value="0">Select</option>
								<?php foreach($combo_list['productcolour1'] as $rec)
								{
									$select = ($rsData['product_color_primary']==$rec['productcolour_id'])?'selected':'';
									echo '<option value="'.$rec['productcolour_id'].'"  parprdids="'.$rec['parent_productline_ids'].'" '.$select.'>'.$rec['productcolour_name'].'</option>';
								}?>
							</select>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Product Colour 2 </label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="product_color_secondary" id="product_color_secondary" class="form-control">
								<option value="0">Select</option>
								<?php foreach($combo_list['productcolour2'] as $rec)
								{
									$select = ($rsData['product_color_secondary']==$rec['productcolour_id'])?'selected':'';
									echo '<option value="'.$rec['productcolour_id'].'"  parprdids="'.$rec['parent_productline_ids'].'" '.$select.'>'.$rec['productcolour_name'].'</option>';
								}?>
							</select>
						</div>
						<div class="col-sm-4 pull-left">
							<div class="col-sm-4 no-padding">
								<label class="lblText">Finance </label>
							</div>
							<div class="col-sm-8">
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="finance1" type="radio" name="finance" value="1" <?php echo ($rsData['finance']==1)?'checked':''; ?>/> In House</label>
								</div>
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="finance1" type="radio" name="finance" value="2" <?php echo ($rsData['finance']==2)?'checked':''; ?>/> Customer</label>
								</div>
							</div>
						</div>						
					</fieldset>
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Product Colour 3</label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<!-- Praga Data load from master-->
							<select name="product_color_additional" id="product_color_additional" class="form-control">
								<option value="0">Select</option>
								<?php foreach($combo_list['productcolour3'] as $rec)
								{
									$select = ($rsData['product_color_additional']==$rec['productcolour_id'])?'selected':'';
									echo '<option value="'.$rec['productcolour_id'].'"  parprdids="'.$rec['parent_productline_ids'].'" '.$select.'>'.$rec['productcolour_name'].'</option>';
								}?>
							</select>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Opportunity Id </label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
							<input name="opportunity_id" type="text" maxlength="100" id="opportunity_id" class="form-control" placeholder="Opportunity Id"  value="<?php echo $rsData['opportunity_id']; ?>"/>
						</div>
						<div class="col-sm-4 pull-left">
							<div class="col-sm-4 no-padding">
								<label class="lblText">Insurance Type  </label>
							</div>
							<div class="col-sm-8">
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="insurance_type1" type="radio" name="insurance_type" value="1" <?php echo ($rsData['insurance_type']==1)?'checked':''; ?>/> In House</label>
								</div>
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="insurance_type2" type="radio" name="insurance_type" value="2" <?php echo ($rsData['insurance_type']==2)?'checked':''; ?>/> Customer</label>
								</div>
							</div>
						</div>		
					</fieldset>
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">EDD <font color="red"><b> * </b></font></label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
                        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input name="edd" type="text" maxlength="100" id="edd" class="form-control pull-right datepicker" placeholder="EDD" value="<?php echo $rsData['edd']; ?>"/></div>
						</div>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Revised EDD </label>
						</div>
						<div class="col-sm-2 pull-left no-padding">
                        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input name="revised_edd" type="text" maxlength="100" id="revised_edd" class="form-control pull-right datepicker" placeholder="Revised EDD" style="width:100%;"  value="<?php echo $rsData['revised_edd']; ?>"/>
                            </div>
						</div>
						<div class="col-sm-4 pull-left clsBkInsShw" >
							<div class="col-sm-4 no-padding">
								<label class="lblText">Insurance Detail</label>
							</div>
							<div class="col-sm-8">
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="insurance_detail1" type="radio" name="insurance_detail" value="1" <?php echo ($rsData['insurance_detail']==1)?'checked':''; ?>/> Normal</label>
								</div>
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="insurance_detail2" type="radio" name="insurance_detail" value="2" <?php echo ($rsData['insurance_detail']==2)?'checked':''; ?>/> Nil Depriciation</label>
								</div>
							</div>
						</div>							
					</fieldset>
					<fieldset>
						<div class="col-sm-2 pull-left">
							<label class="lblText">Remarks </label>
						</div>
						<div class="col-sm-6 pull-left no-padding">
							<input name="remarks" type="text" maxlength="200" id="remarks" class="form-control" placeholder="Remarks" value="<?php echo $rsData['remarks']; ?>"/>
						</div>
						<div class="col-sm-4 pull-left">
							<div class="col-sm-4 no-padding">
								<label class="lblText">Registration Type</label>
							</div>
							<div class="col-sm-8">
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="registration_type1" type="radio" name="registration_type" value="1"  <?php echo ($rsData['registration_type']==1)?'checked':''; ?>/> Permanent</label>
								</div>
								<div class="col-sm-6 no-padding">	
									<label class="lblText"><input id="registration_type2" type="radio" name="registration_type" value="2"  <?php echo ($rsData['registration_type']==2)?'checked':''; ?>/> Temporary</label>
								</div>
							</div>
						</div>								
					</fieldset>
                </div>

                <div id="tab-3" class="tab-content">
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
							<input name="accessories" type="text" id="accessories" class="form-control" value="<?php echo $rsData['accessories']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalPriceQuoteTotal();" <?php echo $fin_read;?> />
						</div>
					</fieldset>
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText">AMC / Extended Warranty </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="amc" type="text"  id="amc" class="form-control"  value="<?php echo $rsData['amc']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalPriceQuoteTotal();"  <?php echo $fin_read;?> />
						</div>
					</fieldset>
					<!--<fieldset class="clsBkExchange">
						<div class="col-sm-3 pull-left">
							<label class="lblText" ID ="lblExcPrice">Vehicle Exchange Price ( - )</label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="ex_price" type="text"  id="ex_price" class="form-control" value="<?php //echo $rsData['ex_price']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" readonly=""  />
						</div>
					</fieldset>--> <input name="ex_price" type="hidden"  id="ex_price" class="form-control" value="" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="1" readonly=""  />
					<fieldset>
						<div class="col-sm-3 pull-left">
							<label class="lblText"><b>Vehicle On Road Price </b> </label>
						</div>
						<div class="col-sm-3 pull-left no-padding">
							<input name="onroad_price" type="text" maxlength="20" id="onroad_price" class="form-control" value="<?php echo $rsData['onroad_price']; ?>" readonly="" />
						</div>
					</fieldset>
                </div>

                <div id="tab-4" class="tab-content">
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
					<div class="col-sm-2">
						<label class="lblText"><b>SRT Addition</b> </label>
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
					<div class="col-sm-2">
						<input name="cosumer_offer_srt_addition" type="text"   id="cosumer_offer_srt_addition" class="form-control" value="<?php echo $rsData['cosumer_offer_srt_addition']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?> />
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
					<div class="col-sm-2">
						<input name="corporate_offer_srt_addition" type="text"  id="corporate_offer_srt_addition" class="form-control"  value="<?php echo $rsData['corporate_offer_srt_addition']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>   />
					</div>
				</fieldset>
				<fieldset class="clsBkExchange">
					<div class="col-sm-4">
						<label class="lblText">Sch : Exchange Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="exchange_offer" type="text" maxlength="20" id="exchange_offer" class="form-control"  value="<?php echo $rsData['exchange_offer']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="exchange_offer_srt" type="text" maxlength="20" id="exchange_offer_srt" class="form-control"  value="<?php echo $rsData['exchange_offer_srt']; ?>" readonly="" />
					</div>
					<div class="col-sm-2">
						<input name="exchange_offer_srt_addition" type="text"  id="exchange_offer_srt_addition" class="form-control"  value="<?php echo $rsData['exchange_offer_srt_addition']; ?>" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?> />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Accessories Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="access_offer" type="text"  id="access_offer" class="form-control"  value="<?php echo $rsData['access_offer']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="access_offer_srt" type="text"  id="access_offer_srt" class="form-control"  value="<?php echo $rsData['access_offer_srt']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()"  <?php echo $acc_read;?> />
					</div>
					<div class="col-sm-2">
						<input name="access_offer_srt_addition" type="text"  id="access_offer_srt_addition" class="form-control"  value="<?php echo $rsData['access_offer_srt_addition']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Insurance Offer </label>
					</div>
					<div class="col-sm-2">
						<input name="insurance_offer" type="text"  id="insurance_offer" class="form-control"  value="<?php echo $rsData['insurance_offer']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="insurance_offer_srt" type="text"  id="insurance_offer_srt" class="form-control"  value="<?php echo $rsData['insurance_offer_srt']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" onkeyup="bkcalOfferPriceTotal()" maxlength="10" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="insurance_offer_srt_addition" type="text"  id="insurance_offer_srt_addition" class="form-control"  value="<?php echo $rsData['insurance_offer_srt_addition']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" onkeyup="bkcalOfferPriceTotal()" maxlength="10"  <?php echo $acc_read;?> />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">Additional Discount </label>
					</div>
					<div class="col-sm-2">
						<input name="add_discount" type="text"  id="add_discount" class="form-control"  value="<?php echo $rsData['add_discount']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?> />
					</div>
					<div class="col-sm-2">
						<input name="add_discount_srt" type="text"  id="add_discount_srt" class="form-control"  value="<?php echo $rsData['add_discount_srt']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="add_discount_srt_addition" type="text"  id="add_discount_srt_addition" class="form-control"  value="<?php echo $rsData['add_discount_srt_addition']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText">EDR</label>
					</div>
					<div class="col-sm-2">
						<input name="edr" type="text"   id="edr" class="form-control"  value="<?php echo $rsData['edr']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="edr_srt" type="text"  id="edr_srt" class="form-control"  value="<?php echo $rsData['edr_srt']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="edr_srt_addition" type="text"  id="edr_srt_addition" class="form-control"  value="<?php echo $rsData['edr_srt_addition']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
				</fieldset>
				<fieldset>
					<div class="col-sm-4">
						<input name="other_offer_desc" type="text" maxlength="50" id="other_offer_desc" class="form-control" placeholder="Others"  value="<?php echo $rsData['other_offer_desc']; ?>"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?> />
					</div>
					<div class="col-sm-2">
						<input name="other_contribution" type="text"   id="other_contribution" class="form-control"  value="<?php echo $rsData['other_contribution']; ?>"   onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?>  />
					</div>
					<div class="col-sm-2">
						<input name="other_contribution_srt" type="text"   id="other_contribution_srt" class="form-control" value="<?php echo $rsData['other_contribution_srt']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal()" <?php echo $acc_read;?> />
					</div>
					<div class="col-sm-2">
						<input name="other_contribution_srt_addition" type="text"   id="other_contribution_srt_addition" class="form-control" value="<?php echo $rsData['other_contribution_srt_addition']; ?>"  onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  onkeyup="bkcalOfferPriceTotal() " <?php echo $acc_read;?> />
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
					<div class="col-sm-2">
						<input name="total_srt_addition" type="text" maxlength="50" id="total_srt_addition" class="form-control" value="<?php echo $rsData['total_srt_addition']; ?>" readonly="" />
					</div>
				</fieldset> 
				<fieldset>
					<div class="col-sm-4">
						<label class="lblText" ID ="lblTotal">Offer remarks </label>
					</div>
					<div class="col-sm-6">
						<textarea name="offer_remarks"  id="offer_remarks" class="form-control" style="height:60px;" ><?php echo $rsData['offer_remarks']; ?></textarea> 
					</div>
					 
					 
				</fieldset>
                </div>

                <div id="tab-5" class="tab-content">
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
                        <td width="13%"><?php echo $booking->convertDate($booking->purifyString($receipt_vals["receipt_date"]));?></td>
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
		  </div> 
        </section> 
      </div>

</form>