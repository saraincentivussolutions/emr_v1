 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Customer advisor (or) Employees
		  	<div class="pull-right">
			  <a class="js-open-modal btn btn-success" href="#" data-modal-id="popup1" onclick="CreateUpdateUserMasterList();"><i class="fa fa-plus"></i> Create New</a>
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
                  <table class="table table-bordered table-striped" id="userMasterTbl" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">S.No</th>
                        <th width="20%">Advisor Id</th>
                        <th width="16%">Advisor name</th>
						<th width="16%">Sales team</th>
                        <th width="13%">Mobile</th>
                        <th width="25%">Actions</th>
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
          <h4 class="modal-title">Employee</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateUserMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmUserDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteUserMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal " id="viewChangeModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change password</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="updateUserPassword();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
    