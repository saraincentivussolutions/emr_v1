<form role="form" id="frmOrderStatusMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Order Status text <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="orderstatus_name" name="orderstatus_name" placeholder="Order status" maxlength="50" />
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="orderstatus_status" name="orderstatus_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>