<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign in</title>

<meta name="apple-mobile-web-app-capable" content="yes">
    
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
	  $(document).bind("mobileinit", function () { $.mobile.ajaxEnabled = false; });
	</script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <!--Plugin JavaScript file-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>
    
    <!-- <script src="/Reservations/lib/js/age_slider_1.js"></script> -->
	
    <!--Plugin CSS file with desired skin-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css"/>
    
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName}}

<div class="col-md-8" style="margin-left:auto; margin-right:auto; float:none; margin-top:50px"><h3 style="text-align:center;">Password reset email sent.</h3></div>
<div class="col-md-5" style="margin-left:auto; margin-right:auto; float:none; margin-top:50px"><p style="text-align:center;">If the email you provided is associated with an account, your password email should arrive shortly. Please click on the link in the email to reset your password</p></div>
<div class="col-md-5" style="margin-left:auto; margin-right:auto; float:none; margin-top:50px"><a href="/"><button> Return to Login Page</button></a>
</body>
</html>
