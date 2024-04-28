<?php	

class stock_entry extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $bindArr=array();
		  
		  $sess_logintype = $this->sess_logintype;
		  $sess_userid = $this->sess_userid; 
		  
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
				$where = 'and (par.parent_productline_name like :search_str or prd.productline_name like :search_str or pclr.productcolour_name like :search_str)';
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }  
		   
		  
		  
		  
		  $tot_sql="select count(distinct se.parent_productline_id, se.productline_id, se.productcolour_id) as cnt from srt_stock_master_entry as se left join srt_parent_productline_master as par on se.parent_productline_id=par.parent_productline_id left join srt_productline_master as prd on se.productline_id=prd.productline_id left join srt_productcolour_master as pclr on se.productcolour_id=pclr.productcolour_id where se.is_deleted<>1 $where and coalesce(stock_chasis_used,0)=0 ";
		 $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr);  
		   
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0; 
		  
		 $sql=" select par.parent_productline_name, prd.productline_name, pclr.productcolour_name, sum(if(se.stock_type=1,1,0)) as open_stock_cnt, sum(if(se.stock_type=2,1,0)) as gstock_cnt from srt_stock_master_entry as se left join srt_parent_productline_master as par on se.parent_productline_id=par.parent_productline_id left join srt_productline_master as prd on se.productline_id=prd.productline_id left join srt_productcolour_master as pclr on se.productcolour_id=pclr.productcolour_id where se.is_deleted<>1 $where  and coalesce(stock_chasis_used,0)=0 group by se.parent_productline_id, se.productline_id, se.productcolour_id order by par.parent_productline_name, prd.productline_name, pclr.productcolour_name "; 
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
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["billing_head_id"]);
				
				$parent_productline_name=$this->purifyString($rs["parent_productline_name"]);  
				$productline_name=$this->purifyString($rs["productline_name"]); 
				$productcolour_name=$this->purifyString($rs["productcolour_name"]);
				$open_stock_cnt=$this->purifyString($rs["open_stock_cnt"]);
				$gstock_cnt=$this->purifyString($rs["gstock_cnt"]);
				$total_stock_cnt=$open_stock_cnt+$gstock_cnt;; 
				
				//$actCtrl='<span class="edit js-open-modal" data-modal-id="popup1" onclick="ViewBilledDetails('.$cid.');"><i class="fa fa-edit"></i> View </span> '; 
				
				//$bill_can_txt="";
				//if($bill_cancelled==1) $bill_can_txt='<span style="color:#FF6633"><strong>(Cancelled)</strong></span>';
 
				$sendRs[$rsCnt]=array($PageSno+1, $parent_productline_name, $productline_name, $productcolour_name, $open_stock_cnt, $gstock_cnt, $total_stock_cnt);
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
		  
		$getid=$this->purifyInsertString($postArr["id"]);
		$stock_date = $this->convertDate(date('Y-m-d'));  
		
		$parent_productlinelist = $this->getModuleComboList('parent_productline');
		$productlinelist = $this->getModuleComboList('productline'); 
		$productcolourlist = $this->getModuleComboList('productcolour'); 
		
		$sendRs=array("stock_date"=>$stock_date, "parent_productlinelist"=>$parent_productlinelist, "productlinelist"=>$productlinelist, "productcolourlist"=>$productcolourlist);  
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	 
	 
	
	public function saveprocess($postArr)
	{ 
		$sess_userid = $this->sess_userid;
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$hid_temp_del = $this->purifyInsertString($postArr["hid_temp_del"]);   
		
		$insunqno_ref=date('dMYhis').'_'.$sess_userid;  
		 
		$txt_chasis_no_srch="";
		foreach($postArr['txt_chasis_no'] as $txt_chasis_no_idVals)
		{
			if($txt_chasis_no_idVals)
			{
				if($txt_chasis_no_srch) $txt_chasis_no_srch.=",";
				$txt_chasis_no_srch.="'{$txt_chasis_no_idVals}'";	
			}
		}
		if($txt_chasis_no_srch=="") $txt_chasis_no_srch="0"; 
		$cnt_ext_sql="select count(*) as ext_cnt from srt_stock_master_entry where chasis_no in ($txt_chasis_no_srch)"; 
		$bindExtCntArr=array();
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"]; 
		 
		$opStatus='failure';
		$opMessage='failure'; 
		
		if($ext_cnt_val>0) 
		{
			$opMessage='Chasis no already exists'; 
			$opExists='exists';
		} 
		else 
		{ 
			 
			$hdn_bill_detid = $postArr['hdn_bill_detid'];
			$txt_stock_entry_date = $postArr['txt_stock_entry_date'];
			$cmb_parentproductline = $postArr['cmb_parentproductline'];
			$cmb_productline = $postArr['cmb_productline'];
			$cmb_product_colour = $postArr['cmb_product_colour'];
			$txt_chasis_no = $postArr['txt_chasis_no'];
			$txt_purchase_cost = $postArr['txt_purchase_cost']; 
			$cmb_stock_status = $postArr['cmb_stock_status']; 
			
			foreach($txt_stock_entry_date as $cat_key=>$cat_val)
			{  
				$stock_master_entry_id = $this->purifyInsertString($hdn_bill_detid[$cat_key]);
				$stock_master_entry_date = $this->convertDate($this->purifyInsertString($txt_stock_entry_date[$cat_key]));
				$parent_productline_id = $this->purifyInsertString($cmb_parentproductline[$cat_key]);
				$productline_id = $this->purifyInsertString($cmb_productline[$cat_key]);
				$productcolour_id = $this->purifyInsertString($cmb_product_colour[$cat_key]);
				$chasis_no = $this->purifyInsertString($txt_chasis_no[$cat_key]);
				$purchase_cost = $this->purifyInsertString($txt_purchase_cost[$cat_key]); 
				$stock_type = $this->purifyInsertString($cmb_stock_status[$cat_key]);
				
				if($stock_master_entry_date and $chasis_no and $productline_id)
				{ 
					
					$ext_cnt_val=0;
					if($stock_master_entry_id>0)
					{
						// Currently no update 
						
						/*$cnt_ext_sql="select count(*) as ext_cnt from spa_billing_sub where billing_head_id=:billing_head_id and stock_master_entry_id=:stock_master_entry_id"; 
						$bindExtCntArr=array(":billing_head_id"=>array("value"=>$id,"type"=>"int"),":stock_master_entry_id"=>array("value"=>$stock_master_entry_id,"type"=>"int"));
						$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
						$ext_cnt_val=$rs_qry_exts["ext_cnt"];*/ 
					}
					
					$ins=" srt_stock_master_entry SET stock_master_entry_date=:stock_master_entry_date, parent_productline_id=:parent_productline_id, productline_id=:productline_id, productcolour_id=:productcolour_id, chasis_no=:chasis_no, purchase_cost=:purchase_cost, stock_type=:stock_type ";
					$insBind=array(":stock_master_entry_date"=>array("value"=>$stock_master_entry_date,"type"=>"text"), ":parent_productline_id"=>array("value"=>$parent_productline_id,"type"=>"int"), ":productline_id"=>array("value"=>$productline_id,"type"=>"int"), ":productcolour_id"=>array("value"=>$productcolour_id,"type"=>"int"), ":chasis_no"=>array("value"=>$chasis_no,"type"=>"text"), ":purchase_cost"=>array("value"=>$purchase_cost,"type"=>"text"), ":stock_type"=>array("value"=>$stock_type,"type"=>"int"), ":sess_user_id"=>array("value"=>$sess_userid,"type"=>"int")); 
					$mod = '';
					if($ext_cnt_val>0) 
					{ 
						$strQuery="UPDATE $ins where stock_master_entry_id=:stock_master_entry_id";
						$insBind[":stock_master_entry_id"]=array("value"=>$stock_master_entry_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						$getmaxid = $this->pdoObj->getMaxRecord('stock_master_entry_id', 'srt_stock_master_entry');
						$id = $getmaxid + 1;
						$strQuery="INSERT INTO $ins, insert_unique_ref=:insert_unique_ref, stock_master_entry_id=:stock_master_entry_id, createdon=now(), createdby=:sess_user_id "; 						$insBind[":stock_master_entry_id"]=array("value"=>$id,"type"=>"int"); 
						$insBind[":insert_unique_ref"]=array("value"=>$insunqno_ref,"dtype"=>"int");
						$mod = 'ins';  
					}
					 
					$exec = $this->pdoObj->execute($strQuery, $insBind); 
					
					if($exec)
					{
						$opStatus='success';
						$opMessage='Stock updated successfully'; 
						
						//praga
						if($mod == 'ins')
						{
							$hid_fileimport = $postArr['hid_temp_import_file'];
							$hid_import = $postArr['hid_import_opt'];
							
							if($hid_import == 1)
							{
								
								$to_path = 'public/data/stock_entry/'.$id.'/import/';
								$this->makedirectory($to_path);
								$filename = basename($hid_fileimport);
								$toFileName=$to_path.$filename;
								if(file_exists($hid_fileimport))
								{
									$fc=copy($hid_fileimport,$toFileName);
									if($fc) 
									{
										$insFilename=$f;
										
									} 
									unlink($hid_fileimport);
								}
							}
						}
						
					}
				}	
			}	
			
			if($hid_temp_del != '')
			{
				$temp_arr = explode(',', $hid_temp_del);
				foreach($temp_arr as $del_id)
				{
					if($del_id)
					{  
						$strQuery=" update spa_billing_sub set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where stock_master_entry_id=:stock_master_entry_id  ";  						
						$bindArr=array( ":stock_master_entry_id"=>array("value"=>$del_id,"dtype"=>"int"), ":sess_user_id"=>array("value"=>$this->sess_userid,"dtype"=>"int")); 
						
						//$exec = $this->pdoObj->execute($strQuery, $bindArr); // Currently no update/ delete option
					}
				}
			}
		} 	 
			 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'new_id'=>$id);  
		
		return json_encode($sendArr);
	}
	
	public function getStockMasterEntry()
	{
		$parent_productlinelist = $this->getModuleComboList('parent_productline');
		$productlinelist = $this->getModuleComboList('productline'); 
		$productcolourlist = $this->getModuleComboList('productcolour'); 
		
		$sendArr = array('parent_productlinelist'=>$parent_productlinelist, 'productlinelist'=>$productlinelist, 'productcolourlist'=>$productcolourlist);
		
		return $sendArr;
	}
	
	 public function importStockEntryData($postArr)
	 {
	 	
		$name = $_FILES['import_file']['name'];
		$size = $_FILES['import_file']['size'];
		$tmp = $_FILES['import_file']['tmp_name'];
		$path = 'public/data/temp/importdata/'.time().'/';
		$this->makedirectory($path);
				
		/*$files = scandir($path); 
		$files = array_diff(scandir($path), array('.', '..'));
		
		$sendFilesArr=array();
		foreach($files as $f)
		{
			unlink($path.$f);
		} */
		
		if(move_uploaded_file($tmp, $path.$name))
		{
			$opMessage = 'File uploaded successfully';
			$opStatus = 'success';
		}

		
		$Filepath =  $path.$name;
		
		if($opStatus == 'success')
		{
		// Excel reader from http://code.google.com/p/php-excel-reader/
		require($this->ProjFile.'/php-excel-reader/excel_reader2.php');
		require($this->ProjFile.'/SpreadsheetReader.php');
		
		date_default_timezone_set('UTC');
		
		if(file_exists($Filepath))
		{
			try
			{
				$Spreadsheet = new SpreadsheetReader($Filepath);
				$Sheets = $Spreadsheet -> Sheets();
				//$Spreadsheet -> ChangeSheet(0);
				$headArr = array();
				
				
				foreach ($Sheets as $Index => $Name)
				{
					
					$Spreadsheet -> ChangeSheet($Index);
					if($Index === 0)
					{
						foreach ($Spreadsheet as $Key => $Row)
						{
							//echo $Key.': ';
							if ($Row)
							{
								if($Key == 1)
								{
									$headArr = $Row;
									
								}
								else
								{
									//print_r($Row);
									$data = array_combine($headArr,$Row);	
									//print_r($data);							
									$stock_details[] = $this->getStockEntryData($data);
								}
		
							}
						}
						
						$sendRs = array('stock_details'=>$stock_details, 'file_path'=>$Filepath);
						$sendArr=array('rsData'=>$sendRs,'status'=>'success', 'message'=>'File imported successfully');
						break;
					}	
				}

				 
			}
			catch (Exception $E)
			{
				$E -> getMessage();
				$sendArr=array();
			}
			//echo json_encode($sendArr);	
			return json_encode($sendArr);	
		}
		}
	 }
	 
	 public function getStockEntryData($arr)
	{
		//Date	Parent productline		Product colour	Chasis No.	Purchase cost	Status
		
		$stock_type = array('Open stock'=>1, 'G stock'=>2);
		
		$parent_productline = $arr['Parent productline'];
		$productline = $arr['Productline'];
		$productcolour = $arr['Product colour'];
		$stock_status = $arr['Status'];
		
		$date = ($arr['Date'])?date('Y-m-d',strtotime($arr['Date'])):'';
		$purchase_rate = $arr['Purchase cost'];		
		$chasis_no = $arr['Chasis No.'];
		$cmb_stock_status = $stock_type[$stock_status];
		
		$strQuery = "select parent_productline_id from srt_parent_productline_master where trim(parent_productline_name)=:parent_productline_name ";
		$bindArr=array(":parent_productline_name"=>array("value"=>$parent_productline,"dtype"=>"text")); 
		$rs_cat = $this->pdoObj->fetchSingle($strQuery, $bindArr);
		$rs_cat['parent_productline_id'] = ($rs_cat['parent_productline_id'])?$rs_cat['parent_productline_id']:0;
		
		$strQuery = "select productline_id from srt_productline_master where trim(productline_name)=:productline and parent_productline_id=:parent_productline_id";
		$bindArr=array(":productline"=>array("value"=>$productline,"dtype"=>"text"), ":parent_productline_id"=>array("value"=>$rs_cat['parent_productline_id'],"dtype"=>"int")); 
		$rs_prd = $this->pdoObj->fetchSingle($strQuery, $bindArr);  
		
		$strQuery = "select productcolour_id from srt_productcolour_master clr where FIND_IN_SET('".$rs_cat['parent_productline_id']."',clr.parent_productline_ids) and  trim(productcolour_name)=:productcolour_name ";
		$bindArr=array(":productcolour_name"=>array("value"=>$productcolour,"dtype"=>"text")); 
		$rs_colr = $this->pdoObj->fetchSingle($strQuery, $bindArr);
		
		
		$rs_prd['productline_id'] = ($rs_prd['productline_id'])?$rs_prd['productline_id']:0;	
		$rs_colr['productcolour_id'] = ($rs_colr['productcolour_id'])?$rs_colr['productcolour_id']:0;
		
		$stock_details=array('txt_stock_entry_date'=>$this->convertDate($date), 'cmb_parentproductline'=>$rs_cat['parent_productline_id'], 'cmb_productline'=>$rs_prd['productline_id'], 'cmb_product_colour'=>$rs_colr['productcolour_id'], 'txt_chasis_no'=>$chasis_no, 'txt_purchase_cost'=>$purchase_rate, 'cmb_stock_status'=>$cmb_stock_status);
		
		return $stock_details;

	}
	
	public function __destruct() 
	{
		
	} 
}

?>