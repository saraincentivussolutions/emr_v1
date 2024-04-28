 <?php
include  dirname(realpath('..')).'/common/class.common.php';  
include 'class.script.php'; 
$finance = new finance(); 
$user_roles_settings=$finance->getUserExtraModulesPrevilage();
$extra_json_elements = (array)$user_roles_settings;
$finance_by_inhouse = (int)$extra_json_elements['finance_by_inhouse'];
$finance_by_customer = (int)$extra_json_elements['finance_by_customer'];
	
?>	
 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Finance List
		  	<div class="pull-right col-md-2 no-padding">
				<select name="financelist_filt_finstatus" id="financelist_filt_finstatus" class="form-control filter" onchange="finlistfinstatuschange();" >
					<option value="-1">-All Status-</option>
					<option value="0">Pending - Yet to process</option>
					<option value="1">KYC pending</option>
					<option value="2">Expected DO pending</option>
					<option value="3">Login pending</option>
					<option value="4">Approval pending</option>
					<option value="5">Document sign pending</option>
					<option value="6">MMR pending</option>
					
					<option value="11">First followup pending</option>
					<option value="12">Second followup pending</option>
					<option value="13">Third followup pending</option>
					<option value="14">Fourth followup pending</option>
					
					<option value="7">DO pending</option>
					<option value="8">DO approval pending</option> 
					<option value="10">Completed</option> 
				   </select>
				</div>	 
				
				<div class="pull-right col-md-2"> 
					<select name="financelist_filt_bytype" id="financelist_filt_bytype" class="form-control filter" onchange="finlistfintypechange();" > 
                    <?php if($finance_by_inhouse===0 || $finance_by_inhouse==0) { $hdn_finance_view = -1; ?><option value="-1" selected="selected">None</option> <?php } ?> 
					<?php if($finance_by_inhouse==1) {  $hdn_finance_view = 1; ?><option value="1">In house</option> <?php } ?>
					<?php if($finance_by_customer==1) { if($finance_by_inhouse == 0)  { $hdn_finance_view = 2; } ?><option value="2">Customer</option> <?php } ?>
					<?php if($finance_by_inhouse==1) {  ?><option value="3">Finance completed - In house</option> <?php } ?>
					<?php if($finance_by_customer==1) { ?><option value="4">Finance completed - Customer</option> <?php } ?>
				   </select><input type="hidden" name="hdn_finance_view" id="hdn_finance_view" value="<?php echo $hdn_finance_view;?>" />
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
                  <table class="table table-bordered table-striped" id="financeListMasterTbl" style="width:100%" >
                    <thead>
                       <tr>
                        <th width="4%">S.No</th>
                        <th width="9%">Order date</th>
                         <th width="9%">Sales Team </th>
						 <th width="7%">CA Name</th>
						<th width="7%">Cust. Name</th>
						<th width="7%">Cust. Mobile</th>
                        <th width="7%">Product Line</th>
						 <th width="9%">Financier</th>
						  <th width="8%">Followed By</th>
						  <th width="6%">Amount</th>
						  <th width="7%">Expected DO Date</th>
						  <th width="7%">KYC Date</th>
						  <th width="13%">Finance Status</th>
                          <th width="9%">Remarks</th> 
                        <th width="7%">Actions</th>
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
          <h4 class="modal-title">Finance List</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateFinanceListMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
        <form role="form" id="frmFinanceListDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteFinanceListMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>
    
    