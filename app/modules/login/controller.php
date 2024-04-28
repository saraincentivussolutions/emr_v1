<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$login = new login();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='chk_details')
	{
		echo $op=$login->login_authentication($postArr);
	
		exit;
	}	
?>