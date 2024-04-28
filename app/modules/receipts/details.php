 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Receipts list
		  	<div class="pull-right">
			  <a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="CreateUpdateReceiptDetailsList();"><i class="fa fa-plus"></i> Add receipt</a>
			  
			</div>		  	 
		</h1>	
		        </section>

        <section class="content">
        <input type="hidden" name="hdn_booking_id" id="hdn_booking_id" />
          <div class="box-body white-bg"  >
		  
		  <div class="table-responsive">
	  	<div class="col-md-12 no-padding">
	 	 	<div class="col-md-1 no-padding ">
            	<label class="lbl-1">Order No.</label>
			</div>	
			<div class="col-md-2">
            	<input type="text" name="recp_list_order_no" id="recp_list_order_no" class="form-control" maxlength="50" readonly=""  />
			</div>	
			<div class="col-md-9 no-padding text-right">
				<button class="btn btn-default btn-sm pull-right" type="button"  onclick="ReceiptEntryBack()" ><i class="fa fa-times"></i> Back to list</button> 
			</div>
        </div>
		 
         
      </div>
	 
	  
		  <div class="alert alert-success alertSuccDivMsg"   >
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                    <div id="succDivMsg" >&nbsp;</div>
                </div>
                  <table class="table table-bordered table-striped" id="receiptsDetailsMasterTbl" >
                    <thead>
                      <tr>
                        <th width="5%">S.No</th>
                        <th width="9%">Entry Date</th>
                        <th width="10%">Entered By</th>
                        <th width="10%">Receipt No.</th>
                        <th width="10%">Receipt Date</th>
                        <th width="10%">Payment Mode</th>
                        <th width="10%">Amount</th>
                        <th width="10%">Remarks</th>
                        <th width="10%">Actions</th>
                         <th width="6%">&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
                </div>
        </section>
        <section>
        
            </section>
        </div>
      
      <div class="modal " id="viewPageModal" role="dialog">
    <div class="modal-dialog" style="">
    
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
          <h4 class="modal-title">Cancel</h4>
        </div>
        <div class="modal-body">
        <form role="form" id="frmReceiptsDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to Cancel?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteReceiptsMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
      <div class="modal " id="viewPageReconsModal" role="dialog">
    <div class="modal-dialog" style="">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Receipts</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateReceiptsBankReconsSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
  
    </div>
    
    