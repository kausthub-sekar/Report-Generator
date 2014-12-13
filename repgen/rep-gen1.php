<?php
//include the Xml2Pdf class
require_once '../../classes/Xml2Pdf.php';
/**
 * include the class Pdf.
*/
require_once '../../classes/Pdf.php';
//include the appvigil-report-parser
require_once 'appvigil-report-parser.php';
//to plot pie chart
require_once '../../classes/xml2df.graph.circle.php';
//to plot horizontal bar graph
require_once '../../classes/xml2df.graph.hbar.php';
//to plot vertical bar graph
require_once '../../classes/xml2df.graph.vbar.php';
//to plot curve graph using a line
require_once '../../classes/xml2df.graph.line.php';

$log=new KLogger ( "/home/ubuntu/www/logs/$systemDate-Appvigil-exec-log.txt" , KLogger::ERROR );

//custom user preferences will come from the appvigil-report-parser command line args
$obj = new Xml2Pdf('report.xml');
$pdf = $obj->render();
$pdf->Output();
$report_generator_log->LogInfo("$user_email Audit $audit_id - Audit Table printed",__FILE__,__LINE__);
?>