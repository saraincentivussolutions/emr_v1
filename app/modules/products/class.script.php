<?php	

class Products extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{  
		  $bindArr=array();
		  $sess_spa_setup_id = $this->sess_spa_setup_id;
		  
		  $Setup_filt = " where 1=2 ";
		  if($sess_spa_setup_id) 
		  {
		  	$Setup_filt = " where spa_setup_id=:spa_setup_id ";
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
				$where = 'and product_name like :search_str'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from spa_product_master $Setup_filt $where and is_deleted<>1";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select product_id, product_name, product_price, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from spa_product_master $Setup_filt $where and is_deleted<>1 order by product_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["product_id"]);
				$cname=$this->purifyString($rs["product_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$cprice=$this->purifyString($rs["product_price"]);
				
				
				//$sendRs[$rsCnt]=array("product_id"=>$cid,"product_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$cprice,$cstatus_desc, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateProductsMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteProductsMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select product_id, product_name, product_price, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from spa_product_master where product_id=:product_id";
			$bindArr=array(":product_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["product_id"]);
		
			$cname=$this->purifyString($recs["product_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$cprice=$this->purifyString($recs["product_price"]);
			
			$sendRs=array("product_id"=>$cid,"product_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc,"product_price"=>$cprice ); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$sess_spa_setup_id = $this->sess_spa_setup_id;
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$product_name=$this->purifyInsertString($postArr["products_name"]);
		$status=$this->purifyInsertString($postArr["products_status"]);
		$product_price=$this->purifyInsertString($postArr["products_price"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from spa_services_master where services_id=:services_id "; 
		$bindExtCntArr=array(":services_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" spa_services_master SET services_name=:services_name, active_status=:active_status, services_price=:services_price";
		$insBind=array(":services_name"=>array("value"=>$product_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),':services_price'=>array("value"=>$product_price,"type"=>"text")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from spa_services_master where trim(services_name)=:services_name and spa_setup_id=:spa_setup_id and is_deleted<>1 and category_id=-2 ";
		$bindExtChkArr=array(":services_name"=>array("value"=>$product_name,"dtype"=>"text"), ":spa_setup_id"=>array("value"=>$sess_spa_setup_id,"dtype"=>"int")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where services_id=:services_id ";
			$insBind[":services_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and services_id<>:services_id ";
			$bindExtChkArr[":services_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Product updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id, spa_setup_id=:spa_setup_id, category_id=-2 "; 
			$insBind[":spa_setup_id"]=array("value"=>$sess_spa_setup_id,"type"=>"int"); 
			
			$opmsg="Product inserted successfully!";
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
		
		$sess_spa_setup_id = $this->sess_spa_setup_id;
		
		$Setup_filt = " where 1=2 ";
		if($sess_spa_setup_id) 
		{
			$Setup_filt = " where spa_setup_id=:spa_setup_id ";
			$bindArr[':spa_setup_id']=array("value"=>$sess_spa_setup_id,"type"=>"int");
		} 
		 
		$strQuery=" update spa_services_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id $Setup_filt and services_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Product details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
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
			$whereor = " and (active_status = 1 or product_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		  $sql="select product_id,product_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from spa_product_master $Setup_filt $whereor and is_deleted<>1 order by product_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product referring Sub Product' as msg from bud_subcategory_master where product_id=:id) union all (select count(*) as ext_cnt, 'Category linked to Expenses' as msg from bud_expense_details where product_id=:id) "; 
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
			$_msg = "Product cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>