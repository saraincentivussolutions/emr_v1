<form role="form" id="frmPriceListMaster" class="fset">
  <input type="hidden" name="hid_id" id="hid_id" />
  <fieldset>
  <div class="col-md-6 ">
    <label>Parent Product Line <span style="color:#FF0000;">*</span></label>
    <select class="form-control" id="parent_productline_id" name="parent_productline_id" onchange="viewPriceListOnChangeParentProductLine()" >
    </select>
  </div>
  <div class="col-md-6">
    <label>Product Line <span style="color:#FF0000;">*</span></label>
    <select class="form-control"  id="productline_id" name="productline_id">
    </select>
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
    <label>Price date <span style="color:#FF0000;">*</span></label>
    <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" name="price_date" id="price_date"  class="form-control pull-right datepicker" maxlength="10" ></div>
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
    <label>Ex-showroom</label>
   <input type="text" class="form-control"  name="ex_showroom_amount" id="ex_showroom_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()"  />
  </div>
  <div class="col-md-6">
    <label>Insurance</label>
   <input type="text" class="form-control"  name="insurance_amount" id="insurance_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()" />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>Life tax, road tax etc.</label>
   <input type="text" class="form-control"  name="tax_amount" id="tax_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()"  />
  </div>
  <div class="col-md-6">
    <label>Basic Accessories</label>
   <input type="text" class="form-control"  name="accessories_amount" id="accessories_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()"  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>TAXI proceeding ChG</label>
   <input type="text" class="form-control"  name="taxi_chg_amount" id="taxi_chg_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; "  onkeyup="pricelistCalcTotal()" />
  </div>
  <div class="col-md-6">
    <label>EW (2+1 yrs)</label>
   <input type="text" class="form-control"  name="ew_amount" id="ew_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()"  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>On road price</label>
   <input type="text" class="form-control"  name="onroad_amount" id="onroad_amount"  style="text-align:right; " readonly=""  />
  </div>
  <div class="col-md-6">
    <label>Nill depriciation</label>
   <input type="text" class="form-control"  name="nill_depriciation_amount" id="nill_depriciation_amount" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8" style="text-align:right; " onkeyup="pricelistCalcTotal()"  />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6">
    <label>On road price inc. Nill depriciation</label>
   <input type="text" class="form-control"  name="onroad_nill_amount" id="onroad_nill_amount"  style="text-align:right; " readonly=""  />
  </div>
   
 
  <div class="col-md-6 divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="price_list_status" name="price_list_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
   
  </div>
  </fieldset>
   
</form>
