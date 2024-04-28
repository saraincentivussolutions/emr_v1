 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Approval 
		
		 <div class="pull-right col-md-2 no-padding">
			<select name="apprlist_filt_paidstatus" id="apprlist_filt_paidstatus" class="form-control filter" onchange="apprlistpaidstatuschange();" > 
				<option value="1">Fully paid</option>
				<option value="2">Finance completed</option> 
				<option value="3">More than 1 lakh</option> 
				<option value="4">More than 50k</option> 
			   </select>
			</div>
		</h1> 
        </section>

        <section class="content">
        
          <div class="box-body white-bg">
		  <div class="alert alert-success alertSuccDivMsg"   >
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                    <div id="succDivMsg" >&nbsp;</div>
                </div>
                  <table class="table table-bordered table-striped" id="approvalMasterTbl" style="width:100%" >
                    <thead>
                      <tr>
                        <th width="4%">S.No</th>
                         <th width="7%">Ord. Date</th>
                        <th width="7%">Ord. Status</th>
                        <th width="9%">Sales team</th>
						<th width="8%">CA Name</th>
						<th width="8%">Cust. Name</th>
                        <th width="8%">Cust. Mobile</th>
						 <th width="8%">Product Line</th>
                        <th width="7%">Veh. OnRoad Price</th>
						<th width="7%">Offer</th>
                        <th width="7%">Amount Received</th>
                        <th width="8%">Approved By</th>
                        <th width="6%">Approval Date</th> 
                        <th width="6%">Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
                </div>
        </section>
      </div>
      
      <div class="modal " id="viewPageModal" role="dialog">
    <div class="modal-dialog" style="width:auto">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Approval</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateApprovalMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
  <div class="modal " id="viewDeleteModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete</h4>
        </div>
        <div class="modal-body">
        <form role="form" id="frmApprovalDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteApprovalMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
    