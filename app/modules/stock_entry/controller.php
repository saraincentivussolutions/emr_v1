<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$stock_entry = new stock_entry();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$stock_entry->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$stock_entry->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{ 
		echo $op=$stock_entry->saveprocess($postArr);
	
		exit;
	}
	
	if($action=='import')
	{
		echo $op=$stock_entry->importStockEntryData($postArr);
	
		exit;
	}
	/*if($action=='delete')
	{
		echo $op=$stock_entry->deleteprocess($postArr);
	
		exit;
	}*/
	 
?>
