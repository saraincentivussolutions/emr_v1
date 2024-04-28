<form role="form" id="frmFinancierMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Name <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="financier_name" name="financier_name" placeholder="Financier" maxlength="50" />
  </div> 
  <div class="form-group">
    <label>Contact person</label>
	<input type="text" class="form-control" id="financier_contact_name" name="financier_contact_name" placeholder="Contact name" maxlength="50" /> 
  </div>
  <div class="form-group">
    <label>Contact Mobile</label>
	<input type="text" class="form-control" id="financier_contact_mobile" name="financier_contact_mobile" placeholder="Contact mobile" maxlength="20" /> 
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="financier_status" name="financier_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>