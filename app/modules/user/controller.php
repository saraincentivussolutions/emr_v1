<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$user = new user();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$user->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$user->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$user->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$user->deleteprocess($postArr);
	
		exit;
	}
	
	if($action=='change_password')
	{
		echo $op=$user->changeUsersPasswordByAdmin($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$user->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getLoginCPSingeView')
	{
		echo $op=$user->getLoginCPSingeView($postArr);
	
		exit;
	}
	if($action=='change_loginuser_password')
	{
		echo $op=$user->changeLoginUsersPassword($postArr);
	
		exit;
	}

?>
