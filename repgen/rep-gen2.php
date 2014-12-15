<?php
//include $_SERVER['DOCUMENT_ROOT'].'/include/seo-check/lib/WSAclient.php';
include $_SERVER['DOCUMENT_ROOT'].'appvigil-report-parser.php';

$reportID= $argv[2]; // or audit id, needs to be fetched from the original appvigil-report-parser run

//we need to substitute WSAclient with an approriate object instantiation for our audit report
//we can also either define some final constant ids for user and substitute API key with 
//an appropriate argument as needed by our application

$WSAclient = new WSAclient(WSA_USER_ID,WSA_API_KEY);

$result=$WSAclient->viewReport($reportID,WSA_SUBSCRIPTION_ID,'xml','EN');

//this is to free up the memory allocated to the WSAclient, we may have to decide and modify it
//according to our application flow
unset($WSAclient);

ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PDF Report</title>
<!--Add your CSS here-->
</head>
<body>    
<?php  
echo WSAParser::viewReportResponse($result);
?>
</body>
</html>
<?php
$HTMLoutput = ob_get_contents();
ob_end_clean();


//Convert HTML 2 PDF by using MPDF PHP library
include $_SERVER['DOCUMENT_ROOT'].'/includes/mpdf.php';
$mpdf=new mPDF(); 

$mpdf->WriteHTML($HTMLoutput);
//save the pdf file as myReport.pdf
$mpdf->Output('myReport.pdf','F');
?>