<form role="form" id="frmProductsMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Product Name</label>
    <input type="text" class="form-control" id="products_name" name="products_name" placeholder="Enter Name" maxlength="50" />
  </div>
   <div class="form-group">
    <label>Price</label>
    <input type="text" class="form-control" id="products_price" name="products_price" placeholder="Enter Amount" maxlength="8" onkeypress="return ValidateNumberKeyPress(this, event);" />
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="products_status" name="products_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>