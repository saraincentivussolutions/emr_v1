<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$products = new Products();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$products->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$products->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$products->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$products->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$products->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
