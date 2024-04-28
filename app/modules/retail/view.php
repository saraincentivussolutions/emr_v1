<form role="form" id="frmRetailListMaster" class="fset">
<input type="hidden" name="hid_id" id="hid_id" />
	<fieldset>
  		<div class="col-md-6 ">
    		<label>Order No. <span style="color:#FF0000;">*</span></label>
    		<input type="text" class="form-control"  name="order_no" id="order_no" readonly="" maxlength="50"  />
  		</div>  
		<div class="col-md-6">
    		<label>Finance Amount </label>
    		<input type="text" class="form-control"  name="finance_amount" id="finance_amount" readonly=""    />
  		</div>
  	</fieldset>
  	<fieldset>
  		
  		<div class="col-md-6">
     		<label>Exchange Amount </label>
    		<input type="text" class="form-control"  name="exchange_amount" id="exchange_amount" readonly=""    />
  		</div>
		<div class="col-md-6">
    		<label>Offer Amount </label>
    		<input type="text" class="form-control"  name="offer_amount" id="offer_amount" readonly=""    />
  		</div>
  	</fieldset>
  		<!--<fieldset>
  		
   	<div class="col-md-6">
    		<label>Payment Received  </label><br />
			<div class="col-md-6 no-padding">
		    	<label class="lblText"><input type="radio" id="payment_received1" name="payment_received" value="1"> Yes </label>
			</div>
			<div class="col-md-6 no-padding">
	 			<label class="lblText"><input type="radio" id="payment_received2" name="payment_received" value="2"> No </label> 
			</div>
		</div> 
   	</fieldset>
 	 <fieldset>
  		<div class="col-md-6">
    		<label>Vehicle Allotted  </label><br />
			<div class="col-md-6 no-padding">
		    	<label class="lblText"><input type="radio" id="vehicle_allotted1" name="vehicle_allotted" value="1"> Yes </label>
			</div>
			<div class="col-md-6 no-padding">
	 			<label class="lblText"><input type="radio" id="vehicle_allotted2" name="vehicle_allotted" value="2"> No </label> 
			</div>
		</div>
  		<div class="col-md-6">
    		<label>Stock Type  </label><br />
			<div class="col-md-6 no-padding">
		    	<label class="lblText"><input type="radio" id="stock_type1" name="stock_type" value="1"> Open stock </label>
			</div>
			<div class="col-md-6 no-padding">
	 			<label class="lblText"><input type="radio" id="stock_type2" name="stock_type" value="2"> G stock </label> 
			</div>
		</div>
  	</fieldset>-->
    <fieldset>
    <div class="col-md-6">
    		<label>Stock Type  </label><br />
			<div class="col-md-6 no-padding">
		    	<label class="lblText"><input type="radio" id="stock_status1" name="stock_status" value="1" checked="checked" > Open stock </label>
			</div>
			<div class="col-md-6 no-padding">
	 			<label class="lblText"><input type="radio" id="stock_status2" name="stock_status" value="2"> G stock </label> 
			</div>
		</div>
  		<div class="col-md-6">
    		<label>Chasis No <span style="color:#FF0000;">*</span></label>
            <input type="hidden" class="form-control"  name="stock_chasis_id" id="stock_chasis_id"     />
    		<div style="width:80%; float:left;" ><input type="text" class="form-control"  name="stock_chasis_no" id="stock_chasis_no" readonly=""   /></div><div style="width:19%; float:right;" ><i class="fa fa-search" aria-hidden="true" onclick="getChasisNoFromStockList();" style="cursor:pointer" ></i></div>
  		</div>
   		
   	</fieldset>
    <fieldset>
  		<div class="col-md-6">
    		<label>Invoice No. <span style="color:#FF0000;">*</span></label>
    		<input type="text" class="form-control"  name="invoice_no" id="invoice_no" maxlength="30"    />
  		</div>
   		<div class="col-md-6">
    		<label>Invoice Date <span style="color:#FF0000;">*</span></label>
    		<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        	<input type="text" name="invoice_date" id="invoice_date"  class="form-control pull-right datepicker" maxlength="10" ></div>
  		</div>   
  	</fieldset>
  	<fieldset style="display:none;" >
  		<div class="col-md-6">
    		<label>RTO Approved </label><br />
			<div class="col-md-6 no-padding">
		    	<label class="lblText"><input type="radio" id="rto_approved1" name="rto_approved" value="1"> Yes </label>
			</div>
			<div class="col-md-6 no-padding">
	 			<label class="lblText"><input type="radio" id="rto_approved2" name="rto_approved" value="2"> No </label> 
			</div>
		</div>
   		<div class="col-md-6">
    		<label>RTO Date </label>
    		<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        	<input type="text" name="rto_date" id="rto_date"  class="form-control pull-right datepicker" maxlength="10" ></div>
  		</div>
   	</fieldset>
</form>
