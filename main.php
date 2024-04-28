<?php 
session_start();

$sessionVal = session_id(); 
if($_SESSION['sess_srtretail_key']=="" or ($_SESSION['sess_srtretail_key']!=$sessionVal) )
{  
?>
<script>location.href="login.php";</script>
<?php
}

?> 
<?PHP include"header.php";?>
<div id="leftMenuDiv"   ></div>
<div class="bodyDiv clsSetHtFOrLFtMnu"   > 	
	<div id="centerBodyDiv" align="left"></div>
	<div id="rightMenuDiv" align="left"></div> 
</div>
<div id="dialogViewer"></div>
<?PHP include"footer.php";?> 