<div class="menu-topnav">
<div class="row" style="70px;">
    <div class="col-md-4 menu-links" style="padding-left:0px;">
    	<a style="padding-left:20px; margin-top:0px; padding-right:7px; padding-top: 0px; padding-bottom: 0px;" href="{$full_url}/"><img style="height:61px; padding:5px;" src="{$theme_logo}"></img></a>
    	<a style="padding-bottom:20px; background-color: {$theme_color}; color: white;" href="/"> Dining Reservations</a>
    </div>
    
    {if $username == ''}
    <div class="col-md-3" style="padding-right:0px">
    </div>
    {else}
	<div class="col-md-3" style="padding-right:0px">
    	<form id="dateForm" name="date_form" action="{$serverself}" method="POST" style="display:contents;">
 		<div class="form-group" style="margin-bottom:0px;">
    		<input class="form-control" style="padding-left:20px;" name="newdate" type="date" value="{$selected_date}" data-role="datebox" id="db1" data-datebox-mode="calbox" data-datebox-close-callback="submitdate" data-datebox-override-date-format="%Y-%m-%d">
            <script type="text/javascript">
				function submitdate() { 
        			$('#dateForm').submit();
				}
			</script>
		</div>
        </form>
    </div>
    {/if}
    {if $username == ''}
    <div class="col-md-2"><span></span></div>
    <div class="col-md-2" style="padding-right:25px; padding-top:10px;">
    <a href="{$full_url}/auth_sign_in.php" id="logoff"><button class="menu-button"> Login</button></a>
    </div>
    {else}
    <div class="col-md-2"><span class="menu-firstname">Hello {$user_firstName}</span></div>
    <div class="col-md-2" style="padding-right:0px; padding-top:10px;">
    <a href="index.php?logoff=1" id="logoff"><button class="menu-button"> Logout</button></a>
    </div>
    
    <div id="bell_icon" class="col-md-1" style="height:60px; cursor:pointer;" onclick="show_notifications()">
    	<img style="height:61px; padding:5px;" src="/images/bell-icon-black.png"></img>
     
    {if $Unread_Total > 0}    
        
        <div style="position:relative; top:-50px; right:-25px;"><span style="border: 3px solid red; border-radius: 15px; color:red; padding:1px 7px; font-weight:600; background-color:white;">{$Unread_Total}</span></div>
        
    {/if}    
        <div id="unread_notifications" class="notifications" style="display:none; position:fixed; top:64px; right:0px; background-color:white; z-index:99; box-shadow: 0 2px 4px #d9d9d9; border:1px solid #9c9c9c;">
        <div>
        <form name="mark_read" action="{$serverself}" method="POST" style="display:contents;">
        <input type="hidden" name="mark_read" value="mark_read">
        <button style="display:block; float:right; width:150px; margin-right:10px;" type="submit" value="mark_read" class="btn btn-default cancel-button-1">Mark Read</button>
        </form>
        </div>
        	<table>
  				<tr>
  				<th>From</th>
                <th>Time</th>
    			<th>Room</th>
    			<th>Message</th>
  			</tr>
            
            {section name=unread loop=$SMS_unread}
            
  			<tr>
    			<td>{$SMS_unread[unread].0}</td>
                <td>{$SMS_unread[unread].6|date_format:"%l:%M %p"}</td>
    			<td>{$SMS_unread[unread].5}</td>
    			<td>{$SMS_unread[unread].2}</td>
  			</tr>
            
            {/section}
			</table>
        </div>
    </div>
    
    
    
    {/if}
</div>
</div>
