 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Price List
		  	<div class="pull-right">
            <a href="#" data-modal-id="popup1" class="month-datepicker"><i class="fa fa-calendar"></i> <span id="viewShowDate">Month & Year</span></a>
			  <a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="CreateUpdatePriceListMasterList();"><i class="fa fa-plus"></i> Create New</a><a class="js-open-modal btn btn-success act-add" href="#" data-modal-id="popup1" onclick="DuplicateEntryPriceListMasterList();"><i class="fa fa-plus"></i> Duplicate Entry</a>
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
                  <table class="table table-bordered table-striped" id="priceListMasterTbl"   >
                    <thead>
                       <tr>
                        <th width="2%">S.No</th>
                        <th width="11%">Parent product line</th>
						<th width="11%">Product line</th>
                        <th width="7%">Vechile type</th>
						 <th width="7%">Price date</th>
						  <th width="6%">Ex showroom</th>
						  <th width="6%">Insurance</th>
						  <th width="6%">TAX</th>
						  <th width="6%">Accessories</th>
						  <th width="6%">TAXI</th>
						  <th width="6%">EW (2+1yr)</th>
						  <th width="6%">On Road</th>
						  <th width="6%">Nill depriciation</th>
						  <th width="6%">On Road included Nill depriciation</th>
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
          <h4 class="modal-title">Price List</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdatePriceListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmPriceListDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deletePriceListMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
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
         <label>Price date</label>
        <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        <input type="text" name="txt_price_date" id="txt_price_date" class="form-control pull-right price-datepicker" maxlength="10"></div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="DuplicateEntryPriceListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Duplicate</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>