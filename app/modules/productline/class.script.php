<?php	

class productline extends common
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
				$where = 'and (productline_name like :search_str or parent_productline_name like :search_str or productline_vc like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_productline_master left join srt_parent_productline_master on srt_productline_master.parent_productline_id= srt_parent_productline_master.parent_productline_id  where srt_productline_master.is_deleted<>1 $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select productline_id, productline_name, srt_productline_master.active_status, parent_productline_name, productline_vc, case srt_productline_master.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_productline_master left join srt_parent_productline_master on srt_productline_master.parent_productline_id= srt_parent_productline_master.parent_productline_id where srt_productline_master.is_deleted<>1 $where order by productline_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["productline_id"]);
				$cname=$this->purifyString($rs["productline_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$parent_productline_name = $this->purifyString($rs["parent_productline_name"]);
				$productline_vc = $this->purifyString($rs["productline_vc"]);
				
				
				//$sendRs[$rsCnt]=array("productline_id"=>$cid,"productline_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1, $cname, $parent_productline_name, $productline_vc, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateProductLineMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteProductLineMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select productline_id,productline_name,active_status, parent_productline_id, productline_vc,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_productline_master where productline_id=:productline_id";
			$bindArr=array(":productline_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["productline_id"]);
		
			$cname=$this->purifyString($recs["productline_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$parent_productline_id = $this->purifyString($recs["parent_productline_id"]);
			$productline_vc = $this->purifyString($recs["productline_vc"]);
			$parent_productline = $this->getModuleComboList('parent_productline', $parent_productline_id);
			
			$sendRs=array("productline_id"=>$cid,"productline_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc, "parent_productline_id"=>$parent_productline_id, "parent_productline_list"=>$parent_productline,'productline_vc'=>$productline_vc); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$productline_name=$this->purifyInsertString($postArr["productline_name"]);
		$status=$this->purifyInsertString($postArr["productline_status"]);
		$productline_vc=$this->purifyInsertString($postArr["productline_vc"]);
		$parent_productline_id=$this->purifyInsertString($postArr["parent_productline_id"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_productline_master where productline_id=:productline_id "; 
		$bindExtCntArr=array(":productline_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_productline_master SET productline_name=:productline_name,active_status=:active_status, productline_vc=:productline_vc, parent_productline_id=:parent_productline_id";
		$insBind=array(":productline_name"=>array("value"=>$productline_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":productline_vc"=>array("value"=>$productline_vc,"type"=>"text"),":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_productline_master where trim(productline_name)=:productline_name  and is_deleted<>1 ";
		$bindExtChkArr=array(":productline_name"=>array("value"=>$productline_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where productline_id=:productline_id ";
			$insBind[":productline_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and productline_id<>:productline_id ";
			$bindExtChkArr[":productline_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Product Line updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id"; 
			
			
			$opmsg="ProductLine inserted successfully!";
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
			//echo $strQuery.json_encode($insBind);
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
		
		
		
		//$strQuery=" delete from srt_productline_master $Setup_filt and productline_id=:productline_id "; 
		$strQuery=" update srt_productline_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where productline_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Parent Product Line details deleted successfully'; 
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
			$whereor = " and (active_status = 1 or productline_id=:id)";
		  }
		 
		  $sql="select productline_id, productline_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, parent_productline_id from srt_productline_master where is_deleted<>1 $whereor order by productline_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub ProductLine' as msg from srt_productline_master where productline_id=:id)"; 
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
			$_msg = "Parent Product Line cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>