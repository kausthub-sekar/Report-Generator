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
	<title>Appvigil Report</title>
	
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
<body id="top" data-spy="scroll" data-target="#nav-wrap" data-offset="10">

<div id="container">
<button id="#switchButton">Switch Report View</button><br><br>
<!-- begin header -->
<header id="header">
	<h1>Android Security Audit Report by Appvigil</h1>
	<div class="meta">
		<strong>Created on: </strong><?php  echo $timestamp_in_userformat." ".$timezone_representation;?> &bull;
		<strong>By: </strong><?php  echo $user_name;?> &bull;
		<strong>Email: </strong><a href="mailto:<?php  echo $user_email;?>"><?php  echo $user_email;?></a>
	</div>
</header>
<!-- end header -->

<!-- begin navigation -->
<nav id="nav-wrap">
	<ul class="nav nav-list bs-docs-sidenav">
		<li class="active"><a href="#welcome"><i class="icon-chevron-right"></i> Welcome</a></li>
		<li><a href="#AuditDetails"><i class="icon-chevron-right"></i> Audit Details</a></li>
		<li id="hideSummary"><a href="#AuditSummary"><i class="icon-chevron-right"></i> Audit Summary</a></li>
		<li id="hideMCode"><a href="#MCode"><i class="icon-chevron-right"></i> Malicious Code</a></li>
		<li><a href="#about-us"><i class="icon-chevron-right"></i> About Us</a></li>
	</ul>
</nav>
<!-- end navigation -->
<!-- begin content -->
<section id="content">
<!-- default report view is assumed to be developer view.
The button should slideToggle to hide the sections and menu items irrelevant to the Executive view
--> 
<!-- begin welcome -->
<section id="welcome">
	<h2>Welcome</h2>
	<p>Thank you for scanning your Android application using the Appvigil Android App Security Auditor. If you have any queries, feedback or suggestions please feel free to contact us.</p>
</section>
<!-- end welcome -->
<!--Audit Table-->
<section id='AuditDetails'>
<h2>Audit Details</h2>
<br>
<table class = "table table-bordered">
<?php

if($user_id == 0)
$user_name = "Appvigil Free User";

$scan_summary = array_unique($xml->xpath('//FindBugsSummary'));
$report_generator_log->LogDebug("$user_email Audit $audit_id - user ID fetched as $user_id & Name $user_name",__FILE__,__LINE__);
?>
<tr><td>Audit ID</td><td><?php echo $audit_id;?></td></tr>
<tr><td>App Name</td><td><?php echo $audit->getField('app_name');?></td></tr>
<tr><td>Audit Time and Date</td><td><?php  echo $timestamp_in_userformat." ".$timezone_representation;?></td></tr>
<tr><td>App Size</td><td><?php echo $audit->getField('app_size_in_mb')." MB";?></td></tr>
<tr><td>Audited By</td><td><?php echo $user_name;?></td></tr>
<tr><td>Classes Audited</td><td><?php echo $scan_summary[0]->attributes()->total_classes;?></td></tr>
<tr><td>Packages Audited</td><td><?php echo $scan_summary[0]->attributes()->num_packages;?></td></tr>

</table>
<?php
$report_generator_log->LogInfo("$user_email Audit $audit_id - Audit Table printed",__FILE__,__LINE__);
?>
</section>
<!-- Malicious Code -->
<section id='AuditSummary'>
<h2>Executive Summary</h2>
<br>
<?php
$package_summary = array_unique($xml->xpath('//PackageStats[@total_bugs!=0]'));
$vulnerable_package_count = count($package_summary);
$report_generator_log->LogInfo("$user_email Audit $audit_id - Vulnerable package count is $vulnerable_package_count",__FILE__,__LINE__);
//echo "Vulnerable Packages: $vulnerable_package_count";
if($vulnerable_package_count == 0)
{
	echo "<h4> No Buggy package found in your app</h4>";
}
else
{
	?>
	<h2>Priority wise result of bugs:</h2>
		<table class="table table-bordered">
		<tr>
			<td>Overall threat level assessment: </td>
			<td>
			<?php 
				if($audit->getTotalBugsCount()<2)
				{
					echo "<img src='../images/p3_bug.png' width='50px' style='height:20px'>";
					echo " Low Risk";
				}
				else if($audit->getTotalBugsCount()<5)
				{
					echo "<img src='../images/p2_bug.png' width='50px' style='height:20px'>";
					echo " Medium Risk";
				}
				else 
				{
					echo "<img src='../images/p1_bug.png' width='50px' style='height:20px'>";
					echo " High Risk";
				}
			?>
			</td>
		</tr>
		<tr>
			<th colspan="2" align="center">Threat distribution percentage: </th>
		</tr>
		 <tr>
			<td>High:</td>
			<td>
				<img src="../images/p1_bug.png" 
				width='<?php echo(100*round($audit->getP1Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo(100*round($audit->getP1Count()/($audit->getTotalBugsCount()),3)); ?> %
			</td>
		 </tr>
		 <tr>
			<td>Medium:</td>
			<td>
				<img src="../images/p2_bug.png" 
				width='<?php echo(100*round($audit->getP2Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo(100*round($audit->getP2Count()/($audit->getTotalBugsCount()),3)); ?> %
			</td>
		 </tr>
		 <tr>
			<td>Low:</td>
			<td>
				<img src="../images/p3_bug.png" 
				width='<?php echo(100*round($audit->getP3Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo(100*round($audit->getP3Count()/($audit->getTotalBugsCount()),3)); ?> %
			</td>
		 </tr>
		 <tr>
			<th colspan="2" align="center">Threat distribution count: </th>
		</tr>
		<tr>
			<td>Total threat count: </td>
			<td><?php echo $vulnerable_package_count; ?></td>
		</tr>
		 <tr>
			<td>High:</td>
			<td>
				<img src="../images/p1_bug.png" 
				width='<?php echo(100*round($audit->getP1Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo round($vulnerable_package_count*(round($audit->getP1Count()/($audit->getTotalBugsCount()),2))); ?> 
			</td>
		 </tr>
		 <tr>
			<td>Medium:</td>
			<td>
				<img src="../images/p2_bug.png" 
				width='<?php echo(100*round($audit->getP2Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo round($vulnerable_package_count*(round($audit->getP2Count()/($audit->getTotalBugsCount()),2))); ?> 
			</td>
		 </tr>
		 <tr>
			<td>Low:</td>
			<td>
				<img src="../images/p3_bug.png" 
				width='<?php echo(100*round($audit->getP3Count()/($audit->getTotalBugsCount()),2))*4; ?>' 
				style="height:20px">
			<?php echo round($vulnerable_package_count*(round($audit->getP3Count()/($audit->getTotalBugsCount()),2))); ?> 
			</td>
		 </tr>
		</table>
	<table class="table table-bordered">
		<tr>
			<h2>Audit Summary</h2>
		</tr>
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

?>

<?php
}//else closed for printing Audit Summary Section

//print chart divs even if Vulnerable package count is 0
$auto_height = $vulnerable_package_count*70;
echo "<table><tr><td><div id='piechart1'></div></td>
<td><div id='piechart2'></div></td></tr></table>
</section>";

?>
<!-- Malicious Code Section-->
<section id='MCode'>
<h2>Malicious Code</h2>
<br>
<?php 
$abbrev_categories = array_unique($xml->xpath('//BugInstance/@abbrev'));
$abbrev_count = count($abbrev_categories);
if($abbrev_count==0)
{
	echo "<h4>Wow!!! No bug has been found in your app. Seems you are doing a pretty good job.</h4>";
}
else
{
	$i=1;
	foreach($abbrev_categories as $abbrev_category)
		{
		//echo "**************Category $i Category Started*****************<br><br>";
		$i++;
		$abbrev = $abbrev_category->abbrev;
		$bug_types = $xml->xpath("//BugInstance[@abbrev='$abbrev']");
		$BugCode = $xml->xpath("//BugCode[@abbrev='$abbrev']");
		$abbrev_meaning = $BugCode[0]->Description;
		//echo "<br>Abbrev: $abbrev";
		echo "<div class='accordion' id='$abbrev-super'><div class='accordion-group'>";
		echo "<div class='accordion-heading'>
		<a class='accordion-toggle collapsed' style='color: rgb(0, 85, 128);' data-toggle='collapse' data-parent='#$abbrev-super' href='#$abbrev'><b>$abbrev_meaning</b> <span class = 'badge' style='background-color: #FC0B0B; color: white;'>".count($bug_types)." Vulnerabilities</span></a></div>";
		echo "<div id='$abbrev' class='accordion-body collapse' style='height: 0px;'> <div class='accordion-inner'>";
		echo "<div class='accordion' id='$abbrev-list'>";



		//break;
		$bug_id = 0;
		foreach($bug_types as $bug_type)
		{
		$bug_id++;
		$full_desc_bug_predicate = ($is_limited!=1 or $bug_id==1 or $bug_id<=count($bug_types)*$limited_percentage);
		$bug_type_code = $bug_type->attributes()->type;
		echo "<div class='accordion-group'>";
		echo "<div class='accordion-heading'>";

		if(!$full_desc_bug_predicate)
		{
			echo "<a class='accordion-toggle collapsed' style='color:gray;' data-toggle='collapse' data-parent='#$abbrev-list' href='#$abbrev-$bug_id'>";
			echo "<b><i class='icon-bug' style='color:gray;'></i> {$bug_type->Class->attributes()->classname} [LIMITED]</b></a>";
			}
			else
			{
			echo "<a class='accordion-toggle collapsed' style='color: rgb(0, 85, 128);' data-toggle='collapse' data-parent='#$abbrev-list' href='#$abbrev-$bug_id'>";
			echo "<b><i class='icon-bug' style='color:#FF8A00;'></i> {$bug_type->Class->attributes()->classname}</b></a>";
			}

			echo "</div>";
			echo "<div id='$abbrev-$bug_id' class='accordion-body collapse' style='height: 0px;'> <div class='accordion-inner'>
			<b>
			{$bug_type->ShortMessage}
			</b><br>
			<b>Priority: </b>
			{$bug_type->attributes()->priority}
			<br>
			<b>Rank: </b>
			{$bug_type->attributes()->rank}
			<hr>";
			if($full_desc_bug_predicate)
			{
				if (array_key_exists("Class" , $bug_type))
				{
					echo "{$bug_type->Class->Message}<br>";
				}
				if (array_key_exists("Method" , $bug_type))
				{
					echo "{$bug_type->Method->Message}<br>";
				}
				if (array_key_exists("Field" , $bug_type))
				{
					echo "{$bug_type->Field->Message}<br>";
				}

				if(strlen($bug_type->SourceLine->Message) > 12)
					echo $bug_type->SourceLine->Message;
				echo "
				<hr>
				<hr><b>Exact Problem: </b>
				{$bug_type->LongMessage}
				<br>
				<hr>";

				$bug_pattern = $xml->xpath("//BugPattern[@type = '$bug_type_code']");
				echo "
				<b>Description: </b>";
				echo (string) $bug_pattern[0]->Details;
			}
			else 
				echo "<i>*Detailed description is available for paid users only.</i>";
			echo "</div></div></div>";
			//insert bug info into database
			$bug = $audit->addBug($abbrev, $bug_type_code, $bug_type->attributes()->priority, $bug_type->attributes()->rank, $bug_type->Class->Message,$bug_type->Method->Message,$bug_type->Field->Message,$bug_type->ShortMessage, $bug_type->LongMessage,$bug_type);
		}
		echo "</div></div></div></div>";
	}
}

$report_generator_log->LogInfo("$user_email Audit $audit_id - All bug instances Printed",__FILE__,__LINE__);
echo "</section>";


if($abbrev_count !=0)
{
echo "<script type='text/javascript'>
      google.load('visualization', '1', {packages:['corechart']});
	  google.setOnLoadCallback(BugsByPriorityChart);
	  function BugsByPriorityChart() {
        var data = google.visualization.arrayToDataTable({$audit->getBugsByPriorityChartData()});
        var options = {
          title: 'Bugs by Priority',
          chartArea:{left:0,top:50,width:'100%',height:'100%'}
        }; 
		document.getElementById('piechart2').setAttribute('style','width: 100%');
		document.getElementById('piechart2').setAttribute('style','height: 500px');
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
          chartArea:{right:25,top:50,width:'100%',height:'100%'}
        };
		document.getElementById('piechart1').setAttribute('style','width: 100%');
		document.getElementById('piechart1').setAttribute('style','height: 500px');
        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
        chart.draw(data, options);
      }
	  </script>";	  
	  
}

?>

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

</body></html>
<?php
$report_generator_log->LogInfo("$user_email Audit $audit_id - End of file reached",__FILE__,__LINE__);
$report_generator_log->logClose();
?>
