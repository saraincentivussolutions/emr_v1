<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$receipts = new receipts();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$receipts->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$receipts->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$receipts->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$receipts->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$receipts->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getDetailList')
	{
		echo $op=$receipts->receiptDetailsList($postArr);
	
		exit;
	}
	if($action=='bank_reconssave')
	{
		echo $op=$receipts->saveBankReconsprocess($postArr);
	
		exit;
	}
	if($action=='getSingeBankReconsView')
	{
		echo $op=$receipts->getSingeBankReconsView($postArr);
	
		exit;
	}
	

?>
