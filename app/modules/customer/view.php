<form role="form" id="frmCustomerMaster">
  <input type="hidden" name="hid_id" id="hid_id" />    
  <div class="form-group">
    <label>Customer name</label>
    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer name"  maxlength="20">
  </div>
   <div class="form-group">
    <label>Mobile</label>
    <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" placeholder="Enter Mobile no."  maxlength="20">
  </div> 
   <div class="form-group">
    <label>Email</label>
    <input type="text" class="form-control" id="customer_email" name="customer_email" placeholder="Enter Email"  maxlength="20">
  </div> 
  <div class="form-group">
    <label>Ref. Employee</label>    
    <select class="form-control" name="refered_employee_id" id="refered_employee_id">
   </select>
  </div>
  
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="customer_status" name="customer_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>