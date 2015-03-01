<?php
/*--------------------------------------------
|	file = admin/index.php
|	description : Administrator main page
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
// include dependiances
require('../config.php');
require('../db/'.$servertype.'.php');
require('cpfunc.php');
level_check(); // check if access is granted

// connect to the database
$DB = new DB($host,$username,$password,$database);
$DB->connect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>BBman 2 Control Panel</title>
<link href="cpstyles.css" rel="stylesheet" type="text/css">
<script>
function lp_check(yes,no,msg)
{
	var p;
	p = prompt(msg,no);
	if(p == yes)
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>
</head>

<body style="margin-top:6%; color:#000000;">
<div style="width:72%; text-align:center; margin:auto; border:1px solid;">

	<div id="top">BBman Control Panel</div>
	<div id="m-c">
 		<div id="menu">
			<div style="position:relative; top:5px; text-align:left; margin:auto; margin-left:10px;">
			<a href="./">[Characters]</a>
			<p></p>
			<a href="?mode=fields">[Fields]</a>
			<p></p>
			<a href="?mode=easyfind">[Easyfind]</a>
			<p></p>
			<a href="?mode=styles">[Styles]</a>
			<p></p>
			<a href="?mode=users">[Users]</a>
			<p></p>
			<a href="?mode=setts">[Settings]</a>
			<p></p>
			<a href="?mode=log">[User log]</a>
			<p></p>
			<a href="?mode=guildtax">[manage guildtaxes]</a>
			<p></p>
			<a href="?mode=viewguildtax">[view your guildtax]</a>
			<p></p>
			<a href="login.php?action=logout">[Logout]</a>
			<p></p>
			<a href="?mode=faq">[FAQ]</a>
			</div>
		</div>
	</div>
</div>

<div id="content">
<div style="margin-left:5px; margin-right:5px;">
<?php
if(!isset($_GET['mode'])) // include the default page for the administration
{
	include('char.php');
}
else
{
	if(preg_match('/[^a-z]+/',$_GET['mode'])) die('Hacking atempt!'); // make sure that no illegal file is included
	include((string)$_GET['mode'].'.php'); // include the requested file
}
?>
</div>
</div>

</body>
</html>