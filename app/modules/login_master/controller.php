<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$login_master = new login_master();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$login_master->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$login_master->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$login_master->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$login_master->deleteprocess($postArr);
	
		exit;
	}
	
	if($action=='change_password')
	{
		echo $op=$login_master->changeLoginMastersPasswordByAdmin($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$login_master->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getLoginCPSingeView')
	{
		echo $op=$login_master->getLoginCPSingeView($postArr);
	
		exit;
	}
	if($action=='change_loginuser_password')
	{
		echo $op=$login_master->changeLoginLoginMastersPassword($postArr);
	
		exit;
	}

?>
