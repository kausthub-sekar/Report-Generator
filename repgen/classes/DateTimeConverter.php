<?php
	
	class DateTimeConverter extends UserDateTimeFormatter implements ISystemDateTimeFinder
	{
		public function getSystemCurrentDateTime()
		{
			return gmdate('Y-m-d H:i:s');
		}
		
		public function getSystemCurrentDate()
		{
			return gmdate('Y-m-d');
		}
		
		public function getSystemCurrentTime()
		{
			return gmdate('H:i:s');
		}
		function convertUserToSystem($DateTime, $user_date_time_format = Null, $timezone_offset = Null)
		{
			if(is_null($user_date_time_format))
				$user_date_time_format = $_SESSION['user_date_time_format'];
			if(is_null($timezone_offset))
				$timezone_offset = $_SESSION['user_timezone_offset'];
		
			$reversed_timezone_offset = $timezone_offset[0]=="+"?"-".substr($timezone_offset,1):"+".substr($timezone_offset,1);
			$reversed_timezone_offset = str_replace("hours ", "hours -",$reversed_timezone_offset);
			//echo "<br>Present UTC Time: ".gmdate('Y-m-d H:i:s');
			//echo "<br>strtotime argument "."$DateTime $reversed_timezone_offset";
			//echo "<br>Argument passwed for conversion "."$DateTime";
			$out = date('Y-m-d H:i:s',strtotime($reversed_timezone_offset,strtotime($DateTime)));
			//$out = date('Y-m-d H:i:s',strtotime($reversed_timezone_offset,strtotime($DateTime)));
			//echo "<br>Converted UTC Time: ".$out;
			//exit();
		
			//echo "</br>";
			return $out;
		}
		
		function convertSystemToUser($DateTime, $user_date_time_format = Null, $timezone_offset = Null)
		{
			if(is_null($user_date_time_format))
				$user_date_time_format = $_SESSION['user_date_time_format'];
			if(is_null($timezone_offset))
				$timezone_offset = $_SESSION['user_timezone_offset'];
		
			//$current = gmdate('Y-m-d H:i:s');
			$out = date($user_date_time_format,strtotime($timezone_offset,strtotime($DateTime)));
			//echo $out;
			//exit();
			return $out;
		}
	}
?>