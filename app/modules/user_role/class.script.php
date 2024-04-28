<?php	

class user_role extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $draw = $postArr['draw'];
		  $start = ($postArr['start'])?$postArr['start']:0;
		  $limit = ($postArr['length'])?$postArr['length']:0;
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  $bindArr=array();
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where = 'where user_role_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_user_role_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select user_role_id,user_role_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_user_role_master $where order by user_role_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["user_role_id"]);
				$cname=$this->purifyString($rs["user_role_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				
				//$sendRs[$rsCnt]=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$cstatus_desc, '<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateUserRoleMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> <span class="delete"  onclick="viewDeleteUserRoleMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
			$getcid= (int) $this->purifyInsertString($postArr["id"]);
			
			$sql="select user_role_id,user_role_name,active_status, extra_json_elements,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_user_role_master where user_role_id=:user_role_id";
			$bindArr=array(":user_role_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["user_role_id"]);
		
			$cname=$this->purifyString($recs["user_role_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$extra_json_elements = json_decode($recs["extra_json_elements"]);
			
			
			
			$sql = "select * from srt_user_role_modules where user_role_id=:user_role_id";
			$user_mod_actions = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			$sendRs=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus, 'user_mod_actions'=>$user_mod_actions, 'extra_json_elements'=>$extra_json_elements); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return ($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$user_role_name=$this->purifyInsertString($postArr["user_role_name"]);
		$status=$this->purifyInsertString($postArr["user_role_status"]);
		$act_extras = array();
		$extra_json_elements = '';
		foreach($postArr['extra_modules'] as $mod)
		{
			list($name, $act) = explode(':', $mod);	
			$act_extras[$name] = $act;
		}
		
		$extra_json_elements = json_encode($act_extras);
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_user_role_master where user_role_id=:user_role_id "; 
		$bindExtCntArr=array(":user_role_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_user_role_master SET user_role_name=:user_role_name,active_status=:active_status, extra_json_elements=:extra_json_elements";
		$insBind=array(":user_role_name"=>array("value"=>$user_role_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), "extra_json_elements"=>array("value"=>$extra_json_elements,"type"=>"text")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_user_role_master where trim(user_role_name)=:user_role_name ";
		$bindExtChkArr=array(":user_role_name"=>array("value"=>$user_role_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_role_id=:user_role_id ";
			$insBind[":user_role_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and user_role_id<>:user_role_id ";
			$bindExtChkArr[":user_role_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="User Role updated successfully!";
			
			$insUpType = "update";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="User Role inserted successfully!";
			
			$insUpType = "insert";
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
					$sql_max="select max(user_role_id) as max_id from srt_user_role_master ";
					$rs_max = $this->pdoObj->fetchSingle($sql_max, '');  
					$max_id	=	$rs_max['max_id'];
					
					$id=$max_id;  
				}
				$module_id = $postArr['module_id'];
				$module_actions = $postArr['module_actions'];
				$module_types = $postArr['module_type'];
				foreach($module_id as $key=>$modid)
				{
					$modid = $this->purifyString($modid);
					$mod_actions = $module_actions[$key];
					$module_type = $module_types[$key];
					$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_user_role_modules where (user_role_id)=:user_role_id and module_id=:module_id and module_type=:module_type  ";
					$bindExtChkArr=array(":module_id"=>array("value"=>$modid,"dtype"=>"int"),":user_role_id"=>array("value"=>$id,"dtype"=>"int"),":module_type"=>array("value"=>$module_type,"dtype"=>"int")); 
					
					$ins=" srt_user_role_modules SET user_role_id=:user_role_id,module_id=:module_id,module_actions=:module_actions,module_type=:module_type ";
					$insBind=array(":user_role_id"=>array("value"=>$id,"type"=>"int"), ":module_id"=>array("value"=>$modid,"type"=>"int"), ":module_actions"=>array("value"=>$mod_actions,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int") ,":module_type"=>array("value"=>$module_type,"dtype"=>"int"));
					
					$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
					$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt']; 
					if($rec_exist_cnt_val>0) 
					{ 
						$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where (user_role_id)=:user_role_id and module_id=:module_id";
						//$insBind[":payroll_id"]=array("value"=>$id,"type"=>"text"); 
						
					}
					else
					{
						$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
					}	
					//echo $strQuery.json_encode($insBind).'<br>';
					$exec = $this->pdoObj->execute($strQuery, $insBind);
					
					if($exec)
					{
							$menu_actions = array();
							$side_menu_actions = array();
							$modules = $this->getSubMenuModulePrevilegeList();
							
							foreach($modules as $action)
							{
								$module_id = $action['module_id'];
								$mod_action = $action['menu_actions'];
								$module_type = $action['module_type'];
								$sub_module_name = $action['sub_module_name'];
								$main_module_name = $action['main_module_name'];
								$sub_module_call_js = $action['sub_module_call_js'];
								
								if($module_type==2)
								$menu_actions[$sub_module_name] = $mod_action;
								
								$side_menu_actions[$main_module_name][$sub_module_name] = array('mod_action'=>$mod_action, 'call_js'=>$sub_module_call_js);
								
							}
							
							$_SESSION['sess_log_user_previlage_submenu_record'] = $side_menu_actions;
					}
				}
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function getUserPrevillage($postArr)
	{
		$menu_actions = array();
		$side_menu_actions = array();
		$modules = $this->getSubMenuModulePrevilegeList();
		
		foreach($modules as $action)
		{
			$module_id = $action['module_id'];
			$mod_action = $action['menu_actions'];
			$module_type = $action['module_type'];
			$sub_module_name = $action['sub_module_name'];
			$main_module_name = $action['main_module_name'];
			$sub_module_call_js = $action['sub_module_call_js'];
			
			if($module_type==2)
			$menu_actions[$sub_module_name] = $mod_action;
			
			$side_menu_actions[$main_module_name][$sub_module_name] = array('mod_action'=>$mod_action, 'call_js'=>$sub_module_call_js);
			
		}
		
		$_SESSION['sess_log_user_previlage_submenu_record'] = $side_menu_actions;
		
		return json_encode(array('menu_actions'=>$menu_actions));
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from srt_user_role_master where user_role_id=:user_role_id "; 
			$bindArr=array( ":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$strQuery=" delete from srt_user_role_modules where user_role_id=:user_role_id ";
			$exec = $this->pdoObj->execute($strQuery, $bindArr); 
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='User Role details deleted successfully'; 
			} 
			
			$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
			
			return json_encode($sendArr);
			
	}		
	
	
	public function comboview($id=0)
	{
		  $bindArr=array();
		  $whereor = '';
		  if($id>0)
		  {
		  	$bindArr=array( ":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or user_role_id=:user_role_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select user_role_id,user_role_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_user_role_master where active_status = 1 $whereor order by user_role_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		/*$cnt_ext_sql="(select count(*) as ext_cnt, 'Category referring Sub Category' as msg from bud_subuser_role_master where user_role_id=:id) union all (select count(*) as ext_cnt, 'Category linked to Expenses' as msg from bud_expense_details where user_role_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); */
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete?';
		/*foreach($rs_qry_exts as $rsop)
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
			$_msg = "Category cannot be deleted";
		}*/
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	
	
	public function __destruct() 
	{
		
	} 
}

?>