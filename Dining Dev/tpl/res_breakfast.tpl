<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reservations: New Reservation</title>
	
<!--    
    <meta name="viewport" content="width=device-width, initial-scale=1">
-->	
	
    <meta name="apple-mobile-web-app-capable" content="yes">
    
	    <!-- Old Jquery CDN implementation -->
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
    
    <!-- Old CDN implementation of boostrap js -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
    <script src="lib/js/bootstrap.min.js"></script>
    
    <!--Plugin JavaScript file-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script> -->
    
    <!-- Old CDN implementation of datatables js -->
    <!-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->
    <script src="lib/js/jquery.dataTables.min.js"></script>
    
    <script src="lib/js/field_selection_1.js"></script>
    <script src="lib/js/datatables.js"></script>
	<script src="lib/js/scripts.js"></script>
    <script src="lib/js/breakfast_count.js"></script>
    <!-- <script src="/Portal/Reservations/lib/js/age_slider_1.js"></script> -->
    <!-- <script src="/Portal/Reservations/lib/js/auto_refresh.js"></script> -->
    <!-- <script src="/Portal/Reservations/lib/js/return_refresh.js"></script>  ->
	
    <!--Plugin CSS file with desired skin-->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css"/> -->
    
    <!-- Old CDN implementation of datatables css -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/> -->
    <link rel="stylesheet" href="lib/css/jquery.dataTables.min.css" />

    <!-- Old CDN implementation of jquery mobile css -->
	<!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" /> --> 
    <link rel="stylesheet" href="lib/css/jquery.mobile-1.4.5.min.css" />
    
    <link rel="stylesheet" href="lib/css/Reservations.css" />
    
    <!-- Old CDN implementation of bootstrap css -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css" />
    
</head>

<body>

{include file="menu.tpl" theme_logo = {$theme_logo} theme_color = {$theme_color} full_url = {$full_url} username = {$username} firstName = {$user_firstName} perms_res_newUser = {$perms_res_newUser} perms_res_deleteUser = {$perms_res_deleteUser} perms_res_timeslots = {$perms_res_timeslots} perms_res_tables = {$perms_res_tables}}

{include file="header.tpl" theme_color = {$theme_color} full_url = {$full_url} res_breakfast_class="active" House_Party_Total = {$House_Party_Total} Lodge_Adults_Total = {$Lodge_Adults_Total} Lodge_Children_Total = {$Lodge_Children_Total} tab_1_name = {$tab_1_name} tab_2_name = {$tab_2_name} tab_3_name = {$tab_3_name} tab_4_name = {$tab_4_name} tab_5_name = {$tab_5_name} tab_6_name = {$tab_6_name}}

<div class="row" style="padding:20px; padding-bottom:500px;">

	<div class="col-md-6" style="margin-left:auto; margin-right:auto; float:none;">
		<div class="row">
        	<div class="col-md-12">
				<div class="row">
					<div class="col-md-6"><h3 style="padding-top:20px;">Breakfast: </h3></div>
					
                    <div class="col-md-6"><button style="border: 1px solid black; border-radius: 10px; box-shadow: 0 1px 10px rgba(0,0,0,.15); margin-left:10px; padding: 10px; padding-bottom:0px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-breakfast"><h3 style="font-size:40px; text-align:center;">{$Breakfast_Total}</h3></button></div>
                    
                    <!-- <div class="col-md-6"><h3 style="font-size:40px; border: 1px solid black; padding: 10px; text-align:center; border-radius: 10px; box-shadow: 0 1px 10px rgba(0,0,0,.15); margin-left:10px;">{$Breakfast_Total}</h3></div> -->
                    
                    <!-- <div class="col-md-6"><h3 style="font-size:40px; border: 1px solid black; padding: 10px; text-align:center; border-radius: 10px; box-shadow: 0 1px 10px rgba(0,0,0,.15); margin-left:10px;">{$Breakfast_Total - $Breakfast_Arrived}</h3></div> -->
					<!-- <div class="col-md-3" style="padding-top:23px;">Remaining</div> -->
				</div>
			</div>
            
            <div class="col-md-12" style="padding-top:20px; padding-left:15px; padding-right:15px;">
            <p style="text-align:center;;">Select a guest name & the number of their party to subtract it from the breakfast guest total above.</p>
            </div>
		</div>
	</div>
    
	<div class="col-md-11" style="margin-top:20px; border-top: 1px solid #b7b7b7; border-right: 1px solid #b7b7b7; border-left: 1px solid #b7b7b7; margin-left:auto; margin-right:auto; float:none;">
        <div class="row">
        	<div class="col-md-12"><h3 style="padding-top:20px; padding-bottom:20px; text-align:center;">Guest List</h3></div>
            <div class="col-md-12">
            	<table id="guest_list" class="display">
                	<thead>
                    	<tr>
                        	<th style="width:10px !important; text-align:center;">Room</th>
                            <th style="text-align:center;">N/R</th>
                            <th style="text-align:center;">Guest Name</th>
                            <th style="text-align:center;">Spouse</th>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Arrival</th>
                            <th style="width:10px !important; text-align:center;">Food</th>
                            <th style="text-align:center;">Affiliation</th>
                        </tr>
                    </thead>
                    <tbody>
                    	 {section name=breakfastreservations loop=$Breakfast_Reservations}
                        
                        {if $Breakfast_Reservations[breakfastreservations].MarketCode == 'N'}
                        <tr style="background-color:#ffffe0;">
                        {else}
                        <tr>
                        {/if}
                        	<td style="text-align:center;">{$Breakfast_Reservations[breakfastreservations].RoomNum}</td>
                            <td style="text-align:center;">{$Breakfast_Reservations[breakfastreservations].MarketCode}</td>
                            <td><button class="ui-button-augment-1 button-status-1 ui-btn ui-shadow ui-corner-all" style="padding-left:10px;" onclick="javascript: return false;" data-toggle="modal" data-target="#modal-{$Breakfast_Reservations[breakfastreservations].ResNumNumeric}">{$Breakfast_Reservations[breakfastreservations].GuestName}</button></td>
                            <td style="text-align:center; font-size:13px;"><span>{$Breakfast_Reservations[breakfastreservations].Comment2}</span><br><span>{$Breakfast_Reservations[breakfastreservations].Comment5}</span></td>
                            <td style="font-size:20px; font-weight:600; text-align:center;">{$Breakfast_Reservations[breakfastreservations].People1 + $Breakfast_Reservations[breakfastreservations].People2 + $Breakfast_Reservations[breakfastreservations].People3 + $Breakfast_Reservations[breakfastreservations].People4 - $Breakfast_Reservations[breakfastreservations].res_party_num}</td> 
                            <td style="text-align:center;"><span style="color:red; font-weight:600;">{$Breakfast_Reservations[breakfastreservations].ResStatus}</span><br><span>{$Breakfast_Reservations[breakfastreservations].Comment21}</span></td>
                            {if $Breakfast_Reservations[breakfastreservations].Comment8 == 'Food Allergens'}
                            <td style="text-align:center;"><img alt="food allergies" style="height:20px;" src="/images/note-6.png" ></td>
                            {else}
                            <td></td>
                            {/if}    
                            <td>{$Breakfast_Reservations[breakfastreservations].Comment7}</td>
						</tr>
                        {/section}
                    </tbody>
				</table>
            </div>            
        </div>	
	</div>
</div>


				 <!-- BEGIN Breakfast Count Modal -->
				<div id="modal-breakfast" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Adjust Breakfast Count</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            
                            <form name="update_breakfast_count" action="{$serverself}" method="POST" style="display:contents;">
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-sm-10 col-md-10 col-lg-10 col-xlg-6" style="margin-right:auto; margin-left:auto; float:none;">
                                    	<div class="row">
                                			<div class="col-md-4"><button class="btn btn-default arrived-button-1" onclick="javascript: return false;" style="height:50px; width:50px; float:right; margin-top:14px; background-image: url(/images/minus-2.png) !important; background-size:cover !important;" id="minus_breakfast"></button></div>
                                			<div class="col-md-4" style="margin-left:auto; margin-right:auto; float:none;"><input style="font-size:40px; font-weight:600; max-height:60px; min-height:60px;" type="number" name="breakfast_count" id="count_breakfast" value="{$Breakfast_Total}"></div>
                                    		<div class="col-md-4"><button class="btn btn-default arrived-button-1" onclick="javascript: return false;" style="height:50px; width:50px; float:left; margin-top:14px; background-image: url(/images/plus-2.png) !important; background-size:cover !important;" id="plus_breakfast"></button></div>
										</div>
									</div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-12">
										<button type="submit" value="update_breakfast_count" class="btn btn-default arrived-button-1">Update</button>
									</div>
								</div>
							</div>
                            </form>
                            <div class="modal-footer" style="height:204px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- END Breakfast Count Modal -->
                
                {section name=breakfastroster loop=$Breakfast_Reservations}
                
                <!-- BEGIN Breakfast Roster Modal -->
				<div id="modal-{$Breakfast_Reservations[breakfastroster].ResNumNumeric}" class="modal fade" role="dialog">
  					<div class="modal-dialog">
                    	<div class="modal-content">
                        	<div class="modal-header">
                            	<div class="row" style="display:contents;">
									<div class="col-md-10"><h4 class="modal-title">Guest Breakfast Arrival</h4></div>
                                    <div class="col-md-2"><h4 class="modal-title"><button type="button" class="close" data-dismiss="modal">&times;</button></h4></div>
								</div>
                            </div>
                            
                            <form name="update_breakfast_count" action="{$serverself}" method="POST" style="display:contents;">
                            <input name="breakfast_res_num" value="{$Breakfast_Reservations[breakfastroster].ResNumNumeric}" type="hidden">
                            <input name="party_total" value="{$Breakfast_Reservations[breakfastroster].People1 + $Breakfast_Reservations[breakfastroster].People2 + $Breakfast_Reservations[breakfastroster].People3 + $Breakfast_Reservations[breakfastroster].People4 - $Breakfast_Reservations[breakfastroster].res_party_num}" type="hidden">
                            <div class="modal-body">
                            	<div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            	<div class="row">
                                	<div class="col-sm-12 col-md-12 col-lg-12 col-xlg-6" style="margin-right:auto; margin-left:auto; float:none;">
                                    	<div class="row" style="border-bottom:1px solid #dee2e6; padding-left:30px; padding-right:30px;">
                                        	<div class="col-md-2">Room #</div>
                                            <div class="col-md-5">Name</div>
                                            <div class="col-md-2">Party</div>
                                            <div class="col-md-3">Arrived</div>
                                        </div>
                                        <div class="row" style="padding-left:30px; padding-right:30px;">
                                        	<div class="col-md-2" style="padding-top:10px; font-weight:600;">{$Breakfast_Reservations[breakfastroster].RoomNum}</div>
                                            <div class="col-md-5" style="padding-top:10px; font-weight:600;">{$Breakfast_Reservations[breakfastroster].GuestName}</div>
                                            <div class="col-md-2" style="padding-top:10px; font-weight:600;">{$Breakfast_Reservations[breakfastroster].People1 + $Breakfast_Reservations[breakfastroster].People2 + $Breakfast_Reservations[breakfastroster].People3 + $Breakfast_Reservations[breakfastroster].People4 - $Breakfast_Reservations[breakfastroster].res_party_num}</div>
                                			<div class="col-md-3"><input type="number" name="arrived_guests" min="1" max="{$Breakfast_Reservations[breakfastroster].People1 + $Breakfast_Reservations[breakfastroster].People2 + $Breakfast_Reservations[breakfastroster].People3 + $Breakfast_Reservations[breakfastroster].People4 - $Breakfast_Reservations[breakfastroster].res_party_num}" required></div>
                                            
										</div>
									</div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12"><br><br></div>
                                </div>
                            </div>
                            <div class="modal-footer">
								<div class="row" style="display:contents;">
									<div class="col-md-12">
										<button type="submit" value="update_breakfast_count" class="btn btn-default arrived-button-1">Update</button>
									</div>
								</div>
							</div>
                            </form>
                            <div class="modal-footer" style="height:204px;">
                            </div>
                        </div>
                    </div>
				</div>
                <!-- END Breakfast Roster Modal -->
                
                {/section}

</body>
</html>