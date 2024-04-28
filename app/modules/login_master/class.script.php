<?php	

class login_master extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $bindArr=array(); 
		  $sess_log_superuser = $this->sess_log_superuser; 
		 
		  $superUserfilt = " and lg.super_user<>1 ";
		  if($sess_log_superuser) { $superUserfilt = "";  }
		  
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
				$where = 'and (lg.login_user_name like :search_str or te.sales_team_name like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  $tot_sql="select count(*) as cnt from srt_login_master  as lg where 1 $where $superUserfilt and lg.is_deleted<>1";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select lg.login_user_id, lg.user_name, lg.active_status, lg.login_user_name, case lg.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, lg.super_user  from srt_login_master  as lg  where 1 $where $superUserfilt and lg.is_deleted<>1 order by lg.login_user_name "; 
			
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["login_user_id"]);  
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$user_display_name=$this->purifyString($rs["login_user_name"]); 
				$super_user=$this->purifyString($rs["super_user"]);
				$user_name=$this->purifyString($rs["user_name"]); 
				
				
				$delbtn = '<span class="delete"  onclick="viewDeleteLoginMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>';
				if($super_user=="1") $delbtn = "";
				
				$actbtn = '<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateLoginMasterList('.$cid.');"><i class="fa fa-edit"></i> Edit </span> '.$delbtn;
				//$chngpwd=' <span class="edit js-open-modal" data-modal-id="popup1" onclick="changeLoginMasterPassword('.$cid.');"><i class="fa fa-key"></i> Change Password </span> ';
				
				
				//$sendRs[$rsCnt]=array("user_id"=>$cid,"user_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$user_display_name, $user_name, $actbtn); 
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
		
		$sql="select login_user_id, login_user_name, user_name, user_password, user_access, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, sales_team_access_ids, user_role_id from srt_login_master  where login_user_id=:login_user_id";
		$bindArr=array(":login_user_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["login_user_id"]);
	
		$cname=$this->purifyString($recs["user_name"]);
		$cstatus=$this->purifyString($recs["active_status"]);
		$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
		$user_display_name=$this->purifyString($recs["login_user_name"]); 
		$user_password=$this->purifyString($recs["user_password"]); 
		$user_role_id=$recs["user_role_id"]?$recs["user_role_id"]:0;
		$sales_team_access_ids=$this->purifyString($recs["sales_team_access_ids"]);
		
		$user_access = array();
		if($dbuser_access) $user_access = json_decode($dbuser_access, TRUE);
		
		$sales_teamlist = $this->getModuleComboList('sales_team');  
		$user_role_list = $this->getModuleComboList('user_role', $user_role_id);  
		
		
		if($cid>0) $user_password = 'sample_pwd';
		
		$sendRs=array("user_id"=>$cid, "user_name"=>$cname,  "status"=>$cstatus, "status_desc"=>$cstatus_desc, "user_display_name"=>$user_display_name, "sales_teamlist"=>$sales_teamlist,"user_role_list"=>$user_role_list,"sales_team_access_ids"=>$sales_team_access_ids, "user_role_id"=>$user_role_id); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	public function saveprocess($postArr)
	{ 
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$status=$this->purifyInsertString($postArr["user_status"]);
		$user_display_name=$this->purifyInsertString($postArr["user_display_name"]);
		$user_name=$this->purifyInsertString($postArr["user_name"]);
		$chk_sales_team = (is_array($postArr['chk_sales_team'])?implode(',',$postArr['chk_sales_team']):'');
		$sales_team_access_ids = $this->purifyInsertString($chk_sales_team);
		$user_role_id=$this->purifyInsertString($postArr["user_role_id"]); 		
		$user_password=$this->purifyInsertString($postArr["user_password"]); 
		 
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_login_master where login_user_id=:login_user_id "; 
		$bindExtCntArr=array(":login_user_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$user_password = $this->encrypt_password($user_password);
		
		$ins=" srt_login_master SET active_status=:active_status, user_name=:user_name, login_user_name=:login_user_name,  user_role_id=:user_role_id,  sales_team_access_ids=:sales_team_access_ids";
		$insBind=array(":user_name"=>array("value"=>$user_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ":login_user_name"=>array("value"=>$user_display_name,"type"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ':user_role_id'=>array("value"=>$user_role_id,"type"=>"int"), ':sales_team_access_ids'=>array("value"=>$sales_team_access_ids,"type"=>"text")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_login_master where trim(user_name)=:user_name and is_deleted<>1  ";
		$bindExtChkArr=array(":user_name"=>array("value"=>$user_name,"dtype"=>"text"));  
		 
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where login_user_id=:login_user_id ";
			$insBind[":login_user_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and login_user_id<>:login_user_id ";
			$bindExtChkArr[":login_user_id"]=array("value"=>$id,"dtype"=>"int");   
			
			$opmsg="Login details updated successfully!"; 
			 
		}
		else
		{
			$strQuery="INSERT INTO $ins, user_password=:user_password,  createdon=now(),createdby=:sess_user_id  "; 	
			$insBind[":user_password"]=array("value"=>$user_password,"type"=>"text"); 		
			$opmsg="Login details insterd successfully!"; 
		}
		
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure'; 
		$opExists=''; 
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='User Id already exists'; 
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
		
		//$strQuery=" delete from srt_login_master where login_user_id=:login_user_id "; 
		$strQuery=" update srt_login_master set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where login_user_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Login details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);
			
	}		
	
	public function encrypt_password($password)
	{
		$password = pwd_encrypt($password);
		return $password;
	} 
	public function changeLoginLoginMastersPassword($postArr)
	{
		$old_password = $this->purifyInsertString($postArr["ucpft_old_password"]);
		$new_password = $this->purifyInsertString($postArr["ucpft_new_password"]); 
		
		$password = $this->encrypt_password($old_password);
		$newpassword = $this->encrypt_password($new_password);
		
		$id=$this->purifyInsertString($postArr["ucpft_hid_id"]);
			
		$bindArr=array(); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_login_master where user_password=:user_password and login_user_id=:login_user_id"; 
		$bindExtCntArr=array(":user_password"=>array("value"=>$password,"type"=>"text"), ':login_user_id'=>array("value"=>$id,"type"=>"text"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];		
		
		if($ext_cnt_val>0)
		{
			$strQuery="UPDATE srt_login_master SET user_password=:user_password, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where login_user_id=:login_user_id ";
			$bindArr=array(":user_password"=>array("value"=>$newpassword,"type"=>"text"), ':login_user_id'=>array("value"=>$id,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Password changed successfully'; 
			} 			
		}
		else
		{
			$opStatus='failure';
			$opMessage='Invalid password'; 
		}
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		return json_encode($sendArr);
		
	}
	public function changeLoginMastersPasswordByAdmin($postArr)
	{
		 
		$new_password = $this->purifyInsertString($postArr["new_password"]);
		$re_password = $this->purifyInsertString($postArr["re_password"]);
		
		$password = $this->encrypt_password($old_password);
		$newpassword = $this->encrypt_password($new_password);
		
		$id=$this->purifyInsertString($postArr["hid_id"]);
			
		$bindArr=array(); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_login_master where login_user_id=:login_user_id"; 
		$bindExtCntArr=array(':login_user_id'=>array("value"=>$id,"type"=>"text"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		 
		if($ext_cnt_val>0)
		{
			$strQuery="UPDATE srt_login_master SET user_password=:user_password, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where login_user_id=:login_user_id ";
			$bindArr=array(":user_password"=>array("value"=>$newpassword,"type"=>"text"), ':login_user_id'=>array("value"=>$id,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Password changed successfully'; 
			} 
			
		}
		else
		{
			$opStatus='failure';
			$opMessage='Invalid password'; 
		}
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		return json_encode($sendArr);
		
	}
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="
		(select count(*) as ext_cnt, 'LoginMaster referring LoginMaster master' as msg from srt_login_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'LoginMaster linked to Category master' as msg from bud_category_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'LoginMaster linked to Subcategory master' as msg from bud_subcategory_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'LoginMaster linked to Fate details' as msg from bud_fare_details_master where createdby=:id)
		union all (select count(*) as ext_cnt, 'LoginMaster linked to Budget allocation' as msg from bud_budget_allocations where createdby=:id)
		union all (select count(*) as ext_cnt, 'LoginMaster linked to Expenses' as msg from bud_expenses where createdby=:id)
		 "; 
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
			$_msg = "Advisor cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	public function comboview($id=0)
	{
		 $bindArr=array(); 
		  
		  $whereor = ' and active_status = 1 ';
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or login_user_id=:id) ";
		  }
		  if($id == 'all')
		  {
		  	// $whereor = " or active_status != 1";
			$whereor = " ";
		  }
		  
		  $sql="select login_user_id, login_user_name, active_status, sales_team_id, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_login_master where is_deleted<>1 $whereor and super_user<>1 order by login_user_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	public function getLoginCPSingeView($postArr)
	{
		$getcid=$this->sess_userid; 
		
		$sql="select login_user_id, login_user_name from srt_login_master  where login_user_id=:login_user_id";
		$bindArr=array(":login_user_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["login_user_id"]); 
		$login_user_name=$this->purifyString($recs["login_user_name"]);
		
		$sts='failure';
		$sendRs=array();
		$message="ERROR! Please logout and login again then try again";
		if($cid>0)
		{
			$sendRs=array("user_id"=>$cid, "user_display_name"=>$login_user_name); 
			$sts='success';
			$message="";
		} 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>$sts,'message'=>$message);  
		
		return json_encode($sendArr);
	}
	public function __destruct() 
	{
		
	} 
}

?>