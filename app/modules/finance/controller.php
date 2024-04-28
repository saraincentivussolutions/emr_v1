<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$finance = new finance();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$finance->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$finance->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$finance->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$finance->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$finance->deleteRestrition($postArr);
	
		exit;
	}
	
	if($action == 'getReceiptView')
	{
		echo $op=$finance->getReceiptFinanceData($postArr);
		exit;
	}
	
	if($action == 'save_customer')
	{
		echo $op=$finance->saveprocessCustomer($postArr);
		exit;
	}
	

?>