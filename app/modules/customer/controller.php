<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$customer = new customer();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$customer->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$customer->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$customer->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$customer->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$customer->deleteRestrition($postArr);
	
		exit;
	}

?>
