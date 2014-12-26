<?php
	use Monolog\Handler\error_log;
	use DateTime;
	class UserDateTimeFormatter {
		function getUserCurrentDate($user_date_format = Null,  $timezone_offset = Null)
		{
			if(is_null($user_date_format))
				$user_date_format = $_SESSION['user_date_format'];
			if(is_null($timezone_offset))
				$timezone_offset = $_SESSION['user_timezone_offset'];
		
			$current = gmdate('Y-m-d H:i:s');
			$out = date($user_date_format,strtotime("$current $timezone_offset"));
			return $out;
		}
		
		function getUserCurrentTime($user_time_format = Null, $timezone_offset = Null)
		{
			if(is_null($user_time_format))
				$user_time_format = $_SESSION['user_time_format'];
			if(is_null($timezone_offset))
				$timezone_offset = $_SESSION['user_timezone_offset'];
		
			$current = gmdate('Y-m-d H:i:s');
			$out = date($user_time_format,strtotime("$current $timezone_offset"));
			return $out;
		}
		
		function getUserCurrentDateTime($user_date_time_format = Null, $timezone_offset = Null)
		{
			if(is_null($user_date_time_format))
				$user_date_time_format = $_SESSION['user_date_time_format'];
			if(is_null($timezone_offset))
				$timezone_offset = $_SESSION['user_timezone_offset'];
		
			$current = gmdate('Y-m-d H:i:s');
			$out = date($user_date_time_format,strtotime("$current $timezone_offset"));
			//echo $out;
			//exit();
			return $out;
		}
		//additional method for custom number format, not placed in a separate class
		function custom_number_format($n, $precision = 0) {
			if ($n < 1000) {
				// Anything less than a million
				$n_format = number_format($n);
			} else if ($n < 1000000) {
				// Anything less than a million
				$n_format = number_format($n / 1000, $precision) . 'K';
			} else if ($n < 1000000000) {
				// Anything less than a billion
				$n_format = number_format($n / 1000000, $precision) . 'M';
			} else {
				// At least a billion
				$n_format = number_format($n / 1000000000, $precision) . 'B';
			}
		
			return $n_format;
		}
	}