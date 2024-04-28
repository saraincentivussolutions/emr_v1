<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$booking = new booking();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$booking->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$booking->getSingleView($postArr);
	
		exit;
	}
?>
