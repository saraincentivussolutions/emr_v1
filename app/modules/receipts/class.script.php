<?php	

class receipts extends common
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
		  
		  $ordstatus_filtval = $postArr['rcptlist_filt_status'];	
		  $ordstatus_filter="";
		  if($ordstatus_filtval)
		  { 
		  	if($ordstatus_filtval=="BRcons")
			{
				$ordstatus_filter=" and rcp.amount_reveived_status<>1"; 
			}
			else
			{
				$ordstatus_filter=" and bk.order_status=:statusfilt"; 
				$bindArr[':statusfilt']=array("value"=>$ordstatus_filtval,"type"=>"int"); 
			}
		   }
		  
		  $tot_sql="select count(distinct bk.booking_transaction_id) as cnt from srt_booking_transaction as bk left join srt_receipts_transaction as rcp on bk.booking_transaction_id=rcp.booking_transaction_id where bk.is_deleted<>1 $where $ordstatus_filter $salesTeamfilt ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received, ca.employee_name as customer_advisor_name from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and rcp.is_deleted<>1)  left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id  where bk.is_deleted<>1 $where $ordstatus_filter $salesTeamfilt group by bk.booking_transaction_id order by order_date "; 
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
				$bk_amount_received =  number_format($this->purifyString($rs["bk_amount_received"]),2);
				
				// <span class="delete act-delete"  onclick="viewDeleteReceiptsMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span> // delete will be inside addedit page
				//$sendRs[$rsCnt]=array("booking_transaction_id"=>$cid,"booking_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $onroad_price, $total_offer, $bk_amount_received, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateReceiptsMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
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
			$where = '';
			 $booking_transaction_id = $postArr['hid_booking_id'];
			 if($booking_transaction_id)
			 {
				$where .= ' and rec.booking_transaction_id =:booking_transaction_id';
				$bindArr[':booking_transaction_id'] = array("value"=>$booking_transaction_id,"type"=>"int");
			 }
			// if($getcid)
			 {
			 	$where .= ' and receipt_transaction_id =:receipt_transaction_id';
				$bindArr[":receipt_transaction_id"]=array("value"=>$getcid,"type"=>"int");
			 }
			 $sql="select bk.booking_transaction_id, bk.order_no, entry_date, entry_by, rec.receipt_transaction_id, rec.receipt_no, rec.receipt_date, rec.receipt_amount, rec.payment_mode, rec.receipt_remarks, rec.amount_reveived_status, rec.chque_dd_type, rec.bank_name, rec.cheque_no from srt_receipts_transaction rec  left join srt_booking_transaction as bk on rec.booking_transaction_id = bk.booking_transaction_id where 1 $where order by entry_date "; 
			 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		 
			$cid=$this->purifyString($recs["receipt_transaction_id"]);
			
			$recs['entry_date']=$this->convertDate($this->htmpurify->purifier->purify($recs['entry_date']));
			$recs['receipt_date']=$this->convertDate($this->htmpurify->purifier->purify($recs['receipt_date'])); 
			
			if(!$cid)
			{
				$recs['entry_date']=$this->convertDate(date('Y-m-d'));
				$recs['receipt_date']=$this->convertDate(date('Y-m-d')); 
			}
			
			$sendRs=$recs; 
			
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr);
			$sql_order = "select bk.order_no from srt_booking_transaction as bk where booking_transaction_id =:booking_transaction_id"; 
			$bindArr = array(':booking_transaction_id'=>array("value"=>$booking_transaction_id,"type"=>"int"));
			$rs_order = $this->pdoObj->fetchSingle($sql_order, $bindArr);
			
			$order_no = $rs_order['order_no'];
			
			
			
			$sendArr=array('rsData'=>$sendRs, 'status'=>'success', 'order_no'=>$order_no);  
			//return $sendArr;
			return json_encode($sendArr);
	}
	
	public function saveprocess($post_arr)
	{
		
		
		$id=$this->purifyInsertString($post_arr["hid_id"]);
		$hid_booking_id=$this->purifyInsertString($post_arr["hid_booking_id"]);
		$get_arr['entry_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['entry_date']));
		$get_arr['entry_by']=$this->htmpurify->purifier->purify($post_arr['entry_by']);
		$get_arr['receipt_no']=$this->htmpurify->purifier->purify($post_arr['receipt_no']);
		$get_arr['receipt_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['receipt_date']));
		$get_arr['payment_mode']=$this->htmpurify->purifier->purify($post_arr['payment_mode']);
		$get_arr['receipt_amount']=$this->htmpurify->purifier->purify($post_arr['receipt_amount']);
		$get_arr['receipt_remarks']=$this->htmpurify->purifier->purify($post_arr['receipt_remarks']);
		
		$get_arr['chque_dd_type']=$this->htmpurify->purifier->purify($post_arr['chque_dd_type']);
		$get_arr['bank_name']=$this->htmpurify->purifier->purify($post_arr['bank_name']);
		$get_arr['cheque_no']=$this->htmpurify->purifier->purify($post_arr['cheque_no']);
		
		$get_arr['amount_reveived_status']=0;
		if($get_arr['payment_mode']==1)
		{
			$get_arr['amount_reveived_status']=1;
			$get_arr['chque_dd_type']=0;
			$get_arr['bank_name']='';
			$get_arr['cheque_no']='';
		}
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_receipts_transaction where receipt_transaction_id=:receipt_transaction_id "; 
		$bindExtCntArr=array(":receipt_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins="  srt_receipts_transaction SET entry_date=:entry_date, entry_by=:entry_by, receipt_date=:receipt_date, payment_mode=:payment_mode, receipt_amount=:receipt_amount, receipt_remarks=:receipt_remarks, amount_reveived_status=:amount_reveived_status, chque_dd_type=:chque_dd_type, bank_name=:bank_name, cheque_no=:cheque_no";
		
		$insBind=array(":booking_transaction_id"=>array("value"=>$hid_booking_id,"dtype"=>"int"), ":entry_date"=>array("value"=>$get_arr['entry_date'],"dtype"=>"text"), ":entry_by"=>array("value"=>$get_arr['entry_by'],"dtype"=>"text"), ":receipt_date"=>array("value"=>$get_arr['receipt_date'],"dtype"=>"text"), ":payment_mode"=>array("value"=>$get_arr['payment_mode'],"dtype"=>"int"), ":receipt_amount"=>array("value"=>$get_arr['receipt_amount'],"dtype"=>"text"), ":receipt_remarks"=>array("value"=>$get_arr['receipt_remarks'],"dtype"=>"text"), ":amount_reveived_status"=>array("value"=>$get_arr['amount_reveived_status'],"dtype"=>"int"), ":chque_dd_type"=>array("value"=>$get_arr['chque_dd_type'],"dtype"=>"int"), ":bank_name"=>array("value"=>$get_arr['bank_name'],"dtype"=>"text"), ":cheque_no"=>array("value"=>$get_arr['cheque_no'],"dtype"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		//, receipt_no=:receipt_no   , ":receipt_no"=>array("value"=>$get_arr['receipt_no'],"dtype"=>"text") auto generated number
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_receipts_transaction where trim(receipt_no)=:receipt_no  and is_deleted<>1 ";
		$bindExtChkArr=array(":receipt_no"=>array("value"=>$get_arr['receipt_no'],"dtype"=>"text")); 
		
		$insUp="";
		if($ext_cnt_val>0) 
		{ 
			//$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id and receipt_transaction_id=:receipt_transaction_id";
			$insBind[":receipt_transaction_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and receipt_transaction_id<>:receipt_transaction_id ";
			$bindExtChkArr[":receipt_transaction_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Receipts updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),booking_transaction_id=:booking_transaction_id, createdby=:sess_user_id"; 
			
			$insUp="insert";
			$opmsg="Receipts inserted successfully!";
		}
		
		//$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		//$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];
		$rec_exist_cnt_val=0;  
		 
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Record already exists'; 
			$opExists='exists';
		} 
		else 
		{
			//echo $strQuery.json_encode($insBind);
			if($insUp=="insert") //Currently no update
			{
				$exec = $this->pdoObj->execute($strQuery, $insBind);
			}
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
				
				if($insUp=="insert")
				{
					$rcno_sql="update srt_receipts_transaction set receipt_no=receipt_transaction_id where coalesce(receipt_no,'')='' ";
					$rcno_arr=array();  
					$this->pdoObj->execute($rcno_sql, $rcno_arr);	
					
					$this->updateBookingStatusToBooked($hid_booking_id); // written in common file
				}				
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$hid_booking_id=$this->purifyInsertString($postArr["hid_booking_id"]);
		
		
		$bindArr=array(); 
		
		
		
		//$strQuery=" delete from srt_booking_transaction $Setup_filt and booking_transaction_id=:booking_transaction_id "; 
		$strQuery=" update srt_receipts_transaction set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where booking_transaction_id=:hid_booking_id and receipt_transaction_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':hid_booking_id']=array("value"=>$hid_booking_id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Receipts details deleted successfully'; 
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
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub Receipts' as msg from srt_booking_transaction where booking_transaction_id=:id)"; 
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
			$_msg = "Receipts cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function receiptDetailsList($postArr)
	{
		 $booking_transaction_id = $postArr['hid_booking_id'];
		 if($booking_transaction_id)
		 {
		 	$where = ' and rec.booking_transaction_id =:booking_transaction_id';
			$bindArr[':booking_transaction_id'] = array("value"=>$booking_transaction_id,"type"=>"int");
		 }
		 $sql="select bk.booking_transaction_id, bk.order_no, entry_date, entry_by, rec.receipt_transaction_id, rec.receipt_no, rec.receipt_date, rec.receipt_amount, case rec.payment_mode when 1 then 'Cash' when 2 then 'Bank' when 3 then 'Vehicle exchange' when 4 then fnam.financier_name else '' end as payment_mode, rec.receipt_remarks as oled_remarks, rec.is_deleted as receip_cancelled, rec.amount_reveived_status,  if((rec.amount_reveived_status=0 and rec.payment_mode=2 and rec.is_deleted=0 and rec.bank_recons_reason_type=2),'Represent',rec.receipt_remarks) as receipt_remarks  from srt_receipts_transaction rec  left join srt_booking_transaction as bk on rec.booking_transaction_id = bk.booking_transaction_id left join srt_finance_transaction as ft on (rec.booking_transaction_id=ft.booking_transaction_id and rec.payment_mode=4 and rec.finance_transaction_id=ft.finance_transaction_id) left join srt_financier_master as fnam on ft.financier_id=fnam.financier_id where 1 $where order by rec.receipt_transaction_id ";  // here rec.is_deleted<>1 is considered as cancelled
			
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr);
		  $senddata = array();
		  $recp_list_order_no="";
		  foreach($recs as $key=>$rs)
		  {
		  	$rs['entry_date'] = $this->convertDate($rs['entry_date']);
			$rs['receipt_date'] = $this->convertDate($rs['receipt_date']);
			 $senddata[$key]  = $rs;
			 
			 $recp_list_order_no=$rs['order_no'];
		  } 
			
			
			$sendArr=array('rsData'=>$senddata,'status'=>'success','recp_list_order_no'=>$recp_list_order_no); 
			return json_encode($sendArr);
	}
	public function saveBankReconsprocess($post_arr)
	{ 
		$id=$this->purifyInsertString($post_arr["hid_bank_recons_id"]);
		 
		$get_arr['bank_recons_entry_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['bank_recons_entry_date']));
		$get_arr['bank_recons_entry_by']=$this->htmpurify->purifier->purify($post_arr['bank_recons_entry_by']);
		$get_arr['bank_recons_entry_status']=$this->htmpurify->purifier->purify($post_arr['bank_recons_entry_status']); 
		$get_arr['bank_recons_reason_type']=$this->htmpurify->purifier->purify($post_arr['bank_recons_reason_type']); 
		$get_arr['bank_recons_remarks']=$this->htmpurify->purifier->purify($post_arr['bank_recons_remarks']); 
		
		$get_arr['amount_reveived_status']=0;
		if($get_arr['bank_recons_entry_status']==1) { $get_arr['amount_reveived_status']=1; $get_arr['bank_recons_reason_type']=""; }
		 
		
		$cnt_ext_sql="select booking_transaction_id, receipt_remarks, chque_dd_type from srt_receipts_transaction where receipt_transaction_id=:receipt_transaction_id "; 
		$bindExtCntArr=array(":receipt_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["booking_transaction_id"];
		$db_book_id=$rs_qry_exts["booking_transaction_id"];
		$db_receipt_remarks=$rs_qry_exts["receipt_remarks"];
		$dbchque_dd_type=$rs_qry_exts["chque_dd_type"];
		
		$ins="  srt_receipts_transaction SET bank_recons_entry_date=:bank_recons_entry_date, bank_recons_entry_by=:bank_recons_entry_by, bank_recons_entry_status=:bank_recons_entry_status, amount_reveived_status=:amount_reveived_status, bank_recons_reason_type=:bank_recons_reason_type, bank_recons_remarks=:bank_recons_remarks";
		
		$insBind=array(  ":bank_recons_entry_date"=>array("value"=>$get_arr['bank_recons_entry_date'],"dtype"=>"text"), ":bank_recons_entry_by"=>array("value"=>$get_arr['bank_recons_entry_by'],"dtype"=>"text"), ":bank_recons_entry_status"=>array("value"=>$get_arr['bank_recons_entry_status'],"dtype"=>"int"), ":amount_reveived_status"=>array("value"=>$get_arr['amount_reveived_status'],"dtype"=>"int"), ":bank_recons_reason_type"=>array("value"=>$get_arr['bank_recons_reason_type'],"dtype"=>"int"), ":bank_recons_remarks"=>array("value"=>$get_arr['bank_recons_remarks'],"dtype"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));  
		
		if($get_arr['bank_recons_entry_status']==2 and ($get_arr['bank_recons_reason_type']==1 or $get_arr['bank_recons_reason_type']==3) )
		{
		 	$chkdd="";
			if($dbchque_dd_type=="1") $chkdd="Cheque";
			else if($dbchque_dd_type=="2") $chkdd="DD";
			 
			$set_receipt_remarks=trim($db_receipt_remarks." ".$chkdd." return"); 
			
			$ins.=" , receipt_remarks=:receipt_remarks, is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id ";
			$insBind[":receipt_remarks"]=array("value"=>$set_receipt_remarks,"type"=>"text"); 
		}
		 
		
		$insUp="";
		if($ext_cnt_val>0) 
		{ 
			$insUp="uponly";
			
			$strQuery="UPDATE $ins, lastmodifiedon=now(), lastmodifiedby=:sess_user_id where receipt_transaction_id=:receipt_transaction_id";
			$insBind[":receipt_transaction_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and receipt_transaction_id<>:receipt_transaction_id ";
			$bindExtChkArr[":receipt_transaction_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Receipts updated successfully!";
		}
		else
		{
			//$strQuery="INSERT INTO $ins, createdon=now(),booking_transaction_id=:booking_transaction_id, createdby=:sess_user_id"; 
			
			$insUp="insert";
			$opmsg="Receipts inserted successfully!";
		} 
		 
		$opStatus='failure';
		$opMessage='failure'; 
 
		if($insUp=="uponly") //Currently ONly update
		{
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			$this->updateBookingStatusToBooked($db_book_id); // written in common file
		}
		
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg;  
		}  
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	public function getSingeBankReconsView($postArr)
	{
			$getcid=$this->purifyInsertString($postArr["id"]);
			 
			 
			$where  = ' and receipt_transaction_id =:receipt_transaction_id';
			$bindArr[":receipt_transaction_id"]=array("value"=>$getcid,"type"=>"int");
			  
			 $sql="select bk.booking_transaction_id, bk.order_no, entry_date, entry_by, rec.receipt_transaction_id, rec.receipt_no, rec.receipt_date, rec.receipt_amount, rec.payment_mode, rec.receipt_remarks, rec.amount_reveived_status, rec.chque_dd_type, rec.bank_name, rec.cheque_no, rec.bank_recons_entry_status, rec.bank_recons_entry_date, rec.bank_recons_entry_by, bank_recons_reason_type, bank_recons_remarks from srt_receipts_transaction rec  left join srt_booking_transaction as bk on rec.booking_transaction_id = bk.booking_transaction_id where 1 $where order by entry_date "; 
			 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		 
			$cid=$this->purifyString($recs["receipt_transaction_id"]); 
			 
			$recs['bank_recons_entry_date']=$this->convertDate($this->htmpurify->purifier->purify($recs['bank_recons_entry_date'])); 
			
			if(!$recs['bank_recons_entry_status']) $recs['bank_recons_entry_date']=$this->convertDate(date('Y-m-d')); 
			
			$sendRs=$recs;  
			
			$order_no = '';
			
			
			
			$sendArr=array('rsData'=>$sendRs, 'status'=>'success', 'order_no'=>$order_no);  
			//return $sendArr;
			return json_encode($sendArr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>