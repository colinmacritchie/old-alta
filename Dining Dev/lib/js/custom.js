$(document).ready(function () {
    //$('#sms_opt_in').change(function () {
	$(document).on("change", ":checkbox", function() {
        
		var checkboxId = $(this).attr('id');
		checkboxId = checkboxId.substr(11);
		//console.log(checkboxId);
		
		if (!this.checked) 
           $('#enter_phone_div_' + checkboxId).hide();
        else 
            $('#enter_phone_div_' + checkboxId).show();
    });
});

function show_notifications() {
  var x = document.getElementById("unread_notifications");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}