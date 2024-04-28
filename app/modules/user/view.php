<form role="form" id="frmUserMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Advisor Id <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="employee_code" name="employee_code" placeholder="Enter Code" maxlength="20"> 
  </div>
    <div class="form-group">
    <label>Advisor Name <span style="color:#FF0000;">*</span></label>
    <input type="text" class="form-control" id="user_display_name" name="user_display_name" placeholder="Enter Name" maxlength="40"> 
  </div>  
  <div class="form-group">
    <label>Mobile</label>
    <input type="text" class="form-control" id="employee_mobile" name="employee_mobile" placeholder="Enter Mobile no."  maxlength="20">
  </div>  
  <div class="form-group">
    <label>Sales team</label>    
    <select class="form-control" name="sales_team_id" id="sales_team_id">
   </select>
  </div>
<!--   <div class="form-group">
    <label>Sales team Access</label>    
    <div id="chk_multi_sales_team" class="col-md-12 no-padding"></div>
  </div>
   <div class="form-group">
    <label>User Role</label>    
    <select class="form-control" name="user_role_id" id="user_role_id">
   </select>
  </div>-->
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">In service</label>
    <select class="form-control" id="user_status" name="user_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>