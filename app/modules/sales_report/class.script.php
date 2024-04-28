<?php	
class salesreport extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $bindArr=array();
		  $sess_spa_setup_id = $this->sess_spa_setup_id; 		  
		  $sess_logintype = $this->sess_logintype;
		  $sess_userid = $this->sess_userid;
		  
		  $Setup_filt = " where 1=2 ";
		  if($sess_spa_setup_id) 
		  {
		  	$Setup_filt = " where bh.spa_setup_id=:spa_setup_id ";
			$bindArr[':spa_setup_id']=array("value"=>$sess_spa_setup_id,"type"=>"int");
		  } 
		  
		  $draw = $postArr['draw'];
		  $start = ($postArr['start'])?$postArr['start']:0;
		  $limit = ($postArr['length'])?$postArr['length']:0; 
		  
		  
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		 
		  $search = ($postArr['search']['value']);
		  $where = '';	
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				//$where = 'and (bh.bill_number like :search_str or if(coalesce(cust.customer_id,0)=0,bh.non_db_customer_name,cust.customer_name) like :search_str or billby.employee_name like :search_str)'; 
				$where = 'and (bh.bill_number like :search_str or billby.employee_name like :search_str)';
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  } 
		  
		  	$cmb_category = ($postArr['cmb_category'])?$this->purifyInsertString($postArr['cmb_category']):'0';			
			$cmb_services = ($postArr['cmb_services'])?$this->purifyInsertString($postArr['cmb_services']):'0'; 
			$bill_by_user = ($postArr['bill_by_user'])?$this->purifyInsertString($postArr['bill_by_user']):'0'; 
			$search_customer = ($postArr['search_customer'])?$this->purifyInsertString($postArr['search_customer']):''; 
			$bill_status = ($postArr['bill_status'])?$this->purifyInsertString($postArr['bill_status']):'-1'; 
			$refered_employee_id = ($postArr['refered_employee_id'])?$this->purifyInsertString($postArr['refered_employee_id']):'0';  
			
			$search_from_date = ($postArr['search_from_date'])?$this->purifyInsertString($this->convertDate($postArr['search_from_date'])):date('Y-m-d'); 
			$search_to_date = ($postArr['search_to_date'])?$this->purifyInsertString($this->convertDate($postArr['search_to_date'])):date('Y-m-d'); 
			
			$where.= 'and bh.bill_date between :srch_filt_billfromdate and :srch_filt_billtodate ';
			$bindArr[':srch_filt_billfromdate']=array("value"=>$search_from_date,"type"=>"text");	
			$bindArr[':srch_filt_billtodate']=array("value"=>$search_to_date,"type"=>"text");	 
			 
			if($cmb_category)
			{
				$where.= 'and srv.category_id=:category_id ';
				$bindArr[':category_id']=array("value"=>$cmb_category,"type"=>"int");	
			}
			if($cmb_services)
			{
				$where.= 'and srv.services_id=:services_id ';
				$bindArr[':services_id']=array("value"=>$cmb_services,"type"=>"int");	
			}
			if($bill_by_user)
			{
				$where.= 'and bh.bill_by=:bill_by ';
				$bindArr[':bill_by']=array("value"=>$bill_by_user,"type"=>"int");	
			}
			if($search_customer!="")
			{
				$cust_val = "%".$search_customer."%"; 
				$where.= 'and (if(coalesce(cust.customer_id,0)=0,bh.non_db_customer_name,cust.customer_name) like :search_customer or cust.customer_mobile like :search_customer)  '; 
				$bindArr[':search_customer']=array("value"=>$cust_val,"type"=>"text");
			}
			if($bill_status!="-1")
			{
				$where.= 'and bh.bill_cancelled=:bill_status ';
				$bindArr[':bill_status']=array("value"=>$bill_status,"type"=>"int");	
			}
			if($refered_employee_id)
			{
				$where.= 'and cust.refered_employee_id=:refered_employee_id ';
				$bindArr[':refered_employee_id']=array("value"=>$refered_employee_id,"type"=>"int");	
			}
		 $tot_sql="select count( distinct bh.billing_head_id) as cnt from spa_billing_head as bh left join spa_billing_sub as bs on bh.billing_head_id=bs.billing_head_id left join spa_services_master as srv on bs.services_id=srv.services_id left join spa_employee_master as billby on bh.bill_by=billby.employee_id left join spa_customer_master as cust on bh.customer_id=cust.customer_id $Setup_filt $where and bh.is_deleted<>1 and bh.bill_cancelled<>1  ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr);  
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;    
		  
		  $sql=" select bh.billing_head_id, bh.bill_number, bh.bill_date, group_concat(srv.services_name) as grp_services, bh.total_bill_amount, if(coalesce(cust.customer_id,0)=0,bh.non_db_customer_name,cust.customer_name) as customer_name, cust.customer_mobile, billby.employee_name as billby_username, bh.bill_cancelled from spa_billing_head as bh left join spa_billing_sub as bs on bh.billing_head_id=bs.billing_head_id left join spa_services_master as srv on bs.services_id=srv.services_id left join spa_employee_master as billby on bh.bill_by=billby.employee_id left join spa_customer_master as cust on bh.customer_id=cust.customer_id $Setup_filt $where and bh.is_deleted<>1 and bh.bill_cancelled<>1 group by bh.billing_head_id order by bh.bill_date desc "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			$xpstsclr[0]="#333399";
			$xpstsclr[1]="#00CC33";
			$xpstsclr[2]="#FF6633";
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start; 
			$sumTotalAmnt=0;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["billing_head_id"]);
				
				$bill_number=$this->purifyString($rs["bill_number"]); 
				$bill_date=$this->convertDate($this->purifyString($rs["bill_date"])); 
				$grp_services=$this->purifyString($rs["grp_services"]); 
				$total_bill_amount=$this->purifyString($rs["total_bill_amount"]);
				$customer_name=$this->purifyString($rs["customer_name"]);
				$billby_username=$this->purifyString($rs["billby_username"]);
				$bill_cancelled=$this->purifyString($rs["bill_cancelled"]); 
				$customer_mobile=$this->purifyString($rs["customer_mobile"]);
				
				$actCtrl='<span class="edit js-open-modal" data-modal-id="popup1" onclick="ViewBilledDetails('.$cid.');"><i class="fa fa-edit"></i> View </span> '; 
				
				$bill_can_txt="";
				if($bill_cancelled==1) $bill_can_txt='<span style="color:#FF6633"><strong>(Cancelled)</strong></span>';
				
				$sumTotalAmnt+=$total_bill_amount;
 
				$sendRs[$rsCnt]=array($PageSno+1, $bill_number, $bill_date, $grp_services, $total_bill_amount, $customer_name, $customer_mobile, $billby_username);
				$rsCnt++;
				$PageSno++;
				
				
			}
			
 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	} 
	 
	public function getSingleView($postArr)
	{
		$sess_userid = $this->sess_userid;
		$sess_logintype = $this->sess_logintype; 
		  
		$from_date = $this->convertDate(date('Y-m-d', strtotime(date('Y-m-d')."-15 day")));
		$to_date = $this->convertDate(date('Y-m-d'));  
		
		$categorylist = $this->getModuleComboList('category', 'all');
		$serviceslist = $this->getModuleComboList('services', 'all'); 
		$employeelist = $this->getModuleComboList('user', 'all'); 
		
		$sendRs=array("from_date"=>$from_date, "to_date"=>$to_date, "categorylist"=>$categorylist, "serviceslist"=>$serviceslist, "employeelist"=>$employeelist, "ref_employee"=>$employeelist);  
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	
	public function __destruct() 
	{
		
	} 
}

?>