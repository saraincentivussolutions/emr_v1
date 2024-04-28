<form role="form" id="frmSourceOfContactMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Name <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="source_of_contact_name" name="source_of_contact_name" placeholder="Source of contact" maxlength="50" />
  </div> 
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="source_of_contact_status" name="source_of_contact_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>