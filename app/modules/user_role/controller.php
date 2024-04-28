<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$user_role = new user_role();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$user_role->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$user_role->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$user_role->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$user_role->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$user_role->deleteRestrition($postArr);
	
		exit;
	}
	
	if($action == 'getUserPrevillage')
	{
		echo $op=$user_role->getUserPrevillage($postArr);
	
		exit;
	}

?>
