<form role="form" id="frmStockEntry" method="post">
  <input type="hidden" name="hid_id" id="hid_id" />  
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
   <input type="hidden" name="hid_temp_import_file" id="hid_temp_import_file"/>
   <input type="hidden" name="hid_import_opt" id="hid_import_opt"/>
<div class="content-wcommon">
         <section class="content-header">
          <h1>
             Stock Entry 
			<div class="col-md-4 no-padding text-right pull-right">		
            <button class="btn btn-primary btn-sm"  onclick="importExcelStackEntryData()" style="margin-right:10px;" type="button"  ><i class="fa fa-floppy-o"></i> Import</button>		  
            
            <button class="btn btn-primary btn-sm"  onclick="downloadExcelStackEntryData()" style="margin-right:10px;" type="button"  ><i class="fa fa-floppy-o"></i> Download</button>		  				
				<button class="btn btn-primary btn-sm"  onclick="CreateUpdateStockEntrySave()" style="margin-right:10px;" type="button"  ><i class="fa fa-floppy-o"></i> Submit</button>		  	
				<button class="btn btn-warning btn-sm" type="button"  onclick="StockEntryBack()" ><i class="fa fa-times"></i> Back to list</button>					
			</div>	
		</h1>	
        <div class="pull-right no-padding"  >
			  <label id="created_by"></label>
			</div>	
        </section>

        <section class="content"> 
	
          <div class="box-body white-bg">
			<table class="form-table table table-bordered table-striped" id="customFields" style="width:100%" >
				<thead>
                
					<th width="5%">S.No</th>
					<th width="12%">Date</th>
					<th width="15%">Parent productline</th> 
					<th width="15%">Productline</th>
					<th width="12%">Product colour</th>
					<th width="18%">Chasis No.</th> 
					<th width="10%">Purchase cost</th> 
					<th width="15%">Status</th> 
				 </thead>
                <tbody>
                <tr id="tempRow" style="display:none">
					<td><label class="lbl-1">rw</label><input type="hidden" name="hdn_bill_detid[]"  id="hdn_bill_detid_rw"/></td>
					<td><div class="input-group date" style="width:150px;" onclick="funcCallDate(this);" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control pull-right datepicker" name="txt_stock_entry_date[]" id="txt_stock_entry_date_rw"></div></td>
					<td><select class="form-control" name="cmb_parentproductline[]" id="cmb_parentproductline_rw" onchange="viewStockEntryOnChangeParentProd(rw)"></select></td>
					<td><select class="form-control" name="cmb_productline[]" id="cmb_productline_rw"  ></select></td>  
					<td><select class="form-control" name="cmb_product_colour[]" id="cmb_product_colour_rw" ></select></td>
					<td><input type="text" class="form-control clsStockChasisNo"  name="txt_chasis_no[]" id="txt_chasis_no_rw" maxlength="50" /></td>
					<td><input type="text" class="form-control"  name="txt_purchase_cost[]" id="txt_purchase_cost_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="10"   style="text-align:right;"   /></td>
					<td><div style="width:89%; float:left" ><select class="form-control"  name="cmb_stock_status[]" id="cmb_stock_status_rw" ><option value="1" >Open stock</option><option value="2" >G stock</option></select></div><div style="width:10%; float:right;padding-top: 12px; color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteStockEntryRow(rw)"></i></div></td>  
                </tr>
                
                </tbody>
				
			</table>
          </div>
		  <div class="col-md-12 no-padding">
		   	<p>&nbsp;</p>
		 	<div class="col-md-4">
			  <a class="btn btn-success addCF" onclick="addStockEntryDetailRow()"><i class="fa fa-plus"></i> Add Line</a>
			</div>
			</div>
        </section>
      </div>
      </form>
      
      <div class="modal " id="viewImportModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Import Stock Entry</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalImportSave" onclick="importStockEntryFile();"><i class="fa fa-floppy-o"></i>&nbsp; Import</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
    </div>