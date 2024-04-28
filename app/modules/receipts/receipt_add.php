<form role="form" id="frmReceiptDetailsMaster" class="fset">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_booking_id" id="hid_booking_id" />
  <fieldset>
  <div class="col-md-6 ">
    <label >Order No &nbsp; <font color="red"><b> * </b></font></label>
    <input name="order_no" type="text" maxlength="50" id="order_no" class="normaltext  form-control" disabled="">
  </div>
  <div class="col-md-6 clsHideRcptNo" style="display:none;" >
    <label >Receipt Number &nbsp; <font color="red"><b> * </b></font></label>
    <input name="receipt_no" type="text" maxlength="50" id="receipt_no" class="normaltext form-control" readonly="readonly" >
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6 ">
    <label >Entry Date &nbsp; </label>
	<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
	<input name="entry_date" type="text" maxlength="20" id="entry_date" class="form-control pull-right datepicker" /></div> 
  </div>
   <div class="col-md-6 ">
    <label >Entered By &nbsp; </label>
    <input name="entry_by" type="text" maxlength="50" id="entry_by" class="normaltext form-control">
  </div>
  </fieldset>
  <fieldset>
  
  <div class="col-md-6">
    <label >Receipt Date <font color="red"><b> * </b></font></label>
	<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
	<input name="receipt_date" type="text" maxlength="20" id="receipt_date" class="form-control pull-right datepicker" /></div> 
  </div>
  <div  class="col-md-6 ">
    	<label >Amount <font color="red"><b> * </b></font></label>
    	<input name="receipt_amount" type="text"   id="receipt_amount" class="form-control" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10">
  	</div>
  </fieldset>
  <fieldset>
  	<div class="col-md-6 ">
		<label >Payment Mode &nbsp; </label><br />
		<div class="col-md-6 no-padding">
			<label class="lblText"><input id="payment_mode1" type="radio" name="payment_mode" checked="checked" value="1"> Cash </label>
		</div>
		<div class="col-md-6 no-padding">
			<label class="lblText"><input id="payment_mode2" type="radio" name="payment_mode" value="2"> Bank </label> 
		</div>
	</div>
	 <div class="col-md-6 clsBankDd">
		<label >Type &nbsp; </label><br />
		<div class="col-md-6 no-padding">
			<label class="lblText"><input id="chque_dd_type1" type="radio" name="chque_dd_type" checked="checked" value="1"> Cheque </label>
		</div>
		<div class="col-md-6 no-padding">
			<label class="lblText"><input id="chque_dd_type2" type="radio" name="chque_dd_type" value="2"> DD </label> 
		</div>
	</div>
  </fieldset>
   <fieldset class="clsBankDd" >
  	 <div  class="col-md-6 ">
    	<label >Bank name </label>
    	<input name="bank_name" type="text"   id="bank_name" class="form-control"   maxlength="50">
  	</div>
	 <div  class="col-md-6 ">
    	<label >Cheque/DD No. </label>
    	<input name="cheque_no" type="text"   id="cheque_no" class="form-control"   maxlength="20">
  	</div>
  </fieldset>
  <fieldset>
  <div  class="col-md-12 ">
    <label >Remarks &nbsp; </label>
    <input name="receipt_remarks" type="text" maxlength="150" id="receipt_remarks" class="form-control">
  </div>
  </fieldset>
</form>
