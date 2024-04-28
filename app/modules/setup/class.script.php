<?php	

class login extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function chk_spasetup_name_exists($postArr)
	{
		$setupname = $this->purifyInsertString($postArr['setupname']);  
		
		$sql= "select spa_setup_id from spa_setup_details where spa_setup_name = :spa_setup_name";
		$bindArr=array(":spa_setup_name"=>array("type"=>"text", "value"=>$setupname));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		$status = 'failure';
		$message = 'Unexpected error';
		if($recs['spa_setup_id'])
		{  
			$status = 'failure';
			$message = 'Name already exists'; 
		}
		else
		{   
			$status = 'success';
			$message = 'Setup name available'; 
		}
		
		$arr = array('status'=>$status, 'message'=>$message);
		$arrjson = json_encode($arr);
		
		return $arrjson;
		
	}
	public function login_authentication($postArr)
	{
		$username = $postArr['username']; 
		$password = $postArr['password']; 
		$device_type = $postArr['device_type']; 
		
		$this->username = $this->purifyInsertString($username);
		$this->password = $this->purifyInsertString($password);
		$this->device_type = $this->purifyInsertString($device_type);
		
		$sql= "select employee_id, user_name, user_password, user_access, active_status, employee_name, super_user  from spa_employee_master where user_name = :user_name and user_password = :user_password";
		$bindArr=array(":user_name"=>array("type"=>"text", "value"=>$this->username), ":user_password"=>array("type"=>"text", "value"=>$this->encrypt_password($this->password)));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		$status = 'failure';
		$message = 'Invalid Login Details';
		if($recs['employee_id'])
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
		$_SESSION['sess_log_userdispname'] = $recs['employee_name'];
		$_SESSION['sess_log_userid'] = $recs['employee_id'];
		$_SESSION['sess_log_superuser'] = $recs['super_user'];
		$_SESSION['sess_log_user_access'] = json_decode($recs['user_access'],true);
		$_SESSION['sess_log_employee_id'] = $recs['employee_id'];
		$_SESSION['sess_spabills_key'] = $_SESSION['sess_key'] = session_id(); 
		 
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