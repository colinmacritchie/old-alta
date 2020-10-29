<div class="topnav" style="background-color: {$theme_color}">
<div class="row">
	<div class="col-md-1" style="padding-right:0px;"><img onClick="location.href = location.href" style="height:53px; padding-left:10px" src="images/refresh-2.png"></img></div>
    <div class="col-md-6" style="padding-left:0px;">
        <!--<a class="{$res_breakfastandlunch_class}" href="res_breakfastandlunch.php">B & L</a>-->
        <!-- <a class="{$res_breakfast_class}" style="border-top: 7px solid {$theme_color};" href="res_breakfast.php">{$tab_1_name}</a> -->
        <a class="{$res_breakfast_class}" style="border-top: 7px solid {$theme_color};" href="breakfast_layout.php">{$tab_1_name}</a>
        <!-- <a class="{$res_lunch_class}" style="border-top: 7px solid {$theme_color};" href="res_lunch.php">{$tab_2_name}</a> -->
        <a class="{$res_lunch_class}" style="border-top: 7px solid {$theme_color};" href="lunch_layout.php">{$tab_2_name}</a>
        <a class="{$res_new_class}" style="display:none;" href="res_new.php">New</a>
  		<a class="{$res_layout_class}" style="border-top: 7px solid {$theme_color};" href="dinner_layout.php">{$tab_3_name}</a>
        <a class="{$res_history_class}" style="display:none;" href="res_history.php">History</a>
        <!-- {if $perms_res_timeslots == '1'} -->
  		<!-- <a class="{$res_timeslots_class}" style="display:none;" href="res_timeslots.php">Timeslots</a> -->
  		<!-- {/if} -->
        {if $perms_res_tables == '1'}
  		<a class="{$res_tables_class}" style="border-top: 7px solid {$theme_color};" href="res_tables.php">{$tab_4_name}</a>
  		{/if}
        {if $perms_res_newUser == '1'}
  		<a class="{$res_newUser_class}" style="display:none;" href="auth_signup.php">New User</a>
  		{/if}
        {if $perms_res_deleteUser == '1'}
  		<a class="{$res_admin_class}" style="border-top: 7px solid {$theme_color};" href="res_admin.php">Admin</a>
  		{/if}
	</div>
    <div class="col-md-3" style="padding-right:0px">

		<input type="text" id="search" placeholder="Search" />
   		<!-- Suggestions will be displayed in below div. -->
   		<div id="display" style="position:fixed; z-index:99; background-color: white; width:40%; border-color:#ddd; border-width:0px; border-style:solid; box-shadow:inset 0 1px 3px rgba(0,0,0,.2); border-radius:0 0 .3125em .3125em;"></div>
		
    </div>
    <div class="col-md-2">
        <div class="ui-btn ui-shadow ui-corner-all" style="padding:7px; margin-right:10px;"><span>Guests: {$RDP_Guest_Total}</span></div>
    </div>
</div>
</div>