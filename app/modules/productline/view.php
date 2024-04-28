<form role="form" id="frmProductLineMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Product Line <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="productline_name" name="productline_name" placeholder="Enter Name" maxlength="50" />
  </div>
  <div class="form-group">
    <label>Parent Product Line <span style="color:#FF0000;">*</span></label>
    <select class="form-control" id="parent_productline_id" name="parent_productline_id">
    </select>
  </div>
   <div class="form-group">
    <label>VC# <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="productline_vc" name="productline_vc" placeholder="Enter VC#" maxlength="50" />
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="productline_status" name="productline_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>