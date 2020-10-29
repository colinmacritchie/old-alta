$(function () {
	
	// var isTabActive = 'Active';
	//console.log(new Date, 'Test 0', vis());
	
	var vis = (function(){
	var stateKey, eventKey, keys = {
		hidden: "visibilitychange",
		webkitHidden: "webkitvisibilitychange",
		mozHidden: "mozvisibilitychange",
		msHidden: "msvisibilitychange"
	};
	for (stateKey in keys) {
		if (stateKey in document) {
			eventKey = keys[stateKey];
			break;
		}
	}
	return function(c) {
		if (c) {
			document.addEventListener(eventKey, c);
			//document.addEventListener("blur", c);
			//document.addEventListener("focus", c);
		}
		return !document[stateKey];
	}
})();

vis(function(){
	
	//isTabActive = vis() ? 'Active' : 'Inactive';
	//document.title = vis() ? 'Visible' : 'Not visible';
	console.log(new Date, 'Visible 1', vis());
	
	
	if (vis()) {
		
		//console.log(new Date, 'Tab is active, yo', vis());
	
		var time = new Date().getTime();
     	$(document.body).bind("mousemove keypress tap", function(e) {
			// console.log(new Date, 'statechange #1', vis());
        	time = new Date().getTime();
     	});

     	function refresh() { 
		// 1000 ms is 1 second. 300000 is 5 minutes.
        	if((new Date().getTime() - time >= 300000) && (vis() == true)) 
        		// window.location.reload(true);
				location.href = location.href;
        	else 
        		setTimeout(refresh, 10000);
     	}

     setTimeout(refresh, 10000);	 
	} 
	
});

// to set the initial state
//document.title = vis() ? 'Visible' : 'Not visible';
//console.log(new Date, 'Visible 1', vis());
	
if (vis()) {
		
	//console.log(new Date, 'Tab is active, yo', vis());
	
	var time = new Date().getTime();
    $(document.body).bind("mousemove keypress tap", function(e) {
		// console.log(new Date, 'statechange #2', vis());
    	time = new Date().getTime();
    });

    function refresh() { 
    	if((new Date().getTime() - time >= 300000) && (vis() == true)) 
        	// window.location.reload(true);
			location.href = location.href;
        else 
            setTimeout(refresh, 10000);
	}

    setTimeout(refresh, 10000);	 	 
}
	
});










/*

$(function () {

	console.log(new Date, 'This is a test');
	 
	 var time = new Date().getTime();
     $(document.body).bind("mousemove keypress tap", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
		 
		 // Additional code to check for tab active status
	 
		 var isTabActive;

		window.onfocus = function () { 
  			isTabActive = true; 
			console.log(new Date, 'Tab is active');
		}; 

		window.onblur = function () { 
  			isTabActive = false; 
			console.log(new Date, 'Tab is not active');
		};

		// 
         if((new Date().getTime() - time >= 15000) && (isTabActive == true)) 
             // window.location.reload(true);
			 location.href = location.href;
         else 
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
		
	 
});

*/