<?php	

class order_status extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
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
				$where = 'and orderstatus_name like :search_str'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_orderstatus_master where 1 $where and is_deleted<>1 and orderstatus_id>0";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select orderstatus_id, orderstatus_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_orderstatus_master where 1 $where and is_deleted<>1 and orderstatus_id>0 order by orderstatus_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["orderstatus_id"]);
				$cname=$this->purifyString($rs["orderstatus_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				//, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateOrderStatusMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteOrderStatusMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>' // Currently No action we add internally
				
				//$sendRs[$rsCnt]=array("orderstatus_id"=>$cid,"orderstatus_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$cstatus_desc);
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
			
			$sql="select orderstatus_id,orderstatus_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_orderstatus_master where orderstatus_id=:orderstatus_id";
			$bindArr=array(":orderstatus_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["orderstatus_id"]);
		
			$cname=$this->purifyString($recs["orderstatus_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			
			$sendRs=array("orderstatus_id"=>$cid,"orderstatus_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc,); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$orderstatus_name=$this->purifyInsertString($postArr["orderstatus_name"]);
		$status=$this->purifyInsertString($postArr["orderstatus_status"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_orderstatus_master where orderstatus_id=:orderstatus_id "; 
		$bindExtCntArr=array(":orderstatus_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_orderstatus_master SET orderstatus_name=:orderstatus_name,active_status=:active_status";
		$insBind=array(":orderstatus_name"=>array("value"=>$orderstatus_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_orderstatus_master where trim(orderstatus_name)=:orderstatus_name and is_deleted<>1 ";
		$bindExtChkArr=array(":orderstatus_name"=>array("value"=>$orderstatus_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where orderstatus_id=:orderstatus_id ";
			$insBind[":orderstatus_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and orderstatus_id<>:orderstatus_id ";
			$bindExtChkArr[":orderstatus_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Order Status updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id ";  
			
			$opmsg="Order Status inserted successfully!";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Record already exists'; 
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
		
		//$strQuery=" delete from srt_orderstatus_master $Setup_filt and orderstatus_id=:orderstatus_id "; 
		$strQuery=" update srt_orderstatus_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where orderstatus_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Order Status details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0, $onlythis="")
	{
		  $bindArr=array(); 
		  
		 
		  
		  $whereor = ' and active_status = 1 ';
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or orderstatus_id=:id) ";
		  }
		  
		  $stsfilt="";
		  if($id == 'all')
		  { 
			$whereor = " ";
		  }
		  else
		  {
		  	  if($id<=2)
			  {
				$stsfilt=" and orderstatus_id<=2 ";
			  }
			  else
			  {
			  	$stsfilt=" and orderstatus_id>2 ";	
			  }
		  }
		  
		  if($onlythis=='onlythisrec')
		  {
		  	$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and orderstatus_id=:id ";
			
		  	$sql="select orderstatus_id, orderstatus_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_orderstatus_master where is_deleted<>1 $whereor order by orderstatus_name ";
		  }
		  else
		  { 
		  	$sql="select orderstatus_id, orderstatus_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_orderstatus_master where is_deleted<>1 $whereor $stsfilt order by orderstatus_name ";
		  }
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'OrderStatus referring Sub OrderStatus' as msg from bud_subOrderStatus_master where orderstatus_id=:id) union all (select count(*) as ext_cnt, 'OrderStatus linked to Expenses' as msg from bud_expense_details where orderstatus_id=:id) "; 
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
			$_msg = "OrderStatus cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>