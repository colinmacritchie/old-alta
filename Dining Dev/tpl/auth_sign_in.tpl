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
    
    <script src="/Reservations/lib/js/age_slider_1.js"></script>
	
    <!--Plugin CSS file with desired skin-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css"/>
    
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName}}

<form name="new_user" action="{$serverself}" method="POST">
<div class="row" style="margin-top:20px; padding-bottom:500px;">
	{if $Login_Status == 'login_failed'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;"><span style="color:red; text-align:center; font-weight:600;">Login Unsuccessful.</span><br><span style="color:red; text-align:center;">Please verify your username and password. If you need to reset your password, click the 'Forgot your password?' link below</span></div>
    {/if}
	<div class="col-md-6" style="margin-left:auto; margin-right:auto; float:none;">
    	<div class="row">
        	<div class="col-md-12"><h3>Sign In to Reservations</h3></div>
            <div class="col-md-12"><input type="text" name="username" placeholder="Username"></div>
            <div class="col-md-12"><input type="password" name="password" placeholder="Password"></div>
            <div class="col-md-12"><button type="submit" name="Submit">Sign In</button></div>
            <div class="col-md-12" style="margin-top:30px"><button style="border:unset; text-shadow:unset; border-radius:unset; box-shadow:unset; background-color:unset; color:#4343ec;" onclick="javascript: return false;" data-toggle="modal" data-target="#resetmodal">Forgot your password?</button></div>
        </div>
    </div>
</div>
</form>


<!-- BEGIN Password Reset Modal -->
<div id="resetmodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    	<!-- Modal content-->
    	<div class="modal-content">
      		<div class="modal-header">
            	<div class="row" style="display:contents;">
            		<div class="col-md-10" style="float:left;"><h4>Reset Password</h4></div>
            		<div class="col-md-2" style="float:right;"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
                </div>
            </div>
            <div class="modal-body">
            	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
            		<form action="" method="post">
    					<input type="text" class="text" name="email" placeholder="Enter your email address" required>
                        <input type="hidden" value="Reset">
    					<button type="submit" class="submit" name="Reset" value="Reset">Submit</button>
					</form>
				</div>
			</div>
            <div class="modal-footer">
            	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
            		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
            </div>
		</div>
    </div>
</div>
<!-- END Password Reset Modal -->



</body>
</html>
