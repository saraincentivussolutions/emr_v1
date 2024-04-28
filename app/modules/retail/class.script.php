<?php	

class retail extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{  
		  $getStkBkids=$this->getChasisNoArrdetails('from_retail'); 
	      $bkComaprearr=$getStkBkids['book_compare_arr'];
		  
		  
		  
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
				$where = 'and (bk.order_no like :search_str or bk.customer_name like :search_str or bk.customer_mobile like :search_str) '; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk inner join srt_approved_details as app on bk.booking_transaction_id=app.booking_transaction_id left join srt_retail as vex on bk.booking_transaction_id=vex.booking_transaction_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id   where bk.is_deleted<>1 and app.retail_status=1 $where and bk.order_status in (3,5,6,7) and coalesce(ret.retail_id,0)=0  $salesTeamfilt";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select bk.booking_transaction_id, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, fin.finance_amount, vex.exchange_price, coalesce(bk.total_tata,0)+coalesce(bk.total_srt,0) as offer_amount, ca.employee_name as customer_advisor_name, clr.productcolour_name as primary_colour,onroad_price, fmas.financier_name  from  srt_booking_transaction as bk inner join srt_approved_details as app on bk.booking_transaction_id=app.booking_transaction_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id left join srt_vehicle_exchange as vex on bk.booking_transaction_id=vex.booking_transaction_id  left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id  left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id left join srt_productcolour_master as clr on bk.product_color_primary=clr.productcolour_id left join srt_financier_master as fmas on fin.financier_id=fmas.financier_id where bk.is_deleted<>1 and app.retail_status=1 $where  and bk.order_status in (3,5,6,7) and coalesce(ret.retail_id,0)=0 $salesTeamfilt order by bk.order_no "; 
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
				
				$finance_amount=$this->purifyString($rs["finance_amount"]);
				$exchange_amount=$this->purifyString($rs["exchange_price"]); 
				$offer_amount=$this->purifyString($rs["offer_amount"]);  
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$financier_name = $this->purifyString($rs["financier_name"]);
				
				$avail_status="Not available";
				if($bkComaprearr[$rs["booking_transaction_id"]]) $avail_status="Aavailable";
				
				// <span class="delete act-delete"  onclick="viewDeleteRetailListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
				 
				$sendRs[$rsCnt]=array($PageSno+1,$order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $primary_colour,  $onroad_price, $financier_name , $avail_status, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateRetailListMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
				$rsCnt++;
				$PageSno++;
				
			} 
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	public function completedlistview($postArr)
	{  
		  $bindArr=array(); 
		  
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
				$where = 'and (bk.order_no like :search_str or bk.customer_name like :search_str or bk.customer_mobile like :search_str) '; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk inner join srt_approved_details as app on bk.booking_transaction_id=app.booking_transaction_id left join srt_retail as vex on bk.booking_transaction_id=vex.booking_transaction_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id   where bk.is_deleted<>1 and app.retail_status=1 $where and bk.order_status in (3,5,6,7) and coalesce(ret.retail_id,0)>0 ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select bk.booking_transaction_id, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, fin.finance_amount, vex.exchange_price, coalesce(bk.total_tata,0)+coalesce(bk.total_srt,0)+coalesce(total_srt_addition,0) as offer_amount, ca.employee_name as customer_advisor_name, clr.productcolour_name as primary_colour,onroad_price, fmas.financier_name, ret.invoice_no, stk.chasis_no  from  srt_booking_transaction as bk inner join srt_approved_details as app on bk.booking_transaction_id=app.booking_transaction_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id left join srt_vehicle_exchange as vex on bk.booking_transaction_id=vex.booking_transaction_id  left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id  left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id left join srt_productcolour_master as clr on bk.product_color_primary=clr.productcolour_id left join srt_financier_master as fmas on fin.financier_id=fmas.financier_id left join srt_stock_master_entry as stk on ret.stock_chasis_id=stk.stock_master_entry_id where bk.is_deleted<>1 and app.retail_status=1 $where  and bk.order_status in (3,5,6,7) and coalesce(ret.retail_id,0)>0 order by bk.order_no "; 
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
				$invoice_no = $this->purifyString($rs["invoice_no"]);
				$chasis_no = $this->purifyString($rs["chasis_no"]);
				
				$finance_amount=$this->purifyString($rs["finance_amount"]);
				$exchange_amount=$this->purifyString($rs["exchange_price"]); 
				$offer_amount=$this->purifyString($rs["offer_amount"]);  
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$financier_name = $this->purifyString($rs["financier_name"]);
				
				$avail_status="";
				
				// <span class="delete act-delete"  onclick="viewDeleteRetailListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
				 
				$sendRs[$rsCnt]=array($PageSno+1,$order_date, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $primary_colour, $chasis_no, $invoice_no, $financier_name , '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateRetailListMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
				$rsCnt++;
				$PageSno++;
				
			} 
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	public function getSingleView($postArr)
	{
			$getcid=$this->purifyInsertString($postArr["id"]);
			
			$sql="select retail_id, bk.booking_transaction_id, bk.order_no, ret.payment_received, ret.vehicle_allotted, ret.stock_type, ret.invoice_no, ret.invoice_date, ret.rto_approved, ret.rto_date, fin.finance_amount, vex.exchange_price, coalesce(bk.total_tata,0)+coalesce(bk.total_srt,0)+coalesce(total_srt_addition,0) as offer_amount, ret.stock_status, ret.stock_chasis_id, stk.chasis_no as stock_chasis_no from srt_booking_transaction as bk inner join srt_approved_details as app on bk.booking_transaction_id=app.booking_transaction_id left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id left join srt_vehicle_exchange as vex on bk.booking_transaction_id=vex.booking_transaction_id left join srt_stock_master_entry as stk on ret.stock_chasis_id=stk.stock_master_entry_id  where bk.booking_transaction_id=:booking_transaction_id";
			$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$retail_id=$this->purifyString($recs["retail_id"]);
			$booking_transaction_id=$this->purifyString($recs["booking_transaction_id"]);
			$order_no=$this->purifyString($recs["order_no"]);
			$payment_received=$this->purifyString($recs["payment_received"]);
			$vehicle_allotted=$this->purifyString($recs["vehicle_allotted"]);
			$stock_type=$this->purifyString($recs["stock_type"]); 
			$invoice_no=$this->purifyString($recs["invoice_no"]);
			$invoice_date=$this->purifyString($this->convertDate($recs["invoice_date"]));  
			$rto_approved=$this->purifyString($recs["rto_approved"]); 
			$rto_date=$this->purifyString($this->convertDate($recs["rto_date"]));  
			
			$finance_amount=$recs["finance_amount"];
			$exchange_amount=$recs["exchange_price"];
			$offer_amount=$recs["offer_amount"];
			$stock_status=$recs["stock_status"];
			$stock_chasis_id=$recs["stock_chasis_id"];
			$stock_chasis_no=$recs["stock_chasis_no"]; 
			 
			
			$sendRs=array("retail_id"=>$retail_id, "booking_transaction_id"=>$booking_transaction_id, "order_no"=>$order_no, "payment_received"=>$payment_received, "vehicle_allotted"=>$vehicle_allotted, "stock_type"=>$stock_type, "invoice_no"=>$invoice_no, "invoice_date"=>$invoice_date, "rto_approved"=>$rto_approved, "rto_date"=>$rto_date, "finance_amount"=>$finance_amount, "exchange_amount"=>$exchange_amount, "offer_amount"=>$offer_amount, "stock_status"=>$stock_status, "stock_chasis_id"=>$stock_chasis_id, "stock_chasis_no"=>$stock_chasis_no); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$payment_received=$this->purifyInsertString($postArr["payment_received"]); 
		$vehicle_allotted=$this->purifyInsertString($postArr["vehicle_allotted"]);		
		$stock_type=$this->purifyInsertString($postArr["stock_type"]); 
		$invoice_no=$this->purifyInsertString($postArr["invoice_no"]); 
		$invoice_date=$this->convertDate($this->htmpurify->purifier->purify($postArr['invoice_date']));
		$rto_approved=$this->purifyInsertString($postArr["rto_approved"]);		
		$rto_date=$this->convertDate($this->htmpurify->purifier->purify($postArr['rto_date'])); 
		 	
		$stock_status=$this->purifyInsertString($postArr["stock_status"]);	
		$stock_chasis_id=$this->purifyInsertString($postArr["stock_chasis_id"]);
		/*if($stock_type!=1)
		{
			$stock_status=0;
			$stock_chasis_id=0;
		} */
		 
		
		$cnt_ext_sql="select retail_id,stock_chasis_id from srt_retail where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["retail_id"];
		$db_stock_chasis_id=$rs_qry_exts["stock_chasis_id"];
		
		$ins=" srt_retail SET payment_received=:payment_received, vehicle_allotted=:vehicle_allotted, stock_type=:stock_type, invoice_no=:invoice_no,  invoice_date=:invoice_date, rto_approved=:rto_approved, rto_date=:rto_date, stock_status=:stock_status, stock_chasis_id=:stock_chasis_id";
		
		$insBind=array(":payment_received"=>array("value"=>$payment_received,"type"=>"int"), ":vehicle_allotted"=>array("value"=>$vehicle_allotted,"type"=>"int")	, ":stock_type"=>array("value"=>$stock_type,"type"=>"int"), ":invoice_no"=>array("value"=>$invoice_no,"type"=>"text"), ":invoice_date"=>array("value"=>$invoice_date,"type"=>"text"), ":rto_approved"=>array("value"=>$rto_approved,"type"=>"text"), ":rto_date"=>array("value"=>$rto_date,"type"=>"text"), ":stock_status"=>array("value"=>$stock_status,"type"=>"int"), ":stock_chasis_id"=>array("value"=>$stock_chasis_id,"type"=>"int"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));   
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id "; 
			$opmsg="Vehicle Exchange details updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id, booking_transaction_id=:booking_transaction_id ";   
			$opmsg="Vehicle Exchange details inserted successfully!";
		} 
		
		$opStatus='failure';
		$opMessage='failure';
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
			
			if($stock_chasis_id)
			{
				if($db_stock_chasis_id!=$stock_chasis_id and $db_stock_chasis_id)
				{
					$srtup="update srt_stock_master_entry set stock_chasis_used=0 where stock_master_entry_id='$db_stock_chasis_id'";
					$this->pdoObj->execute($srtup);
				}
				$srtup="update srt_stock_master_entry set stock_chasis_used=1 where stock_master_entry_id='$stock_chasis_id'";
				$this->pdoObj->execute($srtup);
			}
			else
			{
				if($db_stock_chasis_id)
				{
					$srtup="update srt_stock_master_entry set stock_chasis_used=0 where stock_master_entry_id='$db_stock_chasis_id'";
					$this->pdoObj->execute($srtup);
				}
			} 
			if($invoice_no!="")
			{
				$upordSts="5"; //Retail -O
				if($stock_status==2) $upordSts="7";  //Retail -G
				$bkstsup="update srt_booking_transaction set order_status='{$upordSts}' where booking_transaction_id=:booking_transaction_id and order_status =3 ";
				$bindBkstsArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
				$this->pdoObj->execute($bkstsup, $bindBkstsArr);
			}
			
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);  
		
		$bindArr=array();  
		
		//$strQuery=" delete from srt_retail $Setup_filt and retail_id=:retail_id "; 
		$strQuery=" update srt_retail set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where retail_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		//$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Vehicle Exchange details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array(); 
		  
		  $whereor = ' and active_status = 1 ';
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or retail_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(retail_id,0)>0 ";
		  }
		  $sql="select retail_id, finance_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_retail where 1 $whereor and is_deleted<>1 order by finance_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'RetailList referring Sub RetailList' as msg from bud_subRetailList_master where retail_id=:id) union all (select count(*) as ext_cnt, 'RetailList linked to Expenses' as msg from bud_expense_details where retail_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		//$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete?';
		foreach($rs_qry_exts as $rsop)
		{
			if($rsop['ext_cnt']>0)
			{
				$msgArr[] = $rsop['msg'];
				$_cnt++;
			}
		}
		
		if($_cnt>0)
		{
			$status = 'failure';
			//$_msg = implode("\n",$msgArr);
			$_msg = "Vehicle ExchangeList cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function getStockListDetails($postArr)
	{
		$stock_type = $postArr['stock_type'];
		$booking_id = $postArr['hdn_booking_id'];
		
		$bindArr=array(); 
		  
	  $draw = $postArr['draw'];
	  $start = ($postArr['start'])?$postArr['start']:0;
	  $limit = ($postArr['length'])?$postArr['length']:0;
	  $order = ($postArr['order'])?$postArr['order']:array();
	  
	  $orderColumn = $order[0]['column'];
	  $orderColumn_sort = $order[0]['dir'];
	  
	  $orderQry = ' order by stck.stock_master_entry_date asc';
	  if($orderColumn == 1)
	  {
	  	 if( $orderColumn_sort == 'desc')
		 {
		 	$orderQry = ' order by stck.stock_master_entry_date desc';
		 }
	  }
	  
	  $start=(int) $start; 
	  $limit=(int) $limit; 
	 
	  $search = ($postArr['search']['value']);	  
	  $where = "";
	  if($search)
	  {
			$query_val = "%".$search."%"; 
			$where .= 'and (stck.chasis_no like :search_str) '; 
			$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
	  }
	  
	   if($stock_type)
	  {
			
			$where .= 'and (stck.stock_type = :stock_type) '; 
			$bindArr[':stock_type']=array("value"=>$stock_type,"type"=>"text");
	  }
	  
	   if($booking_id)
	  {
			
			$where .= 'and (bok.booking_transaction_id = :booking_id) '; 
			$bindArr[':booking_id']=array("value"=>$booking_id,"type"=>"int");
	  }
	  
	  $tot_sql="select count(*) as cnt from srt_stock_master_entry stck left join srt_booking_transaction bok on (stck.productline_id = bok.product_line and stck.productcolour_id=bok.product_color_primary) where stck.is_deleted=0 and stck.stock_chasis_used=0 $where ";
	  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
	  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
	$sql = "select stck.chasis_no, stck.stock_master_entry_date, stck.purchase_cost, stck.stock_master_entry_id, TIMESTAMPDIFF( YEAR, stock_master_entry_date, now() ) as age_yr, TIMESTAMPDIFF( MONTH, stock_master_entry_date, now() ) % 12 as age_month,FLOOR( TIMESTAMPDIFF( DAY, stock_master_entry_date, now() ) % 30.4375 ) as age_days from srt_stock_master_entry stck left join srt_booking_transaction bok on (stck.productline_id = bok.product_line and stck.productcolour_id=bok.product_color_primary) where stck.is_deleted=0 and stck.stock_chasis_used=0 $where $orderQry  ";
	
	$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["stock_master_entry_id"]);
				$chasis_no=$this->purifyString($rs["chasis_no"]);
				$stock_master_entry_date=$this->convertDate($this->purifyString($rs["stock_master_entry_date"]));
				$purchase_cost=$this->purifyString($rs["purchase_cost"]);
				
				$stock_product_age='';				
				$age_yr=$this->purifyString($rs["age_yr"]);
				$age_month=$this->purifyString($rs["age_month"]);
				$age_days=$this->purifyString($rs["age_days"]);
				
				if($age_yr) $stock_product_age.=" $age_yr years";
				if($age_month) $stock_product_age.=" $age_month months";
				if($age_days) $stock_product_age.=" $age_days days";
				
				$stock_product_age=trim($stock_product_age);
				
				
				// <span class="delete act-delete"  onclick="viewDeleteRetailListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
				 
				$sendRs[$rsCnt]=array($PageSno+1,$stock_master_entry_date, $stock_product_age, $chasis_no, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="selectStockChasisNo('.$cid.');"><i class="fa fa-hand-o-right"></i> Pick </span>', "DT_RowId"=>"row_$cid");
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