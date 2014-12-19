//prevents conflicts between any different versions of jQuery
//and other library included called easing.js
var j=$.noConflict(true);
/**
 * switches HTML Report view back and forth between Developer and Executive report
 */
j(document).ready(function() {
		j('button').click(function() {
			j('#MCode').slideToggle("fast",function() {
				//hide or show  Malicious Code section
			});
			j('#hideMCode').slideToggle("fast",function() {
				//hide or show the Malicious Code left hand menu item
			});
		});
});