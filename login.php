<script>
//location.href="http://srt-tata.apttrendsetters.in/srtretail/";
</script>
<?php
session_start();
session_destroy();  
?>
<?php
include("login_header.php");
?>
    <div class="wcommon">
      	<div class="login-box">
			<div class="head"><img src="img/logo.png?v2" width="180"></div>			
			<form method="post">
				<div class="col-md-12">
					<input type="text" name="username" class="form-control" placeholder="Username" id="username" >
				</div>
				<div class="col-md-12">
					<input type="password" name="password" class="form-control" placeholder="Password" id="password" >
				</div>
				<div class="col-md-12">
					<button class="btn-login" type="button" onClick="validateLogin()" >Login</button>
					<a class="forgot">&nbsp;</a>
				</div>
			</form>
		</div>		
    </div>
<?php
include("login_footer.php");
?>
<script src="js/jquery.backstretch.min.js"></script>    
<script>
	$(document).ready(function(){
		var hei 		= 	($(window).height()-400);
		var cal			=	(hei/2)
		var login_box	=	$('.login-box').css('margin-top',cal);
	})
	
	$(function()
	{ 
		$("#username").focus();
		$('#username').keypress(function(e) {
			if(e.which == 13) {
			   validateLogin();
			} 
		});
	
		$('#password').keypress(function(e) {
			if(e.which == 13) 
			{
			   validateLogin();
			} 
		}); 
		 
	});
</script>
<script>
	$.backstretch("img/bg.jpg?v2", {speed: 500});
</script>
