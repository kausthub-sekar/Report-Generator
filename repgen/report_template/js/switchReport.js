//prevents conflicts between any different versions of jQuery
//and other library included called easing.js
var j=$.noConflict(true);
/**
 * switches HTML Report view back and forth between Developer and Executive report
 */
j(document).ready(function() {
		j('form input:radio').click(function() {
			if(j('input:checked').val()=="dev")
				{
				/*
				 * this entire section's switching logic
				 * will have to change based on the new wireframe mockup
				 * maybe use show function to show all the details	
				j('#MCode').slideToggle("fast",function() {
					//hide or show  Malicious Code section
				});
				j('#hideMCode').slideToggle("fast",function() {
					//hide or show the Malicious Code left hand menu item
				});
				*/
				}
			else
				{
				//hide all the detailed sections using the hide function
				}
		});
});