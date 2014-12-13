<?php
//include the Xml2Pdf class
require_once '../../classes/Xml2Pdf.php';
/**
 * include the class Pdf.
*/
require_once '../../classesPdf.php';
//include the appvigil-report-parser
require_once 'appvigil-report-parser.php';

$log=new KLogger ( "/home/ubuntu/www/logs/$systemDate-Appvigil-exec-log.txt" , KLogger::ERROR );

//custom user preferences will come from the appvigil-report-parser command line args
$obj = new Xml2Pdf('report.xml');
$pdf = $obj->render();
$pdf->Output();

?>