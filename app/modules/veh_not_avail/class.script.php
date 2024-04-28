<?php	

class veh_not_avail extends common
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
		  
		  $getStkBkids=$this->getChasisNoArrdetails('veh_notavail'); 
		  $nobkDetId=implode(",",$getStkBkids['bknostkids']);
		  if(!$nobkDetId) $nobkDetId="0";
		  
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
		  $ordstatus_filtval = $postArr['ordstatus_filt'];	
		  $ordstatus_filter="";
		  if($ordstatus_filtval){ $ordstatus_filter=" and bk.order_status=:statusfilt"; $bindArr[':statusfilt']=array("value"=>$ordstatus_filtval,"type"=>"int"); }
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk   where bk.is_deleted<>1 $ordstatus_filter  and bk.order_status in (1,3) $where  and bk.booking_transaction_id in ($nobkDetId) $salesTeamfilt  ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received, ca.employee_name as customer_advisor_name, clr.productcolour_name as primary_colour  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) left join srt_stock_master_entry as stk on (bk.product_line=stk.productline_id and coalesce(stk.stock_chasis_used,0)=0 ) left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id left join    srt_productcolour_master as clr on bk.product_color_primary=clr.productcolour_id where bk.is_deleted<>1  $where $ordstatus_filter   and bk.order_status in (1,3) and bk.booking_transaction_id in ($nobkDetId) $salesTeamfilt group by bk.booking_transaction_id order by order_date "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["booking_transaction_id"]);
				$order_no=$this->purifyString($rs["order_no"]);
				$order_date=$this->convertDate($this->purifyString($rs["order_date"]));
				$orderstatus_name=$this->purifyString($rs["orderstatus_name"]);
				$customer_name = $this->purifyString($rs["customer_name"]);
				$customer_mobile = $this->purifyString($rs["customer_mobile"]);
				$productline_name = $this->purifyString($rs["productline_name"]); 
				$sales_team_name = $this->purifyString($rs["sales_team_name"]);
				$customer_advisor_name = $this->purifyString($rs["customer_advisor_name"]);
				$primary_colour = $this->purifyString($rs["primary_colour"]);
				
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$total_offer =  number_format($this->purifyString($rs["total_offer"]),2);
				$bk_amount_received =  number_format($this->purifyString($rs["bk_amount_received"]),2);
				
				 
				$sendRs[$rsCnt]=array($PageSno+1, $order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $primary_colour, $onroad_price, $total_offer, $bk_amount_received);
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	 
	
	public function __destruct() 
	{
		
	} 
}

?>