<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$approval = new approval();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$approval->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$approval->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$approval->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$approval->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$approval->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
