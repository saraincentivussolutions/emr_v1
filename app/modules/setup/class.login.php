<?php
class login extends common
{
	private $username;
	private $password;
	private $device_type;
	
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function login_authentication($username, $password, $device_type)
	{
		$this->username = $this->purifyInsertString($username);
		$this->password = $this->purifyInsertString($password);
		$this->device_type = $this->purifyInsertString($device_type);
		
		$sql="select USERID, USERNAME, USER_PASSWORD, USERTYPE, employee_id  from ADMIN_LOGIN_MASTER where USERNAME = :USERNAME and USER_PASSWORD = :USER_PASSWORD";
		$bindArr=array(":USERNAME"=>array("type"=>"text", "value"=>$this->username), ":USER_PASSWORD"=>array("type"=>"text", "value"=>$this->encrypt_password($this->password)));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);
		$status = 'false';
		$message = 'Invalid Login Details';
		if($recs['USERID'])
		{
			$this->set_sess_variables($recs);
			$status = 'true';
			$message = 'Login Success';
			
			//$check_count = $this->checkLoginAccountOpened($recs); //future will do
			
			if($check_count>0)
			{
				$message = 'Account already opened';
			}
			else
			{
				$this->createLoginSessionLog($recs);
			}
		}
		
		$arr = array('status'=>$status, 'message'=>$message);
		$arrjson = json_encode($arr);
		
		return $arrjson;
		
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
	
	public function set_sess_variables($recs)
	{
		@session_start();
		$_SESSION['sess_log_username'] = $recs['USERNAME'];
		$_SESSION['sess_log_userid'] = $recs['USERID'];
		$_SESSION['sess_log_usertype'] = $recs['USERTYPE']; 
		$_SESSION['sess_log_employee_id'] = $recs['employee_id'];
		$_SESSION['sess_key'] = session_id();
		
		$_SESSION['sess_spa_setup_id'] = "1"; //will be dynamically changed later
	}
	
	public function encrypt_password($password)
	{
		return $password;
	}
}
?>