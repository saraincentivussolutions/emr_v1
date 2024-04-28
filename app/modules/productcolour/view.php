<form role="form" id="frmProductColourMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Product Colour <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="productcolour_name" name="productcolour_name" placeholder="Enter Colour" maxlength="50" />
  </div>
  <div class="form-group multiproductline">
    <label>Parent Product Line </label>
    <div id="chk_multi_product_line" class="col-md-12 no-padding"></div>
  </div>
   <div class="form-group">
    <label>VCH </label>
    <input type="text" class="form-control" id="productcolour_vc" name="productcolour_vc" placeholder="Enter VCH" maxlength="50" />
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="productcolour_status" name="productcolour_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>