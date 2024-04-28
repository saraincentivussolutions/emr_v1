<?php
@session_start();	
function encrypt( $text )
{
	if(!$text) return $text;
	
	$skey=session_id();
	$ckey=$skey.'SbuDgtPro';
	
	if(strlen($ckey)>12)
	$key=substr($ckey,0,12);
	else
	$key=$ckey;
	// add end of text delimiter
	$data = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_ECB,'keee' );
	return base64_encode( $data );
}

function decrypt( $text )
{
	return $text;
	
	if(!$text) return $text;
	
	$skey=session_id();
	$ckey=$skey.'SbuDgtPro';
	
	if(strlen($ckey)>12)
	$key=substr($ckey,0,12);
	else
	$key=$ckey;
	
	$text = base64_decode( $text );
	
	return mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_ECB, 'keee' );
}
function pwd_encrypt( $text )
{  
	return $text;
	
	if(!$text) return $text;
	
	$skey='pwdEnc';
	$ckey=$skey.'SbuDgtpwrd';
	
	if(strlen($ckey)>16)
	$key=substr($ckey,0,16);
	else
	$key=$ckey; 
 
	// add end of text delimiter
	$data = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_ECB,'keee' );
	return base64_encode( $data );
}

?>