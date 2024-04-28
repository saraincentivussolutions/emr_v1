<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$dashboard = new dashboard();
	
	$action=$_POST["action"];
	$postArr=$_POST; 
	 
	if($action=='getSingeView')
	{
		echo $op=$dashboard->getSingleView($postArr);
	
		exit;
	}

?>
