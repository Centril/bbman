<?php
/*--------------------------------------------
|	file = install.php
|	description : The installer
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start();
if(!isset($_POST['step'])) // first step, make your database choises
{
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 1</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td style="width:100%; height:300px;">
	<table rules="all" style="width:100%; height:300px;">
	<tr>
		<td>Database Type</td>
		<td><select name="dbtype"/>
		<option value="mysql" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">MySQL 3.x - 4.0.x</option>
		<option value="mysqli" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">MySQL 4.1+</option>
		<option value="postgre_sql" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">PostgreSQL 8.x</option>
		<option value="mssql" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">MS SQL Server 200x</option>
		<option value="ibase" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">Interbase 6+/Firebird 1+</option>
		<option value="maxdb" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">MaxDB</option>
		<option value="sybase" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">Sybase ASE</option>
		<option value="frontbase" onclick="document.forms[0].host.value = 'your host';document.forms[0].dbname.value = 'your database name';">FrontBase 4.x</option>
		</select></td>
	</tr>
	<tr><td>Hostname</td><td><input name="host" value="" size="55"/></td></tr>
	<tr><td>Username</td><td><input name="username" value="your username" size="55"/></td></tr>
	<tr><td>Password</td><td><input name="password" value="your password" size="55"/></td></tr>
	<tr><td>Database</td><td><input name="dbname" value="" size="55"/></td></tr>
	<tr><td>Table prefix</td><td><input name="tbl_prefix" value="the prefix you want for your table name" size="55"/></td></tr>
	</table>
</td></tr>
<tr style="height:40px; "><td><input type="submit" value=" Submit "/><input type="hidden" name="step" value="2"/></td></tr>
</table></form></div></body></html>
	<?php
}
elseif(isset($_POST['step']) && $_POST['step'] === '2') // check if we can connect to the database
{
	require('db/'.$_POST['dbtype'].'.php');
	$_SESSION = $_POST; // save everything that is in post, in sessions
	extract($_SESSION);
	$DB = new DB($host,$username,$password,$dbname); // fill in the database values
	$conntest = $DB->connect('return');
	if($conntest === 0)
	{
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 5</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td>Can't make a connection to the database, make sure that the values you filled were correct<br/><br/><input type="button" value=" Go Back " onclick="history.back();"/></td></tr></table></form></div></body></html>
		<?php
		die;
	}
	elseif($conntest === -1)
	{
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 5</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td>System error : PHP database extention dose not exist<br/><br/><input type="button" value=" Go Back " onclick="history.back();"/></td></tr></table></form></div></body></html>
		<?php
		die;
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 2</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td><input type="submit" value=" Go on "/><input type="hidden" name="step" value="3"/></td></tr></table></form></div></body></html>
	<?php
}
elseif(isset($_POST['step']) && $_POST['step'] === '3') // table creation
{
	extract($_SESSION);
	require('db/'.$dbtype.'.php');
	$DB = new DB($host,$username,$password,$dbname); // fill in the database values
	$DB->connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 2</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
	<?php
	echo'<tr><td>Attempting to Create all tables</td></tr>';
	echo'<tr><td>Creating table : main</td></tr>';
	// the main table
	$DB->query('CREATE TABLE '.$tbl_prefix.'main (
	Name VARCHAR(30) NOT NULL DEFAULT "undefined",
	Lvl INTEGER(10) NOT NULL DEFAULT "0",
	Profession VARCHAR(10) NOT NULL DEFAULT "undefined",
	Reason VARCHAR(30) NOT NULL DEFAULT "undefined",
	Online CHAR(3) NOT NULL DEFAULT "no",
	id '.$DB->lang('primkey dt int','11').' '.$DB->lang('primkey const').',
	Judgement VARCHAR(20) NOT NULL DEFAULT "undefined",
	'.$DB->lang('primkey',$tbl_prefix.'main','id').');'.
	$DB->lang('spec prim',$tbl_prefix.'main','id'),'install-table-main');

	echo'<tr><td>Creating table : settings</td></tr>';
	// settings table
	$DB->query('CREATE TABLE '.$tbl_prefix.'settings (
	name VARCHAR(100) NOT NULL DEFAULT "undefined",
	setting VARCHAR(100) NOT NULL DEFAULT "undefined",
	'.$DB->lang('primkey',$tbl_prefix.'settings','name').')','install-table-settings');

	echo'<tr><td>Creating table : styles</td></tr>';
	// styles table
	$DB->query('CREATE TABLE '.$tbl_prefix.'styles (
	style VARCHAR(20) NOT NULL DEFAULT "undefined",
	id '.$DB->lang('primkey dt int','11').' '.$DB->lang('primkey const').',
	tpl '.$DB->lang('text').' NOT NULL,
	'.$DB->lang('primkey',$tbl_prefix.'styles','id').');'.
	$DB->lang('spec prim',$tbl_prefix.'styles','id'),'install-table-styles');

	echo'<tr><td>Creating table : users</td></tr>';
	// users table
	$DB->query('CREATE TABLE '.$tbl_prefix.'users (
	username VARCHAR(30) NOT NULL DEFAULT "undefined",
	password VARCHAR(30) NOT NULL DEFAULT "*********",
	level VARCHAR(15) NOT NULL DEFAULT "manager")','install-table-users');

	echo'<tr><td>Creating table : easyfind</td></tr>';
	// easyfind table (module)
	$DB->query('CREATE TABLE '.$tbl_prefix.'easyfind (
	field VARCHAR(100) NOT NULL DEFAULT "error",
	vals VARCHAR(100) NOT NULL DEFAULT "no value",
	'.$DB->lang('primkey',$tbl_prefix.'easyfind','field').')','install-table-easyfind');

	echo'<tr><td>Creating table : log</td></tr>';
	// log table
	$DB->query('CREATE TABLE '.$tbl_prefix.'log (
	day VARCHAR(100) NOT NULL DEFAULT "1 jan 1970",
	time VARCHAR(100) NOT NULL DEFAULT "00:00:00",
	uname VARCHAR(100) NOT NULL DEFAULT "noone",
	id '.$DB->lang('primkey dt int','11').' '.$DB->lang('primkey const').',
	action VARCHAR(100) NOT NULL DEFAULT "did nothing",
	'.$DB->lang('primkey',$tbl_prefix.'log','id').');'.
	$DB->lang('spec prim',$tbl_prefix.'log','id'),'install-table-log');

	echo'<tr><td>Creating table : online</td></tr>';
	// online table (module)
	$DB->query('CREATE TABLE '.$tbl_prefix.'online (
	delid INTEGER(1) NOT NULL DEFAULT "0",
	name VARCHAR(100) NOT NULL DEFAULT "undefined",
	lvl INTEGER(11) NOT NULL DEFAULT "1")','install-table-online');

	echo'<tr><td>Creating table : guildtaxes</td></tr>';
	// guildtaxes table (module)
	$DB->query('CREATE TABLE '.$tbl_prefix.'guildtaxes (
	remains INTEGER(255) NOT NULL DEFAULT "0",
	inactive VARCHAR(5) NOT NULL DEFAULT "no",
	uname VARCHAR(100) NOT NULL DEFAULT "noone")','install-table-guildtaxes');

	echo'<tr><td><input type="submit" value=" Go on "/><input type="hidden" name="step" value="4"/></td></tr></table></form></div></body></html>';
}
elseif(isset($_POST['step']) && $_POST['step'] === '4') // populating table with deault values
{
	extract($_SESSION);
	require('db/'.$dbtype.'.php');
	// connect to the database
	$DB = new DB($host,$username,$password,$dbname);
	$DB->connect();
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 3</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:150px; margin-bottom:-10%; border:1px solid #000000;">
	<?php
	echo'<tr><td>Attempting to poppulate the tables with the information that is needed</td></tr>';
	// make one test, bbed player
	$DB->query("INSERT {$tbl_prefix}main (Name,Lvl,Profession,Reason,Judgement,id,Online) VALUES ('testbbedplayer','23','sorcerer','killed a member','we will revenge...',1,'no')",'install-pop-firstplayer');
	// insert all the settings that we need
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('search','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('sort','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('easyfind','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('alfa','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('online','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('online.unixtime','1')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('online.reftime','3')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('online.world','antica')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('glist','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('glist.guild','satori')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('guildtax.tax','500')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('guildtax.taxevery','31')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('guildtax','yes')",'install-pop-settings','yes');
	$DB->query("INSERT {$tbl_prefix}settings (name,setting) VALUES ('guildtax.lasttimeupdate','1')",'install-pop-settings','yes');
	// insert all the default styles
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("header",1,"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\r\n\"http://www.w3.org/TR/html4/loose.dtd\">\r\n<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\r\n<title>Untitled Document</title>\r\n<style type=\"text/css\">\r\n<!--\r\nbody {\r\n	background-color: #990000;\r\n}\r\n-->\r\n</style></head>\r\n")','install-pop-styles1','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("content",7,"<p></p><table rules=\"all\" style=\"border:1px solid;\" cellspacing=\"2\" cellpadding=\"3\" align=\"center\" bgcolor=\"#FFFFE1\">\n<tr><td>Name</td><td>Lvl</td><td>Profession</td><td>Reason</td><td>Judgement</td><td>Online</td></tr>\n_loop_<tr><td>{$field[\'Name\']}</td><td>{$field[\'Lvl\']}</td><td>{$field[\'Profession\']}</td><td>{$field[\'Reason\']}</td><td>{$field[\'Judgement\']}</td><td>{$field[\'Online\']}</td></tr>_loop_</table>")','install-pop-styles2','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("footer",8,\'<p align="center"><a href="http://www.magicasoft.net/?id=18">powered by BBman 2</a></p></body></html>\')','install-pop-styles3','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("search",3,"<div align=\"center\">\r\n<form action=\"\" method=\"get\">\r\n<table width=\"200\" bgcolor=\"#FFFFE1\" style=\"border:1px solid; \">\r\n<tr>\r\n<td><div align=\"center\">Search</div></td>\r\n</tr>\r\n<tr>\r\n<td><table width=\"200\" border=\"0\">\r\n<tr>\r\n<td><div align=\"center\">\r\n<input name=\"by\" type=\"hidden\" value=\"search\">\r\n<input name=\"val\" type=\"text\" value=\"\">\r\n</div></td>\r\n</tr>\r\n<tr>\r\n<td><div align=\"center\">\r\n<select name=\"s_by\">\r\n<option value=\"Name\">name</option>\r\n<option value=\"Profession\">profession</option>\r\n<option value=\"Lvl\">lvl</option>\r\n<option value=\"Reason\">reason</option>\r\n<option value=\"Judgement\">judgement</option>\r\n</select> \r\n</div></td>\r\n</tr>\r\n<tr>\r\n<td><div align=\"center\">\r\n<input type=\"checkbox\" name=\"exact\" value=\"yes\">\r\nexact\r\n<input type=\"checkbox\" name=\"neg\" value=\"yes\">\r\nnegation</div></td>\r\n</tr>\r\n</table></td>\r\n</tr>\r\n<tr>\r\n<td><div align=\"center\">\r\n<input type=\"submit\" value=\"search\">\r\n</div></td>\r\n</tr>\r\n</table>\r\n</form>\r\n</div>")','install-pop-styles4','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("sort",6,"<p></p><div align=\"center\">sort by</div><table border=\"0\" bgcolor=\"#FFFFE1\" style=\"border:1px solid; \" align=\"center\">\n<tr>\n<td>\n_loop_ |<a href=\"$link\">{$name}</a>| _loop_\n</td>\n</tr>\n</table>")','install-pop-styles5','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("alfa",5,"<p></p><table width=\"200\" border=\"0\" bgcolor=\"#FFFFE1\" style=\"border:1px solid; \" align=\"center\">\n<tr>\n	_loop_<td><a href=\"$link\">($char)</a></td>_loop_\n</tr>\n  </table>")','install-pop-styles6','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("easyfind",4,"<p></p><table width=\"200\" border=\"0\" bgcolor=\"#FFFFE1\" style=\"border:1px solid; \" align=\"center\">\n_loop_\n<tr>\n%@loop%\n      <td><div align=\"center\"><a href=\"$link\">{$name}s</a></div></td>\n%@loop%\n</tr>\n_loop_\n</table>")','install-pop-styles7','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("ext_1",2,"<body>")','install-pop-styles8','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("no match",101,\'<div style="text-align:center; margin:auto;">there were no match made</div>\')','install-pop-styles9','yes');
	$DB->query('INSERT '.$tbl_prefix.'styles (style,id,tpl) VALUES ("guildmemberlist",102,\'ssssssssssssssss\r\n\r\n<table rules=\"all\" style=\"border:1px solid;\">\r\n<tr><td>rank</td><td>name</td><td>title</td><td>joined</td><td>online</td></tr>\r\n_ranks_\r\n<tr style=\"background-color:#FFFF00;\"><td>$uorank</td><td></td><td></td><td></td><td></td></tr>\r\n_member_\r\n<tr><td>$rank</td><td>$name</td><td>$title</td><td>$joined</td><td>$online</td></tr>\r\n_member_\r\n_ranks_\r\n</table>\r\nssssssssssssssss\')','install-pop-styles10','yes');
	// insert an easyfind test
	$DB->query('INSERT '.$tbl_prefix.'easyfind (field,vals) VALUES ("Profession","knight,paladin,sorcerer,druid")','install-pop-easyfind');
	echo'<tr><td>Done with populating the tables</td></tr>
	<tr><td><input type="submit" value=" Go on "/><input type="hidden" name="step" value="5"/></td></tr></table></form></div></body></html>';
}
elseif(isset($_POST['step']) && $_POST['step'] === '5') // demand the superadministrator of the site
{
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 4</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122"/></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:120px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td>Please enter the username and password you are going to use</td></tr>
<tr><td><table style="text-align:center; margin:auto;">
<tr><td>username</td><td>password</td><td>confirm password</td></tr>
<tr><td><input type="text" name="u_username"/></td><td><input type="password" name="u_password"/></td><td><input type="password" name="u_passwordcon"/></td></tr>
</table></td></tr>
<tr><td><input type="submit" value=" Go on "/><input type="hidden" name="step" value="6"/></td></tr></table></form></div></body></html>
	<?php
}
elseif(isset($_POST['step']) && $_POST['step'] === '6') // insert the user and print the new contents of file : 'config.php'
{
	if($_POST['u_password'] !== $_POST['u_passwordcon']) // if the passwords are not identical, then terminate program
	{
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 5</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<form action="install.php" method="post">
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td>the two passwords didn't match please enter two that dose.<br/><br/><input type="button" value=" Go Back " onclick="history.back();"/></td></tr></table></form></div></body></html>
		<?php
		die;
	}
	$u_username = (string)$_POST['u_username'];
	$u_password = (string)$_POST['u_password'];
	extract($_SESSION);
	require('db/'.$dbtype.'.php');
	// connect to the database
	$DB = new DB($host,$username,$password,$dbname);
	$DB->connect();
	// insert the user
	$DB->query("INSERT {$tbl_prefix}users (username,password,level) VALUES ('{$u_username}','{$u_password}','admin')",'install-make-adminuser');
	// print out the new 'config.php' file
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Magicasoft BBman 2 Installation - step 5</title></head>
<body style="background-color:#FFFFFF;"><div style="text-align:center;"><img src="bbman2.gif" width="353" height="122" /></div>
<table rules="all" style="color:#FFFFFF; font-weight:600; background-color:#6699FF; text-align:center; margin:auto; width:50%; height:320px; margin-bottom:-10%; border:1px solid #000000;">
<tr><td>Congratulations, a copy of BBman 2 was installed on your database.<br/><br/>
Now you just need to replace the contents of "config.php" with the following:
<pre style="text-align:left; margin-left:80px;">
&lt?php
/*--------------------------------------------
|	file = config.php
|	description : The database configurations
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

$servertype = '<?php echo $dbtype;?>'; // The database type you are using ( MySQL,PostgreSQL...)
$host = '<?php echo $host;?>'; // The hostname or IP address to the database server
$username = '<?php echo $username;?>'; // The username you are using to connect to the database
$password = '<?php echo $password;?>'; // The password for your username
$database = '<?php echo $dbname;?>'; // The name of the database
$table_prefix = '<?php echo $tbl_prefix;?>'; // The prefix that you are using for your tables
?&gt;
</pre>
please remember to delete this file since it can be a security threat.</td></tr></table></div></body></html>
	<?php
}
?>