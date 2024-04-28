<?php	

class productcolour extends common
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
				$where = 'and (productcolour_name like :search_str or productcolour_vc like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_productcolour_master  where is_deleted<>1  $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select productcolour_id, productcolour_name, active_status, productcolour_vc, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_productcolour_master where is_deleted<>1 and productcolour_id>0 $where order by productcolour_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["productcolour_id"]);
				$cname=$this->purifyString($rs["productcolour_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]); 
				$productcolour_vc = $this->purifyString($rs["productcolour_vc"]);
				
				
				//$sendRs[$rsCnt]=array("productcolour_id"=>$cid,"productcolour_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$productcolour_vc,$cstatus_desc, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateProductColourMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteProductColourMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select productcolour_id,productcolour_name,active_status, parent_productline_ids, productcolour_vc,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_productcolour_master where productcolour_id=:productcolour_id";
			$bindArr=array(":productcolour_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["productcolour_id"]);
		
			$cname=$this->purifyString($recs["productcolour_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$parent_productline_ids = $this->purifyString($recs["parent_productline_ids"]);
			$productcolour_vc = $this->purifyString($recs["productcolour_vc"]);
			$parent_productline_list = $this->parentproductline_custom_comboview($parent_productline_ids);
			
			$sendRs=array("productcolour_id"=>$cid,"productcolour_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc, "parent_productline_ids"=>$parent_productline_ids, "productline_list"=>$parent_productline_list,'productcolour_vc'=>$productcolour_vc); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	public function parentproductline_custom_comboview($parent_productline_ids) //normal comboview should be written in respective class file only. here we facing multiple values
	{
		  $bindArr=array();
		   
		  $whereor = " and active_status = 1 ";
		  if($parent_productline_ids!="")
		  {  
			$whereor = " and (active_status = 1 or parent_productline_id in ($parent_productline_ids)) ";
		  }
		 
		 
		  $sql="select parent_productline_id, parent_productline_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_parent_productline_master where is_deleted<>1 $whereor order by parent_productline_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function saveprocess($postArr)
	{
		
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$productcolour_name=$this->purifyInsertString($postArr["productcolour_name"]);
		$status=$this->purifyInsertString($postArr["productcolour_status"]);
		$productcolour_vc=$this->purifyInsertString($postArr["productcolour_vc"]);
		
		$chk_product_line = (is_array($postArr['chk_product_line'])?implode(',',$postArr['chk_product_line']):'');
		$parent_productline_ids=$this->purifyInsertString($chk_product_line);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_productcolour_master where productcolour_id=:productcolour_id "; 
		$bindExtCntArr=array(":productcolour_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_productcolour_master SET productcolour_name=:productcolour_name,active_status=:active_status, productcolour_vc=:productcolour_vc, parent_productline_ids=:parent_productline_ids";
		$insBind=array(":productcolour_name"=>array("value"=>$productcolour_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":productcolour_vc"=>array("value"=>$productcolour_vc,"type"=>"text"),":parent_productline_ids"=>array("value"=>$parent_productline_ids,"type"=>"text")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_productcolour_master where trim(productcolour_name)=:productcolour_name  and is_deleted<>1 ";
		$bindExtChkArr=array(":productcolour_name"=>array("value"=>$productcolour_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where productcolour_id=:productcolour_id ";
			$insBind[":productcolour_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and productcolour_id<>:productcolour_id ";
			$bindExtChkArr[":productcolour_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Product Colour updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id"; 
			
			
			$opmsg="Product Colour inserted successfully!";
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
		
		//$strQuery=" delete from srt_productcolour_master $Setup_filt and productcolour_id=:productcolour_id "; 
		$strQuery=" update srt_productcolour_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where productcolour_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Product Colour details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array();
		  
		  
		  
		  $whereor = ' and active_status = 1  ';
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or productcolour_id=:id)   ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(productcolour_id,0)>0 ";
		  }
		  $sql="select productcolour_id, productcolour_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, parent_productline_ids from srt_productcolour_master where is_deleted<>1 $whereor order by productcolour_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub ProductColour' as msg from srt_productcolour_master where productcolour_id=:id)"; 
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
			$_msg = "Product Colour cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>