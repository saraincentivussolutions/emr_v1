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
	if($action=='save')
	{
		echo $op=$booking->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$booking->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$booking->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getOnchangeView')
	{
		$modtype=$_POST["modtype"];
		if($modtype=='vehicle_exchange')
		{
			echo $op=$booking->getExchangeAmountSingleView($postArr);
		}
		else if($modtype=='quoatation_offer')
		{
			echo $op=$booking->getQuotationOfferAmountSingleView($postArr);
		}
		else
		{
			echo json_encode(array('status'=>'failure'));
		}
		
	
		exit;
	}
	

?>
