<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$productline = new productline();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$productline->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$productline->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$productline->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$productline->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$productline->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
