<?php	

class offer_approval extends common
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
		  
		  $off_approve_type_filt = $postArr['off_approve_type_filt'];	
		  $offertype_filter="";
		  if($off_approve_type_filt==1)
		  {  
		  	$offertype_filter=" and bk.order_status=1 and (coalesce(access_offer_srt,0)+coalesce(insurance_offer_srt,0)+coalesce(add_discount_srt,0)+coalesce(edr_srt,0)+coalesce(other_contribution_srt,0)+coalesce(total_srt_addition,0)) >0  and off_acc_approved_status not in (1,2,3) "; 
			 
		   }
		   elseif($off_approve_type_filt==2)
		  {  
			
		  	$offertype_filter=" and bk.order_status=1 and bk.off_acc_send_to_md=1 and bk.off_admin_approved_status<>1 ";  
		   }
		    elseif($off_approve_type_filt==3)
		  {  
			
		  	$offertype_filter=" and (bk.off_acc_approved_status=1 or bk.off_admin_approved_status=1 ) ";  
		   }
		   else
		   {
		   	$offertype_filter=" and 1=2";
		   }
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk where bk.is_deleted<>1 $where $offertype_filter $salesTeamfilt ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  
		 // $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0) as total_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1)  where bk.is_deleted<>1 $where $offertype_filter and off_acc_approved_status!=1 and off_admin_approved_status!=1 group by bk.booking_transaction_id  order by order_date "; 
		  
		   $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, bk.off_acc_approved_status, bk.off_admin_approved_status, ca.employee_name as customer_advisor_name from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id where bk.is_deleted<>1 $where $offertype_filter $salesTeamfilt order by order_date "; 
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
				
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$total_offer =  number_format($this->purifyString($rs["total_offer"]),2);
				//$bk_amount_received =  number_format($this->purifyString($rs["bk_amount_received"]),2);
				
				$apprv_by="";
				if($rs["off_acc_approved_status"]==1) $apprv_by="Accounts";
				else if($rs["off_admin_approved_status"]==1) $apprv_by="MD";
				
				
				// <span class="delete act-delete"  onclick="viewDeleteOfferapprovalMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
 
				$sendRs[$rsCnt]=array($PageSno+1, $order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $onroad_price, $total_offer, $apprv_by, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateOfferapprovalMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
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
			
			$sql="select bk.*  from srt_booking_transaction as bk where bk.booking_transaction_id=:booking_transaction_id";
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
			$recs['approved_date'] = $this->purifyString($this->convertDate($recs["approved_date"])); 
			$recs['off_acc_approved_date'] = $this->purifyString($this->convertDate($recs["off_acc_approved_date"])); 
			$recs['off_admin_approved_date'] = $this->purifyString($this->convertDate($recs["off_admin_approved_date"])); 
			
			 
			
			$productline = $this->getModuleComboList('productline', $productline_id);
			$sales_team = $this->getModuleComboList('sales_team', $sales_team_id);
			$parent_product_line = $this->getModuleComboList('parent_productline', $parent_productline_id);
			$productcolour1 = $this->getModuleComboList('productcolour', $productcolour1_id);
			$productcolour2 = $this->getModuleComboList('productcolour', $productcolour2_id);
			$productcolour3 = $this->getModuleComboList('productcolour', $productcolour3_id);
			$order_statuslist = $this->getModuleComboList('order_status', $order_status_id, 'onlythisrec');
			$source_of_contactlist = $this->getModuleComboList('source_of_contact', $source_contact_id);
			$customer_advisorlist = $this->getModuleComboList('user', $source_contact_id);
			
			 
			
			
			
			
			$sendRs=$recs; 
			
			$combo_list = array('sales_team'=>$sales_team, 'productline'=>$productline, 'parent_product_line'=>$parent_product_line, 'productcolour1'=>$productcolour1, 'productcolour2'=>$productcolour2, 'productcolour3'=>$productcolour3, 'order_statuslist'=>$order_statuslist, 'source_of_contactlist'=>$source_of_contactlist, 'customer_advisorlist'=>$customer_advisorlist);
			
			$sendArr=array('rsData'=>$sendRs, 'combo_list'=>$combo_list, 'status'=>'success');  
			return $sendArr;
			//return json_encode($sendArr);
	}
	
	public function saveprocess($post_arr)
	{  
		$id=$this->purifyInsertString($post_arr["hid_id"]);
		$get_arr['off_approve_type']=$this->htmpurify->purifier->purify($post_arr['off_approve_type']);
		
		$get_arr['off_acc_approved_status']=$this->htmpurify->purifier->purify($post_arr['off_acc_approved_status']);
		$get_arr['off_acc_approved_by']=$this->htmpurify->purifier->purify($post_arr['off_acc_approved_by']);
		$get_arr['off_acc_approved_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['off_acc_approved_date']));
		$get_arr['off_acc_send_to_md']=$this->htmpurify->purifier->purify($post_arr['off_acc_send_to_md']);
		$get_arr['off_acc_approved_desc']=$this->htmpurify->purifier->purify($post_arr['off_acc_approved_desc']);
		
		$get_arr['off_admin_approved_status']=$this->htmpurify->purifier->purify($post_arr['off_admin_approved_status']);
		$get_arr['off_admin_approved_by']=$this->htmpurify->purifier->purify($post_arr['off_admin_approved_by']);
		$get_arr['off_admin_approved_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['off_admin_approved_date'])); 
		 
		
		if($get_arr['off_approve_type']==1)
		{
			if($get_arr['off_acc_approved_status']!=2) $get_arr['off_acc_send_to_md']=0;
			
			$strQuery="UPDATE srt_booking_transaction SET off_acc_approved_status=:off_acc_approved_status, off_acc_approved_by=:off_acc_approved_by, off_acc_approved_date=:off_acc_approved_date, off_acc_send_to_md=:off_acc_send_to_md, off_acc_approved_desc=:off_acc_approved_desc, off_acc_approved_logdate=now() where booking_transaction_id=:booking_transaction_id "; 
			
			$insBind=array( ":off_acc_approved_status"=>array("value"=>$get_arr['off_acc_approved_status'],"dtype"=>"int"), ":off_acc_approved_by"=>array("value"=>$get_arr['off_acc_approved_by'],"dtype"=>"text"), ":off_acc_approved_date"=>array("value"=>$get_arr['off_acc_approved_date'],"dtype"=>"text"), ":off_acc_send_to_md"=>array("value"=>$get_arr['off_acc_send_to_md'],"dtype"=>"int"), ":off_acc_approved_desc"=>array("value"=>$get_arr['off_acc_approved_desc'],"dtype"=>"text"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int")); 
			
			$opmsg="Offer approval details updated successfully!";
			
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			$this->updateBookingStatusToBooked($id); // written in common file
		} 
		else if($get_arr['off_approve_type']==2)
		{
			$strQuery="UPDATE srt_booking_transaction SET off_admin_approved_status=:off_admin_approved_status, off_admin_approved_by=:off_admin_approved_by, off_admin_approved_date=:off_admin_approved_date, off_admin_approved_logdate=now() where booking_transaction_id=:booking_transaction_id "; 
			
			$insBind=array( ":off_admin_approved_status"=>array("value"=>$get_arr['off_admin_approved_status'],"dtype"=>"int"), ":off_admin_approved_by"=>array("value"=>$get_arr['off_admin_approved_by'],"dtype"=>"text"), ":off_admin_approved_date"=>array("value"=>$get_arr['off_admin_approved_date'],"dtype"=>"text"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int")); 
			
			$opmsg="Offer approval details updated successfully!";
			
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			$this->updateBookingStatusToBooked($id); // written in common file
		} 
	 
		 
		$opStatus='failure';
		$opMessage='failure'; 
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
			
			$get_arr['access_offer_srt']=$this->htmpurify->purifier->purify($post_arr['access_offer_srt']);
			$get_arr['insurance_offer_srt']=$this->htmpurify->purifier->purify($post_arr['insurance_offer_srt']);
			$get_arr['add_discount_srt']=$this->htmpurify->purifier->purify($post_arr['add_discount_srt']);
			$get_arr['other_contribution_srt']=$this->htmpurify->purifier->purify($post_arr['other_contribution_srt']);
			$get_arr['total_srt']=$this->htmpurify->purifier->purify($post_arr['total_srt']);
			
			$get_arr['cosumer_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['cosumer_offer_srt_addition']);
			$get_arr['corporate_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['corporate_offer_srt_addition']);
			$get_arr['exchange_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['exchange_offer_srt_addition']);
			$get_arr['access_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['access_offer_srt_addition']);
			$get_arr['insurance_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['insurance_offer_srt_addition']);
			$get_arr['add_discount_srt_addition']=$this->htmpurify->purifier->purify($post_arr['add_discount_srt_addition']);
			$get_arr['edr_srt_addition']=$this->htmpurify->purifier->purify($post_arr['edr_srt_addition']);
			$get_arr['other_contribution_srt_addition']=$this->htmpurify->purifier->purify($post_arr['other_contribution_srt_addition']);
			$get_arr['total_srt_addition']=$this->htmpurify->purifier->purify($post_arr['total_srt_addition']);
			
			$updatebkamntsql=" update srt_booking_transaction SET access_offer_srt=:access_offer_srt, insurance_offer_srt=:insurance_offer_srt, add_discount_srt=:add_discount_srt, add_discount_srt=:add_discount_srt, other_contribution_srt=:other_contribution_srt, total_srt=:total_srt, cosumer_offer_srt_addition=:cosumer_offer_srt_addition, corporate_offer_srt_addition=:corporate_offer_srt_addition, exchange_offer_srt_addition=:exchange_offer_srt_addition, access_offer_srt_addition=:access_offer_srt_addition, insurance_offer_srt_addition=:insurance_offer_srt_addition, add_discount_srt_addition=:add_discount_srt_addition, edr_srt_addition=:edr_srt_addition, other_contribution_srt_addition=:other_contribution_srt_addition, total_srt_addition=:total_srt_addition  where booking_transaction_id=:booking_transaction_id ";
			
			$updatebkamntarr=array( ":booking_transaction_id"=>array("value"=>$id,"dtype"=>"int"), ":access_offer_srt"=>array("value"=>$get_arr['access_offer_srt'],"dtype"=>"text"), ":insurance_offer_srt"=>array("value"=>$get_arr['insurance_offer_srt'],"dtype"=>"text"), ":add_discount_srt"=>array("value"=>$get_arr['add_discount_srt'],"dtype"=>"text"), ":other_contribution_srt"=>array("value"=>$get_arr['other_contribution_srt'],"dtype"=>"text"), ":total_srt"=>array("value"=>$get_arr['total_srt'],"dtype"=>"text"), ":cosumer_offer_srt_addition"=>array("value"=>$get_arr['cosumer_offer_srt_addition'],"dtype"=>"text"), ":corporate_offer_srt_addition"=>array("value"=>$get_arr['corporate_offer_srt_addition'],"dtype"=>"text"), ":exchange_offer_srt_addition"=>array("value"=>$get_arr['exchange_offer_srt_addition'],"dtype"=>"text"), ":access_offer_srt_addition"=>array("value"=>$get_arr['access_offer_srt_addition'],"dtype"=>"text"), ":insurance_offer_srt_addition"=>array("value"=>$get_arr['insurance_offer_srt_addition'],"dtype"=>"text"), ":add_discount_srt_addition"=>array("value"=>$get_arr['add_discount_srt_addition'],"dtype"=>"text"), ":edr_srt_addition"=>array("value"=>$get_arr['edr_srt_addition'],"dtype"=>"text"), ":other_contribution_srt_addition"=>array("value"=>$get_arr['other_contribution_srt_addition'],"dtype"=>"text"), ":total_srt_addition"=>array("value"=>$get_arr['total_srt_addition'],"dtype"=>"text") ); 
			
			$this->pdoObj->execute($updatebkamntsql, $updatebkamntarr);
			
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
			$opMessage='Offerapproval details deleted successfully'; 
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
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub Offerapproval' as msg from srt_booking_transaction where booking_transaction_id=:id)"; 
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
			$_msg = "Offerapproval cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	public function getBookingOfferapprovalReceiptDetailsView($postArr)
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