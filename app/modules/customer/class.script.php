<?php	

class customer extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $bindArr=array();
		  $sess_spa_setup_id = $this->sess_spa_setup_id;
		  $sess_log_superuser = $this->sess_log_superuser;
		  
		  $Setup_filt = " where 1=2 ";
		  if($sess_spa_setup_id) 
		  {
		  	$Setup_filt = " where cust.spa_setup_id=:spa_setup_id ";
			$bindArr[':spa_setup_id']=array("value"=>$sess_spa_setup_id,"type"=>"int");
		  } 
		  
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
				//$where = 'and (cust.customer_mobile like :search_str or cust.customer_name like :search_str or emp.employee_name like :search_str)'; 
				$where = 'and (cust.customer_mobile like :search_str or cust.customer_name like :search_str)'; 
				$bindArr[':search_str'] = array("value"=>$query_val,"type"=>"text");
		  }
		  
		 // $tot_sql="select count(cust.customer_id) as cnt from spa_customer_master as cust left join spa_employee_master as emp on cust.refered_employee_id=emp.employee_id $Setup_filt $where ";
		   $tot_sql="select count(cust.customer_id) as cnt from spa_customer_master as cust $Setup_filt $where and is_deleted<>1 ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select cust.customer_id, cust.customer_name, cust.customer_mobile, cust.customer_email, cust.refered_employee_id, cust.active_status, case cust.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, emp.employee_name as ref_emp_name from spa_customer_master as cust left join spa_employee_master as emp on cust.refered_employee_id=emp.employee_id $Setup_filt and cust.is_deleted<>1 $where order by customer_name "; 
			
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["customer_id"]);
				$cname=$this->purifyString($rs["customer_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$customer_mobile=$this->purifyString($rs["customer_mobile"]);
				$customer_email=$this->purifyString($rs["customer_email"]);
				$ref_emp_name=$this->purifyString($rs["ref_emp_name"]);
				
				$actbtn = '<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateCustomerMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"  onclick="viewDeleteCustomerMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span> ';
				
				
				//$sendRs[$rsCnt]=array("user_id"=>$cid,"customer_mobile"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$PageSno]=array($rsCnt+1,$cname, $customer_mobile, $customer_email, $ref_emp_name, $cstatus_desc, $actbtn);
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
		
		$sql="select customer_id, customer_name, customer_mobile, customer_email, refered_employee_id, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from spa_customer_master  where customer_id=:customer_id";
		$bindArr=array(":customer_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["customer_id"]);
	
		$cname=$this->purifyString($recs["customer_name"]);
		$customer_mobile=$this->purifyString($recs["customer_mobile"]);
		$customer_email=$this->purifyString($recs["customer_email"]);
		$cstatus=$this->purifyString($recs["active_status"]);
		$cstatus_desc=$this->purifyString($recs["active_status_desc"]); 
		$refered_employee_id=$this->purifyString($recs["refered_employee_id"]);  
		
		$employeelist = $this->getModuleComboList('user', $refered_employee_id);  
		 
		
		$sendRs=array("customer_id"=>$cid, "customer_name"=>$cname, "status"=>$cstatus, "status_desc"=>$cstatus_desc, "customer_mobile"=>$customer_mobile, "customer_email"=>$customer_email, "refered_employee_id"=>$refered_employee_id, 'employeelist'=>$employeelist); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$sess_spa_setup_id = $this->sess_spa_setup_id;
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$status=$this->purifyInsertString($postArr["customer_status"]);
		$customer_name=$this->purifyInsertString($postArr["customer_name"]);		
		$customer_mobile=$this->purifyInsertString($postArr["customer_mobile"]);
		$customer_email=$this->purifyInsertString($postArr["customer_email"]); 
		$refered_employee_id=$this->purifyInsertString($postArr["refered_employee_id"]);  
		
		$cnt_ext_sql="select count(*) as ext_cnt from spa_customer_master where customer_id=:customer_id "; 
		$bindExtCntArr=array(":customer_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		 
		
		$ins=" spa_customer_master SET customer_name=:customer_name, customer_mobile=:customer_mobile, customer_email=:customer_email, refered_employee_id=:refered_employee_id, active_status=:active_status ";
		$insBind=array(":customer_name"=>array("value"=>$customer_name,"type"=>"text"), ":customer_mobile"=>array("value"=>$customer_mobile,"type"=>"text"), ":customer_email"=>array("value"=>$customer_email,"type"=>"text"), ":refered_employee_id"=>array("value"=>$refered_employee_id,"type"=>"int"), ":active_status"=>array("value"=>$status,"type"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from spa_customer_master where trim(customer_mobile)=:customer_mobile and spa_setup_id=:spa_setup_id and is_deleted<>1  ";
		$bindExtChkArr=array(":customer_mobile"=>array("value"=>$customer_mobile,"dtype"=>"text"), ":spa_setup_id"=>array("value"=>$sess_spa_setup_id,"dtype"=>"int"));  
		 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where customer_id=:customer_id ";
			$insBind[":customer_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and customer_id<>:customer_id ";
			$bindExtChkArr[":customer_id"]=array("value"=>$id,"dtype"=>"int");    
			
			$opmsg="Customer details updated successfully!"; 
			 
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id, spa_setup_id=:spa_setup_id  ";  
			$insBind[":spa_setup_id"]=array("value"=>$sess_spa_setup_id,"type"=>"int"); 
			
			$opmsg="Customer details insterd successfully!"; 
		}
		
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure'; 
		$opExists=''; 
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Customer mobile already exists'; 
			$opExists='exists';
		}		 
		else 
		{
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$bindArr=array(); 
		
		$sess_spa_setup_id = $this->sess_spa_setup_id;
		
		$Setup_filt = " where 1=2 ";
		if($sess_spa_setup_id) 
		{
			$Setup_filt = " where spa_setup_id=:spa_setup_id ";
			$bindArr[':spa_setup_id']=array("value"=>$sess_spa_setup_id,"type"=>"int");
		}
		
		//$strQuery=" delete from spa_customer_master where customer_id=:customer_id "; 
		$strQuery=" update spa_customer_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id $Setup_filt and customer_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Customer deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
			return json_encode($sendArr);
			
	}		
 
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="
		(select count(*) as ext_cnt, 'Customer referring Customer master' as msg from spa_customer_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'Customer linked to Category master' as msg from bud_category_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'Customer linked to Subcategory master' as msg from bud_subcategory_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'Customer linked to Fate details' as msg from bud_fare_details_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'Customer linked to Budget allocation' as msg from bud_budget_allocations where createdby=:id)
		union all (select count(*) as ext_cnt, 'Customer linked to Expenses' as msg from bud_expenses where createdby=:id)
		 "; 
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
			$_msg = "Customer cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	public function comboview($id=0)
	{
		$bindArr=array();
		  
		$sess_spa_setup_id = $this->sess_spa_setup_id;
		
		$Setup_filt = " where 1=2 ";
		if($sess_spa_setup_id) 
		{
			$Setup_filt = " where spa_setup_id=:spa_setup_id ";
			$bindArr[':spa_setup_id']=array("value"=>$sess_spa_setup_id,"type"=>"int");
		}
		
		$whereor = ' and active_status = 1 ';
		if($id>0)
		{ 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or customer_id=:id) ";
		}
		if($id == 'all')
		{
			// $whereor = " or active_status != 1";
			$whereor = " ";
		} 
		 
		$sql="select customer_id, customer_name, customer_mobile, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from spa_customer_master $Setup_filt $whereor and is_deleted<>1 order by customer_name ";
		$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		return $recs;
	}
	public function __destruct() 
	{
		
	} 
}

?>