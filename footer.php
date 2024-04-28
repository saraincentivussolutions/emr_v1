	<script src="js/jQuery-2.1.4.min.js?ver=1.0.0.15"></script>
    <script src="js/jquery-ui.min.js?ver=1.0.0.15" type="text/javascript"></script>
     <script src="js/jquery.form.js?ver=1.0.0.15" type="text/javascript"></script>
	 <script src="js/download.jQuery.js?ver=1.0.0.4" type="text/javascript"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="js/bootstrap.js?ver=1.0.0.15" type="text/javascript"></script>    
	<script src="js/jquery.dataTables.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/dataTables.bootstrap.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/dataTables.buttons.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/buttons.flash.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/jszip.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/pdfmake.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/vfs_fonts.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/buttons.html5.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/buttons.print.min.js?ver=1.0.0.15" type="text/javascript"></script>
    <script src="js/app.js?ver=1.0.0.15" type="text/javascript"></script>    
    <script src="js/core.js?ver=1.0.0.15" type="text/javascript"></script>
	<script src="js/highcharts.js?ver=1.0.0.15"></script>
	<script src="js/no-data-to-display.js?ver=1.0.0.15"></script>
	<script src="js/data.js?ver=1.0.0.15"></script>
	<script src="js/bootstrap-datepicker.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/common.js?ver=1.0.0.15"></script>
	
	<script type="text/javascript" src="js/order_status.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/sales_team.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/user.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/financier.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/parent_productline.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/productline.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/productcolour.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/messages.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/source_of_contact.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/offer_list.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/price_list.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/stock_entry.js?ver=1.0.0.15"></script>
    <script type="text/javascript" src="js/user_role.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/login_master.js?ver=1.0.0.15"></script>
	
	<script type="text/javascript" src="js/booking.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/finance.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/vehicle_exchange.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/approval.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/retail.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/receipts.js?ver=1.0.0.15"></script> 
 	<script type="text/javascript" src="js/offer_approval.js?ver=1.0.0.15"></script>
	<script type="text/javascript" src="js/vehicle_not_available.js?ver=1.0.0.15"></script>
	
	<script type="text/javascript" src="js/rp_bookingreport.js?ver=1.0.0.15"></script>
    
	<script type="text/javascript" src="js/dashboard.js?ver=1.0.0.15"></script>
    <script type="text/javascript" src="js/monthpicker.js?ver=1.0.0.15"></script> 
    
	<script type="text/javascript">
	   GV_menu_permission = (<?=$menujson;?>);
	   GV_menu_permission = eval(GV_menu_permission);
	 
		console.log(GV_menu_permission);
		$( document ).ready(function() {
			var dh=($( document ).height())-154; 
			
			$('.clsSetHtFOrLFtMnu').css('min-height',(dh+'px'));
		});
		
		$(function(){
		
		

			/*$('.datepicker').datepicker({
			  autoclose: true
			});
		
			var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
			
				$('a[data-modal-id]').click(function(e) {
					e.preventDefault();
				$("body").append(appendthis);
				$(".modal-overlay").fadeTo(500, 0.7);
				//$(".js-modalbox").fadeIn(500);
					var modalBox = $(this).attr('data-modal-id');
					$('#'+modalBox).fadeIn($(this).data());
				});  
			  
			  
			$(".js-modal-close, .modal-overlay").click(function() {
				$(".modal-box, .modal-overlay").fadeOut(500, function() {
					$(".modal-overlay").remove();
				});
			 
			});
			 
			$(window).resize(function() {
				$(".modal-box").css({
					top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
					left: ($(window).width() - $(".modal-box").outerWidth()) / 2
				});
			});
			 
			$(window).resize();
			 
		});
		  */
		});		
	</script>
	<div id="overlay_div" class="ui-widget-overlay">
		<div id="loading_div">Loading...Please wait..</div>
	</div>
	
	<div class="modal " id="viewUserChangePwdFoooterModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change password</h4>
        </div>
        <div class="modal-body">
        <form role="form" id="frmUserChangePwdFooter">
						<input type="hidden" name="ucpft_hid_id" id="ucpft_hid_id" />  
						<div class="form-group">
    <label>Current Password</label>
    <input type="password" class="form-control" id="ucpft_old_password" name="ucpft_old_password" placeholder="Enter Current Password"  maxlength="30">
  </div>
  <div class="form-group">
    <label>New Password</label>
    <input type="password" class="form-control" id="ucpft_new_password" name="ucpft_new_password" placeholder="Enter New Password"  maxlength="30">
  </div>
  <div class="form-group">
    <label>Confirm Password</label>
    <input type="password" class="form-control" id="ucpft_re_password" name="ucpft_re_password" placeholder="Enter New Password"  maxlength="30">
  </div> 
 
        </form>
        </div>
        <div class="modal-footer">
		 <button type="button" class="btn btn-primary" id="btnuserChangePwdFooterDelete" onclick="userChangePwdFooterOK();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button> 
        </div>
      </div>
      
    </div>
  </div>
  
  </body>
</html>
<div class="facile">Developed By <a href="http://facileinfotech.com" style="cursor:pointer;" target="_blank" >Facile Infotech</a></div>