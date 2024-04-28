<?php
	
	include 'app/common/class.common.php'; 
	$common = new common();
	
	$action=$_POST['action']?$_POST['action']:'';
	if($action!='download') exit('illegal access!');
	
	 $fullPath = $_POST['fpath'];
	 $fullPath=$common->_UrlEncode($fullPath);  
	 $fullPath = explode('?', $fullPath);
	 
	 $fpath = $fullPath[0];
	 
	 
     if( file_exists($fpath) ){ 
	 	$fsize = filesize($fpath); 
			$path_parts = pathinfo($fpath); 
			$ext = strtolower($path_parts["extension"]); 
			
			// Determine Content Type 
			switch ($ext) { 
			  case "pdf": $ctype="application/pdf"; break; 
			  case "exe": $ctype="application/octet-stream"; break; 
			  case "zip": $ctype="application/zip"; break; 
			  case "doc": $ctype="application/msword"; break; 
			  case "xls": $ctype="application/vnd.ms-excel"; break; 
			  case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
			  case "gif": $ctype="image/gif"; break; 
			  case "png": $ctype="image/png"; break; 
			  case "jpeg": 
			  case "jpg": $ctype="image/jpg"; break; 
			  default: $ctype="application/force-download"; 
			} 
		
			header("Pragma: public"); // required 
			header("Expires: 0"); 
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Cache-Control: private",false); // required for certain browsers 
			header("Content-Type: $ctype"); 
			header("Content-Disposition: attachment; filename=\"".basename($fpath)."\";" ); 
			header("Content-Transfer-Encoding: binary"); 
			header("Content-Length: ".$fsize); 
				readfile($fpath);
				unlink($fpath);
		
		  } else 
			die('File Not Found'); 
	
	
?>