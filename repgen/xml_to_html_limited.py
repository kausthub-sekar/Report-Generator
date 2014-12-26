from __future__ import print_function
import MySQLdb as dblib
import sys, xml.etree.ElementTree as ET
import re

db = dblib.connect("cloudmysql.cnjwrz2mq8zc.ap-southeast-1.rds.amazonaws.com", "wegcloud", "wegcloud_123", "weg_appvigil")
c = db.cursor()

perc = 0.25

htmfile=open(sys.argv[2],'w')
tree = ET.parse(sys.argv[1])
root = tree.getroot()

audit_id = sys.argv[3]
BlackListArray = ["com.google","com.facebook", "android.support","com.apache","org.apache","com.paypal","com.flurry","com.inmobi","com.amazonaws","com.android","com.fasterxml"]
BugArray = [["Bug Type", "Number of Bugs"], ["Method returning array may expose internal representation", 0], ["Mutable static field", 0], ["Storing reference to mutable object", 0]]
PriorityArray = [["Priority", "Number of Bugs"], ["Priority 1", 0], ["Priority 2", 0], ["Priority 3", 0]]
BarArray = [["Class Name", "Priority 1", "Priority 2", "Priority 3"]]

####Getting leftover data from xml
total_classes = 0
num_packages = 0
timestamp = 0
for childy in root:
	if (childy.tag == "FindBugsSummary"):
		total_classes = childy.attrib['total_classes']
		num_packages = childy.attrib['num_packages']
		timestamp = childy.attrib['timestamp']
	else:
		pass

header_string = '''
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Ixtendo">
	<link href="style.css" type="text/css" rel="stylesheet">
	<link rel="shortcut icon" href="./images/favicon.png">
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<title>Appvigil Report</title>

	<!-- begin JS -->
	<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="js/easing.js" type="text/javascript"></script>
	<script src="js/modernizr.custom.js" type="text/javascript"></script>
	<!--[if (gte IE 6)&(lte IE 8)]>
	<script type="text/javascript" src="js/selectivizr-min.js"></script>
	<![endif]-->
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript"></script>
	<script src="js/jquery.ui.totop.min.js" type="text/javascript"></script>
	<script src="js/tinynav.min.js" type="text/javascript"></script>
	<script src="js/custom.js" type="text/javascript"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<!-- end JS -->
	<style>
		.up{
		}
		.p1{
			background-color: #FC0B0B;
		}
		.p2{
			background-color: #FF8A00;
		}
		.p3{
			background-color: #9FC569;
		}
		.bugx{
			
		}
	</style>

</head>

<body id="top" data-spy="scroll" data-target="#nav-wrap" data-offset="10">

<div id="container">
<!-- begin header -->
<header id="header">
	<h1>Android Security Audit Report by Appvigil</h1>
	<div class="meta">
		<strong>Created on:</strong>'''+str(timestamp)+''' &bull;
		<strong>By:</strong> Wegilant, an IIT Bombay incubated company &bull;
		<strong>Email:</strong> <a href="mailto:info@wegilant.com">info@wegilant.com</a>
	</div>
</header>
<!-- end header -->

<!-- begin navigation -->
<nav id="nav-wrap">
	<ul class="nav nav-list bs-docs-sidenav">
		<li class="active"><a href="#welcome"><i class="icon-chevron-right"></i> Welcome</a></li>
		<li><a href="#AuditTable"><i class="icon-chevron-right"></i> Audit Table</a></li>
		<li><a href="#AuditSummary"><i class="icon-chevron-right"></i> Audit Summary</a></li>
		<li><a href="#MCode"><i class="icon-chevron-right"></i> Malicious Code</a></li>
		<li><a href="#about-us"><i class="icon-chevron-right"></i> About Us</a></li>
	</ul>
</nav>
<!-- end navigation -->
<!-- begin content -->
<section id="content">
<!-- begin welcome -->
<section id="welcome">
	<h2>Welcome</h2>
	<p>Thank you for scanning your Android application using the Appvigil Android App Security Auditor. If you have any queries, feedback or suggestions please feel free to contact us.</p>
</section>
<!-- end welcome -->
<!--Audit Table-->
<section id='AuditTable'>
<h2>Audit Table</h2>
<br>
<table class = "table table-bordered">
'''

####Getting data from sql
quer1 = '''SELECT * FROM `audit_details` WHERE `audit_id` = '''+str(audit_id)
c.execute(quer1)
z = c.fetchall()
appname = z[0][3]
tandd = z[0][2]
appsize = z[0][4]
user_id = z[0][1]
quer2 = '''SELECT name FROM `users` WHERE `user_id` = '''+str(user_id)
c.execute(quer2)
z1 = c.fetchall()
auditor = z1[0][0]

print(header_string, file=htmfile)
print("<tr><td>Audit ID</td><td>"+str(audit_id)+"</td></tr>", file=htmfile)
print("<tr><td>App Name</td><td>"+str(appname)+"</td></tr>", file=htmfile)
print("<tr><td>Audit Time and Date</td><td>"+str(timestamp)+"</td></tr>", file=htmfile)
print("<tr><td>App Size</td><td>"+str(appsize)+" MB</td></tr>", file=htmfile)
print("<tr><td>Audited By</td><td>"+str(auditor.capitalize())+"</td></tr>", file=htmfile)
print("<tr><td>Classes Audited</td><td>"+str(total_classes)+"</td></tr>", file=htmfile)
print("<tr><td>Packages Audited</td><td>"+str(num_packages)+"</td></tr>", file=htmfile)

header2 = '''
</table>
</section>
<!-- Malicious Code -->
<section id='AuditSummary'>
<h2>Audit Summary</h2>
<br>
<table class="table table-bordered">
	<tr class="up">
		<td> Package </td>
		<td> Code Size </td>
		<td> Bugs </td>
		<td> Bugs P1 </td>
		<td> Bugs P2</td>
		<td> Bugs P3</td>
	</tr>
'''
print(header2, file=htmfile)

#This is the section for the audit summary
for child1 in root:
	for child in child1:
		if (child.tag == 'PackageStats'): 
			if (int(child.attrib['total_bugs']) > 0):
				print("<tr>", file=htmfile)
				if (any(substring in str(child.attrib['package']) for substring in BlackListArray)):
					pass
				else:
					print("<td class='bugx'>"+str(child.attrib['package'])+"</td>", file=htmfile)
					print("<td class='bugx'>"+str(child.attrib['total_size'])+"</td>", file=htmfile)
					print("<td class='bugx'>"+str(child.attrib['total_bugs'])+"</td>", file=htmfile)
					print("<td class='p1'>", file=htmfile)
					tp1 = 0
					tp2 = 0
					tp3 = 0
					if 'priority_1' in child.attrib:
						print(str(child.attrib['priority_1']), file=htmfile)
						tp1 = int(child.attrib['priority_1'])
					print("</td>", file=htmfile)
					print("<td class='p2'>", file=htmfile)
					if 'priority_2' in child.attrib:
						print(str(child.attrib['priority_2']), file=htmfile)
						tp2 = int(child.attrib['priority_2'])
					print("</td>", file=htmfile)
					print("<td class='p3'>", file=htmfile)
					if 'priority_3' in child.attrib:
						print(str(child.attrib['priority_3']), file=htmfile)
						tp3 = int(child.attrib['priority_3'])
					print("</td>", file=htmfile)
					print("</tr>", file=htmfile)
					temparr = [str(child.attrib['package']), tp1, tp2, tp3]
					BarArray.append(temparr)
					querx = '''INSERT INTO `audit_key_info`(`audit_id`, `package`, `code_size`, `bugsP1`, `bugsP2`, `bugsP3`) VALUES ('''+str(audit_id)+''',' '''+str(child.attrib['package'])+''' ', '''+str(child.attrib['total_size'])+''', '''+str(tp1)+''', '''+str(tp2)+''', '''+str(tp3)+''')'''
					c.execute(querx)

tl = len(BarArray)*60
print('</table><table><tr><td><div id="piechart1" style="width: 550px; height: 500px;"></div></td>', file=htmfile)
print('<td><div id="piechart2" style="width: 550px; height: 500px;"></div></td></tr></table><div id="chart_div" style="width: 100%; height:'+str(tl)+'px;"></div>', file=htmfile)
print('</section>', file=htmfile)

#####SQL Part#####
###bug_info###
for child in root:
	if (child.tag == "BugInstance"):
		if not(any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
			x1 = child.find("Class").attrib['classname']
			if (child.find("Method") != None):
				x2 = child.find("Method").attrib['classname']
			else:
				x2 = None
			if (child.find("Field") != None):
				x3 = child.find("Field").attrib['classname']
			else:
				x3 = None
			# for xchild in root:
			#  		if (xchild.tag == "BugPattern"):
			#  			if (child.attrib['type'] == xchild.attrib['type']):
			#  				bug_detail = xchild.find("Details").text
			# 				bug_detail = re.sub("<p>","",bug_detail)
			# 				bug_detail = re.sub("</p>","",bug_detail)
			# 				bug_detail = re.sub("\n","",bug_detail)
			# 				bug_detail = re.sub(r"\t+"," ",bug_detail)
			# 				bug_detail = re.sub(r"\ +","^",bug_detail)
			# 				bug_detail = re.sub("\^"," ",bug_detail)
			# 				bug_detail = bug_detail.strip(" ")
			# # 				print(bug_detail)
			quer = '''INSERT INTO `audit_bug_info`(`audit_id`, `bug_type`, `bug_priority`, `bug_rank`, `bug_class`, `bug_method`, `bug_field`) VALUES ('''+str(audit_id)+''',' '''+str(child.attrib['type'])+''' ', '''+str(child.attrib['priority'])+''', '''+str(child.attrib['rank'])+''',' '''+str(x1)+''' ',' '''+str(x2)+''' ',' '''+str(x3)+''' ')'''
			c.execute(quer)

db.commit() ##Commenting this line would mean no commit into the database.


mallheader = '''
<!-- Malicious Code -->
<section id='MCode'>
<h2>Malicious Code</h2>
<br>
'''
print(mallheader, file=htmfile)

count = [0,0,0]
for child in root:
	if (child.tag == 'BugInstance' and child.attrib['abbrev'] == "EI"):
		if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
			pass
		else:
			count[0] = count[0] + 1
	elif (child.tag == 'BugInstance' and child.attrib['abbrev'] == "MS"):
		if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
			pass
		else:
			count[1] = count[1] + 1
	elif (child.tag == 'BugInstance' and child.attrib['abbrev'] == "EI2"):
		if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
			pass
		else:
			count[2] = count[2] + 1
	else:
		pass

i = 0
count2 = [1,1,1]
for x in count:
	if int(x*perc) > 1:
		count2[i] = int(x*perc)
	else:
		pass
	i = i+1
vc = 1
kc = 0
if count[0] > 0:
	print("<div class='accordion' id='mallist'><div class='accordion-group'>", file=htmfile)
	print("<div class='accordion-heading'>", file=htmfile)
	print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist" href="#EI"><b>Method returning array may expose internal representation </b><span class = "badge" style="background-color: #FC0B0B; color: white;">'+str(count[0])+' Vulnerabilities</span></a></div>', file=htmfile)
	print('<div id="EI" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
	print("<div class='accordion' id='mallist1'>", file=htmfile)
	iew = 0
	for child in root:
		if (child.tag == 'BugInstance' and child.attrib['abbrev'] == "EI"):
			if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
				pass
			else:	
				BugArray[1][1] = BugArray[1][1] + 1
				if (child.attrib['priority'] == "1"):
					PriorityArray[1][1] = PriorityArray[1][1] + 1
					colo = "#FC0B0B"
				elif (child.attrib['priority'] == "2"):
					PriorityArray[2][1] = PriorityArray[2][1] + 1
					colo = "#FF8A00"
				elif (child.attrib['priority'] == "3"):
					PriorityArray[3][1] = PriorityArray[3][1] + 1
					colo = "#9FC569"
				else:
					pass
				print("<div class='accordion-group'>", file=htmfile)
				print("<div class='accordion-heading'>", file=htmfile)
				for child1 in child:
					if (child1.tag == 'Class'):
						if (iew < count2[0]):
							print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist1" href="#high'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:'+str(colo)+';"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("</b></a></div>", file=htmfile)
						else:
							print('<a class="accordion-toggle collapsed" style="color:gray;" data-toggle="collapse" data-parent="#mallist1" href="#high'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:gray;"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print(" [LIMITED]</b></a></div>", file=htmfile)
						print('<div id="high'+str(iew)+'" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
				for child1 in child:
					if (child1.tag == 'ShortMessage'):
						print("<b>", file=htmfile)
						print(child1.text, file=htmfile)
						print("</b><br>", file=htmfile)
					if (child1.tag == 'Class'):
						print('<b>Priority: </b>', file=htmfile)
						print(child.attrib['priority'], file=htmfile)
						print('<br>', file=htmfile)
						print('<b>Rank: </b>', file=htmfile)
						print(child.attrib['rank'], file=htmfile)
						print("<hr>", file=htmfile)
						if ((iew < count2[0]) and (vc == 1)):	
							print('In Class', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("<br>", file=htmfile)
					if ((iew < count2[0]) and (vc == 1)):
						# print('<b>Type: </b>', file=htmfile)
						# print(child.attrib['type'], file=htmfile)
						# print('<br>', file=htmfile)
						if (child1.tag == 'Method'):
							print(child1.find('Message').text, file=htmfile)
							print('<br>', file=htmfile)
							# print('<b>Method: </b>', file=htmfile)
							# print(child1.attrib['classname'], file=htmfile)
							# print('<br>', file=htmfile)
						if (child1.tag == 'Field'):
							print('In field ', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print('<br>', file=htmfile)
							bx = child1.find('SourceLine').find('Message').text
							bx = re.sub("<","",bx)
							bx = re.sub(">","",bx)
							print(bx, file=htmfile)
							print('<hr>', file=htmfile)
						if (child1.tag == 'LocalVariable'):
							print(child1.find('Message').text, file=htmfile)
						else:
							pass
						if (child.find("LongMessage") != None):
							tv = child.find("LongMessage").text
					elif ((iew >= count2[0]) and (vc == 1)):
						kc = 1
						vc = 0
					else:
						pass
				if (iew < count2[0]):
					print("<hr><b>Exact Problem: </b>", file=htmfile)
					print(tv, file=htmfile)
					print("<br>", file=htmfile)
					print("<hr>", file=htmfile)
					querw = '''SELECT `standard_bug_long_description` FROM `standard_bugs_table` WHERE `standard_bug_type` = \''''+ str(child.attrib['type']) + '''\' '''
					c.execute(querw)
					z = c.fetchall()
					print("<b>Description: </b>", file=htmfile)
					print(z[0][0], file=htmfile)
					# for xchild in root:
					# 	if (xchild.tag == "BugPattern"):
					# 		if (child.attrib['type'] == xchild.attrib['type']):
					# 			print(xchild.find("Details").text, file=htmfile)
				if (kc == 1):
					print("<i>*Detailed description is available for paid version only.</i>", file=htmfile)
					kc = 0
				print('</div></div></div>', file=htmfile)
				iew=iew+1
				kc = 1
	print('</div></div></div></div>', file=htmfile)
else: 
	pass

vc = 1
kc = 0
if count[1] > 0:
	print("<div class='accordion' id='mallist0'><div class='accordion-group'>", file=htmfile)
	print("<div class='accordion-heading'>", file=htmfile)
	print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist0" href="#MS"><b>Mutable static field </b><span class = "badge" style="background-color: #FC0B0B; color: white;">'+str(count[1])+' Vulnerabilities</span></a></div>', file=htmfile)
	print('<div id="MS" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
	print("<div class='accordion' id='mallist10'>", file=htmfile)
	iew = 0
	for child in root:
		if (child.tag == 'BugInstance' and child.attrib['abbrev'] == "MS"):
			if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
				pass
			else:
				BugArray[2][1] = BugArray[2][1] + 1
				if (child.attrib['priority'] == "1"):
					PriorityArray[1][1] = PriorityArray[1][1] + 1
					colo = "#FC0B0B"
				elif (child.attrib['priority'] == "2"):
					PriorityArray[2][1] = PriorityArray[2][1] + 1
					colo = "#FF8A00"
				elif (child.attrib['priority'] == "3"):
					PriorityArray[3][1] = PriorityArray[3][1] + 1
					colo = "#9FC569"
				else:
					pass		
				print("<div class='accordion-group'>", file=htmfile)
				print("<div class='accordion-heading'>", file=htmfile)
				#print(child.attrib['type'], child.attrib['priority'], child.attrib['category'])
				for child1 in child:
					if (child1.tag == 'Class'):
						if (iew < count2[1]):
							print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist10" href="#high0'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:'+str(colo)+';"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("</b></a></div>", file=htmfile)
						else:
							print('<a class="accordion-toggle collapsed" style="color:gray;" data-toggle="collapse" data-parent="#mallist10" href="#high0'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:gray;"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print(" [LIMITED]</b></a></div>", file=htmfile)
						print('<div id="high0'+str(iew)+'" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
				for child1 in child:
					if (child1.tag == 'ShortMessage'):
						print("<b>", file=htmfile)
						print(child1.text, file=htmfile)
						print("</b><br>", file=htmfile)
					if (child1.tag == 'Class'):
						print('<b>Priority: </b>', file=htmfile)
						print(child.attrib['priority'], file=htmfile)
						print('<br>', file=htmfile)
						print('<b>Rank: </b>', file=htmfile)
						print(child.attrib['rank'], file=htmfile)
						print("<hr>", file=htmfile)
						if ((iew < count2[1]) and (vc == 1)):	
							print('In Class', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("<br>", file=htmfile)
					if ((iew < count2[1]) and (vc == 1)):
						# print('<b>Type: </b>', file=htmfile)
						# print(child.attrib['type'], file=htmfile)
						# print('<br>', file=htmfile)
						if (child1.tag == 'Method'):
							print(child1.find('Message').text, file=htmfile)
							print('<br>', file=htmfile)
							# print('<b>Method: </b>', file=htmfile)
							# print(child1.attrib['classname'], file=htmfile)
							# print('<br>', file=htmfile)
						if (child1.tag == 'Field'):
							print('In field ', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print('<br>', file=htmfile)
							bx = child1.find('SourceLine').find('Message').text
							bx = re.sub("<","",bx)
							bx = re.sub(">","",bx)
							print(bx, file=htmfile)
						if (child1.tag == 'LocalVariable'):
							print('<hr>', file=htmfile)
							print(child1.find('Message').text, file=htmfile)
						else:
							pass
						if (child.find("LongMessage") != None):
							tv = child.find("LongMessage").text
					elif ((iew >= count2[1]) and (vc == 1)):
						kc = 1
						vc = 0
					else:
						pass
				if (iew < count2[1]):
					print("<hr><b>Exact Problem: </b>", file=htmfile)
					print(tv, file=htmfile)
					print("<br>", file=htmfile)
					print("<hr>", file=htmfile)
					querw = '''SELECT `standard_bug_long_description` FROM `standard_bugs_table` WHERE `standard_bug_type` = \''''+ str(child.attrib['type']) + '''\' '''
					c.execute(querw)
					z = c.fetchall()
					print("<b>Description: </b>", file=htmfile)
					print(z[0][0], file=htmfile)
					# for xchild in root:
					# 	if (xchild.tag == "BugPattern"):
					# 		if (child.attrib['type'] == xchild.attrib['type']):
					# 			print(xchild.find("Details").text, file=htmfile)
				
				if (kc == 1):
					print("<i>*Detailed description is available for paid version only.</i>", file=htmfile)
					kc = 0
				print('</div></div></div>', file=htmfile)
				iew=iew+1
				kc = 1
	print('</div></div></div></div>', file=htmfile)
else: 
	pass

vc = 1
kc = 0
if count[2] > 0:
	print("<div class='accordion' id='mallist00'><div class='accordion-group'>", file=htmfile)
	print("<div class='accordion-heading'>", file=htmfile)
	print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist00" href="#EI2"><b>Storing reference to mutable object </b><span class = "badge" style="background-color: #FC0B0B; color: white;">'+str(count[2])+' Vulnerabilities</span> </a></div>', file=htmfile)
	print('<div id="EI2" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
	print("<div class='accordion' id='mallist100'>", file=htmfile)
	iew = 0
	for child in root:
		if (child.tag == 'BugInstance' and child.attrib['abbrev'] == "EI2"):
			if (any(substring in child.find("Class").attrib['classname'] for substring in BlackListArray)):
				pass
			else:
				BugArray[3][1] = BugArray[3][1] + 1
				if (child.attrib['priority'] == "1"):
					PriorityArray[1][1] = PriorityArray[1][1] + 1
					colo = "#FC0B0B"
				elif (child.attrib['priority'] == "2"):
					PriorityArray[2][1] = PriorityArray[2][1] + 1
					colo = "#FF8A00"
				elif (child.attrib['priority'] == "3"):
					PriorityArray[3][1] = PriorityArray[3][1] + 1
					colo = "#9FC569"
				else:
					pass
				print("<div class='accordion-group'>", file=htmfile)
				print("<div class='accordion-heading'>", file=htmfile)
				#print(child.attrib['type'], child.attrib['priority'], child.attrib['category'])
				for child1 in child:
					if (child1.tag == 'Class'):
						if (iew < count2[2]):
							print('<a class="accordion-toggle collapsed" style="color: rgb(0, 85, 128);" data-toggle="collapse" data-parent="#mallist100" href="#high00'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:'+str(colo)+';"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("</b></a></div>", file=htmfile)
						else:
							print('<a class="accordion-toggle collapsed" style="color: gray;" data-toggle="collapse" data-parent="#mallist100" href="#high00'+str(iew)+'"><b>', file=htmfile)
							print('<i class="icon-bug" style="color:gray;"></i>', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print(" [LIMITED]</b></a></div>", file=htmfile)
						print('<div id="high00'+str(iew)+'" class="accordion-body collapse" style="height: 0px;"> <div class="accordion-inner">', file=htmfile)
				for child1 in child:
					if (child1.tag == 'ShortMessage'):
						print("<b>", file=htmfile)
						print(child1.text, file=htmfile)
						print("</b><br>", file=htmfile)
					if (child1.tag == 'Class'):
						print('<b>Priority: </b>', file=htmfile)
						print(child.attrib['priority'], file=htmfile)
						print('<br>', file=htmfile)
						print('<b>Rank: </b>', file=htmfile)
						print(child.attrib['rank'], file=htmfile)
						print("<hr>", file=htmfile)
						if ((iew < count2[2]) and (vc == 1)):	
							print('In Class', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print("<br>", file=htmfile)
					if ((iew < count2[2]) and (vc == 1)):
						# print('<b>Type: </b>', file=htmfile)
						# print(child.attrib['type'], file=htmfile)
						# print('<br>', file=htmfile)
						if (child1.tag == 'Method'):
							print(child1.find('Message').text, file=htmfile)
							print('<br>', file=htmfile)
							# print('<b>Method: </b>', file=htmfile)
							# print(child1.attrib['classname'], file=htmfile)
							# print('<br>', file=htmfile)
						if (child1.tag == 'Field'):
							print('In field ', file=htmfile)
							print(child1.attrib['classname'], file=htmfile)
							print('<br>', file=htmfile)
							bx = child1.find('SourceLine').find('Message').text
							bx = re.sub("<","",bx)
							bx = re.sub(">","",bx)
							print(bx, file=htmfile)
						if (child1.tag == 'LocalVariable'):
							print('<hr>', file=htmfile)
							print(child1.find('Message').text, file=htmfile)
						else:
							pass
						if (child.find("LongMessage") != None):
							tv = child.find("LongMessage").text
					elif ((iew >= count2[2]) and (vc == 1)):
						kc = 1
						vc = 0
					else:
						pass
				if (iew < count2[2]):
					print("<hr><b>Exact Problem: </b>", file=htmfile)
					print(tv, file=htmfile)
					print("<br>", file=htmfile)
					print("<hr>", file=htmfile)
					querw = '''SELECT `standard_bug_long_description` FROM `standard_bugs_table` WHERE `standard_bug_type` = \''''+ str(child.attrib['type']) + '''\' '''
					c.execute(querw)
					z = c.fetchall()
					print("<b>Description: </b>", file=htmfile)
					print(z[0][0], file=htmfile)
					# for xchild in root:
					# 	if (xchild.tag == "BugPattern"):
					# 		if (child.attrib['type'] == xchild.attrib['type']):
					# 			print(xchild.find("Details").text, file=htmfile)
				
				if (kc == 1):
					print("<i>*Detailed description is available for paid version only.</i>", file=htmfile)
					kc = 0
				print('</div></div></div>', file=htmfile)
				iew=iew+1
				kc = 1
	print('</div></div></div></div>', file=htmfile)
else:
	pass

chartscript1 = '''
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable('''+str(BugArray)+''');
        var options = {
          title: 'Bugs by Type',
          chartArea:{right:25,top:50,width:"100%",height:"100%"}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
        chart.draw(data, options);
      }
    </script>
'''

chartscript2 = '''
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable('''+str(PriorityArray)+''');
        var options = {
          title: 'Bugs by Priority',
          chartArea:{left:25,top:50,width:"100%",height:"100%"}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart.draw(data, options);
      }
    </script>
'''

chartscript3 = '''
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable('''+str(BarArray)+''');

        var options = {
          title: 'Bugs in Classes by Priority',
          vAxis: {title: 'Class Names',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
'''

print(chartscript1, file=htmfile)
print(chartscript2, file=htmfile)
print(chartscript3, file=htmfile)

print('</section>', file=htmfile)

ender = '''
<!-- begin PSD Files -->
<section id="about-us">

    <h2>About Us</h2>
<br><p><img src = "images/logo.png" style="float: left; padding: 25px;">Wegilant, an IIT Bombay incubated company is at the heart of Web based IT Security. Led by IITian, Mr. Toshendra Sharma [<a href = "https://twitter.com/toshendrasharma" target = "_blank">Twitter</a>, <a href = "http://in.linkedin.com/in/toshendra/" target = "_blank">LinkedIn</a>], our team of IT security specialists offers SaaS based IT security products, consulting and implementation to small, mid and large sizes businesses as well as individuals. <br><br>Our products have been built on a private Android security cloud, which ensures highest level of data & information security over the web.</p>
<br>
<p>
<b><a href = "http://www.appvigil.com" target = "_blank">Appvigil</a></b>: Cloud based Android security audit & penetration testing tool.
<br></p>

<p>To know more about us visit <a href = "http://www.wegilant.com" target = "_blank">www.wegilant.com</a>.</p>

<p>To learn about IT security visit <a href = "http://www.wegversity.com" target = "_blank">www.wegversity.com</a>.</p>

<p>If you need help with patching security loopholes discovered by Appvigil, contact us at +91 8879390101 or mail us at <a href = 'mailto:info@wegilant.com'>info@wegilant.com</a></p>
</section>
<!-- end PSD Files -->
</section>

<!-- begin footer -->
<footer id="footer">
&copy; 2014 Wegilant
</footer>
<!-- end footer -->
</div>
</body></html>
'''
print(ender,file=htmfile)
