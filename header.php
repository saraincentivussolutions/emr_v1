<?php
	@session_start(); 
	$disp_sess_uname=($_SESSION['sess_log_userdispname'])?$_SESSION['sess_log_userdispname']:'';
	include 'app/common/class.common.php'; 
	$common = new common();
	$modules = $common->getSubMenuModulePrevilegeList();
	
	
	$menu_actions = array();
	$side_menu_actions = array();
	
	
	foreach($modules as $action)
	{
		$module_id = $action['module_id'];
		$mod_action = $action['menu_actions'];
		$module_type = $action['module_type'];
		$sub_module_name = $action['sub_module_name'];
		$main_module_name = $action['main_module_name'];
		$sub_module_call_js = $action['sub_module_call_js'];
		
		if($module_type==2)
		$menu_actions[$sub_module_name] = $mod_action;
		
		$side_menu_actions[$main_module_name][$sub_module_name] = array('mod_action'=>$mod_action, 'call_js'=>$sub_module_call_js);
		
	}
	
	$_SESSION['sess_log_user_previlage_submenu_record'] = $side_menu_actions;
	//print_r( $_SESSION['sess_log_user_previlage_record'] );
	$menujson = json_encode($menu_actions);
	
	$sess_log_superuser = $common->sess_log_superuser;
	$shwSettings="";
	if($sess_log_superuser) { $shwSettings="shw";  }   
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Scores</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="css/bootstrap.css?v5" rel="stylesheet" type="text/css" /> 
	 <link href="css/dataTables.bootstrap.css?v5" rel="stylesheet" type="text/css" />
	<link href="css/font-awesome.css?v5" rel="stylesheet" type="text/css" /> 
    <link href="css/core.css?v5" rel="stylesheet" type="text/css" />
    <link href="css/theme.css?v5" rel="stylesheet" type="text/css" />
	<link href="css/datepicker3.css?v5" rel="stylesheet" type="text/css" />
	<link href="css/custom.css?v5" rel="stylesheet" type="text/css" />
	<link href="css/monthpicker.css?v5" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini" onLoad="loadLayout(); ">
    <div class="wcommon">
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <!--<span class="logo-mini"><img src="img/logo-icon.png" width="26"></span>-->
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="img/logo.png" width="160"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <!--<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>-->
		  <div class="dropdown">
			  <button class="dropbtn"><i class="fa fa-bars"></i> Menu</button>
			  <div class="dropdown-content">
				<a href="#" onClick="loadHeaderMenus(this,'hdr_dashboard')">Dashboard</a>
				<a href="#" onClick="loadHeaderMenus(this,'hdr_transactions')" >Transactions</a>
				<!--<a href="#">Companies</a>
				<a href="#">APP Users</a>
				<a href="#">Reports</a>-->
				<?php if($shwSettings=="shw"){ ?><a href="#" onClick="loadHeaderMenus(this,'hdr_settings')">Masters</a><?php } ?>
				<a href="#" onClick="loadHeaderMenus(this,'hdr_reports')">Reports</a>
			  </div>
		  </div>
		  
		  <ol class="breadcrumb">
           
          </ol>
		  
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">              
              <li class="user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                 <!-- <img src="img/user.png" class="user-image" alt="User Image"/>-->
                  <span class="hidden-xs"><?php echo $disp_sess_uname;?></span>
                </a>                
              </li>
              <!-- Control Sidebar Toggle Button -->
              <!--<li>
                <a href="#"><i class="fa fa-cogs"></i></a>
              </li>-->
			  <li>
                <a onClick="loadLoginUserChangePassword()"  title="Change password" ><i class="fa fa-key"></i></a>
              </li>
			  <li>
                <a onClick="headerlogout()" title="Logout" ><i class="fa fa-power-off"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>