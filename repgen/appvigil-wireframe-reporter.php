<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Ixtendo">
	<link href="style.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" href="./images/favicon.png">
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<title>Security Audit Report</title>
	
	<!-- begin JS -->
	<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="js/easing.js" type="text/javascript"></script>
	<script src="js/modernizr.custom.js" type="text/javascript"></script>
	<!--[if (gte IE 6)&(lte IE 8)]>
	<script type="text/javascript" src="js/selectivizr-min.js"></script>
	<![endif]-->
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript"></script>
	<script src="js/jquery.ui.totop.min.js" type="text/javascript"></script>
	<script src="js/tinynav.min.js" type="text/javascript"></script>
	<script src="js/custom.js" type="text/javascript"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript" src="js/switchReport.js"></script>
	<!-- end JS -->
	<style>
		.up{
		}
		.p1{
			background-color: #FC0B0B;
		}
		.p2{
			background-color: #FF8A00;
		}
		.p3{
			background-color: #9FC569;
		}
		.bugx{
			
		}
	</style>

</head>
<?php
$xml_report = $argv[1];
$audit_id = $argv[2];
$is_limited = $argv[3];
$user_email = $argv[4];
$limited_percentage = .25;

//$rootpath = realpath($_SERVER["DOCUMENT_ROOT"]);

//echo "hello";
include "config/db.php";
//echo "hi";
require_once "functions.php";
//to be included once the entire file is parsed
//require_once "$rootpath/classes/UserDateTimeFormatter.php";
//require_once "$rootpath/classes/InputChecker.php";
//require_once "$rootpath/classes/ISystemDateTimeFinder.php";
//require_once "$rootpath/classes/Randomizer.php";
//require_once "$rootpath/classes/DateTimeConverter.php";
require_once "/classes/User.php";
require_once "/classes/Audit.php";
require_once "/classes/ClassUtils.php";
require_once "/includes/init_logger.php";

$audit = getAuditById($audit_id);
$user = getUserByAuditId($audit_id);
$timestamp = $audit->getField('audit_date_time');
$user_date_time_format = $user->getPreference('date_time_format');
$user_timezone_offset = $user->getPreference('offset');
$timezone_representation = $user->getPreference('UTC');
$user_name = $user->getField('name');
$user_email = $user->getField('email');
$user_id = $user->user_id;

$timestamp_in_userformat = convertSystemToUser($timestamp,$user_date_time_format,$user_timezone_offset);

$report_generator_log = $log;
$report_generator_log->LogDebug("$user_email Audit $audit_id - Report parser called with variable xml_report=$xml_report; audit_id=$audit_id; is_limited=$is_limited",__FILE__,__LINE__);
$xml=simplexml_load_file($xml_report);
$report_generator_log->LogDebug("$user_email Audit $audit_id - XML $xml_report loaded",__FILE__,__LINE__);
$report_generator_log->LogDebug("$user_email Audit $audit_id - User's timestamp calculated as $timestamp_in_userformat",__FILE__,__LINE__);
?>
<body id="top" data-spy="scroll" data-target="#nav-wrap">

<div id="container">
<!-- begin header -->
<header id="header">
	<img src="images/appvigil-logo.png">
	<h1>Security Audit Report</h1>
	<div id="View">
		<input type="radio" name="rdoView" id="execView" value="exec">Executive Report &nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="rdoView" id="devView" value="dev" checked="checked">Developer Report<br/><br/>
		<p>A Developer report gives you details of application bugs and recommended solutions.</p>
	</div>
</header>
<!-- end header -->

<!-- begin content -->
<section id="content">
<!-- default report view is assumed to be developer view.
The radio button should switch the view to hide the sections and menu items
that are irrelevant to the Executive view and only display the pie charts and bar graphs
--> 

<!--Audit Table-->
<section id='AuditHighlights'>
<h2>Audit Highlights</h2>
<br>
<table class = "table table-bordered">
<?php

if($user_id == 0)
$user_name = "Appvigil Free User";

$scan_summary = array_unique($xml->xpath('//FindBugsSummary'));
$report_generator_log->LogDebug("$user_email Audit $audit_id - user ID fetched as $user_id & Name $user_name",__FILE__,__LINE__);
$package_summary = array_unique($xml->xpath('//PackageStats[@total_bugs!=0]'));
$vulnerable_package_count = count($package_summary);
$report_generator_log->LogInfo("$user_email Audit $audit_id - Vulnerable package count is $vulnerable_package_count",__FILE__,__LINE__);
?>
<tr>
	<td>App Name: <?php echo $audit->getField('app_name');?></td>
	<td rowspan='2'><h2>Threat Level</h2></td>
	<td rowspan='2' colspan='4'><h2>Bugs</h2></td>
</tr>
<tr>
	<td>App Size: <?php echo $audit->getField('app_size_in_mb')." MB";?></td>
</tr>
<tr>
	<td># Classes Audited: <?php echo $scan_summary[0]->attributes()->total_classes;?></td>
	<td rowspan='2' align="center">
	<?php 
				if($vulnerable_package_count<15)
				{
					echo "<div id='lowThreat'>".$vulnerable_package_count."</div>";
				}
				else if($vulnerable_package_count<25)
				{
					echo "<div id='medThreat'>".$vulnerable_package_count."</div>";
				}
				else 
				{
					echo "<div id='highThreat'>".$vulnerable_package_count."</div>";
				}
	?>	
	</td>
	<td>
	<?php 
			echo "<div id='lowNum'>".round($vulnerable_package_count*(round($audit->getP3Count()/($audit->getTotalBugsCount()),2)))."&nbsp;&nbsp;&nbsp;&nbsp;</div>";
	?>
	</td>
	<td>
	<?php 
			echo "<div id='medNum'>".round($vulnerable_package_count*(round($audit->getP2Count()/($audit->getTotalBugsCount()),2)))."&nbsp;&nbsp;&nbsp;&nbsp;</div>";
	?>
	</td>
	<td>
	<?php
			echo "<div id='highNum'>".round($vulnerable_package_count*(round($audit->getP1Count()/($audit->getTotalBugsCount()),2)))."&nbsp;&nbsp;&nbsp;&nbsp;</div>";
	?>
	</td>
</tr>
<tr>
	<td># Packages Audited: <?php echo $scan_summary[0]->attributes()->num_packages;?></td>
	<td>
		<?php 
		echo "<div id='lowCount'>Low&nbsp;&nbsp;&nbsp;&nbsp;</div>";
		?>
	</td>
	<td>
		<?php 
		echo "<div id='medCount'>Mid&nbsp;&nbsp;&nbsp;&nbsp;</div>";
		?>
	</td>
	<td>
		<?php
		echo "<div id='highCount'>High&nbsp;&nbsp;&nbsp;&nbsp;</div>";
		?>
	</td>
	<?php
		$report_generator_log->LogInfo("$user_email Audit $audit_id - All bug instances Printed",__FILE__,__LINE__);
	?>
</tr>
</table>
<?php
$report_generator_log->LogInfo("$user_email Audit $audit_id - Audit Table printed",__FILE__,__LINE__);
?>
<!-- tabs with bug by severity, bugs by type and summary -->
<ul class="nav nav-tabs">
	<li><a href="#bugsBySeverity" data-toggle="tab">Bugs by Severity</a></li>
	<li><a href="#bugsByType" data-toggle="tab">Bugs by Type</a></li>
	<li><a href="#bugsSummary" data-toggle="tab">Scan Summary</a></li>
</ul>
<div id="tabContent" class="tab-content">
	<div id="bugsBySeverity" class="tab-pane fade in active">
		<h3>Bugs should be displayed by severity here</h3>
		<p>There will be a vertical nav bar/tab menu for low, mid and high severity. - Dev</p>
		<p>Interactive pie chart which should open corresponding section in Dev - Exec</p>
		
		 <div id="bugSeverityList">
		  <ul class="nav nav-tabs nav-stacked" style="width: 14%">
   			<li class="active"><a href="#lowBugs" data-toggle="tab">Low Severity</a></li>
   			<li><a href="#medBugs" data-toggle="tab">Mid Severity</a></li>	
   			<li><a href="#highBugs" data-toggle="tab">High Severity</a></li>
   		 </ul>
   		 </div>
   		 <div id="bugSeverityDetails" class="tab-content">
   		 	<div id="lowBugs" class="tab-pane fade in active">
   				 <p>Display low severity bug details in accordion style with two columns
   				 Problem Description | Recommended Solution</p>
   			</div>
   			<div id="medBugs" class="tab-pane fade in">
   				 <p>Display mid severity bug details in accordion style with two columns
   				 Problem Description | Recommended Solution</p>
   			</div>
   			<div id="highBugs" class="tab-pane fade in">
   				 <p>Display high severity bug details in accordion style with two columns
   				 Problem Description | Recommended Solution</p>
   			</div>
   		</div>
	</div>
	<div id="bugsByType" class="tab-pane fade in">
	
		<h3>Bugs will all be listed by type as a checklist</h3>
		<p>Bugs found will be checked and others will not be checked.</p>
		<p>Interactive bar graph, X-axis:Bug Type, Y-axis:# of bugs - Exec</p>
	
	</div>
	<div id="bugsSummary" class="tab-pane fade in">
	<table class="table table-bordered">
		<tr>
			<h2>Scan Summary</h2>
		</tr>
		<?php
		$package_summary = array_unique($xml->xpath('//PackageStats[@total_bugs!=0]'));
		$vulnerable_package_count = count($package_summary);
		$report_generator_log->LogInfo("$user_email Audit $audit_id - Vulnerable package count is $vulnerable_package_count",__FILE__,__LINE__);
		 if($vulnerable_package_count == 0)
		 {
			echo "<h4> No Buggy package found in your app</h4>";
		 }
		 else
		 {
		 ?>
		 	<tr class="up">
		 	<td> Package </td>
		 	<td> Code Size (Bytes) </td>
		 	<td> Bugs </td>
		 	<td> Bugs P1 </td>
		 	<td> Bugs P2</td>
		 	<td> Bugs P3</td>
		 	</tr>
		 	
		 	<?php
		 	foreach ($package_summary as $package_info)
		 	{
		 		echo "
		 		<tr>
		 		<td class='bugx'>{$package_info->attributes()->package}</td>
		 		<td class='bugx'>{$package_info->attributes()->total_size}</td>
		 		<td class='bugx'>{$package_info->attributes()->total_bugs}</td>
		 		<td class='p1'>";
		 	
		 		if(isset($package_info->attributes()->priority_1))
		 		{
		 		echo $package_info->attributes()->priority_1;
		 		$p1_count = $package_info->attributes()->priority_1;
		 		}
		 		else
		 		{
		 		echo "0";
		 		$p1_count = 0;
		 		}
		 		echo "
		 		</td>
		 		<td class='p2'>";
		if(isset($package_info->attributes()->priority_2))
		 			{
		 			echo $package_info->attributes()->priority_2;
		 			$p2_count = $package_info->attributes()->priority_2;
		 		}
		 		else
		 		{
		 		echo "0";
		 		$p2_count = 0;
		 		}
		 		echo "
		 		</td>
		 		<td class='p3'>";
		 		if(isset($package_info->attributes()->priority_3))
		 		{
		 		echo $package_info->attributes()->priority_3;
		 		$p3_count = $package_info->attributes()->priority_3;
		 		}
		 		else
		 		{
		 		echo "0";
		 		$p3_count = 0;
		 	}
		 	echo "
		 	</td>
		 		</tr>";
		 		$audit->addKeyInfo($package_info->attributes()->package, $package_info->attributes()->total_size, $p1_count, $p2_count, $p3_count);
		 	}
		 	
		 	echo "</table>";
		 	
		 	$report_generator_log->LogInfo("$user_email Audit $audit_id - Package Bug Table Printed",__FILE__,__LINE__);
		 }
		 ?>
	</div>
</div>
	<div id="bugSeverityPieChart" style="width:100%; height:100%">
   			<?php 
   			$abbrev_categories = array_unique($xml->xpath('//BugInstance/@abbrev'));
   			$abbrev_count = count($abbrev_categories);
   			
   			//print pie chart even if there are zero bug instances
   			//print chart divs even if Vulnerable package count is 0
   			$auto_height = $vulnerable_package_count*70;
   			echo "<table>
					<tr>
						<td><div id='piechart1'></div></td>
   						<td><div id='piechart2'></div></td>
					</tr>
				</table>";
   			
   			if($abbrev_count !=0)
   			{
   				echo "<script type='text/javascript'>
   				google.load('visualization', '1', {packages:['corechart']});
   				google.setOnLoadCallback(BugsByPriorityChart);
   				function BugsByPriorityChart() {
   				var data = google.visualization.arrayToDataTable({$audit->getBugsByPriorityChartData()});
   				var options = {
   				title: 'Bugs by Priority',
   				chartArea:{left:0,top:50,width:'100%',height:'100%'},
   				enableInteractivity: true,
   			};
   			document.getElementById('piechart2').setAttribute('style','width: 100%');
   			document.getElementById('piechart2').setAttribute('style','height: 100%');
   			var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
   			chart.draw(data, options);
   			}
   			</script>";
   			
   			echo "<script type='text/javascript'>
   			google.load('visualization', '1', {packages:['corechart']});
   			google.setOnLoadCallback(BugsByCategoriesChart);
   			function BugsByCategoriesChart() {
   			var data = google.visualization.arrayToDataTable({$audit->getBugsByCategoriesChartData()});
   			var options = {
   			title: 'Bugs by Type',
   			chartArea:{right:25,top:50,width:'100%',height:'100%'},
   			enableInteractivity: true,
   			};
   			document.getElementById('piechart1').setAttribute('style','width: 100%');
   			document.getElementById('piechart1').setAttribute('style','height: 100%');
   			var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
   			chart.draw(data, options);
   			}
   			</script>";
   			 
   			}
   			?>
	</div>
	<div id="bugSeverityBarGraph" style="width:100%; height:100%">
	<?php
	$abbrev_categories = array_unique($xml->xpath('//BugInstance/@abbrev'));
	$abbrev_count = count($abbrev_categories);
	
	//print pie chart even if there are zero bug instances
	//print chart divs even if Vulnerable package count is 0
	$auto_height = $vulnerable_package_count*70;
		echo "<table>
					<tr>
						<td><div id='bargraph'></div></td>
					</tr>
				</table>";
		echo "<script type='text/javascript'>
   			google.load('visualization', '1', {packages:['corechart']});
   			google.setOnLoadCallback(BugsByPriorityGraph);
   			function BugsByPriorityGraph() {
   			var data = google.visualization.arrayToDataTable({$audit->getBugsByPriorityChartData()});
   			var options = {
   				 title: 'Bugs by Type',
       			 width: 600,
        		 height: 400,
        		 legend: { position: 'top', textStyle: {color: 'blue', fontSize: 16}, maxLines: 3},
        		 bar: { groupWidth: '61.8%' }, //golden ratio
        		 colors: ['red','yellow','green'],
        		 enableInteractivity: true 
      			};	
   			document.getElementById('bargraph').setAttribute('style','width: 100%');
   			document.getElementById('bargraph').setAttribute('style','height: 100%');
   			var chart = new google.visualization.ColumnChart(document.getElementById('bargraph'));
   			chart.draw(data, options);
   			}
   			</script>";
	?>
	</div>
</section>

<!-- footer will stay the same -->
<!-- begin PSD Files -->
<section id="about-us">

    <h2>About Us</h2>
<br><p><img src = "images/logo.png" style="float: left; padding: 25px;">Wegilant, an IIT Bombay incubated company is at the heart of Web based IT Security. Led by IITian, Mr. Toshendra Sharma [<a href = "https://twitter.com/toshendrasharma" target = "_blank">Twitter</a>, <a href = "http://in.linkedin.com/in/toshendra/" target = "_blank">LinkedIn</a>], our team of IT security specialists offers SaaS based IT security products, consulting and implementation to small, mid and large sizes businesses as well as individuals. <br><br>Our products have been built on a private Android security cloud, which ensures highest level of data & information security over the web.</p>
<br>
<p>
<b><a href = "https://www.appvigil.co" target = "_blank">Appvigil</a></b>: Cloud based Android security audit & penetration testing tool.
<br></p>

<p>To know more about us visit <a href = "http://www.wegilant.com" target = "_blank">www.wegilant.com</a>.</p>

<p>To learn about IT security visit <a href = "http://www.wegversity.com" target = "_blank">www.wegversity.com</a>.</p>

<p>If you need help with patching security loopholes discovered by Appvigil, contact us at +91 8879390101 or mail us at <a href = 'mailto:info@wegilant.com'>info@wegilant.com</a></p>
</section>
<!-- end PSD Files -->
</section>

<!-- begin footer -->
<footer id="footer">
&copy; 2014 Wegilant
</footer>
<!-- end footer -->
</div>
</body>
</html>