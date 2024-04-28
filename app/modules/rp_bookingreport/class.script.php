<?php	

class bookingreport extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{  
		  $bindArr=array();
		  
		  $sess_log_superuser = $this->sess_log_superuser; 
		  $sess_sales_team_access_ids = $this->sess_sales_team_access_ids; 
		  if($sess_sales_team_access_ids=="") $sess_sales_team_access_ids=0;
		 
		  $salesTeamfilt = " and bk.sales_team in ($sess_sales_team_access_ids) ";
		  if($sess_log_superuser) { $salesTeamfilt = "";  } 
		  
		  $draw = $postArr['draw'];
		  $start = ($postArr['start'])?$postArr['start']:0;
		  $limit = ($postArr['length'])?$postArr['length']:0;
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where = 'and (bk.order_no like :search_str or bk.customer_name like :search_str or bk.customer_mobile like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  } 
		  
		  $bkrpt_customer_advisor_1=$postArr['bkrpt_customer_advisor']; 
	$bkrpt_finstatus_1=$postArr['bkrpt_finstatus'];
	$bkrpt_order_status_1=$postArr['bkrpt_order_status'];
	
	$bkrpt_customer_advisor_2=$postArr['bkrpt_customer_advisor_2']; 
	$bkrpt_finstatus_2=$postArr['bkrpt_finstatus_2'];
	$bkrpt_order_status_2=$postArr['bkrpt_order_status_2'];
	
	if($bkrpt_finstatus_1=="") $bkrpt_finstatus_1="-1";
	if($bkrpt_finstatus_2=="") $bkrpt_finstatus_2="-1";
	
	$custFilt="";
	
	if($bkrpt_customer_advisor_1 or $bkrpt_customer_advisor_2)
	{
		$f_ca="";
		for($csfi=1;$csfi<=2;$csfi++)
		{
			$chknm='bkrpt_customer_advisor_'.$csfi;
			if($$chknm)
			{
				if($f_ca) $f_ca.=" or ";
				
				$f_ca.=" bk.customer_advisor=:customer_advisor_{$csfi} ";	
				$bindArr[":customer_advisor_{$csfi}"]=array("value"=>$$chknm,"type"=>"text");
			}
		}
		if($f_ca) $custFilt.=" and ({$f_ca}) "; 
	}
	if($bkrpt_finstatus_1>=0 or $bkrpt_finstatus_2>=0)
	{
		$f_ca="";
		for($csfi=1;$csfi<=2;$csfi++)
		{
			$chknm='bkrpt_finstatus_'.$csfi;
			if($$chknm>=0)
			{
				if($f_ca) $f_ca.=" or ";
				
				$f_ca.=" coalesce(finance_process_status,0)=:finance_process_status_{$csfi} ";	
				$bindArr[":finance_process_status_{$csfi}"]=array("value"=>$$chknm,"type"=>"int");
			}
		}
		if($f_ca) $custFilt.=" and ({$f_ca}) "; 
	}
	if($bkrpt_order_status_1 or $bkrpt_order_status_2)
	{
		$f_ca="";
		for($csfi=1;$csfi<=2;$csfi++)
		{
			$chknm='bkrpt_order_status_'.$csfi;
			if($$chknm)
			{
				if($f_ca) $f_ca.=" or ";
				
				$f_ca.=" bk.order_status=:order_status_{$csfi} ";	
				$bindArr[":order_status_{$csfi}"]=array("value"=>$$chknm,"type"=>"int");
			}
		}
		if($f_ca) $custFilt.=" and ({$f_ca}) "; 
	}
	 
		  // $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price,coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, ca.employee_name as customer_advisor_name, coalesce(total_srt_addition,0) as total_srt_addition, case insurance_type when 1 then 'In house' when 2 then 'Customer' else '' end as insurance_type_desc, coalesce(total_srt,0) as total_srt, bk.order_status, bk.off_acc_approved_status, bk.off_admin_approved_status , coalesce(sum(rcp.receipt_amount),0) as bk_amount_received  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id  where bk.is_deleted<>1 $where $ordstatus_filter and date_format(bk.order_date, '%m-%Y')= date_format(curdate(), '%m-%Y') group by bk.booking_transaction_id  order by order_date "; 
		   
		    $sql="select sls.sales_team_name, ca.employee_name as customer_advisor_name, ordsts.orderstatus_name, bk.order_date, bk.customer_name, bk.customer_mobile, prd.productline_name,  bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0) as contribution_offer,  coalesce(total_srt_addition,0) as srt_addition_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received ,  case finance when 1 then 'In house' when 2 then 'Customer' else '' end as finance_desc, finmas.financier_name,  fintr.finance_amount,   case finance_process_status when 0 then 'Pending' when 1 then 'KYC pending' when 2 then 'Expected DO pending'  when 3 then 'Login pending'  when 4 then 'Document approval pending'  when 5 then 'Document date pending'  when 6 then 'MMR pending'  when 7 then 'DO approve pending'  when 8 then 'DO date pending' when 10 then 'Completed' when 11 then 'First followup pending' when 12 then 'Second followup pending' when 13 then 'Third followup pending' when 14 then 'Fourth followup pending' else 'Pending' end as fin_status, bk.booking_transaction_id, bk.order_no  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id left join srt_finance_transaction as fintr on bk.booking_transaction_id=fintr.booking_transaction_id left join srt_financier_master as finmas on fintr.financier_id=finmas.financier_id  where bk.is_deleted<>1 $custFilt  $salesTeamfilt  group by bk.booking_transaction_id  order by sls.sales_team_name, ca.employee_name, order_date   "; //and date_format(bk.order_date, '%m-%Y')= date_format(curdate(), '%m-%Y') 
			//$sql.="LIMIT :limitstart_val, :limitend_val ";
			//$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			//$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr);  
			
			$sendArr=array('rsData'=>$recs,'status'=>'success'); 
			return $sendArr;
	} 
	
	public function __destruct() 
	{
		
	} 
}

?>