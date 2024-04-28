<?php
@session_start();
$menus = $_SESSION['sess_log_user_previlage_submenu_record']['Transactions'];
?>
<aside class="main-sidebar">
  <section class="sidebar">
    <!--<ul class="sidebar-menu">-->
		<ul class="sidebar-menu"> 
        <?php
		
			$i = 0;
			foreach($menus as $module_name=>$module_det)
			{
				$mod_action = $module_det['mod_action'];
				$call_js = $module_det['call_js'];
				$class = ($i==0)?'active':'';
				$tmpArr = explode(',', $mod_action);
				if(in_array(1, $tmpArr))
				{
					echo "<li class=\"$class\"><a onclick=\"callLeftMenuPages(this,'$call_js');\"><i class=\"fa fa-circle-o\"></i> $module_name</a></li>";
				}	
				$i++;
			}
		
		?>
		   <!--<li class="active"><a onclick="callLeftMenuPages(this,'trans_booking');"><i class="fa fa-circle-o"></i> Booking</a></li>
           <li><a onclick="callLeftMenuPages(this,'trans_offerapproval');"><i class="fa fa-circle-o"></i> Offer approval</a></li>
           <li><a onclick="callLeftMenuPages(this,'trans_receipts');"><i class="fa fa-circle-o"></i> Receipts</a></li>
		   <li><a onclick="callLeftMenuPages(this,'trans_finance');"><i class="fa fa-circle-o"></i> Finance</a></li>
		   <li><a onclick="callLeftMenuPages(this,'trans_vehicle_exchange');"><i class="fa fa-circle-o"></i> Vehicle Exchange</a></li>
		   <li><a onclick="callLeftMenuPages(this,'trans_approval');"><i class="fa fa-circle-o"></i> Approval</a></li>
		   <li><a onclick="callLeftMenuPages(this,'trans_retail');"><i class="fa fa-circle-o"></i> Retail</a></li>
		   <li><a onclick="callLeftMenuPages(this,'trans_veh_not_avail');"><i class="fa fa-circle-o"></i> VNA</a></li>-->
        </ul> 
  </section>
</aside>