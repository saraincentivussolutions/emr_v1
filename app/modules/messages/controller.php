<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$messages = new messages();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$messages->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$messages->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$messages->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$messages->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$messages->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
