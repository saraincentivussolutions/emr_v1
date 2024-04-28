<?php	

class login extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function login_authentication($postArr)
	{
		$username = $postArr['username']; 
		$password = $postArr['password']; 
		$device_type = $postArr['device_type']; 
		
		$this->username = $this->purifyInsertString($username);
		$this->password = $this->purifyInsertString($password);
		$this->device_type = $this->purifyInsertString($device_type);
		
		$sql="select login_user_id, user_name, user_password, user_access, active_status, login_user_name, super_user, user_role_id, sales_team_access_ids from srt_login_master where user_name = :user_name and user_password = :user_password";
		$bindArr=array(":user_name"=>array("type"=>"text", "value"=>$this->username), ":user_password"=>array("type"=>"text", "value"=>$this->encrypt_password($this->password)));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		//echo $sql.json_encode($bindArr);
		$status = 'failure';
		$message = 'Invalid Login Details';
		if($recs['login_user_id'])
		{
			if($recs['active_status']!="1")
			{ 
				$status = 'failure';
				$message = 'User Inactivated';
			}
			else
			{
				$this->set_sess_variables($recs);
				$status = 'success';
				$message = 'Login Success';
			}
			
			//$check_count = $this->checkLoginAccountOpened($recs); //future will do
			
			/*if($check_count>0)
			{
				$message = 'Account already opened';
			}
			else
			{
				$this->createLoginSessionLog($recs);
			}*/
		}
		
		$arr = array('status'=>$status, 'message'=>$message);
		$arrjson = json_encode($arr);
		
		return $arrjson;
		
	}
	public function set_sess_variables($recs)
	{
		@session_start();
		$_SESSION['sess_log_username'] = $recs['user_name'];
		$_SESSION['sess_log_userdispname'] = $recs['login_user_name'];
		$_SESSION['sess_log_userid'] = $recs['login_user_id'];
		$_SESSION['sess_log_superuser'] = $recs['super_user'];
		$_SESSION['sess_log_user_access'] = json_decode($recs['user_access'],true);
		$_SESSION['sess_log_login_user_id'] = $recs['login_user_id'];
		$_SESSION['sess_srtretail_key'] = $_SESSION['sess_key'] = session_id(); 
		$_SESSION['sess_log_user_role'] = $recs['user_role_id'];
		$_SESSION['sess_sales_team_access_ids'] = ($recs['sales_team_access_ids'])?$recs['sales_team_access_ids']:'0';
		 
	}
	
	
	public function createLoginSessionLog($recs)
	{
		$IP_ADDRESS = $this->get_client_ip();
		$SESSION_KEY = session_id();
		$insert = "insert into ADMIN_LOGIN_SESSION_LOG set ";
		$insert.= "LOGIN_USER = :USERID, DEVICE_TYPE = :DEVICE_TYPE, IP_ADDRESS = :IP_ADDRESS, SESSION_KEY = :SESSION_KEY, CREATEDON = now()";
		$bindArr=array(":USERID"=>array("type"=>"int", "value"=>$recs['USERID']), ":DEVICE_TYPE"=>array("type"=>"text", "value"=>($this->device_type)),
					  ":IP_ADDRESS"=>array("type"=>"text", "value"=>$IP_ADDRESS),":SESSION_KEY"=>array("type"=>"text", "value"=>$SESSION_KEY)
		);
		
		$this->pdoObj->execute($insert, $bindArr);		
	}
	
	public function checkLoginAccountOpened($recs)
	{
		
		$sql="select count(*) as cnt from ADMIN_LOGIN_SESSION_LOG where LOGIN_USER = :USERID and DEVICE_TYPE = :DEVICE_TYPE";
		$bindArr=array(":USERID"=>array("type"=>"int", "value"=>$recs['USERID']), ":DEVICE_TYPE"=>array("type"=>"text", "value"=>($this->device_type)));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		return $recs['cnt'];
	}
	
	public function forgot_password($username, $sendby)
	{
		if($sendby) // 1- mail, 2 - sms
		{
			if($sendby == 1)
			{
				
			}
		}
	}
	
	
	public function encrypt_password($password)
	{
		$password = pwd_encrypt($password);
		return $password;
	}
	
	public function __destruct() 
	{
		
	} 
}

?>