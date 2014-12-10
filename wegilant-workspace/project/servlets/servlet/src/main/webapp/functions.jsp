<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1" import="java.lang.String; java.util.Date; java.text.DateFormat; java.text.SimpleDateFormat;"%>
<%@include file="JMS\Serializer\Handler\ArrayCollectionHandler;Monolog\Handler\error_log;" %>    
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Functions</title>
</head>
<body>
	<%!
		int index,i;
		StringBuilder sb;
		String rand_id="";
		public String check_input(String value)
		{
			//trying to replace stripslashes with a simple string replace method
			//hope here string replace should convert \ to blank and \\ to \
			value=value.replace("\\","");
			//first check if string is null
			if (str == null) 
			{
             	return null;
        	}
			//replace all special characters
         	if (value.replaceAll("[a-zA-Z0-9_!@#$%^&*()-=+~.;:,\\Q[\\E\\Q]\\E<>{}\\/? ]","").length() < 1)
         	{
             	return value;
         	}
         	//mysql_real_escape_string does not exist in java
			//somewhat equivalent code to remove special characters except
			//line feed or carriage return can be tried to be replaced
		 return value; 
		}
		
		public String assign_rand_value(String num)
		{
			String rand_value;
			// accepts 1 - 36
		    switch(num)
			{
		        case "1"  : rand_value = "a"; break;
		        case "2"  : rand_value = "b"; break;
		        case "3"  : rand_value = "c"; break;
		        case "4"  : rand_value = "d"; break;
		        case "5"  : rand_value = "e"; break;
		        case "6"  : rand_value = "f"; break;
		        case "7"  : rand_value = "g"; break;
		        case "8"  : rand_value = "h"; break;
		        case "9"  : rand_value = "i"; break;
		        case "10" : rand_value = "j"; break;
		        case "11" : rand_value = "k"; break;
		        case "12" : rand_value = "l"; break;
		        case "13" : rand_value = "m"; break;
		        case "14" : rand_value = "n"; break;
		        case "15" : rand_value = "o"; break;
		        case "16" : rand_value = "p"; break;
		        case "17" : rand_value = "q"; break;
		        case "18" : rand_value = "r"; break;
		        case "19" : rand_value = "s"; break;
		        case "20" : rand_value = "t"; break;
		        case "21" : rand_value = "u"; break;
		        case "22" : rand_value = "v"; break;
		        case "23" : rand_value = "w"; break;
		        case "24" : rand_value = "x"; break;
		        case "25" : rand_value = "y"; break;
		        case "26" : rand_value = "z"; break;
		        case "27" : rand_value = "0"; break;
		        case "28" : rand_value = "1"; break;
		        case "29" : rand_value = "2"; break;
		        case "30" : rand_value = "3"; break;
		        case "31" : rand_value = "4"; break;
		        case "32" : rand_value = "5"; break;
		        case "33" : rand_value = "6"; break;
		        case "34" : rand_value = "7"; break;
		        case "35" : rand_value = "8"; break;
		        case "36" : rand_value = "9"; break;
		    }
		    return rand_value;
		}
		
		public String get_rand_alphanumeric(int length)
		{
			if(length>0)
			{
				for(i=1;i<=length;i++)
				{
					//call another function to make sure random values are generated
					//within the range of cases 1 to 36
					//excluded the microtime portin of the php based functions
					int num = randomWithinRange(1,36);
					rand_id += assign_rand_value(num.toString());
				}
			}
			return rand_id;
		}
		
		//additional function to generate numbers between 1 and 36
		public int randomWithinRange(int min, int max)
		{
			int range=(max-min)+1;
			return (int)(Math.random()*range)+min;
		}
		
		public String get_rand_numbers(int length)
		{
		    if (length>0)
			{
		        rand_id="";
		        for(i=1; i<=length; i++)
				{		           	
		        	//excluded the microtime portin of the php based functions
		        	//will append only numbers from 0 to 9 to rand_id
		            num = randomWithinRange(27,36);
		            rand_id += assign_rand_value(num.toString());
		        }
		    }
		    return rand_id;
		}
		
		public String get_rand_letters(int length)
		{
		    if (length>0)
			{
		        rand_id="";
		        for(i=1; i<=length; i++)
				{		           	
		        	//excluded the microtime portin of the php based functions
		        	//will append only letters from a to z to rand_id
		            num = randomWithinRange(1,26);
		            rand_id += assign_rand_value(num.toString());
		        }
		    }
		    return rand_id;
		}
		
		public String getUserCurrentDate(String user_date_format = null, String timezone_offset = null)
		{
			String current;
			if(user_date_format==null)
			{
				user_date_format =  request.getParameter('user_date_format');
				session.setAttribute('user_date_format',user_date_format);
			}
				
			if(timezone_offset==null)
			{
				timezone_offset = request.getParameter('user_timezone_offset');
				session.setAttribute('user_timezone_offset', user_timezone_offset);	
			}
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("Y-M-d H-m-s");

			// format the date into the required format
			current = destDf.format(date); 
		    //need to replace each invocation to strtotime with custom made java equivalent
		    out = date(user_date_format,strtotime("current $timezone_offset"));
		    return out;
		}
		
		public String getUserCurrentTime(String user_time_format = null, String timezone_offset = null)
		{
			String current;
			if(user_time_format==null)
			{
				user_date_format =  request.getParameter('user_time_format');
				session.setAttribute('user_time_format',user_time_format);
			}
				
			if(timezone_offset==null)
			{
				timezone_offset = request.getParameter('user_timezone_offset');
				session.setAttribute('user_timezone_offset', user_timezone_offset);	
			}
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("Y-M-d H-m-s");

			// format the date into the required format
			current = destDf.format(date); 
		    
		    out = date(user_date_format,strtotime("current $timezone_offset"));
		    return out;
		}
		
		public String getUserCurrentDateTime(String user_date_time_format = null, String timezone_offset = null)
		{
			String current;
			if(user_date_time_format==null)
			{
				user_date_time_format =  request.getParameter('user_time_format');
				session.setAttribute('user_date_time_format',user_date_time_format);
			}
				
			if(timezone_offset==null)
			{
				timezone_offset = request.getParameter('user_timezone_offset');
				session.setAttribute('user_timezone_offset', user_timezone_offset);	
			}
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("Y-M-d H-m-s");

			// format the date into the required format
			current = destDf.format(date); 
		    
		    out = date(user_date_format,strtotime("current $timezone_offset"));
		    return out;
		}
		
		public String getSystemCurrentDateTime()
		{
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("Y-M-d H-m-s");

			// format the date into the required format
			String sysCurrent = destDf.format(date); 
			return sysCurrent;
		}
		
		public String getSystemCurrentDate()
		{
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("Y-M-d");

			// format the date into the required format
			String sysCurrent = destDf.format(date); 
			return sysCurrent;
		}
		
		public String getSystemCurrentTime()
		{
			DateFormat srcDf = new SimpleDateFormat();
			// parse the date string into Date object
			Date date = srcDf.parse(dateStr);
			
			DateFormat destDf = new SimpleDateFormat("H-m-s");

			// format the date into the required format
			String sysCurrent = destDf.format(date); 
			return sysCurrent;
		}
	%>
</body>
</html>