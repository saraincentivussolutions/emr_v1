<?php	

class financier extends common
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
				$where = 'and financier_name like :search_str'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_financier_master where 1 $where and is_deleted<>1 and financier_id>0";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select financier_id, financier_name, financier_contact_name, financier_contact_mobile, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_financier_master where 1 $where and is_deleted<>1 and financier_id>0 order by financier_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["financier_id"]);
				$cname=$this->purifyString($rs["financier_name"]);
				$cdesc=$this->purifyString($rs["financier_contact_name"]);
				$financier_contact_mobile=$this->purifyString($rs["financier_contact_mobile"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				
				//$sendRs[$rsCnt]=array("financier_id"=>$cid,"financier_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname, $cdesc, $financier_contact_mobile, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateFinancierMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteFinancierMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select financier_id,financier_name, financier_contact_name, financier_contact_mobile, active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_financier_master where financier_id=:financier_id";
			$bindArr=array(":financier_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["financier_id"]);
		
			$cname=$this->purifyString($recs["financier_name"]);
			$cdesc=$this->purifyString($recs["financier_contact_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$financier_contact_mobile=$this->purifyString($recs["financier_contact_mobile"]);
			
			$sendRs=array("financier_id"=>$cid,"financier_name"=>$cname, "financier_contact_name"=>$cdesc,"status"=>$cstatus,"status_desc"=>$cstatus_desc,"financier_contact_mobile"=>$financier_contact_mobile); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$financier_name=$this->purifyInsertString($postArr["financier_name"]);
		$financier_contact_name=$this->purifyInsertString($postArr["financier_contact_name"]);
		$financier_contact_mobile=$this->purifyInsertString($postArr["financier_contact_mobile"]);
		$status=$this->purifyInsertString($postArr["financier_status"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_financier_master where financier_id=:financier_id "; 
		$bindExtCntArr=array(":financier_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_financier_master SET financier_name=:financier_name, financier_contact_name=:financier_contact_name, active_status=:active_status, financier_contact_mobile=:financier_contact_mobile";
		$insBind=array(":financier_name"=>array("value"=>$financier_name,"type"=>"text"), ":financier_contact_name"=>array("value"=>$financier_contact_name,"type"=>"text"), ":financier_contact_mobile"=>array("value"=>$financier_contact_mobile,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_financier_master where trim(financier_name)=:financier_name and is_deleted<>1 ";
		$bindExtChkArr=array(":financier_name"=>array("value"=>$financier_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where financier_id=:financier_id ";
			$insBind[":financier_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and financier_id<>:financier_id ";
			$bindExtChkArr[":financier_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Financier updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id ";  
			
			$opmsg="Financier inserted successfully!";
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
		
		//$strQuery=" delete from srt_financier_master $Setup_filt and financier_id=:financier_id "; 
		$strQuery=" update srt_financier_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where financier_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Financier details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array(); 
		  
		  $whereor = ' and active_status = 1 and financier_id not in(-1, -2) ';
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or financier_id=:id) and financier_id not in(-1, -2) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(financier_id,0)>0 ";
		  }
		  $sql="select financier_id, financier_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_financier_master where 1 $whereor and is_deleted<>1 order by financier_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Financier referring Sub Financier' as msg from bud_subFinancier_master where financier_id=:id) union all (select count(*) as ext_cnt, 'Financier linked to Expenses' as msg from bud_expense_details where financier_id=:id) "; 
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
			$_msg = "Financier cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>