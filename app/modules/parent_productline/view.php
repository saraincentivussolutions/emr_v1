<form role="form" id="frmParentProductLineMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Parent Product Line <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="parent_productline_name" name="parent_productline_name" placeholder="Enter Name" maxlength="50" />
  </div>
  
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="parent_productline_status" name="parent_productline_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>