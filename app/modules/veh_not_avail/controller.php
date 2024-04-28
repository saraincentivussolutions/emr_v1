<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$veh_not_avail = new veh_not_avail();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$veh_not_avail->listview($postArr);
	
		exit;
	}

?>
