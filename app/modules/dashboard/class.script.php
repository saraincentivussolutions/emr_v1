<?php	

class dashboard extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function getSingleView($postArr)
	{ 
		$dd_year=$this->purifyInsertString($postArr["dd_year"]);
		$call_chart=$this->purifyInsertString($postArr["call_chart"]);
		
		$dd_pie_yr_duraion=$this->purifyInsertString($postArr["dd_pie_yr_duraion"]); 
		
		$dd_bar_yr_duraion=$this->purifyInsertString($postArr["dd_bar_yr_duraion"]);
		$dd_bar_exp_type=$this->purifyInsertString($postArr["dd_bar_exp_type"]);
		
		 
		
		$pieChartData = array('status'=>'failure');
		$barChartData = array('status'=>'failure');
		
		
		$pie_yr_types = trim(strtolower($dd_pie_yr_duraion));
		$bar_yr_types = trim(strtolower($dd_bar_yr_duraion));
		
		$chk_year_type=$bar_yr_types;
		if($call_chart=="pie") $chk_year_type=$pie_yr_types;
		 
		$bindPieArr=array(":year"=>array("value"=>$dd_year,"type"=>"int"));
		$bindBarArr=array(":year"=>array("value"=>$dd_year,"type"=>"int"));
		
		
		$pie_F_budget_yr=" year(bd.budget_allocation_month)=:year ";
		$pie_F_budget_month=" ";
		
		$pie_F_expn_yr=" year(ed.expense_det_date)=:year ";
		$pie_F_expn_month=" ";
		
		
		
		switch($chk_year_type)
		{
			case 'current year':	$bindPieArr=array(":year"=>array("value"=>date('Y'),"type"=>"int"));
									$bindBarArr=array(":year"=>array("value"=>date('Y'),"type"=>"int"));
								break;
								
			case 'current month': 	$pie_F_budget_month=" and month(bd.budget_allocation_month)=:month "; 
									$pie_F_expn_month=" and month(ed.expense_det_date)=:month "; 
									
									$bindPieArr=array(":year"=>array("value"=>date('Y'),"type"=>"int"));
									$bindPieArr[":month"]=array("value"=>date('m'),"type"=>"int");
									
									$bindBarArr=array(":year"=>array("value"=>date('Y'),"type"=>"int"));
									$bindBarArr[":month"]=array("value"=>date('m'),"type"=>"int");
								break;
								
			case '1st quarter':	$pie_F_budget_month=" and month(bd.budget_allocation_month) in (1,2,3) "; 
									$pie_F_expn_month=" and month(ed.expense_det_date) in (1,2,3) ";   
								break;
								
			case '2nd quarter':		$pie_F_budget_month=" and month(bd.budget_allocation_month) in (4,5,6) "; 
									$pie_F_expn_month=" and month(ed.expense_det_date) in (4,5,6) ";   
								break;
								
			case '3rd quarter':		$pie_F_budget_month=" and month(bd.budget_allocation_month) in (7,8,9) "; 
									$pie_F_expn_month=" and month(ed.expense_det_date) in (7,8,9) ";   
								break;
			case '4th quarter':		$pie_F_budget_month=" and month(bd.budget_allocation_month) in (10,11,12) "; 
									$pie_F_expn_month=" and month(ed.expense_det_date) in (10,11,12) ";   
								break;
		}
		
		
		
		if($call_chart=="pie")
		{   
		
			$pie_sql="select sum(budget_sum_amount) as budget_amount, sum(exp_sum_amount) as expense_amount from (
select  sum(bd.month_amount_inr) as budget_sum_amount, 0 as exp_sum_amount from bud_budget_allocation_details as bd where $pie_F_budget_yr $pie_F_budget_month union all 
select 0 as budget_sum_amount, sum(ed.expense_det_amount) as exp_sum_amount from bud_expenses as eh inner join bud_expense_details as ed on ed.expenses_id=eh.expenses_id where $pie_F_expn_yr $pie_F_expn_month and eh.expenses_status=1 ) as pie_qry "; 

			//echo $pie_sql.json_encode($bindPieArr);
			
			//$recs_pie = $this->pdoObj->fetchSingle($pie_sql, $bindPieArr); 
			
			$budget_amnount = $recs_pie["budget_amount"];
			$expense_amount = $recs_pie["expense_amount"];
			
			if(intval($budget_amnount) or intval($expense_amount))
			{
				$pieChartData = array('data'=>array($budget_amnount,$expense_amount), 'label'=>array('Budget','Expenses'), 'status'=>'success');
			}
			else
			{ 
				$pieChartData = array('data'=>array(), 'label'=>array('Budget','Expenses'), 'status'=>'success');
			}
		
		}
		
		if($call_chart=="bar")
		{ 
			
			if(trim(strtolower($dd_bar_exp_type))=='expenses by category')
			{  
			
				$useMonArr="no";
				
				$bar_sql="select sum(budget_sum_amount) as budget_amount, sum(exp_sum_amount) as expense_amount, cat.category_name as graph_series  from (
select  sum(bd.month_amount_inr) as budget_sum_amount, 0 as exp_sum_amount, subcategory_id as month_id from  bud_budget_allocation_details as bd  where $pie_F_budget_yr $pie_F_budget_month group by bd.subcategory_id

union all 
select 0 as budget_sum_amount, sum(ed.expense_det_amount) as exp_sum_amount, ed.subcategory_id as month_id  from bud_expenses as eh inner join bud_expense_details as ed on ed.expenses_id=eh.expenses_id where $pie_F_expn_yr $pie_F_expn_month and eh.expenses_status=1  group by ed.subcategory_id  ) as bar_qry left join bud_subcategory_master as scat on scat.subcategory_id=bar_qry.month_id left join  bud_category_master as cat on cat.category_id=scat.category_id   group by cat.category_id order by graph_series ";
			} 
			else
			{
				$bindBarArr=array(":year"=>array("value"=>$dd_year,"type"=>"int"));
				
				$useMonArr="use";
				
				$bar_sql="select sum(budget_sum_amount) as budget_amount, sum(exp_sum_amount) as expense_amount, month_id as graph_series from (
select  sum(bd.month_amount_inr) as budget_sum_amount, 0 as exp_sum_amount, month(budget_allocation_month) as month_id from bud_budget_allocation_details as bd where year(bd.budget_allocation_month)=:year group by year(budget_allocation_month), month(budget_allocation_month)  

union all 
select 0 as budget_sum_amount, sum(ed.expense_det_amount) as exp_sum_amount, month(ed.expense_det_date) as month_id  from bud_expenses as eh inner join bud_expense_details as ed on ed.expenses_id=eh.expenses_id where year(ed.expense_det_date)=:year and eh.expenses_status=1  group by year(ed.expense_det_date), month(ed.expense_det_date)  ) as bar_qry group by graph_series order by graph_series ";
			}
			 
			//$recs_bar = $this->pdoObj->fetchMultiple($bar_sql, $bindBarArr); 
			
			$monthSHarr=array(1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');
			
			$series=array();
			$grp_data=array();
			$lpsno=0;
			$totArr=array();
			foreach($recs_bar as $vals_bar)
			{
				if($useMonArr=="use")
				{
					$useVal = $monthSHarr[$vals_bar["graph_series"]];
				}
				else
				{
					$useVal = $this->purifyString($vals_bar["graph_series"]);
				}
				$series[$lpsno]=$useVal;
				
				$grp_data["budget"][$lpsno]=(float) $vals_bar["budget_amount"];
				$grp_data["expenses"][$lpsno]=(float) $vals_bar["expense_amount"];
				
				$totArr["budget"]+=$vals_bar["budget_amount"];
				$totArr["expenses"]+=$vals_bar["expense_amount"];
				
				$lpsno++;	
			}
			
			$barChartData = array('series'=>$series, 'data'=>$grp_data, 'sum_values'=>$totArr, 'status'=>'success');
	
		} 
		 
		
		/*$sql="select * from bud_budget_allocations where budget_allocation_year=:budget_allocation_year";
		$bindArr=array(":budget_allocation_year"=>array("value"=>$cmbbx_year,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); */
	  
		
		$sendRs=array("piechart"=>$pieChartData, "barchart"=>$barChartData); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	 
	public function statuswiselistview($postArr)
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
	 
		 
		   
		    $sql="select bk.booking_transaction_id, bk.sales_team, sls.sales_team_name, bk.order_status, bk.order_date, if((date_format(bk.order_date, '%m-%Y')= date_format(adddate(curdate(), interval -1 month), '%m-%Y')) and bk.order_status IN (1,3),'prev_month','') as last_month_yellow, ret.retail_id, par.parent_productline_name from srt_booking_transaction as bk left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id left join srt_parent_productline_master  as par on bk.parent_product_line=par.parent_productline_id where bk.is_deleted<>1  and ( date_format(bk.order_date, '%m-%Y')= date_format(curdate(), '%m-%Y') or ( date_format(bk.order_date, '%m-%Y')<= date_format(adddate(curdate(), interval -1 month), '%m-%Y')) and  bk.order_status in (1,3)  ) and bk.order_status<>4 $salesTeamfilt order by sls.sales_team_name, par.parent_productline_name, bk.order_date "; 
			//$sql.="LIMIT :limitstart_val, :limitend_val ";
			//$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			//$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			//echo $sql.json_encode($bindArr); 
			
			$sendArr=array('rsData'=>$recs,'status'=>'success'); 
			return $sendArr;
	} 
	public function srtaddnofferlistview($postArr)
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
	 
		 
		   
		    $sql="select  bk.sales_team, bk.parent_product_line , sls.sales_team_name,  par.parent_productline_name, sum(coalesce(bk.total_srt_addition,0)) as sum_srt_add, coalesce(avg(bk.total_srt_addition),0) as avg_srt_add, count(*) as srt_count  from srt_booking_transaction as bk left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id  left join srt_parent_productline_master  as par on bk.parent_product_line=par.parent_productline_id where bk.is_deleted<>1   and bk.order_status<>4 and coalesce(bk.total_srt_addition,0)>0 $salesTeamfilt group by bk.sales_team, bk.parent_product_line order by sls.sales_team_name,  par.parent_productline_name ";  // and  date_format(bk.order_date, '%m-%Y')= date_format(curdate(), '%m-%Y')
			//$sql.="LIMIT :limitstart_val, :limitend_val ";
			//$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			//$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr);  
			
			$sendArr=array('rsData'=>$recs,'status'=>'success'); 
			return $sendArr;
	} 
	public function stockballistview($postArr)
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
	 
		 
		   
		    $sql="select se.parent_productline_id, par.parent_productline_name, sum(if(se.stock_type=1,1,0)) as open_stock_cnt, sum(if(se.stock_type=2,1,0)) as gstock_cnt from srt_stock_master_entry as se left join srt_parent_productline_master as par on se.parent_productline_id=par.parent_productline_id  where se.is_deleted<>1   and coalesce(stock_chasis_used,0)=0 group by se.parent_productline_id order by par.parent_productline_name "; 
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