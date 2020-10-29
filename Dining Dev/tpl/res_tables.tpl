<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reservations: Reservation Tables</title>

<!--	
    <meta name="viewport" content="width=device-width, initial-scale=1">
-->
    
    <meta name="apple-mobile-web-app-capable" content="yes">
    
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
	  $(document).bind("mobileinit", function () { $.mobile.ajaxEnabled = false; });
	</script>
<!--
	<script type="text/javascript">
  		//reset type=date inputs to text
  		$( document ).bind( "mobileinit", function(){
    		$.mobile.page.prototype.options.degradeInputs.date = true;
  		});	
	</script>
-->  
	
	<script src="lib/js/scripts.js"></script>
    <script src="lib/js/custom.js"></script>
	
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jtsage-datebox-jqm@5.1.3/jtsage-datebox.min.js" type="text/javascript"></script>
<!--    
	<script src="https://demos.jquerymobile.com/1.0a4.1/experiments/ui-datepicker/jQuery.ui.datepicker.js"></script>
  	<script src="https://demos.jquerymobile.com/1.0a4.1/experiments/ui-datepicker/jquery.ui.datepicker.mobile.js"></script>
-->    
    
<!--    
	<script src="/Portal/Reservations/lib/js/auto_refresh.js"></script>
    <script src="/Portal/Reservations/lib/js/return_refresh.js"></script>
-->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />    
<!--    
    <link rel="stylesheet" href="jquery.ui.datepicker.mobile.css" /> 
-->    
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName} perms_res_newUser = {$perms_res_newUser} perms_res_deleteUser = {$perms_res_deleteUser} perms_res_timeslots = {$perms_res_timeslots} perms_res_tables = {$perms_res_tables}}

{include file="header.tpl" theme_color = {$theme_color} full_url = {$full_url} res_tables_class="active" House_Adults_Total = {$House_Adults_Total} House_Children_Total = {$House_Children_Total} House_Party_Total = {$House_Party_Total} Lodge_Adults_Total = {$Lodge_Adults_Total} Lodge_Children_Total = {$Lodge_Children_Total} selected_date = {$selected_date} serverself = {$serverself} RDP_Guest_Total = {$RDP_Guest_Total}}

<div class="row" style="margin-right:0px !important; margin-left:0px !important; display:flow-root;">
	<div class="col-md-3 modal-formatting-1 change-button-1" style="float:right;"> 
    <form action="" method="post">
    <input type="hidden" name="changemeal" value="changemeal">	   
        <select onchange="this.form.submit()" name="new_meal">
        	{if $current_meal == 'breakfast'}
            <option selected class = "testclass" value = "breakfast" >Breakfast</option>
            <option class = "testclass" value = "lunch" >Lunch</option>
            <option class = "testclass" value = "dinner" >Dinner</option>
            {elseif $current_meal == 'lunch'}
            <option class = "testclass" value = "breakfast" >Breakfast</option>
            <option selected class = "testclass" value = "lunch" >Lunch</option>
            <option class = "testclass" value = "dinner" >Dinner</option>
            {elseif $current_meal == 'dinner'}
            <option class = "testclass" value = "breakfast" >Breakfast</option>
            <option class = "testclass" value = "lunch" >Lunch</option>
            <option selected class = "testclass" value = "dinner" >Dinner</option>
            {/if}
    	</select>
	</form>                                                                                                          
    </div>
</div>

<div class="row" style="margin-right:0px !important; margin-left:0px !important;">	
	
{foreach $NumRows as $numrow}
<div class="col-sm-8ths col-md-8ths">
	<div class="col-md-12 layout-column-settings" style="margin-right:auto; margin-left:auto; float:left; padding-left:0px !important; padding-right:0px !important;">
		<div class="row-TEST">
        
        <button class="col-sm-10 col-md-10 ui-button-augment-1" style="text-align:center; margin-left:auto; margin-right:auto; margin-top:10px; margin-bottom:30px; border-color:black; padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:5px; font-size:15px; float:none;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-addtable-{$numrow}">+ Add Table </button>
			
        
        {foreach ${$numrow}_RowColumns as $rowcolumns}
        
        
         <!-- BEGIN Add Table Modal -->
				<div id="modal-addtable-{$numrow}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_activate" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-sm-12 col-md-12">
                                    	<div class="row">
                                			<div class="col-sm-10 col-md-10"><h4>Activate table on row # {$numrow}<h4></div>
                                    		<div class="col-sm-2 col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
                              
                                
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-sm-8 col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;">
                                            
                                    			<select required name="table_id">
        											<option hidden disabled selected value> -- Select a Table -- </option>		
                									{section name=activatetable loop=${$numrow}_Activate}
                									<option class = "testclass" value = "{${$numrow}_Activate[activatetable].table_id}" >&nbsp;&nbsp;Table #{${$numrow}_Activate[activatetable].table_num} ({${$numrow}_Activate[activatetable].capacity_min} / {${$numrow}_Activate[activatetable].capacity_max})</option>
                									{/section}
        										</select>
    										                                                                                                                              
                                        </div>
                                        <div class="col-sm-12 col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Activate Table <input value="activate" name="activate" type="submit"></div></div>
									</div>                                   
      							</div>
                                </form>
      							
                                
                                <div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Add Table Modal -->  
        
        
        
        	 
        
		
    		<!-- <div class="col-md-2" style="float:right;"> -->
            <div class="col-sm-12 col-md-12" style="float:right; padding-left:0px; padding-right:0px; padding-bottom:20px; min-height: 225px;">
            	<div class="row-TEST">
                	<div class="col-sm-12 col-md-12" style="text-align:left; float:right; padding-right:3px; padding-left:3px;">
                    <form name="change_table" action="{$serverself}" method="POST" style="display:contents;">
                    
                    {if ${$numrow}_{$rowcolumns}_TableInfo.7 == '0'}
                    
                    	{if ${$numrow}_{$rowcolumns}_TableInfo.8 == ''}
                    
                    		<button class="col-sm-12 col-md-12 ui-button-augment-1 button-status-1 ui-btn ui-shadow ui-corner-all" style="text-align:center; padding-top:6px; padding-bottom:6px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-table{${$numrow}_{$rowcolumns}_TableInfo.0}">#{${$numrow}_{$rowcolumns}_TableInfo.3} ({${$numrow}_{$rowcolumns}_TableInfo.5})</button>
                    
                    	{else}
                        
                        	<button class="col-sm-12 col-md-12 ui-button-augment-1 button-status-1 ui-btn ui-shadow ui-corner-all" style="text-align:center; padding-top:6px; padding-bottom:6px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-table{${$numrow}_{$rowcolumns}_TableInfo.0}">{${$numrow}_{$rowcolumns}_TableInfo.8} ({${$numrow}_{$rowcolumns}_TableInfo.5})</button>
                        
                        {/if}
                    
                    {else}
                    
                    	{if ${$numrow}_{$rowcolumns}_TableInfo.8 == ''}
                    
                    		<button class="col-sm-12 col-md-12 ui-button-augment-1 button-status-4 ui-btn ui-shadow ui-corner-all" style="text-align:center; padding-top:6px; padding-bottom:6px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-table{${$numrow}_{$rowcolumns}_TableInfo.0}">W #{${$numrow}_{$rowcolumns}_TableInfo.3} ({${$numrow}_{$rowcolumns}_TableInfo.5})</button>
                            
						{else}
                        
                        <button class="col-sm-12 col-md-12 ui-button-augment-1 button-status-4 ui-btn ui-shadow ui-corner-all" style="text-align:center; padding-top:6px; padding-bottom:6px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-table{${$numrow}_{$rowcolumns}_TableInfo.0}">{${$numrow}_{$rowcolumns}_TableInfo.8} ({${$numrow}_{$rowcolumns}_TableInfo.5})</button>
                        
                        {/if}
                    
                    {/if}
                    
                    </form>
                    </div>
                    
                    
                    
                    
                    
                    <!-- BEGIN Table Info Modal -->
				<div id="modal-table{${$numrow}_{$rowcolumns}_TableInfo.0}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_options" action="{$serverself}" method="POST" style="display:contents;">
                                <input type="hidden" name="table_id" value="{${$numrow}_{$rowcolumns}_TableInfo.0}">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			
                                            {if ${$numrow}_{$rowcolumns}_TableInfo.7 == '0'}
                                            
                                            	{if ${$numrow}_{$rowcolumns}_TableInfo.8 == ''}
                                            
                                                    <div class="col-sm-10 col-md-10"><h4>Table #{${$numrow}_{$rowcolumns}_TableInfo.3} ({${$numrow}_{$rowcolumns}_TableInfo.5})<h4></div>
                                                    <div class="col-sm-2 col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
												
                                                {else}
                                                
                                                	<div class="col-sm-10 col-md-10"><h4>{${$numrow}_{$rowcolumns}_TableInfo.8} ({${$numrow}_{$rowcolumns}_TableInfo.5})<h4></div>
                                                    <div class="col-sm-2 col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
                                                
                                                {/if}
                                            
                                            {else}
                                            
                                            	{if ${$numrow}_{$rowcolumns}_TableInfo.8 == ''}
                                            
                                                    <div class="col-sm-10 col-md-10"><h4>Wasatch Table #{${$numrow}_{$rowcolumns}_TableInfo.3} ({${$numrow}_{$rowcolumns}_TableInfo.5})<h4></div>
                                                    <div class="col-sm-2 col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
                                                    
												{else}
                                                
                                                	<div class="col-sm-10 col-md-10"><h4>Wa{${$numrow}_{$rowcolumns}_TableInfo.8} ({${$numrow}_{$rowcolumns}_TableInfo.5})<h4></div>
                                                    <div class="col-sm-2 col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
                                                
                                                {/if}
                                            
                                            {/if}
										
                                        
                                        </div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                    
                                    <!--
                                        <div class="col-md-4 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Min Capacity: &nbsp;</div>
                                                <div style="display:inline-block; width:20%;"><input type="text" name="capacity_min" value="{${$numrow}_{$rowcolumns}_TableInfo.4}" min="1" max="10" placeholder="{${$numrow}_{$rowcolumns}_TableInfo.4}"></div>
                                            </div>
										</div>
                                        
                                     -->
                                     	<div class="col-md-4 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Table Name: &nbsp;</div>
                                                <div style="display:inline-block;"><input type="text" name="table_name" value="{${$numrow}_{$rowcolumns}_TableInfo.8}" placeholder="{${$numrow}_{$rowcolumns}_TableInfo.8}"></div>
                                            </div>
										</div>
                                       <div class="col-md-4 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Max Capacity: &nbsp;</div>
                                                <div style="display:inline-block; width:20%;"><input type="text" name="capacity_max" value="{${$numrow}_{$rowcolumns}_TableInfo.5}" min="1" max="10" placeholder="{${$numrow}_{$rowcolumns}_TableInfo.5}"></div>
                                            </div>
										</div>
										<div class="col-md-3 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Overflow? &nbsp;</div>
                                                <input type="hidden" name="wasatch" value="0" />
                                                {if ${$numrow}_{$rowcolumns}_TableInfo.7 == '1'}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" checked="true" name="wasatch" value="{${$numrow}_{$rowcolumns}_TableInfo.7}"></div>
                                                {else}
                                                <div style="display:inline-block; vertical-align:top; padding-top:19px; width:40%;"><input type="checkbox" name="wasatch" value="1"></div>
                                                {/if}
                                            </div>
										</div>
                                        
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Update Table Info<input value="updatetable" name="updatetable" type="submit"></div></div>
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<div class="row" style="display:contents;">
                                        <form name="modal_options3" action="{$serverself}" method="POST" style="display:contents;">
                                        <input type="text" name="capacity_min" value="{${$numrow}_{$rowcolumns}_TableInfo.4}" min="1" max="10" hidden>
                                        <input type="hidden" name="table_id" value="{${$numrow}_{$rowcolumns}_TableInfo.0}">
                                        <div class="col-md-12">
                                        	<button type="submit" value="deactivatetable" name="deactivatetable" class="btn btn-default cancel-button-1">Deactivate Table</button>
                                        </div>
                                        </form>
                                    </div>
      							</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
					<!-- END Table Info Modal -->
                    
                    
                    
                    
                    <!-- BEGIN Add Timeblock Modal -->
				<div id="modal-addtimeblock-{$numrow}_{$rowcolumns}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_addtime" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	<div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Add Time to Table #{${$numrow}_{$rowcolumns}_TableInfo.3}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;"> 
                                        		<input type="hidden" name="table_id" value="{${$numrow}_{$rowcolumns}_TableInfo.0}"> 
                                    			
<!-- This is the old code
                                                
                                                <select required name="block_id">
        											<option hidden disabled selected value> -- Select a Time -- </option>		
                									{section name=addtimeblock loop=${$numrow}_{$rowcolumns}_Addtimeblock}
                									<option class = "testclass" value = "{${$numrow}_{$rowcolumns}_Addtimeblock[addtimeblock].block_id}" >&nbsp;&nbsp; {${$numrow}_{$rowcolumns}_Addtimeblock[addtimeblock].block_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    
-->

												<select required name="res_time_id">
        											<option hidden disabled selected value> -- Select a Time -- </option>		
                									{section name=restimes loop=$ResTimes}
                									<option class = "testclass" value = "{$ResTimes[restimes].res_time_id}" >&nbsp;&nbsp; {$ResTimes[restimes].res_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    
                                                                                                                              
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Add Time<input value="addtime" name="addtime" type="submit"></div></div>
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
				<!-- END Add Timeblock Modal -->
                    
                    
                    
                    
                    
                    
                    <!-- BEGIN Timeblocks -->
                    
                    {foreach ${$numrow}_{$rowcolumns}_ColumnTimeblocks as $columnblocks}
                    
                    <div class="col-md-12 res-status-{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.8}" style="float:right; padding-right:3px; padding-left:3px;">
                	<form name="change_tabletimes" action="{$serverself}" method="POST" style="display:contents;">
					<input type="hidden" name="block_id" value="{${$timeone}_Active[reservations].block_id}">
                	<input type="hidden" name="change_timeslot" value="{${$timeone}_Active[reservations].active}">
                	
                    {if ${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.2 == ''}  
                    
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 timeslot-status-2" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-timeblock{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}" style="font-size:14px;">{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.9|date_format:"%l:%M%p"}
                    </button>
                    
                    {elseif ${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.4 >= ${$numrow}_{$rowcolumns}_TableInfo.5}
                    
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 timeslot-status-0" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-timeblock{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}" style="font-size:14px;">{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.9|date_format:"%l:%M%p"}
                    </button>
                    
                    {else}
                    
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 timeslot-status-3" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-timeblock{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}" style="font-size:14px;">{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.9|date_format:"%l:%M%p"}
                    </button>
                    
                    {/if}					
					
                    </form> 
                	</div>
                    
                    
                    
                    
                    
                    <!-- BEGIN Timeblock Modal -->
				<div id="modal-timeblock{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}" class="modal fade" role="dialog">
  					<div class="modal-dialog">

    					<!-- Modal content-->
    						<div class="modal-content">
                            	<form name="modal_tables_update_timeblocks" action="{$serverself}" method="POST" style="display:contents;">
                                <div class="modal-header">
      							<div class="row" style="display:contents;">
                                	
                                    {if ${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.2 == ''}
                                    
                                    <div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Tbl #{${$numrow}_{$rowcolumns}_TableInfo.3} - {${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.1|date_format:"%l:%M %p"}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
                                <div class="modal-body">
                                    <div class="row modal-formatting-2">
                                        <div class="col-md-8 modal-formatting-1 change-button-1" style="margin-left:auto; margin-right:auto; float:none;"> 
                                        <input type="hidden" name="block_id" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}">	   
                                    			<select required name="schedule_res_id">
        											<option hidden disabled selected value> -- Select a Reservation -- </option>	
                									{section name=scheduleres loop=${$numrow}_{$rowcolumns}_{$columnblocks}_Availres}
                									<option class = "testclass" value = "{${$numrow}_{$rowcolumns}_{$columnblocks}_Availres[scheduleres].res_id}" >&nbsp;&nbsp; {${$numrow}_{$rowcolumns}_{$columnblocks}_Availres[scheduleres].party_name} ({${$numrow}_{$rowcolumns}_{$columnblocks}_Availres[scheduleres].party_num})</option>
                									{/section}
        										</select>                                                                                                                          
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Schedule Reservation<input value="scheduleres" name="scheduleres" type="submit"></div></div>
									</div>                                   
      							</div>
                                </form>
								<div class="modal-footer">
        							<div class="row" style="display:contents;">
                                        <form name="modal_options4" action="{$serverself}" method="POST" style="display:contents;">
                                        <input type="hidden" name="block_id" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}">
                                        <div class="col-md-12">
                                        	<button type="submit" value="deactivatetime" name="deactivatetime" class="btn btn-default cancel-button-1">Deactivate Time</button>
                                        </div>
                                        </form>
                                    </div>
      							</div>
                                    
                                    {else}
                                    
                                    <div class="col-md-12">
                                    	<div class="row">
                                			<div class="col-md-10"><h4>Tbl #{${$numrow}_{$rowcolumns}_TableInfo.3} - {${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.1|date_format:"%l:%M %p"} - {${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.2} ({${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.4}) - {${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.3}<h4></div>
                                    		<div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
										</div>
									</div>
								</div> 
                                </div>
      							<div class="modal-body">
                                    <div class="row modal-formatting-2"> 
                                        <div class="col-md-8 modal-formatting-1 change-button-1">
                                            <div style="display:inline-block; width:100%;">
                                                <input type="hidden" name="original_block_id" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.0}">
                                                <input type="hidden" name="res_id" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.7}">
                                    			<select name="change_block_id">
        											<option hidden disabled selected value> -- New Table & Time -- </option>			
                									<option class = "testclass" value = "131">&nbsp;&nbsp;&nbsp;&nbsp;-- Unassign -- </option>
                                                    {section name=reschedule loop=$Reschedule}
                									<option class = "testclass" value = "{$Reschedule[reschedule].block_id}" >&nbsp;&nbsp;Tbl #{$Reschedule[reschedule].table_num} - {$Reschedule[reschedule].block_time|date_format:"%l:%M %p"}</option>
                									{/section}
        										</select>
    										</div>
                                        </div>
                                        <div class="col-md-4 modal-formatting-1">
                                        	<div class="row">
                                            	<div class="modal-div-1">Date: &nbsp;</div>
                                                <div class="modal-div-1" style="display:inline-block; width:70%; font-weight:400;">{$current_date}</div>
                                            </div>
										</div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Notes: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="notes" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.6}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        	<div class="row">
                                            	<div class="modal-div-1" style="width:25%">Special Requests: &nbsp;</div>
                                                <div style="display:inline-block; width:70%"><input type="text" name="specialrequests" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.5}"></div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="padding-left:0px; padding-right:15px;"><div class="ui-btn ui-input-btn ui-corner-all ui-shadow">Update Reservation<input value="updateres" name="updateres" type="submit"></div></div>
									</div>                                   
      							</div>
                                </form>
                                <div class="modal-footer">
        							<div class="row" style="display:contents;">
                                    	
                                        <form name="modal_options3" action="{$serverself}" method="POST" style="display:contents;">
                                        <div class="col-md-6">
                                        	<input type="hidden" name="res_status" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.8}">
                                        	<input type="hidden" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.7}" name="Arrived">                                            
                                            <button type="submit" value="submit" class="btn btn-default arrived-button-1">Arrived</button>
                                        </div>
                                        </form>
                                        <div class="col-md-6">
                                        	<button class="btn btn-default cancel-button-1" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-confirm-{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.7}">Remove</button>
                                        </div>
                                    </div>
      							</div>
                                
                                {/if}
                                
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      							</div>
    						</div>

  					</div>
				</div>
					<!-- END Timeblock Modal -->
                    
                    
                   
                   
                   <!-- BEGIN Cancel Confirm Modal -->
				<div id="modal-confirm-{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.7}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-7"><h4 class="modal-title">Confirm Cancellation</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-md-10" style="margin-left:auto; margin-right:auto; float:none;"><span style="font-size:20px; font-weight:600;">Are you sure you want to remove this reservation?</span></div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-6">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									</div>
                                    <form name="modal_options_2" action="{$serverself}" method="POST" style="display:contents;">
									<div class="col-md-6">
										<input type="hidden" name="cancel_res_id" value="{${$numrow}_{$rowcolumns}_{$columnblocks}_TableTimeblock.7}">
										<button type="submit" value="submit" class="btn btn-default cancel-button-1">Yes, Remove</button>
									</div>
									</form>
								</div>
							</div>
                            <div class="modal-footer" style="height:304px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- BEGIN Cancel Confirm Modal -->
                    
                    
                    
                    
                    {/foreach}
                    
                    <button class="col-md-12 ui-btn ui-input-btn ui-corner-all ui-shadow ui-button-augment-1 timeslot-status-1" style="max-width:84%; margin-left:15px; top:5px; text-align:center;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-addtimeblock-{$numrow}_{$rowcolumns}">+ Time</button>	 
                    
                    <!-- END Timeblocks -->
                    
                </div>
            </div>  
		{/foreach}
					
		</div>
	</div>
</div>
{/foreach} 



</div>

</body>
</html>