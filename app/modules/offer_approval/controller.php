<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$offer_approval = new offer_approval();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$offer_approval->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$offer_approval->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$offer_approval->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$offer_approval->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$offer_approval->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
