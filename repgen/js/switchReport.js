/**
 * switches HTML Report view back and forth between Developer and Executive report
 */
$(document).ready(function(){
		alert("doc is ready");
		$('#switchButton').click(function(){
			alert("button is clicked");
			$('#MCode').slideToggle("fast",function(){
				//hide or show  Malicious Code section
				alert("should hide MCode");
			});
			$('#AuditSummary').slideToggle("fast",function(){
				//hide or show Audit Summary section
				alert("should hide AuditSummary");
			});
			$('#hideSummary').slideToggle("fast",function(){
				//hide or show AuditSummary left hand menu item
				alert("should hide MCode menu");
			});
			$('#hideMCode').slideToggle("fast",function(){
				//hide or show the Malicious Code left hand menu item
				alert("should hide Audit Summary");
			});
		});
});