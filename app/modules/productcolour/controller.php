<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$productcolour = new productcolour();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$productcolour->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$productcolour->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$productcolour->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$productcolour->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$productcolour->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
