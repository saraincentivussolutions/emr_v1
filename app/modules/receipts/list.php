 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Receipts
		  	<!--<div class="pull-right">
			  <a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="CreateUpdateReceiptsMasterList();"><i class="fa fa-plus"></i> Create New</a>
			</div>		  -->	
			 <div class="pull-right col-md-2 no-padding">
				<select name="rcptlist_filt_ordstatus" id="rcptlist_filt_ordstatus" class="form-control filter" onchange="rcptlistordstatuschange();" >
					<option value="0">-All-</option>
					<option value="1">Pre booking</option>
					<option value="3">Booked</option>
					<option value="BRcons">Bank reconsiliation</option> 
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
                  <table class="table table-bordered table-striped" id="receiptsMasterTbl" style="width:100%" >
                    <thead>
                      <tr>
                        <th width="3%">S.No</th>
                        <th width="8%">Ord. Date</th>
                        <th width="8%">Ord. Status</th>
                        <th width="10%">Sales Team </th>
						<th width="10%">CA name</th>
                        <th width="10%">Cust. Name</th>
                        <th width="10%">Cust. Mobile</th>
                        <th width="9%">Product Line</th>
                        <th width="8%">Veh. Quote</th>
                        <th width="8%">Offer</th>
                        <th width="8%">Receipt amount</th>
                        <th width="8%">Action</th> 
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
          <h4 class="modal-title">Receipts</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateReceiptsMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmReceiptsDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteReceiptsMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
    