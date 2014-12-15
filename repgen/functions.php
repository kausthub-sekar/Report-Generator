<?php
function check_input($value)
{
	$value = xss_clean($value);
	// Stripslashes
	//if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	$value = mysql_real_escape_string($value);
	//$value = "'" . mysql_real_escape_string($value) . "'";
	// Quote if not a number
	//if (!is_numeric($value))
	//{
	//  $value = "'" . mysql_real_escape_string($value) . "'";
	//}
	return $value;
}
//test
function assign_rand_value($num)
{
    // accepts 1 - 36
    switch($num)
	{
        case "1"  : $rand_value = "a"; break;
        case "2"  : $rand_value = "b"; break;
        case "3"  : $rand_value = "c"; break;
        case "4"  : $rand_value = "d"; break;
        case "5"  : $rand_value = "e"; break;
        case "6"  : $rand_value = "f"; break;
        case "7"  : $rand_value = "g"; break;
        case "8"  : $rand_value = "h"; break;
        case "9"  : $rand_value = "i"; break;
        case "10" : $rand_value = "j"; break;
        case "11" : $rand_value = "k"; break;
        case "12" : $rand_value = "l"; break;
        case "13" : $rand_value = "m"; break;
        case "14" : $rand_value = "n"; break;
        case "15" : $rand_value = "o"; break;
        case "16" : $rand_value = "p"; break;
        case "17" : $rand_value = "q"; break;
        case "18" : $rand_value = "r"; break;
        case "19" : $rand_value = "s"; break;
        case "20" : $rand_value = "t"; break;
        case "21" : $rand_value = "u"; break;
        case "22" : $rand_value = "v"; break;
        case "23" : $rand_value = "w"; break;
        case "24" : $rand_value = "x"; break;
        case "25" : $rand_value = "y"; break;
        case "26" : $rand_value = "z"; break;
        case "27" : $rand_value = "0"; break;
        case "28" : $rand_value = "1"; break;
        case "29" : $rand_value = "2"; break;
        case "30" : $rand_value = "3"; break;
        case "31" : $rand_value = "4"; break;
        case "32" : $rand_value = "5"; break;
        case "33" : $rand_value = "6"; break;
        case "34" : $rand_value = "7"; break;
        case "35" : $rand_value = "8"; break;
        case "36" : $rand_value = "9"; break;
    }
    return $rand_value;
}

function get_rand_alphanumeric($length)
{
    if ($length>0)
	{
        $rand_id="";
        for ($i=1; $i<=$length; $i++)
		{
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(1,36);
            $rand_id .= assign_rand_value($num);
        }
    }
    return $rand_id;
}

function get_rand_numbers($length)
{
    if ($length>0)
	{
        $rand_id="";
        for($i=1; $i<=$length; $i++)
		{
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(27,36);
            $rand_id .= assign_rand_value($num);
        }
    }
    return $rand_id;
}

function get_rand_letters($length)
{
    if ($length>0)
	{
        $rand_id="";
        for($i=1; $i<=$length; $i++)
		{
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(1,26);
            $rand_id .= assign_rand_value($num);
        }
    }
    return $rand_id;
}


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


function getSystemCurrentDateTime()
{
    return gmdate('Y-m-d H:i:s');
}

function getSystemCurrentDate()
{
    return gmdate('Y-m-d');
}

function getSystemCurrentTime()
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


function printArray($arr){
    echo '<pre>';
   print_r($arr);
    echo '</pre>';
}

function xss_clean($data)
{
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
 
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
 
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
 
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
 
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
 
        do
        {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
 
        // we are done...
        return $data;
}

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

?>