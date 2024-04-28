<?php	

class price_list extends common
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
		  
		  $price_date = ($postArr['price_date'])?$postArr['price_date']:date('Y-m-d');
		 
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where = 'and (par.parent_productline_name like :search_str or prd.productline_name like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  if($price_date)
		  {
		  		$query_val = "%".$search."%"; 
				$where .= "and DATE_FORMAT(ofl.price_date,'%m-%Y') = DATE_FORMAT('$price_date','%m-%Y')"; 
				//$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_price_list_master as ofl left join srt_parent_productline_master as par on ofl.parent_productline_id=par.parent_productline_id left join srt_productline_master prd on ofl.productline_id=prd.productline_id where ofl.is_deleted<>1 $where ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select ofl.price_list_id, par.parent_productline_name, prd.productline_name, case ofl.vechile_type when 1 then 'Own board' when 2 then 'Taxi' when 3 then 'CSD' else '' end as vechile_type_desc, ofl.price_date, ofl.ex_showroom_amount, ofl.insurance_amount, ofl.taxi_chg_amount, ofl.accessories_amount, ofl.tax_amount, ofl.ew_amount, ofl.nill_depriciation_amount, ofl.cc_amount, ofl.onroad_amount, ofl.onroad_nill_amount, ofl.active_status, case ofl.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_price_list_master as ofl left join srt_parent_productline_master as par on ofl.parent_productline_id=par.parent_productline_id left join srt_productline_master prd on ofl.productline_id=prd.productline_id where ofl.is_deleted<>1 $where  order by par.parent_productline_name, prd.productline_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["price_list_id"]);
				$parent_productline_name=$this->purifyString($rs["parent_productline_name"]);
				$productline_name=$this->purifyString($rs["productline_name"]);
				$vechile_type_desc=$this->purifyString($rs["vechile_type_desc"]);
				$price_date=$this->convertDate($this->purifyString($rs["price_date"])); 
				$ex_showroom_amount=$this->purifyString($rs["ex_showroom_amount"]);
				$insurance_amount=$this->purifyString($rs["insurance_amount"]);
				$taxi_chg_amount=$this->purifyString($rs["taxi_chg_amount"]);
				$accessories_amount=$this->purifyString($rs["accessories_amount"]);
				$tax_amount=$this->purifyString($rs["tax_amount"]);
				$ew_amount=$this->purifyString($rs["ew_amount"]);
				$nill_depriciation_amount=$this->purifyString($rs["nill_depriciation_amount"]);
				$onroad_amount=$this->purifyString($rs["onroad_amount"]);
				$onroad_nill_amount=$this->purifyString($rs["onroad_nill_amount"]);
				
				 
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				
				//$sendRs[$rsCnt]=array("price_list_id"=>$cid,"price_list_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$parent_productline_name, $productline_name, $vechile_type_desc, $price_date, number_format($ex_showroom_amount,2), number_format($insurance_amount,2), number_format($tax_amount,2), number_format($accessories_amount,2), number_format($taxi_chg_amount,2), number_format($ew_amount,2), number_format($onroad_amount,2), number_format($nill_depriciation_amount,2), number_format($onroad_nill_amount,2), '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdatePriceListMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeletePriceListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select ofl.price_list_id, ofl.parent_productline_id, ofl.productline_id, ofl.vechile_type, ofl.price_date, ofl.ex_showroom_amount, ofl.insurance_amount, ofl.taxi_chg_amount, ofl.accessories_amount, ofl.tax_amount, ofl.ew_amount, ofl.nill_depriciation_amount, ofl.cc_amount, ofl.onroad_amount, ofl.onroad_nill_amount, ofl.active_status, case ofl.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc,ofl.product_colour_ids,ofl.registration_type  from srt_price_list_master ofl where price_list_id=:price_list_id";
			$bindArr=array(":price_list_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["price_list_id"]);
		
			$parent_productline_id=$this->purifyString($recs["parent_productline_id"]);
			$productline_id=$this->purifyString($recs["productline_id"]);
			$vechile_type=$this->purifyString($recs["vechile_type"]);
			$ex_showroom_amount=$this->purifyString($recs["ex_showroom_amount"]);
			$insurance_amount=$this->purifyString($recs["insurance_amount"]);
			$taxi_chg_amount=$this->purifyString($recs["taxi_chg_amount"]);
			$accessories_amount=$this->purifyString($recs["accessories_amount"]);
			$tax_amount=$this->purifyString($recs["tax_amount"]);
			$ew_amount=$this->purifyString($recs["ew_amount"]);
			$nill_depriciation_amount=$this->purifyString($recs["nill_depriciation_amount"]);
			$cc_amount=$this->purifyString($recs["cc_amount"]);
			$onroad_amount=$this->purifyString($recs["onroad_amount"]);
			$onroad_nill_amount=$this->purifyString($recs["onroad_nill_amount"]);
			$registration_type=$this->purifyString($recs["registration_type"]);
			
			$price_date=$this->convertDate($this->purifyString($recs["price_date"])); 
			
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			
			$product_colour_ids =$this->purifyString($recs["product_colour_ids"]); 
			
			$parent_productlinelist = $this->getModuleComboList('parent_productline', $parent_productline_id);
			$productlinelist = $this->getModuleComboList('productline', $productline_id); 
			$productcolourlist = $this->getModuleComboList('productcolour', $product_colour_ids); 
			
			$sendRs=array("price_list_id"=>$cid,"parent_productline_id"=>$parent_productline_id, "productline_id"=>$productline_id, "vechile_type"=>$vechile_type, "price_date"=>$price_date, "ex_showroom_amount"=>$ex_showroom_amount, "insurance_amount"=>$insurance_amount, "taxi_chg_amount"=>$taxi_chg_amount, "accessories_amount"=>$accessories_amount, "tax_amount"=>$tax_amount, "ew_amount"=>$ew_amount, "nill_depriciation_amount"=>$nill_depriciation_amount, "cc_amount"=>$cc_amount, "onroad_amount"=>$onroad_amount, "onroad_nill_amount"=>$onroad_nill_amount, "status"=>$cstatus,"status_desc"=>$cstatus_desc,"parent_productlinelist"=>$parent_productlinelist,"productlinelist"=>$productlinelist, "productcolourlist"=>$productcolourlist, 'product_colour_ids'=>$product_colour_ids, 'registration_type'=>$registration_type); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$parent_productline_id=$this->purifyInsertString($postArr["parent_productline_id"]);
		$productline_id=$this->purifyInsertString($postArr["productline_id"]);
		$vechile_type=$this->purifyInsertString($postArr["vechile_type"]); 
		$price_date=$this->convertDate($this->purifyInsertString($postArr["price_date"]));   
		$ex_showroom_amount=$this->purifyInsertString($postArr["ex_showroom_amount"]);
		$insurance_amount=$this->purifyInsertString($postArr["insurance_amount"]);
		$taxi_chg_amount=$this->purifyInsertString($postArr["taxi_chg_amount"]);
		$accessories_amount=$this->purifyInsertString($postArr["accessories_amount"]);
		$tax_amount=$this->purifyInsertString($postArr["tax_amount"]);
		$ew_amount=$this->purifyInsertString($postArr["ew_amount"]);
		$nill_depriciation_amount=$this->purifyInsertString($postArr["nill_depriciation_amount"]);
		$cc_amount=$this->purifyInsertString($postArr["cc_amount"]);
		$onroad_amount=$this->purifyInsertString($postArr["onroad_amount"]);
		$onroad_nill_amount=$this->purifyInsertString($postArr["onroad_nill_amount"]);
		$registration_type=$this->purifyInsertString($postArr["registration_type"]);
		
		
		$chk_product_colour = (is_array($postArr['chk_product_colour'])?implode(',',$postArr['chk_product_colour']):'');
		$product_colour_ids=$this->purifyInsertString($chk_product_colour);
		
		$status=$this->purifyInsertString($postArr["price_list_status"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_price_list_master where price_list_id=:price_list_id "; 
		$bindExtCntArr=array(":price_list_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_price_list_master SET parent_productline_id=:parent_productline_id, productline_id=:productline_id, vechile_type=:vechile_type, price_date=:price_date, ex_showroom_amount=:ex_showroom_amount, insurance_amount=:insurance_amount, taxi_chg_amount=:taxi_chg_amount, accessories_amount=:accessories_amount, tax_amount=:tax_amount, ew_amount=:ew_amount, nill_depriciation_amount=:nill_depriciation_amount, cc_amount=:cc_amount, onroad_amount=:onroad_amount, onroad_nill_amount=:onroad_nill_amount, active_status=:active_status,product_colour_ids=:product_colour_ids,registration_type=:registration_type";
		
		$insBind=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":price_date"=>array("value"=>$price_date,"type"=>"text"), ":ex_showroom_amount"=>array("value"=>$ex_showroom_amount,"type"=>"text"), ":insurance_amount"=>array("value"=>$insurance_amount,"type"=>"text"), ":taxi_chg_amount"=>array("value"=>$taxi_chg_amount,"type"=>"text"), ":accessories_amount"=>array("value"=>$accessories_amount,"type"=>"text"), ":tax_amount"=>array("value"=>$tax_amount,"type"=>"text"), ":ew_amount"=>array("value"=>$ew_amount,"type"=>"text"), ":nill_depriciation_amount"=>array("value"=>$nill_depriciation_amount,"type"=>"text"), ":cc_amount"=>array("value"=>$cc_amount,"type"=>"text"), ":onroad_amount"=>array("value"=>$onroad_amount,"type"=>"text"), ":onroad_nill_amount"=>array("value"=>$onroad_nill_amount,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":product_colour_ids"=>array("value"=>$product_colour_ids,"type"=>"text"),':registration_type'=>array("value"=>$registration_type,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_price_list_master where parent_productline_id=:parent_productline_id and productline_id=:productline_id and vechile_type=:vechile_type and price_date=:price_date and is_deleted<>1 and registration_type=:registration_type ";
		$bindExtChkArr=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":price_date"=>array("value"=>$price_date,"type"=>"text"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int")); 
		
		if($product_colour_ids)
		{
			$sql_ext_chk.=" and product_colour_ids in ($product_colour_ids) "; 
		}
		
		$insUpType="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where price_list_id=:price_list_id ";
			$insBind[":price_list_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and price_list_id<>:price_list_id ";
			$bindExtChkArr[":price_list_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Price List updated successfully!";
		}
		else
		{
			$insUpType="insert";
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id ";  
			
			$opmsg="Price List inserted successfully!";
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
				
				if($insUpType=="insert")
				{
					$mx_sql="select max(price_list_id) as max_id from srt_price_list_master ";
					$rsmax = $this->pdoObj->fetchSingle($mx_sql);
					
					$id=$rsmax["max_id"];   
				}
				
				if($id) $this->updateBokkingQuotePrices($id);
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);  
		
		$bindArr=array();  
		
		//$strQuery=" delete from srt_price_list_master $Setup_filt and price_list_id=:price_list_id "; 
		$strQuery=" update srt_price_list_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where price_list_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Price List details deleted successfully'; 
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
			$whereor = " and (active_status = 1 or price_list_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(price_list_id,0)>0 ";
		  }
		  $sql="select price_list_id, price_list_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_price_list_master where 1 $whereor and is_deleted<>1 order by price_list_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'PriceList referring Sub PriceList' as msg from bud_subPriceList_master where price_list_id=:id) union all (select count(*) as ext_cnt, 'PriceList linked to Expenses' as msg from bud_expense_details where price_list_id=:id) "; 
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
			$_msg = "PriceList cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function duplicateEntryMonthYear($postArr)
	{	
		$price_date = $postArr['price_date'];
		$created_by = $this->sess_userid;
		$strQuery = "select parent_productline_id, productline_id, vechile_type, concat(date_format(curdate(), '%Y-%m'),date_format(price_date, '-%d')) as price_date, ex_showroom_amount, insurance_amount, taxi_chg_amount, accessories_amount, tax_amount, ew_amount, nill_depriciation_amount, cc_amount, onroad_amount, onroad_nill_amount, active_status, product_colour_ids,registration_type from srt_price_list_master where DATE_FORMAT(price_date,'%m-%Y') = :price_date";
		$bindArr=array(":price_date"=>array("value"=>$price_date,"type"=>"text"));
		
		$recs = $this->pdoObj->fetchMultiple($strQuery, $bindArr);
		
		$ins=" srt_price_list_master SET parent_productline_id=:parent_productline_id, productline_id=:productline_id, vechile_type=:vechile_type, price_date=:price_date, ex_showroom_amount=:ex_showroom_amount, insurance_amount=:insurance_amount, taxi_chg_amount=:taxi_chg_amount, accessories_amount=:accessories_amount, tax_amount=:tax_amount, ew_amount=:ew_amount, nill_depriciation_amount=:nill_depriciation_amount, cc_amount=:cc_amount, onroad_amount=:onroad_amount, onroad_nill_amount=:onroad_nill_amount, active_status=:active_status,product_colour_ids=:product_colour_ids,registration_type=:registration_type";
		
		$no_recs = 0;
		
		foreach($recs as $rs)
		{		
				$parent_productline_id = $rs['parent_productline_id'];
				$productline_id = $rs['productline_id'];
				$price_date = $rs['price_date'];
				$vechile_type = $rs['vechile_type'];
				$ex_showroom_amount = $rs['ex_showroom_amount'];
				$insurance_amount = $rs['insurance_amount'];
				$taxi_chg_amount = $rs['taxi_chg_amount'];
				$accessories_amount = $rs['accessories_amount'];
				$tax_amount = $rs['tax_amount'];
				$ew_amount = $rs['ew_amount'];
				$nill_depriciation_amount = $rs['nill_depriciation_amount'];
				$cc_amount = $rs['cc_amount'];
				$onroad_amount = $rs['onroad_amount'];
				$onroad_nill_amount = $rs['onroad_nill_amount'];
				$product_colour_ids = $rs['product_colour_ids'];
				$active_status = $rs['active_status'];
				$registration_type = (int) $rs['registration_type'];
				
				
				
				
				$insBind=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":price_date"=>array("value"=>$price_date,"type"=>"text"), ":ex_showroom_amount"=>array("value"=>$ex_showroom_amount,"type"=>"text"), ":insurance_amount"=>array("value"=>$insurance_amount,"type"=>"text"), ":taxi_chg_amount"=>array("value"=>$taxi_chg_amount,"type"=>"text"), ":accessories_amount"=>array("value"=>$accessories_amount,"type"=>"text"), ":tax_amount"=>array("value"=>$tax_amount,"type"=>"text"), ":ew_amount"=>array("value"=>$ew_amount,"type"=>"text"), ":nill_depriciation_amount"=>array("value"=>$nill_depriciation_amount,"type"=>"text"), ":cc_amount"=>array("value"=>$cc_amount,"type"=>"text"), ":onroad_amount"=>array("value"=>$onroad_amount,"type"=>"text"), ":onroad_nill_amount"=>array("value"=>$onroad_nill_amount,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":product_colour_ids"=>array("value"=>$product_colour_ids,"type"=>"text"),':registration_type'=>array("value"=>$registration_type,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_price_list_master where parent_productline_id=:parent_productline_id and productline_id=:productline_id and vechile_type=:vechile_type and price_date=:price_date and is_deleted<>1 and registration_type=:registration_type ";
		$bindExtChkArr=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":price_date"=>array("value"=>$price_date,"type"=>"text"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int"));  
			
			if($product_colour_ids)
			{
				$sql_ext_chk.=" and product_colour_ids in ($product_colour_ids) "; 
			}
			
			if($ext_cnt_val>0) 
			{ 
				
			}
			else
			{
				$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id ";  
				
				$opmsg="Offer List inserted successfully!";
				
				$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
				$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
				//echo $rec_exist_cnt_val;
				//echo $sql_ext_chk.json_encode($bindExtChkArr);
				//echo '<br>';
				//exit;
				
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
						$no_recs++;
						
						if($insUpType=="insert")
						{
							$mx_sql="select max(price_list_id) as max_id from srt_price_list_master ";
							$rsmax = $this->pdoObj->fetchSingle($mx_sql);
							
							$mxid=$rsmax["max_id"];  
							
							if($mxid) $this->updateBokkingQuotePrices($mxid); 
						} 
						
					} 
				}
			}
			

		}
		
		$opStatus='failure';
		$opMessage='No records to be duplicated.';
		if($no_recs>0)
		{
			$opStatus='success';
			$opMessage='Price List duplicate entry created successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}
	
	public function updateBokkingQuotePrices($id)
	{
		$created_by = $this->sess_userid;
		
		$off_sql="select price_list_id,parent_productline_id,productline_id,vechile_type,product_colour_ids,registration_type, price_date, ex_showroom_amount,insurance_amount,tax_amount,accessories_amount,taxi_chg_amount,ew_amount,nill_depriciation_amount, (insurance_amount+nill_depriciation_amount) as ins_nill_dep from srt_price_list_master where price_list_id=:price_list_id and date_format(price_date,'%m-%Y')=date_format(curdate(),'%m-%Y')";
		$bindOffrArr=array( ":price_list_id"=>array("value"=>$id,"dtype"=>"int"));
		$rs_offer = $this->pdoObj->fetchSingle($off_sql, $bindOffrArr); 
		
		if(!$rs_offer["product_colour_ids"]) $rs_offer["product_colour_ids"]="0"; 
		
		$booking_sql="select bk.booking_transaction_id, bk.insurance_type, bk.corporate_type, bk.ex_vechicle, bk.insurance_detail  from srt_booking_transaction as bk left join srt_retail as ret on bk.booking_transaction_id=ret.retail_id where coalesce(ret.invoice_no,'')='' and parent_product_line=:parent_product_line and product_line=:product_line and product_color_primary in (".$rs_offer["product_colour_ids"].") and vehicle_type=:vehicle_type and registration_type=:registration_type ";
		$bindBkArr=array( ":parent_product_line"=>array("value"=>$rs_offer["parent_productline_id"],"dtype"=>"int"), ":product_line"=>array("value"=>$rs_offer["productline_id"],"dtype"=>"int"), ":vehicle_type"=>array("value"=>$rs_offer["vechile_type"],"dtype"=>"int"), ":registration_type"=>array("value"=>$rs_offer["registration_type"],"dtype"=>"int"));		
		$rs_bk = $this->pdoObj->fetchMultiple($booking_sql, $bindBkArr);
		
		foreach($rs_bk as $rs_val)
		{
			if($rs_val['insurance_type']==1)
			{ 
				if($rs_val['insurance_detail']=='2' and $rs_offer["nill_depriciation_amount"]!="")
				{
					$rs_offer["insurance_amount"]=$rs_offer["ins_nill_dep"];  
				}
			}
			else { $rs_offer["insurance_amount"]="0.00"; }
			
			 
			
			$inslogbk="insert into srt_bakup_booking_transaction_price select *, now() as bakup_bk_price_created_on, :sess_user_id as bakup_bk_price_created_user from srt_booking_transaction where booking_transaction_id=:booking_transaction_id";
			$bindLogArr=array( ":booking_transaction_id"=>array("value"=>$rs_val["booking_transaction_id"],"dtype"=>"int"), ":sess_user_id"=>array("value"=>$created_by,"dtype"=>"int"));
			$this->pdoObj->execute($inslogbk, $bindLogArr);
			
			
			$bk_update="update srt_booking_transaction set ex_showroom_price=:ex_showroom_price, insurance_method=:insurance_method, rto_fee=:rto_fee, taxi_charges=:taxi_charges, accessories=:accessories, amc=:amc where booking_transaction_id=:booking_transaction_id ";
			$bindBkUpArr=array( ":booking_transaction_id"=>array("value"=>$rs_val["booking_transaction_id"],"dtype"=>"int"), 
			
			":ex_showroom_price"=>array("value"=>$rs_offer["ex_showroom_amount"],"dtype"=>"text"), ":insurance_method"=>array("value"=>$rs_offer["insurance_amount"],"dtype"=>"text"), ":rto_fee"=>array("value"=>$rs_offer["tax_amount"],"dtype"=>"text"), ":accessories"=>array("value"=>$rs_offer["accessories_amount"],"dtype"=>"text"), ":taxi_charges"=>array("value"=>$rs_offer["taxi_chg_amount"],"dtype"=>"text"), ":amc"=>array("value"=>$rs_offer["ew_amount"],"dtype"=>"text") );
			$ex_bk_up=$this->pdoObj->execute($bk_update, $bindBkUpArr);
			
			if($ex_bk_up)
			{
			
				$bk_up_total="update  srt_booking_transaction set   onroad_price=coalesce(ex_showroom_price,0)+ coalesce(insurance_method,0) +  coalesce(rto_fee,0) + coalesce(taxi_charges,0) + coalesce(accessories,0)+ coalesce(amc,0)   where booking_transaction_id=:booking_transaction_id ";
  				$bindBkUpTotArr=array( ":booking_transaction_id"=>array("value"=>$rs_val["booking_transaction_id"],"dtype"=>"int"));
				
				$this->pdoObj->execute($bk_up_total, $bindBkUpTotArr);
			} 
			
		}
	}
	
	public function __destruct() 
	{
		
	} 
}

?>