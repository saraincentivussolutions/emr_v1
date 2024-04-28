<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$parent_productline = new parent_productline();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$parent_productline->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$parent_productline->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$parent_productline->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$parent_productline->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$parent_productline->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
