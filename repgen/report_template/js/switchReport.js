//prevents conflicts between any different versions of jQuery
//and other library included called easing.js
var j=$.noConflict(true);
//function to find the currently active bootstrap tab
function getActiveTab(){
      j('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      // Get the name of active tab
      var activeTab = j(e.target).text();
      //alert(activeTab);
      return activeTab;
      });
}
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
					if(getActiveTab()=="Bugs by Severity")
					{
						j('#bugSeverityPieChart').hide();
						j('#bugsBySeverity').show();
					}
					else if(getActiveTab()=="Bugs by Type")
					{
						j('#bugSeverityBarGraph').hide();
						j('#bugsByType').show();
					}
				/*
				j('#bugSeverityDetails').show();
				j('#bugTypeDetails').show();
				j('#bugSeverityList').show();
				j('#bugSeverityPieChart').hide();
				j('#bugSeverityBarGraph').hide();
				*/
				}
			if(j(this).attr("value")=="exec")
				{
				//hide all the detailed sections using the hide function
				/*
				j('#bugSeverityDetails').hide();
				j('#bugTypeDetails').hide();
				j('#bugSeverityList').hide();
				*/
					if(getActiveTab()=="Bugs by Severity")
					{
						j('#bugsBySeverity').hide();
						j('#bugSeverityDetails').hide();
						j('#bugSeverityList').hide();
						j('#bugSeverityPieChart').show();
					}
					else if(getActiveTab()=="Bugs by Type")
					{
						j('#bugsByType').hide();
						j('#bugTypeDetails').hide();
						j('#bugSeverityBarGraph').show();
					}
					/*
					j('#bugSeverityDetails').hide();
					j('#bugTypeDetails').hide();
					j('#bugSeverityList').hide();
					j('#bugSeverityPieChart').hide();
					}
					else
					{
					 j('#bugSeverityPieChart').show();
					}
					
				});
				/*
				j('#bugsByType').click(function(){
					if(j('input[type="radio"]').attr("value")=="dev")
					{	
					j('#bugSeverityDetails').hide();
					j('#bugTypeDetails').hide();
					j('#bugSeverityList').hide();
					j('#bugSeverityBarGraph').hide();
					}
					else
					{
					 j('#bugSeverityBarGraph').show();
					}
				});
				}
				*/
				}
		});
});