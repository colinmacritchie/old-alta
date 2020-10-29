$(function () {
	
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
	//document.title = vis() ? 'Visible' : 'Not visible';
	//console.log(new Date, 'visible ?', vis());
	location.href = location.href;
});

// to set the initial state
//document.title = vis() ? 'Visible' : 'Not visible';
	
});









