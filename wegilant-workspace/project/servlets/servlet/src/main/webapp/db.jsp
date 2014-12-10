<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    import="java.sql.*;" pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>DB</title>
</head>
<body>
<%
	try {
	//try to create a connection with mysql - minor problems in configuring Eclipse plugin	
	Connection conn=Class.forName("com.mysql.jdbc.Driver");
	//db_url is some dummy_db
	//username is wegdummy
	//password is wegdummy123
	//these can be changed to the cloud credentials when deployed
	conn.getConnection("dummy_db","wegdummy","wegdummy123");
	}
	catch(SQLException ex) {
		out.println("Oops! Something went wrong while connecting to the database!");
	}
%>
</body>
</html>