<?php
//require_once('/home/ubuntu/www/my-account/includes/KLogger.php');
require_once '/includes/KLogger.php';
$systemDate =  date('Y-m-d');
//$log = new KLogger ( "/home/ubuntu/www/logs/$systemDate-Appvigil-log.txt" , KLogger::DEBUG );
$log = new KLogger ( __DIR__."/logs/$systemDate-Appvigil-log.txt" , KLogger::DEBUG );
	?>