<?php
	ini_set('display_errors',0);   
	
	define(restrictCallPages,'AllowFromOnlyMain'); 
	 
	$ProjDir = dirname(__DIR__);
	$ProjFile = dirname(__FILE__);
	
	
	include $ProjFile."/class_htmlpurifier.php";
	include $ProjFile.'/class.pdo.php';
	include $ProjFile.'/encrypt_string.php';
	
	class  common
	{
		public $pdoObj=null;
		public $htmpurify = null;
		
		public function __construct()
		{  
			$this->pdoObj 	= 	new PDOClass(); 
			$this->moduleDir = dirname(__DIR__).'/modules/';
			$this->htmpurify 	= 	new htmlpuri_obj(); 
			$this->ProjFile		= dirname(__FILE__);
			
			$this->shwYearDropDownFrom='2014';
			$this->getSessionDetails();
			$this->sess_spa_setup_id='1';
			
			$this->offer_app_acc_min=5000;
			$this->offer_app_acc_max=9999;
			$this->offer_app_admin=10000;
		}
		public function getSessionDetails()
		{
			@session_start();  
			
			$this->sess_userid 					= ($_SESSION['sess_log_userid'])?intval($_SESSION['sess_log_userid']):0;
			$this->sess_username 				= ($_SESSION['sess_log_username'])?$_SESSION['sess_log_username']:''; 
			$this->sess_log_userdispname 		= ($_SESSION['sess_log_userdispname'])?$_SESSION['sess_log_userdispname']:''; 
			$this->sess_log_employee_id 		= ($_SESSION['sess_log_employee_id'])?intval($_SESSION['sess_log_employee_id']):0;
			$this->sess_log_user_access 		= ($_SESSION['sess_log_user_access'])?($_SESSION['sess_log_user_access']):array();  
			$this->sess_log_superuser 			= ($_SESSION['sess_log_superuser'])?intval($_SESSION['sess_log_superuser']):0;
			$this->sess_log_user_role 			= ($_SESSION['sess_log_user_role'])?intval($_SESSION['sess_log_user_role']):0;
			$this->sess_sales_team_access_ids 	= ($_SESSION['sess_sales_team_access_ids'])?$_SESSION['sess_sales_team_access_ids']:0;
			
			$this->sess_spa_setup_id 		= ($_SESSION['sess_spa_setup_id'])?intval($_SESSION['sess_spa_setup_id']):0;
		}
		
		public function convertDate($date)
		{
			$conv_date="";
			if($date=='' || $date=='0000-00-00')
			{
				$conv_date="";
			}
			else
			{
				$split=explode("-",$date);
				
				if(strlen($split[0])!=4)
				{  
					$conv_date=date('Y-m-d',strtotime($date));	
				}
				elseif(strlen($split[2])>4)
				{
					$conv_date=date('d-m-Y H:i',strtotime($date)); 
				}
				else
				{ 
					$conv_date=date('d-m-Y',strtotime($date));	
				}
				
				if ($conv_date=="--") $conv_date="";
				
			}
			return $conv_date;
		}
		public function purifyInsertString($data)
		{		
			//$data = $this->htmpurify->purifier->purify($data);
			return trim($data);
		} 
		
		public function purifyString($data)
		{ 	
			//$data = $this->htmpurify->purifier->purify($data);
			
			/*$data = html_entity_decode($data);
			
			$data = str_replace('"','&quot;',$data);
			$data = str_replace("'",'&apos;',$data);*/
			
			$data = $this->htmpurify->purifier->purify($data);
			
			$data = html_entity_decode($data);
	
						
			return $data;
		} 
		
		
		// Function to get the client IP address
		function get_client_ip() {
			$ipaddress = '';
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
		
		public function getModuleView($module, $view)
		{
			$templateFile = '';
			if($view)
			$templateFile = $this->moduleDir.$module.'/'.$view.'.php';
			
			return $templateFile;
			
		}
		
		public function getModuleProcess($module)
		{
			$templateFile = '';
			if($module)
			$templateFile = $this->moduleDir.$module.'/process_control.php';
			
			return $templateFile;
			
		}
		
		public function getModule($module)
		{	
			$templateFile = $this->moduleDir.$module.'/class.script.php';
			
			if(is_file($templateFile))
			{
				include_once($templateFile);
				
				$obj = new $module;	
				
				return $obj;
			}
		}
		//dialog
		public function getModuleList($module)
		{
			$obj = $this->getModule($module);
			if($obj)
			{
				$list = $obj->listview();	
				return $list;
			}
		
		}
		
		//dropdown
		public function getModuleComboList($module='', $id='', $extraparam=array())
		{
			$obj = $this->getModule($module);
			if($obj)
			{
				$list = $obj->comboview($id, $extraparam);	
				return $list;
			}
		
		}
		
		function makedirectory($up)
		{
			$splt=explode("/",$up);
			$pth = "";
			for($i=0;$i<=sizeof($splt);$i++)
			{
				$pth=$pth.$splt[$i]. "/";
				if(!is_dir($pth)){
						if(!mkdir($pth,0777)){
						 echo "<script language='javascript'>
								alert('Permission denied to create directory');
								</script>";
							return false;				
		
						}
				}
				
			}
			return true;
	
		
		}
		
		function deleteDirectory($dir) { 
		if (!file_exists($dir)) return true; 
		if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
			foreach (scandir($dir) as $item) { 
				if ($item == '.' || $item == '..') continue; 
				if (!$this->deleteDirectory($dir . "/" . $item)) { 
					chmod($dir . "/" . $item, 0777); 
					if (!$this->deleteDirectory($dir . "/" . $item)) return false; 
				}; 
			} 
			return rmdir($dir); 
		} 
		
		//================================ Sample code. 
		
		
		public function getCourseMasterList()
		{
			$sql="select COURSE_ID,COURSE_CODE,COURSE_NAME,COURSE_STATUS, case COURSE_STATUS when 1 then 'Active' else 'Inactive' end as COURSE_STATUS_DESC from ACAD_COURSE_MASTER order by COURSE_NAME ";
			$bindArr=array();
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			 
			$sendRs=array();
			
			$rsCnt=0;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["COURSE_ID"]);
				$ccode=$this->purifyString($rs["COURSE_CODE"]);
				$cname=$this->purifyString($rs["COURSE_NAME"]);
				$cstatus=$this->purifyString($rs["COURSE_STATUS"]);
				$cstatus_desc=$this->purifyString($rs["COURSE_STATUS_DESC"]);
				
				
				$sendRs[$rsCnt]=array("COURSE_ID"=>$cid,"COURSE_CODE"=>$ccode,"COURSE_NAME"=>$cname,"COURSE_STATUS"=>$cstatus,"COURSE_STATUS_DESC"=>$cstatus_desc,);
				$rsCnt++;
				
			}
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			
			
			return json_encode($sendArr);
		}
		
		public function getCourseMasterView($postArr)
		{
			$getcid=$this->purifyInsertString($postArr["id"]);
			
			$sql="select COURSE_ID,COURSE_CODE,COURSE_NAME,COURSE_STATUS, case COURSE_STATUS when 1 then 'Active' else 'Inactive' end as COURSE_STATUS_DESC from ACAD_COURSE_MASTER where COURSE_ID=:COURSE_ID";
			$bindArr=array(":COURSE_ID"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["COURSE_ID"]);
			$ccode=$this->purifyString($recs["COURSE_CODE"]);
			$cname=$this->purifyString($recs["COURSE_NAME"]);
			$cstatus=$this->purifyString($recs["COURSE_STATUS"]);
			$cstatus_desc=$this->purifyString($recs["COURSE_STATUS_DESC"]);
			
			
			$sendRs=array("COURSE_ID"=>$cid,"COURSE_CODE"=>$ccode,"COURSE_NAME"=>$cname,"COURSE_STATUS"=>$cstatus,"COURSE_STATUS_DESC"=>$cstatus_desc,); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
		} 
		public function saveCourseMasterView($postArr)
		{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			$course_code=$this->purifyInsertString($postArr["course_code"]);
			$course_name=$this->purifyInsertString($postArr["course_name"]);
			$course_status=$this->purifyInsertString($postArr["course_status"]); 
			
			$cnt_ext_sql="select count(*) as ext_cnt from ACAD_COURSE_MASTER where COURSE_ID=:COURSE_ID "; 
			$bindExtCntArr=array(":COURSE_ID"=>array("value"=>$id,"type"=>"int"));
			$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
			$ext_cnt_val=$rs_qry_exts["ext_cnt"];
			
			$ins=" ACAD_COURSE_MASTER SET COURSE_CODE=:COURSE_CODE,COURSE_NAME=:COURSE_NAME,COURSE_STATUS=:COURSE_STATUS ";
			$insBind=array(":COURSE_CODE"=>array("value"=>$course_code,"type"=>"text"), ":COURSE_NAME"=>array("value"=>$course_name,"type"=>"text"), ":COURSE_STATUS"=>array("value"=>$course_status,"type"=>"text")); 
			
			if($ext_cnt_val>0) 
			{ 
				$strQuery="UPDATE $ins, LASTMODIFIEDBY=1,LASTMODIFIEDON=now() where COURSE_ID=:COURSE_ID ";
				$insBind[":COURSE_ID"]=array("value"=>$id,"type"=>"text"); 
				 
			}
			else
			{
				$strQuery="INSERT INTO $ins, CREATEDBY=1,CREATEDON=now() "; 
			} 
			
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Record inserted successfully'; 
			} 
			
			$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
			
			return json_encode($sendArr);
		}
		
		public function _UrlEncode($string) {
			$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
			$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
			return str_replace($entities, $replacements, ($string));
		}
		
		public function getMenuModuleList()
		{
			$bindArr=array();
			$sql = "select sub.sub_module_name, parent.module_name as main_module_name, sub.sub_module_id, parent.module_id as main_module_id, sub.sub_module_actions,  if(sub_module_actions is null, module_actions, sub_module_actions) as module_actions,  if(sub_module_actions is null, parent.module_id, sub_module_id) as module_id  from srt_sub_modules sub right join srt_modules parent on sub.module_id = parent.module_id order by parent.module_order, sub.sub_module_order ";
			 $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			 return $recs;
			
		}
		
		public function getSubMenuModulePrevilegeList()
		{
			$bindArr=array(":user_role_id"=>array("value"=>$this->sess_log_user_role,"type"=>"int"));
			$sql = " select menus.*, sub_act.module_actions as menu_actions,sub_act.module_type, sub_module_call_js from (select sub.sub_module_name, parent.module_name as main_module_name, sub.sub_module_id, parent.module_id as main_module_id, sub.sub_module_actions,  if(sub_module_actions is null, parent.module_actions, sub_module_actions) as module_actions,  if(sub_module_actions is null, parent.module_id, sub.sub_module_id) as module_id, module_order, sub.sub_module_order, sub.sub_module_call_js   from srt_sub_modules sub right join srt_modules parent on sub.module_id = parent.module_id order by parent.module_order, sub.sub_module_order) menus left join srt_user_role_modules sub_act on menus.sub_module_id = sub_act.module_id and sub_act.module_type = 2 and sub_act.user_role_id=:user_role_id order by module_order, sub_module_order";
			
			 $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			 return $recs;
			
		}
		
		public function getCategoryID($category_name)
		{
			$sql = "select count(*) as cnt, b.category_id from bud_category_master b where b.category_name = '$category_name' and b.active_status=1";
			$bindArr=array();
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['cnt']>0)
			{
				return $recs['category_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_category_master SET category_name=:category_name,active_status=:active_status";
				$insBind=array(":category_name"=>array("value"=>$category_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select count(*) as cnt, b.category_id from bud_category_master b where b.category_name = '$category_name' and b.active_status=1";
					$bindArr=array();
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['cnt']>0)
					{
						return $recs['category_id'];
					}
				}  
			}
		}
		
		public function getSubCategoryID($category_name, $category_id)
		{
			$sql = "select count(*) as cnt, b.subcategory_id from bud_subcategory_master b where b.subcategory_name = '$category_name' and category_id=:category_id and b.active_status=1";
			$bindArr=array(":category_id"=>array("value"=>$category_id,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['cnt']>0)
			{
				return $recs['subcategory_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_subcategory_master SET subcategory_name=:subcategory_name,active_status=:active_status,category_id=:category_id";
				$insBind=array(":subcategory_name"=>array("value"=>$category_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"),":category_id"=>array("value"=>$category_id,"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select count(*) as cnt, b.subcategory_id from bud_subcategory_master b where b.subcategory_name = '$category_name' and category_id=:category_id and b.active_status=1";
					$bindArr=array(":category_id"=>array("value"=>$category_id,"type"=>"int"));
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['cnt']>0)
					{
						return $recs['subcategory_id'];
					}
				}  
			}
		}
		
		public function updateBookingDetails($id, $uptype='')
		{
			$sql = "select * from srt_booking_transaction where booking_transaction_id=:id limit 1";
			$bindArr=array(":id"=>array("value"=>$id,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($uptype=='booking_price')
			{
				$tot = $recs['ex_showroom_price'] + $recs['insurance_method'] + $recs['rto_fee'] + $recs['taxi_charges'] + $recs['accessories'] + $recs['amc'] - $recs['ex_price'];
				
				$bk_up_sql="update srt_booking_transaction set onroad_price=:onroad_price where booking_transaction_id=:id ";
				$bk_up_arr=array(":onroad_price"=>array("value"=>$tot,"type"=>"text"), ':id'=>array("value"=>$id,"type"=>"int")); 
				$bk_up_exec = $this->pdoObj->execute($bk_up_sql, $bk_up_arr); 
			}
			
			
		}
		public function updateBookingStatusToBooked($id)
		{
			$offer_app_acc_min = ($this->offer_app_acc_min)?$this->offer_app_acc_min:0;
			
			$sql="select bk.booking_transaction_id, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received from srt_booking_transaction as bk left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) where  bk.booking_transaction_id=:id  and bk.order_status=1 and ( ((coalesce(access_offer_srt,0)+coalesce(insurance_offer_srt,0)+coalesce(add_discount_srt,0)+coalesce(edr_srt,0)+coalesce(other_contribution_srt,0)) >=$offer_app_acc_min and (off_acc_approved_status=1 or off_admin_approved_status=1)) or (coalesce(access_offer_srt,0)+coalesce(insurance_offer_srt,0)+coalesce(add_discount_srt,0)+coalesce(edr_srt,0)+coalesce(other_contribution_srt,0)) <$offer_app_acc_min )   group by bk.booking_transaction_id having   coalesce(sum(rcp.receipt_amount),0)>0  limit 1";
			
			//$sql = "select bk.booking_transaction_id, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received from srt_booking_transaction as bk where bk.booking_transaction_id=:id and bk.order_status=1 and ((coalesce(access_offer_srt,0)+coalesce(insurance_offer_srt,0)+coalesce(add_discount_srt,0)+coalesce(edr_srt,0)+coalesce(other_contribution_srt,0)) >=$offer_app_acc_min and (off_acc_approved_status=1 or off_admin_approved_status=1)) left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) group by bk.booking_transaction_id  limit 1";
			$bindArr=array(":id"=>array("value"=>$id,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs["booking_transaction_id"]>0)
			{ 
				$bk_up_sql="update srt_booking_transaction set order_status=3 where booking_transaction_id=:id ";
				$bk_up_arr=array(':id'=>array("value"=>$id,"type"=>"int")); 
				$bk_up_exec = $this->pdoObj->execute($bk_up_sql, $bk_up_arr); 
			}
			
			
		}
		
		public function getChasisNoArrdetails($get_type='')
		{
			$stkqry="select stk.stock_master_entry_id, stk.parent_productline_id, stk.productline_id, stk.productcolour_id, stk.stock_type from srt_stock_master_entry as stk where stk.stock_chasis_used=0 order by stk.stock_master_entry_date ";
			$stkarr=array();
			$stkresop = $this->pdoObj->fetchMultiple($stkqry, $stkarr); 
			
			$stkArr=array();
			foreach($stkresop as $stkval)
			{
				if($get_type=='from_retail')
				{
					$stkArr[$stkval["productline_id"]][$stkval["productcolour_id"]][]=$stkval["stock_master_entry_id"];	 // will change condition later
				}
				elseif($get_type=='veh_notavail')
				{ 
					$stkArr[$stkval["productline_id"]][$stkval["productcolour_id"]][]=$stkval["stock_master_entry_id"];	
				}
			}
			 
			
			$bkCmpArr=array();
			$bknostkids=array();
			$bkDetChcnt=array();
			if($get_type=='from_retail')
			{
				$bkqry="select bk.booking_transaction_id, bk.parent_product_line, bk.product_line, bk.product_color_primary, ret.stock_status from srt_booking_transaction as bk left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id    where  bk.order_status in (3) and coalesce(ret.stock_chasis_id,0)=0 order by bk.booking_transaction_id ";
				$bkarr=array();
				$bkresop = $this->pdoObj->fetchMultiple($bkqry, $bkarr); 
				
				
				foreach($bkresop as $bkkval)
				{
					$lpcnAvil=""; 
							
						if($stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]]) // will change condition later
						{
							
							$ckvalno=$bkDetChcnt[$bkkval["product_line"]][$bkkval["product_color_primary"]]["count"];
							if(!$ckvalno) $ckvalno=0;
							
							$bkDetChcnt[$bkkval["product_line"]][$bkkval["product_color_primary"]]["count"]++;
							
							if($stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]][$ckvalno])
							{
								$lpcnAvil="yes";
								$bkCmpArr[$bkkval["booking_transaction_id"]]=$stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]][$ckvalno];
							}	
							
						}
						if($lpcnAvil!="yes")
						{
							$bknostkids[]=$bkkval["booking_transaction_id"];
							 
						}
					
				}
			
				 
			}
			elseif($get_type=='veh_notavail')
			{
				$bkqry="select bk.booking_transaction_id, bk.parent_product_line, bk.product_line, bk.product_color_primary, ret.stock_status from srt_booking_transaction as bk left join srt_retail as ret on bk.booking_transaction_id=ret.booking_transaction_id where  bk.order_status in (1,3) and coalesce(ret.stock_chasis_id,0)=0 order by bk.booking_transaction_id ";
				$bkarr=array();
				$bkresop = $this->pdoObj->fetchMultiple($bkqry, $bkarr); 
				
				
				foreach($bkresop as $bkkval)
				{
					$lpcnAvil=""; 
						
						
							
						if($stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]])
						{
							 
							$ckvalno=$bkDetChcnt[$bkkval["product_line"]][$bkkval["product_color_primary"]]["count"];
							if(!$ckvalno) $ckvalno=0;
							
							$bkDetChcnt[$bkkval["product_line"]][$bkkval["product_color_primary"]]["count"]++;
							
							if($stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]][$ckvalno])
							{
								$lpcnAvil="yes";
								//$bkCmpArr[$bkkval["booking_transaction_id"]]=$stkArr[$bkkval["product_line"]][$bkkval["product_color_primary"]];
							}	
							
						}
						if($lpcnAvil!="yes")
						{
							$bknostkids[]=$bkkval["booking_transaction_id"];
							 
						}
					
				}
			
				 
			}
			 
			$sendArr=array('book_compare_arr'=>$bkCmpArr, 'bknostkids'=>$bknostkids );
			 
			 
			return $sendArr;
			
		}
		
		public function getUserExtraModulesPrevilage()
		{
			$sql=" select extra_json_elements from srt_user_role_master where user_role_id=:user_role_id "; 
			$bindArr=array( ":user_role_id"=>array("value"=>$this->sess_log_user_role,"dtype"=>"int")); 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			$recs = json_decode($recs["extra_json_elements"]);
			return $recs;
		}

	}	
?>