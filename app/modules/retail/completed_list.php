 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             To be Retail List
		  	<div class="pull-right col-md-2 no-padding">
				<select name="retaillist_filt_cmpstatus" id="retaillist_filt_cmpstatus" class="form-control filter" onchange="retaillistfiltcmpstatuschange();" >
					<option value="1">To be retailed</option>
					<option value="2">Retailed done</option>
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
                  <table class="table table-bordered table-striped" id="retailListMasterTbl" style="width:100%" >
                    <thead>
                       <tr>
                        <th width="5%">S.No</th>
                        <th width="7%">Ord. Date</th>
                        <th width="10%">Sales team</th>
						<th width="10%">CA Name</th>
						<th width="9%">Cust. name</th>
                        <th width="9%">Cust. phone</th>
                        <th width="10%">Product Line</th>
						<th width="9%">Product Colour</th>
						 <th width="8%">Chasis No.</th>
						<th width="9%">Invoice No.</th>
						<th width="8%">Hyp details</th>
                        <th width="6%">Actions</th>
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
          <h4 class="modal-title">Retail List</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateRetailListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmRetailListDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteRetailListMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
  <div class="modal " id="viewStockListModal" role="dialog" >
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Stock List</h4>
        </div>
        <div class="modal-body">
         <table class="table table-bordered table-striped" id="retailStockListMasterTbl"  >
                    <thead>
                      <tr>
                        <th width="7%">S.No</th>
                        <th width="18%">Date</th>
						<th width="30%">Age</th>
                        <th width="35%">Chasis No</th>  
                         <th width="10%"></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
        </div>
<!--        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalOk" onclick="selectStockChasisNo()"  data-dismiss="modal"><i class="fa fa-search"></i>&nbsp; Ok</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
-->      
    </div>
  </div>
      
    </div>
    
    