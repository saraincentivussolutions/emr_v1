<form role="form" id="frmMessagesMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Message <span style="color:#FF0000;">*</span></label>
    <textarea  class="form-control" id="messages_text" name="messages_text" style="height:100px;" ></textarea>
  </div> 
  <div class="form-group divClsActiveStatus">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="messages_status" name="messages_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>