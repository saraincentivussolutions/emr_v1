<form role="form" id="frmLoginMaster">
  <input type="hidden" name="hid_id" id="hid_id" /> 
    <div class="form-group">
    <label>Name <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="user_display_name" name="user_display_name" placeholder="Enter Name" maxlength="40"> 
  </div> 
  <div class="form-group">
    <label>Username</label>
    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Username"  maxlength="20">
  </div>
  <div class="form-group user_password_div">
    <label>Password</label>
    <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter Password"  maxlength="20">
  </div>
   <div class="form-group">
    <label>Sales team Access</label>    
    <div id="chk_multi_sales_team" class="col-md-12 no-padding"></div>
  </div>
   <div class="form-group">
    <label>User Role</label>    
    <select class="form-control" name="user_role_id" id="user_role_id">
   </select>
  </div>
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">In service</label>
    <select class="form-control" id="user_status" name="user_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>