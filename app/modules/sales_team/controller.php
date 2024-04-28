<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$sales_team = new sales_team();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$sales_team->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$sales_team->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$sales_team->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$sales_team->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$sales_team->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
