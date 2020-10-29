<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reservations: Reservation Layout</title>

<!--	
    <meta name="viewport" content="width=device-width, initial-scale=1">
-->
    
    <meta name="apple-mobile-web-app-capable" content="yes">
    
    <!-- Old Jquery CDN implementation. Not needed with jquery mobile -->
	<!-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
    <script src="lib/js/jquery-1.11.1.min.js"></script>
    
    <script type="text/javascript">
	  $(document).bind("mobileinit", function () { $.mobile.ajaxEnabled = false; });
	</script>
	
	<!-- Old CDN implementation of jquery mobile js -->
	<!-- <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->    
    <script src="lib/js/jquery.mobile-1.4.5.min.js" type="text/javascript"></script>
    
	<!-- Old CDN implementation of datebox js -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/jtsage-datebox-jqm@5.1.3/jtsage-datebox.min.js" type="text/javascript"></script> -->
	<script src="lib/js/jtsage-datebox-5.1.3.jqm.min.js" type="text/javascript"></script>
    
	<script src="lib/js/auto_refresh.js"></script>
	<script src="lib/js/return_refresh.js"></script>
	
	<script src="lib/js/field_selection_4.js"></script>
	<script src="lib/js/scripts.js"></script>
	<script src="lib/js/custom.js"></script>
    
    <!-- Old CDN implementation of boostrap js -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
    <script src="lib/js/bootstrap.min.js"></script>

 	<!-- Old CDN implementation of jquery mobile css -->
	<!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" /> --> 
    <link rel="stylesheet" href="lib/css/jquery.mobile-1.4.5.min.css" />
    
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="lib/css/jtsage-datebox.min.css" />
    
    <!-- Old CDN implementation of bootstrap css -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css" />
    
    
</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName} perms_res_newUser = {$perms_res_newUser} perms_res_deleteUser = {$perms_res_deleteUser} perms_res_timeslots = {$perms_res_timeslots} perms_res_tables = {$perms_res_tables} res_8pm_ooh = {$res_8pm_ooh}  enable_table_minimum = {$enable_table_minimum}}

{include file="header.tpl" theme_color = {$theme_color} full_url = {$full_url} res_layout_class="active" House_Adults_Total = {$House_Adults_Total} House_Children_Total = {$House_Children_Total} House_Party_Total = {$House_Party_Total} Lodge_Adults_Total = {$Lodge_Adults_Total} Lodge_Children_Total = {$Lodge_Children_Total}}

<div class="row" style="padding:20px; padding-bottom:500px;">

<!-- 1st Column -->
<div class="col-md-4 layout-column-settings">

	<!-- BEGIN col-1 Reservations -->
    {foreach $colonetimesClean as $timeone}
    <div class="row time-group-row-1">
		<div class="col-md-12">
        	<div class="row">			
				
				{if ${$timeone}_Time == '18:01:00'}
				<div class="col-md-9" style="text-align:left;"><span><h3>Overflow</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$timeone}_Total}</h3></span></div>
				{else}
				<div class="col-md-9" style="text-align:left;"><span><h3>{${$timeone}_Time|date_format:"%l:%M %p"}</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$timeone}_Total}</h3></span></div>
				{/if}
				
			</div>
        </div>        
		<div class="col-md-12">
            <div class="row">
				{section name=reservations loop=${$timeone}_Res}

				
			{if ${$timeone}_Res[reservations].24 == '0'}
				
				{if ${$timeone}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$timeone}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timeone}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$timeone}_Res[reservations].13} - {${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        {if ${$timeone}_{${$timeone}_Res[reservations].14}_Table >= ${$timeone}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{/if}
		
					</div>
				</button>	
				
				
				{else if ${$timeone}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$timeone}_Res[reservations].8}"  onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$timeone}_Res[reservations].8}" style="padding-right:0px;"><span>{${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						{if ${$timeone}_{${$timeone}_Res[reservations].14}_Table >= ${$timeone}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{/if}
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$timeone}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timeone}_Res[reservations].8}" style="padding-right:0px;"><span> {${$timeone}_Res[reservations].13} - {${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						{if ${$timeone}_{${$timeone}_Res[reservations].14}_Table >= ${$timeone}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timeone}_{${$timeone}_Res[reservations].14}_Table}/{${$timeone}_Res[reservations].16})</span>
						</div>
						{/if}

					</div>
				</button>
                
                {/if}
	
			{else}
	
				{if ${$timeone}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$timeone}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timeone}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$timeone}_Res[reservations].13} - {${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
		
					</div>
				</button>	
				
				
				{else if ${$timeone}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$timeone}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$timeone}_Res[reservations].8} style="padding-right:0px;""><span>{${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$timeone}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timeone}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timeone}_Res[reservations].8} style="padding-right:0px;""><span> {${$timeone}_Res[reservations].13} - {${$timeone}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timeone}_Res[reservations].4 == '' && ${$timeone}_Res[reservations].9 == '' && ${$timeone}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timeone}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timeone}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timeone}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>

					</div>
				</button>
                
                {/if}

			{/if}
                 
                 <!--
                 
                 <button class="col-md-2 ui-button-augment-1" style="text-align:center; margin-left:10px; border-color:black; padding-left:5px; padding-right:5px; padding-top:2px; padding-bottom:2px; font-size:20px; max-width:40px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$timeone}_Res[reservations].6}">
                 +
                 </button>
                
                -->
                
                <!-- BEGIN Column 1 Modal -->
				<div id="modal{${$timeone}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	<div class="col-md-2" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$timeone}_Res[reservations].13}" name="room_num" placeholder="{${$timeone}_Res[reservations].13}"><h5></div>
											
											{if ${$timeone}_Res[reservations].25 == ''}
                                			<div class="col-md-8" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$timeone}_Res[reservations].15}" name="party_name" placeholder="{${$timeone}_Res[reservations].15}"><h5></div>
											{else}
											<div class="col-md-8" style="padding-right:0px;">
												<h5><input type="text" class="modal-edit-1" value="{${$timeone}_Res[reservations].15} [{${$timeone}_Res[reservations].25}]" name="party_name_affiliation" placeholder="{${$timeone}_Res[reservations].15} [{${$timeone}_Res[reservations].25}]"><h5>
												<input type="text" name="party_name" value="{${$timeone}_Res[reservations].15}" hidden>
											</div>
											{/if}
                                    		
											<div class="col-md-2" style="padding-left:0px; padding-right:30px;"><h5 class="modal-title"><button style="margin-top:10px !important;" type="button" class="close" data-dismiss="modal">&times;</button></h5></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-6 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Date &amp; Time: &nbsp;</div>
												<div class="modal-div-1" style="font-weight:400;">{$selected_date|date_format:"%m/%d/%Y"}, {${$timeone}_Time|date_format:"%l:%M %p"}</div>
												<input type="hidden" name="change_res_date" value="{$selected_date}">
                                                
												<!-- Old code to be able to change date. Disabled for now.

												<div style="display:inline-block; width:70%;"><input class="form-control" type="date" name="change_res_date" value="{$selected_date}" min="{$current_date}" max="{$date_plus2}" placeholder="{${$timeone}_Res[reservations].1}" data-role="datebox" id="db2" data-datebox-mode="calbox" data-datebox-override-date-format="%Y-%m-%d"></div>

												-->

                                            </div>
										</div>
                                        <div class="col-md-6 modal-formatting-1 change-button-1">
                                            <div style="display:inline-block; width:100%;">
                                        		<input type="hidden" name="res_id" value="{${$timeone}_Res[reservations].6}">
                                                <input type="hidden" name="original_block_id" value="{${$timeone}_Res[reservations].5}">
                                                <input type="hidden" name="original_res_date" value="{$selected_date}">
                                                <input type="hidden" name="original_res_time" value="{${$timeone}_Time}">
												<input type="hidden" name="original_gog_num" value="{${$timeone}_Res[reservations].19}">
												<input type="hidden" name="original_party_num" value="{${$timeone}_Res[reservations].3}">
												<input type="hidden" name="table_num" value="{${$timeone}_Res[reservations].14}">
												<input type="hidden" name="res_time" value="{${$timeone}_Res[reservations].7}">
												<input type="hidden" name="table_max" value="{${$timeone}_Res[reservations].16}">
												<input type="hidden" name="notes" value="">
												<input type="hidden" name="wasatch" value="{${$timeone}_Res[reservations].22}">
												
                                    			
                                                <!--
                                                
                                                <select name="change_res_time">
        											<option hidden selected value="{${$timeone}_Time}">{${$timeone}_Time|date_format:"%l:%M %p"}</option>			
                									{section name=timeblocklayout loop=$Avail_Timeblocks_Layout}
                									<option class = "testclass" value = "{$Avail_Timeblocks_Layout[timeblocklayout].res_time}" >{$Avail_Timeblocks_Layout[timeblocklayout].res_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    
    											-->
    												
    											<select name="change_table_time">
        											<option hidden disabled selected value> -- New Table & Time -- </option>			
                									<option class = "testclass" value = "131">&nbsp;&nbsp;&nbsp;&nbsp;-- Unassign -- </option>
                                                    {section name=reschedule loop=$Reschedule}
                									<option class = "testclass" value = "{$Reschedule[reschedule].block_id}, {$Reschedule[reschedule].table_num}, {$Reschedule[reschedule].block_time}" >&nbsp;&nbsp;{$Reschedule[reschedule].block_time|date_format:"%l:%M %p"} - ({$Reschedule[reschedule].capacity_min} / {$Reschedule[reschedule].capacity_max})</option>
                									{/section}
        										</select>
    
    
    
    										</div>                                                                                                                                                                        
                                        </div>
										 <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Table# : &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$timeone}_Res[reservations].23}" name="actual_table" min="1" placeholder="{${$timeone}_Res[reservations].23}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Party: &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$timeone}_Res[reservations].3}" name="party_num" min="1" placeholder="{${$timeone}_Res[reservations].3}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">GoG: &nbsp;</div>
                                                <div style="display:inline-block; width:40%;"><input type="number" name="gog_num" value="{${$timeone}_Res[reservations].19}"></div>
                                            </div>
                                        </div>
                                        
                                        {if (${$timeone}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$timeone}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                        
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">OOH?: &nbsp;</div>
                                                <input type="hidden" name="out_of_hotel" value="0" />
                                                {if ${$timeone}_Res[reservations].20 == '1'}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" checked="true" name="out_of_hotel" value="{${$timeone}_Res[reservations].20}"></div>
                                                {else}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" name="out_of_hotel" value="1"></div>
                                                {/if}
                                            </div>
                                        </div>
                                        
                                        {/if}
                                        
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Food Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="food_requests_og" value="{${$timeone}_Res[reservations].21}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Special Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="special_requests" value="{${$timeone}_Res[reservations].4}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;">
											<div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Update Reservation<input value="Submit" name="submit" type="submit"></div>
										</div>
										{if ${$timeone}_Res[reservations].27 == 1}
										<div class="col-md-12" style="padding-left:0px; padding-right:15px;">
                                        	<button class="btn btn-default sms-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-sms-{${$timeone}_Res[reservations].6}">SMS</button>
                                        </div>
										{/if}
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<div class="row" style="display:contents;">
                                    	
                                        <form name="modal_options3" action="{$serverself}" method="POST" style="display:contents;">
                                        <div class="col-md-4">
                                        	<input type="hidden" name="res_status" value="{${$timeone}_Res[reservations].8}">
                                        	<input type="hidden" value="{${$timeone}_Res[reservations].6}" name="Arrived">                                            
                                            <button type="submit" value="{${$timeone}_Res[reservations].12}" class="btn btn-default add-button-1">
											
											{if ${$timeone}_Res[reservations].8 == '1'}
											Arrived
											{else if ${$timeone}_Res[reservations].8 == '3'}
											Undo Arrived
											{/if}
											
											</button>
                                        </div>
                                        </form>
                                        
                                        <div class="col-md-4">
                                        	<button class="btn btn-default add-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$timeone}_Res[reservations].6}">+ Res to Table</button>
                                        </div>
                                        
                                         <div class="col-md-4">
                                        	<button class="btn btn-default cancel-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-confirm-{${$timeone}_Res[reservations].6}">Remove</button>
                                        </div>
                                    </div>
      							</div>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 1 Modal -->
                
                <!-- BEGIN Col-1 Confirm Modal -->
				<div id="modal-confirm-{${$timeone}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Confirm Cancellation</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;"><span style="font-size:20px; font-weight:600;">Are you sure you want to delete this reservation?</span></div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-6">
										<button type="button" class="btn btn-default arrived-button-1" data-dismiss="modal">Cancel</button>
									</div>
                                    <form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
									<div class="col-md-6">
										<input type="hidden" name="cancel_res_id" value="{${$timeone}_Res[reservations].6}">
										<input type="hidden" name="table_num" value="{${$timeone}_Res[reservations].14}">
										<input type="hidden" name="res_time" value="{${$timeone}_Res[reservations].7}">
										<input type="hidden" name="res_date" value="{$selected_date}">
										<input type="hidden" name="party_num" value="{${$timeone}_Res[reservations].3}">
										<input type="hidden" name="gog_num" value="{${$timeone}_Res[reservations].19}">
										<input type="hidden" name="block_id" value="{${$timeone}_Res[reservations].5}">
										<button type="submit" value="{${$timeone}_Res[reservations].12}" class="btn btn-default cancel-button-1">Yes, Remove</button>
									</div>
									</form>
								</div>
							</div>
                            <div class="modal-footer" style="height:204px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- BEGIN Col-1 Confirm Modal -->
									
				<!-- BEGIN Col-1 SMS Modal -->
				<div id="modal-sms-{${$timeone}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Guest Messaging</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">                          
                                	<div class="col-md-12" id="content">
                                	{section name=messages loop=${$timeone}_SMS}
                                    	<div class="{${$timeone}_SMS[messages].3}">
											<table style="border-collapse: separate !important;">
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
												<tr><td class="cell"></td><td class="call textcell">{${$timeone}_SMS[messages].2}</td><td class="cell"></td></tr>
                                                <tr><td class="cell"></td><td class="sms_timestamp">{${$timeone}_SMS[messages].1}</td><td></td></tr>
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
											</table>
										</div>
                                    {/section}    
                                    </div>                             
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
                                	<form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
                                    <div class="col-md-9" id="sms-send">
										<input type="text" name="guest_message" required>
									</div>
                                    
									<div class="col-md-3">
										<input type="hidden" name="to_phone" value="{${$timeone}_Res[reservations].26}">
                                        <input type="hidden" name="sms_res_id" value="{${$timeone}_Res[reservations].6}">
										<button type="submit" value="{${$timeone}_Res[reservations].12}" class="btn btn-default sms-button-1">Send</button>
									</div>
									</form>
								</div>
							</div>
                        </div>
                    </div>
				</div>
                <!-- END Col-1 SMS Modal -->
                
                <!-- BEGIN Column 1 Duplicate Modal -->
				<div id="modal-duplicate-{${$timeone}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_duplicate" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="res_time_id" value="{${$timeone}_Res[reservations].17}">
                                <input type="hidden" name="table_id" value="{${$timeone}_Res[reservations].18}">
                                <input type="hidden" name="res_date" value="{${$timeone}_Res[reservations].1}">
								<input type="hidden" name="table_max" value="{${$timeone}_Res[reservations].16}">
								<input type="hidden" name="res_time" value="{${$timeone}_Time}">
								<input type="hidden" name="block_id" value="{${$timeone}_Res[reservations].5}">
								<input type="hidden" name="res_time" value="{${$timeone}_Res[reservations].7}">
                    			<input type="hidden" name="res_date" value="{$selected_date}">
								<input type="hidden" name="table_num" value="{${$timeone}_Res[reservations].14}">
								<input type="hidden" name="wasatch" value="{${$timeone}_Res[reservations].22}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Add additional Reservation to Table #{${$timeone}_Res[reservations].14} at {${$timeone}_Time|date_format:"%l:%M %p"}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value> -- Select a Guest -- </option>
                                                                
                                                                {if (${$timeone}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$timeone}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
                                                                			
                												{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment8}" data-special="">{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-4 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
														
                                                        {if $enable_table_num == '1'}
                                                        
                                                        <div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                                                        <div class="col-md-3" ><input type="number" name="actual_table"></div>
                    									
                                                        {/if} 
                                                        
													</div>
                									<div class="row">
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Add Reservation<input value="duplicate" name="duplicate" type="submit"></div></div>
														
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>	
									
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
                                <div class="modal-footer" style="height:113px;"></div>
    						</div>

  					</div>
				</div>
				<!-- END Column 1 Duplicate Modal -->  
									
									
                {/section}      
               
				
			</div>
        </div>
        <div class="col-md-12">
        	<div class="row">
				{section name=reservations loop=${$timeone}_Avail}
				
				{if ${$timeone}_Avail[reservations].wasatch == '1'}
                    {if enable_table_minimum == '1'}
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timeone}_Avail[reservations].block_id}"><span>(W) ({${$timeone}_Avail[reservations].capacity_min}-{${$timeone}_Avail[reservations].capacity_max}) Available</span></button>
                    {else}
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timeone}_Avail[reservations].block_id}"><span>(W) ({${$timeone}_Avail[reservations].capacity_max}) Available</span></button>
                    {/if}
                {else}
                    {if enable_table_minimum == '1'}
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timeone}_Avail[reservations].block_id}"><span>({${$timeone}_Avail[reservations].capacity_min}-{${$timeone}_Avail[reservations].capacity_max}) Available</span></button>
                    {else}
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timeone}_Avail[reservations].block_id}"><span>({${$timeone}_Avail[reservations].capacity_max}) Available</span></button>
                    {/if}
				{/if}
				
			
             <!-- BEGIN Column 1 Assign Modal -->
				<div id="modal-assign-{${$timeone}_Avail[reservations].block_id}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="block_id" value="{${$timeone}_Avail[reservations].block_id}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	{if enable_table_min == '1'}
                                            <div class="col-md-10"><h4>({${$timeone}_Avail[reservations].capacity_min}-{${$timeone}_Avail[reservations].capacity_max}) - {${$timeone}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
                                            {else}
                                            <div class="col-md-10"><h4>({${$timeone}_Avail[reservations].capacity_max}) - {${$timeone}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
                                            {/if}
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
								<!--	
									
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;">
                                            
                                    			<select required name="select_res">
        											<option hidden disabled selected value> -- Select a Reservation -- </option>		
                									{section name=assignment loop=${$timeone}_Assign}
                									<option class = "testclass" value = "{${$timeone}_Assign[assignment].res_id}" >&nbsp;&nbsp;{${$timeone}_Assign[assignment].party_name} - {${$timeone}_Assign[assignment].room_num}</option>
                									{/section}
        										</select>
                                                                                     
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Assign Reservation<input value="assign" name="assign" type="submit"></div></div>
									</div>                                   
      							</div>

								-->
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value data-food=""> -- Select a Guest -- </option>
                            									
                                                                {if (${$timeone}_Avail[reservations].block_time == '18:00:00' && $res_6pm_ooh == '1') || (${$timeone}_Avail[reservations].block_time == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
																
																{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment3}" data-special="{$Guestlist[guests].Comment24}" data-affiliation="{$Guestlist[guests].Comment2}" data-phone="{$Guestlist[guests].Phone1}">{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-5 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
                                                        
                                                        {if $enable_table_num == '1'}
                                                        
														<div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                    									<div class="col-md-2" ><input type="number" name="actual_table"></div>
                                                        
                                                        {/if}
                                                        
													</div>
                									<div class="row">
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4" style="vertical-align:top; padding-top:15px; height:53px;">Guest Messaging: </div>
																<div class="col-md-1" style="padding-top:20px; padding-left:8px;"><input type="checkbox" name="sms_opt_in" id="sms_opt_in_{${$timeone}_Avail[reservations].block_id}"></div>
																<div class="col-md-7" style="display:none;" id="enter_phone_div_{${$timeone}_Avail[reservations].block_id}"><input type="text" name="guest_phone" id="enter_phone" placeholder="Enter Phone Number" min="10"></div>
															</div>
														</div>
														<div class="col-md-12"><input type="text" name="affiliation" placeholder="Affiliation"></div>
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<input type="hidden" name="block_id" value="{${$timeone}_Avail[reservations].block_id}">
														<input type="hidden" name="res_time" value="{${$timeone}_Avail[reservations].block_time}">
                    									<input type="hidden" name="res_date" value="{$selected_date}">
														<input type="hidden" name="table_num" value="{${$timeone}_Avail[reservations].table_num}">
														<input type="hidden" name="table_max" value="{${$timeone}_Avail[reservations].capacity_max}">
														<input type="hidden" name="wasatch" value="{${$timeone}_Avail[reservations].wasatch}">
														<div class="col-md-12"><input value="Submit" name="newsubmit" type="submit" class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1" style="width:100%; border-color:#afafaf"></div>
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>
							
                                </form>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 1 Assign Modal -->  				
						
        		{/section}			
            
            
            </div>
        </div>
    </div>
    
    {/foreach}   
	<!-- END col-1 Reservations -->

</div>
<!-- END 1st Column -->














<!-- 2nd Column -->
<div class="col-md-4 layout-column-settings">

	<!-- BEGIN col-2 Reservations -->
    {foreach $col2timesClean as $time}
    <div class="row time-group-row-1">
		<div class="col-md-12">
        	<div class="row">			
				
				{if ${$time}_Time == '18:01:00'}
				<div class="col-md-9" style="text-align:left;"><span><h3>Overflow</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$time}_Total}</h3></span></div>
				{else}
				<div class="col-md-9" style="text-align:left;"><span><h3>{${$time}_Time|date_format:"%l:%M %p"}</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$time}_Total}</h3></span></div>
				{/if}
				
			</div>
        </div>        
		<div class="col-md-12">
            <div class="row">
				{section name=reservations loop=${$time}_Res}

				
			{if ${$time}_Res[reservations].24 == '0'}
				
				{if ${$time}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$time}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$time}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$time}_Res[reservations].13} - {${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        {if ${$time}_{${$time}_Res[reservations].14}_Table >= ${$time}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{/if}
		
					</div>
				</button>	
				
				
				{else if ${$time}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$time}_Res[reservations].8}"  onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$time}_Res[reservations].8}" style="padding-right:0px;"><span>{${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						{if ${$time}_{${$time}_Res[reservations].14}_Table >= ${$time}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{/if}
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$time}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$time}_Res[reservations].8}" style="padding-right:0px;"><span> {${$time}_Res[reservations].13} - {${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						{if ${$time}_{${$time}_Res[reservations].14}_Table >= ${$time}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$time}_{${$time}_Res[reservations].14}_Table}/{${$time}_Res[reservations].16})</span>
						</div>
						{/if}

					</div>
				</button>
                
                {/if}
	
			{else}
	
				{if ${$time}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$time}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$time}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$time}_Res[reservations].13} - {${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
		
					</div>
				</button>	
				
				
				{else if ${$time}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$time}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$time}_Res[reservations].8} style="padding-right:0px;""><span>{${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$time}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$time}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$time}_Res[reservations].8} style="padding-right:0px;""><span> {${$time}_Res[reservations].13} - {${$time}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$time}_Res[reservations].4 == '' && ${$time}_Res[reservations].9 == '' && ${$time}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$time}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$time}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$time}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>

					</div>
				</button>
                
                {/if}

			{/if}
                 
                 <!--
                 
                 <button class="col-md-2 ui-button-augment-1" style="text-align:center; margin-left:10px; border-color:black; padding-left:5px; padding-right:5px; padding-top:2px; padding-bottom:2px; font-size:20px; max-width:40px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$time}_Res[reservations].6}">
                 +
                 </button>
                
                -->
                
                <!-- BEGIN Column 2 Modal -->
				<div id="modal{${$time}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	<div class="col-md-2" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$time}_Res[reservations].13}" name="room_num" placeholder="{${$time}_Res[reservations].13}"><h5></div>
											
											{if ${$time}_Res[reservations].25 == ''}
                                			<div class="col-md-8" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$time}_Res[reservations].15}" name="party_name" placeholder="{${$time}_Res[reservations].15}"><h5></div>
											{else}
											<div class="col-md-8" style="padding-right:0px;">
												<h5><input type="text" class="modal-edit-1" value="{${$time}_Res[reservations].15} [{${$time}_Res[reservations].25}]" name="party_name_affiliation" placeholder="{${$time}_Res[reservations].15} [{${$time}_Res[reservations].25}]"><h5>
												<input type="text" name="party_name" value="{${$time}_Res[reservations].15}" hidden>
											</div>
											{/if}
                                    		
											<div class="col-md-2" style="padding-left:0px; padding-right:30px;"><h5 class="modal-title"><button style="margin-top:10px !important;" type="button" class="close" data-dismiss="modal">&times;</button></h5></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-6 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Date &amp; Time: &nbsp;</div>
												<div class="modal-div-1" style="font-weight:400;">{$selected_date|date_format:"%m/%d/%Y"}, {${$time}_Time|date_format:"%l:%M %p"}</div>
												<input type="hidden" name="change_res_date" value="{$selected_date}">
                                                
												<!-- Old code to be able to change date. Disabled for now.

												<div style="display:inline-block; width:70%;"><input class="form-control" type="date" name="change_res_date" value="{$selected_date}" min="{$current_date}" max="{$date_plus2}" placeholder="{${$time}_Res[reservations].1}" data-role="datebox" id="db2" data-datebox-mode="calbox" data-datebox-override-date-format="%Y-%m-%d"></div>

												-->

                                            </div>
										</div>
                                        <div class="col-md-6 modal-formatting-1 change-button-1">
                                            <div style="display:inline-block; width:100%;">
                                        		<input type="hidden" name="res_id" value="{${$time}_Res[reservations].6}">
                                                <input type="hidden" name="original_block_id" value="{${$time}_Res[reservations].5}">
                                                <input type="hidden" name="original_res_date" value="{$selected_date}">
                                                <input type="hidden" name="original_res_time" value="{${$time}_Time}">
												<input type="hidden" name="original_gog_num" value="{${$time}_Res[reservations].19}">
												<input type="hidden" name="original_party_num" value="{${$time}_Res[reservations].3}">
												<input type="hidden" name="table_num" value="{${$time}_Res[reservations].14}">
												<input type="hidden" name="res_time" value="{${$time}_Res[reservations].7}">
												<input type="hidden" name="table_max" value="{${$time}_Res[reservations].16}">
												<input type="hidden" name="notes" value="">
												<input type="hidden" name="wasatch" value="{${$time}_Res[reservations].22}">
												
                                    			
                                                <!--
                                                
                                                <select name="change_res_time">
        											<option hidden selected value="{${$time}_Time}">{${$time}_Time|date_format:"%l:%M %p"}</option>			
                									{section name=timeblocklayout loop=$Avail_Timeblocks_Layout}
                									<option class = "testclass" value = "{$Avail_Timeblocks_Layout[timeblocklayout].res_time}" >{$Avail_Timeblocks_Layout[timeblocklayout].res_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    
    											-->
    												
    											<select name="change_table_time">
        											<option hidden disabled selected value> -- New Table & Time -- </option>			
                									<option class = "testclass" value = "131">&nbsp;&nbsp;&nbsp;&nbsp;-- Unassign -- </option>
                                                    {section name=reschedule loop=$Reschedule}
                									<option class = "testclass" value = "{$Reschedule[reschedule].block_id}, {$Reschedule[reschedule].table_num}, {$Reschedule[reschedule].block_time}" >&nbsp;&nbsp;{$Reschedule[reschedule].block_time|date_format:"%l:%M %p"} - #{$Reschedule[reschedule].table_num} ({$Reschedule[reschedule].capacity_max})</option>
                									{/section}
        										</select>
    
    
    
    										</div>                                                                                                                                                                        
                                        </div>
										 <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Table# : &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$time}_Res[reservations].23}" name="actual_table" min="1" placeholder="{${$time}_Res[reservations].23}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Party: &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$time}_Res[reservations].3}" name="party_num" min="1" placeholder="{${$time}_Res[reservations].3}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">GoG: &nbsp;</div>
                                                <div style="display:inline-block; width:40%;"><input type="number" name="gog_num" value="{${$time}_Res[reservations].19}"></div>
                                            </div>
                                        </div>
                                        
                                        {if (${$time}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$time}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                        
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">OOH?: &nbsp;</div>
                                                <input type="hidden" name="out_of_hotel" value="0" />
                                                {if ${$time}_Res[reservations].20 == '1'}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" checked="true" name="out_of_hotel" value="{${$time}_Res[reservations].20}"></div>
                                                {else}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" name="out_of_hotel" value="1"></div>
                                                {/if}
                                            </div>
                                        </div>
                                        
                                        {/if}
                                        
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Food Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="food_requests_og" value="{${$time}_Res[reservations].21}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Special Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="special_requests" value="{${$time}_Res[reservations].4}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;">
											<div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Update Reservation<input value="Submit" name="submit" type="submit"></div>
										</div>
										{if ${$time}_Res[reservations].27 == 1}
										<div class="col-md-12" style="padding-left:0px; padding-right:15px;">
                                        	<button class="btn btn-default sms-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-sms-{${$time}_Res[reservations].6}">SMS</button>
                                        </div>
										{/if}
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<div class="row" style="display:contents;">
                                    	
                                        <form name="modal_options3" action="{$serverself}" method="POST" style="display:contents;">
                                        <div class="col-md-4">
                                        	<input type="hidden" name="res_status" value="{${$time}_Res[reservations].8}">
                                        	<input type="hidden" value="{${$time}_Res[reservations].6}" name="Arrived">                                            
                                            <button type="submit" value="{${$time}_Res[reservations].12}" class="btn btn-default add-button-1">
											
											{if ${$time}_Res[reservations].8 == '1'}
											Arrived
											{else if ${$time}_Res[reservations].8 == '3'}
											Undo Arrived
											{/if}
											
											</button>
                                        </div>
                                        </form>
                                        
                                        <div class="col-md-4">
                                        	<button class="btn btn-default add-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$time}_Res[reservations].6}">+ Res to Table</button>
                                        </div>
                                        
                                         <div class="col-md-4">
                                        	<button class="btn btn-default cancel-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-confirm-{${$time}_Res[reservations].6}">Remove</button>
                                        </div>
                                    </div>
      							</div>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 2 Modal -->
                
                <!-- BEGIN Col-2 Confirm Modal -->
				<div id="modal-confirm-{${$time}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Confirm Cancellation</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;"><span style="font-size:20px; font-weight:600;">Are you sure you want to delete this reservation?</span></div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-6">
										<button type="button" class="btn btn-default arrived-button-1" data-dismiss="modal">Cancel</button>
									</div>
                                    <form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
									<div class="col-md-6">
										<input type="hidden" name="cancel_res_id" value="{${$time}_Res[reservations].6}">
										<input type="hidden" name="table_num" value="{${$time}_Res[reservations].14}">
										<input type="hidden" name="res_time" value="{${$time}_Res[reservations].7}">
										<input type="hidden" name="res_date" value="{$selected_date}">
										<input type="hidden" name="party_num" value="{${$time}_Res[reservations].3}">
										<input type="hidden" name="gog_num" value="{${$time}_Res[reservations].19}">
										<input type="hidden" name="block_id" value="{${$time}_Res[reservations].5}">
										<button type="submit" value="{${$time}_Res[reservations].12}" class="btn btn-default cancel-button-1">Yes, Remove</button>
									</div>
									</form>
								</div>
							</div>
                            <div class="modal-footer" style="height:204px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- BEGIN Col-2 Confirm Modal -->
									
				<!-- BEGIN Col-2 SMS Modal -->
				<div id="modal-sms-{${$time}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Guest Messaging</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">                          
                                	<div class="col-md-12" id="content">
                                	{section name=messages loop=${$time}_SMS}
                                    	<div class="{${$time}_SMS[messages].3}">
											<table style="border-collapse: separate !important;">
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
												<tr><td class="cell"></td><td class="call textcell">{${$time}_SMS[messages].2}</td><td class="cell"></td></tr>
                                                <tr><td class="cell"></td><td class="sms_timestamp">{${$time}_SMS[messages].1}</td><td></td></tr>
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
											</table>
										</div>
                                    {/section}    
                                    </div>                             
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
                                	<form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
                                    <div class="col-md-9" id="sms-send">
										<input type="text" name="guest_message" required>
									</div>
                                    
									<div class="col-md-3">
										<input type="hidden" name="to_phone" value="{${$time}_Res[reservations].26}">
                                        <input type="hidden" name="sms_res_id" value="{${$time}_Res[reservations].6}">
										<button type="submit" value="{${$time}_Res[reservations].12}" class="btn btn-default sms-button-1">Send</button>
									</div>
									</form>
								</div>
							</div>
                        </div>
                    </div>
				</div>
                <!-- END Col-2 SMS Modal -->
                
                <!-- BEGIN Column 2 Duplicate Modal -->
				<div id="modal-duplicate-{${$time}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_duplicate" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="res_time_id" value="{${$time}_Res[reservations].17}">
                                <input type="hidden" name="table_id" value="{${$time}_Res[reservations].18}">
                                <input type="hidden" name="res_date" value="{${$time}_Res[reservations].1}">
								<input type="hidden" name="table_max" value="{${$time}_Res[reservations].16}">
								<input type="hidden" name="res_time" value="{${$time}_Time}">
								<input type="hidden" name="block_id" value="{${$time}_Res[reservations].5}">
								<input type="hidden" name="res_time" value="{${$time}_Res[reservations].7}">
                    			<input type="hidden" name="res_date" value="{$selected_date}">
								<input type="hidden" name="table_num" value="{${$time}_Res[reservations].14}">
								<input type="hidden" name="wasatch" value="{${$time}_Res[reservations].22}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Add additional Reservation to Table #{${$time}_Res[reservations].14} at {${$time}_Time|date_format:"%l:%M %p"}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value> -- Select a Guest -- </option>
                                                                
                                                                {if (${$time}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$time}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
                                                                			
                												{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment8}" data-special="">{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-4 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
														
                                                        {if $enable_table_num == '1'}
                                                        
                                                        <div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                                                        <div class="col-md-3" ><input type="number" name="actual_table"></div>
                    									
                                                        {/if} 
                                                        
													</div>
                									<div class="row">
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Add Reservation<input value="duplicate" name="duplicate" type="submit"></div></div>
														
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>	
									
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
                                <div class="modal-footer" style="height:113px;"></div>
    						</div>

  					</div>
				</div>
				<!-- END Column 2 Duplicate Modal -->  
									
									
                {/section}      
               
				
			</div>
        </div>
        <div class="col-md-12">
        	<div class="row">
				{section name=reservations loop=${$time}_Avail}
				
				{if ${$time}_Avail[reservations].wasatch == '1'}
                	{if enable_table_minimum == '1'}             	
						<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$time}_Avail[reservations].block_id}"><span>(W) ({${$time}_Avail[reservations].capacity_min}-{${$time}_Avail[reservations].capacity_max}) Available</span></button>               
                	{else}                
                    	<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$time}_Avail[reservations].block_id}"><span>(W) ({${$time}_Avail[reservations].capacity_max}) Available</span></button>                  
                    {/if}          
				{else}              
                	{if enable_table_minimum == '1'}                        
						<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$time}_Avail[reservations].block_id}"><span>({${$time}_Avail[reservations].capacity_min}-{${$time}_Avail[reservations].capacity_max}) Available</span></button>                      
					{else}                  
                    	<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$time}_Avail[reservations].block_id}"><span>({${$time}_Avail[reservations].capacity_max}) Available</span></button>                   
                    {/if}               
				{/if}
				
			
             <!-- BEGIN Column 2 Assign Modal -->
				<div id="modal-assign-{${$time}_Avail[reservations].block_id}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="block_id" value="{${$time}_Avail[reservations].block_id}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	{if enable_table_min == '1'}
                                			<div class="col-md-10"><h4>({${$time}_Avail[reservations].capacity_min}-{${$time}_Avail[reservations].capacity_max}) - {${$time}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
                                            {else}
                                            <div class="col-md-10"><h4>({${$time}_Avail[reservations].capacity_max}) - {${$time}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
                                            {/if}
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
								<!--	
									
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;">
                                            
                                    			<select required name="select_res">
        											<option hidden disabled selected value> -- Select a Reservation -- </option>		
                									{section name=assignment loop=${$time}_Assign}
                									<option class = "testclass" value = "{${$time}_Assign[assignment].res_id}" >&nbsp;&nbsp;{${$time}_Assign[assignment].party_name} - {${$time}_Assign[assignment].room_num}</option>
                									{/section}
        										</select>
                                                                                     
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Assign Reservation<input value="assign" name="assign" type="submit"></div></div>
									</div>                                   
      							</div>

								-->
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value data-food=""> -- Select a Guest -- </option>
                            									
                                                                {if (${$time}_Avail[reservations].block_time == '18:00:00' && $res_6pm_ooh == '1') || (${$time}_Avail[reservations].block_time == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
																
																{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment3}" data-special="{$Guestlist[guests].Comment24}" data-affiliation="{$Guestlist[guests].Comment2}" data-phone="{$Guestlist[guests].Phone1}>{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-5 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
                                                        
                                                        {if $enable_table_num == '1'}
                                                        
														<div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                    									<div class="col-md-2" ><input type="number" name="actual_table"></div>
                                                        
                                                        {/if}
                                                        
													</div>
                									<div class="row">
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4" style="vertical-align:top; padding-top:15px; height:53px;">Guest Messaging: </div>
																<div class="col-md-1" style="padding-top:20px; padding-left:8px;"><input type="checkbox" name="sms_opt_in" id="sms_opt_in_{${$time}_Avail[reservations].block_id}"></div>
																<div class="col-md-7" style="display:none;" id="enter_phone_div_{${$time}_Avail[reservations].block_id}"><input type="text" name="guest_phone" id="enter_phone" placeholder="Enter Phone Number" min="10"></div>
															</div>
														</div>
														<div class="col-md-12"><input type="text" name="affiliation" placeholder="Affiliation"></div>
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<input type="hidden" name="block_id" value="{${$time}_Avail[reservations].block_id}">
														<input type="hidden" name="res_time" value="{${$time}_Avail[reservations].block_time}">
                    									<input type="hidden" name="res_date" value="{$selected_date}">
														<input type="hidden" name="table_num" value="{${$time}_Avail[reservations].table_num}">
														<input type="hidden" name="table_max" value="{${$time}_Avail[reservations].capacity_max}">
														<input type="hidden" name="wasatch" value="{${$time}_Avail[reservations].wasatch}">
														<div class="col-md-12"><input value="Submit" name="newsubmit" type="submit" class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1" style="width:100%; border-color:#afafaf"></div>
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>
							
                                </form>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 2 Assign Modal -->  				
						
        		{/section}			
            
            
            </div>
        </div>
    </div>
    
    {/foreach}   
	<!-- END col-2 Reservations -->

</div>
<!-- END 2nd Column -->
















<!-- 3rd Column -->
<div class="col-md-4 layout-column-settings">

	<!-- BEGIN col-3 Reservations -->
    {foreach $col3timesClean as $timethree}
    <div class="row time-group-row-1">
		<div class="col-md-12">
        	<div class="row">			
				
				{if ${$timethree}_Time == '18:01:00'}
				<div class="col-md-9" style="text-align:left;"><span><h3>Overflow</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$timethree}_Total}</h3></span></div>
				{else}
				<div class="col-md-9" style="text-align:left;"><span><h3>{${$timethree}_Time|date_format:"%l:%M %p"}</h3></span></div>         
                <div class="col-md-3" style="text-align:right;"><span><h3>{${$timethree}_Total}</h3></span></div>
				{/if}
				
			</div>
        </div>        
		<div class="col-md-12">
            <div class="row">
				{section name=reservations loop=${$timethree}_Res}

				
			{if ${$timethree}_Res[reservations].24 == '0'}
				
				{if ${$timethree}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$timethree}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timethree}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$timethree}_Res[reservations].13} - {${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        {if ${$timethree}_{${$timethree}_Res[reservations].14}_Table >= ${$timethree}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{/if}
		
					</div>
				</button>	
				
				
				{else if ${$timethree}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$timethree}_Res[reservations].8}"  onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$timethree}_Res[reservations].8}" style="padding-right:0px;"><span>{${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						{if ${$timethree}_{${$timethree}_Res[reservations].14}_Table >= ${$timethree}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{/if}
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$timethree}_Res[reservations].8}" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timethree}_Res[reservations].8}" style="padding-right:0px;"><span> {${$timethree}_Res[reservations].13} - {${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						{if ${$timethree}_{${$timethree}_Res[reservations].14}_Table >= ${$timethree}_Res[reservations].16}
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{else}
						<div class="col-md-2 table-count-status-1" style="text-align:right;"><span>({${$timethree}_{${$timethree}_Res[reservations].14}_Table}/{${$timethree}_Res[reservations].16})</span>
						</div>
						{/if}

					</div>
				</button>
                
                {/if}
	
			{else}
	
				{if ${$timethree}_Res[reservations].22 == '1'}

				<button class="col-md-12 ui-button-augment-1 wasatch-status-{${$timethree}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timethree}_Res[reservations].8}" style="padding-right:0px;"><span>(W) {${$timethree}_Res[reservations].13} - {${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
		
                        <div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
		
					</div>
				</button>	
				
				
				{else if ${$timethree}_Res[reservations].20 == '1'}
	
	
				<button class="col-md-12 ui-button-augment-1 button-status-{${$timethree}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 ooh-res-status-{${$timethree}_Res[reservations].8} style="padding-right:0px;""><span>{${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}            
                        
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>
	
					</div>
				</button>
	
				{else}
				
                <button class="col-md-12 ui-button-augment-1 button-status-{${$timethree}_Res[reservations].8}" style="margin-top:-5px; border-top:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal{${$timethree}_Res[reservations].6}">
                    <div class="row">
                		<div class="col-md-8 res-status-{${$timethree}_Res[reservations].8} style="padding-right:0px;""><span> {${$timethree}_Res[reservations].13} - {${$timethree}_Res[reservations].15|truncate:19}</span></div>
                        
						{if ${$timethree}_Res[reservations].4 == '' && ${$timethree}_Res[reservations].9 == '' && ${$timethree}_Res[reservations].21 == ''}
                        <div class="col-md-1"><img /></img></div>
                        {else if ${$timethree}_Res[reservations].21 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-6.png"></img></div>
						{else if ${$timethree}_Res[reservations].4 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
						{else if ${$timethree}_Res[reservations].9 !== ''}
                        <div class="col-md-1"><img style="height:20px;" src="images/note-1.png"></img></div>
                        {/if}
						
						<div class="col-md-2 table-count-status-2" style="text-align:right;"><span></span>

					</div>
				</button>
                
                {/if}

			{/if}
                 
                 <!--
                 
                 <button class="col-md-2 ui-button-augment-1" style="text-align:center; margin-left:10px; border-color:black; padding-left:5px; padding-right:5px; padding-top:2px; padding-bottom:2px; font-size:20px; max-width:40px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$timethree}_Res[reservations].6}">
                 +
                 </button>
                
                -->
                
                <!-- BEGIN Column 3 Modal -->
				<div id="modal{${$timethree}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	<div class="col-md-2" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$timethree}_Res[reservations].13}" name="room_num" placeholder="{${$timethree}_Res[reservations].13}"><h5></div>
											
											{if ${$timethree}_Res[reservations].25 == ''}
                                			<div class="col-md-8" style="padding-right:0px;"><h5><input type="text" class="modal-edit-1" value="{${$timethree}_Res[reservations].15}" name="party_name" placeholder="{${$timethree}_Res[reservations].15}"><h5></div>
											{else}
											<div class="col-md-8" style="padding-right:0px;">
												<h5><input type="text" class="modal-edit-1" value="{${$timethree}_Res[reservations].15} [{${$timethree}_Res[reservations].25}]" name="party_name_affiliation" placeholder="{${$timethree}_Res[reservations].15} [{${$timethree}_Res[reservations].25}]"><h5>
												<input type="text" name="party_name" value="{${$timethree}_Res[reservations].15}" hidden>
											</div>
											{/if}
                                    		
											<div class="col-md-2" style="padding-left:0px; padding-right:30px;"><h5 class="modal-title"><button style="margin-top:10px !important;" type="button" class="close" data-dismiss="modal">&times;</button></h5></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-6 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Date &amp; Time: &nbsp;</div>
												<div class="modal-div-1" style="font-weight:400;">{$selected_date|date_format:"%m/%d/%Y"}, {${$timethree}_Time|date_format:"%l:%M %p"}</div>
												<input type="hidden" name="change_res_date" value="{$selected_date}">
                                                
												<!-- Old code to be able to change date. Disabled for now.

												<div style="display:inline-block; width:70%;"><input class="form-control" type="date" name="change_res_date" value="{$selected_date}" min="{$current_date}" max="{$date_plus2}" placeholder="{${$timethree}_Res[reservations].1}" data-role="datebox" id="db2" data-datebox-mode="calbox" data-datebox-override-date-format="%Y-%m-%d"></div>

												-->

                                            </div>
										</div>
                                        <div class="col-md-6 modal-formatting-1 change-button-1">
                                            <div style="display:inline-block; width:100%;">
                                        		<input type="hidden" name="res_id" value="{${$timethree}_Res[reservations].6}">
                                                <input type="hidden" name="original_block_id" value="{${$timethree}_Res[reservations].5}">
                                                <input type="hidden" name="original_res_date" value="{$selected_date}">
                                                <input type="hidden" name="original_res_time" value="{${$timethree}_Time}">
												<input type="hidden" name="original_gog_num" value="{${$timethree}_Res[reservations].19}">
												<input type="hidden" name="original_party_num" value="{${$timethree}_Res[reservations].3}">
												<input type="hidden" name="table_num" value="{${$timethree}_Res[reservations].14}">
												<input type="hidden" name="res_time" value="{${$timethree}_Res[reservations].7}">
												<input type="hidden" name="table_max" value="{${$timethree}_Res[reservations].16}">
												<input type="hidden" name="notes" value="">
												<input type="hidden" name="wasatch" value="{${$timethree}_Res[reservations].22}">
												
                                    			
                                                <!--
                                                
                                                <select name="change_res_time">
        											<option hidden selected value="{${$timethree}_Time}">{${$timethree}_Time|date_format:"%l:%M %p"}</option>			
                									{section name=timeblocklayout loop=$Avail_Timeblocks_Layout}
                									<option class = "testclass" value = "{$Avail_Timeblocks_Layout[timeblocklayout].res_time}" >{$Avail_Timeblocks_Layout[timeblocklayout].res_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    
    											-->
    												
    											<select name="change_table_time">
        											<option hidden disabled selected value> -- New Table & Time -- </option>			
                									<option class = "testclass" value = "131">&nbsp;&nbsp;&nbsp;&nbsp;-- Unassign -- </option>
                                                    {section name=reschedule loop=$Reschedule}
                									<option class = "testclass" value = "{$Reschedule[reschedule].block_id}, {$Reschedule[reschedule].table_num}, {$Reschedule[reschedule].block_time}" >&nbsp;&nbsp;{$Reschedule[reschedule].block_time|date_format:"%l:%M %p"} - ({$Reschedule[reschedule].capacity_min} / {$Reschedule[reschedule].capacity_max})</option>
                									{/section}
        										</select>
    
    
    
    										</div>                                                                                                                                                                        
                                        </div>
										 <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Table# : &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$timethree}_Res[reservations].23}" name="actual_table" min="1" placeholder="{${$timethree}_Res[reservations].23}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Party: &nbsp;</div>
                                                <div class="modal-div-2"><input type="number" value="{${$timethree}_Res[reservations].3}" name="party_num" min="1" placeholder="{${$timethree}_Res[reservations].3}"></div>
                                            </div>
										</div>
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">GoG: &nbsp;</div>
                                                <div style="display:inline-block; width:40%;"><input type="number" name="gog_num" value="{${$timethree}_Res[reservations].19}"></div>
                                            </div>
                                        </div>
                                        
                                        {if (${$timethree}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$timethree}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                        
                                        <div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div style="display:inline-block; vertical-align:top; padding-top:15px; font-weight:700;">OOH?: &nbsp;</div>
                                                <input type="hidden" name="out_of_hotel" value="0" />
                                                {if ${$timethree}_Res[reservations].20 == '1'}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" checked="true" name="out_of_hotel" value="{${$timethree}_Res[reservations].20}"></div>
                                                {else}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" name="out_of_hotel" value="1"></div>
                                                {/if}
                                            </div>
                                        </div>
                                        
                                        {/if}
                                        
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Food Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="food_requests_og" value="{${$timethree}_Res[reservations].21}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Special Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="special_requests" value="{${$timethree}_Res[reservations].4}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;">
											<div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Update Reservation<input value="Submit" name="submit" type="submit"></div>
										</div>
										{if ${$timethree}_Res[reservations].27 == 1}
										<div class="col-md-12" style="padding-left:0px; padding-right:15px;">
                                        	<button class="btn btn-default sms-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-sms-{${$timethree}_Res[reservations].6}">SMS</button>
                                        </div>
										{/if}
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<div class="row" style="display:contents;">
                                    	
                                        <form name="modal_options3" action="{$serverself}" method="POST" style="display:contents;">
                                        <div class="col-md-4">
                                        	<input type="hidden" name="res_status" value="{${$timethree}_Res[reservations].8}">
                                        	<input type="hidden" value="{${$timethree}_Res[reservations].6}" name="Arrived">                                            
                                            <button type="submit" value="{${$timethree}_Res[reservations].12}" class="btn btn-default add-button-1">
											
											{if ${$timethree}_Res[reservations].8 == '1'}
											Arrived
											{else if ${$timethree}_Res[reservations].8 == '3'}
											Undo Arrived
											{/if}
											
											</button>
                                        </div>
                                        </form>
                                        
                                        <div class="col-md-4">
                                        	<button class="btn btn-default add-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-duplicate-{${$timethree}_Res[reservations].6}">+ Res to Table</button>
                                        </div>
                                        
                                         <div class="col-md-4">
                                        	<button class="btn btn-default cancel-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-confirm-{${$timethree}_Res[reservations].6}">Remove</button>
                                        </div>
                                    </div>
      							</div>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 3 Modal -->
                
                <!-- BEGIN Col-3 Confirm Modal -->
				<div id="modal-confirm-{${$timethree}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Confirm Cancellation</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;"><span style="font-size:20px; font-weight:600;">Are you sure you want to delete this reservation?</span></div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-6">
										<button type="button" class="btn btn-default arrived-button-1" data-dismiss="modal">Cancel</button>
									</div>
                                    <form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
									<div class="col-md-6">
										<input type="hidden" name="cancel_res_id" value="{${$timethree}_Res[reservations].6}">
										<input type="hidden" name="table_num" value="{${$timethree}_Res[reservations].14}">
										<input type="hidden" name="res_time" value="{${$timethree}_Res[reservations].7}">
										<input type="hidden" name="res_date" value="{$selected_date}">
										<input type="hidden" name="party_num" value="{${$timethree}_Res[reservations].3}">
										<input type="hidden" name="gog_num" value="{${$timethree}_Res[reservations].19}">
										<input type="hidden" name="block_id" value="{${$timethree}_Res[reservations].5}">
										<button type="submit" value="{${$timethree}_Res[reservations].12}" class="btn btn-default cancel-button-1">Yes, Remove</button>
									</div>
									</form>
								</div>
							</div>
                            <div class="modal-footer" style="height:204px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- BEGIN Col-3 Confirm Modal -->
									
				<!-- BEGIN Col-3 SMS Modal -->
				<div id="modal-sms-{${$timethree}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Guest Messaging</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">                          
                                	<div class="col-md-12" id="content">
                                	{section name=messages loop=${$timethree}_SMS}
                                    	<div class="{${$timethree}_SMS[messages].3}">
											<table style="border-collapse: separate !important;">
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
												<tr><td class="cell"></td><td class="call textcell">{${$timethree}_SMS[messages].2}</td><td class="cell"></td></tr>
                                                <tr><td class="cell"></td><td class="sms_timestamp">{${$timethree}_SMS[messages].1}</td><td></td></tr>
												<tr><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>
											</table>
										</div>
                                    {/section}    
                                    </div>                             
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
                                	<form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
                                    <div class="col-md-9" id="sms-send">
										<input type="text" name="guest_message" required>
									</div>
                                    
									<div class="col-md-3">
										<input type="hidden" name="to_phone" value="{${$timethree}_Res[reservations].26}">
                                        <input type="hidden" name="sms_res_id" value="{${$timethree}_Res[reservations].6}">
										<button type="submit" value="{${$timethree}_Res[reservations].12}" class="btn btn-default sms-button-1">Send</button>
									</div>
									</form>
								</div>
							</div>
                        </div>
                    </div>
				</div>
                <!-- END Col-3 SMS Modal -->
                
                <!-- BEGIN Column 3 Duplicate Modal -->
				<div id="modal-duplicate-{${$timethree}_Res[reservations].6}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_duplicate" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="res_time_id" value="{${$timethree}_Res[reservations].17}">
                                <input type="hidden" name="table_id" value="{${$timethree}_Res[reservations].18}">
                                <input type="hidden" name="res_date" value="{${$timethree}_Res[reservations].1}">
								<input type="hidden" name="table_max" value="{${$timethree}_Res[reservations].16}">
								<input type="hidden" name="res_time" value="{${$timethree}_Time}">
								<input type="hidden" name="block_id" value="{${$timethree}_Res[reservations].5}">
								<input type="hidden" name="res_time" value="{${$timethree}_Res[reservations].7}">
                    			<input type="hidden" name="res_date" value="{$selected_date}">
								<input type="hidden" name="table_num" value="{${$timethree}_Res[reservations].14}">
								<input type="hidden" name="wasatch" value="{${$timethree}_Res[reservations].22}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Add additional Reservation to Table #{${$timethree}_Res[reservations].14} at {${$timethree}_Time|date_format:"%l:%M %p"}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value> -- Select a Guest -- </option>
                                                                
                                                                {if (${$timethree}_Res[reservations].7 == '18:00:00' && $res_6pm_ooh == '1') || (${$timethree}_Res[reservations].7 == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
                                                                			
                												{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment8}" data-special="">{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-4 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
														
                                                        {if $enable_table_num == '1'}
                                                        
                                                        <div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                                                        <div class="col-md-3" ><input type="number" name="actual_table"></div>
                    									
                                                        {/if} 
                                                        
													</div>
                									<div class="row">
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1">Add Reservation<input value="duplicate" name="duplicate" type="submit"></div></div>
														
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>	
									
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
                                <div class="modal-footer" style="height:113px;"></div>
    						</div>

  					</div>
				</div>
				<!-- END Column 3 Duplicate Modal -->  
									
									
                {/section}      
               
				
			</div>
        </div>
        <div class="col-md-12">
        	<div class="row">
				{section name=reservations loop=${$timethree}_Avail}
				
				{if ${$timethree}_Avail[reservations].wasatch == '1'}
                	{if enable_table_minimum == '1'}
						<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timethree}_Avail[reservations].block_id}"><span>(W) ({${$timethree}_Avail[reservations].capacity_min}-{${$timethree}_Avail[reservations].capacity_max}) Available</span></button>
					{else}
                    	<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-4" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timethree}_Avail[reservations].block_id}"><span>(W) ({${$timethree}_Avail[reservations].capacity_max}) Available</span></button>
                    {/if}
				{else}
                	{if enable_table_minimum == '1'}
						<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timethree}_Avail[reservations].block_id}"><span>({${$timethree}_Avail[reservations].capacity_min}-{${$timethree}_Avail[reservations].capacity_max}) Available</span></button>
					{else}
                    	<button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 ui-button-augment-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-assign-{${$timethree}_Avail[reservations].block_id}"><span>({${$timethree}_Avail[reservations].capacity_max}) Available</span></button>
                    {/if}
				{/if}
				
			
             <!-- BEGIN Column 3 Assign Modal -->
				<div id="modal-assign-{${$timethree}_Avail[reservations].block_id}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="block_id" value="{${$timethree}_Avail[reservations].block_id}">
								<input type="hidden" name="notes" value="">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                        	{if enable_table_min == '1'}
                                				<div class="col-md-10"><h4>({${$timethree}_Avail[reservations].capacity_min}-{${$timethree}_Avail[reservations].capacity_max}) - {${$timethree}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
											{else}
                                            	<div class="col-md-10"><h4>({${$timethree}_Avail[reservations].capacity_max}) - {${$timethree}_Avail[reservations].block_time|date_format:"%l:%M %p"}, {$selected_date|date_format:"%m/%d/%Y"}<h4></div>
                                            {/if}
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
									
								<!--	
									
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;">
                                            
                                    			<select required name="select_res">
        											<option hidden disabled selected value> -- Select a Reservation -- </option>		
                									{section name=assignment loop=${$timethree}_Assign}
                									<option class = "testclass" value = "{${$timethree}_Assign[assignment].res_id}" >&nbsp;&nbsp;{${$timethree}_Assign[assignment].party_name} - {${$timethree}_Assign[assignment].room_num}</option>
                									{/section}
        										</select>
                                                                                     
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Assign Reservation<input value="assign" name="assign" type="submit"></div></div>
									</div>                                   
      							</div>

								-->
									
								<div class="modal-body">
									<div class="row modal-formatting-2">
										<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;">
											<div class="row">
            									<form name="new_reservation" action="{$serverself}" method="POST" style="display:contents;">
            									<div class="col-md-12">
                									<div class="row">
                    									<div class="col-md-12">
        													<select name="res_num" class="guestselectclasses" required>
        														<option hidden disabled selected value data-food=""> -- Select a Guest -- </option>
                            									
                                                                {if (${$timethree}_Avail[reservations].block_time == '18:00:00' && $res_6pm_ooh == '1') || (${$timethree}_Avail[reservations].block_time == '20:00:00' && $res_8pm_ooh == '1')}
                                                                <option value="ooh_yes"> -- Out Of Hotel Guest -- </option>	
                                                                {/if}
																
																{section name=guests loop=$Guestlist}
                												<option class="option-formatting" value="{$Guestlist[guests].ResNumNumeric}, {$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4}" data-food="{$Guestlist[guests].Comment3}" data-special="{$Guestlist[guests].Comment24}" data-affiliation="{$Guestlist[guests].Comment2}" data-phone="{$Guestlist[guests].Phone1}>{$Guestlist[guests].RoomNum} - {$Guestlist[guests].GuestName} ({$Guestlist[guests].People1 + $Guestlist[guests].People2 + $Guestlist[guests].People3 + $Guestlist[guests].People4})
																
																{if $Guestlist[guests].Comment7 == ''}
																</option>
																{else}
																 [{$Guestlist[guests].Comment7}]
																</option>
																{/if}
															
                												{/section}
																
        													</select>
														</div>
                									</div>
                
                									<div class="row ooh_row">
														<div class="col-md-6"><input type="text" name="ooh_party_name" placeholder="OOH Party Name" required></div>
                    									<div class="col-md-6"><input type="number" name="ooh_party_num" min="1" placeholder="OOH Party Size" required></div>
													</div>      
                									<div class="row">
														<div class="col-md-5 gog_row" style="vertical-align:top; padding-top:15px;">Guests of Guests: </div>
                    									<div class="col-md-2 gog_row"><input type="number" name="gog_num" value="0"></div>
                                                        
                                                        {if $enable_table_num == '1'}
                                                        
														<div class="col-md-3" style="vertical-align:top; padding-top:15px;">Table #: </div>
                    									<div class="col-md-2" ><input type="number" name="actual_table"></div>
                                                        
                                                        {/if}
                                                        
													</div>
                									<div class="row">
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4" style="vertical-align:top; padding-top:15px; height:53px;">Guest Messaging: </div>
																<div class="col-md-1" style="padding-top:20px; padding-left:8px;"><input type="checkbox" name="sms_opt_in" id="sms_opt_in_{${$timethree}_Avail[reservations].block_id}"></div>
																<div class="col-md-7" style="display:none;" id="enter_phone_div_{${$timethree}_Avail[reservations].block_id}"><input type="text" name="guest_phone" id="enter_phone" placeholder="Enter Phone Number" min="10"></div>
															</div>
														</div>
														<div class="col-md-12"><input type="text" name="affiliation" placeholder="Affiliation"></div>
                										<div class="col-md-12"><input type="text" name="food_requests" placeholder="Food Requests"></div>
                										<div class="col-md-12"><input type="text" name="special_requests" placeholder="Special Requests"></div>
														<input type="hidden" name="block_id" value="{${$timethree}_Avail[reservations].block_id}">
														<input type="hidden" name="res_time" value="{${$timethree}_Avail[reservations].block_time}">
                    									<input type="hidden" name="res_date" value="{$selected_date}">
														<input type="hidden" name="table_num" value="{${$timethree}_Avail[reservations].table_num}">
														<input type="hidden" name="table_max" value="{${$timethree}_Avail[reservations].capacity_max}">
														<input type="hidden" name="wasatch" value="{${$timethree}_Avail[reservations].wasatch}">
														<div class="col-md-12"><input value="Submit" name="newsubmit" type="submit" class="ui-btn ui-input-btn ui-corner-all ui-shadow arrived-button-1" style="width:100%; border-color:#afafaf"></div>
                									</div>
												</div>
            									</form>
											</div>
										</div>
									</div>	
								</div>
							
                                </form>
      							<div class="modal-footer" style="display:none;">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Column 3 Assign Modal -->  				
						
        		{/section}			
            
            
            </div>
        </div>
    </div>
    
    {/foreach}   
	<!-- END col-3 Reservations -->

</div>
<!-- END 3rd Column -->















</div>
</body>
</html>