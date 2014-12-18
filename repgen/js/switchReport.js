/**
 * switches HTML Report view back and forth between Developer and Executive report
 */
$(document).ready(function(){
		$('button').click(function(){
			$('#MCode').slideToggle("fast",function(){
				//hide or show  Malicious Code section
			});
			$('#AuditSummary').slideToggle("fast",function(){
				//hide or show Audit Summary section
			});
			$('#hideSummary').slideToggle("fast",function(){
				//hide or show AuditSummary left hand menu item
			});
			$('#hideMCode').slideToggle("fast",function(){
				//hide or show the Malicious Code left hand menu item
			});
		});
});