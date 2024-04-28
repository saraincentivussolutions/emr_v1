<div class="content-wcommon">
  <section class="content-header">
    <h1> Sales Report </h1>
  </section>
  <section class="content-header">
    <div class="box-body white-bg">
      <div class="table-responsive1">
        <div class="col-md-12">
          <div class="col-md-2">
            <label class="lbl-1">Search by</label>
            <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);">
              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
              <input type="text" name="search_from_date" id="search_from_date"  class="form-control pull-right datepicker" maxlength="10" value="" >
            </div>
          </div>
          <div class="col-md-2">
            <label class="lbl-1">&nbsp;</label>
            <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);">
              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
              <input type="text" name="search_to_date" id="search_to_date"  class="form-control pull-right datepicker" maxlength="10" value="" >
            </div>
          </div>
          <div class="col-md-2">
            <label class="lbl-1">&nbsp;</label>
            <a class="js-open-modal btn btn-warning " href="#" onclick="filterSalesReportDetails();"   >Filter</a> </div>
          <div class="col-md-2 ">
            <label class="lbl-1">&nbsp;</label>
            <a class="js-open-modal btn btn-warning " href="#" onclick="searchDetailsSalesReportGrid();"   >Search</a> </div>
          <div class="col-md-2 ">
            <label class="lbl-1">&nbsp;</label>
            <a class="js-open-modal btn btn-warning " href="#" onclick="exportSalesReportDetails();"   >Export</a> </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">&nbsp; </div>
  </section>
  <section class="content">
    <div class="box-body white-bg">
      <div class="alert alert-success alertSuccDivMsg"   >
        <button type="button" class="close" data-dismiss="alert"> <i class="fa fa-times" aria-hidden="true"></i> </button>
        <div id="succDivMsg" >&nbsp;</div>
      </div>
      <table class="table table-bordered table-striped" id="salesReportMasterTbl" width="100%">
        <thead>
          <tr>
            <th width="6%">S.No</th>
            <th width="15%">Bill No.</th>
            <th width="12%">Bill Date</th>
            <th width="16%">Services</th>
            <th width="10%" style="text-align:right">Bill Amount</th>
            <th width="11%">Customer</th>
			<th width="12%">Cust. Mobile</th>
            <th width="18%">Bill By</th>
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
          <h4 class="modal-title">Advanced Filter</h4>
        </div>
        <div class="modal-body">
		<form role="form" id="frmSalesReportSearch"> 
    <div class="form-group">
    <label>Category</label>
     <select class="form-control" name="cmb_category" id="cmb_category" onchange="viewSalesRpOnChangeCategory()">
   </select>
  </div>
  <div class="form-group">
    <label>Services/Products</label>
     <select class="form-control" name="cmb_services" id="cmb_services">
   </select>
  </div>
  <div >
    <label>Bill By</label>
     <select class="form-control" name="bill_by_user" id="bill_by_user">
   </select>
  </div>
  <div class="form-group">
    <label>Customer/mobile</label>
     <input type="text" name="search_customer" id="search_customer"  class="form-control pull-right" maxlength="30" value="" >
  </div> 
   <div class="form-group">
    <label>Status</label><br />

    <select class="form-control" name="bill_status" id="bill_status">
	<option value="-1">-All-</option>
	<option value="1">Cancelled</option>
	<option value="0">Non Cancelled</option>
   </select>
  </div>
  
  <div class="form-group">
    <label>Ref. By</label>
    <select class="form-control" name="refered_employee_id" id="refered_employee_id">
   </select>
  </div>
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="filterSalesReportSearch();"><i class="fa fa-floppy-o"></i>&nbsp; Search</button>
		  <button type="button" class="btn btn-primary" id="btnModalSave" onclick="filterSalesReportReset();"><i class="fa fa-floppy-o"></i>&nbsp; Reset</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>