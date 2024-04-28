<form role="form" id="frmReceiptBankReconsDetailsMaster" class="fset">
  <input type="hidden" name="hid_bank_recons_id" id="hid_bank_recons_id" />
  <input type="hidden" name="hid_booking_id" id="hid_booking_id" />
   
   <fieldset>
   
   <div class="col-md-6 ">
    <label class="lblText">Approve Status <font color="red"><b> * </b></font></label><br />
	<label class="lblText"><input id="bank_recons_entry_status1" type="radio" name="bank_recons_entry_status" value="1" <?php echo ($rsData['bank_recons_entry_status']==1)?'checked':''; ?>/> Yes</label>&nbsp;&nbsp;
    <label class="lblText"><input id="bank_recons_entry_status2" type="radio" name="bank_recons_entry_status" value="2" <?php echo ($rsData['bank_recons_entry_status']==2)?'checked':''; ?>/> No</label>
  </div>
			
					 
                                </fieldset>
								 <fieldset class="clsRcpBankReconsHide" >
								
								<div class="col-md-12 ">
    <label class="lblText">Reason Type </font></label><br />
	<!--<label class="lblText"><input id="bank_recons_reason_type1" type="radio" name="bank_recons_reason_type" value="1" <?php echo ($rsData['bank_recons_reason_type']==1)?'checked':''; ?>/> Nil balance</label>&nbsp;&nbsp;-->
    <label class="lblText"><input id="bank_recons_reason_type2" type="radio" name="bank_recons_reason_type" value="2" <?php echo ($rsData['bank_recons_reason_type']==2)?'checked':''; ?>/> Represent</label>&nbsp;&nbsp;
	 <label class="lblText"><input id="bank_recons_reason_type3" type="radio" name="bank_recons_reason_type" value="3" <?php echo ($rsData['bank_recons_reason_type']==3)?'checked':''; ?>/> No Represent</label>
  </div>	
   </fieldset>
  <fieldset>
  <div class="col-md-6 ">
    <label >Entry Date <font color="red"><b> * </b></font></label>
	<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
	<input name="bank_recons_entry_date" type="text" maxlength="20" id="bank_recons_entry_date" class="form-control pull-right datepicker" /></div> 
  </div>
   <div class="col-md-6 ">
    <label >Entered By <font color="red"><b> * </b></font></label>
    <input name="bank_recons_entry_by" type="text" maxlength="50" id="bank_recons_entry_by" class="normaltext form-control">
  </div>
  </fieldset>
   <fieldset>
   <div class="col-md-12 ">
    <label >Remarks</label>
    <textarea name="bank_recons_remarks" id="bank_recons_remarks" class="normaltext form-control"></textarea>
  </div>
  </fieldset> 
   
</form>
