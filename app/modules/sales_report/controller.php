<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$salesreport = new salesreport();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$salesreport->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$salesreport->getSingleView($postArr);
	
		exit;
	}	
?>