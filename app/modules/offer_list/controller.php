<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$offer_list = new offer_list();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$offer_list->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$offer_list->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$offer_list->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$offer_list->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$offer_list->deleteRestrition($postArr);
	
		exit;
	}
	
	if($action=='duplicate_entry')
	{
		echo $op=$offer_list->duplicateEntryMonthYear($postArr);
	
		exit;
	}
	
	

?>
