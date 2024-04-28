 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Offer List
		  	<div class="pull-right">
             
              <a href="#" data-modal-id="popup1" class="month-datepicker"><i class="fa fa-calendar"></i> <span id="viewShowDate">Month & Year</span></a>
			  <a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="CreateUpdateOfferListMasterList();"><i class="fa fa-plus"></i> Create New</a><a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="DuplicateEntryOfferListMasterList();"><i class="fa fa-plus"></i> Duplicate Entry</a>
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
                  <table class="table table-bordered table-striped" id="offerListMasterTbl" style="width:100%"   >
                    <thead>
                      <tr>
                        <th width="3%">S.No</th>
                        <th width="12%">Parent product line</th>
						<th width="11%">Product line</th>
                        <th width="8%">Vechile type</th>
						 <th width="8%">Offer date</th>
						  <th width="6%">Cash (TATA)</th>
						  <th width="6%">Cash (SRT)</th>
						  <th width="6%">Exchange (TATA)</th>
						  <th width="6%">Exchange (SRT)</th>
						  <th width="6%">Corporate (TATA)</th>
						  <th width="6%">Corporate (SRT)</th>
						  <th width="6%">EDR (TATA)</th>
						  <th width="6%">EDR (SRT)</th>
                        <th width="12%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
                </div>
        </section>
      </div>
      
      <div class="modal " id="viewPageModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Offer List</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateOfferListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmOfferListDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteOfferListMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
     <div class="modal " id="viewPageDuplicateModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Duplicate Entry</h4>
        </div>
        <div class="modal-body">
       <div class="row">
   <div class="col-md-12">
         <label>Offer date</label>
        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" name="txt_offer_date" id="txt_offer_date" class="form-control pull-right offer-datepicker" maxlength="10"></div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="DuplicateEntryOfferListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Duplicate</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
    
    