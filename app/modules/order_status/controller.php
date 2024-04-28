<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$order_status = new order_status();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$order_status->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$order_status->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$order_status->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$order_status->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$order_status->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
