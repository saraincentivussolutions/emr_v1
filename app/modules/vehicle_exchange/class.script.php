<?php	

class vehicle_exchange extends common
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
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk where bk.is_deleted<>1 and bk.ex_vechicle=1 $where $salesTeamfilt ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date, bk.customer_name, bk.customer_mobile, prd.productline_name, vex.exchange_model, vex.registration_number, vex.exchange_price,ca.employee_name as customer_advisor_name, sls.sales_team_name , finance_previous_loanamnt,actual_value,(case exchange_type when 1 then 'Claim' when 2 then 'Actual' end) as exchange_type, entered_exchange_price from srt_booking_transaction as bk left join srt_vehicle_exchange as vex on bk.booking_transaction_id=vex.booking_transaction_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id  left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id where bk.is_deleted<>1 and bk.ex_vechicle=1 $where $salesTeamfilt  order by bk.order_no "; 
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
				$exchange_model=$this->purifyString($rs["exchange_model"]); 
				$registration_number=$this->purifyString($rs["registration_number"]); 
				$entered_exchange_price=$this->purifyString($rs["entered_exchange_price"]);  
				$exchange_price=$this->purifyString($rs["exchange_price"]); 
				
				$sales_team_name = $this->purifyString($rs["sales_team_name"]);
				$customer_advisor_name = $this->purifyString($rs["customer_advisor_name"]);
				
				$actual_value = $this->purifyString($rs["actual_value"]);
				$exchange_type = $this->purifyString($rs["exchange_type"]);
				$finance_previous_loanamnt = $this->purifyString($rs["finance_previous_loanamnt"]);
				
				
				// <span class="delete act-delete"  onclick="viewDeleteVehicleExchangeListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>
				 
				$sendRs[$rsCnt]=array($PageSno+1,$order_date, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $exchange_model, $registration_number, number_format($entered_exchange_price,2), number_format($finance_previous_loanamnt,2), number_format($exchange_price,2),$exchange_type, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateVehicleExchangeListMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
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
			
			$sql="select vehicle_exchange_id, bk.booking_transaction_id, exchange_model, manufacture_year, numberof_owners, running_km, registration_number, exchange_price, bk.order_no, finance_previous_status, finance_previous_financier, finance_previous_loanamnt, chklist_available, scheme_bonus_tata, scheme_bonus_srt, actual_paid_tata, actual_paid_srt, actual_value, owner_different, owner_name, owner_relationship, proff_collected,exchange_type, entered_exchange_price, coalesce(exchange_offer,0) as exchange_offer, coalesce(exchange_offer_srt,0)+coalesce(exchange_offer_srt_addition,0) as srt_total_offer from srt_booking_transaction as bk left join srt_vehicle_exchange vex on bk.booking_transaction_id=vex.booking_transaction_id where bk.booking_transaction_id=:booking_transaction_id";
			$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			
		 
			$vehicle_exchange_id=$this->purifyString($recs["vehicle_exchange_id"]);
			$booking_transaction_id=$this->purifyString($recs["booking_transaction_id"]);
			$order_no=$this->purifyString($recs["order_no"]);
			$exchange_model=$this->purifyString($recs["exchange_model"]);
			$manufacture_year=$this->purifyString($recs["manufacture_year"]);
			$numberof_owners=$this->purifyString($recs["numberof_owners"]); 
			$running_km=$this->purifyString($recs["running_km"]); 
			$registration_number=$this->purifyString($recs["registration_number"]); 
			$exchange_price=$this->purifyString($recs["exchange_price"]);  
			$entered_exchange_price=$this->purifyString($recs["entered_exchange_price"]);  
			
			$finance_previous_status=$this->purifyString($recs["finance_previous_status"]); 
			$finance_previous_financier=$this->purifyString($recs["finance_previous_financier"]); 
			$finance_previous_loanamnt=$this->purifyString($recs["finance_previous_loanamnt"]); 
			$chklist_available=$this->purifyString($recs["chklist_available"]);
			
			$actual_value = $this->purifyString($recs["actual_value"]);
			$exchange_type = $this->purifyString($recs["exchange_type"]);
			$finance_previous_loanamnt = $this->purifyString($recs["finance_previous_loanamnt"]);
			$scheme_bonus_tata=$this->htmpurify->purifier->purify($recs['scheme_bonus_tata']);
			$scheme_bonus_srt=$this->htmpurify->purifier->purify($recs['scheme_bonus_srt']);
			$actual_paid_tata=$this->htmpurify->purifier->purify($recs['actual_paid_tata']);
			$actual_paid_srt=$this->htmpurify->purifier->purify($recs['actual_paid_srt']);
			$actual_value=$this->htmpurify->purifier->purify($recs['actual_value']);
			$owner_different=$this->htmpurify->purifier->purify($recs['owner_different']);
			$owner_name=$this->htmpurify->purifier->purify($recs['owner_name']);
			$owner_relationship=$this->htmpurify->purifier->purify($recs['owner_relationship']);
			$proff_collected=$this->htmpurify->purifier->purify($recs['proff_collected']);
			$entered_exchange_price=$this->htmpurify->purifier->purify($recs['entered_exchange_price']);
			$exchange_offer=$this->htmpurify->purifier->purify($recs['exchange_offer']);
			$srt_total_offer=$this->htmpurify->purifier->purify($recs['srt_total_offer']);
			
			if(!$finance_previous_financier) $finance_previous_financier=0;
			
			$chklist_avai_list=array();
			if($chklist_available) $chklist_avai_list=json_decode($chklist_available);
			
			$financierlist = $this->getModuleComboList('financier', $finance_previous_financier);
			
			$sendRs=array("vehicle_exchange_id"=>$vehicle_exchange_id, "booking_transaction_id"=>$booking_transaction_id, "order_no"=>$order_no, "exchange_model"=>$exchange_model, "manufacture_year"=>$manufacture_year, "numberof_owners"=>$numberof_owners, "running_km"=>$running_km, "registration_number"=>$registration_number, "exchange_price"=>$exchange_price, "finance_previous_status"=>$finance_previous_status, "finance_previous_financier"=>$finance_previous_financier, "finance_previous_loanamnt"=>$finance_previous_loanamnt, "financierlist"=>$financierlist, "chklist_avai_list"=>$chklist_avai_list, 'scheme_bonus_tata'=>$scheme_bonus_tata, 'scheme_bonus_srt'=>$scheme_bonus_srt, 'actual_paid_tata'=>$actual_paid_tata, 'actual_paid_srt'=>$actual_paid_srt, 'actual_value'=>$actual_value, 'owner_different'=>$owner_different, 'owner_name'=>$owner_name, 'owner_relationship'=>$owner_relationship, 'proff_collected'=>$proff_collected,'exchange_type'=>$exchange_type,'entered_exchange_price'=>$entered_exchange_price,'exchange_offer'=>$exchange_offer,'srt_total_offer'=>$srt_total_offer); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$exchange_model=$this->purifyInsertString($postArr["exchange_model"]); 
		$registration_number=$this->purifyInsertString($postArr["registration_number"]);		
		$exchange_price=$this->purifyInsertString($postArr["exchange_price"]); 
		$entered_exchange_price=$this->purifyInsertString($postArr["exchange_price"]); 
		$finance_previous_status=$this->purifyInsertString($postArr["finance_previous_status"]); 
		$finance_previous_financier=$this->purifyInsertString($postArr["finance_previous_financier"]); 
		$finance_previous_loanamnt=$this->purifyInsertString($postArr["finance_previous_loanamnt"]); 
		
		$get_arr['exchange_type']=$this->htmpurify->purifier->purify($postArr['exchange_type']);
		$get_arr['scheme_bonus_tata']=$this->htmpurify->purifier->purify($postArr['scheme_bonus_tata']);
		$get_arr['scheme_bonus_srt']=$this->htmpurify->purifier->purify($postArr['scheme_bonus_srt']);
		$get_arr['actual_paid_tata']=$this->htmpurify->purifier->purify($postArr['actual_paid_tata']);
		$get_arr['actual_paid_srt']=$this->htmpurify->purifier->purify($postArr['actual_paid_srt']);
		$get_arr['actual_value']=$this->htmpurify->purifier->purify($postArr['actual_value']);
		$get_arr['owner_different']=$this->htmpurify->purifier->purify($postArr['owner_different']);
		$get_arr['owner_name']=$this->htmpurify->purifier->purify($postArr['owner_name']);
		$get_arr['owner_relationship']=$this->htmpurify->purifier->purify($postArr['owner_relationship']);
		$get_arr['proff_collected']=$this->htmpurify->purifier->purify($postArr['proff_collected']);

		$rcpt_remarks=$registration_number.' '.$exchange_model;
		
		if($finance_previous_status!=1)
		{
			$finance_previous_financier=""; $finance_previous_loanamnt="";
		}
		
		  
		if($get_arr['exchange_type']==1)
		{
			$exchange_price=$get_arr['actual_paid_tata']+$get_arr['actual_paid_srt'];
			$rcpt_remarks='Bonus';
		}
		else
		{
			if($finance_previous_status==1)
			{
				$exchange_price=$entered_exchange_price-$finance_previous_loanamnt;	
			}
		}
		
		
		
		$postArr["manufacture_year"]=$this->purifyInsertString($postArr["manufacture_year"]);
		$postArr["numberof_owners"]=$this->purifyInsertString($postArr["numberof_owners"]);		
		$postArr["running_km"]=$this->purifyInsertString($postArr["running_km"]);		
		
		$arrChkZeroEmptyarr=array('manufacture_year', 'numberof_owners', 'running_km');
		
		$ex_avail=array();
		foreach($postArr["chk_available"] as $availval) $ex_avail[]=$this->purifyInsertString($availval);	
		
		$chklist_available=json_encode($ex_avail);
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_vehicle_exchange where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_vehicle_exchange SET exchange_model=:exchange_model, registration_number=:registration_number, exchange_price=:exchange_price, finance_previous_status=:finance_previous_status, finance_previous_financier=:finance_previous_financier, finance_previous_loanamnt=:finance_previous_loanamnt, chklist_available=:chklist_available,exchange_type=:exchange_type, scheme_bonus_tata=:scheme_bonus_tata, scheme_bonus_srt=:scheme_bonus_srt, actual_paid_tata=:actual_paid_tata, actual_paid_srt=:actual_paid_srt, actual_value=:actual_value, owner_different=:owner_different, owner_name=:owner_name, owner_relationship=:owner_relationship, proff_collected=:proff_collected, entered_exchange_price=:entered_exchange_price";
		
		$insBind=array(":exchange_model"=>array("value"=>$exchange_model,"type"=>"int"), ":registration_number"=>array("value"=>$registration_number,"type"=>"text"), ":exchange_price"=>array("value"=>$exchange_price,"type"=>"text"), ":finance_previous_status"=>array("value"=>$finance_previous_status,"type"=>"int"), ":finance_previous_financier"=>array("value"=>$finance_previous_financier,"type"=>"int"), ":finance_previous_loanamnt"=>array("value"=>$finance_previous_loanamnt,"type"=>"text"), ":chklist_available"=>array("value"=>$chklist_available,"type"=>"text"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":exchange_type"=>array("value"=>$get_arr['exchange_type'],"dtype"=>"int"), ":scheme_bonus_tata"=>array("value"=>$get_arr['scheme_bonus_tata'],"dtype"=>"text"), ":scheme_bonus_srt"=>array("value"=>$get_arr['scheme_bonus_srt'],"dtype"=>"text"), ":actual_paid_tata"=>array("value"=>$get_arr['actual_paid_tata'],"dtype"=>"text"), ":actual_paid_srt"=>array("value"=>$get_arr['actual_paid_srt'],"dtype"=>"text"), ":actual_value"=>array("value"=>$get_arr['actual_value'],"dtype"=>"text"), ":owner_different"=>array("value"=>$get_arr['owner_different'],"dtype"=>"int"), ":owner_name"=>array("value"=>$get_arr['owner_name'],"dtype"=>"text"), ":owner_relationship"=>array("value"=>$get_arr['owner_relationship'],"dtype"=>"int"), ":proff_collected"=>array("value"=>$get_arr['proff_collected'],"dtype"=>"int"), ":entered_exchange_price"=>array("value"=>$entered_exchange_price,"dtype"=>"int")  );  
		
		foreach($arrChkZeroEmptyarr as $arrChkZeroEmptyVal)
		{
			if($postArr[$arrChkZeroEmptyVal])
			{
				$ins.=", $arrChkZeroEmptyVal=:$arrChkZeroEmptyVal";
				$insBind[":$arrChkZeroEmptyVal"]=array("value"=>$postArr[$arrChkZeroEmptyVal],"type"=>"int");
			}
			else
			{
				$ins.=", $arrChkZeroEmptyVal=NULL";
			}
		}
		
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
			
			//update booking price details
			$bk_ex_sql="update srt_booking_transaction set ex_price=:ex_price where booking_transaction_id=:booking_transaction_id ";
			$bk_ex_arr=array(":ex_price"=>array("value"=>$exchange_price,"type"=>"text"), ':booking_transaction_id'=>array("value"=>$id,"type"=>"int")); 
			//$bk_ex_exec = $this->pdoObj->execute($bk_ex_sql, $bk_ex_arr);  // Currently Exchange prices moved to Receipt 
			
			if($bk_ex_exec)
			{
				//$this->updateBookingDetails($id, 'booking_price');
			}
			
			// Insert/ update Receipt 
			
			$rcptcnt_ext_sql="select count(*) as ext_cnt from srt_receipts_transaction where veh_ex_bookid=:veh_ex_bookid "; 
			$rcptbindExtCntArr=array(":veh_ex_bookid"=>array("value"=>$id,"type"=>"int"));
			$rcptrs_qry_exts = $this->pdoObj->fetchSingle($rcptcnt_ext_sql, $rcptbindExtCntArr); 
			$rcptext_cnt_val=$rcptrs_qry_exts["ext_cnt"];
			
			$rcptins="  srt_receipts_transaction SET receipt_amount=:receipt_amount, receipt_remarks=:receipt_remarks, entry_by='Exchange'"; 
		
			$rcptinsBind=array(":veh_ex_bookid"=>array("value"=>$id,"dtype"=>"int"), ":receipt_amount"=>array("value"=>$exchange_price,"dtype"=>"text"), ":receipt_remarks"=>array("value"=>$rcpt_remarks,"dtype"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));  
			
			$rcptinsUp="";
			if($rcptext_cnt_val>0) 
			{ 
				$rcptstrQuery="UPDATE $rcptins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where veh_ex_bookid=:veh_ex_bookid"; 
			}
			else
			{
				$rcptstrQuery="INSERT INTO $rcptins, createdon=now(), entry_date=curdate(), receipt_date=curdate(), booking_transaction_id=:booking_transaction_id, createdby=:sess_user_id , amount_reveived_status=1, payment_mode=3, veh_ex_bookid=:veh_ex_bookid"; 
				$rcptinsBind[":booking_transaction_id"]=array("value"=>$id,"type"=>"int");  
				
				$rcptinsUp="insert";
				 
			}
			
			$rcptexec = $this->pdoObj->execute($rcptstrQuery, $rcptinsBind); 
			if($rcptexec)
			{ 
				if($rcptinsUp=="insert")
				{
					$rcno_sql="update srt_receipts_transaction set receipt_no=receipt_transaction_id where coalesce(receipt_no,'')='' ";
					$rcno_arr=array();  
					$this->pdoObj->execute($rcno_sql, $rcno_arr);	
				}				
			}
			$this->updateBookingStatusToBooked($id); // written in common file
		
			
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);  
		
		$bindArr=array();  
		
		//$strQuery=" delete from srt_vehicle_exchange $Setup_filt and vehicle_exchange_id=:vehicle_exchange_id "; 
		$strQuery=" update srt_vehicle_exchange set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where vehicle_exchange_id=:id  ";  
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
			$whereor = " and (active_status = 1 or vehicle_exchange_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(vehicle_exchange_id,0)>0 ";
		  }
		  $sql="select vehicle_exchange_id, finance_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_vehicle_exchange where 1 $whereor and is_deleted<>1 order by finance_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'VehicleExchangeList referring Sub VehicleExchangeList' as msg from bud_subVehicleExchangeList_master where vehicle_exchange_id=:id) union all (select count(*) as ext_cnt, 'VehicleExchangeList linked to Expenses' as msg from bud_expense_details where vehicle_exchange_id=:id) "; 
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
	
	public function __destruct() 
	{
		
	} 
}

?>