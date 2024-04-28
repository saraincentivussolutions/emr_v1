<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$financier = new financier();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$financier->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$financier->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$financier->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$financier->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$financier->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
