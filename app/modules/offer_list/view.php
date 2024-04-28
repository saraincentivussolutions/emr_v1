<form role="form" id="frmOfferListMaster" class="fset">
  <input type="hidden" name="hid_id" id="hid_id" />
	<fieldset>
  <div class="col-md-6 ">
    <label>Parent Product Line <span style="color:#FF0000;">*</span></label>
    <select class="form-control" id="parent_productline_id" name="parent_productline_id" onchange="viewOffersListOnChangeParentProductLine()" >
    </select>
  </div>
   </fieldset>
  <fieldset>
   <div class="col-md-12">
    <label>Product Line</label>
   <div id="chk_multi_product_line" class="col-md-12 no-padding"></div>
  </div>
  </fieldset>
  <fieldset>
   <div class="col-md-12">
    <label>Product Colour</label>
   <div id="chk_multi_product_colour" class="col-md-12 no-padding"></div>
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>Vechile type <span style="color:#FF0000;">*</span></label><br />
	<div class="col-md-4 no-padding">
		<label class="lblText"><input type="radio" id="vechile_type1" name="vechile_type" value="1"> Own board </label>
	</div>
	<div class="col-md-4 no-padding">
		<label class="lblText"><input type="radio" id="vechile_type2" name="vechile_type" value="2"> Taxi </label> 
	</div>
    
    <div class="col-md-4 no-padding">
		<label class="lblText"><input type="radio" id="vechile_type3" name="vechile_type" value="3"> CSD </label> 
	</div>
  </div>
  <div class="col-md-6">
    <label>Offer date <span style="color:#FF0000;">*</span></label>
    <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" name="offer_date" id="offer_date"  class="form-control pull-right datepicker" maxlength="10" ></div>
  </div>
  </fieldset>
    <fieldset>

    <div class="col-md-12">
    <label>Registration type </label><br />
	<div class="col-md-4 no-padding">
		<label class="lblText"><input type="radio" id="registration_type1" name="registration_type" value="1"> Permonent</label>
	</div>
	<div class="col-md-4 no-padding">
		<label class="lblText"><input type="radio" id="registration_type2" name="registration_type" value="2"> Temporary </label> 
	</div>   

  </div>
  </fieldset>
  <fieldset>
 <div class="col-md-6">
    <label>Cash (TATA)</label>
   <input type="text" class="form-control"  name="cash_offer_tata" id="cash_offer_tata" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  <div class="col-md-6">
    <label>Cash (SRT)</label>
   <input type="text" class="form-control"  name="cash_offer_srt" id="cash_offer_srt" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>Exchange (TATA)</label>
   <input type="text" class="form-control"  name="exchange_offer_tata" id="exchange_offer_tata" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  <div class="col-md-6">
    <label>Exchange (SRT)</label>
   <input type="text" class="form-control"  name="exchange_offer_srt" id="exchange_offer_srt" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>Corporate (TATA)</label>
   <input type="text" class="form-control"  name="corporate_offer_tata" id="corporate_offer_tata" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  <div class="col-md-6">
    <label>Corporate (SRT)</label>
   <input type="text" class="form-control"  name="corporate_offer_srt" id="corporate_offer_srt" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>EDR (TATA)</label>
   <input type="text" class="form-control"  name="edr_offer_tata" id="edr_offer_tata" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  <div class="col-md-6">
    <label>EDR (SRT)</label>
   <input type="text" class="form-control"  name="edr_offer_srt" id="edr_offer_srt" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  />
  </div>
  </fieldset>
   
 <fieldset>
  <div class="col-md-6 divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="offer_list_status" name="offer_list_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
  </fieldset>
</form>
