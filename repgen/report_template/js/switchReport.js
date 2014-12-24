//prevents conflicts between any different versions of jQuery
//and other library included called easing.js
var j=$.noConflict(true);
/**
 * switches HTML Report view back and forth between Developer and Executive report
 */
j(document).ready(function() {
	//when document loads hide the executive details initially since developer report is 
	//considered the default view
	j('#bugSeverityPieChart').hide();
	j('#bugSeverityBarGraph').hide();
	j('#View').show();
	
		j('input[type="radio"]').click(function() {
			if(j(this).attr("value")=="dev")
				{
				//hide the charts and show the details
				j('#bugSeverityDetails').show();
				j('#bugTypeDetails').show();
				j('#bugSeverityList').show();
				j('#bugSeverityPieChart').hide();
				j('#bugSeverityBarGraph').hide();
				}
			if(j(this).attr("value")=="exec")
				{
				//hide all the detailed sections using the hide function
				j('#bugSeverityDetails').hide();
				j('#bugTypeDetails').hide();
				j('#bugSeverityList').hide();
				j('#bugsBySeverity').click(function(){
					j('#bugSeverityPieChart').show();
				});
				j('#bugsByType').click(function(){
					j('#bugSeverityBarGraph').show();
				});
				}
		});
		/*doesn't seem to work
		//function to identify the currently active tab in order to hide irrelevant elements
		j(function getActiveTab(){
		      j('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		      // Get the name of active tab
		      var activeTab = j(e.target).text();
		      return activeTab;
		      }
		 */     
});