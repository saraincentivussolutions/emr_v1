<?php	

class finance extends common
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
		  
		  $filt_bytype = $postArr['filt_bytype'];	
		  $filter_bytype="";
		  if($filt_bytype==-1) {  $filter_bytype=" and 1=2 ";   }
		  elseif($filt_bytype==1) {  $filter_bytype=" and bk.finance=1 ";   }
		  else if($filt_bytype==2) {  $filter_bytype=" and bk.finance=2 ";   }
		  else if($filt_bytype==3) {  $filter_bytype=" and bk.finance=1 and finance_process_status=10 ";   }
		  else if($filt_bytype==4) {  $filter_bytype=" and bk.finance=2 and finance_process_status=10 ";   }
		  
		  $filt_finstatus = $postArr['filt_finstatus'];	
		  $filter_finstatus="";
		  if($filt_finstatus>=0) {  $filter_finstatus=" and coalesce(finance_process_status,0)=:statusfilt "; $bindArr[':statusfilt']=array("value"=>$filt_finstatus,"type"=>"int");   }   
		  
		  $tot_sql="select count( distinct bk.booking_transaction_id) as cnt from srt_booking_transaction as bk left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id  where bk.is_deleted<>1 $filter_bytype $filter_finstatus $where $salesTeamfilt ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0; 
		  
		  $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date, bk.customer_name, bk.customer_mobile, prd.productline_name, fimas.financier_name, fin.followed_by, fin.finance_amount, fin.expected_do_date, fin.kyc_date, case finance_process_status when 0 then 'Pending' when 1 then 'KYC pending' when 2 then 'Expected DO pending'  when 3 then 'Login pending'  when 4 then 'Document approval pending'  when 5 then 'Document date pending'  when 6 then 'MMR pending'  when 7 then 'DO date pending'  when 8 then 'DO approve pending' when 10 then 'Completed' when 11 then 'First followup pending' when 12 then 'Second followup pending' when 13 then 'Third followup pending' when 14 then 'Fourth followup pending' else 'Pending' end as fin_status, ca.employee_name as customer_advisor_name, sls.sales_team_name, fin.stage_of_comments as remark_desc,bk.finance from srt_booking_transaction as bk left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id  left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_financier_master as fimas on fin.financier_id=fimas.financier_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id  left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id  where bk.is_deleted<>1  $where $filter_bytype $filter_finstatus $salesTeamfilt order by bk.order_no "; 
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
				$customer_name = $this->purifyString($rs["customer_name"]);
				$customer_mobile = $this->purifyString($rs["customer_mobile"]);
				$productline_name = $this->purifyString($rs["productline_name"]);  
				 
				$financier_name=$this->purifyString($rs["financier_name"]);
				$followed_by=$this->purifyString($rs["followed_by"]); 
				$finance_amount=$this->purifyString($rs["finance_amount"]);
				$expected_do_date=$this->convertDate($this->purifyString($rs["expected_do_date"])); 
				$kyc_date=$this->convertDate($this->purifyString($rs["kyc_date"])); 
				$fin_status=$this->purifyString($rs["fin_status"]);  
				
				$sales_team_name = $this->purifyString($rs["sales_team_name"]);
				$customer_advisor_name = $this->purifyString($rs["customer_advisor_name"]);
				$remark_desc = $this->purifyString($rs["remark_desc"]);
				$finance =  $this->purifyString($rs["finance"]);
				
				// <span class="delete act-delete"  onclick="viewDeleteFinanceListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
				 
				$sendRs[$rsCnt]=array($PageSno+1,$order_date,$sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $financier_name, $followed_by, number_format($finance_amount,2), $expected_do_date, $kyc_date, $fin_status, $remark_desc, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateFinanceListMasterList('.$cid.', '.$finance.');"><i class="fa fa-edit"></i> View </span>');
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
			
			$sql="select finance_transaction_id, bk.booking_transaction_id,financier_id, finance_amount, followed_by, kyc_date, expected_do_date, login_date, approval_status, document_date, mmr_status, do_date, do_approved, remark_desc, bk.order_no, kyc_notes, login_notes, document_notes, do_notes,first_followup_date, second_followup_date, third_followup_date, fourth_followup_date, next_followup_date1, next_followup_date2, next_followup_date3, bk.finance, fin.finance_process_status from srt_booking_transaction as bk left join srt_finance_transaction as fin on bk.booking_transaction_id=fin.booking_transaction_id where bk.booking_transaction_id=:booking_transaction_id";
			$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$finance_transaction_id=$this->purifyString($recs["finance_transaction_id"]);
			$booking_transaction_id=$this->purifyString($recs["booking_transaction_id"]);
			$order_no=$this->purifyString($recs["order_no"]);
			$financier_id=$this->purifyString($recs["financier_id"]);
			$finance_amount=$this->purifyString($recs["finance_amount"]);
			$followed_by=$this->purifyString($recs["followed_by"]);
			$kyc_date=$this->convertDate($this->purifyString($recs["kyc_date"])); 
			$expected_do_date=$this->convertDate($this->purifyString($recs["expected_do_date"])); 
			$login_date=$this->convertDate($this->purifyString($recs["login_date"])); 
			$approval_status=$this->purifyString($recs["approval_status"]);
			$document_date=$this->convertDate($this->purifyString($recs["document_date"])); 
			$mmr_status=$this->purifyString($recs["mmr_status"]);
			$do_date=$this->convertDate($this->purifyString($recs["do_date"])); 
			$do_approved=$this->purifyString($recs["do_approved"]);
			$remark_desc=$this->purifyString($recs["remark_desc"]);
			$finance=$this->purifyString($recs["finance"]);
			$finance_process_status =$this->purifyString($recs["finance_process_status"]);
			
			$first_followup_date=$this->convertDate($this->purifyString($recs["first_followup_date"])); 
			$second_followup_date=$this->convertDate($this->purifyString($recs["second_followup_date"])); 
			$third_followup_date=$this->convertDate($this->purifyString($recs["third_followup_date"])); 
			$fourth_followup_date=$this->convertDate($this->purifyString($recs["fourth_followup_date"]));  
			$next_followup_date1=$this->convertDate($this->purifyString($recs["next_followup_date1"])); 
			$next_followup_date2=$this->convertDate($this->purifyString($recs["next_followup_date2"])); 
			$next_followup_date3=$this->convertDate($this->purifyString($recs["next_followup_date3"]));  

			
			$kyc_notes=$this->purifyString($recs["kyc_notes"]); 
			$login_notes=$this->purifyString($recs["login_notes"]); 
			$document_notes=$this->purifyString($recs["document_notes"]); 
			$do_notes=$this->purifyString($recs["do_notes"]); 
			
			$financierlist = $this->getModuleComboList('financier', $financier_id); 
			
			$sendRs=array("finance_transaction_id"=>$finance_transaction_id, "booking_transaction_id"=>$booking_transaction_id, "order_no"=>$order_no, "financier_id"=>$financier_id, "finance_amount"=>$finance_amount, "followed_by"=>$followed_by, "kyc_date"=>$kyc_date, "expected_do_date"=>$expected_do_date, "login_date"=>$login_date, "approval_status"=>$approval_status, "document_date"=>$document_date, "mmr_status"=>$mmr_status, "do_date"=>$do_date, "do_approved"=>$do_approved, "remark_desc"=>$remark_desc, "financierlist"=>$financierlist, "kyc_notes"=>$kyc_notes, "login_notes"=>$login_notes, "document_notes"=>$document_notes, "do_notes"=>$do_notes, "first_followup_date"=>$first_followup_date,"second_followup_date"=>$second_followup_date,"third_followup_date"=>$third_followup_date,"fourth_followup_date"=>$fourth_followup_date,"next_followup_date1"=>$next_followup_date1,"next_followup_date2"=>$next_followup_date2,"next_followup_date3"=>$next_followup_date3, 'finance'=>$finance, 'finance_process_status'=>$finance_process_status); 
			
		
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$financier_id=$this->purifyInsertString($postArr["financier_id"]);
		$finance_amount=$this->purifyInsertString($postArr["finance_amount"]);
		$followed_by=$this->purifyInsertString($postArr["followed_by"]); 
		$kyc_date=$this->convertDate($this->purifyInsertString($postArr["kyc_date"]));   
		$expected_do_date=$this->convertDate($this->purifyInsertString($postArr["expected_do_date"]));   
		$login_date=$this->convertDate($this->purifyInsertString($postArr["login_date"]));   
		$approval_status=$this->purifyInsertString($postArr["approval_status"]);
		$document_date=$this->convertDate($this->purifyInsertString($postArr["document_date"]));
		$mmr_status=$this->purifyInsertString($postArr["mmr_status"]);
		$do_date=$this->convertDate($this->purifyInsertString($postArr["do_date"]));
		$do_approved=$this->purifyInsertString($postArr["do_approved"]);
		$remark_desc=$this->purifyInsertString($postArr["remark_desc"]); 
		
		$kyc_notes=$this->purifyInsertString($postArr["kyc_notes"]); 
		$login_notes=$this->purifyInsertString($postArr["login_notes"]); 
		$document_notes=$this->purifyInsertString($postArr["document_notes"]); 
		$do_notes=$this->purifyInsertString($postArr["do_notes"]); 
		$hid_finance_transaction =$this->purifyInsertString($postArr["hid_finance_transaction"]); 
		
		$finance_process_status=0;
		$stage_of_comments=$remark_desc; 
		
		if($kyc_date=="" or $kyc_date=='0000-00-00') { $stage_of_comments=$kyc_notes; $finance_process_status=1; }
		else if($expected_do_date=="" or $expected_do_date=='0000-00-00') { $stage_of_comments=$kyc_notes; $finance_process_status=2; }
		else if($login_date=="" or $login_date=='0000-00-00') { $stage_of_comments=$kyc_notes; $finance_process_status=3; }
		else if($approval_status!="1") {  $stage_of_comments=$login_notes;$finance_process_status=4; }
		else if($document_date=="" or $document_date=='0000-00-00') { $stage_of_comments=$login_notes; $finance_process_status=5; }
		else if($mmr_status!="1") { $stage_of_comments=$document_notes;  $finance_process_status=6; }
		else if($do_date=="" or $do_date=='0000-00-00') { $stage_of_comments=$document_notes;  $finance_process_status=7; }
		else if($do_approved!="1") { $stage_of_comments=$do_notes; $finance_process_status=8; } 
	 
		
		//if($finance_process_status==0 and $kyc_date!="" and $kyc_date!='0000-00-00' and $login_date!="" and $login_date!='0000-00-00' and $approval_status==1 and $document_date!="" and $document_date!='0000-00-00' and $mmr_status==1 and $do_date!="" and $do_date!='0000-00-00' and $do_approved==1) $finance_process_status=10; // completed status
		
		if($do_approved==1)  { $finance_process_status=10;  $stage_of_comments=$remark_desc; }
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_finance_transaction where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_finance_transaction SET financier_id=:financier_id, finance_amount=:finance_amount, followed_by=:followed_by, kyc_date=:kyc_date, expected_do_date=:expected_do_date, login_date=:login_date, approval_status=:approval_status, document_date=:document_date, mmr_status=:mmr_status, do_date=:do_date, do_approved=:do_approved, remark_desc=:remark_desc, finance_process_status=:finance_process_status, kyc_notes=:kyc_notes, login_notes=:login_notes, document_notes=:document_notes, do_notes=:do_notes, stage_of_comments=:stage_of_comments";
		
		$insBind=array(":financier_id"=>array("value"=>$financier_id,"type"=>"int"), ":finance_amount"=>array("value"=>$finance_amount,"type"=>"text")	, ":followed_by"=>array("value"=>$followed_by,"type"=>"text"), ":kyc_date"=>array("value"=>$kyc_date,"type"=>"text"), ":expected_do_date"=>array("value"=>$expected_do_date,"type"=>"text"), ":login_date"=>array("value"=>$login_date,"type"=>"text"), ":approval_status"=>array("value"=>$approval_status,"type"=>"text"), ":document_date"=>array("value"=>$document_date,"type"=>"text"), ":mmr_status"=>array("value"=>$mmr_status,"type"=>"text"), ":do_date"=>array("value"=>$do_date,"type"=>"text"), ":do_approved"=>array("value"=>$do_approved,"type"=>"text"), ":remark_desc"=>array("value"=>$remark_desc,"type"=>"text"), ":kyc_notes"=>array("value"=>$kyc_notes,"type"=>"text"), ":login_notes"=>array("value"=>$login_notes,"type"=>"text"), ":document_notes"=>array("value"=>$document_notes,"type"=>"text"), ":do_notes"=>array("value"=>$do_notes,"type"=>"text"), ":stage_of_comments"=>array("value"=>$stage_of_comments,"type"=>"text"), ':finance_process_status'=>array("value"=>$finance_process_status,"type"=>"int"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));  
		
		$mod = '';
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id "; 
			$opmsg="Finance details updated successfully!";
			$mod = 'up';
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id, booking_transaction_id=:booking_transaction_id ";   
			$opmsg="Finance details inserted successfully!";
			$mod = 'ins';
		} 
		
		
		
		$opStatus='failure';
		$opMessage='failure';
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
			
			//praga
			//if($mod == 'ins')
			{
				if($do_approved == 1)
				{
					$sql="select finance_transaction_id from srt_finance_transaction where booking_transaction_id=:booking_transaction_id "; 
					$bindArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
					$rs = $this->pdoObj->fetchSingle($sql, $bindArr); 
									
					$arr = array('finance_amount'=>$finance_amount, 'finance_transaction_id'=>$rs['finance_transaction_id'], 'booking_transaction_id'=>$id);
					echo $this->createReceiptFinance($arr);
				}
			}
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);  
		
		$bindArr=array();  
		
		//$strQuery=" delete from srt_finance_transaction $Setup_filt and finance_transaction_id=:finance_transaction_id "; 
		$strQuery=" update srt_finance_transaction set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where finance_transaction_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		//$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Finance details deleted successfully'; 
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
			$whereor = " and (active_status = 1 or finance_transaction_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(finance_transaction_id,0)>0 ";
		  }
		  $sql="select finance_transaction_id, finance_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_finance_transaction where 1 $whereor and is_deleted<>1 order by finance_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'FinanceList referring Sub FinanceList' as msg from bud_subFinanceList_master where finance_transaction_id=:id) union all (select count(*) as ext_cnt, 'FinanceList linked to Expenses' as msg from bud_expense_details where finance_transaction_id=:id) "; 
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
			$_msg = "FinanceList cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function getReceiptFinanceData($postArr)
	{
		$booking_transaction_id=$this->purifyInsertString($postArr["booking_id"]);
		$where = '';
		 $finance_transaction_id = $postArr['finance_transaction_id'];
		 //if($finance_transaction_id)
		 {
			$where .= ' and rec.finance_transaction_id =:finance_transaction_id';
			$bindArr[':finance_transaction_id'] = array("value"=>$finance_transaction_id,"type"=>"int");
		 }
		// if($getcid)
		 {
			$where .= ' and rec.booking_transaction_id =:booking_transaction_id';
			$bindArr[":booking_transaction_id"]=array("value"=>$booking_transaction_id,"type"=>"int");
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
			
			return json_encode($sendArr);

	}
	
	public function createReceiptFinance($data)
	{
		$finance_amount = $data['finance_amount'];
		$finance_transaction_id = $data['finance_transaction_id'];
		$booking_transaction_id = $data['booking_transaction_id'];
		$payment_mode = 4;
		$receipt_remarks = 'Done by finance';
		$from = $data['from'];		
		$receipt_date =  date('Y-m-d');
		$entry_date = date('Y-m-d');
		$chque_dd_type = 0;
		$entry_by = 'by finance';
		$cheque_no = '';
		$bank_name = '';
				
		if($from=='customer')
		{
			$payment_mode = $data['payment_mode'];
			$receipt_remarks = $data['receipt_remarks'];
			$entry_date = $data['entry_date'];
			$receipt_date = $data['receipt_date'];			
			$entry_by = $data['entry_by'];
			$cheque_no = $data['cheque_no'];
			$bank_name = $data['bank_name'];
			$chque_dd_type = $data['chque_dd_type'];
			
		}
		
		if($entry_date=="") $entry_date = date('Y-m-d');
		if($receipt_date=="") $receipt_date = date('Y-m-d'); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_receipts_transaction where finance_transaction_id=:finance_transaction_id and booking_transaction_id=:booking_transaction_id"; 
		$bindExtCntArr=array(":finance_transaction_id"=>array("value"=>$finance_transaction_id,"type"=>"int"), ":booking_transaction_id"=>array("value"=>$booking_transaction_id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins="  srt_receipts_transaction SET entry_date=:entry_date, entry_by=:entry_by, receipt_date=:receipt_date, payment_mode=:payment_mode, receipt_amount=:receipt_amount, receipt_remarks=:receipt_remarks, amount_reveived_status=1,cheque_no=:cheque_no, bank_name=:bank_name, chque_dd_type=:chque_dd_type";
		
		$insBind=array(":booking_transaction_id"=>array("value"=>$booking_transaction_id,"dtype"=>"int"), ":payment_mode"=>array("value"=>$payment_mode,"dtype"=>"int"), ":receipt_amount"=>array("value"=>$finance_amount,"dtype"=>"text"), ":receipt_remarks"=>array("value"=>$receipt_remarks,"dtype"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":finance_transaction_id"=>array("value"=>$finance_transaction_id,"type"=>"int"), ":entry_date"=>array("value"=>$entry_date,"dtype"=>"text"), ":entry_by"=>array("value"=>$entry_by,"dtype"=>"text"), ":receipt_date"=>array("value"=>$receipt_date,"dtype"=>"text"), ":cheque_no"=>array("value"=>$cheque_no,"dtype"=>"text"), ":bank_name"=>array("value"=>$bank_name,"dtype"=>"text"), ":chque_dd_type"=>array("value"=>$chque_dd_type,"dtype"=>"text")); 
		
		$insUp="";
		if($ext_cnt_val>0) 
		{ 
			/*//$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id and receipt_transaction_id=:receipt_transaction_id";
			$insBind[":receipt_transaction_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and receipt_transaction_id<>:receipt_transaction_id ";
			$bindExtChkArr[":receipt_transaction_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Receipts updated successfully!";*/
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),booking_transaction_id=:booking_transaction_id, finance_transaction_id=:finance_transaction_id,  createdby=:sess_user_id"; 
			
			$insUp="insert";
			$opmsg="Receipts inserted successfully!";
			$rcptexec = $this->pdoObj->execute($strQuery, $insBind);
			//echo $strQuery.json_encode($insBind);
			if($rcptexec)
			{   
				$rcno_sql="update srt_receipts_transaction set receipt_no=receipt_transaction_id where coalesce(receipt_no,'')='' ";
				$rcno_arr=array();  
				$this->pdoObj->execute($rcno_sql, $rcno_arr);	
				 			
			}
			$this->updateBookingStatusToBooked($booking_transaction_id); // written in common file
		}
		
		

	}
	
	public function saveprocessCustomer($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$financier_id=$this->purifyInsertString($postArr["financier_id"]);
		$finance_amount=$this->purifyInsertString($postArr["finance_amount"]);
		$first_followup_date=$this->convertDate($this->purifyInsertString($postArr["first_followup_date"]));   
		$second_followup_date=$this->convertDate($this->purifyInsertString($postArr["second_followup_date"]));   
		$third_followup_date=$this->convertDate($this->purifyInsertString($postArr["third_followup_date"]));   
		$next_followup_date1=$this->convertDate($this->purifyInsertString($postArr["next_followup_date1"]));
		$fourth_followup_date=$this->convertDate($this->purifyInsertString($postArr["fourth_followup_date"]));
		$next_followup_date1=$this->convertDate($this->purifyInsertString($postArr["next_followup_date1"]));
		$next_followup_date2=$this->convertDate($this->purifyInsertString($postArr["next_followup_date2"]));
		$next_followup_date3=$this->convertDate($this->purifyInsertString($postArr["next_followup_date3"]));
		$do_approved=$this->purifyInsertString($postArr["do_approved"]);
		$remark_desc=$this->purifyInsertString($postArr["remark_desc"]);  
		
		$kyc_notes=$this->purifyInsertString($postArr["kyc_notes"]); 
		$login_notes=$this->purifyInsertString($postArr["login_notes"]); 
		$document_notes=$this->purifyInsertString($postArr["document_notes"]); 
		$do_notes=$this->purifyInsertString($postArr["do_notes"]); 
		$hid_finance_transaction =$this->purifyInsertString($postArr["hid_finance_transaction"]); 
		
		$finance_process_status=0;
		$stage_of_comments=$remark_desc;
		
		if($first_followup_date=="" or $first_followup_date=='0000-00-00') { $finance_process_status=11; }
		else if($second_followup_date=="" or $second_followup_date=='0000-00-00') { $stage_of_comments=$kyc_notes; $finance_process_status=12; }
		else if($third_followup_date=="" or $third_followup_date=='0000-00-00') { $stage_of_comments=$login_notes;  $finance_process_status=13; }
		else if($fourth_followup_date=="" or $fourth_followup_date=='0000-00-00') { $stage_of_comments=$document_notes;   $finance_process_status=14; } 
		else if($do_date=="" or $do_date=='0000-00-00') { $stage_of_comments=$do_notes;  $finance_process_status=7; }
		else if($do_approved!="1") {  $stage_of_comments=$do_notes; $finance_process_status=8; } 
		
		/*if($finance_process_status==0 and $kyc_date!="" and $kyc_date!='0000-00-00' and $login_date!="" and $login_date!='0000-00-00' and $approval_status==1 and $document_date!="" and $document_date!='0000-00-00' and $mmr_status==1 and $do_date!="" and $do_date!='0000-00-00' and $do_approved==1) $finance_process_status=10; // completed status*/
		
		if($do_approved==1)  { $finance_process_status=10;  $stage_of_comments=$remark_desc; }
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_finance_transaction where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_finance_transaction SET financier_id=:financier_id, finance_amount=:finance_amount, first_followup_date=:first_followup_date, second_followup_date=:second_followup_date, third_followup_date=:third_followup_date, fourth_followup_date=:fourth_followup_date, next_followup_date1=:next_followup_date1, next_followup_date2=:next_followup_date2, next_followup_date3=:next_followup_date3, do_approved=:do_approved, remark_desc=:remark_desc, finance_process_status=:finance_process_status, kyc_notes=:kyc_notes, login_notes=:login_notes, document_notes=:document_notes, do_notes=:do_notes, stage_of_comments=:stage_of_comments";
		
		$insBind=array(":financier_id"=>array("value"=>$financier_id,"type"=>"int"), ":finance_amount"=>array("value"=>$finance_amount,"type"=>"text")	, ":first_followup_date"=>array("value"=>$first_followup_date,"type"=>"text"), ":second_followup_date"=>array("value"=>$second_followup_date,"type"=>"text"), ":third_followup_date"=>array("value"=>$third_followup_date,"type"=>"text"), ":fourth_followup_date"=>array("value"=>$fourth_followup_date,"type"=>"text"), ":next_followup_date1"=>array("value"=>$next_followup_date1,"type"=>"text"), ":next_followup_date2"=>array("value"=>$next_followup_date2,"type"=>"text"), ":next_followup_date3"=>array("value"=>$next_followup_date3,"type"=>"text"),  ":do_approved"=>array("value"=>$do_approved,"type"=>"text"), ":remark_desc"=>array("value"=>$remark_desc,"type"=>"text"), ":kyc_notes"=>array("value"=>$kyc_notes,"type"=>"text"), ":login_notes"=>array("value"=>$login_notes,"type"=>"text"), ":document_notes"=>array("value"=>$document_notes,"type"=>"text"), ":do_notes"=>array("value"=>$do_notes,"type"=>"text"), ":stage_of_comments"=>array("value"=>$stage_of_comments,"type"=>"text"), ':finance_process_status'=>array("value"=>$finance_process_status,"type"=>"int"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));  
		
		$mod = '';
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id "; 
			$opmsg="Finance details updated successfully!";
			$mod = 'up';
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id, booking_transaction_id=:booking_transaction_id ";   
			$opmsg="Finance details inserted successfully!";
			$mod = 'ins';
		} 
		
		
		
		$opStatus='failure';
		$opMessage='failure';
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
			
			//praga
			//if($mod == 'ins')
			{
				if($do_approved == 1)
				{
					$sql="select finance_transaction_id from srt_finance_transaction where booking_transaction_id=:booking_transaction_id "; 
					$bindArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
					$rs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					$hdn_receipt_chk = $postArr['hdn_receipt_chk'];
					$entry_date = $this->convertDate($this->purifyInsertString($postArr["entry_date"]));
					$receipt_date = $this->convertDate($this->purifyInsertString($postArr["receipt_date"]));
					$payment_mode = $this->purifyInsertString($postArr["payment_mode"]);
					$chque_dd_type = $this->purifyInsertString($postArr["chque_dd_type"]);
					$entry_by = $this->purifyInsertString($postArr["entry_by"]);
					$cheque_no = $this->purifyInsertString($postArr["cheque_no"]);
					$bank_name = $this->purifyInsertString($postArr["bank_name"]);
					$receipt_remarks = $this->purifyInsertString($postArr["receipt_remarks"]);
					$receipt_amount = $this->purifyInsertString($postArr["receipt_amount"]);
					
					if($hdn_receipt_chk)
					{
									
					$arr = array('finance_amount'=>$receipt_amount, 'finance_transaction_id'=>$rs['finance_transaction_id'], 'booking_transaction_id'=>$id, 'entry_date'=>$entry_date, 'receipt_date'=>$receipt_date, 'payment_mode'=>$payment_mode, 'chque_dd_type'=>$chque_dd_type, 'entry_by'=>$entry_by, 'cheque_no'=>$cheque_no, 'bank_name'=>$bank_name, 'receipt_remarks'=>$receipt_remarks, 'from'=>'customer');
					echo $this->createReceiptFinance($arr);
					
					}
				}
			}
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	public function getBookingSingleView($postArr)
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
	
	public function __destruct() 
	{
		
	} 
}

?>