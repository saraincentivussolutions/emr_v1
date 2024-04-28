<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$vehicle_exchange = new vehicle_exchange();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$vehicle_exchange->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$vehicle_exchange->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$vehicle_exchange->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$vehicle_exchange->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$vehicle_exchange->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
