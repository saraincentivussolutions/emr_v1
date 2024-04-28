<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$price_list = new price_list();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$price_list->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$price_list->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$price_list->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$price_list->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$price_list->deleteRestrition($postArr);
	
		exit;
	}
	
	if($action=='duplicate_entry')
	{
		echo $op=$price_list->duplicateEntryMonthYear($postArr);
	
		exit;
	}
	

?>
