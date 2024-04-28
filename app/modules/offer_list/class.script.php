<?php	

class offer_list extends common
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
		  $offer_date = ($postArr['offer_date'])?$postArr['offer_date']:date('Y-m-d');
		  
		  $start=(int) $start; 
		  $limit=(int) $limit; 
		 
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where .= 'and (par.parent_productline_name like :search_str or prd.productline_name like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  if($offer_date)
		  {
		  		$query_val = "%".$search."%"; 
				$where .= "and DATE_FORMAT(ofl.offer_date,'%m-%Y') = DATE_FORMAT('$offer_date','%m-%Y')"; 
				//$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }

		  
		  $tot_sql="select count(*) as cnt from srt_offer_list_master as ofl left join srt_parent_productline_master as par on ofl.parent_productline_id=par.parent_productline_id left join srt_productline_master prd on ofl.productline_id=prd.productline_id where ofl.is_deleted<>1 $where ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select ofl.offer_list_id, par.parent_productline_name, prd.productline_name, case ofl.vechile_type when 1 then 'Own board' when 2 then 'Taxi' when 3 then 'CSD' else '' end as vechile_type_desc, ofl.offer_date, ofl.cash_offer_tata, ofl.cash_offer_srt, ofl.exchange_offer_tata, ofl.exchange_offer_srt, ofl.corporate_offer_tata, ofl.corporate_offer_srt, ofl.edr_offer_tata, ofl.edr_offer_srt, ofl.active_status, case ofl.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_offer_list_master as ofl left join srt_parent_productline_master as par on ofl.parent_productline_id=par.parent_productline_id left join srt_productline_master prd on ofl.productline_id=prd.productline_id where ofl.is_deleted<>1 $where  order by par.parent_productline_name, prd.productline_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["offer_list_id"]);
				$parent_productline_name=$this->purifyString($rs["parent_productline_name"]);
				$productline_name=$this->purifyString($rs["productline_name"]);
				$vechile_type_desc=$this->purifyString($rs["vechile_type_desc"]);
				$offer_date=$this->convertDate($this->purifyString($rs["offer_date"])); 
				$total_offer="-"; 
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				$cash_offer_tata=($rs["cash_offer_tata"])?$rs["cash_offer_tata"]:'';
				$cash_offer_srt=($rs["cash_offer_srt"])?$rs["cash_offer_srt"]:'';
				$exchange_offer_tata=($rs["exchange_offer_tata"])?$rs["exchange_offer_tata"]:'';
				$exchange_offer_srt=($rs["exchange_offer_srt"])?$rs["exchange_offer_srt"]:'';
				$corporate_offer_tata=($rs["corporate_offer_tata"])?$rs["corporate_offer_tata"]:'';
				$corporate_offer_srt=($rs["corporate_offer_srt"])?$rs["corporate_offer_srt"]:'';
				$edr_offer_tata=($rs["edr_offer_tata"])?$rs["edr_offer_tata"]:'';
				$edr_offer_srt=($rs["edr_offer_srt"])?$rs["edr_offer_srt"]:'';
				
				
				//$sendRs[$rsCnt]=array("offer_list_id"=>$cid,"offer_list_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$parent_productline_name, $productline_name, $vechile_type_desc, $offer_date, $cash_offer_tata, $cash_offer_srt, $exchange_offer_tata, $exchange_offer_srt, $corporate_offer_tata, $corporate_offer_srt, $edr_offer_tata, $edr_offer_srt, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateOfferListMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete act-delete"  onclick="viewDeleteOfferListMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select ofl.offer_list_id, ofl.parent_productline_id, ofl.productline_id, ofl.vechile_type, ofl.offer_date, ofl.cash_offer_tata, ofl.cash_offer_srt, ofl.exchange_offer_tata, ofl.exchange_offer_srt, ofl.corporate_offer_tata, ofl.corporate_offer_srt, ofl.edr_offer_tata, ofl.edr_offer_srt, ofl.active_status, case ofl.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, ofl.product_colour_ids,ofl.registration_type from srt_offer_list_master ofl where offer_list_id=:offer_list_id";
			$bindArr=array(":offer_list_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["offer_list_id"]);
		
			$parent_productline_id=$this->purifyString($recs["parent_productline_id"]);
			$productline_id=$this->purifyString($recs["productline_id"]);
			$vechile_type=$this->purifyString($recs["vechile_type"]);
			$cash_offer_tata=$this->purifyString($recs["cash_offer_tata"]);
			$cash_offer_srt=$this->purifyString($recs["cash_offer_srt"]);
			$exchange_offer_tata=$this->purifyString($recs["exchange_offer_tata"]);
			$exchange_offer_srt=$this->purifyString($recs["exchange_offer_srt"]);
			$corporate_offer_tata=$this->purifyString($recs["corporate_offer_tata"]);
			$corporate_offer_srt=$this->purifyString($recs["corporate_offer_srt"]);
			$edr_offer_tata=$this->purifyString($recs["edr_offer_tata"]);
			$edr_offer_srt=$this->purifyString($recs["edr_offer_srt"]);
			$registration_type=$this->purifyString($recs["registration_type"]);
			
			$offer_date=$this->convertDate($this->purifyString($recs["offer_date"])); 
			
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			
			$product_colour_ids =$this->purifyString($recs["product_colour_ids"]); 
			
			$parent_productlinelist = $this->getModuleComboList('parent_productline', $parent_productline_id);
			$productlinelist = $this->getModuleComboList('productline', $productline_id); 
			$productcolourlist = $this->getModuleComboList('productcolour', $product_colour_ids);
			
			$sendRs=array("offer_list_id"=>$cid,"parent_productline_id"=>$parent_productline_id, "productline_id"=>$productline_id, "vechile_type"=>$vechile_type, "offer_date"=>$offer_date, "cash_offer_tata"=>$cash_offer_tata, "cash_offer_srt"=>$cash_offer_srt, "exchange_offer_tata"=>$exchange_offer_tata, "exchange_offer_srt"=>$exchange_offer_srt, "corporate_offer_tata"=>$corporate_offer_tata, "corporate_offer_srt"=>$corporate_offer_srt, "edr_offer_tata"=>$edr_offer_tata, "edr_offer_srt"=>$edr_offer_srt, "status"=>$cstatus,"status_desc"=>$cstatus_desc,"parent_productlinelist"=>$parent_productlinelist,"productlinelist"=>$productlinelist, "productcolourlist"=>$productcolourlist, 'product_colour_ids'=>$product_colour_ids, 'registration_type'=>$registration_type); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$parent_productline_id=$this->purifyInsertString($postArr["parent_productline_id"]);
		//$productline_id=$this->purifyInsertString($postArr["productline_id"]);
		$vechile_type=$this->purifyInsertString($postArr["vechile_type"]); 
		$offer_date=$this->convertDate($this->purifyInsertString($postArr["offer_date"]));   
		$cash_offer_tata=$this->purifyInsertString($postArr["cash_offer_tata"]);
		$cash_offer_srt=$this->purifyInsertString($postArr["cash_offer_srt"]);
		$exchange_offer_tata=$this->purifyInsertString($postArr["exchange_offer_tata"]);
		$exchange_offer_srt=$this->purifyInsertString($postArr["exchange_offer_srt"]);
		$corporate_offer_tata=$this->purifyInsertString($postArr["corporate_offer_tata"]);
		$corporate_offer_srt=$this->purifyInsertString($postArr["corporate_offer_srt"]);
		$edr_offer_tata=$this->purifyInsertString($postArr["edr_offer_tata"]);
		$edr_offer_srt=$this->purifyInsertString($postArr["edr_offer_srt"]);
		$chk_product_colour = (is_array($postArr['chk_product_colour'])?implode(',',$postArr['chk_product_colour']):'');
		$product_colour_ids=$this->purifyInsertString($chk_product_colour);
		$registration_type=$this->purifyInsertString($postArr["registration_type"]);
		$chk_product_line = (is_array($postArr['chk_product_line'])?implode(',',$postArr['chk_product_line']):'');
		$productline_id = $this->purifyInsertString($chk_product_line);		
		
		$status=$this->purifyInsertString($postArr["offer_list_status"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_offer_list_master where offer_list_id=:offer_list_id "; 
		$bindExtCntArr=array(":offer_list_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_offer_list_master SET parent_productline_id=:parent_productline_id, productline_id=:productline_id, vechile_type=:vechile_type, offer_date=:offer_date, cash_offer_tata=:cash_offer_tata, cash_offer_srt=:cash_offer_srt, exchange_offer_tata=:exchange_offer_tata, exchange_offer_srt=:exchange_offer_srt, corporate_offer_tata=:corporate_offer_tata, corporate_offer_srt=:corporate_offer_srt, edr_offer_tata=:edr_offer_tata, edr_offer_srt=:edr_offer_srt, active_status=:active_status,product_colour_ids=:product_colour_ids,registration_type=:registration_type";
		
		$insBind=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":offer_date"=>array("value"=>$offer_date,"type"=>"text"), ":cash_offer_tata"=>array("value"=>$cash_offer_tata,"type"=>"text"), ":cash_offer_srt"=>array("value"=>$cash_offer_srt,"type"=>"text"), ":exchange_offer_tata"=>array("value"=>$exchange_offer_tata,"type"=>"text"), ":exchange_offer_srt"=>array("value"=>$exchange_offer_srt,"type"=>"text"), ":corporate_offer_tata"=>array("value"=>$corporate_offer_tata,"type"=>"text"), ":corporate_offer_srt"=>array("value"=>$corporate_offer_srt,"type"=>"text"), ":edr_offer_tata"=>array("value"=>$edr_offer_tata,"type"=>"text"), ":edr_offer_srt"=>array("value"=>$edr_offer_srt,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":product_colour_ids"=>array("value"=>$product_colour_ids,"type"=>"text"),':registration_type'=>array("value"=>$registration_type,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_offer_list_master where parent_productline_id=:parent_productline_id and vechile_type=:vechile_type and registration_type=:registration_type and  date_format(offer_date, '%m-%Y')=date_format(:offer_date, '%m-%Y') and is_deleted<>1 ";
		$bindExtChkArr=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":offer_date"=>array("value"=>$offer_date,"type"=>"text"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int")); 
		
		if($productline_id)
		{
			$sql_ext_chk.=" and productline_id in ($productline_id) "; 
		}
		if($product_colour_ids)
		{
			$sql_ext_chk.=" and product_colour_ids in ($product_colour_ids) "; 
		}
		
		$insUpType="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where offer_list_id=:offer_list_id ";
			$insBind[":offer_list_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and offer_list_id<>:offer_list_id ";
			$bindExtChkArr[":offer_list_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Offer List updated successfully!";
		}
		else
		{
			$insUpType="insert";
			
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id ";  
			
			$opmsg="Offer List inserted successfully!";
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
				if($insUpType=="insert")
				{
					$mx_sql="select max(offer_list_id) as max_id from srt_offer_list_master ";
					$rsmax = $this->pdoObj->fetchSingle($mx_sql);
					
					$id=$rsmax["max_id"];   
				}
				
				if($id) $this->updateBokkingOfferPrices($id);
				
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
		
		//$strQuery=" delete from srt_offer_list_master $Setup_filt and offer_list_id=:offer_list_id "; 
		$strQuery=" update srt_offer_list_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where offer_list_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Offer List details deleted successfully'; 
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
			$whereor = " and (active_status = 1 or offer_list_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		   if($id == 'only_service')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " and coalesce(offer_list_id,0)>0 ";
		  }
		  $sql="select offer_list_id, offer_list_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_offer_list_master where 1 $whereor and is_deleted<>1 order by offer_list_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'OfferList referring Sub OfferList' as msg from bud_subOfferList_master where offer_list_id=:id) union all (select count(*) as ext_cnt, 'OfferList linked to Expenses' as msg from bud_expense_details where offer_list_id=:id) "; 
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
			$_msg = "OfferList cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function duplicateEntryMonthYear($postArr)
	{	
		$offer_date = $postArr['offer_date'];
		$created_by = $this->sess_userid;
		$strQuery = "insert into srt_offer_list_master(parent_productline_id, productline_id, vechile_type, offer_date, cash_offer_tata, cash_offer_srt, exchange_offer_tata, exchange_offer_srt, corporate_offer_tata, corporate_offer_srt, edr_offer_tata, edr_offer_srt, active_status, createdon, createdby,product_colour_ids,registration_type) select parent_productline_id, productline_id, vechile_type, concat(date_format(curdate(), '%Y-%m'),date_format(offer_date, '-%d')), cash_offer_tata, cash_offer_srt, exchange_offer_tata, exchange_offer_srt, corporate_offer_tata, corporate_offer_srt, edr_offer_tata, edr_offer_srt, active_status, now(), $created_by,product_colour_ids,registration_type from srt_offer_list_master where DATE_FORMAT(offer_date,'%m-%Y') = :offer_date";
		$strQuery = "select parent_productline_id, productline_id, vechile_type, concat(date_format(curdate(), '%Y-%m'),date_format(offer_date, '-%d')) as offer_date, cash_offer_tata, cash_offer_srt, exchange_offer_tata, exchange_offer_srt, corporate_offer_tata, corporate_offer_srt, edr_offer_tata, edr_offer_srt, active_status, now() as createdon, $created_by as createdby, product_colour_ids,registration_type from srt_offer_list_master where DATE_FORMAT(offer_date,'%m-%Y') = :offer_date";
		$bindArr=array(":offer_date"=>array("value"=>$offer_date,"type"=>"text"));
		
		$recs = $this->pdoObj->fetchMultiple($strQuery, $bindArr);
		
				$ins=" srt_offer_list_master SET parent_productline_id=:parent_productline_id, productline_id=:productline_id, vechile_type=:vechile_type, offer_date=:offer_date, cash_offer_tata=:cash_offer_tata, cash_offer_srt=:cash_offer_srt, exchange_offer_tata=:exchange_offer_tata, exchange_offer_srt=:exchange_offer_srt, corporate_offer_tata=:corporate_offer_tata, corporate_offer_srt=:corporate_offer_srt, edr_offer_tata=:edr_offer_tata, edr_offer_srt=:edr_offer_srt, active_status=:active_status,product_colour_ids=:product_colour_ids,registration_type=:registration_type";
				
				/*print_r($recs);
				
				exit;*/
		$no_recs = 0;
		
		foreach($recs as $rs)
		{		
				$parent_productline_id = $rs['parent_productline_id'];
				$productline_id = $rs['productline_id'];
				$offer_date = $rs['offer_date'];
				$vechile_type = $rs['vechile_type'];
				$cash_offer_tata = $rs['cash_offer_tata'];
				$cash_offer_srt = $rs['cash_offer_srt'];
				$exchange_offer_tata = $rs['exchange_offer_tata'];
				$exchange_offer_srt = $rs['exchange_offer_srt'];
				$corporate_offer_tata = $rs['corporate_offer_tata'];
				$corporate_offer_srt = $rs['corporate_offer_srt'];
				$edr_offer_tata = $rs['edr_offer_tata'];
				$edr_offer_srt = $rs['edr_offer_srt'];
				$edr_offer_tata = $rs['edr_offer_tata'];
				$active_status = $rs['active_status'];
				$product_colour_ids = $rs['product_colour_ids'];
				$registration_type = (int) $rs['registration_type'];
				
				
				
				
				$insBind=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int")	, ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":offer_date"=>array("value"=>$offer_date,"type"=>"text"), ":cash_offer_tata"=>array("value"=>$cash_offer_tata,"type"=>"text"), ":cash_offer_srt"=>array("value"=>$cash_offer_srt,"type"=>"text"), ":exchange_offer_tata"=>array("value"=>$exchange_offer_tata,"type"=>"text"), ":exchange_offer_srt"=>array("value"=>$exchange_offer_srt,"type"=>"text"), ":corporate_offer_tata"=>array("value"=>$corporate_offer_tata,"type"=>"text"), ":corporate_offer_srt"=>array("value"=>$corporate_offer_srt,"type"=>"text"), ":edr_offer_tata"=>array("value"=>$edr_offer_tata,"type"=>"text"), ":edr_offer_srt"=>array("value"=>$edr_offer_srt,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":product_colour_ids"=>array("value"=>$product_colour_ids,"type"=>"text"),':registration_type'=>array("value"=>$registration_type,"type"=>"int")); 
					
			$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_offer_list_master where parent_productline_id=:parent_productline_id and vechile_type=:vechile_type and coalesce(registration_type,0)=:registration_type and  date_format(offer_date, '%m-%Y')=date_format(:offer_date, '%m-%Y') and is_deleted<>1 ";
			$bindExtChkArr=array(":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":vechile_type"=>array("value"=>$vechile_type,"type"=>"int"), ":offer_date"=>array("value"=>$offer_date,"type"=>"text"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int")); 
			
			if($productline_id)
			{
				$sql_ext_chk.=" and productline_id in ($productline_id) "; 
			}
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
							$mx_sql="select max(offer_list_id) as max_id from srt_offer_list_master ";
							$rsmax = $this->pdoObj->fetchSingle($mx_sql);
							
							$mxid=$rsmax["max_id"];   
							
							if($mxid) $this->updateBokkingOfferPrices($mxid);
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
			$opMessage='Offer List duplicate entry created successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}
	public function updateBokkingOfferPrices($id)
	{
		$created_by = $this->sess_userid;
		
		$off_sql="select offer_list_id,parent_productline_id,productline_id,vechile_type,product_colour_ids,registration_type, offer_date, cash_offer_tata,cash_offer_srt,exchange_offer_tata,exchange_offer_srt,corporate_offer_tata,corporate_offer_srt,edr_offer_tata,edr_offer_srt from srt_offer_list_master where offer_list_id=:offer_list_id and date_format(offer_date,'%m-%Y')=date_format(curdate(),'%m-%Y')";
		$bindOffrArr=array( ":offer_list_id"=>array("value"=>$id,"dtype"=>"int"));
		$rs_offer = $this->pdoObj->fetchSingle($off_sql, $bindOffrArr); 
		
		if(!$rs_offer["product_colour_ids"]) $rs_offer["product_colour_ids"]="0";
		if(!$rs_offer["productline_id"]) $rs_offer["productline_id"]="0";
		
		$booking_sql="select bk.booking_transaction_id, bk.insurance_type, bk.corporate_type, bk.ex_vechicle  from srt_booking_transaction as bk left join srt_retail as ret on bk.booking_transaction_id=ret.retail_id where coalesce(ret.invoice_no,'')='' and parent_product_line=:parent_product_line and product_line in (".$rs_offer["productline_id"].") and product_color_primary in (".$rs_offer["product_colour_ids"].") and vehicle_type=:vehicle_type and registration_type=:registration_type ";
		$bindBkArr=array( ":parent_product_line"=>array("value"=>$rs_offer["parent_productline_id"],"dtype"=>"int"), ":vehicle_type"=>array("value"=>$rs_offer["vechile_type"],"dtype"=>"int"), ":registration_type"=>array("value"=>$rs_offer["registration_type"],"dtype"=>"int"));		
		$rs_bk = $this->pdoObj->fetchMultiple($booking_sql, $bindBkArr);
		
		foreach($rs_bk as $rs_val)
		{
			if($rs_val["ex_vechicle"]!="1")
			{
				$rs_offer["exchange_offer_tata"]="0.00";
				$rs_offer["exchange_offer_srt"]="0.00";
			}
			if($rs_val["corporate_type"]!="1")
			{ 
				$rs_offer["corporate_offer_tata"]="0.00"; 
				$rs_offer["corporate_offer_srt"]="0.00"; 
			}
			
			$inslogbk="insert into srt_bakup_booking_transaction_offer select *, now() as bakup_bk_offer_created_on, :sess_user_id as bakup_bk_offer_created_user from srt_booking_transaction where booking_transaction_id=:booking_transaction_id";
			$bindLogArr=array( ":booking_transaction_id"=>array("value"=>$rs_val["booking_transaction_id"],"dtype"=>"int"), ":sess_user_id"=>array("value"=>$created_by,"dtype"=>"int"));
			$this->pdoObj->execute($inslogbk, $bindLogArr);
			
			
			$bk_update="update srt_booking_transaction set cosumer_offer=:cosumer_offer, cosumer_offer_srt=:cosumer_offer_srt, corporate_offer=:corporate_offer, corporate_offer_srt=:corporate_offer_srt, exchange_offer=:exchange_offer, exchange_offer_srt=:exchange_offer_srt, edr=:edr, edr_srt=:edr_srt where booking_transaction_id=:booking_transaction_id ";
			$bindBkUpArr=array( ":booking_transaction_id"=>array("value"=>$rs_val["booking_transaction_id"],"dtype"=>"int"), ":cosumer_offer"=>array("value"=>$rs_offer["cash_offer_tata"],"dtype"=>"text"), ":cosumer_offer_srt"=>array("value"=>$rs_offer["cash_offer_srt"],"dtype"=>"text"), ":corporate_offer"=>array("value"=>$rs_offer["corporate_offer_tata"],"dtype"=>"text"), ":corporate_offer_srt"=>array("value"=>$rs_offer["corporate_offer_srt"],"dtype"=>"text"), ":exchange_offer"=>array("value"=>$rs_offer["exchange_offer_tata"],"dtype"=>"text"), ":exchange_offer_srt"=>array("value"=>$rs_offer["exchange_offer_srt"],"dtype"=>"text"), ":edr"=>array("value"=>$rs_offer["edr_offer_tata"],"dtype"=>"text"), ":edr_srt"=>array("value"=>$rs_offer["edr_offer_srt"],"dtype"=>"text"));
			$ex_bk_up=$this->pdoObj->execute($bk_update, $bindBkUpArr);
			
			if($ex_bk_up)
			{
			
				$bk_up_total="update  srt_booking_transaction set   total_tata=coalesce(cosumer_offer,0)+ coalesce(corporate_offer,0) +  coalesce(exchange_offer,0) + coalesce(access_offer,0) + coalesce(insurance_offer,0)+ coalesce(add_discount,0)+ coalesce(edr,0)+ coalesce(other_contribution,0),   
  total_srt=coalesce(cosumer_offer_srt,0)+    coalesce(corporate_offer_srt,0)+  coalesce(exchange_offer_srt,0)+ coalesce(access_offer_srt,0) + coalesce(insurance_offer_srt,0)+ coalesce(add_discount_srt,0)+   coalesce( edr_srt,0)+ coalesce(other_contribution_srt,0)  where booking_transaction_id=:booking_transaction_id ";
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