<?php 
if( !defined( 'restrictCallPages' )) die( 'Restricted access' );
if(!class_exists('htmlpuri_obj')) { require_once 'htmlpurifier_lib/library/HTMLPurifier.auto.php';
class htmlpuri_obj
{    
	 public $purifier = null;  
	 
	 public function __construct() {  
		
		$config = HTMLPurifier_Config::createDefault();
		//$config->set('Core.Encoding', 'ISO-8859-1'); // replace with your encoding
		$config->set('Core.Encoding', 'UTF-8');
		$config->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
		$this->purifier = new HTMLPurifier($config);
    }  
} 
}
?>