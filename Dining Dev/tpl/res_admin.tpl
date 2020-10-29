<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create New User</title>

<meta name="apple-mobile-web-app-capable" content="yes">
    
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
	  $(document).bind("mobileinit", function () { $.mobile.ajaxEnabled = false; });
	</script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jtsage-datebox-jqm@5.1.3/jtsage-datebox.min.js" type="text/javascript"></script>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <!--Plugin JavaScript file-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>
    
    <!-- <script src="/Reservations/lib/js/age_slider_1.js"></script> -->
	<script src="lib/js/scripts.js"></script>
	
    <!--Plugin CSS file with desired skin-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css"/>
    
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName} perms_res_newUser = {$perms_res_newUser} perms_res_deleteUser = {$perms_res_deleteUser} perms_res_timeslots = {$perms_res_timeslots} perms_res_tables = {$perms_res_tables}}

{include file="header.tpl" theme_color = {$theme_color} full_url = {$full_url} res_admin_class ="active" House_Adults_Total = {$House_Adults_Total} House_Children_Total = {$House_Children_Total} House_Party_Total = {$House_Party_Total} Lodge_Adults_Total = {$Lodge_Adults_Total} Lodge_Children_Total = {$Lodge_Children_Total}}

<form name="new_user" action="{$serverself}" method="POST">
<div class="row" style="margin-top:20px; padding-bottom:500px;">

	{if $User_Message == 'user_update_success'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:red; text-align:center; font-weight:600;">User Info only partially updated</span>
    </div>
    {elseif $User_Message == 'perms_delete_success'}
    <div class="col-md-8 center-col" style="border: 1px solid green; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:green; text-align:center; font-weight:600;">User Successfully Deleted</span>
    </div>
    {elseif $User_Message == 'email_exists'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:red; text-align:center; font-weight:600;">Email address already exists for another user.</span>
    </div>
    {elseif $User_Message == 'user_perms_failed'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:red; text-align:center; font-weight:600;">User Permissions Update Failed</span>
    </div>
    {elseif $User_Message == 'user_perms_success'}
    <div class="col-md-8 center-col" style="border: 1px solid green; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:green; text-align:center; font-weight:600;">User Info Successfully Updated</span>
    </div>
    {elseif $User_Message == 'perms_delete_failed'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:red; text-align:center; font-weight:600;">User Delete Failed</span>
    </div>
    {elseif $User_Message == 'user_protected'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;">
    	<span style="color:red; text-align:center; font-weight:600;">Unable to delete (Protected User).</span>
    </div>
    {/if}
	
	
	
	
	
	
	
	
	
	
	
	
	
<!-- BEGIN Column 1 -->	

    <div class="col-md-6" style="float:left; padding-left:40px">
    	<div class="row">
            <div class="col-md-12" style="margin-bottom:20px;"><h3>User Management</h3></div>
            <div class="col-md-12" style="padding-left:30px; padding-right:40px;">
            	<div class="row">
                	{section name=activeusers loop=$User_Info}
            		<button class="col-md-8 ui-button-augment-1" style="border-color: #b1b1b1;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-{$User_Info[activeusers].0}">
                    <div class="row">
                		<div class="col-md-7"><span style="float:left;">{$User_Info[activeusers].4}, {$User_Info[activeusers].3}</span></div>	
                        <div class="col-md-5"><span style="float:right;">({$User_Info[activeusers].1})</span></div>				
					</div>
					</button>  
    
    <!-- BEGIN Column 1 Modal -->
				<div id="modal-{$User_Info[activeusers].0}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-5"><h4><input type="text" class="modal-edit-1" value="{$User_Info[activeusers].3}" name="firstName" placeholder="{$User_Info[activeusers].3}"><h4></div>
                                    		<div class="col-md-5"><h4><input type="text" class="modal-edit-1" value="{$User_Info[activeusers].4}" name="lastName" placeholder="{$User_Info[activeusers].4}"></h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2"> 
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Username: &nbsp;</div>
                                                <input type="hidden" name="old_username" value="{$User_Info[activeusers].1}">
                                                <div style="display:inline-block; width:70%"><input type="text" name="username" value="{$User_Info[activeusers].1}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Email: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="email" value="{$User_Info[activeusers].6}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Department: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="department" value="{$User_Info[activeusers].5}"></div> 
                                            </div>
                                        </div>
                                         <div class="col-md-12" style="margin-bottom:20px;">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Last Login: &nbsp;</div>
                                                <div class="modal-div-1" style="display:inline-block; width:70%; font-weight:400;">{$User_Info[activeusers].7}</div> 
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-left:-20px; margin-top:10px;">
            								<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_access" value="1"{if $User_Info[activeusers].8 == '1'}checked{/if}></span>
											<span style="vertical-align: sub; display:inline-block">Access to Reservations</span>
										</div>
										<div class="col-md-6" style="margin-left:-20px; margin-top:10px;">
            								<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_newUser" value="1"{if $User_Info[activeusers].13 == '1'}checked{/if}></span>
											<span style="vertical-align: sub; display:inline-block">Override Limits</span>
										</div>
                                        <div class="col-md-6" style="margin-left:-20px; margin-top:10px;">
            								<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_timeslots" value="1"{if $User_Info[activeusers].12 == '1'}checked{/if}></span>
											<span style="vertical-align: sub; display:inline-block">Access Tables tab</span>
										</div>
                                         <div class="col-md-6" style="margin-left:-20px; margin-top:10px;">
            								<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_deleteUser" value="1"{if $User_Info[activeusers].10 == '1'}checked{/if}></span>
											<span style="vertical-align: sub; display:inline-block">Access Admin tab</span>
										</div>
                                        <div class="col-md-6" style="margin-left:-20px; margin-top:10px;">
            								<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_8pm_OOH" value="1"{if $User_Info[activeusers].10 == '1'}checked{/if}></span>
											<span style="vertical-align: sub; display:inline-block">8pm OOH Reservations</span>
										</div>
                                        <input type="hidden" name="userid" value="{$User_Info[activeusers].0}">
                                        <div class="col-md-6" style="padding-left:0px; padding-right:15px; margin-top:30px"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow" style="border-color: #b1b1b1;">Update User Info<input value="update_user" name="update_user" type="submit"></div>
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px; padding-right:15px; margin-top:30px">
                                        	<button class="btn btn-default cancel-button-1" style="font-weight:600; padding:10px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-confirm-{$User_Info[activeusers].0}">Delete User</button>
                                        </div>
									</div>                                   
      							</div>
                                </form>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 1 Modal -->
                
                <!-- BEGIN Col-1 Confirm Modal -->
				<div id="modal-confirm-{$User_Info[activeusers].0}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-7"><h4 class="modal-title">Confirm User Deletion</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;"><span style="font-size:20px; font-weight:600;">Are you sure you want to delete this user?</span></div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-6">
										<button type="button" class="btn btn-default" style="border-color: #b1b1b1;" data-dismiss="modal">Cancel</button>
									</div>
                                    <form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
									<div class="col-md-6">
										<input type="hidden" name="delete_userid" value="{$User_Info[activeusers].0}">
										<button type="submit" name="delete_username" value="{$User_Info[activeusers].1}" class="btn btn-default cancel-button-1">Yes, Delete</button>
									</div>
									</form>
								</div>
							</div>
                            <div class="modal-footer" style="height:304px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- END Col-1 Confirm Modal -->
    			
                {/section}
                
				</div>
			</div>
            
            <div class="col-md-12" style="margin-top:30px;"><h3>Create New User</h3></div>
			<div class="col-md-12">
				<form name="new_user" action="{$serverself}" method="POST">
<div class="row" style="margin-top:20px; padding-bottom:500px;">
	{if $Signup_Status == 'signup_success'}
    <div class="col-md-8 center-col" style="border: 1px solid green; margin-bottom:20px; text-align:center; padding:15px;"><span style="color:green; text-align:center; font-weight:600;">New User Successfully created.</span><br><span style="color:green; text-align:center;">A password creation email should soon arrive at the email address entered. Please click the link in the email to finish creating the account.</span></div>
    {elseif $Signup_Status == 'email_exists'}
    <div class="col-md-8 center-col" style="border: 1px solid red; margin-bottom:20px; text-align:center; padding:15px;"><span style="color:red; text-align:center; font-weight:600;">User Email address already exists.</span><br><span style="color:red; text-align:center;">Please enter different email address to create new user.</span></div>
    {/if}

            <div class="col-md-12">
            	<div class="row">
            		<div class="col-md-6"><input required type="text" name="firstName" placeholder="First Name"></div>
            		<div class="col-md-6"><input required type="text" name="lastName" placeholder="Last Name"></div>
				</div>
			</div>
            <div class="col-md-12"><input requiredtype="text" name="username" placeholder="Username"></div>
            <div class="col-md-12"><input type="text" name="department" placeholder="Department"></div>
            <div class="col-md-12"><input required type="email" name="email" id="email" placeholder="Enter Email"></div>
            <div class="col-md-12"><input required type="email" name="confirm_email" id="confirm_email" placeholder="Confirm Email"></div>
            <script language='javascript' type='text/javascript'>
    			var email = document.getElementById("email")
				var confirm_email = document.getElementById("confirm_email");

				function validateEmail(){
  					if(email.value != confirm_email.value) {
    					confirm_email.setCustomValidity("Emails Don't Match");
  					} else {
    					confirm_email.setCustomValidity('');
  					}
				}

				email.onchange = validateEmail;
				confirm_email.onkeyup = validateEmail;
			</script>
    
            
            <div class="col-md-12"><h3>Permissions</h3></div>
            <div class="col-md-12">
            	<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_access" value="1" checked></span>
				<span style="vertical-align: sub; display:inline-block">Access to Reservations</span>
			</div>
			<div class="col-md-12">
				<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_limit_override" value="1"></span>
				<span style="vertical-align: sub; display:inline-block">Override Limits</span>
			</div>
			<div class="col-md-12">
				<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_tables" value="1"></span>
				<span style="vertical-align: sub; display:inline-block">Access Tables tab</span>
			</div>
			<div class="col-md-12">
				<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_deleteUser" value="1"></span>
				<span style="vertical-align: sub; display:inline-block">Access Admin tab</span>
			</div>


    
    <div class="col-md-6" style="margin-top:20px; margin-left:auto; margin-right:auto; float:none;"><button type="submit" name="Submit">Create New User</button></div>
    
</div>
				</form>
			</div>
            
            
		</div>
    </div>		
                
<!-- END Column 1 -->

			
<!-- BEGIN Column 2 -->

	{section name=settings loop=$Settings}
    
    <div class="col-md-6" style="float:right; padding-right:40px">
    	<div class="row">
            <div class="col-md-12" style="margin-bottom:20px;"><h3>Settings</h3></div>
			<div class="col-md-12">
            	<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_6pm_ooh" value="1"{if $Settings[settings].0 == '1'}checked{/if}></span>
				<span style="vertical-align: sub; display:inline-block">6pm OOH Reservations enabled?</span>
			</div>
            <div class="col-md-12">
            	<span style="display: inline-block; width:35px;"><input type="checkbox" name="reservations_8pm_ooh" value="1"{if $Settings[settings].1 == '1'}checked{/if}></span>
				<span style="vertical-align: sub; display:inline-block">8pm OOH Reservations enabled?</span>
			</div>
            <div class="col-md-12">
            	<span style="display: inline-block; width:35px;"><input type="checkbox" name="enable_table_num" value="1"{if $Settings[settings].2 == '1'}checked{/if}></span>
				<span style="vertical-align: sub; display:inline-block">Table Number input field enabled?</span>
			</div>
            <div class="col-md-12">
            	<span style="display: inline-block; width:35px;"><input type="checkbox" name="enable_table_minimum" value="1"{if $Settings[settings].3 == '1'}checked{/if}></span>
				<span style="vertical-align: sub; display:inline-block">Table minimum enabled?</span>
			</div>
         
<!--    
            
            <div class="col-md-4">
        				<select name="reservations_8pm_ooh">		
                			<option class="option-formatting" value="1">Yes</option>
                            <option class="option-formatting" value="0">No</option>
        				</select>
			</div>
            
-->            
            
            <div class="col-md-6" style="margin-top:20px; margin-left:auto; margin-right:auto; float:none;"><button value="update_settings" type="submit" name="update_settings">Update Settings</button></div>
        </div>
    </div>
    
    {/section}
    
<!-- END Column 2 --> 
    
</div>
</form>

</body>
</html>
