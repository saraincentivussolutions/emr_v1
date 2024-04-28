 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Booking
		  	<div class="pull-right">
			  <a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="CreateUpdateBookingMasterList();"><i class="fa fa-plus"></i> Create New</a>
			</div>	
			<div class="pull-right col-md-2">
				<select name="bklist_filt_ordstatus" id="bklist_filt_ordstatus" class="form-control filter" onchange="bklistordstatuschange();" >
					<option value="0">-All-</option>
					<option value="1">Yellow Form</option>
					<option value="3">Booked</option>
					<option value="4">Cancelled</option>
					<option value="6">Delivered</option>
					<option value="5">Retailed</option>
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
                  <table class="table table-bordered table-striped" id="bookingMasterTbl" style="width:100%" >
                    <thead>
                      <tr>
                        <th width="3%">S.No</th>
                        <th width="7%">Ord. Date</th>
                        <th width="7%">Ord. Status</th>
                        <th width="9%">Sales Team </th>
						 <th width="7%">CA Name</th>
                        <th width="7%">Cust. Name</th>
                        <th width="7%">Cust. Mobile</th>
                        <th width="9%">Product Line</th>
                        <th width="7%">Veh. Quote</th>
                        <th width="7%">Total Offer</th>
                        <th width="7%">SRT additional</th>
						<th width="7%">Finance by</th>
						<th width="9%">Remarks</th>
                        <th width="5%">Action</th> 
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
          <h4 class="modal-title">Booking</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateBookingMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmBookingDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteBookingMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
    