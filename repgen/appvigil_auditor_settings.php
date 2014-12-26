<?php

$output_dir = "/var/www/my-account/auditor/uploads/";
$s3_output_dir ="s3://appvigil/all-audited-apps-and-reports/";
$auditor_dir = "/var/www/my-account/auditor";

$fb_home = "/home/ubuntu/app_auditor/findbugs-2.0.3";
$filter = "/home/ubuntu/app_auditor/appvigil-filters/wegfilter.xml";

/////// Plugins /////////
$appvigil_plugins_dir = "/home/ubuntu/app_auditor/appvigil-plugins/";
$fb_sec_plugin = "/home/ubuntu/app_auditor/appvigil-plugins/findsecbugs-plugin-1.2.0.jar";
$fb_contrib_plugin = "/home/ubuntu/app_auditor/appvigil-plugins/fb-contrib-5.2.1.jar";
$plugins_list = scandir($appvigil_plugins_dir);
$appvigil_plugins = "";
foreach($plugins_list as $plugin) { 
	if($plugin=="." or $plugin=="..")
		continue;
	if($appvigil_plugins == "")
		$appvigil_plugins = $appvigil_plugins_dir.$plugin;
	else
		$appvigil_plugins .= ",".$appvigil_plugins_dir.$plugin;
}

$jd = "/home/ubuntu/app_auditor/jd-cmd-master";
$d2j = "/home/ubuntu/app_auditor/dex2jar-0.0.9.15/d2j-dex2jar.sh";
$java_io_temp = "/home/ubuntu/java.temp/";
$manifest_auditor = "/home/ubuntu/app_auditor/manifest_auditor/IntentSpoofing.jar";

$report_template_dir = "/var/www/my-account/auditor/report_template";
$report_parser_dir = "/var/www/my-account/auditor/report_generator";
$all_xml_reports_dir="/var/www/my-account/auditor/all-xml-reports";

?>