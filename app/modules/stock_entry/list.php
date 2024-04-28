 <div class="content-wcommon">
         <section class="content-header">
          <h1>
             Stock list
		  	<div class="pull-right">
			  <a class="js-open-modal btn btn-success" href="#" data-modal-id="popup1" onclick="CreateUpdateStockEntryDetails();"><i class="fa fa-plus"></i> Create New</a>
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
                  <table class="table table-bordered table-striped" id="stockentryListDetailsTbl" >
                    <thead>
                      <tr>
                        <th width="7%">S.No</th>
                        <th width="18%">Parent productline</th>
                        <th width="16%">Productline</th>
                        <th width="17%">Product colour</th>
						<th width="12%" >Open stock</th>
						<th width="13%">G. Stock</th>
						<th width="17%">Total</th> 
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
                </div>
        </section>
      </div>