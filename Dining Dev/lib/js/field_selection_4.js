
$(function () {

//alert("This is test alert #1");	
	
	
var arr = document.getElementsByClassName("guestselectclasses");

for (var k=0; k<arr.length; k++) {
	
	//alert(k);
	
$(arr[k]).change(function() {
	
	//alert("This is test alert #2");	
	
  if ($(this).val() == "ooh_yes") {	  
	//alert("Success?");  
    $('.ooh_row').show();
    $("[name='ooh_party_name']").attr('required', '');
    $("[name='ooh_party_name']").attr('data-error', 'This field is required.');
    $("[name='ooh_party_num']").attr('required', '');
    $("[name='ooh_party_num']").attr('data-error', 'This field is required.');
	$('.gog_row').hide();
	
	var selected = $(this).find('option:selected');
    var food = ''; 
	food = selected.data('food'); 
	var affiliation = '';
	affiliation = selected.data('affiliation'); 
	var phone = '' 
	phone = selected.data('phone'); 
	  
	//alert(food);
	//alert(affiliation);
	  
	var brr = document.getElementsByName("food_requests"); 
	  
	for (var m=0; m<brr.length; m++) {  
			brr[m].value = '';
	}
	
	
	var crr = document.getElementsByName("affiliation"); 
	  
	for (var n=0; n<crr.length; n++) {  
			crr[n].value = '';
	}
	
	
	var drr = document.getElementsByName("guest_phone"); 
	  
	for (var p=0; p<drr.length; p++) {  
			drr[p].value = '';
	}
	
	
	
	
  } else {
	//alert("Failure?");    
    $('.ooh_row').hide();
    $("[name='ooh_party_name']").removeAttr('required');
    $("[name='ooh_party_name']").removeAttr('data-error');
    $("[name='ooh_party_num']").removeAttr('required');
    $("[name='ooh_party_num']").removeAttr('data-error');
	$('.gog_row').show();
    
	var selected = $(this).find('option:selected');
    var food = ''; 
	food = selected.data('food'); 
	var affiliation = '';
	affiliation = selected.data('affiliation');
	var phone = '' 
	phone = selected.data('phone');   
	  
	//alert(food);
	//alert(affiliation);
	  
	var brr = document.getElementsByName("food_requests"); 
	  
	for (var m=0; m<brr.length; m++) {  
	  
	  	if (food) {
			brr[m].value = '';
			brr[m].value = food;
		} else {
			brr[m].value = '';
		}
	}
	
	
	var crr = document.getElementsByName("affiliation"); 
	  
	for (var n=0; n<crr.length; n++) {  
	  
	  	if (affiliation) {
			crr[n].value = '';
			crr[n].value = affiliation;
		} else {
			crr[n].value = '';
		}
	}
	
	
	var drr = document.getElementsByName("guest_phone"); 
	  
	for (var p=0; p<drr.length; p++) {  
	  
	  	if (phone) {
			drr[p].value = '';
			drr[p].value = phone;
		} else {
			drr[p].value = '';
		}
	}
	
  }	
});
//$(arr[k]).trigger("change");	
	
}
	
});