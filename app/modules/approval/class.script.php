<?php	

class approval extends common
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
		  
		  $paidstatus_filtval = $postArr['apprlist_filt_paidstatus'];	
		  $paidstatus_filt_txt=" having 1=2 ";
		  $paidstatus_filt_wr_txt="";
		  if($paidstatus_filtval==1)
		  { 
		  	$paidstatus_filt_txt=" having coalesce(sum(rcp.receipt_amount),0)>=bk.onroad_price ";  
		   }
		   else if($paidstatus_filtval==2)
		  { 
		  	 $paidstatus_filt_txt="";
		  	 $paidstatus_filt_wr_txt=" and finance_process_status=10 ";  
		   }
		   else if($paidstatus_filtval==3)
		  { 
		  	$paidstatus_filt_txt=" having coalesce(sum(rcp.receipt_amount),0)>=100000 ";  
		   }
		   else if($paidstatus_filtval==4)
		  { 
		  	$paidstatus_filt_txt=" having coalesce(sum(rcp.receipt_amount),0)>=50000 ";  
		   }
		  
		  $tot_sql="select  distinct bk.booking_transaction_id, bk.onroad_price from srt_booking_transaction as bk  left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) left join srt_finance_transaction as fintr on (bk.booking_transaction_id=fintr.booking_transaction_id) where bk.is_deleted<>1 $where $paidstatus_filt_wr_txt $salesTeamfilt group by bk.booking_transaction_id $paidstatus_filt_txt ";
		  //$rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  //$totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  $totalRows = $this->pdoObj->rowCount($tot_sql, $bindArr); 
		  
		   
		   $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date, bk.customer_name, bk.customer_mobile, prd.productline_name, ap.approved_by, ap.approved_date, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received, sls.sales_team_name, ca.employee_name as customer_advisor_name, orderstatus_name from srt_booking_transaction as bk  left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_approved_details as ap on bk.booking_transaction_id=ap.booking_transaction_id  left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1)  left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id   left join srt_finance_transaction as fintr on (bk.booking_transaction_id=fintr.booking_transaction_id)  where bk.is_deleted<>1 $where $paidstatus_filt_wr_txt $salesTeamfilt group by bk.booking_transaction_id  $paidstatus_filt_txt  order by order_date "; 
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
				$customer_name=$this->purifyString($rs["customer_name"]);
				$customer_mobile=$this->purifyString($rs["customer_mobile"]); 
				$productline_name=$this->purifyString($rs["productline_name"]);
				$approved_by=$this->purifyString($rs["approved_by"]);
				$approved_date=$this->convertDate($this->purifyString($rs["approved_date"])); 
				$sales_team_name = $this->purifyString($rs["sales_team_name"]);
				$customer_advisor_name = $this->purifyString($rs["customer_advisor_name"]);
				$orderstatus_name=$this->purifyString($rs["orderstatus_name"]);
				
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$total_offer =  number_format($this->purifyString($rs["total_offer"]),2);
				$bk_amount_received =  number_format($this->purifyString($rs["bk_amount_received"]),2);
				
				// <span class="delete act-delete"  onclick="viewDeleteApprovalMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
 
				$sendRs[$rsCnt]=array($PageSno+1,$order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $onroad_price, $total_offer, $bk_amount_received, $approved_by, $approved_date, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateApprovalMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
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
			
			$sql="select bk.*, ap.approved_by, ap.approved_date, ap.retail_status, ap.remark_desc, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received from srt_booking_transaction as bk left join srt_approved_details as ap on bk.booking_transaction_id=ap.booking_transaction_id  left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) where bk.booking_transaction_id=:booking_transaction_id group by bk.booking_transaction_id ";
			$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["booking_transaction_id"]); 
			
				$productline_id = $this->purifyString($recs["product_line"]);
			$sales_team_id = $this->purifyString($recs["sales_team"]);
			$order_status_id = $this->purifyString($recs["order_status"]);
			$parent_productline_id = $this->purifyString($recs["parent_product_line"]);
			$productcolour1_id = $this->purifyString($recs["product_color_primary"]);
			$productcolour2_id = $this->purifyString($recs["product_color_secondary"]);
			$productcolour3_id = $this->purifyString($recs["product_color_additional"]);
			$source_contact_id = $this->purifyString($recs["source_contact"]);
			
			$recs['order_date'] = $this->convertDate($recs["order_date"]);
			$recs['nominee_dob'] = $this->convertDate($recs["nominee_dob"]);
			$recs['dob'] = $this->convertDate($recs["dob"]);
			$recs['edd'] = $this->convertDate($recs["edd"]);
			$recs['revised_edd'] = $this->convertDate($recs["revised_edd"]);
			
			 
			$productline = $this->getModuleComboList('productline', $productline_id);
			$sales_team = $this->getModuleComboList('sales_team', $sales_team_id);
			$parent_product_line = $this->getModuleComboList('parent_productline', $parent_productline_id);
			$productcolour1 = $this->getModuleComboList('productcolour', $productcolour1_id);
			$productcolour2 = $this->getModuleComboList('productcolour', $productcolour2_id);
			$productcolour3 = $this->getModuleComboList('productcolour', $productcolour3_id);
			$order_statuslist = $this->getModuleComboList('order_status', $order_status_id, 'onlythisrec');
			$source_of_contactlist = $this->getModuleComboList('source_of_contact', $source_contact_id);
			$customer_advisorlist = $this->getModuleComboList('user', $source_contact_id);
			
			$recs['approved_date'] = $this->purifyString($this->convertDate($recs["approved_date"])); 
			
			
			
			$sendRs=$recs; 
			
			$combo_list = array('sales_team'=>$sales_team, 'productline'=>$productline, 'parent_product_line'=>$parent_product_line, 'productcolour1'=>$productcolour1, 'productcolour2'=>$productcolour2, 'productcolour3'=>$productcolour3, 'order_statuslist'=>$order_statuslist, 'source_of_contactlist'=>$source_of_contactlist, 'customer_advisorlist'=>$customer_advisorlist);
			
			$sendArr=array('rsData'=>$sendRs, 'combo_list'=>$combo_list, 'status'=>'success');  
			return $sendArr;
			//return json_encode($sendArr);
	}
	
	public function saveprocess($post_arr)
	{ 
		
		$id=$this->purifyInsertString($post_arr["hid_id"]);
		$get_arr['approved_by']=$this->htmpurify->purifier->purify($post_arr['approved_by']);
		$get_arr['approved_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['approved_date']));
		$get_arr['retail_status']=$this->htmpurify->purifier->purify($post_arr['retail_status']);
		$get_arr['remark_desc']=$this->htmpurify->purifier->purify($post_arr['remark_desc']);
		 
		$cnt_ext_sql="select count(*) as ext_cnt from srt_approved_details where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_approved_details SET approved_by=:approved_by, approved_date=:approved_date, retail_status=:retail_status, remark_desc=:remark_desc";
		
		$insBind=array( ":approved_by"=>array("value"=>$get_arr['approved_by'],"dtype"=>"text"), ":approved_date"=>array("value"=>$get_arr['approved_date'],"dtype"=>"text"), ":retail_status"=>array("value"=>$get_arr['retail_status'],"dtype"=>"tinyint"), ":remark_desc"=>array("value"=>$get_arr['remark_desc'],"dtype"=>"int"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_booking_transaction where trim(order_no)=:order_no  and is_deleted<>1 ";
		$bindExtChkArr=array(":order_no"=>array("value"=>$get_arr['order_no'],"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id "; 
			$opmsg="Approval details updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id, booking_transaction_id=:booking_transaction_id"; 
			
			
			$opmsg="Approval details inserted successfully!";
		} 
		 
		$opStatus='failure';
		$opMessage='failure';
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
		} 
	
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$bindArr=array();  
		
		//$strQuery=" delete from srt_booking_transaction $Setup_filt and booking_transaction_id=:booking_transaction_id "; 
		$strQuery=" update srt_booking_transaction set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where booking_transaction_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		//$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Approval details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array();
		  
		  
		  $whereor  ='';
		  
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or booking_transaction_id=:id)";
		  }
		 
		  $sql="select booking_transaction_id, booking_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_booking_transaction WHERE is_deleted<>1  $whereor  order by booking_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub Approval' as msg from srt_booking_transaction where booking_transaction_id=:id)"; 
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
			$_msg = "Approval cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	public function getBookingApprovalReceiptDetailsView($postArr)
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		
		$sql="select rcp.*, case rcp.payment_mode when 1 then 'Cash' when 2 then 'Bank' when 3 then 'Vehicle exchange' when 4 then fnam.financier_name else '' end as paymode_desc from srt_receipts_transaction as rcp left join srt_finance_transaction as ft on (rcp.booking_transaction_id=ft.booking_transaction_id and rcp.payment_mode=4 and rcp.finance_transaction_id=ft.finance_transaction_id) left join srt_financier_master as fnam on ft.financier_id=fnam.financier_id where rcp.booking_transaction_id=:id and rcp.amount_reveived_status=1 and rcp.is_deleted<>1";
		$bindArr=array( ":id"=>array("value"=>$getcid,"dtype"=>"int"));
		$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		
		return $recs;
	}
	
	public function __destruct() 
	{
		
	} 
}

?>