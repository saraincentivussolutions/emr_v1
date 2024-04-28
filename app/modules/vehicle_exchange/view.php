<form role="form" id="frmVehicleExchangeListMaster" class="fset">
  <input type="hidden" name="hid_id" id="hid_id" />
  <fieldset>
  <div class="col-md-6 ">
    <label>Order No. <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control"  name="order_no" id="order_no" readonly="" maxlength="50"  />
  </div>
  
  <div class="col-md-6">
    <label>Exchange Type</label>
    <br />
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="exchange_type1" name="exchange_type" value="1">
      Claim </label>
    </div>
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="exchange_type2" name="exchange_type" value="2" checked="checked">
      Actual </label>
    </div>
  </div>
  
  
  </fieldset>
  
  <fieldset class="clsClaimType">
  <div class="col-md-6">
    <label>If claim</label>
  </div>
  <div class="col-md-3">TATA</div>
  <div class="col-md-3">SRT</div>
  </fieldset>
  <fieldset class="clsClaimType">
  <div class="col-md-6" >
    <label>Scheme Excahnge Bonus</label>
  </div>
  <div class="col-md-3">
    <input type="text" class="form-control"  name="scheme_bonus_tata" id="scheme_bonus_tata" readonly=""     />
  </div>
  <div class="col-md-3">
    <input type="text" class="form-control"  name="scheme_bonus_srt" id="scheme_bonus_srt"  readonly=""   />
  </div>
  </fieldset>
  <fieldset class="clsClaimType">
  <div class="col-md-6">
    <label>Actual Paid <span style="color:#FF0000;">*</span></label>
  </div>
  <div class="col-md-3">
    <input type="text" class="form-control"  name="actual_paid_tata" id="actual_paid_tata" onkeypress="return numberOnlyValidate(event);" maxlength="10"     />
  </div>
  <div class="col-md-3">
    <input type="text" class="form-control"  name="actual_paid_srt" id="actual_paid_srt" onkeypress="return numberOnlyValidate(event);" maxlength="10"     />
  </div>
  </fieldset>
  <fieldset class="clsActualType">
  <div class="col-md-6">
    <label>Exchange model <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control"  name="exchange_model" id="exchange_model"   maxlength="30"  />
  </div>
  </fieldset>
  <fieldset class="clsActualType"> 
  <div class="col-md-6"> 
  <label>Year Of Manufacture</label>
  <input type="text" class="form-control"  name="manufacture_year" id="manufacture_year" onkeypress="return numberOnlyValidate(event);" maxlength="4"     />
  </div>
  <div class="col-md-6">
    <label>Number of Owners </label>
    <input type="text" class="form-control"  name="numberof_owners" id="numberof_owners" onkeypress="return numberOnlyValidate(event);"   maxlength="2"  />
  </div>
  </fieldset> 
  <fieldset class="clsActualType">
  <div class="col-md-6">
    <label>Running Kms </label>
    <input type="text" class="form-control"  name="running_km" id="running_km" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"  />
  </div>
  <div class="col-md-6">
    <label>Registration Number </label>
    <input type="text" class="form-control"  name="registration_number" id="registration_number" maxlength="20"  />
  </div>
  </fieldset >
  <fieldset class="clsActualType">
  <div class="col-md-6">
    <label>Exchange Price <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control"  name="exchange_price" id="exchange_price" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="12" style="text-align:right; "   />
  </div>
  <div class="col-md-6">
    <label>Name of current owner is different</label>
    <br />
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="owner_different1" name="owner_different" value="1">
      Yes </label>
    </div>
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="owner_different2" name="owner_different" value="2" checked="checked">
      No </label>
    </div>
  </div>
  </fieldset>
  <fieldset  class="clsActualType">
  <div class="col-md-6">
    <label>Available</label>
    <br />
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input name="chk_available[]" type="checkbox" value="rc_book">
      RC Book </label>
    </div>
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input name="chk_available[]" type="checkbox" value="insurance">
      Insurance</label>
    </div>
  </div>
  <div class="col-md-6 clsOwnerType">
    <label>If yes, Name of Owner</label>
    <br />
    <input type="text" class="form-control"  name="owner_name" id="owner_name"   maxlength="30"  />
  </div>
  </fieldset>
  <fieldset class="clsActualType">
  <div class="col-md-6">
    <label>Finance previous</label>
    <br />
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="finance_previous_status1" name="finance_previous_status" value="1">
      Yes </label>
    </div>
    <div class="col-md-6 no-padding">
      <label class="lblText">
      <input type="radio" id="finance_previous_status2" name="finance_previous_status" value="2">
      No </label>
    </div>
  </div>
  <div class="col-md-3 clsOwnerType">
    <label>Relationship</label>
    <br />
    <select class="form-control" id="owner_relationship" name="owner_relationship">
      <option value="0">Select</option>
      <option value="1">Father</option>
      <option value="2">Mother</option>
      <option value="3">Wife</option>
      <option value="4">Brother</option>
      <option value="5">Sister</option>
    </select>
  </div>
  <div class="col-md-3 clsOwnerType">
    <label>Proff collected</label>
    <br />
    <input type="checkbox" id="proff_collected" name="proff_collected" value="1">
  </div>
  </fieldset>
  <fieldset class="clsFinbankAmnt clsActualType" >  
  <div class="col-md-6">
    <label>Financier </label>
    <select class="form-control" id="finance_previous_financier" name="finance_previous_financier">
    </select>
  </div>
  <div class="col-md-6">
    <label>Pre Closing amount</label>
    <input type="text" class="form-control"  name="finance_previous_loanamnt" id="finance_previous_loanamnt" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10" style="text-align:right; "   />
  </div>
  </fieldset>
  <fieldset>
  <div class="col-md-6" style="display:none;">
    <label>Actual Value </label>
    <input type="text" class="form-control"  name="actual_value" id="actual_value" onkeypress="return numberOnlyValidate(event);" maxlength="10"     />
  </div>
  
  </fieldset>
</form>
