<?php 
	include '../../common/class.common.php';
	include 'class.script.php'; 
	$source_of_contact = new source_of_contact();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$source_of_contact->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$source_of_contact->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$source_of_contact->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$source_of_contact->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$source_of_contact->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
