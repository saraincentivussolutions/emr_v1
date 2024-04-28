<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$retail = new retail();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$retail->listview($postArr);
	
		exit;
	}
	if($action=='getCompletedList')
	{
		echo $op=$retail->completedlistview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$retail->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$retail->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$retail->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$retail->deleteRestrition($postArr);
	
		exit;
	}
	
	if($action=='getStockList')
	{
		echo $op=$retail->getStockListDetails($postArr);
	
		exit;
	}
	
	

?>
