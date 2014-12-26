<?php
	require_once('../includes/KLogger.php');
	$systemDate =  date('Y-m-d');
	$log = new KLogger ( "/home/Appvigil-Unit-Test-log.txt" , KLogger::DEBUG );
	//KLogger::file_handle =  null;
	$con = mysql_connect("cloudmysql.cnjwrz2mq8zc.ap-southeast-1.rds.amazonaws.com","wegcloud","wegcloud_123") or die("Unable to connect to SERVER.sdfs.!");
	$db = mysql_select_db("weg_appvigil_dev",$con) or die("Unable to connect to DATABASE..!");


	$ArrayUserWithCredits = array('user_id' => '217', 'email' => "unit_test_parent_user@wegilant.com");
	$ArrayUserWithNoCredits = array('user_id' => '219',  'email' => "unit_test_free_user@wegilant.com");
	//$ArrayUserSub = array('user_id' => '220', 'email' => "sumansourabh26@yahoo.com");
	$ArrayUserSub = array('user_id' => '186', 'email' => "unit_test_subuser_@wegilant.com");
	class UserTest extends PHPUnit_Framework_TestCase{
		public function testgetUserById(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			
			//************for UserWithCredits *******************//
			$testUser = getUserById($ArrayUserWithCredits['user_id']);
			$email = $ArrayUserWithCredits['email'];
			$result = mysql_query("SELECT * FROM login_table WHERE `email_id` = '$email' ") or die( mysql_error());
			$assoc = mysql_fetch_assoc($result);
			$actualUser = new User($ArrayUserWithCredits['email'], $assoc['password']);
			$this->assertEquals($testUser, $actualUser);



			//************for UserWithNoCredits *******************//
			$testUser = getUserById($ArrayUserWithNoCredits['user_id']);
			$email = $ArrayUserWithNoCredits['email'];
			$result = mysql_query("SELECT * FROM login_table WHERE `email_id` = '$email' ") or die( mysql_error());
			$assoc = mysql_fetch_assoc($result);
			$actualUser = new User($ArrayUserWithNoCredits['email'], $assoc['password']);
			$this->assertEquals($testUser, $actualUser);

			//************for UserSub *******************//
			$testUser = getUserById($ArrayUserSub['user_id']);
			$email = $ArrayUserSub['email'];
			$result = mysql_query("SELECT * FROM login_table WHERE `email_id` = '$email' ") or die( mysql_error());
			$assoc = mysql_fetch_assoc($result);
			$actualUser = new User($ArrayUserSub['email'], $assoc['password']);
			$this->assertEquals($testUser, $actualUser);
		}


		/**
		* @depends testgetUserById
		*/

		public function testaddCredits(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$testCreditAdded = rand(1,200);
			$testAddSource = rand(1,6);
			$testDateOfAdd = '2014-07-12 23:44:39';

			/********************for UserWithCredits*******************/
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithCredits->addCredits($testCreditAdded, $testAddSource, $testDateOfAdd);
			$id = $ArrayUserWithCredits['user_id'];
			$result = mysql_query("SELECT * FROM `credits_added` WHERE `user_id` = '$id' AND `credits_added`='$testCreditAdded' AND `credit_add_source` = '$testAddSource' AND `date_of_add` = '$testDateOfAdd' ");
			$num = mysql_num_rows($result);
			$this->assertGreaterThanOrEqual(1, $num, "Error in test Add Credits of UserWithCredits");


			/********************for UserWithNoCredits*******************/
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			$UserWithNoCredits->addCredits($testCreditAdded, $testAddSource, $testDateOfAdd);
			$id = $ArrayUserWithNoCredits['user_id'];
			$result = mysql_query("SELECT * FROM `credits_added` WHERE `user_id` = '$id' AND`credits_added`='$testCreditAdded' AND `credit_add_source` = '$testAddSource' AND `date_of_add` = '$testDateOfAdd' ");
			$num = mysql_num_rows($result);
			$this->assertGreaterThanOrEqual(1, $num, "Error in test Add Credits of UserWithNoCredits");

			/********************for UserSub*******************/
			$UserSub = getUserById($ArrayUserSub['user_id']);
			$UserSub->addCredits($testCreditAdded, $testAddSource, $testDateOfAdd);
			$id = $ArrayUserSub['user_id'];
			$result = mysql_query("SELECT * FROM `credits_added` WHERE `user_id` = '$id' AND `credits_added`='$testCreditAdded' AND `credit_add_source` = '$testAddSource' AND `date_of_add` = '$testDateOfAdd' ");
			$num = mysql_num_rows($result);
			$this->assertGreaterThanOrEqual(1, $num, "Error in test Add Credits of UserSub");
		}

		/**
		* @depends testgetUserById
		*/
		public function testaddInfo(){
			//in this function, the entry is being deleted by the function itself so i didn't get how to check weather the entry even existed. The testing can be done only when the subuset is being added.
		}

		/**
		* @depends testgetUserById
		*/

		public function addParentByVerificationCode(){
			//in this function, the entry is being deleted by the function itself so i didn't get how to check weather the entry even existed. The testing can be done only when the subuset is being added.
		}

		public function testaddPartnerById(){
			//the function is called on the time making of account itself, so it addes the 'user_id' -- 'partner_id ' into the database. Now since the user_id is primary key for the this table,i.e., partners_users, we can't call this function addPartnerById again on our dummy user. Else the code has been written as below.

			/*$partnerId = rand(2,9999);
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			//$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			//$UserSub = getUserById($ArrayUserSub['user_id']);

			//**************this function is essentially same for all three users so no need for differenet cases
			
			$UserWithNoCredits->addPartnerById($partnerId);
			$id = $ArrayUserWithNoCredits['user_id'];
			$result = mysql_query("SELECT * FROM `partners_users` WHERE `user_id` = '$id' AND `partner_id` = '$partnerId' ");
			$num = mysql_num_rows($result);
			$this->assertGreaterThanOrEqual(1,$num, "Error in testaddPartnerById");*/

		}

		public function testdeleteSubuser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$UserSub = getUserById($ArrayUserSub['user_id']);
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$is_suspended_before = $UserSub->getField('is_suspended');
			$parent_id_before = $UserSub->getField('parent_id');

			$UserWithCredits->deleteSubuser($UserSub);

			$is_suspended = $UserSub->getField('is_suspended');
			$this->assertEquals(0,$is_suspended, "error in testdeleteSubuser with is_suspended");

			$subuser_email = $UserSub->getField('email');
			$parent_id = $UserSub->getField('parent_id');

			$this->assertEquals(0,$parent_id,"error in testdeleteSubuser with parent_id");

			$UserSub->setField('parent_id', $parent_id_before);
			$UserSub->setField('is_suspended', $is_suspended_before);
		}

		public function testfetchInfo(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			//**************this function is essentially same for all three users so no need for differenet cases

			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$email = $UserWithCredits->getField('email');

			$result =  mysql_query("SELECT * FROM login_table A, users B WHERE A.email_id = B.email and A.email_id = '$email'") or die("unable to fetchInfo ".mysql_error());
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($assoc, $UserWithCredits->info,"error in testfetchInfo");
		}

		public function testgetAllAudits(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithNoCredits['user_id'];
			$UserWithNoCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT `audit_id` FROM `audit_details` WHERE `user_id` = '$id' and `audit_status`=1 order by audit_id desc") or die("Unable to fetch all audit ".mysql_error());
			$expected = array();
			while($row = mysql_fetch_assoc($result)){
				$audit = getAuditById($row['audit_id']);
				array_push($expected, $audit);				
			}

			$actual = $UserWithNoCredits->getAllAudits();

			$this->assertEquals($expected,$actual,"error in testgetAllAudits");
		}

		public function testgetAllUniqueAppsCount(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithNoCredits['user_id'];
			$UserWithNoCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT distinct(app_name) FROM `audit_details` WHERE user_id='$id'") or die("Unable to fetch all unique apps".mysql_error());
			$expected = mysql_num_rows($result);

			$actual = $UserWithNoCredits->getAllUniqueAppsCount();

			$this->assertEquals($actual, $expected, "error in getAllUniqueAppsCount");
		}

		public function testgetTotalPackagesScannedByUser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithNoCredits['user_id'];
			$UserWithNoCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases
			
			$result = mysql_query("SELECT * FROM `audit_key_info` WHERE audit_id in (select audit_id from audit_details where user_id='{$id}')") or die("Unable to fetch all unique packages count".mysql_error());
			$expected =  mysql_num_rows($result);

			$actual = $UserWithNoCredits->getTotalPackagesScannedByUser();

			$this->assertEquals($actual,$expected,"error in getTotalPackagesScannedByUser()");
		}

		public function testgetTotalBugsFoundByUser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithNoCredits['user_id'];
			$UserWithNoCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases
			$result = mysql_query("SELECT * FROM `audit_bug_info` WHERE audit_id in (select audit_id from audit_details where user_id='{$id}')") or die("Unable to fetch all unique packages count".mysql_error());
			
			$expected =  mysql_num_rows($result);

			$actual = $UserWithNoCredits->getTotalBugsFoundByUser();

			$this->assertEquals($actual,$expected,"error in getTotalBugsFoundByUser()");
		}

		public function testgetBoughtHistory(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT A.credits_added, DATE_FORMAT(A.date_of_add ,'%a, %D %M %Y') AS formated_date  FROM credits_added A, credits_source B WHERE A.credit_add_source = B.source_id and B.source_id = '3' and A.user_id = '$id'");
			$expected = array();
			while($row = mysql_fetch_assoc($result)){
				$expected[$row['formated_date']] = $row['credits_added'];
			}

			$actual = $UserWithCredits->getBoughtHistory();
			$this->assertEquals($actual,$expected,"error in testgetBoughtHistory()");
		}

		public function testgetCurrentPlan(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT * FROM user_plan_map A, plans B WHERE A.plan_id = B.id AND A.user_id = '$id' ORDER BY `A`.`transaction_id` DESC LIMIT 0 , 1") or die("unable to get Current Plan ".mysql_error());
			if(mysql_num_rows($result)==0){
				$expected =  false;
			}
			else{
				$assoc = mysql_fetch_assoc($result);
				$expected = $assoc;			
			}

			$actual = $UserWithCredits->getCurrentPlan();
			$this->assertEquals($actual,$expected,"error in testgetCurrentPlan()");
		}

		public function testgetCurrentPlanId(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$assoc = $UserWithCredits->getCurrentPlan();
			$expected = $assoc['plan_id'];

			$actual = $UserWithCredits->getCurrentPlanId();

			$this->assertEquals($actual,$expected,"error in testgetCurrentPlanId()");
		}

		public function testgetCurrentPlanName(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$assoc = $UserWithCredits->getCurrentPlan();
			$expected = $assoc['package_name'];

			$actual = $UserWithCredits->getCurrentPlanName();

			$this->assertEquals($actual,$expected,"error in testgetCurrentPlanName()");
		}

		public function testgetEarnedHistory(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases


			$result = mysql_query("SELECT A.credits_added, DATE_FORMAT(A.date_of_add ,'%a, %D %M %Y') AS formated_date  FROM credits_added A, credits_source B WHERE A.credit_add_source = B.source_id and B.source_id = '2' and A.user_id = '$id'");
			$expected = array();
			while($row = mysql_fetch_assoc($result)){
				$expected[$row['formated_date']] = $row['credits_added'];
			}
			
			$actual = $UserWithCredits->getEarnedHistory();

			$this->assertEquals($actual,$expected,"error in testgetEarnedHistory()");
		}

		public function testgetField(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$email = $ArrayUserWithCredits['email'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result =  mysql_query("SELECT * FROM login_table A, users B WHERE A.email_id = B.email and A.email_id = '$email'") or die("unable to fetchInfo ".mysql_error());
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($assoc['email'],$UserWithCredits->getField('email'));
			$this->assertEquals($assoc['contact'],$UserWithCredits->getField('contact'));
			$this->assertEquals($assoc['registration_date'],$UserWithCredits->getField('registration_date'));
			$this->assertEquals($assoc['utm_source'],$UserWithCredits->getField('utm_source'));

		}

		/**
		* @depends testgetField
		*/
		public function testgetId(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$expected = $UserWithCredits->getField('user_id');

			$actual = $UserWithCredits->getId();
			$this->assertEquals($actual,$expected,"error in testgetId");
		}

		public function testgetInfo(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$email = $ArrayUserWithCredits['email'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result =  mysql_query("SELECT * FROM login_table A, users B WHERE A.email_id = B.email and A.email_id = '$email'") or die("unable to fetchInfo ".mysql_error());
			$expected = mysql_fetch_assoc($result);

			$actual = $UserWithCredits->getInfo();

			$this->assertEquals($actual,$expected,"error in testgetInfo");
		}

		public function testgetLastAudit(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT `audit_id` FROM `audit_details` WHERE `user_id` = '$id' ORDER BY `audit_details`.`audit_id` Desc Limit 1") or die("Unable to fetch Last audit ".mysql_error());
			$row = mysql_fetch_array($result);
			if(mysql_num_rows($result)){
				$expected =  getAuditById($row['audit_id']);
			}else{
				$expected = false;
			}

			$actual = $UserWithCredits->getLastAudit();

			$this->assertEquals($actual,$expected,"error in testgetLastAudit");
		}

		public function testgetLastFinishedAudit(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases


			$result = mysql_query("SELECT `audit_id` FROM `audit_details` WHERE `user_id` = '$id' and audit_status=1 ORDER BY `audit_details`.`audit_id` Desc Limit 1") or die("Unable to fetch Last audit ".mysql_error());
			$row = mysql_fetch_array($result);
			if(mysql_num_rows($result)){
				$expected =  getAuditById($row['audit_id']);
			}else{
				$expected = false;
			}

			$actual = $UserWithCredits->getLastFinishedAudit();

			$this->assertEquals($actual,$expected,"error in testgetLastFinishedAudit");
		}

		/*
		*	@depends testgetCurrentPlan, testgetUserById
		*/

		public function testgetPlanExpiryDate(){

		}
		public function testgetRecentAudits(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases			

			$testCount = rand(1,20);
			$result = mysql_query("SELECT `audit_id` FROM `audit_details` WHERE `user_id` = '$id' and `audit_status` = 1 ORDER BY `audit_details`.`audit_id` Desc Limit $testCount") or die("Unable to fetch Recent audits ".mysql_error());
			$expected = array();
			while ($row = mysql_fetch_array($result)) {
				$newAudit = getAuditById($row['audit_id']);
				array_push($expected, $newAudit);
			}

			$actual = $UserWithCredits->getRecentAudits($testCount);

			$this->assertEquals($actual,$expected,"error in testgetRecentAudits");			
		}

		public function testgetSubusers(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases			

			$expected = array();
			$result = mysql_query("SELECT `user_id` FROM `users` WHERE `parent_id` = '$id'") ;
			while($row = mysql_fetch_assoc($result)){
				$newSubuser = getUserById($row['user_id']);
				array_push($expected, $newSubuser);
			}
			$result = mysql_query("SELECT * FROM  `subuser_verify` WHERE `parent_id` = '$id'") ;
			while($row = mysql_fetch_assoc($result)){
				$newSubuser = getUserByEmail($row['subuser_email']);
				array_push($expected, $newSubuser);
			}
			
			$actual = $UserWithCredits->getSubusers();
			$this->assertEquals($actual,$expected,"error in testgetSubusers");
		}

		public function testgetTotalBoughtCredits(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases

			$result = mysql_query("SELECT SUM(credits_added) as credits_bought FROM credits_added WHERE credit_add_source = '3' and  user_id = '$id'");
			$row = mysql_fetch_assoc($result);
			$credits_added = $row['credits_bought'];
			if($credits_added){
				$expected = $credits_added;
			}else{
				$expected = 0;
			}

			$actual = $UserWithCredits->getTotalBoughtCredits();

			$this->assertEquals($actual,$expected,"error in testgetTotalBoughtCredits");
		}

		/*		function getTotalEarnedCredits(){
			$result = mysql_query("SELECT SUM(credits_added) as credits_earned FROM credits_added WHERE credit_add_source = '2' and user_id = '$this->user_id'") or die("error in getTotalEarnedCredit() ".mysql_error());
			$row = mysql_fetch_assoc($result);
			$credits_earned =  $row['credits_earned'];
			if($credits_earned){
				return $credits_earned;
			}else{
				return 0;
			}
		}*/

		public function testgetTotalEarnedCredits(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases
			
			$result = mysql_query("SELECT SUM(credits_added) as credits_earned FROM credits_added WHERE credit_add_source = '2' and user_id = '$id'");
			$row = mysql_fetch_assoc($result);
			$credits_earned =  $row['credits_earned'];
			if($credits_earned){
				$expected = $credits_earned;
			}else{
				$expected = 0;
			}

			$actual = $UserWithCredits->getTotalEarnedCredits();
			$this->assertEquals($actual,$expected,"error in testgetTotalEarnedCredits");


		}

		

		public function testincrementAuditsAllowed(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			//**************this function is essentially same for all three users so no need for differenet cases
			$testIncrement = rand(2,200);

			$result1 = mysql_query("SELECT `audits_allowed` FROM `users` WHERE `user_id` = '$id'") ;
			$assoc1 = mysql_fetch_assoc($result1);
			$before = $assoc1['audits_allowed'];

			$UserWithCredits->incrementAuditsAllowed($testIncrement);

			$result2 = mysql_query("SELECT `audits_allowed` FROM `users` WHERE `user_id` = '$id'") ;
			$assoc2 = mysql_fetch_assoc($result2);
			$after = $assoc2['audits_allowed'];

			$this->assertEquals($before+$testIncrement, $after);

		}

		/*
		*	@depends testgetCurrentPlan, testisSubuser, testgetSubusers
		*/

		public function testisEligibleForSubuser($value=''){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			$UserSub = getUserById($ArrayUserSub['user_id']);
			$Userarray = array($UserWithCredits, $UserWithNoCredits, $UserSub);

			foreach ($Userarray as $user_to_check) {
				$current_plan = $user_to_check->getCurrentPlan();
				if($user_to_check->isSubuser()){
					$expected = false;
				}
				else{
					if($current_plan['plan_id'] == 1){
						$subusers = $user_to_check->getSubusers();
						$expected = (count($subusers) >= $current_plan['subusers_allowed']) ? false : true ;
					} else {
						$expected = false;
					}
				}
				global $log;
				$actual = $user_to_check->isEligibleForSubuser();
				$this->assertEquals($actual, $expected, "error in testisEligibleForSubuser for user {$user_to_check->getId()} ");

			}
		}

		/*
		*	@depends testgetCurrentPlan, testgetField
		*/

		public function testisEligibleForScan(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			$UserSub = getUserById($ArrayUserSub['user_id']);
			$Userarray = array($UserWithCredits, $UserWithNoCredits, $UserSub);

			foreach ($Userarray as $user_to_check) {
				$current_plan = $user_to_check->getCurrentPlan();
				if($user_to_check->getField('parent_id') == 0){
					if($current_plan['plan_id'] == 1){
						$expected = ($user_to_check->getField('is_suspended')) ? false : true ; 
					}
					else{
						//he must be a parent in free trial
						$expected = (count($user_to_check->getAllAudits())>=1) ? false : true ;
					}
					
				}
					else{
					$parent = getUserById($user_to_check->getField('parent_id'));
					$expected = $user_to_check->isEligibleForScan($parent);
				}
				$actual = $user_to_check->isEligibleForScan();

				$this->assertEquals($actual, $expected, "error in testisEligibleForScan for user {$user_to_check->getId()} ");
			}
		}

		public function testisSubuser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			$UserSub = getUserById($ArrayUserSub['user_id']);

			$result1 = mysql_query("SELECT `parent_id` FROM `users` WHERE `user_id` = '{$UserWithCredits->getId()}'");
			$result2 = mysql_query("SELECT `parent_id` FROM `users` WHERE `user_id` = '{$UserWithNoCredits->getId()}'");
			$result3 = mysql_query("SELECT `parent_id` FROM `users` WHERE `user_id` = '{$UserSub->getId()}'");

			$assoc1 = mysql_fetch_assoc($result1);
			$assoc2 = mysql_fetch_assoc($result2);
			$assoc3 = mysql_fetch_assoc($result3);

			$expected1 = !($assoc1['parent_id'] == 0);
			$expected2 = !($assoc2['parent_id'] == 0);
			$expected3 = !($assoc3['parent_id'] == 0);

			$this->assertEquals($expected1, $UserWithCredits->isSubuser());
			$this->assertEquals($expected2, $UserWithNoCredits->isSubuser());
			$this->assertEquals($expected3, $UserSub->isSubuser());
		}

		public function testisActiveSubuser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$UserWithCredits = getUserById($ArrayUserWithCredits['user_id']);
			$UserWithNoCredits = getUserById($ArrayUserWithNoCredits['user_id']);
			$UserSub = getUserById($ArrayUserSub['user_id']);

			$this->assertTrue($UserSub->isActiveSubuser());
			$this->assertFalse($UserWithCredits->isActiveSubuser());
			$this->assertFalse($UserWithNoCredits->isActiveSubuser());
		}

		public function testmapAuditId(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;
			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);
			//**************this function is essentially same for all three users so no need for differenet cases
			$testAuditId = 9;
			$UserWithCredits->mapAuditId($testAuditId);

			$result = mysql_query("SELECT `user_id` from `audit_details` WHERE audit_id='$testAuditId' ");
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($assoc['user_id'], $UserWithCredits->getId());

		}

		public function testsendForgotPasswordMail(){
			//here only possible way i could think of is to check for entry of the email id in the `forgetpass`, since we are not passing the key into the function it can't be used here.

			//testing it not possible since we are doing testing in terminal and and $_SERVER['HTTP_HOST'] is set by browser, so this testcase gives "Undefined index: HTTP_HOST" error


			/*global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;



			$id = $ArrayUserWithCredits['user_id'];
			$email = $ArrayUserWithCredits['email'];
			$UserWithCredits = getUserById($id);


			$UserWithCredits->sendForgotPasswordMail();

			$result = mysql_query("SELECT * FROM `forgetpass` WHERE `email` = $email");
			$num = mysql_num_rows($result);

			$this->assertGreaterThanOrEqual(1,$num);*/

		}

		public function testsendReferralMail(){
			# code...
		}

		public function testsendSubuserConfirmationMail(){
			
		}

		public function testsetPassword(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$id = $ArrayUserWithCredits['user_id'];
			$email = $ArrayUserWithCredits['email'];
			$UserWithCredits = getUserById($id);

			$passwordInText = "anotherpassword";
			$md5password = md5($passwordInText);

			$UserWithCredits->setPassword($md5password);

			$result = mysql_query("SELECT * FROM `login_table` where `email_id` = '$email'");
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($md5password, $assoc['password']);

		}

		public function testsetField(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			$testname = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
			$testcontact = substr(str_shuffle("0123456789"), 0, 10);
			$testUtmSource = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
			$testComapanyName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

			$UserWithCredits->setField('name',$testname);
			$UserWithCredits->setField('contact',$testcontact);
			$UserWithCredits->setField('utm_source',$testUtmSource);
			$UserWithCredits->setField('company_name',$testComapanyName);

			$result = mysql_query("SELECT * FROM `users` WHERE `user_id` = '$id'");
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($testname, $assoc['name']);
			$this->assertEquals($testcontact, $assoc['contact']);
			$this->assertEquals($testUtmSource, $assoc['utm_source']);
			$this->assertEquals($testComapanyName, $assoc['company_name']);
		}

		public function testsetPreference(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			$testTimezone = rand(75,104);
			$testDateFormat = rand(1,4);
			$testTimeFormat = rand(1,4);
			$testDateTimeFormat = rand(4,4);
			//timezone 	date_format 	time_format 	date_time_format 
			$UserWithCredits->setPreference('timezone',$testTimezone);
			$UserWithCredits->setPreference('date_format',$testDateFormat);
			$UserWithCredits->setPreference('time_format',$testTimeFormat);
			$UserWithCredits->setPreference('date_time_format',$testDateTimeFormat);

			$result = mysql_query("SELECT * FROM `user_preferences`WHERE `user_id` = '$id'");
			$assoc = mysql_fetch_assoc($result);

			$this->assertEquals($testTimezone, $assoc['timezone']);
			$this->assertEquals($testDateFormat, $assoc['date_format']);
			$this->assertEquals($testTimeFormat, $assoc['time_format']);
			$this->assertEquals($testDateTimeFormat, $assoc['date_time_format']);
		}

		public function testgetPreference(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);
			
			$result = mysql_query("SELECT B.GMT as offset, B.id as timezone_id, B.UTC as UTC, C.format as date_time_format,C.id as date_time_format_id, D.format as date_format,D.id as date_format_id, E.format as time_format, E.id as time_format_id FROM user_preferences A, timezones B, timestamp_formats C, date_formats D, time_formats E where A.timezone=B.id and A.date_format = D.id and A.time_format=E.id and A.date_time_format=C.id and A.user_id ='$id'") or die(mysql_error('getPreference Failed!!!'));
			$expected = mysql_fetch_assoc($result);

			$this->assertEquals($expected['offset'], $UserWithCredits->getPreference('offset'));
			$this->assertEquals($expected['timezone_id'], $UserWithCredits->getPreference('timezone_id'));
			$this->assertEquals($expected['UTC'], $UserWithCredits->getPreference('UTC'));
			$this->assertEquals($expected['date_time_format'], $UserWithCredits->getPreference('date_time_format'));
			$this->assertEquals($expected['date_format'], $UserWithCredits->getPreference('date_format'));
			$this->assertEquals($expected['date_format_id'], $UserWithCredits->getPreference('date_format_id'));
			$this->assertEquals($expected['time_format'], $UserWithCredits->getPreference('time_format'));
			$this->assertEquals($expected['time_format_id'], $UserWithCredits->getPreference('time_format_id'));


		}


		public function testtoggleSuspendSubuser(){
			global $ArrayUserWithCredits, $ArrayUserWithNoCredits, $ArrayUserSub;

			$id = $ArrayUserWithCredits['user_id'];
			$UserWithCredits = getUserById($id);

			$UserSubid = $ArrayUserSub['user_id'];
			$UserSub = getUserById($UserSubid);

			$result1 = mysql_query("SELECT `is_suspended` FROM `users` WHERE `user_id` = '$UserSubid' ") or die(mysql_error());
			$assoc1 = mysql_fetch_assoc($result1);
			$before = $assoc1['is_suspended'];
			

			$UserWithCredits->toggleSuspendSubuser($UserSub);

			$result2 = mysql_query("SELECT `is_suspended` FROM `users` WHERE `user_id` = '$UserSubid' ") or die(mysql_error());
			$assoc2 = mysql_fetch_assoc($result2);
			$after = $assoc2['is_suspended'];
			

			$this->assertNotEquals($before,$after,"error in testtoggleSuspendSubuser $before  and $after");
		}



	}


?>