function EmailCheck(emailid) 
{
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( !emailReg.test( emailid ) ) {
		return false;
	} else {
		return true;
	}
}